<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Agenda;
use App\Models\User;
use App\Models\AgendaNotification;
use App\Models\BusinessHours;
use App\Services\GoogleCalendarIntegrationService;
use App\Services\EmailService;
use App\Services\ReminderService;
use Carbon\Carbon;

class LicenciadoAgendaController extends Controller
{
    /**
     * Lista de agendas do licenciado
     */
    public function index(Request $request)
    {
        $dataAtual = $request->get('data', now()->format('Y-m-d'));
        
        $query = Agenda::doUsuario(Auth::id());
        
        if ($request->has('data') && $request->get('data')) {
            $query->doDia($dataAtual);
        }
        
        $agendas = $query->with(['solicitante', 'destinatario', 'aprovadoPor'])
                        ->orderBy('data_inicio', 'desc')
                        ->get();
        
        return view('licenciado.agenda.index', compact('agendas', 'dataAtual'));
    }

    /**
     * Calendário mensal do licenciado
     */
    public function calendar(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $date = Carbon::createFromFormat('Y-m', $month);
        
        $agendas = Agenda::doUsuario(Auth::id())
                        ->whereYear('data_inicio', $date->year)
                        ->whereMonth('data_inicio', $date->month)
                        ->with(['solicitante', 'destinatario'])
                        ->orderBy('data_inicio')
                        ->get();
        
        return view('licenciado.agenda.calendar', compact('agendas', 'month', 'date'));
    }

    /**
     * Formulário para criar nova agenda
     */
    public function create()
    {
        // Buscar todos os usuários que o licenciado pode convidar
        // Role IDs: 1=super_admin, 2=admin, 3=funcionario, 4=licenciado
        $usuarios = User::whereIn('role_id', [1, 2, 3]) // Super Admin, Admin, Funcionário
                       ->orWhere(function($query) {
                           $query->where('role_id', 4) // Outros licenciados
                                 ->where('id', '!=', Auth::id());
                       })
                       ->orderBy('name')
                       ->get();
        
        return view('licenciado.agenda.create', compact('usuarios'));
    }

