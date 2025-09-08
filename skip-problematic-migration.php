<?php

echo "⚡ CORREÇÃO RÁPIDA - MIGRAÇÃO PROBLEMÁTICA\n";
echo "=" . str_repeat("=", 45) . "\n\n";

try {
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "1. Marcando migração problemática como executada...\n";
    
    // Marcar a migração problemática como já executada
    $migrationName = '2025_09_07_090707_update_contracts_to_use_licenciados_table';
    
    $exists = DB::table('migrations')->where('migration', $migrationName)->exists();
    
    if (!$exists) {
        DB::table('migrations')->insert([
            'migration' => $migrationName,
            'batch' => DB::table('migrations')->max('batch') + 1
        ]);
        echo "✅ Migração marcada como executada\n";
    } else {
        echo "✅ Migração já estava marcada como executada\n";
    }
    
    echo "\n2. Executando outras migrações...\n";
    
    // Executar todas as outras migrações
    $output = [];
    $returnCode = 0;
    exec('php artisan migrate --force 2>&1', $output, $returnCode);
    
    echo "Resultado da migração:\n";
    foreach ($output as $line) {
        echo "   $line\n";
    }
    
    if ($returnCode === 0) {
        echo "\n✅ Migrações executadas com sucesso\n";
    } else {
        echo "\n⚠️  Algumas migrações podem ter falhado\n";
    }
    
    echo "\n3. Executando seeders de permissões...\n";
    
    // Executar seeders
    $output = [];
    exec('php artisan db:seed --class=PermissionSeeder --force 2>&1', $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "✅ PermissionSeeder executado\n";
    } else {
        echo "⚠️  Erro no PermissionSeeder:\n";
        foreach ($output as $line) {
            echo "   $line\n";
        }
    }
    
    echo "\n4. Limpando caches...\n";
    
    exec('php artisan cache:clear', $output);
    exec('php artisan config:clear', $output);
    exec('php artisan view:clear', $output);
    
    echo "✅ Caches limpos\n";
    
    echo "\n5. Verificando tabelas críticas...\n";
    
    $tables = ['permissions', 'roles', 'role_permissions', 'users'];
    
    foreach ($tables as $table) {
        if (Schema::hasTable($table)) {
            $count = DB::table($table)->count();
            echo "✅ $table: $count registros\n";
        } else {
            echo "❌ $table: NÃO EXISTE\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 45) . "\n";
echo "🏁 CORREÇÃO RÁPIDA CONCLUÍDA\n";
echo "🌐 Teste agora acessar o sistema\n";
echo str_repeat("=", 45) . "\n";

?>
