<?php

/**
 * CORREÇÃO DE EMERGÊNCIA: Storage Link em Produção
 * Acesse via: https://srv971263.hstgr.cloud/fix-storage-emergency.php?fix=storage
 */

// Verificar parâmetro de segurança
if (!isset($_GET['fix']) || $_GET['fix'] !== 'storage') {
    die('❌ Acesso negado. Use: ?fix=storage');
}

header('Content-Type: text/plain; charset=utf-8');

echo "🔧 CORREÇÃO DE EMERGÊNCIA: Storage Link\n";
echo "======================================\n\n";

$baseDir = dirname(__DIR__);
$publicStoragePath = $baseDir . '/public/storage';
$storageAppPublicPath = $baseDir . '/storage/app/public';

echo "📁 Servidor: " . $_SERVER['HTTP_HOST'] . "\n";
echo "📁 Base: {$baseDir}\n\n";

echo "1. 🔍 DIAGNÓSTICO INICIAL:\n";

// Verificar se os diretórios existem
echo "   storage/app/public existe: " . (is_dir($storageAppPublicPath) ? 'SIM' : 'NÃO') . "\n";
echo "   public/storage existe: " . (file_exists($publicStoragePath) ? 'SIM' : 'NÃO') . "\n";

if (file_exists($publicStoragePath)) {
    echo "   public/storage é link: " . (is_link($publicStoragePath) ? 'SIM' : 'NÃO') . "\n";
    
    if (is_link($publicStoragePath)) {
        $linkTarget = readlink($publicStoragePath);
        echo "   Link aponta para: {$linkTarget}\n";
        
        $correctTarget = realpath($storageAppPublicPath);
        $currentTarget = realpath($linkTarget);
        
        echo "   Target correto: {$correctTarget}\n";
        echo "   Target atual: {$currentTarget}\n";
        echo "   Link correto: " . ($correctTarget === $currentTarget ? 'SIM' : 'NÃO') . "\n";
    }
}

echo "\n2. 🔧 CORREÇÃO DO STORAGE LINK:\n";

// Remover public/storage se existir e estiver incorreto
if (file_exists($publicStoragePath)) {
    if (is_link($publicStoragePath)) {
        $linkTarget = readlink($publicStoragePath);
        $correctTarget = realpath($storageAppPublicPath);
        $currentTarget = realpath($linkTarget);
        
        if ($correctTarget !== $currentTarget) {
            echo "   🗑️ Removendo link incorreto...\n";
            if (unlink($publicStoragePath)) {
                echo "      ✅ Link removido\n";
            } else {
                echo "      ❌ Erro ao remover link\n";
            }
        } else {
            echo "   ✅ Link já está correto\n";
        }
    } else {
        echo "   🗑️ Removendo arquivo/diretório...\n";
        if (is_dir($publicStoragePath)) {
            if (rmdir($publicStoragePath)) {
                echo "      ✅ Diretório removido\n";
            } else {
                echo "      ❌ Erro ao remover diretório\n";
            }
        } else {
            if (unlink($publicStoragePath)) {
                echo "      ✅ Arquivo removido\n";
            } else {
                echo "      ❌ Erro ao remover arquivo\n";
            }
        }
    }
}

// Criar novo link
if (!file_exists($publicStoragePath)) {
    echo "   🔗 Criando storage link...\n";
    
    if (symlink($storageAppPublicPath, $publicStoragePath)) {
        echo "      ✅ Link criado com sucesso\n";
    } else {
        echo "      ❌ Erro ao criar link via symlink\n";
        
        // Tentar via comando shell
        echo "   🔧 Tentando via comando shell...\n";
        $command = "ln -sf '{$storageAppPublicPath}' '{$publicStoragePath}' 2>&1";
        $output = shell_exec($command);
        
        if (file_exists($publicStoragePath) && is_link($publicStoragePath)) {
            echo "      ✅ Link criado via comando\n";
        } else {
            echo "      ❌ Falha via comando: {$output}\n";
        }
    }
}

echo "\n3. 🧪 TESTE DE FUNCIONALIDADE:\n";

