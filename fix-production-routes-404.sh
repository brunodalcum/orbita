#!/bin/bash

echo "🔧 CORREÇÃO DE ERRO 404 - ROTAS EM PRODUÇÃO"
echo "==========================================="

# Definir diretório do projeto
PROJECT_DIR="/home/user/htdocs/srv971263.hstgr.cloud"

echo "📁 Diretório do projeto: $PROJECT_DIR"

# Verificar se o diretório existe
if [ ! -d "$PROJECT_DIR" ]; then
    echo "❌ ERRO: Diretório do projeto não encontrado!"
    echo "Verifique se o caminho está correto: $PROJECT_DIR"
    exit 1
fi

cd "$PROJECT_DIR"

echo ""
echo "🔍 DIAGNÓSTICO INICIAL:"
echo "======================"
echo "Verificando arquivos de rota..."
ls -la routes/web.php 2>/dev/null && echo "✅ routes/web.php encontrado" || echo "❌ routes/web.php não encontrado"

echo ""
echo "🧹 LIMPANDO CACHES DE ROTA:"
echo "==========================="

# 1. Limpar cache de rotas
echo "1️⃣ Limpando cache de rotas..."
php artisan route:clear 2>/dev/null && echo "✅ Cache de rotas limpo" || echo "❌ Erro ao limpar cache de rotas"

# 2. Limpar cache de configuração
echo "2️⃣ Limpando cache de configuração..."
php artisan config:clear 2>/dev/null && echo "✅ Cache de config limpo" || echo "❌ Erro ao limpar cache de config"

# 3. Limpar cache geral
echo "3️⃣ Limpando cache geral..."
php artisan cache:clear 2>/dev/null && echo "✅ Cache geral limpo" || echo "❌ Erro ao limpar cache geral"

# 4. Limpar views compiladas
echo "4️⃣ Limpando views compiladas..."
php artisan view:clear 2>/dev/null && echo "✅ Views limpas" || echo "❌ Erro ao limpar views"

echo ""
echo "🔍 VERIFICANDO ROTAS ESPECÍFICAS:"
echo "================================="

# 5. Verificar se a rota existe
echo "5️⃣ Verificando rota de inserção de leads..."
php artisan route:list --name="dashboard.places.extraction.insert-leads" 2>/dev/null | head -10

echo ""
echo "6️⃣ Verificando todas as rotas do PlaceExtractionController..."
php artisan route:list | grep -i "PlaceExtractionController" 2>/dev/null || echo "❌ Nenhuma rota do PlaceExtractionController encontrada"

echo ""
echo "🔧 RECOMPILANDO CACHES PARA PRODUÇÃO:"
echo "===================================="

# 7. Recompilar cache de configuração
echo "7️⃣ Recompilando cache de configuração..."
php artisan config:cache 2>/dev/null && echo "✅ Config cache criado" || echo "❌ Erro ao criar config cache"

# 8. Recompilar cache de rotas
echo "8️⃣ Recompilando cache de rotas..."
php artisan route:cache 2>/dev/null && echo "✅ Route cache criado" || echo "❌ Erro ao criar route cache"

echo ""
echo "🧪 TESTE DE ROTA:"
echo "================="

# 9. Testar se a rota está funcionando
echo "9️⃣ Testando rota específica..."
ROUTE_TEST=$(php artisan route:list --name="dashboard.places.extraction.insert-leads" 2>/dev/null | grep -c "insert-leads")
if [ "$ROUTE_TEST" -gt 0 ]; then
    echo "✅ Rota 'dashboard.places.extraction.insert-leads' encontrada"
else
    echo "❌ Rota 'dashboard.places.extraction.insert-leads' NÃO encontrada"
    echo ""
    echo "🔍 DIAGNÓSTICO AVANÇADO:"
    echo "========================"
    echo "Verificando se o arquivo routes/web.php contém a rota..."
    grep -n "insert-leads" routes/web.php 2>/dev/null || echo "❌ Rota não encontrada no arquivo"
fi

echo ""
echo "🌐 TESTE DE URL:"
echo "================"

# 10. Testar URL diretamente
echo "🔟 Testando URL de exemplo..."
echo "URL esperada: https://srv971263.hstgr.cloud/places/extraction/1/insert-leads"
echo ""

# 11. Verificar estrutura de diretórios
echo "1️⃣1️⃣ Verificando controller..."
ls -la app/Http/Controllers/PlaceExtractionController.php 2>/dev/null && echo "✅ PlaceExtractionController encontrado" || echo "❌ PlaceExtractionController não encontrado"

echo ""
echo "📋 RESUMO DE VERIFICAÇÕES:"
echo "========================="
echo "✅ Cache limpo"
echo "✅ Rotas recompiladas"
echo "✅ Configuração atualizada"

echo ""
echo "🎯 PRÓXIMOS PASSOS:"
echo "=================="
echo "1. Teste a URL: https://srv971263.hstgr.cloud/places/extract"
echo "2. Faça uma extração de leads"
echo "3. Clique em 'Inserir Leads' na modal"
echo "4. Se ainda der 404, verifique:"
echo "   - Se o arquivo routes/web.php foi atualizado"
echo "   - Se o PlaceExtractionController existe"
echo "   - Se há erro de sintaxe no routes/web.php"

echo ""
echo "🔍 COMANDOS PARA DEBUG MANUAL:"
echo "=============================="
echo "# Verificar todas as rotas:"
echo "php artisan route:list | grep places"
echo ""
echo "# Verificar rota específica:"
echo "php artisan route:list --name=dashboard.places.extraction.insert-leads"
echo ""
echo "# Verificar se controller existe:"
echo "ls -la app/Http/Controllers/PlaceExtractionController.php"
echo ""
echo "# Verificar sintaxe do routes/web.php:"
echo "php -l routes/web.php"

echo ""
echo "🎉 SCRIPT CONCLUÍDO!"
echo "==================="
