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
        Schema::table('reminders', function (Blueprint $table) {
            // Adicionar coluna participant_id após participant_email
            if (!Schema::hasColumn('reminders', 'participant_id')) {
                $table->unsignedBigInteger('participant_id')->nullable()->after('event_id');
                
                // Adicionar chave estrangeira
                $table->foreign('participant_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            // Remover chave estrangeira primeiro
            $table->dropForeign(['participant_id']);
            
            // Remover coluna
            $table->dropColumn('participant_id');
        });
    }
};