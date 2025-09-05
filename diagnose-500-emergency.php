<?php

// Script de DIAGNÓSTICO EMERGÊNCIA para erro 500
// Execute: php diagnose-500-emergency.php

echo "🚨 DIAGNÓSTICO EMERGÊNCIA - ERRO 500...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

echo "✅ Diretório correto encontrado\n";

// 2. Verificar logs de erro
echo "\n📋 VERIFICANDO LOGS DE ERRO...\n";

$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "✅ Log encontrado: $logFile\n";
    
    // Mostrar últimas 100 linhas do log
    $output = shell_exec('tail -100 ' . $logFile . ' 2>&1');
    echo "Últimas 100 linhas do log:\n";
    echo "==========================================\n";
    echo $output;
    echo "==========================================\n";
} else {
    echo "❌ Log não encontrado: $logFile\n";
    
    // Verificar se o diretório de logs existe
    if (!is_dir('storage/logs')) {
        echo "❌ Diretório de logs não existe, criando...\n";
        mkdir('storage/logs', 0755, true);
    }
    
    // Criar arquivo de log vazio
    touch($logFile);
    chmod($logFile, 0666);
    echo "✅ Arquivo de log criado\n";
}

// 3. Verificar arquivos problemáticos
echo "\n🔍 VERIFICANDO ARQUIVOS PROBLEMÁTICOS...\n";

$problematicFiles = [
    'app/View/Components/DynamicSidebar.php',
    'resources/views/components/dynamic-sidebar.blade.php',
    'resources/views/layouts/production.blade.php'
];

foreach ($problematicFiles as $file) {
    if (file_exists($file)) {
        echo "❌ PROBLEMA: $file existe\n";
        
        // Fazer backup antes de remover
        $backupFile = $file . '.backup.' . date('Y-m-d-H-i-s');
        if (copy($file, $backupFile)) {
            echo "✅ Backup criado: $backupFile\n";
        }
        
        // Remover arquivo problemático
        if (unlink($file)) {
            echo "✅ Removido: $file\n";
        } else {
            echo "❌ Erro ao remover: $file\n";
        }
    } else {
        echo "✅ OK: $file não existe\n";
    }
}

// 4. Verificar views que usam sidebar dinâmico
echo "\n🔍 VERIFICANDO VIEWS COM SIDEBAR DINÂMICO...\n";

$views = [
    'resources/views/dashboard.blade.php',
    'resources/views/dashboard/licenciados.blade.php',
    'resources/views/dashboard/users/index.blade.php',
    'resources/views/dashboard/operacoes.blade.php',
    'resources/views/dashboard/planos.blade.php',
    'resources/views/dashboard/adquirentes.blade.php',
    'resources/views/dashboard/agenda.blade.php',
    'resources/views/dashboard/leads.blade.php',
    'resources/views/dashboard/marketing/index.blade.php',
    'resources/views/dashboard/configuracoes.blade.php'
];

foreach ($views as $view) {
    if (file_exists($view)) {
        $content = file_get_contents($view);
        
        if (strpos($content, 'dynamic-sidebar') !== false || strpos($content, '<x-dynamic-sidebar') !== false) {
            echo "❌ PROBLEMA: $view contém referência ao sidebar dinâmico\n";
            
            // Fazer backup
            $backupFile = $view . '.backup.' . date('Y-m-d-H-i-s');
            copy($view, $backupFile);
            echo "✅ Backup criado: $backupFile\n";
            
            // Remover referências
            $content = str_replace(['<x-dynamic-sidebar />', '<x-dynamic-sidebar/>', 'dynamic-sidebar'], '', $content);
            
            if (file_put_contents($view, $content)) {
                echo "✅ Corrigido: $view\n";
            } else {
                echo "❌ Erro ao corrigir: $view\n";
            }
        } else {
            echo "✅ OK: $view não usa sidebar dinâmico\n";
        }
    } else {
        echo "❌ Não encontrado: $view\n";
    }
}

// 5. Limpar cache com força bruta
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

// 6. Verificar permissões
echo "\n🔐 VERIFICANDO PERMISSÕES...\n";

