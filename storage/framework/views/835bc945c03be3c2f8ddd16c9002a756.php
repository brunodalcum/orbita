<?php $__env->startSection('title', 'Pendentes de Aprovação'); ?>
<?php $__env->startSection('subtitle', 'Solicitações aguardando sua aprovação'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-clock text-orange-600 mr-3"></i>
                    Pendentes de Aprovação
                </h2>
                <p class="text-gray-600">Solicitações de reunião aguardando sua aprovação</p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="text-sm text-gray-600 bg-orange-50 px-3 py-2 rounded-lg border border-orange-200">
                    <i class="fas fa-hourglass-half mr-2 text-orange-600"></i>
                    <strong><?php echo e($agendas->count()); ?></strong> solicitação(ões) pendente(s)
                </div>
                
                <a href="<?php echo e(route('licenciado.agenda')); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar à Agenda
                </a>
            </div>
        </div>
    </div>

    <!-- Lista de Solicitações -->
    <div class="grid gap-4">
        <?php $__empty_1 = true; $__currentLoopData = $agendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agenda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Cabeçalho -->
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-plus text-orange-600 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900"><?php echo e($agenda->titulo); ?></h3>
                                    <p class="text-sm text-gray-600">
                                        Solicitado por: <strong><?php echo e($agenda->solicitante->name ?? 'N/A'); ?></strong>
                                    </p>
                                </div>
                                
                                <!-- Badges -->
                                <div class="flex gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Pendente
                                    </span>
                                    
                                    <?php if($agenda->fora_horario_comercial): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-moon mr-1"></i>
                                            Fora do Horário
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Informações da Reunião -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-calendar mr-3 text-purple-500 w-4"></i>
                                    <span><?php echo e(\Carbon\Carbon::parse($agenda->data_inicio)->format('d/m/Y')); ?></span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-clock mr-3 text-purple-500 w-4"></i>
                                    <span><?php echo e(\Carbon\Carbon::parse($agenda->data_inicio)->format('H:i')); ?> - 
                                          <?php echo e(\Carbon\Carbon::parse($agenda->data_fim)->format('H:i')); ?></span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-3 text-purple-500 w-4"></i>
                                    <span><?php echo e(ucfirst($agenda->tipo_reuniao)); ?></span>
                                </div>
                                <?php if($agenda->participantes && count($agenda->participantes) > 0): ?>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-users mr-3 text-purple-500 w-4"></i>
                                        <span><?php echo e(count($agenda->participantes)); ?> participante(s)</span>
                                    </div>
                                <?php endif; ?>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-paper-plane mr-3 text-purple-500 w-4"></i>
                                    <span><?php echo e($agenda->created_at->format('d/m/Y H:i')); ?></span>
                                </div>
                            </div>

                            <!-- Descrição -->
                            <?php if($agenda->descricao): ?>
                                <div class="mb-4">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Descrição:</h4>
                                    <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg"><?php echo e($agenda->descricao); ?></p>
                                </div>
                            <?php endif; ?>

                            <!-- Participantes -->
                            <?php if($agenda->participantes && count($agenda->participantes) > 0): ?>
                                <div class="mb-4">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Participantes:</h4>
                                    <div class="flex flex-wrap gap-2">
                                        <?php $__currentLoopData = $agenda->participantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="inline-flex items-center px-2.5 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                                <i class="fas fa-envelope mr-1"></i>
                                                <?php echo e($participante); ?>

                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Link da Reunião -->
                            <?php if($agenda->google_meet_link || $agenda->meet_link): ?>
                                <div class="mb-4">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Link da Reunião:</h4>
                                    <a href="<?php echo e($agenda->google_meet_link ?? $agenda->meet_link); ?>" 
                                       target="_blank"
                                       class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fas fa-video mr-2"></i>
                                        <?php echo e($agenda->google_meet_link ?? $agenda->meet_link); ?>

                                        <i class="fas fa-external-link-alt ml-2"></i>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <!-- Aviso de Horário -->
                            <?php if($agenda->fora_horario_comercial): ?>
                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-3 mb-4">
                                    <div class="flex items-center text-sm text-purple-800">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <span><strong>Atenção:</strong> Esta reunião está agendada fora do horário comercial (09:00 às 18:00).</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Ações -->
                        <div class="flex flex-col gap-3 ml-6">
                            <button onclick="aprovarAgenda(<?php echo e($agenda->id); ?>)" 
                                    class="inline-flex items-center px-6 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                <i class="fas fa-check mr-2"></i>
                                Aprovar
                            </button>
                            
                            <button onclick="recusarAgenda(<?php echo e($agenda->id); ?>)" 
                                    class="inline-flex items-center px-6 py-2.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                <i class="fas fa-times mr-2"></i>
                                Recusar
                            </button>
                            
                            <button onclick="verDetalhes(<?php echo e($agenda->id); ?>)" 
                                    class="inline-flex items-center px-6 py-2.5 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                <i class="fas fa-eye mr-2"></i>
                                Detalhes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-check-circle text-6xl text-green-300 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Nenhuma solicitação pendente</h3>
                    <p class="text-gray-600 mb-6">
                        Parabéns! Você não possui solicitações de reunião aguardando aprovação.
                    </p>
                    <div class="flex justify-center gap-3">
                        <a href="<?php echo e(route('licenciado.agenda')); ?>" 
                           class="inline-flex items-center px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-calendar mr-2"></i>
                            Ver Agenda
                        </a>
                        <a href="<?php echo e(route('licenciado.agenda.create')); ?>" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Nova Reunião
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de Detalhes -->
<div id="detalhesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Detalhes da Solicitação</h3>
                <button onclick="fecharDetalhes()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="detalhesConteudo">
                <!-- Conteúdo será carregado via JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
// Função para aprovar agenda
function aprovarAgenda(agendaId) {
    if (confirm('Tem certeza que deseja aprovar esta solicitação de reunião?')) {
        const btn = event.target;
        const originalText = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Aprovando...';
        
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
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao aprovar agenda', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }
}

// Função para recusar agenda
function recusarAgenda(agendaId) {
    const motivo = prompt('Motivo da recusa (opcional):');
    if (motivo !== null) { // null = cancelou, string vazia = OK sem motivo
        const btn = event.target;
        const originalText = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Recusando...';
        
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
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao recusar agenda', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }
}

// Função para ver detalhes
function verDetalhes(agendaId) {
    document.getElementById('detalhesModal').classList.remove('hidden');
    document.getElementById('detalhesConteudo').innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i></div>';
    
    // Aqui você pode fazer uma requisição AJAX para buscar mais detalhes se necessário
    // Por enquanto, vamos apenas mostrar as informações já disponíveis
    setTimeout(() => {
        document.getElementById('detalhesConteudo').innerHTML = `
            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">Informações Básicas</h4>
                    <p class="text-sm text-gray-600">ID da Agenda: ${agendaId}</p>
                    <p class="text-sm text-gray-600">Para mais detalhes, consulte a lista principal.</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button onclick="fecharDetalhes()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        Fechar
                    </button>
                </div>
            </div>
        `;
    }, 500);
}

// Função para fechar modal de detalhes
function fecharDetalhes() {
    document.getElementById('detalhesModal').classList.add('hidden');
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
    }, 4000);
}

// Fechar modal ao clicar fora
document.getElementById('detalhesModal').addEventListener('click', function(e) {
    if (e.target === this) {
        fecharDetalhes();
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.licenciado', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/licenciado/agenda/pendentes.blade.php ENDPATH**/ ?>