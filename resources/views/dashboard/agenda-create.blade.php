
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

@section('title', 'Nova Reunião')

@section('content')
<x-simple-branding />
<!-- Branding Dinâmico -->

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header com animação -->
        <div class="mb-12 animate-fadeIn">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full shadow-lg mb-6 animate-bounce">
                    <i class="fas fa-calendar-plus text-white text-2xl"></i>
                </div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4">
                    Nova Reunião
                </h1>
                <p style="color: var(--secondary-color);">
                    ✨ Crie uma reunião incrível e envie convites automáticos para todos os participantes
                </p>
            </div>
            
            <!-- Breadcrumb -->
            <div class="flex items-center justify-center mb-6">
                <nav class="flex items-center space-x-2 text-sm bg-white/80 backdrop-blur-sm px-4 py-2 rounded-full shadow-sm">
                    <a href="{{ route('dashboard.agenda') }}" class="" style="color: var(--primary-color);" hover:text-blue-800 font-medium flex items-center transition-colors">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Agenda
                    </a>
                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    <span class="text-gray-700 font-medium">Nova Reunião</span>
                </nav>
            </div>
        </div>

        <!-- Formulário -->
        <div class="bg-white rounded-2xl shadow-2xl border-2 border-blue-200 animate-slideUp overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-purple-700 h-3"></div>
            <form action="{{ route('agenda.store') }}" method="POST" id="agendaForm">
                @csrf
                
                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        
                        <!-- Coluna Esquerda -->
                        <div class="space-y-6">
                            <!-- Assunto -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-heading " style="color: var(--primary-color);" mr-2"></i>
                                    Assunto da Reunião *
                                </label>
                                <input type="text" 
                                       name="titulo" 
                                       id="titulo" 
                                       required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                                       placeholder="Ex: Reunião de alinhamento comercial"
                                       value="{{ old('titulo') }}">
                                @error('titulo')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Licenciado -->
                            <div class="group">
                                <label class="block text-sm font-semibold text-gray-700 mb-3 group-focus-within:" style="color: var(--primary-color);" transition-colors">
                                    <i class="fas fa-user-tie " style="color: var(--primary-color);" mr-2"></i>
                                    Licenciado
                                </label>
                                <div class="relative">
                                    <select name="licenciado_id" 
                                            id="licenciado_id" 
                                            class="w-full px-4 py-4 pr-12 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-primary transition-all duration-200 bg-gradient-to-r from-white to-blue-50/30 hover:border-blue-300">
                                        <option value="">🔍 Selecione um licenciado...</option>
                                        @foreach($licenciados as $licenciado)
                                            <option value="{{ $licenciado->id }}" 
                                                    data-email="{{ $licenciado->email }}"
                                                    {{ old('licenciado_id') == $licenciado->id ? 'selected' : '' }}>
                                                {{ $licenciado->razao_social }} - {{ $licenciado->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition-colors">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                
                                <!-- Info do Licenciado Selecionado -->
                                <div id="licenciadoInfo" class="hidden mt-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-check text-white"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-green-800" id="licenciadoNome"></p>
                                            <p class="text-sm " style="color: var(--accent-color);"" id="licenciadoEmail"></p>
                                        </div>
                                    </div>
                                </div>
                                
                                @error('licenciado_id')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Participantes Adicionais -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-users " style="color: var(--primary-color);" mr-2"></i>
                                    Participantes Adicionais
                                </label>
                                <div>
                                    <textarea name="participantes" 
                                              id="participantes" 
                                              rows="3" 
                                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-primary transition-all duration-200 bg-gradient-to-r from-white to-blue-50/30 hover:border-blue-300 resize-none"
                                              placeholder="Digite os emails separados por vírgula ou quebra de linha:&#10;email1@exemplo.com, email2@exemplo.com&#10;email3@exemplo.com">{{ old('participantes') }}</textarea>
                                    <p class="text-sm text-gray-500 mt-2 flex items-center">
                                        <i class="fas fa-info-circle mr-2 text-primary"></i>
                                        Separe múltiplos emails por vírgula ou quebra de linha
                                    </p>
                                </div>
                                @error('participantes')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Formato da Reunião -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-video " style="color: var(--primary-color);" mr-2"></i>
                                    Formato da Reunião *
                                </label>
                                <div class="grid grid-cols-1 gap-4">
                                    <label class="format-card flex items-center p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 transition-all bg-gradient-to-r from-white to-blue-50/20">
                                        <input type="radio" 
                                               name="tipo_reuniao" 
                                               value="online" 
                                               class="" style="color: var(--primary-color);" focus:ring-blue-500 w-5 h-5"
                                               {{ old('tipo_reuniao', 'online') == 'online' ? 'checked' : '' }}>
                                        <div class="ml-4 flex-1">
                                            <div class="flex items-center mb-2">
                                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fab fa-google text-white text-lg"></i>
                                                </div>
                                                <span class="font-semibold text-gray-900 text-lg">Online (Google Meet)</span>
                                            </div>
                                            <p style="color: var(--secondary-color);">✨ Link do Meet será gerado automaticamente</p>
                                        </div>
                                        <div class="text-primary">
                                            <i class="fas fa-check-circle text-2xl opacity-0 transition-opacity duration-300"></i>
                                        </div>
                                    </label>
                                    
                                    <label class="format-card flex items-center p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-green-300 transition-all bg-gradient-to-r from-white to-green-50/20">
                                        <input type="radio" 
                                               name="tipo_reuniao" 
                                               value="presencial" 
                                               class="" style="color: var(--accent-color);" focus:ring-green-500 w-5 h-5"
                                               {{ old('tipo_reuniao') == 'presencial' ? 'checked' : '' }}>
                                        <div class="ml-4 flex-1">
                                            <div class="flex items-center mb-2">
                                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fas fa-handshake text-white text-lg"></i>
                                                </div>
                                                <span class="font-semibold text-gray-900 text-lg">Presencial</span>
                                            </div>
                                            <p style="color: var(--secondary-color);">🏢 Reunião no escritório ou local físico</p>
                                        </div>
                                        <div class="text-green-500">
                                            <i class="fas fa-check-circle text-2xl opacity-0 transition-opacity duration-300"></i>
                                        </div>
                                    </label>
                                    
                                    <label class="format-card flex items-center p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-purple-300 transition-all bg-gradient-to-r from-white to-purple-50/20">
                                        <input type="radio" 
                                               name="tipo_reuniao" 
                                               value="hibrida" 
                                               class="text-purple-600 focus:ring-purple-500 w-5 h-5"
                                               {{ old('tipo_reuniao') == 'hibrida' ? 'checked' : '' }}>
                                        <div class="ml-4 flex-1">
                                            <div class="flex items-center mb-2">
                                                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fas fa-users text-white text-lg"></i>
                                                </div>
                                                <span class="font-semibold text-gray-900 text-lg">Híbrida</span>
                                            </div>
                                            <p style="color: var(--secondary-color);">🌐 Alguns presenciais, outros online</p>
                                        </div>
                                        <div class="text-purple-500">
                                            <i class="fas fa-check-circle text-2xl opacity-0 transition-opacity duration-300"></i>
                                        </div>
                                    </label>
                                </div>
                                @error('tipo_reuniao')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Coluna Direita -->
                        <div class="space-y-6">
                            <!-- Data e Horário -->
                            <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
                                <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    Data e Horário
                                </h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-blue-800 mb-2">Data de Início *</label>
                                        <input type="datetime-local" 
                                               name="data_inicio" 
                                               id="data_inicio" 
                                               required
                                               class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                                               value="{{ old('data_inicio') }}">
                                        @error('data_inicio')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-blue-800 mb-2">Data de Término *</label>
                                        <input type="datetime-local" 
                                               name="data_fim" 
                                               id="data_fim" 
                                               required
                                               class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                                               value="{{ old('data_fim') }}">
                                        @error('data_fim')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Link do Google Meet -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fab fa-google " style="color: var(--primary-color);" mr-2"></i>
                                    Link do Google Meet (Opcional)
                                </label>
                                <div class="relative">
                                    <input type="url" 
                                           name="meet_link" 
                                           id="meet_link" 
                                           class="w-full px-4 py-3 pl-12 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-primary transition-all duration-200 bg-gradient-to-r from-white to-blue-50/30 hover:border-blue-300"
                                           placeholder="https://meet.google.com/xxx-xxx-xxx"
                                           value="{{ old('meet_link') }}">
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-primary">
                                        <i class="fab fa-google"></i>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 mt-2 flex items-center">
                                    <i class="fas fa-info-circle mr-2 text-primary"></i>
                                    Se não informado, será gerado automaticamente para reuniões online
                                </p>
                                @error('meet_link')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Observações -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-sticky-note " style="color: var(--primary-color);" mr-2"></i>
                                    Observações
                                </label>
                                <textarea name="descricao" 
                                          id="descricao" 
                                          rows="4" 
                                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-primary transition-all duration-200 bg-gradient-to-r from-white to-blue-50/30 hover:border-blue-300 resize-none"
                                          placeholder="Adicione detalhes importantes sobre a reunião, pauta, documentos necessários, etc.">{{ old('descricao') }}</textarea>
                                @error('descricao')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Resumo da Reunião -->
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-clipboard-check mr-2"></i>
                                    Resumo
                                </h3>
                                <div id="resumoReuniao" class="space-y-3 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2 text-gray-400"></i>
                                        <span id="resumoDuracao">Selecione as datas para ver a duração</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-users mr-2 text-gray-400"></i>
                                        <span id="resumoParticipantes">0 participantes</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-video mr-2 text-gray-400"></i>
                                        <span id="resumoFormato">Formato: Online</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer com Botões -->
                <div class="px-8 py-8 border-t-4 border-blue-300 rounded-b-2xl" style="background: linear-gradient(135deg, #eff6ff 0%, #f3e8ff 100%);">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <a href="{{ route('dashboard.agenda') }}" 
                           class="px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 hover:bg-white hover:border-gray-400 font-medium flex items-center transition-all duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar para Agenda
                        </a>
                        
                        <div class="flex items-center space-x-4">
                            <button type="button" 
                                    onclick="previewReuniao()" 
                                    class="px-6 py-3 border-2 border-blue-300 text-primary rounded-xl hover:bg-blue-50 hover:border-blue-400 font-medium flex items-center transition-all duration-200">
                                <i class="fas fa-eye mr-2"></i>
                                Visualizar
                            </button>
                            
                            <!-- Botão de Salvar -->
                            <button type="submit" 
                                    id="saveButton"
                                    class="save-button-elegant px-8 py-3 text-white rounded-xl font-semibold flex items-center text-lg transform hover:scale-105 transition-all duration-300"
                                    style="background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%); border: 2px solid var(--primary-color); box-shadow: 0 4px 15px rgba(var(--primary-color-rgb), 0.4); opacity: 1;">
                                <i class="fas fa-save mr-3 text-lg"></i>
                                Salvar Reunião
                            </button>
                        </div>
                    </div>
                    
                    <!-- Aviso sobre o botão -->
                    <div class="mt-4 text-center">
                        <p style="color: var(--secondary-color);">
                            <i class="fas fa-info-circle mr-2 text-primary"></i>
                            Preencha todos os campos obrigatórios e clique em "Salvar Reunião"
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast para feedback -->
<div id="toast" class="fixed top-4 right-4 z-50 hidden">
    <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-4 max-w-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i id="toastIcon" class="text-xl"></i>
            </div>
            <div class="ml-3">
                <p id="toastMessage" class="text-sm font-medium text-gray-900"></p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Atualizar resumo quando participantes mudarem
const participantesTextarea = document.getElementById('participantes');
if (participantesTextarea) {
    participantesTextarea.addEventListener('input', updateResumo);
}

// Atualizar resumo da reunião
function updateResumo() {
    // Calcular duração
    const inicio = document.getElementById('data_inicio').value;
    const fim = document.getElementById('data_fim').value;
    
    if (inicio && fim) {
        const dataInicio = new Date(inicio);
        const dataFim = new Date(fim);
        const duracao = (dataFim - dataInicio) / (1000 * 60); // em minutos
        
        if (duracao > 0) {
            const horas = Math.floor(duracao / 60);
            const minutos = duracao % 60;
            let duracaoTexto = '';
            
            if (horas > 0) {
                duracaoTexto += `${horas}h`;
            }
            if (minutos > 0) {
                duracaoTexto += ` ${minutos}min`;
            }
            
            document.getElementById('resumoDuracao').textContent = `Duração: ${duracaoTexto}`;
        } else {
            document.getElementById('resumoDuracao').textContent = 'Data de término deve ser após o início';
        }
    } else {
        document.getElementById('resumoDuracao').textContent = 'Selecione as datas para ver a duração';
    }
    
    // Contar participantes
    const participantesTextarea = document.getElementById('participantes');
    let count = 0;
    if (participantesTextarea && participantesTextarea.value.trim()) {
        const emails = participantesTextarea.value.split(/[,\n\r]+/);
        emails.forEach(email => {
            if (email.trim()) count++;
        });
    }
    
    // Adicionar licenciado se selecionado
    if (document.getElementById('licenciado_id').value) {
        count++;
    }
    
    document.getElementById('resumoParticipantes').textContent = `${count} participante(s)`;
    
    // Formato da reunião
    const formatoSelecionado = document.querySelector('input[name="tipo_reuniao"]:checked');
    if (formatoSelecionado) {
        const formatos = {
            'online': 'Online (Google Meet)',
            'presencial': 'Presencial',
            'hibrida': 'Híbrida'
        };
        document.getElementById('resumoFormato').textContent = `Formato: ${formatos[formatoSelecionado.value]}`;
    }
}

// Auto-adicionar email do licenciado e buscar dados do banco
document.getElementById('licenciado_id').addEventListener('change', function() {
    const licenciadoId = this.value;
    const licenciadoInfo = document.getElementById('licenciadoInfo');
    
    if (licenciadoId) {
        // Mostrar loading
        licenciadoInfo.classList.remove('hidden');
        document.getElementById('licenciadoNome').textContent = 'Carregando...';
        document.getElementById('licenciadoEmail').textContent = '';
        
        // Buscar dados do licenciado no banco
        fetch(`/agenda/licenciado/${licenciadoId}/details`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const licenciado = data.licenciado;
                    
                    // Mostrar informações do licenciado
                    document.getElementById('licenciadoNome').textContent = licenciado.razao_social;
                    document.getElementById('licenciadoEmail').textContent = `📧 ${licenciado.email}`;
                    
                    // Verificar se email já foi adicionado
                    const participantesTextarea = document.getElementById('participantes');
                    const currentValue = participantesTextarea.value.trim();
                    let emailJaAdicionado = false;
                    
                    if (currentValue) {
                        const emails = currentValue.split(/[,\n\r]+/);
                        emailJaAdicionado = emails.some(email => email.trim() === licenciado.email);
                    }
                    
                    if (!emailJaAdicionado) {
                        // Adicionar email ao textarea de participantes
                        const participantesTextarea = document.getElementById('participantes');
                        const currentValue = participantesTextarea.value.trim();
                        
                        if (currentValue) {
                            // Se já tem conteúdo, adicionar vírgula e o novo email
                            participantesTextarea.value = currentValue + ', ' + licenciado.email;
                        } else {
                            // Se está vazio, apenas adicionar o email
                            participantesTextarea.value = licenciado.email;
                        }
                    }
                    
                    // Animação de sucesso
                    licenciadoInfo.classList.add('animate-pulse');
                    setTimeout(() => {
                        licenciadoInfo.classList.remove('animate-pulse');
                    }, 1000);
                    
                } else {
                    document.getElementById('licenciadoNome').textContent = 'Erro ao carregar dados';
                    document.getElementById('licenciadoEmail').textContent = data.message || 'Tente novamente';
                    showToast('Erro ao carregar dados do licenciado', 'error');
                }
            })
            .catch(error => {
                console.error('Erro ao buscar licenciado:', error);
                document.getElementById('licenciadoNome').textContent = 'Erro de conexão';
                document.getElementById('licenciadoEmail').textContent = 'Verifique sua internet';
                showToast('Erro de conexão ao buscar licenciado', 'error');
            });
    } else {
        // Esconder informações se nenhum licenciado selecionado
        licenciadoInfo.classList.add('hidden');
    }
    
    updateResumo();
});

