<?php

// Script para verificar problema com rotas
// Execute: php check-routes-issue.php

echo "🔍 Verificando problema com rotas...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Verificar arquivo de rotas
echo "\n🛣️ Verificando arquivo de rotas...\n";

$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    echo "✅ Arquivo routes/web.php existe\n";
    
    $content = file_get_contents($routesFile);
    echo "Tamanho do arquivo: " . strlen($content) . " caracteres\n";
    
    // Verificar se contém rotas do dashboard
    if (strpos($content, 'dashboard') !== false) {
        echo "✅ Contém rotas do dashboard\n";
    } else {
        echo "❌ NÃO contém rotas do dashboard\n";
    }
    
    // Verificar se contém middleware
    if (strpos($content, 'middleware') !== false) {
        echo "✅ Contém middleware\n";
    } else {
        echo "❌ NÃO contém middleware\n";
    }
    
    // Verificar se contém auth
    if (strpos($content, 'auth') !== false) {
        echo "✅ Contém auth\n";
    } else {
        echo "❌ NÃO contém auth\n";
    }
    
    // Mostrar parte do conteúdo
    echo "\n📋 Parte do conteúdo do arquivo:\n";
    echo substr($content, 0, 1000) . "...\n";
    
} else {
    echo "❌ Arquivo routes/web.php não existe\n";
}

// 3. Verificar rotas registradas
echo "\n🛣️ Verificando rotas registradas...\n";

$routeCommand = 'php artisan route:list --name=dashboard 2>&1';
$output = shell_exec($routeCommand);
echo "Rotas do dashboard:\n$output\n";

// 4. Verificar se o problema é com o middleware
echo "\n🔧 Verificando middleware...\n";

$middlewareFile = 'bootstrap/app.php';
if (file_exists($middlewareFile)) {
    echo "✅ Arquivo bootstrap/app.php existe\n";
    
    $content = file_get_contents($middlewareFile);
    if (strpos($content, 'auth') !== false) {
        echo "✅ Middleware auth encontrado\n";
    } else {
        echo "❌ Middleware auth não encontrado\n";
    }
    
    if (strpos($content, 'web') !== false) {
        echo "✅ Middleware web encontrado\n";
    } else {
        echo "❌ Middleware web não encontrado\n";
    }
    
    // Mostrar parte do conteúdo
    echo "\n📋 Parte do conteúdo do arquivo:\n";
    echo substr($content, 0, 1000) . "...\n";
    
} else {
    echo "❌ Arquivo bootstrap/app.php não existe\n";
}

// 5. Verificar se o problema é com o componente sidebar
echo "\n🔧 Verificando componente sidebar...\n";

$componentFile = 'app/View/Components/DynamicSidebar.php';
if (file_exists($componentFile)) {
    echo "⚠️ Componente DynamicSidebar existe - Pode causar erro\n";
    
    $content = file_get_contents($componentFile);
    if (strpos($content, 'class DynamicSidebar') !== false) {
        echo "✅ Classe DynamicSidebar encontrada\n";
    } else {
        echo "❌ Classe DynamicSidebar não encontrada\n";
    }
    
    if (strpos($content, 'public function render()') !== false) {
        echo "✅ Método render() encontrado\n";
    } else {
        echo "❌ Método render() não encontrado\n";
    }
    
    // Mostrar parte do conteúdo
    echo "\n📋 Parte do conteúdo do componente:\n";
    echo substr($content, 0, 500) . "...\n";
    
} else {
    echo "✅ Componente DynamicSidebar não existe\n";
}

// 6. Verificar se as views usam o componente
echo "\n📄 Verificando views que usam o componente...\n";

$views = [
    'resources/views/dashboard.blade.php',
    'resources/views/dashboard/licenciados.blade.php',
    'resources/views/dashboard/users/index.blade.php'
];

foreach ($views as $view) {
    if (file_exists($view)) {
        echo "✅ $view - Existe\n";
        
        $content = file_get_contents($view);
        if (strpos($content, '<x-dynamic-sidebar') !== false) {
            echo "   ⚠️ Usa <x-dynamic-sidebar> - Pode causar erro\n";
        } else {
            echo "   ✅ Não usa <x-dynamic-sidebar>\n";
        }
        
        if (strpos($content, 'dynamic-sidebar') !== false) {
            echo "   ⚠️ Contém 'dynamic-sidebar' - Pode causar erro\n";
        } else {
            echo "   ✅ Não contém 'dynamic-sidebar'\n";
        }
    } else {
        echo "❌ $view - NÃO EXISTE\n";
    }
}

// 7. Verificar logs de erro
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

// 8. Testar se o sistema está funcionando
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

echo "\n🎉 Verificação de rotas concluída!\n";
echo "✅ Se houver problemas, execute: php emergency-reset.php\n";
