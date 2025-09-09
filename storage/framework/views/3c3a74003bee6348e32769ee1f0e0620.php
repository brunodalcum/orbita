<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato - <?php echo e($contract->licenciado->name); ?> - dspay</title>
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
        .timeline-item { position: relative; }
        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 1rem;
            top: 3rem;
            width: 2px;
            height: calc(100% - 1rem);
            background: #e5e7eb;
        }
        .timeline-item.active::after { background: #3b82f6; }
        .progress-bar { 
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%); 
            transition: width 0.5s ease-in-out;
        }
        .step-indicator {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            position: relative;
            z-index: 10;
        }
        .step-completed { background: #10b981; color: white; }
        .step-current { background: #3b82f6; color: white; }
        .step-pending { background: #e5e7eb; color: #6b7280; }
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
                                Contrato - <?php echo e($contract->licenciado->name); ?>

                            </h1>
                            <p class="text-gray-600 mt-1"><?php echo e($contract->licenciado->email); ?></p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo e($contract->status_color); ?>">
                            <?php echo e($contract->status_label); ?>

                        </span>
                        <?php if($contract->canSendContract()): ?>
                            <button onclick="openGenerateModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-paper-plane mr-2"></i>Enviar Contrato
                            </button>
                        <?php elseif($contract->contract_pdf_path): ?>
                            <div class="flex items-center space-x-3">
                                <a href="<?php echo e(route('contracts.view-pdf', $contract)); ?>" target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-eye mr-2"></i>Exibir Contrato
                                </a>
                                <?php if($contract->signed_contract_path): ?>
                                    <a href="<?php echo e(route('contracts.download', $contract)); ?>" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                                        <i class="fas fa-file-signature mr-2"></i>Download Assinado
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('contracts.download', $contract)); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                        <i class="fas fa-download mr-2"></i>Download PDF
                                    </a>
                                <?php endif; ?>
                                <?php if($contract->status === 'contrato_enviado'): ?>
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                        <i class="fas fa-envelope mr-1"></i>Contrato Enviado
                                    </span>
                                <?php elseif($contract->status === 'aguardando_assinatura'): ?>
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                                        <i class="fas fa-clock mr-1"></i>Aguardando Assinatura
                                    </span>
                                <?php elseif($contract->status === 'contrato_assinado'): ?>
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                                        <i class="fas fa-signature mr-1"></i>Contrato Assinado
                                    </span>
                                <?php elseif($contract->status === 'licenciado_aprovado'): ?>
                                    <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm">
                                        <i class="fas fa-check-circle mr-1"></i>Licenciado Aprovado
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Progress Timeline -->
                    <div class="lg:col-span-1">
                        <div class="card rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6">Progresso do Contrato</h3>
                            
                            <!-- Progress Bar -->
                            <div class="mb-6">
                                <div class="flex justify-between text-sm text-gray-600 mb-2">
                                    <span>Progresso</span>
                                    <span><?php echo e($contract->progress_percentage); ?>%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="progress-bar h-3 rounded-full" style="width: <?php echo e($contract->progress_percentage); ?>%"></div>
                                </div>
                            </div>

                            <!-- Timeline Steps -->
                            <div class="space-y-4">
                                <?php
                                    $steps = [
                                        ['key' => 'criado', 'label' => 'Contrato Criado', 'icon' => 'fas fa-plus'],
                                        ['key' => 'contrato_enviado', 'label' => 'Contrato Enviado', 'icon' => 'fas fa-envelope'],
                                        ['key' => 'aguardando_assinatura', 'label' => 'Aguardando Assinatura', 'icon' => 'fas fa-clock'],
                                        ['key' => 'contrato_assinado', 'label' => 'Contrato Assinado', 'icon' => 'fas fa-signature'],
                                        ['key' => 'licenciado_aprovado', 'label' => 'Licenciado Aprovado', 'icon' => 'fas fa-check-circle']
                                    ];
                                    
                                    $currentStepIndex = array_search($contract->status, array_column($steps, 'key'));
                                ?>

                                <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="timeline-item flex items-start <?php echo e($index <= $currentStepIndex ? 'active' : ''); ?>">
                                        <div class="step-indicator <?php echo e($index < $currentStepIndex ? 'step-completed' : ($index === $currentStepIndex ? 'step-current' : 'step-pending')); ?>">
                                            <?php if($index < $currentStepIndex): ?>
                                                <i class="fas fa-check"></i>
                                            <?php else: ?>
                                                <?php echo e($index + 1); ?>

                                            <?php endif; ?>
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <h4 class="text-sm font-medium <?php echo e($index <= $currentStepIndex ? 'text-gray-900' : 'text-gray-500'); ?>">
                                                <?php echo e($step['label']); ?>

                                            </h4>
                                            <?php if($step['key'] === $contract->status): ?>
                                                <p class="text-xs text-blue-600 mt-1">Etapa atual</p>
                                            <?php elseif($index < $currentStepIndex): ?>
                                                <p class="text-xs text-green-600 mt-1">Concluído</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <!-- Key Dates -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-800 mb-3">Datas Importantes</h4>
                                <div class="space-y-2 text-xs text-gray-600">
                                    <div>Criado: <?php echo e($contract->created_at->format('d/m/Y H:i')); ?></div>
                                    <?php if($contract->documents_approved_at): ?>
                                        <div>Documentos Aprovados: <?php echo e($contract->documents_approved_at->format('d/m/Y H:i')); ?></div>
                                    <?php endif; ?>
                                    <?php if($contract->contract_sent_at): ?>
                                        <div>Contrato Enviado: <?php echo e($contract->contract_sent_at->format('d/m/Y H:i')); ?></div>
                                    <?php endif; ?>
                                    <?php if($contract->contract_signed_at): ?>
                                        <div>Contrato Assinado: <?php echo e($contract->contract_signed_at->format('d/m/Y H:i')); ?></div>
                                    <?php endif; ?>
                                    <?php if($contract->licenciado_released_at): ?>
                                        <div>Licenciado Liberado: <?php echo e($contract->licenciado_released_at->format('d/m/Y H:i')); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Area -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Documents Section -->
                        <?php if($contract->documents->count() > 0): ?>
                            <div class="card rounded-xl p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-semibold text-gray-800">Documentos Enviados</h3>
                                    <?php if($contract->canApproveDocuments()): ?>
                                        <button onclick="openReviewModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                            <i class="fas fa-gavel mr-2"></i>Analisar Documentos
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php $__currentLoopData = $contract->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex items-start">
                                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                                        <?php if($document->isPdf()): ?>
                                                            <i class="fas fa-file-pdf text-red-500"></i>
                                                        <?php elseif($document->isImage()): ?>
                                                            <i class="fas fa-image text-blue-500"></i>
                                                        <?php else: ?>
                                                            <i class="fas fa-file text-gray-500"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900"><?php echo e($document->document_type_label); ?></h4>
                                                        <p class="text-xs text-gray-500 mt-1"><?php echo e($document->original_name); ?></p>
                                                        <p class="text-xs text-gray-400"><?php echo e($document->file_size_formatted); ?></p>
                                                    </div>
                                                </div>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo e($document->status_color); ?>">
                                                    <?php echo e($document->status_label); ?>

                                                </span>
                                            </div>

                                            <?php if($document->admin_notes): ?>
                                                <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                                    <p class="text-xs text-gray-600"><?php echo e($document->admin_notes); ?></p>
                                                    <?php if($document->reviewedBy): ?>
                                                        <p class="text-xs text-gray-500 mt-1">
                                                            Por: <?php echo e($document->reviewedBy->name); ?> em <?php echo e($document->reviewed_at->format('d/m/Y H:i')); ?>

                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="flex justify-between items-center">
                                                <span class="text-xs text-gray-500">
                                                    Enviado em <?php echo e($document->created_at->format('d/m/Y H:i')); ?>

                                                </span>
                                                <a href="<?php echo e(route('contracts.download-document', $document)); ?>" 
                                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                                    <i class="fas fa-download mr-1"></i>Download
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="card rounded-xl p-6 text-center">
                                <i class="fas fa-file-upload text-gray-300 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum documento enviado</h3>
                                <p class="text-gray-500">O licenciado ainda não enviou os documentos necessários.</p>
                            </div>
                        <?php endif; ?>

                        <!-- Admin Notes -->
                        <div class="card rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Observações do Admin</h3>
                            <?php if($contract->observacoes_admin): ?>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                    <p class="text-gray-700"><?php echo e($contract->observacoes_admin); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <form action="<?php echo e(route('contracts.update-notes', $contract)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <div class="mb-4">
                                    <textarea name="observacoes_admin" rows="3" 
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                              placeholder="Adicionar observações sobre este contrato..."><?php echo e(old('observacoes_admin', $contract->observacoes_admin)); ?></textarea>
                                </div>
                                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-save mr-2"></i>Salvar Observações
                                </button>
                            </form>
                        </div>

                        <!-- Audit Log -->
                        <?php if($contract->auditLogs->count() > 0): ?>
                            <div class="card rounded-xl p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Histórico de Ações</h3>
                                <div class="space-y-4 max-h-96 overflow-y-auto">
                                    <?php $__currentLoopData = $contract->auditLogs->sortByDesc('created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-start">
                                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3 mt-0.5">
                                                <i class="<?php echo e($log->action_icon); ?> <?php echo e($log->action_color); ?> text-xs"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between">
                                                    <h4 class="text-sm font-medium text-gray-900"><?php echo e($log->action_label); ?></h4>
                                                    <span class="text-xs text-gray-500"><?php echo e($log->created_at->diffForHumans()); ?></span>
                                                </div>
                                                <p class="text-sm text-gray-600 mt-1"><?php echo e($log->description); ?></p>
                                                <?php if($log->user): ?>
                                                    <p class="text-xs text-gray-500 mt-1">Por: <?php echo e($log->user->name); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Review Documents Modal -->
    <?php if($contract->canApproveDocuments()): ?>
        <div id="reviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Analisar Documentos</h3>
                        <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form action="<?php echo e(route('contracts.review-documents', $contract)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $contract->documents->where('status', 'pendente'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="font-medium"><?php echo e($document->document_type_label); ?></h4>
                                        <a href="<?php echo e(route('contracts.download-document', $document)); ?>" 
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-download mr-1"></i>Ver Documento
                                        </a>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-3">
                                        <label class="flex items-center">
                                            <input type="radio" name="document_reviews[<?php echo e($document->id); ?>][status]" value="aprovado" class="mr-2">
                                            <span class="text-green-600">Aprovar</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="document_reviews[<?php echo e($document->id); ?>][status]" value="rejeitado" class="mr-2">
                                            <span class="text-red-600">Rejeitar</span>
                                        </label>
                                    </div>
                                    
                                    <textarea name="document_reviews[<?php echo e($document->id); ?>][notes]" rows="2" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                              placeholder="Observações sobre este documento..."></textarea>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" onclick="closeReviewModal()" 
                                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                <i class="fas fa-gavel mr-2"></i>Finalizar Análise
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Generate Contract Modal -->
    <?php if($contract->canSendContract()): ?>
        <div id="generateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Confirmar Envio de Contrato</h3>
                        <button onclick="closeGenerateModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Validação de Campos -->
                    <div id="validationSection" class="mb-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <h4 class="font-medium text-blue-900 mb-2">Verificando dados obrigatórios...</h4>
                            <div id="validationResults">
                                <div class="flex items-center text-blue-700">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    Validando campos...
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview dos Dados -->
                    <div id="contractPreview" class="mb-6 hidden">
                        <h4 class="font-medium text-gray-900 mb-3">Dados do Contrato</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-medium text-gray-800 mb-2">Contratante</h5>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <div><strong>Nome:</strong> <?php echo e($contract->licenciado->name ?? 'N/A'); ?></div>
                                    <div><strong>Email:</strong> <?php echo e($contract->licenciado->email ?? 'N/A'); ?></div>
                                    <div><strong>CNPJ:</strong> <span id="previewCnpj"><?php echo e($contract->licenciado->cnpj ?? 'N/A'); ?></span></div>
                                    <div><strong>Cidade/UF:</strong> <?php echo e($contract->licenciado->city ?? 'N/A'); ?>/<?php echo e($contract->licenciado->state ?? 'N/A'); ?></div>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-medium text-gray-800 mb-2">Representante</h5>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <div><strong>Nome:</strong> <?php echo e($contract->licenciado->representative_name ?? $contract->licenciado->name ?? 'N/A'); ?></div>
                                    <div><strong>CPF:</strong> <span id="previewCpf"><?php echo e($contract->licenciado->representative_cpf ?? $contract->licenciado->cpf ?? 'N/A'); ?></span></div>
                                    <div><strong>Email:</strong> <?php echo e($contract->licenciado->representative_email ?? $contract->licenciado->email ?? 'N/A'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div id="modalActions" class="flex justify-between space-x-3">
                        <div>
                            <button type="button" onclick="previewPDF()" 
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors" 
                                    id="previewBtn" disabled>
                                <i class="fas fa-eye mr-2"></i>Pré-visualizar PDF
                            </button>
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="closeGenerateModal()" 
                                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition-colors">
                                Cancelar
                            </button>
                            <button type="button" onclick="generateContract()" 
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors"
                                    id="generateBtn" disabled>
                                <i class="fas fa-paper-plane mr-2"></i>Gerar e Enviar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script>
        function openReviewModal() {
            document.getElementById('reviewModal').classList.remove('hidden');
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
        }

        function openGenerateModal() {
            document.getElementById('generateModal').classList.remove('hidden');
            validateContractFields();
        }

        function closeGenerateModal() {
            document.getElementById('generateModal').classList.add('hidden');
        }

        function validateContractFields() {
            // Simular validação - em produção, fazer chamada AJAX
            setTimeout(() => {
                const validationResults = document.getElementById('validationResults');
                const contractPreview = document.getElementById('contractPreview');
                const previewBtn = document.getElementById('previewBtn');
                const generateBtn = document.getElementById('generateBtn');
                
                // Simular resultado da validação
                const isValid = true; // Em produção, verificar campos reais
                
                if (isValid) {
                    validationResults.innerHTML = `
                        <div class="flex items-center text-green-700">
                            <i class="fas fa-check-circle mr-2"></i>
                            Todos os campos obrigatórios estão preenchidos
                        </div>
                    `;
                    contractPreview.classList.remove('hidden');
                    previewBtn.disabled = false;
                    generateBtn.disabled = false;
                    previewBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    generateBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    validationResults.innerHTML = `
                        <div class="flex items-center text-red-700">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Campos obrigatórios não preenchidos
                        </div>
                        <ul class="mt-2 text-sm text-red-600 list-disc list-inside">
                            <li>CNPJ do contratante</li>
                            <li>Endereço completo</li>
                        </ul>
                    `;
                }
            }, 1500);
        }

        function previewPDF() {
            // Abrir preview em nova aba
            window.open('<?php echo e(route("contracts.preview", $contract)); ?>', '_blank');
        }

        function generateContract() {
            if (confirm('Tem certeza que deseja gerar e enviar o contrato para este licenciado?')) {
                // Mostrar loading
                const generateBtn = document.getElementById('generateBtn');
                const originalText = generateBtn.innerHTML;
                generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Gerando...';
                generateBtn.disabled = true;
                
                // Fazer requisição
                fetch('<?php echo e(route("contracts.send", $contract)); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // Sucesso - recarregar página
                        window.location.reload();
                    } else {
                        throw new Error('Erro na requisição');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao gerar contrato. Tente novamente.');
                    generateBtn.innerHTML = originalText;
                    generateBtn.disabled = false;
                });
            }
        }

        // Close modals when clicking outside
        document.getElementById('reviewModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeReviewModal();
            }
        });

        document.getElementById('generateModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeGenerateModal();
            }
        });

        // Show toast messages
        <?php if(session('success')): ?>
            showToast('<?php echo e(session('success')); ?>', 'success');
        <?php endif; ?>

        <?php if(session('error')): ?>
            showToast('<?php echo e(session('error')); ?>', 'error');
        <?php endif; ?>

        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                    ${message}
                </div>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }
    </script>
</body>
</html>
<?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/dashboard/contracts/show.blade.php ENDPATH**/ ?>