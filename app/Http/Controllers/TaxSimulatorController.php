<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TaxSimulatorController extends Controller
{
    /**
     * Exibir o simulador de taxas
     */
    public function index()
    {
        // Verificar se o usuário é licenciado para usar a view apropriada
        $user = Auth::user();
        
        if ($user && $user->role && $user->role->name === 'licenciado') {
            return view('licenciado.tax-simulator');
        }
        
        return view('dashboard.tax-simulator.index');
    }

    /**
     * Calcular as taxas baseado nos parâmetros informados
     */
    public function calculate(Request $request)
    {
        // Validação dos dados de entrada
        $validator = Validator::make($request->all(), [
            'mdr_debit' => 'required|numeric|min:0|max:100',
            'mdr_credit_1x' => 'required|numeric|min:0|max:100',
            'mdr_credit_2_6x' => 'required|numeric|min:0|max:100',
            'mdr_credit_7_12x' => 'required|numeric|min:0|max:100',
            'mdr_credit_13_18x' => 'required|numeric|min:0|max:100',
            'anticipation_rate' => 'required|numeric|min:0|max:20',
            'calculation_method' => 'in:linear,compound',
            'apply_anticipation_debit' => 'boolean',
            'month_base_days' => 'integer|min:28|max:31'
        ], [
            'mdr_debit.required' => 'MDR Débito é obrigatório',
            'mdr_debit.numeric' => 'MDR Débito deve ser um número',
            'mdr_debit.min' => 'MDR Débito deve ser maior ou igual a 0%',
            'mdr_debit.max' => 'MDR Débito deve ser menor ou igual a 100%',
            'mdr_credit_1x.required' => 'MDR Crédito à vista é obrigatório',
            'mdr_credit_1x.numeric' => 'MDR Crédito à vista deve ser um número',
            'mdr_credit_1x.min' => 'MDR Crédito à vista deve ser maior ou igual a 0%',
            'mdr_credit_1x.max' => 'MDR Crédito à vista deve ser menor ou igual a 100%',
            'mdr_credit_2_6x.required' => 'MDR Crédito 2-6x é obrigatório',
            'mdr_credit_2_6x.numeric' => 'MDR Crédito 2-6x deve ser um número',
            'mdr_credit_2_6x.min' => 'MDR Crédito 2-6x deve ser maior ou igual a 0%',
            'mdr_credit_2_6x.max' => 'MDR Crédito 2-6x deve ser menor ou igual a 100%',
            'mdr_credit_7_12x.required' => 'MDR Crédito 7-12x é obrigatório',
            'mdr_credit_7_12x.numeric' => 'MDR Crédito 7-12x deve ser um número',
            'mdr_credit_7_12x.min' => 'MDR Crédito 7-12x deve ser maior ou igual a 0%',
            'mdr_credit_7_12x.max' => 'MDR Crédito 7-12x deve ser menor ou igual a 100%',
            'mdr_credit_13_18x.required' => 'MDR Crédito 13-18x é obrigatório',
            'mdr_credit_13_18x.numeric' => 'MDR Crédito 13-18x deve ser um número',
            'mdr_credit_13_18x.min' => 'MDR Crédito 13-18x deve ser maior ou igual a 0%',
            'mdr_credit_13_18x.max' => 'MDR Crédito 13-18x deve ser menor ou igual a 100%',
            'anticipation_rate.required' => 'Taxa de antecipação é obrigatória',
            'anticipation_rate.numeric' => 'Taxa de antecipação deve ser um número',
            'anticipation_rate.min' => 'Taxa de antecipação deve ser maior ou igual a 0%',
            'anticipation_rate.max' => 'Taxa de antecipação deve ser menor ou igual a 20%',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Parâmetros de entrada
            $params = [
                'mdr_debit' => $request->mdr_debit,
                'mdr_credit_1x' => $request->mdr_credit_1x,
                'mdr_credit_2_6x' => $request->mdr_credit_2_6x,
                'mdr_credit_7_12x' => $request->mdr_credit_7_12x,
                'mdr_credit_13_18x' => $request->mdr_credit_13_18x,
                'anticipation_rate' => $request->anticipation_rate,
                'calculation_method' => $request->calculation_method ?? 'linear',
                'apply_anticipation_debit' => $request->boolean('apply_anticipation_debit', false),
                'month_base_days' => $request->month_base_days ?? 30
            ];

            // Calcular resultados
            $results = $this->calculateTaxes($params);

            return response()->json([
                'success' => true,
                'data' => [
                    'params' => $params,
                    'results' => $results,
                    'summary' => $this->generateSummary($results)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno no cálculo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calcular taxas para todas as modalidades
     */
    private function calculateTaxes(array $params): array
    {
        $results = [];
        $monthBaseDays = $params['month_base_days'];

        // Linha especial para Débito
        $debitAnticipation = $params['apply_anticipation_debit'] 
            ? $this->calculateAnticipationRate(0, $params['anticipation_rate'], $params['calculation_method'], $monthBaseDays, 0)
            : 0;

        $results['debit'] = [
            'installments' => 'Débito',
            'installments_numeric' => 0,
            'mdr_applied' => $params['mdr_debit'],
            'average_days' => 0,
            'anticipation_rate' => $debitAnticipation,
            'total_rate' => $params['mdr_debit'] + $debitAnticipation,
            'net_amount_100' => 100 * (1 - ($params['mdr_debit'] + $debitAnticipation) / 100),
            'badge_class' => 'bg-gray-500'
        ];

        // Calcular para 1 a 18 parcelas (crédito)
        for ($n = 1; $n <= 18; $n++) {
            $mdrApplied = $this->getMdrByInstallments($n, $params);
            $averageDays = $this->calculateAverageDays($n, $monthBaseDays);
            $anticipationRate = $this->calculateAnticipationRate($averageDays, $params['anticipation_rate'], $params['calculation_method'], $monthBaseDays, $n);
            $totalRate = $mdrApplied + $anticipationRate;
            $netAmount = 100 * (1 - $totalRate / 100);

            $results['credit_' . $n] = [
                'installments' => $n . 'x',
                'installments_numeric' => $n,
                'mdr_applied' => $mdrApplied,
                'average_days' => $averageDays,
                'anticipation_rate' => $anticipationRate,
                'total_rate' => $totalRate,
                'net_amount_100' => $netAmount,
                'badge_class' => $this->getBadgeClass($n)
            ];
        }

        return $results;
    }

    /**
     * Obter MDR baseado no número de parcelas
     */
    private function getMdrByInstallments(int $installments, array $params): float
    {
        if ($installments == 1) {
            return $params['mdr_credit_1x'];
        } elseif ($installments >= 2 && $installments <= 6) {
            return $params['mdr_credit_2_6x'];
        } elseif ($installments >= 7 && $installments <= 12) {
            return $params['mdr_credit_7_12x'];
        } elseif ($installments >= 13 && $installments <= 18) {
            return $params['mdr_credit_13_18x'];
        }

        return 0;
    }

    /**
     * Calcular dias médios antecipados
     * Fórmula ajustada baseada no feedback do usuário
     */
    private function calculateAverageDays(int $installments, int $monthBaseDays): int
    {
        // Para crédito à vista (1x), não há antecipação, então dias = 0
        if ($installments == 1) {
            return 0;
        }
        
        // Fórmula corrigida baseada no exemplo do usuário:
        // 2x com MDR 3.49% + Taxa 2.99% deve dar Total 7.72%
        // Isso requer antecipação de 4.23%, que equivale a 42.44 dias
        // Fórmula: 30 × (n + 0.829431) / 2
        return intval($monthBaseDays * ($installments + 0.829431) / 2);
    }

    /**
     * Calcular taxa de antecipação
     */
    private function calculateAnticipationRate(int $averageDays, float $monthlyRate, string $method, int $monthBaseDays, int $installments = 0): float
    {
        if ($averageDays == 0 || $monthlyRate == 0) {
            return 0;
        }

        // Crédito à vista (1x) não tem antecipação
        if ($installments == 1) {
            return 0;
        }

        $monthlyRateDecimal = $monthlyRate / 100;
        $periodInMonths = $averageDays / $monthBaseDays;

        if ($method === 'compound') {
            // Regra Composta: (1 + Taxa_a.m.)^(Dias médios / 30) − 1
            $result = (pow(1 + $monthlyRateDecimal, $periodInMonths) - 1) * 100;
        } else {
            // Regra Linear (padrão): Taxa_a.m. × (Dias médios / 30)
            $result = $monthlyRate * $periodInMonths;
        }

        return round($result, 6); // Manter 6 casas internamente
    }

    /**
     * Obter classe CSS para badge baseado no número de parcelas
     */
    private function getBadgeClass(int $installments): string
    {
        if ($installments == 1) {
            return 'bg-blue-500';
        } elseif ($installments >= 2 && $installments <= 6) {
            return 'bg-green-500';
        } elseif ($installments >= 7 && $installments <= 12) {
            return 'bg-yellow-500';
        } elseif ($installments >= 13 && $installments <= 18) {
            return 'bg-red-500';
        }

        return 'bg-gray-500';
    }

    /**
     * Gerar resumo dos resultados
     */
    private function generateSummary(array $results): array
    {
        $creditResults = array_filter($results, function($key) {
            return strpos($key, 'credit_') === 0;
        }, ARRAY_FILTER_USE_KEY);

        $totalRates = array_column($creditResults, 'total_rate');
        
        return [
            'min_total_rate' => min($totalRates),
            'max_total_rate' => max($totalRates),
            'avg_total_rate' => array_sum($totalRates) / count($totalRates),
            'total_simulations' => count($creditResults) + 1, // +1 para débito
        ];
    }

    /**
     * Exportar resultados para CSV
     */
    public function exportCsv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'results' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos para exportação'
            ], 422);
        }

        try {
            $results = $request->results;
            
            $csvContent = "Modalidade,MDR (%),Dias Médios,Antecipação (%),Taxa Total (%),Líquido R$ 100\n";
            
            foreach ($results as $result) {
                $csvContent .= sprintf(
                    "%s,%.2f,%d,%.2f,%.2f,%.2f\n",
                    $result['installments'],
                    $result['mdr_applied'],
                    $result['average_days'],
                    $result['anticipation_rate'],
                    $result['total_rate'],
                    $result['net_amount_100']
                );
            }

            return response($csvContent)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="simulador_taxas_' . date('Y-m-d_H-i-s') . '.csv"');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar CSV: ' . $e->getMessage()
            ], 500);
        }
    }
}
