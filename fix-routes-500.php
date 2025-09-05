<?php

// Script para corrigir erro 500 nas rotas
// Execute: php fix-routes-500.php

echo "🔧 Corrigindo erro 500 nas rotas...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Limpar cache completamente
echo "\n🗑️ Limpando cache completamente...\n";

$commands = [
    'php artisan view:clear',
    'php artisan config:clear',
    'php artisan route:clear',
    'php artisan cache:clear',
    'php artisan optimize:clear'
];

foreach ($commands as $command) {
    echo "\n🔄 Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

// 3. Verificar logs de erro
echo "\n📋 Verificando logs de erro...\n";

$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "✅ Log encontrado: $logFile\n";
    
    // Mostrar últimas 50 linhas do log
    $output = shell_exec('tail -50 ' . $logFile . ' 2>&1');
    echo "Últimas 50 linhas do log:\n$output\n";
} else {
    echo "❌ Log não encontrado: $logFile\n";
}

// 4. Verificar se as rotas existem
echo "\n🛣️ Verificando rotas...\n";

$routeCommand = 'php artisan route:list --name=dashboard 2>&1';
$output = shell_exec($routeCommand);
echo "Rotas do dashboard:\n$output\n";

// 5. Verificar se o usuário existe
echo "\n👤 Verificando usuário...\n";

$userCommand = '
use App\Models\User;
$user = User::where("email", "admin@dspay.com.br")->first();
if ($user) {
    echo "✅ Usuário encontrado: " . $user->name . PHP_EOL;
    echo "Status: " . ($user->is_active ? "Ativo" : "Inativo") . PHP_EOL;
    echo "Role: " . ($user->role ? $user->role->display_name : "Nenhum") . PHP_EOL;
} else {
    echo "❌ Usuário não encontrado!" . PHP_EOL;
}
';

$output = shell_exec("php artisan tinker --execute=\"$userCommand\" 2>&1");
echo "Resultado: $output\n";

// 6. Verificar se as views existem
echo "\n📄 Verificando views...\n";

$views = [
    'resources/views/dashboard.blade.php',
    'resources/views/dashboard/licenciados.blade.php',
    'resources/views/dashboard/users/index.blade.php'
];

foreach ($views as $view) {
    if (file_exists($view)) {
        echo "✅ $view - Existe\n";
        
        // Verificar se usa sidebar dinâmico
        $content = file_get_contents($view);
        if (strpos($content, '<x-dynamic-sidebar') !== false) {
            echo "   ⚠️ Usa <x-dynamic-sidebar> - Removendo...\n";
            
            // Remover sidebar dinâmico
            $newContent = str_replace('<x-dynamic-sidebar />', '', $content);
            $newContent = str_replace('<x-dynamic-sidebar/>', '', $newContent);
            
            if ($newContent !== $content) {
                file_put_contents($view, $newContent);
                echo "   ✅ Sidebar dinâmico removido\n";
            }
        } else {
            echo "   ✅ Não usa sidebar dinâmico\n";
        }
    } else {
        echo "❌ $view - NÃO EXISTE\n";
    }
}

// 7. Verificar se o componente DynamicSidebar existe
echo "\n🔧 Verificando componente DynamicSidebar...\n";

$componentFile = 'app/View/Components/DynamicSidebar.php';
if (file_exists($componentFile)) {
    echo "⚠️ Componente DynamicSidebar existe - Removendo...\n";
    unlink($componentFile);
    echo "✅ Componente removido\n";
} else {
    echo "✅ Componente não existe\n";
}

// 8. Verificar se a view do sidebar existe
echo "\n🔧 Verificando view do sidebar...\n";

$sidebarFile = 'resources/views/components/dynamic-sidebar.blade.php';
if (file_exists($sidebarFile)) {
    echo "⚠️ View do sidebar existe - Removendo...\n";
    unlink($sidebarFile);
    echo "✅ View removida\n";
} else {
    echo "✅ View não existe\n";
}

// 9. Verificar permissões
echo "\n🔐 Verificando permissões...\n";

$directories = [
    'storage/framework/views',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        echo "✅ $dir - Permissões: $perms\n";
    } else {
        echo "❌ $dir - NÃO EXISTE\n";
    }
}

// 10. Corrigir permissões
echo "\n🔐 Corrigindo permissões...\n";

$permissionCommands = [
    'chmod -R 755 storage/',
    'chmod -R 755 bootstrap/cache/',
    'chown -R www-data:www-data storage/',
    'chown -R www-data:www-data bootstrap/cache/'
];

foreach ($permissionCommands as $command) {
    echo "\n🔄 Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

// 11. Recriar cache básico
echo "\n🗂️ Recriando cache básico...\n";

$cacheCommands = [
    'php artisan config:cache',
    'php artisan route:cache'
];

foreach ($cacheCommands as $command) {
    echo "\n🔄 Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

// 12. Testar se o sistema está funcionando
echo "\n🧪 Testando sistema...\n";

$testCommand = '
try {
    echo "Testando conexão com banco..." . PHP_EOL;
    $user = App\Models\User::where("email", "admin@dspay.com.br")->first();
    if ($user) {
        echo "✅ Usuário encontrado: " . $user->name . PHP_EOL;
    } else {
        echo "❌ Usuário não encontrado!" . PHP_EOL;
    }
    
    echo "Testando rotas..." . PHP_EOL;
    $routes = [
        "dashboard" => route("dashboard"),
        "licenciados" => route("dashboard.licenciados"),
        "users" => route("dashboard.users.index")
    ];
    
    foreach ($routes as $name => $url) {
        echo "✅ Rota $name: $url" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . PHP_EOL;
}
';

$output = shell_exec("php artisan tinker --execute=\"$testCommand\" 2>&1");
echo "Resultado: $output\n";

echo "\n🎉 Correção concluída!\n";
echo "✅ Teste as rotas em: https://srv971263.hstgr.cloud/dashboard\n";
echo "✅ Se ainda houver erro, verifique os logs acima\n";
