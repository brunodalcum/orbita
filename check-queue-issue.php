<?php

// Script para verificar problema com queue:clear
// Execute: php check-queue-issue.php

echo "🔍 Verificando problema com queue:clear...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Verificar configuração de queue
echo "\n📋 Verificando configuração de queue...\n";

$queueConfig = 'config/queue.php';
if (file_exists($queueConfig)) {
    echo "✅ Arquivo config/queue.php existe\n";
    
    $content = file_get_contents($queueConfig);
    if (strpos($content, 'default') !== false) {
        echo "✅ Configuração default encontrada\n";
    } else {
        echo "❌ Configuração default não encontrada\n";
    }
    
    if (strpos($content, 'sync') !== false) {
        echo "✅ Driver sync encontrado\n";
    } else {
        echo "❌ Driver sync não encontrado\n";
    }
    
    // Mostrar parte do conteúdo
    echo "\n📋 Parte do conteúdo do arquivo:\n";
    echo substr($content, 0, 1000) . "...\n";
    
} else {
    echo "❌ Arquivo config/queue.php não existe\n";
}

// 3. Verificar .env para configuração de queue
echo "\n📋 Verificando .env para configuração de queue...\n";

$envFile = '.env';
if (file_exists($envFile)) {
    echo "✅ Arquivo .env existe\n";
    
    $content = file_get_contents($envFile);
    if (strpos($content, 'QUEUE_CONNECTION') !== false) {
        echo "✅ QUEUE_CONNECTION encontrado\n";
        
        // Extrair valor
        preg_match('/QUEUE_CONNECTION=(.+)/', $content, $matches);
        if (isset($matches[1])) {
            $queueConnection = trim($matches[1]);
            echo "✅ Valor: $queueConnection\n";
        }
    } else {
        echo "❌ QUEUE_CONNECTION não encontrado\n";
    }
    
    if (strpos($content, 'QUEUE_DRIVER') !== false) {
        echo "✅ QUEUE_DRIVER encontrado\n";
        
        // Extrair valor
        preg_match('/QUEUE_DRIVER=(.+)/', $content, $matches);
        if (isset($matches[1])) {
            $queueDriver = trim($matches[1]);
            echo "✅ Valor: $queueDriver\n";
        }
    } else {
        echo "❌ QUEUE_DRIVER não encontrado\n";
    }
    
} else {
    echo "❌ Arquivo .env não existe\n";
}

// 4. Verificar se há jobs na fila
echo "\n📋 Verificando jobs na fila...\n";

$jobsTable = 'jobs';
$failedJobsTable = 'failed_jobs';

// Verificar se as tabelas existem
$checkJobsCommand = 'php artisan tinker --execute="echo \'Jobs: \' . DB::table(\'jobs\')->count() . PHP_EOL; echo \'Failed Jobs: \' . DB::table(\'failed_jobs\')->count() . PHP_EOL;" 2>&1';
$output = shell_exec($checkJobsCommand);
echo "Resultado: $output\n";

// 5. Verificar se o problema é com o comando específico
echo "\n📋 Testando comandos de queue individualmente...\n";

$queueCommands = [
    'php artisan queue:work --help',
    'php artisan queue:restart',
    'php artisan queue:failed'
];

foreach ($queueCommands as $command) {
    echo "\n🔄 Testando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

// 6. Verificar se o problema é com o comando queue:clear especificamente
echo "\n📋 Testando queue:clear especificamente...\n";

$clearCommand = 'php artisan queue:clear --help 2>&1';
$output = shell_exec($clearCommand);
echo "Resultado: $output\n";

// 7. Verificar se há processos de queue rodando
echo "\n📋 Verificando processos de queue...\n";

$processCommand = 'ps aux | grep "queue:work" | grep -v grep 2>&1';
$output = shell_exec($processCommand);
echo "Processos de queue: $output\n";

// 8. Verificar logs de queue
echo "\n📋 Verificando logs de queue...\n";

$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "✅ Log encontrado: $logFile\n";
    
    // Buscar por erros relacionados a queue
    $grepCommand = 'grep -i "queue" ' . $logFile . ' | tail -10 2>&1';
    $output = shell_exec($grepCommand);
    echo "Últimos erros de queue:\n$output\n";
} else {
    echo "❌ Log não encontrado: $logFile\n";
}

// 9. Verificar se o problema é com o driver de queue
echo "\n📋 Verificando driver de queue...\n";

$driverCommand = 'php artisan tinker --execute="echo \'Queue Driver: \' . config(\'queue.default\') . PHP_EOL;" 2>&1';
$output = shell_exec($driverCommand);
echo "Resultado: $output\n";

// 10. Verificar se há jobs pendentes
echo "\n📋 Verificando jobs pendentes...\n";

$pendingCommand = 'php artisan tinker --execute="echo \'Jobs pendentes: \' . DB::table(\'jobs\')->count() . PHP_EOL;" 2>&1';
$output = shell_exec($pendingCommand);
echo "Resultado: $output\n";

// 11. Verificar se o problema é com o comando específico
echo "\n📋 Testando queue:clear com timeout...\n";

$timeoutCommand = 'timeout 10 php artisan queue:clear 2>&1';
$output = shell_exec($timeoutCommand);
echo "Resultado: $output\n";

// 12. Verificar se o problema é com o comando específico
echo "\n📋 Testando queue:clear com diferentes opções...\n";

$options = [
    'php artisan queue:clear --help',
    'php artisan queue:clear --force',
    'php artisan queue:clear --timeout=5'
];

foreach ($options as $option) {
    echo "\n🔄 Testando: $option\n";
    $output = shell_exec($option . ' 2>&1');
    echo "Resultado: $output\n";
}

echo "\n🎉 Verificação de queue concluída!\n";
echo "✅ Se o problema persistir, use o script fix-500-simple.php\n";
echo "✅ O script fix-500-simple.php não usa queue:clear\n";
