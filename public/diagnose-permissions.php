<?php

/**
 * DIAGNÓSTICO COMPLETO DE PERMISSÕES
 * Execute via: https://srv971263.hstgr.cloud/diagnose-permissions.php
 */

header('Content-Type: text/plain; charset=utf-8');

echo "🔍 DIAGNÓSTICO COMPLETO DE PERMISSÕES\n";
echo "====================================\n\n";

$baseDir = dirname(__DIR__);

echo "📁 Diretório base: {$baseDir}\n";
echo "🌐 Servidor: " . ($_SERVER['SERVER_NAME'] ?? 'N/A') . "\n";
echo "👤 Usuário PHP: " . (function_exists('posix_getpwuid') ? posix_getpwuid(posix_geteuid())['name'] : get_current_user()) . "\n";
echo "🔧 Versão PHP: " . PHP_VERSION . "\n\n";

// DIAGNÓSTICO 1: ESTRUTURA DE DIRETÓRIOS
echo "1. 📂 ESTRUTURA DE DIRETÓRIOS:\n";

$requiredDirs = [
    'storage',
    'storage/app',
    'storage/app/public', 
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap',
    'bootstrap/cache'
];

foreach ($requiredDirs as $dir) {
    $fullPath = $baseDir . '/' . $dir;
    
    if (is_dir($fullPath)) {
        $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
        $writable = is_writable($fullPath);
        $readable = is_readable($fullPath);
        
        echo "   📁 {$dir}:\n";
        echo "      Permissões: {$perms}\n";
        echo "      Gravável: " . ($writable ? '✅ SIM' : '❌ NÃO') . "\n";
        echo "      Legível: " . ($readable ? '✅ SIM' : '❌ NÃO') . "\n";
        
        // Informações do proprietário
        if (function_exists('posix_getpwuid')) {
            $stat = stat($fullPath);
            $owner = posix_getpwuid($stat['uid']);
            $group = posix_getgrgid($stat['gid']);
            echo "      Proprietário: {$owner['name']} ({$stat['uid']})\n";
            echo "      Grupo: {$group['name']} ({$stat['gid']})\n";
        }
        
        echo "\n";
    } else {
        echo "   📁 {$dir}: ❌ NÃO EXISTE\n\n";
    }
}

// DIAGNÓSTICO 2: ARQUIVOS CRÍTICOS
echo "2. 📄 ARQUIVOS CRÍTICOS:\n";

$criticalFiles = [
    'storage/logs/laravel.log',
    '.env',
    'composer.json'
];

foreach ($criticalFiles as $file) {
    $fullPath = $baseDir . '/' . $file;
    
    echo "   📄 {$file}:\n";
    
    if (file_exists($fullPath)) {
        $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
        $writable = is_writable($fullPath);
        $readable = is_readable($fullPath);
        $size = filesize($fullPath);
        
        echo "      Existe: ✅ SIM\n";
        echo "      Permissões: {$perms}\n";
        echo "      Gravável: " . ($writable ? '✅ SIM' : '❌ NÃO') . "\n";
        echo "      Legível: " . ($readable ? '✅ SIM' : '❌ NÃO') . "\n";
        echo "      Tamanho: {$size} bytes\n";
        
        // Para o log, mostrar últimas linhas
        if ($file === 'storage/logs/laravel.log' && $readable && $size > 0) {
            $content = file_get_contents($fullPath);
            $lines = explode("\n", trim($content));
            $lastLines = array_slice($lines, -3);
            
            echo "      Últimas linhas:\n";
            foreach ($lastLines as $line) {
                if (!empty(trim($line))) {
                    echo "        " . substr($line, 0, 80) . "\n";
                }
            }
        }
        
    } else {
        echo "      Existe: ❌ NÃO\n";
    }
    
    echo "\n";
}

// DIAGNÓSTICO 3: TESTES DE ESCRITA
echo "3. 🧪 TESTES DE ESCRITA:\n";

$testDirs = [
    'storage/framework/views' => 'Views Cache',
    'bootstrap/cache' => 'Bootstrap Cache',
    'storage/logs' => 'Logs Directory'
];

foreach ($testDirs as $dir => $name) {
    $fullPath = $baseDir . '/' . $dir;
    
    echo "   🧪 {$name} ({$dir}):\n";
    
    if (!is_dir($fullPath)) {
        echo "      Status: ❌ DIRETÓRIO NÃO EXISTE\n\n";
        continue;
    }
    
    // Teste de escrita
    $testFile = $fullPath . '/test_' . time() . '.tmp';
    $testContent = "Teste de escrita - " . date('Y-m-d H:i:s');
    
    $writeResult = @file_put_contents($testFile, $testContent);
    
    if ($writeResult !== false) {
        echo "      Escrita: ✅ SUCESSO ({$writeResult} bytes)\n";
        
        // Teste de leitura
        $readContent = @file_get_contents($testFile);
        if ($readContent === $testContent) {
            echo "      Leitura: ✅ SUCESSO\n";
        } else {
            echo "      Leitura: ❌ FALHA\n";
        }
        
        // Remover arquivo de teste
        @unlink($testFile);
        echo "      Limpeza: ✅ ARQUIVO REMOVIDO\n";
        
    } else {
        echo "      Escrita: ❌ FALHA\n";
        
        // Tentar descobrir o motivo
        $error = error_get_last();
        if ($error) {
            echo "      Erro: " . $error['message'] . "\n";
        }
    }
    
    echo "\n";
}

