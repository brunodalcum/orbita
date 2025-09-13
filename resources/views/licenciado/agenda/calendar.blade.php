@extends('layouts.licenciado')

@section('title', 'Calendário')
@section('subtitle', 'Visualização mensal dos compromissos')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Moderno -->
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-3xl shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white opacity-10 rounded-full"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-24 h-24 bg-white opacity-10 rounded-full"></div>
            
            <div class="relative px-8 py-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-calendar-alt text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white mb-2">
                                Calendário de Compromissos
                            </h1>
                            <p class="text-indigo-100 text-lg">
                                {{ $date->locale('pt_BR')->isoFormat('MMMM [de] YYYY') }}
                            </p>
                            <div class="flex items-center mt-2 text-indigo-200">
                                <i class="fas fa-clock mr-2"></i>
                                <span class="text-sm">{{ $agendas->count() }} compromissos este mês</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <!-- Navegação de Mês Moderna -->
                        <div class="flex items-center bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl p-2">
                            <a href="{{ route('licenciado.agenda.calendar', ['month' => $date->copy()->subMonth()->format('Y-m')]) }}" 
                               class="p-3 text-white hover:bg-white hover:bg-opacity-20 rounded-xl transition-all duration-200 hover:scale-110">
                                <i class="fas fa-chevron-left text-lg"></i>
                            </a>
                            <div class="px-6 py-2 text-white font-semibold">
                                {{ $date->locale('pt_BR')->isoFormat('MMM YYYY') }}
                            </div>
                            <a href="{{ route('licenciado.agenda.calendar', ['month' => $date->copy()->addMonth()->format('Y-m')]) }}" 
                               class="p-3 text-white hover:bg-white hover:bg-opacity-20 rounded-xl transition-all duration-200 hover:scale-110">
                                <i class="fas fa-chevron-right text-lg"></i>
                            </a>
                        </div>
                        
                        <!-- Botão Nova Reunião Moderno -->
                        <a href="{{ route('licenciado.agenda.create') }}" 
                           class="group relative inline-flex items-center px-8 py-4 bg-white text-indigo-600 font-bold rounded-2xl shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <i class="fas fa-plus mr-3 text-lg relative z-10 group-hover:text-white transition-colors duration-300"></i>
                            <span class="relative z-10 group-hover:text-white transition-colors duration-300">Nova Reunião</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendário Moderno -->
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden backdrop-blur-sm">
            <!-- Cabeçalho dos Dias da Semana -->
            <div class="grid grid-cols-7 bg-gradient-to-r from-gray-50 to-gray-100">
                @foreach(['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'] as $index => $dia)
                    <div class="p-6 text-center border-r border-gray-200 last:border-r-0 {{ $index == 0 || $index == 6 ? 'bg-gradient-to-b from-indigo-50 to-purple-50' : '' }}">
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">
                            {{ substr($dia, 0, 3) }}
                        </div>
                        <div class="text-sm font-bold text-gray-800">
                            {{ $dia }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Dias do Calendário -->
            <div class="grid grid-cols-7">
                @php
                    $startOfMonth = $date->copy()->startOfMonth();
                    $endOfMonth = $date->copy()->endOfMonth();
                    $startOfCalendar = $startOfMonth->copy()->startOfWeek();
                    $endOfCalendar = $endOfMonth->copy()->endOfWeek();
                    $currentDate = $startOfCalendar->copy();
                @endphp

                @while($currentDate <= $endOfCalendar)
                    @php
                        $isCurrentMonth = $currentDate->month === $date->month;
                        $isToday = $currentDate->isToday();
                        $isWeekend = $currentDate->isWeekend();
                        $dayAgendas = $agendas->filter(function($agenda) use ($currentDate) {
                            return \Carbon\Carbon::parse($agenda->data_inicio)->format('Y-m-d') === $currentDate->format('Y-m-d');
                        });
                    @endphp

                    <div class="group relative min-h-[140px] p-4 border-r border-b border-gray-100 last:border-r-0 transition-all duration-300 hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 hover:shadow-lg
                        {{ !$isCurrentMonth ? 'bg-gray-50/50' : '' }}
                        {{ $isToday ? 'bg-gradient-to-br from-indigo-100 to-purple-100 ring-2 ring-indigo-300 ring-opacity-50' : '' }}
                        {{ $isWeekend && $isCurrentMonth ? 'bg-gradient-to-br from-blue-50 to-indigo-50' : '' }}">
                        
                        <!-- Número do Dia -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="relative">
                                @if($isToday)
                                    <div class="absolute -inset-2 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full animate-pulse opacity-20"></div>
                                    <span class="relative inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-bold rounded-full shadow-lg">
                                        {{ $currentDate->day }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center w-8 h-8 text-sm font-semibold rounded-full transition-all duration-200
                                        {{ !$isCurrentMonth ? 'text-gray-400' : 'text-gray-700 group-hover:bg-indigo-100 group-hover:text-indigo-700' }}">
                                        {{ $currentDate->day }}
                                    </span>
                                @endif
                            </div>
                            
                            @if($dayAgendas->count() > 0)
                                <div class="flex items-center space-x-1">
                                    <div class="w-2 h-2 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full animate-pulse"></div>
                                    <span class="text-xs font-medium text-indigo-600">{{ $dayAgendas->count() }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Agendas do Dia -->
                        <div class="space-y-2">
                            @foreach($dayAgendas->take(2) as $agenda)
                                <div class="group/agenda relative p-3 rounded-xl cursor-pointer transform transition-all duration-200 hover:scale-105 hover:shadow-md
                                    {{ $agenda->status_aprovacao === 'pendente' ? 'bg-gradient-to-r from-amber-100 to-yellow-100 border border-amber-200 hover:from-amber-200 hover:to-yellow-200' : 
                                       ($agenda->status_aprovacao === 'aprovada' ? 'bg-gradient-to-r from-emerald-100 to-green-100 border border-emerald-200 hover:from-emerald-200 hover:to-green-200' : 
                                       ($agenda->status_aprovacao === 'recusada' ? 'bg-gradient-to-r from-red-100 to-rose-100 border border-red-200 hover:from-red-200 hover:to-rose-200' : 
                                       'bg-gradient-to-r from-indigo-100 to-purple-100 border border-indigo-200 hover:from-indigo-200 hover:to-purple-200')) }}"
                                     title="{{ $agenda->titulo }} - {{ \Carbon\Carbon::parse($agenda->data_inicio)->format('H:i') }}">
                                    
                                    <!-- Horário -->
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-2 h-2 rounded-full
                                                {{ $agenda->status_aprovacao === 'pendente' ? 'bg-amber-500' : 
                                                   ($agenda->status_aprovacao === 'aprovada' ? 'bg-emerald-500' : 
                                                   ($agenda->status_aprovacao === 'recusada' ? 'bg-red-500' : 'bg-indigo-500')) }}">
                                            </div>
                                            <span class="text-xs font-bold
                                                {{ $agenda->status_aprovacao === 'pendente' ? 'text-amber-700' : 
                                                   ($agenda->status_aprovacao === 'aprovada' ? 'text-emerald-700' : 
                                                   ($agenda->status_aprovacao === 'recusada' ? 'text-red-700' : 'text-indigo-700')) }}">
                                                {{ \Carbon\Carbon::parse($agenda->data_inicio)->format('H:i') }}
                                            </span>
                                        </div>
                                        @if($agenda->tipo_reuniao === 'online')
                                            <i class="fas fa-video text-xs text-blue-500"></i>
                                        @elseif($agenda->tipo_reuniao === 'presencial')
                                            <i class="fas fa-map-marker-alt text-xs text-green-500"></i>
                                        @else
                                            <i class="fas fa-users text-xs text-purple-500"></i>
                                        @endif
                                    </div>
                                    
                                    <!-- Título -->
                                    <div class="text-xs font-semibold truncate mb-2
                                        {{ $agenda->status_aprovacao === 'pendente' ? 'text-amber-800' : 
                                           ($agenda->status_aprovacao === 'aprovada' ? 'text-emerald-800' : 
                                           ($agenda->status_aprovacao === 'recusada' ? 'text-red-800' : 'text-indigo-800')) }}">
                                        {{ $agenda->titulo }}
                                    </div>
                                    
                                    <!-- Botões de Ação -->
                                    @if($agenda->destinatario_id === Auth::id() && $agenda->status_aprovacao === 'pendente')
                                        <div class="flex gap-1 opacity-0 group-hover/agenda:opacity-100 transition-opacity duration-200">
                                            <button onclick="aprovarAgenda({{ $agenda->id }})" 
                                                    class="flex-1 text-xs bg-emerald-500 hover:bg-emerald-600 text-white px-2 py-1 rounded-lg transition-colors duration-200 font-medium"
                                                    title="Aprovar">
                                                <i class="fas fa-check mr-1"></i>Aprovar
                                            </button>
                                            <button onclick="recusarAgenda({{ $agenda->id }})" 
                                                    class="flex-1 text-xs bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded-lg transition-colors duration-200 font-medium"
                                                    title="Recusar">
                                                <i class="fas fa-times mr-1"></i>Recusar
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                            @if($dayAgendas->count() > 2)
                                <div class="text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 border border-indigo-200">
                                        <i class="fas fa-plus mr-1"></i>
                                        {{ $dayAgendas->count() - 2 }} mais
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @php $currentDate->addDay(); @endphp
                @endwhile
            </div>
        </div>
    </div>

        <!-- Estatísticas e Legenda -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Estatísticas do Mês -->
            <div class="bg-gradient-to-br from-white to-indigo-50 rounded-3xl shadow-xl border border-indigo-100 p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center mr-4">
                        <i class="fas fa-chart-pie text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Estatísticas do Mês</h3>
                </div>
                
                <div class="grid grid-cols-2 gap-6">
                    @php
                        $totalAgendas = $agendas->count();
                        $pendentes = $agendas->where('status_aprovacao', 'pendente')->count();
                        $aprovadas = $agendas->where('status_aprovacao', 'aprovada')->count();
                        $recusadas = $agendas->where('status_aprovacao', 'recusada')->count();
                        $automaticas = $agendas->where('status_aprovacao', '!=', 'pendente')->where('status_aprovacao', '!=', 'aprovada')->where('status_aprovacao', '!=', 'recusada')->count();
                    @endphp
                    
                    <div class="text-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="text-3xl font-bold text-indigo-600 mb-2">{{ $totalAgendas }}</div>
                        <div class="text-sm font-medium text-gray-600">Total de Compromissos</div>
                    </div>
                    
                    <div class="text-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="text-3xl font-bold text-amber-600 mb-2">{{ $pendentes }}</div>
                        <div class="text-sm font-medium text-gray-600">Aguardando Aprovação</div>
                    </div>
                    
                    <div class="text-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="text-3xl font-bold text-emerald-600 mb-2">{{ $aprovadas }}</div>
                        <div class="text-sm font-medium text-gray-600">Aprovadas</div>
                    </div>
                    
                    <div class="text-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="text-3xl font-bold text-red-600 mb-2">{{ $recusadas }}</div>
                        <div class="text-sm font-medium text-gray-600">Recusadas</div>
                    </div>
                </div>
            </div>
            
            <!-- Legenda Moderna -->
            <div class="bg-gradient-to-br from-white to-purple-50 rounded-3xl shadow-xl border border-purple-100 p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mr-4">
                        <i class="fas fa-palette text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Legenda de Status</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                        <div class="w-6 h-6 bg-gradient-to-r from-indigo-100 to-purple-100 border-2 border-indigo-200 rounded-xl mr-4 flex items-center justify-center">
                            <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Automática</div>
                            <div class="text-sm text-gray-600">Compromissos criados automaticamente</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                        <div class="w-6 h-6 bg-gradient-to-r from-amber-100 to-yellow-100 border-2 border-amber-200 rounded-xl mr-4 flex items-center justify-center">
                            <div class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Pendente</div>
                            <div class="text-sm text-gray-600">Aguardando sua aprovação</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                        <div class="w-6 h-6 bg-gradient-to-r from-emerald-100 to-green-100 border-2 border-emerald-200 rounded-xl mr-4 flex items-center justify-center">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Aprovada</div>
                            <div class="text-sm text-gray-600">Compromissos confirmados</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                        <div class="w-6 h-6 bg-gradient-to-r from-red-100 to-rose-100 border-2 border-red-200 rounded-xl mr-4 flex items-center justify-center">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Recusada</div>
                            <div class="text-sm text-gray-600">Compromissos rejeitados</div>
                        </div>
                    </div>
                </div>
                
                <!-- Tipos de Reunião -->
                <div class="mt-8 pt-6 border-t border-purple-200">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Tipos de Reunião</h4>
                    <div class="flex items-center justify-around">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-video text-blue-600"></i>
                            </div>
                            <div class="text-xs font-medium text-gray-600">Online</div>
                        </div>
                        <div class="text-center">
                            <div class="w-10 h-10 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-map-marker-alt text-green-600"></i>
                            </div>
                            <div class="text-xs font-medium text-gray-600">Presencial</div>
                        </div>
                        <div class="text-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-users text-purple-600"></i>
                            </div>
                            <div class="text-xs font-medium text-gray-600">Híbrida</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
// Função para aprovar agenda
function aprovarAgenda(agendaId) {
    if (confirm('Tem certeza que deseja aprovar esta agenda?')) {
        fetch(`/licenciado/agenda/${agendaId}/aprovar`, {
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
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao aprovar agenda', 'error');
        });
    }
}

// Função para recusar agenda
function recusarAgenda(agendaId) {
    const motivo = prompt('Motivo da recusa (opcional):');
    if (motivo !== null) {
        fetch(`/licenciado/agenda/${agendaId}/recusar`, {
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
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao recusar agenda', 'error');
        });
    }
}

// Função para mostrar toast moderno
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-6 right-6 z-50 px-6 py-4 rounded-2xl shadow-2xl text-white transform transition-all duration-500 translate-x-full backdrop-blur-sm ${
        type === 'success' ? 'bg-gradient-to-r from-emerald-500 to-green-500' : 
        type === 'error' ? 'bg-gradient-to-r from-red-500 to-rose-500' : 
        'bg-gradient-to-r from-indigo-500 to-purple-500'
    }`;
    toast.innerHTML = `
        <div class="flex items-center">
            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'} text-sm"></i>
            </div>
            <div>
                <div class="font-semibold">${message}</div>
                <div class="text-xs opacity-80">
                    ${type === 'success' ? 'Operação realizada com sucesso' : 
                      type === 'error' ? 'Ocorreu um erro na operação' : 
                      'Informação do sistema'}
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animação de entrada
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
        toast.classList.add('scale-105');
        setTimeout(() => toast.classList.remove('scale-105'), 200);
    }, 100);
    
    // Animação de saída
    setTimeout(() => {
        toast.classList.add('translate-x-full', 'scale-95', 'opacity-0');
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 500);
    }, 4000);
}

// Adicionar animações de carregamento da página
document.addEventListener('DOMContentLoaded', function() {
    // Animar entrada dos elementos
    const elements = document.querySelectorAll('.group');
    elements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        setTimeout(() => {
            el.style.transition = 'all 0.6s ease-out';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, index * 50);
    });
});
</script>
@endsection
