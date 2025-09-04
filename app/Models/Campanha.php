<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campanha extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'tipo',
        'modelo_id',
        'status',
        'data_inicio',
        'data_fim',
        'segmentacao',
        'total_destinatarios',
        'emails_enviados',
        'emails_abertos',
        'emails_clicados',
        'user_id'
    ];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'segmentacao' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function modelo()
    {
        return $this->belongsTo(EmailModelo::class, 'modelo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAtivas($query)
    {
        return $query->where('status', 'ativa');
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopePorStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function getTaxaAberturaAttribute()
    {
        if ($this->emails_enviados === 0) return 0;
        return round(($this->emails_abertos / $this->emails_enviados) * 100, 1);
    }

    public function getTaxaCliqueAttribute()
    {
        if ($this->emails_enviados === 0) return 0;
        return round(($this->emails_clicados / $this->emails_enviados) * 100, 1);
    }
}
