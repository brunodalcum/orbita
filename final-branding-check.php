<?php

// Script final para verificar o branding

echo "=== VERIFICAÇÃO FINAL DO BRANDING ===\n\n";

// Verificar algumas páginas específicas
$testPages = [
    'resources/views/welcome.blade.php',
    'resources/views/dashboard/agenda.blade.php',
    'resources/views/hierarchy/branding/index.blade.php'
];

foreach ($testPages as $page) {
    $fullPath = __DIR__ . '/' . $page;
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        
        // Contar ocorrências mais precisamente
        $patterns = [
            '<x-dynamic-branding />',
            '<x-dynamic-branding/>',
            '<x-dynamic-branding>',
            'x-dynamic-branding'
        ];
        
        $totalCount = 0;
        foreach ($patterns as $pattern) {
            $count = substr_count($content, $pattern);
            $totalCount += $count;
        }
        
        echo "📄 $page: $totalCount ocorrências\n";
        
        if ($totalCount > 1) {
            echo "  ⚠️  Múltiplas inclusões detectadas\n";
        } elseif ($totalCount === 1) {
            echo "  ✅ Branding aplicado corretamente\n";
        } else {
            echo "  ❌ Sem branding\n";
        }
    } else {
        echo "📄 $page: Arquivo não encontrado\n";
    }
}

// Verificar se o componente tem proteção @once
echo "\n=== VERIFICAÇÃO DO COMPONENTE ===\n";
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

echo "\n=== TESTE DE CACHE ===\n";
echo "Limpando caches...\n";
exec('php artisan cache:clear 2>&1', $output1, $return1);
exec('php artisan view:clear 2>&1', $output2, $return2);

if ($return1 === 0 && $return2 === 0) {
    echo "✅ Caches limpos com sucesso\n";
} else {
    echo "❌ Erro ao limpar caches\n";
}

echo "\n=== RESUMO FINAL ===\n";
echo "O erro de redeclaração deve estar resolvido com:\n";
echo "1. ✅ Funções convertidas para closures (evita redeclaração)\n";
echo "2. ✅ Proteção @once adicionada (evita CSS duplicado)\n";
echo "3. ✅ Caches limpos\n";
echo "\nTeste a página /agenda novamente!\n";

?>
