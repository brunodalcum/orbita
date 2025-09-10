# 🎯 Sistema de Agenda com Aprovação entre Usuários

## 📋 Funcionalidades Implementadas

### **✅ Sistema de Aprovação:**
- **Solicitação de agenda** entre usuários (Super Admin, Admin, Licenciados, Funcionários)
- **Verificação de disponibilidade** automática
- **Horário comercial** configurável (09:00 às 18:00 por padrão)
- **Aprovação automática** dentro do horário comercial
- **Aprovação manual** fora do horário comercial
- **Sistema de notificações** para solicitações e respostas

### **✅ Verificações Automáticas:**
- **Conflito de horário:** Verifica se destinatário já tem compromisso
- **Horário comercial:** Verifica se está dentro do expediente
- **Disponibilidade:** Impede agendamento em horários ocupados

---

## 🗄️ Estrutura do Banco de Dados

### **📊 Tabela `agendas` (Campos Adicionados):**
```sql
solicitante_id          # Usuário que solicita a agenda
destinatario_id         # Usuário destinatário da agenda  
status_aprovacao        # pendente, aprovada, recusada, automatica
requer_aprovacao        # Se a agenda requer aprovação
fora_horario_comercial  # Se a agenda é fora do horário comercial
aprovada_em             # Data/hora da aprovação
aprovada_por            # Usuário que aprovou
motivo_recusa           # Motivo da recusa se aplicável
notificacao_enviada     # Se notificação foi enviada
```

### **📊 Tabela `business_hours`:**
```sql
user_id      # Usuário específico (null = configuração global)
dia_semana   # segunda, terca, quarta, quinta, sexta, sabado, domingo
hora_inicio  # Hora de início do expediente
hora_fim     # Hora de fim do expediente
ativo        # Se o dia está ativo para agendamentos
```

### **📊 Tabela `agenda_notifications`:**
```sql
agenda_id     # ID da agenda
user_id       # Usuário que recebe a notificação
tipo          # solicitacao, aprovacao, recusa, cancelamento, lembrete
titulo        # Título da notificação
mensagem      # Mensagem da notificação
lida          # Se a notificação foi lida
dados_extras  # Dados extras da notificação (JSON)
```

---

## 🔄 Fluxo de Funcionamento

### **1. 📝 Criação de Agenda:**
```
1. Usuário A solicita reunião com Usuário B
2. Sistema verifica disponibilidade do Usuário B
3. Sistema verifica horário comercial
4. Se dentro do horário: aprovação automática
5. Se fora do horário: requer aprovação manual
6. Notificação enviada ao destinatário
```

### **2. ✅ Processo de Aprovação:**
```
1. Destinatário recebe notificação
2. Pode aprovar ou recusar a solicitação
3. Sistema verifica novamente disponibilidade
4. Notificação de resposta enviada ao solicitante
5. Agenda confirmada ou cancelada
```

### **3. 🚫 Verificações de Conflito:**
```
1. Busca agendas existentes do destinatário
2. Verifica sobreposição de horários
3. Impede agendamento se houver conflito
4. Retorna mensagem de indisponibilidade
```

---

## 🎯 Regras de Negócio

### **📅 Horário Comercial Padrão:**
- **Segunda a Sexta:** 09:00 às 18:00
- **Sábado/Domingo:** Inativo (requer aprovação)
- **Configurável por usuário** ou global

### **🔍 Verificação de Disponibilidade:**
- **Conflito de horário:** Impede agendamento
- **Sobreposição:** Verifica início, fim e períodos internos
- **Exclusão:** Ignora a própria agenda ao editar

### **⚡ Aprovação Automática:**
- **Dentro do horário comercial:** Aprovação automática
- **Sem conflitos:** Agenda confirmada imediatamente
- **Status:** `automatica`

### **👤 Aprovação Manual:**
- **Fora do horário comercial:** Requer aprovação
- **Notificação:** Enviada ao destinatário
- **Status:** `pendente` → `aprovada` ou `recusada`

---

## 🛠️ Métodos e APIs

### **📊 Models:**

#### **BusinessHours:**
```php
isWithinBusinessHours($userId, $dataHora)     # Verifica horário comercial
hasTimeConflict($userId, $inicio, $fim)       # Verifica conflito
createDefaultBusinessHours($userId)           # Cria horários padrão
```

