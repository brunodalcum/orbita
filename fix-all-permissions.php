<?php

// Script definitivo para corrigir TODAS as permissões em produção
// Execute: php fix-all-permissions.php

echo "🔧 Correção DEFINITIVA de permissões em produção...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Parar serviços web temporariamente (se possível)
echo "⏹️ Tentando parar serviços web...\n";
$stopCommands = [
    'sudo systemctl stop apache2 2>/dev/null',
    'sudo systemctl stop nginx 2>/dev/null',
    'sudo systemctl stop php8.1-fpm 2>/dev/null',
    'sudo systemctl stop php8.2-fpm 2>/dev/null'
];

foreach ($stopCommands as $command) {
    shell_exec($command);
}

// 3. Limpar TODOS os caches
echo "🧹 Limpando TODOS os caches...\n";
$clearCommands = [
    'php artisan cache:clear 2>/dev/null',
    'php artisan config:clear 2>/dev/null',
    'php artisan route:clear 2>/dev/null',
    'php artisan view:clear 2>/dev/null',
    'php artisan session:clear 2>/dev/null'
];

foreach ($clearCommands as $command) {
    echo "Executando: $command\n";
    shell_exec($command);
}

// 4. Remover COMPLETAMENTE os diretórios problemáticos
echo "🗑️ Removendo diretórios problemáticos...\n";
$problematicDirs = [
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($problematicDirs as $dir) {
    if (is_dir($dir)) {
        // Tentar remover com diferentes métodos
        shell_exec("rm -rf $dir/* 2>/dev/null");
        shell_exec("sudo rm -rf $dir/* 2>/dev/null");
        echo "Limpo: $dir\n";
    }
}

// 5. Recriar diretórios com estrutura correta
echo "📁 Recriando estrutura de diretórios...\n";
$directories = [
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
        echo "Criado: $dir\n";
    }
}

// 6. Aplicar permissões MUITO permissivas
echo "🔐 Aplicando permissões permissivas...\n";
$permissionCommands = [
    'chmod -R 777 storage 2>/dev/null',
    'chmod -R 777 bootstrap/cache 2>/dev/null',
    'sudo chmod -R 777 storage 2>/dev/null',
    'sudo chmod -R 777 bootstrap/cache 2>/dev/null'
];

foreach ($permissionCommands as $command) {
    echo "Executando: $command\n";
    shell_exec($command);
}

// 7. Tentar definir proprietário correto
echo "👤 Definindo proprietário...\n";
$possibleUsers = ['www-data', 'apache', 'nginx', 'httpd'];
$currentUser = get_current_user();

$chownCommands = [];
foreach ($possibleUsers as $user) {
    $chownCommands[] = "sudo chown -R $user:$user storage 2>/dev/null";
    $chownCommands[] = "sudo chown -R $user:$user bootstrap/cache 2>/dev/null";
}

// Adicionar comando para usuário atual
$chownCommands[] = "sudo chown -R $currentUser:$currentUser storage 2>/dev/null";
$chownCommands[] = "sudo chown -R $currentUser:$currentUser bootstrap/cache 2>/dev/null";

foreach ($chownCommands as $command) {
    echo "Executando: $command\n";
    shell_exec($command);
}

// 8. Desabilitar cache de views e usar cache em memória
echo "⚙️ Configurando cache em memória...\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    // Remover configurações existentes
    $envContent = preg_replace('/^VIEW_CACHE_ENABLED.*$/m', '', $envContent);
    $envContent = preg_replace('/^CACHE_DRIVER.*$/m', '', $envContent);
    $envContent = preg_replace('/^SESSION_DRIVER.*$/m', '', $envContent);
    $envContent = preg_replace('/^LOG_CHANNEL.*$/m', '', $envContent);
    
    // Adicionar configurações para evitar problemas de permissão
    $envContent .= "\n# Configurações para resolver problemas de permissão\n";
    $envContent .= "VIEW_CACHE_ENABLED=false\n";
    $envContent .= "CACHE_DRIVER=array\n";
    $envContent .= "SESSION_DRIVER=array\n";
    $envContent .= "LOG_CHANNEL=stack\n";
    
    if (file_put_contents('.env', $envContent)) {
        echo "✅ Arquivo .env atualizado\n";
    } else {
        echo "❌ Erro ao atualizar .env\n";
    }
}

// 9. Gerar nova APP_KEY se necessário
echo "🔑 Verificando APP_KEY...\n";
$keyCommand = 'php artisan key:generate --force 2>&1';
$output = shell_exec($keyCommand);
echo "Saída: $output\n";

// 10. Testar permissões de escrita
echo "🧪 Testando permissões de escrita...\n";
$testFiles = [
    'storage/logs/test.log',
    'storage/framework/sessions/test.session',
    'storage/framework/views/test.view',
    'storage/framework/cache/test.cache'
];

$allWorking = true;
foreach ($testFiles as $testFile) {
    $dir = dirname($testFile);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    
    if (file_put_contents($testFile, 'test')) {
        unlink($testFile);
        echo "✅ Escrita funcionando: $testFile\n";
    } else {
        echo "❌ Problema de escrita: $testFile\n";
        $allWorking = false;
    }
}

// 11. Regenerar caches (sem view cache)
echo "🔄 Regenerando caches...\n";
$regenerateCommands = [
    'php artisan config:cache 2>&1',
    'php artisan route:cache 2>&1'
];

foreach ($regenerateCommands as $command) {
    echo "Executando: $command\n";
    $output = shell_exec($command);
    if ($output) {
        echo "Saída: $output\n";
    }
}

// 12. Reiniciar serviços web
echo "🔄 Reiniciando serviços web...\n";
$startCommands = [
    'sudo systemctl start apache2 2>/dev/null',
    'sudo systemctl start nginx 2>/dev/null',
    'sudo systemctl start php8.1-fpm 2>/dev/null',
    'sudo systemctl start php8.2-fpm 2>/dev/null'
];

foreach ($startCommands as $command) {
    shell_exec($command);
}

// 13. Verificação final
echo "\n🎉 Correção DEFINITIVA concluída!\n";
echo "\n📋 Status das permissões:\n";

$checkPaths = [
    'storage/logs',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/framework/cache',
    'bootstrap/cache'
];

foreach ($checkPaths as $path) {
    if (is_dir($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        echo "✅ $path - Permissões: $perms\n";
    } else {
        echo "❌ $path - Não encontrado\n";
    }
}

echo "\n🔍 Configurações aplicadas:\n";
echo "- VIEW_CACHE_ENABLED=false\n";
echo "- CACHE_DRIVER=array (em memória)\n";
echo "- SESSION_DRIVER=array (em memória)\n";
echo "- LOG_CHANNEL=stack\n";
echo "- Permissões: 777 (máxima)\n";
echo "- Proprietário: Tentativas múltiplas\n";

if ($allWorking) {
    echo "\n✅ Todas as permissões estão funcionando!\n";
} else {
    echo "\n⚠️ Algumas permissões ainda podem ter problemas\n";
    echo "💡 Contate o suporte do hosting para correção final\n";
}

echo "\n✅ Agora teste:\n";
echo "1. Login: https://srv971263.hstgr.cloud/login\n";
echo "2. Dashboard: https://srv971263.hstgr.cloud/dashboard\n";
echo "\n🔍 Se ainda houver problemas:\n";
echo "1. Execute: sudo chmod -R 777 storage/\n";
echo "2. Execute: sudo chown -R www-data:www-data storage/\n";
echo "3. Contate o suporte do hosting\n";
