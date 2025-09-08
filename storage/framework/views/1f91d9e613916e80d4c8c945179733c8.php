<aside class="w-64 text-white min-h-screen flex flex-col shadow-xl" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <!-- Logo/Header -->
    <div class="p-6 border-b border-white/20">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-user-tie text-purple-900 text-lg"></i>
            </div>
            <div>
                <h2 class="font-bold text-lg"><?php echo e(config('app.name', 'DSPay')); ?></h2>
                <p class="text-white/70 text-sm">Licenciado</p>
            </div>
        </div>
    </div>

    <!-- User Info -->
    <div class="p-4 border-b border-white/20">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-user text-white text-lg"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-sm truncate"><?php echo e($user->name); ?></h3>
                <p class="text-white/70 text-xs"><?php echo e($user->email); ?></p>
                <div class="flex items-center mt-1">
                    <div class="w-2 h-2 bg-green-400 rounded-full mr-1"></div>
                    <span class="text-green-200 text-xs">Online</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-2">
        <!-- Dashboard -->
        <a href="<?php echo e(route('licenciado.dashboard')); ?>" 
           class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-all duration-200 <?php echo e($currentRoute === 'licenciado.dashboard' ? 'bg-white/20 shadow-md' : 'hover:bg-white/10'); ?>">
            <i class="fas fa-tachometer-alt mr-3 text-lg"></i>
            <span class="font-medium">Dashboard</span>
        </a>
        
        <!-- Estabelecimentos -->
        <a href="<?php echo e(route('licenciado.estabelecimentos')); ?>" 
           class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-all duration-200 <?php echo e($currentRoute === 'licenciado.estabelecimentos' ? 'bg-white/20 shadow-md' : 'hover:bg-white/10'); ?>">
            <i class="fas fa-store mr-3 text-lg"></i>
            <span class="font-medium">Estabelecimentos</span>
        </a>
        
        <!-- Vendas -->
        <a href="<?php echo e(route('licenciado.vendas')); ?>" 
           class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-all duration-200 <?php echo e($currentRoute === 'licenciado.vendas' ? 'bg-white/20 shadow-md' : 'hover:bg-white/10'); ?>">
            <i class="fas fa-shopping-cart mr-3 text-lg"></i>
            <span class="font-medium">Vendas</span>
            <span class="ml-auto bg-green-500 text-white text-xs px-2 py-1 rounded-full">R$ 23.5k</span>
        </a>
        
        <!-- Comissões -->
        <a href="<?php echo e(route('licenciado.comissoes')); ?>" 
           class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-all duration-200 <?php echo e($currentRoute === 'licenciado.comissoes' ? 'bg-white/20 shadow-md' : 'hover:bg-white/10'); ?>">
            <i class="fas fa-coins mr-3 text-lg"></i>
            <span class="font-medium">Comissões</span>
            <span class="ml-auto bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">R$ 1.2k</span>
        </a>
        
        <!-- Relatórios -->
        <a href="<?php echo e(route('licenciado.relatorios')); ?>" 
           class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-all duration-200 <?php echo e($currentRoute === 'licenciado.relatorios' ? 'bg-white/20 shadow-md' : 'hover:bg-white/10'); ?>">
            <i class="fas fa-chart-bar mr-3 text-lg"></i>
            <span class="font-medium">Relatórios</span>
        </a>
        
        <!-- Divider -->
        <div class="border-t border-white/20 my-4"></div>
        
        <!-- Perfil -->
        <a href="<?php echo e(route('licenciado.perfil')); ?>" 
           class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-all duration-200 <?php echo e($currentRoute === 'licenciado.perfil' ? 'bg-white/20 shadow-md' : 'hover:bg-white/10'); ?>">
            <i class="fas fa-user-cog mr-3 text-lg"></i>
            <span class="font-medium">Meu Perfil</span>
        </a>
        
        <!-- Suporte -->
        <a href="<?php echo e(route('licenciado.suporte')); ?>" 
           class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-all duration-200 <?php echo e($currentRoute === 'licenciado.suporte' ? 'bg-white/20 shadow-md' : 'hover:bg-white/10'); ?>">
            <i class="fas fa-headset mr-3 text-lg"></i>
            <span class="font-medium">Suporte</span>
        </a>
    </nav>

    <!-- Footer Actions -->
    <div class="p-4 border-t border-white/20">
        <div class="space-y-2">
            <!-- Quick Stats -->
            <div class="bg-white/10 rounded-lg p-3">
                <div class="text-xs text-white/70 mb-1">Vendas Hoje</div>
                <div class="font-bold text-green-400">R$ 2.350,00</div>
            </div>
            
            <!-- Logout -->
            <form method="POST" action="<?php echo e(route('logout.custom')); ?>" class="w-full">
                <?php echo csrf_field(); ?>
                <button type="submit" 
                        class="w-full flex items-center px-4 py-3 rounded-lg text-red-200 hover:bg-red-600/20 transition-all duration-200">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    <span class="font-medium">Sair</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<style>
.sidebar-link {
    transition: all 0.3s ease;
    position: relative;
}

.sidebar-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateX(5px);
}

.sidebar-link:hover i {
    transform: scale(1.1);
}

.sidebar-link.active {
    background-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* Efeito de brilho ao passar o mouse (igual ao admin) */
.sidebar-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transition: left 0.5s ease;
}

.sidebar-link:hover::before {
    left: 100%;
}

@media (max-width: 768px) {
    aside {
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
    }
    
    aside.mobile-open {
        transform: translateX(0);
    }
}
</style>
<?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/components/licenciado-sidebar.blade.php ENDPATH**/ ?>