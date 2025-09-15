<?php

/**
 * SCRIPT DE RESET DO BANCO DE DADOS - PRODUÇÃO
 * 
 * ⚠️  ATENÇÃO: ESTE SCRIPT APAGA TODOS OS DADOS DO BANCO!
 * 
 * Mantém apenas:
 * - Super Admin: brunodalcum@dspay.com.br
 * - Estrutura das tabelas
 * - Dados essenciais do sistema
 */

// ========================================
// CONFIGURAÇÕES DE SEGURANÇA
// ========================================

// Verificação de ambiente (descomente para produção)
// if (env('APP_ENV') !== 'production') {
//     die("❌ ERRO: Este script só pode ser executado em produção!\n");
// }

// Verificação de confirmação obrigatória
$confirmationKey = 'RESET_PRODUCTION_DATABASE_CONFIRMED_2025';
$providedKey = $argv[1] ?? '';

if ($providedKey !== $confirmationKey) {
    echo "🚨 SCRIPT DE RESET DO BANCO DE DADOS - PRODUÇÃO 🚨\n";
    echo "⚠️  ATENÇÃO: Este script irá APAGAR TODOS OS DADOS!\n\n";
    echo "📋 O que será mantido:\n";
    echo "   ✅ Super Admin: brunodalcum@dspay.com.br\n";
    echo "   ✅ Estrutura das tabelas\n";
    echo "   ✅ Roles e permissões básicas\n\n";
    echo "📋 O que será APAGADO:\n";
    echo "   ❌ Todos os usuários (exceto Super Admin)\n";
    echo "   ❌ Todas as operações\n";
    echo "   ❌ Todos os white labels\n";
    echo "   ❌ Todos os licenciados\n";
    echo "   ❌ Todos os leads\n";
    echo "   ❌ Todos os contratos\n";
    echo "   ❌ Toda a agenda\n";
    echo "   ❌ Todos os estabelecimentos\n";
    echo "   ❌ Todos os dados de branding personalizados\n";
    echo "   ❌ TODOS OS OUTROS DADOS\n\n";
    echo "🔐 Para executar, use:\n";
    echo "   php reset-database-production.php $confirmationKey\n\n";
    echo "⚠️  CERTIFIQUE-SE DE TER UM BACKUP ANTES DE EXECUTAR!\n";
    exit(1);
}

// Segunda confirmação interativa
echo "🚨 CONFIRMAÇÃO FINAL 🚨\n";
echo "Você está prestes a APAGAR TODOS OS DADOS do banco de produção!\n";
echo "Digite 'SIM APAGAR TUDO' para confirmar: ";
$handle = fopen("php://stdin", "r");
$confirmation = trim(fgets($handle));
fclose($handle);

if ($confirmation !== 'SIM APAGAR TUDO') {
    echo "❌ Operação cancelada pelo usuário.\n";
    exit(1);
}

// ========================================
// INICIALIZAÇÃO DO LARAVEL
// ========================================

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

echo "\n🚀 INICIANDO RESET DO BANCO DE DADOS...\n\n";

