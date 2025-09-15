<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrbitaOperacao;
use App\Models\WhiteLabel;
use App\Models\NodeBranding;
use Illuminate\Support\Facades\Auth;

class HierarchyDemoController extends Controller
{
    /**
     * Exibir dashboard da hierarquia
     */
    public function index(Request $request)
    {
        $context = $request->get('nodeContext');
        $user = Auth::user();
        
        // Obter estatísticas baseadas no contexto
        $stats = $this->getContextStats($context, $user);
        
        // Obter estrutura da hierarquia
        $hierarchy = $this->getHierarchyStructure($user);
        
        return view('hierarchy.dashboard', compact('context', 'stats', 'hierarchy'));
    }

    /**
     * Exibir árvore completa da hierarquia
     */
    public function tree()
    {
        $user = Auth::user();
        
        // Super Admin vê tudo
        if ($user->isSuperAdminNode()) {
            $operacoes = OrbitaOperacao::with(['whiteLabels', 'users'])->get();
            $tree = $this->buildCompleteTree($operacoes);
        } else {
            // Outros usuários veem apenas sua descendência
            $tree = $this->buildUserTree($user);
        }
        
        return view('hierarchy.tree.index', compact('tree', 'user'));
    }

    /**
     * Testar impersonação
     */
    public function impersonate(Request $request, $userId)
    {
        $currentUser = Auth::user();
        $targetUser = User::findOrFail($userId);
        
        // Verificar se pode impersonar
        if (!$currentUser->canImpersonate($targetUser)) {
            return response()->json([
                'error' => 'Você não tem permissão para impersonar este usuário'
            ], 403);
        }
        
        // Simular impersonação (em produção, seria implementado com sessões)
        return response()->json([
            'success' => true,
            'message' => "Impersonando {$targetUser->name}",
            'target_user' => [
                'id' => $targetUser->id,
                'name' => $targetUser->name,
                'email' => $targetUser->email,
                'node_type' => $targetUser->node_type,
                'hierarchy_level' => $targetUser->hierarchy_level,
                'branding' => $targetUser->getBrandingWithInheritance()
            ]
        ]);
    }

