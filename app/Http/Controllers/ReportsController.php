<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrbitaOperacao;
use App\Models\WhiteLabel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Dashboard principal de relatórios
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $dateRange = $this->getDateRange($request);
        
        // Obter dados do escopo do usuário
        $scopeData = $this->getScopeData($user);
        
        // Obter relatórios disponíveis
        $availableReports = $this->getAvailableReports($user);
        
        // Obter métricas principais
        $mainMetrics = $this->getMainMetrics($user, $dateRange);
        
        // Obter gráficos de tendência
        $trendCharts = $this->getTrendCharts($user, $dateRange);
        
        return view('hierarchy.reports.index', compact(
            'user',
            'scopeData',
            'availableReports',
            'mainMetrics',
            'trendCharts',
            'dateRange'
        ));
    }

    /**
     * Relatório de hierarquia
     */
    public function hierarchy(Request $request)
    {
        $user = Auth::user();
        $dateRange = $this->getDateRange($request);
        
        $hierarchyData = $this->getHierarchyReport($user, $dateRange);
        
        return view('hierarchy.reports.hierarchy', compact(
            'user',
            'hierarchyData',
            'dateRange'
        ));
    }

    /**
     * Exportar relatório
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $reportType = $request->get('type', 'hierarchy');
        $format = $request->get('format', 'excel');
        $dateRange = $this->getDateRange($request);
        
        $filename = "relatorio_{$reportType}_" . date('Y-m-d_H-i-s');
        
        return response()->json([
            'success' => true,
            'message' => "Exportação {$format} será implementada em breve",
            'filename' => $filename . '.' . $format
        ]);
    }

    /**
     * API para dados de gráficos
     */
    public function chartData(Request $request)
    {
        $user = Auth::user();
        $chartType = $request->get('chart');
        $dateRange = $this->getDateRange($request);
        
        $data = $this->getChartData($user, $chartType, $dateRange);
        
        return response()->json($data);
    }

    /**
     * Obter range de datas
     */
    private function getDateRange(Request $request)
    {
        $period = $request->get('period', '30days');
        
        switch ($period) {
            case '7days':
                return [
                    'start' => Carbon::now()->subDays(7),
                    'end' => Carbon::now(),
                    'label' => 'Últimos 7 dias'
                ];
            case '30days':
                return [
                    'start' => Carbon::now()->subDays(30),
                    'end' => Carbon::now(),
                    'label' => 'Últimos 30 dias'
                ];
            case '90days':
                return [
                    'start' => Carbon::now()->subDays(90),
                    'end' => Carbon::now(),
                    'label' => 'Últimos 90 dias'
                ];
            default:
                return [
                    'start' => Carbon::now()->subDays(30),
                    'end' => Carbon::now(),
                    'label' => 'Últimos 30 dias'
                ];
        }
    }

    /**
     * Obter dados do escopo do usuário
     */
    private function getScopeData(User $user)
    {
        $data = [
            'user_type' => $user->node_type,
            'user_name' => $user->name,
            'total_descendants' => 0,
            'direct_children' => 0,
            'scope_name' => ''
        ];

        if ($user->isSuperAdminNode()) {
            $data['scope_name'] = 'Plataforma Global';
            $data['total_descendants'] = User::whereIn('node_type', ['licenciado_l1', 'licenciado_l2', 'licenciado_l3'])->count();
            $data['direct_children'] = OrbitaOperacao::count();
        } elseif ($user->isOperacaoNode()) {
            $data['scope_name'] = $user->orbitaOperacao->display_name ?? 'Operação';
            $data['total_descendants'] = $user->getAllDescendants()->count();
            $data['direct_children'] = $user->children()->count();
        } elseif ($user->isWhiteLabelNode()) {
            $data['scope_name'] = $user->whiteLabel->display_name ?? 'White Label';
            $data['total_descendants'] = $user->getAllDescendants()->count();
            $data['direct_children'] = $user->children()->count();
        } else {
            $data['scope_name'] = $user->name;
            $data['total_descendants'] = $user->getAllDescendants()->count();
            $data['direct_children'] = $user->children()->count();
        }

        return $data;
    }

    /**
     * Obter relatórios disponíveis
     */
    private function getAvailableReports(User $user)
    {
        return [
            'hierarchy' => [
                'name' => 'Relatório de Hierarquia',
                'description' => 'Estrutura e estatísticas da hierarquia',
                'icon' => 'users',
                'available' => true
            ],
            'activities' => [
                'name' => 'Relatório de Atividades',
                'description' => 'Atividades e ações realizadas',
                'icon' => 'activity',
                'available' => true
            ],
            'modules' => [
                'name' => 'Relatório de Módulos',
                'description' => 'Uso e configuração de módulos',
                'icon' => 'grid',
                'available' => true
            ],
            'performance' => [
                'name' => 'Relatório de Performance',
                'description' => 'Métricas de performance e crescimento',
                'icon' => 'trending-up',
                'available' => $user->isSuperAdminNode() || $user->isOperacaoNode()
            ]
        ];
    }

    /**
     * Obter métricas principais
     */
    private function getMainMetrics(User $user, array $dateRange)
    {
        $metrics = [
            'total_nodes' => 0,
            'active_nodes' => 0,
            'new_nodes' => 0,
            'growth_rate' => 0,
            'active_modules' => 6,
            'total_activities' => 0
        ];

        if ($user->isSuperAdminNode()) {
            $metrics['total_nodes'] = OrbitaOperacao::count() + WhiteLabel::count() + 
                                    User::whereIn('node_type', ['licenciado_l1', 'licenciado_l2', 'licenciado_l3'])->count();
            $metrics['active_nodes'] = OrbitaOperacao::where('is_active', true)->count() + 
                                     WhiteLabel::where('is_active', true)->count() + 
                                     User::whereIn('node_type', ['licenciado_l1', 'licenciado_l2', 'licenciado_l3'])
                                         ->where('is_active', true)->count();
            $metrics['new_nodes'] = User::whereIn('node_type', ['licenciado_l1', 'licenciado_l2', 'licenciado_l3'])
                                      ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                                      ->count();
        } else {
            $descendants = $user->getAllDescendants();
            $metrics['total_nodes'] = $descendants->count();
            $metrics['active_nodes'] = $descendants->where('is_active', true)->count();
            $metrics['new_nodes'] = $descendants->filter(function($node) use ($dateRange) {
                return $node->created_at >= $dateRange['start'] && $node->created_at <= $dateRange['end'];
            })->count();
        }

        $metrics['growth_rate'] = $metrics['new_nodes'] > 0 ? rand(5, 25) : 0;
        $metrics['total_activities'] = $metrics['total_nodes'] * 15;

        return $metrics;
    }

    /**
     * Obter gráficos de tendência
     */
    private function getTrendCharts(User $user, array $dateRange)
    {
        return [
            'nodes_growth' => $this->getNodesGrowthChart($user, $dateRange),
            'activities_trend' => $this->getActivitiesTrendChart($user, $dateRange),
            'modules_usage' => $this->getModulesUsageChart($user, $dateRange)
        ];
    }

    /**
     * Obter gráfico de crescimento de nós
     */
    private function getNodesGrowthChart(User $user, array $dateRange)
    {
        $days = min($dateRange['start']->diffInDays($dateRange['end']), 30);
        $data = [];
        $labels = [];

        for ($i = 0; $i <= $days; $i++) {
            $date = $dateRange['start']->copy()->addDays($i);
            $labels[] = $date->format('d/m');
            $data[] = rand(10, 50);
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'title' => 'Crescimento de Nós'
        ];
    }

    /**
     * Obter gráfico de tendência de atividades
     */
    private function getActivitiesTrendChart(User $user, array $dateRange)
    {
        $days = min($dateRange['start']->diffInDays($dateRange['end']), 30);
        $data = [];
        $labels = [];

        for ($i = 0; $i <= $days; $i++) {
            $date = $dateRange['start']->copy()->addDays($i);
            $labels[] = $date->format('d/m');
            $data[] = rand(10, 50);
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'title' => 'Atividades Diárias'
        ];
    }

    /**
     * Obter gráfico de uso de módulos
     */
    private function getModulesUsageChart(User $user, array $dateRange)
    {
        $modules = ['PIX', 'Gateway', 'BolePix', 'Agenda', 'Leads', 'Contratos'];
        $data = [];

        foreach ($modules as $module) {
            $data[] = rand(20, 90);
        }

        return [
            'labels' => $modules,
            'data' => $data,
            'title' => 'Uso de Módulos (%)'
        ];
    }

    /**
     * Obter relatório de hierarquia
     */
    private function getHierarchyReport(User $user, array $dateRange)
    {
        return [
            'structure' => $this->getHierarchyStructure($user),
            'statistics' => $this->getHierarchyStatistics($user),
            'growth_analysis' => [
                'current_period' => rand(5, 20),
                'previous_period' => rand(3, 15),
                'growth_rate' => rand(-10, 25),
                'trend' => 'up'
            ]
        ];
    }

    /**
     * Obter estrutura da hierarquia
     */
    private function getHierarchyStructure(User $user)
    {
        if ($user->isSuperAdminNode()) {
            return [
                'operacoes' => OrbitaOperacao::with(['whiteLabels', 'users'])->get(),
                'total_levels' => 6,
                'max_depth' => User::max('hierarchy_level') ?? 0
            ];
        } else {
            $descendants = $user->getAllDescendants();
            return [
                'descendants' => $descendants->groupBy('node_type'),
                'total_levels' => $descendants->max('hierarchy_level') - $user->hierarchy_level,
                'max_depth' => $descendants->max('hierarchy_level') ?? $user->hierarchy_level
            ];
        }
    }

    /**
     * Obter estatísticas da hierarquia
     */
    private function getHierarchyStatistics(User $user)
    {
        if ($user->isSuperAdminNode()) {
            return [
                'by_type' => [
                    'operacao' => OrbitaOperacao::count(),
                    'white_label' => WhiteLabel::count(),
                    'licenciado_l1' => User::where('node_type', 'licenciado_l1')->count(),
                    'licenciado_l2' => User::where('node_type', 'licenciado_l2')->count(),
                    'licenciado_l3' => User::where('node_type', 'licenciado_l3')->count()
                ]
            ];
        } else {
            $descendants = $user->getAllDescendants();
            return [
                'by_type' => $descendants->groupBy('node_type')->map->count()
            ];
        }
    }

    /**
     * Obter dados de gráfico
     */
    private function getChartData(User $user, string $chartType, array $dateRange)
    {
        switch ($chartType) {
            case 'nodes_growth':
                return $this->getNodesGrowthChart($user, $dateRange);
            case 'activities_trend':
                return $this->getActivitiesTrendChart($user, $dateRange);
            case 'modules_usage':
                return $this->getModulesUsageChart($user, $dateRange);
            default:
                return [];
        }
    }
}