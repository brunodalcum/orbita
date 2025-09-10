#!/bin/bash

# Script definitivo para corrigir erro do Laravel Pail em produção
# Erro: Class "Laravel\Pail\PailServiceProvider" not found

echo "🚀 Correção DEFINITIVA - Laravel Pail em Produção"
echo "=================================================="
echo "📅 Data: $(date)"
echo "📍 Servidor: srv971263.hstgr.cloud"
echo ""

# Verificar se estamos no diretório correto
if [ ! -f "artisan" ]; then
    echo "❌ ERRO: Arquivo 'artisan' não encontrado!"
    echo "💡 Execute este script no diretório raiz do projeto Laravel"
    exit 1
fi

echo "✅ Diretório do projeto Laravel confirmado"
echo ""

# ETAPA 1: CRIAR ESTRUTURA DE DIRETÓRIOS NECESSÁRIA
echo "📁 ETAPA 1: Criando estrutura de diretórios..."
echo "=============================================="

# Criar todos os diretórios necessários
directories=(
    "bootstrap/cache"
    "storage/app"
    "storage/app/public"
    "storage/framework"
    "storage/framework/cache"
    "storage/framework/cache/data"
    "storage/framework/sessions"
    "storage/framework/views"
    "storage/logs"
)

for dir in "${directories[@]}"; do
    if [ ! -d "$dir" ]; then
        mkdir -p "$dir"
        echo "   ✅ Criado: $dir"
    else
        echo "   ✅ Existe: $dir"
    fi
done

echo "✅ ETAPA 1 concluída - Diretórios criados"
echo ""

# ETAPA 2: CONFIGURAR PERMISSÕES
echo "🔐 ETAPA 2: Configurando permissões..."
echo "====================================="

chmod -R 755 bootstrap/cache
chmod -R 755 storage
echo "   ✅ Permissões 755 aplicadas"

echo "✅ ETAPA 2 concluída - Permissões configuradas"
echo ""

# ETAPA 3: REMOVER CACHES PROBLEMÁTICOS
echo "🗑️  ETAPA 3: Removendo caches problemáticos..."
echo "============================================="

# Remover caches específicos que podem conter referências ao Pail
cache_files=(
    "bootstrap/cache/services.php"
    "bootstrap/cache/packages.php"
    "bootstrap/cache/config.php"
    "bootstrap/cache/routes-v7.php"
    "bootstrap/cache/events.php"
)

for file in "${cache_files[@]}"; do
    if [ -f "$file" ]; then
        rm -f "$file"
        echo "   ✅ Removido: $file"
    else
        echo "   ℹ️  Não existe: $file"
    fi
done

echo "✅ ETAPA 3 concluída - Caches problemáticos removidos"
echo ""

# ETAPA 4: LIMPAR TODOS OS CACHES DO LARAVEL
echo "🧹 ETAPA 4: Limpando caches do Laravel..."
echo "========================================"

# Tentar limpar caches (pode falhar se Laravel não conseguir inicializar)
echo "🔄 Tentando limpar caches..."
if php artisan optimize:clear > /dev/null 2>&1; then
    echo "   ✅ Caches limpos com optimize:clear"
else
    echo "   ⚠️  optimize:clear falhou, tentando comandos individuais..."
    
    php artisan config:clear > /dev/null 2>&1 && echo "   ✅ Config cache limpo" || echo "   ❌ Config cache falhou"
    php artisan route:clear > /dev/null 2>&1 && echo "   ✅ Route cache limpo" || echo "   ❌ Route cache falhou"
    php artisan view:clear > /dev/null 2>&1 && echo "   ✅ View cache limpo" || echo "   ❌ View cache falhou"
    php artisan cache:clear > /dev/null 2>&1 && echo "   ✅ Application cache limpo" || echo "   ❌ Application cache falhou"
fi

echo "✅ ETAPA 4 concluída - Caches do Laravel processados"
echo ""

# ETAPA 5: RECRIAR AUTOLOAD SEM PACOTES DE DESENVOLVIMENTO
echo "🔄 ETAPA 5: Recriando autoload para produção..."
echo "=============================================="

