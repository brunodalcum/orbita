<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NodeBranding extends Model
{
    use HasFactory;

    protected $fillable = [
        'node_type',
        'node_id',
        'logo_url',
        'logo_small_url',
        'favicon_url',
        'primary_color',
        'secondary_color',
        'accent_color',
        'text_color',
        'background_color',
        'font_family',
        'custom_css',
        'theme_settings',
        'inherit_from_parent'
    ];

    protected $casts = [
        'theme_settings' => 'array',
        'inherit_from_parent' => 'boolean'
    ];

    /**
     * Relacionamento polimórfico com o nó (operacao, white_label, user)
     */
    public function node(): MorphTo
    {
        return $this->morphTo('node', 'node_type', 'node_id');
    }

    /**
     * Obter branding com herança
     */
    public function getBrandingWithInheritance(): array
    {
        $branding = $this->toArray();
        
        if ($this->inherit_from_parent && $this->node_type !== 'operacao') {
            $parentBranding = $this->getParentBranding();
            
            // Merge com prioridade para valores locais não nulos
            foreach ($parentBranding as $key => $value) {
                if (empty($branding[$key]) && !empty($value)) {
                    $branding[$key] = $value;
                }
            }
        }
        
        return $branding;
    }

    /**
     * Obter branding do pai na hierarquia
     */
    private function getParentBranding(): array
    {
        switch ($this->node_type) {
            case 'white_label':
                $whiteLabel = WhiteLabel::find($this->node_id);
                return $whiteLabel ? $whiteLabel->operacao->getBrandingWithInheritance() : [];
                
            case 'super_admin':
                // Super Admin não tem pai, retorna configurações padrão
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
                ];
                
            case 'user':
                $user = User::find($this->node_id);
                if (!$user) return [];
                
                if ($user->white_label_id) {
                    return $user->whiteLabel->getBrandingWithInheritance();
                } elseif ($user->operacao_id) {
                    return $user->orbitaOperacao->getBrandingWithInheritance();
                } elseif ($user->parent_id) {
                    $parent = User::find($user->parent_id);
                    return $parent ? $parent->getBrandingWithInheritance() : [];
                }
                break;
        }
        
        return [];
    }

    /**
     * Gerar CSS customizado baseado nas configurações
     */
    public function generateCustomCSS(): string
    {
        $branding = $this->getBrandingWithInheritance();
        
        $css = ":root {\n";
        
        if (!empty($branding['primary_color'])) {
            $css .= "  --primary-color: {$branding['primary_color']};\n";
        }
        
        if (!empty($branding['secondary_color'])) {
            $css .= "  --secondary-color: {$branding['secondary_color']};\n";
        }
        
        if (!empty($branding['accent_color'])) {
            $css .= "  --accent-color: {$branding['accent_color']};\n";
        }
        
        if (!empty($branding['text_color'])) {
            $css .= "  --text-color: {$branding['text_color']};\n";
        }
        
        if (!empty($branding['background_color'])) {
            $css .= "  --background-color: {$branding['background_color']};\n";
        }
        
        if (!empty($branding['font_family'])) {
            $css .= "  --font-family: {$branding['font_family']};\n";
        }
        
        $css .= "}\n\n";
        
        // Adicionar CSS customizado se existir
        if (!empty($branding['custom_css'])) {
            $css .= $branding['custom_css'];
        }
        
        return $css;
    }

    /**
     * Scope para branding de um tipo específico
     */
    public function scopeForNodeType($query, string $nodeType)
    {
        return $query->where('node_type', $nodeType);
    }

    /**
     * Scope para branding de um nó específico
     */
    public function scopeForNode($query, string $nodeType, int $nodeId)
    {
        return $query->where('node_type', $nodeType)->where('node_id', $nodeId);
    }

    /**
     * Criar ou atualizar branding para um nó
     */
    public static function updateForNode(string $nodeType, int $nodeId, array $brandingData): self
    {
        return self::updateOrCreate(
            ['node_type' => $nodeType, 'node_id' => $nodeId],
            $brandingData
        );
    }
}