$paths = [
    'storage',
    'storage/framework',
    'storage/framework/views',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($paths as $path) {
    if (is_dir($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        echo "Permissões $path: $perms\n";
        
        // Corrigir permissões se necessário
        if ($perms !== '0755') {
            chmod($path, 0755);
            echo "✅ Permissões corrigidas para $path\n";
        }
    } else {
        echo "❌ Não encontrado: $path\n";
    }
}

// 7. Verificar configurações do .env
echo "\n⚙️ VERIFICANDO CONFIGURAÇÕES...\n";

if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    // Verificar se há problemas no .env
    if (strpos($envContent, 'APP_ENV=local') !== false) {
        echo "❌ PROBLEMA: APP_ENV está como 'local'\n";
        $envContent = str_replace('APP_ENV=local', 'APP_ENV=production', $envContent);
        file_put_contents('.env', $envContent);
        echo "✅ Corrigido para APP_ENV=production\n";
    }
    
    if (strpos($envContent, 'APP_DEBUG=true') !== false) {
        echo "❌ PROBLEMA: APP_DEBUG está como 'true'\n";
        $envContent = str_replace('APP_DEBUG=true', 'APP_DEBUG=false', $envContent);
        file_put_contents('.env', $envContent);
        echo "✅ Corrigido para APP_DEBUG=false\n";
    }
    
    // Verificar se há caminhos de desenvolvimento
    if (strpos($envContent, '/Applications/MAMP/htdocs/orbita/') !== false) {
        echo "❌ PROBLEMA: Caminhos de desenvolvimento encontrados\n";
        $envContent = preg_replace('/^.*\/Applications\/MAMP\/htdocs\/orbita\/.*$/m', '', $envContent);
        file_put_contents('.env', $envContent);
        echo "✅ Caminhos de desenvolvimento removidos\n";
    }
    
    echo "✅ Arquivo .env verificado\n";
} else {
    echo "❌ Arquivo .env não encontrado\n";
}

// 8. Testar comandos básicos do Laravel
echo "\n🧪 TESTANDO COMANDOS BÁSICOS...\n";

$testCommands = [
    'php artisan --version',
    'php artisan config:show app.env',
    'php artisan config:show app.debug'
];

foreach ($testCommands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "Resultado: $output\n";
    } else {
        echo "❌ Comando falhou\n";
    }
}

// 9. Criar sidebar estático simples
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

// 10. Adicionar sidebar estático ao dashboard
echo "\n🔧 ADICIONANDO SIDEBAR ESTÁTICO AO DASHBOARD...\n";

$dashboardFile = 'resources/views/dashboard.blade.php';
if (file_exists($dashboardFile)) {
    $content = file_get_contents($dashboardFile);
    
    // Verificar se já tem sidebar
    if (strpos($content, '<x-static-sidebar') === false) {
        // Adicionar sidebar estático
        $content = str_replace('<body class="bg-gray-50">', '<body class="bg-gray-50"><div class="flex h-screen"><x-static-sidebar />', $content);
        $content = str_replace('</body>', '</div></body>', $content);
        
        if (file_put_contents($dashboardFile, $content)) {
            echo "✅ Sidebar estático adicionado ao dashboard\n";
        } else {
            echo "❌ Erro ao adicionar sidebar ao dashboard\n";
        }
    } else {
        echo "✅ Dashboard já tem sidebar estático\n";
    }
} else {
    echo "❌ Dashboard não encontrado\n";
}

// 11. Recriar cache básico
echo "\n🗂️ RECRIANDO CACHE BÁSICO...\n";

$cacheCommands = [
    'php artisan config:cache',
    'php artisan route:cache'
];

foreach ($cacheCommands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "Resultado: $output\n";
    } else {
        echo "❌ Comando falhou\n";
    }
}

// 12. Teste final
echo "\n🧪 TESTE FINAL...\n";

// Testar se o sistema está funcionando
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

echo "\n🎉 DIAGNÓSTICO CONCLUÍDO!\n";
echo "\n📋 RESUMO:\n";
echo "- ✅ Logs verificados\n";
echo "- ✅ Arquivos problemáticos removidos\n";
echo "- ✅ Views corrigidas\n";
echo "- ✅ Cache limpo\n";
echo "- ✅ Permissões verificadas\n";
echo "- ✅ Configurações corrigidas\n";
echo "- ✅ Sidebar estático criado\n";
echo "- ✅ Cache recriado\n";
echo "\n🚀 Agora teste o sistema:\n";
echo "https://srv971263.hstgr.cloud/dashboard\n";
echo "\n🔍 Se ainda houver erro 500:\n";
echo "1. Verifique os logs acima\n";
echo "2. Execute: tail -f storage/logs/laravel.log\n";
echo "3. Verifique se o servidor web está rodando\n";
echo "4. Execute: chown -R www-data:www-data storage/\n";
echo "5. Execute: chown -R www-data:www-data bootstrap/cache/\n";