#### **Agenda:**
```php
aprovar($aprovadoPorId, $observacoes)         # Aprovar agenda
recusar($recusadoPorId, $motivo)              # Recusar agenda
requerAprovacao()                             # Verifica se requer aprovação
isAprovada()                                  # Verifica se está aprovada
scopePendentesAprovacao($query, $userId)      # Agendas pendentes
scopeDoUsuario($query, $userId)               # Agendas do usuário
```

#### **AgendaNotification:**
```php
createSolicitacaoNotification($agenda, $destinatarioId)  # Notificação de solicitação
createAprovacaoNotification($agenda, $solicitanteId)     # Notificação de aprovação
createRecusaNotification($agenda, $solicitanteId)       # Notificação de recusa
getNaoLidas($userId)                                     # Notificações não lidas
countNaoLidas($userId)                                   # Contar não lidas
```

### **🎮 Controller (AgendaController):**

#### **Métodos Principais:**
```php
store(Request $request)                       # Criar agenda com verificações
aprovar(Request $request, $id)               # Aprovar solicitação
recusar(Request $request, $id)               # Recusar solicitação
pendentesAprovacao()                          # Listar pendentes (view)
apiPendentesAprovacao()                       # Listar pendentes (API)
```

### **🛣️ Rotas:**
```php
POST   /agenda/{id}/aprovar                  # Aprovar agenda
POST   /agenda/{id}/recusar                  # Recusar agenda
GET    /agenda/pendentes-aprovacao           # View de pendentes
GET    /api/agenda/pendentes-aprovacao       # API de pendentes
```

---

## 💻 Exemplos de Uso

### **1. 📝 Criar Agenda com Destinatário:**
```javascript
// No formulário de criação
{
    titulo: "Reunião de Alinhamento",
    data_inicio: "2025-09-11T10:00",
    data_fim: "2025-09-11T11:00",
    destinatario_id: 2,  // ID do usuário destinatário
    tipo_reuniao: "online"
}
```

### **2. ✅ Aprovar Agenda:**
```javascript
fetch('/agenda/123/aprovar', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': token,
        'Content-Type': 'application/json'
    }
})
```

### **3. 🚫 Recusar Agenda:**
```javascript
fetch('/agenda/123/recusar', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': token,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        motivo: "Conflito com outra reunião"
    })
})
```

### **4. 📋 Listar Pendentes:**
```javascript
fetch('/api/agenda/pendentes-aprovacao')
    .then(response => response.json())
    .then(data => {
        console.log(data.agendas);
    });
```

---

## 🎨 Interface do Usuário

### **📝 Formulário de Criação:**
- **Campo adicional:** Seleção de destinatário
- **Validação:** Verificação de disponibilidade
- **Feedback:** Mensagens de conflito ou aprovação

### **📋 Lista de Agendas:**
- **Status visual:** Pendente, aprovada, recusada
- **Badges:** Indicadores de status de aprovação
- **Ações:** Botões de aprovar/recusar para destinatários

### **🔔 Notificações:**
- **Contador:** Notificações não lidas
- **Lista:** Solicitações pendentes
- **Ações rápidas:** Aprovar/recusar direto da notificação

---

## 🚀 Próximos Passos

### **🎨 Interface:**
1. **Atualizar formulário** de criação com seleção de usuário
2. **Criar view** de agendas pendentes
3. **Adicionar notificações** no layout
4. **Implementar badges** de status

### **⚡ Funcionalidades:**
1. **Configuração de horários** por usuário
2. **Notificações em tempo real** (WebSockets)
3. **Relatórios** de aprovações
4. **Integração com email** para notificações

### **🔧 Melhorias:**
1. **Cache** de verificações de disponibilidade
2. **Bulk operations** para múltiplas aprovações
3. **Histórico** de aprovações/recusas
4. **Permissões** granulares por tipo de usuário

---

## 📊 Status da Implementação

### **✅ Concluído:**
- ✅ **Models e migrations** criados
- ✅ **Lógica de verificação** implementada
- ✅ **Sistema de aprovação** funcionando
- ✅ **Notificações** implementadas
- ✅ **APIs** criadas
- ✅ **Rotas** configuradas

### **🔄 Em Desenvolvimento:**
- 🔄 **Interface do usuário** (views)
- 🔄 **Integração com formulário** existente
- 🔄 **Notificações visuais** no dashboard

### **📋 Pendente:**
- 📋 **Testes** automatizados
- 📋 **Documentação** de API
- 📋 **Configuração** de horários por usuário

---

**🎯 Sistema completo de agenda com aprovação implementado!**  
**🚀 Pronto para integração com a interface do usuário!** ✨
