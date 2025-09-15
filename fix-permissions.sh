#!/bin/bash

echo "🔧 CORREÇÃO RÁPIDA DE PERMISSÕES"
echo "================================"
echo ""

# Obter o diretório atual
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"

echo "📁 Diretório: $DIR"
echo ""

echo "1. 🔧 Ajustando permissões dos diretórios..."

# Criar diretórios se não existirem
mkdir -p "$DIR/storage/framework/views"
mkdir -p "$DIR/storage/framework/cache"
mkdir -p "$DIR/storage/framework/sessions"
mkdir -p "$DIR/storage/logs"
mkdir -p "$DIR/bootstrap/cache"

# Ajustar permissões
chmod -R 755 "$DIR/storage/"
chmod -R 755 "$DIR/bootstrap/cache/"

echo "   ✅ Permissões ajustadas"

echo ""
echo "2. 🧹 Limpando cache..."

# Limpar cache de views
rm -f "$DIR/storage/framework/views/"*
rm -f "$DIR/bootstrap/cache/"*.php

echo "   ✅ Cache limpo"

echo ""
echo "3. 🔍 Verificando permissões..."

# Verificar se os diretórios são graváveis
if [ -w "$DIR/storage/framework/views" ]; then
    echo "   ✅ storage/framework/views: GRAVÁVEL"
else
    echo "   ❌ storage/framework/views: NÃO GRAVÁVEL"
fi

if [ -w "$DIR/bootstrap/cache" ]; then
    echo "   ✅ bootstrap/cache: GRAVÁVEL"
else
    echo "   ❌ bootstrap/cache: NÃO GRAVÁVEL"
fi

echo ""
echo "4. 🧪 Teste de escrita..."

# Testar escrita
TEST_FILE="$DIR/storage/framework/views/test_write.txt"
if echo "teste" > "$TEST_FILE" 2>/dev/null; then
    echo "   ✅ Teste de escrita bem-sucedido"
    rm -f "$TEST_FILE"
else
    echo "   ❌ Teste de escrita falhou"
    echo ""
    echo "🔧 EXECUTE COMO ROOT:"
    echo "   sudo bash fix-permissions.sh"
    echo ""
    echo "🔧 OU EXECUTE MANUALMENTE:"
    echo "   sudo chown -R www-data:www-data $DIR/storage/"
    echo "   sudo chown -R www-data:www-data $DIR/bootstrap/cache/"
    echo "   sudo chmod -R 755 $DIR/storage/"
    echo "   sudo chmod -R 755 $DIR/bootstrap/cache/"
fi

echo ""
echo "✅ Correção concluída!"
echo "🔄 Teste a URL: https://srv971263.hstgr.cloud/hierarchy/branding"
