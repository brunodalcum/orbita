<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrbitaOperacao;
use App\Models\WhiteLabel;
use App\Models\NodeBranding;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NodeManagementController extends Controller
{
    /**
     * Lista todos os nós que o usuário pode gerenciar
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $nodeType = $request->get('type', 'all');
        $search = $request->get('search', '');
        
        // Determinar quais nós o usuário pode ver
        $nodes = $this->getManageableNodes($user, $nodeType, $search);
        
        // Estatísticas
        $stats = $this->getNodesStatistics($user);
        
        return view('hierarchy.management.index', compact(
            'user',
            'nodes',
            'stats',
            'nodeType',
            'search'
        ));
    }

    /**
     * Formulário para criar novo nó
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $nodeType = $request->get('type');
        
        // Validar se o usuário pode criar este tipo de nó
        if (!$this->canCreateNodeType($user, $nodeType)) {
            abort(403, 'Você não tem permissão para criar este tipo de nó');
        }
        
        // Obter opções para o formulário
        $parentOptions = $this->getParentOptions($user, $nodeType);
        $availableModules = $this->getAvailableModules();
        
        return view('hierarchy.management.create', compact(
            'user',
            'nodeType',
            'parentOptions',
            'availableModules'
        ));
    }

    /**
     * Salvar novo nó
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $nodeType = $request->get('node_type');
        
        if (!$this->canCreateNodeType($user, $nodeType)) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para criar este tipo de nó'
            ], 403);
        }
        
        // Validação baseada no tipo de nó
        $validator = $this->getValidatorForNodeType($request, $nodeType);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            $node = $this->createNodeByType($request, $nodeType, $user);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Nó criado com sucesso!',
                'node' => $node,
                'redirect' => route('hierarchy.management.show', $node->id)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar nó: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exibir detalhes de um nó
     */
    public function show($id)
    {
        $user = Auth::user();
        $node = $this->findNodeById($id);
        
        if (!$node || !$this->canManageNode($user, $node)) {
            abort(404, 'Nó não encontrado ou sem permissão');
        }
        
        // Obter informações detalhadas
        $nodeDetails = $this->getNodeDetails($node);
        $children = $this->getNodeChildren($node);
        $metrics = $this->getNodeMetrics($node);
        $activities = $this->getNodeActivities($node);
        
        return view('hierarchy.management.show', compact(
            'user',
            'node',
            'nodeDetails',
            'children',
            'metrics',
            'activities'
        ));
    }

    /**
     * Formulário para editar nó
     */
    public function edit($id)
    {
        $user = Auth::user();
        $node = $this->findNodeById($id);
        
        if (!$node || !$this->canManageNode($user, $node)) {
            abort(404, 'Nó não encontrado ou sem permissão');
        }
        
        $parentOptions = $this->getParentOptions($user, $node->node_type ?? 'user', $node);
        $availableModules = $this->getAvailableModules();
        
        return view('hierarchy.management.edit', compact(
            'user',
            'node',
            'parentOptions',
            'availableModules'
        ));
    }

    /**
     * Atualizar nó
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $node = $this->findNodeById($id);
        
        if (!$node || !$this->canManageNode($user, $node)) {
            return response()->json([
                'success' => false,
                'message' => 'Nó não encontrado ou sem permissão'
            ], 404);
        }
        
        $nodeType = $node->node_type ?? ($node instanceof OrbitaOperacao ? 'operacao' : ($node instanceof WhiteLabel ? 'white_label' : 'user'));
        $validator = $this->getValidatorForNodeType($request, $nodeType, $node);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            $this->updateNodeByType($request, $node, $nodeType);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Nó atualizado com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar nó: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Alternar status ativo/inativo
     */
    public function toggleStatus($id)
    {
        $user = Auth::user();
        $node = $this->findNodeById($id);
        
        if (!$node || !$this->canManageNode($user, $node)) {
            return response()->json([
                'success' => false,
                'message' => 'Nó não encontrado ou sem permissão'
            ], 404);
        }
        
        try {
            $node->is_active = !$node->is_active;
            $node->save();
            
            return response()->json([
                'success' => true,
                'message' => $node->is_active ? 'Nó ativado com sucesso' : 'Nó desativado com sucesso',
                'is_active' => $node->is_active
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obter nós que o usuário pode gerenciar
     */
    private function getManageableNodes(User $user, $nodeType = 'all', $search = '')
    {
        $nodes = collect();
        
        if ($user->isSuperAdminNode()) {
            // Super Admin vê tudo
            if ($nodeType === 'all' || $nodeType === 'operacao') {
                $operacoes = OrbitaOperacao::when($search, function($q, $search) {
                    return $q->where('display_name', 'like', "%{$search}%")
                             ->orWhere('name', 'like', "%{$search}%");
                })->get();
                $nodes = $nodes->concat($operacoes);
            }
            
            if ($nodeType === 'all' || $nodeType === 'white_label') {
                $whiteLabels = WhiteLabel::with('orbitaOperacao')
                    ->when($search, function($q, $search) {
                        return $q->where('display_name', 'like', "%{$search}%")
                                 ->orWhere('name', 'like', "%{$search}%");
                    })->get();
                $nodes = $nodes->concat($whiteLabels);
            }
            
            if ($nodeType === 'all' || in_array($nodeType, ['licenciado_l1', 'licenciado_l2', 'licenciado_l3'])) {
                $licenciados = User::whereIn('node_type', ['licenciado_l1', 'licenciado_l2', 'licenciado_l3'])
                    ->when($nodeType !== 'all', function($q) use ($nodeType) {
                        return $q->where('node_type', $nodeType);
                    })
                    ->when($search, function($q, $search) {
                        return $q->where('name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->with(['orbitaOperacao', 'whiteLabel', 'parent'])
                    ->get();
                $nodes = $nodes->concat($licenciados);
            }
        } else {
            // Outros usuários veem apenas seus descendentes
            $descendants = $user->getAllDescendants();
            
            if ($nodeType !== 'all') {
                $descendants = $descendants->where('node_type', $nodeType);
            }
            
            if ($search) {
                $descendants = $descendants->filter(function($node) use ($search) {
                    return stripos($node->name, $search) !== false || 
                           stripos($node->email, $search) !== false;
                });
            }
            
            $nodes = $descendants;
        }
        
        return $nodes->sortBy('name');
    }

    /**
     * Verificar se pode criar tipo de nó
     */
    private function canCreateNodeType(User $user, $nodeType)
    {
        if ($user->isSuperAdminNode()) {
            return in_array($nodeType, ['operacao', 'white_label', 'licenciado_l1', 'licenciado_l2', 'licenciado_l3']);
        }
        
        if ($user->isOperacaoNode()) {
            return in_array($nodeType, ['white_label', 'licenciado_l1']);
        }
        
        if ($user->isWhiteLabelNode()) {
            return $nodeType === 'licenciado_l1';
        }
        
        if ($user->node_type === 'licenciado_l1') {
            return $nodeType === 'licenciado_l2';
        }
        
        if ($user->node_type === 'licenciado_l2') {
            return $nodeType === 'licenciado_l3';
        }
        
        return false;
    }

    /**
     * Criar nó baseado no tipo
     */
    private function createNodeByType(Request $request, $nodeType, User $creator)
    {
        switch ($nodeType) {
            case 'operacao':
                return $this->createOperacao($request, $creator);
            case 'white_label':
                return $this->createWhiteLabel($request, $creator);
            case 'licenciado_l1':
            case 'licenciado_l2':
            case 'licenciado_l3':
                return $this->createLicenciado($request, $nodeType, $creator);
            default:
                throw new \Exception('Tipo de nó inválido');
        }
    }

    /**
     * Criar Operação
     */
    private function createOperacao(Request $request, User $creator)
    {
        $operacao = OrbitaOperacao::create([
            'name' => Str::slug($request->name),
            'display_name' => $request->display_name,
            'description' => $request->description,
            'domain' => $request->domain,
            'subdomain' => $request->subdomain,
            'modules' => $request->modules ?? [],
            'settings' => $request->settings ?? [],
            'is_active' => $request->boolean('is_active', true)
        ]);
        
        // Criar branding se fornecido
        if ($request->has('branding')) {
            $this->createNodeBranding($operacao, 'operacao', $request->branding);
        }
        
        return $operacao;
    }

    /**
     * Criar White Label
     */
    private function createWhiteLabel(Request $request, User $creator)
    {
        $whiteLabel = WhiteLabel::create([
            'operacao_id' => $request->operacao_id,
            'name' => Str::slug($request->name),
            'display_name' => $request->display_name,
            'description' => $request->description,
            'domain' => $request->domain,
            'subdomain' => $request->subdomain,
            'modules' => $request->modules ?? [],
            'settings' => $request->settings ?? [],
            'is_active' => $request->boolean('is_active', true)
        ]);
        
        if ($request->has('branding')) {
            $this->createNodeBranding($whiteLabel, 'white_label', $request->branding);
        }
        
        return $whiteLabel;
    }

    /**
     * Criar Licenciado
     */
    private function createLicenciado(Request $request, $nodeType, User $creator)
    {
        // Calcular hierarchy_level e hierarchy_path
        $parent = null;
        if ($request->parent_id) {
            $parent = User::find($request->parent_id);
        }
        
        $hierarchyLevel = $parent ? $parent->hierarchy_level + 1 : 1;
        $hierarchyPath = $parent ? $parent->hierarchy_path . '/' . $parent->id : '';
        
        $licenciado = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password ?? Str::random(12)),
            'node_type' => $nodeType,
            'parent_id' => $request->parent_id,
            'hierarchy_level' => $hierarchyLevel,
            'hierarchy_path' => $hierarchyPath,
            'operacao_id' => $request->operacao_id,
            'white_label_id' => $request->white_label_id,
            'modules' => $request->modules ?? [],
            'node_settings' => $request->node_settings ?? [],
            'domain' => $request->domain,
            'subdomain' => $request->subdomain,
            'is_active' => $request->boolean('is_active', true)
        ]);
        
        if ($request->has('branding')) {
            $this->createNodeBranding($licenciado, 'user', $request->branding);
        }
        
        return $licenciado;
    }

    /**
     * Criar branding para nó
     */
    private function createNodeBranding($node, $nodeType, $brandingData)
    {
        if (empty($brandingData)) return;
        
        NodeBranding::create([
            'node_type' => $nodeType,
            'node_id' => $node->id,
            'primary_color' => $brandingData['primary_color'] ?? null,
            'secondary_color' => $brandingData['secondary_color'] ?? null,
            'accent_color' => $brandingData['accent_color'] ?? null,
            'text_color' => $brandingData['text_color'] ?? null,
            'background_color' => $brandingData['background_color'] ?? null,
            'font_family' => $brandingData['font_family'] ?? null,
            'custom_css' => $brandingData['custom_css'] ?? null,
            'inherit_from_parent' => $brandingData['inherit_from_parent'] ?? true
        ]);
    }

    /**
     * Obter validador para tipo de nó
     */
    private function getValidatorForNodeType(Request $request, $nodeType, $node = null)
    {
        $rules = [];
        
        switch ($nodeType) {
            case 'operacao':
                $rules = [
                    'name' => 'required|string|max:100|unique:orbita_operacaos,name' . ($node ? ',' . $node->id : ''),
                    'display_name' => 'required|string|max:255',
                    'description' => 'nullable|string|max:500',
                    'domain' => 'nullable|url',
                    'subdomain' => 'nullable|string|max:100',
                ];
                break;
                
            case 'white_label':
                $rules = [
                    'operacao_id' => 'required|exists:orbita_operacaos,id',
                    'name' => 'required|string|max:100|unique:white_labels,name' . ($node ? ',' . $node->id : ''),
                    'display_name' => 'required|string|max:255',
                    'description' => 'nullable|string|max:500',
                    'domain' => 'nullable|url',
                    'subdomain' => 'nullable|string|max:100',
                ];
                break;
                
            case 'licenciado_l1':
            case 'licenciado_l2':
            case 'licenciado_l3':
                $rules = [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email' . ($node ? ',' . $node->id : ''),
                    'password' => ($node ? 'nullable' : 'required') . '|string|min:8',
                    'parent_id' => 'nullable|exists:users,id',
                    'operacao_id' => 'nullable|exists:orbita_operacaos,id',
                    'white_label_id' => 'nullable|exists:white_labels,id',
                ];
                break;
        }
        
        return Validator::make($request->all(), $rules);
    }

    /**
     * Obter opções de pai para formulário
     */
    private function getParentOptions(User $user, $nodeType, $currentNode = null)
    {
        $options = [];
        
        switch ($nodeType) {
            case 'white_label':
                if ($user->isSuperAdminNode()) {
                    $options = OrbitaOperacao::where('is_active', true)->pluck('display_name', 'id');
                } elseif ($user->isOperacaoNode()) {
                    $options = [$user->orbitaOperacao->id => $user->orbitaOperacao->display_name];
                }
                break;
                
            case 'licenciado_l1':
                if ($user->isSuperAdminNode()) {
                    $operacoes = OrbitaOperacao::where('is_active', true)->get();
                    $whiteLabels = WhiteLabel::where('is_active', true)->get();
                    
                    foreach ($operacoes as $op) {
                        $options['operacao_' . $op->id] = 'Operação: ' . $op->display_name;
                    }
                    foreach ($whiteLabels as $wl) {
                        $options['white_label_' . $wl->id] = 'WL: ' . $wl->display_name;
                    }
                } elseif ($user->isOperacaoNode()) {
                    $options['operacao_' . $user->orbitaOperacao->id] = 'Operação: ' . $user->orbitaOperacao->display_name;
                    
                    foreach ($user->orbitaOperacao->whiteLabels as $wl) {
                        $options['white_label_' . $wl->id] = 'WL: ' . $wl->display_name;
                    }
                } elseif ($user->isWhiteLabelNode()) {
                    $options['white_label_' . $user->whiteLabel->id] = 'WL: ' . $user->whiteLabel->display_name;
                }
                break;
                
            case 'licenciado_l2':
                $l1Users = User::where('node_type', 'licenciado_l1')
                              ->where('is_active', true);
                              
                if (!$user->isSuperAdminNode()) {
                    $l1Users = $l1Users->where(function($q) use ($user) {
                        if ($user->isOperacaoNode()) {
                            $q->where('operacao_id', $user->orbitaOperacao->id);
                        } elseif ($user->isWhiteLabelNode()) {
                            $q->where('white_label_id', $user->whiteLabel->id);
                        } elseif ($user->node_type === 'licenciado_l1') {
                            $q->where('id', $user->id);
                        }
                    });
                }
                
                $options = $l1Users->pluck('name', 'id');
                break;
                
            case 'licenciado_l3':
                $l2Users = User::where('node_type', 'licenciado_l2')
                              ->where('is_active', true);
                              
                if (!$user->isSuperAdminNode()) {
                    $descendants = $user->getAllDescendants()->where('node_type', 'licenciado_l2');
                    $options = $descendants->pluck('name', 'id');
                } else {
                    $options = $l2Users->pluck('name', 'id');
                }
                break;
        }
        
        return $options;
    }

    /**
     * Encontrar nó por ID
     */
    private function findNodeById($id)
    {
        // Tentar encontrar em Users primeiro
        $user = User::find($id);
        if ($user && in_array($user->node_type, ['licenciado_l1', 'licenciado_l2', 'licenciado_l3'])) {
            return $user;
        }
        
        // Tentar em Operações
        $operacao = OrbitaOperacao::find($id);
        if ($operacao) {
            return $operacao;
        }
        
        // Tentar em White Labels
        $whiteLabel = WhiteLabel::find($id);
        if ($whiteLabel) {
            return $whiteLabel;
        }
        
        return null;
    }

    /**
     * Verificar se pode gerenciar nó
     */
    private function canManageNode(User $user, $node)
    {
        if ($user->isSuperAdminNode()) {
            return true;
        }
        
        // Verificar se o nó está na hierarquia descendente do usuário
        if ($node instanceof User) {
            return $user->getAllDescendants()->contains('id', $node->id);
        }
        
        if ($node instanceof OrbitaOperacao) {
            return $user->isOperacaoNode() && $user->orbitaOperacao->id === $node->id;
        }
        
        if ($node instanceof WhiteLabel) {
            return ($user->isOperacaoNode() && $user->orbitaOperacao->id === $node->operacao_id) ||
                   ($user->isWhiteLabelNode() && $user->whiteLabel->id === $node->id);
        }
        
        return false;
    }

    /**
     * Obter estatísticas dos nós
     */
    private function getNodesStatistics(User $user)
    {
        $stats = [
            'total_operacoes' => 0,
            'total_white_labels' => 0,
            'total_licenciados_l1' => 0,
            'total_licenciados_l2' => 0,
            'total_licenciados_l3' => 0,
            'active_nodes' => 0,
            'inactive_nodes' => 0
        ];
        
        if ($user->isSuperAdminNode()) {
            $stats['total_operacoes'] = OrbitaOperacao::count();
            $stats['total_white_labels'] = WhiteLabel::count();
            $stats['total_licenciados_l1'] = User::where('node_type', 'licenciado_l1')->count();
            $stats['total_licenciados_l2'] = User::where('node_type', 'licenciado_l2')->count();
            $stats['total_licenciados_l3'] = User::where('node_type', 'licenciado_l3')->count();
            $stats['active_nodes'] = OrbitaOperacao::where('is_active', true)->count() + 
                                    WhiteLabel::where('is_active', true)->count() + 
                                    User::whereIn('node_type', ['licenciado_l1', 'licenciado_l2', 'licenciado_l3'])
                                        ->where('is_active', true)->count();
        } else {
            $descendants = $user->getAllDescendants();
            $stats['total_licenciados_l1'] = $descendants->where('node_type', 'licenciado_l1')->count();
            $stats['total_licenciados_l2'] = $descendants->where('node_type', 'licenciado_l2')->count();
            $stats['total_licenciados_l3'] = $descendants->where('node_type', 'licenciado_l3')->count();
            $stats['active_nodes'] = $descendants->where('is_active', true)->count();
            $stats['inactive_nodes'] = $descendants->where('is_active', false)->count();
        }
        
        return $stats;
    }

    /**
     * Obter módulos disponíveis
     */
    private function getAvailableModules()
    {
        return [
            'pix' => 'PIX',
            'bolepix' => 'BolePix',
            'gateway' => 'Gateway',
            'gerenpix' => 'GerenPix',
            'contraPDV' => 'ContraPDV',
            'agenda' => 'Agenda',
            'leads' => 'Leads',
            'contracts' => 'Contratos'
        ];
    }

    /**
     * Obter detalhes do nó
     */
    private function getNodeDetails($node)
    {
        $details = [
            'type' => $node instanceof OrbitaOperacao ? 'operacao' : 
                     ($node instanceof WhiteLabel ? 'white_label' : $node->node_type),
            'created_at' => $node->created_at,
            'updated_at' => $node->updated_at,
            'is_active' => $node->is_active
        ];
        
        if ($node instanceof User) {
            $details['hierarchy_level'] = $node->hierarchy_level;
            $details['hierarchy_path'] = $node->hierarchy_path;
            $details['email'] = $node->email;
        }
        
        return $details;
    }

    /**
     * Obter filhos do nó
     */
    private function getNodeChildren($node)
    {
        if ($node instanceof User) {
            return $node->children()->get();
        }
        
        if ($node instanceof OrbitaOperacao) {
            $children = collect();
            $children = $children->concat($node->whiteLabels);
            $children = $children->concat($node->users);
            return $children;
        }
        
        if ($node instanceof WhiteLabel) {
            return $node->users;
        }
        
        return collect();
    }

    /**
     * Obter métricas do nó
     */
    private function getNodeMetrics($node)
    {
        return [
            'total_descendants' => method_exists($node, 'getAllDescendants') ? $node->getAllDescendants()->count() : 0,
            'direct_children' => $this->getNodeChildren($node)->count(),
            'active_modules' => is_array($node->modules ?? []) ? count(array_filter($node->modules ?? [], function($m) { return $m['enabled'] ?? false; })) : 0
        ];
    }

    /**
     * Obter atividades do nó
     */
    private function getNodeActivities($node)
    {
        // Por enquanto, atividades simuladas
        return [
            [
                'type' => 'created',
                'description' => 'Nó criado',
                'timestamp' => $node->created_at,
                'user' => 'Sistema'
            ],
            [
                'type' => 'updated',
                'description' => 'Última atualização',
                'timestamp' => $node->updated_at,
                'user' => 'Admin'
            ]
        ];
    }

    /**
     * Atualizar nó por tipo
     */
    private function updateNodeByType(Request $request, $node, $nodeType)
    {
        switch ($nodeType) {
            case 'operacao':
                $node->update([
                    'display_name' => $request->display_name,
                    'description' => $request->description,
                    'domain' => $request->domain,
                    'subdomain' => $request->subdomain,
                    'modules' => $request->modules ?? $node->modules,
                    'settings' => $request->settings ?? $node->settings,
                ]);
                break;
                
            case 'white_label':
                $node->update([
                    'operacao_id' => $request->operacao_id,
                    'display_name' => $request->display_name,
                    'description' => $request->description,
                    'domain' => $request->domain,
                    'subdomain' => $request->subdomain,
                    'modules' => $request->modules ?? $node->modules,
                    'settings' => $request->settings ?? $node->settings,
                ]);
                break;
                
            default:
                $updateData = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'operacao_id' => $request->operacao_id,
                    'white_label_id' => $request->white_label_id,
                    'modules' => $request->modules ?? $node->modules,
                    'node_settings' => $request->node_settings ?? $node->node_settings,
                    'domain' => $request->domain,
                    'subdomain' => $request->subdomain,
                ];
                
                if ($request->filled('password')) {
                    $updateData['password'] = Hash::make($request->password);
                }
                
                $node->update($updateData);
                break;
        }
    }
}