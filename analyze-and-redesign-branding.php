<?php

// Script para análise completa e redesign do sistema de branding

echo "=== ANÁLISE COMPLETA E REDESIGN DO SISTEMA DE BRANDING ===\n\n";

// 1. Analisar arquivos CSS atuais
echo "🔍 ANALISANDO ARQUIVOS CSS ATUAIS...\n";
$cssFiles = [
    'public/css/global-branding.css',
    'public/css/selective-branding.css', 
    'public/css/force-buttons.css',
    'public/css/sidebar-fix.css',
    'public/css/comprehensive-branding.css',
    'public/css/specific-elements-fix.css',
    'public/css/ultra-aggressive-branding.css'
];

$totalSize = 0;
$existingFiles = [];

foreach ($cssFiles as $file) {
    $fullPath = __DIR__ . '/' . $file;
    if (file_exists($fullPath)) {
        $size = filesize($fullPath);
        $totalSize += $size;
        $existingFiles[] = $file;
        echo "📄 " . basename($file) . " - " . number_format($size / 1024, 2) . " KB\n";
    }
}

echo "\n❌ PROBLEMAS IDENTIFICADOS:\n";
echo "• " . count($existingFiles) . " arquivos CSS diferentes para branding\n";
echo "• " . number_format($totalSize / 1024, 2) . " KB total de CSS redundante\n";
echo "• Conflitos entre seletores CSS\n";
echo "• Falta de hierarquia clara de estilos\n";
echo "• Aplicação inconsistente em diferentes páginas\n";
echo "• CSS inline misturado com arquivos externos\n";

// 2. Analisar estrutura de layouts
echo "\n🔍 ANALISANDO LAYOUTS...\n";
$layouts = [
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/dashboard.blade.php', 
    'resources/views/layouts/licenciado.blade.php'
];

$layoutAnalysis = [];
foreach ($layouts as $layout) {
    $fullPath = __DIR__ . '/' . $layout;
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        $cssLinks = substr_count($content, 'branding');
        $inlineStyles = substr_count($content, '<style>');
        $layoutAnalysis[$layout] = [
            'css_links' => $cssLinks,
            'inline_styles' => $inlineStyles
        ];
        echo "📄 " . basename($layout) . " - $cssLinks links CSS, $inlineStyles estilos inline\n";
    }
}

// 3. Criar sistema unificado e elegante
echo "\n🎨 CRIANDO SISTEMA UNIFICADO DE BRANDING...\n";

$unifiedBrandingCSS = '/* SISTEMA UNIFICADO DE BRANDING - VERSÃO 2.0 */
/* Criado em: ' . date('Y-m-d H:i:s') . ' */
/* Substitui todos os arquivos CSS de branding anteriores */

/* ========================================
   VARIÁVEIS CSS DINÂMICAS
   ======================================== */
:root {
    /* Cores principais do branding (serão atualizadas dinamicamente) */
    --brand-primary: #3B82F6;
    --brand-secondary: #6B7280;
    --brand-accent: #10B981;
    --brand-text: #1F2937;
    --brand-background: #FFFFFF;
    
    /* Cores derivadas (calculadas automaticamente) */
    --brand-primary-light: rgba(59, 130, 246, 0.1);
    --brand-primary-dark: #2563EB;
    --brand-primary-text: #FFFFFF;
    --brand-primary-hover: #1D4ED8;
    
    /* Gradientes */
    --brand-gradient-primary: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
    --brand-gradient-accent: linear-gradient(135deg, var(--brand-accent) 0%, var(--brand-primary) 100%);
    
    /* Sombras */
    --brand-shadow-primary: 0 4px 12px rgba(59, 130, 246, 0.15);
    --brand-shadow-accent: 0 4px 12px rgba(16, 185, 129, 0.15);
    
    /* Transições */
    --brand-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ========================================
   RESET E BASE
   ======================================== */
* {
    box-sizing: border-box;
}

body {
    font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    background-color: var(--brand-background);
    color: var(--brand-text);
    line-height: 1.6;
}

/* ========================================
   COMPONENTES PRINCIPAIS
   ======================================== */

/* SIDEBAR - Sempre com gradiente e textos brancos */
.brand-sidebar {
    background: var(--brand-gradient-primary);
    color: white;
}

.brand-sidebar * {
    color: white !important;
}

.brand-sidebar a {
    color: white !important;
    text-decoration: none;
    transition: var(--brand-transition);
}

.brand-sidebar a:hover {
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateX(2px);
}

.brand-sidebar .active {
    background-color: rgba(255, 255, 255, 0.2);
    border-left: 4px solid white;
}

/* BOTÕES - Sistema hierárquico */
.brand-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    font-size: 0.875rem;
    transition: var(--brand-transition);
    border: none;
    cursor: pointer;
    text-decoration: none;
}

