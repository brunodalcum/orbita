<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ImpersonationController extends Controller
{
    /**
     * Iniciar impersonação
     */
    public function start(Request $request, $userId)
    {
        $currentUser = Auth::user();
        $targetUser = User::findOrFail($userId);

        // Verificar se o usuário atual pode impersonar o usuário alvo
        if (!$currentUser->canImpersonate($targetUser)) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para impersonar este usuário.'
            ], 403);
        }

        // Verificar se já não está impersonando alguém
        if (Session::has('impersonating')) {
            return response()->json([
                'success' => false,
                'message' => 'Você já está impersonando outro usuário. Termine a impersonação atual primeiro.'
            ], 400);
        }

        // Salvar informações da impersonação na sessão
        Session::put('impersonating', [
            'original_user_id' => $currentUser->id,
            'target_user_id' => $targetUser->id,
            'started_at' => Carbon::now(),
            'original_user_name' => $currentUser->name,
            'target_user_name' => $targetUser->name
        ]);

        // Log da impersonação para auditoria
        Log::info('Impersonation started', [
            'original_user_id' => $currentUser->id,
            'original_user_email' => $currentUser->email,
            'target_user_id' => $targetUser->id,
            'target_user_email' => $targetUser->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Fazer login como o usuário alvo
        Auth::login($targetUser);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Agora você está operando como {$targetUser->name}",
                'redirect_url' => route('dashboard')
            ]);
        }

        return redirect()->route('dashboard')->with('success', "Agora você está operando como {$targetUser->name}");
    }

    /**
     * Terminar impersonação
     */
    public function stop(Request $request)
    {
        if (!Session::has('impersonating')) {
            return response()->json([
                'success' => false,
                'message' => 'Você não está impersonando nenhum usuário.'
            ], 400);
        }

        $impersonationData = Session::get('impersonating');
        $originalUser = User::find($impersonationData['original_user_id']);

        if (!$originalUser) {
            Session::forget('impersonating');
            return response()->json([
                'success' => false,
                'message' => 'Usuário original não encontrado. Sessão limpa.'
            ], 400);
        }

        // Log do fim da impersonação
        Log::info('Impersonation ended', [
            'original_user_id' => $originalUser->id,
            'original_user_email' => $originalUser->email,
            'target_user_id' => $impersonationData['target_user_id'],
            'target_user_name' => $impersonationData['target_user_name'],
            'duration_minutes' => Carbon::now()->diffInMinutes($impersonationData['started_at']),
            'ip_address' => $request->ip()
        ]);

        // Remover dados da impersonação da sessão
        Session::forget('impersonating');

        // Fazer login como o usuário original
        Auth::login($originalUser);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Você voltou a operar como {$originalUser->name}",
                'redirect_url' => route('dashboard')
            ]);
        }

        return redirect()->route('dashboard')->with('success', "Você voltou a operar como {$originalUser->name}");
    }

    /**
     * Obter status da impersonação
     */
    public function status(Request $request)
    {
        $isImpersonating = Session::has('impersonating');
        $impersonationData = $isImpersonating ? Session::get('impersonating') : null;

        return response()->json([
            'is_impersonating' => $isImpersonating,
            'data' => $impersonationData
        ]);
    }

    /**
     * Listar usuários disponíveis para impersonação
     */
    public function availableUsers(Request $request)
    {
        $currentUser = Auth::user();
        $search = $request->get('search', '');
        $limit = $request->get('limit', 20);

        // Obter descendentes que o usuário atual pode impersonar
        $availableUsers = collect();

        if ($currentUser->isSuperAdminNode()) {
            // Super Admin pode impersonar qualquer um
            $query = User::where('id', '!=', $currentUser->id);
        } else {
            // Outros usuários só podem impersonar descendentes
            $descendants = $currentUser->getAllDescendants();
            if ($descendants->isEmpty()) {
                return response()->json([
                    'users' => [],
                    'total' => 0
                ]);
            }
            
            $descendantIds = $descendants->pluck('id')->toArray();
            $query = User::whereIn('id', $descendantIds);
        }

        // Aplicar filtro de busca
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->select(['id', 'name', 'email', 'node_type', 'hierarchy_level', 'is_active'])
                      ->orderBy('hierarchy_level')
                      ->orderBy('name')
                      ->limit($limit)
                      ->get();

        $total = $query->count();

        return response()->json([
            'users' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'node_type' => $user->node_type,
                    'node_type_label' => ucfirst(str_replace('_', ' ', $user->node_type)),
                    'hierarchy_level' => $user->hierarchy_level,
                    'is_active' => $user->is_active
                ];
            }),
            'total' => $total
        ]);
    }

    /**
     * Histórico de impersonações (últimas 50)
     */
    public function history(Request $request)
    {
        $currentUser = Auth::user();
        
        // Por enquanto, retornar dados simulados
        // Em produção, isso viria de uma tabela de auditoria
        $history = [
            [
                'id' => 1,
                'original_user_name' => $currentUser->name,
                'target_user_name' => 'Licenciado L2 Alpha',
                'target_user_email' => 'l2.alpha@exemplo.com',
                'started_at' => Carbon::now()->subHours(2),
                'ended_at' => Carbon::now()->subHours(1)->subMinutes(30),
                'duration_minutes' => 30,
                'ip_address' => '192.168.1.100'
            ],
            [
                'id' => 2,
                'original_user_name' => $currentUser->name,
                'target_user_name' => 'Licenciado L1 Alpha',
                'target_user_email' => 'l1.alpha@exemplo.com',
                'started_at' => Carbon::now()->subDays(1),
                'ended_at' => Carbon::now()->subDays(1)->addMinutes(15),
                'duration_minutes' => 15,
                'ip_address' => '192.168.1.100'
            ]
        ];

        return response()->json([
            'history' => $history,
            'total' => count($history)
        ]);
    }
}