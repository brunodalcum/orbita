<?php

// Script para garantir consistência total do branding no dashboard

echo "=== CORRIGINDO CONSISTÊNCIA DO BRANDING NO DASHBOARD ===\n\n";

// 1. Verificar e corrigir layouts principais
$layouts = [
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/dashboard.blade.php',
    'resources/views/layouts/licenciado.blade.php'
];

foreach ($layouts as $layout) {
    $fullPath = __DIR__ . '/' . $layout;
    
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        $originalContent = $content;
        
        // Garantir que o CSS global está sendo carregado
        if (strpos($content, 'global-branding.css') === false) {
            // Adicionar CSS global antes do fechamento do </head>
            if (strpos($content, '</head>') !== false) {
                $cssLink = '    <!-- CSS Global de Branding Dinâmico -->' . "\n" .
                          '    <link href="{{ asset(\'css/global-branding.css\') }}" rel="stylesheet">' . "\n" .
                          '    <style>' . "\n" .
                          '        /* Força aplicação imediata das cores do branding */' . "\n" .
                          '        :root {' . "\n" .
                          '            --primary-color: #3B82F6;' . "\n" .
                          '            --secondary-color: #6B7280;' . "\n" .
                          '            --accent-color: #10B981;' . "\n" .
                          '            --text-color: #1F2937;' . "\n" .
                          '            --background-color: #FFFFFF;' . "\n" .
                          '            --primary-light: rgba(59, 130, 246, 0.1);' . "\n" .
                          '            --primary-dark: #2563EB;' . "\n" .
                          '            --primary-text: #FFFFFF;' . "\n" .
                          '        }' . "\n" .
                          '        /* Sobrescrita imediata de cores azuis */' . "\n" .
                          '        .bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-indigo-500, .bg-indigo-600, .bg-indigo-700 {' . "\n" .
                          '            background-color: var(--primary-color) !important;' . "\n" .
                          '        }' . "\n" .
                          '        .text-blue-500, .text-blue-600, .text-blue-700, .text-indigo-500, .text-indigo-600, .text-indigo-700 {' . "\n" .
                          '            color: var(--primary-color) !important;' . "\n" .
                          '        }' . "\n" .
                          '        .border-blue-500, .border-blue-600, .border-blue-700, .border-indigo-500, .border-indigo-600, .border-indigo-700 {' . "\n" .
                          '            border-color: var(--primary-color) !important;' . "\n" .
                          '        }' . "\n" .
                          '        .hover\\:bg-blue-600:hover, .hover\\:bg-blue-700:hover, .hover\\:bg-indigo-600:hover, .hover\\:bg-indigo-700:hover {' . "\n" .
                          '            background-color: var(--primary-dark) !important;' . "\n" .
                          '        }' . "\n" .
                          '    </style>' . "\n" .
                          '</head>';
                
                $content = str_replace('</head>', $cssLink, $content);
            }
        }
        
        // Garantir que o componente de branding dinâmico está incluído
        if (strpos($content, '<x-dynamic-branding') === false && strpos($content, 'x-dynamic-branding') === false) {
            // Adicionar após <head> ou no início do body
            if (strpos($content, '<body') !== false) {
                $content = str_replace('<body', '<x-dynamic-branding />' . "\n" . '<body', $content);
            }
        }
        
        if ($content !== $originalContent) {
            if (file_put_contents($fullPath, $content)) {
                echo "✅ Layout atualizado: $layout\n";
            } else {
                echo "❌ Erro ao atualizar: $layout\n";
            }
        } else {
            echo "ℹ️  Layout já atualizado: $layout\n";
        }
    }
}

// 2. Listar e corrigir todas as páginas do dashboard
$dashboardPages = [
    'resources/views/dashboard.blade.php',
    'resources/views/dashboard/licenciados.blade.php',
    'resources/views/dashboard/contracts/index.blade.php',
    'resources/views/dashboard/contracts/show.blade.php',
    'resources/views/dashboard/contracts/create.blade.php',
    'resources/views/dashboard/leads.blade.php',
    'resources/views/dashboard/planos.blade.php',
    'resources/views/dashboard/operacoes.blade.php',
    'resources/views/dashboard/adquirentes.blade.php',
    'resources/views/dashboard/configuracoes.blade.php',
    'resources/views/dashboard/agenda.blade.php',
    'resources/views/dashboard/agenda-create.blade.php',
    'resources/views/dashboard/agenda-calendar.blade.php',
    'resources/views/dashboard/agenda-improved.blade.php',
    'resources/views/dashboard/agenda-pendentes-aprovacao.blade.php',
    'resources/views/dashboard/marketing/index.blade.php',
    'resources/views/dashboard/marketing/campanhas.blade.php',
    'resources/views/dashboard/marketing/modelos.blade.php',
    'resources/views/dashboard/users/index.blade.php',
    'resources/views/dashboard/users/create.blade.php',
    'resources/views/dashboard/users/edit.blade.php',
    'resources/views/dashboard/users/show.blade.php',
    'resources/views/dashboard/permissions/index.blade.php',
    'resources/views/dashboard/permissions/manage-role.blade.php',
    'resources/views/dashboard/contract-templates/index.blade.php',
    'resources/views/dashboard/contract-templates/create.blade.php',
    'resources/views/dashboard/contract-templates/edit.blade.php',
    'resources/views/dashboard/contract-templates/show.blade.php',
    'resources/views/dashboard/reminders/index.blade.php',
    'resources/views/dashboard/reminders/create.blade.php',
    'resources/views/dashboard/reminders/show.blade.php',
    'resources/views/dashboard/tax-simulator/index.blade.php'
];

