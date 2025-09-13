<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reminder;
use App\Models\Agenda;
use App\Models\UserReminderSettings;
use App\Services\ReminderService;
use App\Jobs\SendReminderEmail;
use Carbon\Carbon;

class ReminderManagementController extends Controller
{
    protected $reminderService;

    public function __construct()
    {
        $this->reminderService = new ReminderService();
    }

    /**
     * Lista todos os lembretes
     */
    public function index(Request $request)
    {
        $query = Reminder::with(['agenda', 'participant'])
            ->orderBy('send_at', 'desc');

        // Filtros
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->channel) {
            $query->where('channel', $request->channel);
        }

        if ($request->date_from) {
            $query->whereDate('send_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('send_at', '<=', $request->date_to);
        }

        $reminders = $query->paginate(20);

        // Estatísticas
        $stats = [
            'total' => Reminder::count(),
            'pending' => Reminder::where('status', 'pending')->count(),
            'sent' => Reminder::where('status', 'sent')->count(),
            'failed' => Reminder::where('status', 'failed')->count(),
            'paused' => Reminder::where('status', 'paused')->count(),
        ];

        return view('dashboard.reminders.index', compact('reminders', 'stats'));
    }

    /**
     * Mostra formulário de criação
     */
    public function create()
    {
        $agendas = Agenda::where(function($q) {
                $q->where('user_id', Auth::id())
                  ->orWhere('solicitante_id', Auth::id())
                  ->orWhere('destinatario_id', Auth::id());
            })
            ->where('data_inicio', '>', now())
            ->orderBy('data_inicio')
            ->get();

        return view('dashboard.reminders.create', compact('agendas'));
    }

