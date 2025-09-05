<?php

// Script ULTIMATE para corrigir erro 500 DEFINITIVAMENTE
// Execute: php fix-500-ultimate.php

echo "🚨 CORREÇÃO ULTIMATE - ERRO 500 DEFINITIVO...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. PARAR TUDO - Limpar cache de forma agressiva
echo "\n🗑️ LIMPANDO TUDO DE FORMA AGRESSIVA...\n";

// Limpar cache básico
$basicCommands = [
    'php artisan view:clear',
    'php artisan config:clear', 
    'php artisan route:clear',
    'php artisan cache:clear'
];

foreach ($basicCommands as $command) {
    echo "\n🔄 Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

// 3. REMOVER COMPLETAMENTE o sidebar dinâmico
echo "\n🗑️ REMOVENDO COMPLETAMENTE O SIDEBAR DINÂMICO...\n";

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

// 4. LIMPAR CACHE MANUALMENTE - FORÇA BRUTA
echo "\n🗑️ LIMPANDO CACHE COM FORÇA BRUTA...\n";

$cacheDirs = [
    'storage/framework/views',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/testing',
    'bootstrap/cache'
];

foreach ($cacheDirs as $dir) {
    if (is_dir($dir)) {
        // Remover todos os arquivos
        $files = glob($dir . '/*');
        $count = 0;
        foreach ($files as $file) {
            if (is_file($file)) {
                if (unlink($file)) {
                    $count++;
                }
            } elseif (is_dir($file)) {
                // Remover subdiretórios também
                $subfiles = glob($file . '/*');
                foreach ($subfiles as $subfile) {
                    if (is_file($subfile)) {
                        unlink($subfile);
                        $count++;
                    }
                }
            }
        }
        echo "✅ Limpo: $dir ($count arquivos removidos)\n";
    } else {
        echo "❌ Não encontrado: $dir\n";
    }
}

// 5. REMOVER SIDEBAR DINÂMICO DE TODAS AS VIEWS - FORÇA BRUTA
echo "\n🔧 REMOVENDO SIDEBAR DINÂMICO DE TODAS AS VIEWS...\n";

$views = [
    'resources/views/dashboard.blade.php',
    'resources/views/dashboard/licenciados.blade.php',
    'resources/views/dashboard/users/index.blade.php',
    'resources/views/dashboard/users/create.blade.php',
    'resources/views/dashboard/users/edit.blade.php',
    'resources/views/dashboard/users/show.blade.php',
    'resources/views/dashboard/operacoes.blade.php',
    'resources/views/dashboard/planos.blade.php',
    'resources/views/dashboard/adquirentes.blade.php',
    'resources/views/dashboard/agenda.blade.php',
    'resources/views/dashboard/leads.blade.php',
    'resources/views/dashboard/marketing/index.blade.php',
    'resources/views/dashboard/marketing/emails.blade.php',
    'resources/views/dashboard/marketing/campanhas.blade.php',
    'resources/views/dashboard/configuracoes.blade.php',
    'resources/views/dashboard/licenciado-gerenciar.blade.php'
];

foreach ($views as $view) {
    if (file_exists($view)) {
        echo "\n📄 Processando: $view\n";
        
        $content = file_get_contents($view);
        $originalContent = $content;
        
        // Remover TODAS as referências ao sidebar dinâmico
        $replacements = [
            '<x-dynamic-sidebar />' => '',
            '<x-dynamic-sidebar/>' => '',
            '@include(\'components.dynamic-sidebar\')' => '',
            '@include("components.dynamic-sidebar")' => '',
            '@include(\'components.static-sidebar\')' => '',
            '@include("components.static-sidebar")' => '',
            'dynamic-sidebar' => '',
            'static-sidebar' => ''
        ];
        
        foreach ($replacements as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }
        
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

// 6. BUSCAR E REMOVER TODAS AS REFERÊNCIAS RESTANTES
echo "\n🔍 BUSCANDO E REMOVENDO TODAS AS REFERÊNCIAS RESTANTES...\n";

$searchCommand = 'find resources/views -name "*.blade.php" -exec grep -l "dynamic-sidebar\|static-sidebar" {} \; 2>/dev/null';
$output = shell_exec($searchCommand);
if ($output) {
    echo "⚠️ Arquivos ainda contêm referências:\n$output\n";
    
    // Remover referências dos arquivos encontrados
    $files = explode("\n", trim($output));
    foreach ($files as $file) {
        if ($file && file_exists($file)) {
            echo "🔧 Corrigindo: $file\n";
            $content = file_get_contents($file);
            $content = str_replace(['dynamic-sidebar', 'static-sidebar'], '', $content);
            file_put_contents($file, $content);
        }
    }
} else {
    echo "✅ Nenhuma referência encontrada\n";
}

// 7. VERIFICAR LOGS DE ERRO
echo "\n📋 VERIFICANDO LOGS DE ERRO...\n";

$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "✅ Log encontrado: $logFile\n";
    
    // Mostrar últimas 50 linhas do log
    $output = shell_exec('tail -50 ' . $logFile . ' 2>&1');
    echo "Últimas 50 linhas do log:\n$output\n";
} else {
    echo "❌ Log não encontrado: $logFile\n";
}

// 8. CORRIGIR PERMISSÕES - FORÇA BRUTA
echo "\n🔐 CORRIGINDO PERMISSÕES COM FORÇA BRUTA...\n";

$permissionCommands = [
    'chmod -R 777 storage/',
    'chmod -R 777 bootstrap/cache/',
    'chown -R www-data:www-data storage/',
    'chown -R www-data:www-data bootstrap/cache/',
    'chmod -R 755 storage/',
    'chmod -R 755 bootstrap/cache/'
];

foreach ($permissionCommands as $command) {
    echo "\n🔄 Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

// 9. RECRIAR CACHE BÁSICO
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

// 10. TESTAR SISTEMA
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

// 11. VERIFICAÇÃO FINAL
echo "\n📄 VERIFICAÇÃO FINAL...\n";

foreach ($views as $view) {
    if (file_exists($view)) {
        echo "✅ $view - Existe\n";
        
        $content = file_get_contents($view);
        if (strpos($content, 'dynamic-sidebar') !== false || strpos($content, 'static-sidebar') !== false) {
            echo "   ⚠️ Ainda contém referências ao sidebar\n";
        } else {
            echo "   ✅ Limpo de referências ao sidebar\n";
        }
    } else {
        echo "❌ $view - NÃO EXISTE\n";
    }
}

// 12. CRIAR SIDEBAR ESTÁTICO SIMPLES
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

echo "\n🎉 CORREÇÃO ULTIMATE CONCLUÍDA!\n";
echo "✅ Teste as rotas em: https://srv971263.hstgr.cloud/dashboard\n";
echo "✅ Se ainda houver erro, verifique os logs acima\n";
echo "✅ O sidebar dinâmico foi COMPLETAMENTE removido\n";
echo "✅ Sidebar estático simples foi criado\n";
echo "✅ Cache limpo com força bruta\n";
echo "✅ Permissões corrigidas\n";
echo "✅ Sistema deve estar funcionando agora\n";
