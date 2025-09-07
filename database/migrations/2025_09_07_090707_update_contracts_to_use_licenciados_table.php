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
        Schema::table('contracts', function (Blueprint $table) {
            // Remover a foreign key existente
            $table->dropForeign(['licenciado_id']);
            
            // Renomear a coluna para ser mais clara
            $table->renameColumn('licenciado_id', 'licenciado_table_id');
            
            // Adicionar nova foreign key para a tabela licenciados
            $table->foreign('licenciado_table_id')->references('id')->on('licenciados')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Remover a foreign key da tabela licenciados
            $table->dropForeign(['licenciado_table_id']);
            
            // Renomear de volta
            $table->renameColumn('licenciado_table_id', 'licenciado_id');
            
            // Restaurar foreign key para a tabela users
            $table->foreign('licenciado_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};