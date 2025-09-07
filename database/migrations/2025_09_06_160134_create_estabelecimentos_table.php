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
        Schema::create('estabelecimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licenciado_id')->constrained('users')->onDelete('cascade');
            $table->string('nome_fantasia');
            $table->string('razao_social');
            $table->string('cnpj', 18)->unique();
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->string('celular')->nullable();
            $table->string('endereco');
            $table->string('numero', 10);
            $table->string('complemento')->nullable();
            $table->string('bairro');
            $table->string('cidade');
            $table->string('estado', 2);
            $table->string('cep', 9);
            $table->enum('status', ['ativo', 'inativo', 'pendente', 'bloqueado'])->default('pendente');
            $table->enum('tipo_negocio', ['varejo', 'atacado', 'servicos', 'alimentacao', 'outros'])->default('varejo');
            $table->decimal('volume_mensal_estimado', 12, 2)->nullable();
            $table->json('documentos')->nullable(); // Para armazenar caminhos dos documentos
            $table->text('observacoes')->nullable();
            $table->timestamp('data_aprovacao')->nullable();
            $table->timestamp('data_bloqueio')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            
            $table->index(['licenciado_id', 'status']);
            $table->index(['cnpj']);
            $table->index(['cidade', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estabelecimentos');
    }
};
