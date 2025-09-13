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
        Schema::table('place_extractions', function (Blueprint $table) {
            $table->integer('leads_inserted')->nullable()->after('total_duplicates')->comment('Número de leads inseridos na tabela leads');
            $table->integer('leads_duplicated')->nullable()->after('leads_inserted')->comment('Número de leads duplicados não inseridos');
            $table->integer('leads_errors')->nullable()->after('leads_duplicated')->comment('Número de leads com erro na inserção');
            $table->timestamp('leads_inserted_at')->nullable()->after('leads_errors')->comment('Data/hora da inserção dos leads');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('place_extractions', function (Blueprint $table) {
            $table->dropColumn(['leads_inserted', 'leads_duplicated', 'leads_errors', 'leads_inserted_at']);
        });
    }
};