// DIAGNÓSTICO 4: INFORMAÇÕES DO SISTEMA
echo "4. 🖥️  INFORMAÇÕES DO SISTEMA:\n";

echo "   Sistema Operacional: " . PHP_OS . "\n";
echo "   Arquitetura: " . php_uname('m') . "\n";

// Verificar se é Windows, Linux, etc.
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    echo "   Tipo: Windows\n";
    echo "   Usuário: " . get_current_user() . "\n";
} else {
    echo "   Tipo: Unix/Linux\n";
    
    // Informações detalhadas do usuário
    if (function_exists('posix_getpwuid')) {
        $userInfo = posix_getpwuid(posix_geteuid());
        echo "   Usuário efetivo: {$userInfo['name']} (UID: {$userInfo['uid']})\n";
        echo "   Grupo efetivo: " . posix_getgrgid(posix_getegid())['name'] . " (GID: " . posix_getegid() . ")\n";
        echo "   Diretório home: {$userInfo['dir']}\n";
        echo "   Shell: {$userInfo['shell']}\n";
    }
}

// Verificar servidor web
if (isset($_SERVER['SERVER_SOFTWARE'])) {
    echo "   Servidor Web: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
}

echo "\n";

// DIAGNÓSTICO 5: CONFIGURAÇÕES PHP
echo "5. ⚙️  CONFIGURAÇÕES PHP RELEVANTES:\n";

$phpSettings = [
    'file_uploads' => 'Upload de arquivos',
    'upload_max_filesize' => 'Tamanho máximo de upload',
    'post_max_size' => 'Tamanho máximo POST',
    'max_execution_time' => 'Tempo máximo de execução',
    'memory_limit' => 'Limite de memória',
    'open_basedir' => 'Restrição de diretório',
    'safe_mode' => 'Modo seguro (deprecated)'
];

foreach ($phpSettings as $setting => $description) {
    $value = ini_get($setting);
    echo "   {$description}: " . ($value ?: 'N/A') . "\n";
}

echo "\n";

// DIAGNÓSTICO 6: RECOMENDAÇÕES
echo "6. 💡 RECOMENDAÇÕES:\n";

$issues = [];
$recommendations = [];

// Verificar problemas comuns
foreach ($requiredDirs as $dir) {
    $fullPath = $baseDir . '/' . $dir;
    if (!is_dir($fullPath)) {
        $issues[] = "Diretório {$dir} não existe";
        $recommendations[] = "Criar diretório: mkdir -p {$fullPath}";
    } elseif (!is_writable($fullPath)) {
        $issues[] = "Diretório {$dir} não é gravável";
        $recommendations[] = "Ajustar permissões: chmod 755 {$fullPath}";
    }
}

// Verificar arquivo de log
$logFile = $baseDir . '/storage/logs/laravel.log';
if (!file_exists($logFile)) {
    $issues[] = "Arquivo laravel.log não existe";
    $recommendations[] = "Criar arquivo: touch {$logFile}";
} elseif (!is_writable($logFile)) {
    $issues[] = "Arquivo laravel.log não é gravável";
    $recommendations[] = "Ajustar permissões: chmod 644 {$logFile}";
}

if (empty($issues)) {
    echo "   🎉 NENHUM PROBLEMA DETECTADO!\n";
    echo "   ✅ Todas as permissões estão corretas\n";
    echo "   ✅ Todos os diretórios existem\n";
    echo "   ✅ Todos os testes de escrita passaram\n";
} else {
    echo "   ⚠️  PROBLEMAS DETECTADOS:\n";
    foreach ($issues as $issue) {
        echo "      • {$issue}\n";
    }
    
    echo "\n   🔧 SOLUÇÕES RECOMENDADAS:\n";
    foreach ($recommendations as $rec) {
        echo "      • {$rec}\n";
    }
    
    echo "\n   🚀 CORREÇÃO AUTOMÁTICA:\n";
    echo "      Execute: https://srv971263.hstgr.cloud/fix-critical-permissions.php?execute=fix-now\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "✅ Diagnóstico completo finalizado!\n";
echo "⏰ Executado em: " . date('Y-m-d H:i:s') . "\n";

?>
