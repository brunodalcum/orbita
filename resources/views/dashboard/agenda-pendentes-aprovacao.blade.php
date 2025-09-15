@extends('layouts.dashboard')

@section('title', 'Aprovação de Compromissos')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-clock text-orange-600 mr-4"></i>
                        Aprovação de Compromissos
                    </h1>
                    <p style="color: var(--secondary-color);">Gerencie as solicitações de reuniões pendentes de aprovação</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-4 py-2">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-orange-500 rounded-full animate-pulse"></div>
                            <span class="text-sm font-medium text-gray-700">
                                {{ $agendas->count() }} pendente(s)
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-3 " style="color: var(--accent-color);""></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-exclamation-triangle mr-3 text-red-600"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Lista de Agendas Pendentes -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Solicitações Pendentes
                    </h2>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">{{ $agendas->count() }} solicitação(ões)</span>
                    </div>
                </div>
            </div>

            @if($agendas->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($agendas as $agenda)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4 flex-1">
                                    <!-- Status Icon -->
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-clock text-orange-600 text-xl"></i>
                                        </div>
                                    </div>

                                    <!-- Informações da Solicitação -->
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $agenda->titulo }}</h3>
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Pendente
                                                </span>
                                                @if($agenda->fora_horario_comercial)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        <i class="fas fa-moon mr-1"></i>
                                                        Fora do Horário
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        @if($agenda->descricao)
                                            <p style="color: var(--secondary-color);">{{ Str::limit($agenda->descricao, 150) }}</p>
                                        @endif
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                            <!-- Solicitante -->
                                            <div class="flex items-center text-sm text-gray-600">
                                                <i class="fas fa-user mr-2 text-blue-500"></i>
                                                <div>
                                                    <span class="font-medium">Solicitante:</span><br>
                                                    {{ $agenda->solicitante->name ?? 'N/A' }}
                                                </div>
                                            </div>
                                            
                                            <!-- Data e Horário -->
                                            <div class="flex items-center text-sm text-gray-600">
                                                <i class="fas fa-calendar mr-2 text-green-500"></i>
                                                <div>
                                                    <span class="font-medium">Data:</span><br>
                                                    {{ \Carbon\Carbon::parse($agenda->data_inicio)->format('d/m/Y') }}<br>
                                                    {{ \Carbon\Carbon::parse($agenda->data_inicio)->format('H:i') }} - 
                                                    {{ \Carbon\Carbon::parse($agenda->data_fim)->format('H:i') }}
                                                </div>
                                            </div>
                                            
                                            <!-- Tipo de Reunião -->
                                            <div class="flex items-center text-sm text-gray-600">
                                                @if($agenda->tipo_reuniao === 'online')
                                                    <i class="fas fa-video mr-2 text-purple-500"></i>
                                                    <div>
                                                        <span class="font-medium">Tipo:</span><br>
                                                        Online
                                                    </div>
                                                @elseif($agenda->tipo_reuniao === 'presencial')
                                                    <i class="fas fa-handshake mr-2 text-orange-500"></i>
                                                    <div>
                                                        <span class="font-medium">Tipo:</span><br>
                                                        Presencial
                                                    </div>
                                                @else
                                                    <i class="fas fa-users mr-2 text-indigo-500"></i>
                                                    <div>
                                                        <span class="font-medium">Tipo:</span><br>
                                                        Híbrida
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Participantes -->
                                            @if($agenda->participantes && count($agenda->participantes) > 0)
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-users mr-2 text-teal-500"></i>
                                                    <div>
                                                        <span class="font-medium">Participantes:</span><br>
                                                        {{ count($agenda->participantes) }} pessoa(s)
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Link do Meet (se disponível) -->
                                        @if($agenda->meet_link)
                                            <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                                <div class="flex items-center">
                                                    <i class="fas fa-video " style="color: var(--primary-color);" mr-2"></i>
                                                    <span class="text-sm font-medium text-blue-800">Link da Reunião:</span>
                                                </div>
                                                <a href="{{ $agenda->meet_link }}" target="_blank" 
                                                   class="" style="color: var(--primary-color);" hover:text-blue-800 text-sm break-all">
                                                    {{ $agenda->meet_link }}
                                                </a>
                                            </div>
                                        @endif

                                        <!-- Participantes Detalhados -->
                                        @if($agenda->participantes && count($agenda->participantes) > 0)
                                            <div class="mb-4">
                                                <span class="text-sm font-medium text-gray-700 mb-2 block">Lista de Participantes:</span>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($agenda->participantes as $participante)
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            <i class="fas fa-envelope mr-1"></i>
                                                            {{ $participante }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Botões de Ação -->
                                <div class="flex-shrink-0 ml-6">
                                    <div class="flex flex-col space-y-2">
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
                                        <button onclick="viewAgenda({{ $agenda->id }})" 
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                            <i class="fas fa-eye mr-2"></i>
                                            Detalhes
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Informações Adicionais -->
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>
                                        <i class="fas fa-clock mr-1"></i>
                                        Solicitado em {{ \Carbon\Carbon::parse($agenda->created_at)->format('d/m/Y H:i') }}
                                    </span>
                                    @if($agenda->fora_horario_comercial)
                                        <span class="text-purple-600 font-medium">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Solicitação fora do horário comercial
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Estado Vazio -->
                <div class="p-12 text-center">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle " style="color: var(--accent-color);" text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Nenhuma solicitação pendente!</h3>
                    <p style="color: var(--secondary-color);">Todas as solicitações de reunião foram processadas.</p>
                    <a href="{{ route('dashboard.agenda') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-calendar mr-2"></i>
                        Ver Todas as Agendas
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Detalhes (reutilizando da agenda principal) -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300" id="viewModalContent">
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-eye " style="color: var(--primary-color);" mr-3"></i>
                    Detalhes da Solicitação
                </h3>
                <button onclick="closeViewAgendaModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div id="agendaDetails">
                <!-- Conteúdo será carregado via JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
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

// Função para ver detalhes da agenda
function viewAgenda(agendaId) {
    fetch(`/agenda/${agendaId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const agenda = data.agenda;
                const licenciado = data.licenciado;
                
                let participantesHtml = '';
                if (agenda.participantes && agenda.participantes.length > 0) {
                    participantesHtml = agenda.participantes.map(p => 
                        `<span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm mr-2 mb-2">${p}</span>`
                    ).join('');
                }
                
                const detailsHtml = `
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Título</label>
                                <p class="text-gray-900 font-semibold">${agenda.titulo}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Aguardando Aprovação
                                </span>
                            </div>
                        </div>
                        
                        ${agenda.descricao ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                            <p class="text-gray-900">${agenda.descricao}</p>
                        </div>
                        ` : ''}
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Data de Início</label>
                                <p class="text-gray-900">${new Date(agenda.data_inicio).toLocaleString('pt-BR')}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Data de Término</label>
                                <p class="text-gray-900">${new Date(agenda.data_fim).toLocaleString('pt-BR')}</p>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Reunião</label>
                            <p class="text-gray-900 capitalize">${agenda.tipo_reuniao}</p>
                        </div>
                        
                        ${agenda.meet_link ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Link da Reunião</label>
                            <a href="${agenda.meet_link}" target="_blank" class="" style="color: var(--primary-color);" hover:text-blue-800 break-all">${agenda.meet_link}</a>
                        </div>
                        ` : ''}
                        
                        ${licenciado ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Licenciado</label>
                            <p class="text-gray-900">${licenciado.razao_social} - ${licenciado.email}</p>
                        </div>
                        ` : ''}
                        
                        ${participantesHtml ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Participantes</label>
                            <div>${participantesHtml}</div>
                        </div>
                        ` : ''}
                    </div>
                `;
                
                document.getElementById('agendaDetails').innerHTML = detailsHtml;
                
                const modal = document.getElementById('viewModal');
                const modalContent = document.getElementById('viewModalContent');
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                
                setTimeout(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao carregar detalhes da agenda', 'error');
        });
}

// Função para fechar modal de detalhes
function closeViewAgendaModal() {
    const modal = document.getElementById('viewModal');
    const modalContent = document.getElementById('viewModalContent');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
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

// Fechar modal ao clicar fora
document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeViewAgendaModal();
    }
});
</script>
@endsection
