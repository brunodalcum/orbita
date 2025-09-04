#!/bin/bash

# Script simples para corrigir erro 419 (CSRF Token Mismatch)
# Execute este script no servidor de produção

echo "🔧 Corrigindo erro 419 (CSRF Token Mismatch)..."

# 1. Limpar caches
echo "🧹 Limpando caches..."
php artisan cache:clear
php artisan config:clear
php artisan session:clear

# 2. Verificar e corrigir APP_KEY
echo "🔑 Verificando APP_KEY..."
if ! grep -q "APP_KEY=base64:" .env || grep -q "APP_KEY=$" .env; then
    echo "Gerando nova APP_KEY..."
    php artisan key:generate
else
    echo "APP_KEY já está configurada"
fi

# 3. Configurar sessões
echo "⚙️ Configurando sessões..."
# Remover configurações existentes
sed -i '/SESSION_DRIVER/d' .env
sed -i '/SESSION_LIFETIME/d' .env

# Adicionar configurações corretas
echo "" >> .env
echo "# Configurações de sessão para resolver erro 419" >> .env
echo "SESSION_DRIVER=file" >> .env
echo "SESSION_LIFETIME=120" >> .env

# 4. Limpar sessões antigas
echo "🗑️ Limpando sessões antigas..."
rm -rf storage/framework/sessions/*

# 5. Corrigir permissões
echo "🔐 Corrigindo permissões..."
chmod -R 755 storage/framework/sessions
chmod -R 755 storage/framework/cache
chmod -R 755 storage/logs

# 6. Regenerar caches
echo "🔄 Regenerando caches..."
php artisan config:cache
php artisan route:cache

# 7. Testar
echo "🧪 Testando configuração..."
if php artisan --version > /dev/null 2>&1; then
    echo "✅ Laravel funcionando"
else
    echo "❌ Problema com Laravel"
fi

echo ""
echo "🎉 Correção do erro 419 concluída!"
echo ""
echo "📋 Configurações aplicadas:"
echo "- APP_KEY gerada/verificada"
echo "- SESSION_DRIVER=file"
echo "- SESSION_LIFETIME=120"
echo "- Sessões antigas removidas"
echo "- Permissões corrigidas"
echo ""
echo "✅ Agora teste o login: https://srv971263.hstgr.cloud/login"
echo ""
echo "🔍 Se ainda houver problemas:"
echo "1. Limpe o cache do navegador (Ctrl+F5)"
echo "2. Tente em uma aba anônima"
echo "3. Verifique se o JavaScript está habilitado"
echo "4. Certifique-se de que o formulário tem o token CSRF"
