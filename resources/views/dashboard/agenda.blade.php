<style>
/* BRANDING FORÇADO PARA ESTA PÁGINA */
:root {
    --primary-color: #3B82F6;
    --secondary-color: #6B7280;
    --accent-color: #10B981;
    --text-color: #1F2937;
    --background-color: #FFFFFF;
    --primary-light: rgba(59, 130, 246, 0.1);
    --primary-dark: #2563EB;
    --primary-text: #FFFFFF;
}

/* Sobrescrita agressiva de todas as cores azuis */
.bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400,
.bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-blue-800, .bg-blue-900,
.bg-indigo-50, .bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700, .bg-indigo-800, .bg-indigo-900 {
    background-color: var(--primary-color) !important;
}

.hover\:bg-blue-600:hover, .hover\:bg-blue-700:hover, .hover\:bg-blue-800:hover,
.hover\:bg-indigo-600:hover, .hover\:bg-indigo-700:hover, .hover\:bg-indigo-800:hover {
    background-color: var(--primary-dark) !important;
}

.text-blue-500, .text-blue-600, .text-blue-700, .text-blue-800, .text-blue-900,
.text-indigo-500, .text-indigo-600, .text-indigo-700, .text-indigo-800, .text-indigo-900 {
    color: var(--primary-color) !important;
}

.border-blue-500, .border-blue-600, .border-blue-700, .border-blue-800, .border-blue-900,
.border-indigo-500, .border-indigo-600, .border-indigo-700, .border-indigo-800, .border-indigo-900 {
    border-color: var(--primary-color) !important;
}

