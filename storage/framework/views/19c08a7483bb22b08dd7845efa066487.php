<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Gestão de Usuários - DSPay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar Dinâmico -->
        <?php if (isset($component)) { $__componentOriginal58bfc5a96c4a22dbbb3ff0b16eeb91ec = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal58bfc5a96c4a22dbbb3ff0b16eeb91ec = $attributes; } ?>
<?php $component = App\View\Components\DynamicSidebar::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\DynamicSidebar::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal58bfc5a96c4a22dbbb3ff0b16eeb91ec)): ?>
<?php $attributes = $__attributesOriginal58bfc5a96c4a22dbbb3ff0b16eeb91ec; ?>
<?php unset($__attributesOriginal58bfc5a96c4a22dbbb3ff0b16eeb91ec); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal58bfc5a96c4a22dbbb3ff0b16eeb91ec)): ?>
<?php $component = $__componentOriginal58bfc5a96c4a22dbbb3ff0b16eeb91ec; ?>
<?php unset($__componentOriginal58bfc5a96c4a22dbbb3ff0b16eeb91ec); ?>
<?php endif; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Gestão de Usuários</h1>
                            <p class="text-gray-600 mt-1">Gerencie usuários e permissões do sistema</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <?php if(auth()->user()->hasPermission('users.create')): ?>
                                <button onclick="openCreateModal()" 
                                        class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-2 rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200 flex items-center space-x-2">
                                    <i class="fas fa-plus"></i>
                                    <span>Novo Usuário</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <!-- Alertas -->
                <?php if(session('success')): ?>
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <!-- Filtros -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex-1 min-w-64">
                            <input type="text" 
                                   placeholder="Buscar usuários..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="flex items-center space-x-4">
                            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Todos os Roles</option>
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($role->id); ?>"><?php echo e($role->display_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Todos os Status</option>
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tabela de Usuários -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Usuário
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Último Acesso
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                                        <span class="text-white font-medium text-sm">
                                                            <?php echo e(strtoupper(substr($user->name, 0, 2))); ?>

                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900"><?php echo e($user->name); ?></div>
                                                    <div class="text-sm text-gray-500"><?php echo e($user->email); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if($user->role): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    <?php if($user->role->name === 'super_admin'): ?> bg-red-100 text-red-800
                                                    <?php elseif($user->role->name === 'admin'): ?> bg-blue-100 text-blue-800
                                                    <?php elseif($user->role->name === 'funcionario'): ?> bg-green-100 text-green-800
                                                    <?php else: ?> bg-gray-100 text-gray-800
                                                    <?php endif; ?>">
                                                    <?php echo e($user->role->display_name); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Sem Role
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if($user->is_active): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Ativo
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    Inativo
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($user->updated_at ? $user->updated_at->format('d/m/Y H:i') : 'Nunca'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <?php if(auth()->user()->hasPermission('users.view')): ?>
                                                    <a href="<?php echo e(route('users.show', $user)); ?>" 
                                                       class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors"
                                                       title="Visualizar">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if(auth()->user()->hasPermission('users.update')): ?>
                                                    <a href="<?php echo e(route('users.edit', $user)); ?>" 
                                                       class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50 transition-colors"
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if(auth()->user()->hasPermission('users.update') && $user->id !== auth()->id()): ?>
                                                    <button onclick="toggleUserStatus(<?php echo e($user->id); ?>, <?php echo e($user->is_active ? 'false' : 'true'); ?>)"
                                                            class="text-yellow-600 hover:text-yellow-900 p-2 rounded-lg hover:bg-yellow-50 transition-colors"
                                                            title="<?php echo e($user->is_active ? 'Desativar' : 'Ativar'); ?>">
                                                        <i class="fas fa-<?php echo e($user->is_active ? 'pause' : 'play'); ?>"></i>
                                                    </button>
                                                <?php endif; ?>
                                                
                                                <?php if(auth()->user()->hasPermission('users.delete') && $user->id !== auth()->id()): ?>
                                                    <button onclick="deleteUser(<?php echo e($user->id); ?>, '<?php echo e($user->name); ?>')"
                                                            class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors"
                                                            title="Excluir">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="text-gray-500">
                                                <i class="fas fa-users text-4xl mb-4"></i>
                                                <p class="text-lg">Nenhum usuário encontrado</p>
                                                <p class="text-sm">Comece criando um novo usuário</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <?php if($users->hasPages()): ?>
                        <div class="px-6 py-4 border-t border-gray-200">
                            <?php echo e($users->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal de Criação de Usuário -->
    <div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Novo Usuário</h3>
                        <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <form id="createUserForm">
                        <?php echo csrf_field(); ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nome -->
                            <div class="md:col-span-2">
                                <label for="modal_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nome Completo *
                                </label>
                                <input type="text" 
                                       id="modal_name" 
                                       name="name" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Digite o nome completo"
                                       required>
                                <div id="error_name" class="text-red-500 text-sm mt-1 hidden"></div>
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2">
                                <label for="modal_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    E-mail *
                                </label>
                                <input type="email" 
                                       id="modal_email" 
                                       name="email" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Digite o e-mail"
                                       required>
                                <div id="error_email" class="text-red-500 text-sm mt-1 hidden"></div>
                            </div>

                            <!-- Senha -->
                            <div>
                                <label for="modal_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Senha *
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           id="modal_password" 
                                           name="password" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-10"
                                           placeholder="Digite a senha"
                                           required>
                                    <button type="button" onclick="togglePassword('modal_password')" 
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i class="fas fa-eye text-gray-400 hover:text-gray-600" id="modal_password_icon"></i>
                                    </button>
                                </div>
                                <div id="error_password" class="text-red-500 text-sm mt-1 hidden"></div>
                            </div>

                            <!-- Confirmar Senha -->
                            <div>
                                <label for="modal_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirmar Senha *
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           id="modal_password_confirmation" 
                                           name="password_confirmation" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-10"
                                           placeholder="Confirme a senha"
                                           required>
                                    <button type="button" onclick="togglePassword('modal_password_confirmation')" 
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i class="fas fa-eye text-gray-400 hover:text-gray-600" id="modal_password_confirmation_icon"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Role -->
                            <div>
                                <label for="modal_role_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Perfil de Acesso *
                                </label>
                                <select id="modal_role_id" 
                                        name="role_id" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required>
                                    <option value="">Selecione um perfil</option>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($role->id); ?>"><?php echo e($role->display_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div id="error_role_id" class="text-red-500 text-sm mt-1 hidden"></div>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="modal_is_active" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status
                                </label>
                                <select id="modal_is_active" 
                                        name="is_active" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="1">Ativo</option>
                                    <option value="0">Inativo</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                            <button type="button" 
                                    onclick="closeCreateModal()" 
                                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" 
                                    id="submitBtn"
                                    class="px-6 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200 flex items-center space-x-2">
                                <i class="fas fa-save"></i>
                                <span>Criar Usuário</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
        // Funções do Modal de Criação
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
            document.getElementById('modal_name').focus();
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
            document.getElementById('createUserForm').reset();
            clearErrors();
        }

        function clearErrors() {
            const errorElements = document.querySelectorAll('[id^="error_"]');
            errorElements.forEach(element => {
                element.classList.add('hidden');
                element.textContent = '';
            });
            
            const inputElements = document.querySelectorAll('#createUserForm input, #createUserForm select');
            inputElements.forEach(element => {
                element.classList.remove('border-red-500');
            });
        }

        function showError(field, message) {
            const errorElement = document.getElementById(`error_${field}`);
            const inputElement = document.getElementById(`modal_${field}`);
            
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
            }
            
            if (inputElement) {
                inputElement.classList.add('border-red-500');
            }
        }

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '_icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Submissão do formulário de criação
        document.getElementById('createUserForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            
            // Mostrar loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Criando...</span>';
            
            clearErrors();
            
            const formData = new FormData(this);
            
            fetch('<?php echo e(route("users.store")); ?>', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers.get('content-type'));
                
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Error response text:', text);
                        throw new Error(`HTTP ${response.status}: ${text.substring(0, 200)}...`);
                    });
                }
                
                return response.json().catch(err => {
                    return response.text().then(text => {
                        console.error('JSON parse error. Response text:', text.substring(0, 500));
                        throw new Error('Resposta não é JSON válido');
                    });
                });
            })
            .then(data => {
                console.log('Success data:', data);
                if (data.success) {
                    closeCreateModal();
                    location.reload();
                } else if (data.errors) {
                    // Mostrar erros de validação
                    Object.keys(data.errors).forEach(field => {
                        showError(field, data.errors[field][0]);
                    });
                } else {
                    alert('Erro ao criar usuário: ' + (data.message || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao criar usuário: ' + error.message);
            })
            .finally(() => {
                // Restaurar botão
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });

        // Fechar modal ao clicar fora
        document.getElementById('createModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCreateModal();
            }
        });

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
                        location.reload();
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
<?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/dashboard/users/index.blade.php ENDPATH**/ ?>