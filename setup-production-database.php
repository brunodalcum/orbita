<?php

// Script para configurar banco de dados em produção
// Execute: php setup-production-database.php

echo "🚀 Configurando banco de dados em PRODUÇÃO...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // 3. Verificar ambiente
    echo "🌍 Ambiente: " . config('app.env') . "\n";
    echo "🔗 Banco: " . config('database.connections.mysql.database') . "\n";
    
    // 4. Executar migrações
    echo "\n📋 Executando migrações...\n";
    $output = shell_exec('php artisan migrate --force 2>&1');
    echo "Saída das migrações:\n$output\n";
    
    // 5. Executar seeders
    echo "\n🌱 Executando seeders...\n";
    $output = shell_exec('php artisan db:seed --force 2>&1');
    echo "Saída dos seeders:\n$output\n";
    
    // 6. Verificar se funcionou
    echo "\n🔍 Verificando resultado...\n";
    
    // Importar classes
    use App\Models\User;
    use App\Models\Role;
    use App\Models\Permission;
    
    $userCount = User::count();
    $roleCount = Role::count();
    $permissionCount = Permission::count();
    
    echo "✅ Usuários: $userCount\n";
    echo "✅ Roles: $roleCount\n";
    echo "✅ Permissões: $permissionCount\n";
    
    // 7. Listar roles criados
    if ($roleCount > 0) {
        echo "\n📋 Roles criados:\n";
        $roles = Role::all();
        foreach ($roles as $role) {
            echo "- ID: {$role->id} | Nome: {$role->name} | Display: {$role->display_name}\n";
        }
    }
    
    // 8. Criar usuário Super Admin
    echo "\n👤 Criando usuário Super Admin...\n";
    
    $superAdminRole = Role::where('name', 'super_admin')->first();
    if (!$superAdminRole) {
        echo "❌ Role Super Admin não encontrado após seeders!\n";
        exit(1);
    }
    
    $name = 'Super Admin';
    $email = 'admin@dspay.com.br';
    $password = 'admin123456';
    
    // Verificar se já existe
    $existingUser = User::where('email', $email)->first();
    
    if ($existingUser) {
        echo "⚠️ Usuário já existe. Atualizando...\n";
        $existingUser->name = $name;
        $existingUser->password = Hash::make($password);
        $existingUser->role_id = $superAdminRole->id;
        $existingUser->is_active = true;
        $existingUser->save();
        $user = $existingUser;
        echo "✅ Usuário atualizado!\n";
    } else {
        echo "🆕 Criando novo usuário...\n";
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->role_id = $superAdminRole->id;
        $user->is_active = true;
        $user->email_verified_at = now();
        $user->save();
        echo "✅ Usuário criado!\n";
    }
    
    // 9. Verificar usuário criado
    $createdUser = User::where('email', $email)->with('role')->first();
    if ($createdUser) {
        echo "\n📋 Usuário Super Admin criado:\n";
        echo "- ID: {$createdUser->id}\n";
        echo "- Nome: {$createdUser->name}\n";
        echo "- Email: {$createdUser->email}\n";
        echo "- Role: " . ($createdUser->role ? $createdUser->role->display_name : 'Nenhum') . "\n";
        echo "- Status: " . ($createdUser->is_active ? 'Ativo' : 'Inativo') . "\n";
        
        // Verificar permissões
        $permissions = $createdUser->getPermissions();
        echo "- Permissões: " . $permissions->count() . " permissões\n";
    }
    
    // 10. Listar todos os usuários
    echo "\n📋 Todos os usuários:\n";
    $allUsers = User::with('role')->get();
    foreach ($allUsers as $u) {
        echo "- {$u->name} ({$u->email}) - " . 
             ($u->role ? $u->role->display_name : 'Sem role') . 
             " - " . ($u->is_active ? 'Ativo' : 'Inativo') . "\n";
    }
    
    echo "\n🎉 SUCESSO! Banco de dados configurado e usuário Super Admin criado!\n";
    echo "✅ Login: https://srv971263.hstgr.cloud/login\n";
    echo "📧 Email: $email\n";
    echo "🔑 Senha: $password\n";
    echo "⚠️ ALTERE A SENHA APÓS O PRIMEIRO LOGIN!\n";
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    echo "Stack: " . $e->getTraceAsString() . "\n";
}
