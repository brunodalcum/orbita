<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrbitaOperacao;
use App\Models\WhiteLabel;
use Illuminate\Support\Facades\Auth;

class HierarchyTreeController extends Controller
{
    /**
     * Exibir visualização em árvore da hierarquia
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $context = $this->determineUserContext($user);
        
        // Obter dados da árvore baseados no contexto
        $treeData = $this->getTreeData($user, $context);
        
        return view('hierarchy.tree.index', compact('user', 'context', 'treeData'));
    }

    /**
     * API para dados da árvore (para carregamento dinâmico)
     */
    public function treeDataApi(Request $request)
    {
        $user = Auth::user();
        $context = $this->determineUserContext($user);
        $nodeId = $request->get('node_id');
        
        if ($nodeId) {
            // Carregar filhos de um nó específico
            $node = User::findOrFail($nodeId);
            $children = $this->getNodeChildren($node, $user);
            return response()->json($children);
        }
        
        // Carregar árvore completa
        $treeData = $this->getTreeData($user, $context);
        return response()->json($treeData);
    }

    /**
     * Buscar nós na árvore
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $nodes = $this->searchNodes($user, $query);
        
        return response()->json($nodes);
    }

    /**
     * Determinar contexto do usuário
     */
    private function determineUserContext(User $user): array
    {
        if ($user->isSuperAdminNode()) {
            return [
                'type' => 'super_admin',
                'scope' => 'global',
                'can_see_all' => true
            ];
        }
        
        return [
            'type' => $user->node_type,
            'scope' => 'limited',
            'can_see_all' => false
        ];
    }

    /**
     * Obter dados da árvore
     */
    private function getTreeData(User $user, array $context): array
    {
        if ($context['type'] === 'super_admin') {
            return $this->getSuperAdminTreeData();
        }
        
        return $this->getUserTreeData($user);
    }

    /**
     * Dados da árvore para Super Admin
     */
    private function getSuperAdminTreeData(): array
    {
        $tree = [];
        
        // Adicionar nó raiz (Super Admin)
        $superAdmins = User::where('node_type', 'super_admin')->get();
        
        foreach ($superAdmins as $superAdmin) {
            $tree[] = [
                'id' => 'super_admin_' . $superAdmin->id,
                'text' => $superAdmin->name . ' (Super Admin)',
                'type' => 'super_admin',
                'icon' => 'fas fa-crown',
                'state' => ['opened' => true],
                'children' => $this->getOperacoesNodes()
            ];
        }
        
        return $tree;
    }

    /**
     * Obter nós das operações
     */
    private function getOperacoesNodes(): array
    {
        $operacoes = OrbitaOperacao::with(['users', 'whiteLabels'])->get();
        $nodes = [];
        
        foreach ($operacoes as $operacao) {
            $children = [];
            
            // Adicionar White Labels
            foreach ($operacao->whiteLabels as $wl) {
                $children[] = [
                    'id' => 'wl_' . $wl->id,
                    'text' => $wl->display_name . ' (White Label)',
                    'type' => 'white_label',
                    'icon' => 'fas fa-tag',
                    'children' => $this->getWhiteLabelChildren($wl)
                ];
            }
            
            // Adicionar usuários diretos da operação
            $directUsers = $operacao->users()->whereNull('white_label_id')->get();
            foreach ($directUsers as $user) {
                $children[] = $this->buildUserNode($user);
            }
            
            $nodes[] = [
                'id' => 'operacao_' . $operacao->id,
                'text' => $operacao->display_name . ' (Operação)',
                'type' => 'operacao',
                'icon' => 'fas fa-building',
                'state' => ['opened' => true],
                'children' => $children
            ];
        }
        
        return $nodes;
    }

    /**
     * Obter filhos de um White Label
     */
    private function getWhiteLabelChildren(WhiteLabel $whiteLabel): array
    {
        $children = [];
        $users = $whiteLabel->users;
        
        foreach ($users as $user) {
            $children[] = $this->buildUserNode($user);
        }
        
        return $children;
    }

