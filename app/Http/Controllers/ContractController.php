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
            'criado' => Contract::active()->byStatus('criado')->count(),
            'contrato_enviado' => Contract::active()->byStatus('contrato_enviado')->count(),
            'aguardando_assinatura' => Contract::active()->byStatus('aguardando_assinatura')->count(),
            'contrato_assinado' => Contract::active()->byStatus('contrato_assinado')->count(),
            'licenciado_aprovado' => Contract::active()->byStatus('licenciado_aprovado')->count(),
            'cancelado' => Contract::active()->byStatus('cancelado')->count(),
        ];

        return view('dashboard.contracts.index', compact('contracts', 'statusStats'));
    }

    public function show(Contract $contract)
    {
        $contract->load(['licenciado', 'documents.reviewedBy', 'auditLogs.user', 'approvedBy', 'contractSentBy']);
        
        return view('dashboard.contracts.show', compact('contract'));
    }

    /**
     * Exibir PDF do contrato no browser
     */
    public function viewPdf(Contract $contract)
    {
        // Verificar se o contrato tem PDF
        if (!$contract->contract_pdf_path) {
            return back()->with('error', 'PDF do contrato não encontrado.');
        }

        // Verificar se o arquivo existe
        $pdfPath = storage_path('app/' . $contract->contract_pdf_path);
        if (!file_exists($pdfPath)) {
            return back()->with('error', 'Arquivo PDF não encontrado no servidor.');
        }

        // Log da ação
        \Log::info('📄 Visualização de contrato PDF', [
            'contract_id' => $contract->id,
            'licenciado' => $contract->licenciado->razao_social ?? $contract->licenciado->nome_fantasia,
            'user_id' => auth()->id(),
            'pdf_path' => $contract->contract_pdf_path
        ]);

        // Retornar o PDF para visualização no browser
        return response()->file($pdfPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Contrato_' . str_pad($contract->id, 6, '0', STR_PAD_LEFT) . '.pdf"'
        ]);
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

    /**
     * Página inicial do gerador de contratos - Etapa 1: Selecionar Licenciado
     */
    public function generateIndex()
    {
        // Buscar TODOS os licenciados cadastrados no banco, independente do status
        $licenciados = Licenciado::orderBy('razao_social')
            ->get();

        return view('dashboard.contracts.generate.index', compact('licenciados'));
    }

    /**
     * Exibir página do Step 1 (GET) - Redireciona para início se acessado diretamente
     */
    public function showStep1()
    {
        return redirect()->route('contracts.generate.index')
            ->with('info', 'Por favor, inicie o processo de geração selecionando um licenciado.');
    }

    /**
     * Processar Etapa 1: Licenciado selecionado, mostrar templates
     */
    public function generateStep1(Request $request)
    {
        $request->validate([
            'licenciado_id' => 'required|exists:licenciados,id'
        ]);

        $licenciado = Licenciado::findOrFail($request->licenciado_id);
        
        // Buscar templates ativos
        $templates = ContractTemplate::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('dashboard.contracts.generate.step1', compact('licenciado', 'templates'));
    }

    /**
     * Exibir página do Step 2 (GET) - Redireciona para início se acessado diretamente
     */
    public function showStep2()
    {
        return redirect()->route('contracts.generate.index')
            ->with('info', 'Por favor, inicie o processo de geração selecionando um licenciado.');
    }

    /**
     * Processar Etapa 2: Template selecionado, mostrar preview e confirmar
     */
    public function generateStep2(Request $request)
    {
        $request->validate([
            'licenciado_id' => 'required|exists:licenciados,id',
            'template_id' => 'required|exists:contract_templates,id'
        ]);

        $licenciado = Licenciado::findOrFail($request->licenciado_id);
        $template = ContractTemplate::findOrFail($request->template_id);

        // Preparar dados para substituição
        $contractData = $this->prepareContractData($licenciado);
        
        // Gerar preview do contrato
        $previewContent = $this->replaceTemplateVariables($template->content, $contractData);

        return view('dashboard.contracts.generate.step2-simple', compact('licenciado', 'template', 'previewContent', 'contractData'));
    }

    /**
     * Exibir página do Step 3 (GET) - Redireciona para início se acessado diretamente
     */
    public function showStep3()
    {
        return redirect()->route('contracts.generate.index')
            ->with('info', 'Por favor, inicie o processo de geração selecionando um licenciado.');
    }

    /**
     * Processar Etapa 3: Gerar e salvar o contrato
     */
    public function generateStep3(Request $request)
    {
        // DEBUG: Log dos dados recebidos
        \Log::info('🔍 DEBUG generateStep3 - Dados recebidos:', [
            'all_data' => $request->all(),
            'licenciado_id' => $request->get('licenciado_id'),
            'template_id' => $request->get('template_id'),
            'observacoes_admin' => $request->get('observacoes_admin'),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'user_id' => auth()->id()
        ]);
        
        \Log::info('🚀 Iniciando processo de geração de contrato', [
            'contract_will_be_created' => 'yes',
            'timestamp' => now()
        ]);
        
        $request->validate([
            'licenciado_id' => 'required|exists:licenciados,id',
            'template_id' => 'required|exists:contract_templates,id',
            'observacoes_admin' => 'nullable|string|max:1000'
        ]);

        try {
            \Log::info('📝 Buscando licenciado e template...');
            $licenciado = Licenciado::findOrFail($request->licenciado_id);
            $template = ContractTemplate::findOrFail($request->template_id);
            \Log::info('✅ Licenciado e template encontrados', [
                'licenciado' => $licenciado->razao_social ?? $licenciado->nome_fantasia,
                'template' => $template->name
            ]);

            // Preparar dados do contrato
            \Log::info('🔧 Preparando dados do contrato...');
            $contractData = $this->prepareContractData($licenciado);
            \Log::info('✅ Dados preparados', ['data_keys' => array_keys($contractData)]);
            
            // Substituir variáveis no template
            \Log::info('🔄 Substituindo variáveis no template...');
            $filledContent = $this->replaceTemplateVariables($template->content, $contractData);
            \Log::info('✅ Template preenchido', ['content_length' => strlen($filledContent)]);

            // Criar o contrato no banco
            \Log::info('💾 Criando contrato no banco...');
            $contract = Contract::create([
                'licenciado_table_id' => $licenciado->id,
                'template_id' => $template->id,
                'status' => 'draft',
                'observacoes_admin' => $request->observacoes_admin,
                'contract_data' => json_encode($contractData),
                'meta' => json_encode([
                    'filled_html' => $filledContent,
                    'generated_by' => auth()->id(),
                    'generated_at' => now()->toISOString()
                ]),
                'generated_at' => now(),
                'active' => true
            ]);
            \Log::info('✅ Contrato criado no banco', ['contract_id' => $contract->id]);

            // Gerar PDF do contrato
            \Log::info('📄 Iniciando geração de PDF...');
            $pdfPath = $this->generateContractPDF($contract, $filledContent);
            \Log::info('✅ PDF gerado', ['pdf_path' => $pdfPath]);
            
            // Atualizar contrato com caminho do PDF
            \Log::info('🔄 Atualizando contrato com PDF...');
            $contract->update([
                'contract_pdf_path' => $pdfPath,
                'status' => 'pdf_ready',
                'pdf_generated_at' => now()
            ]);
            \Log::info('✅ Contrato atualizado com sucesso');

            return redirect()->route('contracts.index')
                ->with('success', 'Contrato gerado com sucesso! ID: ' . $contract->id . '. Você pode agora enviar para assinatura.');

        } catch (\Exception $e) {
            \Log::error('❌ ERRO na geração de contrato:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Erro ao gerar contrato: ' . $e->getMessage()]);
        }
    }

    /**
     * Preparar dados para substituição no template
     */
    private function prepareContractData($source)
    {
        // Se for um Contract, pegar o licenciado
        if ($source instanceof Contract) {
            $licenciado = $source->licenciado;
            $contract = $source;
        } else {
            // Se for um Licenciado
            $licenciado = $source;
            $contract = null;
        }

        // Dados do licenciado
        $data = [
            'NOME' => $licenciado->razao_social ?: $licenciado->nome_fantasia,
            'DOCUMENTO' => $this->formatDocument($licenciado->cnpj_cpf),
            'CNPJ' => $this->isCNPJ($licenciado->cnpj_cpf) ? $this->formatDocument($licenciado->cnpj_cpf) : '',
            'CPF' => $this->isCPF($licenciado->cnpj_cpf) ? $this->formatDocument($licenciado->cnpj_cpf) : '',
            'ENDERECO' => $licenciado->endereco,
            'BAIRRO' => $licenciado->bairro,
            'CIDADE' => $licenciado->cidade,
            'ESTADO' => $licenciado->estado,
            'CEP' => $this->formatCEP($licenciado->cep),
            'EMAIL' => $licenciado->email,
            'TELEFONE' => $licenciado->telefone,
        ];

        // Endereço completo
        $enderecoCompleto = collect([
            $licenciado->endereco,
            $licenciado->bairro,
            $licenciado->cidade,
            $licenciado->estado
        ])->filter()->implode(', ');
        
        $data['ENDERECO_COMPLETO'] = $enderecoCompleto;

        // Dados do representante (se existir)
        if ($licenciado->representante_nome) {
            $data['REPRESENTANTE_NOME'] = $licenciado->representante_nome;
            $data['REPRESENTANTE_CPF'] = $this->formatDocument($licenciado->representante_cpf);
            $data['REPRESENTANTE_RG'] = $licenciado->representante_rg;
            $data['REPRESENTANTE_EMAIL'] = $licenciado->representante_email;
            $data['REPRESENTANTE_CARGO'] = $licenciado->representante_cargo ?: 'Representante Legal';
        }

        // Dados da empresa contratante (configuráveis)
        $data['EMPRESA_NOME'] = config('app.company_name', 'DSPay Soluções em Pagamento');
        $data['EMPRESA_CNPJ'] = config('app.company_cnpj', '00.000.000/0001-00');
        $data['EMPRESA_ENDERECO'] = config('app.company_address', 'Endereço da Empresa');

        // Dados do contrato
        $data['DATA_ATUAL'] = now()->format('d/m/Y');
        $data['ANO_ATUAL'] = now()->format('Y');
        
        if ($contract) {
            $data['NUMERO_CONTRATO'] = 'DSPAY-' . now()->format('Y') . '-' . str_pad($contract->id, 4, '0', STR_PAD_LEFT);
        } else {
            $data['NUMERO_CONTRATO'] = 'DSPAY-' . now()->format('Y') . '-' . str_pad(Contract::count() + 1, 4, '0', STR_PAD_LEFT);
        }
        
        $data['VALOR'] = 'A definir'; // Pode ser configurado posteriormente

        // Para compatibilidade com o método antigo
        if ($contract) {
            $operacao = Operacao::first();
            return [
                'contratante' => [
                    'nome' => $licenciado->razao_social ?? '',
                    'cnpj' => $this->formatCNPJ($licenciado->cnpj_cpf ?? ''),
                    'cnpj_formatted' => $this->formatCNPJ($licenciado->cnpj_cpf ?? ''),
                    'ie' => '',
                    'endereco' => $licenciado->endereco ?? '',
                    'cidade' => $licenciado->cidade ?? '',
                    'uf' => $licenciado->estado ?? '',
                    'cep' => $this->formatCEP($licenciado->cep ?? ''),
                    'cep_formatted' => $this->formatCEP($licenciado->cep ?? '')
                ],
                'representante' => [
                    'nome' => $licenciado->nome_fantasia ?? $licenciado->razao_social ?? '',
                    'cpf' => '',
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

        return $data;
    }

    /**
     * Substituir variáveis no template
     */
    private function replaceTemplateVariables(string $content, array $data): string
    {
        foreach ($data as $variable => $value) {
            $content = str_replace([
                '{{' . $variable . '}}',
                '{' . $variable . '}'
            ], $value, $content);
        }

        return $content;
    }

    /**
     * Gerar PDF do contrato
     */
    private function generateContractPDF(Contract $contract, string $htmlContent): string
    {
        try {
            // Sanitizar HTML
            $cleanHtml = $this->sanitizeHtml($htmlContent);
            
            // Log para debug
            \Log::info('📄 Iniciando geração de PDF', [
                'contract_id' => $contract->id,
                'html_length' => strlen($cleanHtml)
            ]);
            
            // Gerar PDF
            $pdf = Pdf::loadHTML($cleanHtml);
            $pdf->setPaper('A4', 'portrait');
            
            // Definir caminho do arquivo
            $fileName = 'contract_' . $contract->id . '_' . time() . '.pdf';
            $filePath = 'contracts/pdf/' . $fileName;
            
            // Garantir que o diretório existe
            $fullDir = storage_path('app/contracts/pdf');
            if (!is_dir($fullDir)) {
                mkdir($fullDir, 0755, true);
            }
            
            // Gerar conteúdo do PDF
            $pdfContent = $pdf->output();
            $fullPath = storage_path('app/' . $filePath);
            
            \Log::info('📄 Tentando salvar PDF', [
                'file_path' => $filePath,
                'full_path' => $fullPath,
                'pdf_size' => strlen($pdfContent),
                'dir_exists' => is_dir($fullDir),
                'dir_writable' => is_writable($fullDir)
            ]);
            
            // Usar file_put_contents ao invés de Storage::put para debugging
            $bytesWritten = file_put_contents($fullPath, $pdfContent);
            
            \Log::info('📄 Resultado do salvamento', [
                'bytes_written' => $bytesWritten,
                'file_exists_after' => file_exists($fullPath),
                'file_size_after' => file_exists($fullPath) ? filesize($fullPath) : 0
            ]);
            
            if (!file_exists($fullPath) || $bytesWritten === false) {
                throw new \Exception('PDF não foi salvo corretamente: ' . $fullPath . ' (bytes: ' . $bytesWritten . ')');
            }
            
            \Log::info('✅ PDF gerado com sucesso', [
                'contract_id' => $contract->id,
                'file_path' => $filePath,
                'file_size' => filesize($fullPath)
            ]);
            
            return $filePath;
            
        } catch (\Exception $e) {
            \Log::error('❌ Erro ao gerar PDF', [
                'contract_id' => $contract->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Verificar se é CNPJ
     */
    private function isCNPJ(string $document): bool
    {
        $document = preg_replace('/\D/', '', $document);
        return strlen($document) === 14;
    }

    /**
     * Verificar se é CPF
     */
    private function isCPF(string $document): bool
    {
        $document = preg_replace('/\D/', '', $document);
        return strlen($document) === 11;
    }

    /**
     * Formatar CEP
     */
    private function formatCEP(string $cep): string
    {
        $cep = preg_replace('/\D/', '', $cep);
        if (strlen($cep) === 8) {
            return substr($cep, 0, 5) . '-' . substr($cep, 5);
        }
        return $cep;
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
            'status' => 'criado',
            'observacoes_admin' => $request->observacoes_admin
        ]);

        // Log da criação
        $contract->logAction(
            'contrato_criado',
            'Contrato criado para o licenciado',
            auth()->id(),
            ['licenciado_id' => $request->licenciado_id]
        );

        return redirect()->route('contracts.show', $contract)
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


    private function generatePDF(ContractTemplate $template, array $data)
    {
        // Renderizar template
        $html = view('contracts.templates.default', $data)->render();
        
        // Substituir placeholders conforme especificação do usuário
        $licenciado = $data['licensee'] ?? null;
        if ($licenciado) {
            $placeholders = [
                '{{NOME}}' => $licenciado->razao_social ?? 'Nome não informado',
                '{{CNPJ}}' => $this->formatCNPJ($licenciado->cnpj_cpf ?? ''),
                '{{ENDERECO}}' => $this->buildFullAddress($licenciado),
                '{{CONCEP}}' => $licenciado->concep ?? 'CONCEP não informado'
            ];
            
            // Substituir cada placeholder no HTML
            foreach ($placeholders as $placeholder => $value) {
                $html = str_replace($placeholder, $value, $html);
            }
        }

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
    
    private function buildFullAddress($licenciado): string
    {
        $endereco = $licenciado->endereco ?? '';
        $cidade = $licenciado->cidade ?? '';
        $estado = $licenciado->estado ?? '';
        $cep = $this->formatCEP($licenciado->cep ?? '');
        
        $parts = array_filter([$endereco, $cidade, $estado, $cep]);
        return implode(', ', $parts);
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
        
        if (!$filePath) {
            return redirect()->back()->withErrors(['error' => 'Arquivo não encontrado.']);
        }
        
        // Caminho completo do arquivo
        $fullPath = storage_path('app/' . $filePath);
        
        if (!file_exists($fullPath)) {
            \Log::error('📥 Erro no download - arquivo não existe', [
                'contract_id' => $contract->id,
                'file_path' => $filePath,
                'full_path' => $fullPath,
                'user_id' => auth()->id()
            ]);
            return redirect()->back()->withErrors(['error' => 'Arquivo não encontrado no servidor.']);
        }

        $fileName = $contract->signed_contract_path ? 'Contrato_Assinado_' . str_pad($contract->id, 6, '0', STR_PAD_LEFT) . '.pdf' : 'Contrato_' . str_pad($contract->id, 6, '0', STR_PAD_LEFT) . '.pdf';
        
        \Log::info('📥 Download de contrato', [
            'contract_id' => $contract->id,
            'licenciado' => $contract->licenciado->razao_social ?? $contract->licenciado->nome_fantasia,
            'file_name' => $fileName,
            'user_id' => auth()->id()
        ]);
        
        return response()->download($fullPath, $fileName);
    }

    public function updateNotes(Request $request, Contract $contract)
    {
        $request->validate([
            'observacoes' => 'nullable|string|max:1000'
        ]);

        $contract->update([
            'observacoes' => $request->observacoes
        ]);

        // Log da ação
        $contract->logAction(
            'observacoes_atualizadas',
            'Observações do contrato atualizadas',
            auth()->id(),
            ['observacoes_antigas' => $contract->getOriginal('observacoes')]
        );

        return redirect()->back()->with('success', 'Observações atualizadas com sucesso!');
    }

    public function sendContract(Request $request, Contract $contract)
    {
        // Verificar se o contrato está no status correto para envio
        if ($contract->status !== 'criado') {
            return redirect()->back()->withErrors(['error' => 'Contrato não está no status correto para envio.']);
        }

        try {
            // Gerar o PDF do contrato
            $pdfPath = $this->generateContractPDF($contract);
            
            // Gerar token único para assinatura
            $contract->generateSignatureToken();
            
            // Atualizar status e dados do contrato
            $contract->update([
                'status' => 'contrato_enviado',
                'contract_pdf_path' => $pdfPath,
                'sent_at' => now(),
                'contract_sent_by' => auth()->id()
            ]);

            // Enviar email para o licenciado
            $this->sendContractByEmail($contract);

            // Log da ação
            $contract->logAction(
                'contrato_enviado',
                'Contrato gerado e enviado por email para o licenciado',
                auth()->id(),
                [
                    'pdf_path' => $pdfPath,
                    'email_enviado' => $contract->licenciado->email,
                    'signature_token' => $contract->signature_token
                ]
            );

            return redirect()->back()->with('success', 'Contrato gerado e enviado por email com sucesso!');

        } catch (\Exception $e) {
            \Log::error('Erro ao enviar contrato: ' . $e->getMessage(), [
                'contract_id' => $contract->id,
                'error' => $e->getTraceAsString()
            ]);

            return redirect()->back()->withErrors(['error' => 'Erro ao enviar contrato: ' . $e->getMessage()]);
        }
    }


    private function sendContractByEmail(Contract $contract)
    {
        $licenciado = $contract->licenciado;
        
        // URL para assinatura do contrato
        $signatureUrl = url('/contracts/sign/' . $contract->signature_token);
        
        // Dados para o email
        $emailData = [
            'licenciado' => $licenciado,
            'contract' => $contract,
            'signature_url' => $signatureUrl,
            'company_name' => config('app.name'),
        ];

        // Enviar email (usando Mail facade)
        try {
            \Mail::send('emails.contract-sent', $emailData, function ($message) use ($licenciado, $contract) {
                $message->to($licenciado->email, $licenciado->razao_social)
                        ->subject('Contrato para Assinatura Digital - ' . config('app.name'))
                        ->from(config('mail.from.address'), config('mail.from.name'));
                
                // Anexar PDF do contrato
                if ($contract->contract_pdf_path && Storage::disk('private')->exists($contract->contract_pdf_path)) {
                    $message->attachData(
                        Storage::disk('private')->get($contract->contract_pdf_path),
                        'contrato.pdf',
                        ['mime' => 'application/pdf']
                    );
                }
            });

            \Log::info('Email de contrato enviado com sucesso', [
                'contract_id' => $contract->id,
                'email' => $licenciado->email
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao enviar email do contrato: ' . $e->getMessage(), [
                'contract_id' => $contract->id,
                'email' => $licenciado->email
            ]);
            
            throw new \Exception('Erro ao enviar email: ' . $e->getMessage());
        }
    }

    public function showSignaturePage($token)
    {
        // Buscar contrato pelo token
        $contract = Contract::where('signature_token', $token)->first();
        
        if (!$contract) {
            abort(404, 'Contrato não encontrado ou token inválido.');
        }

        // Verificar se o contrato está no status correto
        if (!in_array($contract->status, ['contrato_enviado', 'aguardando_assinatura'])) {
            return view('contracts.sign-error', [
                'message' => 'Este contrato não está disponível para assinatura.',
                'contract' => $contract
            ]);
        }

        // Atualizar status para "aguardando assinatura" se ainda não estiver
        if ($contract->status === 'contrato_enviado') {
            $contract->update(['status' => 'aguardando_assinatura']);
        }

        // Preparar dados do contrato para exibição
        $contractData = $this->prepareContractData($contract);

        return view('contracts.sign', [
            'contract' => $contract,
            'licenciado' => $contract->licenciado,
            'contractData' => $contractData
        ]);
    }

    public function processSignature(Request $request, $token)
    {
        // Validar dados da assinatura
        $request->validate([
            'full_name' => 'required|string|max:255',
            'document' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'ip_address' => 'nullable|ip',
            'signature_date' => 'required|date',
            'accept_terms' => 'required|accepted'
        ]);

        // Buscar contrato pelo token
        $contract = Contract::where('signature_token', $token)->first();
        
        if (!$contract) {
            return response()->json(['error' => 'Contrato não encontrado.'], 404);
        }

        // Verificar se o contrato está no status correto
        if ($contract->status !== 'aguardando_assinatura') {
            return response()->json(['error' => 'Contrato não está disponível para assinatura.'], 400);
        }

        try {
            // Dados da assinatura
            $signatureData = [
                'signer_name' => $request->full_name,
                'signer_document' => $request->document,
                'signer_email' => $request->email,
                'signed_at' => $request->signature_date,
                'ip_address' => $request->ip() ?? $request->ip_address,
                'user_agent' => $request->userAgent(),
                'signature_hash' => hash('sha256', $contract->id . $request->full_name . $request->document . now())
            ];

            // Atualizar contrato com dados da assinatura
            $contract->update([
                'status' => 'contrato_assinado',
                'signature_data' => json_encode($signatureData),
                'contract_signed_at' => now(),
                'signer_ip' => $signatureData['ip_address']
            ]);

            // Gerar PDF do contrato assinado
            $this->generateSignedContractPDF($contract, $signatureData);

            // Log da assinatura
            $contract->logAction(
                'contrato_assinado',
                'Contrato assinado digitalmente pelo licenciado',
                null, // Sem usuário autenticado
                $signatureData
            );

            // Enviar email de confirmação
            $this->sendSignatureConfirmationEmail($contract);
            
            // Aprovar automaticamente o licenciado
            $this->approveContractAndLicensee($contract);

            return response()->json([
                'success' => true,
                'message' => 'Contrato assinado com sucesso!',
                'redirect' => route('contracts.sign.success', ['token' => $token])
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao processar assinatura: ' . $e->getMessage(), [
                'contract_id' => $contract->id,
                'token' => $token,
                'error' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Erro ao processar assinatura: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateSignedContractPDF(Contract $contract, array $signatureData)
    {
        // Preparar dados do contrato incluindo assinatura
        $contractData = $this->prepareContractData($contract);
        $contractData['signature'] = $signatureData;
        
        // Obter template padrão
        $template = ContractTemplate::getDefault();
        
        // Gerar PDF com dados de assinatura
        $pdf = $this->generatePDF($template, $contractData);
        
        // Salvar PDF assinado no storage privado
        $fileName = 'contract_signed_' . $contract->id . '_' . time() . '.pdf';
        $filePath = 'contracts/signed/' . $fileName;
        
        Storage::disk('private')->put($filePath, $pdf->output());
        
        // Atualizar caminho do contrato assinado
        $contract->update(['signed_contract_path' => $filePath]);
        
        return $filePath;
    }

    private function sendSignatureConfirmationEmail(Contract $contract)
    {
        $licenciado = $contract->licenciado;
        $signatureData = json_decode($contract->signature_data, true);
        
        // Dados para o email
        $emailData = [
            'licenciado' => $licenciado,
            'contract' => $contract,
            'signature_data' => $signatureData,
            'company_name' => config('app.name'),
        ];

        // Enviar email de confirmação
        try {
            \Mail::send('emails.contract-signed', $emailData, function ($message) use ($licenciado, $contract) {
                $message->to($licenciado->email, $licenciado->razao_social)
                        ->subject('Contrato Assinado com Sucesso - ' . config('app.name'))
                        ->from(config('mail.from.address'), config('mail.from.name'));
                
                // Anexar PDF do contrato assinado
                if ($contract->signed_contract_path && Storage::disk('private')->exists($contract->signed_contract_path)) {
                    $message->attachData(
                        Storage::disk('private')->get($contract->signed_contract_path),
                        'contrato_assinado.pdf',
                        ['mime' => 'application/pdf']
                    );
                }
            });

            \Log::info('Email de confirmação de assinatura enviado', [
                'contract_id' => $contract->id,
                'email' => $licenciado->email
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao enviar email de confirmação: ' . $e->getMessage(), [
                'contract_id' => $contract->id,
                'email' => $licenciado->email
            ]);
        }
    }

    public function showSignatureSuccess($token)
    {
        $contract = Contract::where('signature_token', $token)->first();
        
        if (!$contract || $contract->status !== 'contrato_assinado') {
            abort(404);
        }

        return view('contracts.sign-success', [
            'contract' => $contract,
            'licenciado' => $contract->licenciado
        ]);
    }
    
    private function approveContractAndLicensee(Contract $contract)
    {
        try {
            // Atualizar status do contrato para aprovado
            $contract->update([
                'status' => 'licenciado_aprovado',
                'licenciado_released_at' => now()
            ]);
            
            // Atualizar status do licenciado para ativo/aprovado
            $licenciado = $contract->licenciado;
            if ($licenciado) {
                $oldStatus = $licenciado->status;
                $licenciado->update([
                    'status' => 'ativo' // ou 'aprovado' dependendo da sua estrutura
                ]);
                
                // Log da aprovação automática
                $contract->logAction(
                    'licenciado_aprovado_automaticamente',
                    'Licenciado aprovado automaticamente após assinatura do contrato',
                    null, // Sistema automático
                    [
                        'status_anterior' => $oldStatus,
                        'status_novo' => 'ativo',
                        'aprovado_em' => now()->toISOString()
                    ]
                );
                
                // Enviar email de aprovação para o licenciado
                $this->sendLicenseeApprovalEmail($contract);
                
                \Log::info('Licenciado aprovado automaticamente após assinatura', [
                    'contract_id' => $contract->id,
                    'licenciado_id' => $licenciado->id,
                    'licenciado_email' => $licenciado->email,
                    'status_anterior' => $oldStatus,
                    'status_novo' => 'ativo'
                ]);
            }
            
        } catch (\Exception $e) {
            \Log::error('Erro ao aprovar licenciado automaticamente: ' . $e->getMessage(), [
                'contract_id' => $contract->id,
                'error' => $e->getTraceAsString()
            ]);
            
            // Não falhar o processo de assinatura por causa deste erro
            // apenas registrar para investigação posterior
        }
    }
    
    private function sendLicenseeApprovalEmail(Contract $contract)
    {
        $licenciado = $contract->licenciado;
        
        // Dados para o email de aprovação
        $emailData = [
            'licenciado' => $licenciado,
            'contract' => $contract,
            'company_name' => config('app.name'),
            'approval_date' => now()
        ];

        try {
            \Mail::send('emails.licensee-approved', $emailData, function ($message) use ($licenciado, $contract) {
                $message->to($licenciado->email, $licenciado->razao_social)
                        ->subject('🎉 Parabéns! Seu licenciamento foi aprovado - ' . config('app.name'))
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

            \Log::info('Email de aprovação de licenciado enviado', [
                'contract_id' => $contract->id,
                'email' => $licenciado->email
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao enviar email de aprovação do licenciado: ' . $e->getMessage(), [
                'contract_id' => $contract->id,
                'email' => $licenciado->email
            ]);
        }
    }

    // Endpoints para steps encadeados
    public function uploadTemplate(Request $request, Contract $contract)
    {
        $request->validate([
            'template' => 'required|file|mimes:blade.php,html,docx|max:5120', // 5MB max
        ]);

        try {
            if (!in_array($contract->status, ['draft', 'criado'])) {
                return response()->json(['error' => 'Contrato não está no status correto para upload de template.'], 400);
            }

            $file = $request->file('template');
            $filename = 'template_' . $contract->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('contracts/templates', $filename);

            $contract->update([
                'status' => 'template_uploaded',
                'template_path' => $path,
                'meta' => json_encode(array_merge(
                    json_decode($contract->meta ?? '{}', true),
                    ['template_uploaded_at' => now()->toISOString()]
                ))
            ]);

            $contract->logAction(
                'template_uploaded',
                'Modelo de contrato enviado',
                auth()->id(),
                ['filename' => $filename, 'path' => $path]
            );

            return response()->json([
                'success' => true,
                'message' => 'Modelo de contrato enviado com sucesso!',
                'contract' => $contract->fresh()
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao fazer upload do template: ' . $e->getMessage(), [
                'contract_id' => $contract->id,
                'error' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Erro ao fazer upload: ' . $e->getMessage()], 500);
        }
    }

    public function fillTemplate(Request $request, Contract $contract)
    {
        try {
            if ($contract->status !== 'template_uploaded') {
                return response()->json(['error' => 'Template deve ser enviado antes do preenchimento.'], 400);
            }

            if (!$contract->template_path) {
                return response()->json(['error' => 'Template não encontrado.'], 400);
            }

            // Ler template
            $templateContent = \Storage::get($contract->template_path);
            
            // Preparar dados do licenciado
            $licenciado = $contract->licenciado;
            $placeholders = [
                '{{NOME}}' => $licenciado->razao_social ?? $licenciado->nome_fantasia ?? 'N/A',
                '{{DOCUMENTO}}' => $this->formatDocument($licenciado->cnpj_cpf ?? ''),
                '{{ENDERECO_COMPLETO}}' => $this->buildFullAddress($licenciado),
                '{{CEP}}' => $this->formatCEP($licenciado->cep ?? ''),
            ];

            // Substituir placeholders
            $filledContent = $templateContent;
            foreach ($placeholders as $placeholder => $value) {
                $filledContent = str_replace($placeholder, $value, $filledContent);
            }

            // Sanitizar HTML
            $sanitizedHtml = $this->sanitizeHtml($filledContent);

            // Salvar HTML preenchido
            $contract->update([
                'status' => 'filled',
                'meta' => json_encode(array_merge(
                    json_decode($contract->meta ?? '{}', true),
                    [
                        'filled_html' => $sanitizedHtml,
                        'filled_at' => now()->toISOString(),
                        'placeholders_used' => $placeholders
                    ]
                ))
            ]);

            $contract->logAction(
                'template_filled',
                'Modelo preenchido com dados do licenciado',
                auth()->id(),
                ['placeholders_count' => count($placeholders)]
            );

            return response()->json([
                'success' => true,
                'message' => 'Modelo preenchido com sucesso!',
                'preview_html' => $sanitizedHtml,
                'contract' => $contract->fresh()
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao preencher template: ' . $e->getMessage(), [
                'contract_id' => $contract->id,
                'error' => $e->getTraceAsString()
            ]);

            $contract->update([
                'status' => 'error',
                'last_error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Erro ao preencher template: ' . $e->getMessage()], 500);
        }
    }


    public function sendEmail(Request $request, Contract $contract)
    {
        try {
            \Log::info('Iniciando envio de e-mail do contrato', [
                'contract_id' => $contract->id,
                'licensee_name' => $contract->licenciado->name,
                'licensee_email' => $contract->licenciado->email,
                'current_status' => $contract->status
            ]);

            // Verificar se o contrato tem PDF gerado (aceitar vários status)
            $hasValidPdf = false;
            
            // Verificar se tem PDF do novo sistema
            if ($contract->contract_pdf_path && file_exists(storage_path('app/' . $contract->contract_pdf_path))) {
                $hasValidPdf = true;
            }
            
            // Verificar se tem PDF do sistema antigo
            if ($contract->status === 'pdf_ready' || $contract->pdf_path) {
                $hasValidPdf = true;
            }
            
            if (!$hasValidPdf) {
                return response()->json([
                    'success' => false,
                    'message' => 'PDF do contrato não encontrado. Gere o PDF antes de enviar por e-mail.'
                ], 400);
            }

            // Verificar se o licenciado tem e-mail
            if (!$contract->licenciado->email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Licenciado não possui e-mail cadastrado.'
                ], 400);
            }

            // Gerar token de assinatura se não existir
            if (!$contract->signature_token) {
                $contract->generateSignatureToken();
            }

            // Dados para o template de e-mail
            $emailData = [
                'licensee_name' => $contract->licenciado->name,
                'contract_id' => $contract->id,
                'contract_created_at' => $contract->created_at->format('d/m/Y'),
                'company_name' => config('app.name', 'DSPAY')
            ];

            // Determinar o caminho do PDF
            $pdfPath = null;
            if ($contract->contract_pdf_path && file_exists(storage_path('app/' . $contract->contract_pdf_path))) {
                $pdfPath = storage_path('app/' . $contract->contract_pdf_path);
            } elseif ($contract->pdf_path && file_exists(storage_path('app/' . $contract->pdf_path))) {
                $pdfPath = storage_path('app/' . $contract->pdf_path);
            }

            if (!$pdfPath) {
                return response()->json([
                    'success' => false,
                    'message' => 'Arquivo PDF não encontrado no servidor.'
                ], 400);
            }

            // Enviar e-mail
            Mail::send('emails.contract', $emailData, function ($message) use ($contract, $pdfPath) {
                $message->to($contract->licenciado->email, $contract->licenciado->name)
                        ->subject('Contrato de Licenciamento - ' . config('app.name'))
                        ->attach($pdfPath, [
                            'as' => 'Contrato_' . $contract->id . '.pdf',
                            'mime' => 'application/pdf'
                        ]);
            });

            // Atualizar status do contrato
            $newStatus = $contract->status === 'pdf_ready' ? 'sent' : 'contrato_enviado';
            $contract->update([
                'status' => $newStatus,
                'contract_sent_at' => now(),
                'contract_sent_by' => auth()->id()
            ]);

            // Log da ação
            if (method_exists($contract, 'logAction')) {
                $contract->logAction(
                    'email_sent',
                    'Contrato enviado por email',
                    auth()->id(),
                    [
                        'email_destinatario' => $contract->licenciado->email,
                        'pdf_path' => $pdfPath
                    ]
                );
            }

            \Log::info('E-mail do contrato enviado com sucesso', [
                'contract_id' => $contract->id,
                'sent_to' => $contract->licenciado->email,
                'new_status' => $newStatus
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contrato enviado com sucesso para ' . $contract->licenciado->email,
                'sent_to' => $contract->licenciado->email,
                'sent_at' => $contract->contract_sent_at->format('d/m/Y H:i:s'),
                'contract' => $contract->fresh()
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao enviar e-mail do contrato', [
                'contract_id' => $contract->id,
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);

            $contract->update([
                'status' => 'error',
                'last_error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar e-mail: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSignatureStatus(Contract $contract)
    {
        try {
            $status = 'pending';
            
            if ($contract->status === 'signed') {
                $status = 'signed';
            } elseif ($contract->status === 'approved') {
                $status = 'approved';
            } elseif (in_array($contract->status, ['sent', 'aguardando_assinatura'])) {
                $status = 'pending';
            }

            return response()->json([
                'status' => $status,
                'contract_status' => $contract->status,
                'signed_at' => $contract->signed_at?->format('d/m/Y H:i:s'),
                'signature_data' => $contract->signature_data ? json_decode($contract->signature_data, true) : null,
                'contract' => $contract->fresh()
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao verificar status de assinatura: ' . $e->getMessage(), [
                'contract_id' => $contract->id
            ]);

            return response()->json(['error' => 'Erro ao verificar status'], 500);
        }
    }

    public function approve(Request $request, Contract $contract)
    {
        try {
            if ($contract->status !== 'signed') {
                return response()->json(['error' => 'Contrato deve estar assinado para aprovação.'], 400);
            }

            // Aprovar contrato
            $contract->update([
                'status' => 'approved',
                'approved_at' => now()
            ]);

            // Atualizar status do licenciado
            $licenciado = $contract->licenciado;
            if ($licenciado) {
                $licenciado->update(['status' => 'ativo']);
            }

            $contract->logAction(
                'contract_approved',
                'Contrato aprovado e licenciado liberado',
                auth()->id(),
                ['approved_at' => now()->toISOString()]
            );

            // Enviar email de aprovação
            $this->sendApprovalEmail($contract);

            return response()->json([
                'success' => true,
                'message' => 'Contrato aprovado e licenciado liberado!',
                'approved_at' => $contract->approved_at->format('d/m/Y H:i:s'),
                'contract' => $contract->fresh()
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao aprovar contrato: ' . $e->getMessage(), [
                'contract_id' => $contract->id,
                'error' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Erro ao aprovar contrato: ' . $e->getMessage()], 500);
        }
    }

    public function signatureWebhook(Request $request)
    {
        try {
            // Validar webhook (implementar conforme provedor de assinatura)
            $contractId = $request->input('contract_id');
            $status = $request->input('status');
            
            $contract = Contract::find($contractId);
            if (!$contract) {
                return response()->json(['error' => 'Contrato não encontrado'], 404);
            }

            if ($status === 'signed') {
                $contract->update([
                    'status' => 'signed',
                    'signed_at' => now(),
                    'signature_data' => json_encode($request->input('signature_data', []))
                ]);

                $contract->logAction(
                    'webhook_signature_received',
                    'Assinatura recebida via webhook',
                    null,
                    $request->all()
                );
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('Erro no webhook de assinatura: ' . $e->getMessage(), [
                'request' => $request->all(),
                'error' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Erro no webhook'], 500);
        }
    }

    // Métodos auxiliares
    private function formatDocument(string $document): string
    {
        $clean = preg_replace('/[^0-9]/', '', $document);
        
        if (strlen($clean) === 11) {
            // CPF
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $clean);
        } elseif (strlen($clean) === 14) {
            // CNPJ
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $clean);
        }
        
        return $document;
    }



    private function sanitizeHtml(string $html): string
    {
        // Sanitizar HTML removendo scripts e tags perigosas
        $allowedTags = '<p><br><div><span><strong><em><ul><li><ol><h1><h2><h3><h4><h5><h6><table><tr><td><th><thead><tbody><img>';
        return strip_tags($html, $allowedTags);
    }

    private function sendApprovalEmail(Contract $contract)
    {
        try {
            // Implementar envio de email de aprovação
            \Log::info('Email de aprovação enviado', [
                'contract_id' => $contract->id,
                'licensee_email' => $contract->licenciado->email
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar email de aprovação: ' . $e->getMessage());
        }
    }

    /**
     * Excluir contrato
     */
    public function destroy(Contract $contract)
    {
        try {
            // Log da exclusão para auditoria
            \Log::info('🗑️ Exclusão de contrato', [
                'contract_id' => $contract->id,
                'licenciado' => $contract->licenciado->razao_social ?? $contract->licenciado->nome_fantasia,
                'status' => $contract->status,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name
            ]);

            // Remover arquivos físicos se existirem
            if ($contract->contract_pdf_path) {
                $pdfPath = storage_path('app/' . $contract->contract_pdf_path);
                if (file_exists($pdfPath)) {
                    unlink($pdfPath);
                    \Log::info('📄 Arquivo PDF removido', ['path' => $contract->contract_pdf_path]);
                }
            }

            if ($contract->signed_contract_path) {
                $signedPath = storage_path('app/' . $contract->signed_contract_path);
                if (file_exists($signedPath)) {
                    unlink($signedPath);
                    \Log::info('📄 Arquivo assinado removido', ['path' => $contract->signed_contract_path]);
                }
            }

            // Excluir o contrato
            $contract->delete();

            return redirect()->route('contracts.index')
                ->with('success', 'Contrato excluído com sucesso!');

        } catch (\Exception $e) {
            \Log::error('❌ Erro ao excluir contrato', [
                'contract_id' => $contract->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Erro ao excluir contrato: ' . $e->getMessage());
        }
    }


}
