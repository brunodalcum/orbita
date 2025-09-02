<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Google\Service\Calendar\EventAttendee;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{
    private $client;
    private $service;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName(config('app.name', 'Orbita Agenda'));
        $this->client->setScopes(config('google-calendar.scopes', Calendar::CALENDAR));
        
        // Configurar SSL para cURL (Windows/XAMPP)
        if (function_exists('configureCurlSSL')) {
            $this->client->setHttpClient(new \GuzzleHttp\Client([
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false, // Temporariamente desabilitar para teste
                    CURLOPT_SSL_VERIFYHOST => false,  // Temporariamente desabilitar para teste
                ]
            ]));
        }
        
        // Verificar se está usando Service Account ou OAuth2
        if (config('google-calendar.service_account_enabled', false)) {
            $this->setupServiceAccount();
        } else {
            $this->setupOAuth2();
        }
        
        $this->service = new Calendar($this->client);
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
            $event = new Event();
            $event->setSummary($titulo);
            $event->setDescription($descricao);

            // Configurar horários
            $timezone = config('google-calendar.default_timezone', 'America/Sao_Paulo');
            
            $start = new EventDateTime();
            $start->setDateTime($dataInicio);
            $start->setTimeZone($timezone);
            $event->setStart($start);

            $end = new EventDateTime();
            $end->setDateTime($dataFim);
            $end->setTimeZone($timezone);
            $event->setEnd($end);

            // Adicionar participantes
            $attendees = [];
            foreach ($participantes as $email) {
                $attendee = new EventAttendee();
                $attendee->setEmail($email);
                $attendees[] = $attendee;
            }
            $event->setAttendees($attendees);

            // Configurar Google Meet se estiver habilitado
            if (config('google-calendar.meet_enabled', true)) {
                $conferenceData = new \Google\Service\Calendar\ConferenceData();
                $createRequest = new \Google\Service\Calendar\CreateConferenceRequest();
                $conferenceSolutionKey = new \Google\Service\Calendar\ConferenceSolutionKey();
                
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
            
            $start = new EventDateTime();
            $start->setDateTime($dataInicio);
            $start->setTimeZone($timezone);
            $event->setStart($start);

            $end = new EventDateTime();
            $end->setDateTime($dataFim);
            $end->setTimeZone($timezone);
            $event->setEnd($end);

            // Atualizar participantes
            $attendees = [];
            foreach ($participantes as $email) {
                $attendee = new EventAttendee();
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
}
