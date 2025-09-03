<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Google_Service_Calendar_EventAttendee;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{
    private $client;
    private $service;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setApplicationName(config('app.name', 'Orbita Agenda'));
        $this->client->setScopes(config('google-calendar.scopes', Google_Service_Calendar::CALENDAR));
        
        // Configurar SSL para cURL (Windows/XAMPP)
        $this->configureCurlSSL();
        
        // Verificar se está usando Service Account ou OAuth2
        if (config('google-calendar.service_account_enabled', false)) {
            $this->setupServiceAccount();
        } else {
            $this->setupOAuth2();
        }
        
        $this->service = new Google_Service_Calendar($this->client);
    }

    /**
     * Configurar SSL para cURL (Windows/XAMPP)
     */
    private function configureCurlSSL()
    {
        // Configurações específicas para Windows/XAMPP
        $curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
        ];

        // Criar cliente HTTP personalizado com configurações SSL
        $httpClient = new \GuzzleHttp\Client([
            'curl' => $curlOptions,
            'timeout' => 30,
            'verify' => false, // Desabilitar verificação SSL para desenvolvimento
        ]);

        $this->client->setHttpClient($httpClient);
    }

    /**
     * Configurar Service Account
     */
    private function setupServiceAccount()
    {
        $credentialsFile = config('google-calendar.service_account_file');
        
        if (!file_exists($credentialsFile)) {
            throw new \Exception("Arquivo de credenciais do Service Account não encontrado: {$credentialsFile}");
        }
        
        $this->client->setAuthConfig($credentialsFile);
        $this->client->setAccessType('offline');
    }

    /**
     * Configurar OAuth2
     */
    private function setupOAuth2()
    {
        $clientId = config('google-calendar.client_id');
        $clientSecret = config('google-calendar.client_secret');
        
        if (!$clientId || !$clientSecret) {
            throw new \Exception('Google Calendar OAuth2 credentials não configuradas. Verifique GOOGLE_CALENDAR_CLIENT_ID e GOOGLE_CALENDAR_CLIENT_SECRET no .env');
        }
        
        $this->client->setClientId($clientId);
        $this->client->setClientSecret($clientSecret);
        $this->client->setRedirectUri(config('google-calendar.redirect_uri'));
        $this->client->setAccessType('offline');
        $this->client->setApprovalPrompt('force');
        
        // Para desenvolvimento, vamos usar um token de acesso direto
        // Em produção, você deve implementar o fluxo OAuth2 completo
        $this->client->setDeveloperKey(config('google-calendar.api_key'));
    }

    /**
     * Criar evento no Google Calendar com Google Meet
     */
    public function createEvent($titulo, $descricao, $dataInicio, $dataFim, $participantes = [])
    {
        try {
            $event = new Google_Service_Calendar_Event();
            $event->setSummary($titulo);
            $event->setDescription($descricao);

            // Configurar horários
            $timezone = config('google-calendar.default_timezone', 'America/Sao_Paulo');
            
            $start = new Google_Service_Calendar_EventDateTime();
            $start->setDateTime($dataInicio);
            $start->setTimeZone($timezone);
            $event->setStart($start);

            $end = new Google_Service_Calendar_EventDateTime();
            $end->setDateTime($dataFim);
            $end->setTimeZone($timezone);
            $event->setEnd($end);

            // Adicionar participantes
            $attendees = [];
            foreach ($participantes as $email) {
                $attendee = new Google_Service_Calendar_EventAttendee();
                $attendee->setEmail($email);
                $attendees[] = $attendee;
            }
            $event->setAttendees($attendees);

            // Configurar Google Meet se estiver habilitado
            if (config('google-calendar.meet_enabled', true)) {
                $conferenceData = new \Google_Service_Calendar_ConferenceData();
                $createRequest = new \Google_Service_Calendar_CreateConferenceRequest();
                $conferenceSolutionKey = new \Google_Service_Calendar_ConferenceSolutionKey();
                
                $conferenceSolutionKey->setType('hangoutsMeet');
                $createRequest->setRequestId(uniqid());
                $createRequest->setConferenceSolutionKey($conferenceSolutionKey);
                $conferenceData->setCreateRequest($createRequest);
                
                $event->setConferenceData($conferenceData);
            }

            // Criar o evento
            $calendarId = config('google-calendar.calendar_id', 'primary');
            $sendUpdates = config('google-calendar.send_updates', 'all');
            
            $createdEvent = $this->service->events->insert($calendarId, $event, [
                'conferenceDataVersion' => 1,
                'sendUpdates' => $sendUpdates
            ]);

            return [
                'success' => true,
                'event_id' => $createdEvent->getId(),
                'meet_link' => $createdEvent->getConferenceData()['entryPoints'][0]['uri'] ?? null,
                'html_link' => $createdEvent->getHtmlLink()
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao criar evento no Google Calendar: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Atualizar evento existente
     */
    public function updateEvent($eventId, $titulo, $descricao, $dataInicio, $dataFim, $participantes = [])
    {
        try {
            $event = $this->service->events->get('primary', $eventId);
            
            $event->setSummary($titulo);
            $event->setDescription($descricao);

            // Atualizar horários
            $timezone = config('google-calendar.default_timezone', 'America/Sao_Paulo');
            
            $start = new Google_Service_Calendar_EventDateTime();
            $start->setDateTime($dataInicio);
            $start->setTimeZone($timezone);
            $event->setStart($start);

            $end = new Google_Service_Calendar_EventDateTime();
            $end->setDateTime($dataFim);
            $end->setTimeZone($timezone);
            $event->setEnd($end);

            // Atualizar participantes
            $attendees = [];
            foreach ($participantes as $email) {
                $attendee = new Google_Service_Calendar_EventAttendee();
                $attendee->setEmail($email);
                $attendees[] = $attendee;
            }
            $event->setAttendees($attendees);

            $calendarId = config('google-calendar.calendar_id', 'primary');
            $sendUpdates = config('google-calendar.send_updates', 'all');
            
            $updatedEvent = $this->service->events->update($calendarId, $eventId, $event, [
                'sendUpdates' => $sendUpdates
            ]);

            return [
                'success' => true,
                'event_id' => $updatedEvent->getId(),
                'meet_link' => $updatedEvent->getConferenceData()['entryPoints'][0]['uri'] ?? null,
                'html_link' => $updatedEvent->getHtmlLink()
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar evento no Google Calendar: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Excluir evento
     */
    public function deleteEvent($eventId)
    {
        try {
            $calendarId = config('google-calendar.calendar_id', 'primary');
            $sendUpdates = config('google-calendar.send_updates', 'all');
            
            $this->service->events->delete($calendarId, $eventId, [
                'sendUpdates' => $sendUpdates
            ]);

            return ['success' => true];

        } catch (\Exception $e) {
            Log::error('Erro ao excluir evento no Google Calendar: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verificar se está autenticado
     */
    public function isAuthenticated()
    {
        try {
            $tokenFile = storage_path('app/google-calendar-token.json');
            
            if (!file_exists($tokenFile)) {
                return false;
            }
            
            $token = json_decode(file_get_contents($tokenFile), true);
            if (!$token || !isset($token['access_token'])) {
                return false;
            }
            
            $this->client->setAccessToken($token);
            
            // Verificar se o token expirou
            if ($this->client->isAccessTokenExpired()) {
                if (isset($token['refresh_token'])) {
                    $this->client->refreshToken($token['refresh_token']);
                    $newToken = $this->client->getAccessToken();
                    file_put_contents($tokenFile, json_encode($newToken));
                    return true;
                } else {
                    return false;
                }
            }
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Erro ao verificar autenticação: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obter URL de autorização OAuth2
     */
    public function getAuthUrl()
    {
        try {
            $authUrl = $this->client->createAuthUrl();
            return [
                'success' => true,
                'auth_url' => $authUrl
            ];
        } catch (\Exception $e) {
            Log::error('Erro ao gerar URL de autorização: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Processar callback OAuth2 e salvar token
     */
    public function handleAuthCallback($code)
    {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);
            
            if (isset($token['error'])) {
                throw new \Exception('Erro na autorização: ' . ($token['error_description'] ?? $token['error']));
            }
            
            // Salvar token
            $tokenFile = storage_path('app/google-calendar-token.json');
            file_put_contents($tokenFile, json_encode($token));
            
            // Configurar token no cliente
            $this->client->setAccessToken($token);
            
            return [
                'success' => true,
                'message' => 'Autorização realizada com sucesso!'
            ];
            
        } catch (\Exception $e) {
            Log::error('Erro ao processar callback OAuth2: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
