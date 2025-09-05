<?php

// Script final para criar usuário Super Admin em produção
// Execute: php create-admin-production-final.php

echo "🚀 Criando usuário Super Admin em PRODUÇÃO...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// 3. Importar classes necessárias
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

try {
    // 4. Verificar ambiente
    echo "🌍 Ambiente: " . config('app.env') . "\n";
    echo "🔗 Banco: " . config('database.connections.mysql.database') . "\n";
    
    // 5. Verificar se as tabelas existem
    echo "\n📋 Verificando estrutura...\n";
    $userCount = User::count();
    $roleCount = Role::count();
    echo "✅ Usuários: $userCount\n";
    echo "✅ Roles: $roleCount\n";
    
    // 6. Verificar role Super Admin
    $superAdminRole = Role::where('name', 'super_admin')->first();
    if (!$superAdminRole) {
        echo "❌ Role Super Admin não encontrado!\n";
        echo "Execute: php artisan db:seed --force\n";
        exit(1);
    }
    echo "✅ Role Super Admin: {$superAdminRole->display_name} (ID: {$superAdminRole->id})\n";
    
    // 7. Dados do usuário
    $name = 'Super Admin';
    $email = 'admin@dspay.com.br';
    $password = 'admin123456';
    
    echo "\n👤 Criando usuário...\n";
    echo "- Nome: $name\n";
    echo "- Email: $email\n";
    echo "- Senha: $password\n";
    
    // 8. Verificar se já existe
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
    
    // 9. Verificar criação
    $createdUser = User::where('email', $email)->with('role')->first();
    if ($createdUser) {
        echo "\n📋 Usuário criado com sucesso:\n";
        echo "- ID: {$createdUser->id}\n";
        echo "- Nome: {$createdUser->name}\n";
        echo "- Email: {$createdUser->email}\n";
        echo "- Role: " . ($createdUser->role ? $createdUser->role->display_name : 'Nenhum') . "\n";
        echo "- Status: " . ($createdUser->is_active ? 'Ativo' : 'Inativo') . "\n";
        echo "- Criado: {$createdUser->created_at}\n";
        
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
    
    echo "\n🎉 SUCESSO! Usuário Super Admin criado em produção!\n";
    echo "✅ Login: https://srv971263.hstgr.cloud/login\n";
    echo "📧 Email: $email\n";
    echo "🔑 Senha: $password\n";
    echo "⚠️ ALTERE A SENHA APÓS O PRIMEIRO LOGIN!\n";
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    echo "Stack: " . $e->getTraceAsString() . "\n";
}
