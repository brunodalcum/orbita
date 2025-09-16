<?php

// Script para corrigir referências ao componente removido

echo "=== CORRIGINDO REFERÊNCIAS DO COMPONENTE REMOVIDO ===\n\n";

// Lista de arquivos que precisam ser corrigidos
$filesToFix = [
    'resources/views/hierarchy/dashboard.blade.php',
    'resources/views/dashboard/contracts/index.blade.php',
    'resources/views/dashboard/licenciados.blade.php',
    'resources/views/dashboard.blade.php',
    'resources/views/dashboard/reminders/show.blade.php',
    'resources/views/dashboard/reminders/create.blade.php',
    'resources/views/dashboard/reminders/index.blade.php',
    'resources/views/dashboard/agenda-calendar.blade.php',
    'resources/views/dashboard/agenda-improved.blade.php',
    'resources/views/dashboard/agenda.blade.php',
    'resources/views/dashboard/planos.blade.php',
    'resources/views/dashboard/marketing/campanhas.blade.php',
    'resources/views/dashboard/marketing/modelos.blade.php',
    'resources/views/dashboard/marketing/campanha-detalhes.blade.php',
    'resources/views/dashboard/marketing/index.blade.php',
    'resources/views/dashboard/operacoes.blade.php',
    'resources/views/dashboard/users/show.blade.php',
    'resources/views/dashboard/users/create.blade.php',
    'resources/views/dashboard/users/edit.blade.php',
    'resources/views/dashboard/users/index.blade.php',
    'resources/views/dashboard/tax-simulator/index.blade.php',
    'resources/views/dashboard/permissions/manage-role.blade.php',
    'resources/views/dashboard/permissions/index.blade.php',
    'resources/views/dashboard/agenda-pendentes-aprovacao.blade.php',
    'resources/views/dashboard/adquirentes.blade.php',
    'resources/views/dashboard/contracts/show.blade.php',
    'resources/views/dashboard/contracts/create.blade.php',
    'resources/views/dashboard/configuracoes.blade.php',
    'resources/views/dashboard/leads.blade.php',
    'resources/views/dashboard/contract-templates/show.blade.php',
    'resources/views/dashboard/contract-templates/create.blade.php',
    'resources/views/dashboard/contract-templates/edit.blade.php',
    'resources/views/dashboard/contract-templates/index.blade.php',
    'resources/views/dashboard/agenda-create.blade.php',
    'resources/views/dashboard/licenciado-gerenciar.blade.php',
    'resources/views/hierarchy/branding/index.blade.php'
];

$fixedCount = 0;
$totalFiles = 0;

foreach ($filesToFix as $file) {
    $fullPath = __DIR__ . '/' . $file;
    
    if (file_exists($fullPath)) {
        $totalFiles++;
        $content = file_get_contents($fullPath);
        $originalContent = $content;
        
        // Substituir referências ao componente removido
        $content = str_replace('<x-dynamic-branding />', '<x-simple-branding />', $content);
        $content = str_replace('<x-dynamic-branding/>', '<x-simple-branding />', $content);
        $content = str_replace('<x-dynamic-branding>', '<x-simple-branding />', $content);
        $content = str_replace('x-dynamic-branding', 'x-simple-branding', $content);
        
        // Remover estilos inline antigos se existirem
        $content = preg_replace('/<style>.*?BRANDING.*?<\/style>/s', '', $content);
        $content = preg_replace('/<style>.*?dynamic.*?<\/style>/s', '', $content);
        
        if ($content !== $originalContent) {
            if (file_put_contents($fullPath, $content)) {
                echo "✅ Corrigido: " . basename($file) . "\n";
                $fixedCount++;
            } else {
                echo "❌ Erro ao corrigir: " . basename($file) . "\n";
            }
        } else {
            echo "ℹ️  Sem mudanças: " . basename($file) . "\n";
        }
    } else {
        echo "⚠️  Não encontrado: " . basename($file) . "\n";
    }
}

