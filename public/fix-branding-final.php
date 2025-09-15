<?php

/**
 * CORREÇÃO FINAL: Branding e Storage em Produção
 * Acesse via: https://srv971263.hstgr.cloud/fix-branding-final.php?fix=branding&confirm=yes
 */

// Verificar parâmetros de segurança
if (!isset($_GET['fix']) || $_GET['fix'] !== 'branding' || !isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
    die('❌ Acesso negado. Use: ?fix=branding&confirm=yes');
}

header('Content-Type: text/plain; charset=utf-8');

echo "🔧 CORREÇÃO FINAL: Branding e Storage em Produção\n";
echo "================================================\n\n";

$baseDir = dirname(__DIR__);
$publicStoragePath = $baseDir . '/public/storage';
$storageAppPublicPath = $baseDir . '/storage/app/public';
$brandingDir = $storageAppPublicPath . '/branding';

echo "📁 Servidor: " . $_SERVER['HTTP_HOST'] . "\n";
echo "📁 Base: {$baseDir}\n\n";

// Arquivos específicos que precisam funcionar
$targetFiles = [
    'branding/logo_1757903080_TjT4xPWeew.png',
    'branding/logo_small_1757903080_Zp14sucJn6.png',
    'branding/favicon_1757903080_MKGLUAUW4L.png'
];

echo "1. 🔍 DIAGNÓSTICO DETALHADO:\n";

// Verificar se os diretórios base existem
echo "   storage/app/public existe: " . (is_dir($storageAppPublicPath) ? 'SIM' : 'NÃO') . "\n";
echo "   storage/app/public/branding existe: " . (is_dir($brandingDir) ? 'SIM' : 'NÃO') . "\n";

// Verificar arquivos específicos no storage
echo "   Arquivos no storage:\n";
foreach ($targetFiles as $file) {
    $filePath = $storageAppPublicPath . '/' . $file;
    if (file_exists($filePath)) {
        $size = number_format(filesize($filePath) / 1024, 2);
        echo "      ✅ {$file} ({$size} KB)\n";
    } else {
        echo "      ❌ {$file} - NÃO ENCONTRADO\n";
    }
}

// Verificar public/storage
echo "   public/storage existe: " . (file_exists($publicStoragePath) ? 'SIM' : 'NÃO') . "\n";
if (file_exists($publicStoragePath)) {
    echo "   public/storage é link: " . (is_link($publicStoragePath) ? 'SIM' : 'NÃO') . "\n";
    
    if (is_link($publicStoragePath)) {
        $linkTarget = readlink($publicStoragePath);
        echo "   Link aponta para: {$linkTarget}\n";
        
        $expectedTarget = realpath($storageAppPublicPath);
        $currentTarget = realpath($linkTarget);
        echo "   Target esperado: {$expectedTarget}\n";
        echo "   Target atual: {$currentTarget}\n";
        echo "   Link correto: " . ($expectedTarget === $currentTarget ? 'SIM' : 'NÃO') . "\n";
    }
}

echo "\n2. 🔧 CORREÇÃO FORÇADA DO STORAGE LINK:\n";

// Função para executar comando e capturar saída
function execCommand($command) {
    $output = [];
    $returnVar = 0;
    exec($command . ' 2>&1', $output, $returnVar);
    return [
        'success' => $returnVar === 0,
        'output' => implode("\n", $output),
        'command' => $command
    ];
}

// Remover public/storage existente
if (file_exists($publicStoragePath)) {
    echo "   🗑️ Removendo public/storage existente...\n";
    
    if (is_link($publicStoragePath)) {
        $result = unlink($publicStoragePath);
    } else {
        $result = is_dir($publicStoragePath) ? rmdir($publicStoragePath) : unlink($publicStoragePath);
    }
    
    if ($result) {
        echo "      ✅ Removido com sucesso\n";
    } else {
        echo "      ❌ Erro ao remover\n";
        
        // Tentar via comando
        $cmd = "rm -rf '{$publicStoragePath}'";
        $result = execCommand($cmd);
        if ($result['success']) {
            echo "      ✅ Removido via comando\n";
        } else {
            echo "      ❌ Falha via comando: " . $result['output'] . "\n";
        }
    }
}

// Criar novo link
echo "   🔗 Criando novo storage link...\n";

$linkCreated = false;

// Método 1: symlink PHP
if (symlink($storageAppPublicPath, $publicStoragePath)) {
    echo "      ✅ Link criado via symlink()\n";
    $linkCreated = true;
} else {
    echo "      ❌ Falha via symlink()\n";
    
    // Método 2: comando ln
    $cmd = "ln -sf '{$storageAppPublicPath}' '{$publicStoragePath}'";
    $result = execCommand($cmd);
    
    if ($result['success'] && is_link($publicStoragePath)) {
        echo "      ✅ Link criado via comando ln\n";
        $linkCreated = true;
    } else {
        echo "      ❌ Falha via comando ln: " . $result['output'] . "\n";
        
        // Método 3: link relativo
        $relativePath = '../storage/app/public';
        $cmd = "cd '{$baseDir}/public' && ln -sf '{$relativePath}' 'storage'";
        $result = execCommand($cmd);
        
        if ($result['success'] && is_link($publicStoragePath)) {
            echo "      ✅ Link criado via caminho relativo\n";
            $linkCreated = true;
        } else {
            echo "      ❌ Falha via caminho relativo: " . $result['output'] . "\n";
        }
    }
}

