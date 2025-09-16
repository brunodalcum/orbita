
<x-dynamic-branding />
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Usuário - DSPay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
/* BRANDING FORÇADO PARA ESTA PÁGINA */
:root {
    --primary-color: #3B82F6;
    --secondary-color: #6B7280;
    --accent-color: #10B981;
    --text-color: #1F2937;
    --background-color: #FFFFFF;
    --primary-light: rgba(59, 130, 246, 0.1);
    --primary-dark: #2563EB;
    --primary-text: #FFFFFF;
}

/* Sobrescrita agressiva de todas as cores azuis */
.bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400,
.bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-blue-800, .bg-blue-900,
.bg-indigo-50, .bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700, .bg-indigo-800, .bg-indigo-900 {
    background-color: var(--primary-color) !important;
}

.hover\:bg-blue-600:hover, .hover\:bg-blue-700:hover, .hover\:bg-blue-800:hover,
.hover\:bg-indigo-600:hover, .hover\:bg-indigo-700:hover, .hover\:bg-indigo-800:hover {
    background-color: var(--primary-dark) !important;
}

.text-blue-500, .text-blue-600, .text-blue-700, .text-blue-800, .text-blue-900,
.text-indigo-500, .text-indigo-600, .text-indigo-700, .text-indigo-800, .text-indigo-900 {
    color: var(--primary-color) !important;
}

