<?php
/**
 * Script para configurar Gmail SMTP rapidamente
 */

echo "📧 CONFIGURAÇÃO GMAIL SMTP - DSPAY\n";
echo "==================================\n\n";

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
    exit;
}

// Configurações
$config = [
    'MAIL_MAILER=smtp',
    'MAIL_HOST=smtp.gmail.com',
    'MAIL_PORT=587',
    "MAIL_USERNAME={$email}",
    "MAIL_PASSWORD={$senha}",
    "MAIL_FROM_ADDRESS={$email}",
    'MAIL_FROM_NAME=Dspay',
    'MAIL_ENCRYPTION=smtp'
];

// Atualizar .env
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

echo "\n✅ Configuração Gmail salva!\n";
echo "Execute os comandos:\n";
echo "php artisan config:clear\n";
echo "php artisan test:email {$email}\n";
echo "\nAgora teste o sistema em: http://127.0.0.1:8000/forgot-password\n";
