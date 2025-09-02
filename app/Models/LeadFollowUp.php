<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadFollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'observacao',
        'proximo_contato',
        'user_id'
    ];

    protected $casts = [
        'proximo_contato' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relacionamento com o Lead
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Relacionamento com o usuário que criou o follow-up
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para ordenar por data de criação (mais recente primeiro)
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope para filtrar por lead
     */
    public function scopeForLead($query, $leadId)
    {
        return $query->where('lead_id', $leadId);
    }
}
