# 🔄 Recuperar Configurações do Google Calendar e Meet

## 📋 **Situação Atual:**
As configurações do Google Calendar e Meet sumiram do seu arquivo `.env`. Este guia te ajudará a recuperá-las.

## 🚨 **Configurações Perdidas:**
- `GOOGLE_CALENDAR_CLIENT_ID`
- `GOOGLE_CALENDAR_CLIENT_SECRET` 
- `GOOGLE_CALENDAR_API_KEY`
- `GOOGLE_MEET_ENABLED`
- `GOOGLE_MEET_DEFAULT_TIMEZONE`

## 🔧 **Passos para Recuperar:**

### 1. **Verificar Google Cloud Console**
Acesse: https://console.cloud.google.com/
- Vá em **APIs & Services > Credentials**
- Procure por **OAuth 2.0 Client IDs** ou **API Keys**
- Anote os valores de `CLIENT_ID`, `CLIENT_SECRET` e `API_KEY`

### 2. **Verificar Projetos Ativos**
- Na mesma página, verifique se o projeto está ativo
- Confirme se a **Google Calendar API** está habilitada

### 3. **Adicionar ao .env**
Copie estas linhas para o final do seu arquivo `.env`:

```env
# Google Calendar & Meet Configuration
GOOGLE_SERVICE_ACCOUNT_ENABLED=false
GOOGLE_CALENDAR_CLIENT_ID=SEU_CLIENT_ID_AQUI
GOOGLE_CALENDAR_CLIENT_SECRET=SEU_CLIENT_SECRET_AQUI
GOOGLE_CALENDAR_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
GOOGLE_CALENDAR_API_KEY=SUA_API_KEY_AQUI
GOOGLE_CALENDAR_ID=primary
GOOGLE_CALENDAR_SCOPES=https://www.googleapis.com/auth/calendar,https://www.googleapis.com/auth/calendar.events
GOOGLE_CALENDAR_SEND_UPDATES=all

# Google Meet Configuration
GOOGLE_MEET_ENABLED=true
GOOGLE_MEET_DEFAULT_TIMEZONE=America/Sao_Paulo
```

### 4. **Testar Configuração**
Após adicionar as configurações, execute:
```bash
php artisan test:google-calendar
```

## 🆘 **Se Não Encontrar as Credenciais:**

### **Opção A: Recriar OAuth2**
1. Acesse Google Cloud Console
2. Crie um novo **OAuth 2.0 Client ID**
3. Configure como **Web application**
4. Adicione URI: `http://127.0.0.1:8000/auth/google/callback`

### **Opção B: Usar Service Account**
1. Crie um **Service Account** no Google Cloud
2. Baixe o arquivo JSON de credenciais
3. Coloque em `storage/app/google-credentials.json`
4. Configure no `.env`:
```env
GOOGLE_SERVICE_ACCOUNT_ENABLED=true
GOOGLE_SERVICE_ACCOUNT_FILE=storage/app/google-credentials.json
```

## 📞 **Suporte:**
Se precisar de ajuda para configurar, posso te auxiliar com os comandos específicos do Laravel.