button[class*="blue"], .btn[class*="blue"], button[class*="indigo"], .btn[class*="indigo"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

button[class*="blue"]:hover, .btn[class*="blue"]:hover, button[class*="indigo"]:hover, .btn[class*="indigo"]:hover {
    background-color: var(--primary-dark) !important;
}

.bg-green-500, .bg-green-600, .bg-green-700 {
    background-color: var(--accent-color) !important;
}

.text-green-500, .text-green-600, .text-green-700, .text-green-800 {
    color: var(--accent-color) !important;
}

/* Sobrescrever estilos inline hardcoded */
[style*="background-color: #3b82f6"], [style*="background-color: #2563eb"],
[style*="background-color: #1d4ed8"], [style*="background-color: #1e40af"] {
    background-color: var(--primary-color) !important;
}

[style*="color: #3b82f6"], [style*="color: #2563eb"],
[style*="color: #1d4ed8"], [style*="color: #1e40af"] {
    color: var(--primary-color) !important;
}

.animate-spin[class*="border-blue"], .animate-spin[class*="border-indigo"] {
    border-color: var(--primary-color) transparent var(--primary-color) transparent !important;
}
</style>
@extends('layouts.dashboard')

@section('title', 'Lista de Compromissos')

@section('content')
<x-dynamic-branding />
<!-- Branding Dinâmico -->

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-calendar-day " style="color: var(--primary-color);" mr-3"></i>
                        @if($isToday && !$hasDateFilter)
                            Compromissos de Hoje
                        @elseif($hasDateFilter)
                            Compromissos - {{ \Carbon\Carbon::parse($dataAtual)->format('d/m/Y') }}
                        @else
                            Lista de Compromissos
                        @endif
                    </h1>
                    <p style="color: var(--secondary-color);">
                        @if($isToday && !$hasDateFilter)
                            Seus compromissos agendados para hoje
                        @elseif($hasDateFilter)
                            Compromissos para a data selecionada
                        @else
                            Gerencie todos os seus compromissos e reuniões
                        @endif
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200">
                        <input type="date" 
                               id="dateFilter"
                               value="{{ request('data') ? request('data') : '' }}" 
                               onchange="changeDate(this.value)"
                               class="px-4 py-2 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @if(request('data'))
                            <button onclick="clearDateFilter()" 
                                    class="px-3 py-2 text-gray-500 hover:text-red-600 transition-colors"
                                    title="Limpar filtro">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    </div>
                    
                    @if(request('data'))
                        <div class="text-sm text-gray-600 bg-blue-50 px-3 py-2 rounded-lg border border-blue-200">
                            <i class="fas fa-filter mr-2 " style="color: var(--primary-color);""></i>
                            Filtrado por: <strong>{{ \Carbon\Carbon::parse(request('data'))->format('d/m/Y') }}</strong>
                        </div>
                    @endif
                    
                    <a href="{{ route('dashboard.agenda.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300 font-semibold flex items-center shadow-lg hover:shadow-xl transform hover:scale-105 border border-blue-400">
                        <i class="fas fa-plus mr-2 text-white"></i>
                        Nova Reunião
                    </a>
                    
                    @php
                        $pendentesCount = 0;
                        if (Auth::check()) {
                            $pendentesCount = \App\Models\Agenda::pendentesAprovacao(Auth::id())->count();
                        }
                    @endphp
                    
                    <a href="{{ route('agenda.pendentes-aprovacao') }}" class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-3 rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all duration-300 font-semibold flex items-center shadow-lg hover:shadow-xl transform hover:scale-105 border border-orange-400">
                        <i class="fas fa-clock mr-2 text-white"></i>
                        Aprovar Compromissos
                        @if($pendentesCount > 0)
                            <span class="ml-2 bg-red-500 text-white text-xs px-2.5 py-1 rounded-full font-bold animate-pulse shadow-md border border-red-400">
                                {{ $pendentesCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-3 " style="color: var(--accent-color);""></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-red-600"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Filtros e Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-calendar-day " style="color: var(--primary-color);" text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p style="color: var(--secondary-color);">Hoje</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $agendas->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle " style="color: var(--accent-color);" text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p style="color: var(--secondary-color);">Confirmados</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $agendas->where('status', 'confirmado')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p style="color: var(--secondary-color);">Pendentes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $agendas->where('status', 'pendente')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-video text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p style="color: var(--secondary-color);">Online</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $agendas->where('tipo_reuniao', 'online')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Compromissos -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Compromissos de {{ \Carbon\Carbon::parse($dataAtual)->locale('pt_BR')->translatedFormat('d \\d\\e F \\d\\e Y') }}
                    </h2>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">{{ $agendas->count() }} compromisso(s)</span>
                    </div>
                </div>
            </div>

            <!-- Contador de resultados -->
            <div class="mb-6 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <i class="fas fa-calendar-check mr-2 " style="color: var(--primary-color);""></i>
                    @if($isToday && !$hasDateFilter)
                        <strong>{{ $agendas->count() }}</strong> compromisso(s) para <strong>hoje</strong> ({{ \Carbon\Carbon::parse($dataAtual)->format('d/m/Y') }})
                    @elseif($hasDateFilter)
                        <strong>{{ $agendas->count() }}</strong> compromisso(s) para <strong>{{ \Carbon\Carbon::parse($dataAtual)->format('d/m/Y') }}</strong>
                    @else
                        <strong>{{ $agendas->count() }}</strong> compromisso(s) encontrado(s)
                    @endif
                </div>
                
                <div class="text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Use o filtro de data para ver outros dias
                </div>
            </div>

            @if($agendas->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($agendas as $agenda)
                        <div class="p-6 hover:bg-gray-50 transition-colors cursor-pointer" onclick="viewAgenda({{ $agenda->id }})">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <!-- Status Icon -->
                                    <div class="flex-shrink-0">
                                    @if($agenda->status === 'concluida')
                                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-check-circle " style="color: var(--accent-color);" text-xl"></i>
                                            </div>
                                    @elseif($agenda->status === 'agendada')
                                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-clock text-yellow-600 text-xl"></i>
                                            </div>
                                        @elseif($agenda->status === 'cancelada')
                                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-times-circle text-red-600 text-xl"></i>
                                            </div>
                                        @else
                                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-calendar text-gray-600 text-xl"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Informações do Compromisso -->
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $agenda->titulo }}</h3>
                                        @if($agenda->descricao)
                                            <p style="color: var(--secondary-color);">{{ Str::limit($agenda->descricao, 100) }}</p>
                                        @endif
                                        
                                        <div class="flex items-center space-x-6 mt-3">
                                            <div class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-clock mr-2"></i>
                                                {{ \Carbon\Carbon::parse($agenda->data_inicio)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($agenda->data_fim)->format('H:i') }}
                                            </div>
                                            
                                            <div class="flex items-center text-sm text-gray-500">
                                                @if($agenda->tipo_reuniao === 'online')
                                                    <i class="fas fa-video mr-2"></i>
                                                    Online
                                                @elseif($agenda->tipo_reuniao === 'presencial')
                                                    <i class="fas fa-handshake mr-2"></i>
                                                    Presencial
                                                @else
                                                    <i class="fas fa-users mr-2"></i>
                                                    Híbrida
                                                @endif
                                            </div>
                                            
                                            @if($agenda->participantes && count($agenda->participantes) > 0)
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <i class="fas fa-users mr-2"></i>
                                                    {{ count($agenda->participantes) }} participante(s)
                                                    
                                                    @php
                                                        $confirmados = $agenda->confirmacoesConfirmadas()->count();
                                                        $pendentes = $agenda->confirmacoesPendentes()->count();
                                                        $recusados = $agenda->confirmacoesRecusadas()->count();
                                                        $total = count($agenda->participantes);
                                                    @endphp
                                                    
                                                    @if($confirmados > 0 || $pendentes > 0 || $recusados > 0)
                                                        <span class="ml-2 flex items-center space-x-1">
                                                            @if($confirmados > 0)
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                    ✅ {{ $confirmados }}
                                                                </span>
                                                            @endif
                                                            @if($pendentes > 0)
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                    ⏰ {{ $pendentes }}
                                                                </span>
                                                            @endif
                                                            @if($recusados > 0)
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                    ❌ {{ $recusados }}
                                                                </span>
                                                            @endif
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Ações -->
                                <div class="flex items-center space-x-2">
                                    @if($agenda->meet_link)
                                        <a href="{{ $agenda->meet_link }}" target="_blank" 
                                           class="p-2 " style="color: var(--accent-color);" hover:bg-green-100 rounded-lg transition-colors"
                                           title="Abrir Google Meet"
                                           onclick="event.stopPropagation()">
                                            <i class="fab fa-google text-lg"></i>
                                        </a>
                                    @endif
                                    
                                    <button onclick="event.stopPropagation(); viewAgendaDetails({{ $agenda->id }})" 
                                            class="p-2 text-purple-600 hover:bg-purple-100 rounded-lg transition-colors"
                                            title="Ver Detalhes">
                                        <i class="fas fa-eye text-lg"></i>
                                    </button>
                                    
                                    <button onclick="event.stopPropagation(); editAgenda({{ $agenda->id }})" 
                                            class="p-2 " style="color: var(--primary-color);" hover:bg-blue-100 rounded-lg transition-colors"
                                            title="Editar">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                    
                                    <button onclick="event.stopPropagation(); confirmDeleteAgenda({{ $agenda->id }}, '{{ addslashes($agenda->titulo) }}')" 
                                            class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                                            title="Excluir">
                                        <i class="fas fa-trash text-lg"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Botões de Aprovação (se for destinatário e pendente) -->
                            @if($agenda->destinatario_id === Auth::id() && $agenda->status_aprovacao === 'pendente')
                                <div class="mt-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <button onclick="aprovarAgenda({{ $agenda->id }})" 
                                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                                            <i class="fas fa-check mr-2"></i>
                                            Aprovar
                                        </button>
                                        <button onclick="recusarAgenda({{ $agenda->id }})" 
                                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                            <i class="fas fa-times mr-2"></i>
                                            Recusar
                                        </button>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            Aguardando Aprovação
                                        </span>
                                        @if($agenda->fora_horario_comercial)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-moon mr-1"></i>
                                                Fora do Horário
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @else
                            <!-- Status Badge -->
                            <div class="mt-4 flex justify-end">
                                    <div class="flex items-center space-x-2">
                                        <!-- Status da Agenda -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($agenda->status === 'concluida') bg-green-100 text-green-800
                                            @elseif($agenda->status === 'agendada') bg-yellow-100 text-yellow-800
                                            @elseif($agenda->status === 'em_andamento') bg-blue-100 text-blue-800
                                            @elseif($agenda->status === 'cancelada') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $agenda->status)) }}
                                </span>
                                        
                                        <!-- Status de Aprovação -->
                                        @if($agenda->status_aprovacao === 'aprovada')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-thumbs-up mr-1"></i>
                                                Aprovada
                                            </span>
                                        @elseif($agenda->status_aprovacao === 'recusada')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-thumbs-down mr-1"></i>
                                                Recusada
                                            </span>
                                        @elseif($agenda->status_aprovacao === 'pendente' && $agenda->destinatario_id !== Auth::id())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <i class="fas fa-hourglass-half mr-1"></i>
                                                Aguardando Aprovação
                                            </span>
                                        @endif
                                        
                                        @if($agenda->fora_horario_comercial)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-moon mr-1"></i>
                                                Fora do Horário
                                            </span>
                                        @endif
                                    </div>
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        @if($isToday && !$hasDateFilter)
                            <i class="fas fa-calendar-day text-gray-400 text-2xl"></i>
                        @else
                            <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                        @endif
                    </div>
                    
                    @if($isToday && !$hasDateFilter)
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum compromisso para hoje</h3>
                        <p style="color: var(--secondary-color);">
                            Você não tem compromissos agendados para hoje ({{ \Carbon\Carbon::parse($dataAtual)->format('d/m/Y') }}).
                            <br>
                            <span class="text-sm">Use o filtro de data acima para ver compromissos de outros dias.</span>
                        </p>
                    @elseif($hasDateFilter)
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum compromisso para {{ \Carbon\Carbon::parse($dataAtual)->format('d/m/Y') }}</h3>
                        <p style="color: var(--secondary-color);">
                            Não há compromissos agendados para esta data.
                            <br>
                            <span class="text-sm">Tente selecionar outra data ou limpe o filtro para ver todos os compromissos.</span>
                        </p>
                    @else
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum compromisso encontrado</h3>
                        <p style="color: var(--secondary-color);">Não há compromissos agendados.</p>
                    @endif
                    
                    <div class="flex items-center justify-center space-x-4">
                        <a href="{{ route('dashboard.agenda.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            Nova Reunião
                        </a>
                        
                        @if($hasDateFilter)
                            <button onclick="clearDateFilter()" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                <i class="fas fa-calendar mr-2"></i>
                                Ver Hoje
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Criação/Edição de Reunião -->
<div id="agendaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900" id="modalTitle">Nova Reunião</h3>
                    <button onclick="closeAgendaModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <form id="agendaForm">
                    <input type="hidden" id="agendaId" name="id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Título da Reunião *</label>
                            <input type="text" id="titulo" name="titulo" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                            <textarea id="descricao" name="descricao" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Data de Início *</label>
                            <input type="datetime-local" id="data_inicio" name="data_inicio" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Data de Fim *</label>
                            <input type="datetime-local" id="data_fim" name="data_fim" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Reunião *</label>
                            <select id="tipo_reuniao" name="tipo_reuniao" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Selecione...</option>
                                <option value="online">Online</option>
                                <option value="presencial">Presencial</option>
                                <option value="hibrida">Híbrida</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Licenciado</label>
                            <select id="licenciado_id" name="licenciado_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Selecione um licenciado...</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Participantes (emails separados por vírgula)</label>
                            <textarea id="participantes" name="participantes" rows="2" placeholder="email1@exemplo.com, email2@exemplo.com"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeAgendaModal()" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </button>
                        <button type="button" onclick="saveAgenda()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Salvar Reunião
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Visualização -->
<div id="viewAgendaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900">Detalhes do Compromisso</h3>
                    <button onclick="closeViewAgendaModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div id="agendaDetails">
                    <!-- Detalhes serão carregados aqui -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">Confirmar Ação</h3>
            </div>
            <div class="p-6">
                <p style="color: var(--secondary-color);">Tem certeza que deseja realizar esta ação?</p>
                <div class="flex justify-end space-x-4">
                    <button onclick="closeConfirmModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button id="confirmButton" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed top-4 right-4 z-50 hidden">
    <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-4 max-w-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i id="toastIcon" class="text-xl"></i>
            </div>
            <div class="ml-3">
                <p id="toastMessage" class="text-sm font-medium text-gray-900"></p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function changeDate(newDate) {
    window.location.href = `{{ route('dashboard.agenda') }}?data=${newDate}`;
}

