<?php

// Script final para corrigir erro 419 (CSRF Token Mismatch)
// Execute: php fix-419-final.php

echo "🔧 Correção FINAL do erro 419 (CSRF Token Mismatch)...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Limpar TODOS os caches
echo "🧹 Limpando todos os caches...\n";
$clearCommands = [
    'php artisan cache:clear',
    'php artisan config:clear',
    'php artisan route:clear',
    'php artisan view:clear',
    'php artisan session:clear'
];

foreach ($clearCommands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "Saída: $output\n";
    }
}

// 3. Verificar e corrigir APP_KEY
echo "🔑 Verificando APP_KEY...\n";
$envFile = '.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    
    // Verificar se APP_KEY está vazia ou não existe
    if (!preg_match('/^APP_KEY=base64:/m', $envContent) || preg_match('/^APP_KEY=$/m', $envContent)) {
        echo "APP_KEY inválida, gerando nova...\n";
        $keyCommand = 'php artisan key:generate --force 2>&1';
        $output = shell_exec($keyCommand);
        echo "Saída: $output\n";
    } else {
        echo "APP_KEY já está configurada\n";
    }
} else {
    echo "⚠️ Arquivo .env não encontrado\n";
}

// 4. Configurar sessões corretamente
echo "⚙️ Configurando sessões...\n";
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    
    // Remover configurações existentes de sessão
    $envContent = preg_replace('/^SESSION_DRIVER.*$/m', '', $envContent);
    $envContent = preg_replace('/^SESSION_LIFETIME.*$/m', '', $envContent);
    $envContent = preg_replace('/^SESSION_ENCRYPT.*$/m', '', $envContent);
    $envContent = preg_replace('/^SESSION_PATH.*$/m', '', $envContent);
    $envContent = preg_replace('/^SESSION_DOMAIN.*$/m', '', $envContent);
    $envContent = preg_replace('/^SESSION_SECURE_COOKIE.*$/m', '', $envContent);
    $envContent = preg_replace('/^SESSION_HTTP_ONLY.*$/m', '', $envContent);
    $envContent = preg_replace('/^SESSION_SAME_SITE.*$/m', '', $envContent);
    
    // Adicionar configurações corretas
    $envContent .= "\n# Configurações de sessão para resolver erro 419\n";
    $envContent .= "SESSION_DRIVER=file\n";
    $envContent .= "SESSION_LIFETIME=120\n";
    $envContent .= "SESSION_ENCRYPT=false\n";
    $envContent .= "SESSION_PATH=/\n";
    $envContent .= "SESSION_DOMAIN=null\n";
    $envContent .= "SESSION_SECURE_COOKIE=false\n";
    $envContent .= "SESSION_HTTP_ONLY=true\n";
    $envContent .= "SESSION_SAME_SITE=lax\n";
    
    if (file_put_contents($envFile, $envContent)) {
        echo "✅ Configurações de sessão atualizadas\n";
    } else {
        echo "❌ Erro ao atualizar .env\n";
    }
}

// 5. Limpar sessões antigas
echo "🗑️ Limpando sessões antigas...\n";
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
echo "🔐 Corrigindo permissões...\n";
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

// 8. Testar configuração
echo "🧪 Testando configuração...\n";
$testCommands = [
    'php artisan --version',
    'php artisan config:show app.key',
    'php artisan config:show session.driver'
];

foreach ($testCommands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "Saída: $output\n";
    }
}

// 9. Criar arquivo de teste para verificar CSRF
echo "🔍 Criando teste de CSRF...\n";
$testFile = 'test-csrf.php';
$testContent = '<?php
require_once "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

echo "APP_KEY: " . config("app.key") . "\n";
echo "SESSION_DRIVER: " . config("session.driver") . "\n";
echo "CSRF Token: " . csrf_token() . "\n";
echo "Session ID: " . session_id() . "\n";
?>';

if (file_put_contents($testFile, $testContent)) {
    echo "Arquivo de teste criado: $testFile\n";
    echo "Execute: php $testFile\n";
}

echo "\n🎉 Correção FINAL do erro 419 concluída!\n";
echo "\n📋 Configurações aplicadas:\n";
echo "- APP_KEY gerada/verificada\n";
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
echo "4. Execute: php $testFile\n";
echo "\n💡 Dicas adicionais:\n";
echo "- Certifique-se de que o formulário tem @csrf\n";
echo "- Verifique se não há JavaScript bloqueando o envio\n";
echo "- Teste com diferentes navegadores\n";
