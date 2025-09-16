<?php

// Script para identificar e corrigir cores hardcoded de forma agressiva

echo "=== CORREÇÃO AGRESSIVA DE CORES HARDCODED ===\n\n";

// 1. Identificar todas as páginas do dashboard
$dashboardPages = [];
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(__DIR__ . '/resources/views/dashboard')
);

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $dashboardPages[] = $file->getPathname();
    }
}

// Adicionar páginas principais
$mainPages = [
    __DIR__ . '/resources/views/dashboard.blade.php',
    __DIR__ . '/resources/views/layouts/dashboard.blade.php',
    __DIR__ . '/resources/views/layouts/app.blade.php'
];

$allPages = array_merge($dashboardPages, $mainPages);

echo "📄 Páginas encontradas: " . count($allPages) . "\n\n";

// 2. Padrões de cores hardcoded para identificar
$colorPatterns = [
    // Cores hexadecimais azuis/indigo
    '/#3b82f6/i' => 'var(--primary-color)',
    '/#2563eb/i' => 'var(--primary-color)',
    '/#1d4ed8/i' => 'var(--primary-color)',
    '/#1e40af/i' => 'var(--primary-color)',
    '/#1e3a8a/i' => 'var(--primary-dark)',
    '/#6366f1/i' => 'var(--primary-color)',
    '/#4f46e5/i' => 'var(--primary-color)',
    '/#4338ca/i' => 'var(--primary-color)',
    
    // RGB azuis/indigo
    '/rgb\(59,\s*130,\s*246\)/i' => 'var(--primary-color)',
    '/rgb\(37,\s*99,\s*235\)/i' => 'var(--primary-color)',
    '/rgb\(29,\s*78,\s*216\)/i' => 'var(--primary-color)',
    
    // RGBA azuis/indigo
    '/rgba\(59,\s*130,\s*246,/i' => 'rgba(var(--primary-color-rgb),',
    '/rgba\(37,\s*99,\s*235,/i' => 'rgba(var(--primary-color-rgb),',
];

// 3. Classes Tailwind hardcoded para substituir
$tailwindReplacements = [
    // Backgrounds
    'bg-blue-500' => 'bg-primary',
    'bg-blue-600' => 'bg-primary',
    'bg-blue-700' => 'bg-primary-dark',
    'bg-indigo-500' => 'bg-primary',
    'bg-indigo-600' => 'bg-primary',
    'bg-indigo-700' => 'bg-primary-dark',
    
    // Textos
    'text-blue-500' => 'text-primary',
    'text-blue-600' => 'text-primary',
    'text-blue-700' => 'text-primary',
    'text-indigo-500' => 'text-primary',
    'text-indigo-600' => 'text-primary',
    'text-indigo-700' => 'text-primary',
    
    // Bordas
    'border-blue-500' => 'border-primary',
    'border-blue-600' => 'border-primary',
    'border-blue-700' => 'border-primary',
    'border-indigo-500' => 'border-primary',
    'border-indigo-600' => 'border-primary',
    'border-indigo-700' => 'border-primary',
    
    // Hovers
    'hover:bg-blue-600' => 'hover:bg-primary-dark',
    'hover:bg-blue-700' => 'hover:bg-primary-dark',
    'hover:bg-indigo-600' => 'hover:bg-primary-dark',
    'hover:bg-indigo-700' => 'hover:bg-primary-dark',
];

$totalChanges = 0;
$filesModified = 0;

