# 🚨 DIAGNÓSTICO E CORREÇÃO - PRODUÇÃO PAROU

## 📋 PASSOS PARA DIAGNÓSTICO

### 1. TESTE RÁPIDO DE STATUS
```bash
php test-production-status.php
```
Este script verifica rapidamente:
- PHP funcionando
- Permissões de escrita
- Arquivos essenciais (.env, vendor)
- Erros recentes no log

### 2. DIAGNÓSTICO COMPLETO
```bash
php diagnose-production-issue.php
```
Este script verifica:
- Bootstrap do Laravel
- Configurações do .env
- Permissões de diretórios
- Logs de erro
- Conexão com banco de dados
- Rotas críticas
- Extensões PHP necessárias
- Espaço em disco

### 3. VERIFICAÇÃO DO SISTEMA DE PERMISSÕES
```bash
php check-permissions-system.php
```
Este script verifica especificamente:
- Tabelas do sistema de permissões
- Usuário Super Admin
- Permissões cadastradas
- Relações role-permission
- Rotas de permissões
- Middleware funcionando

## 🔧 CORREÇÃO DE EMERGÊNCIA

### Executar Script de Correção Automática
```bash
php emergency-production-fix.php
```

Este script executa automaticamente:
1. Limpa todos os caches
2. Corrige permissões de diretórios
3. Recria caches essenciais
4. Verifica banco de dados
5. Testa rotas críticas
6. Gera relatório de status

### Correções Manuais Alternativas

#### 1. Limpar Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### 2. Corrigir Permissões
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

#### 3. Recriar Caches
```bash
php artisan config:cache
php artisan route:cache
```

#### 4. Executar Migrações (se necessário)
```bash
php artisan migrate --force
```

#### 5. Popular Permissões (se necessário)
```bash
php artisan db:seed --class=PermissionSeeder --force
```

## 🔍 PROBLEMAS COMUNS E SOLUÇÕES

### 1. Erro 500 - Internal Server Error
**Possíveis causas:**
- Permissões de arquivo incorretas
- Cache corrompido
- Erro no .env
- Problema de rota

**Solução:**
```bash
php emergency-production-fix.php
```

### 2. Rota 'logout' not defined
**Causa:** Views usando `route('logout')` em vez de `route('logout.custom')`

**Solução:** Já corrigida no código, mas execute:
```bash
php artisan view:clear
```

### 3. Erro de Permissões no Sistema
**Causa:** Tabelas de permissões não foram criadas ou populadas

**Solução:**
```bash
php artisan migrate --force
php artisan db:seed --class=PermissionSeeder --force
```

### 4. Usuário sem Acesso
**Causa:** Usuário sem role ou permissões

**Solução:**
```bash
php artisan tinker
# No tinker:
$user = App\Models\User::find(1);
$superAdminRole = App\Models\Role::where('name', 'super_admin')->first();
$user->update(['role_id' => $superAdminRole->id]);
```

## 📊 VERIFICAÇÃO FINAL

Após executar as correções, verifique:

1. **Acesso ao site:**
   ```
   curl -I http://seu-dominio.com
   ```

2. **Página de login:**
   ```
   curl -I http://seu-dominio.com/login
   ```

3. **Dashboard (após login):**
   ```
   http://seu-dominio.com/dashboard
   ```

4. **Sistema de permissões:**
   ```
   http://seu-dominio.com/permissions
   ```

## 🆘 CONTATO DE EMERGÊNCIA

Se os scripts não resolverem:

1. **Verificar logs detalhados:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Verificar logs do servidor web:**
   ```bash
   tail -f /var/log/nginx/error.log
   # ou
   tail -f /var/log/apache2/error.log
   ```

3. **Verificar status dos serviços:**
   ```bash
   systemctl status nginx
   systemctl status php8.1-fpm
   systemctl status mysql
   ```

## 📝 RELATÓRIO DE STATUS

Após executar os scripts, verifique o arquivo:
```bash
cat production-status.json
```

Este arquivo contém informações sobre o status atual do sistema.
