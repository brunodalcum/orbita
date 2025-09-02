<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Enviando email de teste para: {$email}");
        
        try {
            Mail::raw('Este é um email de teste do sistema Dspay. Se você recebeu este email, significa que a configuração de email está funcionando corretamente!', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Teste de Email - Dspay')
                        ->from('brunodalcum@dspay.com.br', 'Dspay');
            });
            
            $this->info('Email enviado com sucesso!');
            
        } catch (\Exception $e) {
            $this->error('Erro ao enviar email: ' . $e->getMessage());
            $this->error('Detalhes: ' . $e->getTraceAsString());
        }
    }
}
