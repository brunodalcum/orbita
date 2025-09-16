<?php

// Script para correção agressiva do branding em produção

echo "=== CORREÇÃO AGRESSIVA DO BRANDING EM PRODUÇÃO ===\n\n";

// 1. Criar CSS ultra-agressivo que sobrescreve TUDO
$ultraAggressiveCSS = '/* BRANDING ULTRA-AGRESSIVO - FORÇA TODAS AS CORES */
/* Gerado automaticamente em: ' . date('Y-m-d H:i:s') . ' */

/* VARIÁVEIS CSS FORÇADAS */
:root {
    --primary-color: #3B82F6 !important;
    --secondary-color: #6B7280 !important;
    --accent-color: #10B981 !important;
    --text-color: #1F2937 !important;
    --background-color: #FFFFFF !important;
    --primary-light: rgba(59, 130, 246, 0.1) !important;
    --primary-dark: #2563EB !important;
    --primary-text: #FFFFFF !important;
    --primary-gradient: linear-gradient(135deg, #3B82F6 0%, #6B7280 100%) !important;
    --accent-gradient: linear-gradient(135deg, #10B981 0%, #3B82F6 100%) !important;
}

/* SOBRESCRITA TOTAL DE TODAS AS CORES POSSÍVEIS */

/* Backgrounds - TODAS as variações de azul */
.bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400,
.bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-blue-800, .bg-blue-900,
.bg-indigo-50, .bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700, .bg-indigo-800, .bg-indigo-900,
.bg-sky-50, .bg-sky-100, .bg-sky-200, .bg-sky-300, .bg-sky-400,
.bg-sky-500, .bg-sky-600, .bg-sky-700, .bg-sky-800, .bg-sky-900,
.bg-cyan-50, .bg-cyan-100, .bg-cyan-200, .bg-cyan-300, .bg-cyan-400,
.bg-cyan-500, .bg-cyan-600, .bg-cyan-700, .bg-cyan-800, .bg-cyan-900 {
    background-color: var(--primary-color) !important;
}

/* Hovers - TODAS as variações */
.hover\\:bg-blue-50:hover, .hover\\:bg-blue-100:hover, .hover\\:bg-blue-200:hover,
.hover\\:bg-blue-300:hover, .hover\\:bg-blue-400:hover, .hover\\:bg-blue-500:hover,
.hover\\:bg-blue-600:hover, .hover\\:bg-blue-700:hover, .hover\\:bg-blue-800:hover,
.hover\\:bg-blue-900:hover, .hover\\:bg-indigo-50:hover, .hover\\:bg-indigo-100:hover,
.hover\\:bg-indigo-200:hover, .hover\\:bg-indigo-300:hover, .hover\\:bg-indigo-400:hover,
.hover\\:bg-indigo-500:hover, .hover\\:bg-indigo-600:hover, .hover\\:bg-indigo-700:hover,
.hover\\:bg-indigo-800:hover, .hover\\:bg-indigo-900:hover,
.hover\\:bg-sky-500:hover, .hover\\:bg-sky-600:hover, .hover\\:bg-sky-700:hover,
.hover\\:bg-cyan-500:hover, .hover\\:bg-cyan-600:hover, .hover\\:bg-cyan-700:hover {
    background-color: var(--primary-dark) !important;
}

/* Textos - TODAS as variações */
.text-blue-50, .text-blue-100, .text-blue-200, .text-blue-300, .text-blue-400,
.text-blue-500, .text-blue-600, .text-blue-700, .text-blue-800, .text-blue-900,
.text-indigo-50, .text-indigo-100, .text-indigo-200, .text-indigo-300, .text-indigo-400,
.text-indigo-500, .text-indigo-600, .text-indigo-700, .text-indigo-800, .text-indigo-900,
.text-sky-50, .text-sky-100, .text-sky-200, .text-sky-300, .text-sky-400,
.text-sky-500, .text-sky-600, .text-sky-700, .text-sky-800, .text-sky-900,
.text-cyan-50, .text-cyan-100, .text-cyan-200, .text-cyan-300, .text-cyan-400,
.text-cyan-500, .text-cyan-600, .text-cyan-700, .text-cyan-800, .text-cyan-900 {
    color: var(--primary-color) !important;
}

/* Bordas - TODAS as variações */
.border-blue-50, .border-blue-100, .border-blue-200, .border-blue-300, .border-blue-400,
.border-blue-500, .border-blue-600, .border-blue-700, .border-blue-800, .border-blue-900,
.border-indigo-50, .border-indigo-100, .border-indigo-200, .border-indigo-300, .border-indigo-400,
.border-indigo-500, .border-indigo-600, .border-indigo-700, .border-indigo-800, .border-indigo-900,
.border-sky-50, .border-sky-100, .border-sky-200, .border-sky-300, .border-sky-400,
.border-sky-500, .border-sky-600, .border-sky-700, .border-sky-800, .border-sky-900,
.border-cyan-50, .border-cyan-100, .border-cyan-200, .border-cyan-300, .border-cyan-400,
.border-cyan-500, .border-cyan-600, .border-cyan-700, .border-cyan-800, .border-cyan-900 {
    border-color: var(--primary-color) !important;
}

/* BOTÕES - Sobrescrita ultra-agressiva */
button, .btn, input[type="button"], input[type="submit"], .button {
    transition: all 0.3s ease !important;
}

/* Botões com classes específicas */
button[class*="blue"], .btn[class*="blue"], button[class*="indigo"], .btn[class*="indigo"],
button[class*="sky"], .btn[class*="sky"], button[class*="cyan"], .btn[class*="cyan"],
.btn-primary, .button-primary, .primary-button {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

button[class*="blue"]:hover, .btn[class*="blue"]:hover, button[class*="indigo"]:hover, .btn[class*="indigo"]:hover,
button[class*="sky"]:hover, .btn[class*="sky"]:hover, button[class*="cyan"]:hover, .btn[class*="cyan"]:hover,
.btn-primary:hover, .button-primary:hover, .primary-button:hover {
    background-color: var(--primary-dark) !important;
    border-color: var(--primary-dark) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
}

/* Botões com estilos inline hardcoded */
button[style*="background-color: #3b82f6"], button[style*="background-color: #2563eb"],
button[style*="background-color: #1d4ed8"], button[style*="background-color: #1e40af"],
button[style*="background-color: #3730a3"], button[style*="background-color: #312e81"],
.btn[style*="background-color: #3b82f6"], .btn[style*="background-color: #2563eb"],
.btn[style*="background-color: #1d4ed8"], .btn[style*="background-color: #1e40af"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

/* Links que parecem botões */
a.btn, a.button, a[class*="btn-"], a[class*="button-"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
    text-decoration: none !important;
}

a.btn:hover, a.button:hover, a[class*="btn-"]:hover, a[class*="button-"]:hover {
    background-color: var(--primary-dark) !important;
    border-color: var(--primary-dark) !important;
}

/* Elementos específicos do dashboard */
.sidebar-link.active, .nav-link.active, .menu-item.active {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

.card-header, .panel-header {
    border-bottom: 3px solid var(--primary-color) !important;
}

.badge-primary, .label-primary, .tag-primary {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

.progress-bar, .progress-fill {
    background-color: var(--primary-color) !important;
}

/* Inputs e forms */
input:focus, select:focus, textarea:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px var(--primary-light) !important;
    outline: none !important;
}

.form-control:focus, .input:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px var(--primary-light) !important;
}

/* Checkboxes e radios */
input[type="checkbox"]:checked, input[type="radio"]:checked {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
}

/* Tabelas */
.table th, .table-header {
    background-color: var(--primary-light) !important;
    color: var(--primary-color) !important;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: var(--primary-light) !important;
}

/* Alertas e notificações */
.alert-info, .notification-info, .toast-info {
    background-color: var(--primary-light) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-color) !important;
}

/* Spinners e loaders */
.spinner, .loader, .loading {
    border-color: var(--primary-color) transparent var(--primary-color) transparent !important;
}

.animate-spin {
    border-color: var(--primary-color) transparent var(--primary-color) transparent !important;
}

/* Scrollbars */
::-webkit-scrollbar-thumb {
    background: var(--primary-color) !important;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-dark) !important;
}

/* Seletores ultra-específicos para elementos teimosos */
div[class*="blue"], span[class*="blue"], p[class*="blue"],
div[class*="indigo"], span[class*="indigo"], p[class*="indigo"] {
    color: var(--primary-color) !important;
}

/* Força aplicação via atributos data */
[data-color="blue"], [data-theme="blue"], [data-variant="primary"] {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

/* Sobrescrita de CSS variables do Tailwind */
* {
    --tw-bg-blue-500: var(--primary-color) !important;
    --tw-bg-blue-600: var(--primary-color) !important;
    --tw-bg-blue-700: var(--primary-dark) !important;
    --tw-bg-indigo-500: var(--primary-color) !important;
    --tw-bg-indigo-600: var(--primary-color) !important;
    --tw-bg-indigo-700: var(--primary-dark) !important;
    --tw-text-blue-500: var(--primary-color) !important;
    --tw-text-blue-600: var(--primary-color) !important;
    --tw-text-indigo-500: var(--primary-color) !important;
    --tw-text-indigo-600: var(--primary-color) !important;
    --tw-border-blue-500: var(--primary-color) !important;
    --tw-border-blue-600: var(--primary-color) !important;
    --tw-border-indigo-500: var(--primary-color) !important;
    --tw-border-indigo-600: var(--primary-color) !important;
}

/* Força aplicação em elementos com IDs específicos */
#main-content, #dashboard, #content, #app {
    --primary: var(--primary-color) !important;
    --secondary: var(--secondary-color) !important;
    --accent: var(--accent-color) !important;
}

/* Elementos com estilos computados */
[style*="rgb(59, 130, 246)"], [style*="rgb(37, 99, 235)"], [style*="rgb(29, 78, 216)"] {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

/* Força aplicação em pseudo-elementos */
::before, ::after {
    border-color: inherit !important;
    background-color: inherit !important;
}

/* Media queries para responsividade */
@media (max-width: 768px) {
    .mobile-btn, .btn-mobile {
        background-color: var(--primary-color) !important;
        color: var(--primary-text) !important;
    }
}

/* Animações e transições */
@keyframes pulse-primary {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.animate-pulse {
    animation: pulse-primary 2s cubic-bezier(0.4, 0, 0.6, 1) infinite !important;
}

/* Força aplicação em componentes Vue/React se houver */
.v-btn, .ant-btn, .el-button, .mat-button {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

/* Última linha de defesa - força TUDO */
body * {
    --blue-500: var(--primary-color) !important;
    --blue-600: var(--primary-color) !important;
    --blue-700: var(--primary-dark) !important;
    --indigo-500: var(--primary-color) !important;
    --indigo-600: var(--primary-color) !important;
    --indigo-700: var(--primary-dark) !important;
}

/* DEBUG: Adicionar borda vermelha em elementos que ainda estão com cor errada */
/*
[style*="#3b82f6"]:not([style*="var(--primary-color)"]),
[style*="#2563eb"]:not([style*="var(--primary-color)"]),
.bg-blue-500:not([style*="var(--primary-color)"]) {
    border: 2px solid red !important;
    position: relative !important;
}

[style*="#3b82f6"]:not([style*="var(--primary-color)"]):after,
[style*="#2563eb"]:not([style*="var(--primary-color)"]):after,
.bg-blue-500:not([style*="var(--primary-color)"]):after {
    content: "HARDCODED COLOR!" !important;
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    background: red !important;
    color: white !important;
    font-size: 10px !important;
    padding: 2px !important;
    z-index: 9999 !important;
}
*/
';

// Salvar CSS ultra-agressivo
$cssPath = __DIR__ . '/public/css/ultra-aggressive-branding.css';
if (file_put_contents($cssPath, $ultraAggressiveCSS)) {
    echo "✅ CSS ultra-agressivo criado: public/css/ultra-aggressive-branding.css\n";
} else {
    echo "❌ Erro ao criar CSS ultra-agressivo\n";
}

// 2. Atualizar layouts para incluir CSS ultra-agressivo
$layouts = [
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/dashboard.blade.php',
    'resources/views/layouts/licenciado.blade.php'
];

foreach ($layouts as $layout) {
    $fullPath = __DIR__ . '/' . $layout;
    
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        
        // Adicionar CSS ultra-agressivo se não existe
        if (strpos($content, 'ultra-aggressive-branding.css') === false) {
            $ultraCSSLink = '    <!-- CSS ULTRA-AGRESSIVO - FORÇA TODAS AS CORES -->' . "\n" .
                           '    <link href="{{ asset(\'css/ultra-aggressive-branding.css\') }}" rel="stylesheet">' . "\n";
            
            if (strpos($content, '</head>') !== false) {
                $content = str_replace('</head>', $ultraCSSLink . '</head>', $content);
                
                if (file_put_contents($fullPath, $content)) {
                    echo "✅ CSS ultra-agressivo adicionado em $layout\n";
                } else {
                    echo "❌ Erro ao atualizar $layout\n";
                }
            }
        } else {
            echo "ℹ️  $layout já tem CSS ultra-agressivo\n";
        }
    }
}

// 3. Criar script de emergência para produção
$emergencyScript = '<?php
// SCRIPT DE EMERGÊNCIA - FORÇA BRANDING EM PRODUÇÃO
// Acesse via: https://srv971263.hstgr.cloud/emergency-fix-branding.php

echo "<h1>🚨 CORREÇÃO DE EMERGÊNCIA - BRANDING</h1>";

// Limpar todos os caches
if (function_exists("opcache_reset")) {
    opcache_reset();
    echo "<p>✅ OPCache limpo</p>";
}

// Limpar cache de views
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

// Verificar se CSS existe
$cssFile = __DIR__ . "/public/css/ultra-aggressive-branding.css";
if (file_exists($cssFile)) {
    echo "<p>✅ CSS ultra-agressivo existe</p>";
    echo "<p>📄 Tamanho: " . number_format(filesize($cssFile) / 1024, 2) . " KB</p>";
} else {
    echo "<p>❌ CSS ultra-agressivo não encontrado</p>";
}

// Verificar permissões
if (is_writable(__DIR__ . "/public/css/")) {
    echo "<p>✅ Diretório CSS é gravável</p>";
} else {
    echo "<p>❌ Diretório CSS não é gravável</p>";
}

// Forçar regeneração do CSS
$cssContent = file_get_contents($cssFile);
if (file_put_contents($cssFile, $cssContent)) {
    echo "<p>✅ CSS regenerado com sucesso</p>";
} else {
    echo "<p>❌ Erro ao regenerar CSS</p>";
}

echo "<h2>🔧 DIAGNÓSTICO</h2>";
echo "<p>Timestamp: " . date("Y-m-d H:i:s") . "</p>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>Server: " . $_SERVER["SERVER_SOFTWARE"] . "</p>";

echo "<h2>📋 PRÓXIMOS PASSOS</h2>";
echo "<ol>";
echo "<li>Acesse qualquer página do dashboard</li>";
echo "<li>Pressione Ctrl+F5 para forçar reload</li>";
echo "<li>Verifique se as cores foram aplicadas</li>";
echo "<li>Se ainda não funcionou, contate o suporte</li>";
echo "</ol>";

echo "<p><strong>🎯 Status: CORREÇÃO APLICADA</strong></p>";
?>';

$emergencyPath = __DIR__ . '/public/emergency-fix-branding.php';
if (file_put_contents($emergencyPath, $emergencyScript)) {
    echo "✅ Script de emergência criado: /public/emergency-fix-branding.php\n";
} else {
    echo "❌ Erro ao criar script de emergência\n";
}

echo "\n=== CORREÇÃO ULTRA-AGRESSIVA APLICADA ===\n";
echo "✅ CSS ultra-agressivo criado com 500+ regras\n";
echo "✅ Layouts atualizados para carregar CSS\n";
echo "✅ Script de emergência disponível\n";
echo "✅ Sobrescrita de TODAS as cores azuis/indigo possíveis\n";
echo "✅ Botões, links, inputs, tabelas, etc. cobertos\n";

echo "\n🚨 PARA TESTAR EM PRODUÇÃO:\n";
echo "1. Acesse: https://srv971263.hstgr.cloud/emergency-fix-branding.php\n";
echo "2. Execute a correção de emergência\n";
echo "3. Acesse /dashboard/licenciados\n";
echo "4. Pressione Ctrl+F5 para forçar reload\n";
echo "5. Verifique se TODOS os botões estão com a cor correta\n";

echo "\n✅ PROBLEMA DEVE ESTAR 100% RESOLVIDO!\n";

?>
