<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Place extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'place_id',
        'name',
        'formatted_address',
        'vicinity',
        'latitude',
        'longitude',
        'plus_code',
        'formatted_phone_number',
        'international_phone_number',
        'website',
        'types',
        'rating',
        'user_ratings_total',
        'price_level',
        'opening_hours',
        'open_now',
        'photos',
        'editorial_summary',
        'business_status',
        'source',
        'collected_at',
        'search_query',
        'search_location',
        'search_radius',
        'phone_hash',
        'website_domain',
        'address_hash',
        'quality_score',
        'has_phone',
        'has_website',
        'is_active',
        'opt_out',
        'opt_out_date',
        'opt_out_reason',
    ];

    protected $casts = [
        'types' => 'array',
        'opening_hours' => 'array',
        'photos' => 'array',
        'collected_at' => 'datetime',
        'opt_out_date' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'rating' => 'decimal:1',
        'search_radius' => 'decimal:2',
        'open_now' => 'boolean',
        'has_phone' => 'boolean',
        'has_website' => 'boolean',
        'is_active' => 'boolean',
        'opt_out' => 'boolean',
    ];

    protected $dates = [
        'collected_at',
        'opt_out_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Relacionamento com leads através da tabela pivot
     */
    public function leads(): BelongsToMany
    {
        return $this->belongsToMany(Lead::class, 'place_leads')
                    ->withPivot([
                        'extraction_id',
                        'conversion_status',
                        'conversion_notes',
                        'converted_at',
                        'data_completeness_score',
                        'missing_fields',
                        'data_quality_flags',
                        'is_duplicate',
                        'duplicate_of_lead_id',
                        'duplicate_reason',
                        'consent_required',
                        'consent_obtained',
                        'consent_date',
                        'consent_method'
                    ])
                    ->withTimestamps();
    }

    /**
     * Relacionamento com extrações
     */
    public function extractions(): HasMany
    {
        return $this->hasMany(PlaceExtraction::class);
    }

    /**
     * Scope para places ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para places que não fizeram opt-out
     */
    public function scopeNotOptedOut($query)
    {
        return $query->where('opt_out', false);
    }

    /**
     * Scope para places com telefone
     */
    public function scopeWithPhone($query)
    {
        return $query->where('has_phone', true);
    }

    /**
     * Scope para places com website
     */
    public function scopeWithWebsite($query)
    {
        return $query->where('has_website', true);
    }

    /**
     * Scope para buscar por localização (raio)
     */
    public function scopeNearby($query, $latitude, $longitude, $radiusKm = 10)
    {
        $radiusDegrees = $radiusKm / 111; // Aproximação: 1 grau ≈ 111km
        
        return $query->whereBetween('latitude', [$latitude - $radiusDegrees, $latitude + $radiusDegrees])
                     ->whereBetween('longitude', [$longitude - $radiusDegrees, $longitude + $radiusDegrees]);
    }

    /**
     * Scope para filtrar por tipos
     */
    public function scopeOfTypes($query, array $types)
    {
        return $query->where(function ($q) use ($types) {
            foreach ($types as $type) {
                $q->orWhereJsonContains('types', $type);
            }
        });
    }

    /**
     * Scope para places com qualidade mínima
     */
    public function scopeMinQuality($query, $minScore = 50)
    {
        return $query->where('quality_score', '>=', $minScore);
    }

    /**
     * Calcular score de qualidade baseado na completude dos dados
     */
    public function calculateQualityScore(): int
    {
        $score = 0;
        $maxScore = 100;
        
        // Nome (obrigatório) - 20 pontos
        if (!empty($this->name)) $score += 20;
        
        // Endereço - 15 pontos
        if (!empty($this->formatted_address)) $score += 15;
        
        // Telefone - 25 pontos (muito importante para leads)
        if (!empty($this->formatted_phone_number)) $score += 25;
        
        // Website - 20 pontos (importante para leads)
        if (!empty($this->website)) $score += 20;
        
        // Rating - 10 pontos
        if (!empty($this->rating)) $score += 10;
        
        // Horário de funcionamento - 10 pontos
        if (!empty($this->opening_hours)) $score += 10;
        
        return min($score, $maxScore);
    }

    /**
     * Atualizar score de qualidade
     */
    public function updateQualityScore(): void
    {
        $this->quality_score = $this->calculateQualityScore();
        $this->save();
    }

    /**
     * Verificar se é um possível duplicado
     */
    public function findPossibleDuplicates()
    {
        $query = static::where('id', '!=', $this->id);
        
        // Buscar por hash de telefone
        if ($this->phone_hash) {
            $query->orWhere('phone_hash', $this->phone_hash);
        }
        
        // Buscar por domínio do website
        if ($this->website_domain) {
            $query->orWhere('website_domain', $this->website_domain);
        }
        
        // Buscar por hash de endereço
        if ($this->address_hash) {
            $query->orWhere('address_hash', $this->address_hash);
        }
        
        return $query->get();
    }

    /**
     * Converter para lead
     */
    public function convertToLead(array $additionalData = []): Lead
    {
        $leadData = array_merge([
            'nome' => $this->name,
            'email' => $additionalData['email'] ?? null,
            'telefone' => $this->formatted_phone_number,
            'empresa' => $this->name,
            'endereco' => $this->formatted_address,
            'website' => $this->website,
            'origem' => 'google_places',
            'status' => 'novo',
            'observacoes' => $this->editorial_summary,
            'ativo' => true,
        ], $additionalData);
        
        return Lead::create($leadData);
    }

    /**
     * Marcar como opt-out (LGPD)
     */
    public function markAsOptOut(string $reason = null): void
    {
        $this->update([
            'opt_out' => true,
            'opt_out_date' => now(),
            'opt_out_reason' => $reason,
            'is_active' => false,
        ]);
    }

    /**
     * Obter URL do Google Maps
     */
    public function getGoogleMapsUrlAttribute(): string
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }
        
        return "https://www.google.com/maps/search/" . urlencode($this->formatted_address ?? $this->name);
    }

    /**
     * Obter tipos formatados
     */
    public function getFormattedTypesAttribute(): string
    {
        if (!$this->types) return '';
        
        $typeTranslations = [
            'restaurant' => 'Restaurante',
            'pharmacy' => 'Farmácia',
            'hospital' => 'Hospital',
            'bank' => 'Banco',
            'gas_station' => 'Posto de Gasolina',
            'supermarket' => 'Supermercado',
            'store' => 'Loja',
            'establishment' => 'Estabelecimento',
        ];
        
        $translated = array_map(function ($type) use ($typeTranslations) {
            return $typeTranslations[$type] ?? ucfirst(str_replace('_', ' ', $type));
        }, $this->types);
        
        return implode(', ', array_slice($translated, 0, 3)); // Máximo 3 tipos
    }
}