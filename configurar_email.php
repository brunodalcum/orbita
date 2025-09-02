<?php
/**
 * Script para configurar email do sistema Dspay
 * Execute: php configurar_email.php
 */

echo "📧 CONFIGURADOR DE EMAIL - DSPAY\n";
echo "================================\n\n";

echo "Escolha uma opção:\n";
echo "1. Gmail SMTP (Recomendado)\n";
echo "2. Hostinger SMTP\n";
echo "3. Modo Log (Desenvolvimento)\n";
echo "4. Sair\n\n";

$opcao = readline("Digite sua opção (1-4): ");

switch ($opcao) {
    case '1':
        configurarGmail();
        break;
    case '2':
        configurarHostinger();
        break;
    case '3':
        configurarLog();
        break;
    case '4':
        echo "Saindo...\n";
        exit;
    default:
        echo "Opção inválida!\n";
        exit;
}

function configurarGmail() {
    echo "\n📧 CONFIGURAÇÃO GMAIL SMTP\n";
    echo "==========================\n\n";
    
    echo "⚠️  IMPORTANTE: Você precisa criar uma senha de app no Gmail!\n";
    echo "1. Acesse: https://myaccount.google.com/\n";
    echo "2. Vá em: Segurança → Verificação em duas etapas (ative)\n";
    echo "3. Vá em: Senhas de app\n";
    echo "4. Selecione: Email ou Outro\n";
    echo "5. Digite: Dspay Sistema\n";
    echo "6. Copie a senha gerada (16 caracteres)\n\n";
    
    $email = readline("Digite seu email Gmail: ");
    $senha = readline("Digite a senha de app (16 caracteres): ");
    
    if (strlen($senha) !== 16) {
        echo "❌ Erro: A senha de app deve ter 16 caracteres!\n";
        return;
    }
    
    $config = [
        'MAIL_MAILER=smtp',
        'MAIL_HOST=smtp.gmail.com',
        'MAIL_PORT=587',
        "MAIL_USERNAME={$email}",
        "MAIL_PASSWORD={$senha}",
        "MAIL_FROM_ADDRESS={$email}",
        'MAIL_FROM_NAME=Dspay',
        'MAIL_ENCRYPTION=tls'
    ];
    
    atualizarEnv($config);
    echo "✅ Configuração Gmail salva!\n";
    echo "Execute: php artisan config:clear\n";
    echo "Teste: php artisan test:email {$email}\n";
}

function configurarHostinger() {
    echo "\n📧 CONFIGURAÇÃO HOSTINGER SMTP\n";
    echo "==============================\n\n";
    
    $email = readline("Digite o email (ex: brunodalcum@dspay.com.br): ");
    $senha = readline("Digite a senha: ");
    
    $config = [
        'MAIL_MAILER=smtp',
        'MAIL_HOST=smtp.hostinger.com',
        'MAIL_PORT=587',
        "MAIL_USERNAME={$email}",
        "MAIL_PASSWORD={$senha}",
        "MAIL_FROM_ADDRESS={$email}",
        'MAIL_FROM_NAME=Dspay',
        'MAIL_ENCRYPTION=tls'
    ];
    
    atualizarEnv($config);
    echo "✅ Configuração Hostinger salva!\n";
    echo "Execute: php artisan config:clear\n";
    echo "Teste: php artisan test:email {$email}\n";
}

function configurarLog() {
    echo "\n📧 CONFIGURAÇÃO MODO LOG\n";
    echo "=======================\n\n";
    
    $config = [
        'MAIL_MAILER=log',
        'MAIL_HOST=smtp.gmail.com',
        'MAIL_PORT=587',
        'MAIL_USERNAME=test@example.com',
        'MAIL_PASSWORD=password',
        'MAIL_FROM_ADDRESS=test@example.com',
        'MAIL_FROM_NAME=Dspay',
        'MAIL_ENCRYPTION=tls'
    ];
    
    atualizarEnv($config);
    echo "✅ Modo log configurado!\n";
    echo "Os emails serão salvos em: storage/logs/laravel.log\n";
    echo "Visualize em: http://127.0.0.1:8000/email-logs\n";
}

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
