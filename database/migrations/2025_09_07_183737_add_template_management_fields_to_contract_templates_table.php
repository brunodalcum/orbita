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
        Schema::table('contract_templates', function (Blueprint $table) {
            // Campos para gerenciamento de templates
            $table->json('variables')->nullable()->after('placeholders_json'); // Variáveis detectadas
            $table->unsignedBigInteger('created_by')->nullable()->after('is_active'); // Usuário que criou
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by'); // Usuário que atualizou
            
            // Índices para performance
            $table->index('is_active');
            $table->index('created_by');
            
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_templates', function (Blueprint $table) {
            // Remover foreign keys
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            // Remover índices
            $table->dropIndex(['is_active']);
            $table->dropIndex(['created_by']);
            
            // Remover campos
            $table->dropColumn(['variables', 'created_by', 'updated_by']);
        });
    }
};