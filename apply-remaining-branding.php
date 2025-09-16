<?php

// Script para aplicar branding nas páginas restantes mais importantes

$pagesToUpdate = [
    // Páginas importantes que ainda não têm branding
    'resources/views/welcome.blade.php',
    'resources/views/google-auth/success.blade.php',
    'resources/views/google-auth/error.blade.php',
    'resources/views/leads/cadastro.blade.php',
    'resources/views/leads/onboarding.blade.php',
    'resources/views/contracts/show.blade.php',
    'resources/views/licenciado/comissoes.blade.php',
    'resources/views/licenciado/estabelecimentos.blade.php',
    'resources/views/licenciado/relatorios.blade.php',
    'resources/views/licenciado/suporte.blade.php',
    'resources/views/licenciado/perfil.blade.php',
    'resources/views/licenciado/estabelecimentos/create.blade.php',
    'resources/views/licenciado/agenda/calendar.blade.php',
    'resources/views/licenciado/agenda/calendar-modern.blade.php',
    'resources/views/licenciado/vendas.blade.php',
    'resources/views/dashboard/leads/extract.blade.php',
    'resources/views/dashboard/places/extract.blade.php',
    'resources/views/dashboard/contracts/generate/index-simple.blade.php',
    'resources/views/dashboard/agenda-original-backup.blade.php',
    'resources/views/dashboard/marketing/campanha-detalhes.blade.php',
    'resources/views/navigation-menu.blade.php',
    'resources/views/policy.blade.php',
    'resources/views/terms.blade.php'
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
    
    // Determinar onde inserir o branding
    $brandingInserted = false;
    $lines = explode("\n", $content);
    $insertPosition = -1;
    
    // Estratégias de inserção baseadas no tipo de arquivo
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
        
        // Após <!DOCTYPE html>
        if (strpos($line, '<!DOCTYPE html>') === 0) {
            // Procurar por <head>
            for ($j = $i + 1; $j < count($lines); $j++) {
                if (strpos(trim($lines[$j]), '<head>') !== false) {
                    $insertPosition = $j + 1;
                    break;
                }
            }
            if ($insertPosition > 0) break;
        }
        
        // Se encontrar HTML direto no início
        if (strpos($line, '<html') === 0 || strpos($line, '<div') === 0) {
            $insertPosition = $i;
            break;
        }
    }
    
    // Se não encontrou posição específica, inserir no início
    if ($insertPosition === -1) {
        $insertPosition = 0;
    }
    
    // Inserir o branding
    if ($insertPosition >= 0) {
        array_splice($lines, $insertPosition, 0, '<x-dynamic-branding />');
        $content = implode("\n", $lines);
        $brandingInserted = true;
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

// Corrigir cores hardcoded nos templates de contrato e emails
$templatesToFix = [
    'resources/views/contracts/templates/signed-default.blade.php',
    'resources/views/contracts/templates/default.blade.php',
    'resources/views/emails/marketing.blade.php',
    'resources/views/emails/contract.blade.php'
];

foreach ($templatesToFix as $filePath) {
    $fullPath = __DIR__ . '/' . $filePath;
    
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        $originalContent = $content;
        
        // Substituir cores hardcoded
        $content = str_replace('#059669', 'var(--accent-color)', $content);
        $content = str_replace('#047857', 'var(--accent-color)', $content);
        $content = str_replace('#10B981', 'var(--accent-color)', $content);
        $content = str_replace('#3B82F6', 'var(--primary-color)', $content);
        $content = str_replace('#2563EB', 'var(--primary-dark)', $content);
        
        if ($content !== $originalContent) {
            file_put_contents($fullPath, $content);
            $updated[] = $filePath . ' (cores corrigidas)';
        }
    }
}

echo "=== APLICAÇÃO FINAL DE BRANDING ===\n\n";

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
