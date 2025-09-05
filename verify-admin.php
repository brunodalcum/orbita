<?php

// Script para verificar se o usuário Super Admin existe
// Execute: php verify-admin.php

echo "🔍 Verificando usuário Super Admin...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// 3. Importar classes necessárias
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

try {
    // 4. Verificar conexão com banco
    echo "🔗 Verificando conexão com banco de dados...\n";
    $connection = DB::connection()->getPdo();
    echo "✅ Conexão com banco estabelecida\n";
    
    // 5. Verificar tabelas
    echo "\n📋 Verificando tabelas...\n";
    $tables = ['users', 'roles', 'permissions', 'role_permission'];
    foreach ($tables as $table) {
        try {
            $count = DB::table($table)->count();
            echo "✅ Tabela $table: $count registros\n";
        } catch (Exception $e) {
            echo "❌ Tabela $table: " . $e->getMessage() . "\n";
        }
    }
    
    // 6. Verificar usuários
    echo "\n👤 Verificando usuários...\n";
    $totalUsers = User::count();
    echo "Total de usuários: $totalUsers\n";
    
    if ($totalUsers > 0) {
        $users = User::with('role')->get();
        foreach ($users as $user) {
            echo "- ID: {$user->id} | Nome: {$user->name} | Email: {$user->email} | Role: " . 
                 ($user->role ? $user->role->display_name : 'Sem role') . 
                 " | Status: " . ($user->is_active ? 'Ativo' : 'Inativo') . "\n";
        }
    }
    
    // 7. Verificar usuário específico
    echo "\n🔍 Verificando usuário admin@dspay.com.br...\n";
    $adminUser = User::where('email', 'admin@dspay.com.br')->with('role')->first();
    
    if ($adminUser) {
        echo "✅ Usuário encontrado!\n";
        echo "- ID: {$adminUser->id}\n";
        echo "- Nome: {$adminUser->name}\n";
        echo "- Email: {$adminUser->email}\n";
        echo "- Role ID: {$adminUser->role_id}\n";
        echo "- Role: " . ($adminUser->role ? $adminUser->role->display_name : 'Nenhum') . "\n";
        echo "- Status: " . ($adminUser->is_active ? 'Ativo' : 'Inativo') . "\n";
        echo "- Criado em: {$adminUser->created_at}\n";
        echo "- Atualizado em: {$adminUser->updated_at}\n";
        
        // Verificar permissões
        try {
            $permissions = $adminUser->getPermissions();
            echo "- Permissões: " . $permissions->count() . " permissões\n";
        } catch (Exception $e) {
            echo "- Permissões: Erro ao verificar - " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ Usuário admin@dspay.com.br não encontrado!\n";
    }
    
    // 8. Verificar roles
    echo "\n🔐 Verificando roles...\n";
    $roles = Role::all();
    foreach ($roles as $role) {
        echo "- ID: {$role->id} | Nome: {$role->name} | Display: {$role->display_name} | Status: " . 
             ($role->is_active ? 'Ativo' : 'Inativo') . "\n";
    }
    
    // 9. Verificar configurações do banco
    echo "\n⚙️ Configurações do banco...\n";
    echo "- Driver: " . config('database.default') . "\n";
    echo "- Host: " . config('database.connections.mysql.host') . "\n";
    echo "- Database: " . config('database.connections.mysql.database') . "\n";
    echo "- Username: " . config('database.connections.mysql.username') . "\n";
    
    // 10. Teste de inserção
    echo "\n🧪 Teste de inserção...\n";
    try {
        $testUser = new User();
        $testUser->name = 'Teste Verificação';
        $testUser->email = 'teste_verificacao_' . time() . '@test.com';
        $testUser->password = bcrypt('teste123');
        $testUser->role_id = 1;
        $testUser->is_active = true;
        $testUser->save();
        
        echo "✅ Teste de inserção bem-sucedido!\n";
        echo "- Usuário de teste criado com ID: {$testUser->id}\n";
        
        // Remover usuário de teste
        $testUser->delete();
        echo "✅ Usuário de teste removido\n";
        
    } catch (Exception $e) {
        echo "❌ Erro no teste de inserção: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎉 Verificação concluída!\n";
    
} catch (Exception $e) {
    echo "❌ Erro geral: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
