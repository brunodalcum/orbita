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
        Schema::create('orbita_operacaos', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome da operação
            $table->string('display_name'); // Nome para exibição
            $table->text('description')->nullable(); // Descrição da operação
            $table->string('domain')->nullable(); // Domínio personalizado (ex: minhaoperacao.com)
            $table->string('subdomain')->nullable(); // Subdomínio (ex: operacao1.orbita.com)
            $table->json('branding')->nullable(); // Configurações de marca (logo, cores, etc)
            $table->json('modules')->nullable(); // Módulos disponíveis/ativos
            $table->json('settings')->nullable(); // Configurações específicas
            $table->boolean('is_active')->default(true); // Status ativo/inativo
            $table->timestamps();
            
            // Índices
            $table->unique('name');
            $table->unique('domain');
            $table->unique('subdomain');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orbita_operacaos');
    }
};
