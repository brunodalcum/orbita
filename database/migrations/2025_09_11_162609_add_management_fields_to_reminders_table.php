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
            // Campos para pausar/reativar lembretes
            if (!Schema::hasColumn('reminders', 'paused_at')) {
                $table->timestamp('paused_at')->nullable()->after('sent_at');
            }
            if (!Schema::hasColumn('reminders', 'paused_by')) {
                $table->unsignedBigInteger('paused_by')->nullable()->after('paused_at');
            }
            
            // Campo para mensagem personalizada
            if (!Schema::hasColumn('reminders', 'message')) {
                $table->text('message')->nullable()->after('status');
            }
            
            // Campo para identificar quem criou o lembrete
            if (!Schema::hasColumn('reminders', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('message');
            }
            
            // Campo para identificar lembretes de teste
            if (!Schema::hasColumn('reminders', 'is_test')) {
                $table->boolean('is_test')->default(false)->after('created_by');
            }
            
            // Chaves estrangeiras
            if (!Schema::hasColumn('reminders', 'paused_by')) {
                $table->foreign('paused_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('reminders', 'created_by')) {
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            // Remover chaves estrangeiras primeiro
            $table->dropForeign(['paused_by']);
            $table->dropForeign(['created_by']);
            
            // Remover colunas
            $table->dropColumn([
                'paused_at',
                'paused_by', 
                'message',
                'created_by',
                'is_test'
            ]);
        });
    }
};