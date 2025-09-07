<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contratos - dspay</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/dspay-logo.png')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
        .stat-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .progress-bar { 
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%); 
            transition: width 0.5s ease-in-out;
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
                        <h1 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-file-contract text-blue-600 mr-3"></i>
                            Contratos de Licenciados
                        </h1>
                        <p class="text-gray-600 mt-1">Gerencie todo o fluxo de contratos e documentação</p>
                    </div>
                    <a href="<?php echo e(route('contracts.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Novo Contrato
                    </a>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                    <div class="stat-card rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Pendentes</p>
                                <p class="text-2xl font-bold"><?php echo e($statusStats['documentos_pendentes']); ?></p>
                            </div>
                            <i class="fas fa-clock text-white/60 text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Em Análise</p>
                                <p class="text-2xl font-bold"><?php echo e($statusStats['documentos_em_analise']); ?></p>
                            </div>
                            <i class="fas fa-search text-white/60 text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Aprovados</p>
                                <p class="text-2xl font-bold"><?php echo e($statusStats['documentos_aprovados']); ?></p>
                            </div>
                            <i class="fas fa-check-circle text-white/60 text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Enviados</p>
                                <p class="text-2xl font-bold"><?php echo e($statusStats['contrato_enviado']); ?></p>
                            </div>
                            <i class="fas fa-envelope text-white/60 text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Assinados</p>
                                <p class="text-2xl font-bold"><?php echo e($statusStats['contrato_assinado']); ?></p>
                            </div>
                            <i class="fas fa-signature text-white/60 text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-600 to-green-700 rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Liberados</p>
                                <p class="text-2xl font-bold"><?php echo e($statusStats['licenciado_liberado']); ?></p>
                            </div>
                            <i class="fas fa-unlock text-white/60 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card rounded-xl p-6 mb-6">
                    <form method="GET" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-64">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pesquisar</label>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                                   placeholder="Nome ou email do licenciado..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="min-w-48">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Todos os status</option>
                                <option value="documentos_pendentes" <?php echo e(request('status') === 'documentos_pendentes' ? 'selected' : ''); ?>>Documentos Pendentes</option>
                                <option value="documentos_enviados" <?php echo e(request('status') === 'documentos_enviados' ? 'selected' : ''); ?>>Documentos Enviados</option>
                                <option value="documentos_em_analise" <?php echo e(request('status') === 'documentos_em_analise' ? 'selected' : ''); ?>>Em Análise</option>
                                <option value="documentos_aprovados" <?php echo e(request('status') === 'documentos_aprovados' ? 'selected' : ''); ?>>Documentos Aprovados</option>
                                <option value="contrato_enviado" <?php echo e(request('status') === 'contrato_enviado' ? 'selected' : ''); ?>>Contrato Enviado</option>
                                <option value="contrato_assinado" <?php echo e(request('status') === 'contrato_assinado' ? 'selected' : ''); ?>>Contrato Assinado</option>
                                <option value="licenciado_liberado" <?php echo e(request('status') === 'licenciado_liberado' ? 'selected' : ''); ?>>Licenciado Liberado</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                                <i class="fas fa-search mr-2"></i>Filtrar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Contracts List -->
                <div class="card rounded-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Licenciado</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progresso</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criado em</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fas fa-user text-white text-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900"><?php echo e($contract->licenciado->name); ?></div>
                                                    <div class="text-sm text-gray-500"><?php echo e($contract->licenciado->email); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($contract->status_color); ?>">
                                                <?php echo e($contract->status_label); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                                                    <div class="progress-bar h-2 rounded-full" style="width: <?php echo e($contract->progress_percentage); ?>%"></div>
                                                </div>
                                                <span class="text-sm text-gray-600"><?php echo e($contract->progress_percentage); ?>%</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <?php echo e($contract->created_at->format('d/m/Y H:i')); ?>

                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <a href="<?php echo e(route('contracts.show', $contract)); ?>" 
                                                   class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-lg text-sm transition-colors">
                                                    <i class="fas fa-eye mr-1"></i>Ver
                                                </a>
                                                <?php if($contract->canApproveDocuments()): ?>
                                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-lg text-sm">
                                                        <i class="fas fa-clock mr-1"></i>Aguardando
                                                    </span>
                                                <?php elseif($contract->canSendContract()): ?>
                                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-sm">
                                                        <i class="fas fa-check mr-1"></i>Pronto
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-file-contract text-gray-300 text-6xl mb-4"></i>
                                                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum contrato encontrado</h3>
                                                <p class="text-gray-500 mb-4">Comece criando um novo contrato para um licenciado.</p>
                                                <a href="<?php echo e(route('contracts.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                                    <i class="fas fa-plus mr-2"></i>Criar Primeiro Contrato
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($contracts->hasPages()): ?>
                        <div class="px-6 py-4 border-t border-gray-200">
                            <?php echo e($contracts->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
<?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/dashboard/contracts/index.blade.php ENDPATH**/ ?>