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
        Schema::table('planos', function (Blueprint $table) {
            // Adicionar campos de comissão
            $table->json('comissoes_detalhadas')->nullable()->after('taxas_detalhadas');
            $table->decimal('comissao_media', 8, 4)->default(0)->after('taxa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planos', function (Blueprint $table) {
            $table->dropColumn(['comissoes_detalhadas', 'comissao_media']);
        });
    }
};
