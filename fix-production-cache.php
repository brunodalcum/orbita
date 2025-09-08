<?php

/**
 * Script para limpar todos os caches em produção após deploy
 * Resolve erro: Route [contracts.generate.index] not defined.
 */

echo "🔧 Limpando caches de produção...\n\n";

// Comandos para limpar caches
$commands = [
    'php artisan route:clear' => 'Limpando cache de rotas',
    'php artisan config:clear' => 'Limpando cache de configuração',
    'php artisan view:clear' => 'Limpando cache de views',
    'php artisan cache:clear' => 'Limpando cache da aplicação',
    'php artisan optimize:clear' => 'Limpando otimizações',
    'php artisan route:cache' => 'Recriando cache de rotas',
    'php artisan config:cache' => 'Recriando cache de configuração',
];

foreach ($commands as $command => $description) {
    echo "📋 $description...\n";
    echo "   Comando: $command\n";
    
    $output = [];
    $returnCode = 0;
    exec($command . ' 2>&1', $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "   ✅ Sucesso!\n";
        if (!empty($output)) {
            echo "   📄 Output: " . implode("\n   📄 ", $output) . "\n";
        }
    } else {
        echo "   ❌ Erro (código: $returnCode)\n";
        if (!empty($output)) {
            echo "   📄 Erro: " . implode("\n   📄 ", $output) . "\n";
        }
    }
    echo "\n";
}

// Verificar se as rotas estão funcionando
echo "🔍 Verificando rotas de contratos...\n";
$routeOutput = [];
exec('php artisan route:list | grep generate', $routeOutput);

if (!empty($routeOutput)) {
    echo "✅ Rotas de generate encontradas:\n";
    foreach ($routeOutput as $route) {
        echo "   📋 $route\n";
    }
} else {
    echo "❌ Nenhuma rota de generate encontrada!\n";
    echo "   💡 Execute: php artisan route:list para ver todas as rotas\n";
}

echo "\n🎊 Processo concluído!\n";
echo "💡 Se o erro persistir, verifique:\n";
echo "   1. Se o arquivo routes/web.php foi atualizado corretamente\n";
echo "   2. Se há algum erro de sintaxe no arquivo de rotas\n";
echo "   3. Se as permissões de escrita estão corretas\n";
echo "   4. Reinicie o servidor web se necessário\n";

?>
