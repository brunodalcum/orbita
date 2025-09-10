# Configuração de Email - Hostinger SMTP

Para configurar o envio de emails usando o servidor SMTP da Hostinger, adicione as seguintes variáveis no arquivo `.env`:

```env
# Configurações de Email - Hostinger SMTP
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@seudominio.com
MAIL_PASSWORD=sua-senha-do-email
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu-email@seudominio.com
MAIL_FROM_NAME="Sistema Orbita"
```

## Alternativa para SSL (porta 465):
```env
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

## Para desenvolvimento local (usando log):
```env
MAIL_MAILER=log
```

## Como configurar:

1. **Acesse o painel da Hostinger**
2. **Vá em "E-mails"**
3. **Crie um email** (ex: sistema@seudominio.com)
4. **Substitua as variáveis** no arquivo `.env`:
   - `seu-email@seudominio.com` pelo email criado
   - `sua-senha-do-email` pela senha do email
   - `seudominio.com` pelo seu domínio real

5. **Execute os comandos**:
```bash
php artisan config:clear
php artisan config:cache
```

## Teste de envio:
```bash
php artisan tinker
Mail::raw('Teste de email', function($message) {
    $message->to('destino@exemplo.com')->subject('Teste');
});
```

O sistema de agenda agora enviará emails automaticamente com botões de confirmação para os participantes!
