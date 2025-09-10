<?php

namespace App\Http\Controllers;

use App\Models\Licenciado;
use App\Models\Operacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LicenciadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Licenciado::query();

        // Filtro por nome/razão social
        if ($request->filled('nome')) {
            $nome = $request->nome;
            $query->where(function($q) use ($nome) {
                $q->where('razao_social', 'like', "%{$nome}%")
                  ->orWhere('nome_fantasia', 'like', "%{$nome}%");
            });
        }

        // Filtro por cidade
        if ($request->filled('cidade')) {
            $query->where('cidade', 'like', "%{$request->cidade}%");
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por data de cadastro
        if ($request->filled('data_inicial')) {
            $query->whereDate('created_at', '>=', $request->data_inicial);
        }
        if ($request->filled('data_final')) {
            $query->whereDate('created_at', '<=', $request->data_final);
        }

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por operação
        if ($request->filled('operacao')) {
            $operacaoId = $request->operacao;
            $query->whereJsonContains('operacoes', $operacaoId);
        }

        $licenciados = $query->orderBy('created_at', 'desc')->get();
        
        $stats = [
            'total' => Licenciado::count(),
            'aprovados' => Licenciado::where('status', 'aprovado')->count(),
            'em_analise' => Licenciado::where('status', 'em_analise')->count(),
            'recusados' => Licenciado::where('status', 'recusado')->count(),
        ];

        $operacoes = Operacao::orderBy('nome')->get();
        
        // Buscar cidades únicas para o filtro
        $cidades = Licenciado::select('cidade')
            ->distinct()
            ->whereNotNull('cidade')
            ->where('cidade', '!=', '')
            ->orderBy('cidade')
            ->pluck('cidade');

        // Buscar estados únicos para o filtro
        $estados = Licenciado::select('estado')
            ->distinct()
            ->whereNotNull('estado')
            ->where('estado', '!=', '')
            ->orderBy('estado')
            ->pluck('estado');

        return view('dashboard.licenciados', compact('licenciados', 'stats', 'operacoes', 'cidades', 'estados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('licenciados.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log dos dados recebidos para debug
        \Log::info('Dados recebidos no store:', $request->all());
        
        $validator = Validator::make($request->all(), [
            'razao_social' => 'required|string|max:255',
            'nome_fantasia' => 'nullable|string|max:255',
            'cnpj_cpf' => 'required|string|unique:licenciados,cnpj_cpf',
            'endereco' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|size:2',
            'cep' => 'required|string|max:10',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'cartao_cnpj' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'contrato_social' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'rg_cnh' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'comprovante_residencia' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'comprovante_atividade' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'operacoes' => 'required|string', // Mudado de json para string
        ]);

        if ($validator->fails()) {
            \Log::error('Erros de validação:', $validator->errors()->toArray());
            \Log::error('Dados recebidos:', $request->all());
            \Log::error('Arquivos recebidos:', $request->files->all());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors(),
                'debug' => [
                    'received_data' => $request->all(),
                    'files' => array_keys($request->files->all())
                ]
            ], 422);
        }

        try {
            // Upload dos arquivos
            $cartaoCnpjPath = $this->uploadFile($request->file('cartao_cnpj'), 'documentos/cartao_cnpj');
            $contratoSocialPath = $this->uploadFile($request->file('contrato_social'), 'documentos/contrato_social');
            $rgCnhPath = $this->uploadFile($request->file('rg_cnh'), 'documentos/rg_cnh');
            $comprovanteResidenciaPath = $this->uploadFile($request->file('comprovante_residencia'), 'documentos/comprovante_residencia');
            $comprovanteAtividadePath = $this->uploadFile($request->file('comprovante_atividade'), 'documentos/comprovante_atividade');

            // Processar operações selecionadas
            $operacoesSelecionadas = json_decode($request->operacoes, true);
            
            // Criar o licenciado
            $licenciado = Licenciado::create([
                'razao_social' => $request->razao_social,
                'nome_fantasia' => $request->nome_fantasia,
                'cnpj_cpf' => $request->cnpj_cpf,
                'endereco' => $request->endereco,
                'bairro' => $request->bairro,
                'cidade' => $request->cidade,
                'estado' => $request->estado,
                'cep' => $request->cep,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'cartao_cnpj_path' => $cartaoCnpjPath,
                'contrato_social_path' => $contratoSocialPath,
                'rg_cnh_path' => $rgCnhPath,
                'comprovante_residencia_path' => $comprovanteResidenciaPath,
                'comprovante_atividade_path' => $comprovanteAtividadePath,
                'status' => 'em_analise',
            ]);

            // Salvar operações selecionadas
            if ($operacoesSelecionadas) {
                $licenciado->operacoes = $operacoesSelecionadas;
                $licenciado->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Licenciado cadastrado com sucesso!',
                'licenciado' => $licenciado
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cadastrar licenciado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Licenciado $licenciado)
    {
        return view('licenciados.show', compact('licenciado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Licenciado $licenciado)
    {
        return view('licenciados.edit', compact('licenciado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Licenciado $licenciado)
    {
        // Debug: Log all request data
        \Log::info('Update request data:', $request->all());
        
        $validator = Validator::make($request->all(), [
            'razao_social' => 'required|string|max:255',
            'nome_fantasia' => 'nullable|string|max:255',
            'cnpj_cpf' => 'required|string|unique:licenciados,cnpj_cpf,' . $licenciado->id,
            'endereco' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|size:2',
            'cep' => 'required|string|max:10',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'cartao_cnpj' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'contrato_social' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'rg_cnh' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'comprovante_residencia' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'comprovante_atividade' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'operacoes' => 'required|json',
        ]);

        if ($validator->fails()) {
            \Log::error('Erros de validação no update:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Processar operações selecionadas
            $operacoesSelecionadas = json_decode($request->operacoes, true);
            
            $data = [
                'razao_social' => $request->razao_social,
                'nome_fantasia' => $request->nome_fantasia,
                'cnpj_cpf' => $request->cnpj_cpf,
                'endereco' => $request->endereco,
                'bairro' => $request->bairro,
                'cidade' => $request->cidade,
                'estado' => $request->estado,
                'cep' => $request->cep,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'operacoes' => $operacoesSelecionadas,
            ];
            
            // Debug: Log the extracted data
            \Log::info('Extracted data for update:', $data);

            // Upload de novos arquivos se fornecidos
            if ($request->hasFile('cartao_cnpj')) {
                $this->deleteFile($licenciado->cartao_cnpj_path);
                $data['cartao_cnpj_path'] = $this->uploadFile($request->file('cartao_cnpj'), 'documentos/cartao_cnpj');
            }

            if ($request->hasFile('contrato_social')) {
                $this->deleteFile($licenciado->contrato_social_path);
                $data['contrato_social_path'] = $this->uploadFile($request->file('contrato_social'), 'documentos/contrato_social');
            }

            if ($request->hasFile('rg_cnh')) {
                $this->deleteFile($licenciado->rg_cnh_path);
                $data['rg_cnh_path'] = $this->uploadFile($request->file('rg_cnh'), 'documentos/rg_cnh');
            }

            if ($request->hasFile('comprovante_residencia')) {
                $this->deleteFile($licenciado->comprovante_residencia_path);
                $data['comprovante_residencia_path'] = $this->uploadFile($request->file('comprovante_residencia'), 'documentos/comprovante_residencia');
            }

            if ($request->hasFile('comprovante_atividade')) {
                $this->deleteFile($licenciado->comprovante_atividade_path);
                $data['comprovante_atividade_path'] = $this->uploadFile($request->file('comprovante_atividade'), 'documentos/comprovante_atividade');
            }

            $licenciado->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Licenciado atualizado com sucesso!',
                'licenciado' => $licenciado
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar licenciado:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Licenciado $licenciado)
    {
        try {
            // Deletar arquivos
            $this->deleteFile($licenciado->cartao_cnpj_path);
            $this->deleteFile($licenciado->contrato_social_path);
            $this->deleteFile($licenciado->rg_cnh_path);
            $this->deleteFile($licenciado->comprovante_residencia_path);
            $this->deleteFile($licenciado->comprovante_atividade_path);

            $licenciado->delete();

            return response()->json([
                'success' => true,
                'message' => 'Licenciado excluído com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir licenciado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload de arquivo
     */
    private function uploadFile($file, $path)
    {
        if (!$file) {
            return null;
        }

        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs($path, $fileName, 'public');
        
        return $filePath;
    }

    /**
     * Deletar arquivo
     */
    private function deleteFile($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }

    /**
     * Download de arquivo
     */
    public function downloadDocumento(Licenciado $licenciado, $tipo)
    {
        $pathMap = [
            'cartao_cnpj' => $licenciado->cartao_cnpj_path,
            'contrato_social' => $licenciado->contrato_social_path,
            'rg_cnh' => $licenciado->rg_cnh_path,
            'comprovante_residencia' => $licenciado->comprovante_residencia_path,
            'comprovante_atividade' => $licenciado->comprovante_atividade_path,
        ];

        if (!isset($pathMap[$tipo]) || !$pathMap[$tipo]) {
            abort(404, 'Documento não encontrado');
        }

        $path = $pathMap[$tipo];

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Arquivo não encontrado');
        }

        return Storage::disk('public')->download($path);
    }

    /**
     * Página de gerenciamento completo do licenciado
     */
    public function gerenciar(Licenciado $licenciado)
    {
        $operacoes = Operacao::orderBy('nome')->get();
        $followups = $licenciado->followUps()->with('user')->orderBy('created_at', 'desc')->get();
        
        return view('dashboard.licenciado-gerenciar', compact('licenciado', 'operacoes', 'followups'));
    }

    /**
     * Buscar detalhes do licenciado para visualização
     */
    public function getDetalhes(Licenciado $licenciado)
    {
        \Log::info('getDetalhes chamado para licenciado ID: ' . $licenciado->id);
        
        try {
            $response = [
                'success' => true,
                'licenciado' => $licenciado,
                'documentos' => [
                    'cartao_cnpj' => [
                        'nome' => 'Cartão CNPJ',
                        'path' => $licenciado->cartao_cnpj_path,
                        'url' => $licenciado->cartao_cnpj_path ? Storage::disk('public')->url($licenciado->cartao_cnpj_path) : null
                    ],
                    'contrato_social' => [
                        'nome' => 'Contrato Social',
                        'path' => $licenciado->contrato_social_path,
                        'url' => $licenciado->contrato_social_path ? Storage::disk('public')->url($licenciado->contrato_social_path) : null
                    ],
                    'rg_cnh' => [
                        'nome' => 'RG ou CNH',
                        'path' => $licenciado->rg_cnh_path,
                        'url' => $licenciado->rg_cnh_path ? Storage::disk('public')->url($licenciado->rg_cnh_path) : null
                    ],
                    'comprovante_residencia' => [
                        'nome' => 'Comprovante de Residência',
                        'path' => $licenciado->comprovante_residencia_path,
                        'url' => $licenciado->comprovante_residencia_path ? Storage::disk('public')->url($licenciado->comprovante_residencia_path) : null
                    ],
                    'comprovante_atividade' => [
                        'nome' => 'Comprovante de Atividade',
                        'path' => $licenciado->comprovante_atividade_path,
                        'url' => $licenciado->comprovante_atividade_path ? Storage::disk('public')->url($licenciado->comprovante_atividade_path) : null
                    ]
                ]
            ];
            
            \Log::info('getDetalhes response preparado:', $response);
            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error('Erro em getDetalhes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar detalhes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Alterar status do licenciado
     */
    public function alterarStatus(Request $request, Licenciado $licenciado)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:aprovado,recusado,em_analise,risco,ativo,pendente,inativo,vencendo'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $licenciado->update(['status' => $request->status]);

            $statusLabels = [
                'aprovado' => 'Aprovado',
                'recusado' => 'Recusado',
                'em_analise' => 'Em Análise',
                'risco' => 'Risco',
                'ativo' => 'Ativo',
                'pendente' => 'Pendente',
                'inativo' => 'Inativo',
                'vencendo' => 'Vencendo'
            ];

            return response()->json([
                'success' => true,
                'message' => 'Status alterado com sucesso!',
                'licenciado' => $licenciado,
                'status_label' => $statusLabels[$request->status] ?? ucfirst($request->status)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar follow-ups do licenciado
     */
    public function getFollowUp(Licenciado $licenciado)
    {
        try {
            $followups = $licenciado->followUps()->with('user')->get();
            
            return response()->json([
                'success' => true,
                'licenciado' => $licenciado,
                'followups' => $followups
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar follow-ups: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar follow-ups: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Adicionar novo follow-up
     */
    public function storeFollowUp(Request $request, Licenciado $licenciado)
    {
        $validator = Validator::make($request->all(), [
            'tipo' => 'required|in:contato,documentacao,analise,aprovacao,rejeicao,observacao',
            'observacao' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $followup = $licenciado->followUps()->create([
                'tipo' => $request->tipo,
                'observacao' => $request->observacao,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Follow-up adicionado com sucesso!',
                'followup' => $followup->load('user')
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao adicionar follow-up: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao adicionar follow-up: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API para listar licenciados (usado na agenda)
     */
    public function apiList()
    {
        try {
            $licenciados = Licenciado::whereIn('status', ['ativo', 'aprovado'])
                ->whereNotNull('email')
                ->orderBy('razao_social')
                ->get(['id', 'razao_social', 'nome_fantasia', 'email']);

            return response()->json([
                'success' => true,
                'licenciados' => $licenciados
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar licenciados'
            ], 500);
        }
    }
}
