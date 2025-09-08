<?php

echo "🔧 CORREÇÃO DA MIGRAÇÃO DE CONTRACTS\n";
echo "=" . str_repeat("=", 40) . "\n\n";

try {
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // 1. Verificar se a tabela contracts existe
    echo "1. Verificando tabela contracts...\n";
    
    if (!Schema::hasTable('contracts')) {
        echo "❌ Tabela contracts não existe\n";
        exit(1);
    }
    
    echo "✅ Tabela contracts existe\n";
    
    // 2. Verificar estrutura atual da tabela
    echo "\n2. Verificando estrutura atual...\n";
    
    $columns = DB::select("DESCRIBE contracts");
    $columnNames = array_column($columns, 'Field');
    
    echo "   Colunas existentes:\n";
    foreach ($columnNames as $column) {
        echo "   - $column\n";
    }
    
    // 3. Verificar foreign keys existentes
    echo "\n3. Verificando foreign keys...\n";
    
    $foreignKeys = DB::select("
        SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'contracts' 
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    
    if (empty($foreignKeys)) {
        echo "   ⚠️  Nenhuma foreign key encontrada\n";
    } else {
        echo "   Foreign keys existentes:\n";
        foreach ($foreignKeys as $fk) {
            echo "   - {$fk->CONSTRAINT_NAME}: {$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME}\n";
        }
    }
    
    // 4. Verificar se a migração problemática já foi executada
    echo "\n4. Verificando status da migração...\n";
    
    $migrationExists = DB::table('migrations')
        ->where('migration', '2025_09_07_090707_update_contracts_to_use_licenciados_table')
        ->exists();
    
    if ($migrationExists) {
        echo "⚠️  Migração já foi registrada como executada\n";
        echo "🔧 Removendo registro da migração para permitir nova execução...\n";
        
        DB::table('migrations')
            ->where('migration', '2025_09_07_090707_update_contracts_to_use_licenciados_table')
            ->delete();
            
        echo "✅ Registro removido\n";
    } else {
        echo "✅ Migração ainda não foi executada\n";
    }
    
    // 5. Criar migração corrigida
    echo "\n5. Criando migração corrigida...\n";
    
    $migrationContent = '<?php

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
        Schema::table(\'contracts\', function (Blueprint $table) {
            // Verificar se a foreign key existe antes de tentar removê-la
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = \'contracts\' 
                AND COLUMN_NAME = \'licenciado_id\'
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            if (!empty($foreignKeys)) {
                $table->dropForeign([\'licenciado_id\']);
            }
            
            // Verificar se a coluna licenciado_table_id já existe
            if (!Schema::hasColumn(\'contracts\', \'licenciado_table_id\')) {
                // Renomear a coluna apenas se ela ainda não foi renomeada
                if (Schema::hasColumn(\'contracts\', \'licenciado_id\')) {
                    $table->renameColumn(\'licenciado_id\', \'licenciado_table_id\');
                } else {
                    // Se a coluna licenciado_id não existe, criar licenciado_table_id
                    $table->unsignedBigInteger(\'licenciado_table_id\')->nullable();
                }
            }
            
            // Verificar se a tabela licenciados existe antes de criar a foreign key
            if (Schema::hasTable(\'licenciados\')) {
                // Verificar se a foreign key já existe
                $existingForeignKey = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = \'contracts\' 
                    AND COLUMN_NAME = \'licenciado_table_id\'
                    AND REFERENCED_TABLE_NAME = \'licenciados\'
                ");
                
                if (empty($existingForeignKey)) {
                    $table->foreign(\'licenciado_table_id\')->references(\'id\')->on(\'licenciados\')->onDelete(\'cascade\');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(\'contracts\', function (Blueprint $table) {
            // Verificar se a foreign key existe antes de removê-la
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = \'contracts\' 
                AND COLUMN_NAME = \'licenciado_table_id\'
                AND REFERENCED_TABLE_NAME = \'licenciados\'
            ");
            
            if (!empty($foreignKeys)) {
                $table->dropForeign([\'licenciado_table_id\']);
            }
            
            // Renomear de volta apenas se necessário
            if (Schema::hasColumn(\'contracts\', \'licenciado_table_id\')) {
                $table->renameColumn(\'licenciado_table_id\', \'licenciado_id\');
            }
            
            // Restaurar foreign key para users se a tabela existir
            if (Schema::hasTable(\'users\')) {
                $table->foreign(\'licenciado_id\')->references(\'id\')->on(\'users\')->onDelete(\'cascade\');
            }
        });
    }
};';
    
    // Salvar migração corrigida
    $migrationPath = 'database/migrations/2025_09_07_090707_update_contracts_to_use_licenciados_table_fixed.php';
    file_put_contents($migrationPath, $migrationContent);
    
    echo "✅ Migração corrigida salva em: $migrationPath\n";
    
    // 6. Executar a migração corrigida
    echo "\n6. Executando migração corrigida...\n";
    
    $output = [];
    $returnCode = 0;
    exec('php artisan migrate --path=database/migrations/2025_09_07_090707_update_contracts_to_use_licenciados_table_fixed.php --force 2>&1', $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "✅ Migração corrigida executada com sucesso\n";
        foreach ($output as $line) {
            if (strpos($line, 'Migrated:') !== false) {
                echo "   $line\n";
            }
        }
    } else {
        echo "❌ Erro na migração corrigida:\n";
        foreach ($output as $line) {
            echo "   $line\n";
        }
    }
    
    echo "\n7. Executando outras migrações...\n";
    
    // Executar todas as outras migrações
    $output = [];
    exec('php artisan migrate --force 2>&1', $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "✅ Todas as migrações executadas\n";
        foreach ($output as $line) {
            if (strpos($line, 'Migrated:') !== false || strpos($line, 'Nothing to migrate') !== false) {
                echo "   $line\n";
            }
        }
    } else {
        echo "⚠️  Algumas migrações podem ter falhado:\n";
        foreach (array_slice($output, -5) as $line) {
            echo "   $line\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n" . str_repeat("=", 40) . "\n";
echo "🏁 CORREÇÃO CONCLUÍDA\n";
echo str_repeat("=", 40) . "\n";

?>
