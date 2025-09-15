#!/bin/bash

echo "🔧 CORREÇÃO COMPLETA DE PERMISSÕES - LARAVEL"
echo "============================================"
echo ""

# Obter o diretório atual
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"

echo "📁 Diretório: $DIR"
echo ""

echo "1. 📁 CRIANDO ESTRUTURA DE DIRETÓRIOS..."

# Criar todos os diretórios necessários
mkdir -p "$DIR/storage/app/public"
mkdir -p "$DIR/storage/framework/cache/data"
mkdir -p "$DIR/storage/framework/sessions"
mkdir -p "$DIR/storage/framework/views"
mkdir -p "$DIR/storage/logs"
mkdir -p "$DIR/bootstrap/cache"

echo "   ✅ Diretórios criados"

echo ""
echo "2. 📄 CRIANDO ARQUIVOS CRÍTICOS..."

# Criar arquivo de log se não existir
if [ ! -f "$DIR/storage/logs/laravel.log" ]; then
    touch "$DIR/storage/logs/laravel.log"
    echo "   ✅ laravel.log criado"
else
    echo "   ✅ laravel.log já existe"
fi

echo ""
echo "3. 🔧 AJUSTANDO PERMISSÕES..."

# Ajustar permissões de diretórios
chmod -R 755 "$DIR/storage/"
chmod -R 755 "$DIR/bootstrap/cache/"

# Ajustar permissões de arquivos específicos
chmod 644 "$DIR/storage/logs/laravel.log"

echo "   ✅ Permissões ajustadas"

echo ""
echo "4. 🧹 LIMPANDO CACHES..."

# Limpar cache de views
rm -f "$DIR/storage/framework/views/"*
echo "   ✅ Views cache limpo"

# Limpar bootstrap cache
rm -f "$DIR/bootstrap/cache/"*.php
echo "   ✅ Bootstrap cache limpo"

# Limpar framework cache
rm -f "$DIR/storage/framework/cache/data/"*
echo "   ✅ Framework cache limpo"

echo ""
echo "5. 🧪 TESTANDO FUNCIONALIDADES..."

# Teste 1: Views cache
TEST_VIEW="$DIR/storage/framework/views/test_write.txt"
if echo "teste views" > "$TEST_VIEW" 2>/dev/null; then
    echo "   ✅ Views cache: GRAVÁVEL"
    rm -f "$TEST_VIEW"
else
    echo "   ❌ Views cache: NÃO GRAVÁVEL"
fi

# Teste 2: Bootstrap cache
TEST_BOOTSTRAP="$DIR/bootstrap/cache/test_write.txt"
if echo "teste bootstrap" > "$TEST_BOOTSTRAP" 2>/dev/null; then
    echo "   ✅ Bootstrap cache: GRAVÁVEL"
    rm -f "$TEST_BOOTSTRAP"
else
    echo "   ❌ Bootstrap cache: NÃO GRAVÁVEL"
fi

# Teste 3: Log file
LOG_FILE="$DIR/storage/logs/laravel.log"
if echo "[$(date)] testing.INFO: Teste de escrita" >> "$LOG_FILE" 2>/dev/null; then
    echo "   ✅ Log file: GRAVÁVEL"
else
    echo "   ❌ Log file: NÃO GRAVÁVEL"
fi

echo ""
echo "6. 🔍 VERIFICAÇÃO FINAL..."

# Verificar permissões finais
CRITICAL_DIRS=(
    "$DIR/storage/framework/views"
    "$DIR/bootstrap/cache"
    "$DIR/storage/logs"
)

ALL_GOOD=true

for dir in "${CRITICAL_DIRS[@]}"; do
    if [ -w "$dir" ]; then
        echo "   ✅ $(basename "$dir"): OK"
    else
        echo "   ❌ $(basename "$dir"): PROBLEMA"
        ALL_GOOD=false
    fi
done

echo ""

if [ "$ALL_GOOD" = true ]; then
    echo "🎉 SUCESSO COMPLETO!"
    echo "✅ Todos os diretórios estão com permissões corretas"
    echo "✅ Todos os arquivos necessários foram criados"
    echo "✅ Cache foi limpo"
    echo "✅ Testes de escrita passaram"
    echo ""
    echo "🔄 TESTE AGORA: https://srv971263.hstgr.cloud/hierarchy/branding"
else
    echo "⚠️  AINDA HÁ PROBLEMAS!"
    echo ""
    echo "🔧 EXECUTE COMO ROOT:"
    echo "   sudo bash fix-permissions-complete.sh"
    echo ""
    echo "🔧 OU COMANDOS MANUAIS:"
    echo "   sudo chown -R www-data:www-data $DIR/storage/"
    echo "   sudo chown -R www-data:www-data $DIR/bootstrap/cache/"
    echo "   sudo chmod -R 755 $DIR/storage/"
    echo "   sudo chmod -R 755 $DIR/bootstrap/cache/"
    echo "   sudo touch $DIR/storage/logs/laravel.log"
    echo "   sudo chmod 644 $DIR/storage/logs/laravel.log"
    echo ""
    echo "🔧 PARA NGINX:"
    echo "   sudo chown -R nginx:nginx $DIR/storage/"
    echo "   sudo chown -R nginx:nginx $DIR/bootstrap/cache/"
fi

echo ""
echo "✅ Correção completa finalizada!"
