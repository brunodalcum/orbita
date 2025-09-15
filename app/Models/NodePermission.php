<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class NodePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'node_type',
        'node_id',
        'permission_key',
        'granted',
        'restrictions',
        'inherit_from_parent',
        'granted_by',
        'granted_at',
        'expires_at',
        'notes'
    ];

    protected $casts = [
        'granted' => 'boolean',
        'inherit_from_parent' => 'boolean',
        'restrictions' => 'array',
        'granted_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    /**
     * Relacionamento com o usuário que concedeu a permissão
     */
    public function grantedBy()
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    /**
     * Obter o nó relacionado (polimórfico)
     */
    public function node()
    {
        switch ($this->node_type) {
            case 'user':
                return $this->belongsTo(User::class, 'node_id');
            case 'operacao':
                return $this->belongsTo(OrbitaOperacao::class, 'node_id');
            case 'white_label':
                return $this->belongsTo(WhiteLabel::class, 'node_id');
            default:
                return null;
        }
    }

    /**
     * Verificar se a permissão está ativa
     */
    public function isActive(): bool
    {
        if (!$this->granted) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Verificar se a permissão está expirada
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Obter permissões por nó
     */
    public static function getNodePermissions(string $nodeType, int $nodeId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('node_type', $nodeType)
                  ->where('node_id', $nodeId)
                  ->get();
    }

    /**
     * Verificar se um nó tem uma permissão específica
     */
    public static function hasPermission(string $nodeType, int $nodeId, string $permissionKey): bool
    {
        $permission = self::where('node_type', $nodeType)
                         ->where('node_id', $nodeId)
                         ->where('permission_key', $permissionKey)
                         ->first();

        if (!$permission) {
            return false;
        }

        return $permission->isActive();
    }

    /**
     * Conceder permissão a um nó
     */
    public static function grantPermission(
        string $nodeType,
        int $nodeId,
        string $permissionKey,
        int $grantedBy,
        array $restrictions = [],
        ?Carbon $expiresAt = null,
        string $notes = ''
    ): self {
        return self::updateOrCreate(
            [
                'node_type' => $nodeType,
                'node_id' => $nodeId,
                'permission_key' => $permissionKey
            ],
            [
                'granted' => true,
                'restrictions' => $restrictions,
                'granted_by' => $grantedBy,
                'granted_at' => now(),
                'expires_at' => $expiresAt,
                'notes' => $notes
            ]
        );
    }

    /**
     * Revogar permissão de um nó
     */
    public static function revokePermission(string $nodeType, int $nodeId, string $permissionKey): bool
    {
        return self::where('node_type', $nodeType)
                  ->where('node_id', $nodeId)
                  ->where('permission_key', $permissionKey)
                  ->update(['granted' => false]);
    }

    /**
     * Obter todas as permissões disponíveis no sistema
     */
    public static function getAvailablePermissions(): array
    {
        return [
            // Dashboard e Visualização
            'dashboard' => [
                'dashboard.view' => 'Visualizar Dashboard',
                'dashboard.metrics' => 'Ver Métricas Avançadas',
                'dashboard.export' => 'Exportar Dados do Dashboard'
            ],

            // Gerenciamento de Usuários
            'users' => [
                'users.view' => 'Visualizar Usuários',
                'users.create' => 'Criar Usuários',
                'users.edit' => 'Editar Usuários',
                'users.delete' => 'Excluir Usuários',
                'users.impersonate' => 'Impersonar Usuários'
            ],

            // Gerenciamento de Nós
            'nodes' => [
                'nodes.view' => 'Visualizar Nós',
                'nodes.create' => 'Criar Nós',
                'nodes.edit' => 'Editar Nós',
                'nodes.delete' => 'Excluir Nós',
                'nodes.manage_permissions' => 'Gerenciar Permissões de Nós'
            ],

            // Relatórios
            'reports' => [
                'reports.view' => 'Visualizar Relatórios',
                'reports.hierarchy' => 'Relatórios de Hierarquia',
                'reports.activities' => 'Relatórios de Atividades',
                'reports.performance' => 'Relatórios de Performance',
                'reports.export' => 'Exportar Relatórios'
            ],

            // Branding
            'branding' => [
                'branding.view' => 'Visualizar Configurações de Branding',
                'branding.edit' => 'Editar Branding',
                'branding.reset' => 'Resetar Branding',
                'branding.export_css' => 'Exportar CSS Personalizado'
            ],

            // Módulos
            'modules' => [
                'modules.view' => 'Visualizar Módulos',
                'modules.configure' => 'Configurar Módulos',
                'modules.enable_disable' => 'Ativar/Desativar Módulos',
                'modules.reset' => 'Resetar Configurações de Módulos'
            ],

            // Configurações
            'settings' => [
                'settings.view' => 'Visualizar Configurações',
                'settings.edit' => 'Editar Configurações',
                'settings.domains' => 'Gerenciar Domínios',
                'settings.integrations' => 'Gerenciar Integrações'
            ],

            // Auditoria
            'audit' => [
                'audit.view' => 'Visualizar Logs de Auditoria',
                'audit.export' => 'Exportar Logs de Auditoria'
            ],

            // Notificações
            'notifications' => [
                'notifications.view' => 'Visualizar Notificações',
                'notifications.send' => 'Enviar Notificações',
                'notifications.manage' => 'Gerenciar Configurações de Notificações'
            ]
        ];
    }

    /**
     * Obter permissões padrão por tipo de nó
     */
    public static function getDefaultPermissions(string $nodeType): array
    {
        $defaults = [
            'super_admin' => [
                // Super Admin tem todas as permissões
                'dashboard.view', 'dashboard.metrics', 'dashboard.export',
                'users.view', 'users.create', 'users.edit', 'users.delete', 'users.impersonate',
                'nodes.view', 'nodes.create', 'nodes.edit', 'nodes.delete', 'nodes.manage_permissions',
                'reports.view', 'reports.hierarchy', 'reports.activities', 'reports.performance', 'reports.export',
                'branding.view', 'branding.edit', 'branding.reset', 'branding.export_css',
                'modules.view', 'modules.configure', 'modules.enable_disable', 'modules.reset',
                'settings.view', 'settings.edit', 'settings.domains', 'settings.integrations',
                'audit.view', 'audit.export',
                'notifications.view', 'notifications.send', 'notifications.manage'
            ],

            'operacao' => [
                'dashboard.view', 'dashboard.metrics',
                'users.view', 'users.create', 'users.edit', 'users.impersonate',
                'nodes.view', 'nodes.create', 'nodes.edit',
                'reports.view', 'reports.hierarchy', 'reports.activities', 'reports.export',
                'branding.view', 'branding.edit',
                'modules.view', 'modules.configure', 'modules.enable_disable',
                'settings.view', 'settings.edit',
                'notifications.view', 'notifications.send'
            ],

            'white_label' => [
                'dashboard.view', 'dashboard.metrics',
                'users.view', 'users.create', 'users.edit',
                'nodes.view', 'nodes.create', 'nodes.edit',
                'reports.view', 'reports.hierarchy', 'reports.activities',
                'branding.view', 'branding.edit',
                'modules.view', 'modules.configure',
                'settings.view',
                'notifications.view'
            ],

            'licenciado_l1' => [
                'dashboard.view',
                'users.view', 'users.create', 'users.edit',
                'nodes.view', 'nodes.create',
                'reports.view', 'reports.hierarchy',
                'branding.view',
                'modules.view',
                'notifications.view'
            ],

            'licenciado_l2' => [
                'dashboard.view',
                'users.view', 'users.create',
                'nodes.view', 'nodes.create',
                'reports.view',
                'modules.view',
                'notifications.view'
            ],

            'licenciado_l3' => [
                'dashboard.view',
                'users.view',
                'reports.view',
                'modules.view',
                'notifications.view'
            ]
        ];

        return $defaults[$nodeType] ?? [];
    }

    /**
     * Aplicar permissões padrão a um nó
     */
    public static function applyDefaultPermissions(string $nodeType, int $nodeId, int $grantedBy): void
    {
        $permissions = self::getDefaultPermissions($nodeType);

        foreach ($permissions as $permission) {
            self::grantPermission($nodeType, $nodeId, $permission, $grantedBy);
        }
    }
}