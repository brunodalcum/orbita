<?php

// Script para corrigir problema de sessão e CSRF
// Execute: php fix-csrf-session.php

echo "🔧 Corrigindo problema de sessão e CSRF...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Inicializar Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// 3. Verificar configurações atuais
echo "📋 Configurações atuais:\n";
echo "APP_KEY: " . config('app.key') . "\n";
echo "SESSION_DRIVER: " . config('session.driver') . "\n";
echo "SESSION_LIFETIME: " . config('session.lifetime') . "\n";
echo "SESSION_PATH: " . config('session.path') . "\n";

// 4. Iniciar sessão manualmente
echo "\n🔄 Iniciando sessão...\n";
session_start();
echo "Session ID: " . session_id() . "\n";

// 5. Gerar token CSRF
echo "\n🔑 Gerando token CSRF...\n";
$token = csrf_token();
echo "CSRF Token: " . $token . "\n";

// 6. Verificar se a sessão está funcionando
echo "\n🧪 Testando sessão...\n";
$_SESSION['test'] = 'working';
if (isset($_SESSION['test']) && $_SESSION['test'] === 'working') {
    echo "✅ Sessão funcionando\n";
} else {
    echo "❌ Problema com sessão\n";
}

// 7. Verificar permissões do diretório de sessões
echo "\n🔐 Verificando permissões...\n";
$sessionPath = config('session.files');
if (is_dir($sessionPath)) {
    $perms = substr(sprintf('%o', fileperms($sessionPath)), -4);
    echo "Permissões do diretório de sessões: $perms\n";
    
    // Testar escrita
    $testFile = $sessionPath . '/test_' . time() . '.tmp';
    if (file_put_contents($testFile, 'test')) {
        unlink($testFile);
        echo "✅ Escrita no diretório de sessões funcionando\n";
    } else {
        echo "❌ Problema de escrita no diretório de sessões\n";
    }
} else {
    echo "❌ Diretório de sessões não encontrado: $sessionPath\n";
}

// 8. Verificar configuração do .env
echo "\n⚙️ Verificando arquivo .env...\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    // Verificar APP_KEY
    if (preg_match('/^APP_KEY=(.+)$/m', $envContent, $matches)) {
        echo "APP_KEY no .env: " . $matches[1] . "\n";
    } else {
        echo "❌ APP_KEY não encontrada no .env\n";
    }
    
    // Verificar SESSION_DRIVER
    if (preg_match('/^SESSION_DRIVER=(.+)$/m', $envContent, $matches)) {
        echo "SESSION_DRIVER no .env: " . $matches[1] . "\n";
    } else {
        echo "❌ SESSION_DRIVER não encontrado no .env\n";
    }
} else {
    echo "❌ Arquivo .env não encontrado\n";
}

// 9. Criar arquivo de teste de login
echo "\n📝 Criando teste de login...\n";
$testLoginFile = 'test-login.php';
$testLoginContent = '<?php
require_once "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

// Iniciar sessão
session_start();

echo "<h1>Teste de Login</h1>";
echo "<p>APP_KEY: " . config("app.key") . "</p>";
echo "<p>SESSION_DRIVER: " . config("session.driver") . "</p>";
echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>CSRF Token: " . csrf_token() . "</p>";

echo "<form method=\"POST\" action=\"" . route("login") . "\">";
echo csrf_field();
echo "<p>Email: <input type=\"email\" name=\"email\" required></p>";
echo "<p>Senha: <input type=\"password\" name=\"password\" required></p>";
echo "<p><button type=\"submit\">Login</button></p>";
echo "</form>";
?>';

if (file_put_contents($testLoginFile, $testLoginContent)) {
    echo "Arquivo de teste criado: $testLoginFile\n";
    echo "Acesse: http://127.0.0.1:8001/$testLoginFile\n";
}

echo "\n🎉 Diagnóstico concluído!\n";
echo "\n📋 Próximos passos:\n";
echo "1. Verifique se as permissões estão corretas\n";
echo "2. Teste o arquivo: $testLoginFile\n";
echo "3. Se ainda houver problemas, execute:\n";
echo "   - chmod -R 755 storage/framework/sessions\n";
echo "   - chown -R www-data:www-data storage/framework/sessions\n";
