<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaConfirmacao extends Model
{
    use HasFactory;

    protected $table = 'agenda_confirmacoes';

    protected $fillable = [
        'agenda_id',
        'email_participante',
        'token',
        'status',
        'observacao',
        'confirmado_em'
    ];

    protected $casts = [
        'confirmado_em' => 'datetime',
    ];

    /**
     * Relacionamento com a agenda
     */
    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class);
    }

    /**
     * Escopo para confirmações por status
     */
    public function scopePorStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Escopo para confirmações de uma agenda específica
     */
    public function scopeDaAgenda($query, $agendaId)
    {
        return $query->where('agenda_id', $agendaId);
    }

    /**
     * Escopo para confirmações de um participante específico
     */
    public function scopeDoParticipante($query, $email)
    {
        return $query->where('email_participante', $email);
    }

    /**
     * Marcar como confirmado
     */
    public function confirmar()
    {
        $this->update([
            'status' => 'confirmado',
            'confirmado_em' => now()
        ]);
    }

    /**
     * Marcar como pendente
     */
    public function marcarPendente()
    {
        $this->update([
            'status' => 'pendente',
            'confirmado_em' => null
        ]);
    }

    /**
     * Marcar como recusado
     */
    public function recusar()
    {
        $this->update([
            'status' => 'recusado',
            'confirmado_em' => null
        ]);
    }

    /**
     * Verificar se está confirmado
     */
    public function isConfirmado(): bool
    {
        return $this->status === 'confirmado';
    }

    /**
     * Verificar se está pendente
     */
    public function isPendente(): bool
    {
        return $this->status === 'pendente';
    }

    /**
     * Verificar se foi recusado
     */
    public function isRecusado(): bool
    {
        return $this->status === 'recusado';
    }

    /**
     * Gerar token único para confirmação
     */
    public static function generateToken(): string
    {
        do {
            $token = bin2hex(random_bytes(32));
        } while (self::where('token', $token)->exists());

        return $token;
    }

    /**
     * Encontrar por token
     */
    public static function findByToken(string $token)
    {
        return self::where('token', $token)->first();
    }
}
