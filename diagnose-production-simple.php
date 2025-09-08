<?php

echo "🔍 DIAGNÓSTICO SIMPLES DE PRODUÇÃO\n";
echo "==================================\n\n";

// 1. Verificar se estamos no diretório correto
echo "📁 1. VERIFICANDO DIRETÓRIO\n";
echo "---------------------------\n";
echo "Diretório atual: " . getcwd() . "\n";
echo "Arquivo .env existe: " . (file_exists('.env') ? 'SIM' : 'NÃO') . "\n";
echo "Arquivo composer.json existe: " . (file_exists('composer.json') ? 'SIM' : 'NÃO') . "\n";
echo "Diretório vendor existe: " . (is_dir('vendor') ? 'SIM' : 'NÃO') . "\n";
echo "Diretório storage existe: " . (is_dir('storage') ? 'SIM' : 'NÃO') . "\n\n";

// 2. Verificar permissões
echo "🔒 2. VERIFICANDO PERMISSÕES\n";
echo "----------------------------\n";
if (is_dir('storage')) {
    echo "📁 storage/ - Legível: " . (is_readable('storage') ? 'SIM' : 'NÃO') . " | Gravável: " . (is_writable('storage') ? 'SIM' : 'NÃO') . "\n";
    
    if (is_dir('storage/logs')) {
        echo "📁 storage/logs/ - Legível: " . (is_readable('storage/logs') ? 'SIM' : 'NÃO') . " | Gravável: " . (is_writable('storage/logs') ? 'SIM' : 'NÃO') . "\n";
        
        if (file_exists('storage/logs/laravel.log')) {
            echo "📄 storage/logs/laravel.log - Legível: " . (is_readable('storage/logs/laravel.log') ? 'SIM' : 'NÃO') . " | Gravável: " . (is_writable('storage/logs/laravel.log') ? 'SIM' : 'NÃO') . "\n";
            echo "📊 Tamanho do log: " . number_format(filesize('storage/logs/laravel.log') / 1024, 2) . " KB\n";
        } else {
            echo "⚠️  Arquivo storage/logs/laravel.log não existe\n";
        }
    } else {
        echo "❌ Diretório storage/logs/ não existe\n";
    }
} else {
    echo "❌ Diretório storage/ não existe\n";
}
echo "\n";

// 3. Verificar configurações do .env
echo "⚙️  3. VERIFICANDO CONFIGURAÇÕES .ENV\n";
echo "------------------------------------\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    $envLines = explode("\n", $envContent);
    
    $configs = [];
    foreach ($envLines as $line) {
        $line = trim($line);
        if (!empty($line) && strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $configs[trim($key)] = trim($value);
        }
    }
    
    $importantKeys = ['APP_ENV', 'APP_DEBUG', 'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'LOG_CHANNEL'];
    
    foreach ($importantKeys as $key) {
        if (isset($configs[$key])) {
            if ($key === 'DB_PASSWORD') {
                echo "🔐 {$key}: " . (empty($configs[$key]) ? '[VAZIO]' : '[DEFINIDO]') . "\n";
            } else {
                echo "📊 {$key}: " . $configs[$key] . "\n";
            }
        } else {
            echo "❌ {$key}: NÃO DEFINIDO\n";
        }
    }
} else {
    echo "❌ Arquivo .env não encontrado\n";
}
echo "\n";

// 4. Verificar PHP e extensões
echo "🐘 4. VERIFICANDO PHP\n";
echo "--------------------\n";
echo "Versão do PHP: " . phpversion() . "\n";
echo "Extensões necessárias:\n";
$requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath'];
foreach ($requiredExtensions as $ext) {
    echo "  - {$ext}: " . (extension_loaded($ext) ? '✅ OK' : '❌ FALTANDO') . "\n";
}
echo "\n";

// 5. Testar conexão com banco (se possível)
echo "🗄️  5. TESTANDO CONEXÃO COM BANCO\n";
echo "---------------------------------\n";
if (file_exists('.env')) {
    try {
        $envContent = file_get_contents('.env');
        preg_match('/DB_HOST=(.+)/', $envContent, $hostMatch);
        preg_match('/DB_DATABASE=(.+)/', $envContent, $dbMatch);
        preg_match('/DB_USERNAME=(.+)/', $envContent, $userMatch);
        preg_match('/DB_PASSWORD=(.+)/', $envContent, $passMatch);
        preg_match('/DB_PORT=(.+)/', $envContent, $portMatch);
        
        $host = isset($hostMatch[1]) ? trim($hostMatch[1]) : 'localhost';
        $database = isset($dbMatch[1]) ? trim($dbMatch[1]) : '';
        $username = isset($userMatch[1]) ? trim($userMatch[1]) : '';
        $password = isset($passMatch[1]) ? trim($passMatch[1]) : '';
        $port = isset($portMatch[1]) ? trim($portMatch[1]) : '3306';
        
        if (!empty($database) && !empty($username)) {
            $dsn = "mysql:host={$host};port={$port};dbname={$database}";
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            
            echo "✅ Conexão com banco: OK\n";
            echo "📊 Versão MySQL: " . $pdo->query('SELECT VERSION()')->fetchColumn() . "\n";
            
            // Verificar se as tabelas existem
            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            echo "📋 Tabelas encontradas: " . count($tables) . "\n";
            
            $requiredTables = ['users', 'roles'];
            foreach ($requiredTables as $table) {
                if (in_array($table, $tables)) {
                    echo "  ✅ Tabela '{$table}' existe\n";
                    
                    // Contar registros
                    $count = $pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
                    echo "     📊 Registros: {$count}\n";
                } else {
                    echo "  ❌ Tabela '{$table}' não existe\n";
                }
            }
        } else {
            echo "❌ Configurações de banco incompletas no .env\n";
        }
    } catch (Exception $e) {
        echo "❌ Erro na conexão com banco: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ Arquivo .env não encontrado para testar banco\n";
}
echo "\n";

// 6. Verificar últimos logs (se existirem)
echo "📝 6. ÚLTIMAS ENTRADAS DO LOG\n";
echo "-----------------------------\n";
if (file_exists('storage/logs/laravel.log')) {
    $logContent = file_get_contents('storage/logs/laravel.log');
    $logLines = explode("\n", $logContent);
    $lastLines = array_slice($logLines, -20); // Últimas 20 linhas
    
    echo "Últimas 20 linhas do log:\n";
    foreach ($lastLines as $line) {
        if (!empty(trim($line))) {
            echo "  " . $line . "\n";
        }
    }
} else {
    echo "❌ Arquivo de log não encontrado\n";
}
echo "\n";

echo "🎯 DIAGNÓSTICO CONCLUÍDO!\n";
echo "========================\n";
echo "Execute este script em produção com: php diagnose-production-simple.php\n";
echo "E envie todo o resultado para análise.\n\n";

?>
