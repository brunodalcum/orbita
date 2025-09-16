<?php
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

.hover\\:bg-blue-600:hover, .hover\\:bg-blue-700:hover, .hover\\:bg-indigo-600:hover, .hover\\:bg-indigo-700:hover {
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
?>