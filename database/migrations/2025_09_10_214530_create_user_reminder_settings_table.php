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
        Schema::create('user_reminder_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            
            // Configurações de offsets (em minutos)
            $table->json('reminder_offsets')->nullable(); // 48h, 24h, 1h
            
            // Configurações de canais
            $table->boolean('email_enabled')->default(true);
            $table->boolean('whatsapp_enabled')->default(false);
            $table->boolean('sms_enabled')->default(false);
            
            // Configurações de horários
            $table->time('quiet_start')->default('23:00'); // Início do período silencioso
            $table->time('quiet_end')->default('07:00');   // Fim do período silencioso
            $table->boolean('respect_quiet_hours')->default(false);
            
            // Configurações de timezone
            $table->string('timezone')->default('America/Fortaleza');
            
            // Configurações de idioma
            $table->string('language')->default('pt-BR');
            
            $table->timestamps();
            
            // Índices
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_reminder_settings');
    }
};