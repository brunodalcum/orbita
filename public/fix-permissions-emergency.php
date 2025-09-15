<?php

/**
 * Script de emergência para corrigir permissões
 * Acesse via: https://srv971263.hstgr.cloud/fix-permissions-emergency.php
 */

// Verificar se é uma requisição válida
if (!isset($_GET['fix']) || $_GET['fix'] !== 'permissions') {
    http_response_code(404);
    exit('Not Found');
}

header('Content-Type: text/plain; charset=utf-8');

echo "🔧 CORREÇÃO DE EMERGÊNCIA - PERMISSÕES\n";
echo "=====================================\n\n";

$baseDir = dirname(__DIR__);

// Diretórios que precisam de permissões
$directories = [
    $baseDir . '/storage',
    $baseDir . '/storage/framework',
    $baseDir . '/storage/framework/views',
    $baseDir . '/storage/framework/cache',
    $baseDir . '/storage/framework/sessions',
    $baseDir . '/storage/logs',
    $baseDir . '/bootstrap/cache'
];

echo "1. 🔍 VERIFICANDO DIRETÓRIOS:\n";

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        $writable = is_writable($dir);
        echo "   " . basename($dir) . ": {$perms} " . ($writable ? '✅' : '❌') . "\n";
    } else {
        echo "   " . basename($dir) . ": NÃO EXISTE ❌\n";
    }
}

echo "\n2. 🔧 CRIANDO DIRETÓRIOS:\n";

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "   ✅ Criado: " . basename($dir) . "\n";
        } else {
            echo "   ❌ Erro ao criar: " . basename($dir) . "\n";
        }
    }
}

echo "\n3. 🧹 LIMPANDO CACHE:\n";

$viewsDir = $baseDir . '/storage/framework/views';
if (is_dir($viewsDir)) {
    $files = glob($viewsDir . '/*');
    $removed = 0;
    
    foreach ($files as $file) {
        if (is_file($file) && unlink($file)) {
            $removed++;
        }
    }
    
    echo "   ✅ {$removed} arquivos de cache removidos\n";
}

$bootstrapCache = $baseDir . '/bootstrap/cache';
if (is_dir($bootstrapCache)) {
    $files = glob($bootstrapCache . '/*.php');
    $removed = 0;
    
    foreach ($files as $file) {
        if (is_file($file) && basename($file) !== '.gitignore' && unlink($file)) {
            $removed++;
        }
    }
    
    echo "   ✅ {$removed} arquivos de bootstrap cache removidos\n";
}

echo "\n4. 🧪 TESTE DE ESCRITA:\n";

$testFile = $viewsDir . '/test_emergency.txt';
$testContent = 'Teste emergência - ' . date('Y-m-d H:i:s');

if (file_put_contents($testFile, $testContent)) {
    echo "   ✅ Escrita bem-sucedida!\n";
    unlink($testFile);
    echo "   ✅ Arquivo de teste removido\n";
} else {
    echo "   ❌ Escrita falhou!\n";
    echo "\n🔧 EXECUTE NO SERVIDOR:\n";
    echo "   sudo chown -R www-data:www-data {$baseDir}/storage/\n";
    echo "   sudo chown -R www-data:www-data {$baseDir}/bootstrap/cache/\n";
    echo "   sudo chmod -R 755 {$baseDir}/storage/\n";
    echo "   sudo chmod -R 755 {$baseDir}/bootstrap/cache/\n";
}

echo "\n5. 📋 STATUS FINAL:\n";

$allGood = true;
foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $writable = is_writable($dir);
        echo "   " . basename($dir) . ": " . ($writable ? '✅ OK' : '❌ ERRO') . "\n";
        if (!$writable) $allGood = false;
    }
}

if ($allGood) {
    echo "\n🎉 SUCESSO! Todas as permissões estão corretas!\n";
    echo "🔄 Teste agora: https://srv971263.hstgr.cloud/hierarchy/branding\n";
} else {
    echo "\n⚠️  AINDA HÁ PROBLEMAS DE PERMISSÃO!\n";
    echo "🔧 Execute os comandos sudo mostrados acima\n";
}

echo "\n✅ Correção de emergência concluída!\n";

// Auto-remover este arquivo após 1 hora (por segurança)
$fileAge = time() - filemtime(__FILE__);
if ($fileAge > 3600) { // 1 hora
    unlink(__FILE__);
    echo "🗑️  Arquivo de emergência auto-removido por segurança\n";
}
