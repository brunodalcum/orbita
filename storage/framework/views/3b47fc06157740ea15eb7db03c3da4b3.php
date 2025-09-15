<!-- Sidebar Dinâmico -->
<?php
    $user = Auth::user();
    $branding = $user ? $user->getBrandingWithInheritance() : [];
    $logoUrl = $branding['logo_small_url'] ?? $branding['logo_url'] ?? null;
    
    // Para Super Admin, usar logo da Órbita como fallback se não houver personalizada
    if($user && $user->isSuperAdminNode() && !$logoUrl) {
        $logoUrl = 'branding/orbita/orbita-logo-small.svg';
    }
    
    // Determinar tipo de nó para classes CSS específicas
    $nodeTypeClass = '';
    $badgeClass = '';
    if ($user) {
        if ($user->isSuperAdminNode()) {
            $nodeTypeClass = 'super-admin-sidebar';
            $badgeClass = 'super-admin-badge';
        } elseif ($user->node_type === 'operacao') {
            $nodeTypeClass = 'operacao-sidebar';
            $badgeClass = 'operacao-badge';
        } elseif ($user->node_type === 'white_label') {
            $nodeTypeClass = 'white-label-sidebar';
            $badgeClass = 'white-label-badge';
        }
    }
    
    // Usar cores CSS dinâmicas
    $textColor = 'white'; // Padrão para sidebar com gradiente
?>

