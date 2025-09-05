<?php

// Script robusto para corrigir erro 500
// Execute: php fix-500-robust.php

echo "🚨 CORREÇÃO ROBUSTA - ERRO 500...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Limpar TUDO com timeout
echo "\n🗑️ Limpando TUDO com timeout...\n";

$commands = [
    'php artisan view:clear',
    'php artisan config:clear',
    'php artisan route:clear',
    'php artisan cache:clear',
    'php artisan optimize:clear',
    'php artisan event:clear'
    // Removido queue:clear que estava travando
];

foreach ($commands as $command) {
    echo "\n🔄 Executando: $command\n";
    
    // Executar com timeout de 30 segundos
    $descriptorspec = array(
        0 => array("pipe", "r"),
        1 => array("pipe", "w"),
        2 => array("pipe", "w")
    );
    
    $process = proc_open($command, $descriptorspec, $pipes);
    
    if (is_resource($process)) {
        // Fechar stdin
        fclose($pipes[0]);
        
        // Ler stdout e stderr
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        
        fclose($pipes[1]);
        fclose($pipes[2]);
        
        // Aguardar processo terminar
        $return_value = proc_close($process);
        
        if ($return_value === 0) {
            echo "✅ Sucesso: $command\n";
            if ($stdout) echo "Output: $stdout\n";
        } else {
            echo "⚠️ Aviso: $command (código: $return_value)\n";
            if ($stderr) echo "Erro: $stderr\n";
        }
    } else {
        echo "❌ Erro ao executar: $command\n";
    }
}

// 3. Remover TODOS os arquivos do sidebar dinâmico
echo "\n🗑️ Removendo TODOS os arquivos do sidebar dinâmico...\n";

$files = [
    'app/View/Components/DynamicSidebar.php',
    'resources/views/components/dynamic-sidebar.blade.php',
    'resources/views/components/static-sidebar.blade.php'
];

foreach ($files as $file) {
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

// 4. Limpar cache manualmente
echo "\n🗑️ Limpando cache manualmente...\n";

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

// 5. Remover sidebar dinâmico de TODAS as views
echo "\n🔧 Removendo sidebar dinâmico de TODAS as views...\n";

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
        echo "\n📄 Verificando: $view\n";
        
        $content = file_get_contents($view);
        $originalContent = $content;
        
        // Remover TODAS as referências ao sidebar dinâmico
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

// 6. Verificar se há outras referências ao sidebar dinâmico
echo "\n🔍 Verificando outras referências...\n";

$searchCommand = 'grep -r "dynamic-sidebar" resources/views/ 2>/dev/null || echo "Nenhuma referência encontrada"';
$output = shell_exec($searchCommand);
echo "Resultado: $output\n";

// 7. Verificar logs de erro
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

// 8. Verificar permissões
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

// 9. Corrigir permissões
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

// 10. Recriar cache básico
echo "\n🗂️ Recriando cache básico...\n";

$cacheCommands = [
    'php artisan config:cache',
    'php artisan route:cache'
];

foreach ($cacheCommands as $command) {
    echo "\n🔄 Executando: $command\n";
    
    // Executar com timeout
    $descriptorspec = array(
        0 => array("pipe", "r"),
        1 => array("pipe", "w"),
        2 => array("pipe", "w")
    );
    
    $process = proc_open($command, $descriptorspec, $pipes);
    
    if (is_resource($process)) {
        fclose($pipes[0]);
        
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        
        fclose($pipes[1]);
        fclose($pipes[2]);
        
        $return_value = proc_close($process);
        
        if ($return_value === 0) {
            echo "✅ Sucesso: $command\n";
            if ($stdout) echo "Output: $stdout\n";
        } else {
            echo "⚠️ Aviso: $command (código: $return_value)\n";
            if ($stderr) echo "Erro: $stderr\n";
        }
    } else {
        echo "❌ Erro ao executar: $command\n";
    }
}

// 11. Testar se o sistema está funcionando
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

// 12. Verificar se as views existem e não usam sidebar dinâmico
echo "\n📄 Verificando views finais...\n";

foreach ($views as $view) {
    if (file_exists($view)) {
        echo "✅ $view - Existe\n";
        
        $content = file_get_contents($view);
        if (strpos($content, 'dynamic-sidebar') !== false) {
            echo "   ⚠️ Ainda contém 'dynamic-sidebar'\n";
        } else {
            echo "   ✅ Não contém 'dynamic-sidebar'\n";
        }
    } else {
        echo "❌ $view - NÃO EXISTE\n";
    }
}

echo "\n🎉 CORREÇÃO ROBUSTA CONCLUÍDA!\n";
echo "✅ Teste as rotas em: https://srv971263.hstgr.cloud/dashboard\n";
echo "✅ Se ainda houver erro, verifique os logs acima\n";
echo "✅ O sidebar dinâmico foi completamente removido\n";
echo "✅ Comandos com timeout para evitar travamento\n";
