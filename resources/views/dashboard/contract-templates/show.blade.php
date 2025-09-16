
<x-dynamic-branding />
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $template->name }} - Template de Contrato</title>
    
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endif
    
    <style>
        .variable-highlight { background-color: #fef3c7; border: 1px solid #f59e0b; padding: 2px 4px; border-radius: 3px; }
    </style>
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

<body class="bg-gray-50" x-data="templateViewer({{ $template->id }})">
    <div class="flex h-screen">
        <!-- Sidebar Dinâmico -->
        <x-dynamic-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('contract-templates.index') }}" 
                           class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Voltar
                        </a>
                        
                        <div class="h-6 border-l border-gray-300"></div>
                        
                        <div>
                            <div class="flex items-center space-x-3">
                                <h1 class="text-2xl font-bold text-gray-800">
                                    <i class="fas fa-file-code text-primary mr-3"></i>
                                    {{ $template->name }}
                                </h1>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-sm font-medium {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $template->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                            @if($template->description)
                                <p class="text-gray-600 mt-1">{{ $template->description }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <button @click="previewTemplate()" 
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            Preview
                        </button>
                        
                        <a href="{{ route('contract-templates.edit', $template) }}" 
                           class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                            <i class="fas fa-edit mr-2"></i>
                            Editar
                        </a>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Conteúdo Principal -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Informações do Template -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informações do Template</h2>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900">v{{ $template->version }}</div>
                                    <div class="text-sm text-gray-600">Versão</div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900">{{ $usageCount }}</div>
                                    <div class="text-sm text-gray-600">Contratos</div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900">{{ count($variables) }}</div>
                                    <div class="text-sm text-gray-600">Variáveis</div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900">{{ number_format(strlen($template->content)) }}</div>
                                    <div class="text-sm text-gray-600">Caracteres</div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Criado por:</span>
                                    <span class="text-gray-600">{{ $template->createdBy->name ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Data de criação:</span>
                                    <span class="text-gray-600">{{ $template->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Última atualização:</span>
                                    <span class="text-gray-600">{{ $template->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Status:</span>
                                    <span class="text-gray-600">{{ $template->is_active ? 'Ativo' : 'Inativo' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Conteúdo do Template -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-900">Conteúdo do Template</h2>
                                <div class="flex items-center space-x-2">
                                    <button @click="copyContent()" 
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
                                        <i class="fas fa-copy mr-1"></i>
                                        Copiar
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <div class="bg-gray-50 rounded-lg p-4 max-h-96 overflow-y-auto">
                                    <pre class="whitespace-pre-wrap text-sm text-gray-800 font-mono">{{ $template->content }}</pre>
                                </div>
                            </div>
                        </div>

                        <!-- Ações -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <form action="{{ route('contract-templates.toggle-status', $template) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $template->is_active ? 'text-orange-600 hover:text-orange-700 hover:bg-orange-50 border border-orange-200' : 'text-green-600 hover:text-green-700 hover:bg-green-50 border border-green-200' }}">
                                        <i class="fas {{ $template->is_active ? 'fa-pause' : 'fa-play' }} mr-2"></i>
                                        {{ $template->is_active ? 'Desativar' : 'Ativar' }}
                                    </button>
                                </form>
                                
                                <form action="{{ route('contract-templates.duplicate', $template) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 border border-gray-300 rounded-lg transition-colors">
                                        <i class="fas fa-copy mr-2"></i>
                                        Duplicar
                                    </button>
                                </form>
                            </div>
                            
                            @if($usageCount == 0)
                                <form action="{{ route('contract-templates.destroy', $template) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Tem certeza que deseja excluir este template? Esta ação não pode ser desfeita.')" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 border border-red-300 rounded-lg transition-colors">
                                        <i class="fas fa-trash mr-2"></i>
                                        Excluir
                                    </button>
                                </form>
                            @else
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Template em uso por {{ $usageCount }} contrato(s)
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Variáveis Utilizadas -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                Variáveis Utilizadas
                                <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ count($variables) }}
                                </span>
                            </h3>
                            
                            @if(!empty($variables))
                                <div class="space-y-3 max-h-60 overflow-y-auto">
                                    @foreach($variables as $variable)
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="font-mono text-sm font-semibold text-primary">
                                                    {{ $variable['placeholder'] ?? $variable['name'] }}
                                                </span>
                                                @if($variable['required'] ?? false)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                        Obrigatório
                                                    </span>
                                                @endif
                                            </div>
                                            @if(!empty($variable['description']))
                                                <p class="text-xs text-gray-600">{{ $variable['description'] }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4 text-gray-500">
                                    <i class="fas fa-code text-2xl mb-2 block"></i>
                                    <p class="text-sm">Nenhuma variável detectada</p>
                                </div>
                            @endif
                        </div>

                        <!-- Contratos Utilizando -->
                        @if($usageCount > 0)
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    Contratos Utilizando
                                    <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $usageCount }}
                                    </span>
                                </h3>
                                
                                <div class="text-center py-4">
                                    <a href="{{ route('contracts.index', ['template' => $template->id]) }}" 
                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-primary hover:text-primary hover:bg-blue-50 rounded-lg transition-colors">
                                        <i class="fas fa-list mr-2"></i>
                                        Ver Contratos
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- Ações Rápidas -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-blue-900 mb-3">
                                <i class="fas fa-bolt mr-1"></i>
                                Ações Rápidas
                            </h4>
                            <div class="space-y-2">
                                <a href="{{ route('contract-templates.edit', $template) }}" 
                                   class="block w-full text-left px-3 py-2 text-sm text-blue-800 hover:bg-blue-100 rounded-lg transition-colors">
                                    <i class="fas fa-edit mr-2"></i>
                                    Editar Template
                                </a>
                                <button @click="previewTemplate()" 
                                        class="block w-full text-left px-3 py-2 text-sm text-blue-800 hover:bg-blue-100 rounded-lg transition-colors">
                                    <i class="fas fa-eye mr-2"></i>
                                    Visualizar Preview
                                </button>
                                <a href="{{ route('contracts.create', ['template' => $template->id]) }}" 
                                   class="block w-full text-left px-3 py-2 text-sm text-blue-800 hover:bg-blue-100 rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    Criar Contrato
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </main>
        </div>
    </div>

    <!-- Modal Preview -->
    <div x-show="showPreview" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         @keydown.escape="showPreview = false">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showPreview = false"></div>
            
            <div class="inline-block w-full max-w-6xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between mb-4 flex-shrink-0">
                    <h3 class="text-lg font-semibold text-gray-900">Preview: {{ $template->name }}</h3>
                    <button @click="showPreview = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto border border-gray-200 rounded-lg p-6 bg-white">
                    <div class="prose prose-sm max-w-none" x-html="previewContent"></div>
                </div>
                
                <div class="mt-4 flex justify-end space-x-3">
                    <button @click="showPreview = false" 
                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        Fechar
                    </button>
                    <a href="{{ route('contract-templates.edit', $template) }}" 
                       class="px-4 py-2 text-sm font-medium bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors">
                        Editar Template
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function templateViewer(templateId) {
            return {
                showPreview: false,
                previewContent: '',
                
                async previewTemplate() {
                    try {
                        const response = await fetch(`/contract-templates/${templateId}/preview`);
                        const data = await response.json();
                        
                        if (data.success) {
                            this.previewContent = data.preview;
                            this.showPreview = true;
                        } else {
                            alert('Erro ao gerar preview: ' + data.error);
                        }
                    } catch (error) {
                        console.error('Erro:', error);
                        alert('Erro ao carregar preview');
                    }
                },
                
                async copyContent() {
                    try {
                        const content = `{!! $template->content !!}`;
                        await navigator.clipboard.writeText(content);
                        
                        // Mostrar feedback
                        const button = event.target.closest('button');
                        const originalText = button.innerHTML;
                        button.innerHTML = '<i class="fas fa-check mr-1"></i>Copiado!';
                        button.classList.add('text-green-600');
                        
                        setTimeout(() => {
                            button.innerHTML = originalText;
                            button.classList.remove('text-green-600');
                        }, 2000);
                    } catch (error) {
                        alert('Erro ao copiar conteúdo');
                    }
                }
            }
        }
    </script>

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
</body>
</html>
