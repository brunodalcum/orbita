<?php

// Script simples para corrigir sidebar em produção
// Execute: php fix-sidebar-simple.php

echo "🔧 Corrigindo sidebar em PRODUÇÃO (método simples)...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Comandos para executar
$commands = [
    'php artisan view:clear',
    'php artisan config:clear',
    'php artisan route:clear',
    'php artisan config:cache',
    'php artisan route:cache',
    'php artisan view:cache'
];

// 3. Executar comandos
foreach ($commands as $command) {
    echo "\n🔄 Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

// 4. Verificar se o componente está funcionando
echo "\n🧪 Testando componente...\n";

$testCommand = '
use App\Models\User;
use App\View\Components\DynamicSidebar;

$user = User::where("email", "admin@dspay.com.br")->with("role")->first();
if ($user) {
    echo "Usuário encontrado: " . $user->name . PHP_EOL;
    echo "Role: " . ($user->role ? $user->role->display_name : "Nenhum") . PHP_EOL;
    
    try {
        $component = new DynamicSidebar($user);
        $view = $component->render();
        echo "✅ Componente funcionando!" . PHP_EOL;
    } catch (Exception $e) {
        echo "❌ Erro no componente: " . $e->getMessage() . PHP_EOL;
    }
} else {
    echo "❌ Usuário não encontrado!" . PHP_EOL;
}
';

$output = shell_exec("php artisan tinker --execute=\"$testCommand\" 2>&1");
echo "Resultado: $output\n";

echo "\n🎉 Script executado!\n";
echo "✅ Teste o sidebar em: https://srv971263.hstgr.cloud/dashboard\n";
