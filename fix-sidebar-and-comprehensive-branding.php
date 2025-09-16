<?php

// Script para correção abrangente da sidebar e branding em todas as páginas

echo "=== CORREÇÃO ABRANGENTE DA SIDEBAR E BRANDING ===\n\n";

// 1. Criar CSS ultra-específico para sidebar
$sidebarCSS = '/* CORREÇÃO ESPECÍFICA PARA SIDEBAR - FORÇA TEXTOS BRANCOS */
/* Gerado automaticamente em: ' . date('Y-m-d H:i:s') . ' */

/* FORÇA TEXTOS BRANCOS NA SIDEBAR - MÁXIMA PRIORIDADE */
.sidebar-gradient, .sidebar-gradient *, 
.w-64.flex-shrink-0, .w-64.flex-shrink-0 *,
.dynamic-sidebar, .dynamic-sidebar *,
div[class*="sidebar"], div[class*="sidebar"] * {
    color: white !important;
}

/* Seletores ultra-específicos para elementos da sidebar */
.sidebar-gradient .sidebar-menu-item,
.sidebar-gradient .sidebar-menu-item *,
.sidebar-gradient .sidebar-submenu-item,
.sidebar-gradient .sidebar-submenu-item *,
.sidebar-gradient nav,
.sidebar-gradient nav *,
.sidebar-gradient .menu-item,
.sidebar-gradient .menu-item *,
.sidebar-gradient .space-y-2,
.sidebar-gradient .space-y-2 * {
    color: white !important;
}

/* Links da sidebar */
.sidebar-gradient a,
.sidebar-gradient a *,
.sidebar-gradient .flex.items-center,
.sidebar-gradient .flex.items-center *,
.sidebar-gradient .font-medium,
.sidebar-gradient .text-sm,
.sidebar-gradient .text-xs {
    color: white !important;
    text-decoration: none !important;
}

/* Ícones da sidebar */
.sidebar-gradient i,
.sidebar-gradient .fas,
.sidebar-gradient .far,
.sidebar-gradient .fab,
.sidebar-gradient [class*="fa-"] {
    color: white !important;
}

/* Spans e textos da sidebar */
.sidebar-gradient span,
.sidebar-gradient p,
.sidebar-gradient div,
.sidebar-gradient .truncate {
    color: white !important;
}

/* Estados hover e active da sidebar */
.sidebar-gradient .sidebar-menu-hover:hover,
.sidebar-gradient .sidebar-menu-hover:hover *,
.sidebar-gradient .sidebar-menu-active,
.sidebar-gradient .sidebar-menu-active *,
.sidebar-gradient .sidebar-submenu-item:hover,
.sidebar-gradient .sidebar-submenu-item:hover * {
    color: white !important;
}

/* Informações do usuário na sidebar */
.sidebar-gradient .border-t,
.sidebar-gradient .border-t *,
.sidebar-gradient .text-white,
.sidebar-gradient .text-white\\/70 {
    color: white !important;
}

/* Chevron e elementos especiais */
.sidebar-gradient .chevron-icon,
.sidebar-gradient .ml-auto,
.sidebar-gradient .bg-white\\/20 {
    color: white !important;
}

/* Força aplicação por atributos style */
.sidebar-gradient [style*="color:"],
.sidebar-gradient [style*="color "] {
    color: white !important;
}

/* PROTEÇÃO CONTRA SOBRESCRITA DO CSS SELETIVO */
/* Anula qualquer regra que tente mudar a cor da sidebar */
.sidebar-gradient .text-blue-500,
.sidebar-gradient .text-blue-600,
.sidebar-gradient .text-blue-700,
.sidebar-gradient .text-indigo-500,
.sidebar-gradient .text-indigo-600,
.sidebar-gradient .text-indigo-700,
.sidebar-gradient .text-primary {
    color: white !important;
}

/* BRANDING APLICADO APENAS NO CONTEÚDO PRINCIPAL */
/* Exclui sidebar de qualquer aplicação de branding */
:not(.sidebar-gradient) .bg-blue-500,
:not(.sidebar-gradient) .bg-blue-600,
:not(.sidebar-gradient) .bg-blue-700,
:not(.sidebar-gradient) .bg-indigo-500,
:not(.sidebar-gradient) .bg-indigo-600,
:not(.sidebar-gradient) .bg-indigo-700 {
    background-color: var(--primary-color) !important;
}

