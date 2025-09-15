<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrbitaOperacao;
use App\Models\WhiteLabel;
use App\Models\NodeBranding;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HierarchyDashboardController extends Controller
{
    /**
     * Dashboard principal da hierarquia
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $context = $this->determineUserContext($user);
        
        // Obter métricas do dashboard
        $metrics = $this->getDashboardMetrics($user, $context);
        
        // Obter atividades recentes
        $recentActivities = $this->getRecentActivities($user, $context);
        
        // Obter estrutura resumida da hierarquia
        $hierarchySummary = $this->getHierarchySummary($user, $context);
        
        // Obter módulos e status
        $modulesStatus = $this->getModulesStatus($user, $context);
        
        return view('hierarchy.dashboard', compact(
            'user',
            'context',
            'metrics',
            'recentActivities',
            'hierarchySummary',
            'modulesStatus'
        ));
    }

    /**
     * API para métricas em tempo real
     */
    public function metricsApi(Request $request)
    {
        $user = Auth::user();
        $context = $this->determineUserContext($user);
        $metrics = $this->getDashboardMetrics($user, $context);
        
        return response()->json($metrics);
    }

    /**
     * API para atividades recentes
     */
    public function activitiesApi(Request $request)
    {
        $user = Auth::user();
        $context = $this->determineUserContext($user);
        $activities = $this->getRecentActivities($user, $context, $request->get('limit', 10));
        
        return response()->json($activities);
    }

    /**
     * Visualização em árvore da hierarquia
     */
    public function tree(Request $request)
    {
        // Redirecionar para o controller específico da árvore
        return redirect()->route('hierarchy.tree.index');
    }

    /**
     * Determinar contexto do usuário
     */
    private function determineUserContext(User $user): array
    {
        if ($user->isSuperAdminNode()) {
            return [
                'type' => 'super_admin',
                'level' => 'platform',
                'name' => 'Órbita Platform',
                'scope' => 'global',
                'entity' => null
            ];
        }

        if ($user->isOperacaoNode()) {
            return [
                'type' => 'operacao',
                'level' => 'operacao',
                'name' => $user->orbitaOperacao->display_name ?? 'Operação',
                'scope' => 'operacao',
                'entity' => $user->orbitaOperacao
            ];
        }

        if ($user->isWhiteLabelNode()) {
            return [
                'type' => 'white_label',
                'level' => 'white_label',
                'name' => $user->whiteLabel->display_name ?? 'White Label',
                'scope' => 'white_label',
                'entity' => $user->whiteLabel
            ];
        }

        if ($user->isLicenciadoNode()) {
            return [
                'type' => 'licenciado',
                'level' => $user->node_type,
                'name' => $user->name,
                'scope' => 'licenciado',
                'entity' => $user
            ];
        }

        return [
            'type' => 'unknown',
            'level' => 'user',
            'name' => $user->name,
            'scope' => 'user',
            'entity' => $user
        ];
    }

    /**
     * Obter métricas do dashboard
     */
    private function getDashboardMetrics(User $user, array $context): array
    {
        $metrics = [
            'total_nodes' => 0,
            'active_nodes' => 0,
            'total_descendants' => 0,
            'active_modules' => 0,
            'hierarchy_depth' => 0,
            'recent_growth' => 0,
            'performance_score' => 0
        ];

        switch ($context['type']) {
            case 'super_admin':
                $metrics = $this->getSuperAdminMetrics();
                break;
                
            case 'operacao':
                $metrics = $this->getOperacaoMetrics($context['entity']);
                break;
                
            case 'white_label':
                $metrics = $this->getWhiteLabelMetrics($context['entity']);
                break;
                
            case 'licenciado':
                $metrics = $this->getLicenciadoMetrics($user);
                break;
        }

        return $metrics;
    }

    /**
     * Métricas para Super Admin
     */
    private function getSuperAdminMetrics(): array
    {
        $totalOperacoes = OrbitaOperacao::count();
        $totalWhiteLabels = WhiteLabel::count();
        $totalUsers = User::whereIn('node_type', ['licenciado_l1', 'licenciado_l2', 'licenciado_l3'])->count();
        $activeUsers = User::whereIn('node_type', ['licenciado_l1', 'licenciado_l2', 'licenciado_l3'])
                          ->where('is_active', true)->count();

        // Crescimento recente (últimos 30 dias)
        $recentGrowth = User::whereIn('node_type', ['licenciado_l1', 'licenciado_l2', 'licenciado_l3'])
                           ->where('created_at', '>=', Carbon::now()->subDays(30))
                           ->count();

        return [
            'total_nodes' => $totalOperacoes + $totalWhiteLabels + $totalUsers,
            'active_nodes' => $activeUsers,
            'total_descendants' => $totalUsers,
            'active_modules' => 8, // Todos os módulos disponíveis
            'hierarchy_depth' => 6, // Super Admin -> Operação -> WL -> L1 -> L2 -> L3
            'recent_growth' => $recentGrowth,
            'performance_score' => $this->calculatePerformanceScore($activeUsers, $totalUsers),
            'operacoes' => $totalOperacoes,
            'white_labels' => $totalWhiteLabels,
            'licenciados_l1' => User::where('node_type', 'licenciado_l1')->count(),
            'licenciados_l2' => User::where('node_type', 'licenciado_l2')->count(),
            'licenciados_l3' => User::where('node_type', 'licenciado_l3')->count(),
        ];
    }

    /**
     * Métricas para Operação
     */
    private function getOperacaoMetrics(OrbitaOperacao $operacao): array
    {
        $descendants = $operacao->getAllDescendants();
        $activeDescendants = $descendants->where('is_active', true);
        $whiteLabels = $operacao->whiteLabels()->count();
        
        $recentGrowth = User::where('operacao_id', $operacao->id)
                           ->where('created_at', '>=', Carbon::now()->subDays(30))
                           ->count();

        $activeModules = collect($operacao->modules ?? [])->where('enabled', true)->count();

        return [
            'total_nodes' => $descendants->count() + $whiteLabels,
            'active_nodes' => $activeDescendants->count(),
            'total_descendants' => $descendants->count(),
            'active_modules' => $activeModules,
            'hierarchy_depth' => $descendants->max('hierarchy_level') ?? 0,
            'recent_growth' => $recentGrowth,
            'performance_score' => $this->calculatePerformanceScore($activeDescendants->count(), $descendants->count()),
            'white_labels' => $whiteLabels,
            'direct_licenciados' => $operacao->licenciadosL1()->count(),
        ];
    }

    /**
     * Métricas para White Label
     */
    private function getWhiteLabelMetrics(WhiteLabel $whiteLabel): array
    {
        $descendants = $whiteLabel->getAllDescendants();
        $activeDescendants = $descendants->where('is_active', true);
        
        $recentGrowth = User::where('white_label_id', $whiteLabel->id)
                           ->where('created_at', '>=', Carbon::now()->subDays(30))
                           ->count();

        $activeModules = collect($whiteLabel->modules ?? [])->where('enabled', true)->count();
        if ($activeModules === 0) {
            // Se não tem módulos próprios, herda da operação
            $activeModules = collect($whiteLabel->operacao->modules ?? [])->where('enabled', true)->count();
        }

        return [
            'total_nodes' => $descendants->count(),
            'active_nodes' => $activeDescendants->count(),
            'total_descendants' => $descendants->count(),
            'active_modules' => $activeModules,
            'hierarchy_depth' => $descendants->max('hierarchy_level') ?? 0,
            'recent_growth' => $recentGrowth,
            'performance_score' => $this->calculatePerformanceScore($activeDescendants->count(), $descendants->count()),
            'licenciados_l1' => $whiteLabel->licenciadosL1()->count(),
        ];
    }

    /**
     * Métricas para Licenciado
     */
    private function getLicenciadoMetrics(User $licenciado): array
    {
        $descendants = $licenciado->getAllDescendants();
        $activeDescendants = $descendants->where('is_active', true);
        
        $recentGrowth = User::where('parent_id', $licenciado->id)
                           ->where('created_at', '>=', Carbon::now()->subDays(30))
                           ->count();

        $activeModules = 0;
        $modules = $licenciado->modules ?? [];
        foreach ($modules as $module) {
            if (isset($module['enabled']) && $module['enabled']) {
                $activeModules++;
            }
        }

        return [
            'total_nodes' => $descendants->count(),
            'active_nodes' => $activeDescendants->count(),
            'total_descendants' => $descendants->count(),
            'active_modules' => $activeModules,
            'hierarchy_depth' => $descendants->max('hierarchy_level') ?? $licenciado->hierarchy_level,
            'recent_growth' => $recentGrowth,
            'performance_score' => $this->calculatePerformanceScore($activeDescendants->count(), $descendants->count()),
            'direct_children' => $licenciado->children()->count(),
            'can_have_children' => $licenciado->canHaveChildren(),
        ];
    }

    /**
     * Calcular score de performance
     */
    private function calculatePerformanceScore(int $active, int $total): int
    {
        if ($total === 0) return 100;
        return min(100, round(($active / $total) * 100));
    }

    /**
     * Obter atividades recentes
     */
    private function getRecentActivities(User $user, array $context, int $limit = 5): array
    {
        // Por enquanto, atividades simuladas
        // Em produção, seria baseado em logs de auditoria
        $activities = [];
        
        $baseActivities = [
            [
                'type' => 'user_created',
                'title' => 'Novo licenciado criado',
                'description' => 'Licenciado L2 foi adicionado à hierarquia',
                'user' => 'Sistema',
                'timestamp' => Carbon::now()->subMinutes(15),
                'icon' => 'user-plus',
                'color' => 'green'
            ],
            [
                'type' => 'module_enabled',
                'title' => 'Módulo ativado',
                'description' => 'Gateway foi habilitado para White Label Alpha',
                'user' => $user->name,
                'timestamp' => Carbon::now()->subHours(2),
                'icon' => 'toggle-right',
                'color' => 'blue'
            ],
            [
                'type' => 'branding_updated',
                'title' => 'Branding atualizado',
                'description' => 'Logo e cores foram modificados',
                'user' => $user->name,
                'timestamp' => Carbon::now()->subHours(4),
                'icon' => 'palette',
                'color' => 'purple'
            ],
            [
                'type' => 'impersonation',
                'title' => 'Impersonação realizada',
                'description' => 'Usuário foi impersonado para suporte',
                'user' => 'Admin',
                'timestamp' => Carbon::now()->subHours(6),
                'icon' => 'user-check',
                'color' => 'orange'
            ],
            [
                'type' => 'report_generated',
                'title' => 'Relatório gerado',
                'description' => 'Relatório mensal da hierarquia foi criado',
                'user' => 'Sistema',
                'timestamp' => Carbon::now()->subDay(),
                'icon' => 'file-text',
                'color' => 'gray'
            ]
        ];

        return array_slice($baseActivities, 0, $limit);
    }

    /**
     * Obter resumo da hierarquia
     */
    private function getHierarchySummary(User $user, array $context): array
    {
        $summary = [
            'current_node' => [
                'name' => $context['name'],
                'type' => $context['type'],
                'level' => $user->hierarchy_level ?? 1
            ],
            'path' => [],
            'children_preview' => [],
            'siblings_count' => 0
        ];

        if ($user->hierarchy_path) {
            $pathIds = explode('/', $user->hierarchy_path);
            $pathUsers = User::whereIn('id', $pathIds)->get()->keyBy('id');
            
            foreach ($pathIds as $id) {
                if (isset($pathUsers[$id])) {
                    $pathUser = $pathUsers[$id];
                    $summary['path'][] = [
                        'id' => $pathUser->id,
                        'name' => $pathUser->name,
                        'type' => $pathUser->node_type
                    ];
                }
            }
        }

        // Preview dos filhos diretos
        $children = $user->children()->limit(5)->get();
        foreach ($children as $child) {
            $summary['children_preview'][] = [
                'id' => $child->id,
                'name' => $child->name,
                'type' => $child->node_type,
                'is_active' => $child->is_active
            ];
        }

        // Contar irmãos
        if ($user->parent_id) {
            $summary['siblings_count'] = User::where('parent_id', $user->parent_id)
                                            ->where('id', '!=', $user->id)
                                            ->count();
        }

        return $summary;
    }

    /**
     * Obter status dos módulos
     */
    private function getModulesStatus(User $user, array $context): array
    {
        $allModules = [
            'pix' => 'PIX',
            'bolepix' => 'BolePix',
            'gateway' => 'Gateway',
            'gerenpix' => 'GerenPix',
            'contraPDV' => 'ContraPDV',
            'agenda' => 'Agenda',
            'leads' => 'Leads',
            'contracts' => 'Contratos'
        ];

        $modulesStatus = [];

        foreach ($allModules as $key => $name) {
            $status = [
                'name' => $name,
                'key' => $key,
                'enabled' => false,
                'inherited' => false,
                'source' => null
            ];

            // Verificar se o usuário tem acesso ao módulo
            if (method_exists($user, 'hasModuleAccess')) {
                $status['enabled'] = $user->hasModuleAccess($key);
                
                // Determinar a fonte (local, white_label, operacao, parent)
                $userModules = $user->modules ?? [];
                if (isset($userModules[$key])) {
                    $status['source'] = 'local';
                    $status['inherited'] = false;
                } else {
                    $status['inherited'] = true;
                    if ($user->whiteLabel && $user->whiteLabel->hasModule($key)) {
                        $status['source'] = 'white_label';
                    } elseif ($user->orbitaOperacao && $user->orbitaOperacao->hasModule($key)) {
                        $status['source'] = 'operacao';
                    } elseif ($user->parent && $user->parent->hasModuleAccess($key)) {
                        $status['source'] = 'parent';
                    }
                }
            }

            $modulesStatus[] = $status;
        }

        return $modulesStatus;
    }
}