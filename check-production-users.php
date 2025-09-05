<?php

// Script para verificar usuários em produção
// Execute: php check-production-users.php

echo "🔍 Verificando usuários em PRODUÇÃO...\n";

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
    
    // 4. Importar classes
    use App\Models\User;
    use App\Models\Role;
    use Illuminate\Support\Facades\Hash;
    
    // 5. Listar todos os usuários
    echo "\n📋 Todos os usuários no banco:\n";
    $users = User::with('role')->get();
    
    if ($users->count() == 0) {
        echo "❌ Nenhum usuário encontrado!\n";
        exit(1);
    }
    
    foreach ($users as $user) {
        $roleName = $user->role ? $user->role->display_name : 'Sem role';
        $status = $user->is_active ? 'Ativo' : 'Inativo';
        echo "- ID: {$user->id} | Nome: {$user->name} | Email: {$user->email} | Role: {$roleName} | Status: {$status}\n";
    }
    
    // 6. Verificar roles disponíveis
    echo "\n📋 Roles disponíveis:\n";
    $roles = Role::all();
    foreach ($roles as $role) {
        echo "- ID: {$role->id} | Nome: {$role->name} | Display: {$role->display_name}\n";
    }
    
    // 7. Criar usuário Super Admin correto
    echo "\n👤 Criando usuário Super Admin correto...\n";
    
    $superAdminRole = Role::where('name', 'super_admin')->first();
    if (!$superAdminRole) {
        echo "❌ Role Super Admin não encontrado!\n";
        exit(1);
    }
    
    $name = 'Super Admin';
    $email = 'admin@dspay.com.br';
    $password = 'admin123456';
    
    // Verificar se já existe
    $existingUser = User::where('email', $email)->first();
    
    if ($existingUser) {
        echo "⚠️ Usuário admin@dspay.com.br já existe. Atualizando...\n";
        $existingUser->name = $name;
        $existingUser->password = Hash::make($password);
        $existingUser->role_id = $superAdminRole->id;
        $existingUser->is_active = true;
        $existingUser->email_verified_at = now();
        $existingUser->save();
        $user = $existingUser;
        echo "✅ Usuário atualizado!\n";
    } else {
        echo "🆕 Criando novo usuário Super Admin...\n";
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
    
    // 8. Verificar usuário criado
    $createdUser = User::where('email', $email)->with('role')->first();
    if ($createdUser) {
        echo "\n📋 Usuário Super Admin criado/atualizado:\n";
        echo "- ID: {$createdUser->id}\n";
        echo "- Nome: {$createdUser->name}\n";
        echo "- Email: {$createdUser->email}\n";
        echo "- Role: " . ($createdUser->role ? $createdUser->role->display_name : 'Nenhum') . "\n";
        echo "- Status: " . ($createdUser->is_active ? 'Ativo' : 'Inativo') . "\n";
        echo "- Email verificado: " . ($createdUser->email_verified_at ? 'Sim' : 'Não') . "\n";
        
        // Verificar permissões
        $permissions = $createdUser->getPermissions();
        echo "- Permissões: " . $permissions->count() . " permissões\n";
    }
    
    // 9. Testar login
    echo "\n🧪 Testando login...\n";
    $testUser = User::where('email', $email)->first();
    if ($testUser && Hash::check($password, $testUser->password)) {
        echo "✅ Login testado com sucesso!\n";
    } else {
        echo "❌ Erro no teste de login!\n";
    }
    
    // 10. Listar todos os usuários novamente
    echo "\n📋 Usuários finais:\n";
    $allUsers = User::with('role')->get();
    foreach ($allUsers as $u) {
        echo "- {$u->name} ({$u->email}) - " . 
             ($u->role ? $u->role->display_name : 'Sem role') . 
             " - " . ($u->is_active ? 'Ativo' : 'Inativo') . "\n";
    }
    
    echo "\n🎉 SUCESSO! Usuário Super Admin configurado!\n";
    echo "✅ Login: https://srv971263.hstgr.cloud/login\n";
    echo "📧 Email: $email\n";
    echo "🔑 Senha: $password\n";
    echo "⚠️ ALTERE A SENHA APÓS O PRIMEIRO LOGIN!\n";
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    echo "Stack: " . $e->getTraceAsString() . "\n";
}
