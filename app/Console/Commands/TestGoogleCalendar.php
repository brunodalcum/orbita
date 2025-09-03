<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleCalendarService;
use Carbon\Carbon;

class TestGoogleCalendar extends Command
{
    protected $signature = 'test:google-calendar {--verbose : Mostrar informações detalhadas}';
    protected $description = 'Testar integração com Google Calendar';

    public function handle()
    {
        $this->info('🧪 Testando integração com Google Calendar...');
        
        try {
            // Verificar configurações
            $this->info('📋 Verificando configurações...');
            $this->checkConfiguration();
            
            // Testar conexão
            $this->info('🔗 Testando conexão...');
            $this->testConnection();
            
            // Testar criação de evento
            $this->info('📅 Testando criação de evento...');
            $this->testEventCreation();
            
        } catch (\Exception $e) {
            $this->error('❌ Erro durante o teste: ' . $e->getMessage());
            if ($this->option('verbose')) {
                $this->line('Stack trace: ' . $e->getTraceAsString());
            }
            return 1;
        }
        
        $this->info('✅ Teste concluído!');
        return 0;
    }

    private function checkConfiguration()
    {
        $configs = [
            'Client ID' => config('google-calendar.client_id'),
            'Client Secret' => config('google-calendar.client_secret'),
            'API Key' => config('google-calendar.api_key'),
            'Service Account Enabled' => config('google-calendar.service_account_enabled'),
            'Meet Enabled' => config('google-calendar.meet_enabled'),
            'Default Timezone' => config('google-calendar.default_timezone'),
            'Calendar ID' => config('google-calendar.calendar_id'),
        ];

        foreach ($configs as $key => $value) {
            if ($value) {
                $this->line("  ✅ {$key}: " . (is_bool($value) ? ($value ? 'Sim' : 'Não') : $value));
            } else {
                $this->line("  ❌ {$key}: Não configurado");
            }
        }
    }

    private function testConnection()
    {
        try {
            $googleService = new GoogleCalendarService();
            $result = $googleService->testConnection();
            
            if ($result['success']) {
                $this->info("  ✅ {$result['message']}");
                $this->line("  📊 Calendários encontrados: {$result['calendars_count']}");
            } else {
                $this->error("  ❌ Falha na conexão: {$result['error']}");
                if ($this->option('verbose') && isset($result['details'])) {
                    $this->line("  📝 Detalhes: " . json_encode($result['details'], JSON_PRETTY_PRINT));
                }
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Erro ao testar conexão: {$e->getMessage()}");
        }
    }

    private function testEventCreation()
    {
        try {
            $googleService = new GoogleCalendarService();
            
            $titulo = 'Teste de Integração - ' . date('Y-m-d H:i:s');
            $descricao = 'Teste da integração com Google Calendar via Laravel';
            $dataInicio = Carbon::now()->addHour()->toISOString();
            $dataFim = Carbon::now()->addHours(2)->toISOString();
            $participantes = ['teste@exemplo.com'];
            
            $this->line("  📝 Título: {$titulo}");
            $this->line("  🕐 Início: {$dataInicio}");
            $this->line("  🕐 Fim: {$dataFim}");
            $this->line("  👥 Participantes: " . implode(', ', $participantes));
            
            $result = $googleService->createEvent($titulo, $descricao, $dataInicio, $dataFim, $participantes);
            
            if ($result['success']) {
                $this->info("  ✅ Evento criado com sucesso!");
                $this->line("  🆔 ID do evento: {$result['event_id']}");
                if ($result['meet_link']) {
                    $this->line("  🔗 Link do Meet: {$result['meet_link']}");
                }
                $this->line("  🌐 Link do evento: {$result['html_link']}");
                
                // Limpar evento de teste
                $this->info("  🧹 Limpando evento de teste...");
                $deleteResult = $googleService->deleteEvent($result['event_id']);
                if ($deleteResult['success']) {
                    $this->line("  ✅ Evento de teste removido");
                } else {
                    $this->warning("  ⚠️ Não foi possível remover evento de teste: {$deleteResult['error']}");
                }
            } else {
                $this->error("  ❌ Falha ao criar evento: {$result['error']}");
                if ($this->option('verbose') && isset($result['details'])) {
                    $this->line("  📝 Detalhes: " . json_encode($result['details'], JSON_PRETTY_PRINT));
                }
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Erro ao testar criação de evento: {$e->getMessage()}");
            if ($this->option('verbose')) {
                $this->line("  📝 Stack trace: " . $e->getTraceAsString());
            }
        }
    }
}
