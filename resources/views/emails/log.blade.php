<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs de Email - Dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .email-log {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            font-family: monospace;
            font-size: 12px;
            white-space: pre-wrap;
            word-break: break-all;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="flex items-center mb-6">
                <img src="{{ asset('images/dspay-logo.png') }}" alt="dspay" class="h-8 w-auto mr-4">
                <h1 class="text-2xl font-bold text-gray-800">Logs de Email</h1>
            </div>
            
            <div class="mb-6">
                <p class="text-gray-600 mb-4">
                    Esta página mostra os emails que foram "enviados" durante o desenvolvimento. 
                    Como estamos usando <code class="bg-gray-200 px-2 py-1 rounded">MAIL_MAILER=log</code>, 
                    os emails são simulados e salvos nos logs.
                </p>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-800 mb-2">Como funciona:</h3>
                    <ul class="text-blue-700 text-sm space-y-1">
                        <li>• Emails são simulados e salvos em <code>storage/logs/laravel.log</code></li>
                        <li>• Sistema funciona normalmente para testes</li>
                        <li>• Para envio real, configure SMTP válido</li>
                    </ul>
                </div>
            </div>
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Emails Encontrados ({{ count($emails) }})</h2>
                
                @if(count($emails) > 0)
                    @foreach($emails as $email)
                        <div class="email-log">
                            {{ $email }}
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>Nenhum email encontrado nos logs.</p>
                        <p class="text-sm mt-2">Teste o sistema de recuperação de senha para ver emails aqui.</p>
                    </div>
                @endif
            </div>
            
            <div class="flex space-x-4">
                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Voltar ao Login
                </a>
                <a href="{{ route('password.request') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    Testar Recuperação
                </a>
            </div>
        </div>
    </div>
</body>
</html>
