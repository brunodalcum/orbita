<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 TESTANDO CONFIGURAÇÕES SMTP ALTERNATIVAS - HOSTINGER\n";
echo "========================================================\n\n";

// Configuração 1: Porta 587 com TLS (atual)
echo "📧 TESTE 1: Porta 587 com TLS\n";
echo "==============================\n";
$config1 = [
    'MAIL_MAILER=smtp',
    'MAIL_HOST=smtp.hostinger.com',
    'MAIL_PORT=587',
    'MAIL_ENCRYPTION=tls',
    'MAIL_USERNAME=brunodalcum@dspay.com.br',
    'MAIL_PASSWORD=032325Br#',
    'MAIL_FROM_ADDRESS=brunodalcum@dspay.com.br',
    'MAIL_FROM_NAME="DSPay"'
];

atualizarEnv($config1);
echo "✅ Configuração 1 aplicada!\n\n";

// Configuração 2: Porta 465 com SSL
echo "📧 TESTE 2: Porta 465 com SSL\n";
echo "==============================\n";
$config2 = [
    'MAIL_MAILER=smtp',
    'MAIL_HOST=smtp.hostinger.com',
    'MAIL_PORT=465',
    'MAIL_ENCRYPTION=ssl',
    'MAIL_USERNAME=brunodalcum@dspay.com.br',
    'MAIL_PASSWORD=032325Br#',
    'MAIL_FROM_ADDRESS=brunodalcum@dspay.com.br',
    'MAIL_FROM_NAME="DSPay"'
];

atualizarEnv($config2);
echo "✅ Configuração 2 aplicada!\n\n";

// Configuração 3: Porta 587 sem criptografia
echo "📧 TESTE 3: Porta 587 sem criptografia\n";
echo "======================================\n";
$config3 = [
    'MAIL_MAILER=smtp',
    'MAIL_HOST=smtp.hostinger.com',
    'MAIL_PORT=587',
    'MAIL_ENCRYPTION=',
    'MAIL_USERNAME=brunodalcum@dspay.com.br',
    'MAIL_PASSWORD=032325Br#',
    'MAIL_FROM_ADDRESS=brunodalcum@dspay.com.br',
    'MAIL_FROM_NAME="DSPay"'
];

atualizarEnv($config3);
echo "✅ Configuração 3 aplicada!\n\n";

echo "🔧 INSTRUÇÕES PARA TESTAR:\n";
echo "1. Execute: php artisan config:clear\n";
echo "2. Teste cada configuração na interface web\n";
echo "3. Se nenhuma funcionar, pode ser problema de credenciais\n\n";

function atualizarEnv($novasConfigs) {
    $envFile = '.env';
    $linhas = file($envFile, FILE_IGNORE_NEW_LINES);
    
    foreach ($novasConfigs as $novaConfig) {
        $chave = explode('=', $novaConfig)[0];
        $encontrado = false;
        
        for ($i = 0; $i < count($linhas); $i++) {
            if (strpos($linhas[$i], $chave . '=') === 0) {
                $linhas[$i] = $novaConfig;
                $encontrado = true;
                break;
            }
        }
        
        if (!$encontrado) {
            $linhas[] = $novaConfig;
        }
    }
    
    file_put_contents($envFile, implode("\n", $linhas) . "\n");
}