// Event listeners para atualizar resumo
document.getElementById('data_inicio').addEventListener('change', updateResumo);
document.getElementById('data_fim').addEventListener('change', updateResumo);
document.querySelectorAll('input[name="tipo_reuniao"]').forEach(radio => {
    radio.addEventListener('change', function() {
        updateResumo();
        
        // Mostrar ícone de check para o selecionado
        document.querySelectorAll('.format-card .fa-check-circle').forEach(icon => {
            icon.style.opacity = '0';
        });
        
        const selectedCard = this.closest('.format-card');
        const checkIcon = selectedCard.querySelector('.fa-check-circle');
        if (checkIcon) {
            checkIcon.style.opacity = '1';
        }
    });
});

// Validação de data
document.getElementById('data_inicio').addEventListener('change', function() {
    const dataFim = document.getElementById('data_fim');
    if (this.value) {
        dataFim.min = this.value;
        
        // Se data fim não estiver definida, definir 1 hora após o início
        if (!dataFim.value) {
            const inicio = new Date(this.value);
            inicio.setHours(inicio.getHours() + 1);
            dataFim.value = inicio.toISOString().slice(0, 16);
        }
    }
});

// Definir data/hora padrão (próxima hora cheia)
document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();
    now.setMinutes(0, 0, 0); // Zerar minutos e segundos
    now.setHours(now.getHours() + 1); // Próxima hora
    
    const inicioInput = document.getElementById('data_inicio');
    const fimInput = document.getElementById('data_fim');
    
    if (!inicioInput.value) {
        inicioInput.value = now.toISOString().slice(0, 16);
    }
    
    if (!fimInput.value) {
        const fim = new Date(now);
        fim.setHours(fim.getHours() + 1);
        fimInput.value = fim.toISOString().slice(0, 16);
    }
    
    updateResumo();
    
    // Parar animação do botão quando usuário interagir
    const saveButton = document.getElementById('saveButton');
    const form = document.getElementById('agendaForm');
    
    // Remover animação pulse inicial após 3 segundos
    setTimeout(() => {
        if (saveButton) {
            saveButton.classList.remove('animate-pulse');
        }
    }, 3000);
    
    // Adicionar efeito sutil quando usuário preencher
    form.addEventListener('input', function() {
        if (saveButton) {
            saveButton.classList.remove('animate-pulse');
            saveButton.style.boxShadow = '0 6px 20px rgba(var(--primary-color-rgb), 0.6)';
        }
    });
    
    // Animação de loading ao submeter
    form.addEventListener('submit', function(e) {
        if (saveButton) {
            saveButton.innerHTML = `
                <i class="fas fa-spinner fa-spin mr-3 text-xl"></i>
                🔄 SALVANDO...
            `;
            saveButton.disabled = true;
            saveButton.classList.remove('animate-pulse', 'animate-bounce-slow');
            saveButton.classList.add('opacity-75');
        }
    });
});

