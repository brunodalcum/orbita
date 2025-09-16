<?php

// Script para remover inclusões duplicadas do branding

$viewsDir = __DIR__ . '/resources/views';
$fixed = [];
$errors = [];

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($viewsDir, RecursiveDirectoryIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php' && strpos($file->getFilename(), '.blade.php') !== false) {
        $filePath = $file->getPathname();
        $relativePath = str_replace(__DIR__ . '/', '', $filePath);
        $content = file_get_contents($filePath);
        $originalContent = $content;
        
        // Contar ocorrências de x-dynamic-branding
        $count = substr_count($content, '<x-dynamic-branding');
        $count += substr_count($content, '<x-dynamic-branding />');
        
        if ($count > 1) {
            // Remover todas as ocorrências
            $content = str_replace('<x-dynamic-branding />', '', $content);
            $content = str_replace('<x-dynamic-branding/>', '', $content);
            $content = str_replace('<x-dynamic-branding>', '', $content);
            
            // Adicionar apenas uma ocorrência no local correto
            $lines = explode("\n", $content);
            $insertPosition = -1;
            
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
                
                // Se encontrar HTML direto no início
                if (strpos($line, '<html') === 0 || strpos($line, '<div') === 0 || strpos($line, '<!DOCTYPE') === 0) {
                    $insertPosition = $i;
                    break;
                }
            }
            
            // Se não encontrou posição específica, inserir no início
            if ($insertPosition === -1) {
                $insertPosition = 0;
            }
            
            // Inserir o branding uma única vez
            array_splice($lines, $insertPosition, 0, '<x-dynamic-branding />');
            $content = implode("\n", $lines);
            
            // Limpar linhas vazias extras
            $content = preg_replace('/\n\s*\n\s*\n/', "\n\n", $content);
            
            if (file_put_contents($filePath, $content)) {
                $fixed[] = $relativePath;
            } else {
                $errors[] = "Erro ao escrever: $relativePath";
            }
        }
    }
}

echo "=== REMOÇÃO DE BRANDING DUPLICADO ===\n\n";

if (count($fixed) > 0) {
    echo "✅ ARQUIVOS CORRIGIDOS (" . count($fixed) . "):\n";
    foreach ($fixed as $file) {
        echo "  • $file\n";
    }
} else {
    echo "ℹ️  Nenhum arquivo com duplicação encontrado.\n";
}

if (count($errors) > 0) {
    echo "\n❌ ERROS (" . count($errors) . "):\n";
    foreach ($errors as $error) {
        echo "  • $error\n";
    }
}

echo "\n=== RESUMO ===\n";
echo "Arquivos corrigidos: " . count($fixed) . "\n";
echo "Erros: " . count($errors) . "\n";

?>
