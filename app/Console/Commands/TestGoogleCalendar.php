<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleCalendarService;
use App\Services\EmailService;

class TestGoogleCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:google-calendar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Google Calendar integration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testando integração com Google Calendar...');

        // Testar Google Calendar Service
        try {
            $googleService = new GoogleCalendarService();
            $this->info('✅ GoogleCalendarService instanciado com sucesso');
        } catch (\Exception $e) {
            $this->error('❌ Erro ao instanciar GoogleCalendarService: ' . $e->getMessage());
            return 1;
        }

        // Testar Email Service
        try {
            $emailService = new EmailService();
            $this->info('✅ EmailService instanciado com sucesso');
        } catch (\Exception $e) {
            $this->error('❌ Erro ao instanciar EmailService: ' . $e->getMessage());
            return 1;
        }

        // Verificar se o arquivo de credenciais existe
        $credentialsPath = storage_path('app/google-credentials.json');
        if (file_exists($credentialsPath)) {
            $this->info('✅ Arquivo de credenciais encontrado');
        } else {
            $this->warn('⚠️ Arquivo de credenciais não encontrado em: ' . $credentialsPath);
            $this->info('📝 Crie o arquivo seguindo as instruções em GOOGLE_CALENDAR_SETUP.md');
        }

        // Testar criação de evento (simulado)
        $this->info('📅 Testando criação de evento...');
        try {
            $result = $googleService->createEvent(
                'Teste de Integração',
                'Este é um teste da integração com Google Calendar',
                now()->addHour()->toISOString(),
                now()->addHours(2)->toISOString(),
                ['teste@exemplo.com']
            );

            if ($result['success']) {
                $this->info('✅ Evento criado com sucesso no Google Calendar');
                $this->info('🔗 Link do Meet: ' . ($result['meet_link'] ?? 'N/A'));
                
                // Testar exclusão do evento
                $deleteResult = $googleService->deleteEvent($result['event_id']);
                if ($deleteResult['success']) {
                    $this->info('✅ Evento excluído com sucesso');
                } else {
                    $this->warn('⚠️ Erro ao excluir evento: ' . $deleteResult['error']);
                }
            } else {
                $this->warn('⚠️ Erro ao criar evento: ' . $result['error']);
            }
        } catch (\Exception $e) {
            $this->warn('⚠️ Erro ao testar criação de evento: ' . $e->getMessage());
        }

        // Testar envio de e-mail (simulado)
        $this->info('📧 Testando envio de e-mail...');
        try {
            $emailResult = $emailService->sendMeetingConfirmation(
                ['teste@exemplo.com'],
                'Teste de E-mail',
                'Este é um teste de envio de e-mail',
                now()->addHour()->toISOString(),
                now()->addHours(2)->toISOString(),
                'https://meet.google.com/teste',
                'Sistema de Teste'
            );

            if ($emailResult['success']) {
                $this->info('✅ E-mail enviado com sucesso');
            } else {
                $this->warn('⚠️ Erro ao enviar e-mail: ' . $emailResult['error']);
            }
        } catch (\Exception $e) {
            $this->warn('⚠️ Erro ao testar envio de e-mail: ' . $e->getMessage());
        }

        $this->info('🎉 Teste concluído!');
        $this->info('📖 Consulte GOOGLE_CALENDAR_SETUP.md para configuração completa');

        return 0;
    }
}
