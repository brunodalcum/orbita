<?php

// Script final para criar usuário Super Admin em produção
// Execute: php create-super-admin-production.php

echo "🔧 Criando usuário Super Admin em produção...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// 3. Dados do usuário
$name = 'Super Admin';
$email = 'admin@dspay.com.br';
$password = 'admin123456';

echo "📋 Dados do usuário:\n";
echo "- Nome: $name\n";
echo "- Email: $email\n";
echo "- Senha: $password\n";

// 4. Importar classes necessárias
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

try {
    // 5. Verificar se as tabelas existem
    echo "\n🔍 Verificando estrutura do banco...\n";
    
    $userCount = User::count();
    echo "✅ Tabela users: $userCount usuários\n";
    
    $roleCount = Role::count();
    echo "✅ Tabela roles: $roleCount roles\n";
    
    // 6. Verificar se o role Super Admin existe
    $superAdminRole = Role::where('name', 'super_admin')->first();
    if (!$superAdminRole) {
        echo "❌ Role Super Admin não encontrado!\n";
        echo "Execute as migrações e seeders primeiro:\n";
        echo "php artisan migrate --force\n";
        echo "php artisan db:seed --force\n";
        exit(1);
    }
    echo "✅ Role Super Admin encontrado (ID: {$superAdminRole->id})\n";

    // 7. Verificar se o usuário já existe
    $user = User::where('email', $email)->first();

    if ($user) {
        echo "\n⚠️ Usuário já existe. Atualizando...\n";
        
        // Atualizar senha e role
        $user->password = Hash::make($password);
        $user->role_id = $superAdminRole->id;
        $user->is_active = true;
        $user->save();
        
        echo "✅ Usuário atualizado!\n";
    } else {
        echo "\n👤 Criando novo usuário...\n";
        
        // Criar novo usuário
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->role_id = $superAdminRole->id;
        $user->is_active = true;
        $user->email_verified_at = now();
        $user->save();
        
        echo "✅ Usuário criado com sucesso!\n";
    }

    // 8. Verificar se foi criado/atualizado
    $createdUser = User::where('email', $email)->with('role')->first();
    if ($createdUser) {
        echo "\n📋 Informações do usuário:\n";
        echo "- ID: " . $createdUser->id . "\n";
        echo "- Nome: " . $createdUser->name . "\n";
        echo "- Email: " . $createdUser->email . "\n";
        echo "- Role ID: " . $createdUser->role_id . "\n";
        echo "- Role: " . ($createdUser->role ? $createdUser->role->display_name : 'Nenhum') . "\n";
        echo "- Status: " . ($createdUser->is_active ? 'Ativo' : 'Inativo') . "\n";
        echo "- Criado em: " . $createdUser->created_at . "\n";
        
        // Verificar permissões
        $permissions = $createdUser->getPermissions();
        echo "- Permissões: " . $permissions->count() . " permissões\n";
    }

    // 9. Listar todos os usuários
    echo "\n📋 Lista de todos os usuários:\n";
    $allUsers = User::with('role')->get();
    foreach ($allUsers as $u) {
        echo "- " . $u->name . " (" . $u->email . ") - " . 
             ($u->role ? $u->role->display_name : 'Sem role') . 
             " - " . ($u->is_active ? 'Ativo' : 'Inativo') . "\n";
    }

    echo "\n🎉 Usuário Super Admin criado com sucesso!\n";
    echo "✅ Faça login em: https://srv971263.hstgr.cloud/login\n";
    echo "📧 Email: $email\n";
    echo "🔑 Senha: $password\n";
    echo "⚠️ IMPORTANTE: Altere a senha após o primeiro login!\n";
    echo "\n🔐 Dicas de segurança:\n";
    echo "- Use uma senha forte\n";
    echo "- Configure autenticação de dois fatores\n";
    echo "- Mantenha as credenciais seguras\n";

} catch (Exception $e) {
    echo "❌ Erro ao criar usuário: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    
    echo "\n🆘 Soluções possíveis:\n";
    echo "1. Execute as migrações: php artisan migrate --force\n";
    echo "2. Execute os seeders: php artisan db:seed --force\n";
    echo "3. Verifique as configurações do banco no .env\n";
    echo "4. Verifique se o banco de dados está rodando\n";
}
