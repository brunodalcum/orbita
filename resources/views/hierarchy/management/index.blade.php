<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gerenciamento de Nós - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid white;
        }
        .card {
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        /* Garantir que dropdowns apareçam acima de outros elementos */
        .dropdown-menu {
            z-index: 9999 !important;
            position: absolute !important;
        }
        /* Evitar overflow nos containers de lista */
        .overflow-hidden {
            overflow: visible !important;
        }
        /* Garantir que os itens da lista não cortem dropdowns */
        .list-item {
            position: relative;
            z-index: 1;
        }
        .list-item:hover {
            z-index: 10;
        }
        
        /* Estilos para o modal de detalhes */
        .modal-backdrop {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        
        .modal-content {
            animation: modalSlideIn 0.3s ease-out;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        .gradient-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .stat-card {
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        /* Animações para notificações */
        .notification-enter {
            animation: slideInRight 0.3s ease-out;
        }
        
        .notification-exit {
            animation: slideOutRight 0.3s ease-in;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        /* Efeitos de hover melhorados */
        .hover-lift {
            transition: all 0.2s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar Dinâmico -->
        <x-dynamic-sidebar />
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden ml-64">
<div class="min-h-screen bg-gray-50" x-data="nodeManagement()">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Gerenciamento de Nós</h1>
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst(str_replace('_', ' ', $user->node_type)) }}
                    </span>
                    </div>
                    <div class="flex items-center space-x-4">
                    <!-- Dropdown para criar novo nó -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Criar Nó
                            <svg class="ml-2 -mr-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                                </button>
                                
                                    <div x-show="open" @click.away="open = false" x-transition class="dropdown-menu absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50" style="z-index: 9999;">
                                    <div class="py-1">
                                @if($user->isSuperAdminNode())
                                <a href="{{ route('hierarchy.management.create', ['type' => 'operacao']) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="mr-3 h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                                Nova Operação
                                            </a>
                                @endif
                                
                                @if($user->isSuperAdminNode() || $user->isOperacaoNode())
                                <a href="{{ route('hierarchy.management.create', ['type' => 'white_label']) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="mr-3 h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                    </svg>
                                                Novo White Label
                                            </a>
                                        @endif
                                        
                                        @if($user->canHaveChildren())
                                            <a href="{{ route('hierarchy.management.create', ['type' => 'licenciado_l1']) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="mr-3 h-5 w-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                                Novo Licenciado L1
                                            </a>
                                            
                                            @if($user->node_type === 'licenciado_l1' || $user->isSuperAdminNode())
                                            <a href="{{ route('hierarchy.management.create', ['type' => 'licenciado_l2']) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <svg class="mr-3 h-5 w-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                Novo Licenciado L2
                                            </a>
                                            @endif
                                            
                                            @if($user->node_type === 'licenciado_l2' || $user->isSuperAdminNode())
                                            <a href="{{ route('hierarchy.management.create', ['type' => 'licenciado_l3']) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <svg class="mr-3 h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                                </svg>
                                                Novo Licenciado L3
                                                </a>
                                            @endif
                                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

                <!-- Content -->
                <div class="flex-1 overflow-auto p-6">
                    <!-- Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Total de Operações -->
                        <div class="card rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total de Operações</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_operacoes'] }}</dd>
                            </dl>
                    </div>
                </div>
            </div>

                        <!-- Total de White Labels -->
                        <div class="card rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">White Labels</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_white_labels'] }}</dd>
                            </dl>
                    </div>
                </div>
            </div>

                        <!-- Total de Licenciados -->
                        <div class="card rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total de Licenciados</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ $stats['total_licenciados_l1'] + $stats['total_licenciados_l2'] + $stats['total_licenciados_l3'] }}</dd>
                            </dl>
                    </div>
                </div>
            </div>

            <!-- Nós Ativos -->
                        <div class="card rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Nós Ativos</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['active_nodes'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

                    <!-- Filtros e Lista -->
                    <div class="card rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                                <h2 class="text-lg font-medium text-gray-900">Lista de Nós</h2>
                    <div class="flex items-center space-x-4">
                                    <!-- Campo de busca -->
                        <div class="relative">
                                        <input type="text" x-model="searchTerm" @input="filterNodes()" placeholder="Buscar nós..." class="block w-64 pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                                    <!-- Filtro por tipo -->
                                    <select x-model="selectedType" @change="filterNodes()" class="block w-48 pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="">Todos os tipos</option>
                                        <option value="operacao">Operações</option>
                                        <option value="white_label">White Labels</option>
                                        <option value="licenciado_l1">Licenciados L1</option>
                                        <option value="licenciado_l2">Licenciados L2</option>
                                        <option value="licenciado_l3">Licenciados L3</option>
                                    </select>
                    </div>
                </div>
            </div>
            
                        <!-- Lista de nós -->
                        <div class="overflow-visible">
                            <ul class="divide-y divide-gray-200">
                                <template x-for="node in paginatedNodes" :key="node.id">
                                    <li class="px-6 py-4 hover:bg-gray-50 list-item">
                                        <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 rounded-full flex items-center justify-center" :class="{
                                                        'bg-blue-100 text-blue-600': node.node_type === 'operacao',
                                                        'bg-green-100 text-green-600': node.node_type === 'white_label',
                                                        'bg-yellow-100 text-yellow-600': node.node_type === 'licenciado_l1',
                                                        'bg-orange-100 text-orange-600': node.node_type === 'licenciado_l2',
                                                        'bg-red-100 text-red-600': node.node_type === 'licenciado_l3'
                                                    }">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                        </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                                    <div class="flex items-center">
                                                        <div class="text-sm font-medium text-gray-900" x-text="node.name"></div>
                                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="{
                                                            'bg-blue-100 text-blue-800': node.node_type === 'operacao',
                                                            'bg-green-100 text-green-800': node.node_type === 'white_label',
                                                            'bg-yellow-100 text-yellow-800': node.node_type === 'licenciado_l1',
                                                            'bg-orange-100 text-orange-800': node.node_type === 'licenciado_l2',
                                                            'bg-red-100 text-red-800': node.node_type === 'licenciado_l3'
                                                        }" x-text="getNodeTypeLabel(node.node_type)"></span>
                                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="{
                                                            'bg-green-100 text-green-800': node.is_active,
                                                            'bg-red-100 text-red-800': !node.is_active
                                                        }" x-text="node.is_active ? 'Ativo' : 'Inativo'"></span>
                                                    </div>
                                                    <div class="text-sm text-gray-500" x-text="node.email"></div>
                                                    <div class="text-xs text-gray-400">
                                                        Criado em <span x-text="formatDate(node.created_at)"></span>
                                                    </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                                <button @click="viewNode(node)" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <svg class="-ml-1 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Ver
                                                </button>
                                                <button @click="editNode(node)" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <svg class="-ml-1 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Editar
                                                </button>
                                                <div class="relative" x-data="{ open: false }">
                                                    <button @click="open = !open" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        <svg class="-ml-1 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                        </svg>
                                                        Ações
                                                    </button>
                                                    <div x-show="open" @click.away="open = false" x-transition class="dropdown-menu absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50" style="z-index: 9999;">
                                                        <div class="py-1">
                                                            <button @click="toggleStatus(node); open = false" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                                                </svg>
                                                                <span x-text="node.is_active ? 'Desativar' : 'Ativar'"></span>
                                                            </button>
                                                            <button @click="impersonateNode(node); open = false" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                                </svg>
                                                                Impersonar
                                        </button>
                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>

                            <!-- Paginação -->
                            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6" x-show="filteredNodes.length > itemsPerPage">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 flex justify-between sm:hidden">
                                        <button @click="previousPage()" :disabled="currentPage === 1" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                            Anterior
                                        </button>
                                        <button @click="nextPage()" :disabled="currentPage === totalPages" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                            Próximo
                                            </button>
                                    </div>
                                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm text-gray-700">
                                                Mostrando
                                                <span class="font-medium" x-text="((currentPage - 1) * itemsPerPage) + 1"></span>
                                                até
                                                <span class="font-medium" x-text="Math.min(currentPage * itemsPerPage, filteredNodes.length)"></span>
                                                de
                                                <span class="font-medium" x-text="filteredNodes.length"></span>
                                                resultados
                                            </p>
                                        </div>
                                        <div>
                                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                                <button @click="previousPage()" :disabled="currentPage === 1" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                                    </svg>
                                                </button>
                                                <template x-for="page in getVisiblePages()" :key="page">
                                                    <button @click="goToPage(page)" :class="page === currentPage ? 'bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium" x-text="page"></button>
                                                </template>
                                                <button @click="nextPage()" :disabled="currentPage === totalPages" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </button>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                                    </div>

                            <!-- Estado vazio -->
                            <div x-show="filteredNodes.length === 0" class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum nó encontrado</h3>
                                <p class="mt-1 text-sm text-gray-500">Tente ajustar os filtros ou criar um novo nó.</p>
                                <div class="mt-6">
                                    <a href="{{ route('hierarchy.management.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Criar Primeiro Nó
                                    </a>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
            </div>
        </div>
    </div>
</div>

<script>
function nodeManagement() {
    return {
                nodes: @json($nodes->values()),
                filteredNodes: [],
                searchTerm: '{{ $search }}',
                selectedType: '{{ $nodeType }}',
                selectedStatus: 'all',
                loading: false,
                currentPage: 1,
                itemsPerPage: 10,

                init() {
                    this.filteredNodes = [...this.nodes];
                    this.filterNodes();
                },

                filterNodes() {
                    let filtered = [...this.nodes];

                    // Filtrar por termo de busca
                    if (this.searchTerm) {
                        const term = this.searchTerm.toLowerCase();
                        filtered = filtered.filter(node => 
                            node.name.toLowerCase().includes(term) ||
                            node.email.toLowerCase().includes(term)
                        );
                    }

                    // Filtrar por tipo
                    if (this.selectedType) {
                        filtered = filtered.filter(node => node.node_type === this.selectedType);
                    }

                    // Filtrar por status
                    if (this.selectedStatus !== 'all') {
                        const isActive = this.selectedStatus === 'active';
                        filtered = filtered.filter(node => node.is_active === isActive);
                    }

                    this.filteredNodes = filtered;
                    this.currentPage = 1; // Reset para primeira página
                },

                get paginatedNodes() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    return this.filteredNodes.slice(start, end);
                },

                get totalPages() {
                    return Math.ceil(this.filteredNodes.length / this.itemsPerPage);
                },

                previousPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                    }
                },

                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                    }
                },

                goToPage(page) {
                    this.currentPage = page;
                },

                getVisiblePages() {
                    const total = this.totalPages;
                    const current = this.currentPage;
                    const pages = [];

                    if (total <= 7) {
                        for (let i = 1; i <= total; i++) {
                            pages.push(i);
                        }
                    } else {
                        if (current <= 4) {
                            for (let i = 1; i <= 5; i++) {
                                pages.push(i);
                            }
                            pages.push('...');
                            pages.push(total);
                        } else if (current >= total - 3) {
                            pages.push(1);
                            pages.push('...');
                            for (let i = total - 4; i <= total; i++) {
                                pages.push(i);
                            }
                        } else {
                            pages.push(1);
                            pages.push('...');
                            for (let i = current - 1; i <= current + 1; i++) {
                                pages.push(i);
                            }
                            pages.push('...');
                            pages.push(total);
                        }
                    }

                    return pages;
                },

                getNodeTypeLabel(type) {
                    const labels = {
                        'operacao': 'Operação',
                        'white_label': 'White Label',
                        'licenciado_l1': 'Licenciado L1',
                        'licenciado_l2': 'Licenciado L2',
                        'licenciado_l3': 'Licenciado L3'
                    };
                    return labels[type] || type;
                },

                formatDate(dateString) {
                    return new Date(dateString).toLocaleDateString('pt-BR');
                },

                async viewNode(node) {
                    try {
                        this.loading = true;
                        
                        const response = await fetch(`/hierarchy/management/${node.id}`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}`);
                        }
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            this.showNodeDetailsModal(result.node, result.details);
                        } else {
                            alert('Erro ao carregar detalhes do nó');
                        }
                        
                    } catch (error) {
                        console.error('Erro ao visualizar nó:', error);
                        alert('Erro ao carregar detalhes do nó: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                },

                editNode(node) {
                    // Redirecionar para página de edição
                    window.location.href = `/hierarchy/management/${node.id}/edit`;
                },

                async toggleStatus(node) {
                    const action = node.is_active ? 'desativar' : 'ativar';
                    const confirmMessage = node.is_active 
                        ? `Tem certeza que deseja desativar "${node.name}"? O acesso será bloqueado por segurança.`
                        : `Tem certeza que deseja ativar "${node.name}"?`;
                    
                    if (!confirm(confirmMessage)) {
                        return;
                    }
                    
                    try {
                        this.loading = true;
                        
                        const response = await fetch(`/hierarchy/management/${node.id}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
                                'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
                });
                
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}`);
                        }
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            // Atualizar o nó na lista
                            const nodeIndex = this.nodes.findIndex(n => n.id === node.id);
                            if (nodeIndex !== -1) {
                                this.nodes[nodeIndex].is_active = result.new_status;
                            }
                            
                            // Atualizar lista filtrada
                            this.filterNodes();
                            
                            // Mostrar mensagem de sucesso
                            this.showSuccessMessage(result.message);
                            
        } else {
                            alert('Erro ao alterar status do nó');
                }
                        
            } catch (error) {
                console.error('Erro ao alterar status:', error);
                        alert('Erro ao alterar status: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                },

                impersonateNode(node) {
                    const confirmMessage = `Tem certeza que deseja operar como "${node.name}"? Você assumirá o contexto deste nó.`;
                    
                    if (confirm(confirmMessage)) {
                        // Implementar impersonação (redirecionar para dashboard do nó)
                        console.log('Impersonar nó:', node);
                        alert('Funcionalidade de impersonação será implementada em breve');
                    }
                },

                showNodeDetailsModal(node, details) {
                    // Criar modal dinâmico para mostrar detalhes
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4';
                    modal.style.backdropFilter = 'blur(4px)';
                    
                    // Determinar cor do tema baseado no tipo de nó
                    const themeColors = {
                        'operacao': { primary: 'blue', secondary: 'blue-50', accent: 'blue-600' },
                        'white_label': { primary: 'purple', secondary: 'purple-50', accent: 'purple-600' },
                        'licenciado_l1': { primary: 'green', secondary: 'green-50', accent: 'green-600' },
                        'licenciado_l2': { primary: 'yellow', secondary: 'yellow-50', accent: 'yellow-600' },
                        'licenciado_l3': { primary: 'red', secondary: 'red-50', accent: 'red-600' }
                    };
                    
                    const theme = themeColors[node.node_type] || themeColors['operacao'];
                    
                    modal.innerHTML = `
                        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-100">
                            <!-- Header com gradiente -->
                            <div class="bg-gradient-to-r from-${theme.primary}-600 to-${theme.primary}-700 px-6 py-4 relative overflow-hidden">
                                <div class="absolute inset-0 bg-black opacity-10"></div>
                                <div class="relative flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                            <i class="fas ${this.getNodeIcon(node.node_type)} text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-white">
                                                ${this.getNodeTypeLabel(node.node_type)}
                                            </h3>
                                            <p class="text-${theme.primary}-100 text-sm">
                                                ${node.name}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium ${details.basic_info.is_active ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}">
                                            ${details.basic_info.is_active ? '● Ativo' : '● Inativo'}
                                        </span>
                                        <button onclick="this.closest('.fixed').remove()" class="text-white hover:text-${theme.primary}-200 transition-colors duration-200 p-2 rounded-full hover:bg-white hover:bg-opacity-20">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Conteúdo do modal -->
                            <div class="p-6 max-h-[calc(90vh-200px)] overflow-y-auto">
                                ${this.generateNodeDetailsHTML(node, details, theme)}
                            </div>
                            
                            <!-- Footer com ações -->
                            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t">
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    Criado em ${this.formatDate(details.basic_info.created_at)}
                                </div>
                                <div class="flex space-x-3">
                                    <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-medium">
                                        <i class="fas fa-times mr-2"></i>Fechar
                                    </button>
                                    <button onclick="window.location.href='/hierarchy/management/${node.id}/edit'" class="px-4 py-2 bg-${theme.accent} text-white rounded-lg hover:bg-${theme.primary}-700 transition-colors duration-200 font-medium shadow-md">
                                        <i class="fas fa-edit mr-2"></i>Editar
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Adicionar animação de entrada
                    modal.style.opacity = '0';
                    document.body.appendChild(modal);
                    
                    // Trigger da animação
                    requestAnimationFrame(() => {
                        modal.style.opacity = '1';
                        const modalContent = modal.querySelector('div > div');
                        modalContent.style.transform = 'scale(1)';
                    });
                    
                    // Fechar modal ao clicar no backdrop
                    modal.addEventListener('click', (e) => {
                        if (e.target === modal) {
                            this.closeModal(modal);
                        }
                    });
                    
                    // Fechar modal com ESC
                    const handleEscape = (e) => {
                        if (e.key === 'Escape') {
                            this.closeModal(modal);
                            document.removeEventListener('keydown', handleEscape);
                        }
                    };
                    document.addEventListener('keydown', handleEscape);
                },

                generateNodeDetailsHTML(node, details, theme) {
                    let html = `
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Informações Básicas -->
                            <div class="lg:col-span-2">
                                <div class="bg-gradient-to-br from-${theme.secondary} to-white rounded-xl p-6 border border-${theme.primary}-100 shadow-sm">
                                    <div class="flex items-center mb-4">
                                        <div class="w-8 h-8 bg-${theme.accent} rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-info-circle text-white text-sm"></i>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Informações Básicas</h4>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-3">
                                            <div class="flex items-center">
                                                <span class="text-gray-500 text-sm w-16">ID:</span>
                                                <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">#${details.basic_info.id}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="text-gray-500 text-sm w-16">Nome:</span>
                                                <span class="font-medium text-gray-900">${details.basic_info.name}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="text-gray-500 text-sm w-16">Email:</span>
                                                <span class="text-${theme.accent} text-sm">${details.basic_info.email}</span>
                                            </div>
                                        </div>
                                        <div class="space-y-3">
                                            <div class="flex items-center">
                                                <span class="text-gray-500 text-sm w-16">Tipo:</span>
                                                <span class="px-2 py-1 bg-${theme.accent} text-white text-xs rounded-full font-medium">${this.getNodeTypeLabel(details.basic_info.node_type)}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="text-gray-500 text-sm w-16">Nível:</span>
                                                <span class="font-medium text-gray-900">${details.basic_info.hierarchy_level}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="text-gray-500 text-sm w-16">Criado:</span>
                                                <span class="text-sm text-gray-600">${this.formatDate(details.basic_info.created_at)}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Estatísticas -->
                            <div>
                                <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                                    <div class="flex items-center mb-4">
                                        <div class="w-8 h-8 bg-gray-600 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-chart-bar text-white text-sm"></i>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Estatísticas</h4>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                                            <div class="text-2xl font-bold text-blue-600">${details.statistics.direct_children}</div>
                                            <div class="text-xs text-blue-500 font-medium">Filhos Diretos</div>
                                        </div>
                                        <div class="text-center p-3 bg-green-50 rounded-lg">
                                            <div class="text-2xl font-bold text-green-600">${details.statistics.total_descendants}</div>
                                            <div class="text-xs text-green-500 font-medium">Total Descendentes</div>
                                        </div>
                                        <div class="text-center p-3 bg-purple-50 rounded-lg">
                                            <div class="text-2xl font-bold text-purple-600">${details.statistics.active_descendants}</div>
                                            <div class="text-xs text-purple-500 font-medium">Descendentes Ativos</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    // Adicionar informações específicas do tipo de nó
                    if (details.operacao_info) {
                        html += `
                            <div class="mt-6">
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                                    <div class="flex items-center mb-4">
                                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-building text-white text-sm"></i>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Informações da Operação</h4>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-3">
                                            <div>
                                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nome de Exibição</label>
                                                <div class="mt-1 text-sm font-medium text-gray-900">${details.operacao_info.display_name || 'N/A'}</div>
                                            </div>
                                            <div>
                                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Descrição</label>
                                                <div class="mt-1 text-sm text-gray-700">${details.operacao_info.description || 'N/A'}</div>
                                            </div>
                                        </div>
                                        <div class="space-y-3">
                                            <div>
                                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Domínio</label>
                                                <div class="mt-1 text-sm font-mono text-blue-600">${details.operacao_info.domain || 'N/A'}</div>
                                            </div>
                                            <div>
                                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Subdomínio</label>
                                                <div class="mt-1 text-sm font-mono text-blue-600">${details.operacao_info.subdomain || 'N/A'}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }

                    if (details.white_label_info) {
                        html += `
                            <div class="mt-6">
                                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-200">
                                    <div class="flex items-center mb-4">
                                        <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-tags text-white text-sm"></i>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Informações do White Label</h4>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-3">
                                            <div>
                                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nome de Exibição</label>
                                                <div class="mt-1 text-sm font-medium text-gray-900">${details.white_label_info.display_name || 'N/A'}</div>
                                            </div>
                                            <div>
                                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Descrição</label>
                                                <div class="mt-1 text-sm text-gray-700">${details.white_label_info.description || 'N/A'}</div>
                                            </div>
                                        </div>
                                        <div class="space-y-3">
                                            <div>
                                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Domínio</label>
                                                <div class="mt-1 text-sm font-mono text-purple-600">${details.white_label_info.domain || 'N/A'}</div>
                                            </div>
                                            <div>
                                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Subdomínio</label>
                                                <div class="mt-1 text-sm font-mono text-purple-600">${details.white_label_info.subdomain || 'N/A'}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        if (details.parent_operacao) {
                            html += `
                                <div class="mt-6">
                                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                                        <div class="flex items-center mb-4">
                                            <div class="w-8 h-8 bg-gray-600 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-link text-white text-sm"></i>
                                            </div>
                                            <h4 class="text-lg font-semibold text-gray-900">Operação Pai</h4>
                                        </div>
                                        <div class="flex items-center space-x-4 p-4 bg-white rounded-lg border">
                                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-building text-blue-600"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">${details.parent_operacao.name}</div>
                                                <div class="text-sm text-gray-500">${details.parent_operacao.display_name}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }
                    }

                    return html;
                },

                getNodeIcon(nodeType) {
                    const icons = {
                        'operacao': 'fa-building',
                        'white_label': 'fa-tags',
                        'licenciado_l1': 'fa-user-tie',
                        'licenciado_l2': 'fa-user-friends',
                        'licenciado_l3': 'fa-users'
                    };
                    return icons[nodeType] || 'fa-circle';
                },

                closeModal(modal) {
                    // Animação de saída
                    modal.style.opacity = '0';
                    const modalContent = modal.querySelector('div > div');
                    if (modalContent) {
                        modalContent.style.transform = 'scale(0.95)';
                    }
                    
                    // Remover após animação
                    setTimeout(() => {
                        if (modal.parentNode) {
                            modal.parentNode.removeChild(modal);
                        }
                    }, 200);
                },

                showSuccessMessage(message) {
                    // Criar notificação de sucesso moderna
            const notification = document.createElement('div');
                    notification.className = 'fixed top-4 right-4 bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-xl shadow-2xl z-50 transform transition-all duration-300 translate-x-full';
                    notification.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                            <span class="font-medium">${message}</span>
                        </div>
                    `;
            
            document.body.appendChild(notification);
            
                    // Animação de entrada
                    requestAnimationFrame(() => {
                        notification.style.transform = 'translateX(0)';
                    });
                    
                    // Remover após 4 segundos com animação
                    setTimeout(() => {
                        notification.style.transform = 'translateX(full)';
            setTimeout(() => {
                            if (notification.parentNode) {
                                notification.parentNode.removeChild(notification);
                            }
                        }, 300);
                    }, 4000);
        }
    }
}
</script>
</body>
</html>