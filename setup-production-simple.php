<?php

// Script simples para configurar produção
// Execute: php setup-production-simple.php

echo "🚀 Configurando produção (método simples)...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Executar comandos necessários
$commands = [
    'php artisan migrate --force',
    'php artisan db:seed --force',
    'php artisan config:cache',
    'php artisan route:cache',
    'php artisan view:cache'
];

foreach ($commands as $command) {
    echo "\n🔄 Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Saída: $output\n";
}

// 3. Criar usuário Super Admin
echo "\n👤 Criando usuário Super Admin...\n";

$createUserScript = '
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

$superAdminRole = Role::where("name", "super_admin")->first();
if (!$superAdminRole) {
    echo "❌ Role Super Admin não encontrado!" . PHP_EOL;
    exit(1);
}

$user = User::updateOrCreate(
    ["email" => "admin@dspay.com.br"],
    [
        "name" => "Super Admin",
        "password" => Hash::make("admin123456"),
        "role_id" => $superAdminRole->id,
        "is_active" => true,
        "email_verified_at" => now()
    ]
);

echo "✅ Usuário Super Admin criado: " . $user->email . PHP_EOL;
echo "Senha: admin123456" . PHP_EOL;
';

$output = shell_exec("php artisan tinker --execute=\"$createUserScript\" 2>&1");
echo "Resultado: $output\n";

echo "\n🎉 Configuração concluída!\n";
echo "✅ Login: https://srv971263.hstgr.cloud/login\n";
echo "📧 Email: admin@dspay.com.br\n";
echo "🔑 Senha: admin123456\n";
