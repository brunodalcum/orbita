<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ContractDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'document_type',
        'original_name',
        'file_path',
        'mime_type',
        'file_size',
        'status',
        'admin_notes',
        'reviewed_at',
        'reviewed_by'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'file_size' => 'integer'
    ];

    // Relacionamentos
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    // Métodos auxiliares
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pendente' => 'Pendente',
            'aprovado' => 'Aprovado',
            'rejeitado' => 'Rejeitado',
            default => 'Desconhecido'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pendente' => 'bg-yellow-100 text-yellow-800',
            'aprovado' => 'bg-green-100 text-green-800',
            'rejeitado' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getDocumentTypeLabelAttribute()
    {
        return match($this->document_type) {
            'rg' => 'RG',
            'cpf' => 'CPF',
            'cnpj' => 'CNPJ',
            'comprovante_residencia' => 'Comprovante de Residência',
            'contrato_social' => 'Contrato Social',
            'procuracao' => 'Procuração',
            'comprovante_renda' => 'Comprovante de Renda',
            'referencias_bancarias' => 'Referências Bancárias',
            'certidoes_negativas' => 'Certidões Negativas',
            default => ucfirst(str_replace('_', ' ', $this->document_type))
        };
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public function getFileUrl()
    {
        return Storage::url($this->file_path);
    }

    public function fileExists()
    {
        return Storage::exists($this->file_path);
    }

    public function approve($adminNotes = null)
    {
        $this->update([
            'status' => 'aprovado',
            'admin_notes' => $adminNotes,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id()
        ]);

        // Log da ação
        $this->contract->logAction(
            'documento_aprovado',
            "Documento {$this->document_type_label} foi aprovado",
            auth()->id(),
            ['document_id' => $this->id, 'document_type' => $this->document_type]
        );
    }

    public function reject($adminNotes = null)
    {
        $this->update([
            'status' => 'rejeitado',
            'admin_notes' => $adminNotes,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id()
        ]);

        // Log da ação
        $this->contract->logAction(
            'documento_rejeitado',
            "Documento {$this->document_type_label} foi rejeitado",
            auth()->id(),
            ['document_id' => $this->id, 'document_type' => $this->document_type, 'reason' => $adminNotes]
        );
    }

    public static function getRequiredDocumentTypes()
    {
        return [
            'rg' => 'RG',
            'cpf' => 'CPF',
            'comprovante_residencia' => 'Comprovante de Residência',
            'cnpj' => 'CNPJ (se empresa)',
            'contrato_social' => 'Contrato Social (se empresa)',
            'comprovante_renda' => 'Comprovante de Renda',
            'referencias_bancarias' => 'Referências Bancárias'
        ];
    }

    public function isImage()
    {
        return in_array($this->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    public function isPdf()
    {
        return $this->mime_type === 'application/pdf';
    }
}