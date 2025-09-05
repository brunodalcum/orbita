<?php

// Script para verificar middleware de autenticação
// Execute: php check-auth-middleware.php

echo "🔍 Verificando middleware de autenticação...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Verificar se o usuário existe e está ativo
echo "\n👤 Verificando usuário...\n";

$userCommand = '
use App\Models\User;
$user = User::where("email", "admin@dspay.com.br")->with("role")->first();
if ($user) {
    echo "✅ Usuário encontrado: " . $user->name . PHP_EOL;
    echo "Email: " . $user->email . PHP_EOL;
    echo "Status: " . ($user->is_active ? "Ativo" : "Inativo") . PHP_EOL;
    echo "Role: " . ($user->role ? $user->role->display_name : "Nenhum") . PHP_EOL;
    echo "Email verificado: " . ($user->email_verified_at ? "Sim" : "Não") . PHP_EOL;
    
    // Verificar permissões
    $permissions = $user->getPermissions();
    echo "Permissões: " . $permissions->count() . PHP_EOL;
} else {
    echo "❌ Usuário não encontrado!" . PHP_EOL;
}
';

$output = shell_exec("php artisan tinker --execute=\"$userCommand\" 2>&1");
echo "Resultado: $output\n";

// 3. Verificar rotas protegidas
echo "\n🛣️ Verificando rotas protegidas...\n";

$routeCommand = 'php artisan route:list --name=dashboard 2>&1';
$output = shell_exec($routeCommand);
echo "Rotas do dashboard:\n$output\n";

// 4. Verificar middleware
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
} else {
    echo "❌ Arquivo bootstrap/app.php não existe\n";
}

// 5. Verificar arquivo de rotas
echo "\n🛣️ Verificando arquivo de rotas...\n";

$routesFile = 'routes/web.php';
if (file_exists($routesFile)) {
    echo "✅ Arquivo routes/web.php existe\n";
    
    $content = file_get_contents($routesFile);
    if (strpos($content, 'Route::middleware') !== false) {
        echo "✅ Middleware nas rotas encontrado\n";
    } else {
        echo "❌ Middleware nas rotas não encontrado\n";
    }
    
    if (strpos($content, 'dashboard') !== false) {
        echo "✅ Rotas do dashboard encontradas\n";
    } else {
        echo "❌ Rotas do dashboard não encontradas\n";
    }
} else {
    echo "❌ Arquivo routes/web.php não existe\n";
}

// 6. Verificar se o problema é com o componente sidebar
echo "\n🔧 Verificando componente sidebar...\n";

$componentFile = 'app/View/Components/DynamicSidebar.php';
if (file_exists($componentFile)) {
    echo "⚠️ Componente DynamicSidebar existe - Pode causar erro\n";
    
    // Verificar se o componente tem erro
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
} else {
    echo "✅ Componente DynamicSidebar não existe\n";
}

// 7. Verificar se as views usam o componente
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
    } else {
        echo "❌ $view - NÃO EXISTE\n";
    }
}

// 8. Verificar logs de erro
echo "\n📋 Verificando logs de erro...\n";

$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "✅ Log encontrado: $logFile\n";
    
    // Mostrar últimas 30 linhas do log
    $output = shell_exec('tail -30 ' . $logFile . ' 2>&1');
    echo "Últimas 30 linhas do log:\n$output\n";
} else {
    echo "❌ Log não encontrado: $logFile\n";
}

// 9. Testar login
echo "\n🧪 Testando login...\n";

$loginCommand = '
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where("email", "admin@dspay.com.br")->first();
if ($user) {
    echo "Usuário: " . $user->name . PHP_EOL;
    echo "Email: " . $user->email . PHP_EOL;
    echo "Status: " . ($user->is_active ? "Ativo" : "Inativo") . PHP_EOL;
    
    // Testar senha
    if (Hash::check("admin123456", $user->password)) {
        echo "✅ Senha correta!" . PHP_EOL;
    } else {
        echo "❌ Senha incorreta!" . PHP_EOL;
    }
} else {
    echo "❌ Usuário não encontrado!" . PHP_EOL;
}
';

$output = shell_exec("php artisan tinker --execute=\"$loginCommand\" 2>&1");
echo "Resultado: $output\n";

echo "\n🎉 Verificação concluída!\n";
echo "✅ Se houver problemas, execute: php fix-routes-500.php\n";
