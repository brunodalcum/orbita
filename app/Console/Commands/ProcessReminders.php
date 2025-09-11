<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\DispatchReminders;
use Illuminate\Support\Facades\Log;

class ProcessReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:process 
                            {--dry-run : Executar sem disparar jobs (apenas mostrar o que seria processado)}
                            {--limit=100 : Limite de lembretes a processar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processar lembretes pendentes e disparar jobs de envio';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $limit = (int) $this->option('limit');

        $this->info('🔔 Iniciando processamento de lembretes...');
        
        if ($dryRun) {
            $this->warn('⚠️  MODO DRY-RUN: Nenhum job será disparado');
        }

        try {
            if ($dryRun) {
                $this->runDryRun($limit);
            } else {
                $this->runProcessing();
            }

        } catch (\Exception $e) {
            $this->error('❌ Erro ao processar lembretes: ' . $e->getMessage());
            Log::error('Erro no comando ProcessReminders', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 1;
        }

        return 0;
    }

    /**
     * Executar em modo dry-run (apenas mostrar o que seria processado)
     */
    private function runDryRun(int $limit)
    {
        $reminders = \App\Models\Reminder::readyToSend()
            ->orderBy('send_at')
            ->limit($limit)
            ->get();

        $this->info("📋 Lembretes encontrados: {$reminders->count()}");

        if ($reminders->isEmpty()) {
            $this->info('✅ Nenhum lembrete pendente para processar');
            return;
        }

        $this->table(
            ['ID', 'Participante', 'Evento', 'Enviar em', 'Offset', 'Tentativas'],
            $reminders->map(function ($reminder) {
                return [
                    $reminder->id,
                    $reminder->participant_email,
                    \Str::limit($reminder->event_title, 30),
                    $reminder->send_at->format('d/m/Y H:i:s'),
                    $reminder->offset_minutes . 'min',
                    $reminder->attempts,
                ];
            })->toArray()
        );

        $this->info('💡 Execute sem --dry-run para processar estes lembretes');
    }

    /**
     * Executar processamento real
     */
    private function runProcessing()
    {
        $this->info('🚀 Disparando job DispatchReminders...');
        
        DispatchReminders::dispatch();
        
        $this->info('✅ Job DispatchReminders disparado com sucesso');
        $this->info('📧 Os lembretes serão processados em background');
        
        // Mostrar estatísticas básicas
        $pendingCount = \App\Models\Reminder::where('status', 'pending')->count();
        $readyCount = \App\Models\Reminder::readyToSend()->count();
        
        $this->info("📊 Estatísticas:");
        $this->line("   • Lembretes pendentes: {$pendingCount}");
        $this->line("   • Prontos para envio: {$readyCount}");
    }
}