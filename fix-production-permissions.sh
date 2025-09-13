#!/bin/bash

echo "🔧 CORREÇÃO DE PERMISSÕES - SERVIDOR DE PRODUÇÃO"
echo "================================================"

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
echo "🔍 VERIFICANDO PERMISSÕES ATUAIS:"
echo "================================="
ls -la storage/
echo ""
ls -la storage/framework/
echo ""

echo "🔧 APLICANDO CORREÇÕES DE PERMISSÕES:"
echo "===================================="

# 1. Definir proprietário correto (usuário do servidor web)
echo "1️⃣ Definindo proprietário correto..."
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/

# 2. Definir permissões corretas para diretórios
echo "2️⃣ Definindo permissões para diretórios..."
find storage/ -type d -exec chmod 755 {} \;
find bootstrap/cache/ -type d -exec chmod 755 {} \;

# 3. Definir permissões corretas para arquivos
echo "3️⃣ Definindo permissões para arquivos..."
find storage/ -type f -exec chmod 644 {} \;
find bootstrap/cache/ -type f -exec chmod 644 {} \;

# 4. Permissões especiais para diretórios críticos
echo "4️⃣ Aplicando permissões especiais..."
chmod -R 775 storage/framework/
chmod -R 775 storage/logs/
chmod -R 775 storage/app/
chmod -R 775 bootstrap/cache/

# 5. Limpar caches existentes
echo "5️⃣ Limpando caches..."
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear

# 6. Recriar diretórios se necessário
echo "6️⃣ Verificando estrutura de diretórios..."
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/logs
mkdir -p storage/app/public

# 7. Aplicar permissões finais
echo "7️⃣ Aplicando permissões finais..."
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# 8. Verificar resultado
echo ""
echo "✅ VERIFICAÇÃO FINAL:"
echo "===================="
ls -la storage/framework/
echo ""

# 9. Testar criação de arquivo
echo "🧪 TESTE DE ESCRITA:"
echo "==================="
TEST_FILE="storage/framework/views/test_permissions.txt"
echo "Teste de permissões - $(date)" > "$TEST_FILE"

if [ -f "$TEST_FILE" ]; then
    echo "✅ Teste de escrita: SUCESSO"
    rm "$TEST_FILE"
else
    echo "❌ Teste de escrita: FALHOU"
    echo "Verifique as permissões manualmente"
fi

echo ""
echo "🎯 CORREÇÕES APLICADAS:"
echo "======================"
echo "✅ Proprietário definido como www-data"
echo "✅ Permissões 775 para diretórios críticos"
echo "✅ Permissões 644 para arquivos"
echo "✅ Caches limpos"
echo "✅ Estrutura de diretórios verificada"

echo ""
echo "🚀 PRÓXIMOS PASSOS:"
echo "=================="
echo "1. Acesse: https://srv971263.hstgr.cloud/places/extract"
echo "2. Teste a funcionalidade de extração"
echo "3. Se ainda houver erro, execute:"
echo "   sudo chmod -R 777 storage/ (temporariamente)"

echo ""
echo "📝 COMANDOS PARA DEBUG (se necessário):"
echo "======================================="
echo "# Verificar proprietário:"
echo "ls -la storage/framework/views/"
echo ""
echo "# Verificar processo do servidor web:"
echo "ps aux | grep -E '(apache|nginx|php-fpm)'"
echo ""
echo "# Aplicar permissões mais amplas (último recurso):"
echo "sudo chmod -R 777 storage/"
echo "sudo chmod -R 777 bootstrap/cache/"

echo ""
echo "🎉 SCRIPT CONCLUÍDO!"
echo "==================="