<div class="w-64 flex-shrink-0 relative sidebar-gradient <?php echo e($nodeTypeClass); ?>">
    <div class="p-6">
        <!-- Logo -->
        <div class="flex items-center justify-center mb-8 w-full">
            <div class="logo-container sidebar-logo-container">
                <?php if($logoUrl): ?>
                    <img src="<?php echo e(asset('storage/' . $logoUrl)); ?>?v=<?php echo e(time()); ?>" 
                         alt="Logo" 
                         class="sidebar-logo"
                         onerror="this.onerror=null;this.src='<?php echo e(asset('storage/branding/orbita/orbita-logo-small.svg')); ?>';">
                <?php else: ?>
                    <img src="<?php echo e(asset('images/dspay-logo.png')); ?>" 
                         alt="dspay" 
                         class="sidebar-logo"
                         onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiByeD0iOCIgZmlsbD0iIzMzNjZFRiIvPgo8dGV4dCB4PSIyMCIgeT0iMjYiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZm9udC13ZWlnaHQ9ImJvbGQiIGZpbGw9IndoaXRlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5EU1BBWTwvdGV4dD4KPC9zdmc+';">
                <?php endif; ?>
            </div>
        </div>

        <!-- Badge do Tipo de Nó -->
        <?php if($user): ?>
            <div class="flex justify-center mb-6">
                <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo e($badgeClass); ?>">
                    <?php if($user->isSuperAdminNode()): ?>
                        Super Admin
                    <?php elseif($user->node_type === 'operacao'): ?>
                        Operação
                    <?php elseif($user->node_type === 'white_label'): ?>
                        White Label
                    <?php else: ?>
                        <?php echo e(ucfirst($user->node_type)); ?>

                    <?php endif; ?>
                </span>
            </div>
        <?php endif; ?>

        <!-- Menu Dinâmico -->
        <nav class="space-y-2">
            <?php $__currentLoopData = $menuItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="menu-item">
                    <!-- Item Principal -->
                    <a href="<?php echo e(route($item['route'])); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 sidebar-menu-item <?php echo e($item['is_active'] ? 'sidebar-menu-active' : 'sidebar-menu-hover'); ?>"
                       style="color: <?php echo e($textColor); ?>;">
                        <i class="<?php echo e($item['icon']); ?> mr-3"></i>
                        <span class="font-medium"><?php echo e($item['name']); ?></span>
                        <?php if(count($item['submenu']) > 0): ?>
                            <i class="fas fa-chevron-down ml-auto text-sm transition-transform duration-200 chevron-icon" 
                               id="chevron-<?php echo e($loop->index); ?>" 
                               style="color: <?php echo e($textColor); ?>; opacity: 0.8;" 
                               title="Submenu (<?php echo e(count($item['submenu'])); ?> items)"></i>
                        <?php endif; ?>
                    </a>

                    <!-- Submenu -->
                    <?php if(count($item['submenu']) > 0): ?>
                        <div class="submenu ml-4 mt-2 space-y-1 <?php echo e($item['is_active'] ? '' : 'hidden'); ?>" id="submenu-<?php echo e($loop->index); ?>">
                            <?php $__currentLoopData = $item['submenu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e($subItem['route'] === 'dashboard.agenda' && isset($subItem['action']) && $subItem['action'] === 'create' ? route('dashboard.agenda.create') : route($subItem['route']) . (isset($subItem['action']) ? '#' . $subItem['action'] : '')); ?>" 
                                   class="flex items-center px-3 py-2 rounded-lg text-sm sidebar-submenu-item transition-all duration-200"
                                   style="color: <?php echo e($textColor); ?>; opacity: 0.8;">
                                    <i class="fas fa-circle mr-2 text-xs"></i>
                                    <?php echo e($subItem['name']); ?>

                                    <?php if(isset($subItem['action'])): ?>
                                        <span class="ml-auto text-xs bg-white/20 px-2 py-1 rounded"><?php echo e(ucfirst($subItem['action'])); ?></span>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>

        <!-- Informações do Usuário -->
        <?php if($user): ?>
            <div class="mt-8 pt-6 border-t border-white/20">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">
                            <?php echo e($user->name); ?>

                        </p>
                        <p class="text-xs text-white/70 truncate">
                            <?php echo e($user->email); ?>

                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Estilos específicos da sidebar */
.sidebar-gradient {
    background: var(--primary-gradient);
}

.sidebar-logo-container {
    min-height: 40px;
    max-height: 50px;
    width: 100%;
    max-width: 180px;
    display: flex; 
    align-items: center; 
    justify-content: center;
    padding: 4px;
}

.sidebar-logo {
    max-width: 100%; 
    max-height: 100%; 
    width: auto; 
    height: auto; 
    object-fit: contain;
    display: block;
}

.sidebar-menu-item {
    transition: all 0.3s ease;
}

.sidebar-menu-hover:hover {
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateX(4px);
}

.sidebar-menu-active {
    background-color: rgba(255, 255, 255, 0.2);
    border-left: 4px solid white;
    transform: translateX(4px);
}

.sidebar-submenu-item:hover {
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateX(2px);
}

/* Badges específicos por tipo de nó */
.super-admin-badge {
    background: linear-gradient(135deg, #6B46C1 0%, #9333EA 100%);
    color: white;
    box-shadow: 0 2px 4px rgba(107, 70, 193, 0.3);
}

.operacao-badge {
    background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%);
    color: white;
    box-shadow: 0 2px 4px rgba(220, 38, 38, 0.3);
}

.white-label-badge {
    background: linear-gradient(135deg, #059669 0%, #10B981 100%);
    color: white;
    box-shadow: 0 2px 4px rgba(5, 150, 105, 0.3);
}

/* Animações */
.chevron-icon {
    transition: transform 0.3s ease;
}

.menu-item:hover .chevron-icon {
    transform: rotate(180deg);
}

/* Responsividade */
@media (max-width: 768px) {
    .sidebar-logo-container {
        max-width: 120px;
        max-height: 35px;
    }
}
</style>

<script>
// JavaScript para controle do menu
document.addEventListener('DOMContentLoaded', function() {
    // Toggle submenu
    document.querySelectorAll('.menu-item > a').forEach(function(menuItem) {
        menuItem.addEventListener('click', function(e) {
            const submenu = this.parentElement.querySelector('.submenu');
            const chevron = this.querySelector('.chevron-icon');
            
            if (submenu) {
                e.preventDefault();
                submenu.classList.toggle('hidden');
                if (chevron) {
                    chevron.style.transform = submenu.classList.contains('hidden') ? 
                        'rotate(0deg)' : 'rotate(180deg)';
                }
            }
        });
    });
});
</script><?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/components/dynamic-sidebar.blade.php ENDPATH**/ ?>