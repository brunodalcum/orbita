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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licenciado_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', [
                'documentos_pendentes',
                'documentos_enviados', 
                'documentos_em_analise',
                'documentos_aprovados',
                'documentos_rejeitados',
                'contrato_enviado',
                'contrato_assinado',
                'licenciado_liberado'
            ])->default('documentos_pendentes');
            $table->text('observacoes_admin')->nullable();
            $table->string('contract_pdf_path')->nullable(); // Caminho do PDF do contrato
            $table->string('signed_contract_path')->nullable(); // Caminho do contrato assinado
            $table->string('signature_token')->nullable(); // Token único para assinatura
            $table->timestamp('documents_approved_at')->nullable();
            $table->timestamp('contract_sent_at')->nullable();
            $table->timestamp('contract_signed_at')->nullable();
            $table->timestamp('licenciado_released_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // Admin que aprovou
            $table->foreignId('contract_sent_by')->nullable()->constrained('users')->onDelete('set null'); // Admin que enviou contrato
            $table->json('signature_data')->nullable(); // Dados da assinatura eletrônica
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index(['licenciado_id', 'status']);
            $table->index(['status']);
            $table->index(['signature_token']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