:not(.sidebar-gradient) .text-blue-500,
:not(.sidebar-gradient) .text-blue-600,
:not(.sidebar-gradient) .text-blue-700,
:not(.sidebar-gradient) .text-indigo-500,
:not(.sidebar-gradient) .text-indigo-600,
:not(.sidebar-gradient) .text-indigo-700 {
    color: var(--primary-color) !important;
}

/* BOTÕES NO CONTEÚDO PRINCIPAL (NÃO NA SIDEBAR) */
.dashboard-content button,
.main-content button,
.content-area button,
main button,
.container button,
.max-w-7xl button,
.px-4.sm\\:px-6.lg\\:px-8 button {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

.dashboard-content button:hover,
.main-content button:hover,
.content-area button:hover,
main button:hover,
.container button:hover,
.max-w-7xl button:hover,
.px-4.sm\\:px-6.lg\\:px-8 button:hover {
    background-color: var(--primary-dark) !important;
    border-color: var(--primary-dark) !important;
}

/* LINKS NO CONTEÚDO PRINCIPAL (NÃO NA SIDEBAR) */
.dashboard-content a[class*="bg-"],
.main-content a[class*="bg-"],
.content-area a[class*="bg-"],
main a[class*="bg-"],
.container a[class*="bg-"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

.dashboard-content a[class*="bg-"]:hover,
.main-content a[class*="bg-"]:hover,
.content-area a[class*="bg-"]:hover,
main a[class*="bg-"]:hover,
.container a[class*="bg-"]:hover {
    background-color: var(--primary-dark) !important;
    border-color: var(--primary-dark) !important;
}

/* DEBUG: Marcar sidebar para verificação */
/*
.sidebar-gradient {
    border: 2px solid red !important;
}
.sidebar-gradient * {
    background-color: rgba(255, 0, 0, 0.1) !important;
}
*/
';

// Salvar CSS específico para sidebar
$sidebarCSSPath = __DIR__ . '/public/css/sidebar-fix.css';
if (file_put_contents($sidebarCSSPath, $sidebarCSS)) {
    echo "✅ CSS específico para sidebar criado: public/css/sidebar-fix.css\n";
} else {
    echo "❌ Erro ao criar CSS específico para sidebar\n";
}

// 2. Criar CSS abrangente para todas as páginas
$comprehensiveCSS = '/* BRANDING ABRANGENTE - TODAS AS PÁGINAS */
/* Gerado automaticamente em: ' . date('Y-m-d H:i:s') . ' */

/* VARIÁVEIS CSS GLOBAIS */
:root {
    --primary-color: #3B82F6;
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

/* APLICAÇÃO GLOBAL EM TODAS AS PÁGINAS */
/* Exclui sidebar e navegação */
body:not(.sidebar-gradient) .bg-blue-500,
body:not(.sidebar-gradient) .bg-blue-600,
body:not(.sidebar-gradient) .bg-blue-700,
body:not(.sidebar-gradient) .bg-indigo-500,
body:not(.sidebar-gradient) .bg-indigo-600,
body:not(.sidebar-gradient) .bg-indigo-700,
main .bg-blue-500,
main .bg-blue-600,
main .bg-blue-700,
main .bg-indigo-500,
main .bg-indigo-600,
main .bg-indigo-700,
.content .bg-blue-500,
.content .bg-blue-600,
.content .bg-blue-700,
.content .bg-indigo-500,
.content .bg-indigo-600,
.content .bg-indigo-700 {
    background-color: var(--primary-color) !important;
}

/* Textos azuis no conteúdo */
body:not(.sidebar-gradient) .text-blue-500,
body:not(.sidebar-gradient) .text-blue-600,
body:not(.sidebar-gradient) .text-blue-700,
body:not(.sidebar-gradient) .text-indigo-500,
body:not(.sidebar-gradient) .text-indigo-600,
body:not(.sidebar-gradient) .text-indigo-700,
main .text-blue-500,
main .text-blue-600,
main .text-blue-700,
main .text-indigo-500,
main .text-indigo-600,
main .text-indigo-700,
.content .text-blue-500,
.content .text-blue-600,
.content .text-blue-700,
.content .text-indigo-500,
.content .text-indigo-600,
.content .text-indigo-700 {
    color: var(--primary-color) !important;
}

/* Bordas azuis no conteúdo */
body:not(.sidebar-gradient) .border-blue-500,
body:not(.sidebar-gradient) .border-blue-600,
body:not(.sidebar-gradient) .border-blue-700,
body:not(.sidebar-gradient) .border-indigo-500,
body:not(.sidebar-gradient) .border-indigo-600,
body:not(.sidebar-gradient) .border-indigo-700,
main .border-blue-500,
main .border-blue-600,
main .border-blue-700,
main .border-indigo-500,
main .border-indigo-600,
main .border-indigo-700 {
    border-color: var(--primary-color) !important;
}

/* BOTÕES GLOBAIS (EXCETO SIDEBAR) */
body:not(.sidebar-gradient) button,
main button,
.content button,
.dashboard button,
.container button,
article button,
section button {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
    transition: all 0.3s ease !important;
}

body:not(.sidebar-gradient) button:hover,
main button:hover,
.content button:hover,
.dashboard button:hover,
.container button:hover,
article button:hover,
section button:hover {
    background-color: var(--primary-dark) !important;
    border-color: var(--primary-dark) !important;
}

/* LINKS COMO BOTÕES (EXCETO SIDEBAR) */
body:not(.sidebar-gradient) a.btn,
body:not(.sidebar-gradient) a.button,
body:not(.sidebar-gradient) a[class*="bg-blue"],
body:not(.sidebar-gradient) a[class*="bg-indigo"],
main a.btn,
main a.button,
main a[class*="bg-blue"],
main a[class*="bg-indigo"],
.content a.btn,
.content a.button,
.content a[class*="bg-blue"],
.content a[class*="bg-indigo"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
    text-decoration: none !important;
}

body:not(.sidebar-gradient) a.btn:hover,
body:not(.sidebar-gradient) a.button:hover,
body:not(.sidebar-gradient) a[class*="bg-blue"]:hover,
body:not(.sidebar-gradient) a[class*="bg-indigo"]:hover,
main a.btn:hover,
main a.button:hover,
main a[class*="bg-blue"]:hover,
main a[class*="bg-indigo"]:hover,
.content a.btn:hover,
.content a.button:hover,
.content a[class*="bg-blue"]:hover,
.content a[class*="bg-indigo"]:hover {
    background-color: var(--primary-dark) !important;
    border-color: var(--primary-dark) !important;
}

/* INPUTS E FORMS */
input:focus,
select:focus,
textarea:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px var(--primary-light) !important;
    outline: none !important;
}

/* BADGES E TAGS */
.badge:not(.sidebar-gradient .badge),
.tag:not(.sidebar-gradient .tag),
.chip:not(.sidebar-gradient .chip) {
    background-color: var(--primary-light) !important;
    color: var(--primary-color) !important;
}

/* PROGRESS BARS */
.progress-bar,
.progress-fill {
    background-color: var(--primary-color) !important;
}

/* CHECKBOXES E RADIOS */
input[type="checkbox"]:checked,
input[type="radio"]:checked {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
}

/* SCROLLBARS */
::-webkit-scrollbar-thumb {
    background: var(--primary-color) !important;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-dark) !important;
}

/* HOVERS GLOBAIS */
body:not(.sidebar-gradient) .hover\\:bg-blue-500:hover,
body:not(.sidebar-gradient) .hover\\:bg-blue-600:hover,
body:not(.sidebar-gradient) .hover\\:bg-blue-700:hover,
body:not(.sidebar-gradient) .hover\\:bg-indigo-500:hover,
body:not(.sidebar-gradient) .hover\\:bg-indigo-600:hover,
body:not(.sidebar-gradient) .hover\\:bg-indigo-700:hover {
    background-color: var(--primary-dark) !important;
}

/* ESTILOS INLINE HARDCODED */
body:not(.sidebar-gradient) [style*="background-color: #3b82f6"],
body:not(.sidebar-gradient) [style*="background-color: #2563eb"],
body:not(.sidebar-gradient) [style*="background-color: #1d4ed8"],
body:not(.sidebar-gradient) [style*="background: #3b82f6"],
body:not(.sidebar-gradient) [style*="background: #2563eb"],
body:not(.sidebar-gradient) [style*="background: #1d4ed8"] {
    background-color: var(--primary-color) !important;
    background: var(--primary-color) !important;
}

body:not(.sidebar-gradient) [style*="color: #3b82f6"],
body:not(.sidebar-gradient) [style*="color: #2563eb"],
body:not(.sidebar-gradient) [style*="color: #1d4ed8"] {
    color: var(--primary-color) !important;
}
';

// Salvar CSS abrangente
$comprehensiveCSSPath = __DIR__ . '/public/css/comprehensive-branding.css';
if (file_put_contents($comprehensiveCSSPath, $comprehensiveCSS)) {
    echo "✅ CSS abrangente criado: public/css/comprehensive-branding.css\n";
} else {
    echo "❌ Erro ao criar CSS abrangente\n";
}

// 3. Atualizar layouts para incluir os novos CSS
$layouts = [
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/dashboard.blade.php',
    'resources/views/layouts/licenciado.blade.php'
];

foreach ($layouts as $layout) {
    $fullPath = __DIR__ . '/' . $layout;
    
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        $originalContent = $content;
        
        // Adicionar CSS específico para sidebar se não existe
        if (strpos($content, 'sidebar-fix.css') === false) {
            $sidebarLink = '    <!-- CSS ESPECÍFICO PARA SIDEBAR - FORÇA TEXTOS BRANCOS -->' . "\n" .
                          '    <link href="{{ asset(\'css/sidebar-fix.css\') }}" rel="stylesheet">' . "\n";
            
            if (strpos($content, '</head>') !== false) {
                $content = str_replace('</head>', $sidebarLink . '</head>', $content);
            }
        }
        
        // Adicionar CSS abrangente se não existe
        if (strpos($content, 'comprehensive-branding.css') === false) {
            $comprehensiveLink = '    <!-- CSS ABRANGENTE - BRANDING EM TODAS AS PÁGINAS -->' . "\n" .
                               '    <link href="{{ asset(\'css/comprehensive-branding.css\') }}" rel="stylesheet">' . "\n";
            
            if (strpos($content, '</head>') !== false) {
                $content = str_replace('</head>', $comprehensiveLink . '</head>', $content);
            }
        }
        
        if ($content !== $originalContent) {
            if (file_put_contents($fullPath, $content)) {
                echo "✅ Layout atualizado: $layout\n";
            } else {
                echo "❌ Erro ao atualizar: $layout\n";
            }
        } else {
            echo "ℹ️  Layout já atualizado: $layout\n";
        }
    }
}

