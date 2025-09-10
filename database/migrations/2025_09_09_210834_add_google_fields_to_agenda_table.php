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
        Schema::table('agendas', function (Blueprint $table) {
            // Verificar se as colunas não existem antes de adicionar
            if (!Schema::hasColumn('agendas', 'google_event_url')) {
                $table->text('google_event_url')->nullable()->after('google_event_id');
            }
            
            if (!Schema::hasColumn('agendas', 'google_synced_at')) {
                $table->timestamp('google_synced_at')->nullable()->after('google_event_url');
            }
            
            // Adicionar índice se não existir
            if (!Schema::hasColumn('agendas', 'google_event_id') || !collect(Schema::getIndexes('agendas'))->contains(function ($index) {
                return in_array('google_event_id', $index['columns']);
            })) {
                $table->index('google_event_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->dropIndex(['google_event_id']);
            $table->dropColumn([
                'google_event_id',
                'google_event_url', 
                'google_synced_at'
            ]);
        });
    }
};