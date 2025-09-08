<?php

echo "🔧 FORÇANDO EXECUÇÃO DA MIGRAÇÃO ROLE_PERMISSIONS\n";
echo "=" . str_repeat("=", 55) . "\n\n";

try {
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // 1. Verificar migrações pendentes
    echo "1. Verificando migrações pendentes...\n";
    
    $output = [];
    exec('php artisan migrate:status', $output);
    
    $rolePermissionsMigrationPending = false;
    foreach ($output as $line) {
        if (strpos($line, 'role_permissions') !== false) {
            echo "   $line\n";
            if (strpos($line, 'Pending') !== false || strpos($line, 'N') !== false) {
                $rolePermissionsMigrationPending = true;
            }
        }
    }
    
    echo "\n";
    
    // 2. Executar migração específica se estiver pendente
    if ($rolePermissionsMigrationPending) {
        echo "2. Executando migração específica role_permissions...\n";
        
        $output = [];
        $returnCode = 0;
        exec('php artisan migrate --path=database/migrations/2025_09_08_134436_create_role_permissions_table.php --force 2>&1', $output, $returnCode);
        
        if ($returnCode === 0) {
            echo "✅ Migração específica executada com sucesso\n";
            foreach ($output as $line) {
                echo "   $line\n";
            }
        } else {
            echo "❌ Erro na migração específica:\n";
            foreach ($output as $line) {
                echo "   $line\n";
            }
            
            // Tentar executar todas as migrações pendentes
            echo "\n🔧 Tentando executar todas as migrações pendentes...\n";
            $output2 = [];
            exec('php artisan migrate --force 2>&1', $output2, $returnCode2);
            
            if ($returnCode2 === 0) {
                echo "✅ Todas as migrações executadas\n";
                foreach ($output2 as $line) {
                    if (strpos($line, 'role_permissions') !== false || strpos($line, 'Migrated') !== false) {
                        echo "   $line\n";
                    }
                }
            } else {
                echo "❌ Erro ao executar migrações:\n";
                foreach ($output2 as $line) {
                    echo "   $line\n";
                }
            }
        }
    } else {
        echo "2. Migração já foi executada anteriormente\n";
    }
    
    echo "\n";
    
    // 3. Verificar se a tabela foi criada
    echo "3. Verificando criação da tabela...\n";
    
    if (Schema::hasTable('role_permissions')) {
        $count = DB::table('role_permissions')->count();
        echo "✅ Tabela role_permissions existe com $count registros\n";
        
        // Mostrar estrutura da tabela
        $columns = DB::select("DESCRIBE role_permissions");
        echo "   Estrutura da tabela:\n";
        foreach ($columns as $column) {
            echo "   - {$column->Field} ({$column->Type})\n";
        }
    } else {
        echo "❌ Tabela role_permissions ainda não existe\n";
        echo "🔧 Tentando criar manualmente...\n";
        
        // Criar usando o SQL da migração
        try {
            Schema::create('role_permissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('role_id')->constrained()->onDelete('cascade');
                $table->foreignId('permission_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                $table->unique(['role_id', 'permission_id']);
            });
            
            echo "✅ Tabela criada manualmente usando Schema Builder\n";
            
        } catch (Exception $e) {
            echo "❌ Erro ao criar com Schema Builder: " . $e->getMessage() . "\n";
            echo "🔧 Tentando SQL direto...\n";
            
            $sql = "
                CREATE TABLE `role_permissions` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `role_id` bigint(20) unsigned NOT NULL,
                    `permission_id` bigint(20) unsigned NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `role_permissions_role_id_permission_id_unique` (`role_id`,`permission_id`),
                    KEY `role_permissions_role_id_foreign` (`role_id`),
                    KEY `role_permissions_permission_id_foreign` (`permission_id`),
                    CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
                    CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ";
            
            try {
                DB::statement($sql);
                echo "✅ Tabela criada com SQL direto\n";
            } catch (Exception $e2) {
                echo "❌ Erro com SQL direto: " . $e2->getMessage() . "\n";
                exit(1);
            }
        }
    }
    
    echo "\n";
    
    // 4. Popular dados se a tabela estiver vazia
    echo "4. Verificando e populando dados...\n";
    
    $rolePermissionCount = DB::table('role_permissions')->count();
    if ($rolePermissionCount == 0) {
        echo "🔧 Tabela vazia, populando com PermissionSeeder...\n";
        
        $output = [];
        exec('php artisan db:seed --class=PermissionSeeder --force 2>&1', $output);
        
        echo "✅ PermissionSeeder executado\n";
        foreach ($output as $line) {
            if (strpos($line, 'Seeding') !== false || strpos($line, 'Database') !== false) {
                echo "   $line\n";
            }
        }
        
        $newCount = DB::table('role_permissions')->count();
        echo "✅ Nova contagem: $newCount relações\n";
    } else {
        echo "✅ Tabela já possui $rolePermissionCount relações\n";
    }
    
    echo "\n";
    
    // 5. Teste final
    echo "5. Teste final da query problemática...\n";
    
    try {
        $result = DB::select("
            SELECT EXISTS(
                SELECT * FROM `permissions` 
                INNER JOIN `role_permissions` ON `permissions`.`id` = `role_permissions`.`permission_id` 
                WHERE `role_permissions`.`role_id` = 1 AND `permissions`.`name` = 'dashboard.view'
            ) as `exists`
        ");
        
        $exists = $result[0]->exists;
        echo "✅ Query funcionando! Resultado: " . ($exists ? 'SUCESSO' : 'FALHA') . "\n";
        
    } catch (Exception $e) {
        echo "❌ Query ainda falhando: " . $e->getMessage() . "\n";
    }
    
    // 6. Limpar caches
    echo "\n6. Limpando caches finais...\n";
    exec('php artisan cache:clear');
    exec('php artisan config:clear');
    exec('php artisan view:clear');
    echo "✅ Caches limpos\n";
    
} catch (Exception $e) {
    echo "❌ ERRO CRÍTICO: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n" . str_repeat("=", 55) . "\n";
echo "🏁 MIGRAÇÃO FORÇADA CONCLUÍDA!\n";
echo "🌐 Teste agora acessar: /dashboard\n";
echo str_repeat("=", 55) . "\n";

?>
