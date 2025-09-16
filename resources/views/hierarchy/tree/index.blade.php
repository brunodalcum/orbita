@extends('layouts.app')

@section('title', 'Árvore da Hierarquia')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css">
<style>
.hierarchy-tree-container {
    min-height: 600px;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    background: white;
}

.jstree-default .jstree-anchor {
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s;
}

.jstree-default .jstree-anchor:hover {
    background: #f3f4f6;
}

.jstree-default .jstree-clicked {
    background: #dbeafe !important;
    color: #1e40af !important;
}

.node-super_admin > .jstree-anchor { color: #dc2626; font-weight: bold; }
.node-operacao > .jstree-anchor { color: #2563eb; font-weight: 600; }
.node-white_label > .jstree-anchor { color: var(--accent-color); font-weight: 600; }
.node-licenciado_l1 > .jstree-anchor { color: #d97706; }
.node-licenciado_l2 > .jstree-anchor { color: #ea580c; }
.node-licenciado_l3 > .jstree-anchor { color: #dc2626; }

.search-results {
    max-height: 300px;
    overflow-y: auto;
}

.search-result-item {
    padding: 8px 12px;
    border-bottom: 1px solid #f3f4f6;
    cursor: pointer;
    transition: background-color 0.2s;
}

.search-result-item:hover {
    background-color: #f9fafb;
}

.search-result-item:last-child {
    border-bottom: none;
}

.tree-controls {
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    padding: 1rem;
    border-radius: 0.5rem 0.5rem 0 0;
}

.tree-stats {
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
    padding: 1rem;
    border-radius: 0 0 0.5rem 0.5rem;
}
</style>
@endpush

@section('content')
<x-dynamic-branding />

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Árvore da Hierarquia</h1>
                        <p class="mt-1 text-sm text-gray-600">
                            Visualização interativa da estrutura hierárquica
                        </p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button onclick="expandAll()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-expand-arrows-alt mr-2"></i>
                            Expandir Tudo
                        </button>
                        <button onclick="collapseAll()" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-compress-arrows-alt mr-2"></i>
                            Recolher Tudo
                        </button>
                        <a href="{{ route('hierarchy.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar ao Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar com controles -->
            <div class="lg:col-span-1">
                <!-- Busca -->
                <div class="bg-white rounded-lg shadow-sm border mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Buscar na Hierarquia</h3>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="tree-search" 
                                placeholder="Digite nome ou email..." 
                                class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                        
                        <!-- Resultados da busca -->
                        <div id="search-results" class="hidden mt-4 bg-white border border-gray-200 rounded-md shadow-sm">
                            <div class="search-results" id="search-results-list">
                                <!-- Resultados serão inseridos aqui -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="bg-white rounded-lg shadow-sm border mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Filtros</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Nó</label>
                                <select id="filter-node-type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Todos</option>
                                    <option value="super_admin">Super Admin</option>
                                    <option value="operacao">Operação</option>
                                    <option value="white_label">White Label</option>
                                    <option value="licenciado_l1">Licenciado L1</option>
                                    <option value="licenciado_l2">Licenciado L2</option>
                                    <option value="licenciado_l3">Licenciado L3</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select id="filter-status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Todos</option>
                                    <option value="active">Apenas Ativos</option>
                                    <option value="inactive">Apenas Inativos</option>
                                </select>
                            </div>
                            
                            <button onclick="applyFilters()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-filter mr-2"></i>
                                Aplicar Filtros
                            </button>
                            
                            <button onclick="clearFilters()" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-times mr-2"></i>
                                Limpar Filtros
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Legenda -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Legenda</h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-crown text-red-600 mr-3"></i>
                                <span class="text-sm text-gray-700">Super Admin</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-building text-blue-600 mr-3"></i>
                                <span class="text-sm text-gray-700">Operação</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-tag text-green-600 mr-3"></i>
                                <span class="text-sm text-gray-700">White Label</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user text-orange-600 mr-3"></i>
                                <span class="text-sm text-gray-700">Licenciado L1</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user text-orange-700 mr-3"></i>
                                <span class="text-sm text-gray-700">Licenciado L2</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user text-red-600 mr-3"></i>
                                <span class="text-sm text-gray-700">Licenciado L3</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Árvore principal -->
            <div class="lg:col-span-3">
                <div class="hierarchy-tree-container">
                    <!-- Controles da árvore -->
                    <div class="tree-controls">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <h3 class="text-lg font-medium text-gray-900">Estrutura Hierárquica</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Contexto: {{ ucfirst($context['type']) }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="refreshTree()" class="text-gray-600 hover:text-gray-900" title="Atualizar árvore">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <button onclick="toggleFullscreen()" class="text-gray-600 hover:text-gray-900" title="Tela cheia">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Árvore -->
                    <div id="hierarchy-tree" class="p-4" style="min-height: 500px;">
                        <div class="flex items-center justify-center h-64">
                            <div class="text-center">
                                <i class="fas fa-spinner fa-spin text-gray-400 text-2xl mb-4"></i>
                                <p class="text-gray-600">Carregando árvore hierárquica...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Estatísticas da árvore -->
                    <div class="tree-stats">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold text-blue-600" id="stat-total-nodes">-</div>
                                <div class="text-xs text-gray-600">Total de Nós</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-green-600" id="stat-active-nodes">-</div>
                                <div class="text-xs text-gray-600">Nós Ativos</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-orange-600" id="stat-max-depth">-</div>
                                <div class="text-xs text-gray-600">Profundidade Máx.</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-purple-600" id="stat-selected-node">-</div>
                                <div class="text-xs text-gray-600">Nó Selecionado</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de detalhes do nó -->
<div id="node-details-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modal-title">Detalhes do Nó</h3>
                <button onclick="closeNodeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="modal-content">
                <!-- Conteúdo será inserido aqui -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>
<script>
let treeInstance = null;
let searchTimeout = null;

$(document).ready(function() {
    initializeTree();
    setupSearch();
    setupFilters();
});

function initializeTree() {
    $('#hierarchy-tree').jstree({
        'core': {
            'data': {
                'url': '{{ route("hierarchy.tree.data") }}',
                'dataType': 'json'
            },
            'check_callback': true,
            'themes': {
                'responsive': true,
                'variant': 'large'
            }
        },
        'types': {
            'super_admin': { 'icon': 'fas fa-crown' },
            'operacao': { 'icon': 'fas fa-building' },
            'white_label': { 'icon': 'fas fa-tag' },
            'licenciado_l1': { 'icon': 'fas fa-user' },
            'licenciado_l2': { 'icon': 'fas fa-user' },
            'licenciado_l3': { 'icon': 'fas fa-user' }
        },
        'plugins': ['types', 'search', 'contextmenu'],
        'search': {
            'case_insensitive': true,
            'show_only_matches': true
        },
        'contextmenu': {
            'items': function(node) {
                return {
                    'view': {
                        'label': 'Ver Detalhes',
                        'icon': 'fas fa-eye',
                        'action': function() {
                            viewNodeDetails(node);
                        }
                    },
                    'edit': {
                        'label': 'Editar',
                        'icon': 'fas fa-edit',
                        'action': function() {
                            editNode(node);
                        }
                    },
                    'add_child': {
                        'label': 'Adicionar Filho',
                        'icon': 'fas fa-plus',
                        'action': function() {
                            addChildNode(node);
                        }
                    }
                };
            }
        }
    }).on('loaded.jstree', function() {
        treeInstance = $('#hierarchy-tree').jstree(true);
        updateTreeStats();
    }).on('select_node.jstree', function(e, data) {
        updateSelectedNodeStat(data.node);
    });
}

function setupSearch() {
    $('#tree-search').on('input', function() {
        const query = $(this).val();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            $('#search-results').addClass('hidden');
            if (treeInstance) {
                treeInstance.clear_search();
            }
            return;
        }
        
        searchTimeout = setTimeout(() => {
            searchInTree(query);
            searchNodes(query);
        }, 300);
    });
}

function searchInTree(query) {
    if (treeInstance) {
        treeInstance.search(query);
    }
}

function searchNodes(query) {
    fetch(`{{ route("hierarchy.tree.search") }}?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            displaySearchResults(data);
        })
        .catch(error => {
            console.error('Erro na busca:', error);
        });
}

function displaySearchResults(results) {
    const container = $('#search-results-list');
    container.empty();
    
    if (results.length === 0) {
        container.append('<div class="p-4 text-center text-gray-500">Nenhum resultado encontrado</div>');
    } else {
        results.forEach(result => {
            const item = $(`
                <div class="search-result-item" onclick="selectSearchResult('${result.id}')">
                    <div class="font-medium text-gray-900">${result.text}</div>
                    <div class="text-sm text-gray-500">${result.email}</div>
                    <div class="text-xs text-gray-400">${result.hierarchy_path}</div>
                </div>
            `);
            container.append(item);
        });
    }
    
    $('#search-results').removeClass('hidden');
}

function selectSearchResult(nodeId) {
    if (treeInstance) {
        treeInstance.deselect_all();
        treeInstance.select_node(nodeId);
        
        // Expandir caminho até o nó
        const node = treeInstance.get_node(nodeId);
        if (node) {
            treeInstance.open_to(node);
        }
    }
    
    $('#search-results').addClass('hidden');
    $('#tree-search').val('');
}

function setupFilters() {
    $('#filter-node-type, #filter-status').on('change', function() {
        // Auto-aplicar filtros quando mudarem
        // applyFilters();
    });
}

function applyFilters() {
    const nodeType = $('#filter-node-type').val();
    const status = $('#filter-status').val();
    
    if (treeInstance) {
        // Implementar lógica de filtros
        console.log('Aplicando filtros:', { nodeType, status });
    }
}

function clearFilters() {
    $('#filter-node-type').val('');
    $('#filter-status').val('');
    
    if (treeInstance) {
        treeInstance.clear_search();
        treeInstance.show_all();
    }
}

function expandAll() {
    if (treeInstance) {
        treeInstance.open_all();
    }
}

function collapseAll() {
    if (treeInstance) {
        treeInstance.close_all();
    }
}

function refreshTree() {
    if (treeInstance) {
        treeInstance.refresh();
    }
}

function toggleFullscreen() {
    const container = $('.hierarchy-tree-container');
    
    if (container.hasClass('fullscreen')) {
        container.removeClass('fullscreen');
        $('body').removeClass('overflow-hidden');
    } else {
        container.addClass('fullscreen');
        $('body').addClass('overflow-hidden');
    }
}

function updateTreeStats() {
    if (!treeInstance) return;
    
    const allNodes = treeInstance.get_json('#', { flat: true });
    const totalNodes = allNodes.length;
    const activeNodes = allNodes.filter(node => !node.li_attr || !node.li_attr.class || !node.li_attr.class.includes('text-gray-400')).length;
    const maxDepth = Math.max(...allNodes.map(node => node.parents.length));
    
    $('#stat-total-nodes').text(totalNodes);
    $('#stat-active-nodes').text(activeNodes);
    $('#stat-max-depth').text(maxDepth);
}

function updateSelectedNodeStat(node) {
    $('#stat-selected-node').text(node ? node.text.split(' (')[0] : '-');
}

function viewNodeDetails(node) {
    // Implementar visualização de detalhes
    console.log('Ver detalhes do nó:', node);
}

function editNode(node) {
    // Implementar edição do nó
    console.log('Editar nó:', node);
}

function addChildNode(node) {
    // Implementar adição de filho
    console.log('Adicionar filho ao nó:', node);
}

function closeNodeModal() {
    $('#node-details-modal').addClass('hidden');
}

// CSS para fullscreen
$('<style>')
    .prop('type', 'text/css')
    .html(`
        .fullscreen {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 9999 !important;
            border-radius: 0 !important;
        }
        .overflow-hidden {
            overflow: hidden !important;
        }
    `)
    .appendTo('head');
</script>
@endpush
