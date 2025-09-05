<?php

// Script para corrigir usuário usando comandos shell
// Execute: php fix-user-shell.php

echo "🔧 Corrigindo usuário em PRODUÇÃO (método shell)...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Comandos para executar
$commands = [
    'php artisan tinker --execute="echo \"Verificando usuário test@example.com...\"; \$user = App\Models\User::where(\"email\", \"test@example.com\")->first(); if (\$user) { echo \"Usuário encontrado: \" . \$user->name; } else { echo \"Usuário não encontrado\"; }"',
    
    'php artisan tinker --execute="\$user = App\Models\User::where(\"email\", \"test@example.com\")->first(); if (\$user) { \$role = App\Models\Role::where(\"name\", \"super_admin\")->first(); \$user->name = \"Super Admin\"; \$user->email = \"admin@dspay.com.br\"; \$user->password = Hash::make(\"admin123456\"); \$user->role_id = \$role->id; \$user->is_active = true; \$user->email_verified_at = now(); \$user->save(); echo \"Usuário atualizado para Super Admin!\"; } else { echo \"Usuário não encontrado\"; }"',
    
    'php artisan tinker --execute="\$admin = App\Models\User::where(\"email\", \"admin@dspay.com.br\")->with(\"role\")->first(); if (\$admin) { echo \"Usuário: \" . \$admin->name . \" - Email: \" . \$admin->email . \" - Role: \" . (\$admin->role ? \$admin->role->display_name : \"Nenhum\") . \" - Status: \" . (\$admin->is_active ? \"Ativo\" : \"Inativo\"); } else { echo \"Usuário admin não encontrado\"; }"'
];

// 3. Executar comandos
foreach ($commands as $i => $command) {
    echo "\n🔄 Executando comando " . ($i + 1) . "...\n";
    echo "Comando: $command\n";
    
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

echo "\n🎉 Script executado!\n";
echo "✅ Teste o login em: https://srv971263.hstgr.cloud/login\n";
echo "📧 Email: admin@dspay.com.br\n";
echo "🔑 Senha: admin123456\n";
