<?php

// Script para restaurar sistema simples e funcional

echo "=== RESTAURANDO SISTEMA SIMPLES DE BRANDING ===\n\n";

// 1. Remover todos os arquivos CSS problemáticos
echo "🧹 LIMPANDO ARQUIVOS PROBLEMÁTICOS...\n";
$filesToRemove = [
    'public/css/unified-branding.css',
    'public/css/global-branding.css',
    'public/css/force-buttons.css',
    'public/css/sidebar-fix.css',
    'public/css/comprehensive-branding.css',
    'public/css/specific-elements-fix.css',
    'public/css/ultra-aggressive-branding.css'
];

$removedCount = 0;
foreach ($filesToRemove as $file) {
    $fullPath = __DIR__ . '/' . $file;
    if (file_exists($fullPath)) {
        if (unlink($fullPath)) {
            echo "🗑️  Removido: " . basename($file) . "\n";
            $removedCount++;
        }
    }
}
echo "✅ $removedCount arquivos problemáticos removidos\n";

// 2. Criar CSS simples e funcional
echo "\n🎨 CRIANDO CSS SIMPLES E FUNCIONAL...\n";
$simpleCSS = '/* SISTEMA SIMPLES DE BRANDING - FUNCIONAL */
/* Criado em: ' . date('Y-m-d H:i:s') . ' */

/* Variáveis CSS básicas */
:root {
    --primary-color: #3B82F6;
    --secondary-color: #6B7280;
    --accent-color: #10B981;
    --text-color: #1F2937;
    --background-color: #FFFFFF;
    --primary-light: rgba(59, 130, 246, 0.1);
    --primary-dark: #2563EB;
    --primary-text: #FFFFFF;
}

/* SIDEBAR - Sempre com fundo escuro e texto branco */
.sidebar,
.w-64.flex-shrink-0,
[class*="sidebar"] {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%) !important;
}

.sidebar *,
.w-64.flex-shrink-0 *,
[class*="sidebar"] * {
    color: white !important;
}

.sidebar a,
.w-64.flex-shrink-0 a,
[class*="sidebar"] a {
    color: white !important;
    text-decoration: none !important;
}

.sidebar a:hover,
.w-64.flex-shrink-0 a:hover,
[class*="sidebar"] a:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
}