echo "\n=== CORRIGINDO SIDEBAR ===\n";
$sidebarPath = __DIR__ . '/resources/views/components/dynamic-sidebar.blade.php';
if (file_exists($sidebarPath)) {
    $content = file_get_contents($sidebarPath);
    
    // Remover estilos inline problemáticos
    $content = preg_replace('/<style>.*?<\/style>/s', '', $content);
    
    if (file_put_contents($sidebarPath, $content)) {
        echo "✅ Sidebar limpa\n";
    }
}

echo "\n=== VERIFICANDO LAYOUTS ===\n";
$layouts = [
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/dashboard.blade.php',
    'resources/views/layouts/licenciado.blade.php'
];

foreach ($layouts as $layout) {
    $fullPath = __DIR__ . '/' . $layout;
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        
        // Verificar se tem o componente correto
        if (strpos($content, 'simple-branding') !== false) {
            echo "✅ Layout OK: " . basename($layout) . "\n";
        } else {
            echo "⚠️  Layout precisa de correção: " . basename($layout) . "\n";
            
            // Adicionar componente se não existir
            if (strpos($content, '</head>') !== false) {
                $simpleIncludes = '    <!-- SISTEMA SIMPLES DE BRANDING -->' . "\n" .
                                 '    <link href="{{ asset(\'css/simple-branding.css\') }}" rel="stylesheet">' . "\n" .
                                 '    <x-simple-branding />' . "\n";
                
                $content = str_replace('</head>', $simpleIncludes . '</head>', $content);
                
                if (file_put_contents($fullPath, $content)) {
                    echo "✅ Layout corrigido: " . basename($layout) . "\n";
                }
            }
        }
    }
}

echo "\n=== VERIFICANDO COMPONENTE SIMPLES ===\n";
$simpleComponentPath = __DIR__ . '/resources/views/components/simple-branding.blade.php';
if (file_exists($simpleComponentPath)) {
    echo "✅ Componente simple-branding existe\n";
} else {
    echo "❌ Componente simple-branding não encontrado - criando...\n";
    
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
    
    if (file_put_contents($simpleComponentPath, $simpleComponent)) {
        echo "✅ Componente simple-branding criado\n";
    }
}

echo "\n=== VERIFICANDO CSS SIMPLES ===\n";
$simpleCSSPath = __DIR__ . '/public/css/simple-branding.css';
if (file_exists($simpleCSSPath)) {
    echo "✅ CSS simple-branding existe\n";
} else {
    echo "❌ CSS simple-branding não encontrado - criando...\n";
    
    $simpleCSS = '/* SISTEMA SIMPLES DE BRANDING - FUNCIONAL */

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

/* BOTÕES - Usar cor primária */
button:not(.sidebar button):not(.w-64 button),
.btn:not(.sidebar .btn):not(.w-64 .btn) {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

/* COMPATIBILIDADE COM TAILWIND */
.bg-blue-500:not(.sidebar .bg-blue-500):not(.w-64 .bg-blue-500),
.bg-blue-600:not(.sidebar .bg-blue-600):not(.w-64 .bg-blue-600),
.bg-indigo-500:not(.sidebar .bg-indigo-500):not(.w-64 .bg-indigo-500),
.bg-indigo-600:not(.sidebar .bg-indigo-600):not(.w-64 .bg-indigo-600) {
    background-color: var(--primary-color) !important;
}';
    
    if (file_put_contents($simpleCSSPath, $simpleCSS)) {
        echo "✅ CSS simple-branding criado\n";
    }
}

echo "\n=== RESUMO DA CORREÇÃO ===\n";
echo "📄 Arquivos processados: $totalFiles\n";
echo "✅ Arquivos corrigidos: $fixedCount\n";
echo "🔧 Componente simple-branding verificado\n";
echo "🎨 CSS simple-branding verificado\n";
echo "📋 Layouts verificados\n";

echo "\n✅ CORREÇÃO DE REFERÊNCIAS CONCLUÍDA!\n";
echo "Agora o sistema deve funcionar sem erros de componente.\n";

?>
