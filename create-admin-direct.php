<?php

// Script direto para criar usuário Super Admin
// Execute: php create-admin-direct.php

echo "🔧 Criando usuário Super Admin (método direto)...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// 3. Dados do usuário
$name = 'Super Admin';
$email = 'admin@dspay.com.br';
$password = 'admin123456';

echo "📋 Dados do usuário:\n";
echo "- Nome: $name\n";
echo "- Email: $email\n";
echo "- Senha: $password\n";

// 4. Importar classes necessárias
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

try {
    // 5. Verificar se o usuário já existe
    $user = User::where('email', $email)->first();

    if ($user) {
        echo "⚠️ Usuário já existe. Atualizando...\n";
        
        // Atualizar senha e role
        $user->password = Hash::make($password);
        $user->role_id = 1; // Super Admin
        $user->is_active = true;
        $user->save();
        
        echo "✅ Usuário atualizado!\n";
    } else {
        // Criar novo usuário
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->role_id = 1; // Super Admin
        $user->is_active = true;
        $user->email_verified_at = now();
        $user->save();
        
        echo "✅ Usuário criado com sucesso!\n";
    }

    // 6. Verificar se foi criado
    $createdUser = User::where('email', $email)->first();
    if ($createdUser) {
        echo "\n📋 Informações do usuário:\n";
        echo "- ID: " . $createdUser->id . "\n";
        echo "- Nome: " . $createdUser->name . "\n";
        echo "- Email: " . $createdUser->email . "\n";
        echo "- Role ID: " . $createdUser->role_id . "\n";
        echo "- Status: " . ($createdUser->is_active ? 'Ativo' : 'Inativo') . "\n";
        echo "- Criado em: " . $createdUser->created_at . "\n";
        
        // Verificar role
        if ($createdUser->role) {
            echo "- Role: " . $createdUser->role->display_name . "\n";
        } else {
            echo "- Role: Nenhum role atribuído\n";
        }
    }

    echo "\n🎉 Usuário Super Admin criado com sucesso!\n";
    echo "✅ Faça login em: https://srv971263.hstgr.cloud/login\n";
    echo "📧 Email: $email\n";
    echo "🔑 Senha: $password\n";
    echo "⚠️ Lembre-se de alterar a senha após o primeiro login!\n";

} catch (Exception $e) {
    echo "❌ Erro ao criar usuário: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
