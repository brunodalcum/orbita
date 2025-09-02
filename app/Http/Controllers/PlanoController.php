<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plano;
use App\Models\Operacao;
use App\Models\Adquirente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $planos = Plano::orderBy('nome')->get();
        $operacoes = Operacao::orderBy('nome')->get();
        
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
            'taxas' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . implode(', ', $validator->errors()->all())
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
            
            // Criar array com todas as informações do plano
            $dadosPlano = [
                'parceiro' => $request->parceiro,
                'adquirente' => $request->adquirente,
                'modalidade' => $request->modalidade,
                'parcelamento' => $request->parcelamento,
                'tipo' => $request->tipo,
                'taxas' => $request->taxas
            ];

            $plano = Plano::create([
                'nome' => $request->nome,
                'descricao' => $descricao,
                'taxa' => $taxaMedia,
                'taxas_detalhadas' => $request->taxas,
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
            'nome' => 'required|string|max:255',
            'parceiro' => 'required|string|max:255',
            'adquirente' => 'required|string|max:255',
            'modalidade' => 'required|in:D+1,D30',
            'parcelamento' => 'required|in:12,18,21',
            'tipo' => 'required|in:Mundo físico,Online,Tap To Pay',
            'taxas' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . implode(', ', $validator->errors()->all())
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

            $plano = Plano::findOrFail($id);
            $plano->update([
                'nome' => $request->nome,
                'descricao' => $descricao,
                'taxa' => $taxaMedia,
                'taxas_detalhadas' => $request->taxas,
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
}
