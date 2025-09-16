<?php

// Script para testar a consistência do branding

require_once __DIR__ . '/vendor/autoload.php';

// Simular ambiente Laravel mínimo
$app = new Illuminate\Foundation\Application(__DIR__);
$app->singleton('config', function() {
    return new Illuminate\Config\Repository([
        'app' => ['key' => 'base64:' . base64_encode('test-key-32-characters-long!!')],
        'database' => [
            'default' => 'sqlite',
            'connections' => [
                'sqlite' => [
                    'driver' => 'sqlite',
                    'database' => __DIR__ . '/database/database.sqlite',
                ]
            ]
        ]
    ]);
});

try {
    // Verificar se o componente de branding existe
    $brandingComponent = __DIR__ . '/resources/views/components/dynamic-branding.blade.php';
    if (!file_exists($brandingComponent)) {
        throw new Exception("Componente de branding não encontrado!");
    }
    
    echo "✅ Componente de branding encontrado\n";
    
    // Verificar se as CSS variables estão definidas
    $brandingContent = file_get_contents($brandingComponent);
    $requiredVariables = [
        '--primary-color',
        '--secondary-color', 
        '--accent-color',
        '--text-color',
        '--background-color',
        '--primary-light',
        '--primary-dark',
        '--primary-text'
    ];
    
    $missingVariables = [];
    foreach ($requiredVariables as $variable) {
        if (strpos($brandingContent, $variable) === false) {
            $missingVariables[] = $variable;
        }
    }
    
    if (empty($missingVariables)) {
        echo "✅ Todas as CSS variables estão definidas\n";
    } else {
        echo "❌ CSS variables faltando: " . implode(', ', $missingVariables) . "\n";
    }
    
    // Verificar quantas páginas têm branding
    $viewsDir = __DIR__ . '/resources/views';
    $totalPages = 0;
    $pagesWithBranding = 0;
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($viewsDir, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php' && strpos($file->getFilename(), '.blade.php') !== false) {
            // Excluir arquivos que não precisam de branding
            $excludePatterns = [
                '/components/',
                '/emails/',
                '/layouts/app.blade.php',
                '/layouts/guest.blade.php',
                '/contracts/templates/',
                '/agenda/confirmacao',
                '/agenda/rejeicao',
                '/agenda/erro-confirmacao',
                '/agenda/pendente',
                '/agenda/confirmacao-sucesso',
                '/contracts/sign',
                '.backup.',
                '/partials/',
                '/auth/',
                '/profile/',
                '/api/',
                '/errors/'
            ];
            
            $shouldHaveBranding = true;
            foreach ($excludePatterns as $pattern) {
                if (strpos($file->getPathname(), $pattern) !== false) {
                    $shouldHaveBranding = false;
                    break;
                }
            }
            
            if ($shouldHaveBranding) {
                $totalPages++;
                $content = file_get_contents($file->getPathname());
                if (strpos($content, '<x-dynamic-branding') !== false || strpos($content, 'x-dynamic-branding') !== false) {
                    $pagesWithBranding++;
                }
            }
        }
    }
    
    $percentage = $totalPages > 0 ? round(($pagesWithBranding / $totalPages) * 100, 1) : 0;
    
    echo "\n=== ESTATÍSTICAS DE BRANDING ===\n";
    echo "Total de páginas que deveriam ter branding: $totalPages\n";
    echo "Páginas com branding aplicado: $pagesWithBranding\n";
    echo "Cobertura: $percentage%\n";
    
    if ($percentage >= 90) {
        echo "✅ Excelente cobertura de branding!\n";
    } elseif ($percentage >= 70) {
        echo "⚠️  Boa cobertura, mas pode melhorar\n";
    } else {
        echo "❌ Cobertura insuficiente de branding\n";
    }
    
    // Verificar se há cores hardcoded restantes
    echo "\n=== VERIFICAÇÃO DE CORES HARDCODED ===\n";
    $hardcodedColors = ['#3B82F6', '#2563EB', '#1D4ED8', '#10B981', '#059669', '#6B7280'];
    $filesWithHardcoded = [];
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php' && strpos($file->getFilename(), '.blade.php') !== false) {
            $content = file_get_contents($file->getPathname());
            $relativePath = str_replace(__DIR__ . '/', '', $file->getPathname());
            
            foreach ($hardcodedColors as $color) {
                if (strpos($content, $color) !== false && strpos($relativePath, 'dynamic-branding') === false) {
                    if (!isset($filesWithHardcoded[$relativePath])) {
                        $filesWithHardcoded[$relativePath] = [];
                    }
                    $filesWithHardcoded[$relativePath][] = $color;
                }
            }
        }
    }
    
    if (empty($filesWithHardcoded)) {
        echo "✅ Nenhuma cor hardcoded encontrada\n";
    } else {
        echo "⚠️  Cores hardcoded encontradas em " . count($filesWithHardcoded) . " arquivos:\n";
        foreach ($filesWithHardcoded as $file => $colors) {
            echo "  • $file: " . implode(', ', $colors) . "\n";
        }
    }
    
    echo "\n=== TESTE CONCLUÍDO ===\n";
    echo "Status geral: ";
    if ($percentage >= 90 && empty($filesWithHardcoded)) {
        echo "✅ EXCELENTE - Branding totalmente consistente!\n";
    } elseif ($percentage >= 70) {
        echo "⚠️  BOM - Algumas melhorias necessárias\n";
    } else {
        echo "❌ PRECISA MELHORAR - Muitas páginas sem branding\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}

?>
