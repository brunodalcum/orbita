<!DOCTYPE html>
<html lang="pt-BR">
<head>
<x-dynamic-branding />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Calendar - Erro na Autorização</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                ❌ Erro na Autorização
            </h2>
            
            <p class="text-gray-600 mb-6">
                {{ $error }}
            </p>
            
            <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">
                            <strong>Possíveis soluções:</strong><br>
                            1. Verifique se as credenciais estão corretas<br>
                            2. Tente novamente o processo de autorização<br>
                            3. Entre em contato com o suporte se o problema persistir
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="space-y-3">
                <a href="{{ route('dashboard') }}" 
                   class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors block text-center">
                    🏠 Voltar ao Dashboard
                </a>
                
                <button onclick="window.close()" 
                        class="w-full bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
                    ❌ Fechar Janela
                </button>
            </div>
            
            <p class="text-xs text-gray-500 mt-6">
                Se o problema persistir, execute: php artisan google:setup
            </p>
        </div>
    </div>
</body>
</html>
