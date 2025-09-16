<style>
/* BRANDING FORÇADO PARA ESTA PÁGINA */
:root {
    --primary-color: var(--primary-color);
    --secondary-color: var(--secondary-color);
    --accent-color: var(--accent-color);
    --text-color: #1F2937;
    --background-color: #FFFFFF;
    --primary-light: rgba(var(--primary-color-rgb), 0.1);
    --primary-dark: var(--primary-dark);
    --primary-text: #FFFFFF;
}

/* Sobrescrita agressiva de todas as cores azuis */
.bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400,
.bg-primary, .bg-primary, .bg-primary-dark, .bg-blue-800, .bg-blue-900,
.bg-indigo-50, .bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400,
.bg-primary, .bg-primary, .bg-primary-dark, .bg-indigo-800, .bg-indigo-900 {
    background-color: var(--primary-color) !important;
}

.hover\:bg-primary:hover, .hover\:bg-primary-dark:hover, .hover\:bg-blue-800:hover,
.hover\:bg-primary:hover, .hover\:bg-primary-dark:hover, .hover\:bg-indigo-800:hover {
    background-color: var(--primary-dark) !important;
}

.text-primary, .text-primary, .text-primary, .text-blue-800, .text-blue-900,
.text-primary, .text-primary, .text-primary, .text-indigo-800, .text-indigo-900 {
    color: var(--primary-color) !important;
}

.border-primary, .border-primary, .border-primary, .border-blue-800, .border-blue-900,
.border-primary, .border-primary, .border-primary, .border-indigo-800, .border-indigo-900 {
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
[style*="background-color: var(--primary-color)"], [style*="background-color: var(--primary-color)"],
[style*="background-color: var(--primary-color)"], [style*="background-color: var(--primary-color)"] {
    background-color: var(--primary-color) !important;
}

[style*="color: var(--primary-color)"], [style*="color: var(--primary-color)"],
[style*="color: var(--primary-color)"], [style*="color: var(--primary-color)"] {
    color: var(--primary-color) !important;
}

.animate-spin[class*="border-blue"], .animate-spin[class*="border-indigo"] {
    border-color: var(--primary-color) transparent var(--primary-color) transparent !important;
}
</style>
<style>
/* CORREÇÃO AGRESSIVA DE CORES - GERADO AUTOMATICAMENTE */
:root {
    --primary-color: #3B82F6;
    --primary-color-rgb: 59, 130, 246;
    --primary-dark: #2563EB;
    --primary-light: rgba(59, 130, 246, 0.1);
    --primary-text: #FFFFFF;
    --secondary-color: #6B7280;
    --accent-color: #10B981;
}

/* Classes customizadas para substituir Tailwind */
.bg-primary { background-color: var(--primary-color) !important; }
.bg-primary-dark { background-color: var(--primary-dark) !important; }
.text-primary { color: var(--primary-color) !important; }
.border-primary { border-color: var(--primary-color) !important; }
.hover\:bg-primary-dark:hover { background-color: var(--primary-dark) !important; }

/* Sobrescrita total de cores azuis */
.bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400,
.bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-blue-800, .bg-blue-900,
.bg-indigo-50, .bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700, .bg-indigo-800, .bg-indigo-900 {
    background-color: var(--primary-color) !important;
}

.text-blue-50, .text-blue-100, .text-blue-200, .text-blue-300, .text-blue-400,
.text-blue-500, .text-blue-600, .text-blue-700, .text-blue-800, .text-blue-900,
.text-indigo-50, .text-indigo-100, .text-indigo-200, .text-indigo-300, .text-indigo-400,
.text-indigo-500, .text-indigo-600, .text-indigo-700, .text-indigo-800, .text-indigo-900 {
    color: var(--primary-color) !important;
}

.border-blue-50, .border-blue-100, .border-blue-200, .border-blue-300, .border-blue-400,
.border-blue-500, .border-blue-600, .border-blue-700, .border-blue-800, .border-blue-900,
.border-indigo-50, .border-indigo-100, .border-indigo-200, .border-indigo-300, .border-indigo-400,
.border-indigo-500, .border-indigo-600, .border-indigo-700, .border-indigo-800, .border-indigo-900 {
    border-color: var(--primary-color) !important;
}

/* Hovers */
.hover\:bg-blue-50:hover, .hover\:bg-blue-100:hover, .hover\:bg-blue-200:hover,
.hover\:bg-blue-300:hover, .hover\:bg-blue-400:hover, .hover\:bg-blue-500:hover,
.hover\:bg-blue-600:hover, .hover\:bg-blue-700:hover, .hover\:bg-blue-800:hover,
.hover\:bg-indigo-50:hover, .hover\:bg-indigo-100:hover, .hover\:bg-indigo-200:hover,
.hover\:bg-indigo-300:hover, .hover\:bg-indigo-400:hover, .hover\:bg-indigo-500:hover,
.hover\:bg-indigo-600:hover, .hover\:bg-indigo-700:hover, .hover\:bg-indigo-800:hover {
    background-color: var(--primary-dark) !important;
}

/* Sobrescrever estilos inline */
[style*="background-color: #3b82f6"], [style*="background-color: #2563eb"],
[style*="background-color: #1d4ed8"], [style*="background-color: #1e40af"],
[style*="background-color: rgb(59, 130, 246)"], [style*="background-color: rgb(37, 99, 235)"] {
    background-color: var(--primary-color) !important;
}

[style*="color: #3b82f6"], [style*="color: #2563eb"],
[style*="color: #1d4ed8"], [style*="color: #1e40af"],
[style*="color: rgb(59, 130, 246)"], [style*="color: rgb(37, 99, 235)"] {
    color: var(--primary-color) !important;
}

/* Botões e elementos interativos */
button:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
.btn:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
input[type="submit"], input[type="button"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

button:hover:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
.btn:hover:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]) {
    background-color: var(--primary-dark) !important;
}

