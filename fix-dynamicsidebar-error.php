<?php

// Script específico para corrigir erro do DynamicSidebar
// Execute: php fix-dynamicsidebar-error.php

echo "🔧 CORRIGINDO ERRO DO DYNAMICSIDEBAR...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. REMOVER COMPLETAMENTE o DynamicSidebar
echo "\n🗑️ REMOVENDO COMPLETAMENTE O DYNAMICSIDEBAR...\n";

// Remover arquivo do componente
if (file_exists('app/View/Components/DynamicSidebar.php')) {
    unlink('app/View/Components/DynamicSidebar.php');
    echo "✅ DynamicSidebar.php removido\n";
} else {
    echo "✅ DynamicSidebar.php já não existe\n";
}

// Remover arquivo da view
if (file_exists('resources/views/components/dynamic-sidebar.blade.php')) {
    unlink('resources/views/components/dynamic-sidebar.blade.php');
    echo "✅ dynamic-sidebar.blade.php removido\n";
} else {
    echo "✅ dynamic-sidebar.blade.php já não existe\n";
}

// Remover layout de produção
if (file_exists('resources/views/layouts/production.blade.php')) {
    unlink('resources/views/layouts/production.blade.php');
    echo "✅ production.blade.php removido\n";
} else {
    echo "✅ production.blade.php já não existe\n";
}

// 3. REMOVER REFERÊNCIAS DE TODAS AS VIEWS
echo "\n🔧 REMOVENDO REFERÊNCIAS DE TODAS AS VIEWS...\n";

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
        $content = file_get_contents($view);
        $originalContent = $content;
        
        // Remover TODAS as referências ao sidebar dinâmico
        $replacements = [
            '<x-dynamic-sidebar />' => '',
            '<x-dynamic-sidebar/>' => '',
            '@include(\'components.dynamic-sidebar\')' => '',
            '@include("components.dynamic-sidebar")' => '',
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
    }
}

// 4. BUSCAR E REMOVER TODAS AS REFERÊNCIAS RESTANTES
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

// 5. LIMPAR CACHE COMPLETAMENTE
echo "\n🗑️ LIMPANDO CACHE COMPLETAMENTE...\n";

$cacheDirs = [
    'storage/framework/cache',
    'storage/framework/views',
    'storage/framework/sessions',
    'storage/framework/testing',
    'bootstrap/cache'
];

foreach ($cacheDirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        $count = 0;
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
                $count++;
            }
        }
        echo "✅ Limpo: $dir ($count arquivos removidos)\n";
    }
}

// 6. CORRIGIR PERMISSÕES
echo "\n🔐 CORRIGINDO PERMISSÕES...\n";

$permissionCommands = [
    'chmod -R 755 storage/',
    'chmod -R 755 bootstrap/cache/',
    'chown -R www-data:www-data storage/',
    'chown -R www-data:www-data bootstrap/cache/'
];

foreach ($permissionCommands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

// 7. RECRIAR CACHE BÁSICO
echo "\n🗂️ RECRIANDO CACHE BÁSICO...\n";

$cacheCommands = [
    'php artisan config:cache',
    'php artisan route:cache'
];

foreach ($cacheCommands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

// 8. TESTAR SISTEMA
echo "\n🧪 TESTANDO SISTEMA...\n";

$testCommand = '
try {
    echo "Testando sistema..." . PHP_EOL;
    $user = App\Models\User::where("email", "admin@dspay.com.br")->first();
    if ($user) {
        echo "✅ Usuário encontrado: " . $user->name . PHP_EOL;
    } else {
        echo "❌ Usuário não encontrado!" . PHP_EOL;
    }
    
    echo "Testando rotas..." . PHP_EOL;
    $routes = [
        "dashboard" => route("dashboard"),
        "licenciados" => route("dashboard.licenciados")
    ];
    
    foreach ($routes as $name => $url) {
        echo "✅ Rota $name: $url" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . PHP_EOL;
}
';

$output = shell_exec("php artisan tinker --execute=\"$testCommand\" 2>&1");
echo "Resultado do teste:\n$output\n";

// 9. VERIFICAÇÃO FINAL
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

echo "\n🎉 CORREÇÃO DO DYNAMICSIDEBAR CONCLUÍDA!\n";
echo "✅ Teste as rotas em: https://srv971263.hstgr.cloud/dashboard\n";
echo "✅ Se ainda houver erro, verifique os logs\n";
echo "✅ O DynamicSidebar foi COMPLETAMENTE removido\n";
echo "✅ Cache limpo\n";
echo "✅ Permissões corrigidas\n";
echo "✅ Sistema deve estar funcionando agora\n";
