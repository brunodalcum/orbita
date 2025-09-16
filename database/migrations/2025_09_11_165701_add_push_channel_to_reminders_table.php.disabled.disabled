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
        // Modificar o enum para incluir 'push'
        DB::statement("ALTER TABLE reminders MODIFY COLUMN channel ENUM('email','whatsapp','sms','push') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Voltar ao enum original (primeiro mover todos os 'push' para 'email')
        DB::statement("UPDATE reminders SET channel = 'email' WHERE channel = 'push'");
        DB::statement("ALTER TABLE reminders MODIFY COLUMN channel ENUM('email','whatsapp','sms') NOT NULL");
    }
};