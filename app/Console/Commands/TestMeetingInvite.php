<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Agenda;
use App\Services\EmailService;

class TestMeetingInvite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meeting:test-invite 
                            {email : Email para receber o convite}
                            {--agenda-id= : ID da agenda específica}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testar envio de convite de reunião';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $agendaId = $this->option('agenda-id');

        $this->info('📧 Testando envio de convite de reunião...');
        $this->info("Email de destino: {$email}");

        try {
            // Buscar agenda
            $agenda = $agendaId ? Agenda::find($agendaId) : Agenda::orderBy('created_at', 'desc')->first();
            
            if (!$agenda) {
                $this->error('❌ Nenhuma agenda encontrada');
                return 1;
            }

            $this->info("Usando agenda: {$agenda->titulo} (ID: {$agenda->id})");
            $this->info("Data: {$agenda->data_inicio->format('d/m/Y H:i')}");

            // Testar envio
            $emailService = new EmailService();
            $result = $emailService->sendMeetingConfirmation(
                [$email],
                $agenda->titulo . ' (TESTE CONVITE)',
                $agenda->descricao,
                $agenda->data_inicio,
                $agenda->data_fim,
                $agenda->meet_link ?: 'https://meet.google.com/test-link',
                'Sistema Órbita - Teste',
                $agenda->id
            );

            if ($result['success']) {
                $this->info('✅ Convite enviado com sucesso!');
                $this->info("📧 Verifique a caixa de entrada de: {$email}");
                $this->info("📋 Assunto: Confirmação de Reunião: {$agenda->titulo} (TESTE CONVITE)");
            } else {
                $this->error("❌ Erro ao enviar convite: {$result['error']}");
            }

        } catch (\Exception $e) {
            $this->error('❌ Exceção ao testar convite: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}