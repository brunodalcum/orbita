
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

@section('title', 'Gerenciar Permissões - ' . $role->display_name)

@section('content')
<x-simple-branding />

<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
            <a href="{{ route('permissions.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition-colors duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-{{ $role->isSuperAdmin() ? 'crown' : ($role->isAdmin() ? 'user-shield' : ($role->isFuncionario() ? 'user-tie' : 'user')) }} text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $role->display_name }}</h1>
                    <p class="text-gray-600 mt-1">Gerenciar permissões de acesso</p>
                </div>
            </div>
        </div>
        
        <div class="text-right">
            <div class="text-sm text-gray-500 mb-1">{{ $role->users->count() }} usuários com este perfil</div>
            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $role->is_active ? 'Ativo' : 'Inativo' }}
            </span>
        </div>
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

    <!-- Formulário de Permissões -->
    <form method="POST" action="{{ route('permissions.update-role', $role) }}" class="bg-white rounded-lg shadow-md">
        @csrf
        @method('PUT')
        
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Configurar Permissões</h2>
                    <p class="text-gray-600 text-sm mt-1">Selecione as permissões que este perfil deve ter acesso</p>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" onclick="selectAll()" 
                            class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors duration-200 text-sm">
                        <i class="fas fa-check-double mr-1"></i>
                        Selecionar Todas
                    </button>
                    <button type="button" onclick="deselectAll()" 
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200 text-sm">
                        <i class="fas fa-times mr-1"></i>
                        Desmarcar Todas
                    </button>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                            <i class="fas fa-key text-white"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-primary">Total de Permissões</p>
                            <p class="text-2xl font-bold text-blue-900" id="total-permissions">{{ $permissions->flatten()->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-600">Selecionadas</p>
                            <p class="text-2xl font-bold text-green-900" id="selected-count">{{ count($rolePermissions) }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-folder text-white"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-yellow-600">Módulos</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ $permissions->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-purple-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-percentage text-white"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-purple-600">Cobertura</p>
                            <p class="text-2xl font-bold text-purple-900" id="coverage-percentage">
                                {{ $permissions->flatten()->count() > 0 ? round((count($rolePermissions) / $permissions->flatten()->count()) * 100) : 0 }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissões por Módulo -->
            @foreach($permissions as $module => $modulePermissions)
            <div class="mb-8 last:mb-0">
                <div class="flex items-center justify-between mb-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-{{ $moduleIcons[$module] ?? 'folder' }} text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 capitalize">{{ $module }}</h3>
                            <p class="text-sm text-gray-500">{{ $modulePermissions->count() }} permissões disponíveis</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="toggleModule('{{ $module }}')" 
                                class="text-sm bg-primary text-white px-3 py-1 rounded hover:bg-primary transition-colors duration-200">
                            <i class="fas fa-toggle-on mr-1"></i>
                            Alternar Módulo
                        </button>
                        <span class="text-sm text-gray-600" id="module-{{ $module }}-count">
                            <span class="module-selected-count">{{ $modulePermissions->whereIn('id', $rolePermissions)->count() }}</span> / {{ $modulePermissions->count() }}
                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($modulePermissions as $permission)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors duration-200">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" 
                                   name="permissions[]" 
                                   value="{{ $permission->id }}"
                                   class="permission-checkbox module-{{ $module }}-checkbox mt-1 mr-3 rounded border-gray-300 text-primary focus:ring-blue-500"
                                   {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                   onchange="updateCounts()">
                            
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-medium text-gray-900">{{ $permission->display_name }}</h4>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $permission->action === 'view' ? 'blue' : ($permission->action === 'create' ? 'green' : ($permission->action === 'update' ? 'yellow' : ($permission->action === 'delete' ? 'red' : 'purple'))) }}-100 text-{{ $permission->action === 'view' ? 'blue' : ($permission->action === 'create' ? 'green' : ($permission->action === 'update' ? 'yellow' : ($permission->action === 'delete' ? 'red' : 'purple'))) }}-800">
                                        {{ ucfirst($permission->action) }}
                                    </span>
                                </div>
                                
                                @if($permission->description)
                                <p class="text-sm text-gray-500 mb-2">{{ $permission->description }}</p>
                                @endif
                                
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">{{ $permission->name }}</code>
                            </div>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Footer do Formulário -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    As alterações serão aplicadas imediatamente a todos os usuários com este perfil.
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('permissions.index') }}" 
                       class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                        <i class="fas fa-times mr-1"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-2 rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200">
                        <i class="fas fa-save mr-1"></i>
                        Salvar Permissões
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function selectAll() {
    const checkboxes = document.querySelectorAll('.permission-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    updateCounts();
}

function deselectAll() {
    const checkboxes = document.querySelectorAll('.permission-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    updateCounts();
}

function toggleModule(module) {
    const checkboxes = document.querySelectorAll(`.module-${module}-checkbox`);
    const firstCheckbox = checkboxes[0];
    const shouldCheck = !firstCheckbox.checked;
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = shouldCheck;
    });
    updateCounts();
}

function updateCounts() {
    // Contar total selecionado
    const selectedCheckboxes = document.querySelectorAll('.permission-checkbox:checked');
    const totalPermissions = document.querySelectorAll('.permission-checkbox').length;
    
    document.getElementById('selected-count').textContent = selectedCheckboxes.length;
    
    // Calcular porcentagem
    const percentage = totalPermissions > 0 ? Math.round((selectedCheckboxes.length / totalPermissions) * 100) : 0;
    document.getElementById('coverage-percentage').textContent = percentage + '%';
    
    // Atualizar contadores por módulo
    const modules = {!! json_encode(array_keys($permissions->toArray())) !!};
    modules.forEach(module => {
        const moduleCheckboxes = document.querySelectorAll(`.module-${module}-checkbox:checked`);
        const moduleCountElement = document.querySelector(`#module-${module}-count .module-selected-count`);
        if (moduleCountElement) {
            moduleCountElement.textContent = moduleCheckboxes.length;
        }
    });
}

// Inicializar contadores ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    updateCounts();
});
</script>

@endsection
