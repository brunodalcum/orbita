<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HierarchyNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'category', 'title', 'message', 'data',
        'sender_id', 'sender_type', 'sender_name',
        'recipient_id', 'recipient_type',
        'scope', 'scope_node_id', 'scope_node_type',
        'delivery_method', 'scheduled_at', 'sent_at', 'read_at', 'expires_at',
        'status', 'priority', 'is_persistent', 'requires_action',
        'action_url', 'action_text', 'metadata',
        'reference_type', 'reference_id'
    ];

    protected $casts = [
        'data' => 'array',
        'metadata' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_persistent' => 'boolean',
        'requires_action' => 'boolean'
    ];

    /**
     * Relacionamento com o remetente
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relacionamento com o destinatário
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Criar notificação para usuário específico
     */
    public static function createForUser(
        User $recipient,
        string $type,
        string $title,
        string $message,
        ?User $sender = null,
        array $options = []
    ): self {
        $currentUser = $sender ?? Auth::user();
        
        return self::create(array_merge([
            'type' => $type,
            'category' => $options['category'] ?? 'general',
            'title' => $title,
            'message' => $message,
            'sender_id' => $currentUser?->id,
            'sender_type' => 'user',
            'sender_name' => $currentUser?->name,
            'recipient_id' => $recipient->id,
            'recipient_type' => 'user',
            'scope' => 'direct',
            'delivery_method' => 'internal',
            'status' => 'sent',
            'priority' => 'normal',
            'sent_at' => now()
        ], $options));
    }

    /**
     * Criar notificação do sistema
     */
    public static function createSystemNotification(
        string $type,
        string $title,
        string $message,
        ?User $recipient = null,
        array $options = []
    ): self {
        return self::create(array_merge([
            'type' => $type,
            'category' => $options['category'] ?? 'system',
            'title' => $title,
            'message' => $message,
            'sender_type' => 'system',
            'sender_name' => 'Sistema Órbita',
            'recipient_id' => $recipient?->id,
            'recipient_type' => $recipient ? 'user' : null,
            'scope' => $recipient ? 'direct' : 'all',
            'delivery_method' => 'internal',
            'status' => 'sent',
            'priority' => 'normal',
            'sent_at' => now()
        ], $options));
    }

    /**
     * Marcar como lida
     */
    public function markAsRead(): bool
    {
        if ($this->read_at) {
            return false;
        }

        $this->update([
            'read_at' => now(),
            'status' => 'read'
        ]);

        return true;
    }

    /**
     * Obter notificações para um usuário
     */
    public static function getForUser(User $user, int $limit = 20, bool $unreadOnly = false): \Illuminate\Database\Eloquent\Collection
    {
        $query = self::where('recipient_id', $user->id)
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    })
                    ->orderBy('created_at', 'desc');

        if ($unreadOnly) {
            $query->whereNull('read_at');
        }

        return $query->limit($limit)->get();
    }

    /**
     * Contar notificações não lidas
     */
    public static function getUnreadCountForUser(User $user): int
    {
        return self::where('recipient_id', $user->id)
                  ->whereNull('read_at')
                  ->where(function($q) {
                      $q->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                  })
                  ->count();
    }

    /**
     * Obter estatísticas de notificações
     */
    public static function getStatistics(User $user): array
    {
        $baseQuery = self::where('recipient_id', $user->id);
        
        return [
            'total' => $baseQuery->count(),
            'unread' => $baseQuery->clone()->whereNull('read_at')->count(),
            'by_type' => [
                'info' => $baseQuery->clone()->where('type', 'info')->count(),
                'success' => $baseQuery->clone()->where('type', 'success')->count(),
                'warning' => $baseQuery->clone()->where('type', 'warning')->count(),
                'error' => $baseQuery->clone()->where('type', 'error')->count()
            ],
            'requires_action' => $baseQuery->clone()->where('requires_action', true)->whereNull('read_at')->count()
        ];
    }

    /**
     * Obter ícone baseado no tipo
     */
    public function getTypeIconAttribute(): string
    {
        $icons = [
            'info' => 'information-circle',
            'success' => 'check-circle',
            'warning' => 'exclamation-triangle',
            'error' => 'x-circle',
            'alert' => 'bell'
        ];
        
        return $icons[$this->type] ?? 'bell';
    }

    /**
     * Obter cor baseada no tipo
     */
    public function getTypeColorAttribute(): string
    {
        $colors = [
            'info' => 'blue',
            'success' => 'green',
            'warning' => 'yellow',
            'error' => 'red',
            'alert' => 'purple'
        ];
        
        return $colors[$this->type] ?? 'gray';
    }

    /**
     * Scope para notificações não lidas
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope para notificações ativas
     */
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }
}