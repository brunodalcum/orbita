# 👤 **Como Criar Usuário Super Admin em Produção**

## 🚀 **Método 1: Script Completo (Recomendado)**

### **📁 Arquivo: `create-super-admin.php`**

Este script verifica todas as configurações e cria o usuário Super Admin com todas as validações.

### **🔧 Como Executar:**

1. **Acesse o servidor de produção via SSH:**
   ```bash
   ssh root@seu_ip_da_vps
   ```

2. **Navegue para o diretório do projeto:**
   ```bash
   cd /var/www/orbita
   ```

3. **Crie o arquivo:**
   ```bash
   nano create-super-admin.php
   ```

4. **Cole o conteúdo do script** (já criado em `create-super-admin.php`)

5. **Execute:**
   ```bash
   php create-super-admin.php
   ```

### **📋 O que o Script Faz:**

1. **🔍 Verifica configurações** - Banco de dados, migrações
2. **🔍 Verifica tabelas** - Roles e permissions
3. **👤 Cria usuário** - Super Admin com todas as permissões
4. **🔐 Verifica permissões** - Confirma role e status
5. **📋 Lista usuários** - Mostra todos os usuários existentes

## 🚀 **Método 2: Script Simples (Emergência)**

### **📁 Arquivo: `create-admin-simple.php`**

Para casos de emergência ou quando o método completo não funciona.

### **🔧 Como Executar:**

1. **Crie o arquivo:**
   ```bash
   nano create-admin-simple.php
   ```

2. **Cole o conteúdo do script** (já criado em `create-admin-simple.php`)

3. **Execute:**
   ```bash
   php create-admin-simple.php
   ```

## 🚀 **Método 3: Comando Artisan (Manual)**

### **🔧 Via Tinker:**

```bash
php artisan tinker
```

**No Tinker:**
```php
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

// Criar usuário
$user = new User();
$user->name = 'Super Admin';
$user->email = 'admin@dspay.com.br';
$user->password = Hash::make('admin123456');
$user->role_id = 1; // Super Admin
$user->is_active = true;
$user->email_verified_at = now();
$user->save();

echo "Usuário criado: " . $user->email;
```

## 🚀 **Método 4: Seeder Personalizado**

### **📁 Criar Seeder:**

```bash
php artisan make:seeder SuperAdminSeeder
```

**Conteúdo do Seeder:**
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $superAdminRole = Role::where('name', 'super_admin')->first();
        
        if (!$superAdminRole) {
            $this->command->error('Role Super Admin não encontrado!');
            return;
        }

        $user = User::updateOrCreate(
            ['email' => 'admin@dspay.com.br'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123456'),
                'role_id' => $superAdminRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Super Admin criado: ' . $user->email);
    }
}
```

**Executar:**
```bash
php artisan db:seed --class=SuperAdminSeeder
```

## 📋 **Dados do Usuário Super Admin**

### **🔑 Credenciais Padrão:**

- **Nome:** Super Admin
- **Email:** admin@dspay.com.br
- **Senha:** admin123456
- **Role:** Super Admin
- **Status:** Ativo

### **⚠️ IMPORTANTE:**

1. **Altere a senha** após o primeiro login
2. **Configure 2FA** para maior segurança
3. **Mantenha as credenciais seguras**
4. **Use senhas fortes** em produção

## 🧪 **Verificar se Funcionou**

### **1. Testar Login:**
```
https://srv971263.hstgr.cloud/login
```

### **2. Verificar Permissões:**
```bash
php artisan tinker
```

**No Tinker:**
```php
$user = App\Models\User::where('email', 'admin@dspay.com.br')->first();
echo "Role: " . $user->role->display_name;
echo "Permissões: " . $user->getPermissions()->count();
```

### **3. Listar Usuários:**
```bash
php artisan tinker
```

**No Tinker:**
```php
App\Models\User::with('role')->get()->each(function($user) {
    echo $user->name . ' - ' . $user->email . ' - ' . $user->role->display_name . PHP_EOL;
});
```

## 🆘 **Troubleshooting**

### **Problema: Role não encontrado**
```bash
# Verificar se as migrações foram executadas
php artisan migrate:status

# Executar migrações se necessário
php artisan migrate --force

# Executar seeders
php artisan db:seed --force
```

### **Problema: Usuário não consegue fazer login**
```bash
# Verificar se o usuário está ativo
php artisan tinker
```

**No Tinker:**
```php
$user = App\Models\User::where('email', 'admin@dspay.com.br')->first();
$user->is_active = true;
$user->save();
```

### **Problema: Senha não funciona**
```bash
# Redefinir senha
php artisan tinker
```

**No Tinker:**
```php
$user = App\Models\User::where('email', 'admin@dspay.com.br')->first();
$user->password = Hash::make('nova_senha_aqui');
$user->save();
```

## ✅ **Checklist Final**

- ✅ **Usuário criado**
- ✅ **Email configurado**
- ✅ **Senha definida**
- ✅ **Role Super Admin atribuído**
- ✅ **Status ativo**
- ✅ **Login funcionando**
- ✅ **Permissões verificadas**

## 🎉 **Resultado**

Após executar qualquer um dos métodos, você terá:

- **Usuário Super Admin** criado
- **Acesso total** ao sistema
- **Todas as permissões** disponíveis
- **Login funcionando** em produção

**Execute o script no servidor de produção para criar o usuário Super Admin!** 🚀
