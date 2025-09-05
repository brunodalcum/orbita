<?php

// Script para verificar problemas fundamentais
// Execute: php check-fundamental-issues.php

echo "🔍 VERIFICANDO PROBLEMAS FUNDAMENTAIS...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Verificar se o Laravel está funcionando
echo "\n🔧 VERIFICANDO SE O LARAVEL ESTÁ FUNCIONANDO...\n";

$versionCommand = 'php artisan --version 2>&1';
$output = shell_exec($versionCommand);
echo "Versão do Laravel: $output\n";

if (strpos($output, 'Laravel Framework') !== false) {
    echo "✅ Laravel está funcionando\n";
} else {
    echo "❌ Laravel NÃO está funcionando\n";
    echo "❌ Este é o problema principal!\n";
    exit(1);
}

// 3. Verificar se o banco está funcionando
echo "\n🗄️ VERIFICANDO SE O BANCO ESTÁ FUNCIONANDO...\n";

$dbTestCommand = '
try {
    $pdo = new PDO("mysql:host=localhost;port=3306;dbname=orbita", "root", "root");
    echo "✅ Conexão com banco OK" . PHP_EOL;
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    echo "✅ Usuários no banco: " . $result["count"] . PHP_EOL;
    
} catch (Exception $e) {
    echo "❌ Erro na conexão: " . $e->getMessage() . PHP_EOL;
}
';

$output = shell_exec("php -r \"$dbTestCommand\" 2>&1");
echo "Resultado: $output\n";

// 4. Verificar se as rotas estão funcionando
echo "\n🛣️ VERIFICANDO SE AS ROTAS ESTÃO FUNCIONANDO...\n";

$routeCommand = 'php artisan route:list --name=dashboard 2>&1';
$output = shell_exec($routeCommand);
echo "Rotas do dashboard:\n$output\n";

if (strpos($output, 'dashboard') !== false) {
    echo "✅ Rotas do dashboard estão funcionando\n";
} else {
    echo "❌ Rotas do dashboard NÃO estão funcionando\n";
}

// 5. Verificar se o problema é com o servidor web
echo "\n🌐 VERIFICANDO SE O SERVIDOR WEB ESTÁ FUNCIONANDO...\n";

$serverTestCommand = '
try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:8001/dashboard");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "❌ Erro cURL: " . $error . PHP_EOL;
    } else {
        echo "✅ HTTP Code: " . $httpCode . PHP_EOL;
        if ($httpCode == 200) {
            echo "✅ Servidor web está funcionando\n";
        } else {
            echo "❌ Servidor web retornou erro: " . $httpCode . PHP_EOL;
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erro ao testar servidor: " . $e->getMessage() . PHP_EOL;
}
';

$output = shell_exec("php -r \"$serverTestCommand\" 2>&1");
echo "Resultado: $output\n";

// 6. Verificar se o problema é com o PHP
echo "\n🐘 VERIFICANDO SE O PHP ESTÁ FUNCIONANDO...\n";

$phpTestCommand = '
echo "✅ PHP Version: " . PHP_VERSION . PHP_EOL;
echo "✅ PHP SAPI: " . php_sapi_name() . PHP_EOL;
echo "✅ PHP Memory Limit: " . ini_get("memory_limit") . PHP_EOL;
echo "✅ PHP Max Execution Time: " . ini_get("max_execution_time") . PHP_EOL;
echo "✅ PHP Extensions: " . implode(", ", get_loaded_extensions()) . PHP_EOL;
';

$output = shell_exec("php -r \"$phpTestCommand\" 2>&1");
echo "Resultado: $output\n";

// 7. Verificar se o problema é com o Composer
echo "\n📦 VERIFICANDO SE O COMPOSER ESTÁ FUNCIONANDO...\n";

$composerCommand = 'composer --version 2>&1';
$output = shell_exec($composerCommand);
echo "Versão do Composer: $output\n";

if (strpos($output, 'Composer version') !== false) {
    echo "✅ Composer está funcionando\n";
} else {
    echo "❌ Composer NÃO está funcionando\n";
}

// 8. Verificar se o problema é com as dependências
echo "\n📦 VERIFICANDO SE AS DEPENDÊNCIAS ESTÃO FUNCIONANDO...\n";

$depsCommand = 'composer show --installed 2>&1';
$output = shell_exec($depsCommand);
echo "Dependências instaladas:\n$output\n";

if (strpos($output, 'laravel/framework') !== false) {
    echo "✅ Laravel Framework está instalado\n";
} else {
    echo "❌ Laravel Framework NÃO está instalado\n";
}

// 9. Verificar se o problema é com o .env
echo "\n⚙️ VERIFICANDO SE O .ENV ESTÁ FUNCIONANDO...\n";

if (file_exists('.env')) {
    echo "✅ Arquivo .env existe\n";
    
    $envContent = file_get_contents('.env');
    
    // Verificar configurações essenciais
    $requiredConfigs = ['APP_NAME', 'APP_ENV', 'APP_KEY', 'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE'];
    
    foreach ($requiredConfigs as $config) {
        if (strpos($envContent, $config) !== false) {
            echo "✅ $config configurado\n";
        } else {
            echo "❌ $config NÃO configurado\n";
        }
    }
} else {
    echo "❌ Arquivo .env NÃO existe\n";
}

// 10. Verificar se o problema é com o cache
echo "\n🗂️ VERIFICANDO SE O CACHE ESTÁ FUNCIONANDO...\n";

$cacheDirs = [
    'storage/framework/views',
    'storage/framework/cache',
    'storage/framework/sessions',
    'bootstrap/cache'
];

foreach ($cacheDirs as $dir) {
    if (is_dir($dir)) {
        $writable = is_writable($dir) ? 'Sim' : 'Não';
        echo "✅ $dir - Gravável: $writable\n";
    } else {
        echo "❌ $dir - NÃO EXISTE\n";
    }
}

// 11. Verificar se o problema é com os logs
echo "\n📋 VERIFICANDO SE OS LOGS ESTÃO FUNCIONANDO...\n";

$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "✅ Log encontrado: $logFile\n";
    
    $logSize = filesize($logFile);
    echo "Tamanho do log: " . number_format($logSize) . " bytes\n";
    
    // Mostrar últimas 10 linhas
    $output = shell_exec('tail -10 ' . $logFile . ' 2>&1');
    echo "Últimas 10 linhas:\n$output\n";
} else {
    echo "❌ Log não encontrado: $logFile\n";
}

// 12. Verificar se o problema é com o servidor de desenvolvimento
echo "\n🚀 VERIFICANDO SE O SERVIDOR DE DESENVOLVIMENTO ESTÁ FUNCIONANDO...\n";

$serverProcessCommand = 'ps aux | grep "php artisan serve" | grep -v grep 2>&1';
$output = shell_exec($serverProcessCommand);
echo "Processos do servidor:\n$output\n";

if (strpos($output, 'php artisan serve') !== false) {
    echo "✅ Servidor de desenvolvimento está rodando\n";
} else {
    echo "❌ Servidor de desenvolvimento NÃO está rodando\n";
    echo "❌ Execute: php artisan serve\n";
}

echo "\n🎉 VERIFICAÇÃO DE PROBLEMAS FUNDAMENTAIS CONCLUÍDA!\n";
echo "✅ Verifique os resultados acima para identificar problemas\n";
echo "✅ Se houver erros, execute as correções necessárias\n";
echo "✅ Se tudo estiver OK, execute: php fix-simple-direct.php\n";
