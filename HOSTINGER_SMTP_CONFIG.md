# 📧 Configuração SMTP da Hostinger

## 🔧 Configurações para o arquivo .env

Adicione ou atualize as seguintes configurações no seu arquivo `.env`:

```env
# Configurações de E-mail - Hostinger SMTP
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=seu_email@seudominio.com
MAIL_PASSWORD=sua_senha_de_email
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu_email@seudominio.com
MAIL_FROM_NAME="DSPAY - Contratos"
```

## 📋 Informações Importantes

### 🏢 Dados do Servidor SMTP da Hostinger:
- **Servidor SMTP:** smtp.hostinger.com
- **Porta:** 587 (recomendada)
- **Criptografia:** TLS
- **Autenticação:** Sim

### 🔐 Credenciais:
- **Usuário:** Seu e-mail completo (ex: contrato@dspay.com.br)
- **Senha:** A senha do seu e-mail

## 🚀 Passos para Configurar:

1. **Acesse o painel da Hostinger**
2. **Vá em "E-mails"**
3. **Crie ou use um e-mail existente**
4. **Copie as credenciais**
5. **Atualize o arquivo .env**
6. **Teste o envio**

## 🧪 Teste de Configuração

Execute este comando para testar se o e-mail está funcionando:

```bash
php artisan tinker
```

Depois execute:

```php
Mail::raw('Teste de configuração SMTP', function ($message) {
    $message->to('seu_email_teste@gmail.com')
            ->subject('Teste SMTP Hostinger');
});
```

## 📝 Exemplo Completo de .env

```env
APP_NAME="DSPAY"
APP_ENV=production
APP_KEY=base64:sua_chave_aqui
APP_DEBUG=false
APP_URL=https://seudominio.com.br

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=orbita
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

# Mail - Hostinger
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=contrato@dspay.com.br
MAIL_PASSWORD=sua_senha_aqui
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=contrato@dspay.com.br
MAIL_FROM_NAME="DSPAY - Contratos"
```

## 🔍 Solução de Problemas

### ❌ Se der erro de autenticação:
- Verifique se o e-mail e senha estão corretos
- Confirme se o e-mail está ativo na Hostinger
- Tente usar a porta 465 com SSL se a 587 não funcionar

### ❌ Se der timeout:
- Verifique se o servidor permite conexões SMTP externas
- Confirme se não há firewall bloqueando a porta

### ❌ Se der erro SSL:
- Tente mudar de `tls` para `ssl`
- Ou mude a porta de 587 para 465

## ✅ Após Configurar

1. **Limpe o cache:** `php artisan config:clear`
2. **Teste o envio de contrato** através do sistema
3. **Verifique os logs:** `tail -f storage/logs/laravel.log`

---

**📞 Suporte:** Se precisar de ajuda, entre em contato com o suporte da Hostinger para confirmar as configurações SMTP do seu plano.
