<?php

// Solução simples para erro 419
// Execute: php fix-419-simple.php

echo "🔧 Solução simples para erro 419...\n";

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

// 3. Gerar nova APP_KEY
echo "\n🔑 Gerando nova APP_KEY...\n";
$keyCommand = 'php artisan key:generate --force 2>&1';
$output = shell_exec($keyCommand);
echo "Saída: $output\n";

// 4. Configurar sessões no .env
echo "\n⚙️ Configurando sessões...\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    // Remover configurações existentes
    $envContent = preg_replace('/^SESSION_DRIVER.*$/m', '', $envContent);
    $envContent = preg_replace('/^SESSION_LIFETIME.*$/m', '', $envContent);
    
    // Adicionar configurações corretas
    $envContent .= "\n# Configurações para resolver erro 419\n";
    $envContent .= "SESSION_DRIVER=file\n";
    $envContent .= "SESSION_LIFETIME=120\n";
    
    if (file_put_contents('.env', $envContent)) {
        echo "✅ Arquivo .env atualizado\n";
    } else {
        echo "❌ Erro ao atualizar .env\n";
    }
} else {
    echo "❌ Arquivo .env não encontrado\n";
}

// 5. Limpar sessões antigas
echo "\n🗑️ Limpando sessões antigas...\n";
$sessionPath = 'storage/framework/sessions';
if (is_dir($sessionPath)) {
    $files = glob($sessionPath . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "Sessões antigas removidas\n";
} else {
    mkdir($sessionPath, 0755, true);
    echo "Diretório de sessões criado\n";
}

// 6. Corrigir permissões
echo "\n🔐 Corrigindo permissões...\n";
$paths = [
    'storage/framework/sessions',
    'storage/framework/cache',
    'storage/logs'
];

foreach ($paths as $path) {
    if (is_dir($path)) {
        chmod($path, 0755);
        echo "Permissão 755 aplicada: $path\n";
    }
}

// 7. Regenerar caches
echo "\n🔄 Regenerando caches...\n";
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

// 8. Testar
echo "\n🧪 Testando...\n";
$testCommands = [
    'php artisan --version',
    'php artisan config:show app.key'
];

foreach ($testCommands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "Saída: $output\n";
    }
}

echo "\n🎉 Correção concluída!\n";
echo "\n📋 Configurações aplicadas:\n";
echo "- APP_KEY gerada\n";
echo "- SESSION_DRIVER=file\n";
echo "- SESSION_LIFETIME=120\n";
echo "- Sessões antigas removidas\n";
echo "- Permissões corrigidas\n";
echo "- Caches regenerados\n";
echo "\n✅ Agora teste o login:\n";
echo "1. Desenvolvimento: http://127.0.0.1:8001/login\n";
echo "2. Produção: https://srv971263.hstgr.cloud/login\n";
echo "\n🔍 Se ainda houver problemas:\n";
echo "1. Limpe o cache do navegador (Ctrl+F5)\n";
echo "2. Tente em uma aba anônima\n";
echo "3. Verifique se o JavaScript está habilitado\n";
echo "4. Execute: chmod -R 755 storage/\n";
echo "5. Execute: chown -R www-data:www-data storage/\n";
