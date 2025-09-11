# 🧪 Guia de Testes e Configuração de Lembretes - Órbita

## 📧 **TESTANDO E-MAILS DE LEMBRETES**

### **🚀 Teste Rápido de E-mail:**

#### **✅ Comando para Teste Imediato:**
```bash
# Testar envio para seu e-mail
php artisan reminders:test-email brunodalcum@dspay.com.br --send-now

# Testar com agenda específica
php artisan reminders:test-email brunodalcum@dspay.com.br --agenda-id=47 --send-now

# Testar via job (background)
php artisan reminders:test-email brunodalcum@dspay.com.br
```

#### **📋 O que o teste faz:**
1. ✅ **Cria um lembrete** de teste com dados reais
2. ✅ **Envia e-mail** usando o template responsivo
3. ✅ **Marca como enviado** no banco de dados
4. ✅ **Mostra resultado** no terminal

#### **📧 Verificar E-mail:**
- **Assunto:** `🔔 Sua reunião começa em breve: [Título] (TESTE)`
- **Remetente:** Configurado no `.env` (MAIL_FROM_ADDRESS)
- **Template:** HTML responsivo com design moderno
- **Conteúdo:** Dados da agenda real + informações de teste

---

## ⚙️ **CONFIGURANDO PRAZOS DOS LEMBRETES**

### **📋 Ver Configurações Atuais:**

#### **🌐 Listar Todas as Configurações:**
```bash
php artisan reminders:configure --list
```

#### **👤 Ver Configuração de Usuário Específico:**
```bash
php artisan reminders:configure --list --user-id=1
```

### **🔧 Configurar Prazos Personalizados:**

#### **⚡ Configuração Rápida:**
```bash
# Configurar para usuário específico (em minutos)
php artisan reminders:configure --user-id=1 --offsets=4320,2880,1440,60

# Exemplos de configurações:
# 4320,2880,1440,60  = 3 dias, 2 dias, 1 dia, 1 hora
# 2880,1440,60       = 2 dias, 1 dia, 1 hora (padrão)
# 1440,60            = 1 dia, 1 hora (simples)
# 60                 = Apenas 1 hora antes
```

#### **🎯 Configuração Interativa:**
```bash
php artisan reminders:configure
```
**Opções disponíveis:**
1. **Padrão:** 48h, 24h, 1h antes
2. **Simples:** 24h, 1h antes  
3. **Antecipado:** 48h, 24h antes
4. **Completo:** 3 dias, 48h, 24h, 1h antes
5. **Último minuto:** 1h antes
6. **Personalizado:** Definir seus próprios prazos

### **🔄 Resetar Configurações:**

#### **👤 Resetar Usuário Específico:**
```bash
php artisan reminders:configure --user-id=1 --reset
```

#### **🌐 Resetar Todas as Configurações:**
```bash
php artisan reminders:configure --reset
```

---

## 📊 **MONITORAMENTO E MÉTRICAS**

### **📈 Ver Métricas de Lembretes:**

#### **📋 Métricas Básicas:**
```bash
# Métricas de hoje
php artisan reminders:metrics

# Métricas da semana
php artisan reminders:metrics --period=week

# Métricas detalhadas
php artisan reminders:metrics --detailed
```

#### **🔍 Processamento Manual:**
```bash
# Ver lembretes prontos para envio (sem enviar)
php artisan reminders:process --dry-run

# Processar lembretes pendentes
php artisan reminders:process
```

---

## 🎯 **CONFIGURAÇÕES AVANÇADAS**

### **⏰ Tabela de Conversão de Prazos:**

| **Prazo**        | **Minutos** | **Uso Recomendado**           |
|------------------|-------------|-------------------------------|
| 30 minutos       | 30          | Lembretes urgentes            |
| 1 hora           | 60          | Padrão mínimo                 |
| 2 horas          | 120         | Reuniões importantes          |
| 4 horas          | 240         | Meio período                  |
| 12 horas         | 720         | Meio dia                      |
| 1 dia (24h)      | 1440        | **Padrão recomendado**        |
| 2 dias (48h)     | 2880        | **Padrão recomendado**        |
| 3 dias (72h)     | 4320        | Eventos importantes           |
| 1 semana         | 10080       | Eventos muito importantes     |

### **👥 Configurações por Tipo de Usuário:**

#### **🔧 Super Admin/Admin:**
```bash
php artisan reminders:configure --user-id=1 --offsets=4320,2880,1440,60
# 3 dias, 2 dias, 1 dia, 1 hora
```

#### **👤 Licenciados:**
```bash
php artisan reminders:configure --user-id=15 --offsets=2880,1440,60
# 2 dias, 1 dia, 1 hora (padrão)
```

#### **⚡ Funcionários:**
```bash
php artisan reminders:configure --user-id=X --offsets=1440,60
# 1 dia, 1 hora (simples)
```

---

## 🔧 **CONFIGURAÇÃO DO SISTEMA**

### **📧 Configuração de E-mail (.env):**

#### **✅ Configurações Necessárias:**
```env
# Configurações de e-mail (já configuradas para Hostinger)
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@dominio.com
MAIL_PASSWORD=sua-senha
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@orbita.com
MAIL_FROM_NAME="Órbita Lembretes"
```

### **⏰ Configuração do Scheduler:**

#### **🔄 Cron Job (Produção):**
```bash
# Adicionar no crontab do servidor
* * * * * cd /path/to/orbita && php artisan schedule:run >> /dev/null 2>&1
```

