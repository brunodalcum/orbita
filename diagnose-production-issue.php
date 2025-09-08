<?php

echo "🔍 DIAGNÓSTICO DE PRODUÇÃO - VERIFICAÇÃO COMPLETA\n";
echo "=" . str_repeat("=", 50) . "\n\n";

// Verificar se o Laravel está funcionando
echo "1. VERIFICAÇÃO BÁSICA DO LARAVEL\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "✅ Autoload funcionando\n";
    echo "✅ Bootstrap funcionando\n";
} catch (Exception $e) {
    echo "❌ Erro no bootstrap: " . $e->getMessage() . "\n";
    exit(1);
}

// Verificar .env
echo "\n2. VERIFICAÇÃO DO ARQUIVO .env\n";
echo "-" . str_repeat("-", 30) . "\n";

if (file_exists('.env')) {
    echo "✅ Arquivo .env existe\n";
    
    $envContent = file_get_contents('.env');
    $requiredVars = ['APP_KEY', 'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE'];
    
    foreach ($requiredVars as $var) {
        if (strpos($envContent, $var . '=') !== false) {
            $value = env($var);
            if (!empty($value)) {
                echo "✅ $var configurado\n";
            } else {
                echo "⚠️  $var vazio\n";
            }
        } else {
            echo "❌ $var não encontrado\n";
        }
    }
} else {
    echo "❌ Arquivo .env não encontrado\n";
}

// Verificar permissões críticas
echo "\n3. VERIFICAÇÃO DE PERMISSÕES\n";
echo "-" . str_repeat("-", 30) . "\n";

$criticalPaths = [
    'storage/logs',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'bootstrap/cache'
];

foreach ($criticalPaths as $path) {
    if (is_dir($path)) {
        if (is_writable($path)) {
            echo "✅ $path (escrita OK)\n";
        } else {
            echo "❌ $path (sem permissão de escrita)\n";
        }
    } else {
        echo "⚠️  $path (diretório não existe)\n";
    }
}

// Verificar logs de erro
echo "\n4. VERIFICAÇÃO DE LOGS RECENTES\n";
echo "-" . str_repeat("-", 30) . "\n";

$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "✅ Log file existe\n";
    
    // Pegar as últimas 20 linhas
    $lines = file($logFile);
    if ($lines) {
        $recentLines = array_slice($lines, -20);
        echo "📄 Últimas linhas do log:\n";
        foreach ($recentLines as $line) {
            if (stripos($line, 'error') !== false || stripos($line, 'exception') !== false) {
                echo "❌ " . trim($line) . "\n";
            } elseif (stripos($line, 'warning') !== false) {
                echo "⚠️  " . trim($line) . "\n";
            }
        }
    }
} else {
    echo "❌ Log file não encontrado\n";
}

// Verificar cache
echo "\n5. VERIFICAÇÃO DE CACHE\n";
echo "-" . str_repeat("-", 30) . "\n";

$cacheFiles = [
    'bootstrap/cache/config.php' => 'Config cache',
    'bootstrap/cache/routes.php' => 'Routes cache',
    'bootstrap/cache/packages.php' => 'Packages cache'
];

foreach ($cacheFiles as $file => $description) {
    if (file_exists($file)) {
        echo "✅ $description existe\n";
    } else {
        echo "⚠️  $description não existe\n";
    }
}

// Verificar se consegue conectar no banco
echo "\n6. VERIFICAÇÃO DE CONEXÃO COM BANCO\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Tentar uma query simples
    $pdo = DB::connection()->getPdo();
    echo "✅ Conexão com banco funcionando\n";
    
    // Verificar tabelas críticas
    $tables = ['users', 'roles', 'permissions'];
    foreach ($tables as $table) {
        try {
            $count = DB::table($table)->count();
            echo "✅ Tabela '$table' ($count registros)\n";
        } catch (Exception $e) {
            echo "❌ Tabela '$table': " . $e->getMessage() . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erro de conexão com banco: " . $e->getMessage() . "\n";
}

// Verificar rotas críticas
echo "\n7. VERIFICAÇÃO DE ROTAS CRÍTICAS\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    $routes = Route::getRoutes();
    $criticalRoutes = ['login', 'dashboard', 'permissions.index', 'logout.custom'];
    
    foreach ($criticalRoutes as $routeName) {
        if ($routes->hasNamedRoute($routeName)) {
            echo "✅ Rota '$routeName' existe\n";
        } else {
            echo "❌ Rota '$routeName' não encontrada\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Erro ao verificar rotas: " . $e->getMessage() . "\n";
}

// Verificar extensões PHP necessárias
echo "\n8. VERIFICAÇÃO DE EXTENSÕES PHP\n";
echo "-" . str_repeat("-", 30) . "\n";

$requiredExtensions = ['pdo', 'mbstring', 'openssl', 'tokenizer', 'xml', 'json'];
foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ Extensão '$ext' carregada\n";
    } else {
        echo "❌ Extensão '$ext' não encontrada\n";
    }
}

// Verificar espaço em disco
echo "\n9. VERIFICAÇÃO DE ESPAÇO EM DISCO\n";
echo "-" . str_repeat("-", 30) . "\n";

$freeBytes = disk_free_space('.');
$totalBytes = disk_total_space('.');

if ($freeBytes !== false && $totalBytes !== false) {
    $freeGB = round($freeBytes / (1024 * 1024 * 1024), 2);
    $totalGB = round($totalBytes / (1024 * 1024 * 1024), 2);
    $usedPercent = round((($totalBytes - $freeBytes) / $totalBytes) * 100, 1);
    
    echo "💾 Espaço livre: {$freeGB}GB de {$totalGB}GB\n";
    echo "📊 Uso: {$usedPercent}%\n";
    
    if ($freeGB < 1) {
        echo "⚠️  Pouco espaço em disco!\n";
    } else {
        echo "✅ Espaço em disco adequado\n";
    }
} else {
    echo "❌ Não foi possível verificar espaço em disco\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "🏁 DIAGNÓSTICO CONCLUÍDO\n";
echo str_repeat("=", 60) . "\n";

?>
