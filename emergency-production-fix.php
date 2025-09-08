<?php

echo "🚨 CORREÇÃO DE EMERGÊNCIA PARA PRODUÇÃO\n";
echo "=" . str_repeat("=", 50) . "\n\n";

// Função para executar comando e mostrar resultado
function runCommand($command, $description) {
    echo "🔧 $description...\n";
    
    $output = [];
    $returnCode = 0;
    exec($command . ' 2>&1', $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "✅ Sucesso\n";
        if (!empty($output)) {
            echo "   " . implode("\n   ", array_slice($output, -3)) . "\n";
        }
    } else {
        echo "❌ Erro (código: $returnCode)\n";
        if (!empty($output)) {
            echo "   " . implode("\n   ", array_slice($output, -5)) . "\n";
        }
    }
    echo "\n";
    
    return $returnCode === 0;
}

// 1. Limpar todos os caches
runCommand('php artisan cache:clear', 'Limpando cache da aplicação');
runCommand('php artisan config:clear', 'Limpando cache de configuração');
runCommand('php artisan route:clear', 'Limpando cache de rotas');
runCommand('php artisan view:clear', 'Limpando cache de views');

// 2. Corrigir permissões críticas
echo "🔧 Corrigindo permissões...\n";

$directories = [
    'storage',
    'storage/logs',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'bootstrap/cache'
];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        if (chmod($dir, 0775)) {
            echo "✅ Permissões corrigidas: $dir\n";
        } else {
            echo "❌ Falha ao corrigir: $dir\n";
        }
    } else {
        if (mkdir($dir, 0775, true)) {
            echo "✅ Diretório criado: $dir\n";
        } else {
            echo "❌ Falha ao criar: $dir\n";
        }
    }
}
echo "\n";

// 3. Recriar caches essenciais
runCommand('php artisan config:cache', 'Recriando cache de configuração');
runCommand('php artisan route:cache', 'Recriando cache de rotas');

// 4. Verificar se as tabelas existem
echo "🔧 Verificando banco de dados...\n";
try {
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Verificar conexão
    DB::connection()->getPdo();
    echo "✅ Conexão com banco OK\n";
    
    // Verificar tabelas críticas
    $tables = ['users', 'roles', 'permissions'];
    foreach ($tables as $table) {
        try {
            $exists = DB::getSchemaBuilder()->hasTable($table);
            if ($exists) {
                echo "✅ Tabela '$table' existe\n";
            } else {
                echo "❌ Tabela '$table' não existe\n";
            }
        } catch (Exception $e) {
            echo "❌ Erro ao verificar tabela '$table': " . $e->getMessage() . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erro de banco: " . $e->getMessage() . "\n";
    echo "🔧 Tentando executar migrações...\n";
    runCommand('php artisan migrate --force', 'Executando migrações');
}
echo "\n";

// 5. Testar rotas críticas
echo "🔧 Testando rotas críticas...\n";
try {
    $routes = Route::getRoutes();
    $criticalRoutes = ['login', 'dashboard', 'permissions.index'];
    
    foreach ($criticalRoutes as $routeName) {
        if ($routes->hasNamedRoute($routeName)) {
            echo "✅ Rota '$routeName' OK\n";
        } else {
            echo "❌ Rota '$routeName' não encontrada\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Erro ao verificar rotas: " . $e->getMessage() . "\n";
}
echo "\n";

// 6. Criar arquivo de status
$status = [
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION,
    'laravel_working' => class_exists('Illuminate\Foundation\Application'),
    'storage_writable' => is_writable('storage'),
    'env_exists' => file_exists('.env')
];

file_put_contents('production-status.json', json_encode($status, JSON_PRETTY_PRINT));
echo "📄 Status salvo em production-status.json\n\n";

echo str_repeat("=", 50) . "\n";
echo "🏁 CORREÇÃO DE EMERGÊNCIA CONCLUÍDA\n";
echo "📋 Execute 'cat production-status.json' para ver o status\n";
echo str_repeat("=", 50) . "\n";

?>
