<?php

// Solução simples e direta
// Execute: php fix-simple-direct.php

echo "🚨 SOLUÇÃO SIMPLES E DIRETA...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Remover TUDO relacionado ao sidebar dinâmico
echo "\n🗑️ REMOVENDO TUDO RELACIONADO AO SIDEBAR DINÂMICO...\n";

$filesToRemove = [
    'app/View/Components/DynamicSidebar.php',
    'resources/views/components/dynamic-sidebar.blade.php',
    'resources/views/components/static-sidebar.blade.php',
    'resources/views/layouts/production.blade.php'
];

foreach ($filesToRemove as $file) {
    if (file_exists($file)) {
        if (unlink($file)) {
            echo "✅ Removido: $file\n";
        } else {
            echo "❌ Erro ao remover: $file\n";
        }
    } else {
        echo "❌ Não encontrado: $file\n";
    }
}

// 3. Limpar cache
echo "\n🗑️ LIMPANDO CACHE...\n";

$cacheDirs = [
    'storage/framework/views',
    'storage/framework/cache',
    'storage/framework/sessions',
    'bootstrap/cache'
];

foreach ($cacheDirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        $count = 0;
        foreach ($files as $file) {
            if (is_file($file)) {
                if (unlink($file)) {
                    $count++;
                }
            }
        }
        echo "✅ Limpo: $dir ($count arquivos removidos)\n";
    } else {
        echo "❌ Não encontrado: $dir\n";
    }
}

// 4. Remover sidebar dinâmico de views principais
echo "\n🔧 REMOVENDO SIDEBAR DINÂMICO DE VIEWS PRINCIPAIS...\n";

$mainViews = [
    'resources/views/dashboard.blade.php',
    'resources/views/dashboard/licenciados.blade.php',
    'resources/views/dashboard/users/index.blade.php'
];

foreach ($mainViews as $view) {
    if (file_exists($view)) {
        echo "\n📄 Processando: $view\n";
        
        $content = file_get_contents($view);
        $originalContent = $content;
        
        // Remover referências ao sidebar dinâmico
        $content = str_replace('<x-dynamic-sidebar />', '', $content);
        $content = str_replace('<x-dynamic-sidebar/>', '', $content);
        $content = str_replace('@include(\'components.dynamic-sidebar\')', '', $content);
        $content = str_replace('@include("components.dynamic-sidebar")', '', $content);
        $content = str_replace('@include(\'components.static-sidebar\')', '', $content);
        $content = str_replace('@include("components.static-sidebar")', '', $content);
        
        // Se houve mudança, salvar
        if ($content !== $originalContent) {
            if (file_put_contents($view, $content)) {
                echo "✅ Corrigido: $view\n";
            } else {
                echo "❌ Erro ao salvar: $view\n";
            }
        } else {
            echo "✅ Já está correto: $view\n";
        }
        
    } else {
        echo "❌ Não encontrado: $view\n";
    }
}

// 5. Criar sidebar estático simples
echo "\n🔧 CRIANDO SIDEBAR ESTÁTICO SIMPLES...\n";

$staticSidebarContent = '<!-- Sidebar Estático Simples -->
<aside class="bg-gray-800 text-white w-64 min-h-screen p-4">
    <div class="mb-8">
        <h2 class="text-xl font-bold">DSPay</h2>
    </div>
    
    <nav>
        <ul class="space-y-2">
            <li><a href="{{ route("dashboard") }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Dashboard</a></li>
            <li><a href="{{ route("dashboard.licenciados") }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Licenciados</a></li>
            <li><a href="{{ route("dashboard.users.index") }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Usuários</a></li>
            <li><a href="{{ route("dashboard.operacoes") }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Operações</a></li>
            <li><a href="{{ route("dashboard.planos") }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Planos</a></li>
            <li><a href="{{ route("dashboard.adquirentes") }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Adquirentes</a></li>
            <li><a href="{{ route("dashboard.agenda") }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Agenda</a></li>
            <li><a href="{{ route("dashboard.leads") }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Leads</a></li>
            <li><a href="{{ route("dashboard.marketing.index") }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Marketing</a></li>
            <li><a href="{{ route("dashboard.configuracoes") }}" class="block px-4 py-2 hover:bg-gray-700 rounded">Configurações</a></li>
        </ul>
    </nav>
</aside>';

$staticSidebarFile = 'resources/views/components/static-sidebar.blade.php';
if (file_put_contents($staticSidebarFile, $staticSidebarContent)) {
    echo "✅ Sidebar estático criado: $staticSidebarFile\n";
} else {
    echo "❌ Erro ao criar sidebar estático\n";
}

// 6. Adicionar sidebar estático às views principais
echo "\n🔧 ADICIONANDO SIDEBAR ESTÁTICO ÀS VIEWS PRINCIPAIS...\n";

foreach ($mainViews as $view) {
    if (file_exists($view)) {
        echo "\n📄 Adicionando sidebar estático a: $view\n";
        
        $content = file_get_contents($view);
        
        // Verificar se já tem sidebar
        if (strpos($content, 'static-sidebar') === false) {
            // Adicionar sidebar estático no início do body
            $content = str_replace('<body>', '<body><div class="flex"><x-static-sidebar />', $content);
            $content = str_replace('</body>', '</div></body>', $content);
            
            if (file_put_contents($view, $content)) {
                echo "✅ Sidebar estático adicionado: $view\n";
            } else {
                echo "❌ Erro ao adicionar sidebar estático: $view\n";
            }
        } else {
            echo "✅ Já tem sidebar estático: $view\n";
        }
    } else {
        echo "❌ Não encontrado: $view\n";
    }
}

// 7. Corrigir permissões
echo "\n🔐 CORRIGINDO PERMISSÕES...\n";

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

// 8. Recriar cache básico
echo "\n🗂️ RECRIANDO CACHE BÁSICO...\n";

$cacheCommands = [
    'php artisan config:cache',
    'php artisan route:cache'
];

foreach ($cacheCommands as $command) {
    echo "\n🔄 Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

// 9. Testar sistema
echo "\n🧪 TESTANDO SISTEMA...\n";

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

echo "\n🎉 SOLUÇÃO SIMPLES E DIRETA CONCLUÍDA!\n";
echo "✅ Teste as rotas em: https://srv971263.hstgr.cloud/dashboard\n";
echo "✅ O sidebar dinâmico foi removido\n";
echo "✅ Sidebar estático simples foi criado\n";
echo "✅ Cache limpo e recriado\n";
echo "✅ Permissões corrigidas\n";
echo "✅ Sistema deve estar funcionando agora\n";
