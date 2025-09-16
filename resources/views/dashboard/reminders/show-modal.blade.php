<div class="space-y-6">
    <!-- Status e Informações Básicas -->
    <div class="flex items-center justify-between">
        <h4 class="text-lg font-semibold text-gray-900">
            @if($reminder->agenda)
                {{ $reminder->agenda->titulo }}
            @else
                Lembrete de Teste
            @endif
        </h4>
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

    <!-- Informações do Lembrete -->
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Canal</label>
            <p class="text-sm text-gray-900 flex items-center">
                @if($reminder->channel === 'email')
                    <i class="fas fa-envelope text-primary mr-2"></i>
                @elseif($reminder->channel === 'sms')
                    <i class="fas fa-sms text-green-600 mr-2"></i>
                @elseif($reminder->channel === 'push')
                    <i class="fas fa-bell text-purple-600 mr-2"></i>
                @endif
                {{ ucfirst($reminder->channel) }}
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Envio Programado</label>
            <p class="text-sm text-gray-900">
                <i class="fas fa-calendar text-primary mr-2"></i>
                {{ \Carbon\Carbon::parse($reminder->send_at)->format('d/m/Y H:i') }}
            </p>
        </div>

        @if($reminder->sent_at)
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Enviado em</label>
                <p class="text-sm text-gray-900">
                    <i class="fas fa-check text-green-600 mr-2"></i>
                    {{ \Carbon\Carbon::parse($reminder->sent_at)->format('d/m/Y H:i') }}
                </p>
            </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Tentativas</label>
            <p class="text-sm text-gray-900">{{ $reminder->attempts ?? 0 }}</p>
        </div>
    </div>

    <!-- Participante -->
    @if($reminder->participant)
        <div class="border-t border-gray-200 pt-4">
            <label class="block text-sm font-medium text-gray-500 mb-2">Participante</label>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-primary"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ $reminder->participant->name }}</p>
                    <p class="text-xs text-gray-600">{{ $reminder->participant->email }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Agenda Relacionada -->
    @if($reminder->agenda)
        <div class="border-t border-gray-200 pt-4">
            <label class="block text-sm font-medium text-gray-500 mb-2">Agenda</label>
            <div class="bg-gray-50 rounded-lg p-3">
                <p class="text-sm font-semibold text-gray-900 mb-1">{{ $reminder->agenda->titulo }}</p>
                
                @if($reminder->agenda->descricao)
                    <p class="text-xs text-gray-600 mb-2">{{ Str::limit($reminder->agenda->descricao, 100) }}</p>
                @endif

                <div class="flex items-center text-xs text-gray-500 space-x-4">
                    <div class="flex items-center">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ \Carbon\Carbon::parse($reminder->agenda->data_inicio)->format('d/m/Y H:i') }}
                    </div>
                    <div class="flex items-center">
                        @if($reminder->agenda->tipo_reuniao === 'online')
                            <i class="fas fa-video mr-1"></i>
                            Online
                        @elseif($reminder->agenda->tipo_reuniao === 'presencial')
                            <i class="fas fa-handshake mr-1"></i>
                            Presencial
                        @else
                            <i class="fas fa-users mr-1"></i>
                            Híbrida
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Mensagem Personalizada -->
    @if($reminder->message)
        <div class="border-t border-gray-200 pt-4">
            <label class="block text-sm font-medium text-gray-500 mb-2">Mensagem Personalizada</label>
            <div class="bg-gray-50 rounded-lg p-3">
                <p class="text-sm text-gray-700">{{ $reminder->message }}</p>
            </div>
        </div>
    @endif

    <!-- Erro (se houver) -->
    @if($reminder->status === 'failed' && $reminder->last_error)
        <div class="border-t border-gray-200 pt-4">
            <label class="block text-sm font-medium text-red-600 mb-2">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                Erro no Envio
            </label>
            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                <p class="text-sm text-red-800 font-mono">{{ $reminder->last_error }}</p>
            </div>
        </div>
    @endif

    <!-- Ações -->
    <div class="border-t border-gray-200 pt-4 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            @if($reminder->status === 'pending')
                <button onclick="pauseReminder({{ $reminder->id }})" class="text-purple-600 hover:text-purple-800 text-sm">
                    <i class="fas fa-pause mr-1"></i>
                    Pausar
                </button>
            @elseif($reminder->status === 'paused')
                <button onclick="resumeReminder({{ $reminder->id }})" class="text-green-600 hover:text-green-800 text-sm">
                    <i class="fas fa-play mr-1"></i>
                    Reativar
                </button>
            @endif

            @if($reminder->status !== 'sent')
                <button onclick="deleteReminder({{ $reminder->id }})" class="text-red-600 hover:text-red-800 text-sm">
                    <i class="fas fa-trash mr-1"></i>
                    Excluir
                </button>
            @endif
        </div>

        <a href="{{ route('reminders.show', $reminder->id) }}" class="text-primary hover:text-blue-800 text-sm">
            <i class="fas fa-external-link-alt mr-1"></i>
            Ver Completo
        </a>
    </div>
</div>
