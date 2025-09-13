#!/bin/bash

# Script para corrigir erro "Route [dashboard.leads.extract] not defined" em produção
# Execute este script no servidor de produção após fazer o deploy dos arquivos

echo "🔧 Iniciando correção de rotas em produção..."

# Verificar se estamos no diretório correto
if [ ! -f "artisan" ]; then
    echo "❌ Erro: arquivo artisan não encontrado. Execute este script no diretório raiz do Laravel."
    exit 1
fi

echo "📁 Diretório correto encontrado."

# Limpar todos os caches
echo "🧹 Limpando caches..."

php artisan cache:clear
if [ $? -eq 0 ]; then
    echo "✅ Cache geral limpo"
else
    echo "❌ Erro ao limpar cache geral"
fi

php artisan view:clear
if [ $? -eq 0 ]; then
    echo "✅ Cache de views limpo"
else
    echo "❌ Erro ao limpar cache de views"
fi

php artisan route:clear
if [ $? -eq 0 ]; then
    echo "✅ Cache de rotas limpo"
else
    echo "❌ Erro ao limpar cache de rotas"
fi

php artisan config:clear
if [ $? -eq 0 ]; then
    echo "✅ Cache de configuração limpo"
else
    echo "❌ Erro ao limpar cache de configuração"
fi

# Verificar se as rotas places.extract existem
echo "🔍 Verificando rotas places.extract..."
ROUTES_COUNT=$(php artisan route:list | grep -c "places.extract")

if [ "$ROUTES_COUNT" -ge 4 ]; then
    echo "✅ Rotas places.extract encontradas ($ROUTES_COUNT rotas)"
    php artisan route:list | grep "places.extract"
else
    echo "❌ Rotas places.extract não encontradas ou incompletas"
    echo "📋 Rotas disponíveis:"
    php artisan route:list | grep -i places
fi

# Verificar se ainda há referências à rota problemática
echo "🔍 Verificando referências à rota dashboard.leads.extract..."
REFS_COUNT=$(find . -name "*.php" -o -name "*.blade.php" | xargs grep -l "dashboard.leads.extract" 2>/dev/null | grep -v "storage/framework" | wc -l)

if [ "$REFS_COUNT" -eq 0 ]; then
    echo "✅ Nenhuma referência problemática encontrada"
else
    echo "⚠️  Ainda há $REFS_COUNT arquivo(s) com referências à rota dashboard.leads.extract:"
    find . -name "*.php" -o -name "*.blade.php" | xargs grep -l "dashboard.leads.extract" 2>/dev/null | grep -v "storage/framework"
fi

# Otimizar para produção (opcional)
echo "⚡ Otimizando para produção..."

php artisan config:cache
if [ $? -eq 0 ]; then
    echo "✅ Configuração cacheada"
else
    echo "❌ Erro ao cachear configuração"
fi

php artisan route:cache
if [ $? -eq 0 ]; then
    echo "✅ Rotas cacheadas"
else
    echo "❌ Erro ao cachear rotas"
fi

php artisan view:cache
if [ $? -eq 0 ]; then
    echo "✅ Views cacheadas"
else
    echo "❌ Erro ao cachear views"
fi

echo ""
echo "🎉 Correção concluída!"
echo ""
echo "📋 Próximos passos:"
echo "1. Teste o login em: https://srv971263.hstgr.cloud/login"
echo "2. Teste a extração em: https://srv971263.hstgr.cloud/places/extract"
echo "3. Verifique se o menu 'Extrair Leads' funciona corretamente"
echo ""
echo "🔧 Se ainda houver problemas, verifique:"
echo "- Se os arquivos foram atualizados corretamente no servidor"
echo "- Se as permissões dos arquivos estão corretas"
echo "- Se o servidor web foi reiniciado (se necessário)"
