<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        // Campos de hierarquia
        'parent_id',
        'node_type',
        'hierarchy_level',
        'hierarchy_path',
        'operacao_id',
        'white_label_id',
        'node_settings',
        'modules',
        'domain',
        'subdomain'
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
            // Casts para hierarquia
            'hierarchy_level' => 'integer',
            'node_settings' => 'array',
            'modules' => 'array',
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

    // ========================================
    // RELACIONAMENTOS DA HIERARQUIA WHITE LABEL
    // ========================================

    /**
     * Relacionamento com pai na hierarquia
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Relacionamento com filhos na hierarquia
     */
    public function children(): HasMany
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    /**
     * Relacionamento com operação
     */
    public function orbitaOperacao(): BelongsTo
    {
        return $this->belongsTo(OrbitaOperacao::class, 'operacao_id');
    }

    /**
     * Relacionamento com White Label
     */
    public function whiteLabel(): BelongsTo
    {
        return $this->belongsTo(WhiteLabel::class, 'white_label_id');
    }

    /**
     * Relacionamento com branding
     */
    public function branding(): HasOne
    {
        return $this->hasOne(NodeBranding::class, 'node_id');
    }
    
    /**
     * Obter branding específico do nó
     */
    public function getNodeBranding()
    {
        return NodeBranding::where('node_type', $this->node_type ?? 'user')
                          ->where('node_id', $this->id)
                          ->first();
    }

    // ========================================
    // MÉTODOS DA HIERARQUIA WHITE LABEL
    // ========================================

    /**
     * Verificar se é Super Admin
     */
    public function isSuperAdminNode(): bool
    {
        return $this->node_type === 'super_admin';
    }

    /**
     * Verificar se é nó de Operação
     */
    public function isOperacaoNode(): bool
    {
        return $this->node_type === 'operacao';
    }

    /**
     * Verificar se é nó de White Label
     */
    public function isWhiteLabelNode(): bool
    {
        return $this->node_type === 'white_label';
    }

    /**
     * Verificar se é Licenciado (qualquer nível)
     */
    public function isLicenciadoNode(): bool
    {
        return in_array($this->node_type, ['licenciado_l1', 'licenciado_l2', 'licenciado_l3']);
    }

    /**
     * Verificar se é Licenciado L3 (não pode ter filhos)
     */
    public function isLicenciadoL3(): bool
    {
        return $this->node_type === 'licenciado_l3';
    }

    /**
     * Verificar se pode ter filhos
     */
    public function canHaveChildren(): bool
    {
        return !$this->isLicenciadoL3();
    }

    /**
     * Obter todos os descendentes
     */
    public function getAllDescendants()
    {
        $descendants = collect();
        
        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->getAllDescendants());
        }
        
        return $descendants;
    }

    /**
     * Obter todos os ancestrais
     */
    public function getAllAncestors()
    {
        $ancestors = collect();
        $current = $this->parent;
        
        while ($current) {
            $ancestors->push($current);
            $current = $current->parent;
        }
        
        return $ancestors;
    }

    /**
     * Obter caminho completo na hierarquia
     */
    public function getHierarchyPath(): string
    {
        if ($this->hierarchy_path) {
            return $this->hierarchy_path;
        }
        
        $ancestors = $this->getAllAncestors()->reverse();
        $path = $ancestors->pluck('id')->push($this->id)->implode('/');
        
        // Atualizar o campo hierarchy_path
        $this->update(['hierarchy_path' => $path]);
        
        return $path;
    }

    /**
     * Verificar se um módulo está ativo (com herança)
     */
    public function hasModuleAccess(string $moduleName): bool
    {
        $modules = $this->modules ?? [];
        
        // Se definido localmente, usar configuração local
        if (isset($modules[$moduleName])) {
            return $modules[$moduleName]['enabled'] === true;
        }
        
        // Herdar do White Label se existir
        if ($this->whiteLabel) {
            return $this->whiteLabel->hasModule($moduleName);
        }
        
        // Herdar da Operação se existir
        if ($this->orbitaOperacao) {
            return $this->orbitaOperacao->hasModule($moduleName);
        }
        
        // Herdar do pai se existir
        if ($this->parent) {
            return $this->parent->hasModuleAccess($moduleName);
        }
        
        return false;
    }

    /**
     * Obter configurações de branding com herança
     */
    public function getBrandingWithInheritance(): array
    {
        $nodeBranding = $this->getNodeBranding();
        $branding = $nodeBranding?->getBrandingWithInheritance() ?? [];
        
        if (empty($branding) || ($nodeBranding && $nodeBranding->inherit_from_parent)) {
            $parentBranding = $this->getParentBranding();
            $branding = array_merge($parentBranding, $branding);
        }
        
        // Garantir que as chaves essenciais sempre existam
        $defaultBranding = [
            'logo_url' => null,
            'logo_small_url' => null,
            'favicon_url' => null,
            'primary_color' => '#3B82F6',
            'secondary_color' => '#6B7280',
            'accent_color' => '#10B981',
            'text_color' => '#1F2937',
            'background_color' => '#FFFFFF',
            'font_family' => 'Inter',
            'custom_css' => '',
            'inherit_from_parent' => false,
        ];
        
        $finalBranding = array_merge($defaultBranding, $branding);
        
        // Super Admin usa logomarca da Órbita por padrão, mas permite personalização
        if ($this->isSuperAdminNode()) {
            // Se não há branding personalizado, usar logo da Órbita
            if (!$nodeBranding || $nodeBranding->inherit_from_parent) {
                $finalBranding['logo_url'] = 'branding/orbita/orbita-logo.svg';
                $finalBranding['logo_small_url'] = 'branding/orbita/orbita-logo-small.svg';
                $finalBranding['favicon_url'] = 'branding/orbita/orbita-favicon.svg';
            }
            // Se há branding personalizado, manter as configurações personalizadas
        }
        
        return $finalBranding;
    }

    /**
     * Obter configurações de branding sem forçar logo Órbita (para edição)
     */
    public function getBrandingForEditing(): array
    {
        $nodeBranding = $this->getNodeBranding();
        $branding = $nodeBranding?->getBrandingWithInheritance() ?? [];
        
        if (empty($branding) || ($nodeBranding && $nodeBranding->inherit_from_parent)) {
            $parentBranding = $this->getParentBranding();
            $branding = array_merge($parentBranding, $branding);
        }
        
        // Garantir que as chaves essenciais sempre existam
        $defaultBranding = [
            'logo_url' => null,
            'logo_small_url' => null,
            'favicon_url' => null,
            'primary_color' => '#3B82F6',
            'secondary_color' => '#6B7280',
            'accent_color' => '#10B981',
            'text_color' => '#1F2937',
            'background_color' => '#FFFFFF',
            'font_family' => 'Inter',
            'custom_css' => '',
            'inherit_from_parent' => false,
        ];
        
        // Para edição, não forçar logo da Órbita - permitir personalização
        return array_merge($defaultBranding, $branding);
    }

    /**
     * Obter branding do pai na hierarquia
     */
    public function getParentBranding(): array
    {
        if ($this->whiteLabel) {
            return $this->whiteLabel->getBrandingWithInheritance();
        }
        
        if ($this->orbitaOperacao) {
            return $this->orbitaOperacao->getBrandingWithInheritance();
        }
        
        if ($this->parent) {
            return $this->parent->getBrandingWithInheritance();
        }
        
        // Retornar configurações padrão se não há pai (Super Admin usa Órbita)
        return [
            'logo_url' => 'branding/orbita/orbita-logo.svg',
            'logo_small_url' => 'branding/orbita/orbita-logo-small.svg',
            'favicon_url' => 'branding/orbita/orbita-favicon.svg',
            'primary_color' => '#3B82F6',
            'secondary_color' => '#6B7280',
            'accent_color' => '#10B981',
            'text_color' => '#1F2937',
            'background_color' => '#FFFFFF',
            'font_family' => 'Inter',
            'custom_css' => '',
            'inherit_from_parent' => false,
        ];
    }

    /**
     * Verificar se pode impersonar outro usuário
     */
    public function canImpersonate(User $target): bool
    {
        // Super Admin pode impersonar qualquer um
        if ($this->isSuperAdminNode()) {
            return true;
        }
        
        // Verificar se o target é descendente
        return $this->getAllDescendants()->contains('id', $target->id);
    }

    /**
     * Criar filho na hierarquia
     */
    public function createChild(array $userData): User
    {
        if (!$this->canHaveChildren()) {
            throw new \Exception('Este nó não pode ter filhos');
        }
        
        // Determinar o tipo do filho baseado no tipo do pai
        $childNodeType = $this->getChildNodeType();
        
        $userData = array_merge($userData, [
            'parent_id' => $this->id,
            'node_type' => $childNodeType,
            'hierarchy_level' => $this->hierarchy_level + 1,
            'operacao_id' => $this->operacao_id,
            'white_label_id' => $this->white_label_id,
        ]);
        
        $child = User::create($userData);
        $child->getHierarchyPath(); // Gerar hierarchy_path
        
        return $child;
    }

    /**
     * Determinar tipo do nó filho baseado no pai
     */
    private function getChildNodeType(): string
    {
        switch ($this->node_type) {
            case 'operacao':
            case 'white_label':
                return 'licenciado_l1';
            case 'licenciado_l1':
                return 'licenciado_l2';
            case 'licenciado_l2':
                return 'licenciado_l3';
            default:
                throw new \Exception('Tipo de nó inválido para criar filhos');
        }
    }

    /**
     * Scope para usuários por tipo de nó
     */
    public function scopeByNodeType($query, string $nodeType)
    {
        return $query->where('node_type', $nodeType);
    }

    /**
     * Scope para descendentes de um usuário
     */
    public function scopeDescendantsOf($query, int $userId)
    {
        return $query->where('hierarchy_path', 'like', "%/{$userId}/%")
                    ->orWhere('hierarchy_path', 'like', "{$userId}/%");
    }
}
