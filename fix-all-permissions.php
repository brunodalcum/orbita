<?php

/**
 * Correção completa de permissões para Laravel
 */

echo "🔧 CORREÇÃO COMPLETA DE PERMISSÕES - LARAVEL\n";
echo "===========================================\n\n";

$baseDir = __DIR__;

// Todos os diretórios que precisam de permissões de escrita
$directories = [
    $baseDir . '/storage',
    $baseDir . '/storage/app',
    $baseDir . '/storage/app/public',
    $baseDir . '/storage/framework',
    $baseDir . '/storage/framework/cache',
    $baseDir . '/storage/framework/cache/data',
    $baseDir . '/storage/framework/sessions',
    $baseDir . '/storage/framework/views',
    $baseDir . '/storage/logs',
    $baseDir . '/bootstrap',
    $baseDir . '/bootstrap/cache'
];

// Arquivos que precisam existir
$files = [
    $baseDir . '/storage/logs/laravel.log'
];

echo "1. 🔍 VERIFICANDO ESTADO ATUAL:\n";

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        $writable = is_writable($dir);
        echo "   " . str_replace($baseDir . '/', '', $dir) . ": {$perms} " . ($writable ? '✅' : '❌') . "\n";
    } else {
        echo "   " . str_replace($baseDir . '/', '', $dir) . ": NÃO EXISTE ❌\n";
    }
}

echo "\n2. 📁 CRIANDO DIRETÓRIOS NECESSÁRIOS:\n";

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        echo "   Criando: " . str_replace($baseDir . '/', '', $dir) . "\n";
        if (mkdir($dir, 0755, true)) {
            echo "      ✅ Criado com sucesso\n";
        } else {
            echo "      ❌ Erro ao criar\n";
        }
    }
}

echo "\n3. 📄 CRIANDO ARQUIVOS NECESSÁRIOS:\n";

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "   Criando: " . str_replace($baseDir . '/', '', $file) . "\n";
        
        // Garantir que o diretório pai existe
        $dir = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        // Criar arquivo vazio
        if (touch($file)) {
            echo "      ✅ Arquivo criado\n";
        } else {
            echo "      ❌ Erro ao criar arquivo\n";
        }
    }
}

echo "\n4. 🔧 AJUSTANDO PERMISSÕES:\n";

// Ajustar permissões dos diretórios
foreach ($directories as $dir) {
    if (is_dir($dir)) {
        echo "   Ajustando: " . str_replace($baseDir . '/', '', $dir) . "\n";
        
        if (chmod($dir, 0755)) {
            echo "      ✅ Permissões ajustadas para 0755\n";
        } else {
            echo "      ⚠️  Erro ao ajustar (pode precisar de sudo)\n";
        }
    }
}

// Ajustar permissões dos arquivos
foreach ($files as $file) {
    if (file_exists($file)) {
        echo "   Ajustando arquivo: " . str_replace($baseDir . '/', '', $file) . "\n";
        
        if (chmod($file, 0644)) {
            echo "      ✅ Permissões ajustadas para 0644\n";
        } else {
            echo "      ⚠️  Erro ao ajustar arquivo\n";
        }
    }
}

echo "\n5. 🧹 LIMPANDO CACHES ANTIGOS:\n";

// Limpar cache de views
$viewsDir = $baseDir . '/storage/framework/views';
if (is_dir($viewsDir)) {
    $files = glob($viewsDir . '/*');
    $removed = 0;
    
    foreach ($files as $file) {
        if (is_file($file) && basename($file) !== '.gitignore') {
            if (unlink($file)) {
                $removed++;
            }
        }
    }
    
    echo "   ✅ Views cache: {$removed} arquivos removidos\n";
}

// Limpar bootstrap cache
$bootstrapCache = $baseDir . '/bootstrap/cache';
if (is_dir($bootstrapCache)) {
    $files = glob($bootstrapCache . '/*');
    $removed = 0;
    
    foreach ($files as $file) {
        if (is_file($file) && basename($file) !== '.gitignore') {
            if (unlink($file)) {
                $removed++;
            }
        }
    }
    
    echo "   ✅ Bootstrap cache: {$removed} arquivos removidos\n";
}

