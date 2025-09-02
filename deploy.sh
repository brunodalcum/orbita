#!/bin/bash

echo "🚀 Iniciando deploy da DSPay Orbita para produção..."

# 1. Instalar dependências
echo "📦 Instalando dependências..."
composer install --no-dev --optimize-autoloader

# 2. Build dos assets
echo "🎨 Build dos assets..."
npm run build

# 3. Limpar caches
echo "🧹 Limpando caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 4. Otimizar para produção
echo "⚡ Otimizando para produção..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Verificar permissões
echo "🔐 Configurando permissões..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

echo "✅ Deploy concluído com sucesso!"
echo "🌐 Acesse: https://orbita.dspay.com.br"

