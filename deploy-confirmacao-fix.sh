#!/bin/bash

echo "🚀 Deploy: Correção das páginas de confirmação de agenda"
echo "================================================="

# Limpar caches
echo "🧹 Limpando caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Otimizar para produção
echo "⚡ Otimizando para produção..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verificar se as views existem
echo "🔍 Verificando views de confirmação..."
if [ -f "resources/views/agenda/confirmacao.blade.php" ]; then
    echo "✅ View confirmacao.blade.php encontrada"
else
    echo "❌ View confirmacao.blade.php NÃO encontrada"
fi

if [ -f "resources/views/agenda/rejeicao.blade.php" ]; then
    echo "✅ View rejeicao.blade.php encontrada"
else
    echo "❌ View rejeicao.blade.php NÃO encontrada"
fi

if [ -f "resources/views/agenda/pendente.blade.php" ]; then
    echo "✅ View pendente.blade.php encontrada"
else
    echo "❌ View pendente.blade.php NÃO encontrada"
fi

# Verificar permissões
echo "🔐 Verificando permissões..."
chmod -R 755 resources/views/agenda/
chown -R www-data:www-data resources/views/agenda/ 2>/dev/null || echo "⚠️  Não foi possível alterar owner (normal em desenvolvimento)"

# Testar rota
echo "🧪 Testando rota de confirmação..."
php artisan route:list --name=agenda.confirmar

echo ""
echo "✅ Deploy concluído!"
echo "📋 Próximos passos:"
echo "   1. Acesse: https://srv971263.hstgr.cloud/agenda/confirmar/23?status=confirmado&email=brunodalcum%40gmail.com"
echo "   2. Verifique se a página verde de confirmação aparece"
echo "   3. Se ainda houver erro, verifique os logs: tail -f storage/logs/laravel.log"
echo ""