    /**
     * Armazena novo lembrete
     */
    public function store(Request $request)
    {
        $request->validate([
            'agenda_id' => 'required|exists:agendas,id',
            'participant_email' => 'required|email',
            'channel' => 'required|in:email,sms,push',
            'send_at' => 'required|date|after:now',
            'message' => 'nullable|string|max:1000',
        ]);

        try {
            // Verificar se o participante existe
            $participant = \App\Models\User::where('email', $request->participant_email)->first();
            if (!$participant) {
                return back()->withErrors(['participant_email' => 'Usuário não encontrado com este email.']);
            }

            // Verificar se a agenda existe e o usuário tem acesso
            $agenda = Agenda::where('id', $request->agenda_id)
                ->where(function($q) {
                    $q->where('user_id', Auth::id())
                      ->orWhere('solicitante_id', Auth::id())
                      ->orWhere('destinatario_id', Auth::id());
                })
                ->firstOrFail();

            // Calcular offset_minutes baseado na diferença entre send_at e data_inicio da agenda
            $sendAt = \Carbon\Carbon::parse($request->send_at);
            $agendaStart = \Carbon\Carbon::parse($agenda->data_inicio);
            $offsetMinutes = $agendaStart->diffInMinutes($sendAt, false); // false para permitir valores negativos
            
            // Criar lembrete
            $reminder = Reminder::create([
                'event_id' => $agenda->id,
                'participant_id' => $participant->id,
                'participant_email' => $participant->email,
                'participant_name' => $participant->name,
                'channel' => $request->channel,
                'send_at' => $request->send_at,
                'offset_minutes' => abs($offsetMinutes), // Valor absoluto para o offset
                'status' => 'pending',
                'attempts' => 0,
                'message' => $request->message,
                'created_by' => Auth::id(),
                'is_test' => false,
                // Campos do evento para compatibilidade
                'event_title' => $agenda->titulo,
                'event_start_utc' => $agenda->data_inicio,
                'event_end_utc' => $agenda->data_fim,
                'event_timezone' => 'America/Sao_Paulo',
                'event_meet_link' => $agenda->meet_link,
                'event_host_name' => Auth::user()->name,
                'event_host_email' => Auth::user()->email,
                'event_description' => $agenda->descricao,
            ]);

            \Log::info('✅ Lembrete criado manualmente', [
                'reminder_id' => $reminder->id,
                'agenda_id' => $agenda->id,
                'participant_email' => $request->participant_email,
                'send_at' => $request->send_at,
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('reminders.index')
                ->with('success', 'Lembrete criado com sucesso!');

        } catch (\Exception $e) {
            \Log::error('❌ Erro ao criar lembrete manual: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Erro ao criar lembrete: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostra detalhes do lembrete
     */
    public function show($id)
    {
        $reminder = Reminder::with(['agenda', 'participant'])->findOrFail($id);
        
        // Se for uma requisição AJAX (para modal), retornar JSON
        if (request()->ajax()) {
            $html = view('dashboard.reminders.show-modal', compact('reminder'))->render();
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        }
        
        // Senão, retornar a view completa
        return view('dashboard.reminders.show', compact('reminder'));
    }

    /**
     * Pausa um lembrete
     */
    public function pause($id)
    {
        try {
            $reminder = Reminder::findOrFail($id);
            
            if ($reminder->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Apenas lembretes pendentes podem ser pausados.'
                ], 400);
            }

            $reminder->update([
                'status' => 'paused',
                'paused_at' => now(),
                'paused_by' => Auth::id(),
            ]);

            \Log::info('⏸️ Lembrete pausado', [
                'reminder_id' => $reminder->id,
                'paused_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lembrete pausado com sucesso!'
            ]);

        } catch (\Exception $e) {
            \Log::error('❌ Erro ao pausar lembrete: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao pausar lembrete: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reativa um lembrete pausado
     */
    public function resume($id)
    {
        try {
            $reminder = Reminder::findOrFail($id);
            
            if ($reminder->status !== 'paused') {
                return response()->json([
                    'success' => false,
                    'message' => 'Apenas lembretes pausados podem ser reativados.'
                ], 400);
            }

            $reminder->update([
                'status' => 'pending',
                'paused_at' => null,
                'paused_by' => null,
            ]);

            \Log::info('▶️ Lembrete reativado', [
                'reminder_id' => $reminder->id,
                'resumed_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lembrete reativado com sucesso!'
            ]);

        } catch (\Exception $e) {
            \Log::error('❌ Erro ao reativar lembrete: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao reativar lembrete: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exclui um lembrete
     */
    public function destroy($id)
    {
        try {
            $reminder = Reminder::findOrFail($id);
            
            // Não permitir exclusão de lembretes já enviados
            if ($reminder->status === 'sent') {
                return response()->json([
                    'success' => false,
                    'message' => 'Lembretes já enviados não podem ser excluídos.'
                ], 400);
            }

            \Log::info('🗑️ Lembrete excluído', [
                'reminder_id' => $reminder->id,
                'status' => $reminder->status,
                'deleted_by' => Auth::id(),
            ]);

            $reminder->delete();

            return response()->json([
                'success' => true,
                'message' => 'Lembrete excluído com sucesso!'
            ]);

        } catch (\Exception $e) {
            \Log::error('❌ Erro ao excluir lembrete: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir lembrete: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Página de testes e configuração
     */
    public function testConfig()
    {
        // Configurações do usuário
        $userSettings = UserReminderSettings::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'reminder_offsets' => [15, 60, 1440], // 15min, 1h, 1dia
                'quiet_hours_start' => '22:00',
                'quiet_hours_end' => '08:00',
                'timezone' => 'America/Sao_Paulo',
                'enabled_channels' => ['email'],
            ]
        );

        // Estatísticas gerais
        $stats = [
            'total_reminders' => Reminder::count(),
            'pending_reminders' => Reminder::where('status', 'pending')->count(),
            'sent_today' => Reminder::where('status', 'sent')
                ->whereDate('sent_at', today())
                ->count(),
            'failed_today' => Reminder::where('status', 'failed')
                ->whereDate('updated_at', today())
                ->count(),
        ];

        // Próximos lembretes
        $nextReminders = Reminder::where('status', 'pending')
            ->where('send_at', '>', now())
            ->orderBy('send_at')
            ->limit(10)
            ->with(['agenda', 'participant'])
            ->get();

        // Lembretes recentes
        $recentReminders = Reminder::whereIn('status', ['sent', 'failed'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->with(['agenda', 'participant'])
            ->get();

        return view('dashboard.reminders.test-config', compact(
            'userSettings', 
            'stats', 
            'nextReminders', 
            'recentReminders'
        ));
    }

    /**
     * Atualiza configurações do usuário
     */
    public function updateConfig(Request $request)
    {
        $request->validate([
            'reminder_offsets' => 'required|array|min:1',
            'reminder_offsets.*' => 'integer|min:1|max:10080', // máximo 1 semana
            'quiet_hours_start' => 'required|date_format:H:i',
            'quiet_hours_end' => 'required|date_format:H:i',
            'timezone' => 'required|string',
            'enabled_channels' => 'required|array|min:1',
            'enabled_channels.*' => 'in:email,sms,push',
        ]);

        try {
            $userSettings = UserReminderSettings::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'reminder_offsets' => $request->reminder_offsets,
                    'quiet_hours_start' => $request->quiet_hours_start,
                    'quiet_hours_end' => $request->quiet_hours_end,
                    'timezone' => $request->timezone,
                    'enabled_channels' => $request->enabled_channels,
                ]
            );

            \Log::info('⚙️ Configurações de lembrete atualizadas', [
                'user_id' => Auth::id(),
                'settings' => $userSettings->toArray(),
            ]);

            return redirect()->route('reminders.test-config')
                ->with('success', 'Configurações atualizadas com sucesso!');

        } catch (\Exception $e) {
            \Log::error('❌ Erro ao atualizar configurações: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Erro ao atualizar configurações: ' . $e->getMessage()]);
        }
    }

    /**
     * Envia lembrete de teste
     */
    public function sendTest(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
            'test_message' => 'nullable|string|max:500',
        ]);

        try {
            // Criar lembrete de teste
            $testReminder = new Reminder([
                'event_id' => null, // Teste sem evento específico
                'participant_id' => Auth::id(),
                'participant_email' => $request->test_email,
                'participant_name' => Auth::user()->name,
                'channel' => 'email',
                'send_at' => now(),
                'status' => 'pending',
                'message' => $request->test_message ?: 'Este é um lembrete de teste do sistema Orbita.',
                'created_by' => Auth::id(),
                'is_test' => true,
            ]);

            // Disparar email de teste
            SendReminderEmail::dispatch($testReminder, $request->test_email);

            \Log::info('🧪 Lembrete de teste enviado', [
                'test_email' => $request->test_email,
                'sent_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lembrete de teste enviado para ' . $request->test_email
            ]);

        } catch (\Exception $e) {
            \Log::error('❌ Erro ao enviar lembrete de teste: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar teste: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Processa lembretes pendentes manualmente
     */
    public function processNow()
    {
        try {
            \Artisan::call('reminders:process');
            $output = \Artisan::output();

            \Log::info('🔄 Processamento manual de lembretes executado', [
                'executed_by' => Auth::id(),
                'output' => $output,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Processamento de lembretes executado com sucesso!',
                'output' => $output
            ]);

        } catch (\Exception $e) {
            \Log::error('❌ Erro no processamento manual: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro no processamento: ' . $e->getMessage()
            ], 500);
        }
    }
}
