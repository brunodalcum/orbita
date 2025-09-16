<?php

// Script para corrigir cores Tailwind hardcoded nas páginas específicas

$filesToFix = [
    'resources/views/dashboard/licenciados.blade.php',
    'resources/views/dashboard/contracts/index.blade.php'
];

$colorMappings = [
    // Botões primários
    'bg-blue-500' => 'bg-blue-500', // Será sobrescrito pelo CSS do branding
    'bg-blue-600' => 'bg-blue-600',
    'bg-blue-700' => 'bg-blue-700',
    'hover:bg-blue-600' => 'hover:bg-blue-600',
    'hover:bg-blue-700' => 'hover:bg-blue-700',
    
    // Textos
    'text-blue-500' => 'text-blue-500',
    'text-blue-600' => 'text-blue-600',
    'text-blue-700' => 'text-blue-700',
    'text-blue-800' => 'text-blue-800',
    
    // Bordas
    'border-blue-500' => 'border-blue-500',
    'hover:border-blue-500' => 'hover:border-blue-500',
    
    // Backgrounds
    'bg-blue-50' => 'bg-blue-50',
    'bg-blue-100' => 'bg-blue-100',
    'hover:bg-blue-100' => 'hover:bg-blue-100',
    'hover:bg-blue-200' => 'hover:bg-blue-200',
];

// Substituições específicas para usar style inline com variáveis CSS
$specificReplacements = [
    // Cores hexadecimais
    '#3b82f6' => 'var(--primary-color)',
    '#f8fafc' => '#f8fafc', // Manter cinza claro
    
    // Estilos específicos que precisam ser convertidos
    'class="bg-blue-500 hover:bg-blue-600' => 'style="background-color: var(--primary-color);" class="hover:bg-blue-600',
    'class="bg-blue-600 hover:bg-blue-700' => 'style="background-color: var(--primary-color);" class="hover:bg-blue-700',
    'class="text-blue-500' => 'style="color: var(--primary-color);" class="',
    'class="text-blue-600' => 'style="color: var(--primary-color);" class="',
    'class="text-blue-700' => 'style="color: var(--primary-color);" class="',
    'border-b-2 border-blue-500' => 'border-b-2" style="border-color: var(--primary-color);',
];

$updated = [];
$errors = [];

foreach ($filesToFix as $filePath) {
    $fullPath = __DIR__ . '/' . $filePath;
    
    if (!file_exists($fullPath)) {
        $errors[] = "Arquivo não encontrado: $filePath";
        continue;
    }
    
    $content = file_get_contents($fullPath);
    $originalContent = $content;
    $changes = [];
    
    // Aplicar substituições específicas
    foreach ($specificReplacements as $old => $new) {
        if (strpos($content, $old) !== false) {
            $content = str_replace($old, $new, $content);
            $changes[] = "$old → $new";
        }
    }
    
    // Corrigir cores hexadecimais específicas
    $content = str_replace('#3b82f6', 'var(--primary-color)', $content);
    
    // Adicionar estilos CSS específicos para sobrescrever Tailwind
    $additionalCSS = '
        /* Sobrescrever cores Tailwind específicas */
        .bg-blue-500, .bg-blue-600, .bg-blue-700 {
            background-color: var(--primary-color) !important;
        }
        .hover\\:bg-blue-600:hover, .hover\\:bg-blue-700:hover {
            background-color: var(--primary-dark) !important;
        }
        .text-blue-500, .text-blue-600, .text-blue-700, .text-blue-800 {
            color: var(--primary-color) !important;
        }
        .border-blue-500, .hover\\:border-blue-500:hover {
            border-color: var(--primary-color) !important;
        }
        .bg-blue-50, .bg-blue-100 {
            background-color: var(--primary-light) !important;
        }
        .hover\\:bg-blue-100:hover, .hover\\:bg-blue-200:hover {
            background-color: var(--primary-light) !important;
        }
        .border-b-2.border-blue-500 {
            border-color: var(--primary-color) !important;
        }
    ';
    
    // Inserir CSS adicional antes do </style> final
    if (strpos($content, '</style>') !== false) {
        $content = str_replace('</style>', $additionalCSS . '</style>', $content);
        $changes[] = "CSS adicional para sobrescrever Tailwind";
    }
    
    // Se houve mudanças, salvar o arquivo
    if ($content !== $originalContent) {
        if (file_put_contents($fullPath, $content)) {
            $updated[] = $filePath;
            echo "✅ $filePath:\n";
            foreach ($changes as $change) {
                echo "  • $change\n";
            }
            echo "\n";
        } else {
            $errors[] = "Erro ao escrever: $filePath";
        }
    } else {
        echo "ℹ️  $filePath: Nenhuma mudança necessária\n";
    }
}

echo "=== CORREÇÃO DE CORES TAILWIND ===\n\n";

if (count($updated) > 0) {
    echo "✅ ARQUIVOS ATUALIZADOS (" . count($updated) . "):\n";
    foreach ($updated as $file) {
        echo "  • $file\n";
    }
} else {
    echo "ℹ️  Nenhum arquivo precisou ser atualizado.\n";
}

if (count($errors) > 0) {
    echo "\n❌ ERROS (" . count($errors) . "):\n";
    foreach ($errors as $error) {
        echo "  • $error\n";
    }
}

echo "\n=== RESUMO ===\n";
echo "Arquivos processados: " . count($filesToFix) . "\n";
echo "Arquivos atualizados: " . count($updated) . "\n";
echo "Erros: " . count($errors) . "\n";

?>
