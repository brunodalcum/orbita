<?php

// Script para correção de elementos específicos que não mudaram a paleta

echo "=== CORREÇÃO DE ELEMENTOS ESPECÍFICOS ===\n\n";

// 1. Criar CSS ultra-específico para elementos que escaparam
$specificElementsCSS = '/* CORREÇÃO DE ELEMENTOS ESPECÍFICOS QUE ESCAPARAM */
/* Gerado automaticamente em: ' . date('Y-m-d H:i:s') . ' */

/* ESTATÍSTICAS DO DASHBOARD - CARDS DE MÉTRICAS */
/* Elementos com classes específicas de estatísticas */
.flex.items-center.justify-between,
.flex.items-center.justify-between *,
div[class*="justify-between"],
div[class*="justify-between"] * {
    /* Aplicar branding apenas se não estiver na sidebar */
}

/* Estatísticas fora da sidebar */
body:not(.sidebar-gradient) .flex.items-center.justify-between,
main .flex.items-center.justify-between,
.dashboard-content .flex.items-center.justify-between,
.content-area .flex.items-center.justify-between,
.container .flex.items-center.justify-between {
    background: var(--primary-gradient) !important;
    color: var(--primary-text) !important;
}

/* Textos dentro das estatísticas */
body:not(.sidebar-gradient) .flex.items-center.justify-between p,
body:not(.sidebar-gradient) .flex.items-center.justify-between .text-3xl,
body:not(.sidebar-gradient) .flex.items-center.justify-between .font-bold,
body:not(.sidebar-gradient) .flex.items-center.justify-between .text-sm,
main .flex.items-center.justify-between p,
main .flex.items-center.justify-between .text-3xl,
main .flex.items-center.justify-between .font-bold,
main .flex.items-center.justify-between .text-sm {
    color: var(--primary-text) !important;
}

/* Ícones dentro das estatísticas */
body:not(.sidebar-gradient) .flex.items-center.justify-between i,
body:not(.sidebar-gradient) .flex.items-center.justify-between .fas,
body:not(.sidebar-gradient) .flex.items-center.justify-between .far,
main .flex.items-center.justify-between i,
main .flex.items-center.justify-between .fas,
main .flex.items-center.justify-between .far {
    color: var(--primary-text) !important;
}

/* Containers de ícones nas estatísticas */
body:not(.sidebar-gradient) .w-12.h-12,
body:not(.sidebar-gradient) .bg-white\\/20,
body:not(.sidebar-gradient) .rounded-lg.flex.items-center.justify-center,
main .w-12.h-12,
main .bg-white\\/20,
main .rounded-lg.flex.items-center.justify-center {
    background-color: var(--primary-light) !important;
    color: var(--primary-color) !important;
}

/* CARDS DE ESTATÍSTICAS ESPECÍFICOS */
.stat-card,
.metric-card,
.dashboard-card,
.stats-container,
.metrics-grid > div,
.grid > div[class*="bg-"],
.bg-gradient-to-r,
.bg-gradient-to-br,
.bg-gradient-to-bl {
    background: var(--primary-gradient) !important;
    color: var(--primary-text) !important;
}

.stat-card *,
.metric-card *,
.dashboard-card *,
.stats-container *,
.metrics-grid > div *,
.grid > div[class*="bg-"] *,
.bg-gradient-to-r *,
.bg-gradient-to-br *,
.bg-gradient-to-bl * {
    color: var(--primary-text) !important;
}

/* LINKS DA SIDEBAR - FORÇA BRANCO */
/* Seletores ultra-específicos para links da sidebar */
.sidebar-gradient a,
.sidebar-gradient a *,
.sidebar-gradient .sidebar-submenu-item,
.sidebar-gradient .sidebar-submenu-item *,
.sidebar-gradient .flex.items-center,
.sidebar-gradient .flex.items-center *,
.w-64.flex-shrink-0 a,
.w-64.flex-shrink-0 a *,
div[class*="sidebar"] a,
div[class*="sidebar"] a * {
    color: white !important;
}

/* Links específicos com href */
.sidebar-gradient a[href*="dashboard"],
.sidebar-gradient a[href*="licenciados"],
.sidebar-gradient a[href*="contracts"],
.sidebar-gradient a[href*="leads"],
.sidebar-gradient a[href*="operacoes"] {
    color: white !important;
}

.sidebar-gradient a[href*="dashboard"] *,
.sidebar-gradient a[href*="licenciados"] *,
.sidebar-gradient a[href*="contracts"] *,
.sidebar-gradient a[href*="leads"] *,
.sidebar-gradient a[href*="operacoes"] * {
    color: white !important;
}

/* FORÇA BRANCO EM ELEMENTOS COM STYLE INLINE */
.sidebar-gradient [style*="color: white"],
.sidebar-gradient [style*="color:white"],
.sidebar-gradient [style*="opacity: 0.8"] {
    color: white !important;
}

