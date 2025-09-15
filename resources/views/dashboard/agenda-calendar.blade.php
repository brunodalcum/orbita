@extends('layouts.dashboard')

@section('title', 'Calendário')

@section('content')
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
                    <a href="{{ route('dashboard.agenda.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center">
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
                            <a href="/agenda/${agenda.id}/edit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
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
        background: linear-gradient(45deg, #3b82f6, #1d4ed8);
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
