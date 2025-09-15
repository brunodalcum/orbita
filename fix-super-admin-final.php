<?php

/**
 * Script para corrigir definitivamente o Super Admin
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Role;

echo "🔧 CORREÇÃO DEFINITIVA DO SUPER ADMIN\n";
echo "====================================\n\n";

try {
    // 1. Encontrar ou criar role super_admin
    echo "1. 🔍 VERIFICANDO ROLE SUPER ADMIN:\n";
    
    $superAdminRole = Role::where('name', 'super_admin')->first();
    
    if (!$superAdminRole) {
        echo "   ❌ Role 'super_admin' não encontrada, criando...\n";
        
        $superAdminRole = Role::create([
            'name' => 'super_admin',
            'display_name' => 'Super Administrador',
            'description' => 'Acesso total ao sistema'
        ]);
        
        echo "   ✅ Role 'super_admin' criada com ID: {$superAdminRole->id}\n";
    } else {
        echo "   ✅ Role 'super_admin' encontrada com ID: {$superAdminRole->id}\n";
    }
    
    // 2. Encontrar usuário Super Admin
    echo "\n2. 👑 VERIFICANDO USUÁRIO SUPER ADMIN:\n";
    
    $superAdmin = User::where('email', 'brunodalcum@dspay.com.br')->first();
    
    if (!$superAdmin) {
        echo "   ❌ Usuário Super Admin não encontrado!\n";
        exit(1);
    }
    
    echo "   ✅ Usuário encontrado:\n";
    echo "      ID: {$superAdmin->id}\n";
    echo "      Nome: {$superAdmin->name}\n";
    echo "      Email: {$superAdmin->email}\n";
    echo "      Node Type: {$superAdmin->node_type}\n";
    echo "      Role ID: {$superAdmin->role_id}\n";
    echo "      Is Active: " . ($superAdmin->is_active ? 'SIM' : 'NÃO') . "\n";
    
    // 3. Corrigir dados do Super Admin
    echo "\n3. 🔧 CORRIGINDO DADOS:\n";
    
    $updated = false;
    
    // Corrigir node_type
    if ($superAdmin->node_type !== 'super_admin') {
        echo "   🔧 Corrigindo node_type: '{$superAdmin->node_type}' → 'super_admin'\n";
        $superAdmin->node_type = 'super_admin';
        $updated = true;
    } else {
        echo "   ✅ node_type já está correto: 'super_admin'\n";
    }
    
    // Corrigir role_id
    if ($superAdmin->role_id !== $superAdminRole->id) {
        echo "   🔧 Corrigindo role_id: '{$superAdmin->role_id}' → '{$superAdminRole->id}'\n";
        $superAdmin->role_id = $superAdminRole->id;
        $updated = true;
    } else {
        echo "   ✅ role_id já está correto: {$superAdminRole->id}\n";
    }
    
    // Garantir que está ativo
    if (!$superAdmin->is_active) {
        echo "   🔧 Ativando usuário\n";
        $superAdmin->is_active = true;
        $updated = true;
    } else {
        echo "   ✅ Usuário já está ativo\n";
    }
    
    // Salvar se houve mudanças
    if ($updated) {
        $superAdmin->save();
        echo "   ✅ Dados salvos com sucesso!\n";
    } else {
        echo "   ✅ Nenhuma correção necessária\n";
    }
    
    // 4. Testar método isSuperAdminNode
    echo "\n4. 🧪 TESTANDO MÉTODO isSuperAdminNode():\n";
    
    $superAdmin->refresh(); // Recarregar do banco
    
    try {
        $isSuperAdmin = $superAdmin->isSuperAdminNode();
        echo "   ✅ Método executado com sucesso\n";
        echo "   📊 Resultado: " . ($isSuperAdmin ? 'TRUE' : 'FALSE') . "\n";
        
        if ($isSuperAdmin) {
            echo "   ✅ PERFEITO! Método funciona corretamente\n";
        } else {
            echo "   ❌ PROBLEMA: Método ainda retorna FALSE\n";
            
            // Debug do método
            echo "   🔍 Debugando método:\n";
            echo "      node_type == 'super_admin': " . ($superAdmin->node_type === 'super_admin' ? 'SIM' : 'NÃO') . "\n";
            
            if ($superAdmin->role) {
                echo "      role->name: {$superAdmin->role->name}\n";
                echo "      role->name == 'super_admin': " . ($superAdmin->role->name === 'super_admin' ? 'SIM' : 'NÃO') . "\n";
            } else {
                echo "      role: NULL\n";
            }
        }
        
    } catch (Exception $e) {
        echo "   ❌ ERRO ao executar método: " . $e->getMessage() . "\n";
    }
    
    // 5. Verificar outros nós para o seletor
    echo "\n5. 📊 VERIFICANDO OUTROS NÓS:\n";
    
    $otherNodes = User::whereIn('node_type', ['operacao', 'white_label', 'licenciado_l1', 'licenciado_l2', 'licenciado_l3'])
        ->where('is_active', true)
        ->where('id', '!=', $superAdmin->id)
        ->orderBy('node_type')
        ->orderBy('name')
        ->get();
    
    echo "   📊 Total de outros nós: " . $otherNodes->count() . "\n";
    
    if ($otherNodes->count() > 0) {
        foreach ($otherNodes as $node) {
            echo "      - {$node->name} ({$node->email}) - {$node->node_type}\n";
        }
    } else {
        echo "      (Nenhum outro nó encontrado)\n";
    }
    
    // 6. Simular availableNodes
    echo "\n6. 🎯 SIMULANDO availableNodes:\n";
    
    $availableNodes = collect();
    $availableNodes->push($superAdmin);
    $availableNodes = $availableNodes->merge($otherNodes);
    
    echo "   📊 Total availableNodes: " . $availableNodes->count() . "\n";
    
    foreach ($availableNodes as $node) {
        echo "      - {$node->name} ({$node->node_type})\n";
    }
    
    // 7. Resumo final
    echo "\n7. 🎯 RESUMO FINAL:\n";
    
    if ($superAdmin->isSuperAdminNode()) {
        echo "   ✅ TUDO CORRETO!\n";
        echo "   👑 Super Admin configurado corretamente\n";
        echo "   📊 availableNodes: " . $availableNodes->count() . " nós\n";
        echo "   🎯 Seletor DEVE aparecer na página\n";
        
        echo "\n   🌐 TESTE A URL AGORA:\n";
        echo "      https://srv971263.hstgr.cloud/hierarchy/branding\n";
        echo "      O seletor deve aparecer com debug vermelho/verde\n";
        
    } else {
        echo "   ❌ AINDA HÁ PROBLEMAS!\n";
        echo "   🔧 Método isSuperAdminNode() não funciona\n";
        echo "   📞 Contate o desenvolvedor para verificar o método\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERRO DURANTE CORREÇÃO:\n";
    echo "   Mensagem: " . $e->getMessage() . "\n";
    echo "   Arquivo: " . $e->getFile() . "\n";
    echo "   Linha: " . $e->getLine() . "\n";
}

echo "\n✅ Correção concluída!\n";
