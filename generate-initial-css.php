<?php

// Script para gerar o CSS inicial

require_once __DIR__ . '/vendor/autoload.php';

// Simular ambiente Laravel básico
$app = new Illuminate\Foundation\Application(__DIR__);

// Configurar básico
$app->singleton('config', function() {
    return new Illuminate\Config\Repository([
        'app' => ['key' => 'base64:' . base64_encode('test-key-32-characters-long!!')],
    ]);
});

echo "=== GERANDO CSS INICIAL ===\n\n";

// Cores padrão do sistema
$primaryColor = '#3B82F6';
$secondaryColor = '#6B7280';
$accentColor = '#10B981';
$textColor = '#1F2937';
$backgroundColor = '#FFFFFF';

// Função para converter hex para RGB
function hexToRgb($hex) {
    $hex = ltrim($hex, '#');
    return [
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2))
    ];
}

// Função para escurecer cor
function darkenColor($hex, $percent) {
    $rgb = hexToRgb($hex);
    $rgb[0] = max(0, $rgb[0] - ($rgb[0] * $percent / 100));
    $rgb[1] = max(0, $rgb[1] - ($rgb[1] * $percent / 100));
    $rgb[2] = max(0, $rgb[2] - ($rgb[2] * $percent / 100));
    
    return sprintf('#%02x%02x%02x', $rgb[0], $rgb[1], $rgb[2]);
}

// Função para determinar cor de contraste
function getContrastColor($hex) {
    $rgb = hexToRgb($hex);
    $brightness = ($rgb[0] * 299 + $rgb[1] * 587 + $rgb[2] * 114) / 1000;
    return $brightness > 128 ? '#000000' : '#FFFFFF';
}

// Calcular cores derivadas
$primaryRgb = hexToRgb($primaryColor);
$primaryLight = 'rgba(' . implode(',', $primaryRgb) . ', 0.1)';
$primaryDark = darkenColor($primaryColor, 20);
$primaryText = getContrastColor($primaryColor);

// Template do CSS
$cssTemplate = '/* BRANDING DINÂMICO GLOBAL - GERADO AUTOMATICAMENTE */
/* Última atualização: ' . date('Y-m-d H:i:s') . ' */

:root {
    --primary-color: ' . $primaryColor . ';
    --secondary-color: ' . $secondaryColor . ';
    --accent-color: ' . $accentColor . ';
    --text-color: ' . $textColor . ';
    --background-color: ' . $backgroundColor . ';
    --primary-light: ' . $primaryLight . ';
    --primary-dark: ' . $primaryDark . ';
    --primary-text: ' . $primaryText . ';
    --primary-gradient: linear-gradient(135deg, ' . $primaryColor . ' 0%, ' . $secondaryColor . ' 100%);
    --accent-gradient: linear-gradient(135deg, ' . $accentColor . ' 0%, ' . $primaryColor . ' 100%);
}

/* SOBRESCRITA GLOBAL DE TODAS AS CORES TAILWIND */
/* Força aplicação da paleta personalizada em TODAS as páginas */

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
button[class*="blue"], .btn[class*="blue"], 
button[class*="indigo"], .btn[class*="indigo"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
    transition: all 0.3s ease;
}

button[class*="blue"]:hover, .btn[class*="blue"]:hover,
button[class*="indigo"]:hover, .btn[class*="indigo"]:hover {
    background-color: var(--primary-dark) !important;
    transform: translateY(-1px);
}

/* Links */
a:not([class*="text-"]):not([class*="bg-"]) {
    color: var(--primary-color);
    text-decoration: none;
    transition: color 0.3s ease;
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

.sidebar-active, .nav-active {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

.modal-header, .dialog-header {
    background: var(--primary-gradient);
    color: var(--primary-text);
}

.table-primary th {
    background-color: var(--primary-color);
    color: var(--primary-text);
}

.badge-primary, .tag-primary {
    background-color: var(--primary-light);
    color: var(--primary-color);
}

.progress-bar, .progress-fill {
    background-color: var(--primary-color);
}

/* Scrollbars personalizadas */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: var(--primary-color);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-dark);
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
';

// Salvar o CSS
$cssPath = __DIR__ . '/public/css/global-branding.css';

if (file_put_contents($cssPath, $cssTemplate)) {
    echo "✅ CSS inicial gerado com sucesso!\n";
    echo "📄 Arquivo: public/css/global-branding.css\n";
    echo "🎨 Cores aplicadas:\n";
    echo "   • Primária: $primaryColor\n";
    echo "   • Secundária: $secondaryColor\n";
    echo "   • Accent: $accentColor\n";
    echo "   • Primária Escura: $primaryDark\n";
    echo "   • Texto Primário: $primaryText\n";
} else {
    echo "❌ Erro ao gerar CSS inicial\n";
}

echo "\n=== CSS INICIAL GERADO ===\n";
echo "✅ Todas as cores azuis/indigo serão sobrescritas\n";
echo "✅ Sistema funcionará em todas as páginas\n";
echo "✅ Cores serão atualizadas automaticamente quando branding for alterado\n";

?>
