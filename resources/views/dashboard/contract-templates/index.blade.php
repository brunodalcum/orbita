
<x-simple-branding />
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Templates de Contrato - DSPay</title>
    
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endif

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
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-file-code text-primary mr-3"></i>
                            Templates de Contrato
                        </h1>
                        <p class="text-gray-600 mt-1">Gerencie os modelos de contrato do sistema</p>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('contract-templates.create') }}" 
                           class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Novo Template
                        </a>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
            <!-- Filtros e Busca -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   placeholder="Buscar templates..." 
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-primary">
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-primary">
                            <option value="">Todos os status</option>
                            <option value="active">Ativos</option>
                            <option value="inactive">Inativos</option>
                        </select>
                        
                        <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-primary">
                            <option value="">Ordenar por</option>
                            <option value="name">Nome</option>
                            <option value="created_at">Data de criação</option>
                            <option value="updated_at">Última atualização</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Templates List -->
            @if($templates->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="divide-y divide-gray-200">
                        @foreach($templates as $template)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <!-- Template Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-file-contract text-primary text-lg"></i>
                                                </div>
                                            </div>
                                            
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-2 mb-1">
                                                    <h3 class="text-lg font-semibold text-gray-900 truncate">
                                                        {{ $template->name }}
                                                    </h3>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                                        {{ $template->is_active ? 'Ativo' : 'Inativo' }}
                                                    </span>
                                                </div>
                                                
                                                @if($template->description)
                                                    <p class="text-sm text-gray-600 mb-2">
                                                        {{ Str::limit($template->description, 120) }}
                                                    </p>
                                                @endif
                                                
                                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-code mr-1"></i>
                                                        v{{ $template->version }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="fas fa-calendar mr-1"></i>
                                                        {{ $template->created_at->format('d/m/Y H:i') }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="fas fa-file-alt mr-1"></i>
                                                        {{ $template->contracts_count ?? 0 }} contratos
                                                    </span>
                                                    @php
                                                        $variables = json_decode($template->variables ?? '[]', true);
                                                    @endphp
                                                    <span class="flex items-center">
                                                        <i class="fas fa-tags mr-1"></i>
                                                        {{ count($variables) }} variáveis
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex items-center space-x-2 ml-6">
                                        <!-- Preview Button -->
                                        <button onclick="previewTemplate({{ $template->id }})" 
                                                class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors">
                                            <i class="fas fa-eye mr-2"></i>
                                            Preview
                                        </button>
                                        
                                        <!-- View Button -->
                                        <a href="{{ route('contract-templates.show', $template) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                                            <i class="fas fa-file-alt mr-2"></i>
                                            Detalhes
                                        </a>
                                        
                                        <!-- Edit Button -->
                                        <a href="{{ route('contract-templates.edit', $template) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-green-100 hover:bg-green-200 text-green-700 text-sm font-medium rounded-lg transition-colors">
                                            <i class="fas fa-edit mr-2"></i>
                                            Editar
                                        </a>
                                        
                                        <!-- Duplicate Button -->
                                        <form action="{{ route('contract-templates.duplicate', $template) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="inline-flex items-center px-4 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 text-sm font-medium rounded-lg transition-colors">
                                                <i class="fas fa-copy mr-2"></i>
                                                Duplicar
                                            </button>
                                        </form>
                                        
                                        <!-- Status Toggle -->
                                        <form action="{{ route('contract-templates.toggle-status', $template) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $template->is_active ? 'bg-orange-100 hover:bg-orange-200 text-orange-700' : 'bg-emerald-100 hover:bg-emerald-200 text-emerald-700' }}">
                                                <i class="fas {{ $template->is_active ? 'fa-pause' : 'fa-play' }} mr-2"></i>
                                                {{ $template->is_active ? 'Desativar' : 'Ativar' }}
                                            </button>
                                        </form>
                                        
                                        <!-- Delete Button (only if no contracts) -->
                                        @if($template->contracts_count == 0)
                                            <form action="{{ route('contract-templates.destroy', $template) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Tem certeza que deseja excluir este template?')" 
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-lg transition-colors">
                                                    <i class="fas fa-trash mr-2"></i>
                                                    Excluir
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                        @endforeach
                    </div>
                </div>
            @else
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <div class="w-24 h-24 mx-auto mb-4 text-gray-300">
                                <i class="fas fa-file-contract text-6xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum template encontrado</h3>
                            <p class="text-gray-600 mb-6">Comece criando seu primeiro template de contrato.</p>
                            <a href="{{ route('contract-templates.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Criar Template
                            </a>
                        </div>
                    </div>
            @endif

            <!-- Pagination -->
            @if($templates->hasPages())
                <div class="mt-8">
                    {{ $templates->links() }}
                </div>
            @endif
        </main>
    </div>

    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" 
             x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" 
             x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif
            </main>
        </div>
    </div>

    <!-- Modal Preview -->
    <div id="previewModal" 
         class="fixed inset-0 z-50 overflow-y-auto hidden" 
         onclick="closePreviewModal(event)">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>
            
            <div class="inline-block w-full max-w-6xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between mb-4 flex-shrink-0">
                    <h3 class="text-lg font-semibold text-gray-900" id="previewTitle">Preview do Template</h3>
                    <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto border border-gray-200 rounded-lg p-6 bg-white">
                    <div class="prose prose-sm max-w-none" id="previewContent">
                        <div class="text-center py-8">
                            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                            <p class="text-gray-600 mt-2">Carregando preview...</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 flex justify-end">
                    <button onclick="closePreviewModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function previewTemplate(templateId) {
            const modal = document.getElementById('previewModal');
            const content = document.getElementById('previewContent');
            const title = document.getElementById('previewTitle');
            
            // Mostrar modal com loading
            modal.classList.remove('hidden');
            content.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                    <p class="text-gray-600 mt-2">Carregando preview...</p>
                </div>
            `;
            
            try {
                const response = await fetch(`/contract-templates/${templateId}/preview`);
                const data = await response.json();
                
                if (data.success) {
                    content.innerHTML = data.preview;
                    title.textContent = `Preview: ${data.template_name || 'Template'}`;
                } else {
                    content.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-2xl text-red-400"></i>
                            <p class="text-red-600 mt-2">Erro ao carregar preview: ${data.error}</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Erro:', error);
                content.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-400"></i>
                        <p class="text-red-600 mt-2">Erro ao carregar preview</p>
                    </div>
                `;
            }
        }
        
        function closePreviewModal(event) {
            if (!event || event.target === event.currentTarget || event.target.closest('button')) {
                document.getElementById('previewModal').classList.add('hidden');
            }
        }
        
        // Fechar modal com ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closePreviewModal();
            }
        });
    </script>
</body>
</html>
