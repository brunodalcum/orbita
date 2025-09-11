<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            
            // Relacionamentos
            $table->unsignedBigInteger('event_id'); // ID da agenda
            $table->string('participant_email'); // Email do participante
            $table->string('participant_name')->nullable(); // Nome do participante
            
            // Configuração do lembrete
            $table->enum('channel', ['email', 'whatsapp', 'sms'])->default('email');
            $table->timestamp('send_at'); // Quando enviar (UTC)
            $table->integer('offset_minutes'); // Offset em minutos (ex: 2880 = 48h)
            
            // Estado e controle
            $table->enum('status', ['pending', 'sent', 'failed', 'canceled'])->default('pending');
            $table->integer('attempts')->default(0);
            $table->text('last_error')->nullable();
            $table->timestamp('sent_at')->nullable();
            
            // Metadados do evento (snapshot para evitar joins)
            $table->string('event_title');
            $table->timestamp('event_start_utc');
            $table->timestamp('event_end_utc');
            $table->string('event_timezone')->default('America/Fortaleza');
            $table->string('event_meet_link')->nullable();
            $table->string('event_host_name');
            $table->string('event_host_email');
            $table->text('event_description')->nullable();
            
            $table->timestamps();
            
            // Índices para performance
            $table->index(['status', 'send_at']); // Para o dispatcher
            $table->index(['event_id', 'participant_email', 'channel', 'send_at'], 'reminder_uniqueness'); // Para idempotência
            $table->index(['event_id']); // Para buscar lembretes de um evento
            $table->index(['participant_email']); // Para buscar lembretes de um participante
            
            // Chave estrangeira
            $table->foreign('event_id')->references('id')->on('agendas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};