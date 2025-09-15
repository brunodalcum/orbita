#!/bin/bash

echo "🔧 CORREÇÃO COMPLETA DE PRODUÇÃO - STORAGE E BRANDING"
echo "===================================================="
echo ""

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Função para log colorido
log_info() { echo -e "${BLUE}ℹ️  $1${NC}"; }
log_success() { echo -e "${GREEN}✅ $1${NC}"; }
log_warning() { echo -e "${YELLOW}⚠️  $1${NC}"; }
log_error() { echo -e "${RED}❌ $1${NC}"; }

# Obter diretório atual
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"
log_info "Diretório de trabalho: $DIR"

echo ""
echo "1. 🔍 DIAGNÓSTICO COMPLETO:"

# Verificar estrutura de diretórios
log_info "Verificando estrutura de diretórios..."
if [ -d "$DIR/storage/app/public" ]; then
    log_success "storage/app/public existe"
else
    log_error "storage/app/public NÃO existe"
    mkdir -p "$DIR/storage/app/public"
    log_success "storage/app/public criado"
fi

if [ -d "$DIR/storage/app/public/branding" ]; then
    log_success "storage/app/public/branding existe"
    BRANDING_FILES=$(ls -la "$DIR/storage/app/public/branding/" | grep "logo_1757903080" | wc -l)
    log_info "Arquivos de logo encontrados: $BRANDING_FILES"
else
    log_error "storage/app/public/branding NÃO existe"
fi

# Verificar public/storage
if [ -e "$DIR/public/storage" ]; then
    if [ -L "$DIR/public/storage" ]; then
        LINK_TARGET=$(readlink "$DIR/public/storage")
        log_info "public/storage é um link para: $LINK_TARGET"
        
        if [ "$LINK_TARGET" = "$DIR/storage/app/public" ] || [ "$(realpath "$LINK_TARGET")" = "$(realpath "$DIR/storage/app/public")" ]; then
            log_success "Link está correto"
        else
            log_warning "Link está incorreto"
        fi
    else
        log_warning "public/storage existe mas não é um link"
    fi
else
    log_error "public/storage NÃO existe"
fi

echo ""
echo "2. 🔧 CORREÇÃO FORÇADA:"

# Remover public/storage existente
log_info "Removendo public/storage existente..."
rm -rf "$DIR/public/storage"

# Criar novo link simbólico
log_info "Criando novo link simbólico..."
ln -sf "$DIR/storage/app/public" "$DIR/public/storage"

if [ -L "$DIR/public/storage" ]; then
    log_success "Link simbólico criado"
else
    log_error "Falha ao criar link simbólico"
    
    # Tentar método alternativo
    log_info "Tentando método alternativo..."
    cd "$DIR/public"
    ln -sf "../storage/app/public" "storage"
    cd "$DIR"
    
    if [ -L "$DIR/public/storage" ]; then
        log_success "Link criado com método alternativo"
    else
        log_error "Falha total ao criar link"
    fi
fi

echo ""
echo "3. 🔐 AJUSTANDO PERMISSÕES:"

# Ajustar permissões
log_info "Ajustando permissões de diretórios..."
chmod -R 755 "$DIR/storage/app/public/"
chmod -R 755 "$DIR/public/storage" 2>/dev/null || true

# Ajustar propriedade (tentar diferentes usuários)
log_info "Ajustando propriedade dos arquivos..."

# Descobrir usuário do servidor web
WEB_USER=""
if id "www-data" &>/dev/null; then
    WEB_USER="www-data"
elif id "nginx" &>/dev/null; then
    WEB_USER="nginx"
elif id "apache" &>/dev/null; then
    WEB_USER="apache"
else
    WEB_USER=$(whoami)
fi

log_info "Usuário do servidor web detectado: $WEB_USER"

# Tentar ajustar propriedade
chown -R "$WEB_USER:$WEB_USER" "$DIR/storage/app/public/" 2>/dev/null || log_warning "Não foi possível alterar propriedade (pode precisar de sudo)"
chown -R "$WEB_USER:$WEB_USER" "$DIR/public/storage" 2>/dev/null || log_warning "Não foi possível alterar propriedade do link"

echo ""
echo "4. 🧪 TESTES DE FUNCIONALIDADE:"

# Testar acesso aos arquivos
TEST_FILES=(
    "branding/logo_1757903080_TjT4xPWeew.png"
    "branding/logo_small_1757903080_Zp14sucJn6.png"
    "branding/favicon_1757903080_MKGLUAUW4L.png"
)

