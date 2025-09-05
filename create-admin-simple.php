<?php

// Script simples para criar usuário Super Admin
// Execute: php create-admin-simple.php

echo "🔧 Criando usuário Super Admin (método simples)...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Dados do usuário
$name = 'Super Admin';
$email = 'admin@dspay.com.br';
$password = 'admin123456';

echo "📋 Dados do usuário:\n";
echo "- Nome: $name\n";
echo "- Email: $email\n";
echo "- Senha: $password\n";

// 3. Script para criar o usuário
$script = "
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

// Verificar se o usuário já existe
\$user = User::where('email', '$email')->first();

if (\$user) {
    echo '⚠️ Usuário já existe. Atualizando...' . PHP_EOL;
    
    // Atualizar senha e role
    \$user->password = Hash::make('$password');
    \$user->role_id = 1; // Super Admin
    \$user->is_active = true;
    \$user->save();
    
    echo '✅ Usuário atualizado!' . PHP_EOL;
} else {
    // Criar novo usuário
    \$user = new User();
    \$user->name = '$name';
    \$user->email = '$email';
    \$user->password = Hash::make('$password');
    \$user->role_id = 1; // Super Admin
    \$user->is_active = true;
    \$user->email_verified_at = now();
    \$user->save();
    
    echo '✅ Usuário criado com sucesso!' . PHP_EOL;
}

echo 'Email: $email' . PHP_EOL;
echo 'Senha: $password' . PHP_EOL;
echo '⚠️ Altere a senha após o primeiro login!' . PHP_EOL;
";

// 4. Executar o script
echo "\n🚀 Executando criação do usuário...\n";
$output = shell_exec("php artisan tinker --execute=\"$script\" 2>&1");
echo "Resultado: $output\n";

echo "\n🎉 Usuário Super Admin criado!\n";
echo "✅ Faça login em: https://srv971263.hstgr.cloud/login\n";
echo "📧 Email: $email\n";
echo "🔑 Senha: $password\n";
echo "⚠️ Lembre-se de alterar a senha!\n";
