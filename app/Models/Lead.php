<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'empresa',
        'status',
        'origem',
        'observacoes',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopePorStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePorOrigem($query, $origem)
    {
        return $query->where('origem', $origem);
    }

    public function isAtivo()
    {
        return $this->ativo;
    }

    /**
     * Relacionamento com follow-ups
     */
    public function followUps()
    {
        return $this->hasMany(LeadFollowUp::class)->ordered();
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'novo' => 'bg-blue-100 text-blue-800',
            'contatado' => 'bg-yellow-100 text-yellow-800',
            'qualificado' => 'bg-green-100 text-green-800',
            'proposta' => 'bg-purple-100 text-purple-800',
            'fechado' => 'bg-gray-100 text-gray-800',
            'perdido' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
