<?php

echo "🔍 VERIFICAÇÃO DO SISTEMA DE PERMISSÕES\n";
echo "=" . str_repeat("=", 45) . "\n\n";

try {
    // Inicializar Laravel
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "✅ Laravel inicializado com sucesso\n\n";
    
    // 1. Verificar tabelas
    echo "1. VERIFICAÇÃO DE TABELAS\n";
    echo "-" . str_repeat("-", 25) . "\n";
    
    $tables = [
        'users' => 'Usuários',
        'roles' => 'Perfis/Roles', 
        'permissions' => 'Permissões',
        'role_permissions' => 'Relação Role-Permissão'
    ];
    
    foreach ($tables as $table => $description) {
        try {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                echo "✅ $description: $count registros\n";
            } else {
                echo "❌ Tabela '$table' não existe\n";
            }
        } catch (Exception $e) {
            echo "❌ Erro na tabela '$table': " . $e->getMessage() . "\n";
        }
    }
    
    // 2. Verificar usuário Super Admin
    echo "\n2. VERIFICAÇÃO DO USUÁRIO SUPER ADMIN\n";
    echo "-" . str_repeat("-", 35) . "\n";
    
    try {
        $superAdmin = DB::table('users')->where('id', 1)->first();
        if ($superAdmin) {
            echo "✅ Usuário ID 1: {$superAdmin->name}\n";
            echo "   Email: {$superAdmin->email}\n";
            echo "   Role ID: {$superAdmin->role_id}\n";
            
            // Verificar role
            if ($superAdmin->role_id) {
                $role = DB::table('roles')->where('id', $superAdmin->role_id)->first();
                if ($role) {
                    echo "   Role: {$role->display_name} ({$role->name})\n";
                } else {
                    echo "❌ Role não encontrado\n";
                }
            } else {
                echo "⚠️  Usuário sem role atribuído\n";
            }
        } else {
            echo "❌ Usuário ID 1 não encontrado\n";
        }
    } catch (Exception $e) {
        echo "❌ Erro ao verificar usuário: " . $e->getMessage() . "\n";
    }
    
    // 3. Verificar permissões
    echo "\n3. VERIFICAÇÃO DAS PERMISSÕES\n";
    echo "-" . str_repeat("-", 30) . "\n";
    
    try {
        $permissionCount = DB::table('permissions')->where('is_active', 1)->count();
        echo "✅ Total de permissões ativas: $permissionCount\n";
        
        // Verificar permissões críticas
        $criticalPermissions = [
            'dashboard.view',
            'permissoes.view', 
            'usuarios.view',
            'contratos.view'
        ];
        
        foreach ($criticalPermissions as $permission) {
            $exists = DB::table('permissions')->where('name', $permission)->exists();
            echo ($exists ? "✅" : "❌") . " $permission\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Erro ao verificar permissões: " . $e->getMessage() . "\n";
    }
    
    // 4. Verificar relações role-permission
    echo "\n4. VERIFICAÇÃO DAS RELAÇÕES ROLE-PERMISSION\n";
    echo "-" . str_repeat("-", 40) . "\n";
    
    try {
        $roles = DB::table('roles')->get();
        foreach ($roles as $role) {
            $permissionCount = DB::table('role_permissions')
                ->where('role_id', $role->id)
                ->count();
            echo "✅ {$role->display_name}: $permissionCount permissões\n";
        }
    } catch (Exception $e) {
        echo "❌ Erro ao verificar relações: " . $e->getMessage() . "\n";
    }
    
    // 5. Testar rota de permissões
    echo "\n5. VERIFICAÇÃO DE ROTAS\n";
    echo "-" . str_repeat("-", 22) . "\n";
    
    try {
        $routes = Route::getRoutes();
        
        $routesToCheck = [
            'permissions.index' => 'Lista de permissões',
            'permissions.manage-role' => 'Gerenciar permissões do role',
            'logout.custom' => 'Logout customizado'
        ];
        
        foreach ($routesToCheck as $routeName => $description) {
            if ($routes->hasNamedRoute($routeName)) {
                echo "✅ $description ($routeName)\n";
            } else {
                echo "❌ $description ($routeName) - NÃO ENCONTRADA\n";
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Erro ao verificar rotas: " . $e->getMessage() . "\n";
    }
    
    // 6. Testar middleware
    echo "\n6. VERIFICAÇÃO DE MIDDLEWARE\n";
    echo "-" . str_repeat("-", 27) . "\n";
    
    try {
        $middlewares = [
            'App\Http\Middleware\CheckPermission',
            'App\Http\Middleware\RedirectByRole'
        ];
        
        foreach ($middlewares as $middleware) {
            if (class_exists($middleware)) {
                echo "✅ $middleware\n";
            } else {
                echo "❌ $middleware - NÃO ENCONTRADO\n";
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Erro ao verificar middleware: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERRO CRÍTICO: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n" . str_repeat("=", 45) . "\n";
echo "🏁 VERIFICAÇÃO CONCLUÍDA\n";
echo str_repeat("=", 45) . "\n";

?>
