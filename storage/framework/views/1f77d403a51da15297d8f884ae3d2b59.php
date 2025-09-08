<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>
        <link rel="icon" type="image/png" href="<?php echo e(asset('images/dspay-logo.png')); ?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('images/dspay-logo.png')); ?>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <!-- Scripts com fallback -->
        <?php if(file_exists(public_path('build/manifest.json'))): ?>
            <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        <?php else: ?>
            <!-- Fallback CDN para produção -->
            <script src="https://cdn.tailwindcss.com"></script>
            <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
            <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
            <style>
                body { font-family: 'Figtree', sans-serif; }
            </style>
        <?php endif; ?>

        <!-- Styles -->
        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    </head>
    <body class="bg-gray-50">
        <?php if (isset($component)) { $__componentOriginalff9615640ecc9fe720b9f7641382872b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalff9615640ecc9fe720b9f7641382872b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.banner','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('banner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalff9615640ecc9fe720b9f7641382872b)): ?>
<?php $attributes = $__attributesOriginalff9615640ecc9fe720b9f7641382872b; ?>
<?php unset($__attributesOriginalff9615640ecc9fe720b9f7641382872b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalff9615640ecc9fe720b9f7641382872b)): ?>
<?php $component = $__componentOriginalff9615640ecc9fe720b9f7641382872b; ?>
<?php unset($__componentOriginalff9615640ecc9fe720b9f7641382872b); ?>
<?php endif; ?>
        
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
            <div class="flex-1 flex flex-col overflow-hidden ml-64">
                <!-- Header -->
                <header class="bg-white shadow-sm border-b">
                    <div class="flex items-center justify-between px-6 py-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></h1>
                            <p class="text-gray-600"><?php echo $__env->yieldContent('description', 'Painel de controle'); ?></p>
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
                            <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
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

        <?php echo $__env->yieldPushContent('modals'); ?>
        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    </body>
</html>





<?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/layouts/dashboard.blade.php ENDPATH**/ ?>