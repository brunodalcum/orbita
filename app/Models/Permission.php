<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'module',
        'action',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Relacionamento com roles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    /**
     * Scope para permissões ativas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para permissões por módulo
     */
    public function scopeByModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope para permissões por ação
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Verificar se a permissão é de visualização
     */
    public function isViewPermission(): bool
    {
        return $this->action === 'view';
    }

    /**
     * Verificar se a permissão é de criação
     */
    public function isCreatePermission(): bool
    {
        return $this->action === 'create';
    }

    /**
     * Verificar se a permissão é de edição
     */
    public function isUpdatePermission(): bool
    {
        return $this->action === 'update';
    }

    /**
     * Verificar se a permissão é de exclusão
     */
    public function isDeletePermission(): bool
    {
        return $this->action === 'delete';
    }

    /**
     * Verificar se a permissão é de gerenciamento completo
     */
    public function isManagePermission(): bool
    {
        return $this->action === 'manage';
    }
}
