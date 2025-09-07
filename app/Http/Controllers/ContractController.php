<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractDocument;
use App\Models\ContractTemplate;
use App\Models\User;
use App\Models\Licenciado;
use App\Models\Operacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $query = Contract::with(['licenciado', 'documents', 'approvedBy', 'contractSentBy'])
            ->active();

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('licenciado', function($q) use ($search) {
                $q->where('razao_social', 'like', "%{$search}%")
                  ->orWhere('nome_fantasia', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('cnpj_cpf', 'like', "%{$search}%");
            });
        }

        $contracts = $query->orderBy('created_at', 'desc')->paginate(15);

        $statusStats = [
            'documentos_pendentes' => Contract::active()->byStatus('documentos_pendentes')->count(),
            'documentos_em_analise' => Contract::active()->byStatus('documentos_em_analise')->count(),
            'documentos_aprovados' => Contract::active()->byStatus('documentos_aprovados')->count(),
            'contrato_enviado' => Contract::active()->byStatus('contrato_enviado')->count(),
            'contrato_assinado' => Contract::active()->byStatus('contrato_assinado')->count(),
            'licenciado_liberado' => Contract::active()->byStatus('licenciado_liberado')->count(),
        ];

        return view('dashboard.contracts.index', compact('contracts', 'statusStats'));
    }

    public function show(Contract $contract)
    {
        $contract->load(['licenciado', 'documents.reviewedBy', 'auditLogs.user', 'approvedBy', 'contractSentBy']);
        
        return view('dashboard.contracts.show', compact('contract'));
    }

    public function create()
    {
        // Buscar licenciados da tabela licenciados que não têm contrato
        $licenciados = Licenciado::whereDoesntHave('contract')
            ->whereIn('status', ['aprovado', 'ativo']) // Licenciados aprovados ou ativos
            ->orderBy('razao_social')
            ->get();

        return view('dashboard.contracts.create', compact('licenciados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'licenciado_id' => 'required|exists:licenciados,id',
            'observacoes_admin' => 'nullable|string|max:1000'
        ]);

        // Verificar se o licenciado já tem contrato
        $existingContract = Contract::where('licenciado_table_id', $request->licenciado_id)
            ->active()
            ->first();

        if ($existingContract) {
            return redirect()->back()->withErrors(['licenciado_id' => 'Este licenciado já possui um contrato ativo.']);
        }

        $contract = Contract::create([
            'licenciado_table_id' => $request->licenciado_id,
            'status' => 'documentos_pendentes',
            'observacoes_admin' => $request->observacoes_admin
        ]);

        // Log da criação
        $contract->logAction(
            'contrato_criado',
            'Contrato criado para o licenciado',
            auth()->id(),
            ['licenciado_id' => $request->licenciado_id]
        );

        return redirect()->route('dashboard.contracts.show', $contract)
            ->with('success', 'Contrato criado com sucesso!');
    }

    public function reviewDocuments(Request $request, Contract $contract)
    {
        $request->validate([
            'document_reviews' => 'required|array',
            'document_reviews.*.status' => 'required|in:aprovado,rejeitado',
            'document_reviews.*.notes' => 'nullable|string|max:500'
        ]);

        $contract->update(['status' => 'documentos_em_analise']);

        foreach ($request->document_reviews as $docId => $review) {
            $document = $contract->documents()->find($docId);
            if ($document) {
                if ($review['status'] === 'aprovado') {
                    $document->approve($review['notes'] ?? null);
                } else {
                    $document->reject($review['notes'] ?? null);
                }
            }
        }

        // Verificar se todos os documentos foram aprovados
        if ($contract->hasAllDocumentsApproved()) {
            $contract->update([
                'status' => 'documentos_aprovados',
                'documents_approved_at' => now(),
                'approved_by' => auth()->id()
            ]);

            $contract->logAction(
                'documentos_aprovados',
                'Todos os documentos foram aprovados',
                auth()->id()
            );
        }

        return redirect()->route('dashboard.contracts.show', $contract)
            ->with('success', 'Documentos analisados com sucesso!');
    }

    public function downloadDocument(ContractDocument $document)
    {
        if (!$document->fileExists()) {
            return redirect()->back()->withErrors(['error' => 'Arquivo não encontrado.']);
        }

        return Storage::disk('private')->download($document->file_path, $document->original_name);
    }

    public function generateContract(Contract $contract)
    {
        if (!$contract->canSendContract()) {
            return redirect()->back()->withErrors(['error' => 'Contrato não pode ser enviado neste momento.']);
        }

        try {
            // Verificar campos obrigatórios
            $missingFields = $this->validateRequiredFields($contract);
            if (!empty($missingFields)) {
                return redirect()->back()->withErrors([
                    'error' => 'Campos obrigatórios não preenchidos: ' . implode(', ', $missingFields)
                ]);
            }

            // Obter template padrão
            $template = ContractTemplate::getDefault();
            if (!$template) {
                return redirect()->back()->withErrors(['error' => 'Template de contrato não encontrado.']);
            }

            // Preparar dados para o contrato
            $contractData = $this->prepareContractData($contract);

            // Gerar hash único
            $contractHash = hash('sha256', $contract->id . time() . Str::random(32));

            // Gerar PDF
            $pdf = $this->generatePDF($template, $contractData);
            
            // Salvar PDF
            $fileName = "contrato_{$contract->id}_{$contractHash}.pdf";
            $filePath = "contracts/{$contract->id}/{$fileName}";
            
            Storage::disk('private')->put($filePath, $pdf->output());

            // Atualizar contrato
            $contract->update([
                'template_id' => $template->id,
                'contract_pdf_path' => $filePath,
                'contract_hash' => $contractHash,
                'contract_data' => $contractData,
                'generated_at' => now(),
                'status' => 'contrato_enviado',
                'contract_sent_at' => now(),
                'contract_sent_by' => auth()->id()
            ]);

            // Gerar token de assinatura
            $signatureToken = $contract->generateSignatureToken();

            // Log da ação
            $contract->logAction(
                'contrato_gerado',
                'Contrato gerado e enviado automaticamente',
                auth()->id(),
                [
                    'file_path' => $filePath,
                    'contract_hash' => $contractHash,
                    'signature_token' => $signatureToken,
                    'template_id' => $template->id
                ]
            );

            // Enviar por email (implementar depois)
            $this->sendContractEmail($contract, $signatureToken);

            return redirect()->route('contracts.show', $contract)
                ->with('success', 'Contrato gerado e enviado com sucesso!');

        } catch (\Exception $e) {
            // Log do erro
            $contract->logAction(
                'erro_geracao',
                'Erro ao gerar contrato: ' . $e->getMessage(),
                auth()->id(),
                ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]
            );

            return redirect()->back()->withErrors(['error' => 'Erro ao gerar contrato: ' . $e->getMessage()]);
        }
    }

    public function previewContract(Contract $contract)
    {
        if (!$contract->canSendContract()) {
            return response()->json(['error' => 'Contrato não pode ser visualizado neste momento.'], 400);
        }

        try {
            // Verificar campos obrigatórios
            $missingFields = $this->validateRequiredFields($contract);
            if (!empty($missingFields)) {
                return response()->json([
                    'error' => 'Campos obrigatórios não preenchidos',
                    'missing_fields' => $missingFields
                ], 400);
            }

            // Obter template padrão
            $template = ContractTemplate::getDefault();
            if (!$template) {
                return response()->json(['error' => 'Template de contrato não encontrado.'], 404);
            }

            // Preparar dados para o contrato
            $contractData = $this->prepareContractData($contract);

            // Gerar PDF de preview
            $pdf = $this->generatePDF($template, $contractData);

            return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="preview_contrato_' . $contract->id . '.pdf"');

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao gerar preview: ' . $e->getMessage()], 500);
        }
    }

    private function validateRequiredFields(Contract $contract): array
    {
        $template = ContractTemplate::getDefault();
        if (!$template) {
            return ['Template não encontrado'];
        }

        $contractData = $this->prepareContractData($contract);
        return $template->validateRequiredFields($contractData);
    }

    private function prepareContractData(Contract $contract): array
    {
        $licenciado = $contract->licenciado;
        $operacao = Operacao::first(); // Pegar operação padrão ou configurar

        return [
            'contratante' => [
                'nome' => $licenciado->razao_social ?? '',
                'cnpj' => $this->formatCNPJ($licenciado->cnpj_cpf ?? ''),
                'cnpj_formatted' => $this->formatCNPJ($licenciado->cnpj_cpf ?? ''),
                'ie' => '', // Campo não existe na tabela licenciados
                'endereco' => $licenciado->endereco ?? '',
                'cidade' => $licenciado->cidade ?? '',
                'uf' => $licenciado->estado ?? '',
                'cep' => $this->formatCEP($licenciado->cep ?? ''),
                'cep_formatted' => $this->formatCEP($licenciado->cep ?? '')
            ],
            'representante' => [
                'nome' => $licenciado->nome_fantasia ?? $licenciado->razao_social ?? '',
                'cpf' => '', // Campo não existe na tabela licenciados
                'cpf_formatted' => '',
                'email' => $licenciado->email ?? '',
                'telefone' => $this->formatPhone($licenciado->telefone ?? ''),
                'telefone_formatted' => $this->formatPhone($licenciado->telefone ?? '')
            ],
            'contratada' => [
                'nome' => $operacao->company_name ?? 'DSPAY TECNOLOGIA LTDA',
                'cnpj' => $this->formatCNPJ($operacao->cnpj ?? '00.000.000/0001-00'),
                'cnpj_formatted' => $this->formatCNPJ($operacao->cnpj ?? '00.000.000/0001-00'),
                'endereco' => $operacao->address ?? 'Endereço da empresa',
                'cidade' => $operacao->city ?? 'São Paulo',
                'uf' => $operacao->state ?? 'SP',
                'cep' => $this->formatCEP($operacao->zipcode ?? '00000-000'),
                'cep_formatted' => $this->formatCEP($operacao->zipcode ?? '00000-000')
            ],
            'contrato' => [
                'data' => Carbon::now()->locale('pt_BR')->translatedFormat('j \d\e F \d\e Y'),
                'data_formatted' => Carbon::now()->locale('pt_BR')->translatedFormat('j \d\e F \d\e Y'),
                'id' => str_pad($contract->id, 6, '0', STR_PAD_LEFT),
                'id_formatted' => 'CONT-' . str_pad($contract->id, 6, '0', STR_PAD_LEFT),
                'hash' => $contract->contract_hash ?? 'PREVIEW'
            ],
            'licensee' => $licenciado,
            'operation' => $operacao,
            'contract' => $contract
        ];
    }

    private function generatePDF(ContractTemplate $template, array $data)
    {
        // Renderizar template
        $html = view('contracts.templates.default', $data)->render();

        // Gerar PDF
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'isRemoteEnabled' => false,
            'isHtml5ParserEnabled' => true
        ]);

        return $pdf;
    }

    private function sendContractEmail(Contract $contract, string $signatureToken)
    {
        $signatureUrl = route('contracts.sign', $signatureToken);
        
        // Log temporário - implementar envio real depois
        logger("Contrato #{$contract->id} enviado para: {$contract->licenciado->email}");
        logger("URL de assinatura: {$signatureUrl}");
        
        // Aqui você implementaria o envio real do email
        // Mail::to($contract->licenciado->email)->send(new ContractEmail($contract, $signatureUrl));
    }

    private function formatCNPJ(string $cnpj): string
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);
        if (strlen($cnpj) === 14) {
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj);
        }
        return $cnpj;
    }

    private function formatCPF(string $cpf): string
    {
        $cpf = preg_replace('/\D/', '', $cpf);
        if (strlen($cpf) === 11) {
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
        }
        return $cpf;
    }

    private function formatCEP(string $cep): string
    {
        $cep = preg_replace('/\D/', '', $cep);
        if (strlen($cep) === 8) {
            return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $cep);
        }
        return $cep;
    }

    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (strlen($phone) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $phone);
        } elseif (strlen($phone) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $phone);
        }
        return $phone;
    }

    public function downloadContract(Contract $contract)
    {
        $filePath = $contract->signed_contract_path ?? $contract->contract_pdf_path;
        
        if (!$filePath || !Storage::disk('private')->exists($filePath)) {
            return redirect()->back()->withErrors(['error' => 'Arquivo não encontrado.']);
        }

        $fileName = $contract->signed_contract_path ? 'contrato_assinado.pdf' : 'contrato.pdf';
        
        return Storage::disk('private')->download($filePath, $fileName);
    }
}
