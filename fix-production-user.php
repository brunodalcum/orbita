<?php

// Script para corrigir usuário em produção
// Execute: php fix-production-user.php

echo "🔧 Corrigindo usuário em PRODUÇÃO...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// 3. Importar classes
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

try {
    
    // 4. Verificar usuário existente
    echo "🔍 Verificando usuário test@example.com...\n";
    $testUser = User::where('email', 'test@example.com')->first();
    
    if ($testUser) {
        echo "✅ Usuário encontrado!\n";
        echo "- ID: {$testUser->id}\n";
        echo "- Nome: {$testUser->name}\n";
        echo "- Email: {$testUser->email}\n";
        echo "- Role: " . ($testUser->role ? $testUser->role->display_name : 'Sem role') . "\n";
        echo "- Status: " . ($testUser->is_active ? 'Ativo' : 'Inativo') . "\n";
        
        // 5. Atualizar para Super Admin
        echo "\n🔄 Atualizando para Super Admin...\n";
        
        $superAdminRole = Role::where('name', 'super_admin')->first();
        if (!$superAdminRole) {
            echo "❌ Role Super Admin não encontrado!\n";
            exit(1);
        }
        
        $testUser->name = 'Super Admin';
        $testUser->email = 'admin@dspay.com.br';
        $testUser->password = Hash::make('admin123456');
        $testUser->role_id = $superAdminRole->id;
        $testUser->is_active = true;
        $testUser->email_verified_at = now();
        $testUser->save();
        
        echo "✅ Usuário atualizado para Super Admin!\n";
        
    } else {
        echo "❌ Usuário test@example.com não encontrado!\n";
        
        // 6. Criar novo usuário Super Admin
        echo "🆕 Criando novo usuário Super Admin...\n";
        
        $superAdminRole = Role::where('name', 'super_admin')->first();
        if (!$superAdminRole) {
            echo "❌ Role Super Admin não encontrado!\n";
            exit(1);
        }
        
        $user = new User();
        $user->name = 'Super Admin';
        $user->email = 'admin@dspay.com.br';
        $user->password = Hash::make('admin123456');
        $user->role_id = $superAdminRole->id;
        $user->is_active = true;
        $user->email_verified_at = now();
        $user->save();
        
        echo "✅ Usuário Super Admin criado!\n";
    }
    
    // 7. Verificar resultado
    echo "\n📋 Verificando resultado...\n";
    $adminUser = User::where('email', 'admin@dspay.com.br')->with('role')->first();
    
    if ($adminUser) {
        echo "✅ Usuário Super Admin configurado:\n";
        echo "- ID: {$adminUser->id}\n";
        echo "- Nome: {$adminUser->name}\n";
        echo "- Email: {$adminUser->email}\n";
        echo "- Role: " . ($adminUser->role ? $adminUser->role->display_name : 'Nenhum') . "\n";
        echo "- Status: " . ($adminUser->is_active ? 'Ativo' : 'Inativo') . "\n";
        echo "- Email verificado: " . ($adminUser->email_verified_at ? 'Sim' : 'Não') . "\n";
        
        // Verificar permissões
        $permissions = $adminUser->getPermissions();
        echo "- Permissões: " . $permissions->count() . " permissões\n";
        
        // Testar login
        echo "\n🧪 Testando login...\n";
        if (Hash::check('admin123456', $adminUser->password)) {
            echo "✅ Login testado com sucesso!\n";
        } else {
            echo "❌ Erro no teste de login!\n";
        }
    }
    
    // 8. Listar todos os usuários
    echo "\n📋 Todos os usuários:\n";
    $allUsers = User::with('role')->get();
    foreach ($allUsers as $u) {
        echo "- {$u->name} ({$u->email}) - " . 
             ($u->role ? $u->role->display_name : 'Sem role') . 
             " - " . ($u->is_active ? 'Ativo' : 'Inativo') . "\n";
    }
    
    echo "\n🎉 SUCESSO! Usuário Super Admin configurado!\n";
    echo "✅ Login: https://srv971263.hstgr.cloud/login\n";
    echo "📧 Email: admin@dspay.com.br\n";
    echo "🔑 Senha: admin123456\n";
    echo "⚠️ ALTERE A SENHA APÓS O PRIMEIRO LOGIN!\n";
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    echo "Stack: " . $e->getTraceAsString() . "\n";
}
