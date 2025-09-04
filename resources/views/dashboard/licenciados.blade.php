<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Licenciados - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gradient-to-b from-blue-500 to-purple-600 flex-shrink-0">
            <div class="p-6">
                <!-- Logo -->
                <div class="flex items-center mb-8">
                    <img src="{{ asset('images/dspay-logo.png') }}" alt="dspay" class="h-10 w-auto mx-auto">
                </div>

                <!-- Menu -->
                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-all">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('dashboard.licenciados') }}" class="flex items-center px-4 py-3 text-white rounded-lg bg-white/20 border-l-4 border-white">
                        <i class="fas fa-id-card mr-3"></i>
                        Licenciados
                    </a>
                    <a href="{{ route('dashboard.operacoes') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-all">
                        <i class="fas fa-cogs mr-3"></i>
                        Operações
                    </a>
                    <a href="{{ route('dashboard.planos') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-all">
                        <i class="fas fa-chart-line mr-3"></i>
                        Planos
                    </a>
                    <a href="{{ route('dashboard.adquirentes') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-all">
                        <i class="fas fa-building mr-3"></i>
                        Adquirentes
                    </a>
                    <a href="{{ route('dashboard.agenda') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-all">
                        <i class="fas fa-calendar-alt mr-3"></i>
                        Agenda
                    </a>
                </nav>
            </div>

            <!-- User Profile -->
            <div class="absolute bottom-0 w-64 p-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-white font-medium">{{ Auth::user()->name ?? 'Bruno Administrador' }}</p>
                        <p class="text-white/70 text-sm">Administrador</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Licenciados</h1>
                        <p class="text-gray-600">Gerencie os licenciados do sistema</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Novo Licenciado
                        </button>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Total de Licenciados</p>
                                <p class="text-3xl font-bold">{{ $stats['total'] ?? 0 }}</p>
                                <p class="text-white/80 text-sm mt-1">
                                    <i class="fas fa-users mr-1"></i>
                                    Cadastros ativos
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-pink-500 to-red-500 rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Aprovados</p>
                                <p class="text-3xl font-bold">{{ $stats['aprovados'] ?? 0 }}</p>
                                <p class="text-white/80 text-sm mt-1">
                                    <i class="fas fa-check mr-1"></i>
                                    Licenciados ativos
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Em Análise</p>
                                <p class="text-3xl font-bold">{{ $stats['em_analise'] ?? 0 }}</p>
                                <p class="text-white/80 text-sm mt-1">
                                    <i class="fas fa-clock mr-1"></i>
                                    Aguardando aprovação
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Recusados</p>
                                <p class="text-3xl font-bold">{{ $stats['recusados'] ?? 0 }}</p>
                                <p class="text-white/80 text-sm mt-1">
                                    <i class="fas fa-times mr-1"></i>
                                    Cadastros recusados
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-times-circle text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabela de Licenciados -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="flex items-center justify-between p-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Lista de Licenciados</h3>
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <input type="text" placeholder="Buscar licenciados..." class="w-80 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-download mr-2"></i>
                                Exportar
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Razão Social</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CNPJ/CPF</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Cadastro</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if(isset($licenciados) && $licenciados->count() > 0)
                                    @foreach($licenciados as $licenciado)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $licenciado->razao_social }}</div>
                                                <div class="text-sm text-gray-500">{{ $licenciado->nome_fantasia }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $licenciado->cnpj }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($licenciado->status == 'aprovado')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        Aprovado
                                                    </span>
                                                @elseif($licenciado->status == 'em_analise')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Em Análise
                                                    </span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                        Recusado
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $licenciado->created_at->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <button class="text-blue-600 hover:text-blue-900">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="text-green-600 hover:text-green-900">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="text-red-600 hover:text-red-900">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-2"></i>
                                            <p>Nenhum licenciado cadastrado ainda</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>