    /**
     * Salvar nova agenda
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255',
                'descricao' => 'nullable|string',
                'data_inicio' => 'required|date_format:Y-m-d\TH:i',
                'data_fim' => 'required|date_format:Y-m-d\TH:i|after:data_inicio',
                'tipo_reuniao' => 'required|in:presencial,online,hibrida',
                'participantes' => 'nullable|string|max:2000',
                'meet_link' => 'nullable|url',
                'destinatario_id' => 'nullable|exists:users,id'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput()->with('error', 'Por favor, corrija os erros no formulário.');
            }

            // Verificar se há destinatário (agenda entre usuários)
            $destinatarioId = $request->destinatario_id;
            $solicitanteId = Auth::id();
            $requerAprovacao = false;
            $foraHorarioComercial = false;
            $statusAprovacao = 'automatica';

            if ($destinatarioId && $destinatarioId != $solicitanteId) {
                // Verificar conflito de horário
                if (BusinessHours::hasTimeConflict($destinatarioId, $request->data_inicio, $request->data_fim)) {
                    return back()->withErrors(['data_inicio' => 'O destinatário já possui compromisso neste horário.'])
                                ->withInput()
                                ->with('error', 'Horário não disponível para o destinatário.');
                }

                // Verificar se está dentro do horário comercial
                $foraHorarioComercial = !BusinessHours::isWithinBusinessHours($destinatarioId, $request->data_inicio) ||
                                       !BusinessHours::isWithinBusinessHours($destinatarioId, $request->data_fim);

                // Definir se requer aprovação
                $requerAprovacao = $foraHorarioComercial || true; // Sempre requer aprovação por enquanto
                $statusAprovacao = $requerAprovacao ? 'pendente' : 'automatica';
            }

            // Processar participantes
            $participantes = [];
            if ($request->participantes) {
                $emailsString = $request->participantes;
                $emails = preg_split('/[,\n\r]+/', $emailsString);
                
                foreach ($emails as $email) {
                    $email = trim($email);
                    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $participantes[] = $email;
                    }
                }
            }

            // Adicionar email do destinatário automaticamente se for usuário
            if ($destinatarioId) {
                $destinatario = User::find($destinatarioId);
                if ($destinatario && $destinatario->email && filter_var($destinatario->email, FILTER_VALIDATE_EMAIL)) {
                    if (!in_array($destinatario->email, $participantes)) {
                        $participantes[] = $destinatario->email;
                    }
                }
            }
            
            // Adicionar automaticamente o email do criador da agenda
            $creatorEmail = Auth::user()->email;
            if ($creatorEmail && filter_var($creatorEmail, FILTER_VALIDATE_EMAIL)) {
                if (!in_array($creatorEmail, $participantes)) {
                    $participantes[] = $creatorEmail;
                }
            }
            
            // Criar a agenda no banco de dados
            $agenda = new Agenda();
            $agenda->titulo = $request->titulo;
            $agenda->descricao = $request->descricao;
            $agenda->data_inicio = $request->data_inicio;
            $agenda->data_fim = $request->data_fim;
            $agenda->tipo_reuniao = $request->tipo_reuniao;
            $agenda->participantes = $participantes;
            $agenda->meet_link = $request->meet_link;
            $agenda->user_id = Auth::id();
            $agenda->solicitante_id = $solicitanteId;
            $agenda->destinatario_id = $destinatarioId;
            $agenda->status_aprovacao = $statusAprovacao;
            $agenda->requer_aprovacao = $requerAprovacao;
            $agenda->fora_horario_comercial = $foraHorarioComercial;
            // Status da agenda baseado na aprovação
            if ($requerAprovacao && $statusAprovacao === 'pendente') {
                $agenda->status = 'agendada'; // Agenda criada, mas aguardando aprovação
            } else {
                $agenda->status = 'agendada'; // Agenda aprovada automaticamente
            }
            
            $agenda->save();

            // Criar lembretes automáticos
            try {
                $reminderService = new ReminderService();
                $reminderStats = $reminderService->createForEvent($agenda);
                
                \Log::info('✅ Lembretes criados para agenda (licenciado)', [
                    'agenda_id' => $agenda->id,
                    'lembretes_criados' => $reminderStats['created'],
                    'lembretes_pulados' => $reminderStats['skipped'],
                ]);
            } catch (\Exception $e) {
                \Log::error('❌ Erro ao criar lembretes (licenciado): ' . $e->getMessage());
                // Não falhar a criação da agenda por causa dos lembretes
            }

            // Criar notificação se há destinatário e requer aprovação
            if ($destinatarioId && $requerAprovacao) {
                AgendaNotification::createSolicitacaoNotification($agenda, $destinatarioId);
            }

            if ($requerAprovacao) {
                $destinatario = User::find($destinatarioId);
                $message = "Solicitação de reunião enviada para {$destinatario->name}. Aguardando aprovação.";
                if ($foraHorarioComercial) {
                    $message .= ' (Fora do horário comercial)';
                }
            } else {
                $message = 'Reunião agendada com sucesso!';
            }
            
            return redirect()->route('licenciado.agenda')->with('success', $message);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao agendar reunião: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Erro ao agendar reunião: ' . $e->getMessage());
        }
    }

    /**
     * Aprovar solicitação de agenda
     */
    public function aprovar($id)
    {
        try {
            $agenda = Agenda::findOrFail($id);
            
            if ($agenda->destinatario_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Você não tem permissão para aprovar esta agenda.'], 403);
            }
            
            if ($agenda->status_aprovacao !== 'pendente') {
                return response()->json(['success' => false, 'message' => 'Esta agenda já foi processada.'], 400);
            }
            
            $agenda->aprovar(Auth::id());
            
            return response()->json(['success' => true, 'message' => 'Agenda aprovada com sucesso!']);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao aprovar agenda.'], 500);
        }
    }

    /**
     * Recusar solicitação de agenda
     */
    public function recusar(Request $request, $id)
    {
        try {
            $agenda = Agenda::findOrFail($id);
            
            if ($agenda->destinatario_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Você não tem permissão para recusar esta agenda.'], 403);
            }
            
            if ($agenda->status_aprovacao !== 'pendente') {
                return response()->json(['success' => false, 'message' => 'Esta agenda já foi processada.'], 400);
            }
            
            $motivo = $request->input('motivo');
            $agenda->recusar(Auth::id(), $motivo);
            
            return response()->json(['success' => true, 'message' => 'Agenda recusada.']);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao recusar agenda.'], 500);
        }
    }

    /**
     * Listar agendas pendentes de aprovação
     */
    public function pendentesAprovacao()
    {
        $agendas = Agenda::pendentesAprovacao(Auth::id())
                        ->with(['solicitante', 'destinatario'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        return view('licenciado.agenda.pendentes', compact('agendas'));
    }
}
