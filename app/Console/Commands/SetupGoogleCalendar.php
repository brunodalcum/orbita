<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupGoogleCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:google-calendar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configurar Google Calendar de forma interativa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Configuração do Google Calendar para Orbita Agenda');
        $this->line('');

        // Verificar se o arquivo .env existe
        if (!File::exists(base_path('.env'))) {
            $this->error('Arquivo .env não encontrado!');
            return 1;
        }

        $this->info('📋 Configurações necessárias:');
        $this->line('');

        // Perguntar sobre o tipo de autenticação
        $authType = $this->choice(
            'Qual tipo de autenticação você quer usar?',
            ['OAuth2 (desenvolvimento)', 'Service Account (produção)'],
            0
        );

        $isServiceAccount = $authType === 'Service Account (produção)';

        if ($isServiceAccount) {
            $this->setupServiceAccount();
        } else {
            $this->setupOAuth2();
        }

        $this->info('✅ Configuração concluída!');
        $this->line('');
        $this->info('📖 Próximos passos:');
        $this->line('1. Configure as credenciais no Google Cloud Console');
        $this->line('2. Adicione as configurações ao arquivo .env');
        $this->line('3. Execute: php artisan test:google-calendar');
        $this->line('');
        $this->info('📚 Consulte ENV_GOOGLE_CALENDAR_EXAMPLE.md para detalhes');

        return 0;
    }

    /**
     * Configurar Service Account
     */
    private function setupServiceAccount()
    {
        $this->info('🔐 Configurando Service Account...');
        $this->line('');

        $this->line('📝 Passos para Service Account:');
        $this->line('1. Acesse: https://console.cloud.google.com/');
        $this->line('2. Crie um projeto ou selecione um existente');
        $this->line('3. Ative a Google Calendar API');
        $this->line('4. Vá em IAM & Admin > Service Accounts');
        $this->line('5. Crie um Service Account');
        $this->line('6. Baixe o arquivo JSON de credenciais');
        $this->line('7. Coloque em: storage/app/google-credentials.json');
        $this->line('');

        $this->line('⚙️ Configurações para o .env:');
        $this->line('GOOGLE_SERVICE_ACCOUNT_ENABLED=true');
        $this->line('GOOGLE_SERVICE_ACCOUNT_FILE=storage/app/google-credentials.json');
        $this->line('GOOGLE_MEET_ENABLED=true');
        $this->line('GOOGLE_MEET_DEFAULT_TIMEZONE=America/Sao_Paulo');
        $this->line('');

        $this->line('🔑 Permissões necessárias:');
        $this->line('- Calendar API Admin');
        $this->line('- Compartilhar calendário com o email do Service Account');
        $this->line('');
    }

    /**
     * Configurar OAuth2
     */
    private function setupOAuth2()
    {
        $this->info('🔐 Configurando OAuth2...');
        $this->line('');

        $this->line('📝 Passos para OAuth2:');
        $this->line('1. Acesse: https://console.cloud.google.com/');
        $this->line('2. Crie um projeto ou selecione um existente');
        $this->line('3. Ative a Google Calendar API');
        $this->line('4. Vá em APIs & Services > Credentials');
        $this->line('5. Crie OAuth 2.0 Client ID');
        $this->line('6. Configure como Web application');
        $this->line('7. Adicione URI de redirecionamento: http://127.0.0.1:8000/auth/google/callback');
        $this->line('');

        $this->line('⚙️ Configurações para o .env:');
        $this->line('GOOGLE_SERVICE_ACCOUNT_ENABLED=false');
        $this->line('GOOGLE_CALENDAR_CLIENT_ID=seu_client_id_aqui');
        $this->line('GOOGLE_CALENDAR_CLIENT_SECRET=seu_client_secret_aqui');
        $this->line('GOOGLE_CALENDAR_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback');
        $this->line('GOOGLE_MEET_ENABLED=true');
        $this->line('GOOGLE_MEET_DEFAULT_TIMEZONE=America/Sao_Paulo');
        $this->line('');

        $this->line('🔑 Scopes necessários:');
        $this->line('- https://www.googleapis.com/auth/calendar');
        $this->line('- https://www.googleapis.com/auth/calendar.events');
        $this->line('');
    }
}