    /**
     * Construir nó de usuário
     */
    private function buildUserNode(User $user): array
    {
        $children = [];
        
        foreach ($user->children as $child) {
            $children[] = $this->buildUserNode($child);
        }
        
        return [
            'id' => 'user_' . $user->id,
            'text' => $user->name . ' (' . $this->getNodeTypeLabel($user->node_type) . ')',
            'type' => $user->node_type,
            'icon' => $this->getNodeIcon($user->node_type),
            'state' => [
                'opened' => count($children) <= 3,
                'disabled' => !$user->is_active
            ],
            'li_attr' => [
                'class' => $user->is_active ? '' : 'text-gray-400'
            ],
            'a_attr' => [
                'href' => route('hierarchy.management.show', $user->id),
                'title' => $user->email
            ],
            'children' => $children
        ];
    }

    /**
     * Dados da árvore para usuário específico
     */
    private function getUserTreeData(User $user): array
    {
        // Mostrar o usuário atual como raiz e seus descendentes
        return [$this->buildUserNode($user)];
    }

    /**
     * Obter filhos de um nó
     */
    private function getNodeChildren(User $node, User $currentUser): array
    {
        // Verificar se o usuário atual pode ver os filhos deste nó
        if (!$this->canViewNodeChildren($currentUser, $node)) {
            return [];
        }
        
        $children = [];
        
        foreach ($node->children as $child) {
            $children[] = $this->buildUserNode($child);
        }
        
        return $children;
    }

    /**
     * Verificar se pode ver filhos do nó
     */
    private function canViewNodeChildren(User $currentUser, User $node): bool
    {
        if ($currentUser->isSuperAdminNode()) {
            return true;
        }
        
        // Pode ver filhos se o nó for ele mesmo ou um descendente
        return $node->id === $currentUser->id || 
               $currentUser->getAllDescendants()->contains('id', $node->id);
    }

    /**
     * Buscar nós
     */
    private function searchNodes(User $currentUser, string $query): array
    {
        $results = [];
        
        if ($currentUser->isSuperAdminNode()) {
            // Super Admin pode buscar em todos os nós
            $users = User::where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })->limit(20)->get();
        } else {
            // Outros usuários só podem buscar em seus descendentes
            $descendants = $currentUser->getAllDescendants();
            $descendants->push($currentUser);
            
            $users = $descendants->filter(function($user) use ($query) {
                return stripos($user->name, $query) !== false || 
                       stripos($user->email, $query) !== false;
            })->take(20);
        }
        
        foreach ($users as $user) {
            $results[] = [
                'id' => 'user_' . $user->id,
                'text' => $user->name . ' (' . $this->getNodeTypeLabel($user->node_type) . ')',
                'type' => $user->node_type,
                'email' => $user->email,
                'hierarchy_path' => $user->getHierarchyPath(),
                'url' => route('hierarchy.management.show', $user->id)
            ];
        }
        
        return $results;
    }

    /**
     * Obter label do tipo de nó
     */
    private function getNodeTypeLabel(string $nodeType): string
    {
        return match($nodeType) {
            'super_admin' => 'Super Admin',
            'operacao' => 'Operação',
            'white_label' => 'White Label',
            'licenciado_l1' => 'Licenciado L1',
            'licenciado_l2' => 'Licenciado L2',
            'licenciado_l3' => 'Licenciado L3',
            default => ucfirst($nodeType)
        };
    }

    /**
     * Obter ícone do tipo de nó
     */
    private function getNodeIcon(string $nodeType): string
    {
        return match($nodeType) {
            'super_admin' => 'fas fa-crown',
            'operacao' => 'fas fa-building',
            'white_label' => 'fas fa-tag',
            'licenciado_l1', 'licenciado_l2', 'licenciado_l3' => 'fas fa-user',
            default => 'fas fa-circle'
        };
    }
}