try {
    // ========================================
    // BACKUP DO SUPER ADMIN
    // ========================================
    
    echo "📋 1. Fazendo backup do Super Admin...\n";
    
    $superAdmin = DB::table('users')
        ->where('email', 'brunodalcum@dspay.com.br')
        ->first();
    
    if (!$superAdmin) {
        echo "❌ ERRO: Super Admin não encontrado!\n";
        echo "   Criando Super Admin...\n";
        
        // Buscar ou criar role de Super Admin
        $superAdminRole = DB::table('roles')->where('name', 'super_admin')->first();
        if (!$superAdminRole) {
            $roleId = DB::table('roles')->insertGetId([
                'name' => 'super_admin',
                'display_name' => 'Super Administrador',
                'description' => 'Acesso total ao sistema',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $roleId = $superAdminRole->id;
        }
        
        $superAdminData = [
            'name' => 'Bruno Dalcum',
            'email' => 'brunodalcum@dspay.com.br',
            'email_verified_at' => now(),
            'password' => Hash::make('123456789'), // Senha padrão
            'role_id' => $roleId,
            'node_type' => 'super_admin',
            'is_active' => true,
            'hierarchy_level' => 0,
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now()
        ];
    } else {
        $superAdminData = [
            'name' => $superAdmin->name,
            'email' => $superAdmin->email,
            'email_verified_at' => $superAdmin->email_verified_at,
            'password' => $superAdmin->password,
            'role_id' => $superAdmin->role_id,
            'node_type' => 'super_admin',
            'is_active' => true,
            'hierarchy_level' => 0,
            'parent_id' => null,
            'created_at' => $superAdmin->created_at,
            'updated_at' => now()
        ];
    }
    
    echo "✅ Backup do Super Admin realizado.\n\n";
    
    // ========================================
    // DESABILITAR VERIFICAÇÕES DE CHAVE ESTRANGEIRA
    // ========================================
    
    echo "🔧 2. Desabilitando verificações de chave estrangeira...\n";
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    echo "✅ Verificações desabilitadas.\n\n";
    
    // ========================================
    // LIMPEZA DAS TABELAS
    // ========================================
    
    echo "🗑️  3. Limpando tabelas do banco de dados...\n";
    
    $tablesToClean = [
        // Tabelas de dados de usuários e hierarquia
        'users',
        'orbita_operacaos',
        'white_labels',
        'licenciados',
        
        // Tabelas de negócio
        'leads',
        'lead_follow_ups',
        'contracts',
        'contract_documents',
        'contract_audit_logs',
        'agenda',
        'agenda_confirmacaos',
        'agenda_notifications',
        'estabelecimentos',
        'campanhas',
        'email_modelos',
        
        // Tabelas de branding e configurações
        'node_branding',
        'node_domains',
        'node_permissions',
        
        // Tabelas de auditoria e logs
        'audit_logs',
        'hierarchy_notifications',
        'reminders',
        'user_reminder_settings',
        
        // Tabelas de extração e places
        'places',
        'place_extractions',
        
        // Tabelas de sessões e jobs
        'sessions',
        'jobs',
        'job_batches',
        'failed_jobs',
        
        // Tabelas de cache
        'cache',
        'cache_locks'
    ];
    
    foreach ($tablesToClean as $table) {
        try {
            $count = DB::table($table)->count();
            DB::table($table)->truncate();
            echo "   ✅ $table ($count registros removidos)\n";
        } catch (Exception $e) {
            echo "   ⚠️  $table (tabela não existe ou erro: " . $e->getMessage() . ")\n";
        }
    }
    
    echo "\n✅ Limpeza das tabelas concluída.\n\n";
    
    // ========================================
    // RECRIAR SUPER ADMIN
    // ========================================
    
    echo "👑 4. Recriando Super Admin...\n";
    
    $superAdminId = DB::table('users')->insertGetId($superAdminData);
    
    echo "✅ Super Admin recriado com ID: $superAdminId\n";
    echo "   📧 Email: brunodalcum@dspay.com.br\n";
    echo "   🔑 Senha: 123456789\n\n";
    
    // ========================================
    // RECRIAR ROLES E PERMISSÕES BÁSICAS
    // ========================================
    
    echo "🛡️  5. Recriando roles e permissões básicas...\n";
    
    $roles = [
        [
            'name' => 'super_admin',
            'display_name' => 'Super Administrador',
            'description' => 'Acesso total ao sistema'
        ],
        [
            'name' => 'admin',
            'display_name' => 'Administrador',
            'description' => 'Administrador geral'
        ],
        [
            'name' => 'operacao',
            'display_name' => 'Operação',
            'description' => 'Usuário de operação'
        ],
        [
            'name' => 'white_label',
            'display_name' => 'White Label',
            'description' => 'Usuário white label'
        ],
        [
            'name' => 'licenciado',
            'display_name' => 'Licenciado',
            'description' => 'Usuário licenciado'
        ]
    ];
    
    foreach ($roles as $role) {
        try {
            DB::table('roles')->insert([
                'name' => $role['name'],
                'display_name' => $role['display_name'],
                'description' => $role['description'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "   ✅ Role: {$role['name']}\n";
        } catch (Exception $e) {
            echo "   ⚠️  Role {$role['name']} já existe ou erro\n";
        }
    }
    
    // Permissões básicas
    $permissions = [
        'users.view', 'users.create', 'users.update', 'users.delete',
        'hierarchy.view', 'hierarchy.manage', 'hierarchy.create',
        'contracts.view', 'contracts.create', 'contracts.manage',
        'leads.view', 'leads.create', 'leads.manage',
        'agenda.view', 'agenda.create', 'agenda.manage',
        'branding.view', 'branding.manage'
    ];
    
    foreach ($permissions as $permission) {
        try {
            DB::table('permissions')->insert([
                'name' => $permission,
                'display_name' => ucfirst(str_replace('.', ' ', $permission)),
                'description' => 'Permissão para ' . $permission,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "   ✅ Permissão: $permission\n";
        } catch (Exception $e) {
            echo "   ⚠️  Permissão $permission já existe ou erro\n";
        }
    }
    
    echo "\n✅ Roles e permissões recriadas.\n\n";
    
    // ========================================
    // REABILITAR VERIFICAÇÕES DE CHAVE ESTRANGEIRA
    // ========================================
    
    echo "🔧 6. Reabilitando verificações de chave estrangeira...\n";
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    echo "✅ Verificações reabilitadas.\n\n";
    
    // ========================================
    // LIMPEZA DE ARQUIVOS
    // ========================================
    
    echo "🗂️  7. Limpando arquivos de upload...\n";
    
    $uploadDirs = [
        'storage/app/public/branding',
        'storage/app/public/contracts',
        'storage/app/public/uploads',
        'storage/app/public/documents'
    ];
    
    foreach ($uploadDirs as $dir) {
        if (is_dir($dir)) {
            $files = glob($dir . '/*');
            foreach ($files as $file) {
                if (is_file($file) && !str_contains($file, 'orbita')) {
                    unlink($file);
                }
            }
            echo "   ✅ $dir limpo\n";
        }
    }
    
    echo "\n✅ Arquivos de upload limpos.\n\n";
    
    // ========================================
    // FINALIZAÇÃO
    // ========================================
    
    echo "🎉 RESET DO BANCO DE DADOS CONCLUÍDO COM SUCESSO!\n\n";
    echo "📋 RESUMO:\n";
    echo "   ✅ Todas as tabelas foram limpas\n";
    echo "   ✅ Super Admin recriado: brunodalcum@dspay.com.br\n";
    echo "   ✅ Senha padrão: 123456789\n";
    echo "   ✅ Roles e permissões básicas recriadas\n";
    echo "   ✅ Arquivos de upload limpos\n\n";
    echo "🚀 PRÓXIMOS PASSOS:\n";
    echo "   1. Faça login com: brunodalcum@dspay.com.br / 123456789\n";
    echo "   2. Altere a senha do Super Admin\n";
    echo "   3. Configure o branding da Órbita\n";
    echo "   4. Comece a cadastrar operações e white labels\n\n";
    echo "⚠️  LEMBRE-SE: Altere a senha padrão imediatamente!\n";
    
} catch (Exception $e) {
    echo "❌ ERRO DURANTE O RESET:\n";
    echo "   Mensagem: " . $e->getMessage() . "\n";
    echo "   Arquivo: " . $e->getFile() . "\n";
    echo "   Linha: " . $e->getLine() . "\n\n";
    echo "🔧 Reabilitando verificações de chave estrangeira...\n";
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    echo "❌ Reset interrompido. Verifique o erro e tente novamente.\n";
    exit(1);
}

echo "✅ Script finalizado com sucesso!\n";
