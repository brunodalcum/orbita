<style>
/* BRANDING FORÇADO PARA ESTA PÁGINA */
:root {
    --primary-color: var(--primary-color);
    --secondary-color: var(--secondary-color);
    --accent-color: var(--accent-color);
    --text-color: #1F2937;
    --background-color: #FFFFFF;
    --primary-light: rgba(var(--primary-color-rgb), 0.1);
    --primary-dark: var(--primary-dark);
    --primary-text: #FFFFFF;
}

/* Sobrescrita agressiva de todas as cores azuis */
.bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400,
.bg-primary, .bg-primary, .bg-primary-dark, .bg-blue-800, .bg-blue-900,
.bg-indigo-50, .bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400,
.bg-primary, .bg-primary, .bg-primary-dark, .bg-indigo-800, .bg-indigo-900 {
    background-color: var(--primary-color) !important;
}

.hover\:bg-primary:hover, .hover\:bg-primary-dark:hover, .hover\:bg-blue-800:hover,
.hover\:bg-primary:hover, .hover\:bg-primary-dark:hover, .hover\:bg-indigo-800:hover {
    background-color: var(--primary-dark) !important;
}

.text-primary, .text-primary, .text-primary, .text-blue-800, .text-blue-900,
.text-primary, .text-primary, .text-primary, .text-indigo-800, .text-indigo-900 {
    color: var(--primary-color) !important;
}

.border-primary, .border-primary, .border-primary, .border-blue-800, .border-blue-900,
.border-primary, .border-primary, .border-primary, .border-indigo-800, .border-indigo-900 {
    border-color: var(--primary-color) !important;
}