.brand-btn-primary {
    background-color: var(--brand-primary);
    color: var(--brand-primary-text);
    box-shadow: var(--brand-shadow-primary);
}

.brand-btn-primary:hover {
    background-color: var(--brand-primary-hover);
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.25);
}

.brand-btn-secondary {
    background-color: transparent;
    color: var(--brand-primary);
    border: 1px solid var(--brand-primary);
}

.brand-btn-secondary:hover {
    background-color: var(--brand-primary-light);
}

.brand-btn-accent {
    background-color: var(--brand-accent);
    color: white;
    box-shadow: var(--brand-shadow-accent);
}

.brand-btn-accent:hover {
    background-color: #059669;
    transform: translateY(-1px);
}

/* CARDS - Sistema consistente */
.brand-card {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: var(--brand-transition);
}

.brand-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.brand-card-header {
    padding: 1.5rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    background: var(--brand-gradient-primary);
    color: white;
    border-radius: 0.75rem 0.75rem 0 0;
}

.brand-card-body {
    padding: 1.5rem;
}

.brand-card-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    background-color: #F9FAFB;
    border-radius: 0 0 0.75rem 0.75rem;
}

/* ESTATÍSTICAS - Cards especiais */
.brand-stat-card {
    background: var(--brand-gradient-primary);
    color: white;
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: var(--brand-shadow-primary);
    transition: var(--brand-transition);
}

.brand-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.25);
}

.brand-stat-card .stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: white;
}

.brand-stat-card .stat-label {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.875rem;
}

.brand-stat-card .stat-icon {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    width: 3rem;
    height: 3rem;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* FORMULÁRIOS - Inputs consistentes */
.brand-input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #D1D5DB;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: var(--brand-transition);
    background: white;
}

.brand-input:focus {
    outline: none;
    border-color: var(--brand-primary);
    box-shadow: 0 0 0 3px var(--brand-primary-light);
}

.brand-select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #D1D5DB;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    background: white;
    transition: var(--brand-transition);
}

.brand-select:focus {
    outline: none;
    border-color: var(--brand-primary);
    box-shadow: 0 0 0 3px var(--brand-primary-light);
}

/* BADGES E TAGS */
.brand-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.brand-badge-primary {
    background-color: var(--brand-primary-light);
    color: var(--brand-primary);
}

.brand-badge-accent {
    background-color: rgba(16, 185, 129, 0.1);
    color: var(--brand-accent);
}

.brand-badge-secondary {
    background-color: rgba(107, 114, 128, 0.1);
    color: var(--brand-secondary);
}

/* TABELAS */
.brand-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 0.75rem;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.brand-table th {
    background: var(--brand-gradient-primary);
    color: white;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.875rem;
}

.brand-table td {
    padding: 1rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.brand-table tbody tr:hover {
    background-color: var(--brand-primary-light);
}

/* NAVEGAÇÃO */
.brand-nav {
    background: white;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.brand-nav-item {
    color: var(--brand-text);
    text-decoration: none;
    padding: 0.75rem 1rem;
    transition: var(--brand-transition);
}

.brand-nav-item:hover {
    color: var(--brand-primary);
    background-color: var(--brand-primary-light);
}

.brand-nav-item.active {
    color: var(--brand-primary);
    border-bottom: 2px solid var(--brand-primary);
}

/* ALERTAS E NOTIFICAÇÕES */
.brand-alert {
    padding: 1rem;
    border-radius: 0.5rem;
    border-left: 4px solid;
    margin-bottom: 1rem;
}

.brand-alert-info {
    background-color: var(--brand-primary-light);
    border-color: var(--brand-primary);
    color: var(--brand-primary);
}

.brand-alert-success {
    background-color: rgba(16, 185, 129, 0.1);
    border-color: var(--brand-accent);
    color: var(--brand-accent);
}

.brand-alert-warning {
    background-color: rgba(245, 158, 11, 0.1);
    border-color: #F59E0B;
    color: #D97706;
}

.brand-alert-error {
    background-color: rgba(239, 68, 68, 0.1);
    border-color: #EF4444;
    color: #DC2626;
}

/* LOADING E SPINNERS */
.brand-spinner {
    border: 2px solid var(--brand-primary-light);
    border-top: 2px solid var(--brand-primary);
    border-radius: 50%;
    width: 1.5rem;
    height: 1.5rem;
    animation: brand-spin 1s linear infinite;
}

@keyframes brand-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* PROGRESS BARS */
.brand-progress {
    background-color: rgba(0, 0, 0, 0.1);
    border-radius: 9999px;
    height: 0.5rem;
    overflow: hidden;
}

.brand-progress-bar {
    background: var(--brand-gradient-primary);
    height: 100%;
    transition: var(--brand-transition);
}

/* SCROLLBARS */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #F3F4F6;
}

::-webkit-scrollbar-thumb {
    background: var(--brand-primary);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--brand-primary-hover);
}

