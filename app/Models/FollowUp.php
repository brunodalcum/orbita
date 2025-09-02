<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'licenciado_id',
        'tipo',
        'observacao',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento com o licenciado
     */
    public function licenciado()
    {
        return $this->belongsTo(Licenciado::class);
    }

    /**
     * Relacionamento com o usuário que criou o follow-up
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tipos de follow-up disponíveis
     */
    public static function getTipos()
    {
        return [
            'contato' => 'Contato Realizado',
            'documentacao' => 'Documentação',
            'analise' => 'Análise',
            'aprovacao' => 'Aprovação',
            'rejeicao' => 'Rejeição',
            'observacao' => 'Observação Geral'
        ];
    }
}
