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
            // Verificar se a coluna não existe antes de adicionar
            if (!Schema::hasColumn('agendas', 'meet_link')) {
                $table->string('meet_link')->nullable()->after('tipo_reuniao');
            }
            if (!Schema::hasColumn('agendas', 'licenciado_id')) {
                $table->unsignedBigInteger('licenciado_id')->nullable()->after('user_id');
                $table->foreign('licenciado_id')->references('id')->on('licenciados')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            if (Schema::hasColumn('agendas', 'meet_link')) {
                $table->dropColumn('meet_link');
            }
            if (Schema::hasColumn('agendas', 'licenciado_id')) {
                $table->dropForeign(['licenciado_id']);
                $table->dropColumn('licenciado_id');
            }
        });
    }
};
