<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\MarketingEmail;
use App\Models\EmailModelo;
use App\Models\Campanha;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email {email?} {--campanha=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa o envio de e-mails de marketing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?: 'teste@example.com';
        $campanhaId = $this->option('campanha');

        $this->info("🧪 TESTANDO ENVIO DE E-MAIL");
        $this->info("================================");
        $this->info("Email de destino: {$email}");
        $this->info("");

        try {
            // Verificar configuração de e-mail
            $this->info("📧 Verificando configuração de e-mail...");
            $mailConfig = config('mail');
            $this->table(
                ['Configuração', 'Valor'],
                [
                    ['Driver', $mailConfig['default']],
                    ['Host', $mailConfig['mailers'][$mailConfig['default']]['host'] ?? 'N/A'],
                    ['Port', $mailConfig['mailers'][$mailConfig['default']]['port'] ?? 'N/A'],
                    ['From Address', $mailConfig['from']['address'] ?? 'N/A'],
                    ['From Name', $mailConfig['from']['name'] ?? 'N/A'],
                ]
            );

            // Buscar modelo de e-mail
            $modelo = null;
            if ($campanhaId) {
                $campanha = Campanha::with('modelo')->find($campanhaId);
                if ($campanha) {
                    $modelo = $campanha->modelo;
                    $this->info("📋 Usando modelo da campanha: {$campanha->nome}");
                }
            }

            if (!$modelo) {
                $modelo = EmailModelo::first();
                if (!$modelo) {
                    $this->error("❌ Nenhum modelo de e-mail encontrado!");
                    $this->info("Execute: php artisan db:seed --class=EmailModeloSeeder");
                    return 1;
                }
                $this->info("📋 Usando modelo: {$modelo->nome}");
            }

            $this->info("");

            // Testar envio
            $this->info("🚀 Enviando e-mail de teste...");
            
            Mail::to($email)->send(new MarketingEmail($modelo));
            
            $this->info("✅ E-mail enviado com sucesso!");
            $this->info("");

            // Verificar logs
            $this->info("📋 Verificando logs...");
            $logFile = storage_path('logs/laravel.log');
            if (file_exists($logFile)) {
                $lastLines = file($logFile);
                $lastLines = array_slice($lastLines, -10);
                $this->info("Últimas 10 linhas do log:");
                foreach ($lastLines as $line) {
                    $this->line(trim($line));
                }
            }

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Erro ao enviar e-mail: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            
            // Verificar logs de erro
            $this->info("📋 Verificando logs de erro...");
            $logFile = storage_path('logs/laravel.log');
            if (file_exists($logFile)) {
                $lastLines = file($logFile);
                $lastLines = array_slice($lastLines, -20);
                $this->info("Últimas 20 linhas do log:");
                foreach ($lastLines as $line) {
                    $this->line(trim($line));
                }
            }
            
            return 1;
        }
    }
}
