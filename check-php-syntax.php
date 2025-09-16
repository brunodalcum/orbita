<?php

// Script para verificar sintaxe PHP dos arquivos principais

echo "=== VERIFICAÇÃO DE SINTAXE PHP ===\n\n";

$filesToCheck = [
    'app/Http/Controllers/HierarchyBrandingController.php',
    'app/Services/BrandingService.php',
    'app/Http/Controllers/DynamicCSSController.php',
    'app/Http/Middleware/EnsureBrandingMiddleware.php',
    'resources/views/components/dynamic-branding.blade.php'
];

$errors = [];
$success = 0;

foreach ($filesToCheck as $file) {
    $fullPath = __DIR__ . '/' . $file;
    
    if (file_exists($fullPath)) {
        // Verificar sintaxe PHP
        $output = [];
        $returnCode = 0;
        
        exec("php -l \"$fullPath\" 2>&1", $output, $returnCode);
        
        if ($returnCode === 0) {
            echo "✅ $file - Sintaxe OK\n";
            $success++;
        } else {
            echo "❌ $file - ERRO DE SINTAXE:\n";
            foreach ($output as $line) {
                echo "   $line\n";
            }
            $errors[] = $file;
        }
    } else {
        echo "⚠️  $file - Arquivo não encontrado\n";
    }
}

echo "\n=== RESUMO ===\n";
echo "✅ Arquivos OK: $success\n";
echo "❌ Arquivos com erro: " . count($errors) . "\n";

if (count($errors) > 0) {
    echo "\nArquivos com problemas:\n";
    foreach ($errors as $file) {
        echo "- $file\n";
    }
} else {
    echo "\n🎉 Todos os arquivos estão com sintaxe correta!\n";
}

// Verificar se o namespace está correto no HierarchyBrandingController
echo "\n=== VERIFICAÇÃO ESPECÍFICA DO NAMESPACE ===\n";
$brandingController = __DIR__ . '/app/Http/Controllers/HierarchyBrandingController.php';
if (file_exists($brandingController)) {
    $content = file_get_contents($brandingController);
    
    // Verificar se namespace vem antes de use
    if (preg_match('/^<\?php\s*\n\s*namespace/', $content)) {
        echo "✅ Namespace declarado corretamente no início\n";
    } elseif (preg_match('/^<\?php\s*\n\s*use.*\n.*namespace/', $content)) {
        echo "❌ ERRO: use antes do namespace\n";
    } else {
        echo "✅ Estrutura do arquivo parece correta\n";
    }
    
    // Verificar se BrandingService está importado
    if (strpos($content, 'use App\Services\BrandingService;') !== false) {
        echo "✅ BrandingService importado corretamente\n";
    } else {
        echo "⚠️  BrandingService não encontrado nos imports\n";
    }
} else {
    echo "❌ HierarchyBrandingController não encontrado\n";
}

echo "\n✅ VERIFICAÇÃO CONCLUÍDA!\n";

?>
