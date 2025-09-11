<?php

namespace App\Services;

use App\Models\Reminder;
use App\Models\Agenda;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReminderService
{
    /**
     * Offsets padrão em minutos
     * 2880 = 48h, 1440 = 24h, 60 = 1h
     */
    const DEFAULT_OFFSETS = [2880, 1440, 60];

    /**
     * Janela mínima de antecedência (em minutos)
     */
    const MIN_ADVANCE_WINDOW = 30;

    /**
     * Criar lembretes para um evento
     */
    public function createForEvent(Agenda $agenda, ?array $customOffsets = null)
    {
        Log::info('🔔 ReminderService: Criando lembretes para evento', [
            'event_id' => $agenda->id,
            'event_title' => $agenda->titulo,
            'event_start' => $agenda->data_inicio,
        ]);

        $offsets = $customOffsets ?? $this->getOffsetsForUser($agenda->user_id);
        $participants = $this->getParticipants($agenda);
        $eventData = $this->extractEventData($agenda);

        $created = 0;
        $skipped = 0;

        foreach ($participants as $participant) {
            foreach ($offsets as $offsetMinutes) {
                $sendAt = $this->calculateSendTime($agenda->data_inicio, $offsetMinutes);

                // Pular se o horário de envio já passou ou está muito próximo
                if ($this->shouldSkipReminder($sendAt)) {
                    $skipped++;
                    Log::debug('⏭️ Lembrete pulado (horário no passado ou muito próximo)', [
                        'participant' => $participant['email'],
                        'offset' => $offsetMinutes,
                        'send_at' => $sendAt,
                    ]);
                    continue;
                }

                // Criar lembrete (idempotente)
                $reminder = Reminder::createUnique([
                    'event_id' => $agenda->id,
                    'participant_email' => $participant['email'],
                    'participant_name' => $participant['name'],
                    'channel' => 'email', // Por enquanto só email
                    'send_at' => $sendAt,
                    'offset_minutes' => $offsetMinutes,
                    'status' => 'pending',
                    ...$eventData,
                ]);

                if ($reminder->wasRecentlyCreated) {
                    $created++;
                    Log::info('✅ Lembrete criado', [
                        'reminder_id' => $reminder->id,
                        'participant' => $participant['email'],
                        'offset' => $offsetMinutes,
                        'send_at' => $sendAt,
                    ]);
                }
            }
        }

        Log::info('🎯 Lembretes processados para evento', [
            'event_id' => $agenda->id,
            'created' => $created,
            'skipped' => $skipped,
            'participants' => count($participants),
            'offsets' => count($offsets),
        ]);

        return [
            'created' => $created,
            'skipped' => $skipped,
        ];
    }

    /**
     * Reagendar lembretes para um evento (cancelar antigos e criar novos)
     */
    public function rescheduleForEvent(Agenda $agenda, ?array $customOffsets = null)
    {
        Log::info('🔄 ReminderService: Reagendando lembretes para evento', [
            'event_id' => $agenda->id,
            'event_title' => $agenda->titulo,
            'new_start' => $agenda->data_inicio,
        ]);

        // Cancelar lembretes pendentes existentes
        $canceled = $this->cancelForEvent($agenda);

        // Criar novos lembretes
        $created = $this->createForEvent($agenda, $customOffsets);

        Log::info('🎯 Reagendamento concluído', [
            'event_id' => $agenda->id,
            'canceled' => $canceled,
            'created' => $created,
        ]);

        return [
            'canceled' => $canceled,
            'created' => $created,
        ];
    }

    /**
     * Cancelar lembretes de um evento
     */
    public function cancelForEvent(Agenda $agenda)
    {
        Log::info('❌ ReminderService: Cancelando lembretes para evento', [
            'event_id' => $agenda->id,
            'event_title' => $agenda->titulo,
        ]);

        $canceled = Reminder::forEvent($agenda->id)
            ->whereIn('status', ['pending', 'failed'])
            ->update([
                'status' => 'canceled',
                'updated_at' => now(),
            ]);

        Log::info('🎯 Lembretes cancelados', [
            'event_id' => $agenda->id,
            'canceled_count' => $canceled,
        ]);

        return $canceled;
    }

    /**
     * Adicionar lembretes para um novo participante
     */
    public function addParticipantReminders(Agenda $agenda, string $email, ?string $name = null, ?array $customOffsets = null)
    {
        Log::info('➕ ReminderService: Adicionando lembretes para novo participante', [
            'event_id' => $agenda->id,
            'participant' => $email,
        ]);

        $offsets = $customOffsets ?? $this->getOffsetsForUser($agenda->user_id);
        $eventData = $this->extractEventData($agenda);

        $created = 0;

        foreach ($offsets as $offsetMinutes) {
            $sendAt = $this->calculateSendTime($agenda->data_inicio, $offsetMinutes);

            if ($this->shouldSkipReminder($sendAt)) {
                continue;
            }

            $reminder = Reminder::createUnique([
                'event_id' => $agenda->id,
                'participant_email' => $email,
                'participant_name' => $name,
                'channel' => 'email',
                'send_at' => $sendAt,
                'offset_minutes' => $offsetMinutes,
                'status' => 'pending',
                ...$eventData,
            ]);

            if ($reminder->wasRecentlyCreated) {
                $created++;
            }
        }

        Log::info('🎯 Lembretes adicionados para participante', [
            'event_id' => $agenda->id,
            'participant' => $email,
            'created' => $created,
        ]);

        return $created;
    }

    /**
     * Remover lembretes de um participante
     */
    public function removeParticipantReminders(Agenda $agenda, string $email)
    {
        Log::info('➖ ReminderService: Removendo lembretes de participante', [
            'event_id' => $agenda->id,
            'participant' => $email,
        ]);

        $canceled = Reminder::forEvent($agenda->id)
            ->forParticipant($email)
            ->whereIn('status', ['pending', 'failed'])
            ->update([
                'status' => 'canceled',
                'updated_at' => now(),
            ]);

        Log::info('🎯 Lembretes removidos de participante', [
            'event_id' => $agenda->id,
            'participant' => $email,
            'canceled' => $canceled,
        ]);

        return $canceled;
    }

    /**
     * Obter participantes do evento
     */
    private function getParticipants(Agenda $agenda)
    {
        $participants = [];

        // Adicionar participantes da lista
        if (!empty($agenda->participantes)) {
            foreach ($agenda->participantes as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $participants[] = [
                        'email' => $email,
                        'name' => $this->getParticipantName($email),
                    ];
                }
            }
        }

        // Adicionar o criador da agenda se não estiver na lista
        if ($agenda->user && $agenda->user->email) {
            $creatorEmail = $agenda->user->email;
            $alreadyIncluded = collect($participants)->pluck('email')->contains($creatorEmail);
            
            if (!$alreadyIncluded) {
                $participants[] = [
                    'email' => $creatorEmail,
                    'name' => $agenda->user->name,
                ];
            }
        }

        return $participants;
    }

    /**
     * Tentar obter nome do participante pelo email
     */
    private function getParticipantName(string $email)
    {
        // Tentar encontrar usuário pelo email
        $user = \App\Models\User::where('email', $email)->first();
        if ($user) {
            return $user->name;
        }

        // Tentar encontrar licenciado pelo email
        $licenciado = \App\Models\Licenciado::where('email', $email)->first();
        if ($licenciado) {
            return $licenciado->razao_social;
        }

        // Extrair nome do email como fallback
        return ucwords(str_replace(['.', '_', '-'], ' ', explode('@', $email)[0]));
    }

    /**
     * Extrair dados do evento para snapshot
     */
    private function extractEventData(Agenda $agenda)
    {
        return [
            'event_title' => $agenda->titulo,
            'event_start_utc' => $agenda->data_inicio->utc(),
            'event_end_utc' => $agenda->data_fim->utc(),
            'event_timezone' => 'America/Fortaleza', // Padrão brasileiro
            'event_meet_link' => $agenda->meet_link,
            'event_host_name' => $agenda->user->name ?? 'Órbita',
            'event_host_email' => $agenda->user->email ?? 'no-reply@orbita.com',
            'event_description' => $agenda->descricao,
        ];
    }

    /**
     * Calcular horário de envio do lembrete
     */
    private function calculateSendTime(Carbon $eventStart, int $offsetMinutes)
    {
        return $eventStart->copy()->subMinutes($offsetMinutes)->utc();
    }

    /**
     * Verificar se deve pular o lembrete
     */
    private function shouldSkipReminder(Carbon $sendAt)
    {
        $now = now();
        
        // Pular se já passou
        if ($sendAt->isPast()) {
            return true;
        }

        // Pular se está muito próximo (menos que a janela mínima)
        // Usar diffInMinutes com o parâmetro absolute=false para obter valores corretos
        $minutesUntilSend = $now->diffInMinutes($sendAt, false);
        if ($minutesUntilSend < self::MIN_ADVANCE_WINDOW) {
            return true;
        }

        return false;
    }

    /**
     * Obter offsets para um usuário específico
     */
    private function getOffsetsForUser($userId)
    {
        try {
            $settings = \App\Models\UserReminderSettings::getForUser($userId);
            $offsets = $settings->getReminderOffsets();
            
            // Validar que temos pelo menos um offset
            if (empty($offsets)) {
                return self::DEFAULT_OFFSETS;
            }
            
            return $offsets;
        } catch (\Exception $e) {
            Log::warning('Erro ao obter configurações de lembrete do usuário, usando padrão', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
            
            return self::DEFAULT_OFFSETS;
        }
    }

    /**
     * Obter estatísticas de lembretes de um evento
     */
    public function getEventReminderStats(Agenda $agenda)
    {
        $reminders = Reminder::forEvent($agenda->id)->get();

        return [
            'total' => $reminders->count(),
            'pending' => $reminders->where('status', 'pending')->count(),
            'sent' => $reminders->where('status', 'sent')->count(),
            'failed' => $reminders->where('status', 'failed')->count(),
            'canceled' => $reminders->where('status', 'canceled')->count(),
            'next_reminder' => $reminders->where('status', 'pending')
                                       ->sortBy('send_at')
                                       ->first()?->send_at,
        ];
    }

    /**
     * Reprocessar lembretes falhados
     */
    public function retryFailedReminders(Agenda $agenda)
    {
        $failedReminders = Reminder::forEvent($agenda->id)
            ->where('status', 'failed')
            ->where('attempts', '<', 3)
            ->get();

        $retried = 0;

        foreach ($failedReminders as $reminder) {
            $reminder->resetForRetry();
            $retried++;
        }

        Log::info('🔄 Lembretes falhados reprocessados', [
            'event_id' => $agenda->id,
            'retried' => $retried,
        ]);

        return $retried;
    }
}