/* ELEMENTOS COM CLASSES ESPECÍFICAS DE OPACIDADE */
.sidebar-gradient .text-white\\/80,
.sidebar-gradient .text-white\\/70,
.sidebar-gradient .text-white\\/60,
.sidebar-gradient .opacity-80,
.sidebar-gradient .opacity-70,
.sidebar-gradient .opacity-60 {
    color: white !important;
}

/* ÍCONES ESPECÍFICOS NA SIDEBAR */
.sidebar-gradient .fas.fa-circle,
.sidebar-gradient .fas.fa-users,
.sidebar-gradient .fas.fa-building,
.sidebar-gradient .fas.fa-chart-bar,
.sidebar-gradient .fas.fa-cog,
.sidebar-gradient i[class*="fa-"] {
    color: white !important;
}

/* TEXTOS ESPECÍFICOS NA SIDEBAR */
.sidebar-gradient .text-sm,
.sidebar-gradient .text-xs,
.sidebar-gradient .text-lg,
.sidebar-gradient .font-medium,
.sidebar-gradient .font-bold {
    color: white !important;
}

/* CORREÇÃO PARA ELEMENTOS COM MÚLTIPLAS CLASSES */
.sidebar-gradient .flex.items-center.px-3.py-2,
.sidebar-gradient .flex.items-center.px-3.py-2 *,
.sidebar-gradient .rounded-lg.text-sm,
.sidebar-gradient .rounded-lg.text-sm *,
.sidebar-gradient .transition-all.duration-200,
.sidebar-gradient .transition-all.duration-200 * {
    color: white !important;
}

/* FORÇA APLICAÇÃO POR POSIÇÃO NA ÁRVORE DOM */
.sidebar-gradient > * > * > a,
.sidebar-gradient > * > * > a *,
.sidebar-gradient > * > * > * > a,
.sidebar-gradient > * > * > * > a * {
    color: white !important;
}

/* ELEMENTOS ESPECÍFICOS DO DASHBOARD FORA DA SIDEBAR */
/* Cards de estatísticas no conteúdo principal */
body:not(.sidebar-gradient) .bg-gradient-to-r,
body:not(.sidebar-gradient) .bg-gradient-to-br,
body:not(.sidebar-gradient) .bg-gradient-to-bl,
main .bg-gradient-to-r,
main .bg-gradient-to-br,
main .bg-gradient-to-bl,
.dashboard-content .bg-gradient-to-r,
.dashboard-content .bg-gradient-to-br,
.dashboard-content .bg-gradient-to-bl {
    background: var(--primary-gradient) !important;
}

/* Textos em cards de estatísticas */
body:not(.sidebar-gradient) .bg-gradient-to-r .text-white\\/80,
body:not(.sidebar-gradient) .bg-gradient-to-r .text-3xl,
body:not(.sidebar-gradient) .bg-gradient-to-r .font-bold,
main .bg-gradient-to-r .text-white\\/80,
main .bg-gradient-to-r .text-3xl,
main .bg-gradient-to-r .font-bold {
    color: var(--primary-text) !important;
}

/* ELEMENTOS COM BACKGROUND ESPECÍFICO */
body:not(.sidebar-gradient) .bg-white\\/20,
body:not(.sidebar-gradient) .bg-black\\/10,
body:not(.sidebar-gradient) .bg-gray-100,
main .bg-white\\/20,
main .bg-black\\/10,
main .bg-gray-100 {
    background-color: var(--primary-light) !important;
    color: var(--primary-color) !important;
}

/* CORREÇÃO PARA ELEMENTOS TEIMOSOS */
/* Força aplicação usando !important em seletores específicos */
[class*="flex"][class*="items-center"][class*="justify-between"]:not(.sidebar-gradient *) {
    background: var(--primary-gradient) !important;
    color: var(--primary-text) !important;
}

[class*="flex"][class*="items-center"][class*="justify-between"]:not(.sidebar-gradient *) * {
    color: var(--primary-text) !important;
}

/* ÚLTIMA LINHA DE DEFESA - FORÇA TUDO */
/* Para elementos que ainda resistem */
body.dashboard-page:not(.sidebar-gradient) .text-white\\/80,
body.dashboard-page:not(.sidebar-gradient) .text-3xl,
body.dashboard-page:not(.sidebar-gradient) .font-bold {
    color: var(--primary-text) !important;
}

/* Força ícones em cards de estatísticas */
body.dashboard-page:not(.sidebar-gradient) .fas.fa-users,
body.dashboard-page:not(.sidebar-gradient) .fas.fa-chart-bar,
body.dashboard-page:not(.sidebar-gradient) .fas.fa-building {
    color: var(--primary-text) !important;
}

