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
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licenciado_id')->constrained()->onDelete('cascade');
            $table->string('tipo'); // contato, documentacao, analise, aprovacao, rejeicao, observacao
            $table->text('observacao');
            $table->foreignId('user_id')->constrained(); // Usuário que criou o follow-up
            $table->timestamps();
            
            // Índices para melhor performance
            $table->index(['licenciado_id', 'created_at']);
            $table->index('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};
