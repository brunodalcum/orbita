
<x-dynamic-branding />
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modelos de E-mail - DSPay Orbita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar Dinâmico -->
        <x-dynamic-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Modelos de E-mail</h1>
                        <p class="text-gray-600">Gerencie seus modelos de e-mail marketing</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button onclick="openAddModeloModal()" class="bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <i class="fas fa-plus mr-2"></i>
                            Novo Modelo
                        </button>
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-bell"></i>
                        </button>
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-cog"></i>
                        </button>
                        <form method="POST" action="{{ route('logout.custom') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="container mx-auto px-4 py-8">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Modelos de E-mail</h1>
                            <p class="text-gray-600">Crie e gerencie modelos para suas campanhas de marketing</p>
                        </div>
                        <div class="flex space-x-4">
                            <a href="{{ route('dashboard.marketing') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Voltar
                            </a>
                        </div>
                    </div>

                    <!-- Modelos Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($modelos as $modelo)
                        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    @if($modelo->tipo === 'lead')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <i class="fas fa-user-plus mr-1"></i>Lead
                                        </span>
                                    @elseif($modelo->tipo === 'licenciado')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-user-tie mr-1"></i>Licenciado
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            <i class="fas fa-globe mr-1"></i>Geral
                                        </span>
                                    @endif
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="editModelo({{ $modelo->id }})" class="text-primary hover:text-blue-900 p-1 rounded hover:bg-blue-100 transition-colors duration-200" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteModelo({{ $modelo->id }})" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100 transition-colors duration-200" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $modelo->nome }}</h3>
                            <p class="text-sm text-gray-600 mb-3">{{ $modelo->assunto }}</p>
                            
                            <div class="text-xs text-gray-500 mb-4">
                                <p>Criado por: {{ $modelo->user->name ?? 'Usuário' }}</p>
                                <p>Data: {{ $modelo->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button onclick="previewModelo({{ $modelo->id }})" class="flex-1 bg-blue-50 hover:bg-blue-100 text-primary px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i>Preview
                                </button>
                                <button onclick="useModelo({{ $modelo->id }})" class="flex-1 bg-green-50 hover:bg-green-100 text-green-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-paper-plane mr-1"></i>Usar
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full">
                            <div class="text-center text-gray-500 py-12">
                                <i class="fas fa-file-alt text-6xl mb-4"></i>
                                <h3 class="text-xl font-medium mb-2">Nenhum modelo criado</h3>
                                <p class="text-sm">Comece criando seu primeiro modelo de e-mail!</p>
                                <button onclick="openAddModeloModal()" class="mt-4 bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                                    <i class="fas fa-plus mr-2"></i>
                                    Criar Primeiro Modelo
                                </button>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add/Edit Modelo Modal -->
    <div id="modeloModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-0 border-0 w-11/12 md:w-3/4 lg:w-2/3 shadow-2xl rounded-2xl bg-white overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-file-alt text-white text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white" id="modalTitle">Novo Modelo</h3>
                    </div>
                    <button onclick="closeModeloModal()" class="text-white/80 hover:text-white transition-colors duration-200 p-2 hover:bg-white/20 rounded-full">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <form id="modeloForm" class="space-y-6">
                    @csrf
                    <input type="hidden" id="modelo_id" name="id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="nome" class="block text-sm font-semibold text-gray-700 mb-2">Nome do Modelo *</label>
                            <input type="text" id="nome" name="nome" required 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                                   placeholder="Ex: Boas-vindas DSPay">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="tipo" class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Destinatário *</label>
                            <select id="tipo" name="tipo" required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                                <option value="">Selecione o tipo...</option>
                                <option value="lead">Leads</option>
                                <option value="licenciado">Licenciados</option>
                                <option value="geral">Geral</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="assunto" class="block text-sm font-semibold text-gray-700 mb-2">Assunto do E-mail *</label>
                        <input type="text" id="assunto" name="assunto" required 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                               placeholder="Ex: 🚀 Bem-vindo à DSPay — sua nova oportunidade começa agora!">
                    </div>
                    
                    <div class="space-y-2">
                        <label for="conteudo" class="block text-sm font-semibold text-gray-700 mb-2">Conteúdo do E-mail *</label>
                        <textarea id="conteudo" name="conteudo" rows="12" required 
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white resize-none" 
                                  placeholder="Digite o conteúdo do seu e-mail aqui..."></textarea>
                        <p class="text-xs text-gray-500">Use variáveis como {{nome}}, {{empresa}} para personalização</p>
                    </div>
                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex justify-between">
                    <button onclick="closeModeloModal()" 
                            class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all duration-200 font-medium flex items-center space-x-2 shadow-lg">
                        <i class="fas fa-times"></i>
                        <span>Cancelar</span>
                    </button>
                    <button onclick="saveModelo()" 
                            class="px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-xl hover:from-green-700 hover:to-teal-700 transition-all duration-200 font-medium flex items-center space-x-2 shadow-lg">
                        <i class="fas fa-save"></i>
                        <span>Salvar Modelo</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }
        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateX(4px);
        }
    </style>

    <script>
        function openAddModeloModal() {
            document.getElementById('modalTitle').textContent = 'Novo Modelo';
            document.getElementById('modeloForm').reset();
            document.getElementById('modelo_id').value = '';
            document.getElementById('modeloModal').classList.remove('hidden');
        }

        function closeModeloModal() {
            document.getElementById('modeloModal').classList.add('hidden');
        }

        function editModelo(id) {
            // Implementar edição do modelo
            alert(`Editar modelo ${id} será implementado aqui!`);
        }

        function deleteModelo(id) {
            if (confirm('Tem certeza que deseja excluir este modelo?')) {
                // Implementar exclusão do modelo
                alert(`Excluir modelo ${id} será implementado aqui!`);
            }
        }

        function previewModelo(id) {
            // Implementar preview do modelo
            alert(`Preview do modelo ${id} será implementado aqui!`);
        }

        function useModelo(id) {
            // Implementar uso do modelo
            alert(`Usar modelo ${id} será implementado aqui!`);
        }

        function saveModelo() {
            const form = document.getElementById('modeloForm');
            const formData = new FormData(form);
            
            fetch('{{ route("dashboard.marketing.modelos.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeModeloModal();
                    location.reload();
                } else {
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao salvar modelo');
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('modeloModal');
            if (event.target === modal) {
                closeModeloModal();
            }
        }
    </script>
</body>
</html>

