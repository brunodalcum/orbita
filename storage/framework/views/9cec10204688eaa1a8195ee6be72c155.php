<?php $__env->startSection('title', 'Estabelecimentos'); ?>
<?php $__env->startSection('subtitle', 'Gerencie seus estabelecimentos'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Meus Estabelecimentos</h2>
            <p class="text-gray-600">Gerencie todos os seus pontos de venda</p>
        </div>
        <a href="<?php echo e(route('licenciado.estabelecimentos.create')); ?>" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Novo Estabelecimento
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full mr-4">
                    <i class="fas fa-store text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo e(isset($estabelecimentosExemplo) ? $estabelecimentosExemplo->count() : $estabelecimentos->count()); ?></p>
                </div>
            </div>
        </div>
        
        <div class="card p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full mr-4">
                    <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Ativos</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo e(isset($estabelecimentosExemplo) ? $estabelecimentosExemplo->where('status', 'ativo')->count() : $estabelecimentos->where('status', 'ativo')->count()); ?></p>
                </div>
            </div>
        </div>
        
        <div class="card p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full mr-4">
                    <i class="fas fa-shopping-cart text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Vendas do Mês</p>
                    <p class="text-2xl font-bold text-gray-800">R$ <?php echo e(number_format($estabelecimentos->sum('vendas_mes'), 2, ',', '.')); ?></p>
                </div>
            </div>
        </div>
        
        <div class="card p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full mr-4">
                    <i class="fas fa-credit-card text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Transações</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo e($estabelecimentos->sum('transacoes_mes')); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Establishments Table -->
    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h3 class="text-lg font-semibold text-gray-800">Lista de Estabelecimentos</h3>
                <div class="flex space-x-2">
                    <input type="text" placeholder="Buscar estabelecimento..." 
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Estabelecimento</th>
                        <th>CNPJ</th>
                        <th>Status</th>
                        <th>Vendas do Mês</th>
                        <th>Transações</th>
                        <th>Data de Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $estabelecimentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estabelecimento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-store text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800"><?php echo e($estabelecimento->nome); ?></p>
                                    <p class="text-sm text-gray-500">ID: <?php echo e($estabelecimento->id); ?></p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="font-mono text-sm"><?php echo e($estabelecimento->cnpj); ?></span>
                        </td>
                        <td>
                            <span class="status-badge <?php echo e($estabelecimento->status); ?>"><?php echo e(ucfirst($estabelecimento->status)); ?></span>
                        </td>
                        <td>
                            <div>
                                <p class="font-semibold text-green-600">R$ <?php echo e(number_format($estabelecimento->vendas_mes, 2, ',', '.')); ?></p>
                                <p class="text-xs text-gray-500">Este mês</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <p class="font-semibold"><?php echo e($estabelecimento->transacoes_mes); ?></p>
                                <p class="text-xs text-gray-500">transações</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <p class="text-sm"><?php echo e($estabelecimento->created_at->format('d/m/Y')); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($estabelecimento->created_at->diffForHumans()); ?></p>
                            </div>
                        </td>
                        <td>
                            <div class="flex space-x-2">
                                <button class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" 
                                        title="Ver detalhes">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors" 
                                        title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="p-2 text-orange-600 hover:bg-orange-100 rounded-lg transition-colors" 
                                        title="Relatórios">
                                    <i class="fas fa-chart-bar"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Performance Chart -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Performance por Estabelecimento</h3>
            <canvas id="performanceChart" width="400" height="200"></canvas>
        </div>
        
        <!-- Recent Transactions -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Transações Recentes</h3>
            <div class="space-y-3">
                <?php $__currentLoopData = $estabelecimentos->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estabelecimento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-store text-blue-600 text-xs"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800"><?php echo e($estabelecimento->nome); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($estabelecimento->transacoes_mes); ?> transações</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-green-600">R$ <?php echo e(number_format($estabelecimento->vendas_mes, 2, ',', '.')); ?></p>
                        <p class="text-xs text-gray-500">Este mês</p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Performance Chart
    const ctx = document.getElementById('performanceChart').getContext('2d');
    const performanceChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($estabelecimentos->pluck('nome'), 15, 512) ?>,
            datasets: [{
                label: 'Vendas (R$)',
                data: <?php echo json_encode($estabelecimentos->pluck('vendas_mes'), 15, 512) ?>,
                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.licenciado', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/licenciado/estabelecimentos.blade.php ENDPATH**/ ?>