<?php

// Script para corrigir sidebar em produção
// Execute: php fix-sidebar-production.php

echo "🔧 Corrigindo sidebar em PRODUÇÃO...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // 3. Verificar ambiente
    echo "🌍 Ambiente: " . config('app.env') . "\n";
    echo "🔗 Banco: " . config('database.connections.mysql.database') . "\n";
    
    // 4. Importar classes
    use App\Models\User;
    use App\Models\Role;
    use App\Models\Permission;
    
    // 5. Verificar usuário
    echo "\n👤 Verificando usuário...\n";
    $adminUser = User::where('email', 'admin@dspay.com.br')->with('role')->first();
    
    if (!$adminUser) {
        echo "❌ Usuário admin@dspay.com.br não encontrado!\n";
        exit(1);
    }
    
    echo "✅ Usuário encontrado: {$adminUser->name}\n";
    echo "Role: " . ($adminUser->role ? $adminUser->role->display_name : 'Nenhum') . "\n";
    
    // 6. Verificar permissões
    $permissions = $adminUser->getPermissions();
    echo "Permissões: " . $permissions->count() . "\n";
    
    // 7. Limpar cache de views
    echo "\n🗂️ Limpando cache de views...\n";
    $output = shell_exec('php artisan view:clear 2>&1');
    echo "Resultado: $output\n";
    
    // 8. Limpar cache de configuração
    echo "\n⚙️ Limpando cache de configuração...\n";
    $output = shell_exec('php artisan config:clear 2>&1');
    echo "Resultado: $output\n";
    
    // 9. Limpar cache de rotas
    echo "\n🛣️ Limpando cache de rotas...\n";
    $output = shell_exec('php artisan route:clear 2>&1');
    echo "Resultado: $output\n";
    
    // 10. Recriar cache de configuração
    echo "\n⚙️ Recriando cache de configuração...\n";
    $output = shell_exec('php artisan config:cache 2>&1');
    echo "Resultado: $output\n";
    
    // 11. Recriar cache de rotas
    echo "\n🛣️ Recriando cache de rotas...\n";
    $output = shell_exec('php artisan route:cache 2>&1');
    echo "Resultado: $output\n";
    
    // 12. Recriar cache de views
    echo "\n📄 Recriando cache de views...\n";
    $output = shell_exec('php artisan view:cache 2>&1');
    echo "Resultado: $output\n";
    
    // 13. Verificar se o componente está funcionando
    echo "\n🧪 Testando componente...\n";
    
    try {
        $component = new \App\View\Components\DynamicSidebar($adminUser);
        $view = $component->render();
        echo "✅ Componente funcionando\n";
    } catch (Exception $e) {
        echo "❌ Erro no componente: " . $e->getMessage() . "\n";
    }
    
    // 14. Verificar arquivos do sidebar
    echo "\n📁 Verificando arquivos do sidebar...\n";
    
    $sidebarFiles = [
        'resources/views/components/dynamic-sidebar.blade.php',
        'app/View/Components/DynamicSidebar.php'
    ];
    
    foreach ($sidebarFiles as $file) {
        if (file_exists($file)) {
            echo "✅ $file - Existe\n";
        } else {
            echo "❌ $file - NÃO EXISTE\n";
        }
    }
    
    // 15. Verificar permissões de arquivos
    echo "\n🔐 Verificando permissões de arquivos...\n";
    
    $directories = [
        'storage/framework/views',
        'storage/framework/cache',
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
    
    // 16. Testar renderização de uma view
    echo "\n🧪 Testando renderização de view...\n";
    
    try {
        $view = view('dashboard', ['user' => $adminUser]);
        $html = $view->render();
        echo "✅ View dashboard renderizada com sucesso\n";
        echo "Tamanho do HTML: " . strlen($html) . " caracteres\n";
        
        // Verificar se o sidebar está no HTML
        if (strpos($html, 'dynamic-sidebar') !== false) {
            echo "✅ Sidebar encontrado no HTML\n";
        } else {
            echo "❌ Sidebar NÃO encontrado no HTML\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Erro ao renderizar view: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎉 Correção concluída!\n";
    echo "✅ Teste o sidebar em: https://srv971263.hstgr.cloud/dashboard\n";
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    echo "Stack: " . $e->getTraceAsString() . "\n";
}