for FILE in "${TEST_FILES[@]}"; do
    STORAGE_FILE="$DIR/storage/app/public/$FILE"
    PUBLIC_FILE="$DIR/public/storage/$FILE"
    
    log_info "Testando: $FILE"
    
    if [ -f "$STORAGE_FILE" ]; then
        log_success "  Storage: arquivo existe"
        SIZE=$(du -h "$STORAGE_FILE" | cut -f1)
        log_info "  Tamanho: $SIZE"
    else
        log_error "  Storage: arquivo NÃO existe"
    fi
    
    if [ -f "$PUBLIC_FILE" ]; then
        log_success "  Público: acessível via link"
    else
        log_error "  Público: NÃO acessível"
    fi
done

echo ""
echo "5. 🌐 TESTE DE URL:"

# Descobrir o domínio
if [ -n "$HTTP_HOST" ]; then
    DOMAIN="$HTTP_HOST"
elif [ -f "$DIR/.env" ]; then
    DOMAIN=$(grep "APP_URL" "$DIR/.env" | cut -d'=' -f2 | sed 's/https\?:\/\///' | sed 's/["\r\n]//g')
else
    DOMAIN="srv971263.hstgr.cloud"
fi

log_info "Domínio detectado: $DOMAIN"

# Testar URLs
for FILE in "${TEST_FILES[@]}"; do
    URL="https://$DOMAIN/storage/$FILE"
    log_info "Testando URL: $URL"
    
    # Usar curl para testar
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "$URL" --connect-timeout 10 --max-time 15)
    
    if [ "$HTTP_CODE" = "200" ]; then
        log_success "  Status: $HTTP_CODE - ACESSÍVEL"
    else
        log_error "  Status: $HTTP_CODE - NÃO ACESSÍVEL"
    fi
done

echo ""
echo "6. 🔧 COMANDOS DE EMERGÊNCIA:"

echo "Se ainda não funcionar, execute estes comandos manualmente:"
echo ""
echo "# Recriar storage link com força bruta:"
echo "sudo rm -rf $DIR/public/storage"
echo "sudo mkdir -p $DIR/storage/app/public/branding"
echo "sudo ln -sf $DIR/storage/app/public $DIR/public/storage"
echo ""
echo "# Ajustar permissões com sudo:"
echo "sudo chmod -R 755 $DIR/storage/app/public/"
echo "sudo chmod -R 755 $DIR/public/storage"
echo "sudo chown -R $WEB_USER:$WEB_USER $DIR/storage/app/public/"
echo "sudo chown -R $WEB_USER:$WEB_USER $DIR/public/storage"
echo ""
echo "# Testar acesso:"
echo "curl -I https://$DOMAIN/storage/branding/logo_1757903080_TjT4xPWeew.png"

echo ""
echo "7. 📋 VERIFICAÇÃO FINAL:"

# Verificação final
ALL_GOOD=true

if [ -L "$DIR/public/storage" ]; then
    log_success "Storage link: OK"
else
    log_error "Storage link: FALHOU"
    ALL_GOOD=false
fi

ACCESSIBLE_COUNT=0
for FILE in "${TEST_FILES[@]}"; do
    if [ -f "$DIR/public/storage/$FILE" ]; then
        ((ACCESSIBLE_COUNT++))
    fi
done

log_info "Arquivos acessíveis: $ACCESSIBLE_COUNT/${#TEST_FILES[@]}"

if [ "$ACCESSIBLE_COUNT" -eq "${#TEST_FILES[@]}" ]; then
    log_success "Todos os arquivos: ACESSÍVEIS"
else
    log_error "Alguns arquivos: NÃO ACESSÍVEIS"
    ALL_GOOD=false
fi

echo ""
if [ "$ALL_GOOD" = true ]; then
    log_success "🎉 CORREÇÃO COMPLETA BEM-SUCEDIDA!"
    echo ""
    echo "✅ Storage link configurado"
    echo "✅ Permissões ajustadas"
    echo "✅ Arquivos acessíveis"
    echo ""
    echo "🔄 TESTE AGORA:"
    echo "   https://$DOMAIN/hierarchy/branding?node_id=1"
    echo "   https://$DOMAIN/storage/branding/logo_1757903080_TjT4xPWeew.png"
else
    log_warning "⚠️ AINDA HÁ PROBLEMAS!"
    echo ""
    echo "🔧 EXECUTE OS COMANDOS DE EMERGÊNCIA ACIMA COM SUDO"
    echo "🔧 OU ENTRE EM CONTATO COM O ADMINISTRADOR DO SERVIDOR"
fi

echo ""
log_success "Correção finalizada!"
