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
        try {
            // Verificar se o usuário está autenticado
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 401);
            }
            
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
                    \Log::info('Usando link Meet manual devido ao erro: ' . $e->getMessage());
                }
            }
            
            $agenda->save();

            // Enviar e-mails de confirmação
            if (!empty($participantes)) {
                try {
                    $emailService = new EmailService();
                    $organizador = Auth::user()->name ?? Auth::user()->email;
                    
                    // Debug: verificar se o link do Meet está sendo passado
                    \Log::info('Enviando e-mail com link do Meet: ' . $agenda->google_meet_link);
                    \Log::info('Participantes: ' . json_encode($participantes));
                    
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
        $licenciados = Licenciado::where('status', 'ativo')
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
            $licenciado = Licenciado::where('status', 'ativo')
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
     * Confirmar participação na reunião
     */
    public function confirmarParticipacao(Request $request, $id)
    {
        try {
            $status = $request->query('status');
            $email = $request->query('email');
            
            if (!$status || !$email) {
                return redirect()->route('dashboard.agenda')
                    ->with('error', 'Parâmetros inválidos para confirmação');
            }
            
            // Buscar a agenda
            $agenda = Agenda::findOrFail($id);
            
            // Criar ou atualizar confirmação
            $confirmacao = AgendaConfirmacao::updateOrCreate(
                [
                    'agenda_id' => $agenda->id,
                    'email_participante' => $email
                ],
                [
                    'status' => $status,
                    'confirmado_em' => $status === 'confirmado' ? now() : null
                ]
            );
            
            // Notificar o organizador sobre a confirmação
            $this->notificarOrganizador($agenda, $confirmacao);
            
            // Retornar a view de confirmação
            return view('agenda.confirmacao', compact('agenda', 'status', 'confirmacao'));
            
        } catch (\Exception $e) {
            \Log::error('Erro ao confirmar participação: ' . $e->getMessage());
            return redirect()->route('dashboard.agenda')
                ->with('error', 'Erro ao confirmar participação. Tente novamente.');
        }
    }

    /**
     * Notificar o organizador sobre a confirmação
     */
    private function notificarOrganizador($agenda, $confirmacao)
    {
        try {
            $organizador = $agenda->user;
            $statusText = match($confirmacao->status) {
                'confirmado' => 'confirmou presença',
                'pendente' => 'marcou como pendente',
                'recusado' => 'recusou participação',
                default => 'atualizou status'
            };
            
            \Log::info("Participante {$confirmacao->email_participante} {$statusText} na reunião: {$agenda->titulo}");
            
            // Aqui você pode implementar notificação por e-mail para o organizador
            // Por exemplo, enviar um e-mail informando sobre a confirmação
            
        } catch (\Exception $e) {
            \Log::error('Erro ao notificar organizador: ' . $e->getMessage());
        }
    }


}
