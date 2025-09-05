<?php

// Script de emergência para corrigir erro 500
// Execute: php emergency-fix-500.php

echo "🚨 CORREÇÃO DE EMERGÊNCIA - ERRO 500...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Limpar TUDO
echo "\n🗑️ Limpando TUDO...\n";

$commands = [
    'php artisan view:clear',
    'php artisan config:clear',
    'php artisan route:clear',
    'php artisan cache:clear',
    'php artisan optimize:clear',
    'php artisan event:clear',
    'php artisan queue:clear'
];

foreach ($commands as $command) {
    echo "\n🔄 Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

// 3. Remover arquivos problemáticos
echo "\n🗑️ Removendo arquivos problemáticos...\n";

$files = [
    'app/View/Components/DynamicSidebar.php',
    'resources/views/components/dynamic-sidebar.blade.php',
    'resources/views/components/static-sidebar.blade.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "✅ Removido: $file\n";
    } else {
        echo "❌ Não encontrado: $file\n";
    }
}

// 4. Limpar cache manualmente
echo "\n🗑️ Limpando cache manualmente...\n";

$cacheDirs = [
    'storage/framework/views',
    'storage/framework/cache',
    'storage/framework/sessions',
    'bootstrap/cache'
];

foreach ($cacheDirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "✅ Limpo: $dir\n";
    } else {
        echo "❌ Não encontrado: $dir\n";
    }
}

// 5. Verificar logs de erro
echo "\n📋 Verificando logs de erro...\n";

$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "✅ Log encontrado: $logFile\n";
    
    // Mostrar últimas 30 linhas do log
    $output = shell_exec('tail -30 ' . $logFile . ' 2>&1');
    echo "Últimas 30 linhas do log:\n$output\n";
} else {
    echo "❌ Log não encontrado: $logFile\n";
}

// 6. Verificar permissões
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

// 7. Corrigir permissões
echo "\n🔐 Corrigindo permissões...\n";

$permissionCommands = [
    'chmod -R 755 storage/',
    'chmod -R 755 bootstrap/cache/',
    'chown -R www-data:www-data storage/',
    'chown -R www-data:www-data bootstrap/cache/'
];

foreach ($permissionCommands as $command) {
    echo "\n🔄 Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

// 8. Recriar cache básico
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

// 9. Testar se o sistema está funcionando
echo "\n🧪 Testando sistema...\n";

$testCommand = '
try {
    echo "Testando conexão com banco..." . PHP_EOL;
    $user = App\Models\User::where("email", "admin@dspay.com.br")->first();
    if ($user) {
        echo "✅ Usuário encontrado: " . $user->name . PHP_EOL;
    } else {
        echo "❌ Usuário não encontrado!" . PHP_EOL;
    }
    
    echo "Testando rotas..." . PHP_EOL;
    $routes = [
        "dashboard" => route("dashboard"),
        "licenciados" => route("dashboard.licenciados"),
        "users" => route("dashboard.users.index")
    ];
    
    foreach ($routes as $name => $url) {
        echo "✅ Rota $name: $url" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . PHP_EOL;
}
';

$output = shell_exec("php artisan tinker --execute=\"$testCommand\" 2>&1");
echo "Resultado: $output\n";

// 10. Verificar se as views existem
echo "\n📄 Verificando views...\n";

$views = [
    'resources/views/dashboard.blade.php',
    'resources/views/dashboard/licenciados.blade.php',
    'resources/views/dashboard/users/index.blade.php'
];

foreach ($views as $view) {
    if (file_exists($view)) {
        echo "✅ $view - Existe\n";
        
        // Verificar se usa sidebar dinâmico
        $content = file_get_contents($view);
        if (strpos($content, '<x-dynamic-sidebar') !== false) {
            echo "   ⚠️ Usa <x-dynamic-sidebar> - Pode causar erro\n";
        } else {
            echo "   ✅ Não usa sidebar dinâmico\n";
        }
    } else {
        echo "❌ $view - NÃO EXISTE\n";
    }
}

echo "\n🎉 Correção de emergência concluída!\n";
echo "✅ Teste as rotas em: https://srv971263.hstgr.cloud/dashboard\n";
echo "✅ Se ainda houver erro, verifique os logs acima\n";
