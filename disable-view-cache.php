<?php

// Script para desabilitar cache de views definitivamente
// Execute: php disable-view-cache.php

echo "🔧 Desabilitando cache de views...\n";

// 1. Verificar se o arquivo .env existe
if (!file_exists('.env')) {
    echo "❌ Arquivo .env não encontrado!\n";
    exit(1);
}

// 2. Ler o arquivo .env
$envContent = file_get_contents('.env');

// 3. Remover configurações existentes de cache
$envContent = preg_replace('/^VIEW_CACHE_ENABLED.*$/m', '', $envContent);
$envContent = preg_replace('/^CACHE_DRIVER.*$/m', '', $envContent);
$envContent = preg_replace('/^SESSION_DRIVER.*$/m', '', $envContent);

// 4. Adicionar configurações para desabilitar cache
$envContent .= "\n# Configurações para resolver problemas de permissão\n";
$envContent .= "VIEW_CACHE_ENABLED=false\n";
$envContent .= "CACHE_DRIVER=array\n";
$envContent .= "SESSION_DRIVER=array\n";

// 5. Salvar o arquivo .env
if (file_put_contents('.env', $envContent)) {
    echo "✅ Arquivo .env atualizado com sucesso!\n";
} else {
    echo "❌ Erro ao salvar arquivo .env\n";
    exit(1);
}

// 6. Limpar caches existentes
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

// 7. Regenerar caches (exceto view cache)
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

echo "\n🎉 Cache de views desabilitado com sucesso!\n";
echo "\n📋 Configurações aplicadas:\n";
echo "- VIEW_CACHE_ENABLED=false\n";
echo "- CACHE_DRIVER=array\n";
echo "- SESSION_DRIVER=array\n";
echo "\n✅ O sistema agora não usará cache de views, evitando problemas de permissão.\n";
