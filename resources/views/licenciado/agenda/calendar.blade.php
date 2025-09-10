@extends('layouts.licenciado')

@section('title', 'Calendário')
@section('subtitle', 'Visualização mensal dos compromissos')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-calendar text-purple-600 mr-3"></i>
                    Calendário - {{ $date->format('F Y') }}
                </h2>
                <p class="text-gray-600">Visualização mensal dos seus compromissos</p>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Navegação de Mês -->
                <div class="flex items-center bg-gray-50 rounded-lg p-1">
                    <a href="{{ route('licenciado.agenda.calendar', ['month' => $date->copy()->subMonth()->format('Y-m')]) }}" 
                       class="p-2 text-gray-600 hover:text-purple-600 hover:bg-white rounded-md transition-colors">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <span class="px-4 py-2 text-sm font-medium text-gray-900">
                        {{ $date->format('F Y') }}
                    </span>
                    <a href="{{ route('licenciado.agenda.calendar', ['month' => $date->copy()->addMonth()->format('Y-m')]) }}" 
                       class="p-2 text-gray-600 hover:text-purple-600 hover:bg-white rounded-md transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                
                <!-- Botão Nova Reunião -->
                <a href="{{ route('licenciado.agenda.create') }}" 
                   class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Nova Reunião
                </a>
            </div>
        </div>
    </div>

    <!-- Calendário -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Cabeçalho dos Dias da Semana -->
        <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200">
            @foreach(['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'] as $dia)
                <div class="p-4 text-center text-sm font-semibold text-gray-700 border-r border-gray-200 last:border-r-0">
                    {{ $dia }}
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
                    $dayAgendas = $agendas->filter(function($agenda) use ($currentDate) {
                        return \Carbon\Carbon::parse($agenda->data_inicio)->format('Y-m-d') === $currentDate->format('Y-m-d');
                    });
                @endphp

                <div class="min-h-[120px] p-2 border-r border-b border-gray-200 last:border-r-0 {{ !$isCurrentMonth ? 'bg-gray-50' : '' }} {{ $isToday ? 'bg-blue-50' : '' }}">
                    <!-- Número do Dia -->
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium {{ !$isCurrentMonth ? 'text-gray-400' : ($isToday ? 'text-blue-600 font-bold' : 'text-gray-900') }}">
                            {{ $currentDate->day }}
                        </span>
                        @if($isToday)
                            <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                        @endif
                    </div>

                    <!-- Agendas do Dia -->
                    <div class="space-y-1">
                        @foreach($dayAgendas->take(3) as $agenda)
                            <div class="text-xs p-1.5 rounded-md cursor-pointer hover:shadow-sm transition-shadow
                                {{ $agenda->status_aprovacao === 'pendente' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : 
                                   ($agenda->status_aprovacao === 'aprovada' ? 'bg-green-100 text-green-800 border border-green-200' : 
                                   ($agenda->status_aprovacao === 'recusada' ? 'bg-red-100 text-red-800 border border-red-200' : 
                                   'bg-purple-100 text-purple-800 border border-purple-200')) }}"
                                 title="{{ $agenda->titulo }} - {{ \Carbon\Carbon::parse($agenda->data_inicio)->format('H:i') }}">
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-clock text-xs"></i>
                                    <span class="font-medium truncate">{{ \Carbon\Carbon::parse($agenda->data_inicio)->format('H:i') }}</span>
                                </div>
                                <div class="truncate font-medium">{{ $agenda->titulo }}</div>
                                @if($agenda->destinatario_id === Auth::id() && $agenda->status_aprovacao === 'pendente')
                                    <div class="flex gap-1 mt-1">
                                        <button onclick="aprovarAgenda({{ $agenda->id }})" 
                                                class="text-xs bg-green-600 text-white px-1.5 py-0.5 rounded hover:bg-green-700"
                                                title="Aprovar">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button onclick="recusarAgenda({{ $agenda->id }})" 
                                                class="text-xs bg-red-600 text-white px-1.5 py-0.5 rounded hover:bg-red-700"
                                                title="Recusar">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        @if($dayAgendas->count() > 3)
                            <div class="text-xs text-gray-500 text-center py-1">
                                +{{ $dayAgendas->count() - 3 }} mais
                            </div>
                        @endif
                    </div>
                </div>

                @php $currentDate->addDay(); @endphp
            @endwhile
        </div>
    </div>

    <!-- Legenda -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Legenda</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-purple-100 border border-purple-200 rounded"></div>
                <span class="text-sm text-gray-700">Automática</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-yellow-100 border border-yellow-200 rounded"></div>
                <span class="text-sm text-gray-700">Pendente</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-green-100 border border-green-200 rounded"></div>
                <span class="text-sm text-gray-700">Aprovada</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-red-100 border border-red-200 rounded"></div>
                <span class="text-sm text-gray-700">Recusada</span>
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
    }, 3000);
}
</script>
@endsection
