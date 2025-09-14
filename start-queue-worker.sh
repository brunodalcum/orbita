#!/bin/bash

# Script para manter o worker da fila rodando
# Este script deve ser executado em background para processar os jobs automaticamente

cd /Applications/MAMP/htdocs/orbita

echo "🚀 Iniciando worker da fila para processar lembretes..."
echo "📅 Data/Hora: $(date)"
echo "📂 Diretório: $(pwd)"

# Executar o worker da fila em loop
while true; do
    echo "⚡ Iniciando worker da fila..."
    php artisan queue:work --sleep=3 --tries=3 --max-time=3600 --timeout=60
    
    # Se o worker parar por algum motivo, aguardar 5 segundos e reiniciar
    echo "⚠️  Worker parou. Reiniciando em 5 segundos..."
    sleep 5
done
