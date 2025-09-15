<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HierarchyNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Interface principal de notificações
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all'); // all, unread, read
        $category = $request->get('category', 'all');
        
        $notifications = $this->getUserNotifications($user, $filter, $category);
        $stats = HierarchyNotification::getStatistics($user);
        
        return view('hierarchy.notifications.index', compact(
            'user', 'notifications', 'stats', 'filter', 'category'
        ));
    }

    /**
     * Marcar notificação como lida
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = HierarchyNotification::where('id', $id)
                                           ->where('recipient_id', $user->id)
                                           ->first();
        
        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notificação não encontrada'], 404);
        }
        
        $wasRead = $notification->markAsRead();
        
        return response()->json([
            'success' => true,
            'was_read' => $wasRead,
            'message' => $wasRead ? 'Notificação marcada como lida' : 'Notificação já estava lida'
        ]);
    }

    /**
     * Marcar todas as notificações como lidas
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        $count = HierarchyNotification::where('recipient_id', $user->id)
                                    ->whereNull('read_at')
                                    ->update([
                                        'read_at' => now(),
                                        'status' => 'read'
                                    ]);
        
        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => "Todas as {$count} notificações foram marcadas como lidas"
        ]);
    }

    /**
     * Obter contagem de notificações não lidas (API)
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $count = HierarchyNotification::getUnreadCountForUser($user);
        
        return response()->json(['count' => $count]);
    }

    /**
     * Obter notificações recentes (API)
     */
    public function getRecent(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 10);
        
        $notifications = HierarchyNotification::getForUser($user, $limit);
        
        return response()->json([
            'notifications' => $notifications->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'is_read' => $notification->isRead(),
                    'time_ago' => $notification->created_at->diffForHumans(),
                    'action_url' => $notification->action_url,
                    'action_text' => $notification->action_text,
                    'type_icon' => $notification->type_icon,
                    'type_color' => $notification->type_color
                ];
            })
        ]);
    }

    /**
     * Criar nova notificação (para admins)
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!$this->canCreateNotifications($user)) {
            abort(403, 'Você não tem permissão para criar notificações');
        }
        
        $recipients = $this->getAvailableRecipients($user);
        
        return view('hierarchy.notifications.create', compact('user', 'recipients'));
    }

    /**
     * Armazenar nova notificação
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$this->canCreateNotifications($user)) {
            return response()->json(['success' => false, 'message' => 'Sem permissão'], 403);
        }
        
        $request->validate([
            'type' => 'required|in:info,success,warning,error,alert',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*' => 'exists:users,id',
            'category' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'expires_at' => 'nullable|date|after:now',
            'action_url' => 'nullable|url',
            'action_text' => 'nullable|string|max:50'
        ]);
        
        $created = 0;
        $recipients = User::whereIn('id', $request->recipients)->get();
        
        foreach ($recipients as $recipient) {
            HierarchyNotification::createForUser(
                $recipient,
                $request->type,
                $request->title,
                $request->message,
                $user,
                [
                    'category' => $request->category,
                    'priority' => $request->priority,
                    'expires_at' => $request->expires_at,
                    'action_url' => $request->action_url,
                    'action_text' => $request->action_text,
                    'requires_action' => !empty($request->action_url)
                ]
            );
            $created++;
        }
        
        return response()->json([
            'success' => true,
            'message' => "Notificação enviada para {$created} usuários",
            'count' => $created
        ]);
    }

    /**
     * Excluir notificação
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $notification = HierarchyNotification::where('id', $id)
                                           ->where('recipient_id', $user->id)
                                           ->first();
        
        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notificação não encontrada'], 404);
        }
        
        $notification->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Notificação excluída com sucesso'
        ]);
    }

    /**
     * Obter notificações do usuário com filtros
     */
    private function getUserNotifications(User $user, string $filter, string $category)
    {
        $query = HierarchyNotification::where('recipient_id', $user->id)
                                    ->active()
                                    ->orderBy('created_at', 'desc');
        
        if ($filter === 'unread') {
            $query->unread();
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }
        
        if ($category !== 'all') {
            $query->where('category', $category);
        }
        
        return $query->paginate(20);
    }

    /**
     * Verificar se pode criar notificações
     */
    private function canCreateNotifications(User $user): bool
    {
        return $user->isSuperAdminNode() || $user->isOperacaoNode() || $user->isWhiteLabelNode();
    }

    /**
     * Obter destinatários disponíveis
     */
    private function getAvailableRecipients(User $user): \Illuminate\Database\Eloquent\Collection
    {
        if ($user->isSuperAdminNode()) {
            return User::select('id', 'name', 'email', 'node_type')->orderBy('name')->get();
        }
        
        $descendants = $user->getAllDescendants();
        return $descendants->concat(collect([$user]))->sortBy('name');
    }
}