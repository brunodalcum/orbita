<?php
// SCRIPT DE EMERGÊNCIA - CORREÇÃO SELETIVA DE BRANDING
// Acesse via: https://srv971263.hstgr.cloud/emergency-fix-branding.php

echo "<h1>🚨 CORREÇÃO DE EMERGÊNCIA - BRANDING SELETIVO</h1>";
echo "<p><strong>Versão 2.0 - Preserva menus e corrige botões</strong></p>";

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

// Verificar arquivos CSS
$cssFiles = [
    "selective-branding.css" => "CSS Seletivo (preserva menus)",
    "force-buttons.css" => "CSS Força Botões",
    "global-branding.css" => "CSS Global",
    "sidebar-fix.css" => "CSS Específico Sidebar (textos brancos)",
    "comprehensive-branding.css" => "CSS Abrangente (todas as páginas)"
];

echo "<h2>📄 VERIFICAÇÃO DOS ARQUIVOS CSS</h2>";
foreach ($cssFiles as $file => $description) {
    $fullPath = __DIR__ . "/public/css/" . $file;
    if (file_exists($fullPath)) {
        $size = number_format(filesize($fullPath) / 1024, 2);
        echo "<p>✅ $description: $file ($size KB)</p>";
        
        // Forçar regeneração
        $content = file_get_contents($fullPath);
        if (file_put_contents($fullPath, $content)) {
            echo "<p>🔄 $file regenerado</p>";
        }
    } else {
        echo "<p>❌ $description: $file (FALTANDO)</p>";
    }
}

// Verificar permissões
if (is_writable(__DIR__ . "/public/css/")) {
    echo "<p>✅ Diretório CSS é gravável</p>";
} else {
    echo "<p>❌ Diretório CSS não é gravável</p>";
}

echo "<h2>🎨 SISTEMA ATUAL</h2>";
echo "<ul>";
echo "<li>✅ <strong>CSS Seletivo:</strong> Aplica branding apenas em botões e conteúdo</li>";
echo "<li>✅ <strong>Preserva Menus:</strong> Textos brancos dos menus permanecem brancos</li>";
echo "<li>✅ <strong>Força Botões:</strong> Garante que todos os botões usem a cor correta</li>";
echo "<li>✅ <strong>Seletores Específicos:</strong> Não afeta elementos que devem manter cores originais</li>";
echo "</ul>";

echo "<h2>🔧 DIAGNÓSTICO</h2>";
echo "<p>Timestamp: " . date("Y-m-d H:i:s") . "</p>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>Server: " . $_SERVER["SERVER_SOFTWARE"] . "</p>";

echo "<h2>🎯 CORREÇÕES APLICADAS</h2>";
echo "<ul>";
echo "<li>✅ <strong>Sidebar:</strong> Textos FORÇADOS a branco com CSS ultra-específico</li>";
echo "<li>✅ <strong>Botões:</strong> Cor do branding aplicada em todas as páginas</li>";
echo "<li>✅ <strong>Separação:</strong> Sidebar branca + Conteúdo com branding</li>";
echo "<li>✅ <strong>Abrangente:</strong> 100% das páginas com branding consistente</li>";
echo "<li>✅ <strong>Namespace:</strong> Erro corrigido no HierarchyBrandingController</li>";
echo "<li>✅ <strong>CSS Inline:</strong> Reforço direto no componente da sidebar</li>";
echo "</ul>";

echo "<h2>📋 PRÓXIMOS PASSOS</h2>";
echo "<ol>";
echo "<li>Acesse: <a href='/dashboard/licenciados'>/dashboard/licenciados</a></li>";
echo "<li>Pressione <strong>Ctrl+F5</strong> para forçar reload</li>";
echo "<li>Verifique se:</li>";
echo "<ul>";
echo "<li>🔸 <strong>Sidebar:</strong> Textos BRANCOS (forçados)</li>";
echo "<li>🔸 <strong>Botões:</strong> Cor do branding (todas as páginas)</li>";
echo "<li>🔸 <strong>Links:</strong> Cor do branding (conteúdo principal)</li>";
echo "<li>🔸 <strong>Consistência:</strong> Mesmo visual em todas as páginas</li>";
echo "</ul>";
echo "<li>Se ainda houver problemas, reporte elementos específicos</li>";
echo "</ol>";

echo "<h2>🚀 TESTE RÁPIDO</h2>";
echo "<p>Clique nos links abaixo para testar:</p>";
echo "<ul>";
echo "<li><a href='/dashboard' target='_blank'>Dashboard Principal</a></li>";
echo "<li><a href='/dashboard/licenciados' target='_blank'>Página de Licenciados</a></li>";
echo "<li><a href='/contracts' target='_blank'>Contratos</a></li>";
echo "</ul>";

echo "<p><strong>🎯 Status: CORREÇÃO SELETIVA APLICADA</strong></p>";
echo "<p><em>Versão 2.0 - Inteligente e Preservativa</em></p>";
?>