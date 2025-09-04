<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .card {
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .stat-card-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .stat-card-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .stat-card-warning {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
    </style>
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
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-white rounded-lg bg-white/20 border-l-4 border-white">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('dashboard.licenciados') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-all">
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
                    <a href="{{ route('dashboard.leads') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-all">
                        <i class="fas fa-user-plus mr-3"></i>
                        Leads
                    </a>
                    <a href="{{ route('dashboard.marketing') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-all">
                        <i class="fas fa-bullhorn mr-3"></i>
                        Marketing
                    </a>
                    <a href="{{ route('dashboard.configuracoes') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-all">
                        <i class="fas fa-cog mr-3"></i>
                        Configurações
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
                        <p class="text-white font-medium">{{ Auth::user()->name ?? 'Usuário' }}</p>
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
                        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                        <p class="text-gray-600">Bem-vindo ao painel de controle</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-bell"></i>
                        </button>
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-cog"></i>
                        </button>
                        <form method="POST" action="{{ route('logout.custom') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card card rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Total de Licenciados</p>
                                <p class="text-3xl font-bold">{{ $stats['total'] }}</p>
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

                    <div class="stat-card-secondary card rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Aprovados</p>
                                <p class="text-3xl font-bold">{{ $stats['aprovados'] }}</p>
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

                    <div class="stat-card-success card rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Em Análise</p>
                                <p class="text-3xl font-bold">{{ $stats['em_analise'] }}</p>
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

                    <div class="stat-card-warning card rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Recusados</p>
                                <p class="text-3xl font-bold">{{ $stats['recusados'] }}</p>
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



                <!-- Recent Activity & Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Recent Licensees -->
                    <div class="card rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Licenciados Recentes</h3>
                        <div class="space-y-4">
                            @forelse($licenciadosRecentes as $licenciado)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-id-card text-blue-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-medium text-gray-800">{{ $licenciado->razao_social }}</p>
                                            <p class="text-sm text-gray-600">{{ $licenciado->cnpj_cpf }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($licenciado->status === 'aprovado')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Aprovado</span>
                                        @elseif($licenciado->status === 'em_analise')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Em Análise</span>
                                        @elseif($licenciado->status === 'recusado')
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Recusado</span>
                                        @elseif($licenciado->status === 'em_risco')
                                            <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded-full">Em Risco</span>
                                        @endif
                                        <p class="text-sm text-gray-600 mt-1">{{ $licenciado->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <i class="fas fa-inbox text-gray-400 text-4xl mb-2"></i>
                                    <p class="text-gray-500">Nenhum licenciado cadastrado ainda</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Ações Rápidas</h3>
                        <div class="space-y-3">
                            <a href="{{ route('dashboard.licenciados') }}" class="w-full flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                <i class="fas fa-user-plus text-blue-600 mr-3"></i>
                                <span class="text-blue-800 font-medium">Novo Licenciado</span>
                            </a>
                            <a href="{{ route('dashboard.operacoes') }}" class="w-full flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                                <i class="fas fa-cogs text-green-600 mr-3"></i>
                                <span class="text-green-800 font-medium">Gerenciar Operações</span>
                            </a>
                            <a href="{{ route('dashboard.configuracoes') }}" class="w-full flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                                <i class="fas fa-cog text-purple-600 mr-3"></i>
                                <span class="text-purple-800 font-medium">Configurações</span>
                            </a>
                            <button class="w-full flex items-center p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
                                <i class="fas fa-chart-bar text-orange-600 mr-3"></i>
                                <span class="text-orange-800 font-medium">Relatórios</span>
                            </button>
                        </div>
                    </div>

                    <!-- System Status -->
                    <div class="card rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status do Sistema</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                    <span class="text-gray-700">Servidor Principal</span>
                                </div>
                                <span class="text-green-600 text-sm font-medium">Online</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                    <span class="text-gray-700">Banco de Dados</span>
                                </div>
                                <span class="text-green-600 text-sm font-medium">Online</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                                    <span class="text-gray-700">API Externa</span>
                                </div>
                                <span class="text-yellow-600 text-sm font-medium">Lento</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                    <span class="text-gray-700">Backup</span>
                                </div>
                                <span class="text-green-600 text-sm font-medium">Atualizado</span>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>


</body>
</html>
