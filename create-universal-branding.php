<?php

// Script para criar sistema universal de branding

echo "=== CRIANDO SISTEMA UNIVERSAL DE BRANDING ===\n\n";

// 1. Criar middleware para injetar CSS de branding em todas as respostas
$middlewareContent = '<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InjectBrandingMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Só aplicar em respostas HTML
        if ($response->headers->get("Content-Type") && 
            strpos($response->headers->get("Content-Type"), "text/html") !== false) {
            
            $content = $response->getContent();
            
            // Verificar se já tem CSS de branding
            if (strpos($content, "branding-injected") === false) {
                
                // Buscar cores do branding
                try {
                    $branding = DB::table("node_branding")
                        ->where("node_type", "super_admin")
                        ->first();
                    
                    $primaryColor = $branding->primary_color ?? "#3B82F6";
                    $secondaryColor = $branding->secondary_color ?? "#6B7280";
                    $accentColor = $branding->accent_color ?? "#10B981";
                    $textColor = $branding->text_color ?? "#1F2937";
                    $backgroundColor = $branding->background_color ?? "#FFFFFF";
                    
                } catch (Exception $e) {
                    // Fallback para cores padrão
                    $primaryColor = "#3B82F6";
                    $secondaryColor = "#6B7280";
                    $accentColor = "#10B981";
                    $textColor = "#1F2937";
                    $backgroundColor = "#FFFFFF";
                }
                
                // Calcular cores derivadas
                $primaryRgb = $this->hexToRgb($primaryColor);
                $primaryLight = "rgba(" . implode(",", $primaryRgb) . ", 0.1)";
                $primaryDark = $this->darkenColor($primaryColor, 20);
                $primaryText = $this->getContrastColor($primaryColor);
                
                // CSS de branding universal
                $brandingCSS = "
<style id=\"branding-injected\">
/* BRANDING UNIVERSAL - INJETADO VIA MIDDLEWARE */
:root {
    --primary-color: $primaryColor !important;
    --secondary-color: $secondaryColor !important;
    --accent-color: $accentColor !important;
    --text-color: $textColor !important;
    --background-color: $backgroundColor !important;
    --primary-light: $primaryLight !important;
    --primary-dark: $primaryDark !important;
    --primary-text: $primaryText !important;
}

/* FORÇA APLICAÇÃO UNIVERSAL */
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

button[class*=\"blue\"], .btn[class*=\"blue\"], 
button[class*=\"indigo\"], .btn[class*=\"indigo\"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

button[class*=\"blue\"]:hover, .btn[class*=\"blue\"]:hover,
button[class*=\"indigo\"]:hover, .btn[class*=\"indigo\"]:hover {
    background-color: var(--primary-dark) !important;
}

a:not([class*=\"text-\"]):not([class*=\"bg-\"]) {
    color: var(--primary-color);
}

input:focus, select:focus, textarea:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px var(--primary-light) !important;
}

[style*=\"background-color: #3b82f6\"], [style*=\"background-color: #2563eb\"],
[style*=\"background-color: #1d4ed8\"], [style*=\"background-color: #1e40af\"] {
    background-color: var(--primary-color) !important;
}

[style*=\"color: #3b82f6\"], [style*=\"color: #2563eb\"],
[style*=\"color: #1d4ed8\"], [style*=\"color: #1e40af\"] {
    color: var(--primary-color) !important;
}

.animate-spin[class*=\"border-blue\"], .animate-spin[class*=\"border-indigo\"] {
    border-color: var(--primary-color) transparent var(--primary-color) transparent !important;
}
</style>
";
                
                // Injetar CSS antes do </head>
                if (strpos($content, "</head>") !== false) {
                    $content = str_replace("</head>", $brandingCSS . "</head>", $content);
                    $response->setContent($content);
                }
            }
        }
        
        return $response;
    }
    
    private function hexToRgb($hex)
    {
        $hex = ltrim($hex, "#");
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
        
        return sprintf("#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2]);
    }
    
    private function getContrastColor($hex)
    {
        $rgb = $this->hexToRgb($hex);
        $brightness = ($rgb[0] * 299 + $rgb[1] * 587 + $rgb[2] * 114) / 1000;
        return $brightness > 128 ? "#000000" : "#FFFFFF";
    }
}
';

// Salvar o middleware
$middlewarePath = __DIR__ . '/app/Http/Middleware/InjectBrandingMiddleware.php';
$middlewareDir = dirname($middlewarePath);

if (!is_dir($middlewareDir)) {
    mkdir($middlewareDir, 0755, true);
}

if (file_put_contents($middlewarePath, $middlewareContent)) {
    echo "✅ Middleware de injeção de branding criado\n";
} else {
    echo "❌ Erro ao criar middleware\n";
}

// 2. Registrar middleware no Kernel
$kernelPath = __DIR__ . '/app/Http/Kernel.php';

if (file_exists($kernelPath)) {
    $kernelContent = file_get_contents($kernelPath);
    
    // Adicionar middleware ao grupo web se não existir
    if (strpos($kernelContent, 'InjectBrandingMiddleware') === false) {
        // Procurar pelo grupo web
        $pattern = '/(\$middlewareGroups\s*=\s*\[\s*\'web\'\s*=>\s*\[.*?)(\'throttle:web\',)/s';
        $replacement = '$1$2' . "\n            \\App\\Http\\Middleware\\InjectBrandingMiddleware::class,";
        
        $kernelContent = preg_replace($pattern, $replacement, $kernelContent);
        
        if (file_put_contents($kernelPath, $kernelContent)) {
            echo "✅ Middleware registrado no Kernel\n";
        } else {
            echo "❌ Erro ao registrar middleware no Kernel\n";
        }
    } else {
        echo "ℹ️  Middleware já registrado no Kernel\n";
    }
} else {
    echo "⚠️  Kernel não encontrado\n";
}

// 3. Criar script de teste para verificar funcionamento
$testScript = '<?php
// Script de teste para verificar branding universal
// Acesse via: /test-branding.php

header("Content-Type: text/html; charset=utf-8");

echo "<!DOCTYPE html>
<html>
<head>
    <title>Teste de Branding Universal</title>
    <script src=\"https://cdn.tailwindcss.com\"></script>
</head>
<body class=\"p-8\">
    <h1 class=\"text-3xl font-bold mb-6\">🎨 Teste de Branding Universal</h1>
    
    <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6\">
        
        <!-- Botões Azuis -->
        <div class=\"bg-white p-6 rounded-lg shadow\">
            <h2 class=\"text-lg font-semibold mb-4\">Botões Azuis</h2>
            <button class=\"bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mb-2 block w-full\">
                bg-blue-500
            </button>
            <button class=\"bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-2 block w-full\">
                bg-blue-600
            </button>
            <button class=\"bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded block w-full\">
                bg-indigo-500
            </button>
        </div>
        
        <!-- Textos Azuis -->
        <div class=\"bg-white p-6 rounded-lg shadow\">
            <h2 class=\"text-lg font-semibold mb-4\">Textos Azuis</h2>
            <p class=\"text-blue-500 mb-2\">text-blue-500</p>
            <p class=\"text-blue-600 mb-2\">text-blue-600</p>
            <p class=\"text-indigo-500 mb-2\">text-indigo-500</p>
            <p class=\"text-indigo-600\">text-indigo-600</p>
        </div>
        
        <!-- Bordas Azuis -->
        <div class=\"bg-white p-6 rounded-lg shadow\">
            <h2 class=\"text-lg font-semibold mb-4\">Bordas Azuis</h2>
            <div class=\"border-2 border-blue-500 p-3 mb-2 rounded\">border-blue-500</div>
            <div class=\"border-2 border-blue-600 p-3 mb-2 rounded\">border-blue-600</div>
            <div class=\"border-2 border-indigo-500 p-3 rounded\">border-indigo-500</div>
        </div>
        
        <!-- Botões Verdes -->
        <div class=\"bg-white p-6 rounded-lg shadow\">
            <h2 class=\"text-lg font-semibold mb-4\">Botões Verdes (Accent)</h2>
            <button class=\"bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mb-2 block w-full\">
                bg-green-500
            </button>
            <button class=\"bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded mb-2 block w-full\">
                bg-green-600
            </button>
            <button class=\"bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded block w-full\">
                bg-emerald-500
            </button>
        </div>
        
        <!-- Formulários -->
        <div class=\"bg-white p-6 rounded-lg shadow\">
            <h2 class=\"text-lg font-semibold mb-4\">Formulários</h2>
            <input type=\"text\" placeholder=\"Input com foco\" class=\"w-full p-2 border rounded mb-2\">
            <select class=\"w-full p-2 border rounded mb-2\">
                <option>Select com foco</option>
            </select>
            <textarea placeholder=\"Textarea com foco\" class=\"w-full p-2 border rounded\" rows=\"3\"></textarea>
        </div>
        
        <!-- Loading -->
        <div class=\"bg-white p-6 rounded-lg shadow\">
            <h2 class=\"text-lg font-semibold mb-4\">Loading</h2>
            <div class=\"animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mb-4\"></div>
            <div class=\"animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500\"></div>
        </div>
        
    </div>
    
    <div class=\"mt-8 bg-gray-100 p-6 rounded-lg\">
        <h2 class=\"text-lg font-semibold mb-4\">🔍 Status do Branding</h2>
        <p><strong>CSS Injetado:</strong> <span id=\"css-status\">Verificando...</span></p>
        <p><strong>Cor Primária:</strong> <span id=\"primary-color\">Verificando...</span></p>
        <p><strong>Cor Accent:</strong> <span id=\"accent-color\">Verificando...</span></p>
    </div>
    
    <script>
        // Verificar se CSS foi injetado
        const brandingStyle = document.getElementById(\"branding-injected\");
        document.getElementById(\"css-status\").textContent = brandingStyle ? \"✅ Ativo\" : \"❌ Não encontrado\";
        
        // Mostrar cores atuais
        const rootStyles = getComputedStyle(document.documentElement);
        const primaryColor = rootStyles.getPropertyValue(\"--primary-color\").trim();
        const accentColor = rootStyles.getPropertyValue(\"--accent-color\").trim();
        
        document.getElementById(\"primary-color\").textContent = primaryColor || \"Não definida\";
        document.getElementById(\"accent-color\").textContent = accentColor || \"Não definida\";
        
        if (primaryColor) {
            document.getElementById(\"primary-color\").style.color = primaryColor;
        }
        if (accentColor) {
            document.getElementById(\"accent-color\").style.color = accentColor;
        }
    </script>
</body>
</html>";
?>';

$testPath = __DIR__ . '/public/test-branding.php';
if (file_put_contents($testPath, $testScript)) {
    echo "✅ Script de teste criado: /test-branding.php\n";
} else {
    echo "❌ Erro ao criar script de teste\n";
}

echo "\n=== SISTEMA UNIVERSAL CRIADO ===\n";
echo "✅ Middleware que injeta CSS em todas as respostas HTML\n";
echo "✅ Funciona independente de layouts ou componentes\n";
echo "✅ Busca cores diretamente do banco de dados\n";
echo "✅ Aplicado automaticamente em todas as páginas\n";

echo "\n=== PARA TESTAR ===\n";
echo "1. Acesse: /test-branding.php\n";
echo "2. Verifique se as cores estão sendo aplicadas\n";
echo "3. Teste páginas do dashboard\n";
echo "4. Altere branding e veja mudanças instantâneas\n";

?>
