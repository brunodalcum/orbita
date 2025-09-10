<?php

echo "🔧 Corrigindo diretório bootstrap/cache em produção...\n";

// Função para criar diretório com permissões
function createDirectoryWithPermissions($path, $permissions = 0755) {
    if (!is_dir($path)) {
        if (mkdir($path, $permissions, true)) {
            echo "✅ Diretório criado: $path\n";
        } else {
            echo "❌ Erro ao criar diretório: $path\n";
        }
    } else {
        echo "ℹ️ Diretório já existe: $path\n";
    }
    
    // Definir permissões
    if (chmod($path, $permissions)) {
        echo "✅ Permissões definidas para $path: " . decoct($permissions) . "\n";
    } else {
        echo "❌ Erro ao definir permissões para $path\n";
    }
}

// Função para executar comando Artisan
function runArtisanCommand($command) {
    echo "⚡ Executando: php artisan $command\n";
    $output = [];
    $return_var = 0;
    exec("php artisan $command 2>&1", $output, $return_var);
    
    if ($return_var === 0) {
        echo "✅ Comando executado com sucesso\n";
    } else {
        echo "⚠️ Comando executado (pode ter avisos): " . implode("\n", $output) . "\n";
    }
}

try {
    // 1. Criar diretórios necessários
    echo "\n📁 Criando diretórios necessários...\n";
    createDirectoryWithPermissions('bootstrap/cache', 0777);
    createDirectoryWithPermissions('storage/framework/cache', 0777);
    createDirectoryWithPermissions('storage/framework/sessions', 0777);
    createDirectoryWithPermissions('storage/framework/views', 0777);
    createDirectoryWithPermissions('storage/logs', 0777);
    createDirectoryWithPermissions('storage/app', 0755);
    createDirectoryWithPermissions('storage/app/public', 0755);

    // 2. Definir permissões recursivas
    echo "\n🔐 Definindo permissões recursivas...\n";
    
    // Função recursiva para definir permissões
    function setPermissionsRecursive($path, $dirPermissions = 0755, $filePermissions = 0644) {
        if (is_dir($path)) {
            chmod($path, $dirPermissions);
            $items = scandir($path);
            foreach ($items as $item) {
                if ($item != '.' && $item != '..') {
                    setPermissionsRecursive($path . '/' . $item, $dirPermissions, $filePermissions);
                }
            }
        } else {
            chmod($path, $filePermissions);
        }
    }
    
    if (is_dir('bootstrap/cache')) {
        setPermissionsRecursive('bootstrap/cache', 0777, 0666);
        echo "✅ Permissões definidas para bootstrap/cache\n";
    }
    
    if (is_dir('storage')) {
        setPermissionsRecursive('storage', 0777, 0666);
        echo "✅ Permissões definidas para storage\n";
    }

    // 3. Limpar caches existentes
    echo "\n🧹 Limpando caches existentes...\n";
    runArtisanCommand('config:clear');
    runArtisanCommand('route:clear');
    runArtisanCommand('view:clear');
    runArtisanCommand('cache:clear');

    // 4. Recriar caches otimizados
    echo "\n⚡ Recriando caches otimizados...\n";
    runArtisanCommand('config:cache');
    runArtisanCommand('route:cache');
    runArtisanCommand('view:cache');

    // 5. Otimizar autoloader
    echo "\n🚀 Otimizando autoloader...\n";
    exec('composer dump-autoload --optimize 2>&1', $output, $return_var);
    if ($return_var === 0) {
        echo "✅ Autoloader otimizado\n";
    } else {
        echo "⚠️ Autoloader: " . implode("\n", $output) . "\n";
    }

    // 6. Verificação final
    echo "\n📋 Verificação final:\n";
    
    $directories = [
        'bootstrap/cache',
        'storage/framework/cache',
        'storage/framework/sessions',
        'storage/framework/views',
        'storage/logs'
    ];
    
    foreach ($directories as $dir) {
        if (is_dir($dir) && is_writable($dir)) {
            echo "✅ $dir - OK (existe e é gravável)\n";
        } else {
            echo "❌ $dir - PROBLEMA (não existe ou não é gravável)\n";
        }
    }

    echo "\n🎉 Correção concluída com sucesso!\n";
    echo "\n💡 Se ainda houver problemas, execute no servidor:\n";
    echo "sudo chown -R www-data:www-data bootstrap/cache storage\n";
    echo "sudo chmod -R 775 bootstrap/cache storage\n";

} catch (Exception $e) {
    echo "❌ Erro durante a execução: " . $e->getMessage() . "\n";
    exit(1);
}

?>
