<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Dados do lembrete
     */
    public array $reminderData;

    /**
     * Create a new message instance.
     */
    public function __construct(array $reminderData)
    {
        $this->reminderData = $reminderData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->generateSubject();

        return new Envelope(
            from: config('mail.from.address', 'no-reply@orbita.com'),
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reminder',
            with: $this->reminderData,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            // Futuramente: anexar arquivo ICS
        ];
    }

    /**
     * Gerar assunto dinâmico baseado no offset
     */
    private function generateSubject(): string
    {
        $eventTitle = $this->reminderData['event_title'];
        $timeUntil = $this->reminderData['time_until_event'];
        $offsetLabel = $this->reminderData['offset_label'];

        // Assuntos baseados no tempo restante
        if (str_contains($timeUntil, 'menos de 1 hora')) {
            return "🔔 Sua reunião começa em breve: {$eventTitle}";
        } elseif (str_contains($timeUntil, '1 hora')) {
            return "⏰ Lembrete: reunião {$eventTitle} em 1 hora";
        } elseif (str_contains($timeUntil, 'hora')) {
            return "📅 Lembrete: reunião {$eventTitle} hoje";
        } else {
            return "📋 Lembrete: reunião {$eventTitle} {$timeUntil}";
        }
    }
}