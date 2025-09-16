<?php

// Script para encontrar páginas sem branding dinâmico

$viewsDir = __DIR__ . '/resources/views';
$missingBranding = [];
$hasBranding = [];

// Função recursiva para buscar arquivos .blade.php
function findBladeFiles($dir) {
    $files = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php' && strpos($file->getFilename(), '.blade.php') !== false) {
            $files[] = $file->getPathname();
        }
    }
    
    return $files;
}

$bladeFiles = findBladeFiles($viewsDir);

foreach ($bladeFiles as $file) {
    $content = file_get_contents($file);
    $relativePath = str_replace(__DIR__ . '/', '', $file);
    
    // Verificar se tem x-dynamic-branding
    if (strpos($content, '<x-dynamic-branding') !== false || strpos($content, 'x-dynamic-branding') !== false) {
        $hasBranding[] = $relativePath;
    } else {
        // Verificar se é uma página que deveria ter branding (não é component, email, layout base, etc.)
        $shouldHaveBranding = true;
        
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
        
        foreach ($excludePatterns as $pattern) {
            if (strpos($relativePath, $pattern) !== false) {
                $shouldHaveBranding = false;
                break;
            }
        }
        
        if ($shouldHaveBranding) {
            $missingBranding[] = $relativePath;
        }
    }
}

echo "=== PÁGINAS COM BRANDING DINÂMICO ===\n";
echo "Total: " . count($hasBranding) . "\n\n";
foreach ($hasBranding as $file) {
    echo "✅ $file\n";
}

echo "\n=== PÁGINAS SEM BRANDING DINÂMICO ===\n";
echo "Total: " . count($missingBranding) . "\n\n";
foreach ($missingBranding as $file) {
    echo "❌ $file\n";
}

echo "\n=== RESUMO ===\n";
echo "Com branding: " . count($hasBranding) . "\n";
echo "Sem branding: " . count($missingBranding) . "\n";
echo "Total analisado: " . count($bladeFiles) . "\n";

?>