.border-blue-500, .border-blue-600, .border-blue-700, .border-blue-800, .border-blue-900,
.border-indigo-500, .border-indigo-600, .border-indigo-700, .border-indigo-800, .border-indigo-900 {
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
[style*="background-color: #3b82f6"], [style*="background-color: #2563eb"],
[style*="background-color: #1d4ed8"], [style*="background-color: #1e40af"] {
    background-color: var(--primary-color) !important;
}

[style*="color: #3b82f6"], [style*="color: #2563eb"],
[style*="color: #1d4ed8"], [style*="color: #1e40af"] {
    color: var(--primary-color) !important;
}

.animate-spin[class*="border-blue"], .animate-spin[class*="border-indigo"] {
    border-color: var(--primary-color) transparent var(--primary-color) transparent !important;
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
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Detalhes do Usuário</h1>
                            <p class="text-gray-600 mt-1">Informações completas do usuário</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('dashboard.users') }}" 
                               class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors flex items-center space-x-2">
                                <i class="fas fa-arrow-left"></i>
                                <span>Voltar</span>
                            </a>
                            @if(auth()->user()->hasPermission('users.update'))
                                <a href="{{ route('users.edit', $user) }}" 
                                   class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors flex items-center space-x-2">
                                    <i class="fas fa-edit"></i>
                                    <span>Editar</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <div class="max-w-4xl mx-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Informações Principais -->
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-6">Informações Pessoais</h2>
                                
                                <div class="space-y-6">
                                    <!-- Avatar e Nome -->
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0 h-20 w-20">
                                            <div class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                                <span class="text-white font-bold text-2xl">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h3>
                                            <p class="text-gray-600">{{ $user->email }}</p>
                                            <div class="flex items-center mt-2">
                                                @if($user->is_active)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Ativo
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-times-circle mr-1"></i>
                                                        Inativo
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Detalhes -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome Completo</label>
                                            <p class="text-gray-900">{{ $user->name }}</p>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                                            <p class="text-gray-900">{{ $user->email }}</p>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                            @if($user->role)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($user->role->name === 'super_admin') bg-red-100 text-red-800
                                                    @elseif($user->role->name === 'admin') bg-blue-100 text-blue-800
                                                    @elseif($user->role->name === 'funcionario') bg-green-100 text-green-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ $user->role->display_name }}
                                                </span>
                                            @else
                                                <span class="text-gray-500">Sem role definido</span>
                                            @endif
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                            @if($user->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Ativo
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    Inativo
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Permissões -->
                            @if($user->role)
                                <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Permissões do Role</h2>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($user->role->permissions as $permission)
                                            <div class="flex items-center space-x-2 p-3 bg-gray-50 rounded-lg">
                                                <i class="fas fa-check-circle text-green-500"></i>
                                                <span class="text-sm text-gray-700">{{ $permission->display_name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    @if($user->role->permissions->count() === 0)
                                        <p class="text-gray-500 text-center py-4">Este role não possui permissões específicas</p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Sidebar -->
                        <div class="space-y-6">
                            <!-- Ações Rápidas -->
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações Rápidas</h3>
                                
                                <div class="space-y-3">
                                    @if(auth()->user()->hasPermission('users.update'))
                                        <a href="{{ route('users.edit', $user) }}" 
                                           class="w-full flex items-center justify-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                            <i class="fas fa-edit mr-2"></i>
                                            Editar Usuário
                                        </a>
                                    @endif
                                    
                                    @if(auth()->user()->hasPermission('users.update') && $user->id !== auth()->id())
                                        <button onclick="toggleUserStatus({{ $user->id }}, {{ $user->is_active ? 'false' : 'true' }})"
                                                class="w-full flex items-center justify-center px-4 py-2 {{ $user->is_active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-lg transition-colors">
                                            <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }} mr-2"></i>
                                            {{ $user->is_active ? 'Desativar' : 'Ativar' }} Usuário
                                        </button>
                                    @endif
                                    
                                    @if(auth()->user()->hasPermission('users.delete') && $user->id !== auth()->id())
                                        <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')"
                                                class="w-full flex items-center justify-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                            <i class="fas fa-trash mr-2"></i>
                                            Excluir Usuário
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- Informações do Sistema -->
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Sistema</h3>
                                
                                <div class="space-y-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">ID do Usuário:</span>
                                        <p class="text-gray-600">#{{ $user->id }}</p>
                                    </div>
                                    
                                    <div>
                                        <span class="font-medium text-gray-700">Criado em:</span>
                                        <p class="text-gray-600">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    
                                    <div>
                                        <span class="font-medium text-gray-700">Última atualização:</span>
                                        <p class="text-gray-600">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    
                                    @if($user->email_verified_at)
                                        <div>
                                            <span class="font-medium text-gray-700">E-mail verificado:</span>
                                            <p class="text-gray-600">{{ $user->email_verified_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Descrição do Role -->
                            @if($user->role)
                                <div class="bg-white rounded-lg shadow-sm p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Sobre o Role</h3>
                                    
                                    <div class="space-y-3">
                                        <div>
                                            <span class="font-medium text-gray-700">Nome:</span>
                                            <p class="text-gray-600">{{ $user->role->display_name }}</p>
                                        </div>
                                        
                                        @if($user->role->description)
                                            <div>
                                                <span class="font-medium text-gray-700">Descrição:</span>
                                                <p class="text-gray-600">{{ $user->role->description }}</p>
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <span class="font-medium text-gray-700">Nível:</span>
                                            <p class="text-gray-600">{{ $user->role->level }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Confirmar Exclusão</h3>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6">
                        Tem certeza que deseja excluir o usuário <strong id="userName"></strong>? 
                        Esta ação não pode ser desfeita.
                    </p>
                    <div class="flex justify-end space-x-3">
                        <button onclick="closeDeleteModal()" 
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                            Cancelar
                        </button>
                        <button id="confirmDeleteBtn" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Excluir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteUser(userId, userName) {
            document.getElementById('userName').textContent = userName;
            document.getElementById('deleteModal').classList.remove('hidden');
            
            document.getElementById('confirmDeleteBtn').onclick = function() {
                fetch(`/dashboard/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => {
                    if (response.ok) {
                        window.location.href = '/dashboard/users';
                    } else {
                        alert('Erro ao excluir usuário');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erro ao excluir usuário');
                });
            };
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function toggleUserStatus(userId, newStatus) {
            fetch(`/dashboard/users/${userId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Erro ao alterar status do usuário');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao alterar status do usuário');
            });
        }

        // Fechar modal ao clicar fora
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>
