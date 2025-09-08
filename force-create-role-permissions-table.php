<?php

echo "🔧 FORÇANDO CRIAÇÃO DA TABELA ROLE_PERMISSIONS\n";
echo "=" . str_repeat("=", 50) . "\n\n";

try {
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // 1. Verificar se a tabela já existe
    echo "1. Verificando tabela role_permissions...\n";
    
    if (Schema::hasTable('role_permissions')) {
        echo "✅ Tabela role_permissions já existe\n";
        $count = DB::table('role_permissions')->count();
        echo "   Registros: $count\n\n";
    } else {
        echo "❌ Tabela role_permissions NÃO existe\n";
        echo "🔧 Criando tabela manualmente...\n\n";
        
        // 2. Criar a tabela manualmente usando SQL direto
        $sql = "
            CREATE TABLE IF NOT EXISTS `role_permissions` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `role_id` bigint(20) unsigned NOT NULL,
                `permission_id` bigint(20) unsigned NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `role_permissions_role_id_permission_id_unique` (`role_id`,`permission_id`),
                KEY `role_permissions_role_id_foreign` (`role_id`),
                KEY `role_permissions_permission_id_foreign` (`permission_id`),
                CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
                CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        try {
            DB::statement($sql);
            echo "✅ Tabela role_permissions criada com sucesso!\n\n";
        } catch (Exception $e) {
            echo "❌ Erro ao criar tabela: " . $e->getMessage() . "\n";
            
            // Tentar versão mais simples sem foreign keys
            echo "🔧 Tentando versão simplificada...\n";
            $simpleSql = "
                CREATE TABLE IF NOT EXISTS `role_permissions` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `role_id` bigint(20) unsigned NOT NULL,
                    `permission_id` bigint(20) unsigned NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `role_permissions_unique` (`role_id`,`permission_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ";
            
            try {
                DB::statement($simpleSql);
                echo "✅ Tabela role_permissions criada (versão simplificada)!\n\n";
            } catch (Exception $e2) {
                echo "❌ Erro na versão simplificada: " . $e2->getMessage() . "\n";
                exit(1);
            }
        }
    }
    
    // 3. Verificar novamente se a tabela foi criada
    echo "2. Verificação final da tabela...\n";
    if (Schema::hasTable('role_permissions')) {
        $count = DB::table('role_permissions')->count();
        echo "✅ Tabela role_permissions existe com $count registros\n\n";
    } else {
        echo "❌ Tabela ainda não existe\n";
        exit(1);
    }
    
    // 4. Popular com dados básicos do Super Admin
    echo "3. Populando dados básicos...\n";
    
    // Buscar Super Admin role
    $superAdminRole = DB::table('roles')->where('name', 'super_admin')->first();
    if (!$superAdminRole) {
        echo "❌ Role super_admin não encontrado\n";
        exit(1);
    }
    echo "✅ Super Admin Role encontrado (ID: {$superAdminRole->id})\n";
    
    // Buscar todas as permissões
    $permissions = DB::table('permissions')->where('is_active', 1)->get();
    echo "✅ Encontradas " . count($permissions) . " permissões ativas\n";
    
    // Inserir todas as permissões para o Super Admin
    $inserted = 0;
    foreach ($permissions as $permission) {
        try {
            DB::table('role_permissions')->insertOrIgnore([
                'role_id' => $superAdminRole->id,
                'permission_id' => $permission->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $inserted++;
        } catch (Exception $e) {
            echo "⚠️  Erro ao inserir permissão {$permission->name}: " . $e->getMessage() . "\n";
        }
    }
    
    echo "✅ Inseridas $inserted relações role-permission\n\n";
    
    // 5. Testar a query problemática
    echo "4. Testando query problemática...\n";
    
    try {
        $result = DB::select("
            SELECT EXISTS(
                SELECT * FROM permissions 
                INNER JOIN role_permissions ON permissions.id = role_permissions.permission_id 
                WHERE role_permissions.role_id = ? AND permissions.name = ?
            ) as `exists`
        ", [$superAdminRole->id, 'dashboard.view']);
        
        $exists = $result[0]->exists;
        echo "✅ Query funcionando! Resultado: " . ($exists ? 'true' : 'false') . "\n";
        
        if (!$exists) {
            echo "⚠️  Permissão dashboard.view não encontrada para Super Admin\n";
            
            // Tentar inserir especificamente
            $dashboardPermission = DB::table('permissions')->where('name', 'dashboard.view')->first();
            if ($dashboardPermission) {
                DB::table('role_permissions')->insertOrIgnore([
                    'role_id' => $superAdminRole->id,
                    'permission_id' => $dashboardPermission->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                echo "✅ Permissão dashboard.view adicionada manualmente\n";
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Query ainda falhando: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
    
    // 6. Limpar caches
    echo "5. Limpando caches...\n";
    exec('php artisan cache:clear 2>&1', $output1);
    exec('php artisan config:clear 2>&1', $output2);
    exec('php artisan view:clear 2>&1', $output3);
    echo "✅ Caches limpos\n\n";
    
    // 7. Verificação final
    echo "6. Verificação final...\n";
    $finalCount = DB::table('role_permissions')->count();
    echo "✅ Total de relações role-permission: $finalCount\n";
    
    // Testar uma última vez
    try {
        $testResult = DB::table('permissions')
            ->join('role_permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->where('role_permissions.role_id', $superAdminRole->id)
            ->where('permissions.name', 'dashboard.view')
            ->exists();
            
        echo "✅ Teste final da query: " . ($testResult ? 'SUCESSO' : 'FALHA') . "\n";
        
    } catch (Exception $e) {
        echo "❌ Teste final falhou: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERRO CRÍTICO: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "🏁 CORREÇÃO CONCLUÍDA!\n";
echo "🌐 Teste agora: /dashboard\n";
echo str_repeat("=", 50) . "\n";

?>
