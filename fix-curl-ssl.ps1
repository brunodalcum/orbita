Write-Host "🔧 Corrigindo problema do cURL SSL..." -ForegroundColor Yellow

# 1. Verificar se estamos no diretório correto
if (-not (Test-Path "public\index.php")) {
    Write-Host "❌ Erro: Execute este script na raiz do projeto Laravel" -ForegroundColor Red
    exit 1
}

# 2. Limpar todos os caches do Laravel
Write-Host "🧹 Limpando caches do Laravel..." -ForegroundColor Yellow
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# 3. Verificar se o arquivo curl-ssl-fix.php existe
if (Test-Path "config\curl-ssl-fix.php") {
    Write-Host "✅ Arquivo curl-ssl-fix.php encontrado" -ForegroundColor Green
    
    # 4. Verificar se há funções duplicadas
    $content = Get-Content "config\curl-ssl-fix.php" -Raw
    $functionCount = ([regex]::Matches($content, "function configureCurlSSL")).Count
    
    if ($functionCount -gt 1) {
        Write-Host "⚠️  Função configureCurlSSL encontrada $functionCount vezes" -ForegroundColor Yellow
        Write-Host "🔧 Corrigindo arquivo..." -ForegroundColor Yellow
        
        # Substituir o conteúdo do arquivo
        $correctedContent = @'
<?php

// Solução para erro de certificado SSL no cURL (Windows/XAMPP)
// Adicione este arquivo ao seu projeto e configure o cURL

// Verificar se estamos no Windows
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    
    // Configurar certificado SSL para cURL
    $certPath = 'C:\xampp\php\extras\ssl\cacert.pem';
    
    // Se o certificado não existir no XAMPP, usar um alternativo
    if (!file_exists($certPath)) {
        $certPath = 'C:\xampp\php\extras\ssl\cacert.pem';
        
        // Se ainda não existir, criar diretório e baixar certificado
        if (!file_exists($certPath)) {
            $sslDir = dirname($certPath);
            if (!is_dir($sslDir)) {
                mkdir($sslDir, 0755, true);
            }
            
            // URL do certificado CA do Mozilla
            $caUrl = 'https://curl.se/ca/cacert.pem';
            
            // Baixar certificado
            $certContent = file_get_contents($caUrl);
            if ($certContent !== false) {
                file_put_contents($certPath, $certContent);
            }
        }
    }
    
    // Configurar cURL para usar o certificado
    if (file_exists($certPath)) {
        // Definir variável de ambiente
        putenv("CURL_CA_BUNDLE={$certPath}");
        
        // Configurar cURL globalmente
        if (function_exists('curl_init')) {
            // Esta configuração será aplicada a todas as instâncias do cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_CAINFO, $certPath);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_close($ch);
        }
    }
}

// Função para configurar cURL em uma instância específica
if (!function_exists('configureCurlSSL')) {
    function configureCurlSSL($ch) {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $certPath = 'C:\xampp\php\extras\ssl\cacert.pem';
            
            if (file_exists($certPath)) {
                curl_setopt($ch, CURLOPT_CAINFO, $certPath);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            } else {
                // Fallback: desabilitar verificação SSL (NÃO recomendado para produção)
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            }
        }
        
        return $ch;
    }
}
'@
        
        Set-Content "config\curl-ssl-fix.php" $correctedContent
        Write-Host "✅ Arquivo corrigido com sucesso" -ForegroundColor Green
    } else {
        Write-Host "✅ Arquivo curl-ssl-fix.php está correto" -ForegroundColor Green
    }
} else {
    Write-Host "❌ Arquivo curl-ssl-fix.php não encontrado" -ForegroundColor Red
}

# 5. Otimizar para produção
Write-Host "⚡ Otimizando para produção..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache

Write-Host "✅ Script de correção do cURL SSL concluído!" -ForegroundColor Green
Write-Host "🎯 Agora você pode executar o deploy normalmente" -ForegroundColor Cyan
