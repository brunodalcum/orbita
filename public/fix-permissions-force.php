<?php

/**
 * CORREÇÃO FORÇADA DE PERMISSÕES - ÚLTIMA TENTATIVA
 * Acesse via: https://srv971263.hstgr.cloud/fix-permissions-force.php?force=true
 */

// Verificar parâmetro de segurança
if (!isset($_GET['force']) || $_GET['force'] !== 'true') {
    die('❌ Acesso negado. Use: ?force=true');
}

header('Content-Type: text/plain; charset=utf-8');

echo "🚨 CORREÇÃO FORÇADA DE PERMISSÕES - ÚLTIMA TENTATIVA\n";
echo "===================================================\n\n";

$baseDir = dirname(__DIR__);
$errors = [];
$success = [];

echo "📁 Diretório base: $baseDir\n\n";

// Função para executar comando e capturar saída
function executeCommand($command) {
    $output = [];
    $returnVar = 0;
    exec($command . ' 2>&1', $output, $returnVar);
    return [
        'success' => $returnVar === 0,
        'output' => implode("\n", $output),
        'command' => $command
    ];
}

// Comandos críticos para executar
$commands = [
    // Criar diretórios
    "mkdir -p '$baseDir/storage/app/public'",
    "mkdir -p '$baseDir/storage/framework/cache/data'",
    "mkdir -p '$baseDir/storage/framework/sessions'", 
    "mkdir -p '$baseDir/storage/framework/views'",
    "mkdir -p '$baseDir/storage/logs'",
    "mkdir -p '$baseDir/bootstrap/cache'",
    
    // Criar arquivo de log
    "touch '$baseDir/storage/logs/laravel.log'",
    
    // Tentar ajustar permissões (pode falhar se não for root)
    "chmod -R 755 '$baseDir/storage/'",
    "chmod -R 755 '$baseDir/bootstrap/cache/'",
    "chmod 644 '$baseDir/storage/logs/laravel.log'",
];

echo "1. 🔧 EXECUTANDO COMANDOS CRÍTICOS:\n";

foreach ($commands as $command) {
    echo "   Executando: " . str_replace($baseDir, '.', $command) . "\n";
    
    $result = executeCommand($command);
    
    if ($result['success']) {
        echo "      ✅ Sucesso\n";
        $success[] = $command;
    } else {
        echo "      ❌ Falhou: " . $result['output'] . "\n";
        $errors[] = [
            'command' => $command,
            'error' => $result['output']
        ];
    }
}

echo "\n2. 🧹 LIMPEZA FORÇADA DE CACHE:\n";

// Limpar caches usando PHP
$cacheDirs = [
    $baseDir . '/storage/framework/views',
    $baseDir . '/bootstrap/cache',
    $baseDir . '/storage/framework/cache/data'
];

foreach ($cacheDirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        $removed = 0;
        
        foreach ($files as $file) {
            if (is_file($file) && basename($file) !== '.gitignore') {
                if (@unlink($file)) {
                    $removed++;
                }
            }
        }
        
        echo "   ✅ " . str_replace($baseDir . '/', '', $dir) . ": $removed arquivos removidos\n";
    }
}

echo "\n3. 🧪 TESTES CRÍTICOS DE ESCRITA:\n";

$testResults = [];

// Teste 1: Views
$viewsDir = $baseDir . '/storage/framework/views';
$testFile = $viewsDir . '/test_force.txt';
$testContent = 'Teste forçado - ' . date('Y-m-d H:i:s');

if (@file_put_contents($testFile, $testContent)) {
    echo "   ✅ Views cache: ESCRITA OK\n";
    @unlink($testFile);
    $testResults['views'] = true;
} else {
    echo "   ❌ Views cache: ESCRITA FALHOU\n";
    $testResults['views'] = false;
}

// Teste 2: Bootstrap
$bootstrapDir = $baseDir . '/bootstrap/cache';
$testBootstrap = $bootstrapDir . '/test_force.txt';

if (@file_put_contents($testBootstrap, $testContent)) {
    echo "   ✅ Bootstrap cache: ESCRITA OK\n";
    @unlink($testBootstrap);
    $testResults['bootstrap'] = true;
} else {
    echo "   ❌ Bootstrap cache: ESCRITA FALHOU\n";
    $testResults['bootstrap'] = false;
}

