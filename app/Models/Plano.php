<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
        'taxa',
        'taxas_detalhadas',
        'operacoes',
        'status'
    ];

    protected $casts = [
        'operacoes' => 'array',
        'taxa' => 'decimal:4',
        'taxas_detalhadas' => 'array'
    ];

    public function operacoesRelacionadas()
    {
        return $this->belongsToMany(Operacao::class, 'operacao_plano', 'plano_id', 'operacao_id');
    }

    public function getOperacoesNomesAttribute()
    {
        if (!$this->operacoes) {
            return [];
        }
        
        return Operacao::whereIn('id', $this->operacoes)->pluck('nome')->toArray();
    }
}
