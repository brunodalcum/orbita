<?php

// Script para aplicar branding dinâmico nas páginas importantes

$pagesToUpdate = [
    // Páginas da hierarquia
    'resources/views/hierarchy/tree/index.blade.php',
    'resources/views/hierarchy/management/edit.blade.php',
    'resources/views/hierarchy/management/show.blade.php',
    'resources/views/hierarchy/impersonation.blade.php',
    'resources/views/hierarchy/permissions/index.blade.php',
    'resources/views/hierarchy/permissions/manage.blade.php',
    'resources/views/hierarchy/audit/index.blade.php',
    'resources/views/hierarchy/modules/index.blade.php',
    'resources/views/hierarchy/notifications/index.blade.php',
    'resources/views/hierarchy/reports/index.blade.php',
    
    // Páginas do dashboard
    'resources/views/dashboard/contract-templates/index.blade.php',
    'resources/views/dashboard/contract-templates/edit.blade.php',
    'resources/views/dashboard/contract-templates/create.blade.php',
    'resources/views/dashboard/contract-templates/show.blade.php',
    'resources/views/dashboard/permissions/index.blade.php',
    'resources/views/dashboard/permissions/manage-role.blade.php',
    'resources/views/dashboard/users/index.blade.php',
    'resources/views/dashboard/users/edit.blade.php',
    'resources/views/dashboard/users/create.blade.php',
    'resources/views/dashboard/users/show.blade.php',
    'resources/views/dashboard/marketing/index.blade.php',
    'resources/views/dashboard/marketing/campanhas.blade.php',
    'resources/views/dashboard/marketing/modelos.blade.php',
    'resources/views/dashboard/reminders/index.blade.php',
    'resources/views/dashboard/reminders/create.blade.php',
    'resources/views/dashboard/reminders/show.blade.php',
    
    // Páginas do licenciado
    'resources/views/licenciado/dashboard.blade.php',
    'resources/views/licenciado/leads/index.blade.php',
    'resources/views/licenciado/planos.blade.php',
    'resources/views/licenciado/agenda/index.blade.php',
    'resources/views/licenciado/agenda/create.blade.php',
    'resources/views/licenciado/tax-simulator.blade.php',
    
    // Layouts importantes
    'resources/views/layouts/dashboard.blade.php',
    'resources/views/layouts/licenciado.blade.php'
];

$updated = [];
$errors = [];

foreach ($pagesToUpdate as $filePath) {
    $fullPath = __DIR__ . '/' . $filePath;
    
    if (!file_exists($fullPath)) {
        $errors[] = "Arquivo não encontrado: $filePath";
        continue;
    }
    
    $content = file_get_contents($fullPath);
    
    // Verificar se já tem branding
    if (strpos($content, '<x-dynamic-branding') !== false || strpos($content, 'x-dynamic-branding') !== false) {
        continue; // Já tem branding
    }
    
    // Determinar onde inserir o branding baseado no tipo de arquivo
    $brandingInserted = false;
    
    // Para layouts
    if (strpos($filePath, '/layouts/') !== false) {
        // Inserir após <head> ou antes de </head>
        if (strpos($content, '</head>') !== false) {
            $content = str_replace('</head>', "    <x-dynamic-branding />\n</head>", $content);
            $brandingInserted = true;
        }
    } else {
        // Para páginas normais, inserir no início após @extends ou @section
        $lines = explode("\n", $content);
        $insertPosition = 0;
        
        // Encontrar posição ideal para inserir
        for ($i = 0; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            
            // Após @extends
            if (strpos($line, '@extends') === 0) {
                $insertPosition = $i + 1;
                continue;
            }
            
            // Após @section('content') ou similar
            if (strpos($line, "@section('content')") === 0 || strpos($line, '@section("content")') === 0) {
                $insertPosition = $i + 1;
                break;
            }
            
            // Após @section('title')
            if (strpos($line, "@section('title')") === 0 || strpos($line, '@section("title")') === 0) {
                $insertPosition = $i + 1;
                continue;
            }
            
            // Se encontrar HTML direto, inserir antes
            if (strpos($line, '<') === 0 && $insertPosition === 0) {
                $insertPosition = $i;
                break;
            }
        }
        
        // Inserir o branding
        if ($insertPosition >= 0) {
            array_splice($lines, $insertPosition, 0, '<x-dynamic-branding />');
            $content = implode("\n", $lines);
            $brandingInserted = true;
        }
    }
    
    if ($brandingInserted) {
        if (file_put_contents($fullPath, $content)) {
            $updated[] = $filePath;
        } else {
            $errors[] = "Erro ao escrever arquivo: $filePath";
        }
    } else {
        $errors[] = "Não foi possível determinar onde inserir branding: $filePath";
    }
}

echo "=== APLICAÇÃO DE BRANDING DINÂMICO ===\n\n";

echo "✅ ARQUIVOS ATUALIZADOS (" . count($updated) . "):\n";
foreach ($updated as $file) {
    echo "  • $file\n";
}

if (count($errors) > 0) {
    echo "\n❌ ERROS (" . count($errors) . "):\n";
    foreach ($errors as $error) {
        echo "  • $error\n";
    }
}

echo "\n=== RESUMO ===\n";
echo "Arquivos atualizados: " . count($updated) . "\n";
echo "Erros: " . count($errors) . "\n";
echo "Total processado: " . count($pagesToUpdate) . "\n";

?>
