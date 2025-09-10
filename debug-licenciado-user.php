<?php

require_once 'vendor/autoload.php';

// Carregar configuração do Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Licenciado;
use App\Models\User;

echo "🔍 Verificando licenciados e usuários...\n\n";

// Buscar licenciado ID 8
$licenciado = Licenciado::find(8);

if ($licenciado) {
    echo "📋 Licenciado ID 8:\n";
    echo "   Razão Social: {$licenciado->razao_social}\n";
    echo "   Email: {$licenciado->email}\n";
    echo "   User ID: " . ($licenciado->user_id ?? 'NULL') . "\n";
    echo "   Status: {$licenciado->status}\n\n";
    
    // Buscar usuário com o mesmo email
    $user = User::where('email', $licenciado->email)->first();
    
    if ($user) {
        echo "👤 Usuário encontrado com mesmo email:\n";
        echo "   ID: {$user->id}\n";
        echo "   Nome: {$user->name}\n";
        echo "   Email: {$user->email}\n";
        echo "   Role: " . ($user->role ?? 'NULL') . "\n\n";
        
        // Associar o usuário ao licenciado
        $licenciado->user_id = $user->id;
        $licenciado->save();
        
        echo "✅ Licenciado associado ao usuário!\n\n";
    } else {
        echo "❌ Nenhum usuário encontrado com email: {$licenciado->email}\n\n";
        
        // Buscar usuários com emails similares
        echo "🔍 Buscando usuários com emails similares:\n";
        $usuarios = User::where('email', 'like', '%' . explode('@', $licenciado->email)[0] . '%')->get();
        
        foreach ($usuarios as $u) {
            echo "   - ID: {$u->id}, Nome: {$u->name}, Email: {$u->email}\n";
        }
    }
} else {
    echo "❌ Licenciado ID 8 não encontrado\n";
}

echo "\n🔍 Verificando todos os licenciados sem user_id:\n\n";

$licenciadosSemUser = Licenciado::whereNull('user_id')->get();

foreach ($licenciadosSemUser as $lic) {
    echo "📋 Licenciado ID {$lic->id}: {$lic->razao_social} ({$lic->email})\n";
    
    // Tentar encontrar usuário com mesmo email
    $user = User::where('email', $lic->email)->first();
    
    if ($user) {
        echo "   ✅ Usuário encontrado: {$user->name} (ID: {$user->id})\n";
        $lic->user_id = $user->id;
        $lic->save();
        echo "   ✅ Associação criada!\n";
    } else {
        echo "   ❌ Nenhum usuário com este email\n";
    }
    echo "\n";
}
