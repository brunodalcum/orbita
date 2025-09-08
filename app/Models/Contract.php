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
        'active',
        'meta',
        'last_error',
        'filled_at',
        'pdf_generated_at',
        'approved_at',
        'template_path',
        'pdf_path',
        'signed_at'
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
        'licenciado_released_at' => 'datetime',
        'signed_at' => 'datetime',
        'approved_at' => 'datetime',
        'meta' => 'array'
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
            'draft' => 'Rascunho',
            'criado' => 'Licenciado Selecionado',
            'template_uploaded' => 'Template Carregado',
            'filled' => 'Template Preenchido',
            'pdf_ready' => 'PDF Gerado',
            'sent' => 'Enviado por Email',
            'signed' => 'Assinado',
            'approved' => 'Aprovado',
            'error' => 'Erro',
            'cancelado' => 'Cancelado',
            // Status antigos (compatibilidade)
            'contrato_enviado' => 'Contrato Enviado',
            'aguardando_assinatura' => 'Aguardando Assinatura',
            'contrato_assinado' => 'Contrato Assinado',
            'licenciado_aprovado' => 'Licenciado Aprovado',
            default => 'Status Desconhecido'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'criado' => 'bg-blue-100 text-blue-800',
            'template_uploaded' => 'bg-indigo-100 text-indigo-800',
            'filled' => 'bg-purple-100 text-purple-800',
            'pdf_ready' => 'bg-pink-100 text-pink-800',
            'sent' => 'bg-orange-100 text-orange-800',
            'signed' => 'bg-green-100 text-green-800',
            'approved' => 'bg-emerald-100 text-emerald-800',
            'error' => 'bg-red-100 text-red-800',
            'cancelado' => 'bg-red-100 text-red-800',
            // Status antigos (compatibilidade)
            'contrato_enviado' => 'bg-blue-100 text-blue-800',
            'aguardando_assinatura' => 'bg-yellow-100 text-yellow-800',
            'contrato_assinado' => 'bg-green-100 text-green-800',
            'licenciado_aprovado' => 'bg-emerald-100 text-emerald-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getProgressPercentageAttribute()
    {
        return match($this->status) {
            'draft' => 0,
            'criado' => 14,              // Step 1: Licenciado selecionado
            'template_uploaded' => 28,   // Step 2: Template enviado
            'filled' => 42,              // Step 3: Template preenchido
            'pdf_ready' => 57,           // Step 4: PDF gerado
            'sent' => 71,                // Step 5: Email enviado
            'signed' => 85,              // Step 6: Assinado
            'approved' => 100,           // Step 7: Aprovado
            'error' => 0,
            'cancelado' => 0,
            // Status antigos (compatibilidade)
            'contrato_enviado' => 50,
            'aguardando_assinatura' => 75,
            'contrato_assinado' => 90,
            'licenciado_aprovado' => 100,
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
        return $this->status === 'criado';
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