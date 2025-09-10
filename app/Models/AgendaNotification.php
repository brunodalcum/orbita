<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'agenda_id',
        'user_id',
        'tipo',
        'titulo',
        'mensagem',
        'lida',
        'lida_em',
        'dados_extras'
    ];

    protected $casts = [
        'lida' => 'boolean',
        'lida_em' => 'datetime',
        'dados_extras' => 'array'
    ];

    /**
     * Relacionamento com agenda
     */
    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }

    /**
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Criar notificação de solicitação de agenda
     */
    public static function createSolicitacaoNotification($agenda, $destinatarioId)
    {
        $solicitante = User::find($agenda->solicitante_id);
        
        return self::create([
            'agenda_id' => $agenda->id,
            'user_id' => $destinatarioId,
            'tipo' => 'solicitacao',
            'titulo' => 'Nova Solicitação de Agenda',
            'mensagem' => "{$solicitante->name} solicitou uma reunião: \"{$agenda->titulo}\" para " . 
                         \Carbon\Carbon::parse($agenda->data_inicio)->format('d/m/Y H:i'),
            'dados_extras' => [
                'solicitante_nome' => $solicitante->name,
                'data_inicio' => $agenda->data_inicio,
                'data_fim' => $agenda->data_fim,
                'fora_horario' => $agenda->fora_horario_comercial
            ]
        ]);
    }

    /**
     * Criar notificação de aprovação
     */
    public static function createAprovacaoNotification($agenda, $solicitanteId)
    {
        $aprovador = User::find($agenda->aprovada_por);
        
        return self::create([
            'agenda_id' => $agenda->id,
            'user_id' => $solicitanteId,
            'tipo' => 'aprovacao',
            'titulo' => 'Agenda Aprovada',
            'mensagem' => "{$aprovador->name} aprovou sua solicitação de reunião: \"{$agenda->titulo}\"",
            'dados_extras' => [
                'aprovador_nome' => $aprovador->name,
                'data_aprovacao' => $agenda->aprovada_em
            ]
        ]);
    }

    /**
     * Criar notificação de recusa
     */
    public static function createRecusaNotification($agenda, $solicitanteId, $motivo = null)
    {
        $recusador = User::find($agenda->aprovada_por);
        
        return self::create([
            'agenda_id' => $agenda->id,
            'user_id' => $solicitanteId,
            'tipo' => 'recusa',
            'titulo' => 'Agenda Recusada',
            'mensagem' => "{$recusador->name} recusou sua solicitação de reunião: \"{$agenda->titulo}\"" . 
                         ($motivo ? " - Motivo: {$motivo}" : ''),
            'dados_extras' => [
                'recusador_nome' => $recusador->name,
                'motivo' => $motivo
            ]
        ]);
    }

    /**
     * Marcar notificação como lida
     */
    public function marcarComoLida()
    {
        $this->update([
            'lida' => true,
            'lida_em' => now()
        ]);
    }

    /**
     * Buscar notificações não lidas de um usuário
     */
    public static function getNaoLidas($userId)
    {
        return self::where('user_id', $userId)
                   ->where('lida', false)
                   ->with('agenda')
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Contar notificações não lidas
     */
    public static function countNaoLidas($userId)
    {
        return self::where('user_id', $userId)
                   ->where('lida', false)
                   ->count();
    }
}