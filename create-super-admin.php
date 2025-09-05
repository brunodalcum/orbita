<?php

// Script para criar usuário Super Admin em produção
// Execute: php create-super-admin.php

echo "🔧 Criando usuário Super Admin em produção...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Verificar se o banco de dados está configurado
echo "📋 Verificando configurações do banco...\n";
try {
    $output = shell_exec('php artisan migrate:status 2>&1');
    if (strpos($output, 'No migrations found') !== false) {
        echo "❌ Nenhuma migração encontrada. Execute as migrações primeiro.\n";
        exit(1);
    }
    echo "✅ Banco de dados configurado\n";
} catch (Exception $e) {
    echo "❌ Erro ao verificar banco de dados: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Verificar se as tabelas de roles e permissions existem
echo "\n🔍 Verificando tabelas de roles e permissions...\n";
try {
    $output = shell_exec('php artisan tinker --execute="echo \'Roles: \' . App\Models\Role::count() . PHP_EOL; echo \'Permissions: \' . App\Models\Permission::count() . PHP_EOL;" 2>&1');
    echo "Saída: $output\n";
} catch (Exception $e) {
    echo "❌ Erro ao verificar tabelas: " . $e->getMessage() . "\n";
    echo "⚠️ Execute as migrações e seeders primeiro:\n";
    echo "   php artisan migrate --force\n";
    echo "   php artisan db:seed --force\n";
    exit(1);
}

// 4. Criar usuário Super Admin
echo "\n👤 Criando usuário Super Admin...\n";

// Dados do usuário (você pode alterar aqui)
$userData = [
    'name' => 'Super Admin',
    'email' => 'admin@dspay.com.br',
    'password' => 'admin123456', // Senha temporária - deve ser alterada
    'role_id' => 1, // ID do role Super Admin
    'is_active' => true
];

// Script para criar o usuário
$createUserScript = "
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

// Verificar se o usuário já existe
\$existingUser = User::where('email', '{$userData['email']}')->first();
if (\$existingUser) {
    echo '⚠️ Usuário já existe: ' . \$existingUser->email . PHP_EOL;
    echo 'Atualizando para Super Admin...' . PHP_EOL;
    
    // Atualizar role para Super Admin
    \$superAdminRole = Role::where('name', 'super_admin')->first();
    if (\$superAdminRole) {
        \$existingUser->role_id = \$superAdminRole->id;
        \$existingUser->is_active = true;
        \$existingUser->save();
        echo '✅ Usuário atualizado para Super Admin' . PHP_EOL;
    } else {
        echo '❌ Role Super Admin não encontrado' . PHP_EOL;
    }
} else {
    // Criar novo usuário
    \$superAdminRole = Role::where('name', 'super_admin')->first();
    if (!\$superAdminRole) {
        echo '❌ Role Super Admin não encontrado' . PHP_EOL;
        exit(1);
    }
    
    \$user = new User();
    \$user->name = '{$userData['name']}';
    \$user->email = '{$userData['email']}';
    \$user->password = Hash::make('{$userData['password']}');
    \$user->role_id = \$superAdminRole->id;
    \$user->is_active = true;
    \$user->email_verified_at = now();
    \$user->save();
    
    echo '✅ Usuário Super Admin criado com sucesso!' . PHP_EOL;
    echo 'Email: {$userData['email']}' . PHP_EOL;
    echo 'Senha: {$userData['password']}' . PHP_EOL;
    echo '⚠️ IMPORTANTE: Altere a senha após o primeiro login!' . PHP_EOL;
}

// Verificar permissões
\$user = User::where('email', '{$userData['email']}')->first();
if (\$user) {
    echo PHP_EOL . '🔐 Verificando permissões...' . PHP_EOL;
    echo 'Role: ' . (\$user->role ? \$user->role->display_name : 'Nenhum') . PHP_EOL;
    echo 'Permissões: ' . \$user->getPermissions()->count() . ' permissões' . PHP_EOL;
    echo 'Status: ' . (\$user->is_active ? 'Ativo' : 'Inativo') . PHP_EOL;
}
";

// Executar o script
echo "Executando script de criação...\n";
$output = shell_exec("php artisan tinker --execute=\"$createUserScript\" 2>&1");
echo "Saída: $output\n";

// 5. Verificar se o usuário foi criado
echo "\n🧪 Verificando se o usuário foi criado...\n";
$verifyScript = "
use App\Models\User;
\$user = User::where('email', '{$userData['email']}')->first();
if (\$user) {
    echo '✅ Usuário encontrado: ' . \$user->name . PHP_EOL;
    echo 'Email: ' . \$user->email . PHP_EOL;
    echo 'Role: ' . (\$user->role ? \$user->role->display_name : 'Nenhum') . PHP_EOL;
    echo 'Status: ' . (\$user->is_active ? 'Ativo' : 'Inativo') . PHP_EOL;
    echo 'Criado em: ' . \$user->created_at . PHP_EOL;
} else {
    echo '❌ Usuário não encontrado' . PHP_EOL;
}
";

$verifyOutput = shell_exec("php artisan tinker --execute=\"$verifyScript\" 2>&1");
echo "Verificação: $verifyOutput\n";

// 6. Listar todos os usuários
echo "\n📋 Listando todos os usuários...\n";
$listScript = "
use App\Models\User;
\$users = User::with('role')->get();
echo 'Total de usuários: ' . \$users->count() . PHP_EOL;
foreach (\$users as \$user) {
    echo '- ' . \$user->name . ' (' . \$user->email . ') - ' . (\$user->role ? \$user->role->display_name : 'Sem role') . ' - ' . (\$user->is_active ? 'Ativo' : 'Inativo') . PHP_EOL;
}
";

$listOutput = shell_exec("php artisan tinker --execute=\"$listScript\" 2>&1");
echo "Lista de usuários: $listOutput\n";

echo "\n🎉 Processo concluído!\n";
echo "\n📋 Resumo:\n";
echo "- Usuário Super Admin criado/atualizado\n";
echo "- Email: {$userData['email']}\n";
echo "- Senha: {$userData['password']}\n";
echo "- Role: Super Admin\n";
echo "- Status: Ativo\n";
echo "\n⚠️ IMPORTANTE:\n";
echo "1. Altere a senha após o primeiro login\n";
echo "2. Configure autenticação de dois fatores\n";
echo "3. Mantenha as credenciais seguras\n";
echo "\n✅ Agora você pode fazer login em:\n";
echo "https://srv971263.hstgr.cloud/login\n";
