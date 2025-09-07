<?php

/**
 * Script de Limpeza Final do Projeto Orbita
 * Remove arquivos desnecessários, de teste e temporários
 */

echo "🧹 INICIANDO LIMPEZA FINAL DO PROJETO\n";
echo "=====================================\n\n";

$removidos = [];
$erros = [];

// 1. Seeders de teste
$seedersTest = [
    'database/seeders/AdquirenteTestSeeder.php',
    'database/seeders/AgendaTestSeeder.php', 
    'database/seeders/OperacaoTestSeeder.php',
    'database/seeders/PlanoTestSeeder.php'
];

echo "📂 REMOVENDO SEEDERS DE TESTE:\n";
foreach ($seedersTest as $seeder) {
    if (file_exists($seeder)) {
        if (unlink($seeder)) {
            echo "✅ Removido: $seeder\n";
            $removidos[] = $seeder;
        } else {
            echo "❌ Erro ao remover: $seeder\n";
            $erros[] = $seeder;
        }
    } else {
        echo "⚠️  Não encontrado: $seeder\n";
    }
}

// 2. Commands de teste
$commandsTest = [
    'app/Console/Commands/TestAgenda.php',
    'app/Console/Commands/TestAgendaSimple.php',
    'app/Console/Commands/TestEmail.php', 
    'app/Console/Commands/TestEmailCommand.php',
    'app/Console/Commands/TestGoogleCalendar.php'
];

echo "\n📂 REMOVENDO COMMANDS DE TESTE:\n";
foreach ($commandsTest as $command) {
    if (file_exists($command)) {
        if (unlink($command)) {
            echo "✅ Removido: $command\n";
            $removidos[] = $command;
        } else {
            echo "❌ Erro ao remover: $command\n";
            $erros[] = $command;
        }
    } else {
        echo "⚠️  Não encontrado: $command\n";
    }
}

// 3. Limpar views compiladas antigas
echo "\n🗂️  LIMPANDO VIEWS COMPILADAS:\n";
$viewsDir = 'storage/framework/views/';
if (is_dir($viewsDir)) {
    $viewFiles = glob($viewsDir . '*.php');
    $viewsRemovidas = 0;
    foreach ($viewFiles as $viewFile) {
        if (filemtime($viewFile) < strtotime('-1 hour')) {
            if (unlink($viewFile)) {
                $viewsRemovidas++;
            }
        }
    }
    echo "✅ Removidas $viewsRemovidas views compiladas antigas\n";
}

// 4. Limpar sessões antigas
echo "\n🔐 LIMPANDO SESSÕES ANTIGAS:\n";
$sessionsDir = 'storage/framework/sessions/';
if (is_dir($sessionsDir)) {
    $sessionFiles = glob($sessionsDir . 'laravel_session*');
    $sessoesRemovidas = 0;
    foreach ($sessionFiles as $sessionFile) {
        if (filemtime($sessionFile) < strtotime('-1 day')) {
            if (unlink($sessionFile)) {
                $sessoesRemovidas++;
            }
        }
    }
    echo "✅ Removidas $sessoesRemovidas sessões antigas\n";
}

// 5. Executar limpeza do Laravel
echo "\n🚀 EXECUTANDO LIMPEZA DO LARAVEL:\n";
$commands = [
    'php artisan view:clear',
    'php artisan cache:clear', 
    'php artisan config:clear',
    'php artisan route:clear'
];

foreach ($commands as $cmd) {
    echo "⚡ Executando: $cmd\n";
    $output = [];
    $return = 0;
    exec($cmd . ' 2>&1', $output, $return);
    if ($return === 0) {
        echo "✅ Sucesso\n";
    } else {
        echo "❌ Erro: " . implode("\n", $output) . "\n";
    }
}

// 6. Estatísticas finais
echo "\n📊 ESTATÍSTICAS DA LIMPEZA:\n";
echo "============================\n";
echo "✅ Arquivos removidos: " . count($removidos) . "\n";
echo "❌ Erros encontrados: " . count($erros) . "\n";

if (!empty($removidos)) {
    echo "\n📋 ARQUIVOS REMOVIDOS:\n";
    foreach ($removidos as $arquivo) {
        echo "  - $arquivo\n";
    }
}

if (!empty($erros)) {
    echo "\n⚠️  ERROS ENCONTRADOS:\n";
    foreach ($erros as $erro) {
        echo "  - $erro\n";
    }
}

echo "\n🎉 LIMPEZA CONCLUÍDA!\n";
echo "O projeto está agora mais limpo e organizado.\n\n";

// Auto-remoção do script
echo "🗑️  Removendo este script de limpeza...\n";
if (unlink(__FILE__)) {
    echo "✅ Script removido com sucesso!\n";
} else {
    echo "❌ Erro ao remover o script. Remova manualmente: " . __FILE__ . "\n";
}

echo "\n✨ PROCESSO FINALIZADO! ✨\n";
