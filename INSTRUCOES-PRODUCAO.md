# 🚀 Instruções para Corrigir Erro em Produção

## 🔍 **Problema**
O arquivo `fix-production-paths.php` não foi enviado para o servidor de produção.

## 🔧 **Solução: Execute Diretamente no Servidor**

### **Opção 1: Criar Arquivo no Servidor**

1. **Acesse o servidor de produção via SSH ou painel de controle**

2. **Navegue para o diretório do projeto:**
   ```bash
   cd /home/user/htdocs/srv971263.hstgr.cloud/
   ```

3. **Crie o arquivo de correção:**
   ```bash
   nano fix-production-direct.php
   ```

4. **Cole o seguinte conteúdo:**
   ```php
   <?php

   // Script para corrigir problemas de caminhos em produção
   // Execute diretamente no servidor: php fix-production-direct.php

   echo "🔧 Corrigindo problemas de caminhos em produção...\n";

   // 1. Verificar se estamos no diretório correto
   if (!file_exists('artisan')) {
       echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
       exit(1);
   }

   // 2. Verificar configurações atuais
   echo "📋 Verificando configurações atuais...\n";
   echo "Diretório atual: " . getcwd() . "\n";
   echo "APP_ENV: " . (getenv('APP_ENV') ?: 'não definido') . "\n";
   echo "APP_DEBUG: " . (getenv('APP_DEBUG') ?: 'não definido') . "\n";

   // 3. Verificar arquivo .env
   echo "\n⚙️ Verificando arquivo .env...\n";
   if (file_exists('.env')) {
       $envContent = file_get_contents('.env');
       
       // Verificar se há configurações de desenvolvimento
       if (strpos($envContent, 'APP_ENV=local') !== false) {
           echo "⚠️ APP_ENV está definido como 'local' - isso pode causar problemas\n";
       }
       
       if (strpos($envContent, 'APP_DEBUG=true') !== false) {
           echo "⚠️ APP_DEBUG está definido como 'true' - isso pode causar problemas\n";
       }
       
       // Verificar se há caminhos absolutos incorretos
       if (strpos($envContent, '/Applications/MAMP/htdocs/orbita/') !== false) {
           echo "❌ Encontrados caminhos de desenvolvimento no .env\n";
       }
   } else {
       echo "❌ Arquivo .env não encontrado\n";
   }

   // 4. Corrigir configurações para produção
   echo "\n🔧 Corrigindo configurações para produção...\n";
   if (file_exists('.env')) {
       $envContent = file_get_contents('.env');
       
       // Fazer backup do .env
       file_put_contents('.env.backup', $envContent);
       echo "✅ Backup do .env criado\n";
       
       // Substituir configurações de desenvolvimento
       $envContent = preg_replace('/^APP_ENV=.*$/m', 'APP_ENV=production', $envContent);
       $envContent = preg_replace('/^APP_DEBUG=.*$/m', 'APP_DEBUG=false', $envContent);
       
       // Remover caminhos absolutos incorretos
       $envContent = preg_replace('/^.*\/Applications\/MAMP\/htdocs\/orbita\/.*$/m', '', $envContent);
       
       // Adicionar configurações de produção se não existirem
       if (strpos($envContent, 'APP_ENV=production') === false) {
           $envContent .= "\nAPP_ENV=production\n";
       }
       
       if (strpos($envContent, 'APP_DEBUG=false') === false) {
           $envContent .= "\nAPP_DEBUG=false\n";
       }
       
       if (file_put_contents('.env', $envContent)) {
           echo "✅ Arquivo .env corrigido para produção\n";
       } else {
           echo "❌ Erro ao corrigir .env\n";
       }
   } else {
       echo "❌ Arquivo .env não encontrado\n";
   }

   // 5. Limpar caches
   echo "\n🧹 Limpando caches...\n";
   $commands = [
       'php artisan cache:clear',
       'php artisan config:clear',
       'php artisan route:clear',
       'php artisan view:clear'
   ];

   foreach ($commands as $command) {
       echo "Executando: $command\n";
       $output = shell_exec($command . ' 2>&1');
       if ($output) {
           echo "Saída: $output\n";
       }
   }

   // 6. Verificar e criar diretórios necessários
   echo "\n📁 Verificando diretórios...\n";
   $directories = [
       'storage/framework/views',
       'storage/framework/cache',
       'storage/framework/sessions',
       'storage/logs',
       'bootstrap/cache'
   ];

   foreach ($directories as $dir) {
       if (!is_dir($dir)) {
           if (mkdir($dir, 0755, true)) {
               echo "✅ Diretório criado: $dir\n";
           } else {
               echo "❌ Erro ao criar diretório: $dir\n";
           }
       } else {
           echo "✅ Diretório existe: $dir\n";
       }
   }

   // 7. Corrigir permissões
   echo "\n🔐 Corrigindo permissões...\n";
   $paths = [
       'storage',
       'storage/framework',
       'storage/framework/views',
       'storage/framework/cache',
       'storage/framework/sessions',
       'storage/logs',
       'bootstrap/cache'
   ];

   foreach ($paths as $path) {
       if (is_dir($path)) {
           chmod($path, 0755);
           echo "Permissão 755 aplicada: $path\n";
       }
   }

   // 8. Regenerar caches
   echo "\n🔄 Regenerando caches...\n";
   $regenerateCommands = [
       'php artisan config:cache',
       'php artisan route:cache'
   ];

   foreach ($regenerateCommands as $command) {
       echo "Executando: $command\n";
       $output = shell_exec($command . ' 2>&1');
       if ($output) {
           echo "Saída: $output\n";
       }
   }

   // 9. Verificar configurações finais
   echo "\n🧪 Verificando configurações finais...\n";
   $testCommands = [
       'php artisan --version',
       'php artisan config:show app.env',
       'php artisan config:show app.debug'
   ];

   foreach ($testCommands as $command) {
       echo "Executando: $command\n";
       $output = shell_exec($command . ' 2>&1');
       if ($output) {
           echo "Saída: $output\n";
       }
   }

   echo "\n🎉 Correção de caminhos concluída!\n";
   echo "\n📋 Configurações aplicadas:\n";
   echo "- APP_ENV=production\n";
   echo "- APP_DEBUG=false\n";
   echo "- Caminhos de desenvolvimento removidos\n";
   echo "- Diretórios criados/verificados\n";
   echo "- Permissões corrigidas\n";
   echo "- Caches regenerados\n";
   echo "\n✅ Agora teste o login em produção:\n";
   echo "https://srv971263.hstgr.cloud/login\n";
   echo "\n🔍 Se ainda houver problemas:\n";
   echo "1. Verifique se o servidor web está rodando\n";
   echo "2. Verifique se o PHP está configurado corretamente\n";
   echo "3. Execute: chown -R www-data:www-data storage/\n";
   echo "4. Execute: chown -R www-data:www-data bootstrap/cache/\n";
   ```

