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
        Schema::create('campanhas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->enum('tipo', ['lead', 'licenciado', 'geral']);
            $table->unsignedBigInteger('modelo_id');
            $table->enum('status', ['rascunho', 'ativa', 'pausada', 'concluida'])->default('rascunho');
            $table->datetime('data_inicio')->nullable();
            $table->datetime('data_fim')->nullable();
            $table->json('segmentacao')->nullable();
            $table->integer('total_destinatarios')->default(0);
            $table->integer('emails_enviados')->default(0);
            $table->integer('emails_abertos')->default(0);
            $table->integer('emails_clicados')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            $table->foreign('modelo_id')->references('id')->on('email_modelos')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campanhas');
    }
};
