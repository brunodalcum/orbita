# 🚀 Checklist de Deploy - DSPay Orbita

## 📋 Pré-Deploy (Local)

### ✅ Configurações
- [ ] Arquivo `.env` configurado para produção
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://orbita.dspay.com.br`
- [ ] Chave de aplicação gerada (`php artisan key:generate`)

### ✅ Banco de Dados
- [ ] Migrações executadas
- [ ] Seeders executados (se necessário)
- [ ] Backup do banco local

### ✅ Assets
- [ ] `npm run build` executado
- [ ] Arquivos CSS/JS compilados em `public/build/`
- [ ] Manifesto Vite gerado

### ✅ Dependências
- [ ] `composer install --no-dev --optimize-autoloader`
- [ ] `npm install --production`

## 🌐 Deploy na Hostinger

### 📁 Upload de Arquivos
- [ ] Upload de todos os arquivos para `/public_html/`
- [ ] Arquivo `.env` configurado no servidor
- [ ] Arquivo de apresentação PDF no diretório correto

### 🔧 Configurações do Servidor
- [ ] PHP 8.1+ configurado
- [ ] Extensões PHP necessárias habilitadas
- [ ] Banco MySQL configurado
- [ ] Subdomínio `orbita.dspay.com.br` apontando para o diretório

### 📊 Banco de Dados
- [ ] Banco criado na Hostinger
- [ ] Usuário e senha configurados
- [ ] Migrações executadas (`php artisan migrate`)
- [ ] Dados iniciais carregados

### ⚡ Otimizações
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] Caches limpos

### 🔐 Permissões
- [ ] `storage/` com permissão 755
- [ ] `bootstrap/cache/` com permissão 755
- [ ] `.env` com permissão 644

## 🧪 Pós-Deploy

### ✅ Testes
- [ ] Página inicial carregando
- [ ] Login funcionando
- [ ] Cadastro de leads funcionando
- [ ] Download de apresentação funcionando
- [ ] E-mails sendo enviados

### 📱 Verificações
- [ ] HTTPS funcionando
- [ ] Assets carregando corretamente
- [ ] Formulários funcionando
- [ ] Responsividade OK

### 📊 Monitoramento
- [ ] Logs sendo gerados
- [ ] Erros sendo registrados
- [ ] Performance adequada

## 🚨 Problemas Comuns

### ❌ Erro 500
- Verificar permissões de arquivos
- Verificar logs em `storage/logs/`
- Verificar configurações do `.env`

### ❌ Assets não carregam
- Verificar se `npm run build` foi executado
- Verificar se `public/build/` existe
- Verificar configurações do Vite

### ❌ Banco não conecta
- Verificar credenciais no `.env`
- Verificar se o banco existe
- Verificar permissões do usuário

## 📞 Suporte

- **Hostinger**: Suporte técnico via chat/email
- **Laravel**: Documentação oficial
- **Logs**: `storage/logs/laravel.log`

---

**🎯 Objetivo**: Deploy bem-sucedido em `https://orbita.dspay.com.br`



