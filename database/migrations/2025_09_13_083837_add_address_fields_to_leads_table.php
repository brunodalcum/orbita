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
        Schema::table('leads', function (Blueprint $table) {
            $table->text('endereco')->nullable()->after('empresa')->comment('Endereço completo do lead');
            $table->string('cidade')->nullable()->after('endereco')->comment('Cidade do lead');
            $table->string('estado', 2)->nullable()->after('cidade')->comment('Estado do lead (sigla)');
            $table->timestamp('data_contato')->nullable()->after('observacoes')->comment('Data do último contato');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['endereco', 'cidade', 'estado', 'data_contato']);
        });
    }
};