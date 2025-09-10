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
        Schema::create('calendar_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Horários de trabalho por dia da semana (JSON)
            $table->json('working_hours')->nullable();
            
            // Configurações gerais
            $table->integer('buffer_minutes')->default(10); // Buffer entre reuniões
            $table->integer('min_advance_hours')->default(2); // Mínimo de antecedência
            $table->integer('max_advance_days')->default(30); // Máximo de antecedência
            $table->integer('default_duration')->default(30); // Duração padrão (minutos)
            
            // Configurações de fuso horário
            $table->string('timezone')->default('America/Fortaleza');
            
            // Configurações de notificação
            $table->boolean('email_reminders')->default(true);
            $table->json('reminder_times')->nullable(); // 24h e 1h antes (em minutos)
            
            // Configurações de disponibilidade pública
            $table->boolean('public_booking_enabled')->default(false);
            $table->string('public_booking_slug')->nullable()->unique();
            $table->text('booking_policies')->nullable(); // Texto das políticas
            
            // Configurações de integração
            $table->boolean('google_sync_enabled')->default(true);
            $table->boolean('auto_generate_meet')->default(true);
            
            $table->timestamps();
            
            // Índices
            $table->index('user_id');
            $table->index('public_booking_slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_settings');
    }
};