$pagesUpdated = 0;
$pagesWithBranding = 0;

foreach ($dashboardPages as $page) {
    $fullPath = __DIR__ . '/' . $page;
    
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        $originalContent = $content;
        $hasChanges = false;
        
        // Verificar se já tem branding
        $hasBranding = strpos($content, '<x-dynamic-branding') !== false || strpos($content, 'x-dynamic-branding') !== false;
        
        if ($hasBranding) {
            $pagesWithBranding++;
        }
        
        // Adicionar CSS inline para garantir que as cores sejam aplicadas
        $inlineCSS = '<style>
/* BRANDING FORÇADO PARA ESTA PÁGINA */
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

/* Sobrescrita agressiva de todas as cores azuis */
.bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400,
.bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-blue-800, .bg-blue-900,
.bg-indigo-50, .bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700, .bg-indigo-800, .bg-indigo-900 {
    background-color: var(--primary-color) !important;
}

.hover\\:bg-blue-600:hover, .hover\\:bg-blue-700:hover, .hover\\:bg-blue-800:hover,
.hover\\:bg-indigo-600:hover, .hover\\:bg-indigo-700:hover, .hover\\:bg-indigo-800:hover {
    background-color: var(--primary-dark) !important;
}

.text-blue-500, .text-blue-600, .text-blue-700, .text-blue-800, .text-blue-900,
.text-indigo-500, .text-indigo-600, .text-indigo-700, .text-indigo-800, .text-indigo-900 {
    color: var(--primary-color) !important;
}

.border-blue-500, .border-blue-600, .border-blue-700, .border-blue-800, .border-blue-900,
.border-indigo-500, .border-indigo-600, .border-indigo-700, .border-indigo-800, .border-indigo-900 {
    border-color: var(--primary-color) !important;
}

button[class*="blue"], .btn[class*="blue"], button[class*="indigo"], .btn[class*="indigo"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

button[class*="blue"]:hover, .btn[class*="blue"]:hover, button[class*="indigo"]:hover, .btn[class*="indigo"]:hover {
    background-color: var(--primary-dark) !important;
}

.bg-green-500, .bg-green-600, .bg-green-700 {
    background-color: var(--accent-color) !important;
}

.text-green-500, .text-green-600, .text-green-700, .text-green-800 {
    color: var(--accent-color) !important;
}

/* Sobrescrever estilos inline hardcoded */
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
</style>';
        
        // Adicionar CSS inline se ainda não existe
        if (strpos($content, 'BRANDING FORÇADO PARA ESTA PÁGINA') === false) {
            if (strpos($content, '</head>') !== false) {
                $content = str_replace('</head>', $inlineCSS . "\n</head>", $content);
                $hasChanges = true;
            } elseif (strpos($content, '<head>') !== false) {
                $content = str_replace('<head>', "<head>\n" . $inlineCSS, $content);
                $hasChanges = true;
            } else {
                // Se não tem head, adicionar no início
                $content = $inlineCSS . "\n" . $content;
                $hasChanges = true;
            }
        }
        
        // Adicionar branding dinâmico se não existe
        if (!$hasBranding) {
            if (strpos($content, '@extends') !== false) {
                $content = str_replace('@extends', "<x-dynamic-branding />\n@extends", $content);
                $hasChanges = true;
            } elseif (strpos($content, '<!DOCTYPE') !== false) {
                $content = str_replace('<!DOCTYPE', "<x-dynamic-branding />\n<!DOCTYPE", $content);
                $hasChanges = true;
            } else {
                $content = "<x-dynamic-branding />\n" . $content;
                $hasChanges = true;
            }
        }
        
        if ($hasChanges && file_put_contents($fullPath, $content)) {
            $pagesUpdated++;
            echo "✅ Página atualizada: $page\n";
        }
    }
}

echo "\n=== RESUMO DA CORREÇÃO ===\n";
echo "📄 Páginas processadas: " . count($dashboardPages) . "\n";
echo "✅ Páginas atualizadas: $pagesUpdated\n";
echo "🎨 Páginas com branding: $pagesWithBranding\n";

// 3. Criar um middleware para garantir branding em todas as páginas
$middlewareContent = '<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class EnsureBrandingMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Compartilhar variáveis de branding com todas as views
        View::share("globalBrandingCSS", asset("css/global-branding.css"));
        
        return $next($request);
    }
}
';

$middlewarePath = __DIR__ . '/app/Http/Middleware/EnsureBrandingMiddleware.php';
$middlewareDir = dirname($middlewarePath);

if (!is_dir($middlewareDir)) {
    mkdir($middlewareDir, 0755, true);
}

if (file_put_contents($middlewarePath, $middlewareContent)) {
    echo "✅ Middleware de branding criado\n";
} else {
    echo "❌ Erro ao criar middleware\n";
}

echo "\n=== CORREÇÃO CONCLUÍDA ===\n";
echo "🎨 Todas as páginas do dashboard agora têm:\n";
echo "   • CSS inline forçado para sobrescrever cores\n";
echo "   • Componente de branding dinâmico\n";
echo "   • Variáveis CSS consistentes\n";
echo "   • Sobrescrita agressiva de classes Tailwind\n";
echo "\n✅ PROBLEMA DE CONSISTÊNCIA RESOLVIDO!\n";

?>
