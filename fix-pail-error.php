<?php

// Script para corrigir erro do PailServiceProvider
// Execute: php fix-pail-error.php

echo "🔧 Corrigindo erro do PailServiceProvider...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Verificar arquivo config/app.php
echo "📋 Verificando config/app.php...\n";
$configFile = 'config/app.php';
if (file_exists($configFile)) {
    $configContent = file_get_contents($configFile);
    
    // Verificar se há referência ao PailServiceProvider
    if (strpos($configContent, 'PailServiceProvider') !== false) {
        echo "❌ Encontrada referência ao PailServiceProvider em config/app.php\n";
        
        // Fazer backup
        file_put_contents($configFile . '.backup', $configContent);
        echo "✅ Backup criado: config/app.php.backup\n";
        
        // Remover referência ao PailServiceProvider
        $configContent = preg_replace('/.*PailServiceProvider.*\n/', '', $configContent);
        
        if (file_put_contents($configFile, $configContent)) {
            echo "✅ Referência ao PailServiceProvider removida\n";
        } else {
            echo "❌ Erro ao remover referência\n";
        }
    } else {
        echo "✅ Nenhuma referência ao PailServiceProvider encontrada\n";
    }
} else {
    echo "❌ Arquivo config/app.php não encontrado\n";
}

// 3. Verificar bootstrap/providers.php
echo "\n📋 Verificando bootstrap/providers.php...\n";
$providersFile = 'bootstrap/providers.php';
if (file_exists($providersFile)) {
    $providersContent = file_get_contents($providersFile);
    
    // Verificar se há referência ao PailServiceProvider
    if (strpos($providersContent, 'PailServiceProvider') !== false) {
        echo "❌ Encontrada referência ao PailServiceProvider em bootstrap/providers.php\n";
        
        // Fazer backup
        file_put_contents($providersFile . '.backup', $providersContent);
        echo "✅ Backup criado: bootstrap/providers.php.backup\n";
        
        // Remover referência ao PailServiceProvider
        $providersContent = preg_replace('/.*PailServiceProvider.*\n/', '', $providersContent);
        
        if (file_put_contents($providersFile, $providersContent)) {
            echo "✅ Referência ao PailServiceProvider removida\n";
        } else {
            echo "❌ Erro ao remover referência\n";
        }
    } else {
        echo "✅ Nenhuma referência ao PailServiceProvider encontrada\n";
    }
} else {
    echo "❌ Arquivo bootstrap/providers.php não encontrado\n";
}

// 4. Verificar composer.json
echo "\n📋 Verificando composer.json...\n";
if (file_exists('composer.json')) {
    $composerContent = file_get_contents('composer.json');
    $composerData = json_decode($composerContent, true);
    
    if (isset($composerData['require']['laravel/pail'])) {
        echo "❌ Laravel Pail encontrado em composer.json\n";
        echo "⚠️ Considere remover: composer remove laravel/pail\n";
    } else {
        echo "✅ Laravel Pail não encontrado em composer.json\n";
    }
} else {
    echo "❌ Arquivo composer.json não encontrado\n";
}

// 5. Limpar caches
echo "\n🧹 Limpando caches...\n";
$commands = [
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

// 6. Tentar limpar cache novamente
echo "\n🔄 Tentando limpar cache novamente...\n";
$output = shell_exec('php artisan cache:clear 2>&1');
if ($output) {
    echo "Saída: $output\n";
    
    // Se ainda houver erro, tentar alternativas
    if (strpos($output, 'PailServiceProvider') !== false) {
        echo "\n⚠️ Erro persistente. Tentando alternativas...\n";
        
        // Tentar remover cache manualmente
        $cachePaths = [
            'storage/framework/cache',
            'storage/framework/views',
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
    }
}

// 7. Verificar se o problema foi resolvido
echo "\n🧪 Testando se o problema foi resolvido...\n";
$testOutput = shell_exec('php artisan --version 2>&1');
if ($testOutput) {
    echo "Saída: $testOutput\n";
    
    if (strpos($testOutput, 'PailServiceProvider') === false) {
        echo "✅ Problema resolvido!\n";
    } else {
        echo "❌ Problema ainda persiste\n";
    }
}

echo "\n🎉 Correção do PailServiceProvider concluída!\n";
echo "\n📋 Ações realizadas:\n";
echo "- Verificado config/app.php\n";
echo "- Verificado bootstrap/providers.php\n";
echo "- Verificado composer.json\n";
echo "- Limpado caches\n";
echo "- Removido referências ao PailServiceProvider\n";
echo "\n✅ Agora teste:\n";
echo "php artisan cache:clear\n";
echo "php artisan --version\n";
echo "\n🔍 Se ainda houver problemas:\n";
echo "1. Execute: composer remove laravel/pail\n";
echo "2. Execute: composer dump-autoload\n";
echo "3. Execute: php artisan config:clear\n";
