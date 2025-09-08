<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'level',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level' => 'integer'
    ];

    /**
     * Relacionamento com usuários
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relacionamento com permissões
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    /**
     * Verificar se o role tem uma permissão específica
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('name', $permission)->exists();
    }

    /**
     * Verificar se o role tem permissão em um módulo
     */
    public function hasModulePermission(string $module, ?string $action = null): bool
    {
        $query = $this->permissions()->where('module', $module);
        
        if ($action) {
            $query->where('action', $action);
        }
        
        return $query->exists();
    }

    /**
     * Scope para roles ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para roles por nível
     */
    public function scopeByLevel($query, int $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Verificar se é Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->name === 'super_admin';
    }

    /**
     * Verificar se é Admin
     */
    public function isAdmin(): bool
    {
        return $this->name === 'admin';
    }

    /**
     * Verificar se é Funcionário
     */
    public function isFuncionario(): bool
    {
        return $this->name === 'funcionario';
    }

    /**
     * Verificar se é Licenciado
     */
    public function isLicenciado(): bool
    {
        return $this->name === 'licenciado';
    }
}
