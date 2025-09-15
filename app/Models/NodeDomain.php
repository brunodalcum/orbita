<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NodeDomain extends Model
{
    use HasFactory;

    protected $fillable = [
        'node_type',
        'node_id',
        'domain_type',
        'domain',
        'subdomain',
        'base_domain',
        'is_primary',
        'is_active',
        'ssl_enabled',
        'ssl_status',
        'ssl_expires_at',
        'dns_records',
        'verification_token',
        'verified',
        'verified_at',
        'redirect_rules',
        'settings',
        'created_by',
        'notes'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
        'ssl_enabled' => 'boolean',
        'verified' => 'boolean',
        'ssl_expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'dns_records' => 'array',
        'redirect_rules' => 'array',
        'settings' => 'array'
    ];

    /**
     * Relacionamento com o usuário que criou
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
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
     * Obter URL completa do domínio
     */
    public function getFullUrlAttribute(): string
    {
        if ($this->domain_type === 'custom' && $this->domain) {
            return $this->domain;
        }
        
        if ($this->subdomain) {
            return "https://{$this->subdomain}.{$this->base_domain}";
        }
        
        return "https://{$this->base_domain}";
    }

    /**
     * Obter nome do domínio para exibição
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->domain_type === 'custom' && $this->domain) {
            return parse_url($this->domain, PHP_URL_HOST) ?? $this->domain;
        }
        
        if ($this->subdomain) {
            return "{$this->subdomain}.{$this->base_domain}";
        }
        
        return $this->base_domain;
    }

    /**
     * Verificar se o SSL está próximo do vencimento
     */
    public function isSslExpiringSoon(int $days = 30): bool
    {
        if (!$this->ssl_enabled || !$this->ssl_expires_at) {
            return false;
        }
        
        return $this->ssl_expires_at->diffInDays(now()) <= $days;
    }

    /**
     * Verificar se o domínio está configurado corretamente
     */
    public function isConfiguredCorrectly(): bool
    {
        return $this->is_active && $this->verified && 
               ($this->domain_type === 'subdomain' || 
                ($this->domain_type === 'custom' && $this->domain));
    }

    /**
     * Gerar token de verificação
     */
    public function generateVerificationToken(): string
    {
        $token = Str::random(32);
        $this->update(['verification_token' => $token]);
        return $token;
    }

    /**
     * Marcar como verificado
     */
    public function markAsVerified(): bool
    {
        return $this->update([
            'verified' => true,
            'verified_at' => now(),
            'verification_token' => null
        ]);
    }

    /**
     * Obter registros DNS necessários
     */
    public function getRequiredDnsRecords(): array
    {
        $records = [];
        
        if ($this->domain_type === 'custom' && $this->domain) {
            $host = parse_url($this->domain, PHP_URL_HOST);
            
            $records[] = [
                'type' => 'CNAME',
                'name' => $host,
                'value' => $this->base_domain,
                'ttl' => 300
            ];
            
            if ($this->ssl_enabled) {
                $records[] = [
                    'type' => 'TXT',
                    'name' => "_acme-challenge.{$host}",
                    'value' => $this->verification_token ?? 'pending',
                    'ttl' => 300
                ];
            }
        }
        
        return $records;
    }

    /**
     * Obter domínios por nó
     */
    public static function getNodeDomains(string $nodeType, int $nodeId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('node_type', $nodeType)
                  ->where('node_id', $nodeId)
                  ->orderBy('is_primary', 'desc')
                  ->orderBy('created_at', 'desc')
                  ->get();
    }

    /**
     * Obter domínio primário de um nó
     */
    public static function getPrimaryDomain(string $nodeType, int $nodeId): ?self
    {
        return self::where('node_type', $nodeType)
                  ->where('node_id', $nodeId)
                  ->where('is_primary', true)
                  ->where('is_active', true)
                  ->first();
    }

    /**
     * Verificar se um domínio está disponível
     */
    public static function isDomainAvailable(string $domain, ?int $excludeId = null): bool
    {
        $query = self::where(function($q) use ($domain) {
            $q->where('domain', $domain)
              ->orWhere('subdomain', $domain);
        });
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return !$query->exists();
    }

    /**
     * Criar domínio para nó
     */
    public static function createForNode(
        string $nodeType,
        int $nodeId,
        array $data,
        int $createdBy
    ): self {
        // Verificar se já existe um domínio primário
        $existingPrimary = self::where('node_type', $nodeType)
            ->where('node_id', $nodeId)
            ->where('is_primary', true)
            ->first();
        
        // Se está criando um novo primário, desativar o anterior
        if ($data['is_primary'] ?? false && $existingPrimary) {
            $existingPrimary->update(['is_primary' => false]);
        }
        
        return self::create(array_merge($data, [
            'node_type' => $nodeType,
            'node_id' => $nodeId,
            'created_by' => $createdBy,
            'verification_token' => $data['domain_type'] === 'custom' ? Str::random(32) : null
        ]));
    }

    /**
     * Obter estatísticas de domínios
     */
    public static function getStatistics(): array
    {
        return [
            'total_domains' => self::count(),
            'active_domains' => self::where('is_active', true)->count(),
            'verified_domains' => self::where('verified', true)->count(),
            'custom_domains' => self::where('domain_type', 'custom')->count(),
            'subdomains' => self::where('domain_type', 'subdomain')->count(),
            'ssl_enabled' => self::where('ssl_enabled', true)->count(),
            'ssl_expiring_soon' => self::where('ssl_expires_at', '<=', now()->addDays(30))->count()
        ];
    }

    /**
     * Obter domínios por status SSL
     */
    public static function getBySslStatus(string $status): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('ssl_status', $status)
                  ->where('ssl_enabled', true)
                  ->with(['node', 'createdBy'])
                  ->get();
    }

    /**
     * Obter domínios não verificados
     */
    public static function getUnverified(): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('verified', false)
                  ->where('is_active', true)
                  ->with(['node', 'createdBy'])
                  ->get();
    }

    /**
     * Validar configuração de domínio
     */
    public function validateConfiguration(): array
    {
        $errors = [];
        
        if ($this->domain_type === 'custom') {
            if (empty($this->domain)) {
                $errors[] = 'Domínio personalizado é obrigatório';
            } elseif (!filter_var($this->domain, FILTER_VALIDATE_URL)) {
                $errors[] = 'Formato de domínio inválido';
            }
        }
        
        if ($this->domain_type === 'subdomain') {
            if (empty($this->subdomain)) {
                $errors[] = 'Subdomínio é obrigatório';
            } elseif (!preg_match('/^[a-z0-9-]+$/', $this->subdomain)) {
                $errors[] = 'Subdomínio deve conter apenas letras, números e hífens';
            }
        }
        
        return $errors;
    }

    /**
     * Scope para domínios ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para domínios verificados
     */
    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    /**
     * Scope para domínios primários
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}