/* Links */
a:not([class*="text-gray"]):not([class*="text-white"]):not([class*="text-black"]):not([class*="text-red"]):not([class*="text-green"]) {
    color: var(--primary-color) !important;
}

a:hover:not([class*="text-gray"]):not([class*="text-white"]):not([class*="text-black"]):not([class*="text-red"]):not([class*="text-green"]) {
    color: var(--primary-dark) !important;
}

/* Focus states */
input:focus, select:focus, textarea:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px var(--primary-light) !important;
    outline: none !important;
}

/* Spinners e loading */
.animate-spin {
    border-color: var(--primary-color) transparent var(--primary-color) transparent !important;
}

/* Badges e tags */
.badge:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
.tag:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]) {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}
</style>
@extends('layouts.dashboard')

@section('title', 'Calendário')

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
                        <i class="fas fa-calendar-alt " style="color: var(--primary-color);" mr-3"></i>
                        Calendário
                    </h1>
                    <p style="color: var(--secondary-color);">Visualize todos os seus compromissos do mês</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200">
                        <button onclick="previousMonth()" class="p-3 text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-l-lg transition-colors">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div class="px-4 py-3 border-l border-r border-gray-200">
                            <span class="text-lg font-semibold text-gray-900" id="currentMonth">
                                {{ $date->locale('pt_BR')->translatedFormat('F Y') }}
                            </span>
                        </div>
                        <button onclick="nextMonth()" class="p-3 text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-r-lg transition-colors">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <button onclick="goToToday()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors font-medium">
                        <i class="fas fa-calendar-day mr-2"></i>
                        Hoje
                    </button>
                    <a href="{{ route('dashboard.agenda.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition-colors font-medium flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Nova Reunião
                    </a>
                </div>
            </div>
        </div>

        <!-- Estatísticas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-calendar-check " style="color: var(--primary-color);" text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p style="color: var(--secondary-color);">Total do Mês</p>
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

        <!-- Calendário -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-7 gap-1 mb-6">
                    @foreach(['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'] as $day)
                        <div class="p-4 text-center font-semibold text-gray-600 bg-gray-50 rounded-lg">
                            {{ $day }}
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-7 gap-1" id="calendarGrid">
                    @php
                        $firstDay = $date->copy()->startOfMonth();
                        $lastDay = $date->copy()->endOfMonth();
                        $startCalendar = $firstDay->copy()->startOfWeek(Carbon\Carbon::SUNDAY);
                        $endCalendar = $lastDay->copy()->endOfWeek(Carbon\Carbon::SATURDAY);
                        $currentDate = $startCalendar->copy();
                    @endphp

                    @while($currentDate <= $endCalendar)
                        @php
                            $dayAgendas = $agendas->filter(function($agenda) use ($currentDate) {
                                return Carbon\Carbon::parse($agenda->data_inicio)->format('Y-m-d') === $currentDate->format('Y-m-d');
                            });
                            $isCurrentMonth = $currentDate->month === $date->month;
                            $isToday = $currentDate->isToday();
                        @endphp
                        
                        <div class="min-h-[120px] border border-gray-200 rounded-lg p-2 {{ $isCurrentMonth ? 'bg-white' : 'bg-gray-50' }} {{ $isToday ? 'ring-2 ring-blue-500' : '' }} hover:bg-blue-50 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium {{ $isCurrentMonth ? 'text-gray-900' : 'text-gray-400' }} {{ $isToday ? '" style="color: var(--primary-color);" font-bold' : '' }}">
                                    {{ $currentDate->day }}
                                </span>
                                @if($dayAgendas->count() > 0)
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-medium">
                                        {{ $dayAgendas->count() }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="space-y-1">
                                @foreach($dayAgendas->take(3) as $agenda)
                                    <div class="text-xs p-2 rounded-md cursor-pointer transition-colors
                                        @if($agenda->status === 'confirmado') bg-green-100 text-green-800 hover:bg-green-200
                                        @elseif($agenda->status === 'pendente') bg-yellow-100 text-yellow-800 hover:bg-yellow-200
                                        @elseif($agenda->status === 'cancelado') bg-red-100 text-red-800 hover:bg-red-200
                                        @else bg-gray-100 text-gray-800 hover:bg-gray-200
                                        @endif"
                                        onclick="viewAgenda({{ $agenda->id }})">
                                        <div class="font-medium truncate">{{ $agenda->titulo }}</div>
                                        <div class="flex items-center mt-1">
                                            <i class="fas fa-clock mr-1 opacity-75"></i>
                                            {{ Carbon\Carbon::parse($agenda->data_inicio)->format('H:i') }}
                                            @if($agenda->tipo_reuniao === 'online')
                                                <i class="fas fa-video ml-2 opacity-75"></i>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                
                                @if($dayAgendas->count() > 3)
                                    <div class="text-xs text-gray-600 p-2 bg-gray-100 rounded-md text-center">
                                        +{{ $dayAgendas->count() - 3 }} mais
                                    </div>
                                @endif
                            </div>
                        </div>
                        @php $currentDate->addDay(); @endphp
                    @endwhile
                </div>
            </div>
        </div>

        <!-- Lista de Eventos do Dia Selecionado -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200" id="dayEvents" style="display: none;">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4" id="selectedDayTitle">Eventos do Dia</h3>
                <div id="selectedDayEvents" class="space-y-4">
                    <!-- Eventos serão carregados aqui via JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Visualização de Evento (reaproveitado da agenda) -->
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

@endsection

@push('scripts')
<script>
let currentMonth = '{{ $month }}';

function previousMonth() {
    const date = new Date(currentMonth + '-01');
    date.setMonth(date.getMonth() - 1);
    const newMonth = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
    window.location.href = `{{ route('dashboard.agenda.calendar') }}?month=${newMonth}`;
}

function nextMonth() {
    const date = new Date(currentMonth + '-01');
    date.setMonth(date.getMonth() + 1);
    const newMonth = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
    window.location.href = `{{ route('dashboard.agenda.calendar') }}?month=${newMonth}`;
}

function goToToday() {
    const today = new Date();
    const currentMonth = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0');
    window.location.href = `{{ route('dashboard.agenda.calendar') }}?month=${currentMonth}`;
}

function viewAgenda(id) {
    fetch(`/agenda/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const agenda = data.agenda;
                const details = `
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-2xl font-bold text-gray-900 mb-2">${agenda.titulo}</h4>
                            ${agenda.descricao ? `<p style="color: var(--secondary-color);">${agenda.descricao}</p>` : ''}
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Data e Hora</span>
                                    <div class="mt-1">
                                        <p class="text-gray-900 font-medium">
                                            <i class="fas fa-calendar mr-2 " style="color: var(--primary-color);""></i>
                                            ${new Date(agenda.data_inicio).toLocaleDateString('pt-BR')}
                                        </p>
                                        <p class="text-gray-900">
                                            <i class="fas fa-clock mr-2 " style="color: var(--primary-color);""></i>
                                            ${new Date(agenda.data_inicio).toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'})} - 
                                            ${new Date(agenda.data_fim).toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'})}
                                        </p>
                                    </div>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Tipo de Reunião</span>
                                    <p class="mt-1 text-gray-900 font-medium">
                                        <i class="fas ${agenda.tipo_reuniao === 'online' ? 'fa-video' : agenda.tipo_reuniao === 'presencial' ? 'fa-handshake' : 'fa-users'} mr-2 " style="color: var(--primary-color);""></i>
                                        ${agenda.tipo_reuniao.charAt(0).toUpperCase() + agenda.tipo_reuniao.slice(1)}
                                    </p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Status</span>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            ${agenda.status === 'confirmado' ? 'bg-green-100 text-green-800' : 
                                              agenda.status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : 
                                              agenda.status === 'cancelado' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'}">
                                            ${agenda.status.replace('_', ' ').charAt(0).toUpperCase() + agenda.status.replace('_', ' ').slice(1)}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                ${agenda.participantes && agenda.participantes.length > 0 ? `
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Participantes</span>
                                        <div class="mt-1 space-y-2">
                                            ${agenda.participantes.map(p => `
                                                <div class="flex items-center">
                                                    <i class="fas fa-user mr-2 " style="color: var(--primary-color);""></i>
                                                    <span class="text-gray-900">${p}</span>
                                                </div>
                                            `).join('')}
                                        </div>
                                    </div>
                                ` : ''}
                                
                                ${agenda.meet_link ? `
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Link da Reunião</span>
                                        <div class="mt-1">
                                            <a href="${agenda.meet_link}" target="_blank" class="inline-flex items-center " style="color: var(--primary-color);" hover:text-blue-800 font-medium">
                                                <i class="fab fa-google mr-2"></i>
                                                Abrir Google Meet
                                                <i class="fas fa-external-link-alt ml-2 text-sm"></i>
                                            </a>
                                        </div>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <button onclick="closeViewAgendaModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                                Fechar
                            </button>
                            <a href="/agenda/${agenda.id}/edit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark font-medium">
                                <i class="fas fa-edit mr-2"></i>
                                Editar
                            </a>
                        </div>
                    </div>
                `;
                
                document.getElementById('agendaDetails').innerHTML = details;
                document.getElementById('viewAgendaModal').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar agenda:', error);
            alert('Erro ao carregar detalhes do compromisso');
        });
}

function closeViewAgendaModal() {
    document.getElementById('viewAgendaModal').classList.add('hidden');
}

// Adicionar efeitos de hover nos dias do calendário
document.addEventListener('DOMContentLoaded', function() {
    const calendarDays = document.querySelectorAll('#calendarGrid > div');
    calendarDays.forEach(day => {
        day.addEventListener('click', function() {
            // Funcionalidade futura: mostrar eventos do dia selecionado
        });
    });
});
</script>
@endpush

@push('styles')
<style>
    /* Animações suaves */
    .transition-colors {
        transition: all 0.2s ease-in-out;
    }
    
    /* Hover effects para os cards de estatísticas */
    .bg-white:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease-in-out;
    }
    
    /* Estilo para o dia atual */
    .ring-2.ring-blue-500 {
        position: relative;
    }
    
    .ring-2.ring-blue-500::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(45deg, var(--primary-color), var(--primary-color));
        border-radius: 12px;
        z-index: -1;
    }
    
    /* Estilo para eventos no calendário */
    .space-y-1 > div {
        animation: fadeInUp 0.3s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Responsividade para mobile */
    @media (max-width: 768px) {
        .grid-cols-7 {
            font-size: 0.875rem;
        }
        
        .min-h-[120px] {
            min-height: 80px;
        }
        
        .text-3xl {
            font-size: 1.5rem;
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
