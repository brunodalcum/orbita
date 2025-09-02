<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agenda extends Model
{
    protected $fillable = [
        'titulo',
        'descricao',
        'data_inicio',
        'data_fim',
        'google_meet_link',
        'google_event_id',
        'participantes',
        'status',
        'user_id',
        'tipo_reuniao'
    ];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'participantes' => 'array'
    ];

    /**
     * Relacionamento com o usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com as confirmações de participação
     */
    public function confirmacoes()
    {
        return $this->hasMany(AgendaConfirmacao::class);
    }

    /**
     * Obter confirmações confirmadas
     */
    public function confirmacoesConfirmadas()
    {
        return $this->confirmacoes()->porStatus('confirmado');
    }

    /**
     * Obter confirmações pendentes
     */
    public function confirmacoesPendentes()
    {
        return $this->confirmacoes()->porStatus('pendente');
    }

    /**
     * Obter confirmações recusadas
     */
    public function confirmacoesRecusadas()
    {
        return $this->confirmacoes()->porStatus('recusado');
    }

    /**
     * Verificar se um participante confirmou
     */
    public function participanteConfirmou($email): bool
    {
        return $this->confirmacoes()
            ->doParticipante($email)
            ->porStatus('confirmado')
            ->exists();
    }

    /**
     * Verificar se um participante recusou
     */
    public function participanteRecusou($email): bool
    {
        return $this->confirmacoes()
            ->doParticipante($email)
            ->porStatus('recusado')
            ->exists();
    }

    /**
     * Verificar se um participante está pendente
     */
    public function participantePendente($email): bool
    {
        return $this->confirmacoes()
            ->doParticipante($email)
            ->porStatus('pendente')
            ->exists();
    }

    /**
     * Scope para buscar reuniões do dia
     */
    public function scopeDoDia($query, $data = null)
    {
        $data = $data ?: now()->format('Y-m-d');
        return $query->whereDate('data_inicio', $data);
    }

    /**
     * Scope para buscar reuniões ativas
     */
    public function scopeAtivas($query)
    {
        return $query->where('status', 'agendada');
    }
}
