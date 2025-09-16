<?php

// Script para diagnosticar problemas de branding em produção

echo "=== DIAGNÓSTICO DE BRANDING EM PRODUÇÃO ===\n\n";

// 1. Verificar se o usuário está autenticado
session_start();
echo "🔐 VERIFICAÇÃO DE AUTENTICAÇÃO:\n";
echo "Session ID: " . session_id() . "\n";
echo "Session data: " . print_r($_SESSION, true) . "\n";

// 2. Verificar arquivos CSS
echo "\n📄 VERIFICAÇÃO DE ARQUIVOS CSS:\n";

$cssFiles = [
    'public/css/global-branding.css',
    'public/css/app.css',
    'public/build/assets/app.css'
];

foreach ($cssFiles as $file) {
    $fullPath = __DIR__ . '/' . $file;
    if (file_exists($fullPath)) {
        $size = filesize($fullPath);
        $modified = date('Y-m-d H:i:s', filemtime($fullPath));
        echo "✅ $file ($size bytes, modificado: $modified)\n";
        
        // Verificar conteúdo do CSS
        $content = file_get_contents($fullPath);
        if (strpos($content, '--primary-color') !== false) {
            echo "   ✅ Contém variáveis CSS de branding\n";
        } else {
            echo "   ❌ NÃO contém variáveis CSS de branding\n";
        }
    } else {
        echo "❌ $file (NÃO ENCONTRADO)\n";
    }
}

// 3. Verificar permissões de arquivos
echo "\n🔒 VERIFICAÇÃO DE PERMISSÕES:\n";
$directories = [
    'storage/logs',
    'storage/framework/cache',
    'storage/framework/views',
    'bootstrap/cache',
    'public/css'
];

foreach ($directories as $dir) {
    $fullPath = __DIR__ . '/' . $dir;
    if (is_dir($fullPath)) {
        $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
        $writable = is_writable($fullPath) ? 'GRAVÁVEL' : 'NÃO GRAVÁVEL';
        echo "📁 $dir: $perms ($writable)\n";
    } else {
        echo "❌ $dir: NÃO EXISTE\n";
    }
}

// 4. Verificar se as rotas estão funcionando
echo "\n🌐 VERIFICAÇÃO DE ROTAS:\n";

// Simular requisição para verificar rotas
$routes = [
    '/dashboard',
    '/dashboard/licenciados',
    '/css/dynamic-branding.css'
];

foreach ($routes as $route) {
    echo "🔗 Rota $route: ";
    
    // Verificar se existe no arquivo de rotas
    $routesFile = __DIR__ . '/routes/web.php';
    $routesContent = file_get_contents($routesFile);
    
    if (strpos($route, 'licenciados') !== false && strpos($routesContent, 'licenciados') !== false) {
        echo "✅ DEFINIDA\n";
    } elseif (strpos($route, 'dashboard') !== false && strpos($routesContent, 'dashboard') !== false) {
        echo "✅ DEFINIDA\n";
    } elseif (strpos($route, 'dynamic-branding') !== false && strpos($routesContent, 'dynamic-branding') !== false) {
        echo "✅ DEFINIDA\n";
    } else {
        echo "❌ NÃO ENCONTRADA\n";
    }
}

// 5. Verificar banco de dados
echo "\n🗄️ VERIFICAÇÃO DE BANCO DE DADOS:\n";

try {
    // Tentar conectar com SQLite local
    $dbPath = __DIR__ . '/database/database.sqlite';
    if (file_exists($dbPath)) {
        $pdo = new PDO('sqlite:' . $dbPath);
        echo "✅ Conexão SQLite local: OK\n";
        
        // Verificar se há usuários
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "👥 Usuários no banco: " . $result['count'] . "\n";
        
        // Verificar se há branding
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM node_branding");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "🎨 Registros de branding: " . $result['count'] . "\n";
        
    } else {
        echo "❌ Banco SQLite não encontrado\n";
    }
} catch (Exception $e) {
    echo "❌ Erro de conexão: " . $e->getMessage() . "\n";
}

// 6. Verificar componentes Blade
echo "\n🧩 VERIFICAÇÃO DE COMPONENTES BLADE:\n";

$components = [
    'resources/views/components/dynamic-branding.blade.php',
    'resources/views/components/dynamic-sidebar.blade.php'
];

foreach ($components as $component) {
    $fullPath = __DIR__ . '/' . $component;
    if (file_exists($fullPath)) {
        echo "✅ " . basename($component) . "\n";
        
        $content = file_get_contents($fullPath);
        if (strpos($content, '@once') !== false) {
            echo "   ✅ Tem proteção @once\n";
        }
        if (strpos($content, '--primary-color') !== false) {
            echo "   ✅ Define variáveis CSS\n";
        }
    } else {
        echo "❌ " . basename($component) . " (NÃO ENCONTRADO)\n";
    }
}

// 7. Gerar CSS de teste
echo "\n🧪 GERANDO CSS DE TESTE:\n";

$testCSS = '/* CSS DE TESTE PARA BRANDING */
:root {
    --primary-color: #FF0000 !important;
    --secondary-color: #00FF00 !important;
    --accent-color: #0000FF !important;
}

/* SOBRESCRITA AGRESSIVA DE TESTE */
* {
    --tw-bg-blue-500: #FF0000 !important;
    --tw-bg-blue-600: #FF0000 !important;
    --tw-bg-indigo-500: #FF0000 !important;
    --tw-bg-indigo-600: #FF0000 !important;
}

