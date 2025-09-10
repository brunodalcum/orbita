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
        'meet_link',
        'google_meet_link',
        'google_event_id',
        'google_event_url',
        'google_synced_at',
        'participantes',
        'status',
        'user_id',
        'tipo_reuniao',
        'licenciado_id',
        'solicitante_id',
        'destinatario_id',
        'status_aprovacao',
        'requer_aprovacao',
        'fora_horario_comercial',
        'aprovada_em',
        'aprovada_por',
        'motivo_recusa',
        'notificacao_enviada'
    ];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'google_synced_at' => 'datetime',
        'aprovada_em' => 'datetime',
        'participantes' => 'array',
        'requer_aprovacao' => 'boolean',
        'fora_horario_comercial' => 'boolean',
        'notificacao_enviada' => 'boolean'
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

    /**
     * Relacionamento com solicitante
     */
    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    /**
     * Relacionamento com destinatário
     */
    public function destinatario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'destinatario_id');
    }

    /**
     * Relacionamento com quem aprovou
     */
    public function aprovadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprovada_por');
    }

    /**
     * Relacionamento com notificações
     */
    public function notificacoes()
    {
        return $this->hasMany(AgendaNotification::class);
    }

    /**
     * Verificar se a agenda requer aprovação
     */
    public function requerAprovacao(): bool
    {
        return $this->requer_aprovacao && $this->status_aprovacao === 'pendente';
    }

    /**
     * Verificar se está aprovada
     */
    public function isAprovada(): bool
    {
        return in_array($this->status_aprovacao, ['aprovada', 'automatica']);
    }

    /**
     * Verificar se foi recusada
     */
    public function isRecusada(): bool
    {
        return $this->status_aprovacao === 'recusada';
    }

    /**
     * Aprovar agenda
     */
    public function aprovar($aprovadoPorId, $observacoes = null)
    {
        $this->update([
            'status_aprovacao' => 'aprovada',
            'aprovada_em' => now(),
            'aprovada_por' => $aprovadoPorId,
            'status' => 'agendada'
        ]);

        // Criar notificação para o solicitante
        if ($this->solicitante_id) {
            AgendaNotification::createAprovacaoNotification($this, $this->solicitante_id);
        }
    }

    /**
     * Recusar agenda
     */
    public function recusar($recusadoPorId, $motivo = null)
    {
        $this->update([
            'status_aprovacao' => 'recusada',
            'aprovada_em' => now(),
            'aprovada_por' => $recusadoPorId,
            'motivo_recusa' => $motivo,
            'status' => 'cancelada'
        ]);

        // Criar notificação para o solicitante
        if ($this->solicitante_id) {
            AgendaNotification::createRecusaNotification($this, $this->solicitante_id, $motivo);
        }
    }

    /**
     * Scope para agendas pendentes de aprovação
     */
    public function scopePendentesAprovacao($query, $userId = null)
    {
        $query = $query->where('status_aprovacao', 'pendente')
                      ->where('requer_aprovacao', true);
        
        if ($userId) {
            $query->where('destinatario_id', $userId);
        }
        
        return $query;
    }

    /**
     * Scope para agendas do usuário (como solicitante ou destinatário)
     */
    public function scopeDoUsuario($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('solicitante_id', $userId)
              ->orWhere('destinatario_id', $userId);
        });
    }
}
