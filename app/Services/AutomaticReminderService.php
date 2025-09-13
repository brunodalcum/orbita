<?php

namespace App\Services;

use App\Models\Agenda;
use App\Models\Reminder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutomaticReminderService
{
    /**
     * Criar lembretes automáticos para uma agenda
     */
    public function createAutomaticReminders(Agenda $agenda): array
    {
        $reminders = [];
        $participants = $this->getAgendaParticipants($agenda);
        
        Log::info('[LEMBRETES AUTOMÁTICOS] Iniciando criação para agenda ID: ' . $agenda->id);
        Log::info('[LEMBRETES AUTOMÁTICOS] Participantes encontrados: ' . count($participants));
        
        foreach ($participants as $participant) {
            try {
                // 1. Lembrete 1 dia antes (24 horas antes)
                $reminder1Day = $this->createReminder(
                    $agenda,
                    $participant,
                    $this->calculateSendTime($agenda->data_inicio, '1 day'),
                    'Lembrete: Reunião amanhã - ' . $agenda->titulo,
                    1440 // 24 horas em minutos
                );
                if ($reminder1Day) {
                    $reminders[] = $reminder1Day;
                }
                
                // 2. Lembrete no dia às 8h da manhã
                $reminder8AM = $this->createReminder(
                    $agenda,
                    $participant,
                    $this->calculateMorningReminder($agenda->data_inicio),
                    'Lembrete: Reunião hoje - ' . $agenda->titulo,
                    $this->calculateMorningOffset($agenda->data_inicio)
                );
                if ($reminder8AM) {
                    $reminders[] = $reminder8AM;
                }
                
                // 3. Lembrete 1 hora antes
                $reminder1Hour = $this->createReminder(
                    $agenda,
                    $participant,
                    $this->calculateSendTime($agenda->data_inicio, '1 hour'),
                    'Lembrete: Reunião em 1 hora - ' . $agenda->titulo,
                    60 // 1 hora em minutos
                );
                if ($reminder1Hour) {
                    $reminders[] = $reminder1Hour;
                }
                
                Log::info('[LEMBRETES AUTOMÁTICOS] Criados 3 lembretes para: ' . $participant->email);
                
            } catch (\Exception $e) {
                Log::error('[LEMBRETES AUTOMÁTICOS] Erro ao criar lembretes para ' . $participant->email . ': ' . $e->getMessage());
            }
        }
        
        Log::info('[LEMBRETES AUTOMÁTICOS] Total de lembretes criados: ' . count($reminders));
        
        return $reminders;
    }
    
    /**
     * Obter todos os participantes de uma agenda
     */
    private function getAgendaParticipants(Agenda $agenda): array
    {
        $participants = [];
        
        // 1. Usuário principal (criador)
        if ($agenda->user) {
            $participants[] = $agenda->user;
        }
        
        // 2. Solicitante (se diferente do usuário principal)
        if ($agenda->solicitante && $agenda->solicitante->id !== $agenda->user_id) {
            $participants[] = $agenda->solicitante;
        }
        
        // 3. Destinatário (se diferente dos anteriores)
        if ($agenda->destinatario && 
            $agenda->destinatario->id !== $agenda->user_id && 
            $agenda->destinatario->id !== $agenda->solicitante_id) {
            $participants[] = $agenda->destinatario;
        }
        
        // 4. Participantes adicionais (emails extras)
        if ($agenda->participantes) {
            $emailsAdicionais = is_string($agenda->participantes) 
                ? json_decode($agenda->participantes, true) 
                : $agenda->participantes;
                
            if (is_array($emailsAdicionais)) {
                foreach ($emailsAdicionais as $email) {
                    $email = trim($email);
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        // Buscar usuário por email ou criar participante virtual
                        $user = User::where('email', $email)->first();
                        if ($user && !in_array($user->id, array_column($participants, 'id'))) {
                            $participants[] = $user;
                        } elseif (!$user) {
                            // Criar objeto virtual para email externo
                            $virtualUser = new \stdClass();
                            $virtualUser->id = null;
                            $virtualUser->email = $email;
                            $virtualUser->name = $email;
                            $participants[] = $virtualUser;
                        }
                    }
                }
            }
        }
        
        return $participants;
    }
    
    /**
     * Criar um lembrete individual
     */
    private function createReminder(Agenda $agenda, $participant, Carbon $sendTime, string $message, int $offsetMinutes): ?Reminder
    {
        try {
            // Não criar lembrete se a data de envio já passou
            if ($sendTime->isPast()) {
                Log::info('[LEMBRETES AUTOMÁTICOS] Pulando lembrete (data passada): ' . $sendTime->format('Y-m-d H:i:s'));
                return null;
            }
            
            $reminderData = [
                'event_id' => $agenda->id,
                'participant_email' => $participant->email,
                'participant_name' => $participant->name ?? $participant->email,
                'channel' => 'email',
                'send_at' => $sendTime,
                'offset_minutes' => $offsetMinutes,
                'status' => 'pending',
                'attempts' => 0,
                'message' => $message,
                'created_by' => $agenda->user_id,
                'is_test' => false,
                // Campos do evento para compatibilidade
                'event_title' => $agenda->titulo,
                'event_start_utc' => $agenda->data_inicio,
                'event_end_utc' => $agenda->data_fim,
                'event_timezone' => 'America/Sao_Paulo',
                'event_meet_link' => $agenda->meet_link,
                'event_host_name' => $agenda->user->name ?? 'Sistema',
                'event_host_email' => $agenda->user->email ?? 'sistema@dspay.com.br',
                'event_description' => $agenda->descricao,
            ];
            
            // Adicionar participant_id se for usuário do sistema
            if (is_object($participant) && isset($participant->id) && $participant->id) {
                $reminderData['participant_id'] = $participant->id;
            }
            
            $reminder = Reminder::create($reminderData);
            
            Log::info('[LEMBRETES AUTOMÁTICOS] Lembrete criado ID: ' . $reminder->id . ' para ' . $participant->email . ' em ' . $sendTime->format('Y-m-d H:i:s'));
            
            return $reminder;
            
        } catch (\Exception $e) {
            Log::error('[LEMBRETES AUTOMÁTICOS] Erro ao criar lembrete: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Calcular horário de envio baseado no evento
     */
    private function calculateSendTime(string $eventStart, string $interval): Carbon
    {
        $eventTime = Carbon::parse($eventStart);
        
        switch ($interval) {
            case '1 day':
                return $eventTime->copy()->subDay();
            case '1 hour':
                return $eventTime->copy()->subHour();
            default:
                return $eventTime->copy()->subHour();
        }
    }
    
    /**
     * Calcular horário do lembrete matinal (8h da manhã do dia do evento)
     */
    private function calculateMorningReminder(string $eventStart): Carbon
    {
        $eventTime = Carbon::parse($eventStart);
        return $eventTime->copy()->setTime(8, 0, 0); // 8:00 AM do mesmo dia
    }
    
    /**
     * Calcular offset em minutos para o lembrete matinal
     */
    private function calculateMorningOffset(string $eventStart): int
    {
        $eventTime = Carbon::parse($eventStart);
        $morningTime = $eventTime->copy()->setTime(8, 0, 0);
        
        // Calcular diferença em minutos entre 8h e o horário do evento
        $diffInMinutes = $morningTime->diffInMinutes($eventTime, false);
        
        return abs($diffInMinutes);
    }
    
    /**
     * Verificar se uma agenda deve ter lembretes automáticos
     */
    public function shouldCreateReminders(Agenda $agenda): bool
    {
        $eventTime = Carbon::parse($agenda->data_inicio);
        
        // Não criar lembretes para eventos que já passaram
        if ($eventTime->isPast()) {
            Log::info('[LEMBRETES AUTOMÁTICOS] Evento no passado, não criando lembretes');
            return false;
        }
        
        // Não criar lembretes para eventos muito próximos (menos de 2 horas no futuro)
        $hoursUntilEvent = now()->diffInHours($eventTime, false); // false = pode ser negativo
        if ($hoursUntilEvent < 2) {
            Log::info('[LEMBRETES AUTOMÁTICOS] Evento muito próximo (' . $hoursUntilEvent . 'h), não criando lembretes');
            return false;
        }
        
        Log::info('[LEMBRETES AUTOMÁTICOS] Evento adequado para lembretes (' . $hoursUntilEvent . 'h no futuro)');
        return true;
    }
    
    /**
     * Remover lembretes automáticos de uma agenda (quando cancelada/deletada)
     */
    public function removeAutomaticReminders(Agenda $agenda): int
    {
        $count = Reminder::where('event_id', $agenda->id)
            ->where('status', 'pending')
            ->where('is_test', false)
            ->delete();
            
        Log::info('[LEMBRETES AUTOMÁTICOS] Removidos ' . $count . ' lembretes da agenda ID: ' . $agenda->id);
        
        return $count;
    }
}
