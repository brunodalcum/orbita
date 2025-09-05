<?php

// Script para corrigir problema específico de caminhos
// Execute: php fix-path-issue.php

echo "🔧 Corrigindo problema específico de caminhos...\n";

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
    
    // Verificar se há caminhos absolutos incorretos
    if (strpos($envContent, '/Applications/MAMP/htdocs/orbita/') !== false) {
        echo "❌ Encontrados caminhos de desenvolvimento no .env\n";
        
        // Mostrar linhas problemáticas
        $lines = explode("\n", $envContent);
        foreach ($lines as $lineNum => $line) {
            if (strpos($line, '/Applications/MAMP/htdocs/orbita/') !== false) {
                echo "Linha " . ($lineNum + 1) . ": $line\n";
            }
        }
    } else {
        echo "✅ Nenhum caminho de desenvolvimento encontrado no .env\n";
    }
    
    // Verificar configurações de ambiente
    if (strpos($envContent, 'APP_ENV=local') !== false) {
        echo "⚠️ APP_ENV está definido como 'local'\n";
    }
    
    if (strpos($envContent, 'APP_DEBUG=true') !== false) {
        echo "⚠️ APP_DEBUG está definido como 'true'\n";
    }
} else {
    echo "❌ Arquivo .env não encontrado\n";
}

// 4. Corrigir configurações
echo "\n🔧 Corrigindo configurações...\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    // Fazer backup
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
        echo "✅ Arquivo .env corrigido\n";
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

// 10. Testar se o problema foi resolvido
echo "\n🧪 Testando se o problema foi resolvido...\n";
$testOutput = shell_exec('php artisan view:clear 2>&1');
if ($testOutput) {
    echo "Saída: $testOutput\n";
    
    if (strpos($testOutput, '/Applications/MAMP/htdocs/orbita/') === false) {
        echo "✅ Problema de caminhos resolvido!\n";
    } else {
        echo "❌ Problema de caminhos ainda persiste\n";
    }
}

echo "\n🎉 Correção de caminhos concluída!\n";
echo "\n📋 Configurações aplicadas:\n";
echo "- APP_ENV=production\n";
echo "- APP_DEBUG=false\n";
echo "- Caminhos de desenvolvimento removidos\n";
echo "- Diretórios criados/verificados\n";
echo "- Permissões corrigidas\n";
echo "- Caches regenerados\n";
echo "\n✅ Agora teste:\n";
echo "php artisan view:clear\n";
echo "php artisan cache:clear\n";
echo "\n🔍 Se ainda houver problemas:\n";
echo "1. Verifique se não há mais referências a caminhos de desenvolvimento\n";
echo "2. Execute: grep -r '/Applications/MAMP/htdocs/orbita/' .\n";
echo "3. Execute: chown -R www-data:www-data storage/\n";