// Toast notification
function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    const toastIcon = document.getElementById('toastIcon');
    const toastMessage = document.getElementById('toastMessage');
    
    if (type === 'success') {
        toastIcon.className = 'fas fa-check-circle " style="color: var(--accent-color);" text-xl';
    } else if (type === 'error') {
        toastIcon.className = 'fas fa-exclamation-circle text-red-600 text-xl';
    } else {
        toastIcon.className = 'fas fa-info-circle " style="color: var(--primary-color);" text-xl';
    }
    
    toastMessage.textContent = message;
    toast.classList.remove('hidden');
    
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 5000);
}

// Preview da reunião
function previewReuniao() {
    const titulo = document.getElementById('titulo').value;
    const inicio = document.getElementById('data_inicio').value;
    const fim = document.getElementById('data_fim').value;
    
    if (!titulo || !inicio || !fim) {
        showToast('Preencha pelo menos o título e as datas para visualizar', 'error');
        return;
    }
    
    const formato = document.querySelector('input[name="tipo_reuniao"]:checked')?.value || 'online';
    const descricao = document.getElementById('descricao').value;
    
    let preview = `📅 ${titulo}\n\n`;
    preview += `🕐 ${new Date(inicio).toLocaleString('pt-BR')} - ${new Date(fim).toLocaleString('pt-BR')}\n`;
    preview += `📍 Formato: ${formato.charAt(0).toUpperCase() + formato.slice(1)}\n`;
    
    if (descricao) {
        preview += `\n📝 Observações:\n${descricao}`;
    }
    
    alert(preview);
}
</script>
@endpush

