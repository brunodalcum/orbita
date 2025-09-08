<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Para MySQL, precisamos fazer isso em etapas devido às limitações do ENUM
        
        // 1. Adicionar nova coluna temporária
        Schema::table('contracts', function (Blueprint $table) {
            $table->enum('status_temp', [
                'documentos_pendentes',
                'documentos_enviados', 
                'documentos_em_analise',
                'documentos_aprovados',
                'documentos_rejeitados',
                'contrato_enviado',
                'aguardando_assinatura',  // NOVO
                'contrato_assinado',
                'licenciado_liberado',
                'sent',                   // NOVO - compatibilidade com sistema antigo
                'pdf_ready',              // NOVO - compatibilidade com sistema antigo
                'criado',                 // NOVO - status inicial
                'licenciado_aprovado',    // NOVO - status final
                'cancelado'               // NOVO - status de cancelamento
            ])->default('documentos_pendentes')->after('status');
        });
        
        // 2. Copiar dados da coluna original para a temporária
        DB::statement("UPDATE contracts SET status_temp = status");
        
        // 3. Remover coluna original
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        // 4. Renomear coluna temporária
        Schema::table('contracts', function (Blueprint $table) {
            $table->renameColumn('status_temp', 'status');
        });
        
        // 5. Recriar índices (apenas se não existirem)
        try {
            Schema::table('contracts', function (Blueprint $table) {
                $table->index(['licenciado_id', 'status']);
            });
        } catch (\Exception $e) {
            // Índice já existe
        }
        
        try {
            Schema::table('contracts', function (Blueprint $table) {
                $table->index(['status']);
            });
        } catch (\Exception $e) {
            // Índice já existe
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverter para o enum original (apenas se necessário)
        Schema::table('contracts', function (Blueprint $table) {
            $table->enum('status_temp', [
                'documentos_pendentes',
                'documentos_enviados', 
                'documentos_em_analise',
                'documentos_aprovados',
                'documentos_rejeitados',
                'contrato_enviado',
                'contrato_assinado',
                'licenciado_liberado'
            ])->default('documentos_pendentes')->after('status');
        });
        
        // Copiar dados, mantendo apenas os status válidos
        DB::statement("
            UPDATE contracts 
            SET status_temp = CASE 
                WHEN status IN ('aguardando_assinatura', 'sent') THEN 'contrato_enviado'
                WHEN status IN ('pdf_ready', 'criado') THEN 'documentos_pendentes'
                WHEN status = 'licenciado_aprovado' THEN 'licenciado_liberado'
                WHEN status = 'cancelado' THEN 'documentos_rejeitados'
                ELSE status 
            END
        ");
        
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('contracts', function (Blueprint $table) {
            $table->renameColumn('status_temp', 'status');
        });
        
        Schema::table('contracts', function (Blueprint $table) {
            $table->index(['licenciado_id', 'status']);
            $table->index(['status']);
        });
    }
};