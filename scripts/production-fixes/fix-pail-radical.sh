#!/bin/bash

# Solução radical para o problema do Laravel Pail
# Remove completamente as referências ao Pail

echo "🔥 SOLUÇÃO RADICAL - Removendo Laravel Pail"
echo "==========================================="

# 1. Criar diretórios necessários
echo "📁 Criando diretórios..."
mkdir -p bootstrap/cache storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs
chmod -R 755 bootstrap/cache storage

# 2. Remover TODOS os caches
echo "🗑️  Removendo todos os caches..."
rm -rf bootstrap/cache/*
rm -rf storage/framework/cache/*
rm -rf storage/framework/views/*

# 3. Remover Pail do composer.json temporariamente
echo "📦 Removendo Pail do composer.json..."
if [ -f "composer.json.backup" ]; then
    echo "   ⚠️  Backup já existe, pulando..."
else
    cp composer.json composer.json.backup
    echo "   ✅ Backup criado: composer.json.backup"
fi

# Remover linha do Pail do composer.json
sed -i.tmp '/laravel\/pail/d' composer.json
echo "   ✅ Laravel Pail removido do composer.json"

# 4. Recriar autoload sem Pail
echo "🔄 Recriando autoload..."
composer dump-autoload --optimize

# 5. Testar Laravel
echo "🧪 Testando Laravel..."
if php artisan --version > /dev/null 2>&1; then
    echo "   ✅ Laravel funcionando!"
    php artisan --version
    
    # 6. Recriar caches
    echo "⚡ Recriando caches..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
else
    echo "   ❌ Laravel ainda com problemas"
fi

# 7. Restaurar composer.json (opcional)
echo ""
echo "🔄 Para restaurar o Pail depois (APENAS EM DESENVOLVIMENTO):"
echo "   mv composer.json.backup composer.json"
echo "   composer install"
echo ""

echo "✅ Solução radical aplicada!"
echo "🔗 Teste: https://srv971263.hstgr.cloud/login"
echo ""
echo "⚠️  IMPORTANTE:"
echo "   - O Laravel Pail foi removido PERMANENTEMENTE"
echo "   - Isso é CORRETO para produção"
echo "   - Em desenvolvimento, você pode restaurar com:"
echo "     mv composer.json.backup composer.json && composer install"
