Write-Host "🔧 Corrigindo problema do manifest Vite..." -ForegroundColor Yellow

# 1. Verificar se estamos no diretório correto
if (-not (Test-Path "public\index.php")) {
    Write-Host "❌ Erro: Execute este script na raiz do projeto Laravel" -ForegroundColor Red
    exit 1
}

# 2. Instalar dependências Node.js se necessário
if (-not (Test-Path "node_modules")) {
    Write-Host "📦 Instalando dependências Node.js..." -ForegroundColor Yellow
    npm ci --production
}

# 3. Build dos assets
Write-Host "🎨 Build dos assets..." -ForegroundColor Yellow
npm run build

# 4. Verificar se o manifest foi criado
if (Test-Path "public\build\manifest.json") {
    Write-Host "✅ Manifest criado com sucesso!" -ForegroundColor Green
    Write-Host "📁 Localização: public\build\manifest.json" -ForegroundColor Cyan
} else {
    Write-Host "❌ Erro: Manifest não foi criado" -ForegroundColor Red
    Write-Host "🔍 Verifique se o Vite está configurado corretamente" -ForegroundColor Yellow
}

Write-Host "✅ Script de correção concluído!" -ForegroundColor Green


