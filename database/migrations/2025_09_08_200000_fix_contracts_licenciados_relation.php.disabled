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
        Schema::table('contracts', function (Blueprint $table) {
            // Verificar se a foreign key existe antes de tentar removê-la
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'contracts' 
                AND COLUMN_NAME = 'licenciado_id'
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            // Remover foreign key apenas se existir
            foreach ($foreignKeys as $fk) {
                try {
                    $table->dropForeign($fk->CONSTRAINT_NAME);
                } catch (Exception $e) {
                    // Ignorar se não conseguir remover
                }
            }
            
            // Verificar se a coluna licenciado_table_id já existe
            if (!Schema::hasColumn('contracts', 'licenciado_table_id')) {
                // Renomear a coluna apenas se ela ainda não foi renomeada
                if (Schema::hasColumn('contracts', 'licenciado_id')) {
                    $table->renameColumn('licenciado_id', 'licenciado_table_id');
                } else {
                    // Se a coluna licenciado_id não existe, criar licenciado_table_id
                    $table->unsignedBigInteger('licenciado_table_id')->nullable();
                }
            }
        });
        
        // Adicionar foreign key em uma operação separada
        Schema::table('contracts', function (Blueprint $table) {
            // Verificar se a tabela licenciados existe antes de criar a foreign key
            if (Schema::hasTable('licenciados')) {
                // Verificar se a foreign key já existe
                $existingForeignKey = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'contracts' 
                    AND COLUMN_NAME = 'licenciado_table_id'
                    AND REFERENCED_TABLE_NAME = 'licenciados'
                ");
                
                if (empty($existingForeignKey)) {
                    try {
                        $table->foreign('licenciado_table_id')->references('id')->on('licenciados')->onDelete('cascade');
                    } catch (Exception $e) {
                        // Ignorar se não conseguir criar a foreign key
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Verificar se a foreign key existe antes de removê-la
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'contracts' 
                AND COLUMN_NAME = 'licenciado_table_id'
                AND REFERENCED_TABLE_NAME = 'licenciados'
            ");
            
            foreach ($foreignKeys as $fk) {
                try {
                    $table->dropForeign($fk->CONSTRAINT_NAME);
                } catch (Exception $e) {
                    // Ignorar se não conseguir remover
                }
            }
            
            // Renomear de volta apenas se necessário
            if (Schema::hasColumn('contracts', 'licenciado_table_id') && !Schema::hasColumn('contracts', 'licenciado_id')) {
                $table->renameColumn('licenciado_table_id', 'licenciado_id');
            }
        });
        
        // Restaurar foreign key em operação separada
        Schema::table('contracts', function (Blueprint $table) {
            // Restaurar foreign key para users se a tabela existir
            if (Schema::hasTable('users') && Schema::hasColumn('contracts', 'licenciado_id')) {
                try {
                    $table->foreign('licenciado_id')->references('id')->on('users')->onDelete('cascade');
                } catch (Exception $e) {
                    // Ignorar se não conseguir criar
                }
            }
        });
    }
};
