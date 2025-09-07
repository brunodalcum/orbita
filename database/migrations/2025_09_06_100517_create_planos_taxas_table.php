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
        Schema::create('planos_taxas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plano_id')->constrained('planos')->onDelete('cascade');
            $table->enum('modalidade', ['debito', 'credito_avista', 'parcelado']);
            $table->string('bandeira', 20); // visa, mastercard, elo, amex, hipercard, etc.
            $table->integer('parcelas')->nullable(); // NULL para débito e crédito à vista; 2-21 para parcelado
            $table->decimal('taxa_percent', 7, 4); // ex.: 1.5000 (1,50%)
            $table->decimal('comissao_percent', 7, 4); // ex.: 0.8500 (0,85%)
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            // Indexes recomendados
            $table->index(['plano_id', 'modalidade', 'bandeira', 'parcelas']);
            $table->index(['modalidade', 'bandeira', 'parcelas', 'taxa_percent']);
            $table->index(['modalidade', 'bandeira', 'parcelas', 'comissao_percent']);
            $table->index(['ativo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planos_taxas');
    }
};
