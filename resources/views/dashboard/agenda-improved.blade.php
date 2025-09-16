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
                        <i class="fas fa-list-alt " style="color: var(--primary-color);" mr-3"></i>
                        Lista de Compromissos
                    </h1>
                    <p style="color: var(--secondary-color);">Gerencie todos os seus compromissos e reuniões</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200">
                        <input type="date" 
                               value="{{ $dataAtual }}" 
                               onchange="changeDate(this.value)"
                               class="px-4 py-2 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <button onclick="openAddAgendaModal()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Nova Reunião
                    </button>
                </div>
            </div>
        </div>

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

            @if($agendas->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($agendas as $agenda)
                        <div class="p-6 hover:bg-gray-50 transition-colors cursor-pointer" onclick="viewAgenda({{ $agenda->id }})">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <!-- Status Icon -->
                                    <div class="flex-shrink-0">
                                        @if($agenda->status === 'confirmado')
                                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-check-circle " style="color: var(--accent-color);" text-xl"></i>
                                            </div>
                                        @elseif($agenda->status === 'pendente')
                                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-clock text-yellow-600 text-xl"></i>
                                            </div>
                                        @elseif($agenda->status === 'cancelado')
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
                                    
                                    <button onclick="event.stopPropagation(); editAgenda({{ $agenda->id }})" 
                                            class="p-2 " style="color: var(--primary-color);" hover:bg-blue-100 rounded-lg transition-colors"
                                            title="Editar">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                    
                                    <button onclick="event.stopPropagation(); deleteAgenda({{ $agenda->id }})" 
                                            class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                                            title="Excluir">
                                        <i class="fas fa-trash text-lg"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="mt-4 flex justify-end">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($agenda->status === 'confirmado') bg-green-100 text-green-800
                                    @elseif($agenda->status === 'pendente') bg-yellow-100 text-yellow-800
                                    @elseif($agenda->status === 'cancelado') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $agenda->status)) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum compromisso encontrado</h3>
                    <p style="color: var(--secondary-color);">Não há compromissos agendados para esta data.</p>
                    <button onclick="openAddAgendaModal()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Agendar Primeira Reunião
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Aqui continuariam os modais existentes da agenda original -->
<!-- (Modal de criação, visualização, confirmação, etc.) -->

@endsection

@push('scripts')
<script>
function changeDate(newDate) {
    window.location.href = `{{ route('dashboard.agenda') }}?data=${newDate}`;
}

// Funções que já existem na agenda original
// (openAddAgendaModal, viewAgenda, editAgenda, deleteAgenda, etc.)
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
