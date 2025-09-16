@extends('layouts.app')

@section('title', 'Gerenciamento de Permissões')

@section('content')
<x-dynamic-branding />

<div class="min-h-screen bg-gray-50" x-data="permissionsManager()">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Gerenciamento de Permissões</h1>
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {{ ucfirst(str_replace('_', ' ', $user->node_type)) }}
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('hierarchy.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 414 0zM7 10a2 2 0 11-4 0 2 2 0 414 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Nós com Permissões</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_nodes_with_permissions'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Permissões Concedidas</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_permissions_granted'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Permissões Revogadas</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_permissions_revoked'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Permissões Expiradas</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['expired_permissions'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Filtros</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar Nó</label>
                        <input type="text" x-model="searchTerm" @input.debounce.300ms="applyFilters()" placeholder="Nome do nó..." class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Nó</label>
                        <select x-model="selectedType" @change="applyFilters()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="all">Todos</option>
                            @if($user->isSuperAdminNode())
                            <option value="operacao">Operações</option>
                            <option value="white_label">White Labels</option>
                            @endif
                            <option value="user">Usuários</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de nós -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900">Nós da Hierarquia</h2>
                    <div class="text-sm text-gray-500">
                        <span x-text="filteredNodes.length">{{ $nodes->count() }}</span> nós encontrados
                    </div>
                </div>
            </div>
            
            <div class="overflow-hidden">
                <div x-show="loading" class="p-8 text-center">
                    <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">Carregando nós...</p>
                </div>

                <div x-show="!loading">
                    <template x-for="node in paginatedNodes" :key="node.id">
                        <div class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center" :class="getNodeTypeColor(node.node_type)">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-html="getNodeTypeIcon(node.node_type)">
                                                </svg>
                                            </div>
                                        </div>
                                        
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <h3 class="text-sm font-medium text-gray-900" x-text="node.name || node.display_name"></h3>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" :class="node.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                                    <span x-text="node.is_active ? 'Ativo' : 'Inativo'"></span>
                                                </span>
                                            </div>
                                            <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                                <span x-text="getNodeTypeLabel(node.node_type)"></span>
                                                <span x-show="node.email" x-text="node.email"></span>
                                                <span x-show="node.hierarchy_level">Nível <span x-text="node.hierarchy_level"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-2">
                                        <button @click="managePermissions(node)" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            Gerenciar Permissões
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="filteredNodes.length === 0" class="p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum nó encontrado</h3>
                        <p class="mt-1 text-sm text-gray-500">Tente ajustar os filtros de busca.</p>
                    </div>
                </div>
            </div>
            
            <!-- Paginação -->
            <div x-show="totalPages > 1" class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Mostrando <span x-text="(currentPage - 1) * itemsPerPage + 1"></span> a <span x-text="Math.min(currentPage * itemsPerPage, filteredNodes.length)"></span> de <span x-text="filteredNodes.length"></span> resultados
                    </div>
                    <div class="flex items-center space-x-2">
                        <button @click="currentPage > 1 && (currentPage--)" :disabled="currentPage === 1" class="px-3 py-1 border border-gray-300 text-sm rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50">
                            Anterior
                        </button>
                        <template x-for="page in Array.from({length: totalPages}, (_, i) => i + 1)" :key="page">
                            <button @click="currentPage = page" :class="currentPage === page ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'" class="px-3 py-1 border border-gray-300 text-sm rounded-md" x-text="page"></button>
                        </template>
                        <button @click="currentPage < totalPages && (currentPage++)" :disabled="currentPage === totalPages" class="px-3 py-1 border border-gray-300 text-sm rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50">
                            Próximo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function permissionsManager() {
    return {
        nodes: @json($nodes->values()),
        filteredNodes: [],
        searchTerm: '{{ $search }}',
        selectedType: '{{ $nodeType }}',
        loading: false,
        currentPage: 1,
        itemsPerPage: 10,
        
        get totalPages() {
            return Math.ceil(this.filteredNodes.length / this.itemsPerPage);
        },
        
        get paginatedNodes() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.filteredNodes.slice(start, end);
        },
        
        init() {
            this.applyFilters();
        },
        
        applyFilters() {
            this.filteredNodes = this.nodes.filter(node => {
                if (this.searchTerm) {
                    const searchLower = this.searchTerm.toLowerCase();
                    const matchesName = (node.name || node.display_name || '').toLowerCase().includes(searchLower);
                    const matchesEmail = (node.email || '').toLowerCase().includes(searchLower);
                    if (!matchesName && !matchesEmail) {
                        return false;
                    }
                }
                
                if (this.selectedType !== 'all' && node.node_type !== this.selectedType) {
                    return false;
                }
                
                return true;
            });
            
            this.currentPage = 1;
        },
        
        managePermissions(node) {
            window.location.href = `/hierarchy/permissions/${node.node_type}/${node.id}/manage`;
        },
        
        getNodeTypeColor(type) {
            const colors = {
                'operacao': 'bg-blue-500',
                'white_label': 'bg-green-500',
                'user': 'bg-purple-500'
            };
            return colors[type] || 'bg-gray-500';
        },
        
        getNodeTypeLabel(type) {
            const labels = {
                'operacao': 'Operação',
                'white_label': 'White Label',
                'user': 'Usuário'
            };
            return labels[type] || 'Desconhecido';
        },
        
        getNodeTypeIcon(type) {
            const icons = {
                'operacao': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
                'white_label': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>',
                'user': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'
            };
            return icons[type] || '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
        }
    }
}
</script>
@endsection
