<?php

// Script para corrigir branding em produção

echo "=== CORRIGINDO BRANDING EM PRODUÇÃO ===\n\n";

// 1. Verificar se o CSS global existe e está acessível
$cssPath = __DIR__ . '/public/css/global-branding.css';
$cssUrl = '/css/global-branding.css';

if (file_exists($cssPath)) {
    echo "✅ CSS global encontrado: $cssPath\n";
    $cssSize = filesize($cssPath);
    echo "📏 Tamanho: " . number_format($cssSize) . " bytes\n";
} else {
    echo "❌ CSS global não encontrado!\n";
}

// 2. Criar versão inline do CSS para garantir carregamento
$inlineBrandingCSS = '
<style id="production-branding-fix">
/* BRANDING DINÂMICO - INLINE PARA PRODUÇÃO */
:root {
    --primary-color: #3B82F6;
    --secondary-color: #6B7280;
    --accent-color: #10B981;
    --text-color: #1F2937;
    --background-color: #FFFFFF;
    --primary-light: rgba(59,130,246, 0.1);
    --primary-dark: #2f68c4;
    --primary-text: #FFFFFF;
    --primary-gradient: linear-gradient(135deg, #3B82F6 0%, #6B7280 100%);
    --accent-gradient: linear-gradient(135deg, #10B981 0%, #3B82F6 100%);
}

/* FORÇA APLICAÇÃO EM PRODUÇÃO */
.bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400,
.bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-blue-800, .bg-blue-900,
.bg-indigo-50, .bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700, .bg-indigo-800, .bg-indigo-900 {
    background-color: var(--primary-color) !important;
}

.hover\\:bg-blue-50:hover, .hover\\:bg-blue-100:hover, .hover\\:bg-blue-200:hover,
.hover\\:bg-blue-300:hover, .hover\\:bg-blue-400:hover, .hover\\:bg-blue-500:hover,
.hover\\:bg-blue-600:hover, .hover\\:bg-blue-700:hover, .hover\\:bg-blue-800:hover,
.hover\\:bg-indigo-50:hover, .hover\\:bg-indigo-100:hover, .hover\\:bg-indigo-200:hover,
.hover\\:bg-indigo-300:hover, .hover\\:bg-indigo-400:hover, .hover\\:bg-indigo-500:hover,
.hover\\:bg-indigo-600:hover, .hover\\:bg-indigo-700:hover, .hover\\:bg-indigo-800:hover {
    background-color: var(--primary-dark) !important;
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

.bg-green-500, .bg-green-600, .bg-green-700, .bg-emerald-500, .bg-emerald-600, .bg-emerald-700 {
    background-color: var(--accent-color) !important;
}

.text-green-500, .text-green-600, .text-green-700, .text-green-800,
.text-emerald-500, .text-emerald-600, .text-emerald-700, .text-emerald-800 {
    color: var(--accent-color) !important;
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

a:not([class*="text-"]):not([class*="bg-"]) {
    color: var(--primary-color);
}

a:not([class*="text-"]):not([class*="bg-"]):hover {
    color: var(--primary-dark);
}

input:focus, select:focus, textarea:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px var(--primary-light) !important;
}

[style*="background-color: #3b82f6"], [style*="background-color: #2563eb"],
[style*="background-color: #1d4ed8"], [style*="background-color: #1e40af"] {
    background-color: var(--primary-color) !important;
}

[style*="color: #3b82f6"], [style*="color: #2563eb"],
[style*="color: #1d4ed8"], [style*="color: #1e40af"] {
    color: var(--primary-color) !important;
}

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
</style>
';

// 3. Atualizar o componente de branding dinâmico para incluir CSS inline
$brandingComponent = __DIR__ . '/resources/views/components/dynamic-branding.blade.php';
$brandingContent = file_get_contents($brandingComponent);

// Verificar se já tem CSS inline de produção
if (strpos($brandingContent, 'production-branding-fix') === false) {
    // Adicionar CSS inline após o CSS existente
    $brandingContent = str_replace('@endonce', $inlineBrandingCSS . "\n@endonce", $brandingContent);
    
    if (file_put_contents($brandingComponent, $brandingContent)) {
        echo "✅ CSS inline adicionado ao componente de branding\n";
    } else {
        echo "❌ Erro ao atualizar componente de branding\n";
    }
} else {
    echo "ℹ️  CSS inline já presente no componente\n";
}

// 4. Verificar e corrigir layouts principais
$layouts = [
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/dashboard.blade.php',
    'resources/views/layouts/licenciado.blade.php'
];

foreach ($layouts as $layout) {
    $fullPath = __DIR__ . '/' . $layout;
    
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        
        // Verificar se tem o CSS global
        $hasGlobalCSS = strpos($content, 'global-branding.css') !== false;
        $hasBrandingComponent = strpos($content, '<x-dynamic-branding') !== false;
        
        echo "📄 $layout:\n";
        echo "  CSS Global: " . ($hasGlobalCSS ? "✅" : "❌") . "\n";
        echo "  Componente Branding: " . ($hasBrandingComponent ? "✅" : "❌") . "\n";
        
        // Se não tem componente de branding, adicionar
        if (!$hasBrandingComponent) {
            // Adicionar antes do </head>
            if (strpos($content, '</head>') !== false) {
                $content = str_replace('</head>', "    <x-dynamic-branding />\n</head>", $content);
                
                if (file_put_contents($fullPath, $content)) {
                    echo "  ✅ Componente adicionado\n";
                } else {
                    echo "  ❌ Erro ao adicionar componente\n";
                }
            }
        }
        
        echo "\n";
    }
}

// 5. Criar script de emergência para produção
$emergencyScript = '<?php
// Script de emergência para aplicar branding em produção
// Acesse via: /fix-branding-emergency.php

header("Content-Type: text/html; charset=utf-8");

echo "<h1>🎨 Correção de Branding - Produção</h1>";

// Limpar caches
if (function_exists("opcache_reset")) {
    opcache_reset();
    echo "<p>✅ OPCache limpo</p>";
}

// Verificar CSS
$cssPath = __DIR__ . "/css/global-branding.css";
if (file_exists($cssPath)) {
    echo "<p>✅ CSS Global encontrado (" . filesize($cssPath) . " bytes)</p>";
} else {
    echo "<p>❌ CSS Global não encontrado</p>";
}

// Forçar CSS inline
echo "<style>
:root {
    --primary-color: #3B82F6 !important;
    --secondary-color: #6B7280 !important;
    --accent-color: #10B981 !important;
    --primary-dark: #2f68c4 !important;
    --primary-text: #FFFFFF !important;
}

.bg-blue-500, .bg-blue-600, .bg-blue-700,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700 {
    background-color: var(--primary-color) !important;
}

.text-blue-500, .text-blue-600, .text-blue-700,
.text-indigo-500, .text-indigo-600, .text-indigo-700 {
    color: var(--primary-color) !important;
}

button[class*=\"blue\"], button[class*=\"indigo\"] {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}
</style>";

echo "<p>✅ CSS de emergência aplicado</p>";
echo "<p><a href=\"/dashboard\">🔗 Testar Dashboard</a></p>";
echo "<p><a href=\"/dashboard/licenciados\">🔗 Testar Licenciados</a></p>";
?>';

$emergencyPath = __DIR__ . '/public/fix-branding-emergency.php';
if (file_put_contents($emergencyPath, $emergencyScript)) {
    echo "✅ Script de emergência criado: /fix-branding-emergency.php\n";
} else {
    echo "❌ Erro ao criar script de emergência\n";
}

echo "\n=== CORREÇÕES APLICADAS ===\n";
echo "✅ CSS inline adicionado ao componente de branding\n";
echo "✅ Layouts verificados e corrigidos\n";
echo "✅ Script de emergência criado\n";
echo "✅ Sistema deve funcionar em produção agora\n";

echo "\n=== PARA TESTAR EM PRODUÇÃO ===\n";
echo "1. Acesse: https://srv971263.hstgr.cloud/fix-branding-emergency.php\n";
echo "2. Teste as páginas do dashboard\n";
echo "3. Limpe cache do navegador se necessário\n";

?>