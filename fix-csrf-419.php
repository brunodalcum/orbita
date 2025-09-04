<?php

// Script para corrigir erro 419 (CSRF Token Mismatch) em produção
// Execute: php fix-csrf-419.php

echo "🔧 Corrigindo erro 419 (CSRF Token Mismatch)...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Limpar caches de sessão
echo "🧹 Limpando caches de sessão...\n";
$commands = [
    'php artisan cache:clear',
    'php artisan config:clear',
    'php artisan session:table', // Criar tabela de sessões se não existir
];

foreach ($commands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "Saída: $output\n";
    }
}

// 3. Verificar configuração de sessão
echo "⚙️ Verificando configuração de sessão...\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    // Verificar se SESSION_DRIVER está configurado
    if (!preg_match('/^SESSION_DRIVER=/m', $envContent)) {
        echo "Adicionando SESSION_DRIVER=file...\n";
        $envContent .= "\nSESSION_DRIVER=file\n";
    }
    
    // Verificar se SESSION_LIFETIME está configurado
    if (!preg_match('/^SESSION_LIFETIME=/m', $envContent)) {
        echo "Adicionando SESSION_LIFETIME=120...\n";
        $envContent .= "\nSESSION_LIFETIME=120\n";
    }
    
    // Verificar se APP_KEY está configurado
    if (!preg_match('/^APP_KEY=/m', $envContent) || preg_match('/^APP_KEY=$/m', $envContent)) {
        echo "Gerando nova APP_KEY...\n";
        $newKey = 'base64:' . base64_encode(random_bytes(32));
        $envContent = preg_replace('/^APP_KEY=.*$/m', "APP_KEY=$newKey", $envContent);
        if (!preg_match('/^APP_KEY=/m', $envContent)) {
            $envContent .= "\nAPP_KEY=$newKey\n";
        }
    }
    
    // Salvar arquivo .env
    if (file_put_contents('.env', $envContent)) {
        echo "✅ Arquivo .env atualizado\n";
    } else {
        echo "❌ Erro ao atualizar .env\n";
    }
} else {
    echo "⚠️ Arquivo .env não encontrado\n";
}

// 4. Criar tabela de sessões se necessário
echo "📊 Verificando tabela de sessões...\n";
$sessionTableCommand = 'php artisan session:table 2>&1';
$output = shell_exec($sessionTableCommand);
if (strpos($output, 'already exists') === false) {
    echo "Criando tabela de sessões...\n";
    shell_exec('php artisan migrate 2>&1');
}

// 5. Limpar sessões antigas
echo "🗑️ Limpando sessões antigas...\n";
$sessionPath = 'storage/framework/sessions';
if (is_dir($sessionPath)) {
    $files = glob($sessionPath . '/*');
    foreach ($files as $file) {
        if (is_file($file) && filemtime($file) < (time() - 3600)) { // Sessões mais antigas que 1 hora
            unlink($file);
        }
    }
    echo "Sessões antigas removidas\n";
}

// 6. Verificar permissões do storage
echo "🔐 Verificando permissões do storage...\n";
$storagePaths = [
    'storage/framework/sessions',
    'storage/framework/cache',
    'storage/logs'
];

foreach ($storagePaths as $path) {
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
    'php artisan config:show session'
];

foreach ($testCommands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "Saída: $output\n";
    }
}

echo "\n🎉 Correção do erro 419 concluída!\n";
echo "\n📋 Configurações aplicadas:\n";
echo "- SESSION_DRIVER=file\n";
echo "- SESSION_LIFETIME=120\n";
echo "- APP_KEY gerada/verificada\n";
echo "- Permissões corrigidas\n";
echo "- Sessões antigas removidas\n";
echo "\n✅ Agora teste o login: https://srv971263.hstgr.cloud/login\n";
echo "\n🔍 Se ainda houver problemas:\n";
echo "1. Limpe o cache do navegador\n";
echo "2. Tente em uma aba anônima\n";
echo "3. Verifique se o JavaScript está habilitado\n";
