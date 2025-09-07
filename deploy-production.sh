#!/bin/bash

echo "🚀 Iniciando Deploy para Produção - dspay"
echo "=========================================="

# Verificar se estamos no diretório correto
if [ ! -f "artisan" ]; then
    echo "❌ Erro: artisan não encontrado. Execute este script no diretório raiz do Laravel."
    exit 1
fi

# Função para verificar se o comando foi executado com sucesso
check_command() {
    if [ $? -eq 0 ]; then
        echo "✅ $1"
    else
        echo "❌ Erro em: $1"
        exit 1
    fi
}

# 1. Instalar dependências do Composer
echo "📦 Instalando dependências do Composer..."
composer install --no-dev --optimize-autoloader --no-interaction
check_command "Dependências do Composer instaladas"

# 2. Instalar dependências do NPM
echo "📦 Instalando dependências do NPM..."
npm ci --only=production
check_command "Dependências do NPM instaladas"

# 3. Gerar build do Vite para produção
echo "🏗️  Gerando build de produção do Vite..."
npm run build
check_command "Build do Vite gerado"

# 4. Verificar se o manifest foi criado
if [ ! -f "public/build/manifest.json" ]; then
    echo "❌ Erro: manifest.json não foi gerado pelo Vite"
    exit 1
fi
echo "✅ Manifest do Vite verificado"

# 5. Limpar cache do Laravel
echo "🧹 Limpando cache do Laravel..."
php artisan optimize:clear
check_command "Cache limpo"

# 6. Gerar cache otimizado para produção
echo "⚡ Gerando cache otimizado..."
php artisan config:cache
check_command "Config cache gerado"

php artisan route:cache
check_command "Route cache gerado"

php artisan view:cache
check_command "View cache gerado"

# 7. Executar migrações
echo "🗄️  Executando migrações..."
php artisan migrate --force
check_command "Migrações executadas"

# 8. Definir permissões corretas
echo "🔐 Configurando permissões..."
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs storage/framework
check_command "Permissões configuradas"

# 9. Criar link simbólico para storage (se não existir)
if [ ! -L "public/storage" ]; then
    php artisan storage:link
    check_command "Link simbólico criado"
fi

# 10. Verificar arquivos críticos
echo "🔍 Verificando arquivos críticos..."

if [ ! -f ".env" ]; then
    echo "❌ Erro: .env não encontrado"
    exit 1
fi

if [ ! -f "public/build/manifest.json" ]; then
    echo "❌ Erro: manifest.json não encontrado"
    exit 1
fi

if [ ! -d "vendor" ]; then
    echo "❌ Erro: vendor não encontrado"
    exit 1
fi

echo "✅ Arquivos críticos verificados"

# 11. Testar configuração
echo "🧪 Testando configuração..."
php artisan config:show app.env | grep -q "production"
if [ $? -eq 0 ]; then
    echo "✅ Ambiente configurado para produção"
else
    echo "⚠️  Aviso: Ambiente não está configurado para produção"
fi

# 12. Resumo final
echo ""
echo "🎉 Deploy Concluído com Sucesso!"
echo "================================="
echo "✅ Dependências instaladas"
echo "✅ Build do Vite gerado"
echo "✅ Cache otimizado"
echo "✅ Migrações executadas"
echo "✅ Permissões configuradas"
echo ""
echo "📁 Arquivos de build criados:"
ls -la public/build/
echo ""
echo "🌐 Aplicação pronta para produção!"
echo "URL: https://srv971263.hstgr.cloud"
echo ""
echo "📝 Próximos passos:"
echo "1. Faça upload dos arquivos para o servidor"
echo "2. Configure o .env no servidor"
echo "3. Execute 'php artisan optimize' no servidor"
echo ""
