<?php

namespace App\Jobs;

use App\Models\Reminder;
use App\Jobs\SendReminderEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DispatchReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Timeout do job em segundos
     */
    public $timeout = 300; // 5 minutos

    /**
     * Número máximo de tentativas
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('🚀 DispatchReminders: Iniciando processamento de lembretes');

        $startTime = microtime(true);
        $processed = 0;
        $dispatched = 0;
        $errors = 0;

        try {
            // Buscar lembretes prontos para envio
            $reminders = Reminder::readyToSend()
                ->orderBy('send_at')
                ->limit(100) // Processar no máximo 100 por vez
                ->get();

            Log::info('📋 Lembretes encontrados para processamento', [
                'count' => $reminders->count(),
                'cutoff_time' => now()->toISOString(),
            ]);

            foreach ($reminders as $reminder) {
                $processed++;

                try {
                    // Verificar se ainda está pendente (evitar race conditions)
                    if ($reminder->fresh()->status !== 'pending') {
                        Log::debug('⏭️ Lembrete já processado, pulando', [
                            'reminder_id' => $reminder->id,
                            'current_status' => $reminder->fresh()->status,
                        ]);
                        continue;
                    }

                    // Disparar job de envio individual
                    SendReminderEmail::dispatch($reminder);
                    $dispatched++;

                    Log::debug('📤 Job de envio disparado', [
                        'reminder_id' => $reminder->id,
                        'participant' => $reminder->participant_email,
                        'event_title' => $reminder->event_title,
                        'send_at' => $reminder->send_at,
                    ]);

                } catch (\Exception $e) {
                    $errors++;
                    
                    Log::error('❌ Erro ao processar lembrete', [
                        'reminder_id' => $reminder->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);

                    // Marcar como falhou
                    $reminder->markAsFailed($e->getMessage());
                }
            }

        } catch (\Exception $e) {
            Log::error('💥 Erro crítico no DispatchReminders', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e; // Re-throw para que o job seja marcado como falhou
        }

        $executionTime = round((microtime(true) - $startTime) * 1000, 2);

        Log::info('✅ DispatchReminders concluído', [
            'processed' => $processed,
            'dispatched' => $dispatched,
            'errors' => $errors,
            'execution_time_ms' => $executionTime,
        ]);

        // Registrar métricas
        $this->recordMetrics($processed, $dispatched, $errors, $executionTime);
    }

    /**
     * Registrar métricas de performance
     */
    private function recordMetrics(int $processed, int $dispatched, int $errors, float $executionTime)
    {
        // Por enquanto, apenas logs estruturados
        // Futuramente, integrar com sistema de métricas (Prometheus, etc.)
        
        Log::info('📊 Métricas DispatchReminders', [
            'timestamp' => now()->toISOString(),
            'metrics' => [
                'reminders_processed' => $processed,
                'reminders_dispatched' => $dispatched,
                'reminders_errors' => $errors,
                'execution_time_ms' => $executionTime,
                'success_rate' => $processed > 0 ? round(($dispatched / $processed) * 100, 2) : 100,
            ],
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('💥 DispatchReminders falhou completamente', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'attempts' => $this->attempts(),
        ]);
    }
}