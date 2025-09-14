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
        

        <!-- Menu Agenda com Submenu -->
        <div class="relative" x-data="{ open: <?php echo e(request()->routeIs('licenciado.agenda*') ? 'true' : 'false'); ?> }">
            <button @click="open = !open" 
                    class="sidebar-link w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('licenciado.agenda*') ? 'bg-white/20 shadow-md' : 'hover:bg-white/10'); ?>">
                <div class="flex items-center">
                    <i class="fas fa-calendar-alt mr-3 text-lg"></i>
                    <span class="font-medium">Agenda</span>
                </div>
                <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="mt-2 ml-4 space-y-1">
                
                <a href="<?php echo e(route('licenciado.agenda')); ?>" 
                   class="flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-200 <?php echo e($currentRoute === 'licenciado.agenda' ? 'bg-white/30 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white'); ?>">
                    <i class="fas fa-list mr-2"></i>
                    Lista de Compromissos
                </a>
                
                <a href="<?php echo e(route('licenciado.agenda.calendar')); ?>" 
                   class="flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-200 <?php echo e($currentRoute === 'licenciado.agenda.calendar' ? 'bg-white/30 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white'); ?>">
                    <i class="fas fa-calendar mr-2"></i>
                    Calendário
                </a>
                
                <a href="<?php echo e(route('licenciado.agenda.create')); ?>" 
                   class="flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-200 <?php echo e($currentRoute === 'licenciado.agenda.create' ? 'bg-white/30 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white'); ?>">
                    <i class="fas fa-plus mr-2"></i>
                    Nova Reunião
                </a>
                
                <a href="<?php echo e(route('licenciado.agenda.pendentes')); ?>" 
                   class="flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-200 <?php echo e($currentRoute === 'licenciado.agenda.pendentes' ? 'bg-white/30 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white'); ?>">
                    <i class="fas fa-clock mr-2"></i>
                    Pendentes de Aprovação
                    <?php
                        $pendentesCount = 0;
                        if (Auth::check()) {
                            $pendentesCount = \App\Models\Agenda::pendentesAprovacao(Auth::id())->count();
                        }
                    ?>
                    <?php if($pendentesCount > 0): ?>
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full"><?php echo e($pendentesCount); ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
        
        <!-- Menu Leads com Submenu -->
        <div class="relative" x-data="{ open: <?php echo e(request()->routeIs('licenciado.leads*') ? 'true' : 'false'); ?> }">
            <button @click="open = !open" 
                    class="sidebar-link w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('licenciado.leads*') ? 'bg-white/20 shadow-md' : 'hover:bg-white/10'); ?>">
                <div class="flex items-center">
                    <i class="fas fa-users mr-3 text-lg"></i>
                    <span class="font-medium">Leads</span>
                    <?php
                        $leadsCount = 0;
                        if (Auth::check()) {
                            $leadsCount = \App\Models\Lead::doLicenciado(Auth::id())->count();
                        }
                    ?>
                    <?php if($leadsCount > 0): ?>
                        <span class="ml-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full"><?php echo e($leadsCount); ?></span>
                    <?php endif; ?>
                </div>
                <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="mt-2 ml-4 space-y-1">
                
                <a href="<?php echo e(route('licenciado.leads')); ?>" 
                   class="flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('licenciado.leads') && !request()->routeIs('licenciado.leads.extract') ? 'bg-white/30 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white'); ?>">
                    <i class="fas fa-list mr-2"></i>
                    Lista de Leads
                </a>
                
                <a href="<?php echo e(route('licenciado.leads.extract')); ?>" 
                   class="flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('licenciado.leads.extract') ? 'bg-white/30 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white'); ?>">
                    <i class="fas fa-download mr-2"></i>
                    Extrair Leads
                </a>
            </div>
        </div>
        
        <!-- Menu Planos com Submenu -->
        <div class="relative" x-data="{ open: <?php echo e(request()->routeIs('tax-simulator.*') || request()->routeIs('licenciado.planos') ? 'true' : 'false'); ?> }">
            <button @click="open = !open" 
                    class="sidebar-link w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('tax-simulator.*') || request()->routeIs('licenciado.planos') ? 'bg-white/20 shadow-md' : 'hover:bg-white/10'); ?>">
                <div class="flex items-center">
                    <i class="fas fa-chart-line mr-3 text-lg"></i>
                    <span class="font-medium">Planos</span>
                </div>
                <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="mt-2 ml-4 space-y-1">
                
                <a href="<?php echo e(route('tax-simulator.index')); ?>" 
                   class="flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('tax-simulator.*') ? 'bg-white/30 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white'); ?>">
                    <i class="fas fa-calculator mr-2"></i>
                    Simulador de Taxas
                </a>
                
                <a href="<?php echo e(route('licenciado.planos')); ?>" 
                   class="flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('licenciado.planos') ? 'bg-white/30 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white'); ?>">
                    <i class="fas fa-list mr-2"></i>
                    Lista de Planos
                </a>
            </div>
        </div>
        
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