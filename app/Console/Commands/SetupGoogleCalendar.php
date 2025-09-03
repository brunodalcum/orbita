<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleCalendarService;

class SetupGoogleCalendar extends Command
{
    protected $signature = 'google:setup {--force : Forçar nova configuração}';
    protected $description = 'Configurar autenticação OAuth2 para Google Calendar';

    public function handle()
    {
        $this->info('🔧 Configurando Google Calendar OAuth2...');
        
        try {
            $googleService = new GoogleCalendarService();
            
            // Verificar se já está autenticado
            if (method_exists($googleService, 'isAuthenticated') && $googleService->isAuthenticated()) {
                if (!$this->option('force')) {
                    $this->info('✅ Google Calendar já está autenticado!');
                    $this->line('Use --force para reconfigurar.');
                    return 0;
                }
                $this->info('🔄 Reconfigurando autenticação...');
            }
            
            // Obter URL de autorização
            if (method_exists($googleService, 'getAuthUrl')) {
                $result = $googleService->getAuthUrl();
                
                if ($result['success']) {
                    $this->info('🔗 URL de autorização gerada com sucesso!');
                    $this->line('');
                    $this->line('📋 PRÓXIMOS PASSOS:');
                    $this->line('1. Acesse esta URL no seu navegador:');
                    $this->line('   ' . $result['auth_url']);
                    $this->line('');
                    $this->line('2. Faça login com sua conta Google');
                    $this->line('3. Autorize o acesso ao Google Calendar');
                    $this->line('4. Copie o código de autorização da URL de retorno');
                    $this->line('');
                    
                    $code = $this->ask('🔑 Digite o código de autorização:');
                    
                    if ($code) {
                        $this->info('🔄 Processando autorização...');
                        
                        if (method_exists($googleService, 'handleAuthCallback')) {
                            $authResult = $googleService->handleAuthCallback($code);
                            
                            if ($authResult['success']) {
                                $this->info('✅ Autorização realizada com sucesso!');
                                $this->line('');
                                $this->line('🧪 Testando integração...');
                                
                                // Testar criação de evento
                                $testResult = $googleService->createEvent(
                                    'Teste de Integração - ' . date('Y-m-d H:i:s'),
                                    'Teste da integração com Google Calendar via Laravel',
                                    now()->addHour()->toISOString(),
                                    now()->addHours(2)->toISOString(),
                                    ['teste@exemplo.com']
                                );
                                
                                if ($testResult['success']) {
                                    $this->info('✅ Evento de teste criado com sucesso!');
                                    $this->line('🆔 ID: ' . $testResult['event_id']);
                                    
                                    // Limpar evento de teste
                                    $deleteResult = $googleService->deleteEvent($testResult['event_id']);
                                    if ($deleteResult['success']) {
                                        $this->line('🧹 Evento de teste removido');
                                    }
                                } else {
                                    $this->error('❌ Falha ao criar evento de teste: ' . $testResult['error']);
                                }
                            } else {
                                $this->error('❌ Falha na autorização: ' . $authResult['error']);
                                return 1;
                            }
                        } else {
                            $this->error('❌ Método handleAuthCallback não encontrado');
                            return 1;
                        }
                    } else {
                        $this->warn('⚠️ Código de autorização não fornecido');
                        return 1;
                    }
                } else {
                    $this->error('❌ Falha ao gerar URL de autorização: ' . $result['error']);
                    return 1;
                }
            } else {
                $this->error('❌ Método getAuthUrl não encontrado');
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erro durante a configuração: ' . $e->getMessage());
            if ($this->option('verbose')) {
                $this->line('Stack trace: ' . $e->getTraceAsString());
            }
            return 1;
        }
        
        $this->info('🎉 Configuração concluída com sucesso!');
        $this->line('Agora você pode agendar reuniões que serão salvas no Google Calendar.');
        
        return 0;
    }
}
