<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Licenciado;
use Illuminate\Support\Str;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'licenciado_table_id',
        'template_id',
        'status',
        'observacoes_admin',
        'contract_pdf_path',
        'signed_contract_path',
        'signature_token',
        'provider',
        'provider_envelope_id',
        'contract_hash',
        'signer_ip',
        'documents_approved_at',
        'generated_at',
        'sent_at',
        'resent_at',
        'contract_sent_at',
        'contract_signed_at',
        'licenciado_released_at',
        'approved_by',
        'contract_sent_by',
        'signature_data',
        'contract_data',
        'provider_response',
        'active'
    ];

    protected $casts = [
        'signature_data' => 'array',
        'contract_data' => 'array',
        'provider_response' => 'array',
        'active' => 'boolean',
        'documents_approved_at' => 'datetime',
        'generated_at' => 'datetime',
        'sent_at' => 'datetime',
        'resent_at' => 'datetime',
        'contract_sent_at' => 'datetime',
        'contract_signed_at' => 'datetime',
        'licenciado_released_at' => 'datetime'
    ];

    // Relacionamentos
    public function licenciado(): BelongsTo
    {
        return $this->belongsTo(Licenciado::class, 'licenciado_table_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function contractSentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contract_sent_by');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ContractTemplate::class, 'template_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ContractDocument::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(ContractAuditLog::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Métodos auxiliares
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'documentos_pendentes' => 'Documentos Pendentes',
            'documentos_enviados' => 'Documentos Enviados',
            'documentos_em_analise' => 'Documentos em Análise',
            'documentos_aprovados' => 'Documentos Aprovados',
            'documentos_rejeitados' => 'Documentos Rejeitados',
            'contrato_enviado' => 'Contrato Enviado',
            'contrato_assinado' => 'Contrato Assinado',
            'licenciado_liberado' => 'Licenciado Liberado',
            default => 'Status Desconhecido'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'documentos_pendentes' => 'bg-gray-100 text-gray-800',
            'documentos_enviados' => 'bg-blue-100 text-blue-800',
            'documentos_em_analise' => 'bg-yellow-100 text-yellow-800',
            'documentos_aprovados' => 'bg-green-100 text-green-800',
            'documentos_rejeitados' => 'bg-red-100 text-red-800',
            'contrato_enviado' => 'bg-purple-100 text-purple-800',
            'contrato_assinado' => 'bg-indigo-100 text-indigo-800',
            'licenciado_liberado' => 'bg-emerald-100 text-emerald-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getProgressPercentageAttribute()
    {
        return match($this->status) {
            'documentos_pendentes' => 10,
            'documentos_enviados' => 20,
            'documentos_em_analise' => 35,
            'documentos_aprovados' => 50,
            'documentos_rejeitados' => 25,
            'contrato_enviado' => 70,
            'contrato_assinado' => 85,
            'licenciado_liberado' => 100,
            default => 0
        };
    }

    public function generateSignatureToken()
    {
        $this->signature_token = Str::random(64);
        $this->save();
        return $this->signature_token;
    }

    public function canSendContract()
    {
        return $this->status === 'documentos_aprovados';
    }

    public function canApproveDocuments()
    {
        return in_array($this->status, ['documentos_enviados', 'documentos_em_analise']);
    }

    public function hasAllDocumentsApproved()
    {
        return $this->documents()->where('status', 'pendente')->count() === 0 &&
               $this->documents()->where('status', 'aprovado')->count() > 0;
    }

    public function logAction($action, $description, $userId = null, $metadata = [])
    {
        return $this->auditLogs()->create([
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}