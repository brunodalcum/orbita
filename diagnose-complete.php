<?php

// Script de diagnóstico completo
// Execute: php diagnose-complete.php

echo "🔍 DIAGNÓSTICO COMPLETO DO SISTEMA...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Verificar ambiente
echo "\n🌍 VERIFICANDO AMBIENTE...\n";
echo "Diretório atual: " . getcwd() . "\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Laravel Version: " . (file_exists('composer.json') ? json_decode(file_get_contents('composer.json'), true)['require']['laravel/framework'] ?? 'N/A' : 'N/A') . "\n";

// 3. Verificar arquivos essenciais
echo "\n📁 VERIFICANDO ARQUIVOS ESSENCIAIS...\n";

$essentialFiles = [
    'artisan',
    'composer.json',
    '.env',
    'bootstrap/app.php',
    'routes/web.php',
    'app/Http/Kernel.php',
    'config/app.php'
];

foreach ($essentialFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file - Existe\n";
    } else {
        echo "❌ $file - NÃO EXISTE\n";
    }
}

// 4. Verificar configuração do banco
echo "\n🗄️ VERIFICANDO CONFIGURAÇÃO DO BANCO...\n";

if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    // Extrair configurações do banco
    preg_match('/DB_CONNECTION=(.+)/', $envContent, $matches);
    $dbConnection = isset($matches[1]) ? trim($matches[1]) : 'N/A';
    echo "DB Connection: $dbConnection\n";
    
    preg_match('/DB_HOST=(.+)/', $envContent, $matches);
    $dbHost = isset($matches[1]) ? trim($matches[1]) : 'N/A';
    echo "DB Host: $dbHost\n";
    
    preg_match('/DB_PORT=(.+)/', $envContent, $matches);
    $dbPort = isset($matches[1]) ? trim($matches[1]) : 'N/A';
    echo "DB Port: $dbPort\n";
    
    preg_match('/DB_DATABASE=(.+)/', $envContent, $matches);
    $dbDatabase = isset($matches[1]) ? trim($matches[1]) : 'N/A';
    echo "DB Database: $dbDatabase\n";
    
    preg_match('/DB_USERNAME=(.+)/', $envContent, $matches);
    $dbUsername = isset($matches[1]) ? trim($matches[1]) : 'N/A';
    echo "DB Username: $dbUsername\n";
    
    preg_match('/DB_PASSWORD=(.+)/', $envContent, $matches);
    $dbPassword = isset($matches[1]) ? trim($matches[1]) : 'N/A';
    echo "DB Password: " . (strlen($dbPassword) > 0 ? '***' : 'N/A') . "\n";
}

// 5. Testar conexão com banco
echo "\n🔗 TESTANDO CONEXÃO COM BANCO...\n";

try {
    $testCommand = '
    try {
        $pdo = new PDO("mysql:host=' . $dbHost . ';port=' . $dbPort . ';dbname=' . $dbDatabase . '", "' . $dbUsername . '", "' . $dbPassword . '");
        echo "✅ Conexão com banco OK" . PHP_EOL;
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch();
        echo "✅ Usuários no banco: " . $result["count"] . PHP_EOL;
        
    } catch (Exception $e) {
        echo "❌ Erro na conexão: " . $e->getMessage() . PHP_EOL;
    }
    ';
    
    $output = shell_exec("php -r \"$testCommand\" 2>&1");
    echo "Resultado: $output\n";
    
} catch (Exception $e) {
    echo "❌ Erro ao testar conexão: " . $e->getMessage() . "\n";
}

// 6. Verificar rotas
echo "\n🛣️ VERIFICANDO ROTAS...\n";

