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
        Schema::create('contract_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Quem executou a ação
            $table->string('action'); // documento_enviado, documento_aprovado, contrato_enviado, etc.
            $table->text('description'); // Descrição detalhada da ação
            $table->json('metadata')->nullable(); // Dados adicionais (IPs, arquivos, etc.)
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['contract_id', 'action']);
            $table->index(['user_id']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_audit_logs');
    }
};
