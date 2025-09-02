<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Lead;

class WelcomeLeadEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;

    /**
     * Create a new message instance.
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚀 Bem-vindo à DSPay — sua nova oportunidade começa agora!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $downloadUrl = route('leads.download.presentation', $this->lead->id);
        
        return new Content(
            view: 'emails.welcome-lead',
            with: [
                'lead' => $this->lead,
                'downloadUrl' => $downloadUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $filePath = storage_path('app/presentations/dspay-apresentacao.pdf');
        
        // Verificar se o arquivo existe e é válido
        if (!file_exists($filePath)) {
            \Log::warning('Arquivo de apresentação não encontrado: ' . $filePath);
            return [];
        }
        
        // Verificar se é um PDF válido (deve ter pelo menos 1KB e começar com %PDF)
        $fileSize = filesize($filePath);
        $fileContent = file_get_contents($filePath, false, null, 0, 10);
        
        if ($fileSize < 1000 || !str_starts_with($fileContent, '%PDF')) {
            \Log::warning('Arquivo de apresentação inválido: ' . $filePath . ' (Tamanho: ' . $fileSize . ' bytes)');
            return [];
        }
        
        try {
            return [
                \Illuminate\Mail\Mailables\Attachment::fromPath($filePath)
                    ->as('DSPay_Apresentacao.pdf')
                    ->withMime('application/pdf')
            ];
        } catch (\Exception $e) {
            \Log::error('Erro ao anexar arquivo: ' . $e->getMessage());
            return [];
        }
    }
}