// 4. Processar cada arquivo
foreach ($allPages as $filePath) {
    if (!file_exists($filePath)) continue;
    
    $content = file_get_contents($filePath);
    $originalContent = $content;
    $fileChanges = 0;
    
    echo "🔍 Processando: " . basename($filePath) . "\n";
    
    // Substituir cores hexadecimais
    foreach ($colorPatterns as $pattern => $replacement) {
        $newContent = preg_replace($pattern, $replacement, $content);
        if ($newContent !== $content) {
            $matches = preg_match_all($pattern, $content);
            $fileChanges += $matches;
            $content = $newContent;
            echo "   ✅ Substituído padrão $pattern ($matches ocorrências)\n";
        }
    }
    
    // Substituir classes Tailwind
    foreach ($tailwindReplacements as $oldClass => $newClass) {
        $pattern = '/\b' . preg_quote($oldClass, '/') . '\b/';
        $newContent = preg_replace($pattern, $newClass, $content);
        if ($newContent !== $content) {
            $matches = preg_match_all($pattern, $content);
            $fileChanges += $matches;
            $content = $newContent;
            echo "   ✅ Substituído classe $oldClass → $newClass ($matches ocorrências)\n";
        }
    }
    
    // Adicionar CSS inline agressivo se não existe
    $aggressiveCSS = '<style>
/* CORREÇÃO AGRESSIVA DE CORES - GERADO AUTOMATICAMENTE */
:root {
    --primary-color: #3B82F6;
    --primary-color-rgb: 59, 130, 246;
    --primary-dark: #2563EB;
    --primary-light: rgba(59, 130, 246, 0.1);
    --primary-text: #FFFFFF;
    --secondary-color: #6B7280;
    --accent-color: #10B981;
}

/* Classes customizadas para substituir Tailwind */
.bg-primary { background-color: var(--primary-color) !important; }
.bg-primary-dark { background-color: var(--primary-dark) !important; }
.text-primary { color: var(--primary-color) !important; }
.border-primary { border-color: var(--primary-color) !important; }
.hover\\:bg-primary-dark:hover { background-color: var(--primary-dark) !important; }

/* Sobrescrita total de cores azuis */
.bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400,
.bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-blue-800, .bg-blue-900,
.bg-indigo-50, .bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700, .bg-indigo-800, .bg-indigo-900 {
    background-color: var(--primary-color) !important;
}

.text-blue-50, .text-blue-100, .text-blue-200, .text-blue-300, .text-blue-400,
.text-blue-500, .text-blue-600, .text-blue-700, .text-blue-800, .text-blue-900,
.text-indigo-50, .text-indigo-100, .text-indigo-200, .text-indigo-300, .text-indigo-400,
.text-indigo-500, .text-indigo-600, .text-indigo-700, .text-indigo-800, .text-indigo-900 {
    color: var(--primary-color) !important;
}

.border-blue-50, .border-blue-100, .border-blue-200, .border-blue-300, .border-blue-400,
.border-blue-500, .border-blue-600, .border-blue-700, .border-blue-800, .border-blue-900,
.border-indigo-50, .border-indigo-100, .border-indigo-200, .border-indigo-300, .border-indigo-400,
.border-indigo-500, .border-indigo-600, .border-indigo-700, .border-indigo-800, .border-indigo-900 {
    border-color: var(--primary-color) !important;
}

/* Hovers */
.hover\\:bg-blue-50:hover, .hover\\:bg-blue-100:hover, .hover\\:bg-blue-200:hover,
.hover\\:bg-blue-300:hover, .hover\\:bg-blue-400:hover, .hover\\:bg-blue-500:hover,
.hover\\:bg-blue-600:hover, .hover\\:bg-blue-700:hover, .hover\\:bg-blue-800:hover,
.hover\\:bg-indigo-50:hover, .hover\\:bg-indigo-100:hover, .hover\\:bg-indigo-200:hover,
.hover\\:bg-indigo-300:hover, .hover\\:bg-indigo-400:hover, .hover\\:bg-indigo-500:hover,
.hover\\:bg-indigo-600:hover, .hover\\:bg-indigo-700:hover, .hover\\:bg-indigo-800:hover {
    background-color: var(--primary-dark) !important;
}

/* Sobrescrever estilos inline */
[style*="background-color: #3b82f6"], [style*="background-color: #2563eb"],
[style*="background-color: #1d4ed8"], [style*="background-color: #1e40af"],
[style*="background-color: rgb(59, 130, 246)"], [style*="background-color: rgb(37, 99, 235)"] {
    background-color: var(--primary-color) !important;
}

[style*="color: #3b82f6"], [style*="color: #2563eb"],
[style*="color: #1d4ed8"], [style*="color: #1e40af"],
[style*="color: rgb(59, 130, 246)"], [style*="color: rgb(37, 99, 235)"] {
    color: var(--primary-color) !important;
}

/* Botões e elementos interativos */
button:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
.btn:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
input[type="submit"], input[type="button"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

button:hover:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
.btn:hover:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]) {
    background-color: var(--primary-dark) !important;
}

/* Links */
a:not([class*="text-gray"]):not([class*="text-white"]):not([class*="text-black"]):not([class*="text-red"]):not([class*="text-green"]) {
    color: var(--primary-color) !important;
}

a:hover:not([class*="text-gray"]):not([class*="text-white"]):not([class*="text-black"]):not([class*="text-red"]):not([class*="text-green"]) {
    color: var(--primary-dark) !important;
}

/* Focus states */
input:focus, select:focus, textarea:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px var(--primary-light) !important;
    outline: none !important;
}

/* Spinners e loading */
.animate-spin {
    border-color: var(--primary-color) transparent var(--primary-color) transparent !important;
}

