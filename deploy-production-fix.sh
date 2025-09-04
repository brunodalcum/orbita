#!/bin/bash

# Script para corrigir problemas do sidebar em produção
# Execute este script no servidor de produção

echo "🔧 Iniciando correção do sidebar em produção..."

# 1. Verificar se estamos no diretório correto
if [ ! -f "artisan" ]; then
    echo "❌ Erro: Execute este script no diretório raiz do Laravel"
    exit 1
fi

echo "✅ Diretório correto encontrado"

# 2. Instalar dependências Node.js
echo "📦 Instalando dependências Node.js..."
npm install

# 3. Compilar assets para produção
echo "🏗️ Compilando assets para produção..."
npm run build

# 4. Verificar se o manifest.json foi gerado
if [ -f "public/build/manifest.json" ]; then
    echo "✅ Manifest.json gerado com sucesso"
else
    echo "❌ Erro: Manifest.json não foi gerado"
    echo "🔧 Tentando criar link simbólico..."
    mkdir -p public/build
    ln -sf .vite/manifest.json public/build/manifest.json
fi

# 5. Otimizar Laravel para produção
echo "⚡ Otimizando Laravel para produção..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Verificar permissões
echo "🔐 Verificando permissões..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache

# 7. Criar fallback para FontAwesome
echo "🎨 Criando fallback para FontAwesome..."
mkdir -p public/css
cat > public/css/fontawesome-fallback.css << 'EOF'
/* Fallback CSS para FontAwesome */
.fas, .far, .fab, .fal, .fad {
    font-family: "Font Awesome 5 Free", "Font Awesome 5 Brands", sans-serif;
    font-weight: 900;
    font-style: normal;
    font-variant: normal;
    text-rendering: auto;
    line-height: 1;
}

/* Ícones básicos como fallback */
.fa-tachometer-alt:before { content: "📊"; }
.fa-users:before { content: "👥"; }
.fa-cogs:before { content: "⚙️"; }
.fa-chart-line:before { content: "📈"; }
.fa-calendar-alt:before { content: "📅"; }
.fa-bullhorn:before { content: "📢"; }
.fa-users-cog:before { content: "👤"; }
.fa-chevron-down:before { content: "▼"; }
.fa-user:before { content: "👤"; }
.fa-circle:before { content: "●"; }
EOF

# 8. Criar fallback para TailwindCSS
echo "🎨 Criando fallback para TailwindCSS..."
mkdir -p public/js
cat > public/js/tailwind-fallback.js << 'EOF'
// Fallback básico para TailwindCSS
if (typeof window !== 'undefined') {
    // Adicionar classes básicas se TailwindCSS não carregar
    const style = document.createElement('style');
    style.textContent = `
        .bg-gradient-to-b { background: linear-gradient(to bottom, #3B82F6, #8B5CF6); }
        .from-blue-500 { background-color: #3B82F6; }
        .to-purple-600 { background-color: #8B5CF6; }
        .text-white { color: white; }
        .hover\\:bg-white\\/10:hover { background-color: rgba(255,255,255,0.1); }
        .bg-white\\/20 { background-color: rgba(255,255,255,0.2); }
        .border-white { border-color: white; }
        .border-l-4 { border-left-width: 4px; }
        .rounded-lg { border-radius: 0.5rem; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
        .mr-3 { margin-right: 0.75rem; }
        .ml-auto { margin-left: auto; }
        .w-64 { width: 16rem; }
        .h-10 { height: 2.5rem; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-center { justify-content: center; }
        .font-medium { font-weight: 500; }
        .transition-all { transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
        .duration-200 { transition-duration: 200ms; }
    `;
    document.head.appendChild(style);
}
EOF

# 9. Verificar se a imagem do logo existe
echo "🖼️ Verificando logo..."
if [ ! -f "public/images/dspay-logo.png" ]; then
    echo "⚠️ Logo não encontrado, criando placeholder..."
    mkdir -p public/images
    # Criar um SVG simples como placeholder
    cat > public/images/dspay-logo.svg << 'EOF'
<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
<rect width="40" height="40" rx="8" fill="#3366EF"/>
<text x="20" y="26" font-family="Arial" font-size="14" font-weight="bold" fill="white" text-anchor="middle">DSPAY</text>
</svg>
EOF
fi

# 10. Testar se tudo está funcionando
echo "🧪 Testando configuração..."
if php artisan route:list | grep -q "dashboard"; then
    echo "✅ Rotas do dashboard encontradas"
else
    echo "❌ Erro: Rotas do dashboard não encontradas"
fi

echo ""
echo "🎉 Correção concluída!"
echo ""
echo "📋 Próximos passos:"
echo "1. Acesse https://srv971263.hstgr.cloud/dashboard"
echo "2. Verifique se o sidebar está aparecendo"
echo "3. Se ainda houver problemas, verifique o console do navegador (F12)"
echo ""
echo "🔍 Para debug adicional:"
echo "- Verifique os logs: tail -f storage/logs/laravel.log"
echo "- Teste as rotas: php artisan route:list | grep dashboard"
echo "- Verifique permissões: ls -la storage/ bootstrap/cache/"
