<?php $__env->startSection('title', 'Meu Perfil'); ?>
<?php $__env->startSection('subtitle', 'Gerencie suas informações pessoais'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Profile Header -->
    <div class="card p-6">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
            <div class="w-24 h-24 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                <?php echo e(substr($user->name, 0, 1)); ?>

            </div>
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-800"><?php echo e($user->name); ?></h2>
                <p class="text-gray-600 mb-2"><?php echo e($user->email); ?></p>
                <div class="flex items-center space-x-4">
                    <span class="status-badge ativo"><?php echo e($user->role->display_name ?? 'Licenciado'); ?></span>
                    <span class="text-sm text-gray-500">
                        <i class="fas fa-calendar mr-1"></i>
                        Membro desde <?php echo e($user->created_at->format('M Y')); ?>

                    </span>
                </div>
            </div>
            <div>
                <button class="btn-primary">
                    <i class="fas fa-camera mr-2"></i>
                    Alterar Foto
                </button>
            </div>
        </div>
    </div>

    <!-- Profile Form -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Personal Information -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Informações Pessoais</h3>
            
            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nome Completo</label>
                    <input type="text" value="<?php echo e($user->name); ?>" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
                    <input type="email" value="<?php echo e($user->email); ?>" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                    <input type="tel" placeholder="(11) 99999-9999" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">CPF/CNPJ</label>
                    <input type="text" placeholder="000.000.000-00" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data de Nascimento</label>
                    <input type="date" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <button type="submit" class="w-full btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Alterações
                </button>
            </form>
        </div>

        <!-- Address Information -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Endereço</h3>
            
            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">CEP</label>
                    <input type="text" placeholder="00000-000" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option>Selecione...</option>
                            <option>SP - São Paulo</option>
                            <option>RJ - Rio de Janeiro</option>
                            <option>MG - Minas Gerais</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cidade</label>
                        <input type="text" placeholder="Cidade" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rua</label>
                    <input type="text" placeholder="Nome da rua" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Número</label>
                        <input type="text" placeholder="123" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Complemento</label>
                        <input type="text" placeholder="Apto, sala, etc." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bairro</label>
                    <input type="text" placeholder="Nome do bairro" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <button type="submit" class="w-full btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Endereço
                </button>
            </form>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Configurações de Segurança</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Change Password -->
            <div>
                <h4 class="font-medium text-gray-800 mb-4">Alterar Senha</h4>
                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Senha Atual</label>
                        <input type="password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nova Senha</label>
                        <input type="password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Nova Senha</label>
                        <input type="password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-lock mr-2"></i>
                        Alterar Senha
                    </button>
                </form>
            </div>

            <!-- Two Factor Authentication -->
            <div>
                <h4 class="font-medium text-gray-800 mb-4">Autenticação de Dois Fatores</h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-800">SMS</p>
                            <p class="text-sm text-gray-600">Receber códigos via SMS</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-800">E-mail</p>
                            <p class="text-sm text-gray-600">Receber códigos via e-mail</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-800">App Autenticador</p>
                            <p class="text-sm text-gray-600">Google Authenticator, Authy, etc.</p>
                        </div>
                        <button class="text-blue-600 hover:text-blue-800 font-medium">
                            Configurar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Settings -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Configurações da Conta</h3>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium text-gray-800">Notificações por E-mail</p>
                    <p class="text-sm text-gray-600">Receber notificações sobre vendas e comissões</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium text-gray-800">Relatórios Automáticos</p>
                    <p class="text-sm text-gray-600">Receber relatórios mensais automaticamente</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium text-gray-800">Marketing</p>
                    <p class="text-sm text-gray-600">Receber informações sobre novos produtos e promoções</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.licenciado', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/licenciado/perfil.blade.php ENDPATH**/ ?>