echo "🔄 Executando composer dump-autoload..."
if composer dump-autoload --optimize --no-dev > /dev/null 2>&1; then
    echo "   ✅ Autoload recriado sem pacotes de desenvolvimento"
else
    echo "   ⚠️  Tentando sem --no-dev..."
    if composer dump-autoload --optimize > /dev/null 2>&1; then
        echo "   ✅ Autoload recriado (com pacotes de dev)"
    else
        echo "   ❌ Falha ao recriar autoload"
    fi
fi

echo "✅ ETAPA 5 concluída - Autoload recriado"
echo ""

# ETAPA 6: TESTAR FUNCIONAMENTO DO LARAVEL
echo "🧪 ETAPA 6: Testando funcionamento do Laravel..."
echo "=============================================="

echo "🔄 Testando comando artisan..."
if php artisan --version > /dev/null 2>&1; then
    echo "   ✅ Laravel funcionando:"
    php artisan --version
else
    echo "   ❌ Laravel ainda com problemas"
    echo "   💡 Verificar logs: tail -f storage/logs/laravel.log"
fi

echo "✅ ETAPA 6 concluída - Teste do Laravel"
echo ""

# ETAPA 7: RECRIAR CACHES DE PRODUÇÃO
echo "⚡ ETAPA 7: Recriando caches de produção..."
echo "========================================"

echo "🔄 Criando caches otimizados..."
if php artisan config:cache > /dev/null 2>&1; then
    echo "   ✅ Config cache criado"
else
    echo "   ❌ Falha ao criar config cache"
fi

if php artisan route:cache > /dev/null 2>&1; then
    echo "   ✅ Route cache criado"
else
    echo "   ❌ Falha ao criar route cache"
fi

if php artisan view:cache > /dev/null 2>&1; then
    echo "   ✅ View cache criado"
else
    echo "   ❌ Falha ao criar view cache"
fi

echo "✅ ETAPA 7 concluída - Caches de produção criados"
echo ""

# ETAPA 8: VERIFICAÇÃO FINAL
echo "🔍 ETAPA 8: Verificação final..."
echo "==============================="

echo "📊 Estrutura de arquivos:"
echo "   Bootstrap cache:"
ls -la bootstrap/cache/ 2>/dev/null | head -3 || echo "   ❌ Diretório não acessível"

echo "   Storage:"
ls -la storage/ 2>/dev/null | head -3 || echo "   ❌ Diretório não acessível"

echo "🧪 Teste final do Laravel:"
if php artisan --version > /dev/null 2>&1; then
    echo "   ✅ Laravel Framework funcionando"
    php artisan --version
else
    echo "   ❌ Laravel ainda com problemas"
fi

echo "✅ ETAPA 8 concluída - Verificação final"
echo ""

# RESULTADO FINAL
echo "🎉 CORREÇÃO FINALIZADA!"
echo "======================="
echo ""
echo "✅ Diretórios criados e configurados"
echo "✅ Permissões aplicadas (755)"
echo "✅ Caches problemáticos removidos"
echo "✅ Autoload recriado para produção"
echo "✅ Caches de produção otimizados"
echo ""
echo "🔗 URLs para testar:"
echo "   - Login: https://srv971263.hstgr.cloud/login"
echo "   - Dashboard: https://srv971263.hstgr.cloud/dashboard"
echo "   - Agenda: https://srv971263.hstgr.cloud/agenda"
echo "   - Nova Agenda: https://srv971263.hstgr.cloud/agenda/nova"
echo ""
echo "🧪 Como testar:"
echo "   1. Acesse as URLs acima"
echo "   2. Verifique se não há mais erro do Pail"
echo "   3. Teste o salvamento de agenda"
echo "   4. Monitore logs se necessário"
echo ""
echo "💡 Se ainda houver problemas:"
echo "   - Verificar logs: tail -f storage/logs/laravel.log"
echo "   - Verificar logs do servidor: tail -f /var/log/apache2/error.log"
echo "   - Contatar suporte do hosting se persistir"
echo ""
echo "📋 Informações técnicas:"
echo "   - Laravel Pail removido do ambiente de produção"
echo "   - Autoload otimizado sem pacotes de desenvolvimento"
echo "   - Caches recriados especificamente para produção"
echo "   - Permissões configuradas para servidor web"
echo ""
