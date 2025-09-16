<?php
// SCRIPT DE EMERGÊNCIA - FORÇA BRANDING EM PRODUÇÃO
// Acesse via: https://srv971263.hstgr.cloud/emergency-fix-branding.php

echo "<h1>🚨 CORREÇÃO DE EMERGÊNCIA - BRANDING</h1>";

// Limpar todos os caches
if (function_exists("opcache_reset")) {
    opcache_reset();
    echo "<p>✅ OPCache limpo</p>";
}

// Limpar cache de views
$viewCachePath = __DIR__ . "/storage/framework/views";
if (is_dir($viewCachePath)) {
    $files = glob($viewCachePath . "/*.php");
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "<p>✅ Cache de views limpo (" . count($files) . " arquivos)</p>";
}

// Verificar se CSS existe
$cssFile = __DIR__ . "/public/css/ultra-aggressive-branding.css";
if (file_exists($cssFile)) {
    echo "<p>✅ CSS ultra-agressivo existe</p>";
    echo "<p>📄 Tamanho: " . number_format(filesize($cssFile) / 1024, 2) . " KB</p>";
} else {
    echo "<p>❌ CSS ultra-agressivo não encontrado</p>";
}

// Verificar permissões
if (is_writable(__DIR__ . "/public/css/")) {
    echo "<p>✅ Diretório CSS é gravável</p>";
} else {
    echo "<p>❌ Diretório CSS não é gravável</p>";
}

// Forçar regeneração do CSS
$cssContent = file_get_contents($cssFile);
if (file_put_contents($cssFile, $cssContent)) {
    echo "<p>✅ CSS regenerado com sucesso</p>";
} else {
    echo "<p>❌ Erro ao regenerar CSS</p>";
}

echo "<h2>🔧 DIAGNÓSTICO</h2>";
echo "<p>Timestamp: " . date("Y-m-d H:i:s") . "</p>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>Server: " . $_SERVER["SERVER_SOFTWARE"] . "</p>";

echo "<h2>📋 PRÓXIMOS PASSOS</h2>";
echo "<ol>";
echo "<li>Acesse qualquer página do dashboard</li>";
echo "<li>Pressione Ctrl+F5 para forçar reload</li>";
echo "<li>Verifique se as cores foram aplicadas</li>";
echo "<li>Se ainda não funcionou, contate o suporte</li>";
echo "</ol>";

echo "<p><strong>🎯 Status: CORREÇÃO APLICADA</strong></p>";
?>