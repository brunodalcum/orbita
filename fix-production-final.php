<?php

// Script final para corrigir todos os problemas em produção
// Execute: php fix-production-final.php

echo "🚀 Correção final para produção...\n";

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

// 3. Corrigir problema do PailServiceProvider
echo "\n🔧 Corrigindo problema do PailServiceProvider...\n";
$providersFile = 'bootstrap/providers.php';
if (file_exists($providersFile)) {
    $providersContent = file_get_contents($providersFile);
    
    if (strpos($providersContent, 'PailServiceProvider') !== false) {
        echo "❌ Encontrada referência ao PailServiceProvider\n";
        
        // Fazer backup
        file_put_contents($providersFile . '.backup', $providersContent);
        echo "✅ Backup criado: bootstrap/providers.php.backup\n";
        
        // Remover referência ao PailServiceProvider
        $providersContent = preg_replace('/.*PailServiceProvider.*\n/', '', $providersContent);
        
        if (file_put_contents($providersFile, $providersContent)) {
            echo "✅ Referência ao PailServiceProvider removida\n";
        }
    } else {
        echo "✅ Nenhuma referência ao PailServiceProvider encontrada\n";
    }
}

// 4. Corrigir configurações para produção
echo "\n🔧 Corrigindo configurações para produção...\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    // Fazer backup do .env
    file_put_contents('.env.backup', $envContent);
    echo "✅ Backup do .env criado\n";
    
    // Substituir configurações de desenvolvimento
    $envContent = preg_replace('/^APP_ENV=.*$/m', 'APP_ENV=production', $envContent);
    $envContent = preg_replace('/^APP_DEBUG=.*$/m', 'APP_DEBUG=false', $envContent);
    
    // Remover TODAS as linhas com caminhos de desenvolvimento
    $envContent = preg_replace('/^.*\/Applications\/MAMP\/htdocs\/orbita\/.*$/m', '', $envContent);
    
    // Remover linhas vazias extras
    $envContent = preg_replace('/\n\s*\n\s*\n/', "\n\n", $envContent);
    
    // Adicionar configurações de produção se não existirem
    if (strpos($envContent, 'APP_ENV=production') === false) {
        $envContent .= "\nAPP_ENV=production\n";
    }
    
    if (strpos($envContent, 'APP_DEBUG=false') === false) {
        $envContent .= "\nAPP_DEBUG=false\n";
    }
    
    if (file_put_contents('.env', $envContent)) {
        echo "✅ Arquivo .env corrigido para produção\n";
    } else {
        echo "❌ Erro ao corrigir .env\n";
    }
} else {
    echo "❌ Arquivo .env não encontrado\n";
}

// 5. Verificar e criar diretórios necessários
echo "\n📁 Verificando e criando diretórios...\n";
$directories = [
    'storage/framework/views',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✅ Diretório criado: $dir\n";
        } else {
            echo "❌ Erro ao criar diretório: $dir\n";
        }
    } else {
        echo "✅ Diretório existe: $dir\n";
    }
}

// 6. Limpar TODOS os caches
echo "\n🧹 Limpando todos os caches...\n";

// Remover arquivos de cache manualmente
$cachePaths = [
    'storage/framework/cache',
    'storage/framework/views',
    'storage/framework/sessions',
    'bootstrap/cache'
];

foreach ($cachePaths as $path) {
    if (is_dir($path)) {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "Cache limpo: $path\n";
    }
}

// 7. Corrigir permissões
echo "\n🔐 Corrigindo permissões...\n";
$paths = [
    'storage',
    'storage/framework',
    'storage/framework/views',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($paths as $path) {
    if (is_dir($path)) {
        chmod($path, 0755);
        echo "Permissão 755 aplicada: $path\n";
    }
}

// 8. Regenerar caches
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

// 9. Verificar configurações finais
echo "\n🧪 Verificando configurações finais...\n";
$testCommands = [
    'php artisan --version',
    'php artisan config:show app.env',
    'php artisan config:show app.debug'
];

foreach ($testCommands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "Saída: $output\n";
    }
}

// 10. Testar se todos os problemas foram resolvidos
echo "\n🧪 Testando se todos os problemas foram resolvidos...\n";

// Testar cache clear
$cacheTest = shell_exec('php artisan cache:clear 2>&1');
if ($cacheTest && strpos($cacheTest, 'successfully') !== false) {
    echo "✅ Cache clear funcionando\n";
} else {
    echo "❌ Problema com cache clear\n";
}

// Testar view clear
$viewTest = shell_exec('php artisan view:clear 2>&1');
if ($viewTest && strpos($viewTest, 'successfully') !== false) {
    echo "✅ View clear funcionando\n";
} else {
    echo "❌ Problema com view clear\n";
}

// Verificar se não há mais referências a caminhos de desenvolvimento
$grepTest = shell_exec('grep -r "/Applications/MAMP/htdocs/orbita/" . 2>/dev/null | head -5');
if (empty($grepTest)) {
    echo "✅ Nenhuma referência a caminhos de desenvolvimento encontrada\n";
} else {
    echo "⚠️ Ainda há referências a caminhos de desenvolvimento:\n";
    echo $grepTest;
}

echo "\n🎉 Correção final concluída!\n";
echo "\n📋 Configurações aplicadas:\n";
echo "- PailServiceProvider removido\n";
echo "- APP_ENV=production\n";
echo "- APP_DEBUG=false\n";
echo "- Caminhos de desenvolvimento removidos\n";
echo "- Diretórios criados/verificados\n";
echo "- Permissões corrigidas\n";
echo "- Caches regenerados\n";
echo "\n✅ Agora teste o login em produção:\n";
echo "https://srv971263.hstgr.cloud/login\n";
echo "\n🔍 Se ainda houver problemas:\n";
echo "1. Verifique se o servidor web está rodando\n";
echo "2. Verifique se o PHP está configurado corretamente\n";
echo "3. Execute: chown -R www-data:www-data storage/\n";
echo "4. Execute: chown -R www-data:www-data bootstrap/cache/\n";
echo "5. Verifique os logs: tail -f storage/logs/laravel.log\n";
