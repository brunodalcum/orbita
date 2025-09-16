<x-dynamic-branding />
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Contrato - Etapa 1 - DSPay</title>
    
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Branding Dinâmico -->
    
    @endif
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        <x-dynamic-sidebar />
        
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="dashboard-header bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('contracts.index') }}"
                           class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Voltar
                        </a>
                        <div class="h-6 border-l border-gray-300"></div>
                        <div>
                            <h1 class="text-2xl font-bold " style="color: var(--text-color);"">
                                <i class="fas fa-file-contract " style="color: var(--primary-color);" mr-3"></i>
                                Gerar Contrato
                            </h1>
                            <p class="" style="color: var(--secondary-color);" mt-1">Etapa 1 de 3 • Selecionar Licenciado</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Progress Bar -->
            <div class="bg-white border-b px-6 py-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-primary">Etapa 1 de 3</span>
                    <span class="text-sm text-gray-500">33% concluído</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-primary h-2 rounded-full" style="width: 33%"></div>
                </div>
            </div>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-4xl mx-auto">
                    <!-- Steps Overview -->
                    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Fluxo de Geração de Contrato</h2>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium">1</div>
                                <span class="ml-2 text-sm font-medium text-primary">Selecionar Licenciado</span>
                            </div>
                            <div class="flex-1 h-px bg-gray-300"></div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">2</div>
                                <span class="ml-2 text-sm text-gray-500">Escolher Template</span>
                            </div>
                            <div class="flex-1 h-px bg-gray-300"></div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">3</div>
                                <span class="ml-2 text-sm text-gray-500">Gerar Contrato</span>
                            </div>
                        </div>
                    </div>

                    <!-- Licensee Selection -->
                    <div class="bg-white rounded-lg shadow-sm border">
                        <div class="p-6 border-b">
                            <h3 class="text-lg font-semibold text-gray-800">Selecionar Licenciado</h3>
                            <p class="" style="color: var(--secondary-color);" mt-1">Escolha o licenciado para o qual deseja gerar o contrato</p>
                            <div class="mt-3 flex items-center space-x-4 text-xs text-gray-500">
                                <span class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-1"></div>
                                    Ativo
                                </span>
                                <span class="flex items-center">
                                    <div class="w-2 h-2 bg-primary rounded-full mr-1"></div>
                                    Aprovado
                                </span>
                                <span class="flex items-center">
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full mr-1"></div>
                                    Pendente
                                </span>
                                <span class="flex items-center">
                                    <div class="w-2 h-2 bg-red-500 rounded-full mr-1"></div>
                                    Recusado
                                </span>
                                <span class="text-gray-400">• Mostrando todos os licenciados cadastrados</span>
                            </div>
                        </div>

                        <div class="p-6">
                            <form method="POST" action="{{ route('contracts.generate.step1') }}">
                                @csrf
                                
                                <!-- Select de Licenciados -->
                                <div class="mb-6">
                                    <label for="licenciado_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-user mr-2"></i>Selecionar Licenciado
                                    </label>
                                    <select name="licenciado_id" id="licenciado_id" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-primary text-sm"
                                            onchange="showLicenseeInfo(this.value)">
                                        <option value="">-- Selecione um licenciado --</option>
                                        @foreach($licenciados as $licenciado)
                                            <option value="{{ $licenciado->id }}" 
                                                    data-info="{{ json_encode([
                                                        'id' => $licenciado->id,
                                                        'razao_social' => $licenciado->razao_social,
                                                        'nome_fantasia' => $licenciado->nome_fantasia,
                                                        'cnpj_cpf' => $licenciado->cnpj_cpf,
                                                        'email' => $licenciado->email,
                                                        'telefone' => $licenciado->telefone,
                                                        'cidade' => $licenciado->cidade,
                                                        'estado' => $licenciado->estado,
                                                        'endereco' => $licenciado->endereco,
                                                        'status' => $licenciado->status
                                                    ]) }}">
                                                {{ $licenciado->razao_social ?: $licenciado->nome_fantasia }} 
                                                ({{ $licenciado->cnpj_cpf }}) 
                                                - {{ ucfirst($licenciado->status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Informações do Licenciado Selecionado -->
                                <div id="licensee-info" class="hidden mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <h4 class="font-medium text-blue-900 mb-3">
                                        <i class="fas fa-info-circle mr-2"></i>Informações do Licenciado Selecionado
                                    </h4>
                                    <div id="licensee-details" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
                                        <!-- Será preenchido via JavaScript -->
                                    </div>
                                </div>

                                <!-- Continue Button -->
                                <div class="flex justify-end">
                                    <button type="submit" 
                                            id="continue-btn"
                                            disabled
                                            class="inline-flex items-center px-6 py-3 bg-primary text-white font-medium rounded-lg hover:bg-primary-dark focus:ring-4 focus:ring-blue-200 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                        Continuar para Etapa 2
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Função para mostrar informações do licenciado selecionado
        function showLicenseeInfo(licenciadoId) {
            const continueBtn = document.getElementById('continue-btn');
            const licenseeInfo = document.getElementById('licensee-info');
            const licenseeDetails = document.getElementById('licensee-details');
            
            if (!licenciadoId) {
                // Nenhum licenciado selecionado
                licenseeInfo.classList.add('hidden');
                continueBtn.disabled = true;
                return;
            }
            
            // Buscar dados do licenciado selecionado
            const selectElement = document.getElementById('licenciado_id');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const licenciadoData = JSON.parse(selectedOption.getAttribute('data-info'));
            
            // Definir cores por status
            const statusColors = {
                'ativo': 'text-green-600 bg-green-100',
                'aprovado': 'text-primary bg-blue-100',
                'pendente': 'text-yellow-600 bg-yellow-100',
                'recusado': 'text-red-600 bg-red-100'
            };
            
            const statusClass = statusColors[licenciadoData.status] || 'text-gray-600 bg-gray-100';
            
            // Preencher informações
            licenseeDetails.innerHTML = `
                <div>
                    <p class="mb-2"><strong>Nome/Razão Social:</strong><br>
                       ${licenciadoData.razao_social || licenciadoData.nome_fantasia}</p>
                    <p class="mb-2"><strong>CNPJ/CPF:</strong><br>
                       ${licenciadoData.cnpj_cpf}</p>
                    <p class="mb-2"><strong>Status:</strong><br>
                       <span class="px-2 py-1 rounded-full text-xs font-medium ${statusClass}">
                           ${licenciadoData.status.toUpperCase()}
                       </span></p>
                </div>
                <div>
                    <p class="mb-2"><strong>Email:</strong><br>
                       ${licenciadoData.email || 'N/A'}</p>
                    <p class="mb-2"><strong>Telefone:</strong><br>
                       ${licenciadoData.telefone || 'N/A'}</p>
                    <p class="mb-2"><strong>Localização:</strong><br>
                       ${licenciadoData.cidade || 'N/A'}, ${licenciadoData.estado || 'N/A'}</p>
                </div>
            `;
            
            // Mostrar informações e habilitar botão
            licenseeInfo.classList.remove('hidden');
            continueBtn.disabled = false;
            
            console.log('✅ Licenciado selecionado:', licenciadoData);
        }
        
        // Inicialização
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Sistema de seleção de licenciados inicializado');
            console.log('📊 Total de licenciados disponíveis:', {{ $licenciados->count() }});
        });
    </script>
</body>
</html>
