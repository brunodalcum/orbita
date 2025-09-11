<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Agenda;
use App\Services\EmailService;

class DiagnoseEmailIssue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:diagnose {email : Email para testes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnosticar problemas de envio de e-mail';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info('🔍 Diagnóstico de E-mail - Órbita');
        $this->info("Email de teste: {$email}");
        $this->newLine();

        // Teste 1: Configuração básica
        $this->info('1️⃣ Testando configuração básica de e-mail...');
        if ($this->testBasicEmail($email)) {
            $this->info('✅ Configuração básica OK');
        } else {
            $this->error('❌ Problema na configuração básica');
            return 1;
        }

        // Teste 2: E-mail com template
        $this->info('2️⃣ Testando e-mail com template...');
        if ($this->testTemplateEmail($email)) {
            $this->info('✅ Template de e-mail OK');
        } else {
            $this->error('❌ Problema no template de e-mail');
        }

        // Teste 3: EmailService
        $this->info('3️⃣ Testando EmailService...');
        if ($this->testEmailService($email)) {
            $this->info('✅ EmailService OK');
        } else {
            $this->error('❌ Problema no EmailService');
        }

        // Teste 4: Verificar última agenda
        $this->info('4️⃣ Verificando última agenda criada...');
        $this->checkLastAgenda();

        // Teste 5: Configurações do sistema
        $this->info('5️⃣ Verificando configurações...');
        $this->checkConfiguration();

        $this->newLine();
        $this->info('🎯 Diagnóstico concluído!');
        $this->info('📧 Verifique sua caixa de entrada (e spam) para os e-mails de teste');

        return 0;
    }

    /**
     * Testar configuração básica de e-mail
     */
    private function testBasicEmail($email): bool
    {
        try {
            Mail::raw('Teste básico de configuração de e-mail do Órbita', function($message) use ($email) {
                $message->to($email)
                        ->subject('🔧 Teste Básico - Órbita');
            });
            return true;
        } catch (\Exception $e) {
            $this->error("Erro: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Testar e-mail com template
     */
    private function testTemplateEmail($email): bool
    {
        try {
            $data = [
                'titulo' => 'Reunião de Teste',
                'descricao' => 'Esta é uma reunião de teste para verificar o template',
                'data_inicio' => now()->addDay(),
                'data_fim' => now()->addDay()->addHour(),
                'meet_link' => 'https://meet.google.com/test-link',
                'organizador' => 'Sistema Órbita',
                'agenda_id' => 999,
                'participant_email' => $email
            ];

            Mail::send('emails.meeting-confirmation', $data, function($message) use ($email) {
                $message->to($email)
                        ->subject('🎨 Teste Template - Órbita');
            });
            return true;
        } catch (\Exception $e) {
            $this->error("Erro: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Testar EmailService
     */
    private function testEmailService($email): bool
    {
        try {
            $emailService = new EmailService();
            $result = $emailService->sendMeetingConfirmation(
                [$email],
                'Teste EmailService',
                'Teste do serviço de e-mail',
                now()->addDay(),
                now()->addDay()->addHour(),
                'https://meet.google.com/test-service',
                'Sistema Órbita',
                999
            );

            return $result['success'];
        } catch (\Exception $e) {
            $this->error("Erro: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Verificar última agenda
     */
    private function checkLastAgenda()
    {
        $agenda = Agenda::orderBy('created_at', 'desc')->first();
        
        if ($agenda) {
            $this->line("📅 Última agenda: {$agenda->titulo} (ID: {$agenda->id})");
            $this->line("📧 Participantes: " . json_encode($agenda->participantes));
            $this->line("⏰ Criada em: {$agenda->created_at}");
            $this->line("🎯 Data da reunião: {$agenda->data_inicio}");
            
            // Verificar se tem participantes
            if (empty($agenda->participantes)) {
                $this->warn("⚠️  Agenda sem participantes - e-mail não será enviado");
            }
        } else {
            $this->warn("⚠️  Nenhuma agenda encontrada");
        }
    }

    /**
     * Verificar configurações
     */
    private function checkConfiguration()
    {
        $this->line("📧 MAIL_MAILER: " . config('mail.default'));
        $this->line("🏠 MAIL_HOST: " . config('mail.mailers.smtp.host'));
        $this->line("🔌 MAIL_PORT: " . config('mail.mailers.smtp.port'));
        $this->line("👤 MAIL_USERNAME: " . (config('mail.mailers.smtp.username') ? '***configurado***' : 'NÃO CONFIGURADO'));
        $this->line("🔒 MAIL_PASSWORD: " . (config('mail.mailers.smtp.password') ? '***configurado***' : 'NÃO CONFIGURADO'));
        $this->line("📨 MAIL_FROM_ADDRESS: " . config('mail.from.address'));
        $this->line("🏷️  MAIL_FROM_NAME: " . config('mail.from.name'));
        
        // Verificar se as configurações essenciais estão definidas
        if (!config('mail.mailers.smtp.username') || !config('mail.mailers.smtp.password')) {
            $this->warn("⚠️  Configurações de SMTP incompletas no .env");
        }
    }
}