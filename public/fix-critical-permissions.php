<?php

/**
 * CORREÇÃO CRÍTICA DE PERMISSÕES - LARAVEL
 * Execute via: https://srv971263.hstgr.cloud/fix-critical-permissions.php
 */

// Verificar se é uma requisição válida
if (!isset($_GET['execute']) || $_GET['execute'] !== 'fix-now') {
    http_response_code(403);
    die('❌ Acesso negado. Use: ?execute=fix-now');
}

// Definir cabeçalho para texto plano
header('Content-Type: text/plain; charset=utf-8');

echo "🚨 CORREÇÃO CRÍTICA DE PERMISSÕES - LARAVEL\n";
echo "==========================================\n\n";

$baseDir = dirname(__DIR__);
$errors = [];
$success = [];

echo "📁 Diretório base: {$baseDir}\n\n";

// ETAPA 1: FORÇAR CRIAÇÃO DE ESTRUTURA COMPLETA
echo "1. 🏗️  CRIANDO ESTRUTURA COMPLETA:\n";

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
    
    if (!is_dir($fullPath)) {
        echo "   📁 Criando: {$dir}\n";
        
        if (@mkdir($fullPath, 0777, true)) {
            $success[] = "Diretório {$dir} criado";
            echo "      ✅ Sucesso\n";
        } else {
            $errors[] = "Falha ao criar {$dir}";
            echo "      ❌ Falha\n";
        }
    } else {
        echo "   📁 Existe: {$dir} ✅\n";
    }
}

// ETAPA 2: FORÇAR CRIAÇÃO DE ARQUIVOS CRÍTICOS
echo "\n2. 📄 CRIANDO ARQUIVOS CRÍTICOS:\n";

$criticalFiles = [
    'storage/logs/laravel.log' => '',
    'storage/framework/cache/.gitignore' => '*' . "\n" . '!.gitignore',
    'storage/framework/sessions/.gitignore' => '*' . "\n" . '!.gitignore',
    'storage/framework/views/.gitignore' => '*' . "\n" . '!.gitignore',
    'bootstrap/cache/.gitignore' => '*' . "\n" . '!.gitignore'
];

foreach ($criticalFiles as $file => $content) {
    $fullPath = $baseDir . '/' . $file;
    
    echo "   📄 Arquivo: {$file}\n";
    
    // Garantir que o diretório pai existe
    $dir = dirname($fullPath);
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
    
    if (!file_exists($fullPath)) {
        if (@file_put_contents($fullPath, $content) !== false) {
            $success[] = "Arquivo {$file} criado";
            echo "      ✅ Criado\n";
        } else {
            $errors[] = "Falha ao criar {$file}";
            echo "      ❌ Falha\n";
        }
    } else {
        echo "      ✅ Já existe\n";
    }
}

// ETAPA 3: FORÇAR PERMISSÕES MÁXIMAS
echo "\n3. 🔧 FORÇANDO PERMISSÕES MÁXIMAS:\n";

$permissionDirs = [
    'storage' => 0777,
    'bootstrap/cache' => 0777
];

foreach ($permissionDirs as $dir => $perm) {
    $fullPath = $baseDir . '/' . $dir;
    
    echo "   🔧 Ajustando: {$dir}\n";
    
    // Usar comando recursivo mais agressivo
    $octalPerm = sprintf('%o', $perm);
    
    if (@chmod($fullPath, $perm)) {
        echo "      ✅ Permissão {$octalPerm} aplicada\n";
        $success[] = "Permissões {$dir} ajustadas";
        
        // Aplicar recursivamente usando iterator
        try {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($fullPath, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );
            
            $count = 0;
            foreach ($iterator as $item) {
                @chmod($item->getPathname(), $item->isDir() ? 0777 : 0666);
                $count++;
            }
            
            echo "      ✅ {$count} itens processados recursivamente\n";
            
        } catch (Exception $e) {
            echo "      ⚠️  Recursão falhou: " . $e->getMessage() . "\n";
        }
        
    } else {
        $errors[] = "Falha ao ajustar permissões {$dir}";
        echo "      ❌ Falha na permissão\n";
    }
}

// ETAPA 4: LIMPEZA AGRESSIVA DE CACHE
echo "\n4. 🧹 LIMPEZA AGRESSIVA DE CACHE:\n";

$cacheDirs = [
    'storage/framework/views' => 'Views compiladas',
    'storage/framework/cache/data' => 'Cache de dados',
    'bootstrap/cache' => 'Bootstrap cache'
];

foreach ($cacheDirs as $dir => $desc) {
    $fullPath = $baseDir . '/' . $dir;
    
    echo "   🧹 Limpando: {$desc}\n";
    
    if (is_dir($fullPath)) {
        $files = glob($fullPath . '/*');
        $removed = 0;
        
        foreach ($files as $file) {
            if (is_file($file) && basename($file) !== '.gitignore') {
                if (@unlink($file)) {
                    $removed++;
                }
            }
        }
        
        echo "      ✅ {$removed} arquivos removidos\n";
        $success[] = "{$desc}: {$removed} arquivos limpos";
    } else {
        echo "      ⚠️  Diretório não encontrado\n";
    }
}

