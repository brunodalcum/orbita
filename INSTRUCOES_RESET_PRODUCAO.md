# 🚨 RESET COMPLETO DO BANCO DE DADOS - PRODUÇÃO

## ⚠️ **ATENÇÃO CRÍTICA:**

**ESTE PROCESSO APAGA 100% DOS DADOS DO BANCO DE PRODUÇÃO!**

Mantém apenas:
- ✅ Super Admin: `brunodalcum@dspay.com.br`
- ✅ Estrutura das tabelas
- ✅ Roles e permissões básicas

---

## 🗑️ **O QUE SERÁ APAGADO:**

```bash
❌ TODOS os usuários (exceto Super Admin)
❌ TODAS as operações
❌ TODOS os white labels  
❌ TODOS os licenciados
❌ TODOS os leads
❌ TODOS os contratos
❌ TODA a agenda
❌ TODOS os estabelecimentos
❌ TODAS as campanhas
❌ TODO o branding personalizado
❌ TODOS os arquivos de upload
❌ TODOS OS OUTROS DADOS
```

---

## 🛡️ **PROCESSO SEGURO DE RESET:**

### **📋 PASSO 1: Criar Backup**
```bash
# Execute o script de backup
php backup-before-reset.php

# Resultado esperado:
✅ BACKUP CRIADO COM SUCESSO!
📁 Arquivo: storage/backups/backup_before_reset_2025-XX-XX_XX-XX-XX.sql
📊 Tamanho: XX.XX MB
```

### **📋 PASSO 2: Executar Reset**
```bash
# Execute o script de reset com confirmação
php reset-database-production.php RESET_PRODUCTION_DATABASE_CONFIRMED_2025

# O script pedirá confirmação adicional:
Digite 'SIM APAGAR TUDO' para confirmar: SIM APAGAR TUDO
```

### **📋 PASSO 3: Verificar Reset**
```bash
# Execute o script de verificação
php verify-reset.php

# Resultado esperado:
🎉 RESET VERIFICADO COM SUCESSO!
```

---

## 🔐 **CREDENCIAIS PÓS-RESET:**

### **👑 Super Admin:**
```bash
📧 Email: brunodalcum@dspay.com.br
🔑 Senha: 123456789
🏷️  Tipo: super_admin
📊 Nível: 0 (topo da hierarquia)
```

**⚠️ ALTERE A SENHA IMEDIATAMENTE APÓS O PRIMEIRO LOGIN!**

---

## 🚀 **PRÓXIMOS PASSOS APÓS RESET:**

### **1. 🔐 Primeiro Acesso:**
```bash
1. Acesse: https://seu-dominio.com/login
2. Login: brunodalcum@dspay.com.br  
3. Senha: 123456789
4. ⚠️ ALTERE A SENHA IMEDIATAMENTE!
```

### **2. 🎨 Configurar Branding da Órbita:**
```bash
1. Acesse: /hierarchy/branding?node_id=1
2. Faça upload da logo da Órbita
3. Configure cores primárias e secundárias
4. Defina favicon personalizado
5. Salve as configurações
```

### **3. 🏢 Cadastrar Primeira Operação:**
```bash
1. Acesse: /hierarchy/management/create?type=operacao
2. Preencha dados da operação
3. Configure módulos ativos
4. Defina branding específico (opcional)
5. Salve a operação
```

### **4. 🏷️ Cadastrar White Labels:**
```bash
1. Acesse: /hierarchy/management/create?type=white_label
2. Selecione a operação pai
3. Configure dados do white label
4. Defina branding personalizado
5. Configure domínio (opcional)
6. Salve o white label
```

### **5. 👤 Cadastrar Licenciados:**
```bash
1. Acesse: /hierarchy/management/create?type=licenciado_l1
2. Selecione operação ou white label pai
3. Configure dados do licenciado
4. Defina permissões específicas
5. Salve o licenciado
```

---

## 📊 **ESTRUTURA HIERÁRQUICA APÓS RESET:**

```
👑 Super Admin (brunodalcum@dspay.com.br)
├── 🏢 Operação 1 (a ser criada)
│   ├── 🏷️ White Label 1.1 (opcional)
│   │   ├── 👤 Licenciado 1.1.1
│   │   └── 👤 Licenciado 1.1.2
│   └── 👤 Licenciado 1.2 (direto na operação)
└── 🏢 Operação 2 (a ser criada)
    └── 🏷️ White Label 2.1
        └── 👤 Licenciado 2.1.1
```

---

## 🛡️ **SEGURANÇA E BACKUP:**

### **📋 Backup Automático:**
- ✅ Backup criado antes do reset
- ✅ Arquivo salvo em `storage/backups/`
- ✅ Comando de restauração fornecido

### **📋 Para Restaurar (se necessário):**
```bash
# Restaurar backup completo
mysql -u[username] -p[password] [database] < storage/backups/backup_before_reset_YYYY-MM-DD_HH-MM-SS.sql

# Limpar cache após restauração
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### **📋 Verificações de Segurança:**
- ✅ Confirmação dupla obrigatória
- ✅ Chave de segurança específica
- ✅ Verificação de ambiente
- ✅ Backup automático antes do reset

---

## ⚠️ **AVISOS IMPORTANTES:**

### **🚨 ANTES DE EXECUTAR:**
```bash
⚠️ Certifique-se de estar no ambiente correto
⚠️ Tenha certeza de que quer apagar TODOS os dados
⚠️ Verifique se o backup foi criado com sucesso
⚠️ Informe a equipe sobre a manutenção
⚠️ Tenha um plano de rollback se necessário
```

### **🚨 APÓS EXECUTAR:**
```bash
⚠️ Altere a senha do Super Admin imediatamente
⚠️ Configure o branding da Órbita
⚠️ Teste o login e navegação básica
⚠️ Verifique se todas as funcionalidades estão OK
⚠️ Comunique à equipe que o reset foi concluído
```

---

## 🔧 **TROUBLESHOOTING:**

### **❌ Erro: "Super Admin não encontrado"**
```bash
Solução: O script criará automaticamente o Super Admin
Verifique: Email e senha corretos após criação
```

### **❌ Erro: "Tabela não existe"**
```bash
Solução: Execute as migrations primeiro
Comando: php artisan migrate
```

### **❌ Erro: "Permissão negada"**
```bash
Solução: Verifique permissões dos arquivos
Comando: chmod +x *.php
```

### **❌ Erro: "Backup falhou"**
```bash
Solução: Verifique configurações do MySQL
Verifique: Credenciais e conectividade
```

---

## 📞 **SUPORTE:**

### **🆘 Em caso de problemas:**
```bash
1. NÃO ENTRE EM PÂNICO
2. Verifique os logs de erro
3. Execute o script de verificação
4. Se necessário, restaure o backup
5. Contate o suporte técnico
```

### **📋 Informações para Suporte:**
```bash
- Horário do reset
- Mensagens de erro completas
- Resultado do script de verificação
- Tamanho e data do backup
- Ambiente (produção/staging)
```

---

## 🏆 **CONCLUSÃO:**

**Este processo foi projetado para ser:**
- ✅ **Seguro** - Múltiplas confirmações e backup automático
- ✅ **Completo** - Remove todos os dados desnecessários
- ✅ **Verificável** - Script de verificação pós-reset
- ✅ **Reversível** - Backup completo para restauração

**🎯 Após o reset, você terá um sistema limpo e pronto para configurar a hierarquia White Label do zero!**
