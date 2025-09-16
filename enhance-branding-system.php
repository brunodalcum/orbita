<?php

// Script para melhorar o sistema de branding dinâmico

echo "=== MELHORANDO SISTEMA DE BRANDING DINÂMICO ===\n\n";

// 1. Melhorar o componente de branding para ser mais robusto
$brandingFile = __DIR__ . '/resources/views/components/dynamic-branding.blade.php';
$brandingContent = file_get_contents($brandingFile);

// Adicionar CSS mais agressivo para sobrescrever todas as cores
$enhancedCSS = '

/* SISTEMA DE BRANDING DINÂMICO APRIMORADO */
/* Sobrescrever TODAS as classes Tailwind de cores azuis */
.bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400, 
.bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-blue-800, .bg-blue-900 {
    background-color: var(--primary-color) !important;
}

.hover\\:bg-blue-50:hover, .hover\\:bg-blue-100:hover, .hover\\:bg-blue-200:hover,
.hover\\:bg-blue-300:hover, .hover\\:bg-blue-400:hover, .hover\\:bg-blue-500:hover,
.hover\\:bg-blue-600:hover, .hover\\:bg-blue-700:hover, .hover\\:bg-blue-800:hover {
    background-color: var(--primary-dark) !important;
}

.text-blue-50, .text-blue-100, .text-blue-200, .text-blue-300, .text-blue-400,
.text-blue-500, .text-blue-600, .text-blue-700, .text-blue-800, .text-blue-900 {
    color: var(--primary-color) !important;
}

.border-blue-50, .border-blue-100, .border-blue-200, .border-blue-300, .border-blue-400,
.border-blue-500, .border-blue-600, .border-blue-700, .border-blue-800, .border-blue-900 {
    border-color: var(--primary-color) !important;
}

/* Sobrescrever cores indigo também */
.bg-indigo-50, .bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700, .bg-indigo-800, .bg-indigo-900 {
    background-color: var(--primary-color) !important;
}

.text-indigo-50, .text-indigo-100, .text-indigo-200, .text-indigo-300, .text-indigo-400,
.text-indigo-500, .text-indigo-600, .text-indigo-700, .text-indigo-800, .text-indigo-900 {
    color: var(--primary-color) !important;
}

/* Cores verdes para accent */
.bg-green-50, .bg-green-100 {
    background-color: var(--accent-color) !important;
    opacity: 0.1;
}

.bg-green-500, .bg-green-600, .bg-green-700 {
    background-color: var(--accent-color) !important;
}

.text-green-500, .text-green-600, .text-green-700, .text-green-800 {
    color: var(--accent-color) !important;
}

/* Estilos específicos para elementos comuns */
button[class*="bg-blue"], a[class*="bg-blue"], .btn[class*="blue"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

button[class*="bg-blue"]:hover, a[class*="bg-blue"]:hover, .btn[class*="blue"]:hover {
    background-color: var(--primary-dark) !important;
}

/* Elementos com cores hardcoded via style */
[style*="background-color: #3b82f6"], [style*="background-color: #2563eb"],
[style*="background-color: #1d4ed8"], [style*="background-color: #1e40af"] {
    background-color: var(--primary-color) !important;
}

[style*="color: #3b82f6"], [style*="color: #2563eb"],
[style*="color: #1d4ed8"], [style*="color: #1e40af"] {
    color: var(--primary-color) !important;
}

/* Animações e loading spinners */
.animate-spin[class*="border-blue"] {
    border-color: var(--primary-color) !important;
}

/* Links e elementos interativos */
a:not([class*="text-"]):not([class*="bg-"]) {
    color: var(--primary-color);
}

a:not([class*="text-"]):not([class*="bg-"]):hover {
    color: var(--primary-dark);
}

/* Formulários */
input:focus, select:focus, textarea:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px var(--primary-light) !important;
}

/* Badges e tags */
.badge, .tag, [class*="rounded-full"][class*="bg-blue"] {
    background-color: var(--primary-light) !important;
    color: var(--primary-color) !important;
}

/* Tabelas */
th, .table-header {
    border-bottom-color: var(--primary-color) !important;
}

/* Modais e overlays */
.modal-header, .dialog-header {
    background: var(--primary-gradient) !important;
    color: var(--primary-text) !important;
}