// Testar arquivo específico
$testFiles = [
    'branding/logo_1757903080_TjT4xPWeew.png',
    'branding/logo_small_1757903080_Zp14sucJn6.png',
    'branding/favicon_1757903080_MKGLUAUW4L.png'
];

foreach ($testFiles as $testFile) {
    $storageFile = $storageAppPublicPath . '/' . $testFile;
    $publicFile = $publicStoragePath . '/' . $testFile;
    
    echo "   Testando: {$testFile}\n";
    echo "      Storage existe: " . (file_exists($storageFile) ? 'SIM' : 'NÃO') . "\n";
    echo "      Público acessível: " . (file_exists($publicFile) ? 'SIM' : 'NÃO') . "\n";
    
    if (file_exists($publicFile)) {
        $url = 'https://' . $_SERVER['HTTP_HOST'] . '/storage/' . $testFile;
        echo "      URL: {$url}\n";
        echo "      Status: ✅ DEVE FUNCIONAR\n";
    } else {
        echo "      Status: ❌ NÃO FUNCIONARÁ\n";
    }
    echo "\n";
}

echo "4. 🔧 AJUSTANDO PERMISSÕES:\n";

// Ajustar permissões
$paths = [
    $storageAppPublicPath,
    $storageAppPublicPath . '/branding',
    $publicStoragePath
];

foreach ($paths as $path) {
    if (file_exists($path)) {
        $oldPerms = substr(sprintf('%o', fileperms($path)), -4);
        
        if (chmod($path, 0755)) {
            echo "   ✅ {$path}: {$oldPerms} → 0755\n";
        } else {
            echo "   ❌ Erro ao ajustar: {$path}\n";
        }
    }
}

echo "\n5. 📋 VERIFICAÇÃO FINAL:\n";

$allGood = true;

// Verificar link
if (is_link($publicStoragePath)) {
    $linkTarget = readlink($publicStoragePath);
    $correctTarget = realpath($storageAppPublicPath);
    $currentTarget = realpath($linkTarget);
    
    if ($correctTarget === $currentTarget) {
        echo "   ✅ Storage link: CORRETO\n";
    } else {
        echo "   ❌ Storage link: INCORRETO\n";
        $allGood = false;
    }
} else {
    echo "   ❌ Storage link: NÃO EXISTE\n";
    $allGood = false;
}

// Verificar acesso aos arquivos
$accessibleCount = 0;
foreach ($testFiles as $testFile) {
    $publicFile = $publicStoragePath . '/' . $testFile;
    if (file_exists($publicFile)) {
        $accessibleCount++;
    }
}

echo "   ✅ Arquivos acessíveis: {$accessibleCount}/" . count($testFiles) . "\n";

if ($accessibleCount === count($testFiles)) {
    echo "   ✅ Todos os arquivos: ACESSÍVEIS\n";
} else {
    echo "   ❌ Alguns arquivos: NÃO ACESSÍVEIS\n";
    $allGood = false;
}

echo "\n";

if ($allGood) {
    echo "🎉 SUCESSO COMPLETO!\n";
    echo "✅ Storage link configurado corretamente\n";
    echo "✅ Todos os arquivos acessíveis\n";
    echo "✅ Permissões ajustadas\n";
    echo "\n🔄 TESTE AGORA:\n";
    echo "   https://{$_SERVER['HTTP_HOST']}/hierarchy/branding?node_id=1\n";
} else {
    echo "⚠️ AINDA HÁ PROBLEMAS!\n";
    echo "\n🔧 EXECUTE VIA SSH:\n";
    echo "   ssh user@{$_SERVER['HTTP_HOST']}\n";
    echo "   cd /home/user/htdocs/{$_SERVER['HTTP_HOST']}/\n";
    echo "   rm -rf public/storage\n";
    echo "   ln -sf \$(pwd)/storage/app/public \$(pwd)/public/storage\n";
    echo "   chmod -R 755 storage/app/public/\n";
    echo "   chmod -R 755 public/storage\n";
}

echo "\n✅ Correção de emergência finalizada!\n";

// Auto-destruição após 2 horas
$creationTime = filemtime(__FILE__);
if (time() - $creationTime > 7200) { // 2 horas
    @unlink(__FILE__);
    echo "\n🔒 Script removido automaticamente por segurança.\n";
}
?>
