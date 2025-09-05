<?php

// Script para corrigir problemas de assets em produção
// Execute: php fix-production-assets.php

echo "🔧 Corrigindo problemas de assets em produção...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Verificar configurações atuais
echo "📋 Verificando configurações atuais...\n";
echo "Diretório atual: " . getcwd() . "\n";
echo "APP_ENV: " . (getenv('APP_ENV') ?: 'não definido') . "\n";
echo "APP_DEBUG: " . (getenv('APP_DEBUG') ?: 'não definido') . "\n";

// 3. Verificar arquivo .env
echo "\n⚙️ Verificando arquivo .env...\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    // Verificar configurações importantes
    $importantConfigs = [
        'APP_NAME',
        'APP_ENV',
        'APP_DEBUG',
        'APP_URL',
        'VITE_APP_NAME'
    ];
    
    foreach ($importantConfigs as $config) {
        if (preg_match("/^$config=(.+)$/m", $envContent, $matches)) {
            echo "✅ $config: " . $matches[1] . "\n";
        } else {
            echo "❌ $config: não encontrado\n";
        }
    }
} else {
    echo "❌ Arquivo .env não encontrado\n";
}

// 4. Verificar assets
echo "\n📁 Verificando assets...\n";
$assetPaths = [
    'public/build',
    'public/build/manifest.json',
    'public/build/css',
    'public/build/js',
    'public/hot',
    'public/images',
    'public/images/dspay-logo.png'
];

foreach ($assetPaths as $path) {
    if (file_exists($path)) {
        echo "✅ Asset encontrado: $path\n";
    } else {
        echo "❌ Asset não encontrado: $path\n";
    }
}

// 5. Verificar se Vite está configurado
echo "\n⚙️ Verificando configuração do Vite...\n";
if (file_exists('vite.config.js')) {
    echo "✅ vite.config.js encontrado\n";
} else {
    echo "❌ vite.config.js não encontrado\n";
}

if (file_exists('package.json')) {
    echo "✅ package.json encontrado\n";
    $packageContent = file_get_contents('package.json');
    if (strpos($packageContent, 'vite') !== false) {
        echo "✅ Vite configurado no package.json\n";
    } else {
        echo "❌ Vite não configurado no package.json\n";
    }
} else {
    echo "❌ package.json não encontrado\n";
}

// 6. Verificar se há arquivo hot
echo "\n🔥 Verificando arquivo hot...\n";
if (file_exists('public/hot')) {
    echo "✅ Arquivo hot encontrado (modo desenvolvimento)\n";
    $hotContent = file_get_contents('public/hot');
    echo "Conteúdo: $hotContent\n";
} else {
    echo "✅ Arquivo hot não encontrado (modo produção)\n";
}

// 7. Verificar manifest.json
echo "\n📄 Verificando manifest.json...\n";
if (file_exists('public/build/manifest.json')) {
    echo "✅ manifest.json encontrado\n";
    $manifestContent = file_get_contents('public/build/manifest.json');
    $manifest = json_decode($manifestContent, true);
    if ($manifest) {
        echo "✅ manifest.json válido\n";
        echo "Assets encontrados: " . count($manifest) . "\n";
    } else {
        echo "❌ manifest.json inválido\n";
    }
} else {
    echo "❌ manifest.json não encontrado\n";
}

// 8. Verificar se há assets compilados
echo "\n📦 Verificando assets compilados...\n";
$buildDir = 'public/build';
if (is_dir($buildDir)) {
    $files = glob($buildDir . '/*');
    echo "Arquivos em $buildDir:\n";
    foreach ($files as $file) {
        if (is_file($file)) {
            echo "  - " . basename($file) . " (" . number_format(filesize($file)) . " bytes)\n";
        } else {
            echo "  - " . basename($file) . " (diretório)\n";
        }
    }
} else {
    echo "❌ Diretório $buildDir não encontrado\n";
}

// 9. Corrigir configurações para produção
echo "\n🔧 Corrigindo configurações para produção...\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    // Fazer backup
    file_put_contents('.env.backup', $envContent);
    echo "✅ Backup do .env criado\n";
    
    // Garantir que APP_URL está correto para produção
    $envContent = preg_replace('/^APP_URL=.*$/m', 'APP_URL=https://srv971263.hstgr.cloud', $envContent);
    
    // Garantir que APP_ENV está como production
    $envContent = preg_replace('/^APP_ENV=.*$/m', 'APP_ENV=production', $envContent);
    
    // Garantir que APP_DEBUG está como false
    $envContent = preg_replace('/^APP_DEBUG=.*$/m', 'APP_DEBUG=false', $envContent);
    
    if (file_put_contents('.env', $envContent)) {
        echo "✅ Arquivo .env corrigido para produção\n";
    } else {
        echo "❌ Erro ao corrigir .env\n";
    }
} else {
    echo "❌ Arquivo .env não encontrado\n";
}

// 10. Limpar caches
echo "\n🧹 Limpando caches...\n";
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

// 11. Regenerar caches
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

// 12. Verificar configurações finais
echo "\n🧪 Verificando configurações finais...\n";
$testCommands = [
    'php artisan --version',
    'php artisan config:show app.env',
    'php artisan config:show app.debug',
    'php artisan config:show app.url'
];

foreach ($testCommands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "Saída: $output\n";
    }
}

echo "\n🎉 Correção de assets concluída!\n";
echo "\n📋 Configurações aplicadas:\n";
echo "- APP_ENV=production\n";
echo "- APP_DEBUG=false\n";
echo "- APP_URL=https://srv971263.hstgr.cloud\n";
echo "- Caches limpos e regenerados\n";
echo "\n✅ Agora teste:\n";
echo "1. Página principal: https://srv971263.hstgr.cloud/\n";
echo "2. Dashboard: https://srv971263.hstgr.cloud/dashboard\n";
echo "3. Login: https://srv971263.hstgr.cloud/login\n";
echo "\n🔍 Se ainda houver problemas:\n";
echo "1. Verifique se os assets foram compilados: npm run build\n";
echo "2. Verifique se o manifest.json existe\n";
echo "3. Verifique se o arquivo hot foi removido\n";
echo "4. Verifique os logs: tail -f storage/logs/laravel.log\n";
echo "5. Execute: chown -R www-data:www-data storage/\n";
echo "6. Execute: chown -R www-data:www-data bootstrap/cache/\n";
