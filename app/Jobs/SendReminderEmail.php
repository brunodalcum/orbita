<?php

namespace App\Jobs;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderEmail;

class SendReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Timeout do job em segundos
     */
    public $timeout = 60; // 1 minuto

    /**
     * Número máximo de tentativas
     */
    public $tries = 3;

    /**
     * Backoff entre tentativas (em segundos)
     */
    public $backoff = [30, 120, 300]; // 30s, 2min, 5min

    /**
     * O lembrete a ser enviado
     */
    public Reminder $reminder;

    /**
     * Create a new job instance.
     */
    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('📧 SendReminderEmail: Iniciando envio', [
            'reminder_id' => $this->reminder->id,
            'participant' => $this->reminder->participant_email,
            'event_title' => $this->reminder->event_title,
            'attempt' => $this->attempts(),
        ]);

        try {
            // Verificar se ainda está pendente (evitar duplicação)
            $freshReminder = $this->reminder->fresh();
            
            if ($freshReminder->status !== 'pending') {
                Log::warning('⚠️ Lembrete não está mais pendente, cancelando envio', [
                    'reminder_id' => $this->reminder->id,
                    'current_status' => $freshReminder->status,
                ]);
                return;
            }

            // Verificar se o evento ainda existe
            if (!$freshReminder->agenda) {
                Log::warning('⚠️ Evento não encontrado, cancelando lembrete', [
                    'reminder_id' => $this->reminder->id,
                    'event_id' => $this->reminder->event_id,
                ]);
                
                $freshReminder->markAsCanceled();
                return;
            }

            // Preparar dados do email
            $emailData = $this->prepareEmailData($freshReminder);

            // Enviar email
            Mail::to($freshReminder->participant_email)
                ->send(new ReminderEmail($emailData));

            // Marcar como enviado
            $freshReminder->markAsSent();

            Log::info('✅ Lembrete enviado com sucesso', [
                'reminder_id' => $this->reminder->id,
                'participant' => $this->reminder->participant_email,
                'event_title' => $this->reminder->event_title,
                'sent_at' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            $this->handleSendError($e);
        }
    }

    /**
     * Preparar dados para o template do email
     */
    private function prepareEmailData(Reminder $reminder): array
    {
        return [
            // Dados do lembrete
            'reminder_id' => $reminder->id,
            'time_until_event' => $reminder->getTimeUntilEvent(),
            
            // Dados do participante
            'participant_name' => $reminder->participant_name ?: 'Participante',
            'participant_email' => $reminder->participant_email,
            
            // Dados do evento
            'event_title' => $reminder->event_title,
            'event_description' => $reminder->event_description,
            'event_date_formatted' => $reminder->getEventDateFormatted(),
            'event_time_formatted' => $reminder->getEventTimeFormatted(),
            'event_start_formatted' => $reminder->getEventStartFormatted(),
            'event_timezone' => $reminder->event_timezone,
            'event_meet_link' => $reminder->event_meet_link,
            
            // Dados do host
            'host_name' => $reminder->event_host_name,
            'host_email' => $reminder->event_host_email,
            
            // Links e ações
            'calendar_link' => $this->generateCalendarLink($reminder),
            'reschedule_link' => $this->generateRescheduleLink($reminder),
            
            // Metadados
            'offset_minutes' => $reminder->offset_minutes,
            'offset_label' => $this->getOffsetLabel($reminder->offset_minutes),
        ];
    }

    /**
     * Gerar link para adicionar ao calendário (ICS)
     */
    private function generateCalendarLink(Reminder $reminder): string
    {
        // Por enquanto, retornar link básico
        // Futuramente, implementar geração de arquivo ICS
        return route('dashboard.agenda.show', $reminder->event_id);
    }

    /**
     * Gerar link para reagendamento
     */
    private function generateRescheduleLink(Reminder $reminder): string
    {
        // Por enquanto, retornar link para a agenda
        // Futuramente, implementar sistema de reagendamento
        return route('dashboard.agenda.edit', $reminder->event_id);
    }

    /**
     * Obter label amigável do offset
     */
    private function getOffsetLabel(int $offsetMinutes): string
    {
        if ($offsetMinutes >= 1440) {
            $days = intval($offsetMinutes / 1440);
            return $days === 1 ? '1 dia' : "{$days} dias";
        } elseif ($offsetMinutes >= 60) {
            $hours = intval($offsetMinutes / 60);
            return $hours === 1 ? '1 hora' : "{$hours} horas";
        } else {
            return "{$offsetMinutes} minutos";
        }
    }

    /**
     * Tratar erro de envio
     */
    private function handleSendError(\Exception $e): void
    {
        $attempt = $this->attempts();
        $maxAttempts = $this->tries;

        Log::error('❌ Erro ao enviar lembrete', [
            'reminder_id' => $this->reminder->id,
            'participant' => $this->reminder->participant_email,
            'error' => $e->getMessage(),
            'attempt' => $attempt,
            'max_attempts' => $maxAttempts,
        ]);

        // Se esgotou as tentativas, marcar como falhou
        if ($attempt >= $maxAttempts) {
            $this->reminder->fresh()->markAsFailed($e->getMessage());
            
            Log::error('💀 Lembrete marcado como falhou após esgotar tentativas', [
                'reminder_id' => $this->reminder->id,
                'participant' => $this->reminder->participant_email,
                'final_error' => $e->getMessage(),
                'total_attempts' => $attempt,
            ]);
        }

        // Re-throw para que o Laravel gerencie o retry
        throw $e;
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('💥 SendReminderEmail falhou definitivamente', [
            'reminder_id' => $this->reminder->id,
            'participant' => $this->reminder->participant_email,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts(),
        ]);

        // Garantir que o lembrete seja marcado como falhou
        try {
            $this->reminder->fresh()->markAsFailed($exception->getMessage());
        } catch (\Exception $e) {
            Log::error('💥 Erro ao marcar lembrete como falhou', [
                'reminder_id' => $this->reminder->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}