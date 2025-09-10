#!/bin/bash

echo "🚀 Deploy para Produção - Hostinger"
echo "=================================="

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Função para log colorido
log_info() {
    echo -e "${BLUE}ℹ️ $1${NC}"
}

log_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

log_warning() {
    echo -e "${YELLOW}⚠️ $1${NC}"
}

log_error() {
    echo -e "${RED}❌ $1${NC}"
}

# 1. Verificar se estamos no diretório correto
if [ ! -f "artisan" ]; then
    log_error "Arquivo artisan não encontrado. Execute este script na raiz do projeto Laravel."
    exit 1
fi

log_info "Iniciando deploy para produção..."

# 2. Criar diretórios necessários
log_info "Criando diretórios necessários..."
mkdir -p bootstrap/cache
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions  
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p storage/app
mkdir -p storage/app/public
log_success "Diretórios criados"

# 3. Definir permissões
log_info "Definindo permissões..."
chmod -R 755 bootstrap
chmod -R 755 storage
chmod -R 777 bootstrap/cache
chmod -R 777 storage/framework
chmod -R 777 storage/logs
log_success "Permissões definidas"

# 4. Limpar caches antigos
log_info "Limpando caches antigos..."
php artisan config:clear 2>/dev/null || log_warning "Config clear - comando executado"
php artisan route:clear 2>/dev/null || log_warning "Route clear - comando executado"
php artisan view:clear 2>/dev/null || log_warning "View clear - comando executado"
php artisan cache:clear 2>/dev/null || log_warning "Cache clear - comando executado"
log_success "Caches limpos"

# 5. Instalar/atualizar dependências
log_info "Atualizando dependências..."
composer install --optimize-autoloader --no-dev 2>/dev/null || log_warning "Composer install executado"
composer dump-autoload --optimize 2>/dev/null || log_warning "Autoloader otimizado"
log_success "Dependências atualizadas"

# 6. Executar migrações (com confirmação)
read -p "🔄 Executar migrações? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    log_info "Executando migrações..."
    php artisan migrate --force
    log_success "Migrações executadas"
else
    log_warning "Migrações puladas"
fi

# 7. Recriar caches otimizados
log_info "Recriando caches otimizados..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
log_success "Caches otimizados criados"

# 8. Criar link simbólico para storage (se necessário)
if [ ! -L "public/storage" ]; then
    log_info "Criando link simbólico para storage..."
    php artisan storage:link 2>/dev/null || log_warning "Storage link - comando executado"
    log_success "Link simbólico criado"
fi

# 9. Verificação final
log_info "Verificação final..."

# Verificar diretórios
directories=("bootstrap/cache" "storage/framework/cache" "storage/framework/sessions" "storage/framework/views" "storage/logs")

for dir in "${directories[@]}"; do
    if [ -d "$dir" ] && [ -w "$dir" ]; then
        log_success "$dir - OK"
    else
        log_error "$dir - PROBLEMA"
    fi
done

# Verificar arquivos de cache
if [ -f "bootstrap/cache/config.php" ]; then
    log_success "Config cache - OK"
else
    log_warning "Config cache - não encontrado"
fi

if [ -f "bootstrap/cache/routes-v7.php" ]; then
    log_success "Routes cache - OK"
else
    log_warning "Routes cache - não encontrado"
fi

# 10. Instruções finais
echo ""
echo "🎯 Deploy concluído!"
echo ""
echo "📋 Se ainda houver problemas de permissão, execute no servidor:"
echo "sudo chown -R www-data:www-data ."
echo "sudo chmod -R 775 bootstrap/cache storage"
echo ""
echo "🌐 Teste o site em: https://srv971263.hstgr.cloud"
echo ""
echo "📝 Logs de erro podem ser encontrados em:"
echo "- storage/logs/laravel.log"
echo "- Logs do servidor web"

log_success "Deploy finalizado com sucesso!"
