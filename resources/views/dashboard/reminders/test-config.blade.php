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

@section('title', 'Testes & Configuração de Lembretes')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-cog text-primary mr-3"></i>
                        Testes & Configuração de Lembretes
                    </h1>
                    <p class="text-gray-600 mt-2">Configure o sistema de lembretes e execute testes</p>
                </div>
                <a href="{{ route('reminders.index') }}" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>

        <!-- Alertas -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Erro:</strong>
                </div>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Coluna Principal -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Estatísticas Rápidas -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-chart-bar text-primary mr-2"></i>
                        Estatísticas do Sistema
                    </h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-primary">{{ $stats['total_reminders'] }}</div>
                            <div class="text-sm text-blue-800">Total</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending_reminders'] }}</div>
                            <div class="text-sm text-yellow-800">Pendentes</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $stats['sent_today'] }}</div>
                            <div class="text-sm text-green-800">Enviados Hoje</div>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <div class="text-2xl font-bold text-red-600">{{ $stats['failed_today'] }}</div>
                            <div class="text-sm text-red-800">Falharam Hoje</div>
                        </div>
                    </div>
                </div>

                <!-- Teste de Envio -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-flask text-green-600 mr-2"></i>
                        Teste de Envio de Lembrete
                    </h3>
                    
                    <form id="testForm" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email de Teste</label>
                            <input type="email" id="test_email" name="test_email" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="seu@email.com">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mensagem de Teste (Opcional)</label>
                            <textarea id="test_message" name="test_message" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                      placeholder="Digite uma mensagem personalizada para o teste..."></textarea>
                        </div>
                        
                        <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-medium">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Enviar Teste
                        </button>
                    </form>
                </div>

                <!-- Processamento Manual -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-play-circle text-purple-600 mr-2"></i>
                        Processamento Manual
                    </h3>
                    
                    <p class="text-gray-600 mb-4">
                        Execute o processamento de lembretes pendentes manualmente. Normalmente isso é feito automaticamente a cada minuto.
                    </p>
                    
                    <button onclick="processReminders()" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors font-medium">
                        <i class="fas fa-cogs mr-2"></i>
                        Processar Agora
                    </button>
                </div>

                <!-- Configurações do Usuário -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-user-cog text-primary mr-2"></i>
                        Suas Configurações de Lembrete
                    </h3>
                    
                    <form action="{{ route('reminders.update-config') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Intervalos de Lembrete -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Intervalos de Lembrete (em minutos)
                            </label>
                            <div class="grid grid-cols-3 gap-4">
                                @foreach($userSettings->reminder_offsets as $index => $offset)
                                    <input type="number" name="reminder_offsets[]" value="{{ $offset }}" min="1" max="10080" 
                                           class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Ex: 15">
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Quando enviar lembretes antes do evento (ex: 15 = 15 minutos antes)
                            </p>
                        </div>

                        <!-- Horário Silencioso -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Início do Silêncio</label>
                                <input type="time" name="quiet_hours_start" value="{{ $userSettings->quiet_hours_start }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fim do Silêncio</label>
                                <input type="time" name="quiet_hours_end" value="{{ $userSettings->quiet_hours_end }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <!-- Fuso Horário -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fuso Horário</label>
                            <select name="timezone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="America/Sao_Paulo" {{ $userSettings->timezone === 'America/Sao_Paulo' ? 'selected' : '' }}>
                                    América/São Paulo (GMT-3)
                                </option>
                                <option value="America/New_York" {{ $userSettings->timezone === 'America/New_York' ? 'selected' : '' }}>
                                    América/Nova York (GMT-5)
                                </option>
                                <option value="Europe/London" {{ $userSettings->timezone === 'Europe/London' ? 'selected' : '' }}>
                                    Europa/Londres (GMT+0)
                                </option>
                            </select>
                        </div>

                        <!-- Canais Habilitados -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Canais Habilitados</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="enabled_channels[]" value="email" 
                                           {{ in_array('email', $userSettings->enabled_channels) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">
                                        <i class="fas fa-envelope mr-1"></i> Email
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="enabled_channels[]" value="sms" 
                                           {{ in_array('sms', $userSettings->enabled_channels) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">
                                        <i class="fas fa-sms mr-1"></i> SMS
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="enabled_channels[]" value="push" 
                                           {{ in_array('push', $userSettings->enabled_channels) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">
                                        <i class="fas fa-bell mr-1"></i> Push Notification
                                    </span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-dark transition-colors font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Salvar Configurações
                        </button>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Próximos Lembretes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-clock text-yellow-600 mr-2"></i>
                        Próximos Lembretes
                    </h4>
                    
                    @if($nextReminders->count() > 0)
                        <div class="space-y-3">
                            @foreach($nextReminders as $reminder)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $reminder->agenda ? $reminder->agenda->titulo : 'Teste' }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $reminder->participant ? $reminder->participant->name : 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($reminder->send_at)->format('d/m H:i') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Nenhum lembrete pendente</p>
                    @endif
                </div>

                <!-- Lembretes Recentes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-history text-green-600 mr-2"></i>
                        Lembretes Recentes
                    </h4>
                    
                    @if($recentReminders->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentReminders as $reminder)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $reminder->agenda ? Str::limit($reminder->agenda->titulo, 20) : 'Teste' }}
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            {{ $reminder->status === 'sent' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $reminder->status === 'sent' ? 'Enviado' : 'Falhou' }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($reminder->updated_at)->format('d/m H:i') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Nenhum lembrete recente</p>
                    @endif
                </div>

                <!-- Links Úteis -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-blue-900 mb-4">
                        <i class="fas fa-link text-primary mr-2"></i>
                        Links Úteis
                    </h4>
                    
                    <div class="space-y-2">
                        <a href="{{ route('reminders.index') }}" class="block text-primary hover:text-blue-900 text-sm">
                            <i class="fas fa-list mr-2"></i>
                            Ver Todos os Lembretes
                        </a>
                        <a href="{{ route('reminders.create') }}" class="block text-primary hover:text-blue-900 text-sm">
                            <i class="fas fa-plus mr-2"></i>
                            Criar Novo Lembrete
                        </a>
                        <a href="{{ route('dashboard.agenda') }}" class="block text-primary hover:text-blue-900 text-sm">
                            <i class="fas fa-calendar mr-2"></i>
                            Ver Agenda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Envio de teste
document.getElementById('testForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const button = this.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
    button.disabled = true;
    
    fetch('{{ route("reminders.send-test") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            this.reset();
        } else {
            showToast('Erro: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showToast('Erro ao enviar teste', 'error');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
});

// Processamento manual
function processReminders() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processando...';
    button.disabled = true;
    
    fetch('{{ route("reminders.process-now") }}', {
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
            // Recarregar a página após 2 segundos para atualizar estatísticas
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showToast('Erro: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showToast('Erro ao processar lembretes', 'error');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
        type === 'success' ? 'bg-green-600' : 
        type === 'error' ? 'bg-red-600' : 
        'bg-primary'
    }`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>
@endsection
