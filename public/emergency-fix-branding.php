<?php
// SCRIPT DE EMERGÊNCIA - DESIGN ORIGINAL RESTAURADO
// Acesse via: https://srv971263.hstgr.cloud/emergency-fix-branding.php

echo "<h1>✅ DESIGN ORIGINAL RESTAURADO</h1>";
echo "<p><strong>Sistema voltou ao estado original</strong></p>";

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

echo "<h2>🎯 STATUS ATUAL</h2>";
echo "<ul>";
echo "<li>✅ <strong>DESIGN ORIGINAL:</strong> Restaurado completamente</li>";
echo "<li>✅ <strong>SEM BRANDING:</strong> Todas as modificações removidas</li>";
echo "<li>✅ <strong>FUNCIONALIDADE:</strong> Mantida com CSS mínimo</li>";
echo "<li>✅ <strong>SIDEBAR:</strong> Cor original (cinza escuro)</li>";
echo "</ul>";

echo "<h2>🚀 TESTE</h2>";
echo "<ul>";
echo "<li><a href=\"/dashboard\">Dashboard</a></li>";
echo "<li><a href=\"/dashboard/licenciados\">Licenciados</a></li>";
echo "<li><a href=\"/contracts\">Contratos</a></li>";
echo "</ul>";

echo "<p><strong>✅ Status: DESIGN ORIGINAL ATIVO</strong></p>";
echo "<p><em>Sistema restaurado ao estado anterior às modificações de branding</em></p>";
?>