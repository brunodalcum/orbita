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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Ex: 'licenciados.view', 'contratos.create'
            $table->string('display_name'); // Ex: 'Visualizar Licenciados', 'Criar Contratos'
            $table->string('description')->nullable(); // Descrição detalhada da permissão
            $table->string('module'); // Ex: 'licenciados', 'contratos', 'usuarios'
            $table->string('action'); // Ex: 'view', 'create', 'edit', 'delete', 'manage'
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Índices para performance
            $table->index(['module', 'action']);
            $table->index('is_active');
        });
        
        // Tabela pivot para relacionamento many-to-many entre roles e permissions
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Evitar duplicatas
            $table->unique(['role_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
    }
};