@extends('layouts.licenciado')

@section('title', 'Calendário')
@section('subtitle', 'Visualização completa dos compromissos')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Inter', sans-serif; }
    .calendar-modern { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); }
    .glass-effect { backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.8); }
    .status-confirmed { background: linear-gradient(135deg, #10b981, #059669); }
    .status-pending { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .status-cancelled { background: linear-gradient(135deg, #ef4444, #dc2626); }
    .hover-scale { transition: all 0.3s ease; }
    .hover-scale:hover { transform: scale(1.02); }
</style>
@endpush

@section('content')
<div class="calendar-modern min-h-screen py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Moderno -->
        <div class="glass-effect rounded-3xl shadow-xl border border-white/20 p-8 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                
                <!-- Título e Info -->
                <div class="flex items-center space-x-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-alt text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Calendário de Compromissos</h1>
                        <p class="text-gray-600 text-lg">
                            @if($view === 'day')
                                {{ $date->locale('pt_BR')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                            @elseif($view === 'week')
                                Semana de {{ $startOfWeek->format('d/m') }} a {{ $endOfWeek->format('d/m/Y') }}
                            @else
                                {{ $date->locale('pt_BR')->isoFormat('MMMM [de] YYYY') }}
                            @endif
                        </p>
                        <div class="flex items-center mt-2 text-gray-500">
                            <i class="fas fa-clock mr-2"></i>
                            <span class="text-sm">{{ $agendas->count() }} compromissos</span>
                        </div>
                    </div>
                </div>
                
                <!-- Controles -->
                <div class="flex items-center gap-4">
                    
                    <!-- Seletor de Visualização -->
                    <div class="flex bg-white rounded-2xl p-1 shadow-lg border border-gray-200">
                        <a href="{{ route('licenciado.agenda.calendar', ['view' => 'day', 'date' => $date->format('Y-m-d')]) }}" 
                           class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ $view === 'day' ? 'bg-blue-500 text-white shadow-md' : 'text-gray-600 hover:text-blue-600' }}">
                            <i class="fas fa-calendar-day mr-2"></i>Dia
                        </a>
                        <a href="{{ route('licenciado.agenda.calendar', ['view' => 'week', 'date' => $date->format('Y-m-d')]) }}" 
                           class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ $view === 'week' ? 'bg-blue-500 text-white shadow-md' : 'text-gray-600 hover:text-blue-600' }}">
                            <i class="fas fa-calendar-week mr-2"></i>Semana
                        </a>
                        <a href="{{ route('licenciado.agenda.calendar', ['view' => 'month', 'date' => $date->format('Y-m-d')]) }}" 
                           class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ $view === 'month' ? 'bg-blue-500 text-white shadow-md' : 'text-gray-600 hover:text-blue-600' }}">
                            <i class="fas fa-calendar mr-2"></i>Mês
                        </a>
                    </div>
                    
                    <!-- Navegação -->
                    <div class="flex bg-white rounded-2xl p-1 shadow-lg border border-gray-200">
                        @if($view === 'day')
                            <a href="{{ route('licenciado.agenda.calendar', ['view' => 'day', 'date' => $date->copy()->subDay()->format('Y-m-d')]) }}" 
                               class="p-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <div class="px-4 py-3 text-sm font-medium text-gray-900">Hoje</div>
                            <a href="{{ route('licenciado.agenda.calendar', ['view' => 'day', 'date' => $date->copy()->addDay()->format('Y-m-d')]) }}" 
                               class="p-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @elseif($view === 'week')
                            <a href="{{ route('licenciado.agenda.calendar', ['view' => 'week', 'date' => $date->copy()->subWeek()->format('Y-m-d')]) }}" 
                               class="p-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <div class="px-4 py-3 text-sm font-medium text-gray-900">Esta Semana</div>
                            <a href="{{ route('licenciado.agenda.calendar', ['view' => 'week', 'date' => $date->copy()->addWeek()->format('Y-m-d')]) }}" 
                               class="p-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <a href="{{ route('licenciado.agenda.calendar', ['view' => 'month', 'date' => $date->copy()->subMonth()->format('Y-m-d')]) }}" 
                               class="p-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <div class="px-4 py-3 text-sm font-medium text-gray-900">Este Mês</div>
                            <a href="{{ route('licenciado.agenda.calendar', ['view' => 'month', 'date' => $date->copy()->addMonth()->format('Y-m-d')]) }}" 
                               class="p-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @endif
                    </div>
                    
                    <!-- Botão Novo Compromisso -->
                    <a href="{{ route('licenciado.agenda.create') }}" 
                       class="group relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-blue-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <i class="fas fa-plus mr-2 relative z-10"></i>
                        <span class="relative z-10">Novo Compromisso</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Conteúdo do Calendário -->
        @if($view === 'day')
            @include('licenciado.agenda.partials.day-view')
        @elseif($view === 'week')
            @include('licenciado.agenda.partials.week-view')
        @else
            @include('licenciado.agenda.partials.month-view')
        @endif
        
    </div>
</div>

<!-- Modal de Detalhes -->
<div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 backdrop-blur-sm">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-300 scale-95" id="modalContent">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Detalhes do Compromisso</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-full transition-colors duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="eventDetails">
                <!-- Conteúdo será carregado via JavaScript -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Função para abrir modal de detalhes
function showEventDetails(agenda) {
    const statusColors = {
        'confirmado': 'text-green-600 bg-green-100',
        'pendente': 'text-yellow-600 bg-yellow-100',
        'cancelado': 'text-red-600 bg-red-100',
        'aprovada': 'text-green-600 bg-green-100',
        'recusada': 'text-red-600 bg-red-100',
        'automatica': 'text-blue-600 bg-blue-100'
    };
    
    const statusText = {
        'confirmado': 'Confirmado',
        'pendente': 'Pendente',
        'cancelado': 'Cancelado',
        'aprovada': 'Aprovada',
        'recusada': 'Recusada',
        'automatica': 'Automática'
    };
    
    const typeIcons = {
        'online': 'fas fa-video text-blue-500',
        'presencial': 'fas fa-map-marker-alt text-green-500',
        'hibrida': 'fas fa-users text-purple-500'
    };
    
    document.getElementById('eventDetails').innerHTML = `
        <div class="space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-1">${agenda.titulo}</h4>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="${typeIcons[agenda.tipo_reuniao] || 'fas fa-calendar'} mr-2"></i>
                        <span class="capitalize">${agenda.tipo_reuniao}</span>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-medium ${statusColors[agenda.status_aprovacao] || 'text-gray-600 bg-gray-100'}">
                    ${statusText[agenda.status_aprovacao] || agenda.status_aprovacao}
                </span>
            </div>
            
            <div class="bg-gray-50 rounded-2xl p-4">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-gray-500 mb-1">Data</div>
                        <div class="font-medium">${new Date(agenda.data_inicio).toLocaleDateString('pt-BR')}</div>
                    </div>
                    <div>
                        <div class="text-gray-500 mb-1">Horário</div>
                        <div class="font-medium">${new Date(agenda.data_inicio).toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'})} - ${new Date(agenda.data_fim).toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'})}</div>
                    </div>
                </div>
            </div>
            
            ${agenda.descricao ? `
                <div>
                    <div class="text-gray-500 text-sm mb-2">Descrição</div>
                    <div class="text-gray-900 bg-gray-50 rounded-2xl p-4">${agenda.descricao}</div>
                </div>
            ` : ''}
            
            ${agenda.meet_link ? `
                <div>
                    <div class="text-gray-500 text-sm mb-2">Link da Reunião</div>
                    <a href="${agenda.meet_link}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-2 rounded-xl text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Abrir Link
                    </a>
                </div>
            ` : ''}
            
            <div class="flex gap-2 pt-4 border-t border-gray-200">
                <button onclick="editEvent(${agenda.id})" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-xl font-medium transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>Editar
                </button>
                <button onclick="deleteEvent(${agenda.id})" class="flex-1 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl font-medium transition-colors duration-200">
                    <i class="fas fa-trash mr-2"></i>Excluir
                </button>
            </div>
        </div>
    `;
    
    const modal = document.getElementById('eventModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }, 10);
}

// Função para fechar modal
function closeModal() {
    const modal = document.getElementById('eventModal');
    const modalContent = document.getElementById('modalContent');
    
    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Função para editar evento
function editEvent(id) {
    window.location.href = `/licenciado/agenda/${id}/edit`;
}

// Função para excluir evento
function deleteEvent(id) {
    if (confirm('Tem certeza que deseja excluir este compromisso?')) {
        fetch(`/licenciado/agenda/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Compromisso excluído com sucesso!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('Erro ao excluir compromisso', 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao excluir compromisso', 'error');
        });
    }
}

// Função para mostrar toast
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-6 right-6 z-50 px-6 py-4 rounded-2xl shadow-2xl text-white transform transition-all duration-500 translate-x-full ${
        type === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-500' : 
        type === 'error' ? 'bg-gradient-to-r from-red-500 to-rose-500' : 
        'bg-gradient-to-r from-blue-500 to-indigo-500'
    }`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'} mr-3"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => document.body.removeChild(toast), 500);
    }, 3000);
}

// Fechar modal ao clicar fora
document.getElementById('eventModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endpush
@endsection
