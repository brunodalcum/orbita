<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrbitaOperacao;
use App\Models\WhiteLabel;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class HierarchyManagementController extends Controller
{
    /**
     * Listar nós da hierarquia
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Obter parâmetros de filtro
        $search = $request->get('search', '');
        $nodeType = $request->get('type', '');
        
        // Obter nós baseados no contexto do usuário
        $nodes = $this->getNodesForUser($user);
        
        // Obter estatísticas
        $stats = $this->getHierarchyStats($user);
        
        return view('hierarchy.management.index', compact('user', 'nodes', 'stats', 'search', 'nodeType'));
    }

    /**
     * Mostrar detalhes de um nó
     */
    public function show(Request $request, $id)
    {
        $user = Auth::user();
        $node = User::findOrFail($id);
        
        // Verificar permissão para visualizar este nó
        if (!$this->canViewNode($user, $node)) {
            abort(403, 'Você não tem permissão para visualizar este nó');
        }
        
        // Buscar dados específicos do tipo de nó
        $nodeDetails = $this->getNodeDetails($node);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'node' => $node,
                'details' => $nodeDetails
            ]);
        }
        
        return view('hierarchy.management.show', compact('user', 'node', 'nodeDetails'));
    }

    /**
     * Mostrar formulário de edição de um nó
     */
    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        $node = User::findOrFail($id);
        
        // Verificar permissão para editar este nó
        if (!$this->canEditNode($user, $node)) {
            abort(403, 'Você não tem permissão para editar este nó');
        }
        
        // Buscar dados necessários para o formulário
        $availableRoles = Role::where('is_active', true)->get();
        $availableModules = $this->getAvailableModules();
        $availableOperacoes = \App\Models\OrbitaOperacao::where('is_active', true)->orderBy('name')->get();
        $availableWhiteLabels = User::where('node_type', 'white_label')->where('is_active', true)->orderBy('name')->get();
        
        // Buscar dados específicos do nó
        $nodeDetails = $this->getNodeDetails($node);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'node' => $node,
                'details' => $nodeDetails,
                'availableOperacoes' => $availableOperacoes,
                'availableWhiteLabels' => $availableWhiteLabels,
                'availableModules' => $availableModules
            ]);
        }
        
        return view('hierarchy.management.edit', compact('user', 'node', 'nodeDetails', 'availableRoles', 'availableModules', 'availableOperacoes', 'availableWhiteLabels'));
    }

    /**
     * Atualizar um nó
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $node = User::findOrFail($id);
        
        // Verificar permissão para editar este nó
        if (!$this->canEditNode($user, $node)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Você não tem permissão para editar este nó'], 403);
            }
            abort(403, 'Você não tem permissão para editar este nó');
        }
        
        try {
            // Validações básicas (excluindo email se não mudou)
            $rules = [
                'user_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8|confirmed',
                'name' => 'nullable|string|max:255',
                'display_name' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'description' => 'nullable|string',
                'domain' => 'nullable|string|max:255',
                'subdomain' => 'nullable|string|max:255',
                'is_active' => 'boolean',
                'modules' => 'nullable|array',
            ];
            
            $validatedData = $request->validate($rules);
            
            // Atualizar dados do usuário
            $updateData = [
                'name' => $validatedData['user_name'],
                'email' => $validatedData['email'],
                'is_active' => $validatedData['is_active'] ?? true,
            ];
            
            // Atualizar senha se fornecida
            if (!empty($validatedData['password'])) {
                $updateData['password'] = Hash::make($validatedData['password']);
            }
            
            $node->update($updateData);
            
            // Atualizar registros específicos do tipo de nó
            $this->updateNodeSpecificRecords($node, $validatedData);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Nó atualizado com sucesso',
                    'node' => $node->fresh()
                ]);
            }
            
            return redirect()->route('hierarchy.management.index')->with('success', 'Nó atualizado com sucesso');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Dados inválidos',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Erro interno do servidor: ' . $e->getMessage()], 500);
            }
            throw $e;
        }
    }

    /**
     * Alternar status de um nó (ativar/desativar)
     */
    public function toggleStatus(Request $request, $id)
    {
        $user = Auth::user();
        $node = User::findOrFail($id);
        
        // Verificar permissão para alterar status deste nó
        if (!$this->canEditNode($user, $node)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Você não tem permissão para alterar o status deste nó'], 403);
            }
            abort(403, 'Você não tem permissão para alterar o status deste nó');
        }
        
        try {
            $newStatus = !$node->is_active;
            
            // Se está desativando, bloquear a senha
            if (!$newStatus) {
                $this->blockNodeAccess($node);
            }
            
            // Atualizar status
            $node->update(['is_active' => $newStatus]);
            
            // Atualizar registros específicos do tipo de nó
            $this->updateNodeSpecificStatus($node, $newStatus);
            
            $statusText = $newStatus ? 'ativado' : 'desativado';
            $message = "Nó {$statusText} com sucesso";
            
            if (!$newStatus) {
                $message .= ". Acesso bloqueado por segurança.";
            }
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'node' => $node->fresh(),
                    'new_status' => $newStatus
                ]);
            }
            
            return redirect()->route('hierarchy.management.index')->with('success', $message);
            
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Erro ao alterar status: ' . $e->getMessage()], 500);
            }
            throw $e;
        }
    }

    /**
     * Mostrar formulário de criação de nó
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $parentId = $request->get('parent_id');
        $nodeType = $request->get('type');
        
        // Se não foi especificado um tipo, mostrar página de seleção
        if (!$nodeType) {
            $availableTypes = $this->getAvailableNodeTypes($user);
            return view('hierarchy.management.select-type', compact('user', 'availableTypes'));
        }
        
        // Validar se o usuário pode criar este tipo de nó
        if (!$this->canCreateNodeType($user, $nodeType, $parentId)) {
            abort(403, 'Você não tem permissão para criar este tipo de nó');
        }
        
        $parent = $parentId ? User::findOrFail($parentId) : null;
        $availableRoles = Role::where('is_active', true)->get();
        $availableModules = $this->getAvailableModules();
        
        // Buscar operações disponíveis para relacionamento
        $availableOperacoes = \App\Models\OrbitaOperacao::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        // Buscar white labels disponíveis para relacionamento
        $availableWhiteLabels = User::where('node_type', 'white_label')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('hierarchy.management.create', compact('user', 'parent', 'nodeType', 'availableRoles', 'availableModules', 'availableOperacoes', 'availableWhiteLabels'));
    }

    /**
     * Armazenar novo nó
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        try {
            // Validações básicas
            $validatedData = $request->validate([
                'user_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
                'password_confirmation' => 'required|same:password',
            'node_type' => 'required|in:operacao,white_label,licenciado_l1,licenciado_l2,licenciado_l3',
            'parent_id' => 'nullable|exists:users,id',
                'name' => 'nullable|string|max:255',
                'display_name' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'description' => 'nullable|string',
                'domain' => 'nullable|string|max:255',
                'subdomain' => 'nullable|string|max:255',
                'is_active' => 'boolean',
                'modules' => 'nullable|array',
                'operacao_id' => 'required_if:node_type,white_label,licenciado_l1,licenciado_l2,licenciado_l3|nullable|exists:orbita_operacaos,id',
                'white_label_id' => 'nullable|exists:users,id',
                'licenciado_pai_id' => 'nullable|exists:users,id'
            ]);
            
            // Validações específicas de hierarquia
            $this->validateHierarchyRelationships($validatedData);
        
        // Validar se pode criar este tipo de nó
        if (!$this->canCreateNodeType($user, $request->node_type, $request->parent_id)) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Você não tem permissão para criar este tipo de nó'], 403);
                }
            return back()->withErrors(['node_type' => 'Você não tem permissão para criar este tipo de nó']);
        }
        
        DB::beginTransaction();
        
            $newNode = $this->createNode($validatedData, $user);
            
            DB::commit();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Nó criado com sucesso!',
                    'node' => [
                        'id' => $newNode->id,
                        'name' => $newNode->name,
                        'email' => $newNode->email,
                        'node_type' => $newNode->node_type
                    ]
                ]);
            }
            
            return redirect()->route('hierarchy.management.index')
                           ->with('success', 'Nó criado com sucesso!');
                           
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Dados inválidos',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
                           
        } catch (\Exception $e) {
            DB::rollback();
            
            \Log::error('Erro ao criar nó da hierarquia: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'request_data' => $request->all(),
                'exception' => $e
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Erro interno do servidor',
                    'message' => config('app.debug') ? $e->getMessage() : 'Erro ao criar nó'
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Erro ao criar nó: ' . $e->getMessage()]);
        }
    }

    /**
     * Determinar contexto do usuário
     */
    private function determineUserContext(User $user): array
    {
        if ($user->isSuperAdminNode()) {
            return ['type' => 'super_admin', 'scope' => 'global'];
        }
        
        return ['type' => $user->node_type, 'scope' => 'limited'];
    }


    /**
     * Obter módulos disponíveis
     */
    private function getAvailableModules(): array
    {
        return [
            'pix' => 'PIX',
            'bolepix' => 'BolePix',
            'gateway' => 'Gateway de Pagamento',
            'gerenpix' => 'GerenPix',
            'confrapdv' => 'ConfraPDV',
            'agenda' => 'Agenda de Reuniões',
            'leads' => 'Gestão de Leads',
            'contratos' => 'Gestão de Contratos',
            'campanhas' => 'Campanhas de Marketing',
            'estabelecimentos' => 'Gestão de Estabelecimentos',
            'relatorios' => 'Relatórios Avançados',
            'auditoria' => 'Logs de Auditoria',
            'notificacoes' => 'Sistema de Notificações',
            'branding' => 'Personalização de Marca',
            'dominios' => 'Gestão de Domínios'
        ];
    }

    /**
     * Validar relacionamentos hierárquicos
     */
    private function validateHierarchyRelationships(array $data): void
    {
        $nodeType = $data['node_type'];
        
        // White Label deve ter uma operação
        if ($nodeType === 'white_label') {
            if (empty($data['operacao_id'])) {
                throw new \Illuminate\Validation\ValidationException(
                    validator([], []),
                    ['operacao_id' => ['White Label deve estar vinculado a uma operação']]
                );
            }
            
            // Verificar se a operação existe na tabela orbita_operacaos
            $operacao = \App\Models\OrbitaOperacao::find($data['operacao_id']);
            if (!$operacao || !$operacao->is_active) {
                throw new \Illuminate\Validation\ValidationException(
                    validator([], []),
                    ['operacao_id' => ['A operação selecionada é inválida ou inativa']]
                );
            }
        }
        
        // Licenciados devem ter uma operação
        if (in_array($nodeType, ['licenciado_l1', 'licenciado_l2', 'licenciado_l3'])) {
            if (empty($data['operacao_id'])) {
                throw new \Illuminate\Validation\ValidationException(
                    validator([], []),
                    ['operacao_id' => ['Licenciado deve estar vinculado a uma operação']]
                );
            }
            
            // Verificar se a operação existe na tabela orbita_operacaos
            $operacao = \App\Models\OrbitaOperacao::find($data['operacao_id']);
            if (!$operacao || !$operacao->is_active) {
                throw new \Illuminate\Validation\ValidationException(
                    validator([], []),
                    ['operacao_id' => ['A operação selecionada é inválida ou inativa']]
                );
            }
            
            // Se white_label_id foi fornecido, validar
            if (!empty($data['white_label_id'])) {
                $whiteLabel = User::find($data['white_label_id']);
                if (!$whiteLabel || $whiteLabel->node_type !== 'white_label') {
                    throw new \Illuminate\Validation\ValidationException(
                        validator([], []),
                        ['white_label_id' => ['O White Label selecionado é inválido']]
                    );
                }
                
                // Verificar se o White Label pertence à mesma operação
                // Assumindo que temos um campo hierarchy_path ou similar para verificar
                // Por enquanto, vamos aceitar qualquer WL ativo
            }
            
            // Validações específicas para L2 e L3
            if ($nodeType === 'licenciado_l2') {
                if (empty($data['licenciado_pai_id'])) {
                    throw new \Illuminate\Validation\ValidationException(
                        validator([], []),
                        ['licenciado_pai_id' => ['Licenciado L2 deve ter um Licenciado L1 pai']]
                    );
                }
                
                $pai = User::find($data['licenciado_pai_id']);
                if (!$pai || $pai->node_type !== 'licenciado_l1') {
                    throw new \Illuminate\Validation\ValidationException(
                        validator([], []),
                        ['licenciado_pai_id' => ['O Licenciado L1 pai selecionado é inválido']]
                    );
                }
            }
            
            if ($nodeType === 'licenciado_l3') {
                if (empty($data['licenciado_pai_id'])) {
                    throw new \Illuminate\Validation\ValidationException(
                        validator([], []),
                        ['licenciado_pai_id' => ['Licenciado L3 deve ter um Licenciado L2 pai']]
                    );
                }
                
                $pai = User::find($data['licenciado_pai_id']);
                if (!$pai || $pai->node_type !== 'licenciado_l2') {
                    throw new \Illuminate\Validation\ValidationException(
                        validator([], []),
                        ['licenciado_pai_id' => ['O Licenciado L2 pai selecionado é inválido']]
                    );
                }
            }
        }
    }

    /**
     * Obter estatísticas da hierarquia
     */
    private function getHierarchyStats(User $user): array
    {
        if ($user->isSuperAdminNode()) {
            // Super Admin vê estatísticas globais
            return [
                'total_operacoes' => User::where('node_type', 'operacao')->count(),
                'total_white_labels' => User::where('node_type', 'white_label')->count(),
                'total_licenciados_l1' => User::where('node_type', 'licenciado_l1')->count(),
                'total_licenciados_l2' => User::where('node_type', 'licenciado_l2')->count(),
                'total_licenciados_l3' => User::where('node_type', 'licenciado_l3')->count(),
                'active_nodes' => User::whereIn('node_type', ['operacao', 'white_label', 'licenciado_l1', 'licenciado_l2', 'licenciado_l3'])
                                     ->where('is_active', true)
                                     ->count(),
            ];
        } else {
            // Outros usuários veem estatísticas de sua descendência
            $descendants = $user->getAllDescendants();
            $descendantIds = $descendants->pluck('id')->toArray();
            $descendantIds[] = $user->id; // Incluir o próprio usuário
            
            return [
                'total_operacoes' => User::whereIn('id', $descendantIds)->where('node_type', 'operacao')->count(),
                'total_white_labels' => User::whereIn('id', $descendantIds)->where('node_type', 'white_label')->count(),
                'total_licenciados_l1' => User::whereIn('id', $descendantIds)->where('node_type', 'licenciado_l1')->count(),
                'total_licenciados_l2' => User::whereIn('id', $descendantIds)->where('node_type', 'licenciado_l2')->count(),
                'total_licenciados_l3' => User::whereIn('id', $descendantIds)->where('node_type', 'licenciado_l3')->count(),
                'active_nodes' => User::whereIn('id', $descendantIds)
                                     ->whereIn('node_type', ['operacao', 'white_label', 'licenciado_l1', 'licenciado_l2', 'licenciado_l3'])
                                     ->where('is_active', true)
                                     ->count(),
            ];
        }
    }

    /**
     * Obter nós para o usuário baseado em suas permissões
     */
    private function getNodesForUser(User $user)
    {
        if ($user->isSuperAdminNode()) {
            // Super Admin vê todos os nós
            return User::whereIn('node_type', ['operacao', 'white_label', 'licenciado_l1', 'licenciado_l2', 'licenciado_l3'])
                      ->with('role')
                      ->orderBy('hierarchy_level')
                      ->orderBy('name')
                      ->paginate(20);
        }
        
        // Outros usuários veem apenas seus descendentes
        $descendants = $user->getAllDescendants();
        $descendants->push($user); // Incluir o próprio usuário
        
        return $descendants->sortBy('hierarchy_level');
    }

    /**
     * Obter tipos de nó disponíveis para o usuário
     */
    private function getAvailableNodeTypes(User $user): array
    {
        $types = [];
        
        if ($user->isSuperAdminNode()) {
            $types[] = [
                'key' => 'operacao',
                'name' => 'Nova Operação',
                'description' => 'Criar uma nova operação (nível raiz)',
                'icon' => 'fas fa-building',
                'color' => 'blue'
            ];
        }
        
        if ($user->isSuperAdminNode() || $user->isOperacaoNode()) {
            $types[] = [
                'key' => 'white_label',
                'name' => 'Novo White Label',
                'description' => 'Criar um novo white label',
                'icon' => 'fas fa-tag',
                'color' => 'green'
            ];
        }
        
        if ($user->canHaveChildren()) {
            $types[] = [
                'key' => 'licenciado_l1',
                'name' => 'Novo Licenciado L1',
                'description' => 'Criar um licenciado nível 1',
                'icon' => 'fas fa-user',
                'color' => 'yellow'
            ];
            
            if ($user->node_type === 'licenciado_l1' || $user->isSuperAdminNode()) {
                $types[] = [
                    'key' => 'licenciado_l2',
                    'name' => 'Novo Licenciado L2',
                    'description' => 'Criar um licenciado nível 2',
                    'icon' => 'fas fa-user-friends',
                    'color' => 'orange'
                ];
            }
            
            if ($user->node_type === 'licenciado_l2' || $user->isSuperAdminNode()) {
                $types[] = [
                    'key' => 'licenciado_l3',
                    'name' => 'Novo Licenciado L3',
                    'description' => 'Criar um licenciado nível 3',
                    'icon' => 'fas fa-users',
                    'color' => 'red'
                ];
            }
        }
        
        return $types;
    }

    /**
     * Verificar se pode criar tipo de nó
     */
    private function canCreateNodeType(User $user, string $nodeType, ?int $parentId): bool
    {
        if ($user->isSuperAdminNode()) {
            return true; // Super Admin pode criar qualquer coisa
        }
        
        $parent = $parentId ? User::find($parentId) : null;
        
        // Verificar se o parent é descendente do usuário atual
        if ($parent && !$user->getAllDescendants()->contains('id', $parent->id) && $parent->id !== $user->id) {
            return false;
        }
        
        // Validar hierarquia
        switch ($nodeType) {
            case 'licenciado_l1':
                return $user->node_type === 'operacao' || $user->node_type === 'white_label';
            case 'licenciado_l2':
                return $user->node_type === 'licenciado_l1' || $user->isSuperAdminNode();
            case 'licenciado_l3':
                return $user->node_type === 'licenciado_l2' || $user->isSuperAdminNode();
            default:
                return false;
        }
    }

    /**
     * Criar novo nó
     */
    private function createNode(array $data, User $creator): User
    {
        // Determinar parent_id baseado nos relacionamentos hierárquicos
        $parentId = $this->determineParentId($data);
        $parent = $parentId ? User::find($parentId) : null;
        
        // Determinar role_id baseado no tipo de nó
        $roleId = $this->getRoleIdForNodeType($data['node_type']);
        
        $nodeData = [
            'name' => $data['user_name'], // Nome do usuário
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $roleId,
            'node_type' => $data['node_type'],
            'is_active' => $data['is_active'] ?? true,
            'parent_id' => $parentId,
        ];
        
        // Definir hierarchy_level baseado no tipo de nó
        switch ($data['node_type']) {
            case 'operacao':
                $nodeData['hierarchy_level'] = 1;
                break;
            case 'white_label':
                $nodeData['hierarchy_level'] = 2;
                break;
            case 'licenciado_l1':
                $nodeData['hierarchy_level'] = $data['white_label_id'] ? 3 : 2;
                break;
            case 'licenciado_l2':
                $nodeData['hierarchy_level'] = $parent ? $parent->hierarchy_level + 1 : 3;
                break;
            case 'licenciado_l3':
                $nodeData['hierarchy_level'] = $parent ? $parent->hierarchy_level + 1 : 4;
                break;
        }
        
        $node = User::create($nodeData);
        
        // Criar registros específicos do tipo de nó
        $this->createNodeSpecificRecords($node, $data);
        
        // Configurar módulos se fornecidos
        if (isset($data['modules']) && is_array($data['modules'])) {
            $this->configureNodeModules($node, $data['modules']);
        }
        
        // Gerar hierarchy_path
        $node->getHierarchyPath();
        
        return $node;
    }

    /**
     * Determinar parent_id baseado nos relacionamentos hierárquicos
     */
    private function determineParentId(array $data): ?int
    {
        $nodeType = $data['node_type'];
        
        switch ($nodeType) {
            case 'operacao':
                // Operações não têm pai (são raiz)
                return null;
                
            case 'white_label':
                // White Label tem como pai o usuário da operação
                return $this->findUserIdByOperacaoId($data['operacao_id'] ?? null);
                
            case 'licenciado_l1':
                // L1 pode ter como pai um white label ou uma operação
                if (!empty($data['white_label_id'])) {
                    return $data['white_label_id']; // ID do usuário white label
                } else {
                    return $this->findUserIdByOperacaoId($data['operacao_id'] ?? null);
                }
                
            case 'licenciado_l2':
                // L2 tem como pai um L1
                return $data['licenciado_pai_id'] ?? null;
                
            case 'licenciado_l3':
                // L3 tem como pai um L2
                return $data['licenciado_pai_id'] ?? null;
                
            default:
                return null;
        }
    }

    /**
     * Encontrar o user_id correspondente ao operacao_id
     */
    private function findUserIdByOperacaoId(?int $operacaoId): ?int
    {
        if (!$operacaoId) {
            return null;
        }
        
        // Buscar a operação na tabela orbita_operacaos
        $operacao = \App\Models\OrbitaOperacao::find($operacaoId);
        if (!$operacao) {
            return null;
        }
        
        // Buscar o usuário correspondente na tabela users
        $userOperacao = User::where('node_type', 'operacao')
            ->where('name', $operacao->name)
            ->first();
            
        return $userOperacao ? $userOperacao->id : null;
    }

    /**
     * Verificar se o usuário pode visualizar um nó
     */
    private function canViewNode(User $user, User $node): bool
    {
        // Super Admin pode ver tudo
        if ($user->isSuperAdminNode()) {
            return true;
        }
        
        // Pode ver seus próprios dados
        if ($user->id === $node->id) {
            return true;
        }
        
        // Pode ver nós descendentes
        return $this->isDescendant($user, $node);
    }

    /**
     * Verificar se o usuário pode editar um nó
     */
    private function canEditNode(User $user, User $node): bool
    {
        // Super Admin pode editar tudo
        if ($user->isSuperAdminNode()) {
            return true;
        }
        
        // Pode editar seus próprios dados
        if ($user->id === $node->id) {
            return true;
        }
        
        // Pode editar nós descendentes diretos
        return $node->parent_id === $user->id;
    }

    /**
     * Verificar se um nó é descendente de outro
     */
    private function isDescendant(User $ancestor, User $node): bool
    {
        $current = $node;
        while ($current->parent_id) {
            if ($current->parent_id === $ancestor->id) {
                return true;
            }
            $current = User::find($current->parent_id);
            if (!$current) break;
        }
        return false;
    }

    /**
     * Obter detalhes específicos de um nó
     */
    private function getNodeDetails(User $node): array
    {
        $details = [
            'basic_info' => [
                'id' => $node->id,
                'name' => $node->name,
                'email' => $node->email,
                'node_type' => $node->node_type,
                'hierarchy_level' => $node->hierarchy_level,
                'parent_id' => $node->parent_id,
                'is_active' => $node->is_active,
                'created_at' => $node->created_at,
                'updated_at' => $node->updated_at,
            ]
        ];

        // Buscar dados específicos por tipo de nó
        switch ($node->node_type) {
            case 'operacao':
                $operacao = \App\Models\OrbitaOperacao::where('name', $node->name)->first();
                if ($operacao) {
                    $details['operacao_info'] = [
                        'id' => $operacao->id,
                        'display_name' => $operacao->display_name,
                        'description' => $operacao->description,
                        'domain' => $operacao->domain,
                        'subdomain' => $operacao->subdomain,
                        'branding' => $operacao->branding,
                        'modules' => $operacao->modules,
                        'settings' => $operacao->settings,
                    ];
                }
                break;

            case 'white_label':
                $whiteLabel = \App\Models\WhiteLabel::latest()->first();
                if ($whiteLabel) {
                    $details['white_label_info'] = [
                        'id' => $whiteLabel->id,
                        'display_name' => $whiteLabel->display_name,
                        'description' => $whiteLabel->description,
                        'operacao_id' => $whiteLabel->operacao_id,
                        'domain' => $whiteLabel->domain,
                        'subdomain' => $whiteLabel->subdomain,
                        'branding' => $whiteLabel->branding,
                        'modules' => $whiteLabel->modules,
                        'settings' => $whiteLabel->settings,
                    ];
                    
                    // Buscar operação pai
                    $operacao = $whiteLabel->operacao;
                    if ($operacao) {
                        $details['parent_operacao'] = [
                            'id' => $operacao->id,
                            'name' => $operacao->name,
                            'display_name' => $operacao->display_name,
                        ];
                    }
                }
                break;
        }

        // Buscar estatísticas do nó
        $details['statistics'] = $this->getNodeStatistics($node);

        return $details;
    }

    /**
     * Obter estatísticas de um nó específico
     */
    private function getNodeStatistics(User $node): array
    {
        $stats = [
            'direct_children' => User::where('parent_id', $node->id)->count(),
            'total_descendants' => 0,
            'active_descendants' => 0,
        ];

        // Calcular descendentes totais recursivamente
        $descendants = $this->getAllDescendants($node);
        $stats['total_descendants'] = $descendants->count();
        $stats['active_descendants'] = $descendants->where('is_active', true)->count();

        return $stats;
    }

    /**
     * Obter todos os descendentes de um nó
     */
    private function getAllDescendants(User $node)
    {
        $descendants = collect();
        $directChildren = User::where('parent_id', $node->id)->get();
        
        foreach ($directChildren as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($this->getAllDescendants($child));
        }
        
        return $descendants;
    }

    /**
     * Bloquear acesso de um nó (alterar senha para hash aleatório)
     */
    private function blockNodeAccess(User $node): void
    {
        // Gerar hash aleatório para bloquear acesso
        $blockedPassword = Hash::make('BLOCKED_' . time() . '_' . $node->id);
        $node->update(['password' => $blockedPassword]);
        
        // Log da ação de bloqueio
        \Log::info("Node access blocked", [
            'node_id' => $node->id,
            'node_type' => $node->node_type,
            'node_email' => $node->email,
            'blocked_at' => now(),
            'blocked_by' => Auth::id()
        ]);
    }

    /**
     * Atualizar registros específicos do tipo de nó
     */
    private function updateNodeSpecificRecords(User $node, array $data): void
    {
        switch ($node->node_type) {
            case 'operacao':
                $operacao = \App\Models\OrbitaOperacao::where('name', $node->name)->first();
                if ($operacao) {
                    $operacao->update([
                        'display_name' => $data['display_name'] ?? $operacao->display_name,
                        'description' => $data['description'] ?? $operacao->description,
                        'domain' => $data['domain'] ?? $operacao->domain,
                        'subdomain' => $data['subdomain'] ?? $operacao->subdomain,
                        'is_active' => $data['is_active'] ?? $operacao->is_active,
                    ]);
                }
                break;

            case 'white_label':
                $whiteLabel = \App\Models\WhiteLabel::latest()->first();
                if ($whiteLabel) {
                    $whiteLabel->update([
                        'display_name' => $data['display_name'] ?? $whiteLabel->display_name,
                        'description' => $data['description'] ?? $whiteLabel->description,
                        'domain' => $data['domain'] ?? $whiteLabel->domain,
                        'subdomain' => $data['subdomain'] ?? $whiteLabel->subdomain,
                        'is_active' => $data['is_active'] ?? $whiteLabel->is_active,
                    ]);
                }
                break;
        }
    }

    /**
     * Atualizar status em registros específicos do tipo de nó
     */
    private function updateNodeSpecificStatus(User $node, bool $status): void
    {
        switch ($node->node_type) {
            case 'operacao':
                $operacao = \App\Models\OrbitaOperacao::where('name', $node->name)->first();
                if ($operacao) {
                    $operacao->update(['is_active' => $status]);
                }
                break;

            case 'white_label':
                $whiteLabel = \App\Models\WhiteLabel::latest()->first();
                if ($whiteLabel) {
                    $whiteLabel->update(['is_active' => $status]);
                }
                break;
        }
    }

    /**
     * Obter role_id baseado no tipo de nó
     */
    private function getRoleIdForNodeType(string $nodeType): int
    {
        $roleMap = [
            'operacao' => 2, // admin
            'white_label' => 2, // admin
            'licenciado_l1' => 4, // licenciado
            'licenciado_l2' => 4, // licenciado
            'licenciado_l3' => 4, // licenciado
        ];
        
        return $roleMap[$nodeType] ?? 4; // Default para licenciado
    }
    
    /**
     * Criar registros específicos do tipo de nó
     */
    private function createNodeSpecificRecords(User $node, array $data): void
    {
        if ($data['node_type'] === 'operacao') {
            // Criar registro na tabela orbita_operacaos
            \App\Models\OrbitaOperacao::create([
                'name' => $data['name'] ?? $data['user_name'],
                'display_name' => $data['display_name'] ?? $data['user_name'],
                'description' => $data['description'] ?? null,
                'user_id' => $node->id,
                'is_active' => $data['is_active'] ?? true,
            ]);
        } elseif ($data['node_type'] === 'white_label') {
            // Criar registro na tabela white_labels
            \App\Models\WhiteLabel::create([
                'name' => $data['name'] ?? $data['user_name'],
                'display_name' => $data['display_name'] ?? $data['user_name'],
                'description' => $data['description'] ?? null,
                'operacao_id' => $data['operacao_id'],
                'is_active' => $data['is_active'] ?? true,
            ]);
        }
        
        // Criar configurações de domínio se fornecidas
        if (isset($data['domain']) || isset($data['subdomain'])) {
            \App\Models\NodeDomain::create([
                'node_type' => $data['node_type'],
                'node_id' => $node->id,
                'domain_type' => isset($data['domain']) ? 'custom' : 'subdomain',
                'domain' => $data['domain'] ?? null,
                'subdomain' => $data['subdomain'] ?? null,
                'base_domain' => 'orbita.dspay.com.br',
                'is_primary' => true,
                'status' => 'active',
            ]);
        }
    }
    
    /**
     * Configurar módulos do nó
     */
    private function configureNodeModules(User $node, array $modules): void
    {
        foreach ($modules as $moduleKey => $moduleConfig) {
            if (isset($moduleConfig['enabled']) && $moduleConfig['enabled']) {
                // Aqui você pode implementar a lógica para ativar módulos
                // Por exemplo, criar registros em uma tabela de módulos por nó
                \Log::info("Módulo {$moduleKey} ativado para nó {$node->id}");
            }
        }
    }

}