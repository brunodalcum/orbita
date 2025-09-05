<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Usuário - DSPay</title>
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
                            <h1 class="text-2xl font-bold text-gray-900">Detalhes do Usuário</h1>
                            <p class="text-gray-600 mt-1">Informações completas do usuário</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="<?php echo e(route('dashboard.users')); ?>" 
                               class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors flex items-center space-x-2">
                                <i class="fas fa-arrow-left"></i>
                                <span>Voltar</span>
                            </a>
                            <?php if(auth()->user()->hasPermission('users.update')): ?>
                                <a href="<?php echo e(route('users.edit', $user)); ?>" 
                                   class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors flex items-center space-x-2">
                                    <i class="fas fa-edit"></i>
                                    <span>Editar</span>
                                </a>
                            <?php endif; ?>
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
                                                    <?php echo e(strtoupper(substr($user->name, 0, 2))); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-bold text-gray-900"><?php echo e($user->name); ?></h3>
                                            <p class="text-gray-600"><?php echo e($user->email); ?></p>
                                            <div class="flex items-center mt-2">
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
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Detalhes -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome Completo</label>
                                            <p class="text-gray-900"><?php echo e($user->name); ?></p>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                                            <p class="text-gray-900"><?php echo e($user->email); ?></p>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
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
                                                <span class="text-gray-500">Sem role definido</span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
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
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Permissões -->
                            <?php if($user->role): ?>
                                <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Permissões do Role</h2>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <?php $__currentLoopData = $user->role->permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex items-center space-x-2 p-3 bg-gray-50 rounded-lg">
                                                <i class="fas fa-check-circle text-green-500"></i>
                                                <span class="text-sm text-gray-700"><?php echo e($permission->display_name); ?></span>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    
                                    <?php if($user->role->permissions->count() === 0): ?>
                                        <p class="text-gray-500 text-center py-4">Este role não possui permissões específicas</p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Sidebar -->
                        <div class="space-y-6">
                            <!-- Ações Rápidas -->
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações Rápidas</h3>
                                
                                <div class="space-y-3">
                                    <?php if(auth()->user()->hasPermission('users.update')): ?>
                                        <a href="<?php echo e(route('users.edit', $user)); ?>" 
                                           class="w-full flex items-center justify-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                            <i class="fas fa-edit mr-2"></i>
                                            Editar Usuário
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if(auth()->user()->hasPermission('users.update') && $user->id !== auth()->id()): ?>
                                        <button onclick="toggleUserStatus(<?php echo e($user->id); ?>, <?php echo e($user->is_active ? 'false' : 'true'); ?>)"
                                                class="w-full flex items-center justify-center px-4 py-2 <?php echo e($user->is_active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600'); ?> text-white rounded-lg transition-colors">
                                            <i class="fas fa-<?php echo e($user->is_active ? 'pause' : 'play'); ?> mr-2"></i>
                                            <?php echo e($user->is_active ? 'Desativar' : 'Ativar'); ?> Usuário
                                        </button>
                                    <?php endif; ?>
                                    
                                    <?php if(auth()->user()->hasPermission('users.delete') && $user->id !== auth()->id()): ?>
                                        <button onclick="deleteUser(<?php echo e($user->id); ?>, '<?php echo e($user->name); ?>')"
                                                class="w-full flex items-center justify-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                            <i class="fas fa-trash mr-2"></i>
                                            Excluir Usuário
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Informações do Sistema -->
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Sistema</h3>
                                
                                <div class="space-y-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">ID do Usuário:</span>
                                        <p class="text-gray-600">#<?php echo e($user->id); ?></p>
                                    </div>
                                    
                                    <div>
                                        <span class="font-medium text-gray-700">Criado em:</span>
                                        <p class="text-gray-600"><?php echo e($user->created_at->format('d/m/Y H:i')); ?></p>
                                    </div>
                                    
                                    <div>
                                        <span class="font-medium text-gray-700">Última atualização:</span>
                                        <p class="text-gray-600"><?php echo e($user->updated_at->format('d/m/Y H:i')); ?></p>
                                    </div>
                                    
                                    <?php if($user->email_verified_at): ?>
                                        <div>
                                            <span class="font-medium text-gray-700">E-mail verificado:</span>
                                            <p class="text-gray-600"><?php echo e($user->email_verified_at->format('d/m/Y H:i')); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Descrição do Role -->
                            <?php if($user->role): ?>
                                <div class="bg-white rounded-lg shadow-sm p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Sobre o Role</h3>
                                    
                                    <div class="space-y-3">
                                        <div>
                                            <span class="font-medium text-gray-700">Nome:</span>
                                            <p class="text-gray-600"><?php echo e($user->role->display_name); ?></p>
                                        </div>
                                        
                                        <?php if($user->role->description): ?>
                                            <div>
                                                <span class="font-medium text-gray-700">Descrição:</span>
                                                <p class="text-gray-600"><?php echo e($user->role->description); ?></p>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div>
                                            <span class="font-medium text-gray-700">Nível:</span>
                                            <p class="text-gray-600"><?php echo e($user->role->level); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
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
<?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/dashboard/users/show.blade.php ENDPATH**/ ?>