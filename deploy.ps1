Write-Host "🚀 Iniciando deploy da DSPay Orbita para produção..." -ForegroundColor Green

# 1. Instalar dependências
Write-Host "📦 Instalando dependências..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader

# 2. Build dos assets
Write-Host "🎨 Build dos assets..." -ForegroundColor Yellow
npm run build

# 3. Corrigir manifesto Vite
Write-Host "🔧 Corrigindo manifesto Vite..." -ForegroundColor Yellow
if (Test-Path "public\build\.vite\manifest.json") {
    Copy-Item "public\build\.vite\manifest.json" "public\build\manifest.json" -Force
    Write-Host "✅ Manifesto corrigido" -ForegroundColor Green
}

# 4. Limpar caches
Write-Host "🧹 Limpando caches..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 5. Otimizar para produção
Write-Host "⚡ Otimizando para produção..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Verificar permissões (Windows)
Write-Host "🔐 Configurando permissões..." -ForegroundColor Yellow
# No Windows, as permissões são gerenciadas pelo sistema

Write-Host "✅ Deploy concluído com sucesso!" -ForegroundColor Green
Write-Host "🌐 Acesse: https://orbita.dspay.com.br" -ForegroundColor Cyan
