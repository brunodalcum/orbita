<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReminderMetrics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:metrics 
                            {--period=today : Período para métricas (today, yesterday, week, month)}
                            {--format=table : Formato de saída (table, json, log)}
                            {--detailed : Mostrar métricas detalhadas}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gerar métricas e relatórios de lembretes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $period = $this->option('period');
        $format = $this->option('format');
        $detailed = $this->option('detailed');

        $this->info('📊 Gerando métricas de lembretes...');

        try {
            $dateRange = $this->getDateRange($period);
            $metrics = $this->calculateMetrics($dateRange, $detailed);

            $this->displayMetrics($metrics, $format, $period);

            // Registrar métricas nos logs
            $this->logMetrics($metrics, $period);

        } catch (\Exception $e) {
            $this->error('❌ Erro ao gerar métricas: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Obter intervalo de datas baseado no período
     */
    private function getDateRange(string $period): array
    {
        switch ($period) {
            case 'today':
                return [
                    'start' => now()->startOfDay(),
                    'end' => now()->endOfDay(),
                ];

            case 'yesterday':
                return [
                    'start' => now()->subDay()->startOfDay(),
                    'end' => now()->subDay()->endOfDay(),
                ];

            case 'week':
                return [
                    'start' => now()->startOfWeek(),
                    'end' => now()->endOfWeek(),
                ];

            case 'month':
                return [
                    'start' => now()->startOfMonth(),
                    'end' => now()->endOfMonth(),
                ];

            default:
                return [
                    'start' => now()->startOfDay(),
                    'end' => now()->endOfDay(),
                ];
        }
    }

    /**
     * Calcular métricas
     */
    private function calculateMetrics(array $dateRange, bool $detailed): array
    {
        $start = $dateRange['start'];
        $end = $dateRange['end'];

        // Métricas básicas
        $totalReminders = Reminder::whereBetween('created_at', [$start, $end])->count();
        $sentReminders = Reminder::whereBetween('sent_at', [$start, $end])->where('status', 'sent')->count();
        $failedReminders = Reminder::whereBetween('updated_at', [$start, $end])->where('status', 'failed')->count();
        $canceledReminders = Reminder::whereBetween('updated_at', [$start, $end])->where('status', 'canceled')->count();
        $pendingReminders = Reminder::where('status', 'pending')->count();

        // Taxa de sucesso
        $successRate = $totalReminders > 0 ? round(($sentReminders / $totalReminders) * 100, 2) : 0;

        // Latência média (tempo entre agendado e enviado)
        $avgLatency = Reminder::whereBetween('sent_at', [$start, $end])
            ->where('status', 'sent')
            ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, send_at, sent_at)) as avg_seconds')
            ->value('avg_seconds');

        $avgLatency = $avgLatency ? round($avgLatency, 2) : 0;

        $metrics = [
            'period' => [
                'start' => $start->format('d/m/Y H:i'),
                'end' => $end->format('d/m/Y H:i'),
            ],
            'totals' => [
                'created' => $totalReminders,
                'sent' => $sentReminders,
                'failed' => $failedReminders,
                'canceled' => $canceledReminders,
                'pending' => $pendingReminders,
            ],
            'performance' => [
                'success_rate' => $successRate,
                'avg_latency_seconds' => $avgLatency,
                'avg_latency_formatted' => $this->formatLatency($avgLatency),
            ],
        ];

        if ($detailed) {
            $metrics['detailed'] = $this->getDetailedMetrics($start, $end);
        }

        return $metrics;
    }

    /**
     * Obter métricas detalhadas
     */
    private function getDetailedMetrics(Carbon $start, Carbon $end): array
    {
        // Por canal
        $byChannel = Reminder::whereBetween('created_at', [$start, $end])
            ->selectRaw('channel, status, COUNT(*) as count')
            ->groupBy(['channel', 'status'])
            ->get()
            ->groupBy('channel')
            ->map(function ($items) {
                return $items->pluck('count', 'status')->toArray();
            })
            ->toArray();

        // Por offset
        $byOffset = Reminder::whereBetween('created_at', [$start, $end])
            ->selectRaw('offset_minutes, status, COUNT(*) as count')
            ->groupBy(['offset_minutes', 'status'])
            ->get()
            ->groupBy('offset_minutes')
            ->map(function ($items, $offset) {
                $data = $items->pluck('count', 'status')->toArray();
                $data['offset_label'] = $this->formatOffset($offset);
                return $data;
            })
            ->toArray();

        // Erros mais comuns
        $commonErrors = Reminder::whereBetween('updated_at', [$start, $end])
            ->where('status', 'failed')
            ->whereNotNull('last_error')
            ->selectRaw('last_error, COUNT(*) as count')
            ->groupBy('last_error')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'error' => \Str::limit($item->last_error, 100),
                    'count' => $item->count,
                ];
            })
            ->toArray();

        // Distribuição por hora do dia
        $byHour = Reminder::whereBetween('sent_at', [$start, $end])
            ->where('status', 'sent')
            ->selectRaw('HOUR(sent_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->pluck('count', 'hour')
            ->toArray();

        return [
            'by_channel' => $byChannel,
            'by_offset' => $byOffset,
            'common_errors' => $commonErrors,
            'by_hour' => $byHour,
        ];
    }

    /**
     * Exibir métricas
     */
    private function displayMetrics(array $metrics, string $format, string $period)
    {
        switch ($format) {
            case 'json':
                $this->line(json_encode($metrics, JSON_PRETTY_PRINT));
                break;

            case 'log':
                Log::info('📊 Métricas de Lembretes', $metrics);
                $this->info('✅ Métricas registradas nos logs');
                break;

            case 'table':
            default:
                $this->displayTableMetrics($metrics, $period);
                break;
        }
    }

    /**
     * Exibir métricas em formato tabela
     */
    private function displayTableMetrics(array $metrics, string $period)
    {
        $this->info("📊 Métricas de Lembretes - " . ucfirst($period));
        $this->info("Período: {$metrics['period']['start']} até {$metrics['period']['end']}");
        $this->newLine();

        // Totais
        $this->info('📈 Totais:');
        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Criados', $metrics['totals']['created']],
                ['Enviados', $metrics['totals']['sent']],
                ['Falharam', $metrics['totals']['failed']],
                ['Cancelados', $metrics['totals']['canceled']],
                ['Pendentes', $metrics['totals']['pending']],
            ]
        );

        // Performance
        $this->info('⚡ Performance:');
        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Taxa de Sucesso', $metrics['performance']['success_rate'] . '%'],
                ['Latência Média', $metrics['performance']['avg_latency_formatted']],
            ]
        );

        // Métricas detalhadas
        if (isset($metrics['detailed'])) {
            $this->displayDetailedMetrics($metrics['detailed']);
        }
    }

    /**
     * Exibir métricas detalhadas
     */
    private function displayDetailedMetrics(array $detailed)
    {
        $this->newLine();
        $this->info('🔍 Métricas Detalhadas:');

        // Por canal
        if (!empty($detailed['by_channel'])) {
            $this->info('📧 Por Canal:');
            foreach ($detailed['by_channel'] as $channel => $stats) {
                $this->line("  {$channel}: " . json_encode($stats));
            }
        }

        // Por offset
        if (!empty($detailed['by_offset'])) {
            $this->info('⏰ Por Offset:');
            foreach ($detailed['by_offset'] as $offset => $stats) {
                $label = $stats['offset_label'] ?? $offset . 'min';
                unset($stats['offset_label']);
                $this->line("  {$label}: " . json_encode($stats));
            }
        }

        // Erros comuns
        if (!empty($detailed['common_errors'])) {
            $this->info('❌ Erros Mais Comuns:');
            $this->table(
                ['Erro', 'Ocorrências'],
                collect($detailed['common_errors'])->map(function ($error) {
                    return [$error['error'], $error['count']];
                })->toArray()
            );
        }
    }

    /**
     * Registrar métricas nos logs
     */
    private function logMetrics(array $metrics, string $period)
    {
        Log::info('📊 Métricas de Lembretes Geradas', [
            'period' => $period,
            'timestamp' => now()->toISOString(),
            'metrics' => $metrics,
        ]);
    }

    /**
     * Formatar latência em formato legível
     */
    private function formatLatency(float $seconds): string
    {
        if ($seconds < 60) {
            return round($seconds, 1) . 's';
        } elseif ($seconds < 3600) {
            return round($seconds / 60, 1) . 'min';
        } else {
            return round($seconds / 3600, 1) . 'h';
        }
    }

    /**
     * Formatar offset em formato legível
     */
    private function formatOffset(int $minutes): string
    {
        if ($minutes >= 1440) {
            $days = intval($minutes / 1440);
            return $days === 1 ? '1 dia' : "{$days} dias";
        } elseif ($minutes >= 60) {
            $hours = intval($minutes / 60);
            return $hours === 1 ? '1 hora' : "{$hours} horas";
        } else {
            return "{$minutes} minutos";
        }
    }
}