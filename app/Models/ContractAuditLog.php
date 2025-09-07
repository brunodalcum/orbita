<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'user_id',
        'action',
        'description',
        'metadata',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    // Relacionamentos
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Métodos auxiliares
    public function getActionLabelAttribute()
    {
        return match($this->action) {
            'documento_enviado' => 'Documento Enviado',
            'documento_aprovado' => 'Documento Aprovado',
            'documento_rejeitado' => 'Documento Rejeitado',
            'documentos_aprovados' => 'Todos os Documentos Aprovados',
            'contrato_gerado' => 'Contrato Gerado',
            'contrato_enviado' => 'Contrato Enviado',
            'contrato_assinado' => 'Contrato Assinado',
            'licenciado_liberado' => 'Licenciado Liberado',
            'status_alterado' => 'Status Alterado',
            'observacao_adicionada' => 'Observação Adicionada',
            default => ucfirst(str_replace('_', ' ', $this->action))
        };
    }

    public function getActionIconAttribute()
    {
        return match($this->action) {
            'documento_enviado' => 'fas fa-upload',
            'documento_aprovado' => 'fas fa-check-circle',
            'documento_rejeitado' => 'fas fa-times-circle',
            'documentos_aprovados' => 'fas fa-check-double',
            'contrato_gerado' => 'fas fa-file-pdf',
            'contrato_enviado' => 'fas fa-envelope',
            'contrato_assinado' => 'fas fa-signature',
            'licenciado_liberado' => 'fas fa-unlock',
            'status_alterado' => 'fas fa-exchange-alt',
            'observacao_adicionada' => 'fas fa-comment',
            default => 'fas fa-info-circle'
        };
    }

    public function getActionColorAttribute()
    {
        return match($this->action) {
            'documento_enviado' => 'text-blue-600',
            'documento_aprovado' => 'text-green-600',
            'documento_rejeitado' => 'text-red-600',
            'documentos_aprovados' => 'text-emerald-600',
            'contrato_gerado' => 'text-purple-600',
            'contrato_enviado' => 'text-indigo-600',
            'contrato_assinado' => 'text-teal-600',
            'licenciado_liberado' => 'text-green-700',
            'status_alterado' => 'text-orange-600',
            'observacao_adicionada' => 'text-gray-600',
            default => 'text-gray-500'
        };
    }
}