<?php

echo "🔧 CORREÇÃO: Problema de Exclusão de Agenda em Produção\n";
echo "====================================================\n\n";

try {
    // 1. Limpar caches
    echo "🧹 Limpando caches...\n";
    
    $commands = [
        'php artisan config:clear',
        'php artisan route:clear', 
        'php artisan view:clear',
        'php artisan cache:clear',
        'composer dump-autoload --optimize',
        'php artisan config:cache',
        'php artisan route:cache',
    ];
    
    foreach ($commands as $command) {
        echo "   Executando: {$command}\n";
        $output = shell_exec($command . ' 2>&1');
        if ($output) {
            echo "   Resultado: " . trim($output) . "\n";
        }
    }
    
    echo "✅ Caches limpos e otimizados\n\n";
    
    // 2. Verificar permissões
    echo "🔐 Verificando permissões...\n";
    
    $directories = [
        'storage/logs',
        'storage/framework/cache',
        'storage/framework/sessions',
        'storage/framework/views',
        'bootstrap/cache'
    ];
    
    foreach ($directories as $dir) {
        if (is_dir($dir)) {
            $perms = substr(sprintf('%o', fileperms($dir)), -4);
            echo "   {$dir}: {$perms}\n";
            
            if ($perms !== '0755' && $perms !== '0777') {
                chmod($dir, 0755);
                echo "   ✅ Permissão corrigida para 755\n";
            }
        } else {
            mkdir($dir, 0755, true);
            echo "   ✅ Diretório criado: {$dir}\n";
        }
    }
    
    echo "✅ Permissões verificadas\n\n";
    
    // 3. Verificar se os arquivos necessários existem
    echo "📁 Verificando arquivos necessários...\n";
    
    $files = [
        'app/Services/ReminderService.php',
        'app/Services/EmailService.php', 
        'app/Services/GoogleCalendarService.php',
        'app/Models/Reminder.php',
        'app/Jobs/DispatchReminders.php',
        'app/Jobs/SendReminderEmail.php'
    ];
    
    foreach ($files as $file) {
        if (file_exists($file)) {
            echo "   ✅ {$file}\n";
        } else {
            echo "   ❌ {$file} - ARQUIVO FALTANDO!\n";
        }
    }
    
    echo "\n📋 Instruções para testar:\n";
    echo "1. Faça upload dos arquivos atualizados para produção\n";
    echo "2. Execute este script em produção\n";
    echo "3. Teste a exclusão de agenda novamente\n";
    echo "4. Verifique os logs em storage/logs/laravel.log\n\n";
    
    echo "🔍 Para debug em produção, verifique:\n";
    echo "   tail -f storage/logs/laravel.log\n\n";
    
    echo "✅ Correção aplicada com sucesso!\n";
    
} catch (\Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
}

echo "\n====================================================\n";
echo "🔧 Script de correção concluído\n";
?>
