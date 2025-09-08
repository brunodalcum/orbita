<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Contrato - Etapa 2 - DSPay</title>
    
    <?php if(file_exists(public_path('build/manifest.json'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php else: ?>
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php endif; ?>
    
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
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
        
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <a href="<?php echo e(route('contracts.generate.index')); ?>"
                           class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Voltar
                        </a>
                        <div class="h-6 border-l border-gray-300"></div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">
                                <i class="fas fa-file-contract text-blue-600 mr-3"></i>
                                Gerar Contrato
                            </h1>
                            <p class="text-gray-600 mt-1">Etapa 2 de 3 • Escolher Template</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Progress Bar -->
            <div class="bg-white border-b px-6 py-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-blue-600">Etapa 2 de 3</span>
                    <span class="text-sm text-gray-500">66% concluído</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 66%"></div>
                </div>
            </div>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-6xl mx-auto">
                    <!-- Steps Overview -->
                    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Fluxo de Geração de Contrato</h2>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span class="ml-2 text-sm font-medium text-green-600">Licenciado Selecionado</span>
                            </div>
                            <div class="flex-1 h-px bg-green-300"></div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium">2</div>
                                <span class="ml-2 text-sm font-medium text-blue-600">Escolher Template</span>
                            </div>
                            <div class="flex-1 h-px bg-gray-300"></div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">3</div>
                                <span class="ml-2 text-sm text-gray-500">Gerar Contrato</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Selected Licensee Info -->
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-lg shadow-sm border p-6 sticky top-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                    <i class="fas fa-user-check text-green-600 mr-2"></i>
                                    Licenciado Selecionado
                                </h3>
                                
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Nome/Razão Social</label>
                                        <p class="text-gray-900"><?php echo e($licenciado->razao_social ?: $licenciado->nome_fantasia); ?></p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">CNPJ/CPF</label>
                                        <p class="text-gray-900"><?php echo e($licenciado->cnpj_cpf); ?></p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Email</label>
                                        <p class="text-gray-900"><?php echo e($licenciado->email); ?></p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Status</label>
                                        <span class="inline-flex px-2 py-1 text-xs rounded-full 
                                            <?php echo e($licenciado->status === 'ativo' ? 'bg-green-100 text-green-800' : 
                                               ($licenciado->status === 'aprovado' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')); ?>">
                                            <?php echo e($licenciado->status); ?>

                                        </span>
                                    </div>
                                    
                                    <?php if($licenciado->representante_nome): ?>
                                    <div class="pt-3 border-t">
                                        <label class="text-sm font-medium text-gray-600">Representante</label>
                                        <p class="text-gray-900"><?php echo e($licenciado->representante_nome); ?></p>
                                        <p class="text-sm text-gray-600"><?php echo e($licenciado->representante_cargo ?: 'Representante Legal'); ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Template Selection -->
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-lg shadow-sm border">
                                <div class="p-6 border-b">
                                    <h3 class="text-lg font-semibold text-gray-800">Escolher Template de Contrato</h3>
                                    <p class="text-gray-600 mt-1">Selecione o modelo de contrato que será usado</p>
                                </div>

                                <div class="p-6">
                                    <?php if($templates->count() > 0): ?>
                                        <div class="space-y-4">
                                            <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors cursor-pointer template-card"
                                                 onclick="selectTemplate(<?php echo e(json_encode($template)); ?>)">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-3">
                                                            <h4 class="font-medium text-gray-900"><?php echo e($template->name); ?></h4>
                                                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                                                v<?php echo e($template->version); ?>

                                                            </span>
                                                        </div>
                                                        
                                                        <?php if($template->description): ?>
                                                        <p class="text-sm text-gray-600 mt-1"><?php echo e($template->description); ?></p>
                                                        <?php endif; ?>
                                                        
                                                        <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                                            <span><i class="fas fa-calendar mr-1"></i><?php echo e($template->created_at->format('d/m/Y')); ?></span>
                                                            <span><i class="fas fa-user mr-1"></i><?php echo e($template->createdBy->name ?? 'Sistema'); ?></span>
                                                            <?php if($template->variables): ?>
                                                                <span><i class="fas fa-code mr-1"></i><?php echo e(count(json_decode($template->variables, true))); ?> variáveis</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4 flex items-center space-x-2">
                                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center border-gray-300 template-circle">
                                                            <i class="fas fa-check text-white text-xs template-check" style="display: none;"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>

                                        <!-- Continue Button -->
                                        <div class="mt-6 flex justify-end">
                                            <form method="POST" action="<?php echo e(route('contracts.generate.step3')); ?>" id="templateForm">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="licenciado_id" value="<?php echo e($licenciado->id); ?>">
                                                <input type="hidden" name="template_id" id="selected_template_id">
                                                <button type="submit" 
                                                        id="continue-btn"
                                                        disabled
                                                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                                    Continuar para Etapa 3
                                                    <i class="fas fa-arrow-right ml-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-8">
                                            <i class="fas fa-file-alt text-gray-400 text-3xl mb-3"></i>
                                            <p class="text-gray-500 mb-4">Nenhum template de contrato disponível</p>
                                            <a href="<?php echo e(route('contract-templates.create')); ?>" 
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                                <i class="fas fa-plus mr-2"></i>Criar Template
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        let selectedTemplateId = null;
        
        function selectTemplate(template) {
            console.log('✅ Template selecionado:', template);
            
            // Remover seleção anterior
            document.querySelectorAll('.template-card').forEach(card => {
                const circle = card.querySelector('.template-circle');
                const check = card.querySelector('.template-check');
                circle.className = 'w-5 h-5 rounded-full border-2 flex items-center justify-center border-gray-300 template-circle';
                check.style.display = 'none';
            });
            
            // Marcar template atual como selecionado
            const currentCard = event.currentTarget;
            const circle = currentCard.querySelector('.template-circle');
            const check = currentCard.querySelector('.template-check');
            
            circle.className = 'w-5 h-5 rounded-full border-2 flex items-center justify-center border-blue-500 bg-blue-500 template-circle';
            check.style.display = 'block';
            
            // Atualizar dados do formulário
            selectedTemplateId = template.id;
            document.getElementById('selected_template_id').value = template.id;
            
            // Habilitar botão de continuar
            const continueBtn = document.getElementById('continue-btn');
            continueBtn.disabled = false;
            
            console.log('📋 Template ID definido:', template.id);
        }
        
        // Validar formulário antes do envio
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Sistema de seleção de templates inicializado');
            console.log('📊 Templates disponíveis:', <?php echo e($templates->count()); ?>);
            
            const form = document.getElementById('templateForm');
            form.addEventListener('submit', function(e) {
                const templateId = document.getElementById('selected_template_id').value;
                const licenciadoId = document.querySelector('input[name="licenciado_id"]').value;
                const csrfToken = document.querySelector('input[name="_token"]').value;
                
                console.log('🔍 DEBUG - Dados do formulário:');
                console.log('  📋 Template ID:', templateId);
                console.log('  👤 Licenciado ID:', licenciadoId);
                console.log('  🔐 CSRF Token:', csrfToken ? 'Presente' : 'AUSENTE');
                console.log('  🎯 Action URL:', form.action);
                console.log('  🔧 Method:', form.method);
                
                if (!templateId) {
                    e.preventDefault();
                    alert('❌ ERRO: Nenhum template selecionado!\\nPor favor, clique em um template primeiro.');
                    return false;
                }
                
                if (!licenciadoId) {
                    e.preventDefault();
                    alert('❌ ERRO: Licenciado ID não encontrado!\\nVolte ao Step 1.');
                    return false;
                }
                
                if (!csrfToken) {
                    e.preventDefault();
                    alert('❌ ERRO: Token CSRF ausente!\\nRecarregue a página.');
                    return false;
                }
                
                // Adicionar loading
                const btn = document.getElementById('continue-btn');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Gerando contrato...';
                
                console.log('✅ Enviando formulário via POST...');
                // Formulário enviado normalmente se chegou até aqui
            });
        });
    </script>
</body>
</html>
<?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/dashboard/contracts/generate/step1.blade.php ENDPATH**/ ?>