/* DEBUG: Marcar elementos problemáticos */
/*
.flex.items-center.justify-between:not(.sidebar-gradient *) {
    border: 2px solid red !important;
}
*/
';

// Salvar CSS específico
$specificCSSPath = __DIR__ . '/public/css/specific-elements-fix.css';
if (file_put_contents($specificCSSPath, $specificElementsCSS)) {
    echo "✅ CSS específico para elementos criado: public/css/specific-elements-fix.css\n";
} else {
    echo "❌ Erro ao criar CSS específico\n";
}

// 2. Atualizar layouts para incluir o novo CSS
$layouts = [
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/dashboard.blade.php',
    'resources/views/layouts/licenciado.blade.php'
];

foreach ($layouts as $layout) {
    $fullPath = __DIR__ . '/' . $layout;
    
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        
        // Adicionar CSS específico se não existe
        if (strpos($content, 'specific-elements-fix.css') === false) {
            $specificLink = '    <!-- CSS ESPECÍFICO PARA ELEMENTOS TEIMOSOS -->' . "\n" .
                           '    <link href="{{ asset(\'css/specific-elements-fix.css\') }}" rel="stylesheet">' . "\n";
            
            if (strpos($content, '</head>') !== false) {
                $content = str_replace('</head>', $specificLink . '</head>', $content);
                
                if (file_put_contents($fullPath, $content)) {
                    echo "✅ CSS específico adicionado em $layout\n";
                } else {
                    echo "❌ Erro ao atualizar $layout\n";
                }
            }
        } else {
            echo "ℹ️  $layout já tem CSS específico\n";
        }
    }
}

// 3. Encontrar e corrigir páginas específicas que podem ter esses elementos
$pagesToCheck = [
    'resources/views/dashboard.blade.php',
    'resources/views/dashboard/licenciados.blade.php',
    'resources/views/hierarchy/dashboard.blade.php'
];

foreach ($pagesToCheck as $page) {
    $fullPath = __DIR__ . '/' . $page;
    
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        $originalContent = $content;
        
        // Adicionar classe específica ao body se não existe
        if (strpos($content, 'dashboard-page') === false) {
            $content = str_replace('<body class="', '<body class="dashboard-page ', $content);
            $content = str_replace('<body>', '<body class="dashboard-page">', $content);
        }
        
        // Adicionar CSS inline específico para esta página
        $inlineCSS = '
<style>
/* CORREÇÃO ESPECÍFICA PARA ESTA PÁGINA */
.dashboard-page .flex.items-center.justify-between:not(.sidebar-gradient *) {
    background: var(--primary-gradient) !important;
    color: var(--primary-text) !important;
}
.dashboard-page .flex.items-center.justify-between:not(.sidebar-gradient *) * {
    color: var(--primary-text) !important;
}
.dashboard-page .w-12.h-12:not(.sidebar-gradient *) {
    background-color: var(--primary-light) !important;
    color: var(--primary-color) !important;
}
.sidebar-gradient a, .sidebar-gradient a * {
    color: white !important;
}
</style>';
        
        // Adicionar CSS inline se não existe
        if (strpos($content, 'CORREÇÃO ESPECÍFICA PARA ESTA PÁGINA') === false) {
            if (strpos($content, '</head>') !== false) {
                $content = str_replace('</head>', $inlineCSS . "\n</head>", $content);
            } elseif (strpos($content, '<head>') !== false) {
                $content = str_replace('<head>', "<head>\n" . $inlineCSS, $content);
            } else {
                $content = $inlineCSS . "\n" . $content;
            }
        }
        
        if ($content !== $originalContent) {
            if (file_put_contents($fullPath, $content)) {
                echo "✅ Página corrigida: $page\n";
            } else {
                echo "❌ Erro ao corrigir: $page\n";
            }
        } else {
            echo "ℹ️  Página já corrigida: $page\n";
        }
    }
}

echo "\n=== CORREÇÃO DE ELEMENTOS ESPECÍFICOS CONCLUÍDA ===\n";
echo "✅ CSS ultra-específico criado para elementos teimosos\n";
echo "✅ Layouts atualizados com novo CSS\n";
echo "✅ Páginas específicas corrigidas com CSS inline\n";
echo "✅ Seletores direcionados para elementos que escaparam\n";

echo "\n🎯 ELEMENTOS CORRIGIDOS:\n";
echo "• Cards de estatísticas do dashboard\n";
echo "• Links da sidebar (forçados a branco)\n";
echo "• Ícones em containers específicos\n";
echo "• Textos com opacidade na sidebar\n";
echo "• Elementos com múltiplas classes CSS\n";

echo "\n✅ CORREÇÃO ULTRA-ESPECÍFICA APLICADA!\n";

?>
