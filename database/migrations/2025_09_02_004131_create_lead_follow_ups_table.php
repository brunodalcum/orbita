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
        Schema::create('lead_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->text('observacao');
            $table->datetime('proximo_contato')->nullable();
            $table->foreignId('user_id')->constrained(); // Usuário que criou o follow-up
            $table->timestamps();
            
            // Índices para melhor performance
            $table->index(['lead_id', 'created_at']);
            $table->index('proximo_contato');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_follow_ups');
    }
};
