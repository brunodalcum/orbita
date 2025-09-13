<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plano;
use App\Models\PlanoTaxa;
use App\Models\Operacao;
use App\Models\Adquirente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanoController extends Controller
{
    /**
     * Display a listing of the resource with advanced filtering.
     */
    public function index(Request $request)
    {
        // Aplicar filtros se existirem parâmetros
        if ($request->hasAny(['modalidades', 'bandeiras', 'taxa_min', 'taxa_max', 'comissao_min', 'comissao_max', 'parcelas_min', 'parcelas_max', 'adquirente_id', 'parceiro_id', 'ativo', 'include_rates'])) {
            return $this->filterPlanos($request);
        }

        // Busca padrão sem filtros
        $planos = Plano::with(['taxasAtivas' => function ($query) {
                $query->orderBy('modalidade')->orderBy('bandeira')->orderBy('parcelas');
            }])
            ->orderBy('nome')
            ->get();

        $operacoes = Operacao::orderBy('nome')->get();
        
        // Se for requisição AJAX, retornar JSON
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'planos' => $planos,
                'total' => $planos->count()
            ]);
        }
        
        return view('dashboard.planos', compact('planos', 'operacoes'));
    }

    /**
     * Get all operations for AJAX requests.
     */
    public function getOperacoes()
    {
        $operacoes = Operacao::orderBy('nome')->get();
        
        return response()->json([
            'success' => true,
            'operacoes' => $operacoes
        ]);
    }

    /**
     * Get all acquirers for AJAX requests.
     */
    public function getAdquirentes()
    {
        $adquirentes = Adquirente::where('status', 'ativo')->orderBy('nome')->get();
        
        return response()->json([
            'success' => true,
            'adquirentes' => $adquirentes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'parceiro' => 'required|string|max:255',
            'adquirente' => 'required|string|max:255',
            'modalidade' => 'required|in:D+1,D30',
            'parcelamento' => 'required|in:12,18,21',
            'tipo' => 'required|in:Mundo físico,Online,Tap To Pay',
            'taxas' => 'required|array',
            'comissoes' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . implode(', ', $validator->errors()->all()),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Criar descrição baseada nos dados do plano
            $descricao = "Plano {$request->nome} - {$request->parceiro} - {$request->adquirente} - {$request->modalidade} - {$request->parcelamento}x - {$request->tipo}";
            
            // Calcular taxa média (pode ser ajustado conforme necessário)
            $taxaMedia = 0;
            $contador = 0;
            
            if (isset($request->taxas['debito'])) {
                foreach ($request->taxas['debito'] as $taxa) {
                    if (is_numeric($taxa)) {
                        $taxaMedia += $taxa;
                        $contador++;
                    }
                }
            }
            
            if (isset($request->taxas['credito_vista'])) {
                foreach ($request->taxas['credito_vista'] as $taxa) {
                    if (is_numeric($taxa)) {
                        $taxaMedia += $taxa;
                        $contador++;
                    }
                }
            }
            
            if (isset($request->taxas['parcelado'])) {
                foreach ($request->taxas['parcelado'] as $bandeira) {
                    foreach ($bandeira as $taxa) {
                        if (is_numeric($taxa)) {
                            $taxaMedia += $taxa;
                            $contador++;
                        }
                    }
                }
            }
            
            $taxaMedia = $contador > 0 ? $taxaMedia / $contador : 0;

            // Calcular comissão média
            $comissaoMedia = 0;
            $contadorComissao = 0;
            
            if (isset($request->comissoes['debito'])) {
                foreach ($request->comissoes['debito'] as $comissao) {
                    if (is_numeric($comissao)) {
                        $comissaoMedia += $comissao;
                        $contadorComissao++;
                    }
                }
            }
            
            if (isset($request->comissoes['credito_vista'])) {
                foreach ($request->comissoes['credito_vista'] as $comissao) {
                    if (is_numeric($comissao)) {
                        $comissaoMedia += $comissao;
                        $contadorComissao++;
                    }
                }
            }
            
            if (isset($request->comissoes['parcelado'])) {
                foreach ($request->comissoes['parcelado'] as $bandeira) {
                    foreach ($bandeira as $comissao) {
                        if (is_numeric($comissao)) {
                            $comissaoMedia += $comissao;
                            $contadorComissao++;
                        }
                    }
                }
            }
            
            $comissaoMedia = $contadorComissao > 0 ? $comissaoMedia / $contadorComissao : 0;
            
            // Criar array com todas as informações do plano
            $dadosPlano = [
                'parceiro' => $request->parceiro,
                'adquirente' => $request->adquirente,
                'modalidade' => $request->modalidade,
                'parcelamento' => $request->parcelamento,
                'tipo' => $request->tipo,
                'taxas' => $request->taxas,
                'comissoes' => $request->comissoes
            ];

            $plano = Plano::create([
                'nome' => $request->nome,
                'descricao' => $descricao,
                'taxa' => $taxaMedia,
                'taxas_detalhadas' => $request->taxas,
                'comissoes_detalhadas' => $request->comissoes,
                'comissao_media' => $comissaoMedia,
                'operacoes' => [], // Por enquanto vazio, pode ser expandido depois
                'status' => 'ativo'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Plano criado com sucesso!',
                'plano' => $plano
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar plano: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $plano = Plano::findOrFail($id);
            
            // Decodificar dados do plano da descrição
            $planData = [];
            if ($plano->descricao && strpos($plano->descricao, ' - ') !== false) {
                $parts = explode(' - ', $plano->descricao);
                if (count($parts) >= 6) {
                    $planData = [
                        'parceiro' => $parts[1] ?? 'N/A',
                        'adquirente' => $parts[2] ?? 'N/A',
                        'modalidade' => $parts[3] ?? 'N/A',
                        'parcelamento' => $parts[4] ?? 'N/A',
                        'tipo' => $parts[5] ?? 'N/A'
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'plano' => $plano,
                'planData' => $planData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar plano: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $plano = Plano::findOrFail($id);
            $operacoes = Operacao::orderBy('nome')->get();
            
            // Decodificar dados do plano da descrição
            $planData = [];
            if ($plano->descricao && strpos($plano->descricao, ' - ') !== false) {
                $parts = explode(' - ', $plano->descricao);
                if (count($parts) >= 6) {
                    $planData = [
                        'parceiro' => $parts[1] ?? '',
                        'adquirente' => $parts[2] ?? '',
                        'modalidade' => $parts[3] ?? '',
                        'parcelamento' => $parts[4] ?? '',
                        'tipo' => $parts[5] ?? ''
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'plano' => $plano,
                'planData' => $planData,
                'operacoes' => $operacoes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar dados do plano: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'nullable|string|max:255',
            'parceiro' => 'nullable|string|max:255',
            'adquirente' => 'nullable|string|max:255',
            'modalidade' => 'nullable|in:D+1,D30',
            'parcelamento' => 'nullable|in:12,18,21',
            'tipo' => 'nullable|in:Mundo físico,Online,Tap To Pay',
            'taxas' => 'required|array',
            'comissoes' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . implode(', ', $validator->errors()->all()),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Criar descrição baseada nos dados do plano
            $descricao = "Plano {$request->nome} - {$request->parceiro} - {$request->adquirente} - {$request->modalidade} - {$request->parcelamento}x - {$request->tipo}";
            
            // Calcular taxa média (pode ser ajustado conforme necessário)
            $taxaMedia = 0;
            $contador = 0;
            
            if (isset($request->taxas['debito'])) {
                foreach ($request->taxas['debito'] as $taxa) {
                    if (is_numeric($taxa)) {
                        $taxaMedia += $taxa;
                        $contador++;
                    }
                }
            }
            
            if (isset($request->taxas['credito_vista'])) {
                foreach ($request->taxas['credito_vista'] as $taxa) {
                    if (is_numeric($taxa)) {
                        $taxaMedia += $taxa;
                        $contador++;
                    }
                }
            }
            
            if (isset($request->taxas['parcelado'])) {
                foreach ($request->taxas['parcelado'] as $bandeira) {
                    foreach ($bandeira as $taxa) {
                        if (is_numeric($taxa)) {
                            $taxaMedia += $taxa;
                            $contador++;
                        }
                    }
                }
            }
            
            $taxaMedia = $contador > 0 ? $taxaMedia / $contador : 0;

            // Calcular comissão média
            $comissaoMedia = 0;
            $contadorComissao = 0;
            
            if (isset($request->comissoes['debito'])) {
                foreach ($request->comissoes['debito'] as $comissao) {
                    if (is_numeric($comissao)) {
                        $comissaoMedia += $comissao;
                        $contadorComissao++;
                    }
                }
            }
            
            if (isset($request->comissoes['credito_vista'])) {
                foreach ($request->comissoes['credito_vista'] as $comissao) {
                    if (is_numeric($comissao)) {
                        $comissaoMedia += $comissao;
                        $contadorComissao++;
                    }
                }
            }
            
            if (isset($request->comissoes['parcelado'])) {
                foreach ($request->comissoes['parcelado'] as $bandeira) {
                    foreach ($bandeira as $comissao) {
                        if (is_numeric($comissao)) {
                            $comissaoMedia += $comissao;
                            $contadorComissao++;
                        }
                    }
                }
            }
            
            $comissaoMedia = $contadorComissao > 0 ? $comissaoMedia / $contadorComissao : 0;

            $plano = Plano::findOrFail($id);
            $plano->update([
                'nome' => $request->nome,
                'descricao' => $descricao,
                'taxa' => $taxaMedia,
                'taxas_detalhadas' => $request->taxas,
                'comissoes_detalhadas' => $request->comissoes,
                'comissao_media' => $comissaoMedia,
                'operacoes' => [] // Por enquanto vazio, pode ser expandido depois
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Plano atualizado com sucesso!',
                'plano' => $plano
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar plano: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $plano = Plano::findOrFail($id);
            $plano->delete();

            return response()->json([
                'success' => true,
                'message' => 'Plano excluído com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir plano: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the status of the specified plan.
     */
    public function toggleStatus(Request $request, string $id)
    {
        try {
            $plano = Plano::findOrFail($id);
            $newStatus = $request->status === 'ativo' ? 'ativo' : 'inativo';
            
            $plano->update(['status' => $newStatus]);

            $actionText = $newStatus === 'ativo' ? 'ativado' : 'desativado';

            return response()->json([
                'success' => true,
                'message' => "Plano {$actionText} com sucesso!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status do plano: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filter plans based on criteria.
     */
    public function filter(Request $request)
    {
        try {
            $query = Plano::query();

            // Filtro por adquirente - Método corrigido e robusto
            if ($request->filled('adquirente')) {
                $adquirente = $request->adquirente;
                
                // Buscar adquirentes que correspondem ao filtro
                $adquirenteIds = \App\Models\Adquirente::where('nome', 'LIKE', "%{$adquirente}%")->pluck('id');
                
                if ($adquirenteIds->isNotEmpty()) {
                    // Usar whereIn com os IDs encontrados (mais seguro)
                    $query->whereIn('adquirente_id', $adquirenteIds);
                } else {
                    // Se não encontrar adquirentes, filtrar para não retornar nada
                    $query->where('id', -1); // Condição impossível para não retornar resultados
                }
            }

            // Filtro por parceiro
            if ($request->filled('parceiro')) {
                $parceiro = $request->parceiro;
                $query->whereHas('parceiro', function($q) use ($parceiro) {
                    $q->where('nome', 'LIKE', "%{$parceiro}%");
                });
            }

            // Filtro por nome
            if ($request->filled('nome')) {
                $nome = $request->nome;
                $query->where('nome', 'LIKE', "%{$nome}%");
            }

            // Filtro por tipo de taxa
            if ($request->filled('tipo_taxa')) {
                $tipoTaxa = $request->tipo_taxa;
                $bandeira = $request->bandeira;
                $taxaMin = $request->taxa_min;
                $taxaMax = $request->taxa_max;

                if ($tipoTaxa === 'debito') {
                    if ($bandeira) {
                        $query->whereRaw("JSON_EXTRACT(taxas_detalhadas, '$.debito.{$bandeira}') >= ?", [$taxaMin])
                              ->whereRaw("JSON_EXTRACT(taxas_detalhadas, '$.debito.{$bandeira}') <= ?", [$taxaMax]);
                    } else {
                        $query->where(function($q) use ($taxaMin, $taxaMax) {
                            $q->whereRaw("JSON_EXTRACT(taxas_detalhadas, '$.debito.visa_master') >= ?", [$taxaMin])
                              ->whereRaw("JSON_EXTRACT(taxas_detalhadas, '$.debito.visa_master') <= ?", [$taxaMax])
                              ->orWhereRaw("JSON_EXTRACT(taxas_detalhadas, '$.debito.elo') >= ?", [$taxaMin])
                              ->whereRaw("JSON_EXTRACT(taxas_detalhadas, '$.debito.elo') <= ?", [$taxaMax])
                              ->orWhereRaw("JSON_EXTRACT(taxas_detalhadas, '$.debito.demais') >= ?", [$taxaMin])
                              ->whereRaw("JSON_EXTRACT(taxas_detalhadas, '$.debito.demais') <= ?", [$taxaMax]);
                        });
                    }
                } elseif ($tipoTaxa === 'credito_vista') {
                    if ($bandeira) {
                        $query->whereRaw("JSON_EXTRACT(taxas_detalhadas, '$.credito_vista.{$bandeira}') >= ?", [$taxaMin])
                              ->whereRaw("JSON_EXTRACT(taxas_detalhadas, '$.credito_vista.{$bandeira}') <= ?", [$taxaMax]);
                    } else {
                        $query->where(function($q) use ($taxaMin, $taxaMax) {
                            $q->whereRaw("JSON_EXTRACT(taxas_detalhadas, '$.credito_vista.visa_master') >= ?", [$taxaMin])
                              ->whereRaw("JSON_EXTRACT(taxas_detalhadas, '$.credito_vista.visa_master') <= ?", [$taxaMax])
                              ->orWhereRaw("JSON_EXTRACT(taxas_detalhadas, '$.credito_vista.elo') >= ?", [$taxaMin])
                              ->whereRaw("JSON_EXTRACT(taxas_detalhadas, '$.credito_vista.elo') <= ?", [$taxaMax])
                              ->orWhereRaw("JSON_EXTRACT(taxas_detalhadas, '$.credito_vista.demais') >= ?", [$taxaMin])
                              ->whereRaw("JSON_EXTRACT(taxas_detalhadas, '$.credito_vista.demais') <= ?", [$taxaMax]);
                        });
                    }
                } elseif ($tipoTaxa === 'parcelado') {
                    // Para parcelado, verificar se tem dados de parcelamento
                    $query->whereRaw("JSON_EXTRACT(taxas_detalhadas, '$.parcelado') IS NOT NULL");
                }
            }

            // Filtro por comissão
            if ($request->filled('comissao_min') || $request->filled('comissao_max')) {
                $comissaoMin = $request->comissao_min ?? 0;
                $comissaoMax = $request->comissao_max ?? 999;
                $query->whereBetween('comissao_media', [$comissaoMin, $comissaoMax]);
            }

            // Ordenação
            $sortBy = $request->sort_by ?? 'nome';
            $sortOrder = $request->sort_order ?? 'asc';
            
            switch ($sortBy) {
                case 'adquirente':
                    $query->join('adquirentes', 'planos.adquirente_id', '=', 'adquirentes.id')
                          ->orderBy('adquirentes.nome', $sortOrder)
                          ->select('planos.*');
                    break;
                case 'parceiro':
                    $query->join('operacoes', 'planos.parceiro_id', '=', 'operacoes.id')
                          ->orderBy('operacoes.nome', $sortOrder)
                          ->select('planos.*');
                    break;
                case 'comissao_media':
                    $query->orderBy('comissao_media', $sortOrder);
                    break;
                default:
                    $query->orderBy('nome', $sortOrder);
            }

            // Incluir relacionamentos
            $planos = $query->with(['adquirente', 'parceiro'])->get();

            return response()->json([
                'success' => true,
                'planos' => $planos,
                'total' => $planos->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao filtrar planos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Advanced filtering method according to specification
     */
    public function filterPlanos(Request $request)
    {
        try {
            // Validação dos parâmetros
            $validator = Validator::make($request->all(), [
                'modalidades' => 'sometimes|array',
                'modalidades.*' => 'in:debito,credito_avista,parcelado',
                'bandeiras' => 'sometimes|array',
                'bandeiras.*' => 'string|max:20',
                'taxa_min' => 'sometimes|numeric|min:0|max:100',
                'taxa_max' => 'sometimes|numeric|min:0|max:100',
                'comissao_min' => 'sometimes|numeric|min:0|max:100',
                'comissao_max' => 'sometimes|numeric|min:0|max:100',
                'parcelas_min' => 'sometimes|integer|min:1|max:21',
                'parcelas_max' => 'sometimes|integer|min:1|max:21',
                'adquirente_id' => 'sometimes|array',
                'adquirente_id.*' => 'integer|exists:adquirentes,id',
                'parceiro_id' => 'sometimes|array',
                'parceiro_id.*' => 'integer|exists:operacoes,id',
                'ativo' => 'sometimes|boolean',
                'include_rates' => 'sometimes|boolean',
                'page' => 'sometimes|integer|min:1',
                'per_page' => 'sometimes|integer|min:1|max:100',
                'sort' => 'sometimes|in:taxa_asc,taxa_desc,comissao_asc,comissao_desc,nome_asc,nome_desc'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parâmetros inválidos: ' . implode(', ', $validator->errors()->all()),
                    'errors' => $validator->errors()
                ], 422);
            }

            // Construir query base
            $query = Plano::query();

            // Filtros opcionais em planos
            if ($request->filled('adquirente_id')) {
                $query->whereIn('adquirente_id', $request->adquirente_id);
            }

            if ($request->filled('parceiro_id')) {
                $query->whereIn('parceiro_id', $request->parceiro_id);
            }

            $ativo = $request->get('ativo', true);
            $query->where('ativo', $ativo);

            // Filtros complexos via relacionamento com PlanoTaxa
            $query->whereHas('taxasAtivas', function ($taxasQuery) use ($request) {
                // Filtro por modalidades
                if ($request->filled('modalidades')) {
                    $taxasQuery->whereIn('modalidade', $request->modalidades);
                }

                // Filtro por bandeiras
                if ($request->filled('bandeiras')) {
                    $taxasQuery->whereIn('bandeira', $request->bandeiras);
                }

                // Filtro por faixa de taxa
                if ($request->filled('taxa_min')) {
                    $taxasQuery->where('taxa_percent', '>=', $request->taxa_min);
                }
                if ($request->filled('taxa_max')) {
                    $taxasQuery->where('taxa_percent', '<=', $request->taxa_max);
                }

                // Filtro por faixa de comissão
                if ($request->filled('comissao_min')) {
                    $taxasQuery->where('comissao_percent', '>=', $request->comissao_min);
                }
                if ($request->filled('comissao_max')) {
                    $taxasQuery->where('comissao_percent', '<=', $request->comissao_max);
                }

                // Filtro por faixa de parcelas (somente para parcelado)
                if ($request->filled('modalidades') && in_array('parcelado', $request->modalidades)) {
                    if ($request->filled('parcelas_min')) {
                        $taxasQuery->where(function ($q) use ($request) {
                            $q->where('modalidade', '!=', 'parcelado')
                              ->orWhere('parcelas', '>=', $request->parcelas_min);
                        });
                    }
                    if ($request->filled('parcelas_max')) {
                        $taxasQuery->where(function ($q) use ($request) {
                            $q->where('modalidade', '!=', 'parcelado')
                              ->orWhere('parcelas', '<=', $request->parcelas_max);
                        });
                    }
                }
            });

            // Incluir taxas que casaram o filtro se solicitado
            if ($request->get('include_rates', false)) {
                $query->with(['taxasAtivas' => function ($taxasQuery) use ($request) {
                    // Aplicar os mesmos filtros nas taxas incluídas
                    if ($request->filled('modalidades')) {
                        $taxasQuery->whereIn('modalidade', $request->modalidades);
                    }
                    if ($request->filled('bandeiras')) {
                        $taxasQuery->whereIn('bandeira', $request->bandeiras);
                    }
                    if ($request->filled('taxa_min')) {
                        $taxasQuery->where('taxa_percent', '>=', $request->taxa_min);
                    }
                    if ($request->filled('taxa_max')) {
                        $taxasQuery->where('taxa_percent', '<=', $request->taxa_max);
                    }
                    if ($request->filled('comissao_min')) {
                        $taxasQuery->where('comissao_percent', '>=', $request->comissao_min);
                    }
                    if ($request->filled('comissao_max')) {
                        $taxasQuery->where('comissao_percent', '<=', $request->comissao_max);
                    }
                    if ($request->filled('modalidades') && in_array('parcelado', $request->modalidades)) {
                        if ($request->filled('parcelas_min')) {
                            $taxasQuery->where(function ($q) use ($request) {
                                $q->where('modalidade', '!=', 'parcelado')
                                  ->orWhere('parcelas', '>=', $request->parcelas_min);
                            });
                        }
                        if ($request->filled('parcelas_max')) {
                            $taxasQuery->where(function ($q) use ($request) {
                                $q->where('modalidade', '!=', 'parcelado')
                                  ->orWhere('parcelas', '<=', $request->parcelas_max);
                            });
                        }
                    }
                    
                    $taxasQuery->orderBy('modalidade')->orderBy('bandeira')->orderBy('parcelas');
                }]);
            }

            // Ordenação
            $sort = $request->get('sort', 'nome_asc');
            switch ($sort) {
                case 'taxa_asc':
                    $query->join('planos_taxas', 'planos.id', '=', 'planos_taxas.plano_id')
                          ->where('planos_taxas.ativo', true)
                          ->select('planos.*', \DB::raw('MIN(planos_taxas.taxa_percent) as min_taxa'))
                          ->groupBy('planos.id')
                          ->orderBy('min_taxa', 'asc');
                    break;
                case 'taxa_desc':
                    $query->join('planos_taxas', 'planos.id', '=', 'planos_taxas.plano_id')
                          ->where('planos_taxas.ativo', true)
                          ->select('planos.*', \DB::raw('MAX(planos_taxas.taxa_percent) as max_taxa'))
                          ->groupBy('planos.id')
                          ->orderBy('max_taxa', 'desc');
                    break;
                case 'comissao_asc':
                    $query->join('planos_taxas', 'planos.id', '=', 'planos_taxas.plano_id')
                          ->where('planos_taxas.ativo', true)
                          ->select('planos.*', \DB::raw('MIN(planos_taxas.comissao_percent) as min_comissao'))
                          ->groupBy('planos.id')
                          ->orderBy('min_comissao', 'asc');
                    break;
                case 'comissao_desc':
                    $query->join('planos_taxas', 'planos.id', '=', 'planos_taxas.plano_id')
                          ->where('planos_taxas.ativo', true)
                          ->select('planos.*', \DB::raw('MAX(planos_taxas.comissao_percent) as max_comissao'))
                          ->groupBy('planos.id')
                          ->orderBy('max_comissao', 'desc');
                    break;
                case 'nome_desc':
                    $query->orderBy('nome', 'desc');
                    break;
                default: // nome_asc
                    $query->orderBy('nome', 'asc');
            }

            // Paginação
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);

            if ($request->filled('page') || $request->filled('per_page')) {
                $planos = $query->paginate($perPage, ['*'], 'page', $page);
            } else {
                $planos = $query->get();
            }

            // Retornar resposta
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'planos' => $planos instanceof \Illuminate\Pagination\LengthAwarePaginator ? $planos->items() : $planos,
                    'total' => $planos instanceof \Illuminate\Pagination\LengthAwarePaginator ? $planos->total() : $planos->count(),
                    'current_page' => $planos instanceof \Illuminate\Pagination\LengthAwarePaginator ? $planos->currentPage() : 1,
                    'last_page' => $planos instanceof \Illuminate\Pagination\LengthAwarePaginator ? $planos->lastPage() : 1,
                    'per_page' => $perPage
                ]);
            }

            $operacoes = Operacao::orderBy('nome')->get();
            return view('dashboard.planos', compact('planos', 'operacoes'));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao filtrar planos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available bandeiras for filtering
     */
    public function getBandeiras()
    {
        try {
            $bandeiras = PlanoTaxa::select('bandeira')
                ->where('ativo', true)
                ->distinct()
                ->orderBy('bandeira')
                ->pluck('bandeira');

            return response()->json([
                'success' => true,
                'bandeiras' => $bandeiras
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar bandeiras: ' . $e->getMessage()
            ], 500);
        }
    }
}
