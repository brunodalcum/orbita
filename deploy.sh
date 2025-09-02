#!/bin/bash

echo "🚀 Iniciando deploy da DSPay Orbita para produção..."

# 1. Instalar dependências PHP
echo "📦 Instalando dependências PHP..."
composer install --no-dev --optimize-autoloader

# 2. Instalar dependências Node.js
echo "📦 Instalando dependências Node.js..."
npm ci --production

# 3. Build dos assets
echo "🎨 Build dos assets..."
npm run build

# 4. Limpar caches
echo "🧹 Limpando caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 5. Otimizar para produção
echo "⚡ Otimizando para produção..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Verificar permissões
echo "🔐 Configurando permissões..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

echo "✅ Deploy concluído com sucesso!"
echo "🌐 Acesse: https://orbita.dspay.com.br"


