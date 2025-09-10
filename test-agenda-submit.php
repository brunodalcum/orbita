<?php
/**
 * Script para testar o salvamento de agenda e identificar o loop
 */

// Simular dados de um formulário de agenda
$testData = [
    'titulo' => 'Reunião de Teste',
    'descricao' => 'Teste para identificar problema do loop',
    'data_inicio' => '2025-09-11T10:00',
    'data_fim' => '2025-09-11T11:00',
    'tipo_reuniao' => 'online',
    'participantes' => 'teste@example.com',
    'meet_link' => '',
    'licenciado_id' => ''
];

echo "🧪 Teste de Salvamento de Agenda\n";
echo "================================\n\n";

echo "📋 Dados de teste:\n";
foreach ($testData as $key => $value) {
    echo "   $key: " . ($value ?: '(vazio)') . "\n";
}

echo "\n🔍 Verificando validação...\n";

// Simular validação
$rules = [
    'titulo' => 'required|string|max:255',
    'descricao' => 'nullable|string',
    'data_inicio' => 'required|date_format:Y-m-d\TH:i',
    'data_fim' => 'required|date_format:Y-m-d\TH:i|after:data_inicio',
    'tipo_reuniao' => 'required|in:presencial,online,hibrida',
    'participantes' => 'nullable|string|max:2000',
    'meet_link' => 'nullable|url',
    'licenciado_id' => 'nullable|exists:licenciados,id'
];

echo "\n✅ Regras de validação:\n";
foreach ($rules as $field => $rule) {
    echo "   $field: $rule\n";
}

// Verificar formato das datas
echo "\n📅 Verificando formato das datas:\n";
$dataInicio = $testData['data_inicio'];
$dataFim = $testData['data_fim'];

echo "   data_inicio: $dataInicio\n";
echo "   data_fim: $dataFim\n";

// Testar se as datas são válidas
$formatoEsperado = 'Y-m-d\TH:i';
$dateInicio = DateTime::createFromFormat($formatoEsperado, $dataInicio);
$dateFim = DateTime::createFromFormat($formatoEsperado, $dataFim);

if ($dateInicio && $dateFim) {
    echo "   ✅ Formato das datas está correto\n";
    
    if ($dateFim > $dateInicio) {
        echo "   ✅ Data de término é posterior ao início\n";
    } else {
        echo "   ❌ Data de término deve ser posterior ao início\n";
    }
} else {
    echo "   ❌ Formato das datas está incorreto\n";
    echo "   💡 Formato esperado: Y-m-d\\TH:i (ex: 2025-09-11T10:00)\n";
}

// Verificar tipo de reunião
echo "\n🎯 Verificando tipo de reunião:\n";
$tiposValidos = ['presencial', 'online', 'hibrida'];
$tipoSelecionado = $testData['tipo_reuniao'];

if (in_array($tipoSelecionado, $tiposValidos)) {
    echo "   ✅ Tipo '$tipoSelecionado' é válido\n";
} else {
    echo "   ❌ Tipo '$tipoSelecionado' é inválido\n";
    echo "   💡 Tipos válidos: " . implode(', ', $tiposValidos) . "\n";
}

// Verificar participantes
echo "\n👥 Verificando participantes:\n";
$participantes = $testData['participantes'];
if ($participantes) {
    $emails = preg_split('/[,\n\r]+/', $participantes);
    $emailsValidos = [];
    
    foreach ($emails as $email) {
        $email = trim($email);
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailsValidos[] = $email;
        }
    }
    
    echo "   📧 Emails encontrados: " . count($emailsValidos) . "\n";
    foreach ($emailsValidos as $email) {
        echo "      - $email\n";
    }
} else {
    echo "   ℹ️  Nenhum participante adicional\n";
}

echo "\n🔗 URLs relevantes:\n";
echo "   - Formulário: http://127.0.0.1:8000/agenda/nova\n";
echo "   - Submit: POST http://127.0.0.1:8000/agenda\n";
echo "   - Redirect: http://127.0.0.1:8000/agenda\n";

echo "\n💡 Possíveis causas do loop:\n";
echo "   1. Validação falhando silenciosamente\n";
echo "   2. Erro na criação da agenda\n";
echo "   3. Problema no redirecionamento\n";
echo "   4. JavaScript interferindo no submit\n";
echo "   5. Middleware bloqueando a requisição\n";

echo "\n🧪 Para testar:\n";
echo "   1. Acesse: http://127.0.0.1:8000/agenda/nova\n";
echo "   2. Preencha o formulário com os dados acima\n";
echo "   3. Clique em 'Salvar Reunião'\n";
echo "   4. Verifique os logs: tail -f storage/logs/laravel.log\n";
echo "   5. Observe se há logs de validação ou erro\n";

echo "\n📊 Comandos úteis:\n";
echo "   - Ver logs: tail -f storage/logs/laravel.log\n";
echo "   - Limpar logs: echo '' > storage/logs/laravel.log\n";
echo "   - Testar rota: php artisan route:list | grep agenda\n";

echo "\n";