.bg-blue-500, .bg-blue-600, .bg-blue-700,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700,
button[class*="blue"], button[class*="indigo"],
.btn-primary, .btn[class*="blue"] {
    background-color: #FF0000 !important;
    border-color: #FF0000 !important;
    color: #FFFFFF !important;
}

.text-blue-500, .text-blue-600, .text-blue-700,
.text-indigo-500, .text-indigo-600, .text-indigo-700 {
    color: #FF0000 !important;
}

.border-blue-500, .border-blue-600, .border-blue-700,
.border-indigo-500, .border-indigo-600, .border-indigo-700 {
    border-color: #FF0000 !important;
}

/* Sobrescrever estilos inline */
[style*="background-color: #3b82f6"],
[style*="background-color: #2563eb"],
[style*="background-color: #1d4ed8"] {
    background-color: #FF0000 !important;
}

[style*="color: #3b82f6"],
[style*="color: #2563eb"],
[style*="color: #1d4ed8"] {
    color: #FF0000 !important;
}

/* Força aplicação em elementos específicos */
button, .button, input[type="submit"], input[type="button"] {
    background-color: #FF0000 !important;
    border-color: #FF0000 !important;
    color: #FFFFFF !important;
}

a:not(.text-gray):not(.text-white):not(.text-black) {
    color: #FF0000 !important;
}

/* CSS para debug visual */
.debug-branding {
    border: 3px solid #FF0000 !important;
    background-color: rgba(255, 0, 0, 0.1) !important;
}
';

$testCSSPath = __DIR__ . '/public/css/test-branding.css';
if (file_put_contents($testCSSPath, $testCSS)) {
    echo "✅ CSS de teste criado: public/css/test-branding.css\n";
    echo "   🔴 Cores de teste: VERMELHO (para identificar elementos)\n";
} else {
    echo "❌ Erro ao criar CSS de teste\n";
}

// 8. Criar página de teste
echo "\n📄 CRIANDO PÁGINA DE TESTE:\n";

$testPage = '<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Branding</title>
    <link href="css/test-branding.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-blue-600">🧪 Teste de Branding</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4 text-indigo-600">Botões de Teste</h2>
                <div class="space-y-3">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Botão Azul Tailwind
                    </button>
                    <button class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded">
                        Botão Indigo Tailwind
                    </button>
                    <button style="background-color: #3b82f6; color: white; padding: 8px 16px; border-radius: 4px; border: none;">
                        Botão com Estilo Inline
                    </button>
                    <button class="btn-primary px-4 py-2 rounded">
                        Botão Classe Customizada
                    </button>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4 text-blue-700">Textos de Teste</h2>
                <div class="space-y-2">
                    <p class="text-blue-500">Texto azul Tailwind</p>
                    <p class="text-indigo-600">Texto indigo Tailwind</p>
                    <p style="color: #3b82f6;">Texto com cor inline</p>
                    <a href="#" class="text-blue-600 hover:text-blue-800">Link azul</a>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
                <h2 class="text-xl font-semibold mb-4">Bordas de Teste</h2>
                <div class="border border-blue-300 p-3 rounded mb-3">
                    Div com borda azul
                </div>
                <div class="border-2 border-indigo-400 p-3 rounded">
                    Div com borda indigo
                </div>
            </div>
            
            <div class="bg-blue-50 p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Background de Teste</h2>
                <div class="bg-blue-100 p-3 rounded mb-3">
                    Background azul claro
                </div>
                <div class="bg-indigo-200 p-3 rounded">
                    Background indigo claro
                </div>
            </div>
        </div>
        
        <div class="mt-8 bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">🔍 Informações de Debug</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <strong>CSS Carregado:</strong><br>
                    <span class="text-green-600">✅ test-branding.css</span>
                </div>
                <div>
                    <strong>Cores Esperadas:</strong><br>
                    <span style="color: #FF0000;">🔴 VERMELHO</span>
                </div>
                <div>
                    <strong>Timestamp:</strong><br>
                    ' . date('Y-m-d H:i:s') . '
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Debug de CSS
        console.log("🧪 Teste de Branding carregado");
        console.log("CSS Variables:", getComputedStyle(document.documentElement).getPropertyValue("--primary-color"));
        
        // Adicionar classe de debug a todos os botões
        document.querySelectorAll("button").forEach(btn => {
            btn.classList.add("debug-branding");
        });
    </script>
</body>
</html>';

$testPagePath = __DIR__ . '/public/test-branding.html';
if (file_put_contents($testPagePath, $testPage)) {
    echo "✅ Página de teste criada: /test-branding.html\n";
    echo "   🌐 Acesse: https://srv971263.hstgr.cloud/test-branding.html\n";
} else {
    echo "❌ Erro ao criar página de teste\n";
}

echo "\n=== RESUMO DO DIAGNÓSTICO ===\n";
echo "📋 Arquivos verificados\n";
echo "🔒 Permissões verificadas\n";
echo "🌐 Rotas verificadas\n";
echo "🗄️ Banco de dados verificado\n";
echo "🧩 Componentes verificados\n";
echo "🧪 CSS de teste criado (VERMELHO)\n";
echo "📄 Página de teste criada\n";

echo "\n🎯 PRÓXIMOS PASSOS:\n";
echo "1. Acesse /test-branding.html para ver se o CSS está funcionando\n";
echo "2. Se os elementos ficarem VERMELHOS, o CSS está sendo aplicado\n";
echo "3. Se não ficarem vermelhos, há problema de carregamento de CSS\n";
echo "4. Verifique os logs do navegador (F12 > Console)\n";

echo "\n✅ DIAGNÓSTICO CONCLUÍDO!\n";

?>
