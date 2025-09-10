<?php
/**
 * Script para corrigir erro do Laravel Pail em produção
 * 
 * O Laravel Pail é um pacote de desenvolvimento que não deve ser carregado em produção.
 * Este script remove os caches que podem estar causando o erro.
 */

echo "🔧 Corrigindo erro do Laravel Pail em produção...\n\n";

// 1. Limpar todos os caches
echo "1. Limpando caches do Laravel...\n";
$commands = [
    'php artisan config:clear',
    'php artisan cache:clear', 
    'php artisan view:clear',
    'php artisan route:clear',
    'php artisan optimize:clear'
];

foreach ($commands as $command) {
    echo "   Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "   Resultado: " . trim($output) . "\n";
    }
}

// 2. Remover caches específicos que podem conter referências ao Pail
echo "\n2. Removendo caches de serviços e pacotes...\n";
$cacheFiles = [
    'bootstrap/cache/services.php',
    'bootstrap/cache/packages.php',
    'bootstrap/cache/config.php'
];

foreach ($cacheFiles as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "   ✅ Removido: $file\n";
    } else {
        echo "   ℹ️  Não encontrado: $file\n";
    }
}

// 3. Verificar se o Pail está corretamente em require-dev
echo "\n3. Verificando configuração do composer...\n";
if (file_exists('composer.json')) {
    $composer = json_decode(file_get_contents('composer.json'), true);
    
    if (isset($composer['require']['laravel/pail'])) {
        echo "   ❌ PROBLEMA: Laravel Pail está em 'require' (produção)\n";
        echo "   💡 SOLUÇÃO: Mover para 'require-dev'\n";
    } elseif (isset($composer['require-dev']['laravel/pail'])) {
        echo "   ✅ OK: Laravel Pail está corretamente em 'require-dev'\n";
    } else {
        echo "   ℹ️  Laravel Pail não encontrado no composer.json\n";
    }
}

// 4. Recriar autoload
echo "\n4. Recriando autoload do composer...\n";
$output = shell_exec('composer dump-autoload --optimize 2>&1');
echo "   Resultado: " . trim($output) . "\n";

// 5. Verificar se há providers personalizados carregando o Pail
echo "\n5. Verificando providers...\n";
$providerFiles = [
    'config/app.php',
    'bootstrap/providers.php'
];

foreach ($providerFiles as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'PailServiceProvider') !== false) {
            echo "   ❌ PROBLEMA: PailServiceProvider encontrado em $file\n";
        } else {
            echo "   ✅ OK: Nenhuma referência ao Pail em $file\n";
        }
    }
}

echo "\n✅ Correção concluída!\n";
echo "\n📋 Próximos passos:\n";
echo "   1. Faça upload deste projeto para produção\n";
echo "   2. Execute este script em produção: php fix-pail-production.php\n";
echo "   3. Teste o acesso ao site\n";

echo "\n🔗 URLs para testar:\n";
echo "   - Login: https://srv971263.hstgr.cloud/login\n";
echo "   - Dashboard: https://srv971263.hstgr.cloud/dashboard\n";
echo "   - Agenda: https://srv971263.hstgr.cloud/agenda\n";

echo "\n💡 Dica: O Laravel Pail é apenas para desenvolvimento local.\n";
echo "   Em produção, use os logs padrão do Laravel ou ferramentas como Telescope.\n\n";
