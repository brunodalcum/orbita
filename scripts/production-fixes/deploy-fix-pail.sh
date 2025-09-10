#!/bin/bash

# Script para corrigir erro do Laravel Pail em produção
# Execute este script no servidor de produção

echo "🚀 Iniciando correção do erro Laravel Pail em produção..."
echo "📅 Data: $(date)"
echo "📍 Diretório: $(pwd)"
echo ""

# Verificar se estamos no diretório correto
if [ ! -f "artisan" ]; then
    echo "❌ ERRO: Arquivo 'artisan' não encontrado!"
    echo "💡 Certifique-se de estar no diretório raiz do projeto Laravel"
    exit 1
fi

echo "✅ Diretório do projeto Laravel confirmado"
echo ""

# 1. Backup dos caches atuais (caso necessário reverter)
echo "📦 1. Fazendo backup dos caches atuais..."
if [ -d "bootstrap/cache" ]; then
    cp -r bootstrap/cache bootstrap/cache_backup_$(date +%Y%m%d_%H%M%S) 2>/dev/null || true
    echo "   ✅ Backup criado"
else
    echo "   ℹ️  Diretório de cache não existe"
fi

# 2. Limpar todos os caches
echo ""
echo "🧹 2. Limpando todos os caches do Laravel..."
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 3. Remover caches específicos que podem conter referências ao Pail
echo ""
echo "🗑️  3. Removendo caches problemáticos..."
rm -f bootstrap/cache/services.php
rm -f bootstrap/cache/packages.php
rm -f bootstrap/cache/config.php
echo "   ✅ Caches removidos"

# 4. Recriar autoload
echo ""
echo "🔄 4. Recriando autoload do composer..."
composer dump-autoload --optimize --no-dev

# 5. Otimizar para produção (sem pacotes de desenvolvimento)
echo ""
echo "⚡ 5. Otimizando para produção..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Verificar se o site está funcionando
echo ""
echo "🔍 6. Verificando funcionamento..."
if php artisan --version > /dev/null 2>&1; then
    echo "   ✅ Laravel está funcionando"
    php artisan --version
else
    echo "   ❌ Erro ao executar artisan"
    exit 1
fi

echo ""
echo "🎉 Correção concluída com sucesso!"
echo ""
echo "🔗 URLs para testar:"
echo "   - Login: https://srv971263.hstgr.cloud/login"
echo "   - Dashboard: https://srv971263.hstgr.cloud/dashboard"
echo "   - Agenda: https://srv971263.hstgr.cloud/agenda"
echo ""
echo "💡 Dicas:"
echo "   - O Laravel Pail agora só será carregado em desenvolvimento"
echo "   - Em produção, use 'tail -f storage/logs/laravel.log' para ver logs"
echo "   - Se houver problemas, restaure o backup: mv bootstrap/cache_backup_* bootstrap/cache"
echo ""
