<?php

// Script PHP para corrigir permissões em produção
// Execute: php fix-permissions.php

echo "🔧 Corrigindo permissões em produção...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Limpar caches
echo "🧹 Limpando caches...\n";
$commands = [
    'php artisan cache:clear',
    'php artisan config:clear',
    'php artisan route:clear',
    'php artisan view:clear'
];

foreach ($commands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "Saída: $output\n";
    }
}

// 3. Remover arquivos de cache
echo "🗑️ Removendo arquivos de cache...\n";
$cacheDirs = [
    'storage/framework/views',
    'storage/framework/cache',
    'storage/framework/sessions',
    'bootstrap/cache'
];

foreach ($cacheDirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "Limpo: $dir\n";
    }
}

// 4. Corrigir permissões
echo "🔐 Corrigindo permissões...\n";
$paths = ['storage', 'bootstrap/cache'];

foreach ($paths as $path) {
    if (is_dir($path)) {
        chmod($path, 0777);
        echo "Permissão 777 aplicada: $path\n";
    }
}

// 5. Desabilitar cache de views
echo "⚙️ Desabilitando cache de views...\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    // Remover configurações existentes
    $envContent = preg_replace('/^VIEW_CACHE_ENABLED.*$/m', '', $envContent);
    $envContent = preg_replace('/^CACHE_DRIVER.*$/m', '', $envContent);
    $envContent = preg_replace('/^SESSION_DRIVER.*$/m', '', $envContent);
    
    // Adicionar novas configurações
    $envContent .= "\n# Configurações para resolver problemas de permissão\n";
    $envContent .= "VIEW_CACHE_ENABLED=false\n";
    $envContent .= "CACHE_DRIVER=array\n";
    $envContent .= "SESSION_DRIVER=array\n";
    
    if (file_put_contents('.env', $envContent)) {
        echo "✅ Arquivo .env atualizado\n";
    } else {
        echo "❌ Erro ao atualizar .env\n";
    }
} else {
    echo "⚠️ Arquivo .env não encontrado\n";
}

// 6. Regenerar caches
echo "🔄 Regenerando caches...\n";
$regenerateCommands = [
    'php artisan config:cache',
    'php artisan route:cache'
];

foreach ($regenerateCommands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "Saída: $output\n";
    }
}

// 7. Testar permissões
echo "🧪 Testando permissões...\n";
$testFile = 'storage/framework/views/test_permissions.tmp';

try {
    if (file_put_contents($testFile, 'test')) {
        unlink($testFile);
        echo "✅ Permissões de escrita funcionando!\n";
    } else {
        echo "❌ Problemas de permissão detectados\n";
    }
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}

echo "\n🎉 Correção concluída!\n";
echo "\n📋 Configurações aplicadas:\n";
echo "- VIEW_CACHE_ENABLED=false\n";
echo "- CACHE_DRIVER=array\n";
echo "- SESSION_DRIVER=array\n";
echo "- Permissões: 777\n";
echo "\n✅ Agora teste o dashboard: https://srv971263.hstgr.cloud/dashboard\n";
