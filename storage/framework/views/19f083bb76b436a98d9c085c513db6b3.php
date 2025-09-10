<?php $__env->startSection('title', 'Lista de Compromissos'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-list-alt text-blue-600 mr-3"></i>
                        Lista de Compromissos
                    </h1>
                    <p class="text-gray-600 mt-2">Gerencie todos os seus compromissos e reuniões</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200">
                        <input type="date" 
                               id="dateFilter"
                               value="<?php echo e(request('data') ? request('data') : ''); ?>" 
                               onchange="changeDate(this.value)"
                               class="px-4 py-2 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <?php if(request('data')): ?>
                            <button onclick="clearDateFilter()" 
                                    class="px-3 py-2 text-gray-500 hover:text-red-600 transition-colors"
                                    title="Limpar filtro">
                                <i class="fas fa-times"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <?php if(request('data')): ?>
                        <div class="text-sm text-gray-600 bg-blue-50 px-3 py-2 rounded-lg border border-blue-200">
                            <i class="fas fa-filter mr-2 text-blue-600"></i>
                            Filtrado por: <strong><?php echo e(\Carbon\Carbon::parse(request('data'))->format('d/m/Y')); ?></strong>
                        </div>
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('dashboard.agenda.create')); ?>" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Nova Reunião
                    </a>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <?php if(session('success')): ?>
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-600"></i>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-red-600"></i>
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <!-- Filtros e Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Hoje</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo e($agendas->count()); ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Confirmados</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo e($agendas->where('status', 'confirmado')->count()); ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Pendentes</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo e($agendas->where('status', 'pendente')->count()); ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-video text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Online</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo e($agendas->where('tipo_reuniao', 'online')->count()); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Compromissos -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Compromissos de <?php echo e(\Carbon\Carbon::parse($dataAtual)->locale('pt_BR')->translatedFormat('d \\d\\e F \\d\\e Y')); ?>

                    </h2>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500"><?php echo e($agendas->count()); ?> compromisso(s)</span>
                    </div>
                </div>
            </div>

            <!-- Contador de resultados -->
            <div class="mb-6 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <i class="fas fa-calendar-check mr-2 text-blue-600"></i>
                    <?php if(request('data')): ?>
                        <strong><?php echo e($agendas->count()); ?></strong> reunião(ões) encontrada(s) para <strong><?php echo e(\Carbon\Carbon::parse(request('data'))->format('d/m/Y')); ?></strong>
                    <?php else: ?>
                        <strong><?php echo e($agendas->count()); ?></strong> reunião(ões) no total
                    <?php endif; ?>
                </div>
                
                <?php if(!request('data') && $agendas->count() > 0): ?>
                    <div class="text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Use o filtro de data para ver reuniões específicas
                    </div>
                <?php endif; ?>
            </div>

            <?php if($agendas->count() > 0): ?>
                <div class="divide-y divide-gray-200">
                    <?php $__currentLoopData = $agendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agenda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-6 hover:bg-gray-50 transition-colors cursor-pointer" onclick="viewAgenda(<?php echo e($agenda->id); ?>)">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <!-- Status Icon -->
                                    <div class="flex-shrink-0">
                                    <?php if($agenda->status === 'concluida'): ?>
                                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                        </div>
                                    <?php elseif($agenda->status === 'agendada'): ?>
                                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-clock text-yellow-600 text-xl"></i>
                                            </div>
                                        <?php elseif($agenda->status === 'cancelada'): ?>
                                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-times-circle text-red-600 text-xl"></i>
                                            </div>
                                        <?php else: ?>
                                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-calendar text-gray-600 text-xl"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Informações do Compromisso -->
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900"><?php echo e($agenda->titulo); ?></h3>
                                        <?php if($agenda->descricao): ?>
                                            <p class="text-gray-600 mt-1"><?php echo e(Str::limit($agenda->descricao, 100)); ?></p>
                                        <?php endif; ?>
                                        
                                        <div class="flex items-center space-x-6 mt-3">
                                            <div class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-clock mr-2"></i>
                                                <?php echo e(\Carbon\Carbon::parse($agenda->data_inicio)->format('H:i')); ?> - 
                                                <?php echo e(\Carbon\Carbon::parse($agenda->data_fim)->format('H:i')); ?>

                                            </div>
                                            
                                            <div class="flex items-center text-sm text-gray-500">
                                                <?php if($agenda->tipo_reuniao === 'online'): ?>
                                                    <i class="fas fa-video mr-2"></i>
                                                    Online
                                                <?php elseif($agenda->tipo_reuniao === 'presencial'): ?>
                                                    <i class="fas fa-handshake mr-2"></i>
                                                    Presencial
                                                <?php else: ?>
                                                    <i class="fas fa-users mr-2"></i>
                                                    Híbrida
                                                <?php endif; ?>
                                            </div>
                                            
                                            <?php if($agenda->participantes && count($agenda->participantes) > 0): ?>
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <i class="fas fa-users mr-2"></i>
                                                    <?php echo e(count($agenda->participantes)); ?> participante(s)
                                                    
                                                    <?php
                                                        $confirmados = $agenda->confirmacoesConfirmadas()->count();
                                                        $pendentes = $agenda->confirmacoesPendentes()->count();
                                                        $recusados = $agenda->confirmacoesRecusadas()->count();
                                                        $total = count($agenda->participantes);
                                                    ?>
                                                    
                                                    <?php if($confirmados > 0 || $pendentes > 0 || $recusados > 0): ?>
                                                        <span class="ml-2 flex items-center space-x-1">
                                                            <?php if($confirmados > 0): ?>
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                    ✅ <?php echo e($confirmados); ?>

                                                                </span>
                                                            <?php endif; ?>
                                                            <?php if($pendentes > 0): ?>
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                    ⏰ <?php echo e($pendentes); ?>

                                                                </span>
                                                            <?php endif; ?>
                                                            <?php if($recusados > 0): ?>
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                    ❌ <?php echo e($recusados); ?>

                                                                </span>
                                                            <?php endif; ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ações -->
                                <div class="flex items-center space-x-2">
                                    <?php if($agenda->meet_link): ?>
                                        <a href="<?php echo e($agenda->meet_link); ?>" target="_blank" 
                                           class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors"
                                           title="Abrir Google Meet"
                                           onclick="event.stopPropagation()">
                                            <i class="fab fa-google text-lg"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <button onclick="event.stopPropagation(); viewAgendaDetails(<?php echo e($agenda->id); ?>)" 
                                            class="p-2 text-purple-600 hover:bg-purple-100 rounded-lg transition-colors"
                                            title="Ver Detalhes">
                                        <i class="fas fa-eye text-lg"></i>
                                    </button>
                                    
                                    <button onclick="event.stopPropagation(); editAgenda(<?php echo e($agenda->id); ?>)" 
                                            class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors"
                                            title="Editar">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                    
                                    <button onclick="event.stopPropagation(); confirmDeleteAgenda(<?php echo e($agenda->id); ?>, '<?php echo e(addslashes($agenda->titulo)); ?>')" 
                                            class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                                            title="Excluir">
                                        <i class="fas fa-trash text-lg"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="mt-4 flex justify-end">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    <?php if($agenda->status === 'concluida'): ?> bg-green-100 text-green-800
                                    <?php elseif($agenda->status === 'agendada'): ?> bg-yellow-100 text-yellow-800
                                    <?php elseif($agenda->status === 'em_andamento'): ?> bg-blue-100 text-blue-800
                                    <?php elseif($agenda->status === 'cancelada'): ?> bg-red-100 text-red-800
                                    <?php else: ?> bg-gray-100 text-gray-800
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $agenda->status))); ?>

                                </span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum compromisso encontrado</h3>
                    <p class="text-gray-600 mb-6">Não há compromissos agendados para esta data.</p>
                    <button onclick="openAddAgendaModal()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Agendar Primeira Reunião
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de Criação/Edição de Reunião -->
<div id="agendaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900" id="modalTitle">Nova Reunião</h3>
                    <button onclick="closeAgendaModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <form id="agendaForm">
                    <input type="hidden" id="agendaId" name="id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Título da Reunião *</label>
                            <input type="text" id="titulo" name="titulo" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                            <textarea id="descricao" name="descricao" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Data de Início *</label>
                            <input type="datetime-local" id="data_inicio" name="data_inicio" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Data de Fim *</label>
                            <input type="datetime-local" id="data_fim" name="data_fim" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Reunião *</label>
                            <select id="tipo_reuniao" name="tipo_reuniao" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Selecione...</option>
                                <option value="online">Online</option>
                                <option value="presencial">Presencial</option>
                                <option value="hibrida">Híbrida</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Licenciado</label>
                            <select id="licenciado_id" name="licenciado_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Selecione um licenciado...</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Participantes (emails separados por vírgula)</label>
                            <textarea id="participantes" name="participantes" rows="2" placeholder="email1@exemplo.com, email2@exemplo.com"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeAgendaModal()" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </button>
                        <button type="button" onclick="saveAgenda()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Salvar Reunião
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Visualização -->
<div id="viewAgendaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900">Detalhes do Compromisso</h3>
                    <button onclick="closeViewAgendaModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div id="agendaDetails">
                    <!-- Detalhes serão carregados aqui -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">Confirmar Ação</h3>
            </div>
            <div class="p-6">
                <p id="confirmMessage" class="text-gray-600 mb-6">Tem certeza que deseja realizar esta ação?</p>
                <div class="flex justify-end space-x-4">
                    <button onclick="closeConfirmModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button id="confirmButton" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed top-4 right-4 z-50 hidden">
    <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-4 max-w-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i id="toastIcon" class="text-xl"></i>
            </div>
            <div class="ml-3">
                <p id="toastMessage" class="text-sm font-medium text-gray-900"></p>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function changeDate(newDate) {
    window.location.href = `<?php echo e(route('dashboard.agenda')); ?>?data=${newDate}`;
}

