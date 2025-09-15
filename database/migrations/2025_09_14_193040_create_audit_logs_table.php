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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Quem executou a ação
            $table->string('user_name')->nullable(); // Nome do usuário (para casos de exclusão)
            $table->string('user_type')->nullable(); // Tipo do usuário (super_admin, operacao, etc.)
            $table->unsignedBigInteger('impersonated_by')->nullable(); // Se estava impersonando
            $table->string('action'); // Ação executada (create, update, delete, login, etc.)
            $table->string('resource_type'); // Tipo do recurso (user, domain, permission, etc.)
            $table->unsignedBigInteger('resource_id')->nullable(); // ID do recurso afetado
            $table->string('resource_name')->nullable(); // Nome do recurso (para referência)
            $table->json('old_values')->nullable(); // Valores anteriores (para updates)
            $table->json('new_values')->nullable(); // Novos valores
            $table->json('metadata')->nullable(); // Dados adicionais (IP, user agent, etc.)
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('session_id')->nullable();
            $table->string('request_id')->nullable(); // Para rastrear requests relacionados
            $table->string('severity')->default('info'); // info, warning, error, critical
            $table->text('description')->nullable(); // Descrição legível da ação
            $table->json('context')->nullable(); // Contexto adicional
            $table->boolean('sensitive')->default(false); // Se contém dados sensíveis
            $table->timestamp('occurred_at'); // Quando a ação ocorreu
            $table->timestamps();

            // Índices para performance
            $table->index(['user_id', 'occurred_at']);
            $table->index(['resource_type', 'resource_id']);
            $table->index(['action', 'occurred_at']);
            $table->index(['severity', 'occurred_at']);
            $table->index('impersonated_by');
            $table->index('ip_address');
            $table->index('session_id');
            $table->index('request_id');
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('impersonated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};