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
        Schema::create('white_labels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operacao_id')->constrained('orbita_operacaos')->onDelete('cascade');
            $table->string('name'); // Nome do White Label
            $table->string('display_name'); // Nome para exibição
            $table->text('description')->nullable(); // Descrição
            $table->string('domain')->nullable(); // Domínio personalizado (ex: minhawl.com)
            $table->string('subdomain')->nullable(); // Subdomínio (ex: wl1.orbita.com)
            $table->json('branding')->nullable(); // Configurações de marca herdadas/customizadas
            $table->json('modules')->nullable(); // Módulos disponíveis/ativos (herda da operação)
            $table->json('settings')->nullable(); // Configurações específicas
            $table->boolean('is_active')->default(true); // Status ativo/inativo
            $table->timestamps();
            
            // Índices
            $table->unique(['operacao_id', 'name']);
            $table->unique('domain');
            $table->unique('subdomain');
            $table->index(['operacao_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('white_labels');
    }
};
