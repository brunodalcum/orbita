<?php

// Script para criar um sistema que atualiza o CSS dinamicamente

echo "=== CRIANDO SISTEMA DE CSS DINÂMICO ===\n\n";

// 1. Criar um endpoint para servir CSS dinâmico
$dynamicCSSController = '<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class DynamicCSSController extends Controller
{
    /**
     * Serve CSS dinâmico baseado no branding do usuário
     */
    public function serveBrandingCSS(Request $request)
    {
        $user = Auth::user();
        $branding = $user ? $user->getBrandingWithInheritance() : [];
        
        // Cores padrão se não houver branding personalizado
        $primaryColor = $branding[\'primary_color\'] ?? \'#3B82F6\';
        $secondaryColor = $branding[\'secondary_color\'] ?? \'#6B7280\';
        $accentColor = $branding[\'accent_color\'] ?? \'#10B981\';
        $textColor = $branding[\'text_color\'] ?? \'#1F2937\';
        $backgroundColor = $branding[\'background_color\'] ?? \'#FFFFFF\';
        
        // Calcular cores derivadas
        $primaryRgb = $this->hexToRgb($primaryColor);
        $primaryLight = \'rgba(\' . implode(\',\', $primaryRgb) . \', 0.1)\';
        $primaryDark = $this->darkenColor($primaryColor, 20);
        $primaryText = $this->getContrastColor($primaryColor);
        
        $css = \'/* BRANDING DINÂMICO - GERADO EM TEMPO REAL */
/* Usuário: \' . ($user ? $user->email : \'Anônimo\') . \' */
/* Gerado em: \' . date(\'Y-m-d H:i:s\') . \' */

:root {
    --primary-color: \' . $primaryColor . \';
    --secondary-color: \' . $secondaryColor . \';
    --accent-color: \' . $accentColor . \';
    --text-color: \' . $textColor . \';
    --background-color: \' . $backgroundColor . \';
    --primary-light: \' . $primaryLight . \';
    --primary-dark: \' . $primaryDark . \';
    --primary-text: \' . $primaryText . \';
    --primary-gradient: linear-gradient(135deg, \' . $primaryColor . \' 0%, \' . $secondaryColor . \' 100%);
    --accent-gradient: linear-gradient(135deg, \' . $accentColor . \' 0%, \' . $primaryColor . \' 100%);
}

/* SOBRESCRITA TOTAL DE CORES TAILWIND */
.bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400,
.bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-blue-800, .bg-blue-900,
.bg-indigo-50, .bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700, .bg-indigo-800, .bg-indigo-900 {
    background-color: var(--primary-color) !important;
}

.hover\\\\:bg-blue-50:hover, .hover\\\\:bg-blue-100:hover, .hover\\\\:bg-blue-200:hover,
.hover\\\\:bg-blue-300:hover, .hover\\\\:bg-blue-400:hover, .hover\\\\:bg-blue-500:hover,
.hover\\\\:bg-blue-600:hover, .hover\\\\:bg-blue-700:hover, .hover\\\\:bg-blue-800:hover,
.hover\\\\:bg-indigo-50:hover, .hover\\\\:bg-indigo-100:hover, .hover\\\\:bg-indigo-200:hover,
.hover\\\\:bg-indigo-300:hover, .hover\\\\:bg-indigo-400:hover, .hover\\\\:bg-indigo-500:hover,
.hover\\\\:bg-indigo-600:hover, .hover\\\\:bg-indigo-700:hover, .hover\\\\:bg-indigo-800:hover {
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

button[class*="blue"], .btn[class*="blue"], button[class*="indigo"], .btn[class*="indigo"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
    transition: all 0.3s ease;
}

button[class*="blue"]:hover, .btn[class*="blue"]:hover, button[class*="indigo"]:hover, .btn[class*="indigo"]:hover {
    background-color: var(--primary-dark) !important;
    transform: translateY(-1px);
}

a:not([class*="text-"]):not([class*="bg-"]) {
    color: var(--primary-color);
    transition: color 0.3s ease;
}

a:not([class*="text-"]):not([class*="bg-"]):hover {
    color: var(--primary-dark);
}

input:focus, select:focus, textarea:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px var(--primary-light) !important;
    outline: none;
}

.animate-spin[class*="border-blue"], .animate-spin[class*="border-indigo"] {
    border-color: var(--primary-color) transparent var(--primary-color) transparent !important;
}

[style*="background-color: #3b82f6"], [style*="background-color: #2563eb"],
[style*="background-color: #1d4ed8"], [style*="background-color: #1e40af"] {
    background-color: var(--primary-color) !important;
}

[style*="color: #3b82f6"], [style*="color: #2563eb"],
[style*="color: #1d4ed8"], [style*="color: #1e40af"] {
    color: var(--primary-color) !important;
}

.dashboard-card, .metric-card, .stat-card {
    border-left: 4px solid var(--primary-color);
}

.sidebar-active, .nav-active {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

::-webkit-scrollbar-thumb {
    background: var(--primary-color);
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
}
\';
        
        return Response::make($css, 200, [
            \'Content-Type\' => \'text/css\',
            \'Cache-Control\' => \'no-cache, no-store, must-revalidate\',
            \'Pragma\' => \'no-cache\',
            \'Expires\' => \'0\'
        ]);
    }
    
    private function hexToRgb($hex)
    {
        $hex = ltrim($hex, \'#\');
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        ];
    }
    
    private function darkenColor($hex, $percent)
    {
        $rgb = $this->hexToRgb($hex);
        $rgb[0] = max(0, $rgb[0] - ($rgb[0] * $percent / 100));
        $rgb[1] = max(0, $rgb[1] - ($rgb[1] * $percent / 100));
        $rgb[2] = max(0, $rgb[2] - ($rgb[2] * $percent / 100));
        
        return sprintf(\'#%02x%02x%02x\', $rgb[0], $rgb[1], $rgb[2]);
    }
    
    private function getContrastColor($hex)
    {
        $rgb = $this->hexToRgb($hex);
        $brightness = ($rgb[0] * 299 + $rgb[1] * 587 + $rgb[2] * 114) / 1000;
        return $brightness > 128 ? \'#000000\' : \'#FFFFFF\';
    }
}
';

// Salvar o controller
$controllerPath = __DIR__ . '/app/Http/Controllers/DynamicCSSController.php';
$controllerDir = dirname($controllerPath);

if (!is_dir($controllerDir)) {
    mkdir($controllerDir, 0755, true);
}

if (file_put_contents($controllerPath, $dynamicCSSController)) {
    echo "✅ DynamicCSSController criado\n";
} else {
    echo "❌ Erro ao criar DynamicCSSController\n";
}

// 2. Adicionar rota para o CSS dinâmico
$routeAddition = "
// Rota para CSS dinâmico de branding
Route::get('/css/dynamic-branding.css', [App\Http\Controllers\DynamicCSSController::class, 'serveBrandingCSS'])
    ->name('dynamic.branding.css')
    ->middleware(['web']);
";

$routesPath = __DIR__ . '/routes/web.php';
$routesContent = file_get_contents($routesPath);

if (strpos($routesContent, 'dynamic-branding.css') === false) {
    // Adicionar no final do arquivo, antes do fechamento
    $routesContent = rtrim($routesContent) . "\n" . $routeAddition;
    
    if (file_put_contents($routesPath, $routesContent)) {
        echo "✅ Rota para CSS dinâmico adicionada\n";
    } else {
        echo "❌ Erro ao adicionar rota\n";
    }
} else {
    echo "ℹ️  Rota para CSS dinâmico já existe\n";
}

// 3. Atualizar layouts para usar CSS dinâmico
$layouts = [
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/dashboard.blade.php',
    'resources/views/layouts/licenciado.blade.php'
];

foreach ($layouts as $layout) {
    $fullPath = __DIR__ . '/' . $layout;
    
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        
        // Adicionar CSS dinâmico se não existe
        if (strpos($content, 'dynamic-branding.css') === false) {
            $dynamicCSSLink = '    <!-- CSS Dinâmico de Branding -->' . "\n" .
                             '    <link href="{{ route(\'dynamic.branding.css\') }}" rel="stylesheet">' . "\n";
            
            if (strpos($content, '</head>') !== false) {
                $content = str_replace('</head>', $dynamicCSSLink . '</head>', $content);
                
                if (file_put_contents($fullPath, $content)) {
                    echo "✅ CSS dinâmico adicionado em $layout\n";
                } else {
                    echo "❌ Erro ao atualizar $layout\n";
                }
            }
        } else {
            echo "ℹ️  $layout já tem CSS dinâmico\n";
        }
    }
}

echo "\n=== SISTEMA DE CSS DINÂMICO CRIADO ===\n";
echo "✅ Controller para servir CSS dinâmico criado\n";
echo "✅ Rota /css/dynamic-branding.css adicionada\n";
echo "✅ Layouts atualizados para usar CSS dinâmico\n";
echo "✅ CSS será gerado em tempo real baseado no usuário logado\n";
echo "✅ Cores serão sempre consistentes em todas as páginas\n";

?>
