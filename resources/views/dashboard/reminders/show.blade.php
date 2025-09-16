@extends('layouts.dashboard')

@section('title', 'Detalhes do Lembrete')

@section('content')
<x-dynamic-branding />
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-eye text-blue-600 mr-3"></i>
                        Detalhes do Lembrete
                    </h1>
                    <p class="text-gray-600 mt-2">Informações completas sobre este lembrete</p>
                </div>
                <div class="flex items-center space-x-4">
                    @if($reminder->status === 'pending')
                        <button onclick="pauseReminder({{ $reminder->id }})" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-pause mr-2"></i>
                            Pausar
                        </button>
                    @elseif($reminder->status === 'paused')
                        <button onclick="resumeReminder({{ $reminder->id }})" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-play mr-2"></i>
                            Reativar
                        </button>
                    @endif
                    
                    @if($reminder->status !== 'sent')
                        <button onclick="deleteReminder({{ $reminder->id }})" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>
                            Excluir
                        </button>
                    @endif
                    
                    <a href="{{ route('reminders.index') }}" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informações Principais -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status e Informações Básicas -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Informações Básicas</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($reminder->status === 'sent') bg-green-100 text-green-800
                            @elseif($reminder->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($reminder->status === 'failed') bg-red-100 text-red-800
                            @elseif($reminder->status === 'paused') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            @if($reminder->status === 'sent')
                                <i class="fas fa-check-circle mr-2"></i>
                            @elseif($reminder->status === 'pending')
                                <i class="fas fa-clock mr-2"></i>
                            @elseif($reminder->status === 'failed')
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                            @elseif($reminder->status === 'paused')
                                <i class="fas fa-pause-circle mr-2"></i>
                            @endif
                            {{ ucfirst($reminder->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">ID do Lembrete</label>
                            <p class="text-lg text-gray-900">#{{ $reminder->id }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Canal de Envio</label>
                            <p class="text-lg text-gray-900 flex items-center">
                                @if($reminder->channel === 'email')
                                    <i class="fas fa-envelope text-blue-600 mr-2"></i>
                                @elseif($reminder->channel === 'sms')
                                    <i class="fas fa-sms text-green-600 mr-2"></i>
                                @elseif($reminder->channel === 'push')
                                    <i class="fas fa-bell text-purple-600 mr-2"></i>
                                @endif
                                {{ ucfirst($reminder->channel) }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Data de Envio Programada</label>
                            <p class="text-lg text-gray-900">
                                <i class="fas fa-calendar text-blue-600 mr-2"></i>
                                {{ \Carbon\Carbon::parse($reminder->send_at)->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        @if($reminder->sent_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Data de Envio Real</label>
                                <p class="text-lg text-gray-900">
                                    <i class="fas fa-check text-green-600 mr-2"></i>
                                    {{ \Carbon\Carbon::parse($reminder->sent_at)->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Criado em</label>
                            <p class="text-lg text-gray-900">
                                {{ \Carbon\Carbon::parse($reminder->created_at)->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tentativas</label>
                            <p class="text-lg text-gray-900">{{ $reminder->attempts ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Participante -->
                @if($reminder->participant)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-user text-blue-600 mr-2"></i>
                            Participante
                        </h3>
                        
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-lg font-semibold text-gray-900">{{ $reminder->participant->name }}</p>
                                <p class="text-gray-600">{{ $reminder->participant->email }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Agenda Relacionada -->
                @if($reminder->agenda)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                            Agenda Relacionada
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Título</label>
                                <p class="text-lg text-gray-900">{{ $reminder->agenda->titulo }}</p>
                            </div>

                            @if($reminder->agenda->descricao)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Descrição</label>
                                    <p class="text-gray-700">{{ $reminder->agenda->descricao }}</p>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Data de Início</label>
                                    <p class="text-gray-900">
                                        {{ \Carbon\Carbon::parse($reminder->agenda->data_inicio)->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Data de Fim</label>
                                    <p class="text-gray-900">
                                        {{ \Carbon\Carbon::parse($reminder->agenda->data_fim)->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Tipo de Reunião</label>
                                <p class="text-gray-900 flex items-center">
                                    @if($reminder->agenda->tipo_reuniao === 'online')
                                        <i class="fas fa-video text-blue-600 mr-2"></i>
                                        Online
                                    @elseif($reminder->agenda->tipo_reuniao === 'presencial')
                                        <i class="fas fa-handshake text-green-600 mr-2"></i>
                                        Presencial
                                    @else
                                        <i class="fas fa-users text-purple-600 mr-2"></i>
                                        Híbrida
                                    @endif
                                </p>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <a href="{{ route('dashboard.agenda.show', $reminder->agenda->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    Ver Agenda Completa
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Mensagem -->
                @if($reminder->message)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-comment text-blue-600 mr-2"></i>
                            Mensagem Personalizada
                        </h3>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $reminder->message }}</p>
                        </div>
                    </div>
                @endif

                <!-- Erro (se houver) -->
                @if($reminder->status === 'failed' && $reminder->last_error)
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-red-900 mb-4">
                            <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                            Erro no Envio
                        </h3>
                        
                        <div class="bg-red-100 rounded-lg p-4">
                            <p class="text-red-800 font-mono text-sm">{{ $reminder->last_error }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Ações Rápidas -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Ações</h4>
                    
                    <div class="space-y-3">
                        @if($reminder->status === 'pending')
                            <button onclick="pauseReminder({{ $reminder->id }})" class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors text-sm">
                                <i class="fas fa-pause mr-2"></i>
                                Pausar Lembrete
                            </button>
                        @elseif($reminder->status === 'paused')
                            <button onclick="resumeReminder({{ $reminder->id }})" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm">
                                <i class="fas fa-play mr-2"></i>
                                Reativar Lembrete
                            </button>
                        @endif
                        
                        @if($reminder->status !== 'sent')
                            <button onclick="deleteReminder({{ $reminder->id }})" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm">
                                <i class="fas fa-trash mr-2"></i>
                                Excluir Lembrete
                            </button>
                        @endif
                        
                        <a href="{{ route('reminders.create') }}" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm text-center block">
                            <i class="fas fa-plus mr-2"></i>
                            Criar Novo Lembrete
                        </a>
                    </div>
                </div>

                <!-- Informações Técnicas -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Informações Técnicas</h4>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">ID:</span>
                            <span class="text-gray-900 font-mono">#{{ $reminder->id }}</span>
                        </div>
                        
                        @if($reminder->event_id)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Agenda ID:</span>
                                <span class="text-gray-900 font-mono">#{{ $reminder->event_id }}</span>
                            </div>
                        @endif
                        
                        @if($reminder->participant_id)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Usuário ID:</span>
                                <span class="text-gray-900 font-mono">#{{ $reminder->participant_id }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Criado:</span>
                            <span class="text-gray-900">{{ $reminder->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Atualizado:</span>
                            <span class="text-gray-900">{{ $reminder->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        
                        @if($reminder->paused_at)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Pausado em:</span>
                                <span class="text-gray-900">{{ \Carbon\Carbon::parse($reminder->paused_at)->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h4>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-plus text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Lembrete Criado</p>
                                <p class="text-xs text-gray-500">{{ $reminder->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($reminder->paused_at)
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-pause text-purple-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Pausado</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($reminder->paused_at)->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($reminder->sent_at)
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Enviado com Sucesso</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($reminder->sent_at)->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @elseif($reminder->status === 'failed')
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-times text-red-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Falha no Envio</p>
                                    <p class="text-xs text-gray-500">{{ $reminder->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function pauseReminder(id) {
    if (confirm('Tem certeza que deseja pausar este lembrete?')) {
        fetch(`/reminders/${id}/pause`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Erro: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao pausar lembrete', 'error');
        });
    }
}

function resumeReminder(id) {
    if (confirm('Tem certeza que deseja reativar este lembrete?')) {
        fetch(`/reminders/${id}/resume`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Erro: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao reativar lembrete', 'error');
        });
    }
}

function deleteReminder(id) {
    if (confirm('Tem certeza que deseja excluir este lembrete? Esta ação não pode ser desfeita.')) {
        fetch(`/reminders/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => {
                    window.location.href = '{{ route("reminders.index") }}';
                }, 1000);
            } else {
                showToast('Erro: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao excluir lembrete', 'error');
        });
    }
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
        type === 'success' ? 'bg-green-600' : 
        type === 'error' ? 'bg-red-600' : 
        'bg-blue-600'
    }`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>
@endsection
