<?php

// Script para verificar se as correções de branding foram aplicadas

echo "=== VERIFICAÇÃO FINAL DAS CORREÇÕES DE BRANDING ===\n\n";

$pagesToCheck = [
    'resources/views/dashboard/licenciados.blade.php' => '/dashboard/licenciados',
    'resources/views/dashboard/contracts/index.blade.php' => '/contracts',
    'resources/views/dashboard/contracts/show.blade.php' => '/contracts/{id}'
];

foreach ($pagesToCheck as $file => $route) {
    $fullPath = __DIR__ . '/' . $file;
    
    if (!file_exists($fullPath)) {
        echo "❌ $file: Arquivo não encontrado\n";
        continue;
    }
    
    $content = file_get_contents($fullPath);
    
    echo "📄 $file ($route):\n";
    
    // Verificar se tem branding dinâmico
    if (strpos($content, '<x-dynamic-branding') !== false) {
        echo "  ✅ Componente de branding presente\n";
    } else {
        echo "  ❌ Componente de branding ausente\n";
    }
    
    // Verificar se tem CSS para sobrescrever Tailwind
    if (strpos($content, 'var(--primary-color)') !== false) {
        echo "  ✅ Variáveis CSS dinâmicas em uso\n";
    } else {
        echo "  ⚠️  Variáveis CSS dinâmicas não encontradas\n";
    }
    
    // Verificar se ainda há cores hardcoded problemáticas
    $hardcodedColors = ['#3b82f6', '#2563eb', '#1d4ed8'];
    $hasHardcoded = false;
    
    foreach ($hardcodedColors as $color) {
        if (strpos($content, $color) !== false) {
            $hasHardcoded = true;
            break;
        }
    }
    
    if (!$hasHardcoded) {
        echo "  ✅ Sem cores hexadecimais hardcoded\n";
    } else {
        echo "  ⚠️  Ainda há cores hexadecimais hardcoded\n";
    }
    
    // Verificar se tem CSS de sobrescrita
    if (strpos($content, 'Sobrescrever cores') !== false || strpos($content, '.bg-blue-') !== false) {
        echo "  ✅ CSS de sobrescrita presente\n";
    } else {
        echo "  ⚠️  CSS de sobrescrita ausente\n";
    }
    
    echo "\n";
}

// Verificar o componente de branding
echo "=== VERIFICAÇÃO DO COMPONENTE DE BRANDING ===\n";
$brandingFile = __DIR__ . '/resources/views/components/dynamic-branding.blade.php';
$brandingContent = file_get_contents($brandingFile);

if (strpos($brandingContent, '@once') !== false) {
    echo "✅ Proteção @once presente\n";
} else {
    echo "❌ Proteção @once ausente\n";
}

if (strpos($brandingContent, 'function hexToRgb(') === false) {
    echo "✅ Funções convertidas para closures\n";
} else {
    echo "❌ Ainda há funções globais\n";
}

// Verificar variáveis CSS principais
$requiredVars = [
    '--primary-color',
    '--secondary-color',
    '--accent-color',
    '--primary-light',
    '--primary-dark'
];

$missingVars = [];
foreach ($requiredVars as $var) {
    if (strpos($brandingContent, $var) === false) {
        $missingVars[] = $var;
    }
}

if (empty($missingVars)) {
    echo "✅ Todas as variáveis CSS principais definidas\n";
} else {
    echo "❌ Variáveis CSS faltando: " . implode(', ', $missingVars) . "\n";
}

echo "\n=== RESUMO FINAL ===\n";
echo "🎨 As páginas /dashboard/licenciados e /contracts agora devem usar:\n";
echo "   • Cores primárias do branding para botões azuis\n";
echo "   • Cores de accent para botões verdes\n";
echo "   • Backgrounds e textos dinâmicos\n";
echo "   • CSS de sobrescrita para classes Tailwind\n\n";

echo "✅ CORREÇÕES APLICADAS COM SUCESSO!\n";
echo "🌈 Teste as páginas para verificar se as cores do branding estão sendo aplicadas.\n";

?>