echo "\n3. 🔐 AJUSTANDO PERMISSÕES:\n";

// Ajustar permissões dos diretórios
$paths = [
    $storageAppPublicPath => 'storage/app/public',
    $brandingDir => 'storage/app/public/branding',
    $publicStoragePath => 'public/storage'
];

foreach ($paths as $path => $name) {
    if (file_exists($path)) {
        echo "   Ajustando {$name}...\n";
        
        if (chmod($path, 0755)) {
            echo "      ✅ Permissões ajustadas para 0755\n";
        } else {
            echo "      ❌ Erro ao ajustar permissões\n";
            
            // Tentar via comando
            $cmd = "chmod 755 '{$path}'";
            $result = execCommand($cmd);
            if ($result['success']) {
                echo "      ✅ Permissões ajustadas via comando\n";
            } else {
                echo "      ❌ Falha via comando: " . $result['output'] . "\n";
            }
        }
    }
}

// Ajustar permissões dos arquivos específicos
foreach ($targetFiles as $file) {
    $filePath = $storageAppPublicPath . '/' . $file;
    if (file_exists($filePath)) {
        chmod($filePath, 0644);
    }
}

echo "\n4. 🧪 TESTE DE FUNCIONALIDADE:\n";

$allWorking = true;

foreach ($targetFiles as $file) {
    echo "   Testando: {$file}\n";
    
    $storageFile = $storageAppPublicPath . '/' . $file;
    $publicFile = $publicStoragePath . '/' . $file;
    
    // Verificar no storage
    if (file_exists($storageFile)) {
        $size = number_format(filesize($storageFile) / 1024, 2);
        echo "      ✅ Storage: {$size} KB\n";
    } else {
        echo "      ❌ Storage: NÃO ENCONTRADO\n";
        $allWorking = false;
        continue;
    }
    
    // Verificar via public
    if (file_exists($publicFile)) {
        echo "      ✅ Público: ACESSÍVEL\n";
    } else {
        echo "      ❌ Público: NÃO ACESSÍVEL\n";
        $allWorking = false;
    }
    
    // Testar URL
    $url = 'https://' . $_SERVER['HTTP_HOST'] . '/storage/' . $file;
    echo "      URL: {$url}\n";
    
    // Usar cURL para testar
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        echo "      ✅ HTTP: {$httpCode} - FUNCIONANDO\n";
    } else {
        echo "      ❌ HTTP: {$httpCode} - NÃO FUNCIONANDO\n";
        $allWorking = false;
    }
    
    echo "\n";
}

echo "5. 📋 RESULTADO FINAL:\n";

if ($linkCreated && $allWorking) {
    echo "🎉 SUCESSO COMPLETO!\n";
    echo "✅ Storage link criado e funcionando\n";
    echo "✅ Todos os arquivos acessíveis\n";
    echo "✅ URLs respondendo corretamente\n";
    echo "\n🔄 TESTE AGORA:\n";
    echo "   https://{$_SERVER['HTTP_HOST']}/hierarchy/branding?node_id=1\n";
    echo "   https://{$_SERVER['HTTP_HOST']}/storage/branding/logo_1757903080_TjT4xPWeew.png\n";
} else {
    echo "⚠️ AINDA HÁ PROBLEMAS!\n";
    echo "\n🔧 COMANDOS PARA EXECUTAR VIA SSH:\n";
    echo "ssh user@{$_SERVER['HTTP_HOST']}\n";
    echo "cd {$baseDir}\n";
    echo "sudo rm -rf public/storage\n";
    echo "sudo ln -sf \$(pwd)/storage/app/public \$(pwd)/public/storage\n";
    echo "sudo chmod -R 755 storage/app/public/\n";
    echo "sudo chmod -R 755 public/storage\n";
    echo "sudo chown -R www-data:www-data storage/app/public/\n";
    echo "sudo chown -R www-data:www-data public/storage\n";
    echo "\n🧪 TESTAR:\n";
    echo "curl -I https://{$_SERVER['HTTP_HOST']}/storage/branding/logo_1757903080_TjT4xPWeew.png\n";
}

echo "\n✅ Correção final executada!\n";

// Auto-destruição após 3 horas
$creationTime = filemtime(__FILE__);
if (time() - $creationTime > 10800) { // 3 horas
    @unlink(__FILE__);
    echo "\n🔒 Script removido automaticamente por segurança.\n";
}
?>
