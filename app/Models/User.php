<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relacionamento com role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Verificar se o usuário tem uma permissão específica
     */
    public function hasPermission(string $permission): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->hasPermission($permission);
    }

    /**
     * Verificar se o usuário tem permissão em um módulo
     */
    public function hasModulePermission(string $module, ?string $action = null): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->hasModulePermission($module, $action);
    }

    /**
     * Verificar se o usuário é Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role && $this->role->isSuperAdmin();
    }

    /**
     * Verificar se o usuário é Admin
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->isAdmin();
    }

    /**
     * Verificar se o usuário é Funcionário
     */
    public function isFuncionario(): bool
    {
        return $this->role && $this->role->isFuncionario();
    }

    /**
     * Verificar se o usuário é Licenciado
     */
    public function isLicenciado(): bool
    {
        return $this->role && $this->role->isLicenciado();
    }

    /**
     * Verificar se o usuário está ativo
     */
    public function isActive(): bool
    {
        return $this->is_active && $this->role && $this->role->is_active;
    }

    /**
     * Obter todas as permissões do usuário
     */
    public function getPermissions()
    {
        if (!$this->role) {
            return collect();
        }

        return $this->role->permissions;
    }

    /**
     * Scope para usuários ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para usuários por role
     */
    public function scopeByRole($query, string $roleName)
    {
        return $query->whereHas('role', function ($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }

    /**
     * Relacionamento com estabelecimentos (para licenciados)
     */
    public function estabelecimentos(): HasMany
    {
        return $this->hasMany(Estabelecimento::class, 'licenciado_id');
    }

    /**
     * Obter estabelecimentos ativos do licenciado
     */
    public function estabelecimentosAtivos(): HasMany
    {
        return $this->estabelecimentos()->where('ativo', true);
    }

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class, 'licenciado_id');
    }

    public function contractsApproved(): HasMany
    {
        return $this->hasMany(Contract::class, 'approved_by');
    }

    public function contractsSent(): HasMany
    {
        return $this->hasMany(Contract::class, 'contract_sent_by');
    }
}
