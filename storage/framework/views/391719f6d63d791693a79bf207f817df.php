<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('subtitle', 'Visão geral das suas atividades'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Welcome Message -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Bem-vindo, <?php echo e($licenciado->name); ?>! 👋</h2>
                <p class="text-blue-100">Aqui está um resumo das suas atividades recentes</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-chart-line text-6xl text-blue-200"></i>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Estabelecimentos Ativos -->
        <div class="stat-card green">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Estabelecimentos</p>
                    <p class="text-3xl font-bold"><?php echo e($stats->estabelecimentos_ativos); ?></p>
                    <p class="text-green-200 text-xs mt-1">Ativos</p>
                </div>
                <div class="text-4xl text-green-200">
                    <i class="fas fa-store"></i>
                </div>
            </div>
        </div>

        <!-- Vendas do Mês -->
        <div class="stat-card blue">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Vendas do Mês</p>
                    <p class="text-3xl font-bold">R$ <?php echo e(number_format($stats->vendas_mes, 2, ',', '.')); ?></p>
                    <p class="text-blue-200 text-xs mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>+<?php echo e($stats->crescimento_vendas); ?>%
                    </p>
                </div>
                <div class="text-4xl text-blue-200">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>

        <!-- Transações -->
        <div class="stat-card orange">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-pink-100 text-sm font-medium">Transações</p>
                    <p class="text-3xl font-bold"><?php echo e($stats->transacoes_mes); ?></p>
                    <p class="text-pink-200 text-xs mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>+<?php echo e($stats->crescimento_transacoes); ?>%
                    </p>
                </div>
                <div class="text-4xl text-pink-200">
                    <i class="fas fa-credit-card"></i>
                </div>
            </div>
        </div>

        <!-- Comissões -->
        <div class="stat-card purple">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-cyan-100 text-sm font-medium">Comissões</p>
                    <p class="text-3xl font-bold">R$ <?php echo e(number_format($stats->comissao_mes, 2, ',', '.')); ?></p>
                    <p class="text-cyan-200 text-xs mt-1">Este mês</p>
                </div>
                <div class="text-4xl text-cyan-200">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activities -->
        <div class="lg:col-span-2">
            <div class="card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Atividades Recentes</h3>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Ver todas <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="activity-item <?php echo e($activity->color); ?>">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-1">
                                    <i class="<?php echo e($activity->icon); ?> text-gray-600 mr-2"></i>
                                    <p class="text-gray-800 font-medium"><?php echo e($activity->descricao); ?></p>
                                </div>
                                <p class="text-gray-500 text-sm"><?php echo e($activity->data->diffForHumans()); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-6">
            <!-- Quick Actions Card -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Ações Rápidas</h3>
                <div class="space-y-3">
                    <a href="<?php echo e(route('licenciado.estabelecimentos')); ?>" 
                       class="w-full flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                        <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                        <span class="text-blue-800 font-medium">Novo Estabelecimento</span>
                    </a>
                    <a href="<?php echo e(route('licenciado.vendas')); ?>" 
                       class="w-full flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <i class="fas fa-chart-line text-green-600 mr-3"></i>
                        <span class="text-green-800 font-medium">Ver Vendas</span>
                    </a>
                    <a href="<?php echo e(route('licenciado.relatorios')); ?>" 
                       class="w-full flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                        <i class="fas fa-file-download text-purple-600 mr-3"></i>
                        <span class="text-purple-800 font-medium">Baixar Relatório</span>
                    </a>
                    <a href="<?php echo e(route('licenciado.suporte')); ?>" 
                       class="w-full flex items-center p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
                        <i class="fas fa-headset text-orange-600 mr-3"></i>
                        <span class="text-orange-800 font-medium">Suporte</span>
                    </a>
                </div>
            </div>

            <!-- Performance Card -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Performance</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Meta Mensal</span>
                            <span class="font-medium">78%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full" style="width: 78%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">R$ 23.500 de R$ 30.000</p>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Transações</span>
                            <span class="font-medium">85%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">235 de 275 esperadas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Plans -->
    <div class="card p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-800">Planos Disponíveis</h3>
            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Ver todos <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
            <?php $__currentLoopData = $availablePlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <h4 class="font-semibold text-gray-800 mb-2"><?php echo e($plan->nome); ?></h4>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Débito:</span>
                        <span class="font-medium text-green-600"><?php echo e($plan->comissao_media); ?>%</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Status:</span>
                        <span class="status-badge <?php echo e($plan->ativo ? 'ativo' : 'inativo'); ?>">
                            <?php echo e($plan->ativo ? 'Ativo' : 'Inativo'); ?>

                        </span>
                    </div>
                </div>
                <button class="w-full mt-3 bg-blue-600 text-white py-2 px-4 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors">
                    Ver Detalhes
                </button>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add any dashboard-specific JavaScript here
    console.log('Dashboard do Licenciado carregado com sucesso!');
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.licenciado', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/licenciado/dashboard.blade.php ENDPATH**/ ?>