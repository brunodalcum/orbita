<?php

// Script para testar se o erro de redeclaração foi corrigido

echo "=== TESTE DE CORREÇÃO DO BRANDING ===\n\n";

// Verificar se o componente foi corrigido
$brandingFile = __DIR__ . '/resources/views/components/dynamic-branding.blade.php';
$content = file_get_contents($brandingFile);

// Verificar se as funções foram convertidas para closures
if (strpos($content, 'function hexToRgb(') !== false) {
    echo "❌ Ainda há função hexToRgb() declarada globalmente\n";
} else {
    echo "✅ Função hexToRgb() convertida para closure\n";
}

if (strpos($content, 'function getBrightness(') !== false) {
    echo "❌ Ainda há função getBrightness() declarada globalmente\n";
} else {
    echo "✅ Função getBrightness() convertida para closure\n";
}

// Verificar se há proteção @once
if (strpos($content, '@once') !== false) {
    echo "✅ Proteção @once adicionada\n";
} else {
    echo "❌ Proteção @once não encontrada\n";
}

// Verificar se as variáveis CSS estão sendo definidas
$requiredVars = [
    '--primary-color',
    '--secondary-color',
    '--accent-color',
    '--text-color',
    '--background-color'
];

$missingVars = [];
foreach ($requiredVars as $var) {
    if (strpos($content, $var) === false) {
        $missingVars[] = $var;
    }
}

if (empty($missingVars)) {
    echo "✅ Todas as variáveis CSS estão definidas\n";
} else {
    echo "❌ Variáveis CSS faltando: " . implode(', ', $missingVars) . "\n";
}

// Verificar se há páginas com múltiplas inclusões do branding
echo "\n=== VERIFICAÇÃO DE MÚLTIPLAS INCLUSÕES ===\n";

$viewsDir = __DIR__ . '/resources/views';
$pagesWithMultipleBranding = [];

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($viewsDir, RecursiveDirectoryIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php' && strpos($file->getFilename(), '.blade.php') !== false) {
        $fileContent = file_get_contents($file->getPathname());
        $relativePath = str_replace(__DIR__ . '/', '', $file->getPathname());
        
        // Contar ocorrências de x-dynamic-branding
        $count = substr_count($fileContent, '<x-dynamic-branding');
        $count += substr_count($fileContent, 'x-dynamic-branding');
        
        if ($count > 1) {
            $pagesWithMultipleBranding[$relativePath] = $count;
        }
    }
}

if (empty($pagesWithMultipleBranding)) {
    echo "✅ Nenhuma página com múltiplas inclusões encontrada\n";
} else {
    echo "⚠️  Páginas com múltiplas inclusões:\n";
    foreach ($pagesWithMultipleBranding as $page => $count) {
        echo "  • $page: $count inclusões\n";
    }
}

echo "\n=== RESUMO ===\n";

$issues = 0;
if (strpos($content, 'function hexToRgb(') !== false) $issues++;
if (strpos($content, 'function getBrightness(') !== false) $issues++;
if (strpos($content, '@once') === false) $issues++;
if (!empty($missingVars)) $issues++;
if (!empty($pagesWithMultipleBranding)) $issues++;

if ($issues === 0) {
    echo "✅ SUCESSO - Todos os problemas foram corrigidos!\n";
    echo "O erro de redeclaração deve estar resolvido.\n";
} else {
    echo "⚠️  ATENÇÃO - $issues problema(s) ainda precisam ser resolvidos.\n";
}

echo "\n=== TESTE CONCLUÍDO ===\n";

?>
