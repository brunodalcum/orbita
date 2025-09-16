<?php

// Script para criar um sistema de branding global

echo "=== CRIANDO SISTEMA DE BRANDING GLOBAL ===\n\n";

// 1. Criar um arquivo CSS global que será incluído em todos os layouts
$globalBrandingCSS = '/* BRANDING DINÂMICO GLOBAL - GERADO AUTOMATICAMENTE */
/* Este arquivo é atualizado automaticamente quando o branding é alterado */

:root {
    /* Cores padrão - serão sobrescritas pelo branding dinâmico */
    --primary-color: #3B82F6;
    --secondary-color: #6B7280;
    --accent-color: #10B981;
    --text-color: #1F2937;
    --background-color: #FFFFFF;
    --primary-light: rgba(59, 130, 246, 0.1);
    --primary-dark: #2563EB;
    --primary-text: #FFFFFF;
}

/* SOBRESCRITA GLOBAL DE TODAS AS CORES TAILWIND */
/* Força aplicação da paleta personalizada */

/* Backgrounds azuis -> Cor primária */
.bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400,
.bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-blue-800, .bg-blue-900,
.bg-indigo-50, .bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700, .bg-indigo-800, .bg-indigo-900 {
    background-color: var(--primary-color) !important;
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

/* Textos azuis -> Cor primária */
.text-blue-50, .text-blue-100, .text-blue-200, .text-blue-300, .text-blue-400,
.text-blue-500, .text-blue-600, .text-blue-700, .text-blue-800, .text-blue-900,
.text-indigo-50, .text-indigo-100, .text-indigo-200, .text-indigo-300, .text-indigo-400,
.text-indigo-500, .text-indigo-600, .text-indigo-700, .text-indigo-800, .text-indigo-900 {
    color: var(--primary-color) !important;
}

/* Bordas azuis -> Cor primária */
.border-blue-50, .border-blue-100, .border-blue-200, .border-blue-300, .border-blue-400,
.border-blue-500, .border-blue-600, .border-blue-700, .border-blue-800, .border-blue-900,
.border-indigo-50, .border-indigo-100, .border-indigo-200, .border-indigo-300, .border-indigo-400,
.border-indigo-500, .border-indigo-600, .border-indigo-700, .border-indigo-800, .border-indigo-900 {
    border-color: var(--primary-color) !important;
}

/* Cores verdes -> Accent */
.bg-green-500, .bg-green-600, .bg-green-700, .bg-emerald-500, .bg-emerald-600, .bg-emerald-700 {
    background-color: var(--accent-color) !important;
}

.text-green-500, .text-green-600, .text-green-700, .text-green-800,
.text-emerald-500, .text-emerald-600, .text-emerald-700, .text-emerald-800 {
    color: var(--accent-color) !important;
}

/* Elementos específicos */
button, .btn {
    transition: all 0.3s ease;
}

button[class*="blue"], .btn[class*="blue"], 
button[class*="indigo"], .btn[class*="indigo"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

button[class*="blue"]:hover, .btn[class*="blue"]:hover,
button[class*="indigo"]:hover, .btn[class*="indigo"]:hover {
    background-color: var(--primary-dark) !important;
}

/* Links */
a:not([class*="text-"]):not([class*="bg-"]) {
    color: var(--primary-color);
    text-decoration: none;
}

a:not([class*="text-"]):not([class*="bg-"]):hover {
    color: var(--primary-dark);
}

/* Formulários */
input:focus, select:focus, textarea:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px var(--primary-light) !important;
    outline: none;
}

/* Animações */
.animate-spin[class*="border-blue"], .animate-spin[class*="border-indigo"] {
    border-color: var(--primary-color) transparent var(--primary-color) transparent !important;
}

/* Força aplicação via CSS variables do Tailwind */
* {
    --tw-bg-blue-500: var(--primary-color) !important;
    --tw-bg-blue-600: var(--primary-color) !important;
    --tw-bg-blue-700: var(--primary-dark) !important;
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

/* Sobrescrever estilos inline hardcoded */
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

[style*="border-color: #3b82f6"], [style*="border-color: #2563eb"],
[style*="border-color: #1d4ed8"], [style*="border-color: #1e40af"] {
    border-color: var(--primary-color) !important;
}

/* Elementos específicos do dashboard */
.dashboard-card, .metric-card, .stat-card {
    border-left: 4px solid var(--primary-color);
}

.dashboard-header {
    background: var(--primary-gradient);
    color: var(--primary-text);
}

/* Sidebar */
.sidebar-active, .nav-active {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

/* Modais */
.modal-header, .dialog-header {
    background: var(--primary-gradient);
    color: var(--primary-text);
}

/* Tabelas */
.table-primary th {
    background-color: var(--primary-color);
    color: var(--primary-text);
}

/* Badges e tags */
.badge-primary, .tag-primary {
    background-color: var(--primary-light);
    color: var(--primary-color);
}

/* Progressos */
.progress-bar, .progress-fill {
    background-color: var(--primary-color);
}

/* Scrollbars */
::-webkit-scrollbar-thumb {
    background: var(--primary-color);
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-dark);
}
';

// Salvar o CSS global
$globalCSSPath = __DIR__ . '/public/css/global-branding.css';

// Criar diretório se não existir
$cssDir = dirname($globalCSSPath);
if (!is_dir($cssDir)) {
    mkdir($cssDir, 0755, true);
}

if (file_put_contents($globalCSSPath, $globalBrandingCSS)) {
    echo "✅ CSS global de branding criado: public/css/global-branding.css\n";
} else {
    echo "❌ Erro ao criar CSS global\n";
}

// 2. Atualizar layouts principais para incluir o CSS global
$layouts = [
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/dashboard.blade.php',
    'resources/views/layouts/licenciado.blade.php'
];

foreach ($layouts as $layout) {
    $fullPath = __DIR__ . '/' . $layout;
    
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        
        // Verificar se já tem o CSS global
        if (strpos($content, 'global-branding.css') === false) {
            // Adicionar antes do fechamento do </head>
            if (strpos($content, '</head>') !== false) {
                $cssLink = '    <!-- CSS Global de Branding Dinâmico -->' . "\n" .
                          '    <link href="{{ asset(\'css/global-branding.css\') }}" rel="stylesheet">' . "\n" .
                          '</head>';
                
                $content = str_replace('</head>', $cssLink, $content);
                
                if (file_put_contents($fullPath, $content)) {
                    echo "✅ CSS global adicionado em $layout\n";
                } else {
                    echo "❌ Erro ao atualizar $layout\n";
                }
            }
        } else {
            echo "ℹ️  $layout já tem CSS global\n";
        }
    }
}

echo "\n=== SISTEMA GLOBAL CRIADO ===\n";
echo "✅ CSS global de branding criado\n";
echo "✅ Layouts principais atualizados\n";
echo "✅ Todas as cores azuis/indigo serão sobrescritas\n";
echo "✅ Sistema funciona mesmo sem componente x-dynamic-branding\n";

?>
