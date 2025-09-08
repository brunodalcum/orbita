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
        Schema::table('contracts', function (Blueprint $table) {
            // Adicionar nova coluna temporária com os novos status
            $table->enum('status_new', [
                'criado',              // Fase 0: Contrato criado
                'contrato_enviado',    // Fase 1: PDF gerado e enviado por email
                'aguardando_assinatura', // Fase 2: Aguardando assinatura do licenciado
                'contrato_assinado',   // Fase 3: Contrato assinado digitalmente
                'licenciado_aprovado', // Fase 4: Licenciado validado e aprovado
                'cancelado'            // Status para contratos cancelados
            ])->default('criado')->after('status');
        });

        // Mapear e copiar dados dos status antigos para novos
        $statusMap = [
            'documentos_pendentes' => 'criado',
            'documentos_enviados' => 'criado',
            'documentos_em_analise' => 'criado',
            'documentos_aprovados' => 'criado',
            'documentos_rejeitados' => 'cancelado',
            'contrato_enviado' => 'contrato_enviado',
            'contrato_assinado' => 'contrato_assinado',
            'licenciado_liberado' => 'licenciado_aprovado'
        ];

        foreach ($statusMap as $oldStatus => $newStatus) {
            DB::table('contracts')
                ->where('status', $oldStatus)
                ->update(['status_new' => $newStatus]);
        }

        Schema::table('contracts', function (Blueprint $table) {
            // Remover coluna antiga e renomear a nova
            $table->dropColumn('status');
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Reverter para os status antigos
            $table->enum('status', [
                'documentos_pendentes',
                'documentos_enviados',
                'documentos_em_analise',
                'documentos_aprovados',
                'documentos_rejeitados',
                'contrato_enviado',
                'contrato_assinado',
                'licenciado_liberado'
            ])->default('documentos_pendentes')->change();
        });
    }
};
