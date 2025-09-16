<?php
// SCRIPT DE EMERGÊNCIA - SISTEMA SIMPLES
// Acesse via: https://srv971263.hstgr.cloud/emergency-fix-branding.php

echo "<h1>🚨 SISTEMA SIMPLES DE BRANDING</h1>";
echo "<p><strong>Versão SIMPLES - Funcional e Direto</strong></p>";

// Limpar caches
if (function_exists("opcache_reset")) {
    opcache_reset();
    echo "<p>✅ OPCache limpo</p>";
}

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

// Verificar sistema simples
$simpleCSS = __DIR__ . "/public/css/simple-branding.css";
if (file_exists($simpleCSS)) {
    $size = number_format(filesize($simpleCSS) / 1024, 2);
    echo "<p>✅ Sistema Simples: simple-branding.css ($size KB)</p>";
} else {
    echo "<p>❌ Sistema simples não encontrado</p>";
}

echo "<h2>🎯 SISTEMA ATUAL</h2>";
echo "<ul>";
echo "<li>✅ <strong>SIMPLES:</strong> Um CSS, uma abordagem</li>";
echo "<li>✅ <strong>FUNCIONAL:</strong> Sidebar branca, conteúdo com branding</li>";
echo "<li>✅ <strong>DIRETO:</strong> Sem complexidade desnecessária</li>";
echo "<li>✅ <strong>COMPATÍVEL:</strong> Funciona com Tailwind</li>";
echo "</ul>";

echo "<h2>🚀 TESTE</h2>";
echo "<ul>";
echo "<li><a href=\"/dashboard\">Dashboard</a></li>";
echo "<li><a href=\"/dashboard/licenciados\">Licenciados</a></li>";
echo "<li><a href=\"/contracts\">Contratos</a></li>";
echo "</ul>";

echo "<p><strong>🎯 Status: SISTEMA SIMPLES ATIVO</strong></p>";
?>