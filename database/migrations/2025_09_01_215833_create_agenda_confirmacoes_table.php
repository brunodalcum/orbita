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
        Schema::create('agenda_confirmacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agenda_id')->constrained('agendas')->onDelete('cascade');
            $table->string('email_participante');
            $table->enum('status', ['confirmado', 'pendente', 'recusado'])->default('pendente');
            $table->text('observacao')->nullable();
            $table->timestamp('confirmado_em')->nullable();
            $table->timestamps();
            
            // Índices para melhor performance
            $table->index(['agenda_id', 'email_participante']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_confirmacoes');
    }
};
