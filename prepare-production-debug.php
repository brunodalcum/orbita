<?php

echo "🔧 PREPARANDO AMBIENTE DE PRODUÇÃO PARA DEBUG\n";
echo "=============================================\n\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Limpar logs antigos
echo "🧹 1. LIMPANDO LOGS ANTIGOS\n";
echo "---------------------------\n";

$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    $originalSize = filesize($logFile);
    echo "📊 Tamanho original do log: " . number_format($originalSize / 1024, 2) . " KB\n";
    
    // Fazer backup dos últimos logs
    $backupFile = 'storage/logs/laravel-backup-' . date('Y-m-d-H-i-s') . '.log';
    if ($originalSize > 1024 * 1024) { // Se maior que 1MB
        echo "💾 Fazendo backup do log atual...\n";
        copy($logFile, $backupFile);
        echo "✅ Backup criado: {$backupFile}\n";
        
        // Limpar log atual
        file_put_contents($logFile, '');
        echo "🧹 Log principal limpo\n";
    } else {
        echo "ℹ️  Log pequeno, não precisa limpar\n";
    }
} else {
    echo "⚠️  Arquivo de log não existe, será criado automaticamente\n";
}
echo "\n";

// 3. Verificar permissões
echo "🔒 2. VERIFICANDO E CORRIGINDO PERMISSÕES\n";
echo "-----------------------------------------\n";

$directories = [
    'storage',
    'storage/logs',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/app',
    'bootstrap/cache'
];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $readable = is_readable($dir);
        $writable = is_writable($dir);
        echo "📁 {$dir}: Legível=" . ($readable ? '✅' : '❌') . " Gravável=" . ($writable ? '✅' : '❌');
        
        if (!$writable) {
            echo " → Tentando corrigir...";
            if (chmod($dir, 0755)) {
                echo " ✅ Corrigido";
            } else {
                echo " ❌ Falhou";
            }
        }
        echo "\n";
    } else {
        echo "❌ {$dir}: Diretório não existe\n";
    }
}
echo "\n";

// 4. Limpar caches
echo "🧹 3. LIMPANDO CACHES\n";
echo "--------------------\n";

$commands = [
    'php artisan config:clear',
    'php artisan route:clear', 
    'php artisan view:clear',
    'php artisan cache:clear'
];

foreach ($commands as $command) {
    echo "⚡ Executando: {$command}\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "   " . trim($output) . "\n";
    }
}
echo "\n";

// 5. Criar arquivo de teste para verificar escrita
echo "📝 4. TESTANDO ESCRITA DE LOGS\n";
echo "------------------------------\n";

$testMessage = "🧪 TESTE DE LOG - " . date('Y-m-d H:i:s') . " - Script de preparação executado";
$logContent = "[" . date('Y-m-d H:i:s') . "] production.INFO: {$testMessage}\n";

if (file_put_contents($logFile, $logContent, FILE_APPEND | LOCK_EX)) {
    echo "✅ Teste de escrita no log: OK\n";
    echo "📄 Mensagem de teste adicionada ao log\n";
} else {
    echo "❌ Erro ao escrever no log\n";
}
echo "\n";

// 6. Verificar configurações críticas
echo "⚙️  5. VERIFICANDO CONFIGURAÇÕES\n";
echo "-------------------------------\n";

if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    // Verificar se o debug está habilitado
    if (strpos($envContent, 'APP_DEBUG=true') !== false) {
        echo "🔍 APP_DEBUG: ✅ Habilitado (bom para debug)\n";
    } else {
        echo "🔍 APP_DEBUG: ⚠️  Desabilitado (considere habilitar temporariamente)\n";
    }
    
    // Verificar log level
    if (strpos($envContent, 'LOG_LEVEL=') !== false) {
        preg_match('/LOG_LEVEL=(.+)/', $envContent, $matches);
        $logLevel = isset($matches[1]) ? trim($matches[1]) : 'info';
        echo "📊 LOG_LEVEL: {$logLevel}\n";
    } else {
        echo "📊 LOG_LEVEL: info (padrão)\n";
    }
    
    // Verificar canal de log
    if (strpos($envContent, 'LOG_CHANNEL=') !== false) {
        preg_match('/LOG_CHANNEL=(.+)/', $envContent, $matches);
        $logChannel = isset($matches[1]) ? trim($matches[1]) : 'stack';
        echo "📡 LOG_CHANNEL: {$logChannel}\n";
    } else {
        echo "📡 LOG_CHANNEL: stack (padrão)\n";
    }
} else {
    echo "❌ Arquivo .env não encontrado\n";
}
echo "\n";

// 7. Instruções finais
echo "🎯 PREPARAÇÃO CONCLUÍDA!\n";
echo "=======================\n";
echo "O ambiente está preparado para debug. Agora:\n\n";
echo "1️⃣  Teste a criação de usuário na interface web\n";
echo "2️⃣  Execute: tail -f storage/logs/laravel.log\n";
echo "3️⃣  Ou execute: php diagnose-production-simple.php\n";
echo "4️⃣  Envie os logs completos para análise\n\n";
echo "📝 Os logs agora incluem informações detalhadas sobre:\n";
echo "   - Dados recebidos na requisição\n";
echo "   - Status da role selecionada\n";
echo "   - Verificação de email único\n";
echo "   - Dados preparados para criação\n";
echo "   - Erros específicos na criação\n\n";

?>
