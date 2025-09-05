<?php

// Script para remover sidebar dinâmico das views
// Execute: php remove-dynamic-sidebar.php

echo "🔧 Removendo sidebar dinâmico das views...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Lista de views para verificar
$views = [
    'resources/views/dashboard.blade.php',
    'resources/views/dashboard/licenciados.blade.php',
    'resources/views/dashboard/users/index.blade.php',
    'resources/views/dashboard/users/create.blade.php',
    'resources/views/dashboard/users/edit.blade.php',
    'resources/views/dashboard/users/show.blade.php',
    'resources/views/dashboard/operacoes.blade.php',
    'resources/views/dashboard/planos.blade.php',
    'resources/views/dashboard/adquirentes.blade.php',
    'resources/views/dashboard/agenda.blade.php',
    'resources/views/dashboard/leads.blade.php',
    'resources/views/dashboard/marketing/index.blade.php',
    'resources/views/dashboard/marketing/emails.blade.php',
    'resources/views/dashboard/marketing/campanhas.blade.php',
    'resources/views/dashboard/configuracoes.blade.php',
    'resources/views/dashboard/licenciado-gerenciar.blade.php'
];

// 3. Verificar e corrigir cada view
foreach ($views as $view) {
    if (file_exists($view)) {
        echo "\n📄 Verificando: $view\n";
        
        $content = file_get_contents($view);
        $originalContent = $content;
        
        // Remover sidebar dinâmico
        $content = str_replace('<x-dynamic-sidebar />', '', $content);
        $content = str_replace('<x-dynamic-sidebar/>', '', $content);
        $content = str_replace('@include(\'components.dynamic-sidebar\')', '', $content);
        $content = str_replace('@include("components.dynamic-sidebar")', '', $content);
        
        // Se houve mudança, salvar
        if ($content !== $originalContent) {
            file_put_contents($view, $content);
            echo "✅ Corrigido: $view\n";
        } else {
            echo "✅ Já está correto: $view\n";
        }
        
    } else {
        echo "❌ Não encontrado: $view\n";
    }
}

// 4. Verificar se há outras referências ao sidebar dinâmico
echo "\n🔍 Verificando outras referências...\n";

$searchCommand = 'grep -r "dynamic-sidebar" resources/views/ 2>/dev/null || echo "Nenhuma referência encontrada"';
$output = shell_exec($searchCommand);
echo "Resultado: $output\n";

// 5. Limpar cache
echo "\n🗂️ Limpando cache...\n";

$commands = [
    'php artisan view:clear',
    'php artisan config:clear',
    'php artisan route:clear'
];

foreach ($commands as $command) {
    echo "\n🔄 Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

echo "\n🎉 Sidebar dinâmico removido das views!\n";
echo "✅ Teste as rotas em: https://srv971263.hstgr.cloud/dashboard\n";