    /**
     * Testar herança de módulos
     */
    public function testModules(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        
        $modules = [
            'pix' => $user->hasModuleAccess('pix'),
            'bolepix' => $user->hasModuleAccess('bolepix'),
            'gateway' => $user->hasModuleAccess('gateway'),
            'gerenpix' => $user->hasModuleAccess('gerenpix'),
            'agenda' => $user->hasModuleAccess('agenda'),
        ];
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'node_type' => $user->node_type,
                'hierarchy_path' => $user->hierarchy_path
            ],
            'modules' => $modules,
            'inheritance_chain' => $this->getModuleInheritanceChain($user)
        ]);
    }

    /**
     * Testar herança de branding
     */
    public function testBranding(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $branding = $user->getBrandingWithInheritance();
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'node_type' => $user->node_type
            ],
            'branding' => $branding,
            'inheritance_chain' => $this->getBrandingInheritanceChain($user)
        ]);
    }

    /**
     * Criar novo nó filho
     */
    public function createChild(Request $request, $parentId)
    {
        $parent = User::findOrFail($parentId);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);
        
        try {
            $child = $parent->createChild([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => 4, // Licenciado
                'is_active' => true
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Filho criado com sucesso',
                'child' => [
                    'id' => $child->id,
                    'name' => $child->name,
                    'email' => $child->email,
                    'node_type' => $child->node_type,
                    'hierarchy_level' => $child->hierarchy_level,
                    'hierarchy_path' => $child->hierarchy_path
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Obter estatísticas do contexto atual
     */
    private function getContextStats($context, $user): array
    {
        $stats = [
            'total_users' => 0,
            'active_modules' => 0,
            'descendants' => 0,
            'hierarchy_depth' => 0
        ];
        
        if (!$user) return $stats;
        
        switch ($context['type']) {
            case 'platform':
                $stats['total_users'] = User::count();
                $stats['active_modules'] = count($this->getAllModules());
                break;
                
            case 'operacao':
                $operacao = $context['entity'];
                $stats['total_users'] = $operacao->getAllDescendants()->count();
                $stats['active_modules'] = collect($operacao->modules ?? [])->where('enabled', true)->count();
                break;
                
            case 'white_label':
                $whiteLabel = $context['entity'];
                $stats['total_users'] = $whiteLabel->getAllDescendants()->count();
                $stats['active_modules'] = collect($whiteLabel->modules ?? [])->where('enabled', true)->count();
                break;
                
            default:
                $stats['descendants'] = $user->getAllDescendants()->count();
                $stats['hierarchy_depth'] = $user->hierarchy_level;
        }
        
        return $stats;
    }

    /**
     * Obter estrutura da hierarquia para o usuário
     */
    private function getHierarchyStructure($user): array
    {
        if (!$user) return [];
        
        if ($user->isSuperAdminNode()) {
            return $this->getSuperAdminHierarchy();
        }
        
        return $this->getUserHierarchy($user);
    }

    /**
     * Construir árvore completa (Super Admin)
     */
    private function buildCompleteTree($operacoes): array
    {
        $tree = [];
        
        foreach ($operacoes as $operacao) {
            $operacaoNode = [
                'id' => $operacao->id,
                'name' => $operacao->display_name,
                'type' => 'operacao',
                'children' => []
            ];
            
            // White Labels da operação
            foreach ($operacao->whiteLabels as $wl) {
                $wlNode = [
                    'id' => $wl->id,
                    'name' => $wl->display_name,
                    'type' => 'white_label',
                    'children' => $this->buildUserChildren($wl->licenciadosL1)
                ];
                $operacaoNode['children'][] = $wlNode;
            }
            
            // Licenciados diretos da operação (sem WL)
            $directL1 = $operacao->licenciadosL1;
            foreach ($directL1 as $l1) {
                $operacaoNode['children'][] = $this->buildUserNode($l1);
            }
            
            $tree[] = $operacaoNode;
        }
        
        return $tree;
    }

    /**
     * Construir nó de usuário com filhos
     */
    private function buildUserNode($user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'type' => $user->node_type,
            'children' => $this->buildUserChildren($user->children)
        ];
    }

    /**
     * Construir filhos de usuários
     */
    private function buildUserChildren($users): array
    {
        $children = [];
        
        foreach ($users as $user) {
            $children[] = $this->buildUserNode($user);
        }
        
        return $children;
    }

    /**
     * Obter cadeia de herança de módulos
     */
    private function getModuleInheritanceChain($user): array
    {
        $chain = [];
        
        // Configurações locais
        if ($user->modules) {
            $chain[] = [
                'source' => 'user',
                'id' => $user->id,
                'name' => $user->name,
                'modules' => $user->modules
            ];
        }
        
        // White Label
        if ($user->whiteLabel && $user->whiteLabel->modules) {
            $chain[] = [
                'source' => 'white_label',
                'id' => $user->whiteLabel->id,
                'name' => $user->whiteLabel->display_name,
                'modules' => $user->whiteLabel->modules
            ];
        }
        
        // Operação
        if ($user->orbitaOperacao && $user->orbitaOperacao->modules) {
            $chain[] = [
                'source' => 'operacao',
                'id' => $user->orbitaOperacao->id,
                'name' => $user->orbitaOperacao->display_name,
                'modules' => $user->orbitaOperacao->modules
            ];
        }
        
        return $chain;
    }

    /**
     * Obter cadeia de herança de branding
     */
    private function getBrandingInheritanceChain($user): array
    {
        $chain = [];
        
        // Branding local
        if ($user->branding) {
            $chain[] = [
                'source' => 'user',
                'id' => $user->id,
                'name' => $user->name,
                'branding' => $user->branding->toArray()
            ];
        }
        
        // White Label
        if ($user->whiteLabel && $user->whiteLabel->branding) {
            $chain[] = [
                'source' => 'white_label',
                'id' => $user->whiteLabel->id,
                'name' => $user->whiteLabel->display_name,
                'branding' => $user->whiteLabel->branding->toArray()
            ];
        }
        
        // Operação
        if ($user->orbitaOperacao && $user->orbitaOperacao->branding) {
            $chain[] = [
                'source' => 'operacao',
                'id' => $user->orbitaOperacao->id,
                'name' => $user->orbitaOperacao->display_name,
                'branding' => $user->orbitaOperacao->branding->toArray()
            ];
        }
        
        return $chain;
    }

    /**
     * Obter todos os módulos disponíveis
     */
    private function getAllModules(): array
    {
        return [
            'pix',
            'bolepix', 
            'gateway',
            'gerenpix',
            'contraPDV',
            'agenda',
            'leads',
            'contracts'
        ];
    }

    /**
     * Obter hierarquia para Super Admin
     */
    private function getSuperAdminHierarchy(): array
    {
        return [
            'operacoes' => OrbitaOperacao::count(),
            'white_labels' => WhiteLabel::count(),
            'total_users' => User::count(),
            'licenciados_l1' => User::where('node_type', 'licenciado_l1')->count(),
            'licenciados_l2' => User::where('node_type', 'licenciado_l2')->count(),
            'licenciados_l3' => User::where('node_type', 'licenciado_l3')->count(),
        ];
    }

    /**
     * Obter hierarquia para usuário específico
     */
    private function getUserHierarchy($user): array
    {
        return [
            'ancestors' => $user->getAllAncestors()->count(),
            'descendants' => $user->getAllDescendants()->count(),
            'direct_children' => $user->children()->count(),
            'hierarchy_level' => $user->hierarchy_level,
            'can_have_children' => $user->canHaveChildren(),
        ];
    }
}