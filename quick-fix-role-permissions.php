<?php

echo "⚡ CORREÇÃO RÁPIDA - TABELA ROLE_PERMISSIONS\n";
echo "=" . str_repeat("=", 45) . "\n\n";

try {
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // 1. Verificar se a tabela existe
    echo "1. Verificando tabela role_permissions...\n";
    
    if (Schema::hasTable('role_permissions')) {
        echo "✅ Tabela role_permissions já existe\n";
        $count = DB::table('role_permissions')->count();
        echo "   Registros: $count\n\n";
    } else {
        echo "❌ Tabela role_permissions NÃO existe\n";
        echo "🔧 Executando migração...\n\n";
        
        // Executar apenas as migrações
        exec('php artisan migrate --force', $output, $returnCode);
        
        if ($returnCode === 0) {
            echo "✅ Migração executada com sucesso\n\n";
        } else {
            echo "❌ Erro na migração\n";
            foreach ($output as $line) {
                echo "   $line\n";
            }
            exit(1);
        }
    }
    
    // 2. Verificar se as permissões estão populadas
    echo "2. Verificando dados das permissões...\n";
    
    $permissionCount = DB::table('permissions')->count();
    $rolePermissionCount = DB::table('role_permissions')->count();
    
    echo "   Permissões: $permissionCount\n";
    echo "   Relações role-permission: $rolePermissionCount\n\n";
    
    if ($permissionCount == 0 || $rolePermissionCount == 0) {
        echo "🔧 Populando dados...\n";
        
        // Executar seeders
        exec('php artisan db:seed --class=PermissionSeeder --force', $output1);
        echo "✅ PermissionSeeder executado\n";
        
        // Verificar novamente
        $newPermissionCount = DB::table('permissions')->count();
        $newRolePermissionCount = DB::table('role_permissions')->count();
        echo "   Nova contagem - Permissões: $newPermissionCount, Relações: $newRolePermissionCount\n\n";
    }
    
    // 3. Testar a query problemática
    echo "3. Testando query problemática...\n";
    
    try {
        $result = DB::select("
            SELECT EXISTS(
                SELECT * FROM permissions 
                INNER JOIN role_permissions ON permissions.id = role_permissions.permission_id 
                WHERE role_permissions.role_id = 1 AND permissions.name = 'dashboard.view'
            ) as `exists`
        ");
        
        echo "✅ Query funcionando! Resultado: " . ($result[0]->exists ? 'true' : 'false') . "\n";
        
    } catch (Exception $e) {
        echo "❌ Query ainda falhando: " . $e->getMessage() . "\n";
        
        // Tentar criar a relação manualmente
        echo "🔧 Tentando criar relação manualmente...\n";
        
        $superAdminRole = DB::table('roles')->where('name', 'super_admin')->first();
        $dashboardPermission = DB::table('permissions')->where('name', 'dashboard.view')->first();
        
        if ($superAdminRole && $dashboardPermission) {
            DB::table('role_permissions')->insertOrIgnore([
                'role_id' => $superAdminRole->id,
                'permission_id' => $dashboardPermission->id
            ]);
            echo "✅ Relação criada manualmente\n";
        } else {
            echo "❌ Não foi possível criar a relação\n";
            echo "   Super Admin Role: " . ($superAdminRole ? 'Encontrado' : 'Não encontrado') . "\n";
            echo "   Dashboard Permission: " . ($dashboardPermission ? 'Encontrado' : 'Não encontrado') . "\n";
        }
    }
    
    echo "\n";
    
    // 4. Limpar caches
    echo "4. Limpando caches...\n";
    exec('php artisan cache:clear', $output);
    exec('php artisan config:clear', $output);
    exec('php artisan view:clear', $output);
    echo "✅ Caches limpos\n\n";
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    exit(1);
}

echo str_repeat("=", 45) . "\n";
echo "🏁 CORREÇÃO CONCLUÍDA!\n";
echo "🌐 Teste agora: /dashboard\n";
echo str_repeat("=", 45) . "\n";

?>