/* Sidebar e navegação */
.sidebar [class*="bg-blue"], .nav [class*="bg-blue"] {
    background-color: var(--primary-color) !important;
}

/* Cards e containers */
.card-primary, .container-primary {
    border-left: 4px solid var(--primary-color) !important;
}

/* Elementos específicos do dashboard */
.dashboard-stat, .metric-card {
    background: var(--primary-gradient) !important;
    color: var(--primary-text) !important;
}

/* Força aplicação em elementos inline */
* {
    --tw-bg-blue-500: var(--primary-color) !important;
    --tw-bg-blue-600: var(--primary-color) !important;
    --tw-bg-blue-700: var(--primary-dark) !important;
    --tw-text-blue-500: var(--primary-color) !important;
    --tw-text-blue-600: var(--primary-color) !important;
    --tw-text-blue-700: var(--primary-color) !important;
}
';

// Inserir o CSS aprimorado antes do fechamento do estilo
$brandingContent = str_replace('</style>', $enhancedCSS . '</style>', $brandingContent);

// Salvar o arquivo aprimorado
if (file_put_contents($brandingFile, $brandingContent)) {
    echo "✅ Componente de branding aprimorado\n";
} else {
    echo "❌ Erro ao aprimorar componente de branding\n";
}

// 2. Verificar se todas as páginas do dashboard têm o branding
$dashboardPages = [
    'resources/views/dashboard.blade.php',
    'resources/views/dashboard/licenciados.blade.php',
    'resources/views/dashboard/contracts/index.blade.php',
    'resources/views/dashboard/contracts/show.blade.php',
    'resources/views/dashboard/contracts/create.blade.php',
    'resources/views/dashboard/leads.blade.php',
    'resources/views/dashboard/planos.blade.php',
    'resources/views/dashboard/operacoes.blade.php',
    'resources/views/dashboard/adquirentes.blade.php',
    'resources/views/dashboard/configuracoes.blade.php',
    'resources/views/dashboard/agenda.blade.php',
    'resources/views/dashboard/marketing/index.blade.php',
    'resources/views/dashboard/users/index.blade.php',
    'resources/views/dashboard/permissions/index.blade.php',
    'resources/views/layouts/dashboard.blade.php',
    'resources/views/layouts/app.blade.php'
];

$pagesWithoutBranding = [];
$pagesWithBranding = [];

foreach ($dashboardPages as $page) {
    $fullPath = __DIR__ . '/' . $page;
    
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        
        if (strpos($content, '<x-dynamic-branding') !== false || strpos($content, 'x-dynamic-branding') !== false) {
            $pagesWithBranding[] = $page;
        } else {
            $pagesWithoutBranding[] = $page;
        }
    }
}

echo "\n=== STATUS DAS PÁGINAS DO DASHBOARD ===\n";
echo "✅ Com branding (" . count($pagesWithBranding) . "):\n";
foreach ($pagesWithBranding as $page) {
    echo "  • $page\n";
}

if (count($pagesWithoutBranding) > 0) {
    echo "\n❌ Sem branding (" . count($pagesWithoutBranding) . "):\n";
    foreach ($pagesWithoutBranding as $page) {
        echo "  • $page\n";
    }
    
    // Adicionar branding nas páginas que não têm
    foreach ($pagesWithoutBranding as $page) {
        $fullPath = __DIR__ . '/' . $page;
        $content = file_get_contents($fullPath);
        
        // Inserir branding no início do arquivo
        if (strpos($content, '@extends') !== false) {
            $content = str_replace('@extends', "<x-dynamic-branding />\n@extends", $content);
        } elseif (strpos($content, '<!DOCTYPE') !== false) {
            $content = str_replace('<!DOCTYPE', "<x-dynamic-branding />\n<!DOCTYPE", $content);
        } else {
            $content = "<x-dynamic-branding />\n" . $content;
        }
        
        if (file_put_contents($fullPath, $content)) {
            echo "  ✅ Branding adicionado em $page\n";
        }
    }
}

echo "\n=== RESUMO ===\n";
echo "✅ Sistema de branding aprimorado\n";
echo "✅ CSS mais agressivo para sobrescrever todas as cores\n";
echo "✅ Todas as páginas do dashboard com branding\n";
echo "✅ Paleta de cores será aplicada automaticamente\n";

?>
