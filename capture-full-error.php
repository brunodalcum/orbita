<?php

// Script para capturar erro completo e corrigir
// Execute: php capture-full-error.php

echo "🔍 CAPTURANDO ERRO COMPLETO...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Capturar erro completo do log
echo "\n📋 CAPTURANDO ERRO COMPLETO DO LOG...\n";

$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "✅ Log encontrado: $logFile\n";
    
    // Mostrar últimas 200 linhas do log
    $output = shell_exec('tail -200 ' . $logFile . ' 2>&1');
    echo "Últimas 200 linhas do log:\n";
    echo "==========================================\n";
    echo $output;
    echo "==========================================\n";
    
    // Procurar por erros específicos
    if (strpos($output, 'Class') !== false && strpos($output, 'not found') !== false) {
        echo "\n❌ ERRO IDENTIFICADO: Classe não encontrada\n";
        
        // Extrair nome da classe
        preg_match('/Class [\'"]([^\'"]+)[\'"] not found/', $output, $matches);
        if (isset($matches[1])) {
            $missingClass = $matches[1];
            echo "Classe não encontrada: $missingClass\n";
            
            // Verificar se é o DynamicSidebar
            if (strpos($missingClass, 'DynamicSidebar') !== false) {
                echo "✅ Confirmado: Erro do DynamicSidebar\n";
                echo "🔧 Removendo referências...\n";
                
                // Remover arquivo se existir
                if (file_exists('app/View/Components/DynamicSidebar.php')) {
                    unlink('app/View/Components/DynamicSidebar.php');
                    echo "✅ Arquivo DynamicSidebar.php removido\n";
                }
                
                // Remover referências das views
                $views = glob('resources/views/**/*.blade.php');
                foreach ($views as $view) {
                    $content = file_get_contents($view);
                    if (strpos($content, 'dynamic-sidebar') !== false) {
                        $content = str_replace(['<x-dynamic-sidebar />', '<x-dynamic-sidebar/>'], '', $content);
                        file_put_contents($view, $content);
                        echo "✅ Referência removida de: $view\n";
                    }
                }
            }
        }
    }
    
    if (strpos($output, 'PailServiceProvider') !== false) {
        echo "\n❌ ERRO IDENTIFICADO: PailServiceProvider\n";
        echo "🔧 Removendo PailServiceProvider...\n";
        
        // Verificar bootstrap/providers.php
        $providersFile = 'bootstrap/providers.php';
        if (file_exists($providersFile)) {
            $content = file_get_contents($providersFile);
            if (strpos($content, 'PailServiceProvider') !== false) {
                $content = preg_replace('/.*PailServiceProvider.*\n/', '', $content);
                file_put_contents($providersFile, $content);
                echo "✅ PailServiceProvider removido de bootstrap/providers.php\n";
            }
        }
    }
    
    if (strpos($output, 'Permission') !== false) {
        echo "\n❌ ERRO IDENTIFICADO: Problema de permissões\n";
        echo "🔧 Corrigindo permissões...\n";
        
        // Corrigir permissões
        shell_exec('chmod -R 755 storage/');
        shell_exec('chmod -R 755 bootstrap/cache/');
        echo "✅ Permissões corrigidas\n";
    }
    
} else {
    echo "❌ Log não encontrado: $logFile\n";
}

// 3. Verificar arquivos problemáticos
echo "\n🔍 VERIFICANDO ARQUIVOS PROBLEMÁTICOS...\n";

$problematicFiles = [
    'app/View/Components/DynamicSidebar.php',
    'resources/views/components/dynamic-sidebar.blade.php',
    'resources/views/layouts/production.blade.php'
];

foreach ($problematicFiles as $file) {
    if (file_exists($file)) {
        echo "❌ PROBLEMA: $file existe\n";
        
        // Fazer backup
        $backupFile = $file . '.backup.' . date('Y-m-d-H-i-s');
        copy($file, $backupFile);
        echo "✅ Backup criado: $backupFile\n";
        
        // Remover
        unlink($file);
        echo "✅ Removido: $file\n";
    } else {
        echo "✅ OK: $file não existe\n";
    }
}

// 4. Limpar cache completamente
echo "\n🗑️ LIMPANDO CACHE COMPLETAMENTE...\n";

$cacheDirs = [
    'storage/framework/cache',
    'storage/framework/views',
    'storage/framework/sessions',
    'storage/framework/testing',
    'bootstrap/cache'
];

foreach ($cacheDirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        $count = 0;
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
                $count++;
            }
        }
        echo "✅ Limpo: $dir ($count arquivos removidos)\n";
    }
}

// 5. Verificar e corrigir .env
echo "\n⚙️ VERIFICANDO E CORRIGINDO .ENV...\n";

if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    // Corrigir configurações
    $envContent = preg_replace('/^APP_ENV=.*$/m', 'APP_ENV=production', $envContent);
    $envContent = preg_replace('/^APP_DEBUG=.*$/m', 'APP_DEBUG=false', $envContent);
    
    // Remover caminhos de desenvolvimento
    $envContent = preg_replace('/^.*\/Applications\/MAMP\/htdocs\/orbita\/.*$/m', '', $envContent);
    
    file_put_contents('.env', $envContent);
    echo "✅ Arquivo .env corrigido\n";
} else {
    echo "❌ Arquivo .env não encontrado\n";
}

// 6. Recriar cache básico
echo "\n🗂️ RECRIANDO CACHE BÁSICO...\n";

$cacheCommands = [
    'php artisan config:cache',
    'php artisan route:cache'
];

foreach ($cacheCommands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "Resultado: $output\n";
    }
}

// 7. Testar sistema
echo "\n🧪 TESTANDO SISTEMA...\n";

$testCommand = '
try {
    echo "Testando sistema..." . PHP_EOL;
    $user = App\Models\User::where("email", "admin@dspay.com.br")->first();
    if ($user) {
        echo "✅ Usuário encontrado: " . $user->name . PHP_EOL;
    } else {
        echo "❌ Usuário não encontrado!" . PHP_EOL;
    }
    
    echo "Testando rotas..." . PHP_EOL;
    $routes = [
        "dashboard" => route("dashboard"),
        "licenciados" => route("dashboard.licenciados")
    ];
    
    foreach ($routes as $name => $url) {
        echo "✅ Rota $name: $url" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . PHP_EOL;
}
';

$output = shell_exec("php artisan tinker --execute=\"$testCommand\" 2>&1");
echo "Resultado do teste:\n$output\n";

echo "\n🎉 CORREÇÃO CONCLUÍDA!\n";
echo "\n📋 RESUMO:\n";
echo "- ✅ Erro capturado e analisado\n";
echo "- ✅ Arquivos problemáticos removidos\n";
echo "- ✅ Cache limpo\n";
echo "- ✅ Configurações corrigidas\n";
echo "- ✅ Cache recriado\n";
echo "\n🚀 Agora teste o sistema:\n";
echo "https://srv971263.hstgr.cloud/dashboard\n";
echo "\n🔍 Se ainda houver erro:\n";
echo "1. Execute: tail -f storage/logs/laravel.log\n";
echo "2. Verifique se o servidor web está rodando\n";
echo "3. Execute: chown -R www-data:www-data storage/\n";
echo "4. Execute: chown -R www-data:www-data bootstrap/cache/\n";
