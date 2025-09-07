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
            $table->boolean('ativo')->default(true)->after('status');
            $table->foreignId('adquirente_id')->nullable()->after('ativo')->constrained('adquirentes')->onDelete('set null');
            $table->foreignId('parceiro_id')->nullable()->after('adquirente_id')->constrained('operacoes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planos', function (Blueprint $table) {
            $table->dropForeign(['parceiro_id']);
            $table->dropForeign(['adquirente_id']);
            $table->dropColumn(['ativo', 'adquirente_id', 'parceiro_id']);
        });
    }
};