button[class*="blue"], .btn[class*="blue"], button[class*="indigo"], .btn[class*="indigo"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

button[class*="blue"]:hover, .btn[class*="blue"]:hover, button[class*="indigo"]:hover, .btn[class*="indigo"]:hover {
    background-color: var(--primary-dark) !important;
}

.bg-green-500, .bg-green-600, .bg-green-700 {
    background-color: var(--accent-color) !important;
}

.text-green-500, .text-green-600, .text-green-700, .text-green-800 {
    color: var(--accent-color) !important;
}

/* Sobrescrever estilos inline hardcoded */
[style*="background-color: var(--primary-color)"], [style*="background-color: var(--primary-color)"],
[style*="background-color: var(--primary-color)"], [style*="background-color: var(--primary-color)"] {
    background-color: var(--primary-color) !important;
}

[style*="color: var(--primary-color)"], [style*="color: var(--primary-color)"],
[style*="color: var(--primary-color)"], [style*="color: var(--primary-color)"] {
    color: var(--primary-color) !important;
}

.animate-spin[class*="border-blue"], .animate-spin[class*="border-indigo"] {
    border-color: var(--primary-color) transparent var(--primary-color) transparent !important;
}
</style>
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

@section('title', 'Criar Lembrete')

@section('content')
<x-dynamic-branding />

<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-plus-circle text-primary mr-3"></i>
                        Criar Novo Lembrete
                    </h1>
                    <p class="text-gray-600 mt-2">Configure um lembrete personalizado para uma agenda específica</p>
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
                    <strong>Erro ao criar lembrete:</strong>
                </div>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulário -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Informações do Lembrete</h3>
            </div>

            <form action="{{ route('reminders.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Seleção de Agenda -->
                <div>
                    <label for="agenda_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-2"></i>
                        Agenda/Compromisso *
                    </label>
                    <select name="agenda_id" id="agenda_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                        <option value="">Selecione uma agenda</option>
                        @foreach($agendas as $agenda)
                            <option value="{{ $agenda->id }}" {{ old('agenda_id') == $agenda->id ? 'selected' : '' }}>
                                {{ $agenda->titulo }} - {{ \Carbon\Carbon::parse($agenda->data_inicio)->format('d/m/Y H:i') }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Selecione a agenda para a qual deseja criar o lembrete</p>
                </div>

                <!-- Email do Participante -->
                <div>
                    <label for="participant_email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2"></i>
                        Email do Participante *
                    </label>
                    <input type="email" name="participant_email" id="participant_email" value="{{ old('participant_email') }}" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                           placeholder="exemplo@email.com">
                    <p class="text-xs text-gray-500 mt-1">Email do usuário que receberá o lembrete</p>
                </div>

                <!-- Canal de Envio -->
                <div>
                    <label for="channel" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Canal de Envio *
                    </label>
                    <select name="channel" id="channel" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                        <option value="">Selecione o canal</option>
                        <option value="email" {{ old('channel') === 'email' ? 'selected' : '' }}>
                            <i class="fas fa-envelope"></i> Email
                        </option>
                        <option value="sms" {{ old('channel') === 'sms' ? 'selected' : '' }}>
                            <i class="fas fa-sms"></i> SMS
                        </option>
                        <option value="push" {{ old('channel') === 'push' ? 'selected' : '' }}>
                            <i class="fas fa-bell"></i> Push Notification
                        </option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Como o lembrete será enviado</p>
                </div>

                <!-- Data e Hora de Envio -->
                <div>
                    <label for="send_at" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-clock mr-2"></i>
                        Data e Hora de Envio *
                    </label>
                    <input type="datetime-local" name="send_at" id="send_at" value="{{ old('send_at') }}" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                           min="{{ now()->format('Y-m-d\TH:i') }}">
                    <p class="text-xs text-gray-500 mt-1">Quando o lembrete deve ser enviado (deve ser no futuro)</p>
                </div>

                <!-- Mensagem Personalizada -->
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment mr-2"></i>
                        Mensagem Personalizada (Opcional)
                    </label>
                    <textarea name="message" id="message" rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors resize-none"
                              placeholder="Digite uma mensagem personalizada para o lembrete (deixe em branco para usar a mensagem padrão)">{{ old('message') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Máximo 1000 caracteres. Se não informado, será usada a mensagem padrão do sistema.</p>
                </div>

                <!-- Botões -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('reminders.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    
                    <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-8 py-3 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300 font-semibold flex items-center shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i>
                        Criar Lembrete
                    </button>
                </div>
            </form>
        </div>

        <!-- Informações Adicionais -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-primary text-xl"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-blue-900 mb-2">Informações Importantes</h4>
                    <ul class="text-blue-800 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check text-primary mr-2 mt-1 text-sm"></i>
                            <span>O lembrete será enviado automaticamente na data e hora especificadas</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-primary mr-2 mt-1 text-sm"></i>
                            <span>Você pode pausar ou excluir lembretes antes do envio</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-primary mr-2 mt-1 text-sm"></i>
                            <span>Lembretes já enviados não podem ser excluídos, apenas visualizados</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-primary mr-2 mt-1 text-sm"></i>
                            <span>O participante deve ter um cadastro no sistema para receber o lembrete</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-preencher data de envio baseada na agenda selecionada
document.getElementById('agenda_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    if (selectedOption.value) {
        // Extrair data da agenda e sugerir 1 hora antes
        const agendaText = selectedOption.text;
        const dateMatch = agendaText.match(/(\d{2}\/\d{2}\/\d{4} \d{2}:\d{2})/);
        
        if (dateMatch) {
            const [day, month, year, hour, minute] = dateMatch[1].split(/[\/\s:]/);
            const agendaDate = new Date(year, month - 1, day, hour, minute);
            
            // Sugerir 1 hora antes
            const reminderDate = new Date(agendaDate.getTime() - (60 * 60 * 1000));
            
            // Formatar para datetime-local
            const formattedDate = reminderDate.toISOString().slice(0, 16);
            document.getElementById('send_at').value = formattedDate;
        }
    }
});

// Validação em tempo real
document.getElementById('send_at').addEventListener('change', function() {
    const selectedDate = new Date(this.value);
    const now = new Date();
    
    if (selectedDate <= now) {
        this.setCustomValidity('A data de envio deve ser no futuro');
        this.reportValidity();
    } else {
        this.setCustomValidity('');
    }
});

// Contador de caracteres para mensagem
document.getElementById('message').addEventListener('input', function() {
    const maxLength = 1000;
    const currentLength = this.value.length;
    const remaining = maxLength - currentLength;
    
    // Criar ou atualizar contador
    let counter = document.getElementById('message-counter');
    if (!counter) {
        counter = document.createElement('div');
        counter.id = 'message-counter';
        counter.className = 'text-xs text-gray-500 mt-1';
        this.parentNode.appendChild(counter);
    }
    
    counter.textContent = `${currentLength}/${maxLength} caracteres`;
    
    if (remaining < 0) {
        counter.className = 'text-xs text-red-500 mt-1';
        this.setCustomValidity('Mensagem muito longa');
    } else {
        counter.className = 'text-xs text-gray-500 mt-1';
        this.setCustomValidity('');
    }
});
</script>
@endsection