// Variáveis globais
let currentAction = null;
let currentAgendaId = null;

// Abrir modal para nova agenda
function openAddAgendaModal() {
    const modal = document.getElementById('agendaModal');
    const modalTitle = document.getElementById('modalTitle');
    const agendaForm = document.getElementById('agendaForm');
    const agendaId = document.getElementById('agendaId');
    
    modalTitle.textContent = 'Nova Reunião';
    agendaForm.reset();
    agendaId.value = '';
    loadLicenciados();
    modal.classList.remove('hidden');
}

// Fechar modais
function closeAgendaModal() {
    document.getElementById('agendaModal').classList.add('hidden');
}

function closeViewAgendaModal() {
    document.getElementById('viewAgendaModal').classList.add('hidden');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}

// Visualizar, editar e excluir agenda
function viewAgenda(id) {
    fetch(`/agenda/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const agenda = data.agenda;
                document.getElementById('agendaDetails').innerHTML = `
                    <div class="space-y-4">
                        <h4 class="text-xl font-bold">${agenda.titulo}</h4>
                        <p><strong>Data:</strong> ${new Date(agenda.data_inicio).toLocaleString('pt-BR')}</p>
                        <p><strong>Status:</strong> ${agenda.status}</p>
                        ${agenda.meet_link ? `<a href="${agenda.meet_link}" target="_blank" class="" style="color: var(--primary-color);"">Google Meet</a>` : ''}
                    </div>
                `;
                document.getElementById('viewAgendaModal').classList.remove('hidden');
            }
        });
}

function editAgenda(id) {
    // Implementar edição
    console.log('Editar agenda:', id);
}

function deleteAgenda(id) {
    currentAction = 'delete';
    currentAgendaId = id;
    document.getElementById('confirmMessage').textContent = 'Tem certeza que deseja excluir esta reunião?';
    document.getElementById('confirmModal').classList.remove('hidden');
}

function saveAgenda() {
    // Implementar salvamento
    console.log('Salvar agenda');
}

function loadLicenciados() {
    // Implementar carregamento de licenciados
    console.log('Carregar licenciados');
}

function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    toast.classList.remove('hidden');
    document.getElementById('toastMessage').textContent = message;
    setTimeout(() => toast.classList.add('hidden'), 3000);
}
</script>
@endpush

@push('styles')
<style>
    /* Hover effects para os cards */
    .bg-white:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease-in-out;
    }
    
    /* Animações suaves */
    .transition-colors {
        transition: all 0.2s ease-in-out;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .text-3xl {
            font-size: 1.5rem;
        }
        
        .grid-cols-4 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        
        .space-x-6 {
            flex-direction: column;
            align-items: flex-start;
            space-x: 0;
            gap: 0.5rem;
        }
    }
        
        /* Estilos dinâmicos do dashboard */
        .dashboard-header {
            background: var(--background-color);
            color: var(--text-color);
        }
        .stat-card {
            background: var(--primary-gradient);
            color: var(--primary-text);
        }
        .progress-bar {
            background: var(--accent-gradient);
        }
    </style>
@endpush

<!-- Modal de Confirmação de Exclusão -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 transform scale-95 opacity-0 transition-all duration-300" id="deleteModalContent">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Confirmar Exclusão</h3>
            <p style="color: var(--secondary-color);">
                Tem certeza que deseja excluir a reunião 
                <strong id="deleteAgendaTitle" class="text-gray-900"></strong>?
                <br><br>
                <span class="text-sm text-red-600">Esta ação não pode ser desfeita.</span>
            </p>
            <div class="flex space-x-4">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-6 py-3 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button onclick="deleteAgenda()" 
                        id="confirmDeleteBtn"
                        class="flex-1 px-6 py-3 bg-red-600 text-white rounded-xl font-medium hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>
                    Excluir
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalhes da Agenda -->
<div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300" id="detailsModalContent">
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-calendar-alt " style="color: var(--primary-color);" mr-3"></i>
                    Detalhes da Reunião
                </h3>
                <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="agendaDetailsContent" class="space-y-6">
                <!-- Conteúdo será carregado via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição da Agenda -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300" id="editModalContent">
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-edit " style="color: var(--primary-color);" mr-3"></i>
                    Editar Reunião
                </h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editAgendaForm" method="POST">
                @csrf
                @method('PUT')
                <div id="editFormContent" class="space-y-6">
                    <!-- Conteúdo será carregado via JavaScript -->
                </div>
                
                <div class="flex space-x-4 mt-8">
                    <button type="button" onclick="closeEditModal()" 
                            class="flex-1 px-6 py-3 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/agenda-actions.js') }}"></script>

<script>
// Função para alterar o filtro de data
function changeDate(selectedDate) {
    if (selectedDate) {
        // Redirecionar para a mesma página com o parâmetro de data
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('data', selectedDate);
        window.location.href = currentUrl.toString();
    } else {
        // Se não há data selecionada, remover o parâmetro
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.delete('data');
        window.location.href = currentUrl.toString();
    }
}

// Função para limpar filtro de data
function clearDateFilter() {
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.delete('data');
    window.location.href = currentUrl.toString();
}

// Função para aprovar agenda
function aprovarAgenda(agendaId) {
    if (confirm('Tem certeza que deseja aprovar esta solicitação de reunião?')) {
        const btn = event.target;
        const originalText = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Aprovando...';
        
        fetch(`/agenda/${agendaId}/aprovar`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message, 'error');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao aprovar agenda', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }
}

// Função para recusar agenda
function recusarAgenda(agendaId) {
    const motivo = prompt('Motivo da recusa (opcional):');
    if (motivo !== null) { // null = cancelou, string vazia = OK sem motivo
        const btn = event.target;
        const originalText = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Recusando...';
        
        fetch(`/agenda/${agendaId}/recusar`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ motivo: motivo })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message, 'error');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao recusar agenda', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }
}

// Função para mostrar toast
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 translate-x-full ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'} mr-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 4000);
}
</script>
