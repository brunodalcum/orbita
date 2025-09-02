Write-Host "🚀 Iniciando deploy da DSPay Orbita para produção..." -ForegroundColor Green

# 1. Instalar dependências PHP
Write-Host "📦 Instalando dependências PHP..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader

# 2. Instalar dependências Node.js
Write-Host "📦 Instalando dependências Node.js..." -ForegroundColor Yellow
npm ci --production

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

# 7. Verificar permissões (Windows)
Write-Host "🔐 Configurando permissões..." -ForegroundColor Yellow
# No Windows, as permissões são gerenciadas pelo sistema

Write-Host "✅ Deploy concluído com sucesso!" -ForegroundColor Green
Write-Host "🌐 Acesse: https://orbita.dspay.com.br" -ForegroundColor Cyan
