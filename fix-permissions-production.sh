#!/bin/bash

# Script para corrigir permissões em produção
# Execute este script no servidor de produção

echo "🔧 Corrigindo permissões em produção..."

# 1. Verificar se estamos no diretório correto
if [ ! -f "artisan" ]; then
    echo "❌ Erro: Execute este script no diretório raiz do Laravel"
    exit 1
fi

echo "✅ Diretório correto encontrado"

# 2. Parar serviços que podem estar usando os arquivos
echo "⏹️ Parando serviços..."
sudo systemctl stop apache2 2>/dev/null || true
sudo systemctl stop nginx 2>/dev/null || true
sudo systemctl stop php8.1-fpm 2>/dev/null || true
sudo systemctl stop php8.2-fpm 2>/dev/null || true

# 3. Limpar todos os caches
echo "🧹 Limpando caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# 4. Remover diretórios de cache problemáticos
echo "🗑️ Removendo diretórios de cache..."
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*
rm -rf bootstrap/cache/*

# 5. Recriar diretórios com permissões corretas
echo "📁 Recriando diretórios..."
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# 6. Definir permissões corretas
echo "🔐 Definindo permissões..."

# Permissões para diretórios
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Permissões específicas para cache de views
chmod -R 777 storage/framework/views
chmod -R 777 storage/framework/cache
chmod -R 777 storage/framework/sessions
chmod -R 777 storage/logs

# 7. Definir proprietário correto
echo "👤 Definindo proprietário..."

# Tentar diferentes usuários web comuns
if id "www-data" &>/dev/null; then
    chown -R www-data:www-data storage
    chown -R www-data:www-data bootstrap/cache
    echo "✅ Proprietário definido como www-data"
elif id "apache" &>/dev/null; then
    chown -R apache:apache storage
    chown -R apache:apache bootstrap/cache
    echo "✅ Proprietário definido como apache"
elif id "nginx" &>/dev/null; then
    chown -R nginx:nginx storage
    chown -R nginx:nginx bootstrap/cache
    echo "✅ Proprietário definido como nginx"
else
    # Se não conseguir identificar o usuário web, usar o usuário atual
    CURRENT_USER=$(whoami)
    chown -R $CURRENT_USER:$CURRENT_USER storage
    chown -R $CURRENT_USER:$CURRENT_USER bootstrap/cache
    echo "✅ Proprietário definido como $CURRENT_USER"
fi

# 8. Verificar se o usuário web tem acesso
echo "🔍 Verificando acesso do usuário web..."

# Testar escrita no diretório de views
TEST_FILE="storage/framework/views/test_permissions.tmp"
if touch "$TEST_FILE" 2>/dev/null; then
    rm -f "$TEST_FILE"
    echo "✅ Permissões de escrita funcionando"
else
    echo "❌ Ainda há problemas de permissão"
    
    # Tentar com sudo se disponível
    if command -v sudo &> /dev/null; then
        echo "🔧 Tentando com sudo..."
        sudo chmod -R 777 storage/framework/views
        sudo chown -R www-data:www-data storage/framework/views 2>/dev/null || true
        sudo chown -R apache:apache storage/framework/views 2>/dev/null || true
        sudo chown -R nginx:nginx storage/framework/views 2>/dev/null || true
    fi
fi

# 9. Regenerar caches
echo "🔄 Regenerando caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 10. Verificar se funcionou
echo "🧪 Testando se as permissões estão corretas..."
if php artisan view:clear 2>/dev/null; then
    echo "✅ Cache de views funcionando corretamente"
else
    echo "❌ Ainda há problemas com cache de views"
    echo "🔧 Tentando solução alternativa..."
    
    # Solução alternativa: desabilitar cache de views temporariamente
    echo "APP_VIEW_CACHE=false" >> .env
    echo "⚠️ Cache de views desabilitado temporariamente"
fi

# 11. Reiniciar serviços web
echo "🔄 Reiniciando serviços web..."
sudo systemctl start apache2 2>/dev/null || true
sudo systemctl start nginx 2>/dev/null || true
sudo systemctl start php8.1-fpm 2>/dev/null || true
sudo systemctl start php8.2-fpm 2>/dev/null || true

# 12. Verificação final
echo ""
echo "🎉 Correção de permissões concluída!"
echo ""
echo "📋 Verificações finais:"
echo "1. Permissões do storage:"
ls -la storage/ | head -5

echo ""
echo "2. Permissões do bootstrap/cache:"
ls -la bootstrap/cache/

echo ""
echo "3. Teste de escrita:"
if touch storage/framework/views/test_final.tmp 2>/dev/null; then
    rm -f storage/framework/views/test_final.tmp
    echo "✅ Permissões de escrita funcionando"
else
    echo "❌ Ainda há problemas - execute com sudo se necessário"
fi

echo ""
echo "🔍 Se ainda houver problemas:"
echo "1. Verifique o usuário web: ps aux | grep -E '(apache|nginx|php-fpm)'"
echo "2. Verifique o grupo: groups \$(whoami)"
echo "3. Execute: sudo chmod -R 777 storage/framework/views"
echo "4. Execute: sudo chown -R www-data:www-data storage/framework/views"
