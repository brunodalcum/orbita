<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NodeDomain;
use App\Models\User;
use App\Models\OrbitaOperacao;
use App\Models\WhiteLabel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DomainsController extends Controller
{
    /**
     * Interface principal de gerenciamento de domínios
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$this->canManageDomains($user)) {
            abort(403, 'Você não tem permissão para gerenciar domínios');
        }
        
        $status = $request->get('status', 'all');
        $type = $request->get('type', 'all');
        $search = $request->get('search', '');
        
        $domains = $this->getManageableDomains($user, $status, $type, $search);
        $stats = NodeDomain::getStatistics();
        
        return view('hierarchy.domains.index', compact(
            'user', 'domains', 'stats', 'status', 'type', 'search'
        ));
    }

    /**
     * Gerenciar domínios de um nó específico
     */
    public function manage($nodeType, $nodeId)
    {
        $user = Auth::user();
        
        if (!$this->canManageDomains($user)) {
            abort(403, 'Você não tem permissão para gerenciar domínios');
        }
        
        $node = $this->findNode($nodeType, $nodeId);
        if (!$node || !$this->canManageNodeDomains($user, $node, $nodeType)) {
            abort(403, 'Nó não encontrado ou sem permissão');
        }
        
        $domains = NodeDomain::getNodeDomains($nodeType, $nodeId);
        $primaryDomain = NodeDomain::getPrimaryDomain($nodeType, $nodeId);
        
        return view('hierarchy.domains.manage', compact(
            'user', 'node', 'nodeType', 'domains', 'primaryDomain'
        ));
    }

    /**
     * Criar novo domínio
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$this->canManageDomains($user)) {
            return response()->json(['success' => false, 'message' => 'Sem permissão'], 403);
        }
        
        $validator = $this->getValidator($request);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        
        try {
            $nodeType = $request->get('node_type');
            $nodeId = $request->get('node_id');
            
            $domain = NodeDomain::createForNode(
                $nodeType,
                $nodeId,
                $request->only(['domain_type', 'domain', 'subdomain', 'base_domain', 'is_primary', 'ssl_enabled', 'notes']),
                $user->id
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Domínio criado com sucesso!',
                'domain' => $domain
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar domínio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualizar domínio
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$this->canManageDomains($user)) {
            return response()->json(['success' => false, 'message' => 'Sem permissão'], 403);
        }
        
        $domain = NodeDomain::find($id);
        if (!$domain) {
            return response()->json(['success' => false, 'message' => 'Domínio não encontrado'], 404);
        }
        
        try {
            $domain->update($request->only([
                'domain_type', 'domain', 'subdomain', 'base_domain',
                'is_primary', 'is_active', 'ssl_enabled', 'notes'
            ]));
            
            return response()->json([
                'success' => true,
                'message' => 'Domínio atualizado com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar domínio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar domínio
     */
    public function verify($id)
    {
        $domain = NodeDomain::find($id);
        if (!$domain) {
            return response()->json(['success' => false, 'message' => 'Domínio não encontrado'], 404);
        }
        
        try {
            $domain->markAsVerified();
            
            return response()->json([
                'success' => true,
                'message' => 'Domínio verificado com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao verificar domínio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Alternar status ativo/inativo
     */
    public function toggleStatus($id)
    {
        $domain = NodeDomain::find($id);
        if (!$domain) {
            return response()->json(['success' => false, 'message' => 'Domínio não encontrado'], 404);
        }
        
        try {
            $domain->is_active = !$domain->is_active;
            $domain->save();
            
            return response()->json([
                'success' => true,
                'message' => $domain->is_active ? 'Domínio ativado' : 'Domínio desativado',
                'is_active' => $domain->is_active
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar se o usuário pode gerenciar domínios
     */
    private function canManageDomains(User $user): bool
    {
        return $user->isSuperAdminNode() || $user->isOperacaoNode() || $user->isWhiteLabelNode();
    }

    /**
     * Verificar se pode gerenciar domínios de um nó específico
     */
    private function canManageNodeDomains(User $user, $node, string $nodeType): bool
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
     * Obter domínios que o usuário pode gerenciar
     */
    private function getManageableDomains(User $user, string $status, string $type, string $search)
    {
        $query = NodeDomain::with(['createdBy']);
        
        if (!$user->isSuperAdminNode()) {
            $descendants = $user->getAllDescendants();
            $descendantIds = $descendants->pluck('id');
            
            $query->where('node_type', 'user')->whereIn('node_id', $descendantIds);
        }
        
        if ($status !== 'all') {
            switch ($status) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'verified':
                    $query->where('verified', true);
                    break;
            }
        }
        
        if ($type !== 'all') {
            $query->where('domain_type', $type);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('domain', 'like', "%{$search}%")
                  ->orWhere('subdomain', 'like', "%{$search}%");
            });
        }
        
        return $query->orderBy('created_at', 'desc')->paginate(20);
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
     * Obter validador para domínio
     */
    private function getValidator(Request $request): \Illuminate\Validation\Validator
    {
        $rules = [
            'node_type' => 'required|in:user,operacao,white_label',
            'node_id' => 'required|integer',
            'domain_type' => 'required|in:custom,subdomain',
            'base_domain' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
            'ssl_enabled' => 'boolean',
            'notes' => 'nullable|string|max:500'
        ];
        
        if ($request->get('domain_type') === 'custom') {
            $rules['domain'] = 'required|url|max:255';
        } else {
            $rules['subdomain'] = 'required|string|max:100|regex:/^[a-z0-9-]+$/';
        }
        
        return Validator::make($request->all(), $rules);
    }
}