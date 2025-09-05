<?php

// Script para corrigir problema da página principal
// Execute: php fix-homepage.php

echo "🔧 Corrigindo problema da página principal...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Verificar configurações atuais
echo "📋 Verificando configurações atuais...\n";
echo "Diretório atual: " . getcwd() . "\n";
echo "APP_ENV: " . (getenv('APP_ENV') ?: 'não definido') . "\n";
echo "APP_DEBUG: " . (getenv('APP_DEBUG') ?: 'não definido') . "\n";

// 3. Verificar arquivo .env
echo "\n⚙️ Verificando arquivo .env...\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    // Verificar configurações importantes
    $importantConfigs = [
        'APP_NAME',
        'APP_ENV',
        'APP_DEBUG',
        'APP_URL',
        'DB_CONNECTION',
        'DB_HOST',
        'DB_PORT',
        'DB_DATABASE',
        'DB_USERNAME',
        'DB_PASSWORD'
    ];
    
    foreach ($importantConfigs as $config) {
        if (preg_match("/^$config=(.+)$/m", $envContent, $matches)) {
            echo "✅ $config: " . $matches[1] . "\n";
        } else {
            echo "❌ $config: não encontrado\n";
        }
    }
} else {
    echo "❌ Arquivo .env não encontrado\n";
}

// 4. Verificar rotas
echo "\n🛣️ Verificando rotas...\n";
$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    $routesContent = file_get_contents($routesFile);
    
    // Verificar se há rota para a página principal
    if (strpos($routesContent, "Route::get('/',") !== false) {
        echo "✅ Rota para página principal encontrada\n";
    } else {
        echo "❌ Rota para página principal não encontrada\n";
    }
    
    // Verificar se há rota para dashboard
    if (strpos($routesContent, "Route::get('/dashboard',") !== false) {
        echo "✅ Rota para dashboard encontrada\n";
    } else {
        echo "❌ Rota para dashboard não encontrada\n";
    }
} else {
    echo "❌ Arquivo routes/web.php não encontrado\n";
}

// 5. Verificar views
echo "\n📄 Verificando views...\n";
$viewsDir = 'resources/views';
if (is_dir($viewsDir)) {
    $views = [
        'welcome.blade.php',
        'dashboard.blade.php',
        'auth/login.blade.php'
    ];
    
    foreach ($views as $view) {
        if (file_exists($viewsDir . '/' . $view)) {
            echo "✅ View encontrada: $view\n";
        } else {
            echo "❌ View não encontrada: $view\n";
        }
    }
} else {
    echo "❌ Diretório resources/views não encontrado\n";
}

// 6. Verificar controllers
echo "\n🎮 Verificando controllers...\n";
$controllersDir = 'app/Http/Controllers';
if (is_dir($controllersDir)) {
    $controllers = [
        'Controller.php',
        'DashboardController.php'
    ];
    
    foreach ($controllers as $controller) {
        if (file_exists($controllersDir . '/' . $controller)) {
            echo "✅ Controller encontrado: $controller\n";
        } else {
            echo "❌ Controller não encontrado: $controller\n";
        }
    }
} else {
    echo "❌ Diretório app/Http/Controllers não encontrado\n";
}

// 7. Verificar banco de dados
echo "\n🗄️ Verificando banco de dados...\n";
try {
    $output = shell_exec('php artisan migrate:status 2>&1');
    if ($output) {
        echo "✅ Comando migrate:status executado\n";
        if (strpos($output, 'No migrations found') !== false) {
            echo "⚠️ Nenhuma migração encontrada\n";
        } else {
            echo "✅ Migrações encontradas\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Erro ao verificar migrações: " . $e->getMessage() . "\n";
}

// 8. Verificar permissões
echo "\n🔐 Verificando permissões...\n";
$paths = [
    'storage',
    'storage/framework',
    'storage/framework/views',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/logs',
    'bootstrap/cache',
    'public'
];

foreach ($paths as $path) {
    if (is_dir($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        echo "✅ $path - Permissões: $perms\n";
    } else {
        echo "❌ $path - Diretório não encontrado\n";
    }
}

// 9. Limpar caches
echo "\n🧹 Limpando caches...\n";
$commands = [
    'php artisan cache:clear',
    'php artisan config:clear',
    'php artisan route:clear',
    'php artisan view:clear'
];

foreach ($commands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "Saída: $output\n";
    }
}

// 10. Regenerar caches
echo "\n🔄 Regenerando caches...\n";
$regenerateCommands = [
    'php artisan config:cache',
    'php artisan route:cache'
];

foreach ($regenerateCommands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "Saída: $output\n";
    }
}

// 11. Verificar se o problema foi resolvido
echo "\n🧪 Testando se o problema foi resolvido...\n";
$testCommands = [
    'php artisan --version',
    'php artisan route:list --path=/',
    'php artisan route:list --path=/dashboard'
];

foreach ($testCommands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "Saída: $output\n";
    }
}

echo "\n🎉 Verificação da página principal concluída!\n";
echo "\n📋 Verificações realizadas:\n";
echo "- Configurações do .env\n";
echo "- Rotas definidas\n";
echo "- Views disponíveis\n";
echo "- Controllers existentes\n";
echo "- Banco de dados\n";
echo "- Permissões\n";
echo "- Caches limpos e regenerados\n";
echo "\n✅ Agora teste:\n";
echo "1. Página principal: https://srv971263.hstgr.cloud/\n";
echo "2. Dashboard: https://srv971263.hstgr.cloud/dashboard\n";
echo "3. Login: https://srv971263.hstgr.cloud/login\n";
echo "\n🔍 Se ainda houver problemas:\n";
echo "1. Verifique os logs: tail -f storage/logs/laravel.log\n";
echo "2. Verifique se o servidor web está rodando\n";
echo "3. Verifique se o PHP está configurado corretamente\n";
echo "4. Execute: chown -R www-data:www-data storage/\n";
echo "5. Execute: chown -R www-data:www-data bootstrap/cache/\n";
