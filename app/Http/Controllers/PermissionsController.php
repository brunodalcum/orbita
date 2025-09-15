<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NodePermission;
use App\Models\User;
use App\Models\OrbitaOperacao;
use App\Models\WhiteLabel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PermissionsController extends Controller
{
    /**
     * Interface principal de gerenciamento de permissões
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$this->canManagePermissions($user)) {
            abort(403, 'Você não tem permissão para gerenciar permissões');
        }
        
        $nodeType = $request->get('node_type', 'all');
        $search = $request->get('search', '');
        
        $nodes = $this->getManageableNodes($user, $nodeType, $search);
        $availablePermissions = NodePermission::getAvailablePermissions();
        $stats = $this->getPermissionsStatistics($user);
        
        return view('hierarchy.permissions.index', compact(
            'user',
            'nodes',
            'availablePermissions',
            'stats',
            'nodeType',
            'search'
        ));
    }

    /**
     * Gerenciar permissões de um nó específico
     */
    public function manage($nodeType, $nodeId)
    {
        $user = Auth::user();
        
        if (!$this->canManagePermissions($user)) {
            abort(403, 'Você não tem permissão para gerenciar permissões');
        }
        
        $node = $this->findNode($nodeType, $nodeId);
        if (!$node || !$this->canManageNodePermissions($user, $node, $nodeType)) {
            abort(403, 'Nó não encontrado ou sem permissão');
        }
        
        $currentPermissions = NodePermission::getNodePermissions($nodeType, $nodeId);
        $availablePermissions = NodePermission::getAvailablePermissions();
        $defaultPermissions = NodePermission::getDefaultPermissions($this->getNodeTypeForPermissions($node, $nodeType));
        
        return view('hierarchy.permissions.manage', compact(
            'user',
            'node',
            'nodeType',
            'currentPermissions',
            'availablePermissions',
            'defaultPermissions'
        ));
    }

    /**
     * Atualizar permissões de um nó
     */
    public function update(Request $request, $nodeType, $nodeId)
    {
        $user = Auth::user();
        
        if (!$this->canManagePermissions($user)) {
            return response()->json(['success' => false, 'message' => 'Sem permissão'], 403);
        }
        
        $node = $this->findNode($nodeType, $nodeId);
        if (!$node || !$this->canManageNodePermissions($user, $node, $nodeType)) {
            return response()->json(['success' => false, 'message' => 'Nó não encontrado'], 404);
        }
        
        try {
            $permissions = $request->get('permissions', []);
            $updated = 0;
            
            $availablePermissions = collect(NodePermission::getAvailablePermissions())
                ->flatMap(function($group) { return array_keys($group); })
                ->toArray();
            
            foreach ($availablePermissions as $permissionKey) {
                $granted = in_array($permissionKey, $permissions);
                
                NodePermission::updateOrCreate(
                    [
                        'node_type' => $nodeType,
                        'node_id' => $nodeId,
                        'permission_key' => $permissionKey
                    ],
                    [
                        'granted' => $granted,
                        'granted_by' => $user->id,
                        'granted_at' => $granted ? now() : null
                    ]
                );
                
                $updated++;
            }
            
            return response()->json([
                'success' => true,
                'message' => "Permissões atualizadas com sucesso! ({$updated} permissões processadas)"
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar permissões: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aplicar permissões padrão
     */
    public function applyDefaults($nodeType, $nodeId)
    {
        $user = Auth::user();
        
        if (!$this->canManagePermissions($user)) {
            return response()->json(['success' => false, 'message' => 'Sem permissão'], 403);
        }
        
        $node = $this->findNode($nodeType, $nodeId);
        if (!$node || !$this->canManageNodePermissions($user, $node, $nodeType)) {
            return response()->json(['success' => false, 'message' => 'Nó não encontrado'], 404);
        }
        
        try {
            NodePermission::applyDefaultPermissions($nodeType, $nodeId, $user->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Permissões padrão aplicadas com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao aplicar permissões padrão: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar se o usuário pode gerenciar permissões
     */
    private function canManagePermissions(User $user): bool
    {
        return $user->isSuperAdminNode() || 
               $user->isOperacaoNode() || 
               $user->isWhiteLabelNode();
    }

    /**
     * Verificar se pode gerenciar permissões de um nó específico
     */
    private function canManageNodePermissions(User $user, $node, string $nodeType): bool
    {
        if ($user->isSuperAdminNode()) {
            return true;
        }
        
        if ($nodeType === 'user' && $node instanceof User) {
            return $user->getAllDescendants()->contains('id', $node->id);
        }
        
        return false;
    }

    /**
     * Obter nós que o usuário pode gerenciar
     */
    private function getManageableNodes(User $user, string $nodeType = 'all', string $search = '')
    {
        $nodes = collect();
        
        if ($user->isSuperAdminNode()) {
            if ($nodeType === 'all' || $nodeType === 'operacao') {
                $operacoes = OrbitaOperacao::when($search, function($q, $search) {
                    return $q->where('display_name', 'like', "%{$search}%");
                })->get()->map(function($op) {
                    $op->node_type = 'operacao';
                    return $op;
                });
                $nodes = $nodes->concat($operacoes);
            }
            
            if ($nodeType === 'all' || $nodeType === 'user') {
                $users = User::whereIn('node_type', ['licenciado_l1', 'licenciado_l2', 'licenciado_l3'])
                    ->when($search, function($q, $search) {
                        return $q->where('name', 'like', "%{$search}%");
                    })->get()->map(function($user) {
                        $user->node_type = 'user';
                        return $user;
                    });
                $nodes = $nodes->concat($users);
            }
        } else {
            $descendants = $user->getAllDescendants();
            
            if ($search) {
                $descendants = $descendants->filter(function($node) use ($search) {
                    return stripos($node->name, $search) !== false;
                });
            }
            
            $nodes = $descendants->map(function($user) {
                $user->node_type = 'user';
                return $user;
            });
        }
        
        return $nodes->sortBy('name');
    }

    /**
     * Encontrar nó por tipo e ID
     */
    private function findNode(string $nodeType, int $nodeId)
    {
        switch ($nodeType) {
            case 'user':
                return User::find($nodeId);
            case 'operacao':
                return OrbitaOperacao::find($nodeId);
            case 'white_label':
                return WhiteLabel::find($nodeId);
            default:
                return null;
        }
    }

    /**
     * Obter tipo de nó para permissões
     */
    private function getNodeTypeForPermissions($node, string $nodeType): string
    {
        if ($nodeType === 'user' && $node instanceof User) {
            return $node->node_type;
        }
        
        return $nodeType;
    }

    /**
     * Obter estatísticas de permissões
     */
    private function getPermissionsStatistics(User $user): array
    {
        return [
            'total_nodes_with_permissions' => NodePermission::distinct('node_type', 'node_id')->count(),
            'total_permissions_granted' => NodePermission::where('granted', true)->count(),
            'total_permissions_revoked' => NodePermission::where('granted', false)->count(),
            'expired_permissions' => NodePermission::where('expires_at', '<', now())->count()
        ];
    }
}