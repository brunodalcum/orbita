<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Enviar e-mail de confirmação de reunião
     */
    public function sendMeetingConfirmation($participantes, $titulo, $descricao, $dataInicio, $dataFim, $meetLink = null, $organizador = null, $agendaId = null)
    {
        try {
            // Criar confirmações pendentes para todos os participantes
            if ($agendaId) {
                $this->criarConfirmacoesPendentes($agendaId, $participantes);
            }
            
            foreach ($participantes as $email) {
                $email = trim($email);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->sendEmail($email, $titulo, $descricao, $dataInicio, $dataFim, $meetLink, $organizador, $agendaId);
                }
            }
            
            return ['success' => true];
        } catch (\Exception $e) {
            Log::error('Erro ao enviar e-mails de confirmação: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Criar confirmações pendentes para os participantes
     */
    public function criarConfirmacoesPendentes($agendaId, $participantes)
    {
        try {
            foreach ($participantes as $email) {
                $email = trim($email);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    \App\Models\AgendaConfirmacao::updateOrCreate(
                        [
                            'agenda_id' => $agendaId,
                            'email_participante' => $email
                        ],
                        [
                            'status' => 'pendente',
                            'confirmado_em' => null
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error('Erro ao criar confirmações pendentes: ' . $e->getMessage());
        }
    }

    /**
     * Enviar e-mail individual
     */
    private function sendEmail($email, $titulo, $descricao, $dataInicio, $dataFim, $meetLink = null, $organizador = null, $agendaId = null)
    {
        $data = [
            'titulo' => $titulo,
            'descricao' => $descricao,
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
            'meet_link' => $meetLink,
            'organizador' => $organizador,
            'agenda_id' => $agendaId,
            'participant_email' => $email
        ];

        Mail::send('emails.meeting-confirmation', $data, function ($message) use ($email, $titulo) {
            $message->to($email)
                    ->subject('Confirmação de Reunião: ' . $titulo);
        });
    }

    /**
     * Enviar e-mail de atualização de reunião
     */
    public function sendMeetingUpdate($participantes, $titulo, $descricao, $dataInicio, $dataFim, $meetLink = null, $organizador = null, $agendaId = null)
    {
        try {
            foreach ($participantes as $email) {
                $email = trim($email);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->sendUpdateEmail($email, $titulo, $descricao, $dataInicio, $dataFim, $meetLink, $organizador, $agendaId);
                }
            }
            
            return ['success' => true];
        } catch (\Exception $e) {
            Log::error('Erro ao enviar e-mails de atualização: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Enviar e-mail de atualização individual
     */
    private function sendUpdateEmail($email, $titulo, $descricao, $dataInicio, $dataFim, $meetLink = null, $organizador = null, $agendaId = null)
    {
        $data = [
            'titulo' => $titulo,
            'descricao' => $descricao,
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
            'meet_link' => $meetLink,
            'organizador' => $organizador,
            'agenda_id' => $agendaId,
            'participant_email' => $email
        ];

        // Usar o mesmo template de confirmação para atualizações
        Mail::send('emails.meeting-confirmation', $data, function ($message) use ($email, $titulo) {
            $message->to($email)
                    ->subject('Reunião Atualizada: ' . $titulo);
        });
    }

    /**
     * Enviar e-mail de cancelamento de reunião
     */
    public function sendMeetingCancellation($participantes, $titulo, $organizador = null)
    {
        try {
            foreach ($participantes as $email) {
                $email = trim($email);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->sendCancellationEmail($email, $titulo, $organizador);
                }
            }
            
            return ['success' => true];
        } catch (\Exception $e) {
            Log::error('Erro ao enviar e-mails de cancelamento: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Enviar e-mail de cancelamento individual
     */
    private function sendCancellationEmail($email, $titulo, $organizador = null)
    {
        $data = [
            'titulo' => $titulo,
            'organizador' => $organizador
        ];

        Mail::send('emails.meeting-cancellation', $data, function ($message) use ($email, $titulo) {
            $message->to($email)
                    ->subject('Reunião Cancelada: ' . $titulo);
        });
    }
}
