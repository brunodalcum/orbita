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
        Schema::create('place_extractions', function (Blueprint $table) {
            $table->id();
            
            // Usuário que executou a extração
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Parâmetros da busca
            $table->string('query'); // Termo de busca (ex: "farmácia")
            $table->string('location')->nullable(); // Localização (endereço ou lat,lng)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('radius')->nullable(); // Raio em metros
            $table->json('types')->nullable(); // Tipos de estabelecimento
            $table->string('language')->default('pt-BR');
            $table->string('region')->default('BR');
            
            // Resultados
            $table->integer('total_found')->default(0); // Total encontrado pela API
            $table->integer('total_processed')->default(0); // Total processado
            $table->integer('total_new')->default(0); // Novos places adicionados
            $table->integer('total_updated')->default(0); // Places atualizados
            $table->integer('total_duplicates')->default(0); // Duplicatas encontradas
            
            // Status da extração
            $table->enum('status', ['pending', 'running', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('duration_seconds')->nullable();
            
            // Custos e limites
            $table->integer('api_requests_made')->default(0);
            $table->decimal('estimated_cost', 8, 4)->default(0); // Em USD
            $table->boolean('hit_rate_limit')->default(false);
            
            // Compliance e auditoria
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('compliance_flags')->nullable(); // Flags de compliance
            $table->text('legal_basis')->nullable(); // Base legal LGPD
            
            $table->timestamps();
            
            // Índices
            $table->index(['user_id', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index(['query', 'location']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('place_extractions');
    }
};