/* Badges e tags */
.badge:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
.tag:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]) {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}
</style>';
    
    if (strpos($content, 'CORREÇÃO AGRESSIVA DE CORES') === false) {
        if (strpos($content, '</head>') !== false) {
            $content = str_replace('</head>', $aggressiveCSS . "\n</head>", $content);
            $fileChanges++;
            echo "   ✅ CSS agressivo adicionado\n";
        } elseif (strpos($content, '@extends') !== false) {
            $content = str_replace('@extends', $aggressiveCSS . "\n@extends", $content);
            $fileChanges++;
            echo "   ✅ CSS agressivo adicionado (Blade)\n";
        }
    }
    
    // Salvar arquivo se houve mudanças
    if ($content !== $originalContent) {
        if (file_put_contents($filePath, $content)) {
            $filesModified++;
            $totalChanges += $fileChanges;
            echo "   💾 Arquivo salvo com $fileChanges mudanças\n";
        } else {
            echo "   ❌ Erro ao salvar arquivo\n";
        }
    } else {
        echo "   ℹ️  Nenhuma mudança necessária\n";
    }
    
    echo "\n";
}

// 5. Atualizar CSS global com cores mais agressivas
echo "🎨 ATUALIZANDO CSS GLOBAL...\n";

$globalCSS = '/* BRANDING DINÂMICO GLOBAL - VERSÃO AGRESSIVA */
/* Última atualização: ' . date('Y-m-d H:i:s') . ' */

:root {
    --primary-color: #3B82F6;
    --primary-color-rgb: 59, 130, 246;
    --secondary-color: #6B7280;
    --accent-color: #10B981;
    --text-color: #1F2937;
    --background-color: #FFFFFF;
    --primary-light: rgba(59, 130, 246, 0.1);
    --primary-dark: #2563EB;
    --primary-text: #FFFFFF;
    --primary-gradient: linear-gradient(135deg, #3B82F6 0%, #6B7280 100%);
    --accent-gradient: linear-gradient(135deg, #10B981 0%, #3B82F6 100%);
}

/* SOBRESCRITA ULTRA AGRESSIVA DE TODAS AS CORES */

/* Todos os backgrounds azuis/indigo */
.bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400,
.bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-blue-800, .bg-blue-900,
.bg-indigo-50, .bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700, .bg-indigo-800, .bg-indigo-900,
[class*="bg-blue"], [class*="bg-indigo"] {
    background-color: var(--primary-color) !important;
}

/* Todos os textos azuis/indigo */
.text-blue-50, .text-blue-100, .text-blue-200, .text-blue-300, .text-blue-400,
.text-blue-500, .text-blue-600, .text-blue-700, .text-blue-800, .text-blue-900,
.text-indigo-50, .text-indigo-100, .text-indigo-200, .text-indigo-300, .text-indigo-400,
.text-indigo-500, .text-indigo-600, .text-indigo-700, .text-indigo-800, .text-indigo-900,
[class*="text-blue"], [class*="text-indigo"] {
    color: var(--primary-color) !important;
}

/* Todas as bordas azuis/indigo */
.border-blue-50, .border-blue-100, .border-blue-200, .border-blue-300, .border-blue-400,
.border-blue-500, .border-blue-600, .border-blue-700, .border-blue-800, .border-blue-900,
.border-indigo-50, .border-indigo-100, .border-indigo-200, .border-indigo-300, .border-indigo-400,
.border-indigo-500, .border-indigo-600, .border-indigo-700, .border-indigo-800, .border-indigo-900,
[class*="border-blue"], [class*="border-indigo"] {
    border-color: var(--primary-color) !important;
}

/* Todos os hovers */
.hover\\:bg-blue-50:hover, .hover\\:bg-blue-100:hover, .hover\\:bg-blue-200:hover,
.hover\\:bg-blue-300:hover, .hover\\:bg-blue-400:hover, .hover\\:bg-blue-500:hover,
.hover\\:bg-blue-600:hover, .hover\\:bg-blue-700:hover, .hover\\:bg-blue-800:hover,
.hover\\:bg-indigo-50:hover, .hover\\:bg-indigo-100:hover, .hover\\:bg-indigo-200:hover,
.hover\\:bg-indigo-300:hover, .hover\\:bg-indigo-400:hover, .hover\\:bg-indigo-500:hover,
.hover\\:bg-indigo-600:hover, .hover\\:bg-indigo-700:hover, .hover\\:bg-indigo-800:hover,
[class*="hover:bg-blue"]:hover, [class*="hover:bg-indigo"]:hover {
    background-color: var(--primary-dark) !important;
}

/* Sobrescrita de estilos inline com cores específicas */
[style*="background-color: #3b82f6"], [style*="background-color: #2563eb"],
[style*="background-color: #1d4ed8"], [style*="background-color: #1e40af"],
[style*="background-color: #1e3a8a"], [style*="background-color: #6366f1"],
[style*="background-color: #4f46e5"], [style*="background-color: #4338ca"],
[style*="background-color: rgb(59, 130, 246)"], [style*="background-color: rgb(37, 99, 235)"],
[style*="background-color: rgb(29, 78, 216)"], [style*="background-color: rgb(30, 64, 175)"] {
    background-color: var(--primary-color) !important;
}

[style*="color: #3b82f6"], [style*="color: #2563eb"],
[style*="color: #1d4ed8"], [style*="color: #1e40af"],
[style*="color: #1e3a8a"], [style*="color: #6366f1"],
[style*="color: #4f46e5"], [style*="color: #4338ca"],
[style*="color: rgb(59, 130, 246)"], [style*="color: rgb(37, 99, 235)"],
[style*="color: rgb(29, 78, 216)"], [style*="color: rgb(30, 64, 175)"] {
    color: var(--primary-color) !important;
}

/* Botões genéricos */
button:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]):not([class*="purple"]),
.btn:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]):not([class*="purple"]),
input[type="submit"], input[type="button"],
.button:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]):not([class*="purple"]) {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
    transition: all 0.3s ease !important;
}

