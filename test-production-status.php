<?php

echo "🔍 TESTE RÁPIDO DE STATUS DA PRODUÇÃO\n";
echo "=" . str_repeat("=", 40) . "\n\n";

// Teste básico de PHP
echo "1. PHP funcionando: ";
echo "✅ Sim (versão " . PHP_VERSION . ")\n";

// Teste de escrita
echo "2. Escrita funcionando: ";
$testFile = 'test_write_' . time() . '.tmp';
if (file_put_contents($testFile, 'test')) {
    echo "✅ Sim\n";
    unlink($testFile);
} else {
    echo "❌ Não\n";
}

// Teste de .env
echo "3. Arquivo .env: ";
if (file_exists('.env')) {
    echo "✅ Existe\n";
} else {
    echo "❌ Não encontrado\n";
}

// Teste de vendor
echo "4. Vendor directory: ";
if (is_dir('vendor')) {
    echo "✅ Existe\n";
} else {
    echo "❌ Não encontrado\n";
}

// Teste de storage
echo "5. Storage writable: ";
if (is_writable('storage')) {
    echo "✅ Sim\n";
} else {
    echo "❌ Não\n";
}

// Teste de bootstrap/cache
echo "6. Bootstrap cache: ";
if (is_writable('bootstrap/cache')) {
    echo "✅ Writable\n";
} else {
    echo "❌ Não writable\n";
}

// Verificar se há erros no log
echo "7. Erros recentes: ";
if (file_exists('storage/logs/laravel.log')) {
    $log = file_get_contents('storage/logs/laravel.log');
    $today = date('Y-m-d');
    
    if (strpos($log, $today) !== false && (strpos($log, 'ERROR') !== false || strpos($log, 'Exception') !== false)) {
        echo "⚠️  Encontrados erros de hoje\n";
    } else {
        echo "✅ Sem erros recentes\n";
    }
} else {
    echo "⚠️  Log não encontrado\n";
}

echo "\n" . str_repeat("=", 40) . "\n";
echo "Data/Hora: " . date('Y-m-d H:i:s') . "\n";
echo str_repeat("=", 40) . "\n";

?>
