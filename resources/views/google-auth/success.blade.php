<!DOCTYPE html>
<html lang="pt-BR">
<head>
<x-dynamic-branding />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Calendar - Autorização Concluída</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                ✅ Autorização Concluída!
            </h2>
            
            <p class="text-gray-600 mb-6">
                {{ $message }}
            </p>
            
            <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-800">
                            <strong>Próximos passos:</strong><br>
                            1. Volte ao sistema<br>
                            2. Agende uma nova reunião<br>
                            3. Ela será salva automaticamente no Google Calendar
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="space-y-3">
                <a href="{{ route('dashboard') }}" 
                   class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors block text-center">
                    🏠 Voltar ao Dashboard
                </a>
                
                <a href="{{ route('dashboard.agenda') }}" 
                   class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors block text-center">
                    📅 Ir para Agenda
                </a>
            </div>
            
            <p class="text-xs text-gray-500 mt-6">
                Esta janela pode ser fechada com segurança.
            </p>
        </div>
    </div>
</body>
</html>
