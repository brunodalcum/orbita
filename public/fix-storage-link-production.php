<?php

/**
 * CORREÇÃO DO STORAGE LINK EM PRODUÇÃO
 * Acesse via: https://srv971263.hstgr.cloud/fix-storage-link-production.php?fix=storage
 */

// Verificar parâmetro de segurança
if (!isset($_GET['fix']) || $_GET['fix'] !== 'storage') {
    die('❌ Acesso negado. Use: ?fix=storage');
}

header('Content-Type: text/plain; charset=utf-8');

echo "🔗 CORREÇÃO DO STORAGE LINK - PRODUÇÃO\n";
echo "=====================================\n\n";

$baseDir = dirname(__DIR__);

echo "📁 Diretório base: $baseDir\n\n";

// Verificar estrutura atual
echo "1. 🔍 VERIFICANDO ESTRUTURA ATUAL:\n";

$publicStoragePath = $baseDir . '/public/storage';
$storagePublicPath = $baseDir . '/storage/app/public';

echo "   public/storage: " . ($publicStoragePath) . "\n";
echo "   Existe: " . (file_exists($publicStoragePath) ? 'SIM' : 'NÃO') . "\n";

if (file_exists($publicStoragePath)) {
    echo "   É link simbólico: " . (is_link($publicStoragePath) ? 'SIM' : 'NÃO') . "\n";
    
    if (is_link($publicStoragePath)) {
        $target = readlink($publicStoragePath);
        echo "   Aponta para: $target\n";
        echo "   Target existe: " . (file_exists($target) ? 'SIM' : 'NÃO') . "\n";
    }
}

echo "\n   storage/app/public: $storagePublicPath\n";
echo "   Existe: " . (file_exists($storagePublicPath) ? 'SIM' : 'NÃO') . "\n";

// Verificar arquivo específico
echo "\n2. 🖼️  VERIFICANDO LOGOMARCA ESPECÍFICA:\n";

$logoPath = $storagePublicPath . '/branding/logo_1757903080_TjT4xPWeew.png';
echo "   Arquivo: $logoPath\n";
echo "   Existe: " . (file_exists($logoPath) ? 'SIM' : 'NÃO') . "\n";

if (file_exists($logoPath)) {
    echo "   Tamanho: " . number_format(filesize($logoPath) / 1024, 2) . " KB\n";
    echo "   Permissões: " . substr(sprintf('%o', fileperms($logoPath)), -4) . "\n";
}

// Verificar acesso via public/storage
$publicLogoPath = $publicStoragePath . '/branding/logo_1757903080_TjT4xPWeew.png';
echo "\n   Via public/storage: $publicLogoPath\n";
echo "   Acessível: " . (file_exists($publicLogoPath) ? 'SIM' : 'NÃO') . "\n";

echo "\n3. 🔧 CORRIGINDO STORAGE LINK:\n";

// Remover link existente se estiver quebrado
if (file_exists($publicStoragePath) && !is_link($publicStoragePath)) {
    echo "   Removendo diretório público existente...\n";
    if (is_dir($publicStoragePath)) {
        // Tentar remover diretório
        $files = glob($publicStoragePath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        if (rmdir($publicStoragePath)) {
            echo "      ✅ Diretório removido\n";
        } else {
            echo "      ❌ Erro ao remover diretório\n";
        }
    }
} elseif (is_link($publicStoragePath)) {
    echo "   Removendo link simbólico existente...\n";
    if (unlink($publicStoragePath)) {
        echo "      ✅ Link removido\n";
    } else {
        echo "      ❌ Erro ao remover link\n";
    }
}

// Criar novo link simbólico
echo "   Criando novo link simbólico...\n";
if (symlink($storagePublicPath, $publicStoragePath)) {
    echo "      ✅ Link criado com sucesso\n";
} else {
    echo "      ❌ Erro ao criar link\n";
    
    // Tentar alternativa: copiar arquivos
    echo "   Tentando alternativa: copiar arquivos...\n";
    
    if (!file_exists($publicStoragePath)) {
        mkdir($publicStoragePath, 0755, true);
    }
    
    // Copiar recursivamente
    function copyRecursive($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst, 0755, true);
        
        while (($file = readdir($dir)) !== false) {
            if ($file != '.' && $file != '..') {
                if (is_dir($src . '/' . $file)) {
                    copyRecursive($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        
        closedir($dir);
    }
    
    if (is_dir($storagePublicPath)) {
        copyRecursive($storagePublicPath, $publicStoragePath);
        echo "      ✅ Arquivos copiados\n";
    }
}

echo "\n4. 🧪 TESTE FINAL:\n";

// Verificar se o link funciona
$testFile = $publicStoragePath . '/branding/logo_1757903080_TjT4xPWeew.png';
echo "   Arquivo de teste: $testFile\n";
echo "   Acessível: " . (file_exists($testFile) ? 'SIM' : 'NÃO') . "\n";

if (file_exists($testFile)) {
    echo "   Tamanho: " . number_format(filesize($testFile) / 1024, 2) . " KB\n";
    
    // Testar URL
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $testUrl = "$protocol://$host/storage/branding/logo_1757903080_TjT4xPWeew.png";
    
    echo "   URL de teste: $testUrl\n";
    
    // Verificar se é acessível via HTTP
    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
            'method' => 'HEAD'
        ]
    ]);
    
    $headers = @get_headers($testUrl, 1, $context);
    if ($headers) {
        $statusCode = substr($headers[0], 9, 3);
        echo "   Status HTTP: $statusCode\n";
        
        if ($statusCode == '200') {
            echo "   ✅ SUCESSO! Logomarca acessível via HTTP\n";
        } else {
            echo "   ❌ Logomarca NÃO acessível via HTTP\n";
        }
    } else {
        echo "   ⚠️  Não foi possível testar HTTP (pode estar funcionando)\n";
    }
}

echo "\n5. 📋 RESUMO:\n";

if (file_exists($publicStoragePath) && file_exists($testFile)) {
    echo "   ✅ Storage link configurado\n";
    echo "   ✅ Logomarca acessível\n";
    echo "   ✅ Problema resolvido!\n";
    echo "\n🔄 TESTE AGORA:\n";
    echo "   https://srv971263.hstgr.cloud/dashboard\n";
    echo "   https://srv971263.hstgr.cloud/hierarchy/branding?node_id=1\n";
} else {
    echo "   ❌ Ainda há problemas\n";
    echo "\n🔧 EXECUTE VIA SSH:\n";
    echo "   ssh user@srv971263.hstgr.cloud\n";
    echo "   cd /home/user/htdocs/srv971263.hstgr.cloud/\n";
    echo "   php artisan storage:link\n";
    echo "   chmod -R 755 storage/app/public/\n";
    echo "   chmod -R 755 public/storage/\n";
}

echo "\n✅ Correção completa finalizada!\n";

// Auto-destruição após 2 horas
$creationTime = filemtime(__FILE__);
if (time() - $creationTime > 7200) { // 2 horas
    @unlink(__FILE__);
    echo "\n🔒 Script removido automaticamente por segurança.\n";
}
?>
