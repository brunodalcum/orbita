<?php $__env->startSection('title', 'Perfil de Usuário - Permissões'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Perfil de Usuário</h1>
            <p class="text-gray-600 mt-2">Gerencie as permissões de acesso para cada tipo de usuário</p>
        </div>
        
        <?php if(auth()->user() && auth()->user()->hasPermission('permissoes.create')): ?>
        <a href="<?php echo e(route('permissions.create')); ?>" 
           class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200 flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Nova Permissão</span>
        </a>
        <?php endif; ?>
    </div>

    <!-- Mensagens de Feedback -->
    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
        <div class="flex">
            <div class="py-1"><i class="fas fa-check-circle mr-2"></i></div>
            <div><?php echo e(session('success')); ?></div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
        <div class="flex">
            <div class="py-1"><i class="fas fa-exclamation-circle mr-2"></i></div>
            <div><?php echo e(session('error')); ?></div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Cards de Roles -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-<?php echo e($role->isSuperAdmin() ? 'crown' : ($role->isAdmin() ? 'user-shield' : ($role->isFuncionario() ? 'user-tie' : 'user'))); ?> text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900"><?php echo e($role->display_name); ?></h3>
                            <p class="text-sm text-gray-500">Nível <?php echo e($role->level); ?></p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo e($role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                        <?php echo e($role->is_active ? 'Ativo' : 'Inativo'); ?>

                    </span>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2"><?php echo e($role->description ?? 'Sem descrição'); ?></p>
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-users mr-2"></i>
                        <span><?php echo e($role->users_count ?? $role->users->count()); ?> usuários</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-500 mt-1">
                        <i class="fas fa-key mr-2"></i>
                        <span><?php echo e($role->permissions->count()); ?> permissões</span>
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    <?php if(auth()->user() && auth()->user()->hasPermission('permissoes.update')): ?>
                    <a href="<?php echo e(route('permissions.manage-role', $role)); ?>" 
                       class="flex-1 bg-blue-500 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors duration-200 text-sm">
                        <i class="fas fa-edit mr-1"></i>
                        Gerenciar
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Tabela de Permissões por Módulo -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Permissões por Módulo</h2>
            <p class="text-gray-600 text-sm mt-1">Visualize todas as permissões organizadas por módulo do sistema</p>
        </div>
        
        <div class="p-6">
            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $modulePermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="mb-8 last:mb-0">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-<?php echo e($moduleIcons[$module] ?? 'folder'); ?> text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 capitalize"><?php echo e($module); ?></h3>
                        <p class="text-sm text-gray-500"><?php echo e($modulePermissions->count()); ?> permissões</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php $__currentLoopData = $modulePermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-medium text-gray-900"><?php echo e($permission->display_name); ?></h4>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo e($permission->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo e($permission->is_active ? 'Ativa' : 'Inativa'); ?>

                            </span>
                        </div>
                        
                        <p class="text-sm text-gray-500 mb-3"><?php echo e($permission->description ?? 'Sem descrição'); ?></p>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-xs text-gray-400">
                                <code class="bg-gray-100 px-2 py-1 rounded"><?php echo e($permission->name); ?></code>
                            </div>
                            
                            <?php if(auth()->user() && auth()->user()->hasPermission('permissoes.update')): ?>
                            <div class="flex space-x-1">
                                <a href="<?php echo e(route('permissions.show', $permission)); ?>" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('permissions.edit', $permission)); ?>" 
                                   class="text-yellow-600 hover:text-yellow-800 text-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if(auth()->user()->hasPermission('permissoes.delete')): ?>
                                <form method="POST" action="<?php echo e(route('permissions.destroy', $permission)); ?>" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta permissão?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/dashboard/permissions/index.blade.php ENDPATH**/ ?>