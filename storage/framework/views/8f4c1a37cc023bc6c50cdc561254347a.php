<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - dspay</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/dspay-logo.png')); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('images/dspay-logo.png')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .card {
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .stat-card-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .stat-card-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .stat-card-warning {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
    </style>
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
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                        <p class="text-gray-600">Bem-vindo ao painel de controle</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-bell"></i>
                        </button>
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-cog"></i>
                        </button>
                        <form method="POST" action="<?php echo e(route('logout.custom')); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="p-2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card card rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Total de Licenciados</p>
                                <p class="text-3xl font-bold"><?php echo e($stats['total']); ?></p>
                                <p class="text-white/80 text-sm mt-1">
                                    <i class="fas fa-users mr-1"></i>
                                    Cadastros ativos
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card-secondary card rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Aprovados</p>
                                <p class="text-3xl font-bold"><?php echo e($stats['aprovados']); ?></p>
                                <p class="text-white/80 text-sm mt-1">
                                    <i class="fas fa-check mr-1"></i>
                                    Licenciados ativos
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card-success card rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Em Análise</p>
                                <p class="text-3xl font-bold"><?php echo e($stats['em_analise']); ?></p>
                                <p class="text-white/80 text-sm mt-1">
                                    <i class="fas fa-clock mr-1"></i>
                                    Aguardando aprovação
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card-warning card rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Recusados</p>
                                <p class="text-3xl font-bold"><?php echo e($stats['recusados']); ?></p>
                                <p class="text-white/80 text-sm mt-1">
                                    <i class="fas fa-times mr-1"></i>
                                    Cadastros recusados
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-times-circle text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Recent Activity & Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Recent Licensees -->
                    <div class="card rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Licenciados Recentes</h3>
                        <div class="space-y-4">
                            <?php $__empty_1 = true; $__currentLoopData = $licenciadosRecentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $licenciado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-id-card text-blue-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-medium text-gray-800"><?php echo e($licenciado->razao_social); ?></p>
                                            <p class="text-sm text-gray-600"><?php echo e($licenciado->cnpj_cpf); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <?php if($licenciado->status === 'aprovado'): ?>
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Aprovado</span>
                                        <?php elseif($licenciado->status === 'em_analise'): ?>
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Em Análise</span>
                                        <?php elseif($licenciado->status === 'recusado'): ?>
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Recusado</span>
                                        <?php elseif($licenciado->status === 'em_risco'): ?>
                                            <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded-full">Em Risco</span>
                                        <?php endif; ?>
                                        <p class="text-sm text-gray-600 mt-1"><?php echo e($licenciado->created_at->diffForHumans()); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-8">
                                    <i class="fas fa-inbox text-gray-400 text-4xl mb-2"></i>
                                    <p class="text-gray-500">Nenhum licenciado cadastrado ainda</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Ações Rápidas</h3>
                        <div class="space-y-3">
                            <a href="<?php echo e(route('dashboard.licenciados')); ?>" class="w-full flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                <i class="fas fa-user-plus text-blue-600 mr-3"></i>
                                <span class="text-blue-800 font-medium">Novo Licenciado</span>
                            </a>
                            <a href="<?php echo e(route('dashboard.operacoes')); ?>" class="w-full flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                                <i class="fas fa-cogs text-green-600 mr-3"></i>
                                <span class="text-green-800 font-medium">Gerenciar Operações</span>
                            </a>
                            <a href="<?php echo e(route('dashboard.configuracoes')); ?>" class="w-full flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                                <i class="fas fa-cog text-purple-600 mr-3"></i>
                                <span class="text-purple-800 font-medium">Configurações</span>
                            </a>
                            <button class="w-full flex items-center p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
                                <i class="fas fa-chart-bar text-orange-600 mr-3"></i>
                                <span class="text-orange-800 font-medium">Relatórios</span>
                            </button>
                        </div>
                    </div>

                    <!-- Últimos Leads Cadastrados -->
                    <div class="card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Últimos Leads Cadastrados</h3>
                            <a href="<?php echo e(route('dashboard.leads')); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Ver todos
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        
                        <?php if($leadsRecentes->count() > 0): ?>
                            <div class="space-y-3">
                                <?php $__currentLoopData = $leadsRecentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-user text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 text-sm"><?php echo e($lead->nome); ?></p>
                                                <p class="text-gray-500 text-xs">
                                                    <?php echo e($lead->email); ?> • <?php echo e($lead->telefone); ?>

                                                </p>
                                                <?php if($lead->empresa): ?>
                                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mt-1">
                                                        <?php echo e($lead->empresa); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="flex items-center mb-1">
                                                <?php if($lead->status === 'novo'): ?>
                                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                    <span class="text-green-600 text-xs font-medium">Novo</span>
                                                <?php elseif($lead->status === 'em_contato'): ?>
                                                    <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                                    <span class="text-yellow-600 text-xs font-medium">Em Contato</span>
                                                <?php elseif($lead->status === 'convertido'): ?>
                                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                                    <span class="text-blue-600 text-xs font-medium">Convertido</span>
                                                <?php else: ?>
                                                    <div class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>
                                                    <span class="text-gray-600 text-xs font-medium"><?php echo e(ucfirst($lead->status)); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <p class="text-gray-400 text-xs">
                                                <?php echo e($lead->created_at->diffForHumans()); ?>

                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <i class="fas fa-users text-gray-300 text-4xl mb-3"></i>
                                <p class="text-gray-500">Nenhum lead cadastrado ainda</p>
                                <a href="<?php echo e(route('dashboard.leads')); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2 inline-block">
                                    Gerenciar leads
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>


</body>
</html>
<?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/dashboard.blade.php ENDPATH**/ ?>