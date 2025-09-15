<?php

/**
 * Script de debug específico para produção
 * Verifica por que o seletor não aparece em https://srv971263.hstgr.cloud/hierarchy/branding
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;

echo "🔍 DEBUG BRANDING PRODUÇÃO - " . date('Y-m-d H:i:s') . "\n\n";

try {
    // Verificar ambiente
    echo "🌍 AMBIENTE:\n";
    echo "   APP_ENV: " . env('APP_ENV', 'não definido') . "\n";
    echo "   APP_URL: " . env('APP_URL', 'não definido') . "\n";
    echo "   DB_HOST: " . env('DB_HOST', 'não definido') . "\n";
    echo "   DB_DATABASE: " . env('DB_DATABASE', 'não definido') . "\n\n";
    
    // Verificar conexão com banco
    echo "🗄️  CONEXÃO COM BANCO:\n";
    try {
        $connection = DB::connection();
        $databaseName = $connection->getDatabaseName();
        echo "   ✅ Conectado ao banco: $databaseName\n";
    } catch (Exception $e) {
        echo "   ❌ Erro de conexão: " . $e->getMessage() . "\n";
        exit(1);
    }
    
    // Buscar Super Admin
    echo "\n👑 SUPER ADMIN:\n";
    $superAdmin = User::where('email', 'brunodalcum@dspay.com.br')->first();
    
    if (!$superAdmin) {
        echo "   ❌ Super Admin NÃO encontrado!\n";
        echo "   🔍 Verificando outros usuários...\n";
        
        $allUsers = User::all();
        echo "   📊 Total de usuários: " . $allUsers->count() . "\n";
        
        foreach ($allUsers as $user) {
            echo "      - ID {$user->id}: {$user->name} ({$user->email}) - {$user->node_type}\n";
        }
        
        exit(1);
    }
    
    echo "   ✅ Super Admin encontrado:\n";
    echo "      ID: {$superAdmin->id}\n";
    echo "      Nome: {$superAdmin->name}\n";
    echo "      Email: {$superAdmin->email}\n";
    echo "      Tipo: {$superAdmin->node_type}\n";
    echo "      Ativo: " . ($superAdmin->is_active ? 'SIM' : 'NÃO') . "\n";
    echo "      É Super Admin: " . ($superAdmin->isSuperAdminNode() ? 'SIM' : 'NÃO') . "\n";
    
    // Verificar método isSuperAdminNode
    echo "\n🔍 VERIFICANDO MÉTODO isSuperAdminNode():\n";
    try {
        $isSuperAdmin = $superAdmin->isSuperAdminNode();
        echo "   ✅ Método executado com sucesso: " . ($isSuperAdmin ? 'TRUE' : 'FALSE') . "\n";
        
        // Verificar condições do método
        echo "   🔍 Verificando condições:\n";
        echo "      node_type == 'super_admin': " . ($superAdmin->node_type === 'super_admin' ? 'SIM' : 'NÃO') . "\n";
        echo "      role->name == 'super_admin': ";
        
        if ($superAdmin->role) {
            echo ($superAdmin->role->name === 'super_admin' ? 'SIM' : 'NÃO') . " (role: {$superAdmin->role->name})\n";
        } else {
            echo "NÃO (role não encontrada)\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erro ao executar método: " . $e->getMessage() . "\n";
    }
    
    // Buscar outros nós
    echo "\n🏢 OUTROS NÓS:\n";
    $otherNodes = User::whereIn('node_type', ['operacao', 'white_label', 'licenciado_l1', 'licenciado_l2', 'licenciado_l3'])
        ->where('is_active', true)
        ->where('id', '!=', $superAdmin->id)
        ->orderBy('node_type')
        ->orderBy('name')
        ->get();
    
    echo "   📊 Total de outros nós: " . $otherNodes->count() . "\n";
    
    if ($otherNodes->count() > 0) {
        foreach ($otherNodes as $node) {
            echo "      - ID {$node->id}: {$node->name} ({$node->email}) - {$node->node_type}\n";
        }
    } else {
        echo "      (Nenhum outro nó encontrado)\n";
    }
    
    // Simular coleção final
    echo "\n📋 COLEÇÃO FINAL (availableNodes):\n";
    $availableNodes = collect();
    $availableNodes->push($superAdmin);
    $availableNodes = $availableNodes->merge($otherNodes);
    
    echo "   📊 Total: " . $availableNodes->count() . "\n";
    foreach ($availableNodes as $node) {
        echo "      - {$node->name} ({$node->node_type})\n";
    }
    
    // Verificar se a view deve mostrar o seletor
    echo "\n🎨 VERIFICAÇÃO DA VIEW:\n";
    echo "   \$user->isSuperAdminNode(): " . ($superAdmin->isSuperAdminNode() ? 'TRUE' : 'FALSE') . "\n";
    echo "   Condição @if(\$user->isSuperAdminNode()): " . ($superAdmin->isSuperAdminNode() ? 'PASSA' : 'NÃO PASSA') . "\n";
    
    if ($superAdmin->isSuperAdminNode()) {
        echo "   ✅ Seletor DEVE aparecer na view\n";
    } else {
        echo "   ❌ Seletor NÃO vai aparecer na view\n";
        echo "   🔧 PROBLEMA: Método isSuperAdminNode() retorna FALSE\n";
    }
    
    // Verificar cache
    echo "\n💾 VERIFICAÇÃO DE CACHE:\n";
    try {
        $cacheKey = 'user_' . $superAdmin->id . '_is_super_admin';
        echo "   🔍 Verificando cache para chave: $cacheKey\n";
        
        // Limpar cache específico se existir
        if (cache()->has($cacheKey)) {
            cache()->forget($cacheKey);
            echo "   🗑️  Cache limpo\n";
        } else {
            echo "   ℹ️  Sem cache específico\n";
        }
        
    } catch (Exception $e) {
        echo "   ⚠️  Erro ao verificar cache: " . $e->getMessage() . "\n";
    }
    
    // Verificar sessão
    echo "\n🔐 VERIFICAÇÃO DE SESSÃO:\n";
    echo "   ℹ️  Este script não tem acesso à sessão web\n";
    echo "   ℹ️  Verifique se o usuário está logado corretamente em produção\n";
    
    // Recomendações
    echo "\n🔧 RECOMENDAÇÕES:\n";
    
    if (!$superAdmin->isSuperAdminNode()) {
        echo "   ❌ PROBLEMA CRÍTICO: isSuperAdminNode() retorna FALSE\n";
        echo "   🔧 Soluções:\n";
        echo "      1. Verificar se node_type = 'super_admin'\n";
        echo "      2. Verificar se role->name = 'super_admin'\n";
        echo "      3. Limpar cache: php artisan cache:clear\n";
        echo "      4. Verificar se o usuário está logado corretamente\n";
    } else {
        echo "   ✅ Método isSuperAdminNode() funciona corretamente\n";
        echo "   🔧 Se o seletor não aparece, verificar:\n";
        echo "      1. Cache do navegador (Ctrl+F5)\n";
        echo "      2. Cache de views: php artisan view:clear\n";
        echo "      3. Se o usuário está realmente logado\n";
        echo "      4. Se não há erro JavaScript na página\n";
    }
    
    echo "\n📊 ESTATÍSTICAS FINAIS:\n";
    echo "   👑 Super Admin: " . ($superAdmin ? '1' : '0') . "\n";
    echo "   🏢 Operações: " . $otherNodes->where('node_type', 'operacao')->count() . "\n";
    echo "   🏷️  White Labels: " . $otherNodes->where('node_type', 'white_label')->count() . "\n";
    echo "   👤 Licenciados: " . $otherNodes->whereIn('node_type', ['licenciado_l1', 'licenciado_l2', 'licenciado_l3'])->count() . "\n";
    echo "   📊 Total de nós: " . $availableNodes->count() . "\n";
    
} catch (Exception $e) {
    echo "❌ ERRO DURANTE O DEBUG:\n";
    echo "   Mensagem: " . $e->getMessage() . "\n";
    echo "   Arquivo: " . $e->getFile() . "\n";
    echo "   Linha: " . $e->getLine() . "\n";
    echo "   Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n✅ Debug finalizado - " . date('Y-m-d H:i:s') . "\n";
