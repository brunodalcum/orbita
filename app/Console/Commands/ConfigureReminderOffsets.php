<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserReminderSettings;
use App\Models\User;

class ConfigureReminderOffsets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:configure 
                            {--user-id= : ID do usuário (deixe vazio para configuração global)}
                            {--offsets= : Offsets em minutos separados por vírgula (ex: 2880,1440,60)}
                            {--list : Listar configurações atuais}
                            {--reset : Resetar para configurações padrão}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configurar prazos de lembretes por usuário ou globalmente';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');
        $offsets = $this->option('offsets');
        $list = $this->option('list');
        $reset = $this->option('reset');

        if ($list) {
            return $this->listConfigurations($userId);
        }

        if ($reset) {
            return $this->resetConfigurations($userId);
        }

        if ($offsets) {
            return $this->setOffsets($userId, $offsets);
        }

        // Modo interativo
        return $this->interactiveConfiguration($userId);
    }

    /**
     * Listar configurações atuais
     */
    private function listConfigurations($userId)
    {
        $this->info('📋 Configurações de Lembretes');
        $this->newLine();

        if ($userId) {
            // Configuração específica do usuário
            $user = User::find($userId);
            if (!$user) {
                $this->error("❌ Usuário com ID {$userId} não encontrado");
                return 1;
            }

            $settings = UserReminderSettings::getForUser($userId);
            $this->displayUserSettings($user, $settings);

        } else {
            // Listar todos os usuários com configurações personalizadas
            $this->info('🌐 Configurações Padrão do Sistema:');
            $defaults = UserReminderSettings::getSystemDefaults();
            $this->displayOffsets($defaults['reminder_offsets'], 'Padrão');
            $this->newLine();

            $customSettings = UserReminderSettings::with('user')->get();
            
            if ($customSettings->count() > 0) {
                $this->info('👥 Usuários com Configurações Personalizadas:');
                foreach ($customSettings as $setting) {
                    $this->displayUserSettings($setting->user, $setting);
                }
            } else {
                $this->info('ℹ️  Nenhum usuário possui configurações personalizadas');
            }
        }

        return 0;
    }

    /**
     * Resetar configurações
     */
    private function resetConfigurations($userId)
    {
        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("❌ Usuário com ID {$userId} não encontrado");
                return 1;
            }

            UserReminderSettings::where('user_id', $userId)->delete();
            $this->info("✅ Configurações do usuário {$user->name} resetadas para o padrão");

        } else {
            if ($this->confirm('⚠️  Tem certeza que deseja resetar TODAS as configurações personalizadas?')) {
                UserReminderSettings::truncate();
                $this->info('✅ Todas as configurações personalizadas foram resetadas');
            } else {
                $this->info('❌ Operação cancelada');
            }
        }

        return 0;
    }

    /**
     * Definir offsets
     */
    private function setOffsets($userId, $offsetsString)
    {
        // Parsear offsets
        $offsets = array_map('intval', explode(',', $offsetsString));
        $offsets = array_filter($offsets, function($offset) {
            return $offset > 0;
        });

        if (empty($offsets)) {
            $this->error('❌ Nenhum offset válido fornecido');
            return 1;
        }

        // Ordenar em ordem decrescente
        rsort($offsets);

        if ($userId) {
            // Configuração específica do usuário
            $user = User::find($userId);
            if (!$user) {
                $this->error("❌ Usuário com ID {$userId} não encontrado");
                return 1;
            }

            $settings = UserReminderSettings::getForUser($userId);
            $settings->updateReminderOffsets($offsets);

            $this->info("✅ Offsets configurados para {$user->name}:");
            $this->displayOffsets($offsets);

        } else {
            $this->error('❌ Para alterar configurações globais, especifique --user-id ou use o modo interativo');
            return 1;
        }

        return 0;
    }

    /**
     * Configuração interativa
     */
    private function interactiveConfiguration($userId)
    {
        $this->info('🔧 Configuração Interativa de Lembretes');
        $this->newLine();

        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("❌ Usuário com ID {$userId} não encontrado");
                return 1;
            }
            $this->info("Configurando para: {$user->name} ({$user->email})");
        } else {
            // Selecionar usuário
            $users = User::orderBy('name')->get();
            $userChoices = $users->mapWithKeys(function($user) {
                return [$user->id => "{$user->name} ({$user->email})"];
            })->toArray();

            $userId = $this->choice('Selecione o usuário:', $userChoices);
            $user = User::find($userId);
        }

        // Mostrar configuração atual
        $settings = UserReminderSettings::getForUser($userId);
        $this->info('Configuração atual:');
        $this->displayOffsets($settings->getReminderOffsets());
        $this->newLine();

        // Opções predefinidas
        $presets = [
            '1' => [2880, 1440, 60],     // 48h, 24h, 1h (padrão)
            '2' => [1440, 60],           // 24h, 1h
            '3' => [2880, 1440],         // 48h, 24h
            '4' => [4320, 2880, 1440, 60], // 3 dias, 48h, 24h, 1h
            '5' => [60],                 // Apenas 1h
            'custom' => 'Personalizado'
        ];

        $this->info('Opções disponíveis:');
        $this->info('1. Padrão: 48h, 24h, 1h antes');
        $this->info('2. Simples: 24h, 1h antes');
        $this->info('3. Antecipado: 48h, 24h antes');
        $this->info('4. Completo: 3 dias, 48h, 24h, 1h antes');
        $this->info('5. Último minuto: 1h antes');
        $this->info('custom. Personalizado');

        $choice = $this->choice('Escolha uma opção:', array_keys($presets));

        if ($choice === 'custom') {
            $offsetsInput = $this->ask('Digite os offsets em minutos separados por vírgula (ex: 2880,1440,60):');
            $offsets = array_map('intval', explode(',', $offsetsInput));
            $offsets = array_filter($offsets, function($offset) {
                return $offset > 0;
            });

            if (empty($offsets)) {
                $this->error('❌ Nenhum offset válido fornecido');
                return 1;
            }
        } else {
            $offsets = $presets[$choice];
        }

        // Ordenar e aplicar
        rsort($offsets);
        $settings->updateReminderOffsets($offsets);

        $this->info('✅ Configuração aplicada com sucesso!');
        $this->info('Nova configuração:');
        $this->displayOffsets($offsets);

        return 0;
    }

    /**
     * Exibir configurações de um usuário
     */
    private function displayUserSettings(User $user, UserReminderSettings $settings)
    {
        $this->info("👤 {$user->name} ({$user->email}):");
        $this->displayOffsets($settings->getReminderOffsets(), '  ');
        $this->line("  📧 Email: " . ($settings->email_enabled ? 'Ativado' : 'Desativado'));
        $this->line("  🌙 Período silencioso: " . ($settings->respect_quiet_hours ? 
            "{$settings->quiet_start->format('H:i')} - {$settings->quiet_end->format('H:i')}" : 'Desativado'));
        $this->line("  🌍 Timezone: {$settings->timezone}");
        $this->newLine();
    }

    /**
     * Exibir offsets formatados
     */
    private function displayOffsets(array $offsets, string $prefix = '')
    {
        foreach ($offsets as $offset) {
            $label = $this->formatOffset($offset);
            $this->line("{$prefix}⏰ {$offset} minutos ({$label})");
        }
    }

    /**
     * Formatar offset em texto legível
     */
    private function formatOffset(int $minutes): string
    {
        if ($minutes >= 1440) {
            $days = intval($minutes / 1440);
            return $days === 1 ? '1 dia antes' : "{$days} dias antes";
        } elseif ($minutes >= 60) {
            $hours = intval($minutes / 60);
            return $hours === 1 ? '1 hora antes' : "{$hours} horas antes";
        } else {
            return "{$minutes} minutos antes";
        }
    }
}