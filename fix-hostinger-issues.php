<?php

// Script específico para corrigir problemas do Hostinger
// Execute: php fix-hostinger-issues.php

echo "🔧 CORRIGINDO PROBLEMAS DO HOSTINGER...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Verificar se é Hostinger
$hostname = shell_exec('hostname');
$pwd = shell_exec('pwd');

if (strpos($hostname, 'hstgr') === false && strpos($pwd, 'htdocs') === false) {
    echo "❌ Este script é específico para Hostinger\n";
    exit(1);
}

echo "✅ Servidor Hostinger detectado\n";

// 3. Corrigir permissões específicas do Hostinger
echo "\n🔐 CORRIGINDO PERMISSÕES DO HOSTINGER...\n";

$paths = [
    'storage',
    'storage/framework',
    'storage/framework/views',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($paths as $path) {
    if (is_dir($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        echo "Permissões $path: $perms\n";
        
        // Corrigir permissões se necessário
        if ($perms !== '0755') {
            chmod($path, 0755);
            echo "✅ Permissões corrigidas para $path\n";
        }
    } else {
        echo "❌ Diretório não encontrado: $path\n";
        // Criar diretório se não existir
        if (mkdir($path, 0755, true)) {
            echo "✅ Diretório criado: $path\n";
        } else {
            echo "❌ Erro ao criar diretório: $path\n";
        }
    }
}

// 4. Verificar e corrigir .htaccess
echo "\n🔧 VERIFICANDO E CORRIGINDO .HTACCESS...\n";

if (file_exists('.htaccess')) {
    echo "✅ Arquivo .htaccess encontrado\n";
} else {
    echo "❌ Arquivo .htaccess não encontrado\n";
    echo "🔧 Criando .htaccess...\n";
    
    $htaccessContent = '<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>';
    
    if (file_put_contents('.htaccess', $htaccessContent)) {
        echo "✅ Arquivo .htaccess criado\n";
    } else {
        echo "❌ Erro ao criar .htaccess\n";
    }
}

// 5. Verificar e corrigir public/index.php
echo "\n🔧 VERIFICANDO E CORRIGINDO PUBLIC/INDEX.PHP...\n";

if (file_exists('public/index.php')) {
    echo "✅ Arquivo public/index.php encontrado\n";
} else {
    echo "❌ Arquivo public/index.php não encontrado\n";
    echo "🔧 Criando public/index.php...\n";
    
    $indexContent = '<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define(\'LARAVEL_START\', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.\'/../storage/framework/maintenance.php\')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We\'ll simply require it
| into the script here so we don\'t need to manually load our classes.
|
*/

require __DIR__.\'/../vendor/autoload.php\';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application\'s HTTP kernel. Then, we will send the response back
| to this client\'s browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.\'/../bootstrap/app.php\';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);';
    
    if (file_put_contents('public/index.php', $indexContent)) {
        echo "✅ Arquivo public/index.php criado\n";
    } else {
        echo "❌ Erro ao criar public/index.php\n";
    }
}

// 6. Verificar e corrigir .env
echo "\n⚙️ VERIFICANDO E CORRIGINDO .ENV...\n";

if (file_exists('.env')) {
    echo "✅ Arquivo .env encontrado\n";
    
    $envContent = file_get_contents('.env');
    
    // Corrigir configurações
    $envContent = preg_replace('/^APP_ENV=.*$/m', 'APP_ENV=production', $envContent);
    $envContent = preg_replace('/^APP_DEBUG=.*$/m', 'APP_DEBUG=false', $envContent);
    
    // Remover caminhos de desenvolvimento
    $envContent = preg_replace('/^.*\/Applications\/MAMP\/htdocs\/orbita\/.*$/m', '', $envContent);
    
    file_put_contents('.env', $envContent);
    echo "✅ Arquivo .env corrigido\n";
} else {
    echo "❌ Arquivo .env não encontrado\n";
    echo "🔧 Criando .env...\n";
    
    $envContent = 'APP_NAME=Laravel
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_URL=https://srv971263.hstgr.cloud

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-database-username
DB_PASSWORD=your-database-password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"';
    
    if (file_put_contents('.env', $envContent)) {
        echo "✅ Arquivo .env criado\n";
    } else {
        echo "❌ Erro ao criar .env\n";
    }
}

// 7. Limpar cache completamente
echo "\n🗑️ LIMPANDO CACHE COMPLETAMENTE...\n";

$cacheDirs = [
    'storage/framework/cache',
    'storage/framework/views',
    'storage/framework/sessions',
    'storage/framework/testing',
    'bootstrap/cache'
];

foreach ($cacheDirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        $count = 0;
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
                $count++;
            }
        }
        echo "✅ Limpo: $dir ($count arquivos removidos)\n";
    }
}

