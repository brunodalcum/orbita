#!/bin/bash

# Solução rápida para o erro de permissões em produção
# Execute este script no servidor de produção

echo "🚀 Solução rápida para erro de permissões..."

# 1. Limpar cache de views
echo "🧹 Limpando cache de views..."
php artisan view:clear

# 2. Remover arquivos de cache problemáticos
echo "🗑️ Removendo arquivos de cache..."
rm -rf storage/framework/views/*

# 3. Definir permissões corretas
echo "🔐 Corrigindo permissões..."
chmod -R 777 storage/framework/views
chmod -R 777 storage/framework/cache
chmod -R 777 storage/framework/sessions
chmod -R 777 storage/logs

# 4. Tentar diferentes usuários web
echo "👤 Definindo proprietário..."
chown -R www-data:www-data storage/framework/views 2>/dev/null || \
chown -R apache:apache storage/framework/views 2>/dev/null || \
chown -R nginx:nginx storage/framework/views 2>/dev/null || \
chown -R $(whoami):$(whoami) storage/framework/views

# 5. Testar se funcionou
echo "🧪 Testando..."
if php artisan view:clear; then
    echo "✅ Problema resolvido!"
else
    echo "❌ Ainda há problemas. Execute com sudo:"
    echo "sudo chmod -R 777 storage/framework/views"
    echo "sudo chown -R www-data:www-data storage/framework/views"
fi
