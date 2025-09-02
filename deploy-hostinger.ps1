Write-Host "🚀 Deploy específico para Hostinger - DSPay Orbita" -ForegroundColor Green
Write-Host "==================================================" -ForegroundColor Green

# 1. Verificar se estamos no diretório correto
if (-not (Test-Path "public\index.php")) {
    Write-Host "❌ Erro: Execute este script na raiz do projeto Laravel" -ForegroundColor Red
    exit 1
}

Write-Host "✅ Diretório correto detectado" -ForegroundColor Green

# 2. Instalar dependências
Write-Host "📦 Instalando dependências..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader

# 3. Build dos assets
Write-Host "🎨 Build dos assets..." -ForegroundColor Yellow
npm run build

# 4. Corrigir manifesto Vite
Write-Host "🔧 Corrigindo manifesto Vite..." -ForegroundColor Yellow
if (Test-Path "public\build\.vite\manifest.json") {
    Copy-Item "public\build\.vite\manifest.json" "public\build\manifest.json" -Force
    Write-Host "✅ Manifesto corrigido" -ForegroundColor Green
}

# 5. Limpar caches
Write-Host "🧹 Limpando caches..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 6. Otimizar para produção
Write-Host "⚡ Otimizando para produção..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache

Write-Host "✅ Deploy concluído!" -ForegroundColor Green
Write-Host "📋 IMPORTANTE: Leia o arquivo HOSTINGER_FIX.md para resolver o problema" -ForegroundColor Cyan
Write-Host "🌐 Após corrigir o Document Root, acesse: https://orbita.dspay.com.br" -ForegroundColor Yellow
