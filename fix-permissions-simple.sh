#!/bin/bash

echo "🔧 CORREÇÃO SIMPLES DE PERMISSÕES (SEM SUDO)"
echo "============================================="

# Navegar para o diretório do projeto
cd /home/user/htdocs/srv971263.hstgr.cloud

echo "📁 Diretório atual: $(pwd)"

echo ""
echo "🔍 VERIFICANDO PERMISSÕES ATUAIS:"
echo "================================="
ls -la storage/framework/ 2>/dev/null || echo "❌ Diretório storage/framework não encontrado"

echo ""
echo "🔧 APLICANDO CORREÇÕES (SEM SUDO):"
echo "=================================="

# 1. Tentar criar diretórios necessários
echo "1️⃣ Criando diretórios necessários..."
mkdir -p storage/framework/views
mkdir -p storage/framework/cache  
mkdir -p storage/framework/sessions
mkdir -p storage/logs
mkdir -p storage/app/public
mkdir -p bootstrap/cache

# 2. Aplicar permissões que o usuário pode alterar
echo "2️⃣ Aplicando permissões disponíveis..."
chmod -R 755 storage/ 2>/dev/null || echo "⚠️ Algumas permissões podem precisar de sudo"
chmod -R 755 bootstrap/cache/ 2>/dev/null || echo "⚠️ Algumas permissões podem precisar de sudo"

# 3. Tentar permissões mais amplas nos diretórios críticos
echo "3️⃣ Tentando permissões mais amplas..."
chmod -R 777 storage/framework/ 2>/dev/null
chmod -R 777 storage/logs/ 2>/dev/null
chmod -R 777 bootstrap/cache/ 2>/dev/null

# 4. Limpar caches
echo "4️⃣ Limpando caches..."
php artisan cache:clear 2>/dev/null || echo "⚠️ Erro ao limpar cache"
php artisan view:clear 2>/dev/null || echo "⚠️ Erro ao limpar views"
php artisan config:clear 2>/dev/null || echo "⚠️ Erro ao limpar config"
php artisan route:clear 2>/dev/null || echo "⚠️ Erro ao limpar routes"

# 5. Verificar resultado
echo ""
echo "✅ VERIFICAÇÃO:"
echo "==============="
ls -la storage/framework/ 2>/dev/null

# 6. Teste de escrita
echo ""
echo "🧪 TESTE DE ESCRITA:"
echo "==================="
TEST_DIR="storage/framework/views"
TEST_FILE="$TEST_DIR/test_write.txt"

if [ -d "$TEST_DIR" ]; then
    echo "Teste $(date)" > "$TEST_FILE" 2>/dev/null
    if [ -f "$TEST_FILE" ]; then
        echo "✅ Escrita em $TEST_DIR: SUCESSO"
        rm "$TEST_FILE" 2>/dev/null
    else
        echo "❌ Escrita em $TEST_DIR: FALHOU"
        echo "Execute: sudo chmod -R 777 storage/"
    fi
else
    echo "❌ Diretório $TEST_DIR não existe"
fi

echo ""
echo "🎯 RESUMO:"
echo "=========="
echo "Se ainda houver erro, execute no servidor:"
echo ""
echo "# Opção 1 - Com sudo (recomendado):"
echo "sudo chown -R www-data:www-data storage/ bootstrap/cache/"
echo "sudo chmod -R 775 storage/ bootstrap/cache/"
echo ""
echo "# Opção 2 - Permissões amplas (temporário):"
echo "chmod -R 777 storage/"
echo "chmod -R 777 bootstrap/cache/"
echo ""
echo "# Opção 3 - Limpar e recriar:"
echo "rm -rf storage/framework/views/*"
echo "php artisan view:clear"

echo ""
echo "🚀 Teste agora: https://srv971263.hstgr.cloud/places/extract"
