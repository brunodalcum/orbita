#!/bin/bash

# Script de emergência para corrigir Laravel Pail em produção
# Uso: bash emergency-fix-pail.sh

echo "🚨 CORREÇÃO EMERGENCIAL - Laravel Pail"
echo "======================================"

# Criar diretórios essenciais
mkdir -p bootstrap/cache storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs

# Configurar permissões
chmod -R 755 bootstrap/cache storage

# Remover caches problemáticos
rm -f bootstrap/cache/services.php bootstrap/cache/packages.php bootstrap/cache/config.php

# Recriar autoload sem dev
composer dump-autoload --optimize --no-dev

# Limpar e recriar caches
php artisan optimize:clear 2>/dev/null || echo "Optimize clear falhou"
php artisan config:cache 2>/dev/null || echo "Config cache falhou"
php artisan route:cache 2>/dev/null || echo "Route cache falhou"

echo "✅ Correção emergencial concluída!"
echo "🔗 Teste: https://srv971263.hstgr.cloud/login"
