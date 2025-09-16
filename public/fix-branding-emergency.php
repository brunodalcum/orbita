<?php
// Script de emergência para aplicar branding em produção
// Acesse via: /fix-branding-emergency.php

header("Content-Type: text/html; charset=utf-8");

echo "<h1>🎨 Correção de Branding - Produção</h1>";

// Limpar caches
if (function_exists("opcache_reset")) {
    opcache_reset();
    echo "<p>✅ OPCache limpo</p>";
}

// Verificar CSS
$cssPath = __DIR__ . "/css/global-branding.css";
if (file_exists($cssPath)) {
    echo "<p>✅ CSS Global encontrado (" . filesize($cssPath) . " bytes)</p>";
} else {
    echo "<p>❌ CSS Global não encontrado</p>";
}

// Forçar CSS inline
echo "<style>
:root {
    --primary-color: #3B82F6 !important;
    --secondary-color: #6B7280 !important;
    --accent-color: #10B981 !important;
    --primary-dark: #2f68c4 !important;
    --primary-text: #FFFFFF !important;
}

.bg-blue-500, .bg-blue-600, .bg-blue-700,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700 {
    background-color: var(--primary-color) !important;
}

.text-blue-500, .text-blue-600, .text-blue-700,
.text-indigo-500, .text-indigo-600, .text-indigo-700 {
    color: var(--primary-color) !important;
}

button[class*=\"blue\"], button[class*=\"indigo\"] {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}
</style>";

echo "<p>✅ CSS de emergência aplicado</p>";
echo "<p><a href=\"/dashboard\">🔗 Testar Dashboard</a></p>";
echo "<p><a href=\"/dashboard/licenciados\">🔗 Testar Licenciados</a></p>";
?>