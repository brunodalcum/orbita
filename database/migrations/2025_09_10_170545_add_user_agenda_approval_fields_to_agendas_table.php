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
        Schema::table('agendas', function (Blueprint $table) {
            // Campos para sistema de aprovação entre usuários
            if (!Schema::hasColumn('agendas', 'solicitante_id')) {
                $table->unsignedBigInteger('solicitante_id')->nullable()->after('user_id')->comment('Usuário que solicita a agenda');
                $table->foreign('solicitante_id')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('agendas', 'destinatario_id')) {
                $table->unsignedBigInteger('destinatario_id')->nullable()->after('solicitante_id')->comment('Usuário destinatário da agenda');
                $table->foreign('destinatario_id')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('agendas', 'status_aprovacao')) {
                $table->enum('status_aprovacao', ['pendente', 'aprovada', 'recusada', 'automatica'])->default('pendente')->after('status')->comment('Status da aprovação da agenda');
            }
            
            if (!Schema::hasColumn('agendas', 'requer_aprovacao')) {
                $table->boolean('requer_aprovacao')->default(true)->after('status_aprovacao')->comment('Se a agenda requer aprovação');
            }
            
            if (!Schema::hasColumn('agendas', 'fora_horario_comercial')) {
                $table->boolean('fora_horario_comercial')->default(false)->after('requer_aprovacao')->comment('Se a agenda é fora do horário comercial');
            }
            
            if (!Schema::hasColumn('agendas', 'aprovada_em')) {
                $table->timestamp('aprovada_em')->nullable()->after('fora_horario_comercial')->comment('Data/hora da aprovação');
            }
            
            if (!Schema::hasColumn('agendas', 'aprovada_por')) {
                $table->unsignedBigInteger('aprovada_por')->nullable()->after('aprovada_em')->comment('Usuário que aprovou');
                $table->foreign('aprovada_por')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('agendas', 'motivo_recusa')) {
                $table->text('motivo_recusa')->nullable()->after('aprovada_por')->comment('Motivo da recusa se aplicável');
            }
            
            if (!Schema::hasColumn('agendas', 'notificacao_enviada')) {
                $table->boolean('notificacao_enviada')->default(false)->after('motivo_recusa')->comment('Se notificação foi enviada');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            // Remover foreign keys primeiro
            $table->dropForeign(['solicitante_id']);
            $table->dropForeign(['destinatario_id']);
            $table->dropForeign(['aprovada_por']);
            
            // Remover colunas
            $table->dropColumn([
                'solicitante_id',
                'destinatario_id', 
                'status_aprovacao',
                'requer_aprovacao',
                'fora_horario_comercial',
                'aprovada_em',
                'aprovada_por',
                'motivo_recusa',
                'notificacao_enviada'
            ]);
        });
    }
};