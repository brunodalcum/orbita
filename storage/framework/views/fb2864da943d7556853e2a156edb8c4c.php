<?php $__env->startSection('title', 'Nova Reunião'); ?>
<?php $__env->startSection('subtitle', 'Agendar novo compromisso'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-2">
                        <i class="fas fa-plus-circle mr-3"></i>
                        Nova Reunião
                    </h2>
                    <p class="text-purple-100">Preencha os dados para agendar uma nova reunião</p>
                </div>
                <a href="<?php echo e(route('licenciado.agenda')); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>

        <!-- Formulário -->
        <form action="<?php echo e(route('licenciado.agenda.store')); ?>" method="POST" id="agendaForm" class="p-8">
            <?php echo csrf_field(); ?>
            
            <!-- Alertas -->
            <?php if(session('error')): ?>
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?php echo e(session('error')); ?>

                    </div>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Corrija os seguintes erros:</strong>
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Coluna Esquerda -->
                <div class="space-y-6">
                    <!-- Título -->
                    <div class="form-group">
                        <label for="titulo" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-heading text-purple-500 mr-2"></i>
                            Título da Reunião *
                        </label>
                        <input type="text" 
                               id="titulo" 
                               name="titulo" 
                               value="<?php echo e(old('titulo')); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 <?php $__errorArgs = ['titulo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="Ex: Reunião de Alinhamento"
                               required>
                        <?php $__errorArgs = ['titulo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Descrição -->
                    <div class="form-group">
                        <label for="descricao" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-align-left text-purple-500 mr-2"></i>
                            Descrição
                        </label>
                        <textarea id="descricao" 
                                  name="descricao" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 <?php $__errorArgs = ['descricao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  placeholder="Descreva o objetivo da reunião..."><?php echo e(old('descricao')); ?></textarea>
                        <?php $__errorArgs = ['descricao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Tipo de Reunião -->
                    <div class="form-group">
                        <label for="tipo_reuniao" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-purple-500 mr-2"></i>
                            Tipo de Reunião *
                        </label>
                        <select id="tipo_reuniao" 
                                name="tipo_reuniao" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 <?php $__errorArgs = ['tipo_reuniao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required>
                            <option value="">Selecione o tipo</option>
                            <option value="presencial" <?php echo e(old('tipo_reuniao') === 'presencial' ? 'selected' : ''); ?>>
                                Presencial
                            </option>
                            <option value="online" <?php echo e(old('tipo_reuniao') === 'online' ? 'selected' : ''); ?>>
                                Online (Google Meet)
                            </option>
                            <option value="hibrida" <?php echo e(old('tipo_reuniao') === 'hibrida' ? 'selected' : ''); ?>>
                                Híbrida
                            </option>
                        </select>
                        <?php $__errorArgs = ['tipo_reuniao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Link da Reunião -->
                    <div class="form-group">
                        <label for="meet_link" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-video text-purple-500 mr-2"></i>
                            Link da Reunião
                        </label>
                        <input type="url" 
                               id="meet_link" 
                               name="meet_link" 
                               value="<?php echo e(old('meet_link')); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 <?php $__errorArgs = ['meet_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="https://meet.google.com/...">
                        <?php $__errorArgs = ['meet_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Opcional. Será gerado automaticamente se não informado.
                        </p>
                    </div>
                </div>

                <!-- Coluna Direita -->
                <div class="space-y-6">
                    <!-- Data e Hora de Início -->
                    <div class="form-group">
                        <label for="data_inicio" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-plus text-purple-500 mr-2"></i>
                            Data e Hora de Início *
                        </label>
                        <input type="datetime-local" 
                               id="data_inicio" 
                               name="data_inicio" 
                               value="<?php echo e(old('data_inicio')); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 <?php $__errorArgs = ['data_inicio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required>
                        <?php $__errorArgs = ['data_inicio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Data e Hora de Fim -->
                    <div class="form-group">
                        <label for="data_fim" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-minus text-purple-500 mr-2"></i>
                            Data e Hora de Fim *
                        </label>
                        <input type="datetime-local" 
                               id="data_fim" 
                               name="data_fim" 
                               value="<?php echo e(old('data_fim')); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 <?php $__errorArgs = ['data_fim'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required>
                        <?php $__errorArgs = ['data_fim'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Destinatário -->
                    <div class="form-group">
                        <label for="destinatario_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user-check text-purple-500 mr-2"></i>
                            Convidar Usuário
                        </label>
                        <select id="destinatario_id" 
                                name="destinatario_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                onchange="adicionarEmailDestinatario()">
                            <option value="">Selecione um usuário (opcional)</option>
                            <?php $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $roleNames = [
                                        1 => 'Super Admin',
                                        2 => 'Admin', 
                                        3 => 'Funcionário',
                                        4 => 'Licenciado'
                                    ];
                                    $roleName = $roleNames[$usuario->role_id] ?? 'Usuário';
                                ?>
                                <option value="<?php echo e($usuario->id); ?>" 
                                        data-email="<?php echo e($usuario->email); ?>"
                                        <?php echo e(old('destinatario_id') == $usuario->id ? 'selected' : ''); ?>>
                                    <?php echo e($usuario->name); ?> (<?php echo e($roleName); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Selecione para solicitar aprovação de outro usuário
                        </p>
                    </div>

                    <!-- Participantes Adicionais -->
                    <div class="form-group">
                        <label for="participantes" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-users text-purple-500 mr-2"></i>
                            Participantes Adicionais
                            <span id="participantesCount" class="text-xs text-gray-500 ml-2">(0)</span>
                        </label>
                        <textarea id="participantes" 
                                  name="participantes" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 <?php $__errorArgs = ['participantes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  placeholder="Digite os emails separados por vírgula ou quebra de linha&#10;exemplo@email.com, outro@email.com"
                                  oninput="contarParticipantes()"><?php echo e(old('participantes')); ?></textarea>
                        <?php $__errorArgs = ['participantes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Emails separados por vírgula ou quebra de linha
                        </p>
                    </div>
                </div>
            </div>

            <!-- Botão de Envio -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Os campos marcados com * são obrigatórios
                    </div>
                    
                    <button type="submit" 
                            id="submitBtn"
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        <i class="fas fa-save mr-2"></i>
                        <span id="submitText">Agendar Reunião</span>
                        <div id="submitSpinner" class="hidden ml-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Adicionar email do destinatário automaticamente
function adicionarEmailDestinatario() {
    const select = document.getElementById('destinatario_id');
    const textarea = document.getElementById('participantes');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value && selectedOption.dataset.email) {
        const email = selectedOption.dataset.email;
        const currentEmails = textarea.value.trim();
        
        // Verificar se o email já não está na lista
        const emailsList = currentEmails ? currentEmails.split(/[,\n\r]+/).map(e => e.trim()) : [];
        
        if (!emailsList.includes(email)) {
            if (currentEmails) {
                textarea.value = currentEmails + '\n' + email;
            } else {
                textarea.value = email;
            }
            contarParticipantes();
        }
    }
}

// Contar participantes
function contarParticipantes() {
    const textarea = document.getElementById('participantes');
    const counter = document.getElementById('participantesCount');
    
    if (textarea.value.trim()) {
        const emails = textarea.value.split(/[,\n\r]+/).map(e => e.trim()).filter(e => e.length > 0);
        counter.textContent = `(${emails.length})`;
    } else {
        counter.textContent = '(0)';
    }
}

// Configurar data mínima (agora)
document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    
    const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
    
    document.getElementById('data_inicio').min = minDateTime;
    document.getElementById('data_fim').min = minDateTime;
    
    // Auto-ajustar data fim quando data início mudar
    document.getElementById('data_inicio').addEventListener('change', function() {
        const dataInicio = new Date(this.value);
        const dataFim = new Date(dataInicio.getTime() + 60 * 60 * 1000); // +1 hora
        
        const year = dataFim.getFullYear();
        const month = String(dataFim.getMonth() + 1).padStart(2, '0');
        const day = String(dataFim.getDate()).padStart(2, '0');
        const hours = String(dataFim.getHours()).padStart(2, '0');
        const minutes = String(dataFim.getMinutes()).padStart(2, '0');
        
        document.getElementById('data_fim').value = `${year}-${month}-${day}T${hours}:${minutes}`;
        document.getElementById('data_fim').min = this.value;
    });
    
    // Contar participantes inicial
    contarParticipantes();
});

// Loading no submit
document.getElementById('agendaForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    const text = document.getElementById('submitText');
    const spinner = document.getElementById('submitSpinner');
    
    btn.disabled = true;
    text.textContent = 'Agendando...';
    spinner.classList.remove('hidden');
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.licenciado', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/licenciado/agenda/create.blade.php ENDPATH**/ ?>