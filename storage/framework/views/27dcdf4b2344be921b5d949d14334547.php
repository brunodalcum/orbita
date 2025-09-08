<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Dashboard - dspay'); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/dspay-logo.png')); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('images/dspay-logo.png')); ?>">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- TailwindCSS com fallback -->
    <script src="https://cdn.tailwindcss.com" 
            onerror="this.onerror=null;this.href='<?php echo e(asset('js/tailwind-fallback.js')); ?>';"></script>
    
    <!-- FontAwesome com fallback -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          onerror="this.onerror=null;this.href='<?php echo e(asset('css/fontawesome-fallback.css')); ?>';">
    
    <!-- Assets do Vite com fallback -->
    <?php if(file_exists(public_path('build/manifest.json'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php else: ?>
        <!-- Fallback para produção quando Vite não está disponível -->
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
            body { font-family: 'Inter', sans-serif; }
            .card { 
                background: white; 
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); 
                transition: all 0.3s ease; 
            }
            .card:hover { 
                transform: translateY(-2px); 
                box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); 
            }
        </style>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <?php endif; ?>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Fallback CSS caso TailwindCSS não carregue */
        .fallback-sidebar {
            width: 16rem;
            background: linear-gradient(to bottom, #3B82F6, #8B5CF6);
            flex-shrink: 0;
            position: relative;
        }
        
        .fallback-menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: white;
            border-radius: 0.5rem;
            transition: all 0.2s;
            text-decoration: none;
        }
        
        .fallback-menu-item:hover {
            background-color: rgba(255,255,255,0.1);
        }
        
        .fallback-menu-item.active {
            background-color: rgba(255,255,255,0.2);
            border-left: 4px solid white;
        }
        
        .fallback-logo {
            height: 2.5rem;
            width: auto;
            margin: 0 auto;
        }
        
        /* Garantir que o sidebar seja visível */
        .sidebar-container {
            display: flex !important;
            height: 100vh;
        }
        
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="sidebar-container">
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
        <div class="main-content">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
                        <p class="text-gray-600"><?php echo $__env->yieldContent('page-description', 'Bem-vindo ao painel de controle'); ?></p>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700"><?php echo e(Auth::user()->name ?? 'Usuário'); ?></span>
                        </div>
                        
                        <!-- Logout -->
                        <form method="POST" action="<?php echo e(route('logout.custom')); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                                <i class="fas fa-sign-out-alt mr-1"></i>
                                Sair
                            </button>
                        </form>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-auto p-6">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script>
        // Verificar se o sidebar está visível
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.w-64');
            if (!sidebar) {
                console.error('Sidebar não encontrado!');
                // Tentar recarregar a página após 2 segundos
                setTimeout(() => {
                    if (confirm('Problema detectado com o sidebar. Deseja recarregar a página?')) {
                        window.location.reload();
                    }
                }, 2000);
            } else {
                console.log('Sidebar carregado com sucesso!');
            }
        });
        
        // Fallback para FontAwesome
        if (typeof window.FontAwesome === 'undefined') {
            console.warn('FontAwesome não carregado, usando fallbacks');
            // Adicionar fallbacks para ícones
            document.querySelectorAll('.fas, .far, .fab').forEach(function(icon) {
                const iconName = icon.className.match(/fa-([a-z-]+)/);
                if (iconName) {
                    const fallbackMap = {
                        'tachometer-alt': '📊',
                        'users': '👥',
                        'cogs': '⚙️',
                        'chart-line': '📈',
                        'calendar-alt': '📅',
                        'bullhorn': '📢',
                        'users-cog': '👤',
                        'chevron-down': '▼',
                        'user': '👤',
                        'circle': '●',
                        'sign-out-alt': '🚪'
                    };
                    if (fallbackMap[iconName[1]]) {
                        icon.textContent = fallbackMap[iconName[1]];
                        icon.style.fontFamily = 'Arial, sans-serif';
                    }
                }
            });
        }
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/layouts/production.blade.php ENDPATH**/ ?>