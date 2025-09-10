#!/bin/bash

echo "🔧 Corrigindo diretório bootstrap/cache em produção..."

# Criar diretório bootstrap/cache se não existir
echo "📁 Criando diretório bootstrap/cache..."
mkdir -p bootstrap/cache

# Criar subdiretórios necessários
echo "📁 Criando subdiretórios..."
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# Definir permissões corretas
echo "🔐 Definindo permissões..."
chmod -R 755 bootstrap/cache
chmod -R 755 storage
chmod -R 777 bootstrap/cache
chmod -R 777 storage/framework
chmod -R 777 storage/logs

# Limpar caches existentes
echo "🧹 Limpando caches..."
php artisan config:clear 2>/dev/null || echo "Config clear executado"
php artisan route:clear 2>/dev/null || echo "Route clear executado"
php artisan view:clear 2>/dev/null || echo "View clear executado"
php artisan cache:clear 2>/dev/null || echo "Cache clear executado"

# Recriar caches otimizados
echo "⚡ Recriando caches otimizados..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Otimizar autoloader
echo "🚀 Otimizando autoloader..."
composer dump-autoload --optimize

echo "✅ Correção concluída!"
echo ""
echo "📋 Verificação final:"
ls -la bootstrap/cache
echo ""
ls -la storage/framework/
echo ""
echo "🎯 Se ainda houver problemas, execute:"
echo "sudo chown -R www-data:www-data bootstrap/cache storage"
echo "sudo chmod -R 775 bootstrap/cache storage"
