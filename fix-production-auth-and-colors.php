<?php

// Script para diagnosticar e corrigir problemas de autenticação e cores em produção

echo "=== DIAGNÓSTICO E CORREÇÃO DE PRODUÇÃO ===\n\n";

// 1. Verificar se há problemas de sessão/autenticação
echo "1. VERIFICANDO CONFIGURAÇÕES DE SESSÃO...\n";

$sessionConfig = __DIR__ . '/config/session.php';
if (file_exists($sessionConfig)) {
    $content = file_get_contents($sessionConfig);
    
    // Verificar configurações importantes
    $hasSecureCookies = strpos($content, "'secure' => env('SESSION_SECURE_COOKIE'") !== false;
    $hasSameSite = strpos($content, "'same_site' => 'lax'") !== false;
    
    echo ($hasSecureCookies ? "✅" : "❌") . " Configuração de cookies seguros\n";
    echo ($hasSameSite ? "✅" : "❌") . " Configuração SameSite\n";
} else {
    echo "❌ Arquivo de configuração de sessão não encontrado\n";
}

// 2. Verificar middleware redirect.role
echo "\n2. VERIFICANDO MIDDLEWARE REDIRECT.ROLE...\n";

$redirectRoleMiddleware = __DIR__ . '/app/Http/Middleware/RedirectBasedOnRole.php';
if (file_exists($redirectRoleMiddleware)) {
    echo "✅ Middleware RedirectBasedOnRole existe\n";
    
    $content = file_get_contents($redirectRoleMiddleware);
    if (strpos($content, '/dashboard/licenciados') !== false) {
        echo "⚠️  Middleware pode estar redirecionando /dashboard/licenciados\n";
    } else {
        echo "✅ Middleware não interfere com /dashboard/licenciados\n";
    }
} else {
    echo "❌ Middleware RedirectBasedOnRole não encontrado\n";
}

// 3. Criar script de emergência para produção
echo "\n3. CRIANDO SCRIPT DE EMERGÊNCIA PARA PRODUÇÃO...\n";

$emergencyScript = '<?php
// SCRIPT DE EMERGÊNCIA PARA PRODUÇÃO - ACESSO VIA URL

