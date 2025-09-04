<?php
/**
 * Script para testar configurações de email
 */

echo "📧 TESTE DE CONFIGURAÇÕES DE EMAIL\n";
echo "==================================\n\n";

// Configurações para testar
$configs = [
    'Hostinger 587' => [
        'MAIL_MAILER=smtp',
        'MAIL_HOST=smtp.hostinger.com',
        'MAIL_PORT=587',
        'MAIL_ENCRYPTION=tls',
        'MAIL_USERNAME=brunodalcum@dspay.com.br',
        'MAIL_PASSWORD=032325br',
        'MAIL_FROM_ADDRESS=brunodalcum@dspay.com.br',
        'MAIL_FROM_NAME=DSPay'
    ],
    'Hostinger 465' => [
        'MAIL_MAILER=smtp',
        'MAIL_HOST=smtp.hostinger.com',
        'MAIL_PORT=465',
        'MAIL_ENCRYPTION=ssl',
        'MAIL_USERNAME=brunodalcum@dspay.com.br',
        'MAIL_PASSWORD=032325br',
        'MAIL_FROM_ADDRESS=brunodalcum@dspay.com.br',
        'MAIL_FROM_NAME=DSPay'
    ],
    'Gmail' => [
        'MAIL_MAILER=smtp',
        'MAIL_HOST=smtp.gmail.com',
        'MAIL_PORT=587',
        'MAIL_ENCRYPTION=tls',
        'MAIL_USERNAME=brunodalcum@dspay.com.br',
        'MAIL_PASSWORD=032325br',
        'MAIL_FROM_ADDRESS=brunodalcum@dspay.com.br',
        'MAIL_FROM_NAME=DSPay'
    ]
];

echo "Escolha uma configuração para testar:\n";
echo "1. Hostinger SMTP (Porta 587 - TLS)\n";
echo "2. Hostinger SMTP (Porta 465 - SSL)\n";
echo "3. Gmail SMTP (Porta 587 - TLS)\n";
echo "4. Voltar para modo LOG\n\n";

$opcao = readline("Digite sua opção (1-4): ");

$configNames = ['Hostinger 587', 'Hostinger 465', 'Gmail', 'Log'];
$configIndex = (int)$opcao - 1;

if ($opcao == '4') {
    $config = [
        'MAIL_MAILER=log',
        'MAIL_HOST=smtp.gmail.com',
        'MAIL_PORT=587',
        'MAIL_USERNAME=test@example.com',
        'MAIL_PASSWORD=password',
        'MAIL_FROM_ADDRESS=test@example.com',
        'MAIL_FROM_NAME=DSPay',
        'MAIL_ENCRYPTION=tls'
    ];
    $configName = 'Log';
} else {
    $config = $configs[$configNames[$configIndex]];
    $configName = $configNames[$configIndex];
}

// Atualizar arquivo .env
$envFile = '.env';
$linhas = file($envFile, FILE_IGNORE_NEW_LINES);

foreach ($config as $novaConfig) {
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

echo "\n✅ Configuração '{$configName}' aplicada!\n";
echo "🔄 Limpando cache...\n";
exec('php artisan config:clear');

echo "✅ Pronto! Teste agora o envio de email.\n";
echo "📋 Se não funcionar, tente outra configuração.\n\n";

echo "🔍 Para verificar logs de erro:\n";
echo "tail -f storage/logs/laravel.log\n";
