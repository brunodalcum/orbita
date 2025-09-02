Write-Host "🔧 Corrigindo local do manifesto Vite..." -ForegroundColor Yellow

# Verificar se o build existe
if (Test-Path "public\build\.vite\manifest.json") {
    # Copiar manifesto para a raiz do build
    Copy-Item "public\build\.vite\manifest.json" "public\build\manifest.json" -Force
    Write-Host "✅ Manifesto copiado para public\build\manifest.json" -ForegroundColor Green
} else {
    Write-Host "❌ Manifesto não encontrado. Execute 'npm run build' primeiro." -ForegroundColor Red
}

Write-Host "🎯 Manifesto corrigido com sucesso!" -ForegroundColor Green

