<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlaceExtraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'query',
        'location',
        'latitude',
        'longitude',
        'radius',
        'types',
        'language',
        'region',
        'total_found',
        'total_processed',
        'total_new',
        'total_updated',
        'total_duplicates',
        'status',
        'error_message',
        'started_at',
        'completed_at',
        'duration_seconds',
        'api_requests_made',
        'estimated_cost',
        'hit_rate_limit',
        'ip_address',
        'user_agent',
        'compliance_flags',
        'legal_basis',
    ];

    protected $casts = [
        'types' => 'array',
        'compliance_flags' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'estimated_cost' => 'decimal:4',
        'hit_rate_limit' => 'boolean',
    ];

    protected $dates = [
        'started_at',
        'completed_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com places encontrados
     */
    public function places(): HasMany
    {
        return $this->hasMany(Place::class, 'extraction_id');
    }

    /**
     * Scope para extrações do usuário
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para extrações por status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para extrações recentes
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Marcar como iniciada
     */
    public function markAsStarted(): void
    {
        $this->update([
            'status' => 'running',
            'started_at' => now(),
        ]);
    }

    /**
     * Marcar como completada
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'duration_seconds' => $this->started_at ? now()->diffInSeconds($this->started_at) : null,
        ]);
    }

    /**
     * Marcar como falhada
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'completed_at' => now(),
            'duration_seconds' => $this->started_at ? now()->diffInSeconds($this->started_at) : null,
        ]);
    }

    /**
     * Incrementar contador de requisições API
     */
    public function incrementApiRequests(int $count = 1): void
    {
        $this->increment('api_requests_made', $count);
        
        // Estimar custo baseado no preço do Google Places API
        // Text Search: $0.032 per request
        // Place Details: $0.017 per request
        $textSearchCost = $count * 0.032;
        $this->increment('estimated_cost', $textSearchCost);
    }

    /**
     * Atualizar estatísticas
     */
    public function updateStats(array $stats): void
    {
        $this->update([
            'total_found' => $stats['total_found'] ?? $this->total_found,
            'total_processed' => $stats['total_processed'] ?? $this->total_processed,
            'total_new' => $stats['total_new'] ?? $this->total_new,
            'total_updated' => $stats['total_updated'] ?? $this->total_updated,
            'total_duplicates' => $stats['total_duplicates'] ?? $this->total_duplicates,
        ]);
    }

    /**
     * Obter taxa de sucesso
     */
    public function getSuccessRateAttribute(): float
    {
        if ($this->total_found === 0) return 0;
        
        return ($this->total_processed / $this->total_found) * 100;
    }

    /**
     * Obter duração formatada
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_seconds) return 'N/A';
        
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;
        
        if ($minutes > 0) {
            return "{$minutes}m {$seconds}s";
        }
        
        return "{$seconds}s";
    }

    /**
     * Obter custo formatado
     */
    public function getFormattedCostAttribute(): string
    {
        return 'US$ ' . number_format($this->estimated_cost, 4);
    }

    /**
     * Verificar se está em execução
     */
    public function isRunning(): bool
    {
        return $this->status === 'running';
    }

    /**
     * Verificar se foi completada
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Verificar se falhou
     */
    public function hasFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Obter resumo da extração
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'query' => $this->query,
            'location' => $this->location,
            'status' => $this->status,
            'total_found' => $this->total_found,
            'total_processed' => $this->total_processed,
            'success_rate' => $this->success_rate,
            'duration' => $this->formatted_duration,
            'cost' => $this->formatted_cost,
            'created_at' => $this->created_at->format('d/m/Y H:i'),
        ];
    }
}