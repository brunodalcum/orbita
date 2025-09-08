<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

echo "🔍 DIAGNÓSTICO DE PRODUÇÃO - CRIAÇÃO DE USUÁRIOS\n";
echo "================================================\n\n";

// Criar aplicação Laravel
$app = new Application(realpath(__DIR__));

// Carregar configurações
$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

// Bootstrap da aplicação
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "✅ Aplicação Laravel inicializada\n\n";

try {
    // 1. Testar conexão com banco
    echo "🔗 1. TESTANDO CONEXÃO COM BANCO DE DADOS\n";
    echo "-----------------------------------------\n";
    
    $pdo = DB::connection()->getPdo();
    echo "✅ Conexão com banco: OK\n";
    echo "📊 Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
    echo "📊 Versão: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n\n";
    
} catch (\Exception $e) {
    echo "❌ Erro na conexão com banco: " . $e->getMessage() . "\n\n";
    exit(1);
}

try {
    // 2. Verificar tabelas
    echo "📋 2. VERIFICANDO ESTRUTURA DAS TABELAS\n";
    echo "---------------------------------------\n";
    
    // Verificar tabela users
    $usersColumns = DB::select("DESCRIBE users");
    echo "✅ Tabela 'users' encontrada com " . count($usersColumns) . " colunas\n";
    
    foreach ($usersColumns as $column) {
        echo "  - {$column->Field} ({$column->Type}) " . ($column->Null === 'NO' ? 'NOT NULL' : 'NULL') . "\n";
    }
    echo "\n";
    
    // Verificar tabela roles
    $rolesColumns = DB::select("DESCRIBE roles");
    echo "✅ Tabela 'roles' encontrada com " . count($rolesColumns) . " colunas\n";
    
    foreach ($rolesColumns as $column) {
        echo "  - {$column->Field} ({$column->Type}) " . ($column->Null === 'NO' ? 'NOT NULL' : 'NULL') . "\n";
    }
    echo "\n";
    
} catch (\Exception $e) {
    echo "❌ Erro ao verificar tabelas: " . $e->getMessage() . "\n\n";
}

try {
    // 3. Verificar dados existentes
    echo "📊 3. VERIFICANDO DADOS EXISTENTES\n";
    echo "----------------------------------\n";
    
    $userCount = User::count();
    echo "👥 Total de usuários: {$userCount}\n";
    
    $roleCount = Role::count();
    echo "🎭 Total de roles: {$roleCount}\n";
    
    if ($roleCount > 0) {
        echo "📋 Roles disponíveis:\n";
        $roles = Role::all();
        foreach ($roles as $role) {
            echo "  - ID: {$role->id} | Nome: {$role->name} | Display: {$role->display_name} | Ativo: " . ($role->is_active ? 'SIM' : 'NÃO') . "\n";
        }
    }
    echo "\n";
    
} catch (\Exception $e) {
    echo "❌ Erro ao verificar dados: " . $e->getMessage() . "\n\n";
}

