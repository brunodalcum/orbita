<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BusinessHours extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dia_semana',
        'hora_inicio',
        'hora_fim',
        'ativo'
    ];

    protected $casts = [
        'hora_inicio' => 'datetime:H:i',
        'hora_fim' => 'datetime:H:i',
        'ativo' => 'boolean'
    ];

    /**
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verificar se um horário está dentro do expediente
     */
    public static function isWithinBusinessHours($userId, $dataHora)
    {
        $carbon = Carbon::parse($dataHora);
        $diaSemana = self::getDiaSemanaPortugues($carbon->dayOfWeek);
        
        // Buscar horário específico do usuário ou global
        $businessHour = self::where(function($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhereNull('user_id');
        })
        ->where('dia_semana', $diaSemana)
        ->where('ativo', true)
        ->orderBy('user_id', 'desc') // Priorizar configuração específica do usuário
        ->first();

        if (!$businessHour) {
            return false; // Sem configuração = fora do horário
        }

        $horaAtual = $carbon->format('H:i');
        $horaInicio = Carbon::parse($businessHour->hora_inicio)->format('H:i');
        $horaFim = Carbon::parse($businessHour->hora_fim)->format('H:i');

        return $horaAtual >= $horaInicio && $horaAtual <= $horaFim;
    }

    /**
     * Verificar se há conflito de horário para um usuário
     */
    public static function hasTimeConflict($userId, $dataInicio, $dataFim, $agendaIdExcluir = null)
    {
        $query = \App\Models\Agenda::where(function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('solicitante_id', $userId)
              ->orWhere('destinatario_id', $userId);
        })
        ->where('status_aprovacao', '!=', 'recusada')
        ->where(function($q) use ($dataInicio, $dataFim) {
            $q->whereBetween('data_inicio', [$dataInicio, $dataFim])
              ->orWhereBetween('data_fim', [$dataInicio, $dataFim])
              ->orWhere(function($subQ) use ($dataInicio, $dataFim) {
                  $subQ->where('data_inicio', '<=', $dataInicio)
                       ->where('data_fim', '>=', $dataFim);
              });
        });

        if ($agendaIdExcluir) {
            $query->where('id', '!=', $agendaIdExcluir);
        }

        return $query->exists();
    }

    /**
     * Converter número do dia da semana para português
     */
    private static function getDiaSemanaPortugues($dayOfWeek)
    {
        $dias = [
            0 => 'domingo',
            1 => 'segunda',
            2 => 'terca',
            3 => 'quarta',
            4 => 'quinta',
            5 => 'sexta',
            6 => 'sabado'
        ];

        return $dias[$dayOfWeek] ?? 'segunda';
    }

    /**
     * Criar horários comerciais padrão para um usuário
     */
    public static function createDefaultBusinessHours($userId = null)
    {
        $horariosDefault = [
            ['dia_semana' => 'segunda', 'hora_inicio' => '09:00', 'hora_fim' => '18:00'],
            ['dia_semana' => 'terca', 'hora_inicio' => '09:00', 'hora_fim' => '18:00'],
            ['dia_semana' => 'quarta', 'hora_inicio' => '09:00', 'hora_fim' => '18:00'],
            ['dia_semana' => 'quinta', 'hora_inicio' => '09:00', 'hora_fim' => '18:00'],
            ['dia_semana' => 'sexta', 'hora_inicio' => '09:00', 'hora_fim' => '18:00'],
            ['dia_semana' => 'sabado', 'hora_inicio' => '09:00', 'hora_fim' => '12:00', 'ativo' => false],
            ['dia_semana' => 'domingo', 'hora_inicio' => '09:00', 'hora_fim' => '18:00', 'ativo' => false],
        ];

        foreach ($horariosDefault as $horario) {
            self::updateOrCreate(
                [
                    'user_id' => $userId,
                    'dia_semana' => $horario['dia_semana']
                ],
                [
                    'hora_inicio' => $horario['hora_inicio'],
                    'hora_fim' => $horario['hora_fim'],
                    'ativo' => $horario['ativo'] ?? true
                ]
            );
        }
    }
}