<?php

// Diagnóstico do erro 419
// Execute: php diagnose-419.php

echo "🔍 Diagnóstico do erro 419...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Verificar configurações básicas
echo "📋 Verificando configurações...\n";

// APP_KEY
$appKey = env('APP_KEY');
if (empty($appKey) || $appKey === 'base64:') {
    echo "❌ APP_KEY não configurada ou vazia\n";
} else {
    echo "✅ APP_KEY configurada: " . substr($appKey, 0, 20) . "...\n";
}

// SESSION_DRIVER
$sessionDriver = env('SESSION_DRIVER', 'file');
echo "SESSION_DRIVER: $sessionDriver\n";

// 3. Verificar diretório de sessões
echo "\n📁 Verificando diretório de sessões...\n";
$sessionPath = storage_path('framework/sessions');
if (is_dir($sessionPath)) {
    echo "✅ Diretório de sessões existe: $sessionPath\n";
    
    // Verificar permissões
    $perms = substr(sprintf('%o', fileperms($sessionPath)), -4);
    echo "Permissões: $perms\n";
    
    // Testar escrita
    $testFile = $sessionPath . '/test_' . time() . '.tmp';
    if (file_put_contents($testFile, 'test')) {
        unlink($testFile);
        echo "✅ Escrita funcionando\n";
    } else {
        echo "❌ Problema de escrita\n";
    }
} else {
    echo "❌ Diretório de sessões não existe: $sessionPath\n";
}

// 4. Verificar arquivo .env
echo "\n⚙️ Verificando arquivo .env...\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    // Verificar linhas importantes
    $lines = explode("\n", $envContent);
    $importantLines = [];
    
    foreach ($lines as $line) {
        if (strpos($line, 'APP_KEY=') === 0 || 
            strpos($line, 'SESSION_DRIVER=') === 0 ||
            strpos($line, 'SESSION_LIFETIME=') === 0) {
            $importantLines[] = $line;
        }
    }
    
    echo "Linhas importantes do .env:\n";
    foreach ($importantLines as $line) {
        echo "  $line\n";
    }
} else {
    echo "❌ Arquivo .env não encontrado\n";
}

// 5. Verificar se há problemas de cache
echo "\n🧹 Verificando caches...\n";
$cacheFiles = [
    'bootstrap/cache/config.php',
    'bootstrap/cache/routes.php',
    'bootstrap/cache/packages.php',
    'bootstrap/cache/services.php'
];

foreach ($cacheFiles as $cacheFile) {
    if (file_exists($cacheFile)) {
        echo "✅ Cache existe: $cacheFile\n";
    } else {
        echo "⚠️ Cache não existe: $cacheFile\n";
    }
}

// 6. Criar arquivo de teste simples
echo "\n📝 Criando arquivo de teste...\n";
$testFile = 'test-419-simple.php';
$testContent = '<?php
// Teste simples de CSRF
require_once "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

echo "APP_KEY: " . config("app.key") . "\n";
echo "SESSION_DRIVER: " . config("session.driver") . "\n";

// Tentar gerar token sem iniciar sessão
try {
    $token = csrf_token();
    echo "CSRF Token: " . $token . "\n";
} catch (Exception $e) {
    echo "Erro ao gerar CSRF: " . $e->getMessage() . "\n";
}
?>';

if (file_put_contents($testFile, $testContent)) {
    echo "Arquivo de teste criado: $testFile\n";
    echo "Execute: php $testFile\n";
}

// 7. Verificar se o problema é de permissões
echo "\n🔐 Verificando permissões...\n";
$paths = [
    'storage',
    'storage/framework',
    'storage/framework/sessions',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($paths as $path) {
    if (is_dir($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        $owner = posix_getpwuid(fileowner($path));
        echo "$path - Permissões: $perms, Owner: " . $owner['name'] . "\n";
    }
}

echo "\n🎯 Diagnóstico concluído!\n";
echo "\n💡 Soluções recomendadas:\n";
echo "1. Se APP_KEY estiver vazia: php artisan key:generate\n";
echo "2. Se permissões estiverem incorretas: chmod -R 755 storage/\n";
echo "3. Se proprietário estiver incorreto: chown -R www-data:www-data storage/\n";
echo "4. Limpar caches: php artisan cache:clear && php artisan config:clear\n";
echo "5. Testar: php $testFile\n";