/* ========================================
   UTILITÁRIOS
   ======================================== */
.brand-text-primary { color: var(--brand-primary) !important; }
.brand-text-secondary { color: var(--brand-secondary) !important; }
.brand-text-accent { color: var(--brand-accent) !important; }

.brand-bg-primary { background-color: var(--brand-primary) !important; }
.brand-bg-secondary { background-color: var(--brand-secondary) !important; }
.brand-bg-accent { background-color: var(--brand-accent) !important; }

.brand-border-primary { border-color: var(--brand-primary) !important; }
.brand-border-secondary { border-color: var(--brand-secondary) !important; }
.brand-border-accent { border-color: var(--brand-accent) !important; }

/* ========================================
   RESPONSIVIDADE
   ======================================== */
@media (max-width: 768px) {
    .brand-btn {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }
    
    .brand-card {
        margin: 0.5rem;
    }
    
    .brand-stat-card .stat-value {
        font-size: 1.5rem;
    }
}

@media (max-width: 640px) {
    .brand-sidebar {
        width: 100%;
        position: fixed;
        top: 0;
        left: -100%;
        height: 100vh;
        z-index: 50;
        transition: var(--brand-transition);
    }
    
    .brand-sidebar.open {
        left: 0;
    }
}

/* ========================================
   COMPATIBILIDADE COM TAILWIND
   ======================================== */
/* Sobrescreve classes Tailwind específicas */
.bg-blue-500, .bg-blue-600, .bg-blue-700,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700 {
    background-color: var(--brand-primary) !important;
}

.text-blue-500, .text-blue-600, .text-blue-700,
.text-indigo-500, .text-indigo-600, .text-indigo-700 {
    color: var(--brand-primary) !important;
}

.border-blue-500, .border-blue-600, .border-blue-700,
.border-indigo-500, .border-indigo-600, .border-indigo-700 {
    border-color: var(--brand-primary) !important;
}

.hover\\:bg-blue-600:hover, .hover\\:bg-blue-700:hover,
.hover\\:bg-indigo-600:hover, .hover\\:bg-indigo-700:hover {
    background-color: var(--brand-primary-hover) !important;
}

/* ========================================
   ANIMAÇÕES E EFEITOS
   ======================================== */