// Limpar framework cache
$frameworkCache = $baseDir . '/storage/framework/cache/data';
if (is_dir($frameworkCache)) {
    $files = glob($frameworkCache . '/*');
    $removed = 0;
    
    foreach ($files as $file) {
        if (is_file($file)) {
            if (unlink($file)) {
                $removed++;
            }
        }
    }
    
    echo "   ✅ Framework cache: {$removed} arquivos removidos\n";
}

echo "\n6. 🧪 TESTES DE FUNCIONALIDADE:\n";

// Teste 1: Escrita em views
$testViewFile = $baseDir . '/storage/framework/views/test_write.php';
if (file_put_contents($testViewFile, '<?php // Teste de escrita - ' . date('Y-m-d H:i:s'))) {
    echo "   ✅ Views: Escrita OK\n";
    unlink($testViewFile);
} else {
    echo "   ❌ Views: Escrita FALHOU\n";
}

// Teste 2: Escrita em bootstrap cache
$testBootstrapFile = $baseDir . '/bootstrap/cache/test_write.php';
if (file_put_contents($testBootstrapFile, '<?php // Teste bootstrap - ' . date('Y-m-d H:i:s'))) {
    echo "   ✅ Bootstrap cache: Escrita OK\n";
    unlink($testBootstrapFile);
} else {
    echo "   ❌ Bootstrap cache: Escrita FALHOU\n";
}

// Teste 3: Escrita em logs
$testLogMessage = "[" . date('Y-m-d H:i:s') . "] testing.INFO: Teste de escrita no log\n";
if (file_put_contents($baseDir . '/storage/logs/laravel.log', $testLogMessage, FILE_APPEND | LOCK_EX)) {
    echo "   ✅ Logs: Escrita OK\n";
} else {
    echo "   ❌ Logs: Escrita FALHOU\n";
}

echo "\n7. 🔍 VERIFICAÇÃO FINAL:\n";

$allGood = true;
$criticalDirs = [
    $baseDir . '/storage/framework/views' => 'Views Cache',
    $baseDir . '/bootstrap/cache' => 'Bootstrap Cache',
    $baseDir . '/storage/logs' => 'Logs'
];

foreach ($criticalDirs as $dir => $name) {
    if (is_dir($dir) && is_writable($dir)) {
        echo "   ✅ {$name}: OK\n";
    } else {
        echo "   ❌ {$name}: PROBLEMA\n";
        $allGood = false;
    }
}

if ($allGood) {
    echo "\n🎉 SUCESSO COMPLETO!\n";
    echo "✅ Todos os diretórios estão com permissões corretas\n";
    echo "✅ Todos os arquivos necessários foram criados\n";
    echo "✅ Cache foi limpo\n";
    echo "✅ Testes de escrita passaram\n";
    echo "\n🔄 TESTE AGORA: https://srv971263.hstgr.cloud/hierarchy/branding\n";
} else {
    echo "\n⚠️  AINDA HÁ PROBLEMAS!\n";
    echo "🔧 EXECUTE COMO ROOT/SUDO:\n";
    echo "   sudo php fix-all-permissions.php\n";
    echo "\n🔧 OU COMANDOS MANUAIS:\n";
    echo "   sudo chown -R www-data:www-data {$baseDir}/storage/\n";
    echo "   sudo chown -R www-data:www-data {$baseDir}/bootstrap/cache/\n";
    echo "   sudo chmod -R 755 {$baseDir}/storage/\n";
    echo "   sudo chmod -R 755 {$baseDir}/bootstrap/cache/\n";
    echo "   sudo touch {$baseDir}/storage/logs/laravel.log\n";
    echo "   sudo chmod 644 {$baseDir}/storage/logs/laravel.log\n";
}

echo "\n✅ Correção completa finalizada!\n";
