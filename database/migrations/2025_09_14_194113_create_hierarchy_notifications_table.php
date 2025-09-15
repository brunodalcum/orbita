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
        Schema::create('hierarchy_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // system, alert, info, warning, success, error
            $table->string('category'); // user_action, system_update, hierarchy_change, etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Dados adicionais da notificação
            
            // Remetente (quem enviou)
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->string('sender_type')->nullable(); // user, system
            $table->string('sender_name')->nullable();
            
            // Destinatário (quem deve receber)
            $table->unsignedBigInteger('recipient_id')->nullable();
            $table->string('recipient_type')->nullable(); // user, node_type (operacao, white_label, etc.)
            
            // Escopo hierárquico
            $table->string('scope')->default('direct'); // direct, descendants, ancestors, all
            $table->unsignedBigInteger('scope_node_id')->nullable(); // ID do nó de escopo
            $table->string('scope_node_type')->nullable(); // Tipo do nó de escopo
            
            // Controle de entrega
            $table->string('delivery_method')->default('internal'); // internal, email, sms, push
            $table->timestamp('scheduled_at')->nullable(); // Para notificações agendadas
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            
            // Status e prioridade
            $table->string('status')->default('pending'); // pending, sent, read, expired, failed
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            $table->boolean('is_persistent')->default(false); // Se deve ficar visível após lida
            $table->boolean('requires_action')->default(false); // Se requer ação do usuário
            
            // Metadados
            $table->string('action_url')->nullable(); // URL para ação relacionada
            $table->string('action_text')->nullable(); // Texto do botão de ação
            $table->json('metadata')->nullable(); // Metadados adicionais
            $table->string('reference_type')->nullable(); // Tipo do recurso relacionado
            $table->unsignedBigInteger('reference_id')->nullable(); // ID do recurso relacionado
            
            $table->timestamps();

            // Índices para performance
            $table->index(['recipient_id', 'status', 'created_at'], 'notif_recipient_status_created');
            $table->index(['sender_id', 'created_at'], 'notif_sender_created');
            $table->index(['type', 'category'], 'notif_type_category');
            $table->index(['scope', 'scope_node_id'], 'notif_scope_node');
            $table->index(['status', 'scheduled_at'], 'notif_status_scheduled');
            $table->index(['expires_at'], 'notif_expires');
            $table->index(['reference_type', 'reference_id'], 'notif_reference');
            
            // Foreign keys
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hierarchy_notifications');
    }
};