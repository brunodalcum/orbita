<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar o enum para incluir 'paused'
        DB::statement("ALTER TABLE reminders MODIFY COLUMN status ENUM('pending','sent','failed','canceled','paused') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Voltar ao enum original (primeiro mover todos os 'paused' para 'pending')
        DB::statement("UPDATE reminders SET status = 'pending' WHERE status = 'paused'");
        DB::statement("ALTER TABLE reminders MODIFY COLUMN status ENUM('pending','sent','failed','canceled') NOT NULL DEFAULT 'pending'");
    }
};