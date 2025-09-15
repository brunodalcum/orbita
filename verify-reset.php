<?php

/**
 * SCRIPT DE VERIFICAÇÃO PÓS-RESET
 * 
 * Verifica se o reset foi executado corretamente
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 VERIFICANDO RESET DO BANCO DE DADOS...\n\n";

try {
    $errors = [];
    $warnings = [];
    $success = [];
    
    // ========================================
    // VERIFICAR SUPER ADMIN
    // ========================================
    
    echo "👑 1. Verificando Super Admin...\n";
    
    $superAdmin = DB::table('users')
        ->where('email', 'brunodalcum@dspay.com.br')
        ->first();
    
    if ($superAdmin) {
        $success[] = "Super Admin encontrado (ID: {$superAdmin->id})";
        echo "   ✅ Super Admin existe\n";
        echo "   📧 Email: {$superAdmin->email}\n";
        echo "   👤 Nome: {$superAdmin->name}\n";
        echo "   🏷️  Tipo: {$superAdmin->node_type}\n";
        echo "   📊 Nível: {$superAdmin->hierarchy_level}\n";
        
        if ($superAdmin->node_type !== 'super_admin') {
            $warnings[] = "Tipo do Super Admin não é 'super_admin'";
        }
        
        if ($superAdmin->hierarchy_level != 0) {
            $warnings[] = "Nível hierárquico do Super Admin não é 0";
        }
        
    } else {
        $errors[] = "Super Admin não encontrado!";
        echo "   ❌ Super Admin NÃO encontrado\n";
    }
    
    echo "\n";
    
    // ========================================
    // VERIFICAR LIMPEZA DAS TABELAS
    // ========================================
    
    echo "🗑️  2. Verificando limpeza das tabelas...\n";
    
    $tablesToCheck = [
        'orbita_operacaos' => 'Operações',
        'white_labels' => 'White Labels',
        'licenciados' => 'Licenciados',
        'leads' => 'Leads',
        'contracts' => 'Contratos',
        'agenda' => 'Agenda',
        'estabelecimentos' => 'Estabelecimentos',
        'campanhas' => 'Campanhas'
    ];
    
    foreach ($tablesToCheck as $table => $description) {
        try {
            $count = DB::table($table)->count();
            if ($count === 0) {
                $success[] = "$description limpos";
                echo "   ✅ $description: 0 registros\n";
            } else {
                $warnings[] = "$description ainda tem $count registros";
                echo "   ⚠️  $description: $count registros\n";
            }
        } catch (Exception $e) {
            $warnings[] = "Erro ao verificar $description: " . $e->getMessage();
            echo "   ⚠️  $description: Erro na verificação\n";
        }
    }
    
    echo "\n";
    
    // ========================================
    // VERIFICAR USUÁRIOS
    // ========================================
    
    echo "👥 3. Verificando usuários...\n";
    
    $totalUsers = DB::table('users')->count();
    $activeUsers = DB::table('users')->where('is_active', true)->count();
    
    echo "   📊 Total de usuários: $totalUsers\n";
    echo "   ✅ Usuários ativos: $activeUsers\n";
    
    if ($totalUsers === 1) {
        $success[] = "Apenas 1 usuário no sistema (Super Admin)";
        echo "   ✅ Apenas Super Admin no sistema\n";
    } else {
        $warnings[] = "Existem $totalUsers usuários no sistema";
        echo "   ⚠️  Existem outros usuários além do Super Admin\n";
        
        // Listar outros usuários
        $otherUsers = DB::table('users')
            ->where('email', '!=', 'brunodalcum@dspay.com.br')
            ->select('id', 'name', 'email', 'node_type')
            ->get();
        
        foreach ($otherUsers as $user) {
            echo "      - ID {$user->id}: {$user->name} ({$user->email}) - {$user->node_type}\n";
        }
    }
    
    echo "\n";
    
    // ========================================
    // VERIFICAR ROLES E PERMISSÕES
    // ========================================
    
    echo "🛡️  4. Verificando roles e permissões...\n";
    
    $rolesCount = DB::table('roles')->count();
    $permissionsCount = DB::table('permissions')->count();
    
    echo "   📊 Roles: $rolesCount\n";
    echo "   📊 Permissões: $permissionsCount\n";
    
    $expectedRoles = ['super_admin', 'admin', 'operacao', 'white_label', 'licenciado'];
    $existingRoles = DB::table('roles')->pluck('name')->toArray();
    
    foreach ($expectedRoles as $role) {
        if (in_array($role, $existingRoles)) {
            echo "   ✅ Role '$role' existe\n";
        } else {
            $warnings[] = "Role '$role' não encontrada";
            echo "   ⚠️  Role '$role' não encontrada\n";
        }
    }
    
    echo "\n";
    
    // ========================================
    // VERIFICAR BRANDING
    // ========================================
    
    echo "🎨 5. Verificando branding...\n";
    
    $brandingCount = DB::table('node_branding')->count();
    echo "   📊 Registros de branding: $brandingCount\n";
    
    if ($brandingCount === 0) {
        $success[] = "Branding limpo - pronto para configuração";
        echo "   ✅ Branding limpo\n";
    } else {
        $warnings[] = "Ainda existem $brandingCount registros de branding";
        echo "   ⚠️  Ainda existem registros de branding\n";
    }
    
    echo "\n";
    
    // ========================================
    // VERIFICAR ARQUIVOS
    // ========================================
    
    echo "🗂️  6. Verificando arquivos de upload...\n";
    
    $uploadDirs = [
        'storage/app/public/branding' => 'Branding',
        'storage/app/public/contracts' => 'Contratos',
        'storage/app/public/uploads' => 'Uploads',
        'storage/app/public/documents' => 'Documentos'
    ];
    
    foreach ($uploadDirs as $dir => $description) {
        if (is_dir($dir)) {
            $files = glob($dir . '/*');
            $fileCount = count($files);
            
            if ($fileCount === 0) {
                $success[] = "$description limpos";
                echo "   ✅ $description: 0 arquivos\n";
            } else {
                $warnings[] = "$description ainda tem $fileCount arquivos";
                echo "   ⚠️  $description: $fileCount arquivos\n";
            }
        } else {
            echo "   ℹ️  $description: Diretório não existe\n";
        }
    }
    
    echo "\n";
    
    // ========================================
    // RELATÓRIO FINAL
    // ========================================
    
    echo "📋 RELATÓRIO FINAL:\n\n";
    
    if (count($success) > 0) {
        echo "✅ SUCESSOS (" . count($success) . "):\n";
        foreach ($success as $item) {
            echo "   ✅ $item\n";
        }
        echo "\n";
    }
    
    if (count($warnings) > 0) {
        echo "⚠️  AVISOS (" . count($warnings) . "):\n";
        foreach ($warnings as $item) {
            echo "   ⚠️  $item\n";
        }
        echo "\n";
    }
    
    if (count($errors) > 0) {
        echo "❌ ERROS (" . count($errors) . "):\n";
        foreach ($errors as $item) {
            echo "   ❌ $item\n";
        }
        echo "\n";
    }
    
    // ========================================
    // CONCLUSÃO
    // ========================================
    
    if (count($errors) === 0) {
        echo "🎉 RESET VERIFICADO COM SUCESSO!\n\n";
        echo "🚀 PRÓXIMOS PASSOS:\n";
        echo "   1. Acesse: http://seu-dominio.com/login\n";
        echo "   2. Login: brunodalcum@dspay.com.br\n";
        echo "   3. Senha: 123456789\n";
        echo "   4. ⚠️  ALTERE A SENHA IMEDIATAMENTE!\n";
        echo "   5. Configure o branding da Órbita\n";
        echo "   6. Comece a cadastrar operações\n\n";
        
        if (count($warnings) > 0) {
            echo "⚠️  Existem alguns avisos, mas o sistema está funcional.\n";
        }
        
    } else {
        echo "❌ RESET INCOMPLETO!\n";
        echo "   Existem erros que precisam ser corrigidos.\n";
        echo "   Verifique os erros acima e execute o reset novamente se necessário.\n\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERRO DURANTE A VERIFICAÇÃO:\n";
    echo "   Mensagem: " . $e->getMessage() . "\n";
    echo "   Arquivo: " . $e->getFile() . "\n";
    echo "   Linha: " . $e->getLine() . "\n\n";
}

echo "✅ Verificação finalizada!\n";
