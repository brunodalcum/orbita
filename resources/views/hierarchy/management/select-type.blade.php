<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Selecionar Tipo de Nó - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid white;
        }
        .type-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .type-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar Dinâmico -->
        <x-dynamic-sidebar />
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden ml-64">
            <!-- Header -->
            <div class="bg-white shadow-sm border-b">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <div class="flex items-center">
                            <h1 class="text-xl font-semibold text-gray-900">Selecionar Tipo de Nó</h1>
                            <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst(str_replace('_', ' ', $user->node_type)) }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('hierarchy.management.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Voltar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-auto p-6">
                <div class="max-w-4xl mx-auto">
                    <!-- Título e Descrição -->
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Que tipo de nó você deseja criar?</h2>
                        <p class="text-gray-600">Selecione o tipo de nó que você gostaria de adicionar à hierarquia.</p>
                    </div>

                    <!-- Grid de Tipos -->
                    @if(count($availableTypes) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($availableTypes as $type)
                        <div class="type-card bg-white rounded-lg shadow-md p-6 border-2 border-transparent hover:border-{{ $type['color'] }}-200" 
                             onclick="selectType('{{ $type['key'] }}')">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-{{ $type['color'] }}-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="{{ $type['icon'] }} text-2xl text-{{ $type['color'] }}-600"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $type['name'] }}</h3>
                                <p class="text-sm text-gray-600 mb-4">{{ $type['description'] }}</p>
                                <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-{{ $type['color'] }}-600 hover:bg-{{ $type['color'] }}-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $type['color'] }}-500 transition-colors">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Criar {{ $type['name'] }}
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum tipo disponível</h3>
                        <p class="mt-1 text-sm text-gray-500">Você não tem permissão para criar novos nós ou atingiu o limite da hierarquia.</p>
                        <div class="mt-6">
                            <a href="{{ route('hierarchy.management.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Voltar ao Gerenciamento
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectType(type) {
            window.location.href = `{{ route('hierarchy.management.create') }}?type=${type}`;
        }
    </script>
</body>
</html>
