<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'user_name', 'user_type', 'impersonated_by', 'action',
        'resource_type', 'resource_id', 'resource_name', 'old_values', 'new_values',
        'metadata', 'ip_address', 'user_agent', 'session_id', 'request_id',
        'severity', 'description', 'context', 'sensitive', 'occurred_at'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
        'context' => 'array',
        'sensitive' => 'boolean',
        'occurred_at' => 'datetime'
    ];

    /**
     * Relacionamento com o usuário que executou a ação
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com o usuário que estava sendo impersonado
     */
    public function impersonatedBy()
    {
        return $this->belongsTo(User::class, 'impersonated_by');
    }

    /**
     * Registrar uma ação de auditoria
     */
    public static function log(
        string $action,
        string $resourceType,
        ?int $resourceId = null,
        ?string $resourceName = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        string $severity = 'info',
        ?string $description = null,
        ?array $context = null,
        bool $sensitive = false
    ): self {
        $user = Auth::user();
        $impersonatedBy = session('impersonated_by');
        
        return self::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'user_type' => $user?->node_type,
            'impersonated_by' => $impersonatedBy,
            'action' => $action,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'resource_name' => $resourceName,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'metadata' => [
                'url' => Request::fullUrl(),
                'method' => Request::method(),
                'route' => Request::route()?->getName(),
            ],
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'session_id' => session()->getId(),
            'request_id' => Str::uuid()->toString(),
            'severity' => $severity,
            'description' => $description ?? self::generateDescription($action, $resourceType, $resourceName),
            'context' => $context,
            'sensitive' => $sensitive,
            'occurred_at' => now()
        ]);
    }

    /**
     * Registrar login
     */
    public static function logLogin(User $user, bool $successful = true): self
    {
        return self::log(
            $successful ? 'login' : 'login_failed',
            'authentication',
            $user->id,
            $user->name,
            null,
            null,
            $successful ? 'info' : 'warning',
            $successful ? "Usuário {$user->name} fez login" : "Tentativa de login falhada para {$user->name}"
        );
    }

    /**
     * Registrar criação de recurso
     */
    public static function logCreate(string $resourceType, $resource, ?string $description = null): self
    {
        $resourceId = is_object($resource) ? $resource->id : null;
        $resourceName = is_object($resource) ? ($resource->name ?? $resource->display_name ?? null) : $resource;
        $newValues = is_object($resource) ? $resource->toArray() : ['value' => $resource];

        return self::log('create', $resourceType, $resourceId, $resourceName, null, $newValues, 'info', $description);
    }

    /**
     * Registrar atualização de recurso
     */
    public static function logUpdate(string $resourceType, $resource, array $oldValues, ?string $description = null): self
    {
        $resourceId = is_object($resource) ? $resource->id : null;
        $resourceName = is_object($resource) ? ($resource->name ?? $resource->display_name ?? null) : $resource;
        $newValues = is_object($resource) ? $resource->toArray() : ['value' => $resource];

        return self::log('update', $resourceType, $resourceId, $resourceName, $oldValues, $newValues, 'info', $description);
    }

    /**
     * Obter logs por usuário
     */
    public static function getByUser(int $userId, int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('user_id', $userId)->orderBy('occurred_at', 'desc')->limit($limit)->get();
    }

    /**
     * Obter estatísticas de auditoria
     */
    public static function getStatistics(?Carbon $start = null, ?Carbon $end = null): array
    {
        $query = self::query();
        
        if ($start && $end) {
            $query->whereBetween('occurred_at', [$start, $end]);
        }
        
        return [
            'total_logs' => $query->count(),
            'by_action' => $query->groupBy('action')->selectRaw('action, count(*) as count')->pluck('count', 'action')->toArray(),
            'by_severity' => $query->groupBy('severity')->selectRaw('severity, count(*) as count')->pluck('count', 'severity')->toArray(),
            'unique_users' => $query->distinct('user_id')->count('user_id'),
            'sensitive_logs' => $query->where('sensitive', true)->count()
        ];
    }

    /**
     * Gerar descrição automática
     */
    private static function generateDescription(string $action, string $resourceType, ?string $resourceName): string
    {
        $actionMap = [
            'create' => 'criou',
            'update' => 'atualizou',
            'delete' => 'excluiu',
            'login' => 'fez login',
            'logout' => 'fez logout'
        ];
        
        $resourceMap = [
            'user' => 'usuário',
            'domain' => 'domínio',
            'permission' => 'permissão',
            'node' => 'nó'
        ];
        
        $actionText = $actionMap[$action] ?? $action;
        $resourceText = $resourceMap[$resourceType] ?? $resourceType;
        
        if ($resourceName) {
            return "Usuário {$actionText} {$resourceText} '{$resourceName}'";
        }
        
        return "Usuário {$actionText} {$resourceText}";
    }

    /**
     * Obter ícone baseado na ação
     */
    public function getActionIconAttribute(): string
    {
        $icons = [
            'create' => 'plus-circle',
            'update' => 'pencil',
            'delete' => 'trash',
            'login' => 'login',
            'logout' => 'logout'
        ];
        
        return $icons[$this->action] ?? 'activity';
    }

    /**
     * Obter cor baseada na severidade
     */
    public function getSeverityColorAttribute(): string
    {
        $colors = [
            'info' => 'blue',
            'warning' => 'yellow',
            'error' => 'red',
            'critical' => 'red'
        ];
        
        return $colors[$this->severity] ?? 'gray';
    }

    /**
     * Scope para logs recentes
     */
    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('occurred_at', '>=', now()->subHours($hours));
    }
}