<?php

// Script para verificar arquivos do sidebar
// Execute: php check-sidebar-files.php

echo "🔍 Verificando arquivos do sidebar...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Verificar arquivos do sidebar
echo "\n📁 Verificando arquivos do sidebar...\n";

$files = [
    'resources/views/components/dynamic-sidebar.blade.php',
    'app/View/Components/DynamicSidebar.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file - Existe\n";
        $size = filesize($file);
        echo "   Tamanho: " . number_format($size) . " bytes\n";
        
        // Verificar conteúdo
        $content = file_get_contents($file);
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

// 3. Verificar views que usam o sidebar
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
        
    } else {
        echo "❌ $view - NÃO EXISTE\n";
    }
}

// 4. Verificar cache
echo "\n🗂️ Verificando cache...\n";

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

// 5. Verificar permissões
echo "\n🔐 Verificando permissões...\n";

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

echo "\n🎉 Verificação concluída!\n";
echo "✅ Se houver problemas, execute: php recreate-sidebar-production.php\n";