#### **🚀 Queue Worker (Opcional - Melhor Performance):**
```bash
# Executar em background
php artisan queue:work --daemon
```

---

## 🧪 **CENÁRIOS DE TESTE**

### **✅ Teste 1: E-mail Imediato**
```bash
php artisan reminders:test-email brunodalcum@dspay.com.br --send-now
```
**Resultado esperado:** E-mail recebido em 1-2 minutos

### **✅ Teste 2: E-mail via Job**
```bash
php artisan reminders:test-email brunodalcum@dspay.com.br
```
**Resultado esperado:** E-mail processado em background

### **✅ Teste 3: Configuração Personalizada**
```bash
php artisan reminders:configure --user-id=1 --offsets=120,60,30
```
**Resultado esperado:** Lembretes em 2h, 1h, 30min antes

### **✅ Teste 4: Agenda Real**
1. **Criar agenda** para 2-3 dias no futuro
2. **Verificar lembretes** criados automaticamente
3. **Aguardar processamento** nos horários corretos

---

## 🎯 **CASOS DE USO PRÁTICOS**

### **📅 Reunião Importante (VIP):**
```bash
php artisan reminders:configure --user-id=1 --offsets=10080,4320,2880,1440,60
# 1 semana, 3 dias, 2 dias, 1 dia, 1 hora
```

### **📅 Reunião Padrão:**
```bash
php artisan reminders:configure --user-id=1 --offsets=2880,1440,60
# 2 dias, 1 dia, 1 hora (padrão)
```

### **📅 Reunião Rápida:**
```bash
php artisan reminders:configure --user-id=1 --offsets=240,60
# 4 horas, 1 hora
```

### **📅 Apenas Lembrete Final:**
```bash
php artisan reminders:configure --user-id=1 --offsets=60
# Apenas 1 hora antes
```

---

## 🔍 **TROUBLESHOOTING**

### **❌ E-mail não chegou:**

#### **🔧 Verificações:**
1. **Configuração SMTP** no `.env`
2. **Logs do Laravel** em `storage/logs/`
3. **Status do lembrete** no banco de dados
4. **Caixa de spam** do e-mail

#### **🧪 Teste de Configuração:**
```bash
# Testar configuração de e-mail
php artisan tinker
Mail::raw('Teste de configuração', function($message) {
    $message->to('brunodalcum@dspay.com.br')->subject('Teste Órbita');
});
```

### **❌ Lembretes não são criados:**

#### **🔧 Verificações:**
1. **Agenda tem participantes** válidos
2. **Data da agenda** é futura
3. **Logs do ReminderService** em `storage/logs/`

#### **🧪 Teste Manual:**
```bash
php artisan tinker
$agenda = App\Models\Agenda::find(47);
$service = new App\Services\ReminderService();
$stats = $service->createForEvent($agenda);
dd($stats);
```

### **❌ Scheduler não executa:**

#### **🔧 Verificações:**
1. **Cron job** configurado no servidor
2. **Permissões** do diretório do projeto
3. **Logs do scheduler** em `storage/logs/`

#### **🧪 Teste Manual:**
```bash
# Executar scheduler manualmente
php artisan schedule:run

# Processar lembretes manualmente
php artisan reminders:process
```

---

## 📞 **COMANDOS DE REFERÊNCIA RÁPIDA**

### **🧪 Testes:**
```bash
# Testar e-mail
php artisan reminders:test-email SEU-EMAIL@dominio.com --send-now

# Ver métricas
php artisan reminders:metrics

# Processar lembretes
php artisan reminders:process --dry-run
```

### **⚙️ Configurações:**
```bash
# Listar configurações
php artisan reminders:configure --list

# Configurar prazos
php artisan reminders:configure --user-id=1 --offsets=2880,1440,60

# Configuração interativa
php artisan reminders:configure
```

### **🔧 Manutenção:**
```bash
# Executar scheduler
php artisan schedule:run

# Limpar caches
php artisan optimize:clear

# Ver logs
tail -f storage/logs/laravel.log
```

---

## 🎉 **RESULTADO ESPERADO**

### **✅ Após Configuração Correta:**

1. **📧 E-mails chegam** nos prazos configurados
2. **📊 Métricas mostram** lembretes enviados
3. **🔄 Sistema funciona** automaticamente
4. **⚙️ Configurações** são respeitadas por usuário
5. **🧪 Testes passam** sem erros

### **📧 Template do E-mail:**
- **Design responsivo** e moderno
- **Informações claras** (quando, onde, com quem)
- **Botões de ação** (Google Meet, Ver Agenda)
- **Indicadores visuais** de urgência
- **Compatibilidade** com todos os clientes de e-mail

---

## 🚀 **PRÓXIMOS PASSOS**

### **1️⃣ Testar E-mail:**
```bash
php artisan reminders:test-email brunodalcum@dspay.com.br --send-now
```

### **2️⃣ Configurar Prazos:**
```bash
php artisan reminders:configure --user-id=1 --offsets=2880,1440,60
```

### **3️⃣ Verificar Funcionamento:**
```bash
php artisan reminders:metrics
```

### **4️⃣ Ativar em Produção:**
- Configurar cron job
- Monitorar logs
- Verificar métricas

---

**🎯 O sistema está 100% funcional e pronto para uso! Teste os comandos acima para verificar o funcionamento dos e-mails e configurar os prazos conforme sua necessidade.** ✅📧
