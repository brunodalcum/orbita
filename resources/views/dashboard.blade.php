<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - dspay</title>
    @php
        $user = Auth::user();
        $faviconUrl = 'images/dspay-logo.png'; // padrão
        
        if ($user) {
            // Todos os usuários podem ter favicon personalizado
            $branding = $user->getBrandingWithInheritance();
            if (!empty($branding['favicon_url'])) {
                $faviconUrl = 'storage/' . $branding['favicon_url'];
            } elseif ($user->isSuperAdminNode()) {
                // Super Admin usa favicon da Órbita como fallback
                $faviconUrl = 'storage/branding/orbita/orbita-favicon.svg';
            }
        }
    @endphp
    
    <link rel="icon" type="image/png" href="{{ asset($faviconUrl) }}?v={{ time() }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset($faviconUrl) }}?v={{ time() }}">
    
    <!-- Branding Dinâmico -->
    <x-dynamic-branding />
    
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
            background: var(--primary-gradient);
            color: var(--primary-text);
        }
        .stat-card-secondary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            color: white;
        }
        .stat-card-success {
            background: var(--accent-gradient);
            color: white;
        }
        .stat-card-warning {
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--primary-color) 100%);
            color: white;
        }
        .dashboard-header {
            background: var(--background-color);
            color: var(--text-color);
        }
        .dashboard-card {
            border-left: 4px solid var(--primary-color);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar Dinâmico -->
        <x-dynamic-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="dashboard-header bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold" style="color: var(--text-color);">Dashboard</h1>
                        <p style="color: var(--secondary-color);">Bem-vindo ao painel de controle</p>
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

                    <!-- Compromissos do Dia -->
                    <div class="card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Compromissos do Dia</h3>
                            <a href="{{ route('dashboard.agenda') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Ver todos
                            </a>
                        </div>
                        <div class="space-y-3">
                            @forelse($compromissosHoje as $compromisso)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center flex-1">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center
                                            @if($compromisso->tipo_reuniao === 'online') bg-blue-100
                                            @elseif($compromisso->tipo_reuniao === 'presencial') bg-green-100
                                            @else bg-purple-100
                                            @endif">
                                            @if($compromisso->tipo_reuniao === 'online')
                                                <i class="fas fa-video text-blue-600"></i>
                                            @elseif($compromisso->tipo_reuniao === 'presencial')
                                                <i class="fas fa-handshake text-green-600"></i>
                                            @else
                                                <i class="fas fa-users text-purple-600"></i>
                                            @endif
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="font-medium text-gray-800 text-sm">{{ Str::limit($compromisso->titulo, 25) }}</p>
                                            <div class="flex items-center text-xs text-gray-600 mt-1">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ \Carbon\Carbon::parse($compromisso->data_inicio)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($compromisso->data_fim)->format('H:i') }}
                                                @if($compromisso->solicitante && $compromisso->solicitante->id !== Auth::id())
                                                    <span class="ml-2 text-gray-500">• {{ Str::limit($compromisso->solicitante->name, 15) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if($compromisso->status_aprovacao === 'pendente' && $compromisso->destinatario_id === Auth::id())
                                            <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded-full">Pendente</span>
                                        @elseif($compromisso->status_aprovacao === 'aprovada')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Confirmada</span>
                                        @elseif($compromisso->status_aprovacao === 'recusada')
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Recusada</span>
                                        @else
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Agendada</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <i class="fas fa-calendar-day text-gray-400 text-4xl mb-2"></i>
                                    <p class="text-gray-500 text-sm">Nenhum compromisso para hoje</p>
                                    <a href="{{ route('dashboard.agenda.create') }}" class="inline-flex items-center mt-3 px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-plus mr-2"></i>
                                        Nova Reunião
                                    </a>
                                </div>
                            @endforelse
                        </div>
                        
                        @if($compromissosHoje->count() > 0)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">
                                        <i class="fas fa-calendar-check mr-1 text-blue-600"></i>
                                        {{ $compromissosHoje->count() }} compromisso(s) hoje
                                    </span>
                                    <a href="{{ route('dashboard.agenda.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        <i class="fas fa-plus mr-1"></i>
                                        Nova Reunião
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Últimos Leads Cadastrados -->
                    <div class="card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Últimos Leads Cadastrados</h3>
                            <a href="{{ route('dashboard.leads') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Ver todos
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        
                        @if($leadsRecentes->count() > 0)
                            <div class="space-y-3">
                                @foreach($leadsRecentes as $lead)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-user text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 text-sm">{{ $lead->nome }}</p>
                                                <p class="text-gray-500 text-xs">
                                                    {{ $lead->email }} • {{ $lead->telefone }}
                                                </p>
                                                @if($lead->empresa)
                                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mt-1">
                                                        {{ $lead->empresa }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="flex items-center mb-1">
                                                @if($lead->status === 'novo')
                                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                    <span class="text-green-600 text-xs font-medium">Novo</span>
                                                @elseif($lead->status === 'em_contato')
                                                    <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                                    <span class="text-yellow-600 text-xs font-medium">Em Contato</span>
                                                @elseif($lead->status === 'convertido')
                                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                                    <span class="text-blue-600 text-xs font-medium">Convertido</span>
                                                @else
                                                    <div class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>
                                                    <span class="text-gray-600 text-xs font-medium">{{ ucfirst($lead->status) }}</span>
                                                @endif
                                            </div>
                                            <p class="text-gray-400 text-xs">
                                                {{ $lead->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-users text-gray-300 text-4xl mb-3"></i>
                                <p class="text-gray-500">Nenhum lead cadastrado ainda</p>
                                <a href="{{ route('dashboard.leads') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2 inline-block">
                                    Gerenciar leads
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>


</body>
</html>
