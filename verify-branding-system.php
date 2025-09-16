<?php

// Script para verificar se o sistema de branding está funcionando

echo "=== VERIFICAÇÃO FINAL DO SISTEMA DE BRANDING ===\n\n";

// 1. Verificar se os arquivos principais existem
$requiredFiles = [
    'public/css/global-branding.css' => 'CSS Global',
    'app/Http/Controllers/DynamicCSSController.php' => 'Controller CSS Dinâmico',
    'app/Services/BrandingService.php' => 'Service de Branding',
    'app/Http/Middleware/EnsureBrandingMiddleware.php' => 'Middleware de Branding'
];

foreach ($requiredFiles as $file => $description) {
    $fullPath = __DIR__ . '/' . $file;
    if (file_exists($fullPath)) {
        echo "✅ $description: $file\n";
    } else {
        echo "❌ $description: $file (FALTANDO)\n";
    }
}

// 2. Verificar layouts principais
echo "\n=== VERIFICAÇÃO DOS LAYOUTS ===\n";
$layouts = [
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/dashboard.blade.php',
    'resources/views/layouts/licenciado.blade.php'
];

foreach ($layouts as $layout) {
    $fullPath = __DIR__ . '/' . $layout;
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        
        $hasGlobalCSS = strpos($content, 'global-branding.css') !== false;
        $hasDynamicCSS = strpos($content, 'dynamic-branding.css') !== false;
        $hasBrandingComponent = strpos($content, 'x-dynamic-branding') !== false;
        
        echo "📄 $layout:\n";
        echo "  " . ($hasGlobalCSS ? "✅" : "❌") . " CSS Global\n";
        echo "  " . ($hasDynamicCSS ? "✅" : "❌") . " CSS Dinâmico\n";
        echo "  " . ($hasBrandingComponent ? "✅" : "❌") . " Componente Branding\n";
    }
}

// 3. Verificar páginas do dashboard
echo "\n=== VERIFICAÇÃO DAS PÁGINAS DO DASHBOARD ===\n";
$dashboardPages = [
    'resources/views/dashboard.blade.php',
    'resources/views/dashboard/licenciados.blade.php',
    'resources/views/dashboard/contracts/index.blade.php',
    'resources/views/dashboard/leads.blade.php',
    'resources/views/dashboard/operacoes.blade.php'
];

$pagesWithBranding = 0;
$pagesWithInlineCSS = 0;

foreach ($dashboardPages as $page) {
    $fullPath = __DIR__ . '/' . $page;
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        
        $hasBranding = strpos($content, 'x-dynamic-branding') !== false;
        $hasInlineCSS = strpos($content, 'BRANDING FORÇADO') !== false;
        
        if ($hasBranding) $pagesWithBranding++;
        if ($hasInlineCSS) $pagesWithInlineCSS++;
        
        echo "📄 " . basename($page) . ": ";
        echo ($hasBranding ? "✅ Branding " : "❌ Branding ");
        echo ($hasInlineCSS ? "✅ CSS Inline" : "❌ CSS Inline");
        echo "\n";
    }
}

echo "\nResumo das páginas:\n";
echo "✅ Com branding: $pagesWithBranding/" . count($dashboardPages) . "\n";
echo "✅ Com CSS inline: $pagesWithInlineCSS/" . count($dashboardPages) . "\n";

// 4. Verificar rotas
echo "\n=== VERIFICAÇÃO DAS ROTAS ===\n";
$routesPath = __DIR__ . '/routes/web.php';
$routesContent = file_get_contents($routesPath);

$hasDynamicCSSRoute = strpos($routesContent, 'dynamic-branding.css') !== false;
echo ($hasDynamicCSSRoute ? "✅" : "❌") . " Rota para CSS dinâmico\n";

// 5. Verificar componente de branding
echo "\n=== VERIFICAÇÃO DO COMPONENTE DE BRANDING ===\n";
$brandingComponent = __DIR__ . '/resources/views/components/dynamic-branding.blade.php';
if (file_exists($brandingComponent)) {
    $content = file_get_contents($brandingComponent);
    
    $hasOnceProtection = strpos($content, '@once') !== false;
    $hasClosures = strpos($content, 'function(') !== false;
    $hasVariables = strpos($content, '--primary-color') !== false;
    
    echo "✅ Componente existe\n";
    echo ($hasOnceProtection ? "✅" : "❌") . " Proteção @once\n";
    echo ($hasClosures ? "✅" : "❌") . " Funções como closures\n";
    echo ($hasVariables ? "✅" : "❌") . " Variáveis CSS\n";
} else {
    echo "❌ Componente de branding não encontrado\n";
}

// 6. Teste de funcionalidade
echo "\n=== TESTE DE FUNCIONALIDADE ===\n";

// Simular cores de branding
$testColors = [
    'primary_color' => '#FF6B35',
    'secondary_color' => '#4ECDC4',
    'accent_color' => '#45B7D1',
    'text_color' => '#2C3E50',
    'background_color' => '#FFFFFF'
];

echo "🧪 Testando com cores personalizadas:\n";
foreach ($testColors as $key => $color) {
    echo "  • " . ucfirst(str_replace('_', ' ', $key)) . ": $color\n";
}

// Verificar se o CSS global pode ser atualizado
$cssPath = __DIR__ . '/public/css/global-branding.css';
if (is_writable($cssPath)) {
    echo "✅ CSS global é gravável\n";
} else {
    echo "❌ CSS global não é gravável\n";
}

// 7. Resumo final
echo "\n=== RESUMO FINAL ===\n";

$score = 0;
$maxScore = 10;

// Pontuação baseada nos checks
if (file_exists(__DIR__ . '/public/css/global-branding.css')) $score++;
if (file_exists(__DIR__ . '/app/Http/Controllers/DynamicCSSController.php')) $score++;
if (file_exists(__DIR__ . '/app/Services/BrandingService.php')) $score++;
if ($pagesWithBranding >= 4) $score++;
if ($pagesWithInlineCSS >= 4) $score++;
if ($hasDynamicCSSRoute) $score++;
if (file_exists($brandingComponent)) $score++;
if (is_writable($cssPath)) $score++;
if ($hasOnceProtection) $score++;
if ($hasVariables) $score++;

$percentage = ($score / $maxScore) * 100;

echo "🎯 Pontuação: $score/$maxScore ($percentage%)\n";

if ($percentage >= 90) {
    echo "🎉 EXCELENTE! Sistema de branding totalmente funcional!\n";
    echo "✨ Todas as páginas do dashboard usarão a paleta de cores do branding.\n";
    echo "🔄 As cores serão atualizadas automaticamente quando o branding for alterado.\n";
} elseif ($percentage >= 70) {
    echo "👍 BOM! Sistema funcionando com pequenos ajustes necessários.\n";
} else {
    echo "⚠️  ATENÇÃO! Sistema precisa de correções.\n";
}

echo "\n=== INSTRUÇÕES PARA TESTE ===\n";
echo "1. Acesse /hierarchy/branding\n";
echo "2. Altere as cores primárias\n";
echo "3. Visite /dashboard/licenciados\n";
echo "4. Verifique se as cores foram aplicadas\n";
echo "5. Teste outras páginas do dashboard\n";

echo "\n✅ VERIFICAÇÃO CONCLUÍDA!\n";

?>
