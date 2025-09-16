<x-simple-branding />
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Contrato - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Branding Dinâmico -->

    
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
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar Dinâmico -->
        <x-dynamic-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="dashboard-header bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <a href="{{ route('contracts.index') }}" class="mr-4 hover:opacity-80" style="color: var(--secondary-color);">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold" style="color: var(--text-color);">
                                <i class="fas fa-plus mr-3" style="color: var(--primary-color);"></i>
                                Novo Contrato
                            </h1>
                            <p class="mt-1" style="color: var(--secondary-color);">Criar um novo contrato para licenciado</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-2xl mx-auto">
                    <div class="card rounded-xl p-8">
                        <form action="{{ route('contracts.store') }}" method="POST">
                            @csrf

                            <div class="mb-6">
                                <label for="licenciado_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Licenciado <span class="text-red-500">*</span>
                                </label>
                                <select name="licenciado_id" id="licenciado_id" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('licenciado_id') border-red-500 @enderror">
                                    <option value="">Selecione um licenciado</option>
                                    @foreach($licenciados as $licenciado)
                                        <option value="{{ $licenciado->id }}" {{ old('licenciado_id') == $licenciado->id ? 'selected' : '' }}>
                                            {{ $licenciado->razao_social }} ({{ $licenciado->nome_fantasia }}) - {{ $licenciado->cnpj_cpf }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('licenciado_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                
                                @if($licenciados->isEmpty())
                                    <p class="text-yellow-600 text-sm mt-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Não há licenciados disponíveis. Todos os licenciados já possuem contratos ativos.
                                    </p>
                                @endif
                            </div>

                            <div class="mb-6">
                                <label for="observacoes_admin" class="block text-sm font-medium text-gray-700 mb-2">
                                    Observações Iniciais
                                </label>
                                <textarea name="observacoes_admin" id="observacoes_admin" rows="4" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('observacoes_admin') border-red-500 @enderror"
                                          placeholder="Adicione observações ou instruções especiais para este contrato...">{{ old('observacoes_admin') }}</textarea>
                                @error('observacoes_admin')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Informações sobre o processo -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                                <h3 class="text-lg font-medium text-blue-900 mb-3">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Sobre o Processo de Contrato
                                </h3>
                                <div class="text-sm text-blue-800 space-y-2">
                                    <p><strong>1. Documentos Pendentes:</strong> O licenciado será notificado para enviar os documentos necessários.</p>
                                    <p><strong>2. Análise:</strong> Você poderá revisar e aprovar/rejeitar cada documento enviado.</p>
                                    <p><strong>3. Contrato:</strong> Após aprovação dos documentos, o contrato será gerado e enviado automaticamente.</p>
                                    <p><strong>4. Assinatura:</strong> O licenciado receberá um link para assinatura eletrônica.</p>
                                    <p><strong>5. Liberação:</strong> Após assinatura, o licenciado será automaticamente liberado no sistema.</p>
                                </div>
                            </div>

                            <!-- Documentos necessários -->
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-3">
                                    <i class="fas fa-file-alt mr-2"></i>
                                    Documentos Necessários
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700">
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        RG (Frente e Verso)
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        CPF
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        Comprovante de Residência
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        CNPJ (se empresa)
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        Contrato Social (se empresa)
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        Comprovante de Renda
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        Referências Bancárias
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-4">
                                <a href="{{ route('contracts.index') }}" 
                                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Cancelar
                                </a>
                                <button type="submit" 
                                        class="px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors {{ $licenciados->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $licenciados->isEmpty() ? 'disabled' : '' }}>
                                    <i class="fas fa-plus mr-2"></i>Criar Contrato
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-md z-50">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('.fixed.top-4').remove();
            }, 5000);
        </script>
    @endif
</body>
</html>