5. **Salve o arquivo (Ctrl+X, Y, Enter)**

6. **Execute o script:**
   ```bash
   php fix-production-direct.php
   ```

### **Opção 2: Comandos Manuais**

Se não conseguir criar o arquivo, execute estes comandos diretamente:

```bash
# 1. Navegar para o diretório do projeto
cd /home/user/htdocs/srv971263.hstgr.cloud/

# 2. Corrigir .env
sed -i 's/^APP_ENV=.*/APP_ENV=production/' .env
sed -i 's/^APP_DEBUG=.*/APP_DEBUG=false/' .env
sed -i '/\/Applications\/MAMP\/htdocs\/orbita\//d' .env

# 3. Limpar caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 4. Criar diretórios
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/logs
mkdir -p bootstrap/cache

# 5. Corrigir permissões
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# 6. Regenerar caches
php artisan config:cache
php artisan route:cache
```

### **Opção 3: Via Painel de Controle**

1. **Acesse o painel de controle do hosting**
2. **Vá para "File Manager"**
3. **Navegue para o diretório do projeto**
4. **Crie um novo arquivo chamado `fix-production-direct.php`**
5. **Cole o conteúdo do script**
6. **Salve o arquivo**
7. **Execute via terminal ou painel de controle**

## 🧪 **Teste Após Correção**

1. **Verificar configurações:**
   ```bash
   php artisan config:show app.env
   php artisan config:show app.debug
   ```

2. **Testar login:**
   ```
   https://srv971263.hstgr.cloud/login
   ```

## 🎯 **Resultado Esperado**

- ✅ **APP_ENV=production**
- ✅ **APP_DEBUG=false**
- ✅ **Sem caminhos de desenvolvimento**
- ✅ **Diretórios criados**
- ✅ **Permissões corretas**
- ✅ **Login funcionando**

## 🆘 **Se Ainda Houver Problemas**

Execute estes comandos adicionais:

```bash
# Corrigir proprietário
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/

# Verificar permissões
ls -la storage/framework/views/
ls -la bootstrap/cache/
```

**Execute uma das opções acima para corrigir o problema em produção!** 🚀
