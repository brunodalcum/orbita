<?php

/**
 * Script para corrigir erro de sintaxe em produção
 * Execute este script diretamente em produção
 */

echo "🔧 CORREÇÃO DE ERRO DE SINTAXE - PRODUÇÃO\n";
echo "========================================\n\n";

// 1. Limpar TODOS os caches
echo "1. 🧹 LIMPANDO CACHES COMPLETAMENTE:\n";

$cachePaths = [
    __DIR__ . '/storage/framework/views',
    __DIR__ . '/storage/framework/cache',
    __DIR__ . '/bootstrap/cache'
];

foreach ($cachePaths as $cachePath) {
    if (is_dir($cachePath)) {
        $files = glob($cachePath . '/*');
        $removed = 0;
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
                $removed++;
            }
        }
        echo "   ✅ " . basename($cachePath) . ": {$removed} arquivos removidos\n";
    } else {
        echo "   ⚠️  Diretório não encontrado: " . basename($cachePath) . "\n";
    }
}

// 2. Verificar integridade do arquivo
echo "\n2. 📄 VERIFICANDO ARQUIVO DA VIEW:\n";

$viewPath = __DIR__ . '/resources/views/hierarchy/branding/index.blade.php';

if (!file_exists($viewPath)) {
    echo "   ❌ Arquivo não encontrado!\n";
    exit(1);
}

$content = file_get_contents($viewPath);
$lines = file($viewPath, FILE_IGNORE_NEW_LINES);

echo "   ✅ Arquivo existe\n";
echo "   📊 Tamanho: " . strlen($content) . " bytes\n";
echo "   📊 Linhas: " . count($lines) . "\n";
echo "   🕒 Modificado: " . date('Y-m-d H:i:s', filemtime($viewPath)) . "\n";

// 3. Verificar estrutura condicional
echo "\n3. 🔍 VERIFICANDO ESTRUTURA CONDICIONAL:\n";

$ifCount = substr_count($content, '@if');
$endifCount = substr_count($content, '@endif');

echo "   @if: {$ifCount}\n";
echo "   @endif: {$endifCount}\n";

if ($ifCount === $endifCount) {
    echo "   ✅ Estrutura balanceada\n";
} else {
    echo "   ❌ Estrutura desbalanceada! Diferença: " . abs($ifCount - $endifCount) . "\n";
}

// 4. Verificar caracteres especiais ou problemas de encoding
echo "\n4. 🔍 VERIFICANDO ENCODING:\n";

$encoding = mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'ASCII'], true);
echo "   Encoding detectado: " . ($encoding ?: 'desconhecido') . "\n";

if ($encoding !== 'UTF-8') {
    echo "   ⚠️  Encoding não é UTF-8, pode causar problemas\n";
} else {
    echo "   ✅ Encoding correto (UTF-8)\n";
}

// 5. Verificar se há caracteres invisíveis problemáticos
$hasInvisibleChars = preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', $content);
if ($hasInvisibleChars) {
    echo "   ⚠️  Caracteres invisíveis encontrados\n";
} else {
    echo "   ✅ Sem caracteres invisíveis problemáticos\n";
}

// 6. Verificar últimas linhas do arquivo
echo "\n5. 📋 ÚLTIMAS 10 LINHAS DO ARQUIVO:\n";

$lastLines = array_slice($lines, -10);
foreach ($lastLines as $index => $line) {
    $lineNum = count($lines) - 10 + $index + 1;
    echo "   {$lineNum}: " . $line . "\n";
}

// 7. Verificar se há @endif suspeito
echo "\n6. 🔍 VERIFICANDO @endif SUSPEITOS:\n";

$endifLines = [];
foreach ($lines as $lineNum => $line) {
    if (strpos($line, '@endif') !== false) {
        $endifLines[] = ($lineNum + 1) . ': ' . trim($line);
    }
}

echo "   @endif encontrados:\n";
foreach ($endifLines as $endifLine) {
    echo "      " . $endifLine . "\n";
}

// 8. Criar versão limpa do arquivo (sem modificar o original)
echo "\n7. 🔧 CRIANDO VERSÃO LIMPA:\n";

$cleanPath = $viewPath . '.clean';
$cleanContent = $content;

// Remover possíveis caracteres invisíveis
$cleanContent = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $cleanContent);

// Normalizar quebras de linha
$cleanContent = str_replace(["\r\n", "\r"], "\n", $cleanContent);

if (file_put_contents($cleanPath, $cleanContent)) {
    echo "   ✅ Versão limpa criada: {$cleanPath}\n";
    
    // Verificar se a versão limpa é diferente
    if (md5($content) !== md5($cleanContent)) {
        echo "   ⚠️  Arquivo foi modificado na limpeza\n";
        echo "   🔧 Para aplicar: mv {$cleanPath} {$viewPath}\n";
    } else {
        echo "   ✅ Arquivo já estava limpo\n";
        unlink($cleanPath);
    }
} else {
    echo "   ❌ Erro ao criar versão limpa\n";
}

// 9. Verificar permissões
echo "\n8. 🔐 VERIFICANDO PERMISSÕES:\n";

$paths = [
    __DIR__ . '/storage',
    __DIR__ . '/storage/framework',
    __DIR__ . '/storage/framework/views',
    __DIR__ . '/bootstrap/cache',
    $viewPath
];

foreach ($paths as $path) {
    if (file_exists($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        $isWritable = is_writable($path);
        echo "   {$path}: {$perms} " . ($isWritable ? '✅' : '❌') . "\n";
    }
}

// 10. Tentar compilar a view (se possível)
echo "\n9. 🧪 TESTE DE COMPILAÇÃO:\n";

try {
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
        
        if (file_exists(__DIR__ . '/bootstrap/app.php')) {
            $app = require_once __DIR__ . '/bootstrap/app.php';
            $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
            
            // Dados mínimos para teste
            $testData = [
                'user' => (object)[
                    'name' => 'Test User',
                    'isSuperAdminNode' => function() { return true; }
                ],
                'targetUser' => (object)['name' => 'Test'],
                'currentBranding' => ['primary_color' => '#000'],
                'parentBranding' => null,
                'availableFonts' => [],
                'colorPresets' => [],
                'availableNodes' => collect(),
                'selectedNodeId' => null
            ];
            
            try {
                \Illuminate\Support\Facades\View::make('hierarchy.branding.index', $testData)->render();
                echo "   ✅ View compila sem erros!\n";
            } catch (Exception $e) {
                echo "   ❌ Erro na compilação:\n";
                echo "      " . $e->getMessage() . "\n";
                echo "      Arquivo: " . $e->getFile() . "\n";
                echo "      Linha: " . $e->getLine() . "\n";
            }
        } else {
            echo "   ⚠️  Bootstrap não encontrado, pulando teste\n";
        }
    } else {
        echo "   ⚠️  Autoload não encontrado, pulando teste\n";
    }
} catch (Exception $e) {
    echo "   ⚠️  Erro no teste: " . $e->getMessage() . "\n";
}

echo "\n🏁 DIAGNÓSTICO CONCLUÍDO!\n";
echo "📋 PRÓXIMOS PASSOS:\n";
echo "   1. Teste a URL novamente\n";
echo "   2. Se ainda houver erro, verifique os logs do servidor\n";
echo "   3. Considere reiniciar o servidor web\n";
echo "   4. Se necessário, use a versão limpa do arquivo\n";

echo "\n✅ Script finalizado!\n";