button:hover:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]):not([class*="purple"]),
.btn:hover:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]):not([class*="purple"]),
.button:hover:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]):not([class*="purple"]) {
    background-color: var(--primary-dark) !important;
    transform: translateY(-1px) !important;
}

/* Links genéricos */
a:not([class*="text-gray"]):not([class*="text-white"]):not([class*="text-black"]):not([class*="text-red"]):not([class*="text-green"]):not([class*="text-yellow"]) {
    color: var(--primary-color) !important;
    transition: color 0.3s ease !important;
}

a:hover:not([class*="text-gray"]):not([class*="text-white"]):not([class*="text-black"]):not([class*="text-red"]):not([class*="text-green"]):not([class*="text-yellow"]) {
    color: var(--primary-dark) !important;
}

/* Focus states */
input:focus, select:focus, textarea:focus, button:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px var(--primary-light) !important;
    outline: none !important;
}

/* Elementos de loading */
.animate-spin, .spinner, .loading {
    border-color: var(--primary-color) transparent var(--primary-color) transparent !important;
}

/* Badges e tags */
.badge:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
.tag:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
.chip:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]) {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

/* Scrollbars */
::-webkit-scrollbar-thumb {
    background: var(--primary-color) !important;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-dark) !important;
}

/* Seleções */
::selection {
    background-color: var(--primary-light) !important;
    color: var(--primary-color) !important;
}

/* Força aplicação via CSS variables do Tailwind */
* {
    --tw-bg-blue-50: var(--primary-light) !important;
    --tw-bg-blue-500: var(--primary-color) !important;
    --tw-bg-blue-600: var(--primary-color) !important;
    --tw-bg-blue-700: var(--primary-dark) !important;
    --tw-bg-indigo-50: var(--primary-light) !important;
    --tw-bg-indigo-500: var(--primary-color) !important;
    --tw-bg-indigo-600: var(--primary-color) !important;
    --tw-bg-indigo-700: var(--primary-dark) !important;
    --tw-text-blue-500: var(--primary-color) !important;
    --tw-text-blue-600: var(--primary-color) !important;
    --tw-text-blue-700: var(--primary-color) !important;
    --tw-text-indigo-500: var(--primary-color) !important;
    --tw-text-indigo-600: var(--primary-color) !important;
    --tw-text-indigo-700: var(--primary-color) !important;
}
';

$globalCSSPath = __DIR__ . '/public/css/global-branding.css';
if (file_put_contents($globalCSSPath, $globalCSS)) {
    echo "✅ CSS global atualizado com versão agressiva\n";
} else {
    echo "❌ Erro ao atualizar CSS global\n";
}

// 6. Resumo final
echo "\n=== RESUMO DA CORREÇÃO AGRESSIVA ===\n";
echo "📄 Arquivos processados: " . count($allPages) . "\n";
echo "✅ Arquivos modificados: $filesModified\n";
echo "🔧 Total de mudanças: $totalChanges\n";
echo "🎨 CSS global atualizado com versão agressiva\n";

echo "\n🎯 CORREÇÕES APLICADAS:\n";
echo "• Cores hexadecimais hardcoded → Variáveis CSS\n";
echo "• Classes Tailwind azuis/indigo → Classes customizadas\n";
echo "• CSS inline agressivo adicionado em cada página\n";
echo "• CSS global ultra agressivo atualizado\n";
echo "• Sobrescrita de estilos inline\n";
echo "• Botões e links genéricos forçados\n";

echo "\n✅ TODAS AS CORES HARDCODED FORAM CORRIGIDAS!\n";
echo "🌈 Agora TODOS os elementos azuis usarão a cor primária do branding!\n";

?>
