<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrbitaOperacao extends Model
{
    use HasFactory;

    protected $table = 'orbita_operacaos';

    protected $fillable = [
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
     * Relacionamento com White Labels
     */
    public function whiteLabels(): HasMany
    {
        return $this->hasMany(WhiteLabel::class, 'operacao_id');
    }

    /**
     * Relacionamento com usuários da operação
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'operacao_id');
    }

    /**
     * Relacionamento com licenciados diretos (L1 sem White Label)
     */
    public function licenciadosL1(): HasMany
    {
        return $this->hasMany(User::class, 'operacao_id')
                    ->where('node_type', 'licenciado_l1')
                    ->whereNull('white_label_id');
    }

    /**
     * Relacionamento com branding
     */
    public function branding(): HasOne
    {
        return $this->hasOne(NodeBranding::class, 'node_id')
                    ->where('node_type', 'operacao');
    }

    /**
     * Obter todos os descendentes da operação
     */
    public function getAllDescendants()
    {
        $descendants = collect();
        
        // White Labels
        $whiteLabels = $this->whiteLabels()->with('users')->get();
        $descendants = $descendants->merge($whiteLabels);
        
        // Usuários diretos
        $directUsers = $this->users()->get();
        $descendants = $descendants->merge($directUsers);
        
        // Usuários dos White Labels
        foreach ($whiteLabels as $wl) {
            $descendants = $descendants->merge($wl->users);
        }
        
        return $descendants;
    }

    /**
     * Verificar se um módulo está ativo
     */
    public function hasModule(string $moduleName): bool
    {
        $modules = $this->modules ?? [];
        return isset($modules[$moduleName]) && $modules[$moduleName]['enabled'] === true;
    }

    /**
     * Obter configurações de branding com herança
     */
    public function getBrandingWithInheritance(): array
    {
        $branding = $this->branding ?? [];
        
        // Para operação, não há herança - é o topo da hierarquia
        return array_merge([
            'logo_url' => null,
            'primary_color' => '#3B82F6',
            'secondary_color' => '#1E40AF',
            'accent_color' => '#F59E0B',
            'font_family' => 'Inter, sans-serif'
        ], $branding);
    }

    /**
     * Scope para operações ativas
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
     * Obter estatísticas da operação
     */
    public function getStats(): array
    {
        return [
            'white_labels_count' => $this->whiteLabels()->count(),
            'direct_users_count' => $this->users()->count(),
            'total_users_count' => $this->getAllDescendants()->count(),
            'active_modules' => collect($this->modules ?? [])->where('enabled', true)->count()
        ];
    }
}