<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WhiteLabel extends Model
{
    use HasFactory;

    protected $fillable = [
        'operacao_id',
        'name',
        'display_name',
        'description',
        'domain',
        'subdomain',
        'branding',
        'modules',
        'settings',
        'is_active'
    ];

    protected $casts = [
        'branding' => 'array',
        'modules' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Relacionamento com a operação pai
     */
    public function operacao(): BelongsTo
    {
        return $this->belongsTo(OrbitaOperacao::class, 'operacao_id');
    }

    /**
     * Relacionamento com usuários do White Label
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'white_label_id');
    }

    /**
     * Relacionamento com licenciados L1 do White Label
     */
    public function licenciadosL1(): HasMany
    {
        return $this->hasMany(User::class, 'white_label_id')
                    ->where('node_type', 'licenciado_l1');
    }

    /**
     * Relacionamento com branding
     */
    public function branding(): HasOne
    {
        return $this->hasOne(NodeBranding::class, 'node_id')
                    ->where('node_type', 'white_label');
    }

    /**
     * Obter todos os descendentes do White Label
     */
    public function getAllDescendants()
    {
        $descendants = collect();
        
        // Usuários diretos (L1)
        $l1Users = $this->licenciadosL1()->get();
        $descendants = $descendants->merge($l1Users);
        
        // Para cada L1, obter seus descendentes (L2, L3)
        foreach ($l1Users as $l1) {
            $descendants = $descendants->merge($l1->getAllDescendants());
        }
        
        return $descendants;
    }

    /**
     * Verificar se um módulo está ativo (com herança da operação)
     */
    public function hasModule(string $moduleName): bool
    {
        $modules = $this->modules ?? [];
        
        // Se definido localmente, usar configuração local
        if (isset($modules[$moduleName])) {
            return $modules[$moduleName]['enabled'] === true;
        }
        
        // Caso contrário, herdar da operação
        return $this->operacao->hasModule($moduleName);
    }

    /**
     * Obter configurações de branding com herança da operação
     */
    public function getBrandingWithInheritance(): array
    {
        $operacaoBranding = $this->operacao->getBrandingWithInheritance();
        $localBranding = $this->branding ?? [];
        
        // Merge com prioridade para configurações locais
        return array_merge($operacaoBranding, $localBranding);
    }

    /**
     * Scope para White Labels ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para buscar por domínio
     */
    public function scopeByDomain($query, string $domain)
    {
        return $query->where('domain', $domain)->orWhere('subdomain', $domain);
    }

    /**
     * Scope para White Labels de uma operação
     */
    public function scopeOfOperacao($query, int $operacaoId)
    {
        return $query->where('operacao_id', $operacaoId);
    }

    /**
     * Obter estatísticas do White Label
     */
    public function getStats(): array
    {
        return [
            'direct_users_count' => $this->users()->count(),
            'l1_users_count' => $this->licenciadosL1()->count(),
            'total_descendants_count' => $this->getAllDescendants()->count(),
            'active_modules' => collect($this->modules ?? [])->where('enabled', true)->count()
        ];
    }

    /**
     * Verificar se pode criar licenciados L1
     */
    public function canCreateL1(): bool
    {
        return $this->is_active;
    }
}