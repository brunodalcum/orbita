<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\AgendaConfirmacao;
use App\Models\Licenciado;
use App\Services\GoogleCalendarService;
use App\Services\EmailService;
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
        $agendas = Agenda::where('user_id', Auth::id())
            ->doDia($dataAtual)
            ->orderBy('data_inicio')
            ->get();

        return view('dashboard.agenda', compact('agendas', 'dataAtual'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log de debug para verificar dados recebidos
        \Log::info('🧪 DEBUG - Agenda Store chamado', [
            'request_data' => $request->all(),
            'user_id' => Auth::id(),
            'user_authenticated' => Auth::check()
        ]);
        
        try {
            
            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255',
                'descricao' => 'nullable|string',
                'data_inicio' => 'required|date',
                'data_fim' => 'required|date|after:data_inicio',
                'tipo_reuniao' => 'required|in:presencial,online,hibrida',
                'participantes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação: ' . implode(', ', $validator->errors()->all()),
                    'errors' => $validator->errors()
                ], 422);
            }

            // Processar participantes
            $participantes = [];
            if ($request->participantes) {
                $participantes = json_decode($request->participantes, true) ?? [];
            }
            
            // Criar a agenda no banco de dados
            $agenda = new Agenda();
            $agenda->titulo = $request->titulo;
            $agenda->descricao = $request->descricao;
            $agenda->data_inicio = $request->data_inicio;
            $agenda->data_fim = $request->data_fim;
            $agenda->tipo_reuniao = $request->tipo_reuniao;
            $agenda->participantes = $participantes;
            $agenda->user_id = Auth::id();
            $agenda->status = 'agendada';
            
            // Tentar criar evento no Google Calendar se for reunião online ou híbrida
            if ($request->tipo_reuniao === 'online' || $request->tipo_reuniao === 'hibrida') {
                try {
                    // Temporariamente desabilitado para debug
                    \Log::info('Google Calendar temporariamente desabilitado para debug');
                    // Fallback: gerar link do Meet manualmente
                    $agenda->google_meet_link = 'https://meet.google.com/' . strtolower(substr(md5(uniqid()), 0, 8)) . '-' . strtolower(substr(md5(uniqid()), 0, 4)) . '-' . strtolower(substr(md5(uniqid()), 0, 4));
                    
                } catch (\Exception $e) {
                    \Log::error('Erro ao criar evento no Google Calendar: ' . $e->getMessage());
                    // Fallback: gerar link do Meet manualmente se o Google falhar
                    $agenda->google_meet_link = 'https://meet.google.com/' . strtolower(substr(md5(uniqid()), 0, 8)) . '-' . strtolower(substr(md5(uniqid()), 0, 4)) . '-' . strtolower(substr(md5(uniqid()), 0, 4));
                    \Log::info('Usando link Meet manual devido ao erro: ' . $e->getMessage());
                }
            }
            
            $agenda->save();

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

            return response()->json([
                'success' => true,
                'message' => 'Reunião agendada com sucesso!' . (!empty($participantes) ? ' E-mails enviados aos participantes.' : ''),
                'agenda' => $agenda
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao agendar reunião: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirmar participação em reunião (rota pública)
     */
    public function confirmarParticipacao(Request $request, $id)
    {
        // Log de debug para verificar se o método está sendo chamado
        \Log::info('🎯 Método confirmarParticipacao chamado', [
            'id' => $id,
            'request_data' => $request->all(),
            'url' => $request->fullUrl(),
            'method' => $request->method()
        ]);
        
        try {
            $agenda = Agenda::findOrFail($id);
            $email = $request->get('email');
            $status = $request->get('status', 'confirmado');
            
            \Log::info('🔍 Dados processados', [
                'agenda_titulo' => $agenda->titulo,
                'email' => $email,
                'status' => $status
            ]);
            
            // Validar status
            if (!in_array($status, ['confirmado', 'recusado', 'pendente'])) {
                $status = 'confirmado';
            }
            
            // Validar email
            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                \Log::warning('Email inválido fornecido: ' . $email);
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
            
            // Log da confirmação
            \Log::info('Participação confirmada', [
                'agenda_id' => $agenda->id,
                'email' => $email,
                'status' => $status,
                'ip' => $request->ip()
            ]);
            
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
            $agenda = Agenda::where('user_id', Auth::id())->findOrFail($id);
            
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
                'agenda' => $agenda
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
            $agenda = Agenda::where('user_id', Auth::id())->findOrFail($id);
            
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
            'participantes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . implode(', ', $validator->errors()->all())
            ], 422);
        }

        try {
            $agenda = Agenda::where('user_id', Auth::id())->findOrFail($id);
            
            // Processar participantes
            $participantes = [];
            if ($request->participantes) {
                $participantes = json_decode($request->participantes, true) ?? [];
            }
            
            // Atualizar dados básicos
            $agenda->titulo = $request->titulo;
            $agenda->descricao = $request->descricao;
            $agenda->data_inicio = $request->data_inicio;
            $agenda->data_fim = $request->data_fim;
            $agenda->tipo_reuniao = $request->tipo_reuniao;
            $agenda->participantes = $participantes;
            
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

            // Enviar e-mails de atualização
            if (!empty($participantes)) {
                try {
                    $emailService = new EmailService();
                    $organizador = Auth::user()->name ?? Auth::user()->email;
                    $emailService->sendMeetingUpdate(
                        $participantes,
                        $request->titulo,
                        $request->descricao,
                        $request->data_inicio,
                        $request->data_fim,
                        $agenda->google_meet_link,
                        $organizador
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
            $agenda = Agenda::where('user_id', Auth::id())->findOrFail($id);
            
            // Excluir do Google Calendar se existir
            if ($agenda->google_event_id) {
                try {
                    $googleService = new GoogleCalendarService();
                    $googleService->deleteEvent($agenda->google_event_id);
                } catch (\Exception $e) {
                    \Log::error('Erro ao excluir evento do Google Calendar: ' . $e->getMessage());
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
                } catch (\Exception $e) {
                    \Log::error('Erro ao enviar e-mails de cancelamento: ' . $e->getMessage());
                }
            }
            
            $agenda->delete();

            return response()->json([
                'success' => true,
                'message' => 'Reunião excluída com sucesso!' . (!empty($agenda->participantes) ? ' E-mails de cancelamento enviados aos participantes.' : '')
            ]);
        } catch (\Exception $e) {
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
            $agenda = Agenda::where('user_id', Auth::id())->findOrFail($id);
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

}
