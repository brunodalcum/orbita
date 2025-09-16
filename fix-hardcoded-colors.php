<?php

// Script para substituir cores hardcoded por variáveis CSS dinâmicas

$viewsDir = __DIR__ . '/resources/views';

// Mapeamento de cores hardcoded para variáveis CSS
$colorMappings = [
    // Cores azuis (primárias)
    '#3B82F6' => 'var(--primary-color)',
    '#2563EB' => 'var(--primary-dark)',
    '#1D4ED8' => 'var(--primary-dark)',
    '#1E40AF' => 'var(--primary-dark)',
    'rgb(59, 130, 246)' => 'var(--primary-color)',
    'rgba(59, 130, 246' => 'rgba(var(--primary-color-rgb)',
    
    // Classes Tailwind para cores primárias
    'bg-blue-600' => 'bg-blue-600',  // Será tratado pelo CSS do branding
    'bg-blue-500' => 'bg-blue-500',
    'bg-indigo-600' => 'bg-indigo-600',
    'bg-indigo-500' => 'bg-indigo-500',
    'text-blue-600' => 'text-blue-600',
    'text-blue-500' => 'text-blue-500',
    'text-indigo-600' => 'text-indigo-600',
    'text-indigo-500' => 'text-indigo-500',
    'border-blue-600' => 'border-blue-600',
    'border-blue-500' => 'border-blue-500',
    
    // Cores verdes (accent)
    '#10B981' => 'var(--accent-color)',
    '#059669' => 'var(--accent-color)',
    '#047857' => 'var(--accent-color)',
    'rgb(16, 185, 129)' => 'var(--accent-color)',
    
    // Cores cinzas (secundárias)
    '#6B7280' => 'var(--secondary-color)',
    '#4B5563' => 'var(--secondary-color)',
    '#374151' => 'var(--secondary-color)',
];

// Função recursiva para buscar arquivos .blade.php
function findBladeFiles($dir) {
    $files = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php' && strpos($file->getFilename(), '.blade.php') !== false) {
            // Excluir alguns arquivos
            $excludePatterns = [
                '/emails/',
                '/components/dynamic-branding.blade.php',
                '.backup.',
                '/contracts/templates/'
            ];
            
            $shouldProcess = true;
            foreach ($excludePatterns as $pattern) {
                if (strpos($file->getPathname(), $pattern) !== false) {
                    $shouldProcess = false;
                    break;
                }
            }
            
            if ($shouldProcess) {
                $files[] = $file->getPathname();
            }
        }
    }
    
    return $files;
}

$bladeFiles = findBladeFiles($viewsDir);
$updated = [];
$changes = [];

foreach ($bladeFiles as $file) {
    $content = file_get_contents($file);
    $originalContent = $content;
    $fileChanges = [];
    
    // Aplicar substituições de cores
    foreach ($colorMappings as $oldColor => $newColor) {
        if (strpos($content, $oldColor) !== false) {
            $content = str_replace($oldColor, $newColor, $content);
            $fileChanges[] = "$oldColor → $newColor";
        }
    }
    
    // Procurar por estilos inline com cores hardcoded
    $patterns = [
        // background-color: #hex
        '/background-color:\s*#([0-9A-Fa-f]{6})/i',
        // color: #hex
        '/color:\s*#([0-9A-Fa-f]{6})/i',
        // border-color: #hex
        '/border-color:\s*#([0-9A-Fa-f]{6})/i',
    ];
    
    foreach ($patterns as $pattern) {
        if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $hexColor = '#' . strtoupper($match[1]);
                
                // Mapear cores conhecidas
                $replacement = null;
                switch ($hexColor) {
                    case '#3B82F6':
                    case '#2563EB':
                    case '#1D4ED8':
                    case '#1E40AF':
                        $replacement = 'var(--primary-color)';
                        break;
                    case '#10B981':
                    case '#059669':
                    case '#047857':
                        $replacement = 'var(--accent-color)';
                        break;
                    case '#6B7280':
                    case '#4B5563':
                    case '#374151':
                        $replacement = 'var(--secondary-color)';
                        break;
                }
                
                if ($replacement) {
                    $content = str_replace($match[0], str_replace($hexColor, $replacement, $match[0]), $content);
                    $fileChanges[] = "CSS: {$match[0]} → " . str_replace($hexColor, $replacement, $match[0]);
                }
            }
        }
    }
    
    // Se houve mudanças, salvar o arquivo
    if ($content !== $originalContent) {
        if (file_put_contents($file, $content)) {
            $relativePath = str_replace(__DIR__ . '/', '', $file);
            $updated[] = $relativePath;
            $changes[$relativePath] = $fileChanges;
        }
    }
}

echo "=== CORREÇÃO DE CORES HARDCODED ===\n\n";

if (count($updated) > 0) {
    echo "✅ ARQUIVOS ATUALIZADOS (" . count($updated) . "):\n";
    foreach ($updated as $file) {
        echo "\n📄 $file:\n";
        foreach ($changes[$file] as $change) {
            echo "  • $change\n";
        }
    }
} else {
    echo "ℹ️  Nenhuma cor hardcoded encontrada para substituir.\n";
}

echo "\n=== RESUMO ===\n";
echo "Arquivos processados: " . count($bladeFiles) . "\n";
echo "Arquivos atualizados: " . count($updated) . "\n";

?>
