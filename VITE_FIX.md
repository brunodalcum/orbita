# 🔧 Solução para Erro do Manifest Vite

## Problema
```
Vite manifest not found at: /home/user/htdocs/srv971263.hstgr.cloud/public/build/manifest.json
```

## Causa
O erro ocorre porque os assets do Vite não foram compilados durante o deploy. O Laravel está procurando pelo arquivo `manifest.json` que é gerado quando você executa `npm run build`.

## Soluções

### 1. Solução Rápida (Recomendada)
Execute o script de correção:
```bash
# No PowerShell
.\fix-manifest.ps1

# No Linux/Mac
chmod +x fix-manifest.ps1
./fix-manifest.ps1
```

### 2. Solução Manual
```bash
# 1. Instalar dependências Node.js
npm ci --production

# 2. Build dos assets
npm run build

# 3. Verificar se o manifest foi criado
ls public/build/manifest.json
```

### 3. Deploy Completo
Execute o script de deploy atualizado:
```bash
# PowerShell
.\deploy.ps1

# Linux/Mac
./deploy.sh
```

## Verificação
Após executar uma das soluções, verifique se o arquivo existe:
```bash
ls -la public/build/manifest.json
```

## Estrutura Esperada
```
public/
└── build/
    ├── manifest.json          # ← Este arquivo deve existir
    ├── css/
    │   └── app-[hash].css
    └── js/
        └── app-[hash].js
```

## Prevenção
Para evitar este problema no futuro, sempre execute o deploy completo que inclui:
1. ✅ Instalação das dependências PHP (`composer install`)
2. ✅ Instalação das dependências Node.js (`npm ci --production`)
3. ✅ Build dos assets (`npm run build`)
4. ✅ Otimização do Laravel (`php artisan config:cache`)

## Comandos Úteis
```bash
# Verificar status do Vite
npm run dev

# Build para produção
npm run build

# Limpar cache do Laravel
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

# 🔧 Problema Adicional: Erro do cURL SSL

## Problema
```
PHP Fatal error: Cannot redeclare function configureCurlSSL()
```

## Causa
A função `configureCurlSSL()` está sendo declarada múltiplas vezes, causando conflito.

## Solução
Execute o script de correção do cURL SSL:
```bash
# No PowerShell
.\fix-curl-ssl.ps1
```

## Solução Manual
```bash
# 1. Limpar todos os caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# 2. Verificar se o arquivo curl-ssl-fix.php está correto
# (deve ter apenas uma declaração da função)

# 3. Otimizar para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Ordem de Execução Recomendada
1. 🔧 Corrigir problema do cURL SSL: `.\fix-curl-ssl.ps1`
2. 🎨 Corrigir problema do Vite: `.\fix-manifest.ps1`
3. 🚀 Executar deploy completo: `.\deploy.ps1`
