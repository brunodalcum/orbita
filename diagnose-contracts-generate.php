<?php

echo "🔍 DIAGNÓSTICO: /contracts/generate\n";
echo "================================\n\n";

// Testar conectividade
echo "1. Testando conectividade...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/contracts/generate');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "❌ Erro de conexão: $error\n";
    exit(1);
}

echo "✅ Conectividade OK\n";
echo "📊 Status HTTP: $httpCode\n";

if ($httpCode == 302) {
    echo "✅ DIAGNÓSTICO: Rota funcionando corretamente!\n";
    echo "📍 Status 302 = Redirect para login (comportamento esperado)\n";
    echo "🔐 O usuário precisa fazer login para acessar a página\n\n";
    
    // Extrair header Location
    if (preg_match('/Location: (.+)/', $response, $matches)) {
        $location = trim($matches[1]);
        echo "🔄 Redirecionando para: $location\n";
    }
    
} elseif ($httpCode == 500) {
    echo "❌ ERRO 500 REAL detectado!\n";
    echo "📄 Resposta do servidor:\n";
    echo substr($response, strpos($response, "\r\n\r\n") + 4);
    
} else {
    echo "⚠️  Status inesperado: $httpCode\n";
    echo "📄 Resposta:\n";
    echo substr($response, 0, 500) . "...\n";
}

echo "\n";

// Testar se o servidor está rodando
echo "2. Verificando servidor Laravel...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200 || $httpCode == 302) {
    echo "✅ Servidor Laravel funcionando\n";
} else {
    echo "❌ Problema com servidor Laravel (Status: $httpCode)\n";
}

echo "\n";

// Verificar arquivos
echo "3. Verificando arquivos...\n";

$files = [
    'routes/web.php' => 'Arquivo de rotas',
    'app/Http/Controllers/ContractController.php' => 'Controller',
    'resources/views/dashboard/contracts/generate/index.blade.php' => 'View principal'
];

foreach ($files as $file => $desc) {
    if (file_exists($file)) {
        echo "✅ $desc: OK\n";
    } else {
        echo "❌ $desc: AUSENTE ($file)\n";
    }
}

echo "\n";

// Verificar sintaxe PHP
echo "4. Verificando sintaxe PHP...\n";

$phpFiles = [
    'app/Http/Controllers/ContractController.php',
    'resources/views/dashboard/contracts/generate/index.blade.php'
];

foreach ($phpFiles as $file) {
    if (file_exists($file)) {
        $output = [];
        $return = 0;
        exec("php -l \"$file\" 2>&1", $output, $return);
        
        if ($return === 0) {
            echo "✅ $file: Sintaxe OK\n";
        } else {
            echo "❌ $file: Erro de sintaxe\n";
            echo "   " . implode("\n   ", $output) . "\n";
        }
    }
}

echo "\n";

// Conclusão
echo "🎯 CONCLUSÃO:\n";
echo "=============\n";

if ($httpCode == 302) {
    echo "✅ A rota /contracts/generate está FUNCIONANDO corretamente!\n";
    echo "📍 O status 302 indica que o usuário não está logado\n";
    echo "🔐 Solução: Faça login no sistema antes de acessar a página\n";
    echo "🌐 Acesse: http://127.0.0.1:8000/login\n";
} else {
    echo "❌ Há um problema real com a rota\n";
    echo "📞 Verifique os logs: tail -f storage/logs/laravel.log\n";
}

echo "\n🎉 Diagnóstico concluído!\n";
