<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\AgendaConfirmacao;
use App\Models\Licenciado;
use App\Services\GoogleCalendarService;
use App\Services\GoogleCalendarIntegrationService;
use App\Services\EmailService;
use App\Services\ReminderService;
use App\Services\AutomaticReminderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataAtual = request('data', now()->format('Y-m-d'));
        $isToday = $dataAtual === now()->format('Y-m-d');
        $hasDateFilter = request()->has('data');
        
        // Buscar agendas do usuário (como criador, solicitante ou destinatário)
        $query = Agenda::where(function($q) {
            $q->where('user_id', Auth::id())
              ->orWhere('solicitante_id', Auth::id())
              ->orWhere('destinatario_id', Auth::id());
        });
        
        // Sempre filtrar por data (padrão: hoje)
        $query->doDia($dataAtual);
        
        $agendas = $query->orderBy('data_inicio', 'asc')->get();

        // Contar total de agendas pendentes de aprovação para o botão
        $pendentesCount = Agenda::where('destinatario_id', Auth::id())
            ->where('status_aprovacao', 'pendente')
            ->count();

        return view('dashboard.agenda', compact('agendas', 'dataAtual', 'isToday', 'hasDateFilter', 'pendentesCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Buscar licenciados ativos para o select
        $licenciados = Licenciado::whereIn('status', ['ativo', 'aprovado'])
            ->whereNotNull('email')
            ->orderBy('razao_social')
            ->get();

        return view('dashboard.agenda-create', compact('licenciados'));
    }

    /**
     * Store a newly created resource in storage.
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
                'licenciado_id' => 'nullable|exists:licenciados,id',
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

            // Se um licenciado foi selecionado, encontrar o user_id correspondente pelo email
            if ($request->licenciado_id && !$destinatarioId) {
                $licenciado = \App\Models\Licenciado::find($request->licenciado_id);
                if ($licenciado && $licenciado->email) {
                    // Buscar usuário pelo email do licenciado
                    $user = \App\Models\User::where('email', $licenciado->email)->first();
                    if ($user) {
                        $destinatarioId = $user->id;
                        \Log::info('🎯 Licenciado encontrado e usuário correspondente localizado', [
                            'licenciado_id' => $licenciado->id,
                            'licenciado_email' => $licenciado->email,
                            'user_id' => $user->id,
                            'user_name' => $user->name
                        ]);
                    } else {
                        \Log::warning('⚠️ Usuário não encontrado para o licenciado', [
                            'licenciado_id' => $licenciado->id,
                            'licenciado_email' => $licenciado->email
                        ]);
                    }
                }
            }

            if ($destinatarioId && $destinatarioId != $solicitanteId) {
                // Verificar conflito de horário
                if (\App\Models\BusinessHours::hasTimeConflict($destinatarioId, $request->data_inicio, $request->data_fim)) {
                    return back()->withErrors(['data_inicio' => 'O destinatário já possui compromisso neste horário.'])
                                ->withInput()
                                ->with('error', 'Horário não disponível para o destinatário.');
                }

                // Verificar se está dentro do horário comercial
                $foraHorarioComercial = !\App\Models\BusinessHours::isWithinBusinessHours($destinatarioId, $request->data_inicio) ||
                                       !\App\Models\BusinessHours::isWithinBusinessHours($destinatarioId, $request->data_fim);

                // Definir se requer aprovação
                $requerAprovacao = $foraHorarioComercial || true; // Sempre requer aprovação por enquanto
                $statusAprovacao = $requerAprovacao ? 'pendente' : 'automatica';
            }

            // Processar participantes
            $participantes = [];
            if ($request->participantes) {
                // Dividir por vírgula ou quebra de linha
                $emailsString = $request->participantes;
                $emails = preg_split('/[,\n\r]+/', $emailsString);
                
                // Limpar e filtrar emails válidos
                foreach ($emails as $email) {
                    $email = trim($email);
                    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $participantes[] = $email;
                    }
                }
            }
            
            // Adicionar automaticamente o email do licenciado se selecionado
            if ($request->licenciado_id) {
                $licenciado = \App\Models\Licenciado::find($request->licenciado_id);
                if ($licenciado && $licenciado->email && filter_var($licenciado->email, FILTER_VALIDATE_EMAIL)) {
                    // Verificar se o email do licenciado já não está na lista
                    if (!in_array($licenciado->email, $participantes)) {
                        $participantes[] = $licenciado->email;
                        \Log::info('📧 Email do licenciado adicionado automaticamente', [
                            'licenciado_id' => $licenciado->id,
                            'licenciado_email' => $licenciado->email,
                            'participantes_total' => count($participantes)
                        ]);
                    }
                }
            }
            
            // Adicionar automaticamente o email do criador da agenda
            $creatorEmail = Auth::user()->email;
            if ($creatorEmail && filter_var($creatorEmail, FILTER_VALIDATE_EMAIL)) {
                if (!in_array($creatorEmail, $participantes)) {
                    $participantes[] = $creatorEmail;
                    \Log::info('📧 Email do criador adicionado automaticamente', [
                        'creator_email' => $creatorEmail,
                        'participantes_total' => count($participantes)
                    ]);
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
            $agenda->licenciado_id = $request->licenciado_id;
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
            
            // Integração com Google Calendar e Meet
            if ($request->tipo_reuniao === 'online' || $request->tipo_reuniao === 'hibrida') {
                try {
                    $googleService = new GoogleCalendarIntegrationService();
                    
                    // Verificar se há token de acesso do usuário
                    $token = session('google_token') ?? \Illuminate\Support\Facades\Cache::get("google_token_user_" . Auth::id());
                    
                    if ($token && $googleService->isConfigured()) {
                        $googleService->setAccessToken($token);
                        
                        // Criar evento no Google Calendar
                        $eventData = [
                            'titulo' => $request->titulo,
                            'descricao' => $request->descricao,
                            'data_inicio' => $request->data_inicio,
                            'data_fim' => $request->data_fim,
                            'tipo_reuniao' => $request->tipo_reuniao,
                            'participantes' => array_column($participantes, 'email')
                        ];
                        
                        $googleEvent = $googleService->createEvent($eventData);
                        
                        // Salvar informações do Google
                        $agenda->google_event_id = $googleEvent['google_event_id'];
                        $agenda->google_meet_link = $googleEvent['google_meet_link'];
                        $agenda->google_event_url = $googleEvent['event_url'];
                        $agenda->google_synced_at = now();
                        
                        \Log::info('✅ Evento criado no Google Calendar', [
                            'agenda_id' => $agenda->id ?? 'novo',
                            'google_event_id' => $googleEvent['google_event_id'],
                            'meet_link' => $googleEvent['google_meet_link']
                        ]);
                        
                    } else {
                        // Fallback: gerar link do Meet manualmente
                        $agenda->google_meet_link = $googleService->generateMeetLink();
                        \Log::warning('⚠️ Google Calendar não autenticado, usando Meet link manual', [
                            'meet_link' => $agenda->google_meet_link
                        ]);
                    }
                    
                } catch (\Exception $e) {
                    \Log::error('❌ Erro ao criar evento no Google Calendar: ' . $e->getMessage());
                    
                    // Fallback: gerar link do Meet manualmente
                    $googleService = new GoogleCalendarIntegrationService();
                    $agenda->google_meet_link = $googleService->generateMeetLink();
                    \Log::info('🔄 Usando Meet link manual devido ao erro: ' . $agenda->google_meet_link);
                }
            }
            
            $agenda->save();

            // Criar lembretes automáticos (1 dia antes, 8h da manhã, 1 hora antes)
            try {
                $automaticReminderService = new AutomaticReminderService();
                
                if ($automaticReminderService->shouldCreateReminders($agenda)) {
                    $reminders = $automaticReminderService->createAutomaticReminders($agenda);
                    
                    \Log::info('✅ Lembretes automáticos criados para agenda', [
                        'agenda_id' => $agenda->id,
                        'lembretes_criados' => count($reminders),
                        'tipos' => ['1 dia antes', '8h da manhã', '1 hora antes'],
                    ]);
                } else {
                    \Log::info('⚠️ Lembretes automáticos não criados (evento muito próximo ou no passado)');
                }
            } catch (\Exception $e) {
                \Log::error('❌ Erro ao criar lembretes: ' . $e->getMessage());
                // Não falhar a criação da agenda por causa dos lembretes
            }

            // Criar notificação se há destinatário e requer aprovação
            if ($destinatarioId && $requerAprovacao) {
                \App\Models\AgendaNotification::createSolicitacaoNotification($agenda, $destinatarioId);
            }

            // Enviar e-mails de confirmação
            if (!empty($participantes)) {
                try {
                    $emailService = new EmailService();
                    $organizador = Auth::user()->name ?? Auth::user()->email;
                    
                    \Log::info('Enviando emails de confirmação', [
                        'agenda_id' => $agenda->id,
                        'participantes' => $participantes,
                        'meet_link' => $agenda->google_meet_link
                    ]);
                    
                    $emailService->sendMeetingConfirmation(
                        $participantes,
                        $request->titulo,
                        $request->descricao,
                        $request->data_inicio,
                        $request->data_fim,
                        $agenda->google_meet_link,
                        $organizador,
                        $agenda->id
                    );
                    
                    \Log::info('Emails de confirmação enviados com sucesso');
                    
                } catch (\Exception $e) {
                    \Log::error('Erro ao enviar e-mails: ' . $e->getMessage());
                    // Continua mesmo se o e-mail falhar
                }
            }

            if ($requerAprovacao) {
                $destinatario = \App\Models\User::find($destinatarioId);
                $message = "Solicitação de reunião enviada para {$destinatario->name}. Aguardando aprovação.";
                if ($foraHorarioComercial) {
                    $message .= ' (Fora do horário comercial)';
                }
            } else {
                $message = 'Reunião agendada com sucesso!';
                if (!empty($participantes)) {
                    $message .= ' E-mails enviados aos participantes.';
                }
            }
            
            
            return redirect()->route('dashboard.agenda')->with('success', $message);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao agendar reunião: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Erro ao agendar reunião: ' . $e->getMessage());
        }
    }

    /**
     * Aprovar solicitação de agenda
     */
    public function aprovar(Request $request, $id)
    {
        try {
            $agenda = Agenda::findOrFail($id);
            
            // Verificar se o usuário pode aprovar esta agenda
            if ($agenda->destinatario_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Você não tem permissão para aprovar esta agenda.'], 403);
            }
            
            if ($agenda->status_aprovacao !== 'pendente') {
                return response()->json(['success' => false, 'message' => 'Esta agenda já foi processada.'], 400);
            }
            
            // Verificar novamente se não há conflito de horário
            if (\App\Models\BusinessHours::hasTimeConflict(Auth::id(), $agenda->data_inicio, $agenda->data_fim, $agenda->id)) {
                return response()->json(['success' => false, 'message' => 'Você já possui compromisso neste horário.'], 400);
            }
            
            $agenda->aprovar(Auth::id());
            
            return response()->json(['success' => true, 'message' => 'Agenda aprovada com sucesso!']);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao aprovar agenda: ' . $e->getMessage());
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
            
            // Verificar se o usuário pode recusar esta agenda
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
            \Log::error('Erro ao recusar agenda: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao recusar agenda.'], 500);
        }
    }
    
    /**
     * Listar agendas pendentes de aprovação (view)
     */
    public function pendentesAprovacao()
    {
        $agendas = Agenda::pendentesAprovacao(Auth::id())
                        ->with(['solicitante', 'destinatario'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        return view('dashboard.agenda-pendentes-aprovacao', compact('agendas'));
    }
    
    /**
     * API para listar agendas pendentes de aprovação
     */
    public function apiPendentesAprovacao()
    {
        $agendas = Agenda::pendentesAprovacao(Auth::id())
                        ->with(['solicitante', 'destinatario'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        return response()->json(['agendas' => $agendas]);
    }

    /**
     * Confirmar participação em reunião (rota pública)
     */
    public function confirmarParticipacao(Request $request, $id)
    {
        
        try {
            $agenda = Agenda::findOrFail($id);
            $email = $request->get('email');
            $status = $request->get('status', 'confirmado');
            
            
            // Validar status
            if (!in_array($status, ['confirmado', 'recusado', 'pendente'])) {
                $status = 'confirmado';
            }
            
            // Validar email
            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return redirect()->route('agenda.confirmacao.sucesso', [
                    'status' => 'error',
                    'message' => 'Email inválido'
                ]);
            }
            
            // Atualizar ou criar confirmação
            \App\Models\AgendaConfirmacao::updateOrCreate(
                [
                    'agenda_id' => $agenda->id,
                    'email_participante' => $email
                ],
                [
                    'status' => $status,
                    'confirmado_em' => ($status === 'confirmado') ? now() : null
                ]
            );
            
            
            // Redirecionar para página específica baseada no status
            $viewData = [
                'titulo' => $agenda->titulo,
                'agenda_id' => $agenda->id,
                'email' => $email,
                'status' => $status
            ];
            
            switch($status) {
                case 'confirmado':
                    return view('agenda.confirmacao', $viewData);
                case 'recusado':
                    return view('agenda.rejeicao', $viewData);
                case 'pendente':
                    return view('agenda.pendente', $viewData);
                default:
                    return view('agenda.confirmacao', $viewData);
            }
            
        } catch (\Exception $e) {
            \Log::error('🚨 [PRODUÇÃO] Erro ao confirmar participação', [
                'erro' => $e->getMessage(),
                'linha' => $e->getLine(),
                'arquivo' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
                'agenda_id' => $id,
                'email' => $request->get('email'),
                'status' => $request->get('status')
            ]);
            
            // Retornar página de erro ao invés de redirect
            return view('agenda.confirmacao-sucesso', [
                'status' => 'error',
                'message' => 'Erro ao processar confirmação: ' . $e->getMessage(),
                'titulo' => '',
                'action' => 'error'
            ]);
        }
    }
    
    /**
     * Página de sucesso da confirmação
     */
    public function confirmacaoSucesso(Request $request)
    {
        $status = $request->get('status', 'success');
        $action = $request->get('action', 'confirmado');
        $titulo = $request->get('titulo', 'Reunião');
        $message = $request->get('message', '');
        
        return view('agenda.confirmacao-sucesso', compact('status', 'action', 'titulo', 'message'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Super Admin pode ver qualquer agenda, outros usuários só suas próprias
            if (Auth::user()->role_id == 1) { // Super Admin
                $agenda = Agenda::findOrFail($id);
            } else {
                $agenda = Agenda::where('user_id', Auth::id())->findOrFail($id);
            }
            
            // Buscar licenciado se existir
            $licenciado = null;
            if ($agenda->licenciado_id) {
                $licenciado = \App\Models\Licenciado::find($agenda->licenciado_id);
            }
            
            // Adicionar informações de confirmação
            $agenda->confirmacoes = [
                'confirmados' => $agenda->confirmacoesConfirmadas()->count(),
                'pendentes' => $agenda->confirmacoesPendentes()->count(),
                'recusados' => $agenda->confirmacoesRecusadas()->count()
            ];
            
            // Adicionar detalhes das confirmações
            $agenda->detalhes_confirmacoes = $agenda->confirmacoes()
                ->with('agenda')
                ->get()
                ->map(function ($confirmacao) {
                    return [
                        'email' => $confirmacao->email_participante,
                        'status' => $confirmacao->status,
                        'confirmado_em' => $confirmacao->confirmado_em,
                        'observacao' => $confirmacao->observacao
                    ];
                });
            
            return response()->json([
                'success' => true,
                'agenda' => $agenda,
                'licenciado' => $licenciado
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar reunião: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            // Super Admin pode editar qualquer agenda, outros usuários só suas próprias
            if (Auth::user()->role_id == 1) { // Super Admin
                $agenda = Agenda::findOrFail($id);
            } else {
                $agenda = Agenda::where('user_id', Auth::id())->findOrFail($id);
            }
            
            return response()->json([
                'success' => true,
                'agenda' => $agenda
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar dados da reunião: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after:data_inicio',
            'tipo_reuniao' => 'required|in:presencial,online,hibrida',
            'participantes' => 'nullable|string|max:2000',
            'meet_link' => 'nullable|url',
            'licenciado_id' => 'nullable|exists:licenciados,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . implode(', ', $validator->errors()->all())
            ], 422);
        }

        try {
            // Super Admin pode atualizar qualquer agenda, outros usuários só suas próprias
            if (Auth::user()->role_id == 1) { // Super Admin
                $agenda = Agenda::findOrFail($id);
            } else {
                $agenda = Agenda::where('user_id', Auth::id())->findOrFail($id);
            }
            
            // Processar participantes
            $participantes = [];
            if ($request->participantes) {
                // Dividir por vírgula ou quebra de linha
                $emailsString = $request->participantes;
                $emails = preg_split('/[,\n\r]+/', $emailsString);
                
                // Limpar e filtrar emails válidos
                foreach ($emails as $email) {
                    $email = trim($email);
                    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $participantes[] = $email;
                    }
                }
            }
            
            // Adicionar automaticamente o email do licenciado se selecionado
            if ($request->licenciado_id) {
                $licenciado = \App\Models\Licenciado::find($request->licenciado_id);
                if ($licenciado && $licenciado->email && filter_var($licenciado->email, FILTER_VALIDATE_EMAIL)) {
                    // Verificar se o email do licenciado já não está na lista
                    if (!in_array($licenciado->email, $participantes)) {
                        $participantes[] = $licenciado->email;
                    }
                }
            }
            
            // Atualizar dados básicos
            $agenda->titulo = $request->titulo;
            $agenda->descricao = $request->descricao;
            $agenda->data_inicio = $request->data_inicio;
            $agenda->data_fim = $request->data_fim;
            $agenda->tipo_reuniao = $request->tipo_reuniao;
            $agenda->participantes = $participantes;
            $agenda->meet_link = $request->meet_link;
            $agenda->licenciado_id = $request->licenciado_id;
            
            // Atualizar no Google Calendar se necessário
            if (($request->tipo_reuniao === 'online' || $request->tipo_reuniao === 'hibrida') && $agenda->google_event_id) {
                try {
                    $googleService = new GoogleCalendarService();
                    $googleResult = $googleService->updateEvent(
                        $agenda->google_event_id,
                        $request->titulo,
                        $request->descricao,
                        $request->data_inicio,
                        $request->data_fim,
                        $participantes
                    );
                    
                    if ($googleResult['success']) {
                        $agenda->google_meet_link = $googleResult['meet_link'];
                    }
                } catch (\Exception $e) {
                    \Log::error('Erro ao atualizar evento no Google Calendar: ' . $e->getMessage());
                    // Fallback: gerar link do Meet manualmente se o Google falhar
                    $agenda->google_meet_link = 'https://meet.google.com/' . strtolower(substr(md5(uniqid()), 0, 8)) . '-' . strtolower(substr(md5(uniqid()), 0, 4)) . '-' . strtolower(substr(md5(uniqid()), 0, 4));
                    \Log::info('Usando link Meet manual devido ao erro de atualização: ' . $e->getMessage());
                }
            } elseif (($request->tipo_reuniao === 'online' || $request->tipo_reuniao === 'hibrida') && !$agenda->google_event_id) {
                // Criar novo evento no Google Calendar
                try {
                    $googleService = new GoogleCalendarService();
                    $googleResult = $googleService->createEvent(
                        $request->titulo,
                        $request->descricao,
                        $request->data_inicio,
                        $request->data_fim,
                        $participantes
                    );
                    
                    if ($googleResult['success']) {
                        $agenda->google_event_id = $googleResult['event_id'];
                        $agenda->google_meet_link = $googleResult['meet_link'];
                    }
                } catch (\Exception $e) {
                    \Log::error('Erro ao criar evento no Google Calendar: ' . $e->getMessage());
                    // Fallback: gerar link do Meet manualmente se o Google falhar
                    $agenda->google_meet_link = 'https://meet.google.com/' . strtolower(substr(md5(uniqid()), 0, 8)) . '-' . strtolower(substr(md5(uniqid()), 0, 4)) . '-' . strtolower(substr(md5(uniqid()), 0, 4));
                    \Log::info('Usando link Meet manual devido ao erro de criação: ' . $e->getMessage());
                }
            }
            
            $agenda->save();

            // Reagendar lembretes automáticos
            try {
                $reminderService = new ReminderService();
                $reminderStats = $reminderService->rescheduleForEvent($agenda);
                
                \Log::info('✅ Lembretes reagendados para agenda', [
                    'agenda_id' => $agenda->id,
                    'lembretes_cancelados' => $reminderStats['canceled'],
                    'lembretes_criados' => $reminderStats['created']['created'],
                ]);
            } catch (\Exception $e) {
                \Log::error('❌ Erro ao reagendar lembretes: ' . $e->getMessage());
                // Não falhar a atualização da agenda por causa dos lembretes
            }

            // Enviar e-mails de atualização e criar confirmações
            if (!empty($participantes)) {
                try {
                    $emailService = new EmailService();
                    $organizador = Auth::user()->name ?? Auth::user()->email;
                    
                    // Criar confirmações para novos participantes
                    $emailService->criarConfirmacoesPendentes($agenda->id, $participantes);
                    
                    $emailService->sendMeetingUpdate(
                        $participantes,
                        $request->titulo,
                        $request->descricao,
                        $request->data_inicio,
                        $request->data_fim,
                        $agenda->meet_link ?: $agenda->google_meet_link,
                        $organizador,
                        $agenda->id
                    );
                } catch (\Exception $e) {
                    \Log::error('Erro ao enviar e-mails de atualização: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Reunião atualizada com sucesso!' . (!empty($participantes) ? ' E-mails enviados aos participantes.' : ''),
                'agenda' => $agenda
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar reunião: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Super Admin pode excluir qualquer agenda, outros usuários só suas próprias
            if (Auth::user()->role_id == 1) { // Super Admin
                $agenda = Agenda::findOrFail($id);
            } else {
                $agenda = Agenda::where('user_id', Auth::id())->findOrFail($id);
            }
            
            // Log para debug
            \Log::info('🗑️ Iniciando exclusão de agenda', [
                'agenda_id' => $agenda->id,
                'titulo' => $agenda->titulo,
                'user_id' => Auth::id(),
                'user_role' => Auth::user()->role_id,
                'is_super_admin' => Auth::user()->role_id == 1,
            ]);
            
            // Excluir do Google Calendar se existir
            if ($agenda->google_event_id) {
                try {
                    $googleService = new GoogleCalendarService();
                    $googleService->deleteEvent($agenda->google_event_id);
                    \Log::info('✅ Evento excluído do Google Calendar', ['event_id' => $agenda->google_event_id]);
                } catch (\Exception $e) {
                    \Log::error('❌ Erro ao excluir evento do Google Calendar: ' . $e->getMessage());
                }
            }
            
            // Enviar e-mails de cancelamento
            if (!empty($agenda->participantes)) {
                try {
                    $emailService = new EmailService();
                    $organizador = Auth::user()->name ?? Auth::user()->email;
                    $emailService->sendMeetingCancellation(
                        $agenda->participantes,
                        $agenda->titulo,
                        $organizador
                    );
                    \Log::info('✅ E-mails de cancelamento enviados');
                } catch (\Exception $e) {
                    \Log::error('❌ Erro ao enviar e-mails de cancelamento: ' . $e->getMessage());
                }
            }
            
            // Remover lembretes automáticos pendentes
            try {
                $automaticReminderService = new AutomaticReminderService();
                $removedCount = $automaticReminderService->removeAutomaticReminders($agenda);
                
                \Log::info('✅ Lembretes automáticos removidos para agenda excluída', [
                    'agenda_id' => $agenda->id,
                    'lembretes_removidos' => $removedCount,
                ]);
            } catch (\Exception $e) {
                \Log::error('❌ Erro ao remover lembretes automáticos: ' . $e->getMessage());
                // Não falhar a exclusão da agenda por causa dos lembretes
            }
            
            // Excluir a agenda
            $agenda->delete();
            
            \Log::info('✅ Agenda excluída com sucesso', ['agenda_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Reunião excluída com sucesso!' . (!empty($agenda->participantes) ? ' E-mails de cancelamento enviados aos participantes.' : '')
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('❌ Agenda não encontrada para exclusão', [
                'agenda_id' => $id,
                'user_id' => Auth::id(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Reunião não encontrada ou você não tem permissão para excluí-la.'
            ], 404);
            
        } catch (\Exception $e) {
            \Log::error('❌ ERRO CRÍTICO ao excluir agenda', [
                'agenda_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir reunião: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the status of the specified meeting.
     */
    public function toggleStatus(Request $request, string $id)
    {
        try {
            // Super Admin pode alterar status de qualquer agenda, outros usuários só suas próprias
            if (Auth::user()->role_id == 1) { // Super Admin
                $agenda = Agenda::findOrFail($id);
            } else {
                $agenda = Agenda::where('user_id', Auth::id())->findOrFail($id);
            }
            $newStatus = $request->status;
            
            $agenda->update(['status' => $newStatus]);

            $actionText = match($newStatus) {
                'agendada' => 'agendada',
                'em_andamento' => 'iniciada',
                'concluida' => 'concluída',
                'cancelada' => 'cancelada',
                default => 'atualizada'
            };

            return response()->json([
                'success' => true,
                'message' => "Reunião {$actionText} com sucesso!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status da reunião: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get agenda for a specific date
     */
    public function getAgendaPorData(Request $request, $data = null)
    {
        // Pode receber a data como parâmetro da URL ou como query parameter
        $data = $data ?? $request->input('data', now()->format('Y-m-d'));
        
        $agendas = Agenda::where('user_id', Auth::id())
            ->doDia($data)
            ->orderBy('data_inicio')
            ->get();

        // Adicionar informações de confirmação para cada agenda
        $agendas->each(function ($agenda) {
            $agenda->confirmacoes = [
                'confirmados' => $agenda->confirmacoesConfirmadas()->count(),
                'pendentes' => $agenda->confirmacoesPendentes()->count(),
                'recusados' => $agenda->confirmacoesRecusadas()->count()
            ];
        });

        return response()->json([
            'success' => true,
            'agendas' => $agendas
        ]);
    }

    /**
     * Get licenciados for select dropdown
     */
    public function getLicenciados()
    {
        $licenciados = Licenciado::whereIn('status', ['ativo', 'aprovado'])
            ->whereNotNull('email')
            ->orderBy('razao_social')
            ->get(['id', 'razao_social', 'nome_fantasia', 'email']);

        return response()->json([
            'success' => true,
            'licenciados' => $licenciados
        ]);
    }

    /**
     * Get licenciado details by ID
     */
    public function getLicenciadoDetails($id)
    {
        try {
            $licenciado = Licenciado::whereIn('status', ['ativo', 'aprovado'])
                ->whereNotNull('email')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'licenciado' => [
                    'id' => $licenciado->id,
                    'razao_social' => $licenciado->razao_social,
                    'nome_fantasia' => $licenciado->nome_fantasia,
                    'email' => $licenciado->email
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Licenciado não encontrado'
            ], 404);
        }
    }

    /**
     * Exibe a view do calendário mensal
     */
    public function calendar(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $date = Carbon::createFromFormat('Y-m', $month);
        
        // Buscar todos os eventos do mês para o usuário logado (como criador, solicitante ou destinatário)
        $agendas = Agenda::where(function($q) {
                $q->where('user_id', Auth::id())
                  ->orWhere('solicitante_id', Auth::id())
                  ->orWhere('destinatario_id', Auth::id());
            })
            ->whereYear('data_inicio', $date->year)
            ->whereMonth('data_inicio', $date->month)
            ->orderBy('data_inicio')
            ->get();

        return view('dashboard.agenda-calendar', compact('agendas', 'month', 'date'));
    }

}
