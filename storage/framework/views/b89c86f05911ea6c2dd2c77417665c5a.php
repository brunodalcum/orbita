<?php $__env->startSection('title', 'Agenda'); ?>
<?php $__env->startSection('subtitle', 'Gerencie seus compromissos'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header com Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-calendar-alt text-purple-600 mr-3"></i>
                    Minha Agenda
                </h2>
                <p class="text-gray-600">Visualize e gerencie seus compromissos</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Filtro de Data -->
                <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200">
                    <input type="date" 
                           id="dateFilter"
                           value="<?php echo e(request('data') ? request('data') : ''); ?>" 
                           onchange="changeDate(this.value)"
                           class="px-4 py-2 border-0 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <?php if(request('data')): ?>
                        <button onclick="clearDateFilter()" 
                                class="px-3 py-2 text-gray-500 hover:text-red-600 transition-colors"
                                title="Limpar filtro">
                            <i class="fas fa-times"></i>
                        </button>
                    <?php endif; ?>
                </div>
                
                <!-- Botão Nova Reunião -->
                <a href="<?php echo e(route('licenciado.agenda.create')); ?>" 
                   class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Nova Reunião
                </a>
            </div>
        </div>
        
        <!-- Informações do Filtro -->
        <div class="flex flex-wrap gap-3 mt-4">
            <?php if(request('data')): ?>
                <div class="text-sm text-gray-600 bg-purple-50 px-3 py-2 rounded-lg border border-purple-200">
                    <i class="fas fa-filter mr-2 text-purple-600"></i>
                    Filtrado por: <strong><?php echo e(\Carbon\Carbon::parse(request('data'))->format('d/m/Y')); ?></strong>
                </div>
            <?php endif; ?>
            <div class="text-sm text-gray-600">
                <i class="fas fa-calendar-check mr-2 text-purple-600"></i>
                <?php if(request('data')): ?>
                    <strong><?php echo e($agendas->count()); ?></strong> reunião(ões) encontrada(s) para <strong><?php echo e(\Carbon\Carbon::parse(request('data'))->format('d/m/Y')); ?></strong>
                <?php else: ?>
                    <strong><?php echo e($agendas->count()); ?></strong> reunião(ões) no total
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    <?php if(session('success')): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <?php echo e(session('success')); ?>

            </div>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?php echo e(session('error')); ?>

            </div>
        </div>
    <?php endif; ?>

    <!-- Lista de Agendas -->
    <div class="grid gap-4">
        <?php $__empty_1 = true; $__currentLoopData = $agendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agenda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Título e Status -->
                            <div class="flex items-center gap-3 mb-3">
                                <h3 class="text-lg font-semibold text-gray-900"><?php echo e($agenda->titulo); ?></h3>
                                
                                <!-- Badge de Status -->
                                <?php if($agenda->status === 'agendada'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Agendada
                                    </span>
                                <?php elseif($agenda->status === 'pendente'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Pendente
                                    </span>
                                <?php elseif($agenda->status === 'em_andamento'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-play-circle mr-1"></i>
                                        Em Andamento
                                    </span>
                                <?php elseif($agenda->status === 'concluida'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-check mr-1"></i>
                                        Concluída
                                    </span>
                                <?php elseif($agenda->status === 'cancelada'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Cancelada
                                    </span>
                                <?php endif; ?>

                                <!-- Badge de Aprovação -->
                                <?php if($agenda->status_aprovacao === 'pendente'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <i class="fas fa-hourglass-half mr-1"></i>
                                        Aguardando Aprovação
                                    </span>
                                <?php elseif($agenda->status_aprovacao === 'aprovada'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-thumbs-up mr-1"></i>
                                        Aprovada
                                    </span>
                                <?php elseif($agenda->status_aprovacao === 'recusada'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-thumbs-down mr-1"></i>
                                        Recusada
                                    </span>
                                <?php endif; ?>

                                <?php if($agenda->fora_horario_comercial): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-moon mr-1"></i>
                                        Fora do Horário
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Informações -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar mr-2 text-purple-500"></i>
                                    <?php echo e(\Carbon\Carbon::parse($agenda->data_inicio)->format('d/m/Y')); ?>

                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-2 text-purple-500"></i>
                                    <?php echo e(\Carbon\Carbon::parse($agenda->data_inicio)->format('H:i')); ?> - 
                                    <?php echo e(\Carbon\Carbon::parse($agenda->data_fim)->format('H:i')); ?>

                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-purple-500"></i>
                                    <?php echo e(ucfirst($agenda->tipo_reuniao)); ?>

                                </div>
                                <?php if($agenda->participantes && count($agenda->participantes) > 0): ?>
                                    <div class="flex items-center">
                                        <i class="fas fa-users mr-2 text-purple-500"></i>
                                        <?php echo e(count($agenda->participantes)); ?> participante(s)
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Solicitante/Destinatário -->
                            <?php if($agenda->solicitante_id && $agenda->solicitante_id !== Auth::id()): ?>
                                <div class="mt-3 flex items-center text-sm text-gray-600">
                                    <i class="fas fa-user-plus mr-2 text-blue-500"></i>
                                    <span>Solicitado por: <strong><?php echo e($agenda->solicitante->name ?? 'N/A'); ?></strong></span>
                                </div>
                            <?php endif; ?>

                            <?php if($agenda->destinatario_id && $agenda->destinatario_id !== Auth::id()): ?>
                                <div class="mt-3 flex items-center text-sm text-gray-600">
                                    <i class="fas fa-user-check mr-2 text-green-500"></i>
                                    <span>Destinatário: <strong><?php echo e($agenda->destinatario->name ?? 'N/A'); ?></strong></span>
                                </div>
                            <?php endif; ?>

                            <?php if($agenda->descricao): ?>
                                <div class="mt-3">
                                    <p class="text-gray-700 text-sm"><?php echo e(Str::limit($agenda->descricao, 100)); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Ações -->
                        <div class="flex flex-col gap-2 ml-4">
                            <!-- Botões de Aprovação (se for destinatário e pendente) -->
                            <?php if($agenda->destinatario_id === Auth::id() && $agenda->status_aprovacao === 'pendente'): ?>
                                <button onclick="aprovarAgenda(<?php echo e($agenda->id); ?>)" 
                                        class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-check mr-1"></i>
                                    Aprovar
                                </button>
                                <button onclick="recusarAgenda(<?php echo e($agenda->id); ?>)" 
                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition-colors">
                                    <i class="fas fa-times mr-1"></i>
                                    Recusar
                                </button>
                            <?php endif; ?>

                            <!-- Links -->
                            <?php if($agenda->google_meet_link || $agenda->meet_link): ?>
                                <a href="<?php echo e($agenda->google_meet_link ?? $agenda->meet_link); ?>" 
                                   target="_blank"
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-video mr-1"></i>
                                    Meet
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Nenhuma agenda encontrada</h3>
                    <p class="text-gray-600 mb-6">
                        <?php if(request('data')): ?>
                            Não há compromissos agendados para <?php echo e(\Carbon\Carbon::parse(request('data'))->format('d/m/Y')); ?>.
                        <?php else: ?>
                            Você ainda não possui compromissos agendados.
                        <?php endif; ?>
                    </p>
                    <a href="<?php echo e(route('licenciado.agenda.create')); ?>" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Criar Nova Reunião
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Função para alterar o filtro de data
function changeDate(selectedDate) {
    if (selectedDate) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('data', selectedDate);
        window.location.href = currentUrl.toString();
    } else {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.delete('data');
        window.location.href = currentUrl.toString();
    }
}

// Função para limpar filtro de data
function clearDateFilter() {
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.delete('data');
    window.location.href = currentUrl.toString();
}

// Função para aprovar agenda
function aprovarAgenda(agendaId) {
    if (confirm('Tem certeza que deseja aprovar esta agenda?')) {
        fetch(`/licenciado/agenda/${agendaId}/aprovar`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao aprovar agenda', 'error');
        });
    }
}

// Função para recusar agenda
function recusarAgenda(agendaId) {
    const motivo = prompt('Motivo da recusa (opcional):');
    if (motivo !== null) { // null = cancelou, string vazia = OK sem motivo
        fetch(`/licenciado/agenda/${agendaId}/recusar`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ motivo: motivo })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao recusar agenda', 'error');
        });
    }
}

// Função para mostrar toast
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 translate-x-full ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'} mr-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 3000);
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.licenciado', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/licenciado/agenda/index.blade.php ENDPATH**/ ?>