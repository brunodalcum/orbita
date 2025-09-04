#!/bin/bash

# Solução simples para problemas de permissões em produção
# Execute este script no servidor de produção

echo "🔧 Corrigindo permissões em produção..."

# 1. Limpar caches
echo "🧹 Limpando caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. Remover arquivos de cache problemáticos
echo "🗑️ Removendo arquivos de cache..."
rm -rf storage/framework/views/*
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf bootstrap/cache/*

# 3. Corrigir permissões
echo "🔐 Corrigindo permissões..."
chmod -R 777 storage
chmod -R 777 bootstrap/cache

# 4. Desabilitar cache de views
echo "⚙️ Desabilitando cache de views..."
# Remover linhas existentes
sed -i '/VIEW_CACHE_ENABLED/d' .env
sed -i '/CACHE_DRIVER/d' .env
sed -i '/SESSION_DRIVER/d' .env

# Adicionar novas configurações
echo "" >> .env
echo "# Configurações para resolver problemas de permissão" >> .env
echo "VIEW_CACHE_ENABLED=false" >> .env
echo "CACHE_DRIVER=array" >> .env
echo "SESSION_DRIVER=array" >> .env

# 5. Regenerar caches
echo "🔄 Regenerando caches..."
php artisan config:cache
php artisan route:cache

# 6. Testar se funcionou
echo "🧪 Testando permissões..."
if touch storage/framework/views/test.tmp 2>/dev/null; then
    rm -f storage/framework/views/test.tmp
    echo "✅ Permissões funcionando!"
else
    echo "❌ Ainda há problemas de permissão"
    echo "🔧 Tentando com permissões mais permissivas..."
    chmod -R 777 storage/framework/views
fi

echo ""
echo "🎉 Correção concluída!"
echo ""
echo "📋 Configurações aplicadas:"
echo "- VIEW_CACHE_ENABLED=false"
echo "- CACHE_DRIVER=array"
echo "- SESSION_DRIVER=array"
echo "- Permissões: 777"
echo ""
echo "✅ Agora teste o dashboard: https://srv971263.hstgr.cloud/dashboard"
