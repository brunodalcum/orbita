<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Services\GoogleCalendarIntegrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CalendarController extends Controller
{
    private $googleService;

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['publicEvents', 'publicFreebusy', 'publicBooking']);
        $this->middleware('throttle:60,1')->only(['publicBooking']);
    }

    /**
     * GET /api/calendar/events - Lista eventos para FullCalendar
     */
    public function events(Request $request)
    {
        try {
            $start = $request->get('start');
            $end = $request->get('end');
            
            // Validar datas
            if (!$start || !$end) {
                return response()->json(['error' => 'Parâmetros start e end são obrigatórios'], 400);
            }

            $startDate = Carbon::parse($start);
            $endDate = Carbon::parse($end);
            
            // Cache key baseado no usuário e período
            $cacheKey = 'calendar_events_' . Auth::id() . '_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d');
            
            $events = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($startDate, $endDate) {
                return $this->fetchEvents($startDate, $endDate);
            });

            return response()->json($events);

        } catch (\Exception $e) {
            Log::error('❌ Erro ao listar eventos da agenda', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request' => $request->all()
            ]);

            return response()->json(['error' => 'Erro ao carregar eventos'], 500);
        }
    }

    /**
     * POST /api/calendar/events - Cria novo evento
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'summary' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'start' => 'required|date',
                'end' => 'required|date|after:start',
                'attendees' => 'nullable|array',
                'attendees.*' => 'email',
                'generateMeet' => 'boolean',
                'timezone' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Dados inválidos',
                    'details' => $validator->errors()
                ], 422);
            }

            // Verificar conflitos antes de criar
            $conflicts = $this->checkConflicts($request->start, $request->end);
            if (!empty($conflicts)) {
                return response()->json([
                    'error' => 'Conflito de horário detectado',
                    'conflicts' => $conflicts
                ], 409);
            }

            // Criar evento
            $event = $this->createEvent($request->all());
            
            // Limpar cache
            $this->clearEventsCache();

            return response()->json([
                'success' => true,
                'message' => 'Evento criado com sucesso',
                'event' => $event
            ], 201);

        } catch (\Exception $e) {
            Log::error('❌ Erro ao criar evento', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json(['error' => 'Erro ao criar evento'], 500);
        }
    }

    /**
     * PATCH /api/calendar/events/{id} - Atualiza evento
     */
    public function update(Request $request, $id)
    {
        try {
            $agenda = Agenda::where('user_id', Auth::id())->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'summary' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'start' => 'sometimes|required|date',
                'end' => 'sometimes|required|date|after:start',
                'attendees' => 'nullable|array',
                'attendees.*' => 'email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Dados inválidos',
                    'details' => $validator->errors()
                ], 422);
            }

            // Verificar conflitos se mudou horário
            if ($request->has('start') || $request->has('end')) {
                $start = $request->get('start', $agenda->data_inicio);
                $end = $request->get('end', $agenda->data_fim);
                
                $conflicts = $this->checkConflicts($start, $end, $id);
                if (!empty($conflicts)) {
                    return response()->json([
                        'error' => 'Conflito de horário detectado',
                        'conflicts' => $conflicts
                    ], 409);
                }
            }

            // Atualizar evento
            $updatedEvent = $this->updateEvent($agenda, $request->all());
            
            // Limpar cache
            $this->clearEventsCache();

            return response()->json([
                'success' => true,
                'message' => 'Evento atualizado com sucesso',
                'event' => $updatedEvent
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erro ao atualizar evento', [
                'error' => $e->getMessage(),
                'agenda_id' => $id,
                'request' => $request->all()
            ]);

            return response()->json(['error' => 'Erro ao atualizar evento'], 500);
        }
    }

    /**
     * DELETE /api/calendar/events/{id} - Remove evento
     */
    public function destroy($id)
    {
        try {
            $agenda = Agenda::where('user_id', Auth::id())->findOrFail($id);

            // Cancelar no Google Calendar se existir
            if ($agenda->google_event_id) {
                $this->cancelGoogleEvent($agenda->google_event_id);
            }

            // Marcar como cancelado ao invés de deletar
            $agenda->update(['status' => 'cancelada']);

            // Limpar cache
            $this->clearEventsCache();

            return response()->json([
                'success' => true,
                'message' => 'Evento cancelado com sucesso'
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erro ao cancelar evento', [
                'error' => $e->getMessage(),
                'agenda_id' => $id
            ]);

            return response()->json(['error' => 'Erro ao cancelar evento'], 500);
        }
    }

    /**
     * POST /api/calendar/freebusy - Verifica disponibilidade
     */
    public function freebusy(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start' => 'required|date',
                'end' => 'required|date|after:start',
                'duration' => 'nullable|integer|min:15|max:480', // 15min a 8h
                'buffer' => 'nullable|integer|min:0|max:60' // buffer entre reuniões
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Dados inválidos',
                    'details' => $validator->errors()
                ], 422);
            }

            $slots = $this->getAvailableSlots(
                $request->start,
                $request->end,
                $request->get('duration', 30),
                $request->get('buffer', 10)
            );

            return response()->json([
                'success' => true,
                'available_slots' => $slots,
                'total_slots' => count($slots)
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erro ao verificar disponibilidade', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json(['error' => 'Erro ao verificar disponibilidade'], 500);
        }
    }

    /**
     * Buscar eventos do período
     */
    private function fetchEvents($startDate, $endDate)
    {
        $agendas = Agenda::where('user_id', Auth::id())
            ->where('status', '!=', 'cancelada')
            ->whereBetween('data_inicio', [$startDate, $endDate])
            ->get();

        return $agendas->map(function ($agenda) {
            return [
                'id' => $agenda->id,
                'title' => $agenda->titulo,
                'start' => $agenda->data_inicio->toISOString(),
                'end' => $agenda->data_fim->toISOString(),
                'description' => $agenda->descricao,
                'backgroundColor' => $this->getEventColor($agenda),
                'borderColor' => $this->getEventColor($agenda),
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'type' => $agenda->tipo_reuniao,
                    'status' => $agenda->status,
                    'meetLink' => $agenda->google_meet_link,
                    'googleEventId' => $agenda->google_event_id,
                    'participantes' => $agenda->participantes
                ]
            ];
        })->toArray();
    }

    /**
     * Criar novo evento
     */
    private function createEvent($data)
    {
        // Criar no banco local
        $agenda = new Agenda();
        $agenda->titulo = $data['summary'];
        $agenda->descricao = $data['description'] ?? '';
        $agenda->data_inicio = $data['start'];
        $agenda->data_fim = $data['end'];
        $agenda->tipo_reuniao = $data['generateMeet'] ? 'online' : 'presencial';
        $agenda->participantes = $data['attendees'] ?? [];
        $agenda->user_id = Auth::id();
        $agenda->status = 'agendada';

        // Integrar com Google Calendar se configurado
        if ($data['generateMeet'] ?? false) {
            try {
                $googleEvent = $this->createGoogleEvent($data);
                $agenda->google_event_id = $googleEvent['google_event_id'];
                $agenda->google_meet_link = $googleEvent['google_meet_link'];
                $agenda->google_event_url = $googleEvent['event_url'];
                $agenda->google_synced_at = now();
            } catch (\Exception $e) {
                Log::warning('⚠️ Falha na integração Google, usando fallback', [
                    'error' => $e->getMessage()
                ]);
                $agenda->google_meet_link = $this->generateFallbackMeetLink();
            }
        }

        $agenda->save();

        return [
            'id' => $agenda->id,
            'eventId' => $agenda->google_event_id,
            'htmlLink' => $agenda->google_event_url,
            'meetLink' => $agenda->google_meet_link,
            'title' => $agenda->titulo,
            'start' => $agenda->data_inicio->toISOString(),
            'end' => $agenda->data_fim->toISOString()
        ];
    }

    /**
     * Verificar conflitos de horário
     */
    private function checkConflicts($start, $end, $excludeId = null)
    {
        $query = Agenda::where('user_id', Auth::id())
            ->where('status', '!=', 'cancelada')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('data_inicio', [$start, $end])
                  ->orWhereBetween('data_fim', [$start, $end])
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->where('data_inicio', '<=', $start)
                         ->where('data_fim', '>=', $end);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->get(['id', 'titulo', 'data_inicio', 'data_fim'])->toArray();
    }

    /**
     * Obter slots disponíveis
     */
    private function getAvailableSlots($start, $end, $duration, $buffer)
    {
        // Implementar lógica de slots disponíveis
        // Por agora, retorna exemplo básico
        $slots = [];
        $current = Carbon::parse($start);
        $endTime = Carbon::parse($end);

        while ($current->copy()->addMinutes($duration)->lte($endTime)) {
            // Verificar se não há conflitos
            $slotEnd = $current->copy()->addMinutes($duration);
            $conflicts = $this->checkConflicts($current, $slotEnd);
            
            if (empty($conflicts)) {
                $slots[] = [
                    'start' => $current->toISOString(),
                    'end' => $slotEnd->toISOString(),
                    'duration' => $duration
                ];
            }

            $current->addMinutes($duration + $buffer);
        }

        return $slots;
    }

    /**
     * Criar evento no Google Calendar
     */
    private function createGoogleEvent($data)
    {
        try {
            $this->googleService = new GoogleCalendarIntegrationService();
            $token = session('google_token') ?? \Illuminate\Support\Facades\Cache::get("google_token_user_" . Auth::id());
            
            if ($token && $this->googleService->isConfigured()) {
                $this->googleService->setAccessToken($token);
                return $this->googleService->createEvent([
                    'titulo' => $data['summary'],
                    'descricao' => $data['description'] ?? '',
                    'data_inicio' => $data['start'],
                    'data_fim' => $data['end'],
                    'tipo_reuniao' => 'online',
                    'participantes' => $data['attendees'] ?? []
                ]);
            }
        } catch (\Exception $e) {
            Log::error('❌ Erro ao criar evento no Google: ' . $e->getMessage());
        }

        throw new \Exception('Google Calendar não disponível');
    }

    /**
     * Gerar link Meet manual (fallback)
     */
    private function generateFallbackMeetLink()
    {
        return 'https://meet.google.com/orbita-' . strtolower(substr(md5(uniqid()), 0, 8)) . '-' . strtolower(substr(md5(uniqid()), 0, 4));
    }

    /**
     * Obter cor do evento baseado no tipo/status
     */
    private function getEventColor($agenda)
    {
        switch ($agenda->tipo_reuniao) {
            case 'online':
                return '#28a745'; // Verde para online
            case 'presencial':
                return '#007bff'; // Azul para presencial
            case 'hibrida':
                return '#ffc107'; // Amarelo para híbrida
            default:
                return '#6c757d'; // Cinza padrão
        }
    }

    /**
     * Limpar cache de eventos
     */
    private function clearEventsCache()
    {
        $pattern = 'calendar_events_' . Auth::id() . '_*';
        // Implementar limpeza de cache baseada no padrão
        Cache::flush(); // Por agora, limpa tudo (otimizar depois)
    }

    /**
     * Atualizar evento existente
     */
    private function updateEvent($agenda, $data)
    {
        // Atualizar campos locais
        if (isset($data['summary'])) $agenda->titulo = $data['summary'];
        if (isset($data['description'])) $agenda->descricao = $data['description'];
        if (isset($data['start'])) $agenda->data_inicio = $data['start'];
        if (isset($data['end'])) $agenda->data_fim = $data['end'];
        if (isset($data['attendees'])) $agenda->participantes = $data['attendees'];

        // Atualizar no Google Calendar se existir
        if ($agenda->google_event_id) {
            try {
                $this->updateGoogleEvent($agenda->google_event_id, $data);
                $agenda->google_synced_at = now();
            } catch (\Exception $e) {
                Log::warning('⚠️ Falha ao atualizar no Google Calendar', [
                    'error' => $e->getMessage(),
                    'agenda_id' => $agenda->id
                ]);
            }
        }

        $agenda->save();

        return [
            'id' => $agenda->id,
            'title' => $agenda->titulo,
            'start' => $agenda->data_inicio->toISOString(),
            'end' => $agenda->data_fim->toISOString(),
            'meetLink' => $agenda->google_meet_link
        ];
    }

    /**
     * Atualizar evento no Google Calendar
     */
    private function updateGoogleEvent($googleEventId, $data)
    {
        try {
            $this->googleService = new GoogleCalendarIntegrationService();
            $token = session('google_token') ?? \Illuminate\Support\Facades\Cache::get("google_token_user_" . Auth::id());
            
            if ($token && $this->googleService->isConfigured()) {
                $this->googleService->setAccessToken($token);
                return $this->googleService->updateEvent($googleEventId, [
                    'titulo' => $data['summary'] ?? '',
                    'descricao' => $data['description'] ?? '',
                    'data_inicio' => $data['start'] ?? '',
                    'data_fim' => $data['end'] ?? '',
                    'participantes' => $data['attendees'] ?? []
                ]);
            }
        } catch (\Exception $e) {
            Log::error('❌ Erro ao atualizar evento no Google: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cancelar evento no Google Calendar
     */
    private function cancelGoogleEvent($googleEventId)
    {
        try {
            $this->googleService = new GoogleCalendarIntegrationService();
            $token = session('google_token') ?? \Illuminate\Support\Facades\Cache::get("google_token_user_" . Auth::id());
            
            if ($token && $this->googleService->isConfigured()) {
                $this->googleService->setAccessToken($token);
                $this->googleService->deleteEvent($googleEventId);
            }
        } catch (\Exception $e) {
            Log::warning('⚠️ Falha ao cancelar no Google Calendar', [
                'error' => $e->getMessage(),
                'google_event_id' => $googleEventId
            ]);
        }
    }
}
