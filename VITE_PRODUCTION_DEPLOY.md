# 🚀 Guia de Deploy - Correção do Erro Vite Manifest

## ❌ **Problema Identificado**

```
Illuminate\Foundation\ViteManifestNotFoundException
Vite manifest not found at: /home/user/htdocs/srv971263.hstgr.cloud/public/build/manifest.json
```

**URL:** https://srv971263.hstgr.cloud/dashboard/users

---

## ✅ **Soluções Implementadas**

### 1. **Build de Produção Gerado Localmente**
```bash
npm run build
```

**Arquivos criados:**
- ✅ `public/build/manifest.json`
- ✅ `public/build/css/app-DatVzB5z.css` (102.78 kB)
- ✅ `public/build/js/app-C0G0cght.js` (35.48 kB)

### 2. **Fallback Implementado nos Layouts**
- ✅ `resources/views/layouts/app.blade.php`
- ✅ `resources/views/layouts/dashboard.blade.php`
- ✅ `resources/views/layouts/guest.blade.php`
- ✅ `resources/views/layouts/production.blade.php`

**Lógica de Fallback:**
```blade
@if (file_exists(public_path('build/manifest.json')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    <!-- Fallback CDN para produção -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
@endif
```

### 3. **Script de Deploy Automatizado**
- ✅ `deploy-production.sh` criado
- ✅ Inclui build do Vite
- ✅ Verificações de integridade
- ✅ Cache otimizado

---

## 🛠️ **Instruções de Deploy**

### **Opção 1: Deploy Completo (Recomendado)**

1. **Execute o script de deploy:**
```bash
./deploy-production.sh
```

2. **Faça upload dos arquivos para o servidor:**
```bash
# Via FTP/SFTP, inclua OBRIGATORIAMENTE:
- public/build/manifest.json
- public/build/css/app-*.css
- public/build/js/app-*.js
- Todos os outros arquivos do projeto
```

3. **No servidor, execute:**
```bash
cd /home/user/htdocs/srv971263.hstgr.cloud
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### **Opção 2: Deploy Apenas dos Assets**

Se você já fez o deploy do código, basta:

1. **Gerar build localmente:**
```bash
npm run build
```

2. **Fazer upload apenas da pasta:**
```bash
public/build/
├── manifest.json
├── css/app-*.css
└── js/app-*.js
```

3. **No servidor:**
```bash
php artisan view:clear
```

### **Opção 3: Deploy sem Build (Fallback)**

Se não conseguir fazer o build, o sistema usará CDN automaticamente:
- ✅ Tailwind CSS via CDN
- ✅ Alpine.js via CDN  
- ✅ Axios via CDN
- ✅ Font Awesome já configurado

---

## 🔍 **Verificação do Deploy**

### **1. Verificar se o manifest existe:**
```bash
# No servidor
ls -la /home/user/htdocs/srv971263.hstgr.cloud/public/build/manifest.json
```

### **2. Testar a aplicação:**
```bash
# Acessar no navegador:
https://srv971263.hstgr.cloud/dashboard/users
```

### **3. Verificar logs de erro:**
```bash
# No servidor
tail -f storage/logs/laravel.log
```

---

## 📂 **Estrutura de Arquivos Necessária**

```
public/
├── build/
│   ├── manifest.json          ← OBRIGATÓRIO
│   ├── css/
│   │   └── app-*.css         ← OBRIGATÓRIO
│   └── js/
│       └── app-*.js          ← OBRIGATÓRIO
├── images/
└── index.php
```

---

## 🚨 **Troubleshooting**

### **Erro persiste após deploy:**

1. **Verificar permissões:**
```bash
chmod -R 755 public/build/
```

2. **Limpar todos os caches:**
```bash
php artisan optimize:clear
```

3. **Verificar .env:**
```bash
APP_ENV=production
APP_DEBUG=false
```

4. **Testar fallback:**
```bash
# Renomear temporariamente o manifest
mv public/build/manifest.json public/build/manifest.json.bak
# Testar se o site carrega com CDN
# Restaurar o manifest
mv public/build/manifest.json.bak public/build/manifest.json
```

### **Build não funciona localmente:**

1. **Reinstalar dependências:**
```bash
rm -rf node_modules package-lock.json
npm install
npm run build
```

2. **Verificar Node.js:**
```bash
node --version  # Requer Node 16+
npm --version
```

---

## ✅ **Checklist de Deploy**

- [ ] Build gerado localmente (`npm run build`)
- [ ] Arquivos `public/build/*` enviados para servidor
- [ ] Permissões configuradas (755)
- [ ] Cache limpo no servidor
- [ ] Teste da URL funcionando
- [ ] Verificação de fallback (se necessário)

---

## 🎯 **Resultado Esperado**

Após o deploy correto:
- ✅ URL https://srv971263.hstgr.cloud/dashboard/users carrega sem erros
- ✅ Estilos aplicados corretamente
- ✅ JavaScript funcionando
- ✅ Interface responsiva e interativa

---

## 📞 **Suporte**

Se o erro persistir após seguir este guia:

1. Verificar logs: `storage/logs/laravel.log`
2. Testar em modo de desenvolvimento local
3. Verificar se todos os arquivos foram enviados
4. Confirmar permissões do servidor

**O sistema tem fallback automático, então mesmo sem o build, a aplicação deve funcionar com CDN!**