/* BOTÕES - Usar cor primária */
button:not(.sidebar button):not(.w-64 button),
.btn:not(.sidebar .btn):not(.w-64 .btn),
input[type="submit"]:not(.sidebar input):not(.w-64 input),
input[type="button"]:not(.sidebar input):not(.w-64 input) {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

button:hover:not(.sidebar button):not(.w-64 button),
.btn:hover:not(.sidebar .btn):not(.w-64 .btn),
input[type="submit"]:hover:not(.sidebar input):not(.w-64 input),
input[type="button"]:hover:not(.sidebar input):not(.w-64 input) {
    background-color: var(--primary-dark) !important;
    transform: translateY(-1px);
}

/* LINKS que funcionam como botões */
a.btn:not(.sidebar a):not(.w-64 a),
a[class*="bg-"]:not(.sidebar a):not(.w-64 a) {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
    text-decoration: none !important;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    display: inline-flex;
    align-items: center;
    font-weight: 500;
    transition: all 0.3s ease;
}

a.btn:hover:not(.sidebar a):not(.w-64 a),
a[class*="bg-"]:hover:not(.sidebar a):not(.w-64 a) {
    background-color: var(--primary-dark) !important;
    transform: translateY(-1px);
}

/* INPUTS - Foco com cor primária */
input:focus:not(.sidebar input):not(.w-64 input),
select:focus:not(.sidebar select):not(.w-64 select),
textarea:focus:not(.sidebar textarea):not(.w-64 textarea) {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px var(--primary-light) !important;
    outline: none !important;
}

/* BADGES e TAGS */
.badge:not(.sidebar .badge):not(.w-64 .badge),
.tag:not(.sidebar .tag):not(.w-64 .tag) {
    background-color: var(--primary-light) !important;
    color: var(--primary-color) !important;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

/* CARDS de estatísticas */
.stat-card:not(.sidebar .stat-card):not(.w-64 .stat-card),
.metric-card:not(.sidebar .metric-card):not(.w-64 .metric-card) {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%) !important;
    color: var(--primary-text) !important;
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.stat-card *:not(.sidebar .stat-card *):not(.w-64 .stat-card *),
.metric-card *:not(.sidebar .metric-card *):not(.w-64 .metric-card *) {
    color: var(--primary-text) !important;
}

/* TABELAS - Cabeçalho com cor primária */
table th:not(.sidebar table th):not(.w-64 table th),
.table th:not(.sidebar .table th):not(.w-64 .table th) {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

/* PROGRESS BARS */
.progress-bar:not(.sidebar .progress-bar):not(.w-64 .progress-bar) {
    background-color: var(--primary-color) !important;
}

/* CHECKBOXES e RADIOS */
input[type="checkbox"]:checked:not(.sidebar input):not(.w-64 input),
input[type="radio"]:checked:not(.sidebar input):not(.w-64 input) {
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

/* COMPATIBILIDADE COM TAILWIND */
.bg-blue-500:not(.sidebar .bg-blue-500):not(.w-64 .bg-blue-500),
.bg-blue-600:not(.sidebar .bg-blue-600):not(.w-64 .bg-blue-600),
.bg-blue-700:not(.sidebar .bg-blue-700):not(.w-64 .bg-blue-700),
.bg-indigo-500:not(.sidebar .bg-indigo-500):not(.w-64 .bg-indigo-500),
.bg-indigo-600:not(.sidebar .bg-indigo-600):not(.w-64 .bg-indigo-600),
.bg-indigo-700:not(.sidebar .bg-indigo-700):not(.w-64 .bg-indigo-700) {
    background-color: var(--primary-color) !important;
}

.text-blue-500:not(.sidebar .text-blue-500):not(.w-64 .text-blue-500),
.text-blue-600:not(.sidebar .text-blue-600):not(.w-64 .text-blue-600),
.text-blue-700:not(.sidebar .text-blue-700):not(.w-64 .text-blue-700),
.text-indigo-500:not(.sidebar .text-indigo-500):not(.w-64 .text-indigo-500),
.text-indigo-600:not(.sidebar .text-indigo-600):not(.w-64 .text-indigo-600),
.text-indigo-700:not(.sidebar .text-indigo-700):not(.w-64 .text-indigo-700) {
    color: var(--primary-color) !important;
}

.border-blue-500:not(.sidebar .border-blue-500):not(.w-64 .border-blue-500),
.border-blue-600:not(.sidebar .border-blue-600):not(.w-64 .border-blue-600),
.border-blue-700:not(.sidebar .border-blue-700):not(.w-64 .border-blue-700),
.border-indigo-500:not(.sidebar .border-indigo-500):not(.w-64 .border-indigo-500),
.border-indigo-600:not(.sidebar .border-indigo-600):not(.w-64 .border-indigo-600),
.border-indigo-700:not(.sidebar .border-indigo-700):not(.w-64 .border-indigo-700) {
    border-color: var(--primary-color) !important;
}

.hover\\:bg-blue-600:hover:not(.sidebar .hover\\:bg-blue-600):not(.w-64 .hover\\:bg-blue-600),
.hover\\:bg-blue-700:hover:not(.sidebar .hover\\:bg-blue-700):not(.w-64 .hover\\:bg-blue-700),
.hover\\:bg-indigo-600:hover:not(.sidebar .hover\\:bg-indigo-600):not(.w-64 .hover\\:bg-indigo-600),
.hover\\:bg-indigo-700:hover:not(.sidebar .hover\\:bg-indigo-700):not(.w-64 .hover\\:bg-indigo-700) {
    background-color: var(--primary-dark) !important;
}
';

$simpleCSSPath = __DIR__ . '/public/css/simple-branding.css';
if (file_put_contents($simpleCSSPath, $simpleCSS)) {
    echo "✅ CSS simples criado: public/css/simple-branding.css\n";
    echo "📏 Tamanho: " . number_format(strlen($simpleCSS) / 1024, 2) . " KB\n";
} else {
    echo "❌ Erro ao criar CSS simples\n";
}

// 3. Criar componente simples
echo "\n🔧 CRIANDO COMPONENTE SIMPLES...\n";
$simpleComponent = '@php
    $user = Auth::user();
    $branding = $user ? $user->getBrandingWithInheritance() : [];
    
    $primaryColor = $branding[\'primary_color\'] ?? \'#3B82F6\';
    $secondaryColor = $branding[\'secondary_color\'] ?? \'#6B7280\';
    $accentColor = $branding[\'accent_color\'] ?? \'#10B981\';
    $textColor = $branding[\'text_color\'] ?? \'#1F2937\';
    $backgroundColor = $branding[\'background_color\'] ?? \'#FFFFFF\';
    
    $primaryRgb = sscanf($primaryColor, "#%02x%02x%02x");
    $primaryLight = sprintf("rgba(%d, %d, %d, 0.1)", $primaryRgb[0], $primaryRgb[1], $primaryRgb[2]);
    $primaryDark = sprintf("#%02x%02x%02x", 
        max(0, $primaryRgb[0] - 30),
        max(0, $primaryRgb[1] - 30), 
        max(0, $primaryRgb[2] - 30)
    );
    $primaryBrightness = ($primaryRgb[0] * 299 + $primaryRgb[1] * 587 + $primaryRgb[2] * 114) / 1000;
    $primaryText = $primaryBrightness > 128 ? \'#000000\' : \'#FFFFFF\';
@endphp

<style>
:root {
    --primary-color: {{ $primaryColor }};
    --secondary-color: {{ $secondaryColor }};
    --accent-color: {{ $accentColor }};
    --text-color: {{ $textColor }};
    --background-color: {{ $backgroundColor }};
    --primary-light: {{ $primaryLight }};
    --primary-dark: {{ $primaryDark }};
    --primary-text: {{ $primaryText }};
}
</style>';

$componentPath = __DIR__ . '/resources/views/components/simple-branding.blade.php';
if (file_put_contents($componentPath, $simpleComponent)) {
    echo "✅ Componente simples criado: resources/views/components/simple-branding.blade.php\n";
} else {
    echo "❌ Erro ao criar componente simples\n";
}

// 4. Limpar e atualizar layouts
echo "\n🔧 LIMPANDO E ATUALIZANDO LAYOUTS...\n";
$layouts = [
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/dashboard.blade.php',
    'resources/views/layouts/licenciado.blade.php'
];

foreach ($layouts as $layout) {
    $fullPath = __DIR__ . '/' . $layout;
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        
        // Remover todos os links CSS de branding antigos
        $content = preg_replace('/.*branding.*\.css.*\n/', '', $content);
        $content = preg_replace('/.*unified-branding.*\n/', '', $content);
        $content = preg_replace('/.*<x-unified-branding.*\n/', '', $content);
        $content = preg_replace('/.*<x-dynamic-branding.*\n/', '', $content);
        
        // Remover estilos inline
        $content = preg_replace('/<style>.*?<\/style>/s', '', $content);
        
        // Adicionar sistema simples
        $simpleIncludes = '    <!-- SISTEMA SIMPLES DE BRANDING -->' . "\n" .
                         '    <link href="{{ asset(\'css/simple-branding.css\') }}" rel="stylesheet">' . "\n" .
                         '    <x-simple-branding />' . "\n";
        
        $content = str_replace('</head>', $simpleIncludes . '</head>', $content);
        
        if (file_put_contents($fullPath, $content)) {
            echo "✅ Layout limpo e atualizado: " . basename($layout) . "\n";
        }
    }
}

// 5. Limpar componentes antigos
echo "\n🧹 LIMPANDO COMPONENTES ANTIGOS...\n";
$oldComponents = [
    'resources/views/components/unified-branding.blade.php',
    'resources/views/components/dynamic-branding.blade.php'
];

$cleanedComponents = 0;
foreach ($oldComponents as $component) {
    $fullPath = __DIR__ . '/' . $component;
    if (file_exists($fullPath)) {
        if (unlink($fullPath)) {
            echo "🗑️  Removido: " . basename($component) . "\n";
            $cleanedComponents++;
        }
    }
}

// 6. Atualizar sidebar para usar classes simples
echo "\n🔧 ATUALIZANDO SIDEBAR...\n";
$sidebarPath = __DIR__ . '/resources/views/components/dynamic-sidebar.blade.php';
if (file_exists($sidebarPath)) {
    $content = file_get_contents($sidebarPath);
    
    // Substituir classes complexas por simples
    $content = str_replace('brand-sidebar', 'sidebar', $content);
    $content = str_replace('sidebar-gradient', 'sidebar', $content);
    
    // Remover estilos inline complexos
    $content = preg_replace('/<style>.*?<\/style>/s', '', $content);
    
    if (file_put_contents($sidebarPath, $content)) {
        echo "✅ Sidebar simplificada\n";
    }
}

// 7. Criar script de emergência simples
$emergencyScript = '<?php
// SCRIPT DE EMERGÊNCIA - SISTEMA SIMPLES
// Acesse via: https://srv971263.hstgr.cloud/emergency-fix-branding.php

echo "<h1>🚨 SISTEMA SIMPLES DE BRANDING</h1>";
echo "<p><strong>Versão SIMPLES - Funcional e Direto</strong></p>";

// Limpar caches
if (function_exists("opcache_reset")) {
    opcache_reset();
    echo "<p>✅ OPCache limpo</p>";
}

$viewCachePath = __DIR__ . "/storage/framework/views";
if (is_dir($viewCachePath)) {
    $files = glob($viewCachePath . "/*.php");
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "<p>✅ Cache de views limpo (" . count($files) . " arquivos)</p>";
}

// Verificar sistema simples
$simpleCSS = __DIR__ . "/public/css/simple-branding.css";
if (file_exists($simpleCSS)) {
    $size = number_format(filesize($simpleCSS) / 1024, 2);
    echo "<p>✅ Sistema Simples: simple-branding.css ($size KB)</p>";
} else {
    echo "<p>❌ Sistema simples não encontrado</p>";
}

echo "<h2>🎯 SISTEMA ATUAL</h2>";
echo "<ul>";
echo "<li>✅ <strong>SIMPLES:</strong> Um CSS, uma abordagem</li>";
echo "<li>✅ <strong>FUNCIONAL:</strong> Sidebar branca, conteúdo com branding</li>";
echo "<li>✅ <strong>DIRETO:</strong> Sem complexidade desnecessária</li>";
echo "<li>✅ <strong>COMPATÍVEL:</strong> Funciona com Tailwind</li>";
echo "</ul>";

echo "<h2>🚀 TESTE</h2>";
echo "<ul>";
echo "<li><a href=\"/dashboard\">Dashboard</a></li>";
echo "<li><a href=\"/dashboard/licenciados\">Licenciados</a></li>";
echo "<li><a href=\"/contracts\">Contratos</a></li>";
echo "</ul>";

echo "<p><strong>🎯 Status: SISTEMA SIMPLES ATIVO</strong></p>";
?>';

$emergencyPath = __DIR__ . '/public/emergency-fix-branding.php';
if (file_put_contents($emergencyPath, $emergencyScript)) {
    echo "✅ Script de emergência simples criado\n";
}

echo "\n=== RESTAURAÇÃO SIMPLES CONCLUÍDA ===\n";
echo "✅ Arquivos problemáticos removidos\n";
echo "✅ CSS simples e funcional criado\n";
echo "✅ Componente simples criado\n";
echo "✅ Layouts limpos e atualizados\n";
echo "✅ Sidebar simplificada\n";
echo "✅ Script de emergência atualizado\n";

echo "\n🎯 SISTEMA AGORA É:\n";
echo "• SIMPLES (1 CSS, 1 componente)\n";
echo "• FUNCIONAL (sidebar branca, conteúdo com branding)\n";
echo "• DIRETO (sem complexidade)\n";
echo "• COMPATÍVEL (funciona com Tailwind)\n";

echo "\n✅ VOLTA AO BÁSICO CONCLUÍDA!\n";

?>