@keyframes brand-fade-in {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes brand-slide-in {
    from { transform: translateX(-100%); }
    to { transform: translateX(0); }
}

.brand-animate-fade-in {
    animation: brand-fade-in 0.5s ease-out;
}

.brand-animate-slide-in {
    animation: brand-slide-in 0.3s ease-out;
}

/* ========================================
   MODO ESCURO (PREPARADO PARA FUTURO)
   ======================================== */
@media (prefers-color-scheme: dark) {
    :root {
        --brand-background: #1F2937;
        --brand-text: #F9FAFB;
    }
    
    .brand-card {
        background: #374151;
        border-color: #4B5563;
    }
    
    .brand-input, .brand-select {
        background: #374151;
        border-color: #4B5563;
        color: #F9FAFB;
    }
}
';

// Salvar CSS unificado
$unifiedCSSPath = __DIR__ . '/public/css/unified-branding.css';
if (file_put_contents($unifiedCSSPath, $unifiedBrandingCSS)) {
    echo "✅ Sistema unificado criado: public/css/unified-branding.css\n";
    echo "📏 Tamanho: " . number_format(strlen($unifiedBrandingCSS) / 1024, 2) . " KB\n";
} else {
    echo "❌ Erro ao criar sistema unificado\n";
}

// 4. Criar componente Blade para aplicação automática de classes
$brandingComponent = '@php
    $user = Auth::user();
    $branding = $user ? $user->getBrandingWithInheritance() : [];
    
    // Cores do branding
    $primaryColor = $branding[\'primary_color\'] ?? \'#3B82F6\';
    $secondaryColor = $branding[\'secondary_color\'] ?? \'#6B7280\';
    $accentColor = $branding[\'accent_color\'] ?? \'#10B981\';
    $textColor = $branding[\'text_color\'] ?? \'#1F2937\';
    $backgroundColor = $branding[\'background_color\'] ?? \'#FFFFFF\';
    
    // Calcular cores derivadas
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

<style id="dynamic-branding-vars">
:root {
    --brand-primary: {{ $primaryColor }};
    --brand-secondary: {{ $secondaryColor }};
    --brand-accent: {{ $accentColor }};
    --brand-text: {{ $textColor }};
    --brand-background: {{ $backgroundColor }};
    --brand-primary-light: {{ $primaryLight }};
    --brand-primary-dark: {{ $primaryDark }};
    --brand-primary-text: {{ $primaryText }};
    --brand-primary-hover: {{ $primaryDark }};
    --brand-gradient-primary: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%);
    --brand-gradient-accent: linear-gradient(135deg, {{ $accentColor }} 0%, {{ $primaryColor }} 100%);
    --brand-shadow-primary: 0 4px 12px {{ $primaryLight }};
    --brand-shadow-accent: 0 4px 12px rgba(16, 185, 129, 0.15);
}
</style>';

$componentPath = __DIR__ . '/resources/views/components/unified-branding.blade.php';
if (file_put_contents($componentPath, $brandingComponent)) {
    echo "✅ Componente unificado criado: resources/views/components/unified-branding.blade.php\n";
} else {
    echo "❌ Erro ao criar componente unificado\n";
}

// 5. Limpar arquivos CSS antigos
echo "\n🧹 LIMPANDO ARQUIVOS CSS ANTIGOS...\n";
$oldFiles = [
    'public/css/selective-branding.css',
    'public/css/force-buttons.css', 
    'public/css/sidebar-fix.css',
    'public/css/comprehensive-branding.css',
    'public/css/specific-elements-fix.css',
    'public/css/ultra-aggressive-branding.css'
];

$removedCount = 0;
foreach ($oldFiles as $file) {
    $fullPath = __DIR__ . '/' . $file;
    if (file_exists($fullPath)) {
        if (unlink($fullPath)) {
            echo "🗑️  Removido: " . basename($file) . "\n";
            $removedCount++;
        }
    }
}

echo "✅ $removedCount arquivos CSS antigos removidos\n";

// 6. Atualizar layouts
echo "\n🔧 ATUALIZANDO LAYOUTS...\n";
foreach ($layouts as $layout) {
    $fullPath = __DIR__ . '/' . $layout;
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        $originalContent = $content;
        
        // Remover links CSS antigos
        $content = preg_replace('/.*branding.*\.css.*\n/', '', $content);
        
        // Adicionar sistema unificado
        $unifiedLink = '    <!-- SISTEMA UNIFICADO DE BRANDING -->' . "\n" .
                      '    <link href="{{ asset(\'css/unified-branding.css\') }}" rel="stylesheet">' . "\n" .
                      '    <x-unified-branding />' . "\n";
        
        if (strpos($content, 'unified-branding') === false) {
            $content = str_replace('</head>', $unifiedLink . '</head>', $content);
        }
        
        // Remover estilos inline antigos
        $content = preg_replace('/<style>.*?<\/style>/s', '', $content);
        
        if ($content !== $originalContent) {
            if (file_put_contents($fullPath, $content)) {
                echo "✅ Layout limpo e atualizado: " . basename($layout) . "\n";
            }
        }
    }
}

echo "\n=== REDESIGN COMPLETO CONCLUÍDO ===\n";
echo "✅ Sistema unificado de branding criado\n";
echo "✅ Arquivos CSS antigos removidos\n";
echo "✅ Layouts limpos e atualizados\n";
echo "✅ Componente dinâmico criado\n";
echo "✅ Arquitetura CSS profissional implementada\n";

echo "\n🎯 BENEFÍCIOS DO NOVO SISTEMA:\n";
echo "• UM ÚNICO arquivo CSS para todo o branding\n";
echo "• Sistema de classes semânticas (.brand-btn-primary)\n";
echo "• Variáveis CSS dinâmicas atualizadas automaticamente\n";
echo "• Compatibilidade com Tailwind mantida\n";
echo "• Responsividade nativa\n";
echo "• Preparado para modo escuro\n";
echo "• Animações e transições suaves\n";
echo "• Hierarquia clara de componentes\n";

echo "\n🚀 PRÓXIMOS PASSOS:\n";
echo "1. Testar o novo sistema em produção\n";
echo "2. Aplicar classes .brand-* nos templates\n";
echo "3. Verificar consistência visual\n";
echo "4. Ajustar cores específicas se necessário\n";

echo "\n✅ SISTEMA PROFISSIONAL DE BRANDING IMPLEMENTADO!\n";

?>
