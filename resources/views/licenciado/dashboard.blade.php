@extends('layouts.licenciado')

@section('title', 'Dashboard')
@section('subtitle', 'Visão geral das suas atividades')

@section('content')
<x-dynamic-branding />
<div class="space-y-6">
    <!-- Welcome Message -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Bem-vindo, {{ $licenciado->name }}! 👋</h2>
                <p class="text-blue-100">Aqui está um resumo das suas atividades recentes</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-chart-line text-6xl text-blue-200"></i>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Estabelecimentos Ativos -->
        <div class="stat-card green">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Estabelecimentos</p>
                    <p class="text-3xl font-bold">{{ $stats->estabelecimentos_ativos }}</p>
                    <p class="text-green-200 text-xs mt-1">Ativos</p>
                </div>
                <div class="text-4xl text-green-200">
                    <i class="fas fa-store"></i>
                </div>
            </div>
        </div>

        <!-- Vendas do Mês -->
        <div class="stat-card blue">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Vendas do Mês</p>
                    <p class="text-3xl font-bold">R$ {{ number_format($stats->vendas_mes, 2, ',', '.') }}</p>
                    <p class="text-blue-200 text-xs mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>+{{ $stats->crescimento_vendas }}%
                    </p>
                </div>
                <div class="text-4xl text-blue-200">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>

        <!-- Transações -->
        <div class="stat-card orange">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-pink-100 text-sm font-medium">Transações</p>
                    <p class="text-3xl font-bold">{{ $stats->transacoes_mes }}</p>
                    <p class="text-pink-200 text-xs mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>+{{ $stats->crescimento_transacoes }}%
                    </p>
                </div>
                <div class="text-4xl text-pink-200">
                    <i class="fas fa-credit-card"></i>
                </div>
            </div>
        </div>

        <!-- Comissões -->
        <div class="stat-card purple">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-cyan-100 text-sm font-medium">Comissões</p>
                    <p class="text-3xl font-bold">R$ {{ number_format($stats->comissao_mes, 2, ',', '.') }}</p>
                    <p class="text-cyan-200 text-xs mt-1">Este mês</p>
                </div>
                <div class="text-4xl text-cyan-200">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Agendas de Hoje -->
        <div class="lg:col-span-2">
            <div class="card p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-day text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Agendas de Hoje</h3>
                            <p class="text-gray-600 text-sm">{{ \Carbon\Carbon::today()->locale('pt_BR')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('licenciado.agenda.calendar') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Ver calendário <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                @if($todayAgendas->count() > 0)
                    <div class="space-y-4">
                        @foreach($todayAgendas as $agenda)
                            @php
                                $startTime = \Carbon\Carbon::parse($agenda->data_inicio);
                                $endTime = \Carbon\Carbon::parse($agenda->data_fim);
                                $statusColors = [
                                    'confirmado' => 'border-green-200 bg-green-50',
                                    'aprovada' => 'border-green-200 bg-green-50',
                                    'pendente' => 'border-yellow-200 bg-yellow-50',
                                    'cancelado' => 'border-red-200 bg-red-50',
                                    'recusada' => 'border-red-200 bg-red-50',
                                    'automatica' => 'border-blue-200 bg-blue-50'
                                ];
                                $statusColor = $statusColors[$agenda->status_aprovacao] ?? 'border-gray-200 bg-gray-50';
                                
                                $statusBadgeColors = [
                                    'confirmado' => 'bg-green-100 text-green-800',
                                    'aprovada' => 'bg-green-100 text-green-800',
                                    'pendente' => 'bg-yellow-100 text-yellow-800',
                                    'cancelado' => 'bg-red-100 text-red-800',
                                    'recusada' => 'bg-red-100 text-red-800',
                                    'automatica' => 'bg-blue-100 text-blue-800'
                                ];
                                $statusBadgeColor = $statusBadgeColors[$agenda->status_aprovacao] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            
                            <div class="group relative border-2 {{ $statusColor }} rounded-xl p-4 hover:shadow-lg transition-all duration-200 cursor-pointer"
                                 onclick="showAgendaDetails({{ json_encode($agenda) }})">
                                
                                <!-- Indicador de Status -->
                                <div class="absolute -left-1 top-4 w-3 h-3 rounded-full
                                    {{ $agenda->status_aprovacao === 'confirmado' || $agenda->status_aprovacao === 'aprovada' ? 'bg-green-500' : 
                                       ($agenda->status_aprovacao === 'pendente' ? 'bg-yellow-500' : 
                                       ($agenda->status_aprovacao === 'cancelado' || $agenda->status_aprovacao === 'recusada' ? 'bg-red-500' : 'bg-blue-500')) }}">
                                </div>
                                
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <!-- Horário -->
                                        <div class="flex items-center mb-2">
                                            <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mr-3">
                                                <span class="text-sm font-bold text-gray-700">{{ $startTime->format('H:i') }}</span>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-lg font-semibold text-gray-900 mb-1">{{ $agenda->titulo }}</h4>
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    <span>{{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }}</span>
                                                    <span class="mx-2">•</span>
                                                    <span>{{ $startTime->diffInMinutes($endTime) }} min</span>
                                                    @if($agenda->tipo_reuniao === 'online')
                                                        <i class="fas fa-video text-blue-500 ml-2" title="Online"></i>
                                                    @elseif($agenda->tipo_reuniao === 'presencial')
                                                        <i class="fas fa-map-marker-alt text-green-500 ml-2" title="Presencial"></i>
                                                    @else
                                                        <i class="fas fa-users text-purple-500 ml-2" title="Híbrida"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Descrição -->
                                        @if($agenda->descricao)
                                            <p class="text-gray-600 text-sm line-clamp-2 mb-2">{{ $agenda->descricao }}</p>
                                        @endif
                                        
                                        <!-- Participantes -->
                                        @if($agenda->destinatario)
                                            <div class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-user mr-1"></i>
                                                <span>Com: {{ $agenda->destinatario->name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Status Badge -->
                                    <div class="ml-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusBadgeColor }}">
                                            @if($agenda->status_aprovacao === 'confirmado' || $agenda->status_aprovacao === 'aprovada')
                                                <i class="fas fa-check mr-1"></i>Confirmado
                                            @elseif($agenda->status_aprovacao === 'pendente')
                                                <i class="fas fa-clock mr-1"></i>Pendente
                                            @elseif($agenda->status_aprovacao === 'cancelado' || $agenda->status_aprovacao === 'recusada')
                                                <i class="fas fa-times mr-1"></i>Cancelado
                                            @else
                                                <i class="fas fa-calendar mr-1"></i>Automático
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Ações Rápidas (aparecem no hover) -->
                                <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <div class="flex space-x-2">
                                        @if($agenda->meet_link)
                                            <a href="{{ $agenda->meet_link }}" target="_blank" 
                                               onclick="event.stopPropagation()"
                                               class="w-8 h-8 bg-blue-500 hover:bg-blue-600 text-white rounded-full flex items-center justify-center transition-colors duration-200"
                                               title="Abrir reunião">
                                                <i class="fas fa-video text-xs"></i>
                                            </a>
                                        @endif
                                        <button onclick="event.stopPropagation(); editAgenda({{ $agenda->id }})" 
                                                class="w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-full flex items-center justify-center transition-colors duration-200"
                                                title="Editar">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Estado Vazio -->
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-day text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Nenhum compromisso hoje</h4>
                        <p class="text-gray-600 mb-4">Você não tem compromissos agendados para hoje.</p>
                        <a href="{{ route('licenciado.agenda.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-plus mr-2"></i>
                            Agendar Compromisso
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-6">
            <!-- Quick Actions Card -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Ações Rápidas</h3>
                <div class="space-y-3">
                    <a href="{{ route('licenciado.estabelecimentos') }}" 
                       class="w-full flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                        <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                        <span class="text-blue-800 font-medium">Novo Estabelecimento</span>
                    </a>
                    <a href="{{ route('licenciado.vendas') }}" 
                       class="w-full flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <i class="fas fa-chart-line text-green-600 mr-3"></i>
                        <span class="text-green-800 font-medium">Ver Vendas</span>
                    </a>
                    <a href="{{ route('licenciado.relatorios') }}" 
                       class="w-full flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                        <i class="fas fa-file-download text-purple-600 mr-3"></i>
                        <span class="text-purple-800 font-medium">Baixar Relatório</span>
                    </a>
                    <a href="{{ route('licenciado.suporte') }}" 
                       class="w-full flex items-center p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
                        <i class="fas fa-headset text-orange-600 mr-3"></i>
                        <span class="text-orange-800 font-medium">Suporte</span>
                    </a>
                </div>
            </div>

            <!-- Performance Card -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Performance</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Meta Mensal</span>
                            <span class="font-medium">78%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full" style="width: 78%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">R$ 23.500 de R$ 30.000</p>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Transações</span>
                            <span class="font-medium">85%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">235 de 275 esperadas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Plans -->
    <div class="card p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-800">Planos Disponíveis</h3>
            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Ver todos <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
            @foreach($availablePlans as $plan)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <h4 class="font-semibold text-gray-800 mb-2">{{ $plan->nome }}</h4>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Débito:</span>
                        <span class="font-medium text-green-600">{{ $plan->comissao_media }}%</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Status:</span>
                        <span class="status-badge {{ $plan->ativo ? 'ativo' : 'inativo' }}">
                            {{ $plan->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                </div>
                <button class="w-full mt-3 bg-blue-600 text-white py-2 px-4 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors">
                    Ver Detalhes
                </button>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard do Licenciado carregado com sucesso!');
});

// Função para mostrar detalhes da agenda
function showAgendaDetails(agenda) {
    const statusText = {
        'confirmado': 'Confirmado',
        'aprovada': 'Aprovada',
        'pendente': 'Pendente',
        'cancelado': 'Cancelado',
        'recusada': 'Recusada',
        'automatica': 'Automática'
    };
    
    const typeText = {
        'online': 'Online',
        'presencial': 'Presencial',
        'hibrida': 'Híbrida'
    };
    
    const startTime = new Date(agenda.data_inicio);
    const endTime = new Date(agenda.data_fim);
    
    let detailsHtml = `
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" onclick="closeAgendaModal()">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Detalhes do Compromisso</h3>
                    <button onclick="closeAgendaModal()" class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-full transition-colors duration-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">${agenda.titulo}</h4>
                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <i class="fas fa-calendar mr-2"></i>
                            <span>${startTime.toLocaleDateString('pt-BR')}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <i class="fas fa-clock mr-2"></i>
                            <span>${startTime.toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'})} - ${endTime.toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'})}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <i class="fas fa-${agenda.tipo_reuniao === 'online' ? 'video' : agenda.tipo_reuniao === 'presencial' ? 'map-marker-alt' : 'users'} mr-2"></i>
                            <span>${typeText[agenda.tipo_reuniao] || agenda.tipo_reuniao}</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${
                                agenda.status_aprovacao === 'confirmado' || agenda.status_aprovacao === 'aprovada' ? 'bg-green-100 text-green-800' :
                                agenda.status_aprovacao === 'pendente' ? 'bg-yellow-100 text-yellow-800' :
                                agenda.status_aprovacao === 'cancelado' || agenda.status_aprovacao === 'recusada' ? 'bg-red-100 text-red-800' :
                                'bg-blue-100 text-blue-800'
                            }">
                                ${statusText[agenda.status_aprovacao] || agenda.status_aprovacao}
                            </span>
                        </div>
                    </div>
                    
                    ${agenda.descricao ? `
                        <div>
                            <h5 class="font-medium text-gray-900 mb-2">Descrição:</h5>
                            <p class="text-gray-600 text-sm bg-gray-50 rounded-lg p-3">${agenda.descricao}</p>
                        </div>
                    ` : ''}
                    
                    ${agenda.destinatario ? `
                        <div>
                            <h5 class="font-medium text-gray-900 mb-2">Participante:</h5>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-user mr-2"></i>
                                <span>${agenda.destinatario.name}</span>
                            </div>
                        </div>
                    ` : ''}
                    
                    ${agenda.meet_link ? `
                        <div>
                            <h5 class="font-medium text-gray-900 mb-2">Link da Reunião:</h5>
                            <a href="${agenda.meet_link}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Abrir Link
                            </a>
                        </div>
                    ` : ''}
                    
                    <div class="flex gap-2 pt-4 border-t border-gray-200">
                        <button onclick="editAgenda(${agenda.id})" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>Editar
                        </button>
                        <button onclick="closeAgendaModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', detailsHtml);
}

// Função para fechar modal de detalhes
function closeAgendaModal() {
    const modal = document.querySelector('.fixed.inset-0.bg-black');
    if (modal) {
        modal.remove();
    }
}

// Função para editar agenda
function editAgenda(id) {
    window.location.href = `/licenciado/agenda/${id}/edit`;
}
</script>
@endpush
