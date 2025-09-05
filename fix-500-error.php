<?php

// Script para corrigir erro 500 em produção
// Execute: php fix-500-error.php

echo "🔧 Corrigindo erro 500 em PRODUÇÃO...\n";

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
    'php artisan cache:clear',
    'php artisan optimize:clear'
];

// 3. Executar comandos de limpeza
foreach ($commands as $command) {
    echo "\n🔄 Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

// 4. Verificar logs de erro
echo "\n📋 Verificando logs de erro...\n";

$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "✅ Log encontrado: $logFile\n";
    
    // Mostrar últimas 20 linhas do log
    $output = shell_exec('tail -20 ' . $logFile . ' 2>&1');
    echo "Últimas 20 linhas do log:\n$output\n";
} else {
    echo "❌ Log não encontrado: $logFile\n";
}

// 5. Verificar permissões
echo "\n🔐 Verificando permissões...\n";

$directories = [
    'storage/framework/views',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        echo "✅ $dir - Permissões: $perms\n";
    } else {
        echo "❌ $dir - NÃO EXISTE\n";
    }
}

// 6. Recriar cache básico
echo "\n🗂️ Recriando cache básico...\n";

$cacheCommands = [
    'php artisan config:cache',
    'php artisan route:cache'
];

foreach ($cacheCommands as $command) {
    echo "\n🔄 Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

// 7. Verificar se o componente está funcionando
echo "\n🧪 Testando componente...\n";

$testCommand = '
try {
    $user = App\Models\User::where("email", "admin@dspay.com.br")->with("role")->first();
    if ($user) {
        echo "Usuário encontrado: " . $user->name . PHP_EOL;
        echo "Role: " . ($user->role ? $user->role->display_name : "Nenhum") . PHP_EOL;
        
        $component = new App\View\Components\DynamicSidebar($user);
        $view = $component->render();
        echo "✅ Componente funcionando!" . PHP_EOL;
    } else {
        echo "❌ Usuário não encontrado!" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . PHP_EOL;
}
';

$output = shell_exec("php artisan tinker --execute=\"$testCommand\" 2>&1");
echo "Resultado: $output\n";

echo "\n🎉 Correção concluída!\n";
echo "✅ Teste as rotas em: https://srv971263.hstgr.cloud/dashboard\n";
