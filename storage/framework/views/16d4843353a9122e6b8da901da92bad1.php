<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($template->name); ?> - Template de Contrato</title>
    
    <?php if(file_exists(public_path('build/manifest.json'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php else: ?>
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php endif; ?>
    
    <style>
        .variable-highlight { background-color: #fef3c7; border: 1px solid #f59e0b; padding: 2px 4px; border-radius: 3px; }
    </style>
</head>

<body class="bg-gray-50" x-data="templateViewer(<?php echo e($template->id); ?>)">
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
                    <div class="flex items-center space-x-4">
                        <a href="<?php echo e(route('contract-templates.index')); ?>" 
                           class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Voltar
                        </a>
                        
                        <div class="h-6 border-l border-gray-300"></div>
                        
                        <div>
                            <div class="flex items-center space-x-3">
                                <h1 class="text-2xl font-bold text-gray-800">
                                    <i class="fas fa-file-code text-blue-600 mr-3"></i>
                                    <?php echo e($template->name); ?>

                                </h1>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-sm font-medium <?php echo e($template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'); ?>">
                                    <?php echo e($template->is_active ? 'Ativo' : 'Inativo'); ?>

                                </span>
                            </div>
                            <?php if($template->description): ?>
                                <p class="text-gray-600 mt-1"><?php echo e($template->description); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <button @click="previewTemplate()" 
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            Preview
                        </button>
                        
                        <a href="<?php echo e(route('contract-templates.edit', $template)); ?>" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                            <i class="fas fa-edit mr-2"></i>
                            Editar
                        </a>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Conteúdo Principal -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Informações do Template -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informações do Template</h2>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900">v<?php echo e($template->version); ?></div>
                                    <div class="text-sm text-gray-600">Versão</div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900"><?php echo e($usageCount); ?></div>
                                    <div class="text-sm text-gray-600">Contratos</div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900"><?php echo e(count($variables)); ?></div>
                                    <div class="text-sm text-gray-600">Variáveis</div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900"><?php echo e(number_format(strlen($template->content))); ?></div>
                                    <div class="text-sm text-gray-600">Caracteres</div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Criado por:</span>
                                    <span class="text-gray-600"><?php echo e($template->createdBy->name ?? 'N/A'); ?></span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Data de criação:</span>
                                    <span class="text-gray-600"><?php echo e($template->created_at->format('d/m/Y H:i')); ?></span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Última atualização:</span>
                                    <span class="text-gray-600"><?php echo e($template->updated_at->format('d/m/Y H:i')); ?></span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Status:</span>
                                    <span class="text-gray-600"><?php echo e($template->is_active ? 'Ativo' : 'Inativo'); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Conteúdo do Template -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-900">Conteúdo do Template</h2>
                                <div class="flex items-center space-x-2">
                                    <button @click="copyContent()" 
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
                                        <i class="fas fa-copy mr-1"></i>
                                        Copiar
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <div class="bg-gray-50 rounded-lg p-4 max-h-96 overflow-y-auto">
                                    <pre class="whitespace-pre-wrap text-sm text-gray-800 font-mono"><?php echo e($template->content); ?></pre>
                                </div>
                            </div>
                        </div>

                        <!-- Ações -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <form action="<?php echo e(route('contract-templates.toggle-status', $template)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors <?php echo e($template->is_active ? 'text-orange-600 hover:text-orange-700 hover:bg-orange-50 border border-orange-200' : 'text-green-600 hover:text-green-700 hover:bg-green-50 border border-green-200'); ?>">
                                        <i class="fas <?php echo e($template->is_active ? 'fa-pause' : 'fa-play'); ?> mr-2"></i>
                                        <?php echo e($template->is_active ? 'Desativar' : 'Ativar'); ?>

                                    </button>
                                </form>
                                
                                <form action="<?php echo e(route('contract-templates.duplicate', $template)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 border border-gray-300 rounded-lg transition-colors">
                                        <i class="fas fa-copy mr-2"></i>
                                        Duplicar
                                    </button>
                                </form>
                            </div>
                            
                            <?php if($usageCount == 0): ?>
                                <form action="<?php echo e(route('contract-templates.destroy', $template)); ?>" 
                                      method="POST" 
                                      onsubmit="return confirm('Tem certeza que deseja excluir este template? Esta ação não pode ser desfeita.')" 
                                      class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 border border-red-300 rounded-lg transition-colors">
                                        <i class="fas fa-trash mr-2"></i>
                                        Excluir
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Template em uso por <?php echo e($usageCount); ?> contrato(s)
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Variáveis Utilizadas -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                Variáveis Utilizadas
                                <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <?php echo e(count($variables)); ?>

                                </span>
                            </h3>
                            
                            <?php if(!empty($variables)): ?>
                                <div class="space-y-3 max-h-60 overflow-y-auto">
                                    <?php $__currentLoopData = $variables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="font-mono text-sm font-semibold text-blue-600">
                                                    <?php echo e($variable['placeholder'] ?? $variable['name']); ?>

                                                </span>
                                                <?php if($variable['required'] ?? false): ?>
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                        Obrigatório
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <?php if(!empty($variable['description'])): ?>
                                                <p class="text-xs text-gray-600"><?php echo e($variable['description']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4 text-gray-500">
                                    <i class="fas fa-code text-2xl mb-2 block"></i>
                                    <p class="text-sm">Nenhuma variável detectada</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Contratos Utilizando -->
                        <?php if($usageCount > 0): ?>
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    Contratos Utilizando
                                    <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <?php echo e($usageCount); ?>

                                    </span>
                                </h3>
                                
                                <div class="text-center py-4">
                                    <a href="<?php echo e(route('contracts.index', ['template' => $template->id])); ?>" 
                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">
                                        <i class="fas fa-list mr-2"></i>
                                        Ver Contratos
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Ações Rápidas -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-blue-900 mb-3">
                                <i class="fas fa-bolt mr-1"></i>
                                Ações Rápidas
                            </h4>
                            <div class="space-y-2">
                                <a href="<?php echo e(route('contract-templates.edit', $template)); ?>" 
                                   class="block w-full text-left px-3 py-2 text-sm text-blue-800 hover:bg-blue-100 rounded-lg transition-colors">
                                    <i class="fas fa-edit mr-2"></i>
                                    Editar Template
                                </a>
                                <button @click="previewTemplate()" 
                                        class="block w-full text-left px-3 py-2 text-sm text-blue-800 hover:bg-blue-100 rounded-lg transition-colors">
                                    <i class="fas fa-eye mr-2"></i>
                                    Visualizar Preview
                                </button>
                                <a href="<?php echo e(route('contracts.create', ['template' => $template->id])); ?>" 
                                   class="block w-full text-left px-3 py-2 text-sm text-blue-800 hover:bg-blue-100 rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    Criar Contrato
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </main>
        </div>
    </div>

    <!-- Modal Preview -->
    <div x-show="showPreview" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         @keydown.escape="showPreview = false">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showPreview = false"></div>
            
            <div class="inline-block w-full max-w-6xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between mb-4 flex-shrink-0">
                    <h3 class="text-lg font-semibold text-gray-900">Preview: <?php echo e($template->name); ?></h3>
                    <button @click="showPreview = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto border border-gray-200 rounded-lg p-6 bg-white">
                    <div class="prose prose-sm max-w-none" x-html="previewContent"></div>
                </div>
                
                <div class="mt-4 flex justify-end space-x-3">
                    <button @click="showPreview = false" 
                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        Fechar
                    </button>
                    <a href="<?php echo e(route('contract-templates.edit', $template)); ?>" 
                       class="px-4 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        Editar Template
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function templateViewer(templateId) {
            return {
                showPreview: false,
                previewContent: '',
                
                async previewTemplate() {
                    try {
                        const response = await fetch(`/contract-templates/${templateId}/preview`);
                        const data = await response.json();
                        
                        if (data.success) {
                            this.previewContent = data.preview;
                            this.showPreview = true;
                        } else {
                            alert('Erro ao gerar preview: ' + data.error);
                        }
                    } catch (error) {
                        console.error('Erro:', error);
                        alert('Erro ao carregar preview');
                    }
                },
                
                async copyContent() {
                    try {
                        const content = `<?php echo $template->content; ?>`;
                        await navigator.clipboard.writeText(content);
                        
                        // Mostrar feedback
                        const button = event.target.closest('button');
                        const originalText = button.innerHTML;
                        button.innerHTML = '<i class="fas fa-check mr-1"></i>Copiado!';
                        button.classList.add('text-green-600');
                        
                        setTimeout(() => {
                            button.innerHTML = originalText;
                            button.classList.remove('text-green-600');
                        }, 2000);
                    } catch (error) {
                        alert('Erro ao copiar conteúdo');
                    }
                }
            }
        }
    </script>

    <?php if(session('success')): ?>
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" 
             x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <?php echo e(session('success')); ?>

            </div>
        </div>
    <?php endif; ?>
</body>
</html>
<?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/dashboard/contract-templates/show.blade.php ENDPATH**/ ?>