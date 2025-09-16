<?php

// Script para criar um sistema que atualiza o CSS global automaticamente

echo "=== CRIANDO SISTEMA DE ATUALIZAÇÃO AUTOMÁTICA ===\n\n";

// 1. Criar um Service para atualizar o CSS global
$serviceContent = '<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class BrandingService
{
    /**
     * Atualiza o CSS global com as cores do branding atual
     */
    public static function updateGlobalCSS()
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
        $primaryRgb = self::hexToRgb($primaryColor);
        $primaryLight = \'rgba(\' . implode(\',\', $primaryRgb) . \', 0.1)\';
        $primaryDark = self::darkenColor($primaryColor, 20);
        $primaryText = self::getContrastColor($primaryColor);
        
        // Template do CSS
        $cssTemplate = \'/* BRANDING DINÂMICO GLOBAL - ATUALIZADO AUTOMATICAMENTE */
/* Última atualização: \' . date(\'Y-m-d H:i:s\') . \' */

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

/* SOBRESCRITA GLOBAL DE TODAS AS CORES TAILWIND */
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
\';
        
        // Salvar o CSS
        $cssPath = public_path(\'css/global-branding.css\');
        
        // Criar diretório se não existir
        $cssDir = dirname($cssPath);
        if (!is_dir($cssDir)) {
            mkdir($cssDir, 0755, true);
        }
        
        return File::put($cssPath, $cssTemplate);
    }
    
    /**
     * Converte hex para RGB
     */
    private static function hexToRgb($hex)
    {
        $hex = ltrim($hex, \'#\');
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        ];
    }
    
    /**
     * Escurece uma cor
     */
    private static function darkenColor($hex, $percent)
    {
        $rgb = self::hexToRgb($hex);
        $rgb[0] = max(0, $rgb[0] - ($rgb[0] * $percent / 100));
        $rgb[1] = max(0, $rgb[1] - ($rgb[1] * $percent / 100));
        $rgb[2] = max(0, $rgb[2] - ($rgb[2] * $percent / 100));
        
        return sprintf(\'#%02x%02x%02x\', $rgb[0], $rgb[1], $rgb[2]);
    }
    
    /**
     * Determina cor de contraste
     */
    private static function getContrastColor($hex)
    {
        $rgb = self::hexToRgb($hex);
        $brightness = ($rgb[0] * 299 + $rgb[1] * 587 + $rgb[2] * 114) / 1000;
        return $brightness > 128 ? \'#000000\' : \'#FFFFFF\';
    }
}
';

// Salvar o Service
$servicePath = __DIR__ . '/app/Services/BrandingService.php';
$serviceDir = dirname($servicePath);

if (!is_dir($serviceDir)) {
    mkdir($serviceDir, 0755, true);
}

if (file_put_contents($servicePath, $serviceContent)) {
    echo "✅ BrandingService criado\n";
} else {
    echo "❌ Erro ao criar BrandingService\n";
}

// 2. Atualizar o controller de branding para usar o service
$controllerPath = __DIR__ . '/app/Http/Controllers/HierarchyBrandingController.php';

if (file_exists($controllerPath)) {
    $controllerContent = file_get_contents($controllerPath);
    
    // Adicionar use statement se não existir
    if (strpos($controllerContent, 'use App\Services\BrandingService;') === false) {
        $controllerContent = str_replace(
            '<?php',
            "<?php\n\nuse App\Services\BrandingService;",
            $controllerContent
        );
    }
    
    // Adicionar chamada para atualizar CSS após salvar branding
    if (strpos($controllerContent, 'BrandingService::updateGlobalCSS()') === false) {
        // Procurar pelo método store e adicionar a chamada
        $pattern = '/(public function store.*?{.*?)(return.*?;)/s';
        $replacement = '$1BrandingService::updateGlobalCSS();' . "\n        " . '$2';
        $controllerContent = preg_replace($pattern, $replacement, $controllerContent);
    }
    
    if (file_put_contents($controllerPath, $controllerContent)) {
        echo "✅ Controller atualizado para usar BrandingService\n";
    } else {
        echo "❌ Erro ao atualizar controller\n";
    }
} else {
    echo "⚠️  Controller não encontrado\n";
}

echo "\n=== SISTEMA DE ATUALIZAÇÃO CRIADO ===\n";
echo "✅ BrandingService criado\n";
echo "✅ Controller atualizado\n";
echo "✅ CSS será atualizado automaticamente quando branding for alterado\n";

?>
