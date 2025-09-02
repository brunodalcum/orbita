# 🔧 SOLUÇÃO PARA PROBLEMAS NO HOSTINGER

## ❌ Problema: Todas as páginas retornam "não encontrada"

### 🔍 Possíveis Causas:

1. **Document Root incorreto**
2. **Arquivo .htaccess não está sendo lido**
3. **Configuração do Laravel incorreta**
4. **Permissões de arquivo incorretas**

### ✅ SOLUÇÕES:

#### 1. Verificar Document Root no Hostinger:
- Acesse o painel do Hostinger
- Vá em "Gerenciar Sites" → "Configurações"
- Verifique se o Document Root está apontando para: `/public_html/`
- **IMPORTANTE**: O Document Root deve apontar para a pasta `public_html`, não para a raiz do projeto

#### 2. Estrutura de Arquivos Correta:
```
public_html/          ← Document Root deve apontar aqui
├── index.php
├── .htaccess
├── css/
├── js/
└── build/
```

#### 3. Mover Arquivos para public_html:
Se o Document Root estiver incorreto, você precisa:
1. Acessar o File Manager do Hostinger
2. Mover todos os arquivos da pasta `public/` para `public_html/`
3. Mover o arquivo `.htaccess` da pasta `public/` para `public_html/`

#### 4. Verificar Permissões:
```bash
# No File Manager do Hostinger, definir:
- Arquivos: 644
- Pastas: 755
- storage/: 755
- bootstrap/cache/: 755
```

#### 5. Configuração do .env:
```env
APP_URL=https://orbita.dspay.com.br
APP_ENV=production
APP_DEBUG=false
```

### 🚀 PASSOS PARA CORRIGIR:

1. **Acesse o painel do Hostinger**
2. **Vá em "Gerenciar Sites" → "Configurações"**
3. **Verifique o Document Root**
4. **Se estiver incorreto, mude para `/public_html/`**
5. **No File Manager, mova os arquivos da pasta `public/` para `public_html/`**
6. **Teste acessando: `https://orbita.dspay.com.br`**

### 📞 Suporte Hostinger:
Se o problema persistir, entre em contato com o suporte do Hostinger mencionando:
- "Laravel application not working"
- "Document Root configuration issue"
- "All pages return 404 error"

### 🔗 URLs de Teste:
- `https://orbita.dspay.com.br/test.php` (arquivo de teste)
- `https://orbita.dspay.com.br/` (página inicial)
- `https://orbita.dspay.com.br/login` (login do dashboard)

