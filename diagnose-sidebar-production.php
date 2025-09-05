<?php

// Script para diagnosticar sidebar em produção
// Execute: php diagnose-sidebar-production.php

echo "🔍 Diagnosticando sidebar em PRODUÇÃO...\n";

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
    
    // 5. Verificar usuário logado
    echo "\n👤 Verificando usuário...\n";
    $adminUser = User::where('email', 'admin@dspay.com.br')->with('role')->first();
    
    if ($adminUser) {
        echo "✅ Usuário encontrado:\n";
        echo "- ID: {$adminUser->id}\n";
        echo "- Nome: {$adminUser->name}\n";
        echo "- Email: {$adminUser->email}\n";
        echo "- Role: " . ($adminUser->role ? $adminUser->role->display_name : 'Nenhum') . "\n";
        echo "- Status: " . ($adminUser->is_active ? 'Ativo' : 'Inativo') . "\n";
        
        // Verificar permissões
        $permissions = $adminUser->getPermissions();
        echo "- Permissões: " . $permissions->count() . " permissões\n";
        
        // Listar algumas permissões
        echo "\n📋 Algumas permissões:\n";
        foreach ($permissions->take(10) as $permission) {
            echo "- {$permission->name} ({$permission->display_name})\n";
        }
        
    } else {
        echo "❌ Usuário admin@dspay.com.br não encontrado!\n";
        exit(1);
    }
    
    // 6. Verificar roles
    echo "\n📋 Verificando roles...\n";
    $roles = Role::all();
    echo "Total de roles: " . $roles->count() . "\n";
    foreach ($roles as $role) {
        echo "- ID: {$role->id} | Nome: {$role->name} | Display: {$role->display_name}\n";
    }
    
    // 7. Verificar permissões
    echo "\n📋 Verificando permissões...\n";
    $permissions = Permission::all();
    echo "Total de permissões: " . $permissions->count() . "\n";
    
    // 8. Verificar arquivos do sidebar
    echo "\n📁 Verificando arquivos do sidebar...\n";
    
    $sidebarFiles = [
        'resources/views/components/dynamic-sidebar.blade.php',
        'resources/views/components/dynamic-sidebar-production.blade.php',
        'app/View/Components/DynamicSidebar.php'
    ];
    
    foreach ($sidebarFiles as $file) {
        if (file_exists($file)) {
            echo "✅ $file - Existe\n";
            $size = filesize($file);
            echo "   Tamanho: " . number_format($size) . " bytes\n";
        } else {
            echo "❌ $file - NÃO EXISTE\n";
        }
    }
    
    // 9. Verificar se o componente está registrado
    echo "\n🔧 Verificando registro do componente...\n";
    
    $componentFile = 'app/View/Components/DynamicSidebar.php';
    if (file_exists($componentFile)) {
        echo "✅ Componente DynamicSidebar existe\n";
        
        // Verificar conteúdo do componente
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
        echo "❌ Componente DynamicSidebar não existe\n";
    }
    
    // 10. Verificar views que usam o sidebar
    echo "\n📄 Verificando views que usam o sidebar...\n";
    
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
                echo "   ✅ Usa <x-dynamic-sidebar>\n";
            } else {
                echo "   ❌ NÃO usa <x-dynamic-sidebar>\n";
            }
        } else {
            echo "❌ $view - NÃO EXISTE\n";
        }
    }
    
    // 11. Verificar cache de views
    echo "\n🗂️ Verificando cache de views...\n";
    
    $cacheDir = 'storage/framework/views';
    if (is_dir($cacheDir)) {
        $files = scandir($cacheDir);
        $cacheFiles = array_filter($files, function($file) {
            return $file !== '.' && $file !== '..' && strpos($file, 'dynamic-sidebar') !== false;
        });
        
        if (count($cacheFiles) > 0) {
            echo "✅ Cache do sidebar encontrado:\n";
            foreach ($cacheFiles as $file) {
                echo "- $file\n";
            }
        } else {
            echo "❌ Nenhum cache do sidebar encontrado\n";
        }
    } else {
        echo "❌ Diretório de cache não existe\n";
    }
    
    // 12. Testar renderização do componente
    echo "\n🧪 Testando renderização do componente...\n";
    
    try {
        $component = new \App\View\Components\DynamicSidebar($adminUser);
        $view = $component->render();
        echo "✅ Componente renderizado com sucesso\n";
        echo "Tipo da view: " . get_class($view) . "\n";
    } catch (Exception $e) {
        echo "❌ Erro ao renderizar componente: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎉 Diagnóstico concluído!\n";
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    echo "Stack: " . $e->getTraceAsString() . "\n";
}
