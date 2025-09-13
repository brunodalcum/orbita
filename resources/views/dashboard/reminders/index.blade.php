@extends('layouts.dashboard')

@section('title', 'Gerenciar Lembretes')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-bell text-blue-600 mr-3"></i>
                        Gerenciar Lembretes
                    </h1>
                    <p class="text-gray-600 mt-2">Acompanhe e gerencie todos os lembretes do sistema</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('reminders.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300 font-semibold flex items-center shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>
                        Criar Lembrete
                    </a>
                    <a href="{{ route('reminders.test-config') }}" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-300 font-semibold flex items-center shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-cog mr-2"></i>
                        Testes & Config
                    </a>
                </div>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-bell text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pendentes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Enviados</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['sent'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Falharam</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['failed'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-pause-circle text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pausados</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['paused'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <form method="GET" action="{{ route('reminders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todos os Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                        <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Enviado</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Falhou</option>
                        <option value="paused" {{ request('status') === 'paused' ? 'selected' : '' }}>Pausado</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Canal</label>
                    <select name="channel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todos os Canais</option>
                        <option value="email" {{ request('channel') === 'email' ? 'selected' : '' }}>Email</option>
                        <option value="sms" {{ request('channel') === 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="push" {{ request('channel') === 'push' ? 'selected' : '' }}>Push</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data Início</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data Fim</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="md:col-span-4 flex items-center justify-between">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>
                        Filtrar
                    </button>
                    <a href="{{ route('reminders.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Limpar Filtros
                    </a>
                </div>
            </form>
        </div>

        <!-- Lista de Lembretes -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Lembretes ({{ $reminders->total() }})</h3>
            </div>

            @if($reminders->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($reminders as $reminder)
                        <div class="reminder-item border-b border-gray-200 last:border-b-0" id="reminder-{{ $reminder->id }}">
                            <!-- Cabeçalho Principal -->
                            <div class="p-6 hover:bg-gray-50 transition-colors cursor-pointer" onclick="toggleReminderDetails({{ $reminder->id }})">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <!-- Status Icon -->
                                        <div class="flex-shrink-0">
                                            @if($reminder->status === 'sent')
                                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                                </div>
                                            @elseif($reminder->status === 'pending')
                                                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                                                </div>
                                            @elseif($reminder->status === 'failed')
                                                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                                                </div>
                                            @elseif($reminder->status === 'paused')
                                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-pause-circle text-purple-600 text-xl"></i>
                                                </div>
                                            @else
                                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-bell text-gray-600 text-xl"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Informações do Lembrete -->
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <h4 class="text-lg font-semibold text-gray-900">
                                                    @if($reminder->agenda)
                                                        {{ $reminder->agenda->titulo }}
                                                    @else
                                                        Lembrete de Teste
                                                    @endif
                                                </h4>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($reminder->status === 'sent') bg-green-100 text-green-800
                                                    @elseif($reminder->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($reminder->status === 'failed') bg-red-100 text-red-800
                                                    @elseif($reminder->status === 'paused') bg-purple-100 text-purple-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($reminder->status) }}
                                                </span>
                                            </div>

                                            <div class="flex items-center text-sm text-gray-500 space-x-4">
                                                <div class="flex items-center">
                                                    <i class="fas fa-user mr-1"></i>
                                                    {{ $reminder->participant ? $reminder->participant->name : ($reminder->participant_name ?: 'N/A') }}
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-envelope mr-1"></i>
                                                    {{ ucfirst($reminder->channel) }}
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($reminder->send_at)->format('d/m/Y H:i') }}
                                                </div>
                                                @if($reminder->sent_at)
                                                    <div class="flex items-center">
                                                        <i class="fas fa-check mr-1"></i>
                                                        Enviado: {{ \Carbon\Carbon::parse($reminder->sent_at)->format('d/m/Y H:i') }}
                                                    </div>
                                                @endif
                                            </div>

                                            @if($reminder->message)
                                                <p class="text-sm text-gray-600 mt-2">{{ Str::limit($reminder->message, 100) }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Ações e Indicador -->
                                    <div class="flex items-center space-x-2">
                                        <!-- Ações -->
                                        @if($reminder->status === 'pending')
                                            <button onclick="event.stopPropagation(); pauseReminder({{ $reminder->id }})" class="text-purple-600 hover:text-purple-800 p-2 rounded-lg hover:bg-purple-50 transition-colors" title="Pausar">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                        @elseif($reminder->status === 'paused')
                                            <button onclick="event.stopPropagation(); resumeReminder({{ $reminder->id }})" class="text-green-600 hover:text-green-800 p-2 rounded-lg hover:bg-green-50 transition-colors" title="Reativar">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        @endif

                                        @if($reminder->status !== 'sent')
                                            <button onclick="event.stopPropagation(); deleteReminder({{ $reminder->id }})" class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-colors" title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif

                                        <!-- Indicador de Expansão -->
                                        <div class="text-gray-400">
                                            <i class="fas fa-chevron-down transform transition-transform duration-200" id="chevron-{{ $reminder->id }}"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Detalhes Expandidos -->
                            <div class="hidden bg-gray-50 border-t border-gray-200" id="details-{{ $reminder->id }}">
                                <div class="p-6 space-y-6">
                                    <!-- Informações Técnicas -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div class="space-y-4">
                                            <h5 class="font-semibold text-gray-900 flex items-center">
                                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                                Informações Básicas
                                            </h5>
                                            <div class="space-y-2 text-sm">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">ID:</span>
                                                    <span class="font-mono">#{{ $reminder->id }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Canal:</span>
                                                    <span class="capitalize">{{ $reminder->channel }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Offset:</span>
                                                    <span>{{ $reminder->offset_minutes ?? 0 }} min</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Tentativas:</span>
                                                    <span>{{ $reminder->attempts ?? 0 }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Teste:</span>
                                                    <span>{{ $reminder->is_test ? 'Sim' : 'Não' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="space-y-4">
                                            <h5 class="font-semibold text-gray-900 flex items-center">
                                                <i class="fas fa-user text-green-600 mr-2"></i>
                                                Participante
                                            </h5>
                                            <div class="space-y-2 text-sm">
                                                <div>
                                                    <span class="text-gray-600">Nome:</span>
                                                    <p class="font-medium">{{ $reminder->participant ? $reminder->participant->name : ($reminder->participant_name ?: 'N/A') }}</p>
                                                </div>
                                                <div>
                                                    <span class="text-gray-600">Email:</span>
                                                    <p class="font-medium">{{ $reminder->participant_email }}</p>
                                                </div>
                                                @if($reminder->participant)
                                                    <div>
                                                        <span class="text-gray-600">ID do Usuário:</span>
                                                        <p class="font-mono">#{{ $reminder->participant_id }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="space-y-4">
                                            <h5 class="font-semibold text-gray-900 flex items-center">
                                                <i class="fas fa-clock text-purple-600 mr-2"></i>
                                                Datas e Horários
                                            </h5>
                                            <div class="space-y-2 text-sm">
                                                <div>
                                                    <span class="text-gray-600">Envio Programado:</span>
                                                    <p class="font-medium">{{ \Carbon\Carbon::parse($reminder->send_at)->format('d/m/Y H:i') }}</p>
                                                </div>
                                                @if($reminder->sent_at)
                                                    <div>
                                                        <span class="text-gray-600">Enviado em:</span>
                                                        <p class="font-medium text-green-600">{{ \Carbon\Carbon::parse($reminder->sent_at)->format('d/m/Y H:i') }}</p>
                                                    </div>
                                                @endif
                                                @if($reminder->paused_at)
                                                    <div>
                                                        <span class="text-gray-600">Pausado em:</span>
                                                        <p class="font-medium text-purple-600">{{ \Carbon\Carbon::parse($reminder->paused_at)->format('d/m/Y H:i') }}</p>
                                                    </div>
                                                @endif
                                                <div>
                                                    <span class="text-gray-600">Criado em:</span>
                                                    <p class="font-medium">{{ \Carbon\Carbon::parse($reminder->created_at)->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Agenda Relacionada -->
                                    @if($reminder->agenda)
                                        <div class="border-t border-gray-200 pt-6">
                                            <h5 class="font-semibold text-gray-900 flex items-center mb-4">
                                                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                                                Agenda Relacionada
                                            </h5>
                                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <h6 class="font-medium text-gray-900 mb-2">{{ $reminder->agenda->titulo }}</h6>
                                                        @if($reminder->agenda->descricao)
                                                            <p class="text-sm text-gray-600 mb-3">{{ $reminder->agenda->descricao }}</p>
                                                        @endif
                                                        <div class="space-y-1 text-sm">
                                                            <div class="flex items-center text-gray-600">
                                                                <i class="fas fa-calendar mr-2"></i>
                                                                {{ \Carbon\Carbon::parse($reminder->agenda->data_inicio)->format('d/m/Y') }}
                                                            </div>
                                                            <div class="flex items-center text-gray-600">
                                                                <i class="fas fa-clock mr-2"></i>
                                                                {{ \Carbon\Carbon::parse($reminder->agenda->data_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($reminder->agenda->data_fim)->format('H:i') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="space-y-2 text-sm">
                                                            <div class="flex items-center">
                                                                @if($reminder->agenda->tipo_reuniao === 'online')
                                                                    <i class="fas fa-video text-blue-600 mr-2"></i>
                                                                    <span>Online</span>
                                                                @elseif($reminder->agenda->tipo_reuniao === 'presencial')
                                                                    <i class="fas fa-handshake text-green-600 mr-2"></i>
                                                                    <span>Presencial</span>
                                                                @else
                                                                    <i class="fas fa-users text-purple-600 mr-2"></i>
                                                                    <span>Híbrida</span>
                                                                @endif
                                                            </div>
                                                            @if($reminder->agenda->meet_link)
                                                                <div>
                                                                    <span class="text-gray-600">Link:</span>
                                                                    <a href="{{ $reminder->agenda->meet_link }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs break-all">
                                                                        {{ Str::limit($reminder->agenda->meet_link, 40) }}
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="mt-3">
                                                            <a href="{{ route('dashboard.agenda.show', $reminder->agenda->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                                <i class="fas fa-external-link-alt mr-1"></i>
                                                                Ver Agenda Completa
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Mensagem Personalizada -->
                                    @if($reminder->message)
                                        <div class="border-t border-gray-200 pt-6">
                                            <h5 class="font-semibold text-gray-900 flex items-center mb-3">
                                                <i class="fas fa-comment text-green-600 mr-2"></i>
                                                Mensagem Personalizada
                                            </h5>
                                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                                <p class="text-gray-700 whitespace-pre-wrap">{{ $reminder->message }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Erro (se houver) -->
                                    @if($reminder->status === 'failed' && $reminder->last_error)
                                        <div class="border-t border-gray-200 pt-6">
                                            <h5 class="font-semibold text-red-900 flex items-center mb-3">
                                                <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                                Erro no Envio
                                            </h5>
                                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                                <p class="text-red-800 font-mono text-sm">{{ $reminder->last_error }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Auditoria -->
                                    <div class="border-t border-gray-200 pt-6">
                                        <h5 class="font-semibold text-gray-900 flex items-center mb-3">
                                            <i class="fas fa-history text-gray-600 mr-2"></i>
                                            Auditoria
                                        </h5>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                            @if($reminder->createdBy)
                                                <div>
                                                    <span class="text-gray-600">Criado por:</span>
                                                    <p class="font-medium">{{ $reminder->createdBy->name }}</p>
                                                </div>
                                            @endif
                                            @if($reminder->pausedBy)
                                                <div>
                                                    <span class="text-gray-600">Pausado por:</span>
                                                    <p class="font-medium">{{ $reminder->pausedBy->name }}</p>
                                                </div>
                                            @endif
                                            <div>
                                                <span class="text-gray-600">Última atualização:</span>
                                                <p class="font-medium">{{ \Carbon\Carbon::parse($reminder->updated_at)->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Ações Detalhadas -->
                                    <div class="border-t border-gray-200 pt-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                @if($reminder->status === 'pending')
                                                    <button onclick="pauseReminder({{ $reminder->id }})" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors text-sm">
                                                        <i class="fas fa-pause mr-2"></i>
                                                        Pausar Lembrete
                                                    </button>
                                                @elseif($reminder->status === 'paused')
                                                    <button onclick="resumeReminder({{ $reminder->id }})" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm">
                                                        <i class="fas fa-play mr-2"></i>
                                                        Reativar Lembrete
                                                    </button>
                                                @endif
                                                
                                                @if($reminder->status !== 'sent')
                                                    <button onclick="deleteReminder({{ $reminder->id }})" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm">
                                                        <i class="fas fa-trash mr-2"></i>
                                                        Excluir Lembrete
                                                    </button>
                                                @endif
                                            </div>
                                            
                                            <a href="{{ route('reminders.show', $reminder->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                <i class="fas fa-external-link-alt mr-1"></i>
                                                Ver Página Completa
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginação -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $reminders->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bell-slash text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum lembrete encontrado</h3>
                    <p class="text-gray-600 mb-6">
                        @if(request()->hasAny(['status', 'channel', 'date_from', 'date_to']))
                            Não há lembretes que correspondam aos filtros aplicados.
                        @else
                            Ainda não há lembretes no sistema.
                        @endif
                    </p>
                    <a href="{{ route('reminders.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Criar Primeiro Lembrete
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Detalhes -->
<div id="reminderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Detalhes do Lembrete</h3>
                    <button onclick="closeReminderModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div id="reminderModalContent" class="p-6">
                <!-- Conteúdo será carregado via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
// Função para expandir/recolher detalhes do lembrete
function toggleReminderDetails(id) {
    const detailsDiv = document.getElementById(`details-${id}`);
    const chevron = document.getElementById(`chevron-${id}`);
    
    if (detailsDiv.classList.contains('hidden')) {
        // Expandir
        detailsDiv.classList.remove('hidden');
        chevron.classList.add('rotate-180');
    } else {
        // Recolher
        detailsDiv.classList.add('hidden');
        chevron.classList.remove('rotate-180');
    }
}

// Funções JavaScript para gerenciar lembretes
function viewReminder(id) {
    // Usar a nova função de toggle ao invés do modal
    toggleReminderDetails(id);
}

function closeReminderModal() {
    document.getElementById('reminderModal').classList.add('hidden');
}

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
                location.reload();
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
                location.reload();
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
                location.reload();
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
    // Implementação simples de toast
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
