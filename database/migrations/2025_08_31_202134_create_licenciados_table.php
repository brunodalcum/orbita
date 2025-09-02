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
        Schema::create('licenciados', function (Blueprint $table) {
            $table->id();
            
            // Dados Básicos (Step 1)
            $table->string('razao_social');
            $table->string('nome_fantasia')->nullable();
            $table->string('cnpj_cpf')->unique();
            $table->string('endereco');
            $table->string('bairro');
            $table->string('cidade');
            $table->string('estado', 2);
            $table->string('cep', 9);
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            
            // Documentos (Step 2)
            $table->string('cartao_cnpj_path')->nullable();
            $table->string('contrato_social_path')->nullable();
            $table->string('rg_cnh_path')->nullable();
            $table->string('comprovante_residencia_path')->nullable();
            $table->string('comprovante_atividade_path')->nullable();
            
            // Operações (Step 3)
            $table->boolean('pagseguro')->default(false);
            $table->boolean('adiq')->default(false);
            $table->boolean('confrapag')->default(false);
            $table->boolean('mercadopago')->default(false);
            
            // Status e Controle
            $table->enum('status', ['ativo', 'pendente', 'inativo', 'vencendo'])->default('pendente');
            $table->text('observacoes')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Índices
            $table->index('cnpj_cpf');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenciados');
    }
};
