<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'participant_id',
        'participant_email',
        'participant_name',
        'channel',
        'send_at',
        'offset_minutes',
        'status',
        'attempts',
        'last_error',
        'sent_at',
        'event_title',
        'event_start_utc',
        'event_end_utc',
        'event_timezone',
        'event_meet_link',
        'event_host_name',
        'event_host_email',
        'event_description',
        'message',
        'created_by',
        'paused_at',
        'paused_by',
        'is_test',
    ];

    protected $casts = [
        'send_at' => 'datetime',
        'sent_at' => 'datetime',
        'paused_at' => 'datetime',
        'event_start_utc' => 'datetime',
        'event_end_utc' => 'datetime',
        'attempts' => 'integer',
        'offset_minutes' => 'integer',
        'is_test' => 'boolean',
    ];

    /**
     * Relacionamento com a agenda
     */
    public function agenda()
    {
        return $this->belongsTo(Agenda::class, 'event_id');
    }

    /**
     * Relacionamento com o participante (usuário)
     */
    public function participant()
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    /**
     * Relacionamento com quem criou o lembrete
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relacionamento com quem pausou o lembrete
     */
    public function pausedBy()
    {
        return $this->belongsTo(User::class, 'paused_by');
    }

    /**
     * Scope para lembretes pendentes prontos para envio
     */
    public function scopeReadyToSend($query)
    {
        return $query->where('status', 'pending')
                    ->where('send_at', '<=', now());
    }

    /**
     * Scope para lembretes pendentes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para lembretes de um evento específico
     */
    public function scopeForEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    /**
     * Scope para lembretes de um participante específico
     */
    public function scopeForParticipant($query, $email)
    {
        return $query->where('participant_email', $email);
    }

    /**
     * Scope para um canal específico
     */
    public function scopeForChannel($query, $channel)
    {
        return $query->where('channel', $channel);
    }

    /**
     * Marcar como enviado
     */
    public function markAsSent()
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Marcar como falhou
     */
    public function markAsFailed($error = null)
    {
        $this->update([
            'status' => 'failed',
            'attempts' => $this->attempts + 1,
            'last_error' => $error,
        ]);
    }

    /**
     * Marcar como cancelado
     */
    public function markAsCanceled()
    {
        $this->update([
            'status' => 'canceled',
        ]);
    }

    /**
     * Verificar se pode tentar novamente
     */
    public function canRetry()
    {
        return $this->attempts < 3 && $this->status === 'failed';
    }

    /**
     * Resetar para nova tentativa
     */
    public function resetForRetry()
    {
        if ($this->canRetry()) {
            $this->update([
                'status' => 'pending',
                'last_error' => null,
            ]);
        }
    }

    /**
     * Obter data/hora formatada no timezone do evento
     */
    public function getEventStartFormatted()
    {
        return $this->event_start_utc
            ->setTimezone($this->event_timezone)
            ->format('d/m/Y \à\s H:i');
    }

    /**
     * Obter hora de início formatada no timezone do evento
     */
    public function getEventTimeFormatted()
    {
        $start = $this->event_start_utc->setTimezone($this->event_timezone);
        $end = $this->event_end_utc->setTimezone($this->event_timezone);
        
        return $start->format('H:i') . ' às ' . $end->format('H:i');
    }

    /**
     * Obter data formatada no timezone do evento
     */
    public function getEventDateFormatted()
    {
        return $this->event_start_utc
            ->setTimezone($this->event_timezone)
            ->format('d/m/Y');
    }

    /**
     * Obter tempo restante até o evento
     */
    public function getTimeUntilEvent()
    {
        $now = now();
        $eventStart = $this->event_start_utc;
        
        $diff = $now->diffInHours($eventStart);
        
        if ($diff < 1) {
            return 'em menos de 1 hora';
        } elseif ($diff < 24) {
            return "em {$diff} hora(s)";
        } else {
            $days = intval($diff / 24);
            return "em {$days} dia(s)";
        }
    }

    /**
     * Verificar se é um lembrete único (chave de idempotência)
     */
    public static function isUnique($eventId, $participantEmail, $channel, $sendAt)
    {
        return !self::where('event_id', $eventId)
                   ->where('participant_email', $participantEmail)
                   ->where('channel', $channel)
                   ->where('send_at', $sendAt)
                   ->exists();
    }

    /**
     * Criar lembrete único (idempotente)
     */
    public static function createUnique(array $data)
    {
        // Verificar se já existe
        $exists = self::where('event_id', $data['event_id'])
                     ->where('participant_email', $data['participant_email'])
                     ->where('channel', $data['channel'])
                     ->where('send_at', $data['send_at'])
                     ->first();

        if ($exists) {
            return $exists; // Retorna o existente
        }

        return self::create($data); // Cria novo
    }
}