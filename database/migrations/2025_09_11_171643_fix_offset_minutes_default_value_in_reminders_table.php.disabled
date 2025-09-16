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
        // Tornar offset_minutes nullable com valor padrão 0
        DB::statement("ALTER TABLE reminders MODIFY COLUMN offset_minutes INT NULL DEFAULT 0");
        
        // Também vamos tornar attempts nullable com valor padrão 0
        DB::statement("ALTER TABLE reminders MODIFY COLUMN attempts INT NULL DEFAULT 0");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Voltar ao estado anterior (NOT NULL sem default)
        DB::statement("ALTER TABLE reminders MODIFY COLUMN offset_minutes INT NOT NULL");
        DB::statement("ALTER TABLE reminders MODIFY COLUMN attempts INT NOT NULL");
    }
};