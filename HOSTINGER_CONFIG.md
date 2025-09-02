# 🌐 Configuração na Hostinger - DSPay Orbita

## 📋 Informações do Projeto

- **Nome**: DSPay Orbita
- **Subdomínio**: orbita.dspay.com.br
- **Framework**: Laravel 11
- **PHP**: 8.1+
- **Banco**: MySQL

## 🚀 Passos para Deploy

### 1. **Criar Subdomínio**
- Acesse o painel da Hostinger
- Vá em **Domains** → **Subdomains**
- Crie: `orbita.dspay.com.br`
- Aponte para: `/public_html/`

### 2. **Upload dos Arquivos**
- Use **File Manager** ou **FTP**
- Upload para: `/public_html/`
- **IMPORTANTE**: Todos os arquivos devem estar na raiz

### 3. **Configurar Banco de Dados**
- Crie um banco MySQL
- Anote: host, nome, usuário, senha
- Execute as migrações

### 4. **Configurar .env**
```env
APP_NAME="DSPay Orbita"
APP_ENV=production
APP_KEY=base64:sua_chave_aqui
APP_DEBUG=false
APP_URL=https://orbita.dspay.com.br

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=seu_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

MAIL_MAILER=smtp
MAIL_HOST=seu_smtp
MAIL_PORT=587
MAIL_USERNAME=seu_email
MAIL_PASSWORD=sua_senha
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu_email
MAIL_FROM_NAME="DSPay Orbita"
```

## 🔧 Comandos no Terminal da Hostinger

### **Acessar Terminal SSH**
```bash
# Navegar para o diretório
cd public_html

# Instalar dependências
composer install --no-dev --optimize-autoloader

# Gerar chave da aplicação
php artisan key:generate

# Executar migrações
php artisan migrate

# Limpar caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Otimizar para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Configurar permissões
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
```

## 📁 Estrutura de Arquivos

```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
│   ├── build/          ← Assets compilados
│   ├── index.php       ← Ponto de entrada
│   └── .htaccess       ← Configurações Apache
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env                ← Configurações
└── artisan
```

## 🚨 Problemas Comuns

### **Erro 500**
- Verificar permissões: `chmod -R 755 storage/`
- Verificar logs: `storage/logs/laravel.log`
- Verificar `.env` configurado

### **Assets não carregam**
- Verificar se `public/build/` existe
- Verificar se `npm run build` foi executado localmente
- Verificar configurações do Vite

### **Banco não conecta**
- Verificar credenciais no `.env`
- Verificar se o banco existe
- Verificar permissões do usuário MySQL

## 📊 Verificações Pós-Deploy

- [ ] Página inicial carregando
- [ ] Login funcionando
- [ ] Cadastro de leads funcionando
- [ ] Download de apresentação funcionando
- [ ] E-mails sendo enviados
- [ ] HTTPS funcionando
- [ ] Assets carregando

## 📞 Suporte Hostinger

- **Chat**: Disponível 24/7
- **Email**: support@hostinger.com
- **Documentação**: help.hostinger.com

---

**🎯 URL Final**: https://orbita.dspay.com.br


