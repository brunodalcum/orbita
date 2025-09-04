#!/bin/bash

# Script para corrigir o sidebar em produção
# Execute este script no servidor de produção

echo "🔧 Corrigindo sidebar em produção..."

# 1. Verificar se estamos no diretório correto
if [ ! -f "artisan" ]; then
    echo "❌ Erro: Execute este script no diretório raiz do Laravel"
    exit 1
fi

echo "✅ Diretório correto encontrado"

# 2. Limpar caches
echo "🧹 Limpando caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 3. Regenerar caches
echo "🔄 Regenerando caches..."
php artisan config:cache
php artisan route:cache

# 4. Verificar se as rotas estão funcionando
echo "🧪 Testando rotas do sidebar..."
php artisan route:list | grep -E "(dashboard|licenciados|leads|operacoes|planos|adquirentes|agenda|marketing|configuracoes|users)" | head -10

# 5. Verificar permissões do storage
echo "🔐 Verificando permissões..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# 6. Testar se o Laravel está funcionando
echo "🎯 Testando Laravel..."
if php artisan --version > /dev/null 2>&1; then
    echo "✅ Laravel funcionando corretamente"
else
    echo "❌ Problema com Laravel"
    exit 1
fi

# 7. Verificar se o sidebar está sendo carregado
echo "🔍 Verificando componente do sidebar..."
if [ -f "resources/views/components/dynamic-sidebar.blade.php" ]; then
    echo "✅ Componente do sidebar encontrado"
else
    echo "❌ Componente do sidebar não encontrado"
    exit 1
fi

# 8. Verificar se o usuário tem role
echo "👤 Verificando usuários e roles..."
php artisan tinker --execute="
\$user = \App\Models\User::first();
if (\$user && !\$user->role_id) {
    \$role = \App\Models\Role::where('name', 'super_admin')->first();
    if (\$role) {
        \$user->update(['role_id' => \$role->id]);
        echo 'Role atribuído ao usuário: ' . \$user->name . PHP_EOL;
    }
} else {
    echo 'Usuário já tem role: ' . (\$user ? \$user->role->display_name : 'N/A') . PHP_EOL;
}
"

echo ""
echo "🎉 Correção do sidebar concluída!"
echo ""
echo "📋 Próximos passos:"
echo "1. Acesse https://srv971263.hstgr.cloud/dashboard"
echo "2. Verifique se o sidebar está aparecendo"
echo "3. Teste a navegação entre as páginas"
echo ""
echo "🔍 Se ainda houver problemas:"
echo "1. Verifique o console do navegador (F12)"
echo "2. Verifique os logs: tail -f storage/logs/laravel.log"
echo "3. Teste as rotas individualmente"
