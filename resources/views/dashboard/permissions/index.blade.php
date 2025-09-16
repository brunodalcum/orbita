
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

@section('title', 'Perfil de Usuário - Permissões')

@section('content')
<x-simple-branding />

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Perfil de Usuário</h1>
            <p class="text-gray-600 mt-2">Gerencie as permissões de acesso para cada tipo de usuário</p>
        </div>
        
        @if(auth()->user() && auth()->user()->hasPermission('permissoes.create'))
        <a href="{{ route('permissions.create') }}" 
           class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200 flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Nova Permissão</span>
        </a>
        @endif
    </div>

    <!-- Mensagens de Feedback -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
        <div class="flex">
            <div class="py-1"><i class="fas fa-check-circle mr-2"></i></div>
            <div>{{ session('success') }}</div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
        <div class="flex">
            <div class="py-1"><i class="fas fa-exclamation-circle mr-2"></i></div>
            <div>{{ session('error') }}</div>
        </div>
    </div>
    @endif

    <!-- Cards de Roles -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($roles as $role)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-{{ $role->isSuperAdmin() ? 'crown' : ($role->isAdmin() ? 'user-shield' : ($role->isFuncionario() ? 'user-tie' : 'user')) }} text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $role->display_name }}</h3>
                            <p class="text-sm text-gray-500">Nível {{ $role->level }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $role->is_active ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">{{ $role->description ?? 'Sem descrição' }}</p>
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-users mr-2"></i>
                        <span>{{ $role->users_count ?? $role->users->count() }} usuários</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-500 mt-1">
                        <i class="fas fa-key mr-2"></i>
                        <span>{{ $role->permissions->count() }} permissões</span>
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    @if(auth()->user() && auth()->user()->hasPermission('permissoes.update'))
                    <a href="{{ route('permissions.manage-role', $role) }}" 
                       class="flex-1 bg-primary text-white text-center py-2 px-4 rounded-lg hover:bg-primary transition-colors duration-200 text-sm">
                        <i class="fas fa-edit mr-1"></i>
                        Gerenciar
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Tabela de Permissões por Módulo -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Permissões por Módulo</h2>
            <p class="text-gray-600 text-sm mt-1">Visualize todas as permissões organizadas por módulo do sistema</p>
        </div>
        
        <div class="p-6">
            @foreach($permissions as $module => $modulePermissions)
            <div class="mb-8 last:mb-0">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-{{ $moduleIcons[$module] ?? 'folder' }} text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 capitalize">{{ $module }}</h3>
                        <p class="text-sm text-gray-500">{{ $modulePermissions->count() }} permissões</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($modulePermissions as $permission)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-medium text-gray-900">{{ $permission->display_name }}</h4>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $permission->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $permission->is_active ? 'Ativa' : 'Inativa' }}
                            </span>
                        </div>
                        
                        <p class="text-sm text-gray-500 mb-3">{{ $permission->description ?? 'Sem descrição' }}</p>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-xs text-gray-400">
                                <code class="bg-gray-100 px-2 py-1 rounded">{{ $permission->name }}</code>
                            </div>
                            
                            @if(auth()->user() && auth()->user()->hasPermission('permissoes.update'))
                            <div class="flex space-x-1">
                                <a href="{{ route('permissions.show', $permission) }}" 
                                   class="text-primary hover:text-blue-800 text-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('permissions.edit', $permission) }}" 
                                   class="text-yellow-600 hover:text-yellow-800 text-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(auth()->user()->hasPermission('permissoes.delete'))
                                <form method="POST" action="{{ route('permissions.destroy', $permission) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta permissão?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
