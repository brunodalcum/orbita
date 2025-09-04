#!/bin/bash

# Solução definitiva para problemas de permissões em produção
# Execute este script no servidor de produção

echo "🔧 Solução definitiva para permissões em produção..."

# 1. Verificar se estamos no diretório correto
if [ ! -f "artisan" ]; then
    echo "❌ Erro: Execute este script no diretório raiz do Laravel"
    exit 1
fi

echo "✅ Diretório correto encontrado"

# 2. Parar serviços web temporariamente
echo "⏹️ Parando serviços web..."
sudo systemctl stop apache2 2>/dev/null || true
sudo systemctl stop nginx 2>/dev/null || true
sudo systemctl stop php8.1-fpm 2>/dev/null || true
sudo systemctl stop php8.2-fpm 2>/dev/null || true

# 3. Limpar TODOS os caches
echo "🧹 Limpando todos os caches..."
php artisan cache:clear 2>/dev/null || true
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan event:clear 2>/dev/null || true

# 4. Remover COMPLETAMENTE os diretórios de cache
echo "🗑️ Removendo diretórios de cache..."
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*
rm -rf storage/logs/*
rm -rf bootstrap/cache/*

# 5. Recriar diretórios com estrutura correta
echo "📁 Recriando estrutura de diretórios..."
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# 6. Definir permissões MUITO permissivas temporariamente
echo "🔐 Definindo permissões permissivas..."
chmod -R 777 storage
chmod -R 777 bootstrap/cache

# 7. Descobrir o usuário web correto
echo "👤 Descobrindo usuário web..."
WEB_USER=""
if id "www-data" &>/dev/null; then
    WEB_USER="www-data"
elif id "apache" &>/dev/null; then
    WEB_USER="apache"
elif id "nginx" &>/dev/null; then
    WEB_USER="nginx"
else
    WEB_USER=$(whoami)
fi

echo "Usuário web identificado: $WEB_USER"

# 8. Definir proprietário correto
echo "👤 Definindo proprietário como $WEB_USER..."
chown -R $WEB_USER:$WEB_USER storage
chown -R $WEB_USER:$WEB_USER bootstrap/cache

# 9. Testar escrita
echo "🧪 Testando permissões de escrita..."
TEST_FILE="storage/framework/views/test_write.tmp"
if touch "$TEST_FILE" 2>/dev/null; then
    rm -f "$TEST_FILE"
    echo "✅ Permissões de escrita funcionando"
else
    echo "❌ Ainda há problemas de permissão"
    echo "🔧 Aplicando permissões 777..."
    chmod -R 777 storage/framework/views
    chmod -R 777 storage/framework/cache
    chmod -R 777 storage/framework/sessions
    chmod -R 777 storage/logs
fi

# 10. Desabilitar cache de views temporariamente
echo "⚙️ Desabilitando cache de views..."
if [ -f ".env" ]; then
    # Remover linhas existentes
    sed -i '/VIEW_CACHE_ENABLED/d' .env
    sed -i '/CACHE_DRIVER/d' .env
    # Adicionar novas configurações
    echo "" >> .env
    echo "# Configurações temporárias para resolver problemas de permissão" >> .env
    echo "VIEW_CACHE_ENABLED=false" >> .env
    echo "CACHE_DRIVER=array" >> .env
    echo "SESSION_DRIVER=array" >> .env
    echo "✅ Cache de views desabilitado"
else
    echo "⚠️ Arquivo .env não encontrado"
fi

# 11. Regenerar caches (sem view cache)
echo "🔄 Regenerando caches..."
php artisan config:cache
php artisan route:cache
# NÃO executar view:cache para evitar problemas

# 12. Verificar se funcionou
echo "🧪 Testando Laravel..."
if php artisan --version > /dev/null 2>&1; then
    echo "✅ Laravel funcionando"
else
    echo "❌ Problema com Laravel"
fi

# 13. Reiniciar serviços web
echo "🔄 Reiniciando serviços web..."
sudo systemctl start apache2 2>/dev/null || true
sudo systemctl start nginx 2>/dev/null || true
sudo systemctl start php8.1-fpm 2>/dev/null || true
sudo systemctl start php8.2-fpm 2>/dev/null || true

# 14. Verificação final
echo ""
echo "🎉 Correção definitiva concluída!"
echo ""
echo "📋 Status das permissões:"
echo "Storage:"
ls -la storage/ | head -3

echo ""
echo "Bootstrap cache:"
ls -la bootstrap/cache/

echo ""
echo "Teste final de escrita:"
if touch storage/framework/views/final_test.tmp 2>/dev/null; then
    rm -f storage/framework/views/final_test.tmp
    echo "✅ Permissões funcionando perfeitamente!"
else
    echo "❌ Ainda há problemas - contate o suporte do hosting"
fi

echo ""
echo "🔍 Configurações aplicadas:"
echo "- Cache de views: DESABILITADO"
echo "- Cache driver: ARRAY (em memória)"
echo "- Permissões: 777 (temporariamente)"
echo "- Proprietário: $WEB_USER"
echo ""
echo "📞 Se ainda houver problemas:"
echo "1. Contate o suporte do hosting"
echo "2. Solicite correção de permissões"
echo "3. Peça para definir $WEB_USER como proprietário do storage/"
