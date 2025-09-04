#!/bin/bash

# Script para FORÇAR permissões em produção
# Execute este script no servidor de produção

echo "🔧 FORÇANDO permissões em produção..."

# 1. Parar serviços web
echo "⏹️ Parando serviços web..."
sudo systemctl stop apache2 2>/dev/null || true
sudo systemctl stop nginx 2>/dev/null || true
sudo systemctl stop php8.1-fpm 2>/dev/null || true
sudo systemctl stop php8.2-fpm 2>/dev/null || true

# 2. Limpar caches
echo "🧹 Limpando caches..."
php artisan cache:clear 2>/dev/null || true
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# 3. Remover TUDO dos diretórios problemáticos
echo "🗑️ Removendo arquivos problemáticos..."
sudo rm -rf storage/framework/views/*
sudo rm -rf storage/framework/cache/*
sudo rm -rf storage/framework/sessions/*
sudo rm -rf storage/logs/*
sudo rm -rf bootstrap/cache/*

# 4. FORÇAR permissões 777
echo "🔐 FORÇANDO permissões 777..."
sudo chmod -R 777 storage
sudo chmod -R 777 bootstrap/cache

# 5. FORÇAR proprietário www-data
echo "👤 FORÇANDO proprietário www-data..."
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache

# 6. Configurar cache em memória
echo "⚙️ Configurando cache em memória..."
# Remover configurações existentes
sed -i '/VIEW_CACHE_ENABLED/d' .env
sed -i '/CACHE_DRIVER/d' .env
sed -i '/SESSION_DRIVER/d' .env
sed -i '/LOG_CHANNEL/d' .env

# Adicionar configurações
echo "" >> .env
echo "# Configurações para resolver problemas de permissão" >> .env
echo "VIEW_CACHE_ENABLED=false" >> .env
echo "CACHE_DRIVER=array" >> .env
echo "SESSION_DRIVER=array" >> .env
echo "LOG_CHANNEL=stack" >> .env

# 7. Gerar nova APP_KEY
echo "🔑 Gerando nova APP_KEY..."
php artisan key:generate --force

# 8. Testar permissões
echo "🧪 Testando permissões..."
if touch storage/logs/test.log 2>/dev/null; then
    rm -f storage/logs/test.log
    echo "✅ Permissões de log funcionando"
else
    echo "❌ Problema com logs"
fi

if touch storage/framework/sessions/test.session 2>/dev/null; then
    rm -f storage/framework/sessions/test.session
    echo "✅ Permissões de sessão funcionando"
else
    echo "❌ Problema com sessões"
fi

# 9. Regenerar caches
echo "🔄 Regenerando caches..."
php artisan config:cache
php artisan route:cache

# 10. Reiniciar serviços
echo "🔄 Reiniciando serviços..."
sudo systemctl start apache2 2>/dev/null || true
sudo systemctl start nginx 2>/dev/null || true
sudo systemctl start php8.1-fpm 2>/dev/null || true
sudo systemctl start php8.2-fpm 2>/dev/null || true

echo ""
echo "🎉 FORÇA aplicada com sucesso!"
echo ""
echo "📋 Configurações aplicadas:"
echo "- Permissões: 777 (máxima)"
echo "- Proprietário: www-data"
echo "- Cache: em memória (array)"
echo "- Sessões: em memória (array)"
echo "- Logs: stack"
echo ""
echo "✅ Teste agora:"
echo "1. Login: https://srv971263.hstgr.cloud/login"
echo "2. Dashboard: https://srv971263.hstgr.cloud/dashboard"
echo ""
echo "🔍 Se ainda houver problemas:"
echo "1. Verifique se o usuário web é www-data"
echo "2. Execute: ps aux | grep -E '(apache|nginx|php-fpm)'"
echo "3. Contate o suporte do hosting"