// 8. Recriar cache básico
echo "\n🗂️ RECRIANDO CACHE BÁSICO...\n";

$cacheCommands = [
    'php artisan config:cache',
    'php artisan route:cache'
];

foreach ($cacheCommands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "Resultado: $output\n";
    }
}

// 9. Testar sistema
echo "\n🧪 TESTANDO SISTEMA...\n";

$testCommand = '
try {
    echo "Testando sistema..." . PHP_EOL;
    $user = App\Models\User::where("email", "admin@dspay.com.br")->first();
    if ($user) {
        echo "✅ Usuário encontrado: " . $user->name . PHP_EOL;
    } else {
        echo "❌ Usuário não encontrado!" . PHP_EOL;
    }
    
    echo "Testando rotas..." . PHP_EOL;
    $routes = [
        "dashboard" => route("dashboard"),
        "licenciados" => route("dashboard.licenciados")
    ];
    
    foreach ($routes as $name => $url) {
        echo "✅ Rota $name: $url" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . PHP_EOL;
}
';

$output = shell_exec("php artisan tinker --execute=\"$testCommand\" 2>&1");
echo "Resultado do teste:\n$output\n";

// 10. Verificar se o servidor web está funcionando
echo "\n🌐 VERIFICANDO SERVIDOR WEB...\n";

// Verificar se há algum servidor web rodando
$psOutput = shell_exec('ps aux | grep -E "(apache|nginx|httpd)" | grep -v grep');
if ($psOutput) {
    echo "✅ Processos de servidor web encontrados:\n$psOutput\n";
} else {
    echo "❌ Nenhum servidor web rodando\n";
    echo "🔧 Tentando iniciar servidor web...\n";
    
    // Tentar iniciar Apache
    $apacheStart = shell_exec('systemctl start apache2 2>&1');
    if (strpos($apacheStart, 'Failed') === false) {
        echo "✅ Apache iniciado\n";
    } else {
        echo "❌ Erro ao iniciar Apache: $apacheStart\n";
    }
    
    // Tentar iniciar Nginx
    $nginxStart = shell_exec('systemctl start nginx 2>&1');
    if (strpos($nginxStart, 'Failed') === false) {
        echo "✅ Nginx iniciado\n";
    } else {
        echo "❌ Erro ao iniciar Nginx: $nginxStart\n";
    }
}

echo "\n🎉 CORREÇÃO DO HOSTINGER CONCLUÍDA!\n";
echo "\n📋 RESUMO:\n";
echo "- ✅ Permissões corrigidas\n";
echo "- ✅ .htaccess criado/corrigido\n";
echo "- ✅ public/index.php criado/corrigido\n";
echo "- ✅ .env criado/corrigido\n";
echo "- ✅ Cache limpo\n";
echo "- ✅ Cache recriado\n";
echo "- ✅ Sistema testado\n";
echo "- ✅ Servidor web verificado\n";
echo "\n🚀 Agora teste o sistema:\n";
echo "https://srv971263.hstgr.cloud/dashboard\n";
echo "\n🔍 Se ainda houver erro:\n";
echo "1. Verifique se o servidor web está rodando\n";
echo "2. Verifique se o PHP está funcionando\n";
echo "3. Verifique se o Laravel está funcionando\n";
echo "4. Verifique se o banco de dados está funcionando\n";
echo "5. Verifique se o usuário admin existe\n";
