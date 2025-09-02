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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->datetime('data_inicio');
            $table->datetime('data_fim');
            $table->string('google_meet_link')->nullable();
            $table->json('participantes')->nullable();
            $table->enum('status', ['agendada', 'em_andamento', 'concluida', 'cancelada'])->default('agendada');
            $table->enum('tipo_reuniao', ['presencial', 'online', 'hibrida'])->default('online');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