@push('styles')
<style>
    /* Animações personalizadas */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes bounce {
        0%, 20%, 53%, 80%, 100% { transform: translateY(0); }
        40%, 43% { transform: translateY(-10px); }
        70% { transform: translateY(-5px); }
        90% { transform: translateY(-2px); }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.8s ease-out;
    }
    
    .animate-slideUp {
        animation: slideUp 0.6s ease-out;
    }
    
    .animate-bounce {
        animation: bounce 2s infinite;
    }
    
    .animate-bounce-slow {
        animation: bounce-slow 3s infinite;
    }
    
    @keyframes bounce-slow {
        0%, 20%, 53%, 80%, 100% { transform: translateY(0); }
        40%, 43% { transform: translateY(-5px); }
        70% { transform: translateY(-2px); }
        90% { transform: translateY(-1px); }
    }
    
    /* Gradientes e efeitos */
    .bg-gradient-to-br {
        background-image: linear-gradient(to bottom right, var(--tw-gradient-stops));
    }
    
    .bg-clip-text {
        -webkit-background-clip: text;
        background-clip: text;
    }
    
    .text-transparent {
        color: transparent;
    }
    
    /* Efeitos de hover melhorados */
    .group:hover .group-hover\\:scale-105 {
        transform: scale(1.05);
    }
    
    .group:hover .group-hover\\:shadow-lg {
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    /* Transições suaves */
    .transition-all {
        transition: all 0.3s ease-in-out;
    }
    
    .transition-colors {
        transition: color 0.2s ease-in-out, background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
    }
    
    /* Hover effects para os cards de formato */
    input[type="radio"]:checked + div {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(var(--primary-color-rgb), 0.15);
        transform: translateY(-2px);
    }
    
    .format-card {
        transition: all 0.3s ease-in-out;
    }
    
    .format-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    /* Estilo para inputs focus melhorado */
    input:focus, textarea:focus, select:focus {
        outline: none;
        box-shadow: 0 0 0 4px rgba(var(--primary-color-rgb), 0.1);
        transform: translateY(-1px);
    }
    
    /* Efeitos de backdrop blur */
    .backdrop-blur-sm {
        backdrop-filter: blur(4px);
    }
    
    /* Inputs com gradiente */
    .gradient-input {
        background: #ffffff !important;
        border: 2px solid #d1d5db !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important;
    }
    
    .gradient-input:focus {
        background: #ffffff !important;
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 4px rgba(var(--primary-color-rgb), 0.1) !important;
    }
    
    /* Melhorar todos os inputs */
    input, textarea, select {
        background: #ffffff !important;
        border: 2px solid #d1d5db !important;
        opacity: 1 !important;
    }
    
    input:focus, textarea:focus, select:focus {
        background: #ffffff !important;
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 4px rgba(var(--primary-color-rgb), 0.1) !important;
        opacity: 1 !important;
    }
    
    /* Botões com efeitos */
    .btn-gradient {
        background: linear-gradient(135deg, var(--primary-color) 0%, #7c3aed 100%) !important;
        border: 2px solid var(--primary-color) !important;
        box-shadow: 0 6px 20px rgba(29, 78, 216, 0.5) !important;
        transition: all 0.3s ease-in-out !important;
        opacity: 1 !important;
    }
    
    .btn-gradient:hover {
        background: linear-gradient(135deg, var(--primary-color) 0%, #6d28d9 100%) !important;
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 30px rgba(29, 78, 216, 0.6) !important;
        border-color: var(--primary-color) !important;
    }
    
    .btn-gradient:active {
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 15px rgba(29, 78, 216, 0.4) !important;
    }
    
    /* Botão elegante de salvar */
    .save-button-elegant {
        background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%) !important;
        border: 2px solid var(--primary-color) !important;
        box-shadow: 0 4px 15px rgba(var(--primary-color-rgb), 0.4) !important;
        opacity: 1 !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
        position: relative !important;
        overflow: hidden !important;
    }
    
    .save-button-elegant::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.3s ease;
    }
    
    .save-button-elegant:hover::before {
        left: 100%;
    }
    
    .save-button-elegant:hover {
        background: linear-gradient(135deg, var(--primary-color) 0%, #7c3aed 100%) !important;
        transform: translateY(-2px) scale(1.02) !important;
        box-shadow: 0 6px 20px rgba(var(--primary-color-rgb), 0.5) !important;
        border-color: var(--primary-color) !important;
    }
    
    .save-button-elegant:active {
        transform: translateY(-1px) scale(1.01) !important;
        box-shadow: 0 3px 10px rgba(var(--primary-color-rgb), 0.4) !important;
    }
    
    /* Cards com glassmorphism */
    .glass-card {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(15px) !important;
        border: 2px solid rgba(var(--primary-color-rgb), 0.2) !important;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1) !important;
    }
    
    /* Animação para o resumo */
    .resumo-item {
        transition: all 0.3s ease-in-out;
    }
    
    .resumo-item:hover {
        transform: translateX(5px);
        color: var(--primary-color);
    }
    
    /* Loading spinner */
    .spinner {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Responsividade melhorada */
    @media (max-width: 768px) {
        .grid-cols-1.lg\\:grid-cols-2 {
            grid-template-columns: 1fr;
        }
        
        .text-4xl {
            font-size: 2rem;
        }
        
        .text-xl {
            font-size: 1.125rem;
        }
        
        .p-8 {
            padding: 1.5rem;
        }
    }
    
    /* Efeitos de parallax suave */
    .parallax-bg {
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
    
    /* Sombras personalizadas */
    .shadow-custom {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .shadow-custom-lg {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
        
        /* Estilos dinâmicos do dashboard */
        .dashboard-header {
            background: var(--background-color);
            color: var(--text-color);
        }
        .stat-card {
            background: var(--primary-gradient);
            color: var(--primary-text);
        }
        .progress-bar {
            background: var(--accent-gradient);
        }
    </style>
@endpush