// Teste 3: Log
$logFile = $baseDir . '/storage/logs/laravel.log';
$logMessage = "[" . date('Y-m-d H:i:s') . "] testing.INFO: Teste forçado de escrita no log\n";

if (@file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX)) {
    echo "   ✅ Log file: ESCRITA OK\n";
    $testResults['log'] = true;
} else {
    echo "   ❌ Log file: ESCRITA FALHOU\n";
    $testResults['log'] = false;
}

echo "\n4. 📋 DIAGNÓSTICO DETALHADO:\n";

// Verificar proprietário e permissões
$criticalPaths = [
    $baseDir . '/storage/framework/views',
    $baseDir . '/bootstrap/cache',
    $baseDir . '/storage/logs'
];

foreach ($criticalPaths as $path) {
    if (file_exists($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        $owner = function_exists('posix_getpwuid') ? posix_getpwuid(fileowner($path))['name'] : 'unknown';
        $writable = is_writable($path) ? 'SIM' : 'NÃO';
        
        echo "   📁 " . str_replace($baseDir . '/', '', $path) . ":\n";
        echo "      Permissões: $perms\n";
        echo "      Proprietário: $owner\n";
        echo "      Gravável: $writable\n";
    } else {
        echo "   ❌ " . str_replace($baseDir . '/', '', $path) . ": NÃO EXISTE\n";
    }
}

echo "\n5. 🎯 RESULTADO FINAL:\n";

$allTestsPassed = $testResults['views'] && $testResults['bootstrap'] && $testResults['log'];

if ($allTestsPassed) {
    echo "🎉 SUCESSO TOTAL!\n";
    echo "✅ Todos os testes de escrita passaram\n";
    echo "✅ Laravel deve funcionar normalmente\n";
    echo "\n🔄 TESTE AGORA:\n";
    echo "   https://srv971263.hstgr.cloud/hierarchy/branding\n";
} else {
    echo "⚠️  AINDA HÁ PROBLEMAS!\n";
    echo "\n🔧 EXECUTE VIA SSH (OBRIGATÓRIO):\n";
    echo "ssh user@srv971263.hstgr.cloud\n";
    echo "cd /home/user/htdocs/srv971263.hstgr.cloud/\n";
    echo "\n# Comandos para executar:\n";
    echo "sudo mkdir -p storage/{app/public,framework/{cache/data,sessions,views},logs}\n";
    echo "sudo mkdir -p bootstrap/cache\n";
    echo "sudo touch storage/logs/laravel.log\n";
    echo "sudo chown -R www-data:www-data storage/ bootstrap/cache/\n";
    echo "sudo chmod -R 755 storage/ bootstrap/cache/\n";
    echo "sudo chmod 644 storage/logs/laravel.log\n";
    echo "\n# Limpar cache:\n";
    echo "sudo rm -f storage/framework/views/*\n";
    echo "sudo rm -f bootstrap/cache/*.php\n";
    echo "\n# Testar escrita:\n";
    echo "echo 'teste' > storage/framework/views/test.txt && rm storage/framework/views/test.txt\n";
    echo "echo 'teste' > bootstrap/cache/test.txt && rm bootstrap/cache/test.txt\n";
    echo "echo 'teste' >> storage/logs/laravel.log\n";
}

if (!empty($errors)) {
    echo "\n❌ ERROS ENCONTRADOS:\n";
    foreach ($errors as $error) {
        echo "   Comando: " . str_replace($baseDir, '.', $error['command']) . "\n";
        echo "   Erro: " . $error['error'] . "\n\n";
    }
}

echo "\n📊 RESUMO:\n";
echo "   Comandos executados: " . count($commands) . "\n";
echo "   Sucessos: " . count($success) . "\n";
echo "   Erros: " . count($errors) . "\n";
echo "   Views OK: " . ($testResults['views'] ? 'SIM' : 'NÃO') . "\n";
echo "   Bootstrap OK: " . ($testResults['bootstrap'] ? 'SIM' : 'NÃO') . "\n";
echo "   Log OK: " . ($testResults['log'] ? 'SIM' : 'NÃO') . "\n";

echo "\n✅ Diagnóstico completo finalizado!\n";

// Auto-destruição após 2 horas
$creationTime = filemtime(__FILE__);
if (time() - $creationTime > 7200) { // 2 horas
    @unlink(__FILE__);
    echo "\n🔒 Script removido automaticamente por segurança.\n";
}
?>
