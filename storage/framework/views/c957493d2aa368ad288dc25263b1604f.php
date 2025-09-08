<?php $__env->startSection('title', 'Gerenciar Permissões - ' . $role->display_name); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
            <a href="<?php echo e(route('permissions.index')); ?>" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition-colors duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-<?php echo e($role->isSuperAdmin() ? 'crown' : ($role->isAdmin() ? 'user-shield' : ($role->isFuncionario() ? 'user-tie' : 'user'))); ?> text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900"><?php echo e($role->display_name); ?></h1>
                    <p class="text-gray-600 mt-1">Gerenciar permissões de acesso</p>
                </div>
            </div>
        </div>
        
        <div class="text-right">
            <div class="text-sm text-gray-500 mb-1"><?php echo e($role->users->count()); ?> usuários com este perfil</div>
            <span class="px-3 py-1 text-sm font-semibold rounded-full <?php echo e($role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                <?php echo e($role->is_active ? 'Ativo' : 'Inativo'); ?>

            </span>
        </div>
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

    <!-- Formulário de Permissões -->
    <form method="POST" action="<?php echo e(route('permissions.update-role', $role)); ?>" class="bg-white rounded-lg shadow-md">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
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
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-key text-white"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-600">Total de Permissões</p>
                            <p class="text-2xl font-bold text-blue-900" id="total-permissions"><?php echo e($permissions->flatten()->count()); ?></p>
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
                            <p class="text-2xl font-bold text-green-900" id="selected-count"><?php echo e(count($rolePermissions)); ?></p>
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
                            <p class="text-2xl font-bold text-yellow-900"><?php echo e($permissions->count()); ?></p>
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
                                <?php echo e($permissions->flatten()->count() > 0 ? round((count($rolePermissions) / $permissions->flatten()->count()) * 100) : 0); ?>%
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissões por Módulo -->
            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $modulePermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="mb-8 last:mb-0">
                <div class="flex items-center justify-between mb-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-<?php echo e($moduleIcons[$module] ?? 'folder'); ?> text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 capitalize"><?php echo e($module); ?></h3>
                            <p class="text-sm text-gray-500"><?php echo e($modulePermissions->count()); ?> permissões disponíveis</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="toggleModule('<?php echo e($module); ?>')" 
                                class="text-sm bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition-colors duration-200">
                            <i class="fas fa-toggle-on mr-1"></i>
                            Alternar Módulo
                        </button>
                        <span class="text-sm text-gray-600" id="module-<?php echo e($module); ?>-count">
                            <span class="module-selected-count"><?php echo e($modulePermissions->whereIn('id', $rolePermissions)->count()); ?></span> / <?php echo e($modulePermissions->count()); ?>

                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php $__currentLoopData = $modulePermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors duration-200">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" 
                                   name="permissions[]" 
                                   value="<?php echo e($permission->id); ?>"
                                   class="permission-checkbox module-<?php echo e($module); ?>-checkbox mt-1 mr-3 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                   <?php echo e(in_array($permission->id, $rolePermissions) ? 'checked' : ''); ?>

                                   onchange="updateCounts()">
                            
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-medium text-gray-900"><?php echo e($permission->display_name); ?></h4>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-<?php echo e($permission->action === 'view' ? 'blue' : ($permission->action === 'create' ? 'green' : ($permission->action === 'update' ? 'yellow' : ($permission->action === 'delete' ? 'red' : 'purple')))); ?>-100 text-<?php echo e($permission->action === 'view' ? 'blue' : ($permission->action === 'create' ? 'green' : ($permission->action === 'update' ? 'yellow' : ($permission->action === 'delete' ? 'red' : 'purple')))); ?>-800">
                                        <?php echo e(ucfirst($permission->action)); ?>

                                    </span>
                                </div>
                                
                                <?php if($permission->description): ?>
                                <p class="text-sm text-gray-500 mb-2"><?php echo e($permission->description); ?></p>
                                <?php endif; ?>
                                
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600"><?php echo e($permission->name); ?></code>
                            </div>
                        </label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <!-- Footer do Formulário -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    As alterações serão aplicadas imediatamente a todos os usuários com este perfil.
                </div>
                
                <div class="flex space-x-3">
                    <a href="<?php echo e(route('permissions.index')); ?>" 
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
    const modules = <?php echo json_encode(array_keys($permissions->toArray())); ?>;
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/dashboard/permissions/manage-role.blade.php ENDPATH**/ ?>