// ETAPA 5: TESTES CRÍTICOS DE FUNCIONALIDADE
echo "\n5. 🧪 TESTES CRÍTICOS DE FUNCIONALIDADE:\n";

$tests = [
    'Views Cache' => 'storage/framework/views/test_critical.php',
    'Bootstrap Cache' => 'bootstrap/cache/test_critical.php',
    'Log File' => 'storage/logs/laravel.log'
];

$allTestsPassed = true;

foreach ($tests as $testName => $testPath) {
    $fullPath = $baseDir . '/' . $testPath;
    $testContent = "<?php\n// Teste crítico - " . date('Y-m-d H:i:s') . "\n// {$testName}\n";
    
    echo "   🧪 Testando: {$testName}\n";
    
    if ($testName === 'Log File') {
        // Teste especial para log
        $logMessage = "[" . date('Y-m-d H:i:s') . "] testing.CRITICAL: Teste crítico de escrita no log\n";
        $result = @file_put_contents($fullPath, $logMessage, FILE_APPEND | LOCK_EX);
    } else {
        // Teste normal de escrita
        $result = @file_put_contents($fullPath, $testContent);
    }
    
    if ($result !== false) {
        echo "      ✅ PASSOU - {$result} bytes escritos\n";
        $success[] = "{$testName}: teste passou";
        
        // Remover arquivo de teste (exceto log)
        if ($testName !== 'Log File') {
            @unlink($fullPath);
        }
    } else {
        echo "      ❌ FALHOU - não conseguiu escrever\n";
        $errors[] = "{$testName}: teste falhou";
        $allTestsPassed = false;
    }
}

// ETAPA 6: VERIFICAÇÃO FINAL E DIAGNÓSTICO
echo "\n6. 🔍 VERIFICAÇÃO FINAL:\n";

$finalCheck = [
    'storage/framework/views' => 'Views Cache',
    'bootstrap/cache' => 'Bootstrap Cache', 
    'storage/logs' => 'Logs Directory'
];

echo "   📊 Status dos diretórios críticos:\n";

foreach ($finalCheck as $dir => $name) {
    $fullPath = $baseDir . '/' . $dir;
    
    if (is_dir($fullPath)) {
        $writable = is_writable($fullPath);
        $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
        
        echo "      {$name}: {$perms} " . ($writable ? '✅ GRAVÁVEL' : '❌ NÃO GRAVÁVEL') . "\n";
        
        if (!$writable) {
            $allTestsPassed = false;
        }
    } else {
        echo "      {$name}: ❌ NÃO EXISTE\n";
        $allTestsPassed = false;
    }
}

// ETAPA 7: RELATÓRIO FINAL
echo "\n7. 📋 RELATÓRIO FINAL:\n";

echo "   ✅ Sucessos: " . count($success) . "\n";
foreach ($success as $s) {
    echo "      • {$s}\n";
}

if (!empty($errors)) {
    echo "\n   ❌ Erros: " . count($errors) . "\n";
    foreach ($errors as $e) {
        echo "      • {$e}\n";
    }
}

echo "\n" . str_repeat("=", 50) . "\n";

if ($allTestsPassed && empty($errors)) {
    echo "🎉 SUCESSO COMPLETO!\n";
    echo "✅ Todos os diretórios estão funcionais\n";
    echo "✅ Todos os arquivos foram criados\n";
    echo "✅ Permissões foram ajustadas\n";
    echo "✅ Cache foi limpo\n";
    echo "✅ Todos os testes passaram\n";
    echo "\n🚀 TESTE AGORA:\n";
    echo "   https://srv971263.hstgr.cloud/hierarchy/branding\n";
    echo "\n🎯 O Laravel deve funcionar perfeitamente!\n";
    
} else {
    echo "⚠️  CORREÇÃO PARCIAL!\n";
    echo "\n🔧 COMANDOS PARA EXECUTAR NO SERVIDOR:\n";
    echo "\n# Comandos como root:\n";
    echo "sudo mkdir -p {$baseDir}/storage/logs\n";
    echo "sudo mkdir -p {$baseDir}/bootstrap/cache\n";
    echo "sudo touch {$baseDir}/storage/logs/laravel.log\n";
    echo "sudo chown -R www-data:www-data {$baseDir}/storage/\n";
    echo "sudo chown -R www-data:www-data {$baseDir}/bootstrap/cache/\n";
    echo "sudo chmod -R 777 {$baseDir}/storage/\n";
    echo "sudo chmod -R 777 {$baseDir}/bootstrap/cache/\n";
    echo "sudo chmod 666 {$baseDir}/storage/logs/laravel.log\n";
    
    echo "\n# Para Nginx:\n";
    echo "sudo chown -R nginx:nginx {$baseDir}/storage/\n";
    echo "sudo chown -R nginx:nginx {$baseDir}/bootstrap/cache/\n";
    
    echo "\n# Verificar usuário do servidor web:\n";
    echo "ps aux | grep -E '(apache|nginx|httpd)'\n";
}

echo "\n✅ Correção crítica finalizada!\n";
echo "⏰ Executado em: " . date('Y-m-d H:i:s') . "\n";

?>
