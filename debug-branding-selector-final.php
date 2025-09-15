<?php

/**
 * Debug final para o seletor de branding
 * Execute este script em produção para diagnosticar o problema
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;

echo "🔍 DEBUG FINAL - SELETOR DE BRANDING\n";
echo "===================================\n\n";

try {
    // 1. Verificar Super Admin
    echo "1. 👑 VERIFICANDO SUPER ADMIN:\n";
    $superAdmin = User::where('email', 'brunodalcum@dspay.com.br')->first();
    
    if (!$superAdmin) {
        echo "   ❌ Super Admin não encontrado!\n";
        
        // Listar todos os usuários
        $allUsers = User::all();
        echo "   📊 Usuários no banco:\n";
        foreach ($allUsers as $user) {
            echo "      - ID {$user->id}: {$user->name} ({$user->email}) - {$user->node_type}\n";
        }
        exit(1);
    }
    
    echo "   ✅ Super Admin encontrado:\n";
    echo "      ID: {$superAdmin->id}\n";
    echo "      Nome: {$superAdmin->name}\n";
    echo "      Email: {$superAdmin->email}\n";
    echo "      Node Type: {$superAdmin->node_type}\n";
    echo "      Is Active: " . ($superAdmin->is_active ? 'SIM' : 'NÃO') . "\n";
    
    // 2. Testar método isSuperAdminNode
    echo "\n2. 🔍 TESTANDO isSuperAdminNode():\n";
    try {
        $isSuperAdmin = $superAdmin->isSuperAdminNode();
        echo "   ✅ Resultado: " . ($isSuperAdmin ? 'TRUE' : 'FALSE') . "\n";
        
        if (!$isSuperAdmin) {
            echo "   ❌ PROBLEMA CRÍTICO: Método retorna FALSE!\n";
            echo "   🔍 Verificando condições:\n";
            
            // Verificar node_type
            echo "      node_type === 'super_admin': " . ($superAdmin->node_type === 'super_admin' ? 'SIM' : 'NÃO') . "\n";
            echo "      node_type atual: '{$superAdmin->node_type}'\n";
            
            // Verificar role
            if ($superAdmin->role) {
                echo "      role->name: {$superAdmin->role->name}\n";
                echo "      role->name === 'super_admin': " . ($superAdmin->role->name === 'super_admin' ? 'SIM' : 'NÃO') . "\n";
            } else {
                echo "      role: NULL (não encontrada)\n";
            }
            
            // Tentar corrigir
            echo "\n   🔧 TENTANDO CORREÇÃO:\n";
            if ($superAdmin->node_type !== 'super_admin') {
                $superAdmin->node_type = 'super_admin';
                $superAdmin->save();
                echo "      ✅ node_type corrigido para 'super_admin'\n";
            }
            
            // Verificar role
            if (!$superAdmin->role || $superAdmin->role->name !== 'super_admin') {
                $superAdminRole = \App\Models\Role::where('name', 'super_admin')->first();
                if ($superAdminRole) {
                    $superAdmin->role_id = $superAdminRole->id;
                    $superAdmin->save();
                    echo "      ✅ role_id corrigido\n";
                } else {
                    echo "      ⚠️  Role 'super_admin' não encontrada\n";
                }
            }
            
            // Testar novamente
            $superAdmin->refresh();
            $isNowSuperAdmin = $superAdmin->isSuperAdminNode();
            echo "      🔍 Teste após correção: " . ($isNowSuperAdmin ? 'TRUE ✅' : 'FALSE ❌') . "\n";
        }
    } catch (Exception $e) {
        echo "   ❌ ERRO ao executar método: " . $e->getMessage() . "\n";
    }
    
    // 3. Simular controller
    echo "\n3. 🎮 SIMULANDO CONTROLLER:\n";
    
    Auth::login($superAdmin);
    $user = Auth::user();
    echo "   ✅ Usuário autenticado: {$user->name}\n";
    echo "   🔍 isSuperAdminNode(): " . ($user->isSuperAdminNode() ? 'TRUE' : 'FALSE') . "\n";
    
    // Simular lógica do controller
    $availableNodes = collect();
    if ($user->isSuperAdminNode()) {
        echo "   ✅ Usuário é Super Admin - criando availableNodes\n";
        
        // Sempre incluir o próprio Super Admin primeiro
        $availableNodes->push($user);
        echo "   ✅ Super Admin adicionado à coleção\n";
        
        // Buscar outros nós ativos
        $otherNodes = User::whereIn('node_type', ['operacao', 'white_label', 'licenciado_l1', 'licenciado_l2', 'licenciado_l3'])
            ->where('is_active', true)
            ->where('id', '!=', $user->id)
            ->orderBy('node_type')
            ->orderBy('name')
            ->get();
        
        echo "   📊 Outros nós encontrados: " . $otherNodes->count() . "\n";
        
        foreach ($otherNodes as $node) {
            echo "      - {$node->name} ({$node->email}) - {$node->node_type}\n";
        }
        
        // Adicionar outros nós à coleção
        $availableNodes = $availableNodes->merge($otherNodes);
        
        echo "   📊 Total availableNodes: " . $availableNodes->count() . "\n";
        
    } else {
        echo "   ❌ Usuário NÃO é Super Admin - availableNodes vazio\n";
    }
    
    // 4. Simular condições da view
    echo "\n4. 🎨 SIMULANDO CONDIÇÕES DA VIEW:\n";
    
    echo "   🔍 Condição \$user->isSuperAdminNode(): " . ($user->isSuperAdminNode() ? 'TRUE' : 'FALSE') . "\n";
    
    if ($user->isSuperAdminNode()) {
        echo "   ✅ SELETOR DEVE APARECER\n";
        
        // Simular lógica PHP da view
        if ($availableNodes->isEmpty()) {
            $availableNodes = collect([$user]);
            echo "   🔧 availableNodes estava vazio - adicionado usuário atual\n";
        }
        
        $superAdminNodes = $availableNodes->where('node_type', 'super_admin');
        $otherNodes = $availableNodes->where('node_type', '!=', 'super_admin')->groupBy('node_type');
        
        if ($superAdminNodes->isEmpty() && $user->isSuperAdminNode()) {
            $superAdminNodes = collect([$user]);
            echo "   🔧 superAdminNodes estava vazio - adicionado usuário atual\n";
        }
        
        echo "   📊 Super Admin nodes: " . $superAdminNodes->count() . "\n";
        echo "   📊 Other nodes groups: " . $otherNodes->count() . "\n";
        
        // Mostrar HTML que seria gerado
        echo "\n   🌐 HTML QUE SERIA GERADO:\n";
        echo "   <select id=\"node-selector\">\n";
        echo "       <option value=\"\">Selecione um nó...</option>\n";
        
        if ($superAdminNodes->count() > 0) {
            echo "       <optgroup label=\"Super Admin\">\n";
            foreach ($superAdminNodes as $node) {
                echo "           <option value=\"{$node->id}\">{$node->name} ({$node->email}) - Super Admin</option>\n";
            }
            echo "       </optgroup>\n";
        }
        
        if ($otherNodes->count() > 0) {
            foreach ($otherNodes as $nodeType => $nodes) {
                $label = ucfirst(str_replace('_', ' ', $nodeType));
                echo "       <optgroup label=\"{$label}\">\n";
                foreach ($nodes as $node) {
                    echo "           <option value=\"{$node->id}\">{$node->name} ({$node->email})</option>\n";
                }
                echo "       </optgroup>\n";
            }
        } else {
            echo "       <optgroup label=\"Informação\">\n";
            echo "           <option disabled>Nenhuma operação ou white label cadastrado ainda</option>\n";
            echo "       </optgroup>\n";
        }
        
        echo "   </select>\n";
        
    } else {
        echo "   ❌ SELETOR NÃO VAI APARECER\n";
        echo "   🔧 PROBLEMA: isSuperAdminNode() retorna FALSE\n";
    }
    
    // 5. Verificar se há problema na view
    echo "\n5. 📄 VERIFICANDO VIEW:\n";
    
    $viewPath = __DIR__ . '/resources/views/hierarchy/branding/index.blade.php';
    if (file_exists($viewPath)) {
        echo "   ✅ View existe: {$viewPath}\n";
        
        $viewContent = file_get_contents($viewPath);
        
        // Verificar se tem a condição correta
        if (strpos($viewContent, '@if($user->isSuperAdminNode())') !== false) {
            echo "   ✅ Condição encontrada: @if(\$user->isSuperAdminNode())\n";
        } else {
            echo "   ❌ Condição NÃO encontrada!\n";
        }
        
        // Verificar se tem o seletor
        if (strpos($viewContent, 'id="node-selector"') !== false) {
            echo "   ✅ Seletor encontrado no HTML\n";
        } else {
            echo "   ❌ Seletor NÃO encontrado no HTML!\n";
        }
        
    } else {
        echo "   ❌ View não existe!\n";
    }
    
    // 6. Resumo final
    echo "\n6. 🎯 RESUMO FINAL:\n";
    
    $issues = [];
    
    if (!$user->isSuperAdminNode()) {
        $issues[] = "isSuperAdminNode() retorna FALSE";
    }
    
    if ($availableNodes->isEmpty()) {
        $issues[] = "availableNodes está vazio";
    }
    
    if (empty($issues)) {
        echo "   ✅ TUDO ESTÁ CORRETO!\n";
        echo "   🎯 O seletor DEVE aparecer na página\n";
        echo "   🔍 Se não está aparecendo, pode ser:\n";
        echo "      - Cache do navegador\n";
        echo "      - JavaScript com erro\n";
        echo "      - CSS ocultando o elemento\n";
    } else {
        echo "   ❌ PROBLEMAS ENCONTRADOS:\n";
        foreach ($issues as $issue) {
            echo "      - {$issue}\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ ERRO DURANTE DEBUG:\n";
    echo "   Mensagem: " . $e->getMessage() . "\n";
    echo "   Arquivo: " . $e->getFile() . "\n";
    echo "   Linha: " . $e->getLine() . "\n";
}

echo "\n✅ Debug concluído!\n";
