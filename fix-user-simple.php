<?php

// Script simples para corrigir usuário em produção
// Execute: php fix-user-simple.php

echo "🔧 Corrigindo usuário em PRODUÇÃO (método simples)...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Executar comando via Artisan Tinker
echo "🔄 Executando correção via Artisan Tinker...\n";

$tinkerCommand = '
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

echo "🔍 Verificando usuário test@example.com..." . PHP_EOL;
$testUser = User::where("email", "test@example.com")->first();

if ($testUser) {
    echo "✅ Usuário encontrado: " . $testUser->name . PHP_EOL;
    
    echo "🔄 Atualizando para Super Admin..." . PHP_EOL;
    $superAdminRole = Role::where("name", "super_admin")->first();
    
    if (!$superAdminRole) {
        echo "❌ Role Super Admin não encontrado!" . PHP_EOL;
        exit(1);
    }
    
    $testUser->name = "Super Admin";
    $testUser->email = "admin@dspay.com.br";
    $testUser->password = Hash::make("admin123456");
    $testUser->role_id = $superAdminRole->id;
    $testUser->is_active = true;
    $testUser->email_verified_at = now();
    $testUser->save();
    
    echo "✅ Usuário atualizado para Super Admin!" . PHP_EOL;
    echo "📧 Email: admin@dspay.com.br" . PHP_EOL;
    echo "🔑 Senha: admin123456" . PHP_EOL;
    
} else {
    echo "❌ Usuário test@example.com não encontrado!" . PHP_EOL;
    
    echo "🆕 Criando novo usuário Super Admin..." . PHP_EOL;
    $superAdminRole = Role::where("name", "super_admin")->first();
    
    if (!$superAdminRole) {
        echo "❌ Role Super Admin não encontrado!" . PHP_EOL;
        exit(1);
    }
    
    $user = new User();
    $user->name = "Super Admin";
    $user->email = "admin@dspay.com.br";
    $user->password = Hash::make("admin123456");
    $user->role_id = $superAdminRole->id;
    $user->is_active = true;
    $user->email_verified_at = now();
    $user->save();
    
    echo "✅ Usuário Super Admin criado!" . PHP_EOL;
    echo "📧 Email: admin@dspay.com.br" . PHP_EOL;
    echo "🔑 Senha: admin123456" . PHP_EOL;
}

echo "📋 Verificando resultado..." . PHP_EOL;
$adminUser = User::where("email", "admin@dspay.com.br")->with("role")->first();
if ($adminUser) {
    echo "✅ Usuário Super Admin configurado:" . PHP_EOL;
    echo "- ID: " . $adminUser->id . PHP_EOL;
    echo "- Nome: " . $adminUser->name . PHP_EOL;
    echo "- Email: " . $adminUser->email . PHP_EOL;
    echo "- Role: " . ($adminUser->role ? $adminUser->role->display_name : "Nenhum") . PHP_EOL;
    echo "- Status: " . ($adminUser->is_active ? "Ativo" : "Inativo") . PHP_EOL;
    echo "- Email verificado: " . ($adminUser->email_verified_at ? "Sim" : "Não") . PHP_EOL;
    
    $permissions = $adminUser->getPermissions();
    echo "- Permissões: " . $permissions->count() . " permissões" . PHP_EOL;
    
    echo "🧪 Testando login..." . PHP_EOL;
    if (Hash::check("admin123456", $adminUser->password)) {
        echo "✅ Login testado com sucesso!" . PHP_EOL;
    } else {
        echo "❌ Erro no teste de login!" . PHP_EOL;
    }
}

echo "📋 Todos os usuários:" . PHP_EOL;
$allUsers = User::with("role")->get();
foreach ($allUsers as $u) {
    echo "- " . $u->name . " (" . $u->email . ") - " . 
         ($u->role ? $u->role->display_name : "Sem role") . 
         " - " . ($u->is_active ? "Ativo" : "Inativo") . PHP_EOL;
}

echo "🎉 SUCESSO! Usuário Super Admin configurado!" . PHP_EOL;
echo "✅ Login: https://srv971263.hstgr.cloud/login" . PHP_EOL;
echo "📧 Email: admin@dspay.com.br" . PHP_EOL;
echo "🔑 Senha: admin123456" . PHP_EOL;
echo "⚠️ ALTERE A SENHA APÓS O PRIMEIRO LOGIN!" . PHP_EOL;
';

// 3. Executar comando
$output = shell_exec("php artisan tinker --execute=\"$tinkerCommand\" 2>&1");
echo "Resultado:\n$output\n";

echo "\n🎉 Script executado com sucesso!\n";
echo "✅ Teste o login em: https://srv971263.hstgr.cloud/login\n";
echo "📧 Email: admin@dspay.com.br\n";
echo "🔑 Senha: admin123456\n";
