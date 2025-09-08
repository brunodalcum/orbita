<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Campos para controle do stepper
            $table->json('meta')->nullable()->after('signature_data');
            $table->text('last_error')->nullable()->after('meta');
            $table->timestamp('filled_at')->nullable()->after('created_at');
            $table->timestamp('pdf_generated_at')->nullable()->after('filled_at');
            $table->timestamp('approved_at')->nullable()->after('licenciado_released_at');
            
            // Atualizar enum de status para incluir novos estados
            $table->dropColumn('status');
        });
        
        Schema::table('contracts', function (Blueprint $table) {
            $table->enum('status', [
                'draft',           // Rascunho inicial
                'filled',          // Dados preenchidos no modelo
                'pdf_ready',       // PDF gerado
                'sent',            // Email enviado
                'signed',          // Contrato assinado
                'approved',        // Aprovado e liberado
                'error'            // Erro em alguma etapa
            ])->default('draft')->after('licenciado_table_id');
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn([
                'meta',
                'last_error', 
                'filled_at',
                'pdf_generated_at',
                'approved_at'
            ]);
            
            $table->dropColumn('status');
        });
        
        Schema::table('contracts', function (Blueprint $table) {
            $table->enum('status', [
                'criado', 'contrato_enviado', 'aguardando_assinatura',
                'contrato_assinado', 'licenciado_aprovado', 'cancelado'
            ])->default('criado')->after('licenciado_table_id');
        });
    }
};