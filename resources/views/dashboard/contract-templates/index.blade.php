
<x-dynamic-branding />
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
                            <i class="fas fa-file-code text-blue-600 mr-3"></i>
                            Templates de Contrato
                        </h1>
                        <p class="text-gray-600 mt-1">Gerencie os modelos de contrato do sistema</p>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('contract-templates.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
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
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos os status</option>
                            <option value="active">Ativos</option>
                            <option value="inactive">Inativos</option>
                        </select>
                        
                        <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
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
                                                    <i class="fas fa-file-contract text-blue-600 text-lg"></i>
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
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
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
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
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
