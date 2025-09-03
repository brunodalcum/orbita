<?php
/**
 * Script de Configuração do Google Calendar
 * Execute este script para configurar as credenciais do Google Calendar
 */

echo "🔧 Configurador do Google Calendar para Orbita\n";
echo "==============================================\n\n";

// Verificar se o arquivo .env existe
$envFile = '.env';
if (!file_exists($envFile)) {
    echo "❌ Arquivo .env não encontrado!\n";
    echo "Crie o arquivo .env na raiz do projeto primeiro.\n";
    exit(1);
}

echo "📁 Arquivo .env encontrado.\n\n";

// Ler o arquivo .env atual
$envContent = file_get_contents($envFile);

// Verificar configurações existentes
$configs = [
    'GOOGLE_SERVICE_ACCOUNT_ENABLED' => false,
    'GOOGLE_CALENDAR_CLIENT_ID' => false,
    'GOOGLE_CALENDAR_CLIENT_SECRET' => false,
    'GOOGLE_CALENDAR_API_KEY' => false,
    'GOOGLE_CALENDAR_ID' => false,
    'GOOGLE_CALENDAR_SCOPES' => false,
    'GOOGLE_CALENDAR_SEND_UPDATES' => false,
    'GOOGLE_MEET_ENABLED' => false,
    'GOOGLE_MEET_DEFAULT_TIMEZONE' => false,
    'GOOGLE_SERVICE_ACCOUNT_FILE' => false,
];

foreach ($configs as $key => $value) {
    if (strpos($envContent, $key) !== false) {
        $configs[$key] = true;
    }
}

echo "📋 Status das configurações:\n";
foreach ($configs as $key => $configured) {
    echo "  " . ($configured ? "✅" : "❌") . " {$key}\n";
}

echo "\n";

// Verificar se todas as configurações estão presentes
$missingConfigs = array_filter($configs, function($value) { return !$value; });

if (empty($missingConfigs)) {
    echo "✅ Todas as configurações do Google Calendar estão presentes!\n";
    echo "Execute 'php artisan test:google-calendar' para testar a integração.\n";
    exit(0);
}

echo "⚠️  Configurações faltando: " . count($missingConfigs) . "\n\n";

// Adicionar configurações faltando
$newConfigs = [
    "",
    "# ========================================",
    "# GOOGLE CALENDAR CONFIGURATION",
    "# ========================================",
    "",
    "# 1. Habilite o Service Account (RECOMENDADO para produção)",
    "GOOGLE_SERVICE_ACCOUNT_ENABLED=true",
    "",
    "# 2. OU use OAuth2 (para desenvolvimento)",
    "# GOOGLE_SERVICE_ACCOUNT_ENABLED=false",
    "# GOOGLE_CALENDAR_CLIENT_ID=seu_client_id_aqui",
    "# GOOGLE_CALENDAR_CLIENT_SECRET=seu_client_secret_aqui",
    "# GOOGLE_CALENDAR_REDIRECT_URI=http://localhost/auth/google/callback",
    "",
    "# 3. API Key (sempre necessária)",
    "GOOGLE_CALENDAR_API_KEY=sua_api_key_aqui",
    "",
    "# 4. Configurações do calendário",
    "GOOGLE_CALENDAR_ID=primary",
    "GOOGLE_CALENDAR_SCOPES=https://www.googleapis.com/auth/calendar,https://www.googleapis.com/auth/calendar.events",
    "GOOGLE_CALENDAR_SEND_UPDATES=all",
    "",
    "# 5. Configurações do Google Meet",
    "GOOGLE_MEET_ENABLED=true",
    "GOOGLE_MEET_DEFAULT_TIMEZONE=America/Sao_Paulo",
    "",
    "# 6. Arquivo de credenciais do Service Account",
    "GOOGLE_SERVICE_ACCOUNT_FILE=storage/app/google-credentials.json",
    "",
    "# ========================================",
    "# INSTRUÇÕES DE CONFIGURAÇÃO",
    "# ========================================",
    "",
    "# 1. Acesse: https://console.cloud.google.com/",
    "# 2. Crie um projeto ou selecione um existente",
    "# 3. Ative a Google Calendar API",
    "# 4. Crie credenciais (Service Account ou OAuth2)",
    "# 5. Para Service Account:",
    "#    - Crie uma conta de serviço",
    "#    - Baixe o arquivo JSON de credenciais",
    "#    - Coloque em storage/app/google-credentials.json",
    "#    - Compartilhe o calendário com o email da conta de serviço",
    "# 6. Para OAuth2:",
    "#    - Configure as URLs de redirecionamento",
    "#    - Obtenha Client ID e Client Secret",
    "# 7. Configure a API Key para autenticação adicional",
    "",
    "# ========================================",
    "# TESTE DA INTEGRAÇÃO",
    "# ========================================",
    "",
    "# Execute: php artisan test:google-calendar",
    "# Para detalhes: php artisan test:google-calendar --verbose",
];

echo "🔧 Adicionando configurações ao arquivo .env...\n";

// Adicionar ao final do arquivo
$newContent = $envContent . "\n" . implode("\n", $newConfigs);

if (file_put_contents($envFile, $newContent)) {
    echo "✅ Configurações adicionadas com sucesso!\n\n";
    
    echo "📝 PRÓXIMOS PASSOS:\n";
    echo "1. Configure suas credenciais reais no arquivo .env\n";
    echo "2. Para Service Account: coloque o arquivo JSON em storage/app/google-credentials.json\n";
    echo "3. Para OAuth2: configure GOOGLE_CALENDAR_CLIENT_ID e GOOGLE_CALENDAR_CLIENT_SECRET\n";
    echo "4. Configure GOOGLE_CALENDAR_API_KEY\n";
    echo "5. Execute: php artisan test:google-calendar\n\n";
    
    echo "📚 Para mais detalhes, consulte o arquivo GOOGLE_CALENDAR_CONFIG.env\n";
} else {
    echo "❌ Erro ao escrever no arquivo .env\n";
    echo "Verifique as permissões do arquivo.\n";
    exit(1);
}
