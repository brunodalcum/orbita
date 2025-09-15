<?php

/**
 * SCRIPT DE BACKUP ANTES DO RESET
 * 
 * Cria um backup completo do banco de dados antes de executar o reset
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "💾 CRIANDO BACKUP DO BANCO DE DADOS...\n\n";

try {
    // Obter configurações do banco
    $host = config('database.connections.mysql.host');
    $database = config('database.connections.mysql.database');
    $username = config('database.connections.mysql.username');
    $password = config('database.connections.mysql.password');
    $port = config('database.connections.mysql.port', 3306);
    
    // Nome do arquivo de backup
    $backupFile = 'backup_before_reset_' . date('Y-m-d_H-i-s') . '.sql';
    $backupPath = storage_path('backups');
    
    // Criar diretório de backup se não existir
    if (!is_dir($backupPath)) {
        mkdir($backupPath, 0755, true);
    }
    
    $fullBackupPath = $backupPath . '/' . $backupFile;
    
    // Comando mysqldump
    $command = sprintf(
        'mysqldump -h%s -P%s -u%s -p%s %s > %s',
        escapeshellarg($host),
        escapeshellarg($port),
        escapeshellarg($username),
        escapeshellarg($password),
        escapeshellarg($database),
        escapeshellarg($fullBackupPath)
    );
    
    echo "🔄 Executando backup...\n";
    echo "   Host: $host\n";
    echo "   Database: $database\n";
    echo "   Arquivo: $backupFile\n\n";
    
    // Executar backup
    $output = [];
    $returnCode = 0;
    exec($command, $output, $returnCode);
    
    if ($returnCode === 0 && file_exists($fullBackupPath)) {
        $fileSize = round(filesize($fullBackupPath) / 1024 / 1024, 2);
        echo "✅ BACKUP CRIADO COM SUCESSO!\n";
        echo "   📁 Arquivo: $fullBackupPath\n";
        echo "   📊 Tamanho: {$fileSize} MB\n\n";
        
        echo "🔐 INFORMAÇÕES IMPORTANTES:\n";
        echo "   ⚠️  Guarde este backup em local seguro\n";
        echo "   ⚠️  Para restaurar: mysql -u$username -p$password $database < $fullBackupPath\n";
        echo "   ⚠️  Backup válido até: " . date('Y-m-d H:i:s', strtotime('+30 days')) . "\n\n";
        
        echo "🚀 Agora você pode executar o reset com segurança:\n";
        echo "   php reset-database-production.php RESET_PRODUCTION_DATABASE_CONFIRMED_2025\n\n";
        
    } else {
        echo "❌ ERRO AO CRIAR BACKUP!\n";
        echo "   Código de retorno: $returnCode\n";
        echo "   Saída: " . implode("\n", $output) . "\n\n";
        echo "⚠️  NÃO execute o reset sem um backup válido!\n";
        exit(1);
    }
    
} catch (Exception $e) {
    echo "❌ ERRO DURANTE O BACKUP:\n";
    echo "   Mensagem: " . $e->getMessage() . "\n";
    echo "   Arquivo: " . $e->getFile() . "\n";
    echo "   Linha: " . $e->getLine() . "\n\n";
    echo "⚠️  NÃO execute o reset sem um backup válido!\n";
    exit(1);
}

echo "✅ Script de backup finalizado!\n";
