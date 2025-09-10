<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Google\Service\Calendar\ConferenceData;
use Google\Service\Calendar\CreateConferenceRequest;
use Google\Service\Calendar\ConferenceSolutionKey;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class GoogleCalendarIntegrationService
{
    private $client;
    private $calendar;
    private $calendarId;

    public function __construct()
    {
        $this->initializeClient();
    }

    /**
     * Inicializar cliente Google
     */
    private function initializeClient()
    {
        try {
            // Verificar se as configurações básicas existem
            $clientId = config('google.client_id');
            $clientSecret = config('google.client_secret');
            
            if (empty($clientId) || empty($clientSecret)) {
                throw new \Exception('Credenciais Google não configuradas');
            }

            $this->client = new Client();
            $this->client->setClientId($clientId);
            $this->client->setClientSecret($clientSecret);
            $this->client->setRedirectUri(config('google.redirect_uri'));
            $this->client->setScopes(config('google.scopes'));
            $this->client->setAccessType('offline');
            $this->client->setPrompt('consent');

            // Configurar Service Account se habilitado
            if (config('google.service_account.enabled')) {
                $this->setupServiceAccount();
            }

            $this->calendar = new Calendar($this->client);
            $this->calendarId = config('google.calendar.primary_calendar_id');

            Log::info('🔗 Google Calendar Service inicializado com sucesso');
        } catch (\Exception $e) {
            Log::error('❌ Erro ao inicializar Google Calendar Service: ' . $e->getMessage());
            // Não fazer throw para não quebrar a aplicação
            $this->client = null;
            $this->calendar = null;
        }
    }

    /**
     * Configurar Service Account para acesso sem autenticação
     */
    private function setupServiceAccount()
    {
        $credentialsPath = config('google.service_account.credentials_path');
        
        if (file_exists($credentialsPath)) {
            $this->client->setAuthConfig($credentialsPath);
            
            // Impersonar usuário se configurado
            if (config('google.service_account.subject_email')) {
                $this->client->setSubject(config('google.service_account.subject_email'));
            }
            
            Log::info('🔐 Service Account configurado: ' . $credentialsPath);
        } else {
            Log::warning('⚠️ Arquivo de credenciais Service Account não encontrado: ' . $credentialsPath);
        }
    }

    /**
     * Definir token de acesso
     */
    public function setAccessToken($token)
    {
        $this->client->setAccessToken($token);
        
        // Refresh token se necessário
        if ($this->client->isAccessTokenExpired()) {
            if ($this->client->getRefreshToken()) {
                $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                Log::info('🔄 Token Google atualizado automaticamente');
            } else {
                Log::warning('⚠️ Token Google expirado e sem refresh token');
                return false;
            }
        }
        
        return true;
    }

    /**
     * Obter URL de autorização do Google
     */
    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    /**
     * Processar código de autorização e obter token
     */
    public function handleAuthCallback($code)
    {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);
            
            if (isset($token['error'])) {
                throw new \Exception('Erro na autenticação: ' . $token['error']);
            }

            Log::info('✅ Token Google obtido com sucesso');
            return $token;
        } catch (\Exception $e) {
            Log::error('❌ Erro ao processar callback Google: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Criar evento no Google Calendar com Google Meet
     */
    public function createEvent($eventData)
    {
        try {
            $event = new Event();
            
            // Dados básicos do evento
            $event->setSummary($eventData['titulo']);
            $event->setDescription($eventData['descricao'] ?? '');
            
            // Data e hora de início
            $start = new EventDateTime();
            $start->setDateTime($this->formatDateTime($eventData['data_inicio']));
            $start->setTimeZone(config('google.calendar.timezone'));
            $event->setStart($start);
            
            // Data e hora de fim
            $end = new EventDateTime();
            $end->setDateTime($this->formatDateTime($eventData['data_fim']));
            $end->setTimeZone(config('google.calendar.timezone'));
            $event->setEnd($end);
            
            // Adicionar participantes se fornecidos
            if (!empty($eventData['participantes'])) {
                $attendees = [];
                foreach ($eventData['participantes'] as $email) {
                    $attendees[] = ['email' => $email];
                }
                $event->setAttendees($attendees);
            }

            // Configurar Google Meet se for reunião online
            if ($eventData['tipo_reuniao'] === 'online' && config('google.meet.auto_generate')) {
                $this->addGoogleMeet($event);
            }

            // Criar evento no Google Calendar
            $createdEvent = $this->calendar->events->insert($this->calendarId, $event);
            
            Log::info('📅 Evento criado no Google Calendar', [
                'google_event_id' => $createdEvent->getId(),
                'titulo' => $eventData['titulo'],
                'meet_link' => $createdEvent->getHangoutLink()
            ]);

            return [
                'google_event_id' => $createdEvent->getId(),
                'google_meet_link' => $createdEvent->getHangoutLink(),
                'event_url' => $createdEvent->getHtmlLink(),
                'ical_uid' => $createdEvent->getICalUID(),
            ];

        } catch (\Exception $e) {
            Log::error('❌ Erro ao criar evento no Google Calendar', [
                'erro' => $e->getMessage(),
                'event_data' => $eventData
            ]);
            throw $e;
        }
    }

    /**
     * Atualizar evento no Google Calendar
     */
    public function updateEvent($googleEventId, $eventData)
    {
        try {
            // Buscar evento existente
            $event = $this->calendar->events->get($this->calendarId, $googleEventId);
            
            // Atualizar dados
            $event->setSummary($eventData['titulo']);
            $event->setDescription($eventData['descricao'] ?? '');
            
            // Atualizar data e hora de início
            $start = new EventDateTime();
            $start->setDateTime($this->formatDateTime($eventData['data_inicio']));
            $start->setTimeZone(config('google.calendar.timezone'));
            $event->setStart($start);
            
            // Atualizar data e hora de fim
            $end = new EventDateTime();
            $end->setDateTime($this->formatDateTime($eventData['data_fim']));
            $end->setTimeZone(config('google.calendar.timezone'));
            $event->setEnd($end);
            
            // Atualizar participantes
            if (!empty($eventData['participantes'])) {
                $attendees = [];
                foreach ($eventData['participantes'] as $email) {
                    $attendees[] = ['email' => $email];
                }
                $event->setAttendees($attendees);
            }

            // Salvar alterações
            $updatedEvent = $this->calendar->events->update($this->calendarId, $googleEventId, $event);
            
            Log::info('📝 Evento atualizado no Google Calendar', [
                'google_event_id' => $googleEventId,
                'titulo' => $eventData['titulo']
            ]);

            return [
                'google_event_id' => $updatedEvent->getId(),
                'google_meet_link' => $updatedEvent->getHangoutLink(),
                'event_url' => $updatedEvent->getHtmlLink(),
            ];

        } catch (\Exception $e) {
            Log::error('❌ Erro ao atualizar evento no Google Calendar', [
                'google_event_id' => $googleEventId,
                'erro' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Deletar evento do Google Calendar
     */
    public function deleteEvent($googleEventId)
    {
        try {
            $this->calendar->events->delete($this->calendarId, $googleEventId);
            
            Log::info('🗑️ Evento deletado do Google Calendar', [
                'google_event_id' => $googleEventId
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('❌ Erro ao deletar evento do Google Calendar', [
                'google_event_id' => $googleEventId,
                'erro' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Adicionar Google Meet ao evento
     */
    private function addGoogleMeet($event)
    {
        $conferenceRequest = new CreateConferenceRequest();
        $conferenceRequest->setRequestId(uniqid());
        
        $conferenceSolution = new ConferenceSolutionKey();
        $conferenceSolution->setType('hangoutsMeet');
        $conferenceRequest->setConferenceSolutionKey($conferenceSolution);
        
        $conferenceData = new ConferenceData();
        $conferenceData->setCreateRequest($conferenceRequest);
        
        $event->setConferenceData($conferenceData);
        
        Log::info('🎥 Google Meet adicionado ao evento');
    }

    /**
     * Formatar data/hora para o formato do Google Calendar
     */
    private function formatDateTime($dateTime)
    {
        return Carbon::parse($dateTime)->format('c'); // ISO 8601
    }

    /**
     * Listar eventos do calendário
     */
    public function listEvents($startDate = null, $endDate = null, $maxResults = 50)
    {
        try {
            $optParams = [
                'maxResults' => $maxResults,
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeZone' => config('google.calendar.timezone'),
            ];

            if ($startDate) {
                $optParams['timeMin'] = Carbon::parse($startDate)->format('c');
            }

            if ($endDate) {
                $optParams['timeMax'] = Carbon::parse($endDate)->format('c');
            }

            $results = $this->calendar->events->listEvents($this->calendarId, $optParams);
            $events = $results->getItems();

            Log::info('📋 Eventos listados do Google Calendar', [
                'total' => count($events)
            ]);

            return $events;
        } catch (\Exception $e) {
            Log::error('❌ Erro ao listar eventos do Google Calendar: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verificar se o serviço está configurado corretamente
     */
    public function isConfigured()
    {
        return !empty(config('google.client_id')) && 
               !empty(config('google.client_secret')) && 
               $this->client !== null;
    }

    /**
     * Verificar se está autenticado
     */
    public function isAuthenticated()
    {
        return !$this->client->isAccessTokenExpired();
    }

    /**
     * Gerar link do Google Meet manualmente (fallback)
     */
    public function generateMeetLink()
    {
        // Gerar um link único do Google Meet
        $meetId = 'orbita-' . uniqid() . '-' . rand(1000, 9999);
        return "https://meet.google.com/$meetId";
    }
}
