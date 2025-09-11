<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reminder;
use App\Models\Agenda;
use App\Jobs\SendReminderEmail;
use App\Mail\ReminderEmail;
use Illuminate\Support\Facades\Mail;

class TestReminderEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:test-email 
                            {email : Email para receber o teste}
                            {--agenda-id= : ID da agenda para usar no teste}
                            {--send-now : Enviar imediatamente ao invés de usar job}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testar envio de e-mail de lembrete';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $agendaId = $this->option('agenda-id');
        $sendNow = $this->option('send-now');

        $this->info('📧 Testando envio de e-mail de lembrete...');
        $this->info("Email de destino: {$email}");

        try {
            // Buscar agenda para usar no teste
            $agenda = $agendaId ? Agenda::find($agendaId) : Agenda::first();
            
            if (!$agenda) {
                $this->error('❌ Nenhuma agenda encontrada para usar no teste');
                return 1;
            }

            $this->info("Usando agenda: {$agenda->titulo}");

            // Criar lembrete de teste
            $testReminder = $this->createTestReminder($agenda, $email);
            
            $this->info("✅ Lembrete de teste criado (ID: {$testReminder->id})");

            if ($sendNow) {
                // Enviar imediatamente
                $this->sendEmailDirectly($testReminder);
            } else {
                // Usar job (recomendado)
                $this->sendEmailViaJob($testReminder);
            }

        } catch (\Exception $e) {
            $this->error('❌ Erro ao testar e-mail: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Criar lembrete de teste
     */
    private function createTestReminder(Agenda $agenda, string $email): Reminder
    {
        return Reminder::create([
            'event_id' => $agenda->id,
            'participant_email' => $email,
            'participant_name' => 'Usuário Teste',
            'channel' => 'email',
            'send_at' => now(), // Enviar agora
            'offset_minutes' => 60, // 1 hora (para exemplo)
            'status' => 'pending',
            'event_title' => $agenda->titulo . ' (TESTE)',
            'event_start_utc' => $agenda->data_inicio->utc(),
            'event_end_utc' => $agenda->data_fim->utc(),
            'event_timezone' => 'America/Fortaleza',
            'event_meet_link' => $agenda->meet_link ?: 'https://meet.google.com/test-link-demo',
            'event_host_name' => $agenda->user->name ?? 'Sistema Órbita',
            'event_host_email' => $agenda->user->email ?? 'no-reply@orbita.com',
            'event_description' => 'Este é um e-mail de teste do sistema de lembretes automáticos do Órbita.',
        ]);
    }

    /**
     * Enviar e-mail diretamente
     */
    private function sendEmailDirectly(Reminder $reminder)
    {
        $this->info('📤 Enviando e-mail diretamente...');

        try {
            // Preparar dados do e-mail
            $emailData = [
                'reminder_id' => $reminder->id,
                'time_until_event' => $reminder->getTimeUntilEvent(),
                'participant_name' => $reminder->participant_name,
                'participant_email' => $reminder->participant_email,
                'event_title' => $reminder->event_title,
                'event_description' => $reminder->event_description,
                'event_date_formatted' => $reminder->getEventDateFormatted(),
                'event_time_formatted' => $reminder->getEventTimeFormatted(),
                'event_start_formatted' => $reminder->getEventStartFormatted(),
                'event_timezone' => $reminder->event_timezone,
                'event_meet_link' => $reminder->event_meet_link,
                'host_name' => $reminder->event_host_name,
                'host_email' => $reminder->event_host_email,
                'calendar_link' => route('dashboard.agenda.show', $reminder->event_id),
                'reschedule_link' => route('dashboard.agenda.edit', $reminder->event_id),
                'offset_minutes' => $reminder->offset_minutes,
                'offset_label' => $this->getOffsetLabel($reminder->offset_minutes),
            ];

            // Enviar e-mail
            Mail::to($reminder->participant_email)->send(new ReminderEmail($emailData));

            // Marcar como enviado
            $reminder->markAsSent();

            $this->info('✅ E-mail enviado com sucesso!');
            $this->info("📧 Verifique a caixa de entrada de: {$reminder->participant_email}");

        } catch (\Exception $e) {
            $this->error('❌ Erro ao enviar e-mail: ' . $e->getMessage());
            $reminder->markAsFailed($e->getMessage());
        }
    }

    /**
     * Enviar e-mail via job
     */
    private function sendEmailViaJob(Reminder $reminder)
    {
        $this->info('🚀 Disparando job de envio...');

        try {
            SendReminderEmail::dispatch($reminder);
            
            $this->info('✅ Job disparado com sucesso!');
            $this->info('📧 O e-mail será enviado em background');
            $this->info("Verifique a caixa de entrada de: {$reminder->participant_email}");
            
            // Aguardar um pouco e verificar status
            $this->info('⏳ Aguardando processamento...');
            sleep(3);
            
            $updatedReminder = $reminder->fresh();
            $this->info("Status atual: {$updatedReminder->status}");
            
            if ($updatedReminder->status === 'sent') {
                $this->info('✅ E-mail processado e enviado!');
            } elseif ($updatedReminder->status === 'failed') {
                $this->error("❌ Falha no envio: {$updatedReminder->last_error}");
            } else {
                $this->warn('⏳ E-mail ainda sendo processado...');
            }

        } catch (\Exception $e) {
            $this->error('❌ Erro ao disparar job: ' . $e->getMessage());
        }
    }

    /**
     * Obter label do offset
     */
    private function getOffsetLabel(int $offsetMinutes): string
    {
        if ($offsetMinutes >= 1440) {
            $days = intval($offsetMinutes / 1440);
            return $days === 1 ? '1 dia antes' : "{$days} dias antes";
        } elseif ($offsetMinutes >= 60) {
            $hours = intval($offsetMinutes / 60);
            return $hours === 1 ? '1 hora antes' : "{$hours} horas antes";
        } else {
            return "{$offsetMinutes} minutos antes";
        }
    }
}