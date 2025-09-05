<?php

// Script simples para diagnosticar sidebar em produção
// Execute: php diagnose-sidebar-simple.php

echo "🔍 Diagnosticando sidebar em PRODUÇÃO (método simples)...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Comandos para executar
$commands = [
    'php artisan tinker --execute="echo \"Verificando usuário...\"; \$user = App\Models\User::where(\"email\", \"admin@dspay.com.br\")->with(\"role\")->first(); if (\$user) { echo \"Usuário: \" . \$user->name . \" - Role: \" . (\$user->role ? \$user->role->display_name : \"Nenhum\"); } else { echo \"Usuário não encontrado\"; }"',
    
    'php artisan tinker --execute="echo \"Verificando permissões...\"; \$user = App\Models\User::where(\"email\", \"admin@dspay.com.br\")->first(); if (\$user) { \$permissions = \$user->getPermissions(); echo \"Permissões: \" . \$permissions->count(); } else { echo \"Usuário não encontrado\"; }"',
    
    'php artisan tinker --execute="echo \"Verificando arquivos do sidebar...\"; if (file_exists(\"resources/views/components/dynamic-sidebar.blade.php\")) { echo \"✅ View do sidebar existe\"; } else { echo \"❌ View do sidebar não existe\"; } if (file_exists(\"app/View/Components/DynamicSidebar.php\")) { echo \" - ✅ Componente existe\"; } else { echo \" - ❌ Componente não existe\"; }"',
    
    'php artisan tinker --execute="echo \"Testando componente...\"; \$user = App\Models\User::where(\"email\", \"admin@dspay.com.br\")->first(); if (\$user) { try { \$component = new App\View\Components\DynamicSidebar(\$user); \$view = \$component->render(); echo \"✅ Componente funcionando\"; } catch (Exception \$e) { echo \"❌ Erro: \" . \$e->getMessage(); } } else { echo \"❌ Usuário não encontrado\"; }"',
    
    'php artisan tinker --execute="echo \"Verificando cache...\"; \$cacheDir = \"storage/framework/views\"; if (is_dir(\$cacheDir)) { \$files = scandir(\$cacheDir); \$cacheFiles = array_filter(\$files, function(\$file) { return \$file !== \".\" && \$file !== \"..\" && strpos(\$file, \"dynamic-sidebar\") !== false; }); echo \"Cache do sidebar: \" . count(\$cacheFiles) . \" arquivos\"; } else { echo \"❌ Diretório de cache não existe\"; }"'
];

// 3. Executar comandos
foreach ($commands as $i => $command) {
    echo "\n🔄 Executando comando " . ($i + 1) . "...\n";
    echo "Comando: " . substr($command, 0, 100) . "...\n";
    
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

echo "\n🎉 Diagnóstico concluído!\n";
echo "✅ Se houver problemas, execute: php recreate-sidebar-production.php\n";
