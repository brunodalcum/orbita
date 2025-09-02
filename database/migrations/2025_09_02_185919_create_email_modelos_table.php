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
        Schema::create('email_modelos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('assunto');
            $table->longText('conteudo');
            $table->enum('tipo', ['lead', 'licenciado', 'geral'])->default('geral');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            
            $table->index(['tipo', 'ativo']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_modelos');
    }
};
