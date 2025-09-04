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
        Schema::table('licenciados', function (Blueprint $table) {
            if (!Schema::hasColumn('licenciados', 'email')) {
                $table->string('email')->nullable()->after('cep');
            }
            if (!Schema::hasColumn('licenciados', 'telefone')) {
                $table->string('telefone')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenciados', function (Blueprint $table) {
            $table->dropColumn(['email', 'telefone']);
        });
    }
};