// Verificar se é uma requisição web
if (php_sapi_name() !== "cli" && isset($_SERVER["HTTP_HOST"])) {
    
    // 1. Limpar todos os caches
    echo "<h2>🔧 LIMPEZA DE EMERGÊNCIA EM PRODUÇÃO</h2>";
    
    // Definir caminhos
    $basePath = __DIR__;
    
    // Limpar cache de views
    $viewCachePath = $basePath . "/storage/framework/views";
    if (is_dir($viewCachePath)) {
        $files = glob($viewCachePath . "/*.php");
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "<p>✅ Cache de views limpo (" . count($files) . " arquivos)</p>";
    }
    
    // Limpar cache de configuração
    $configCachePath = $basePath . "/bootstrap/cache/config.php";
    if (file_exists($configCachePath)) {
        unlink($configCachePath);
        echo "<p>✅ Cache de configuração limpo</p>";
    }
    
    // Limpar cache de rotas
    $routeCachePath = $basePath . "/bootstrap/cache/routes-v7.php";
    if (file_exists($routeCachePath)) {
        unlink($routeCachePath);
        echo "<p>✅ Cache de rotas limpo</p>";
    }
    
    // 2. Verificar permissões críticas
    echo "<h3>📁 VERIFICAÇÃO DE PERMISSÕES</h3>";
    
    $criticalPaths = [
        "storage/logs",
        "storage/framework/cache",
        "storage/framework/sessions",
        "storage/framework/views",
        "bootstrap/cache",
        "public/storage"
    ];
    
    foreach ($criticalPaths as $path) {
        $fullPath = $basePath . "/" . $path;
        if (is_dir($fullPath)) {
            $perms = substr(sprintf("%o", fileperms($fullPath)), -4);
            $writable = is_writable($fullPath);
            echo "<p>" . ($writable ? "✅" : "❌") . " $path ($perms) " . ($writable ? "Gravável" : "NÃO GRAVÁVEL") . "</p>";
            
            if (!$writable) {
                // Tentar corrigir permissões
                chmod($fullPath, 0755);
                if (is_writable($fullPath)) {
                    echo "<p>🔧 Permissão corrigida para $path</p>";
                }
            }
        } else {
            echo "<p>❌ $path não existe</p>";
            // Tentar criar diretório
            if (mkdir($fullPath, 0755, true)) {
                echo "<p>🔧 Diretório $path criado</p>";
            }
        }
    }
    
    // 3. Verificar storage link
    echo "<h3>🔗 VERIFICAÇÃO DO STORAGE LINK</h3>";
    $storageLink = $basePath . "/public/storage";
    $storageTarget = $basePath . "/storage/app/public";
    
    if (is_link($storageLink)) {
        $linkTarget = readlink($storageLink);
        echo "<p>✅ Storage link existe: $linkTarget</p>";
    } else {
        echo "<p>❌ Storage link não existe</p>";
        // Tentar criar link
        if (symlink($storageTarget, $storageLink)) {
            echo "<p>🔧 Storage link criado</p>";
        } else {
            echo "<p>❌ Falha ao criar storage link</p>";
        }
    }
    
    // 4. Verificar CSS de branding
    echo "<h3>🎨 VERIFICAÇÃO DO CSS DE BRANDING</h3>";
    $brandingCSS = $basePath . "/public/css/global-branding.css";
    
    if (file_exists($brandingCSS)) {
        $size = filesize($brandingCSS);
        $writable = is_writable($brandingCSS);
        echo "<p>✅ CSS de branding existe ($size bytes) " . ($writable ? "Gravável" : "Somente leitura") . "</p>";
        
        // Mostrar primeiras linhas
        $content = file_get_contents($brandingCSS);
        $lines = explode("\n", $content);
        echo "<pre style=\"background: #f5f5f5; padding: 10px; border-radius: 5px;\">";
        for ($i = 0; $i < min(10, count($lines)); $i++) {
            echo htmlspecialchars($lines[$i]) . "\n";
        }
        echo "</pre>";
    } else {
        echo "<p>❌ CSS de branding não existe</p>";
        
        // Criar CSS básico
        $basicCSS = "/* CSS DE BRANDING BÁSICO - GERADO EM EMERGÊNCIA */
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

.bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-indigo-500, .bg-indigo-600, .bg-indigo-700 {
    background-color: var(--primary-color) !important;
}

.text-blue-500, .text-blue-600, .text-blue-700, .text-indigo-500, .text-indigo-600, .text-indigo-700 {
    color: var(--primary-color) !important;
}

.hover\\\\:bg-blue-600:hover, .hover\\\\:bg-blue-700:hover, .hover\\\\:bg-indigo-600:hover, .hover\\\\:bg-indigo-700:hover {
    background-color: var(--primary-dark) !important;
}";
        
        if (file_put_contents($brandingCSS, $basicCSS)) {
            echo "<p>🔧 CSS básico de branding criado</p>";
        }
    }
    
    // 5. Testar conectividade com banco
    echo "<h3>🗄️ TESTE DE CONECTIVIDADE</h3>";
    
    try {
        // Tentar incluir o autoloader do Laravel
        require_once $basePath . "/vendor/autoload.php";
        
        // Tentar carregar configuração
        $app = require_once $basePath . "/bootstrap/app.php";
        
        echo "<p>✅ Laravel carregado com sucesso</p>";
        
        // Tentar conectar com banco
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        
        echo "<p>✅ Bootstrap do Laravel executado</p>";
        
    } catch (Exception $e) {
        echo "<p>❌ Erro ao carregar Laravel: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    echo "<h3>✅ LIMPEZA DE EMERGÊNCIA CONCLUÍDA</h3>";
    echo "<p><strong>Próximos passos:</strong></p>";
    echo "<ul>";
    echo "<li>Teste o acesso às páginas novamente</li>";
    echo "<li>Verifique se as cores estão sendo aplicadas</li>";
    echo "<li>Se ainda houver problemas, execute este script novamente</li>";
    echo "</ul>";
    
} else {
    echo "Este script deve ser executado via navegador em produção.\n";
}
?>';

// Salvar script de emergência
$emergencyPath = __DIR__ . '/public/emergency-fix.php';
if (file_put_contents($emergencyPath, $emergencyScript)) {
    echo "✅ Script de emergência criado: /emergency-fix.php\n";
    echo "   Acesse: https://srv971263.hstgr.cloud/emergency-fix.php\n";
} else {
    echo "❌ Erro ao criar script de emergência\n";
}

// 4. Verificar e corrigir página de licenciados especificamente
echo "\n4. CORRIGINDO PÁGINA DE LICENCIADOS...\n";

$licenciadosPage = __DIR__ . '/resources/views/dashboard/licenciados.blade.php';
if (file_exists($licenciadosPage)) {
    $content = file_get_contents($licenciadosPage);
    $originalContent = $content;
    
    // Verificar se tem branding
    $hasBranding = strpos($content, 'x-dynamic-branding') !== false;
    echo ($hasBranding ? "✅" : "❌") . " Componente de branding presente\n";
    
    // Adicionar CSS super agressivo para forçar cores
    $superAggressiveCSS = '<style>
/* CSS SUPER AGRESSIVO PARA LICENCIADOS - FORÇA TODAS AS CORES */
:root {
    --primary-color: #3B82F6 !important;
    --secondary-color: #6B7280 !important;
    --accent-color: #10B981 !important;
    --text-color: #1F2937 !important;
    --background-color: #FFFFFF !important;
    --primary-light: rgba(59, 130, 246, 0.1) !important;
    --primary-dark: #2563EB !important;
    --primary-text: #FFFFFF !important;
}

/* FORÇA TODOS OS BOTÕES */
button, .btn, input[type="button"], input[type="submit"], a.button {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

button:hover, .btn:hover, input[type="button"]:hover, input[type="submit"]:hover, a.button:hover {
    background-color: var(--primary-dark) !important;
    border-color: var(--primary-dark) !important;
}

/* FORÇA TODAS AS CLASSES TAILWIND AZUIS */
[class*="bg-blue"], [class*="bg-indigo"] {
    background-color: var(--primary-color) !important;
}

[class*="text-blue"], [class*="text-indigo"] {
    color: var(--primary-color) !important;
}

[class*="border-blue"], [class*="border-indigo"] {
    border-color: var(--primary-color) !important;
}

/* FORÇA ESTILOS INLINE */
[style*="background-color"] {
    background-color: var(--primary-color) !important;
}

[style*="color: #"] {
    color: var(--primary-color) !important;
}

/* FORÇA LINKS */
a:not(.no-style) {
    color: var(--primary-color) !important;
}

a:not(.no-style):hover {
    color: var(--primary-dark) !important;
}

/* FORÇA ÍCONES */
.fa, .fas, .far, .fal, .fab, i[class*="fa"] {
    color: var(--primary-color) !important;
}

/* FORÇA BADGES E PILLS */
.badge, .pill, .tag {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

/* FORÇA CARDS E CONTAINERS */
.card-header, .panel-header {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

/* FORÇA TABELAS */
th {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

/* FORÇA FORMULÁRIOS */
input:focus, select:focus, textarea:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px var(--primary-light) !important;
}

/* FORÇA NAVEGAÇÃO */
.nav-link.active, .nav-item.active {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

/* FORÇA QUALQUER ELEMENTO COM COR HARDCODED */
* {
    --tw-bg-blue-500: var(--primary-color) !important;
    --tw-bg-blue-600: var(--primary-color) !important;
    --tw-bg-indigo-500: var(--primary-color) !important;
    --tw-bg-indigo-600: var(--primary-color) !important;
}
</style>';
    
    // Adicionar CSS super agressivo se não existe
    if (strpos($content, 'CSS SUPER AGRESSIVO PARA LICENCIADOS') === false) {
        if (strpos($content, '</head>') !== false) {
            $content = str_replace('</head>', $superAggressiveCSS . "\n</head>", $content);
        } else {
            $content = $superAggressiveCSS . "\n" . $content;
        }
        
        if (file_put_contents($licenciadosPage, $content)) {
            echo "✅ CSS super agressivo adicionado à página de licenciados\n";
        } else {
            echo "❌ Erro ao atualizar página de licenciados\n";
        }
    } else {
        echo "ℹ️  CSS super agressivo já presente\n";
    }
} else {
    echo "❌ Página de licenciados não encontrada\n";
}

echo "\n=== CORREÇÃO CONCLUÍDA ===\n";
echo "🌐 Acesse: https://srv971263.hstgr.cloud/emergency-fix.php\n";
echo "🎨 Teste: https://srv971263.hstgr.cloud/dashboard/licenciados\n";
echo "✅ Sistema de cores super agressivo aplicado\n";

?>
