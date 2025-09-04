<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Senha - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <link href="{{ asset('app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .login-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .login-card {
            background: white;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .input-field {
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        .input-field:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background: #e5e7eb;
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="login-container min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-sm">
        <!-- Card de Nova Senha -->
        <div class="login-card rounded-lg p-8">
            <!-- Logo -->
            <div class="text-center mb-8">
                <!-- Logo dspay PNG -->
                <div class="mb-6">
                    <img src="{{ asset('images/dspay-logo.png') }}" alt="dspay" class="h-12 w-auto mx-auto">
                </div>
                
                <h1 class="text-xl font-semibold text-gray-800 mb-1">Nova Senha</h1>
                <p class="text-sm text-gray-600">Digite sua nova senha</p>
            </div>

            <!-- Mensagens de erro -->
            @if ($errors->any())
                <div class="mb-6 p-3 bg-red-50 border border-red-200 rounded-md">
                    <div class="text-red-700 text-sm">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Formulário -->
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email', $request->email) }}"
                        required 
                        autofocus 
                        autocomplete="username"
                        class="w-full px-3 py-2 input-field rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                        placeholder="Digite seu email"
                    >
                </div>

                <!-- Nova Senha -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Nova Senha
                    </label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="new-password"
                        class="w-full px-3 py-2 input-field rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                        placeholder="Digite sua nova senha"
                    >
                </div>

                <!-- Confirmar Nova Senha -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmar Nova Senha
                    </label>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        class="w-full px-3 py-2 input-field rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                        placeholder="Confirme sua nova senha"
                    >
                </div>

                <!-- Botão de Reset -->
                <button 
                    type="submit" 
                    class="w-full btn-login text-white font-medium py-2 px-4 rounded-md"
                >
                    Redefinir Senha
                </button>
            </form>

            <!-- Voltar para Login -->
            <div class="mt-6 text-center">
                <a 
                    href="{{ route('login') }}" 
                    class="inline-block w-full btn-secondary font-medium py-2 px-4 rounded-md text-center"
                >
                    Voltar para o Login
                </a>
            </div>
        </div>
    </div>
</body>
</html>
