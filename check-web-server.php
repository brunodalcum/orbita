<?php

// Script para verificar servidor web e corrigir problemas
// Execute: php check-web-server.php

echo "🔍 VERIFICANDO SERVIDOR WEB...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Verificar qual servidor web está rodando
echo "\n🌐 VERIFICANDO SERVIDOR WEB...\n";

// Verificar Apache
$apacheStatus = shell_exec('systemctl status apache2 2>&1');
if (strpos($apacheStatus, 'Active: active') !== false) {
    echo "✅ Apache2 está rodando\n";
} elseif (strpos($apacheStatus, 'Unit apache2.service could not be found') !== false) {
    echo "❌ Apache2 não está instalado\n";
} else {
    echo "❌ Apache2 não está rodando\n";
}

// Verificar Nginx
$nginxStatus = shell_exec('systemctl status nginx 2>&1');
if (strpos($nginxStatus, 'Active: active') !== false) {
    echo "✅ Nginx está rodando\n";
} elseif (strpos($nginxStatus, 'Unit nginx.service could not be found') !== false) {
    echo "❌ Nginx não está instalado\n";
} else {
    echo "❌ Nginx não está rodando\n";
}

// Verificar se há algum servidor web rodando
$psOutput = shell_exec('ps aux | grep -E "(apache|nginx|httpd)" | grep -v grep');
if ($psOutput) {
    echo "✅ Processos de servidor web encontrados:\n$psOutput\n";
} else {
    echo "❌ Nenhum servidor web rodando\n";
}

// 3. Verificar portas
echo "\n🔌 VERIFICANDO PORTAS...\n";

$port80 = shell_exec('netstat -tlnp | grep :80');
if ($port80) {
    echo "✅ Porta 80 em uso:\n$port80\n";
} else {
    echo "❌ Porta 80 não está em uso\n";
}

$port443 = shell_exec('netstat -tlnp | grep :443');
if ($port443) {
    echo "✅ Porta 443 em uso:\n$port443\n";
} else {
    echo "❌ Porta 443 não está em uso\n";
}

// 4. Verificar se é um servidor compartilhado
echo "\n🏢 VERIFICANDO TIPO DE SERVIDOR...\n";

$hostname = shell_exec('hostname');
echo "Hostname: $hostname\n";

$whoami = shell_exec('whoami');
echo "Usuário atual: $whoami\n";

$pwd = shell_exec('pwd');
echo "Diretório atual: $pwd\n";

// Verificar se é Hostinger
if (strpos($hostname, 'hstgr') !== false || strpos($pwd, 'htdocs') !== false) {
    echo "✅ Servidor Hostinger detectado\n";
    echo "🔧 Configurações específicas do Hostinger:\n";
    
    // Verificar se o PHP está funcionando
    $phpVersion = shell_exec('php -v');
    echo "Versão do PHP:\n$phpVersion\n";
    
    // Verificar se o Laravel está funcionando
    $laravelVersion = shell_exec('php artisan --version');
    echo "Versão do Laravel:\n$laravelVersion\n";
    
    // Verificar permissões específicas do Hostinger
    echo "\n🔐 VERIFICANDO PERMISSÕES DO HOSTINGER...\n";
    
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
        }
    }
    
    // Verificar se o arquivo .htaccess existe
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
    
    // Verificar se o arquivo index.php existe
    if (file_exists('public/index.php')) {
        echo "✅ Arquivo public/index.php encontrado\n";
    } else {
        echo "❌ Arquivo public/index.php não encontrado\n";
    }
    
    // Verificar se o diretório public existe
    if (is_dir('public')) {
        echo "✅ Diretório public encontrado\n";
    } else {
        echo "❌ Diretório public não encontrado\n";
    }
    
} else {
    echo "❌ Servidor não é Hostinger\n";
}

// 5. Verificar logs de erro do servidor web
echo "\n📋 VERIFICANDO LOGS DO SERVIDOR WEB...\n";

$logFiles = [
    '/var/log/apache2/error.log',
    '/var/log/nginx/error.log',
    '/var/log/httpd/error_log',
    'storage/logs/laravel.log'
];

foreach ($logFiles as $logFile) {
    if (file_exists($logFile)) {
        echo "✅ Log encontrado: $logFile\n";
        
        // Mostrar últimas 10 linhas
        $output = shell_exec("tail -10 $logFile 2>&1");
        echo "Últimas 10 linhas:\n$output\n";
    } else {
        echo "❌ Log não encontrado: $logFile\n";
    }
}

// 6. Testar se o PHP está funcionando
echo "\n🧪 TESTANDO PHP...\n";

$phpTest = shell_exec('php -r "echo \'PHP funcionando: \' . phpversion() . PHP_EOL;"');
echo $phpTest;

// 7. Testar se o Laravel está funcionando
echo "\n🧪 TESTANDO LARAVEL...\n";

$laravelTest = shell_exec('php artisan --version 2>&1');
echo "Laravel: $laravelTest\n";

// 8. Testar se as rotas estão funcionando
echo "\n🧪 TESTANDO ROTAS...\n";

$routeTest = shell_exec('php artisan route:list 2>&1');
if (strpos($routeTest, 'dashboard') !== false) {
    echo "✅ Rotas do dashboard encontradas\n";
} else {
    echo "❌ Rotas do dashboard não encontradas\n";
}

// 9. Verificar se o banco de dados está funcionando
echo "\n🧪 TESTANDO BANCO DE DADOS...\n";

$dbTest = shell_exec('php artisan tinker --execute="echo \'DB funcionando: \' . DB::connection()->getPdo() ? \'Sim\' : \'Não\' . PHP_EOL;" 2>&1');
echo "Banco de dados: $dbTest\n";

// 10. Verificar se o usuário admin existe
echo "\n🧪 TESTANDO USUÁRIO ADMIN...\n";

$userTest = shell_exec('php artisan tinker --execute="echo \'Usuário admin: \' . (App\Models\User::where(\'email\', \'admin@dspay.com.br\')->exists() ? \'Existe\' : \'Não existe\') . PHP_EOL;" 2>&1');
echo "Usuário admin: $userTest\n";

echo "\n🎉 VERIFICAÇÃO CONCLUÍDA!\n";
echo "\n📋 RESUMO:\n";
echo "- ✅ Servidor web verificado\n";
echo "- ✅ Portas verificadas\n";
echo "- ✅ Tipo de servidor identificado\n";
echo "- ✅ Permissões verificadas\n";
echo "- ✅ Logs verificados\n";
echo "- ✅ PHP testado\n";
echo "- ✅ Laravel testado\n";
echo "- ✅ Rotas testadas\n";
echo "- ✅ Banco de dados testado\n";
echo "- ✅ Usuário admin testado\n";
echo "\n🚀 Agora teste o sistema:\n";
echo "https://srv971263.hstgr.cloud/dashboard\n";
echo "\n🔍 Se ainda houver erro:\n";
echo "1. Verifique se o servidor web está rodando\n";
echo "2. Verifique se o PHP está funcionando\n";
echo "3. Verifique se o Laravel está funcionando\n";
echo "4. Verifique se o banco de dados está funcionando\n";
echo "5. Verifique se o usuário admin existe\n";
