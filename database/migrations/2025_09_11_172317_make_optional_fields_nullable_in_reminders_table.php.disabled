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
        // Tornar campos opcionais nullable
        DB::statement("ALTER TABLE reminders MODIFY COLUMN event_title VARCHAR(255) NULL");
        DB::statement("ALTER TABLE reminders MODIFY COLUMN event_start_utc TIMESTAMP NULL");
        DB::statement("ALTER TABLE reminders MODIFY COLUMN event_end_utc TIMESTAMP NULL");
        DB::statement("ALTER TABLE reminders MODIFY COLUMN event_timezone VARCHAR(255) NULL");
        DB::statement("ALTER TABLE reminders MODIFY COLUMN event_meet_link VARCHAR(255) NULL");
        DB::statement("ALTER TABLE reminders MODIFY COLUMN event_host_name VARCHAR(255) NULL");
        DB::statement("ALTER TABLE reminders MODIFY COLUMN event_host_email VARCHAR(255) NULL");
        DB::statement("ALTER TABLE reminders MODIFY COLUMN event_description TEXT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Voltar ao estado anterior (NOT NULL)
        // Primeiro, atualizar registros NULL para valores padrão
        DB::statement("UPDATE reminders SET event_title = 'N/A' WHERE event_title IS NULL");
        DB::statement("UPDATE reminders SET event_start_utc = NOW() WHERE event_start_utc IS NULL");
        DB::statement("UPDATE reminders SET event_end_utc = NOW() WHERE event_end_utc IS NULL");
        DB::statement("UPDATE reminders SET event_host_name = 'Sistema' WHERE event_host_name IS NULL");
        DB::statement("UPDATE reminders SET event_host_email = 'sistema@orbita.com' WHERE event_host_email IS NULL");
        
        // Depois, tornar NOT NULL novamente
        DB::statement("ALTER TABLE reminders MODIFY COLUMN event_title VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE reminders MODIFY COLUMN event_start_utc TIMESTAMP NOT NULL");
        DB::statement("ALTER TABLE reminders MODIFY COLUMN event_end_utc TIMESTAMP NOT NULL");
        DB::statement("ALTER TABLE reminders MODIFY COLUMN event_timezone VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE reminders MODIFY COLUMN event_meet_link VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE reminders MODIFY COLUMN event_host_name VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE reminders MODIFY COLUMN event_host_email VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE reminders MODIFY COLUMN event_description TEXT NOT NULL");
    }
};