$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    echo "Tamanho do arquivo de rotas: " . strlen($content) . " caracteres\n";
    
    // Contar rotas
    $routeCount = substr_count($content, 'Route::');
    echo "Número de rotas: $routeCount\n";
    
    // Verificar rotas específicas
    $specificRoutes = ['dashboard', 'licenciados', 'users'];
    foreach ($specificRoutes as $route) {
        if (strpos($content, $route) !== false) {
            echo "✅ Rota '$route' encontrada\n";
        } else {
            echo "❌ Rota '$route' NÃO encontrada\n";
        }
    }
} else {
    echo "❌ Arquivo de rotas não existe\n";
}

// 7. Verificar middleware
echo "\n🔧 VERIFICANDO MIDDLEWARE...\n";

$middlewareFile = 'bootstrap/app.php';
if (file_exists($middlewareFile)) {
    $content = file_get_contents($middlewareFile);
    
    if (strpos($content, 'auth') !== false) {
        echo "✅ Middleware auth encontrado\n";
    } else {
        echo "❌ Middleware auth NÃO encontrado\n";
    }
    
    if (strpos($content, 'web') !== false) {
        echo "✅ Middleware web encontrado\n";
    } else {
        echo "❌ Middleware web NÃO encontrado\n";
    }
} else {
    echo "❌ Arquivo de middleware não existe\n";
}

// 8. Verificar views
echo "\n📄 VERIFICANDO VIEWS...\n";

$views = [
    'resources/views/dashboard.blade.php',
    'resources/views/dashboard/licenciados.blade.php',
    'resources/views/dashboard/users/index.blade.php'
];

foreach ($views as $view) {
    if (file_exists($view)) {
        echo "✅ $view - Existe\n";
        
        $content = file_get_contents($view);
        if (strpos($content, 'dynamic-sidebar') !== false) {
            echo "   ⚠️ Contém 'dynamic-sidebar'\n";
        } else {
            echo "   ✅ Não contém 'dynamic-sidebar'\n";
        }
    } else {
        echo "❌ $view - NÃO EXISTE\n";
    }
}

// 9. Verificar logs
echo "\n📋 VERIFICANDO LOGS...\n";

$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "✅ Log encontrado: $logFile\n";
    
    $logSize = filesize($logFile);
    echo "Tamanho do log: " . number_format($logSize) . " bytes\n";
    
    // Mostrar últimas 20 linhas
    $output = shell_exec('tail -20 ' . $logFile . ' 2>&1');
    echo "Últimas 20 linhas:\n$output\n";
} else {
    echo "❌ Log não encontrado: $logFile\n";
}

// 10. Verificar permissões
echo "\n🔐 VERIFICANDO PERMISSÕES...\n";

$directories = [
    'storage',
    'storage/framework',
    'storage/framework/views',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        $writable = is_writable($dir) ? 'Sim' : 'Não';
        echo "✅ $dir - Permissões: $perms - Gravável: $writable\n";
    } else {
        echo "❌ $dir - NÃO EXISTE\n";
    }
}

// 11. Testar comandos artisan
echo "\n⚙️ TESTANDO COMANDOS ARTISAN...\n";

$artisanCommands = [
    'php artisan --version',
    'php artisan route:list --name=dashboard',
    'php artisan config:show app.name'
];

foreach ($artisanCommands as $command) {
    echo "\n🔄 Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

// 12. Verificar se há erros de sintaxe
echo "\n🔍 VERIFICANDO ERROS DE SINTAXE...\n";

$phpFiles = [
    'bootstrap/app.php',
    'routes/web.php',
    'config/app.php'
];

foreach ($phpFiles as $file) {
    if (file_exists($file)) {
        $output = shell_exec("php -l $file 2>&1");
        if (strpos($output, 'No syntax errors') !== false) {
            echo "✅ $file - Sem erros de sintaxe\n";
        } else {
            echo "❌ $file - Erro de sintaxe: $output\n";
        }
    }
}

echo "\n🎉 DIAGNÓSTICO COMPLETO CONCLUÍDO!\n";
echo "✅ Verifique os resultados acima para identificar problemas\n";
echo "✅ Se houver erros, execute as correções necessárias\n";
