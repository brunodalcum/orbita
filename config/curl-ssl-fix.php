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

