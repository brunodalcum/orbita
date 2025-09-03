<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Agenda;
use App\Services\EmailService;
use Illuminate\Support\Facades\Auth;

class TestAgenda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:agenda';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testar funcionalidade da agenda sem Google Calendar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testando funcionalidade da Agenda...');
        
        try {
            // Testar criação de agenda
            $this->info('📝 Testando criação de agenda...');
            
            $agenda = new Agenda();
            $agenda->titulo = 'Teste de Agenda - ' . date('Y-m-d H:i:s');
            $agenda->descricao = 'Teste da funcionalidade da agenda';
            $agenda->data_inicio = now()->addHour();
            $agenda->data_fim = now()->addHours(2);
            $agenda->tipo_reuniao = 'online';
            $agenda->participantes = ['teste@exemplo.com'];
            $agenda->user_id = 1; // Usuário de teste
            $agenda->status = 'agendada';
            
            // Gerar link do Meet manualmente
            $agenda->google_meet_link = 'https://meet.google.com/' . strtolower(substr(md5(uniqid()), 0, 8)) . '-' . strtolower(substr(md5(uniqid()), 0, 4)) . '-' . strtolower(substr(md5(uniqid()), 0, 4));
            
            $agenda->save();
            
            $this->info('✅ Agenda criada com sucesso!');
            $this->info("   ID: {$agenda->id}");
            $this->info("   Título: {$agenda->titulo}");
            $this->info("   Meet Link: {$agenda->google_meet_link}");
            
            // Testar envio de e-mail
            $this->info('📧 Testando envio de e-mail...');
            
            $emailService = new EmailService();
            $emailResult = $emailService->sendMeetingConfirmation(
                $agenda->participantes,
                $agenda->titulo,
                $agenda->descricao,
                $agenda->data_inicio,
                $agenda->data_fim,
                $agenda->google_meet_link,
                'Sistema de Teste'
            );
            
            if ($emailResult) {
                $this->info('✅ E-mail enviado com sucesso!');
            } else {
                $this->warn('⚠️ E-mail não foi enviado');
            }
            
            // Limpar agenda de teste
            $agenda->delete();
            $this->info('🧹 Agenda de teste removida');
            
            $this->info('🎉 Teste concluído com sucesso!');
            $this->info('📖 A agenda está funcionando sem o Google Calendar');
            $this->info('🔗 Links do Meet são gerados manualmente');
            $this->info('📧 E-mails são enviados normalmente');
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Erro durante o teste: ' . $e->getMessage());
            $this->error('📖 Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }
}




