<?php

echo "🔧 CORREÇÃO DAS TABELAS DE PERMISSÕES EM PRODUÇÃO\n";
echo "=" . str_repeat("=", 55) . "\n\n";

try {
    // Inicializar Laravel
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "✅ Laravel inicializado com sucesso\n\n";
    
    // 1. Verificar conexão com banco
    echo "1. VERIFICANDO CONEXÃO COM BANCO\n";
    echo "-" . str_repeat("-", 33) . "\n";
    
    try {
        $pdo = DB::connection()->getPdo();
        echo "✅ Conexão com banco MySQL funcionando\n";
        echo "   Database: " . DB::connection()->getDatabaseName() . "\n\n";
    } catch (Exception $e) {
        echo "❌ Erro de conexão: " . $e->getMessage() . "\n";
        exit(1);
    }
    
    // 2. Verificar tabelas existentes
    echo "2. VERIFICANDO TABELAS EXISTENTES\n";
    echo "-" . str_repeat("-", 33) . "\n";
    
    $requiredTables = [
        'users' => 'Usuários',
        'roles' => 'Perfis/Roles',
        'permissions' => 'Permissões',
        'role_permissions' => 'Relação Role-Permissão'
    ];
    
    $missingTables = [];
    
    foreach ($requiredTables as $table => $description) {
        try {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                echo "✅ $description ($table): $count registros\n";
            } else {
                echo "❌ $description ($table): NÃO EXISTE\n";
                $missingTables[] = $table;
            }
        } catch (Exception $e) {
            echo "❌ Erro ao verificar '$table': " . $e->getMessage() . "\n";
            $missingTables[] = $table;
        }
    }
    
    echo "\n";
    
    // 3. Executar migrações se necessário
    if (!empty($missingTables)) {
        echo "3. EXECUTANDO MIGRAÇÕES NECESSÁRIAS\n";
        echo "-" . str_repeat("-", 35) . "\n";
        
        echo "🔧 Executando: php artisan migrate --force\n";
        
        $output = [];
        $returnCode = 0;
        exec('php artisan migrate --force 2>&1', $output, $returnCode);
        
        if ($returnCode === 0) {
            echo "✅ Migrações executadas com sucesso\n";
            foreach ($output as $line) {
                if (strpos($line, 'Migrated:') !== false || strpos($line, 'Nothing to migrate') !== false) {
                    echo "   $line\n";
                }
            }
        } else {
            echo "❌ Erro nas migrações:\n";
            foreach ($output as $line) {
                echo "   $line\n";
            }
        }
        echo "\n";
    } else {
        echo "3. MIGRAÇÕES\n";
        echo "-" . str_repeat("-", 10) . "\n";
        echo "✅ Todas as tabelas já existem\n\n";
    }
    
    // 4. Verificar novamente após migrações
    echo "4. VERIFICAÇÃO FINAL DAS TABELAS\n";
    echo "-" . str_repeat("-", 33) . "\n";
    
    $allTablesExist = true;
    foreach ($requiredTables as $table => $description) {
        try {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                echo "✅ $description ($table): $count registros\n";
            } else {
                echo "❌ $description ($table): AINDA NÃO EXISTE\n";
                $allTablesExist = false;
            }
        } catch (Exception $e) {
            echo "❌ Erro ao verificar '$table': " . $e->getMessage() . "\n";
            $allTablesExist = false;
        }
    }
    
    echo "\n";
    
    // 5. Popular permissões se as tabelas existirem mas estiverem vazias
    if ($allTablesExist) {
        echo "5. VERIFICANDO E POPULANDO PERMISSÕES\n";
        echo "-" . str_repeat("-", 37) . "\n";
        
        $permissionCount = DB::table('permissions')->count();
        $roleCount = DB::table('roles')->count();
        
        echo "   Permissões existentes: $permissionCount\n";
        echo "   Roles existentes: $roleCount\n";
        
        if ($permissionCount == 0 || $roleCount == 0) {
            echo "🔧 Executando seeders...\n";
            
            // Executar seeder de roles
            $output = [];
            exec('php artisan db:seed --class=RoleSeeder --force 2>&1', $output, $returnCode);
            if ($returnCode === 0) {
                echo "✅ RoleSeeder executado\n";
            } else {
                echo "⚠️  Erro no RoleSeeder: " . implode(', ', $output) . "\n";
            }
            
            // Executar seeder de permissions
            $output = [];
            exec('php artisan db:seed --class=PermissionSeeder --force 2>&1', $output, $returnCode);
            if ($returnCode === 0) {
                echo "✅ PermissionSeeder executado\n";
            } else {
                echo "⚠️  Erro no PermissionSeeder: " . implode(', ', $output) . "\n";
            }
        } else {
            echo "✅ Dados já existem, não é necessário popular\n";
        }
        echo "\n";
    }
    
    // 6. Verificar usuário Super Admin
    echo "6. VERIFICANDO USUÁRIO SUPER ADMIN\n";
    echo "-" . str_repeat("-", 32) . "\n";
    
    try {
        $user = DB::table('users')->where('id', 1)->first();
        if ($user) {
            echo "✅ Usuário encontrado: {$user->name} ({$user->email})\n";
            
            if ($user->role_id) {
                $role = DB::table('roles')->where('id', $user->role_id)->first();
                if ($role) {
                    echo "✅ Role: {$role->display_name} ({$role->name})\n";
                } else {
                    echo "⚠️  Role ID {$user->role_id} não encontrado\n";
                }
            } else {
                echo "⚠️  Usuário sem role - atribuindo Super Admin...\n";
                $superAdminRole = DB::table('roles')->where('name', 'super_admin')->first();
                if ($superAdminRole) {
                    DB::table('users')->where('id', 1)->update(['role_id' => $superAdminRole->id]);
                    echo "✅ Role Super Admin atribuído\n";
                } else {
                    echo "❌ Role Super Admin não encontrado\n";
                }
            }
        } else {
            echo "❌ Usuário ID 1 não encontrado\n";
        }
    } catch (Exception $e) {
        echo "❌ Erro ao verificar usuário: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
    
    // 7. Teste final da query problemática
    echo "7. TESTANDO QUERY QUE ESTAVA FALHANDO\n";
    echo "-" . str_repeat("-", 37) . "\n";
    
    try {
        $exists = DB::table('permissions')
            ->join('role_permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->where('role_permissions.role_id', 1)
            ->where('permissions.name', 'dashboard.view')
            ->exists();
            
        echo "✅ Query funcionando! Resultado: " . ($exists ? 'true' : 'false') . "\n";
        
    } catch (Exception $e) {
        echo "❌ Query ainda falhando: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERRO CRÍTICO: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n" . str_repeat("=", 55) . "\n";
echo "🏁 CORREÇÃO CONCLUÍDA\n";
echo "📋 Agora teste acessar: /dashboard ou /permissions\n";
echo str_repeat("=", 55) . "\n";

?>
