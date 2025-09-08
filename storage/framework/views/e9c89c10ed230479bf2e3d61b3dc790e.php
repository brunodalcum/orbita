<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Contrato - dspay</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/dspay-logo.png')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .card { 
            background: white; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); 
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
                    <div class="flex items-center">
                        <a href="<?php echo e(route('contracts.index')); ?>" class="mr-4 text-gray-600 hover:text-gray-800">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">
                                <i class="fas fa-plus text-green-600 mr-3"></i>
                                Novo Contrato
                            </h1>
                            <p class="text-gray-600 mt-1">Criar um novo contrato para licenciado</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-2xl mx-auto">
                    <div class="card rounded-xl p-8">
                        <form action="<?php echo e(route('contracts.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            <div class="mb-6">
                                <label for="licenciado_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Licenciado <span class="text-red-500">*</span>
                                </label>
                                <select name="licenciado_id" id="licenciado_id" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['licenciado_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Selecione um licenciado</option>
                                    <?php $__currentLoopData = $licenciados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $licenciado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($licenciado->id); ?>" <?php echo e(old('licenciado_id') == $licenciado->id ? 'selected' : ''); ?>>
                                            <?php echo e($licenciado->razao_social); ?> (<?php echo e($licenciado->nome_fantasia); ?>) - <?php echo e($licenciado->cnpj_cpf); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['licenciado_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                
                                <?php if($licenciados->isEmpty()): ?>
                                    <p class="text-yellow-600 text-sm mt-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Não há licenciados disponíveis. Todos os licenciados já possuem contratos ativos.
                                    </p>
                                <?php endif; ?>
                            </div>

                            <div class="mb-6">
                                <label for="observacoes_admin" class="block text-sm font-medium text-gray-700 mb-2">
                                    Observações Iniciais
                                </label>
                                <textarea name="observacoes_admin" id="observacoes_admin" rows="4" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['observacoes_admin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                          placeholder="Adicione observações ou instruções especiais para este contrato..."><?php echo e(old('observacoes_admin')); ?></textarea>
                                <?php $__errorArgs = ['observacoes_admin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Informações sobre o processo -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                                <h3 class="text-lg font-medium text-blue-900 mb-3">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Sobre o Processo de Contrato
                                </h3>
                                <div class="text-sm text-blue-800 space-y-2">
                                    <p><strong>1. Documentos Pendentes:</strong> O licenciado será notificado para enviar os documentos necessários.</p>
                                    <p><strong>2. Análise:</strong> Você poderá revisar e aprovar/rejeitar cada documento enviado.</p>
                                    <p><strong>3. Contrato:</strong> Após aprovação dos documentos, o contrato será gerado e enviado automaticamente.</p>
                                    <p><strong>4. Assinatura:</strong> O licenciado receberá um link para assinatura eletrônica.</p>
                                    <p><strong>5. Liberação:</strong> Após assinatura, o licenciado será automaticamente liberado no sistema.</p>
                                </div>
                            </div>

                            <!-- Documentos necessários -->
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-3">
                                    <i class="fas fa-file-alt mr-2"></i>
                                    Documentos Necessários
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700">
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        RG (Frente e Verso)
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        CPF
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        Comprovante de Residência
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        CNPJ (se empresa)
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        Contrato Social (se empresa)
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        Comprovante de Renda
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        Referências Bancárias
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-4">
                                <a href="<?php echo e(route('contracts.index')); ?>" 
                                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Cancelar
                                </a>
                                <button type="submit" 
                                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors <?php echo e($licenciados->isEmpty() ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                                        <?php echo e($licenciados->isEmpty() ? 'disabled' : ''); ?>>
                                    <i class="fas fa-plus mr-2"></i>Criar Contrato
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-md z-50">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <?php echo e(session('success')); ?>

            </div>
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('.fixed.top-4').remove();
            }, 5000);
        </script>
    <?php endif; ?>
</body>
</html>
<?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/dashboard/contracts/create.blade.php ENDPATH**/ ?>