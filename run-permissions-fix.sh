#!/bin/bash

echo "🚀 EXECUTANDO CORREÇÃO DAS PERMISSÕES EM PRODUÇÃO"
echo "=================================================="
echo ""

# 1. Executar migrações
echo "1. Executando migrações..."
php artisan migrate --force
echo ""

# 2. Popular permissões
echo "2. Populando permissões..."
php artisan db:seed --class=PermissionSeeder --force
echo ""

# 3. Limpar caches
echo "3. Limpando caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
echo ""

# 4. Recriar caches
echo "4. Recriando caches..."
php artisan config:cache
php artisan route:cache
echo ""

echo "✅ CORREÇÃO CONCLUÍDA!"
echo "🌐 Teste agora acessar o sistema"
echo "=================================================="