// Variáveis globais
let currentAction = null;
let currentAgendaId = null;

// Abrir modal para nova agenda
function openAddAgendaModal() {
    const modal = document.getElementById('agendaModal');
    const modalTitle = document.getElementById('modalTitle');
    const agendaForm = document.getElementById('agendaForm');
    const agendaId = document.getElementById('agendaId');
    
    modalTitle.textContent = 'Nova Reunião';
    agendaForm.reset();
    agendaId.value = '';
    loadLicenciados();
    modal.classList.remove('hidden');
}

// Fechar modais
function closeAgendaModal() {
    document.getElementById('agendaModal').classList.add('hidden');
}

function closeViewAgendaModal() {
    document.getElementById('viewAgendaModal').classList.add('hidden');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}

// Visualizar, editar e excluir agenda
function viewAgenda(id) {
    fetch(`/agenda/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const agenda = data.agenda;
                document.getElementById('agendaDetails').innerHTML = `
                    <div class="space-y-4">
                        <h4 class="text-xl font-bold">${agenda.titulo}</h4>
                        <p><strong>Data:</strong> ${new Date(agenda.data_inicio).toLocaleString('pt-BR')}</p>
                        <p><strong>Status:</strong> ${agenda.status}</p>
                        ${agenda.meet_link ? `<a href="${agenda.meet_link}" target="_blank" class="text-blue-600">Google Meet</a>` : ''}
                    </div>
                `;
                document.getElementById('viewAgendaModal').classList.remove('hidden');
            }
        });
}

function editAgenda(id) {
    // Implementar edição
    console.log('Editar agenda:', id);
}

function deleteAgenda(id) {
    currentAction = 'delete';
    currentAgendaId = id;
    document.getElementById('confirmMessage').textContent = 'Tem certeza que deseja excluir esta reunião?';
    document.getElementById('confirmModal').classList.remove('hidden');
}

function saveAgenda() {
    // Implementar salvamento
    console.log('Salvar agenda');
}

function loadLicenciados() {
    // Implementar carregamento de licenciados
    console.log('Carregar licenciados');
}

function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    toast.classList.remove('hidden');
    document.getElementById('toastMessage').textContent = message;
    setTimeout(() => toast.classList.add('hidden'), 3000);
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Hover effects para os cards */
    .bg-white:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease-in-out;
    }
    
    /* Animações suaves */
    .transition-colors {
        transition: all 0.2s ease-in-out;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .text-3xl {
            font-size: 1.5rem;
        }
        
        .grid-cols-4 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        
        .space-x-6 {
            flex-direction: column;
            align-items: flex-start;
            space-x: 0;
            gap: 0.5rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<!-- Modal de Confirmação de Exclusão -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 transform scale-95 opacity-0 transition-all duration-300" id="deleteModalContent">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Confirmar Exclusão</h3>
            <p class="text-gray-600 mb-6">
                Tem certeza que deseja excluir a reunião 
                <strong id="deleteAgendaTitle" class="text-gray-900"></strong>?
                <br><br>
                <span class="text-sm text-red-600">Esta ação não pode ser desfeita.</span>
            </p>
            <div class="flex space-x-4">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-6 py-3 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button onclick="deleteAgenda()" 
                        id="confirmDeleteBtn"
                        class="flex-1 px-6 py-3 bg-red-600 text-white rounded-xl font-medium hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>
                    Excluir
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalhes da Agenda -->
<div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300" id="detailsModalContent">
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>
                    Detalhes da Reunião
                </h3>
                <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="agendaDetailsContent" class="space-y-6">
                <!-- Conteúdo será carregado via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição da Agenda -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300" id="editModalContent">
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-edit text-blue-600 mr-3"></i>
                    Editar Reunião
                </h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editAgendaForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div id="editFormContent" class="space-y-6">
                    <!-- Conteúdo será carregado via JavaScript -->
                </div>
                
                <div class="flex space-x-4 mt-8">
                    <button type="button" onclick="closeEditModal()" 
                            class="flex-1 px-6 py-3 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo e(asset('js/agenda-actions.js')); ?>"></script>

<script>
// Função para alterar o filtro de data
function changeDate(selectedDate) {
    if (selectedDate) {
        // Redirecionar para a mesma página com o parâmetro de data
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('data', selectedDate);
        window.location.href = currentUrl.toString();
    } else {
        // Se não há data selecionada, remover o parâmetro
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
</script>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/dashboard/agenda.blade.php ENDPATH**/ ?>