try {
    // 4. Testar criação de usuário
    echo "👤 4. TESTANDO CRIAÇÃO DE USUÁRIO DE TESTE\n";
    echo "------------------------------------------\n";
    
    // Verificar se já existe usuário de teste
    $testUser = User::where('email', 'teste.producao@orbita.com')->first();
    if ($testUser) {
        echo "⚠️  Usuário de teste já existe, removendo...\n";
        $testUser->delete();
        echo "✅ Usuário de teste removido\n";
    }
    
    // Buscar role de funcionário (ID 3)
    $funcionarioRole = Role::find(3);
    if (!$funcionarioRole) {
        echo "❌ Role 'funcionario' (ID 3) não encontrada!\n";
        echo "📋 Roles disponíveis:\n";
        $roles = Role::all();
        foreach ($roles as $role) {
            echo "  - ID: {$role->id} | Nome: {$role->name}\n";
        }
        echo "\n";
        
        // Usar primeira role disponível
        $funcionarioRole = $roles->first();
        echo "⚠️  Usando role: {$funcionarioRole->name} (ID: {$funcionarioRole->id})\n";
    }
    
    echo "🔧 Criando usuário de teste...\n";
    
    $userData = [
        'name' => 'Usuário Teste Produção',
        'email' => 'teste.producao@orbita.com',
        'password' => Hash::make('senha123'),
        'role_id' => $funcionarioRole->id,
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now()
    ];
    
    echo "📝 Dados do usuário:\n";
    foreach ($userData as $key => $value) {
        if ($key === 'password') {
            echo "  - {$key}: [HASH GERADO]\n";
        } else {
            echo "  - {$key}: {$value}\n";
        }
    }
    echo "\n";
    
    // Tentar criar usando Eloquent
    echo "🚀 Tentativa 1: Usando Eloquent Model...\n";
    try {
        $user = User::create($userData);
        echo "✅ Usuário criado com sucesso via Eloquent!\n";
        echo "📊 ID do usuário: {$user->id}\n";
        echo "📊 Nome: {$user->name}\n";
        echo "📊 Email: {$user->email}\n";
        echo "📊 Role ID: {$user->role_id}\n";
        echo "📊 Ativo: " . ($user->is_active ? 'SIM' : 'NÃO') . "\n\n";
        
        // Limpar usuário de teste
        $user->delete();
        echo "🧹 Usuário de teste removido\n\n";
        
    } catch (\Exception $e) {
        echo "❌ Erro ao criar usuário via Eloquent: " . $e->getMessage() . "\n";
        echo "📋 Stack trace:\n" . $e->getTraceAsString() . "\n\n";
        
        // Tentar criar usando Query Builder
        echo "🚀 Tentativa 2: Usando Query Builder...\n";
        try {
            $userId = DB::table('users')->insertGetId($userData);
            echo "✅ Usuário criado com sucesso via Query Builder!\n";
            echo "📊 ID do usuário: {$userId}\n\n";
            
            // Limpar usuário de teste
            DB::table('users')->where('id', $userId)->delete();
            echo "🧹 Usuário de teste removido\n\n";
            
        } catch (\Exception $e2) {
            echo "❌ Erro ao criar usuário via Query Builder: " . $e2->getMessage() . "\n";
            echo "📋 Stack trace:\n" . $e2->getTraceAsString() . "\n\n";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ Erro geral no teste de criação: " . $e->getMessage() . "\n\n";
}

try {
    // 5. Verificar permissões de escrita
    echo "🔒 5. VERIFICANDO PERMISSÕES\n";
    echo "----------------------------\n";
    
    // Verificar permissões do diretório storage
    $storageDir = storage_path();
    echo "📁 Diretório storage: {$storageDir}\n";
    echo "✅ Legível: " . (is_readable($storageDir) ? 'SIM' : 'NÃO') . "\n";
    echo "✅ Gravável: " . (is_writable($storageDir) ? 'SIM' : 'NÃO') . "\n";
    
    // Verificar permissões do diretório de logs
    $logsDir = storage_path('logs');
    echo "📁 Diretório logs: {$logsDir}\n";
    echo "✅ Legível: " . (is_readable($logsDir) ? 'SIM' : 'NÃO') . "\n";
    echo "✅ Gravável: " . (is_writable($logsDir) ? 'SIM' : 'NÃO') . "\n";
    
    // Verificar arquivo de log
    $logFile = storage_path('logs/laravel.log');
    if (file_exists($logFile)) {
        echo "📄 Arquivo de log: {$logFile}\n";
        echo "✅ Legível: " . (is_readable($logFile) ? 'SIM' : 'NÃO') . "\n";
        echo "✅ Gravável: " . (is_writable($logFile) ? 'SIM' : 'NÃO') . "\n";
        echo "📊 Tamanho: " . number_format(filesize($logFile) / 1024, 2) . " KB\n";
    } else {
        echo "⚠️  Arquivo de log não existe: {$logFile}\n";
    }
    echo "\n";
    
} catch (\Exception $e) {
    echo "❌ Erro ao verificar permissões: " . $e->getMessage() . "\n\n";
}

// 6. Verificar configurações do ambiente
echo "⚙️  6. VERIFICANDO CONFIGURAÇÕES DO AMBIENTE\n";
echo "--------------------------------------------\n";
echo "🌍 APP_ENV: " . env('APP_ENV', 'não definido') . "\n";
echo "🔧 APP_DEBUG: " . (env('APP_DEBUG', false) ? 'true' : 'false') . "\n";
echo "🗄️  DB_CONNECTION: " . env('DB_CONNECTION', 'não definido') . "\n";
echo "🏠 DB_HOST: " . env('DB_HOST', 'não definido') . "\n";
echo "📊 DB_DATABASE: " . env('DB_DATABASE', 'não definido') . "\n";
echo "👤 DB_USERNAME: " . env('DB_USERNAME', 'não definido') . "\n";
echo "🔐 DB_PASSWORD: " . (env('DB_PASSWORD') ? '[DEFINIDA]' : '[NÃO DEFINIDA]') . "\n";
echo "📝 LOG_CHANNEL: " . env('LOG_CHANNEL', 'não definido') . "\n\n";

echo "🎯 DIAGNÓSTICO CONCLUÍDO!\n";
echo "========================\n";
echo "Execute este script em produção e envie o resultado completo.\n";
echo "Comando: php diagnose-production-users.php\n\n";

?>
