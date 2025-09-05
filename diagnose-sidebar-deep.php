<?php

// Script para diagnóstico profundo do sidebar em produção
// Execute: php diagnose-sidebar-deep.php

echo "🔍 Diagnóstico profundo do sidebar em PRODUÇÃO...\n";

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
    
    // 7. Verificar arquivos do sidebar
    echo "\n📁 Verificando arquivos do sidebar...\n";
    
    $sidebarFiles = [
        'resources/views/components/dynamic-sidebar.blade.php',
        'app/View/Components/DynamicSidebar.php'
    ];
    
    foreach ($sidebarFiles as $file) {
        if (file_exists($file)) {
            echo "✅ $file - Existe\n";
            $content = file_get_contents($file);
            echo "   Tamanho: " . strlen($content) . " caracteres\n";
            
            // Verificar conteúdo específico
            if (strpos($content, 'dashboard') !== false) {
                echo "   ✅ Contém 'dashboard'\n";
            } else {
                echo "   ❌ NÃO contém 'dashboard'\n";
            }
            
            if (strpos($content, 'licenciados') !== false) {
                echo "   ✅ Contém 'licenciados'\n";
            } else {
                echo "   ❌ NÃO contém 'licenciados'\n";
            }
            
        } else {
            echo "❌ $file - NÃO EXISTE\n";
        }
    }
    
    // 8. Verificar views que usam o sidebar
    echo "\n📄 Verificando views que usam o sidebar...\n";
    
    $views = [
        'resources/views/dashboard.blade.php',
        'resources/views/dashboard/licenciados.blade.php'
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
            
            if (strpos($content, 'dynamic-sidebar') !== false) {
                echo "   ✅ Contém 'dynamic-sidebar'\n";
            } else {
                echo "   ❌ NÃO contém 'dynamic-sidebar'\n";
            }
            
        } else {
            echo "❌ $view - NÃO EXISTE\n";
        }
    }
    
    // 9. Verificar cache de views
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
                $content = file_get_contents($cacheDir . '/' . $file);
                echo "  Tamanho: " . strlen($content) . " caracteres\n";
                
                if (strpos($content, 'dashboard') !== false) {
                    echo "  ✅ Contém 'dashboard'\n";
                } else {
                    echo "  ❌ NÃO contém 'dashboard'\n";
                }
            }
        } else {
            echo "❌ Nenhum cache do sidebar encontrado\n";
        }
    } else {
        echo "❌ Diretório de cache não existe\n";
    }
    
    // 10. Testar renderização do componente
    echo "\n🧪 Testando renderização do componente...\n";
    
    try {
        $component = new \App\View\Components\DynamicSidebar($adminUser);
        $view = $component->render();
        echo "✅ Componente renderizado com sucesso\n";
        echo "Tipo da view: " . get_class($view) . "\n";
        
        // Verificar se a view tem conteúdo
        $html = $view->render();
        echo "Tamanho do HTML: " . strlen($html) . " caracteres\n";
        
        if (strpos($html, 'dashboard') !== false) {
            echo "✅ HTML contém 'dashboard'\n";
        } else {
            echo "❌ HTML NÃO contém 'dashboard'\n";
        }
        
        if (strpos($html, 'licenciados') !== false) {
            echo "✅ HTML contém 'licenciados'\n";
        } else {
            echo "❌ HTML NÃO contém 'licenciados'\n";
        }
        
        // Mostrar parte do HTML
        echo "\n📋 Parte do HTML gerado:\n";
        echo substr($html, 0, 500) . "...\n";
        
    } catch (Exception $e) {
        echo "❌ Erro ao renderizar componente: " . $e->getMessage() . "\n";
        echo "Stack: " . $e->getTraceAsString() . "\n";
    }
    
    // 11. Testar renderização de uma view completa
    echo "\n🧪 Testando renderização de view completa...\n";
    
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
        
        if (strpos($html, 'dashboard') !== false) {
            echo "✅ Menu 'dashboard' encontrado no HTML\n";
        } else {
            echo "❌ Menu 'dashboard' NÃO encontrado no HTML\n";
        }
        
        // Mostrar parte do HTML
        echo "\n📋 Parte do HTML da view:\n";
        echo substr($html, 0, 1000) . "...\n";
        
    } catch (Exception $e) {
        echo "❌ Erro ao renderizar view: " . $e->getMessage() . "\n";
        echo "Stack: " . $e->getTraceAsString() . "\n";
    }
    
    // 12. Verificar se o componente está registrado
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
        
        if (strpos($content, 'dynamic-sidebar') !== false) {
            echo "✅ View 'dynamic-sidebar' encontrada\n";
        } else {
            echo "❌ View 'dynamic-sidebar' não encontrada\n";
        }
        
    } else {
        echo "❌ Componente DynamicSidebar não existe\n";
    }
    
    echo "\n🎉 Diagnóstico concluído!\n";
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    echo "Stack: " . $e->getTraceAsString() . "\n";
}