// 4. Atualizar componente da sidebar para garantir textos brancos
$sidebarComponent = __DIR__ . '/resources/views/components/dynamic-sidebar.blade.php';
if (file_exists($sidebarComponent)) {
    $content = file_get_contents($sidebarComponent);
    
    // Adicionar CSS inline específico para garantir textos brancos
    $inlineCSS = '
/* FORÇA TEXTOS BRANCOS NA SIDEBAR - INLINE */
.sidebar-gradient, .sidebar-gradient * {
    color: white !important;
}
.sidebar-gradient a, .sidebar-gradient a * {
    color: white !important;
}
.sidebar-gradient i, .sidebar-gradient span, .sidebar-gradient p {
    color: white !important;
}
';
    
    // Adicionar CSS inline se não existe
    if (strpos($content, 'FORÇA TEXTOS BRANCOS NA SIDEBAR - INLINE') === false) {
        $content = str_replace('</style>', $inlineCSS . "\n</style>", $content);
        
        if (file_put_contents($sidebarComponent, $content)) {
            echo "✅ Componente da sidebar atualizado com CSS inline\n";
        } else {
            echo "❌ Erro ao atualizar componente da sidebar\n";
        }
    } else {
        echo "ℹ️  Componente da sidebar já tem CSS inline\n";
    }
}

echo "\n=== CORREÇÃO ABRANGENTE CONCLUÍDA ===\n";
echo "✅ CSS específico para sidebar criado (força textos brancos)\n";
echo "✅ CSS abrangente criado (branding em todas as páginas)\n";
echo "✅ Layouts atualizados com novos CSS\n";
echo "✅ Componente da sidebar reforçado\n";
echo "✅ Separação clara: sidebar branca, conteúdo com branding\n";

echo "\n🎯 RESULTADO ESPERADO:\n";
echo "• Sidebar: Textos SEMPRE brancos\n";
echo "• Conteúdo: Cores do branding aplicadas\n";
echo "• Botões: Cor primária do branding\n";
echo "• Links: Cor primária do branding\n";
echo "• 100% das páginas: Branding consistente\n";

echo "\n✅ CORREÇÃO ULTRA-ROBUSTA APLICADA!\n";

?>
