<?php

/**
 * Script para corrigir permissões em produção
 */

echo "🔧 CORREÇÃO DE PERMISSÕES - PRODUÇÃO\n";
echo "===================================\n\n";

// Diretórios que precisam de permissões de escrita
$directories = [
    __DIR__ . '/storage',
    __DIR__ . '/storage/app',
    __DIR__ . '/storage/framework',
    __DIR__ . '/storage/framework/cache',
    __DIR__ . '/storage/framework/sessions',
    __DIR__ . '/storage/framework/views',
    __DIR__ . '/storage/logs',
    __DIR__ . '/bootstrap/cache'
];

echo "1. 🔍 VERIFICANDO PERMISSÕES ATUAIS:\n";

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        $writable = is_writable($dir);
        echo "   {$dir}: {$perms} " . ($writable ? '✅' : '❌') . "\n";
    } else {
        echo "   {$dir}: NÃO EXISTE ❌\n";
    }
}

echo "\n2. 🔧 CORRIGINDO PERMISSÕES:\n";

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        echo "   📁 Criando diretório: {$dir}\n";
        if (mkdir($dir, 0755, true)) {
            echo "      ✅ Criado com sucesso\n";
        } else {
            echo "      ❌ Erro ao criar\n";
        }
    }
    
    if (is_dir($dir)) {
        echo "   🔧 Ajustando permissões: {$dir}\n";
        
        // Tentar chmod
        if (chmod($dir, 0755)) {
            echo "      ✅ Permissões ajustadas para 0755\n";
        } else {
            echo "      ⚠️  Erro ao ajustar permissões (pode precisar de sudo)\n";
        }
        
        // Verificar se ficou gravável
        if (is_writable($dir)) {
            echo "      ✅ Diretório agora é gravável\n";
        } else {
            echo "      ❌ Diretório ainda não é gravável\n";
        }
    }
}

echo "\n3. 🧹 LIMPANDO CACHE ANTIGO:\n";

$cacheDir = __DIR__ . '/storage/framework/views';
if (is_dir($cacheDir)) {
    $files = glob($cacheDir . '/*');
    $removed = 0;
    
    foreach ($files as $file) {
        if (is_file($file)) {
            if (unlink($file)) {
                $removed++;
            }
        }
    }
    
    echo "   ✅ {$removed} arquivos de cache removidos\n";
} else {
    echo "   ⚠️  Diretório de cache não existe\n";
}

echo "\n4. 🔍 VERIFICAÇÃO FINAL:\n";

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        $writable = is_writable($dir);
        echo "   {$dir}: {$perms} " . ($writable ? '✅' : '❌') . "\n";
    }
}

echo "\n5. 🧪 TESTE DE ESCRITA:\n";

$testFile = __DIR__ . '/storage/framework/views/test_write.txt';
$testContent = 'Teste de escrita - ' . date('Y-m-d H:i:s');

if (file_put_contents($testFile, $testContent)) {
    echo "   ✅ Teste de escrita bem-sucedido\n";
    
    if (file_exists($testFile)) {
        unlink($testFile);
        echo "   ✅ Arquivo de teste removido\n";
    }
} else {
    echo "   ❌ Teste de escrita falhou\n";
    echo "   🔧 SOLUÇÕES:\n";
    echo "      1. Execute como root: sudo php fix-permissions-production.php\n";
    echo "      2. Ou execute manualmente:\n";
    echo "         sudo chmod -R 755 storage/\n";
    echo "         sudo chmod -R 755 bootstrap/cache/\n";
    echo "         sudo chown -R www-data:www-data storage/\n";
    echo "         sudo chown -R www-data:www-data bootstrap/cache/\n";
}

echo "\n6. 📋 COMANDOS MANUAIS (se necessário):\n";
echo "   # Para Apache:\n";
echo "   sudo chown -R www-data:www-data " . __DIR__ . "/storage/\n";
echo "   sudo chown -R www-data:www-data " . __DIR__ . "/bootstrap/cache/\n";
echo "   sudo chmod -R 755 " . __DIR__ . "/storage/\n";
echo "   sudo chmod -R 755 " . __DIR__ . "/bootstrap/cache/\n";
echo "\n   # Para Nginx:\n";
echo "   sudo chown -R nginx:nginx " . __DIR__ . "/storage/\n";
echo "   sudo chown -R nginx:nginx " . __DIR__ . "/bootstrap/cache/\n";
echo "   sudo chmod -R 755 " . __DIR__ . "/storage/\n";
echo "   sudo chmod -R 755 " . __DIR__ . "/bootstrap/cache/\n";

echo "\n✅ Correção de permissões concluída!\n";
echo "🔄 Teste a URL novamente: https://srv971263.hstgr.cloud/hierarchy/branding\n";
