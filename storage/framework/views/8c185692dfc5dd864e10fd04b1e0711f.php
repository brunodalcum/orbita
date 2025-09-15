<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Licenciados - dspay</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/dspay-logo.png')); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('images/dspay-logo.png')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="<?php echo e(asset('app.css')); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Branding Dinâmico -->
    <?php if (isset($component)) { $__componentOriginal9ddf4d6754b0e3c33300f8613a00c4e4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ddf4d6754b0e3c33300f8613a00c4e4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dynamic-branding','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-branding'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ddf4d6754b0e3c33300f8613a00c4e4)): ?>
<?php $attributes = $__attributesOriginal9ddf4d6754b0e3c33300f8613a00c4e4; ?>
<?php unset($__attributesOriginal9ddf4d6754b0e3c33300f8613a00c4e4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ddf4d6754b0e3c33300f8613a00c4e4)): ?>
<?php $component = $__componentOriginal9ddf4d6754b0e3c33300f8613a00c4e4; ?>
<?php unset($__componentOriginal9ddf4d6754b0e3c33300f8613a00c4e4); ?>
<?php endif; ?>
    
    <style>
        /* Estilos para os filtros */
        #filter-chevron {
            transition: transform 0.3s ease;
        }
        
        .filter-card {
            transition: all 0.3s ease;
        }
        
        .filter-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        /* Animação para os campos de filtro */
        .filter-input {
            transition: all 0.3s ease;
        }
        
        .filter-input:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }
        
        /* Estilo para o contador de resultados */
        .results-counter {
            background: var(--primary-gradient); color: var(--primary-text);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 600;
        }
        
        /* Responsividade para filtros */
        @media (max-width: 768px) {
            .filter-grid {
                grid-template-columns: 1fr !important;
            }
        }
            
        /* Estilos dinâmicos do dashboard */
        .dashboard-header {
            background: var(--background-color);
            color: var(--text-color);
        }
        .stat-card {
            background: var(--primary-gradient);
            color: var(--primary-text);
        }
        .progress-bar {
            background: var(--accent-gradient);
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
            <header class="dashboard-header bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold" style="color: var(--text-color);">Licenciados</h1>
                        <p style="color: var(--secondary-color);">Gerencie os licenciados do sistema</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button onclick="openLicenciadoModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Novo Licenciado
                        </button>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Total de Licenciados</p>
                                <p class="text-3xl font-bold"><?php echo e($stats['total'] ?? 0); ?></p>
                                <p class="text-white/80 text-sm mt-1">
                                    <i class="fas fa-users mr-1"></i>
                                    Cadastros ativos
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-pink-500 to-red-500 rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Aprovados</p>
                                <p class="text-3xl font-bold"><?php echo e($stats['aprovados'] ?? 0); ?></p>
                                <p class="text-white/80 text-sm mt-1">
                                    <i class="fas fa-check mr-1"></i>
                                    Licenciados ativos
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Em Análise</p>
                                <p class="text-3xl font-bold"><?php echo e($stats['em_analise'] ?? 0); ?></p>
                                <p class="text-white/80 text-sm mt-1">
                                    <i class="fas fa-clock mr-1"></i>
                                    Aguardando aprovação
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Recusados</p>
                                <p class="text-3xl font-bold"><?php echo e($stats['recusados'] ?? 0); ?></p>
                                <p class="text-white/80 text-sm mt-1">
                                    <i class="fas fa-times mr-1"></i>
                                    Cadastros recusados
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-times-circle text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="bg-white rounded-lg shadow-sm border mb-6 filter-card">
                    <div class="p-6 border-b">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <i class="fas fa-filter mr-2 text-blue-500"></i>
                                Filtros
                            </h3>
                            <button onclick="toggleFilters()" class="text-blue-500 hover:" style="color: var(--primary-color);" transition-colors">
                                <i class="fas fa-chevron-down" id="filter-chevron"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div id="filters-content" class="p-6">
                        <form method="GET" action="<?php echo e(route('dashboard.licenciados')); ?>" id="filters-form">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 filter-grid">
                                <!-- Filtro por Nome -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-user mr-1"></i>
                                        Nome/Razão Social
                                    </label>
                                    <input type="text" 
                                           name="nome" 
                                           value="<?php echo e(request('nome')); ?>"
                                           placeholder="Digite o nome..."
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent filter-input">
                                </div>

                                <!-- Filtro por Cidade -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        Cidade
                                    </label>
                                    <select name="cidade" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent filter-input">
                                        <option value="">Todas as cidades</option>
                                        <?php $__currentLoopData = $cidades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cidade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($cidade); ?>" <?php echo e(request('cidade') == $cidade ? 'selected' : ''); ?>>
                                                <?php echo e($cidade); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <!-- Filtro por Estado -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-map mr-1"></i>
                                        Estado
                                    </label>
                                    <select name="estado" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent filter-input">
                                        <option value="">Todos os estados</option>
                                        <?php $__currentLoopData = $estados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($estado); ?>" <?php echo e(request('estado') == $estado ? 'selected' : ''); ?>>
                                                <?php echo e($estado); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <!-- Filtro por Operação -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-cogs mr-1"></i>
                                        Operação
                                    </label>
                                    <select name="operacao" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent filter-input">
                                        <option value="">Todas as operações</option>
                                        <?php $__currentLoopData = $operacoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $operacao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($operacao->id); ?>" <?php echo e(request('operacao') == $operacao->id ? 'selected' : ''); ?>>
                                                <?php echo e($operacao->nome); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <!-- Filtro por Status -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-flag mr-1"></i>
                                        Status
                                    </label>
                                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent filter-input">
                                        <option value="">Todos os status</option>
                                        <option value="aprovado" <?php echo e(request('status') == 'aprovado' ? 'selected' : ''); ?>>Aprovado</option>
                                        <option value="em_analise" <?php echo e(request('status') == 'em_analise' ? 'selected' : ''); ?>>Em Análise</option>
                                        <option value="recusado" <?php echo e(request('status') == 'recusado' ? 'selected' : ''); ?>>Recusado</option>
                                        <option value="ativo" <?php echo e(request('status') == 'ativo' ? 'selected' : ''); ?>>Ativo</option>
                                        <option value="inativo" <?php echo e(request('status') == 'inativo' ? 'selected' : ''); ?>>Inativo</option>
                                        <option value="pendente" <?php echo e(request('status') == 'pendente' ? 'selected' : ''); ?>>Pendente</option>
                                        <option value="risco" <?php echo e(request('status') == 'risco' ? 'selected' : ''); ?>>Risco</option>
                                        <option value="vencendo" <?php echo e(request('status') == 'vencendo' ? 'selected' : ''); ?>>Vencendo</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Filtros de Data -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        Data Inicial
                                    </label>
                                    <input type="date" 
                                           name="data_inicial" 
                                           value="<?php echo e(request('data_inicial')); ?>"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent filter-input">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        Data Final
                                    </label>
                                    <input type="date" 
                                           name="data_final" 
                                           value="<?php echo e(request('data_final')); ?>"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent filter-input">
                                </div>
                            </div>

                            <!-- Botões de Ação -->
                            <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                                <div class="flex items-center space-x-4">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors">
                                        <i class="fas fa-search mr-2"></i>
                                        Aplicar Filtros
                                    </button>
                                    <a href="<?php echo e(route('dashboard.licenciados')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                                        <i class="fas fa-times mr-2"></i>
                                        Limpar Filtros
                                    </a>
                                </div>
                                
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <span class="results-counter"><?php echo e($licenciados->count()); ?></span> licenciado(s) encontrado(s)
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabela de Licenciados -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="flex items-center justify-between p-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Lista de Licenciados</h3>
                        <div class="flex items-center space-x-4">
                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-download mr-2"></i>
                                Exportar
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Razão Social</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CNPJ/CPF</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Cadastro</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if(isset($licenciados) && $licenciados->count() > 0): ?>
                                    <?php $__currentLoopData = $licenciados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $licenciado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900"><?php echo e($licenciado->razao_social); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo e($licenciado->nome_fantasia); ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?php echo e($licenciado->cnpj_cpf_formatado); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php if($licenciado->status == 'aprovado'): ?>
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        Aprovado
                                                    </span>
                                                <?php elseif($licenciado->status == 'em_analise'): ?>
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Em Análise
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                        Recusado
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?php echo e($licenciado->created_at->format('d/m/Y')); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="<?php echo e(route('licenciados.gerenciar', $licenciado->id)); ?>" 
                                                       class="inline-flex items-center px-3 py-2 text-sm font-medium " style="color: var(--primary-color);" bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors duration-200" 
                                                       title="Gerenciar Licenciado">
                                                        <i class="fas fa-eye mr-1"></i>
                                                        <span class="hidden sm:inline">Gerenciar</span>
                                                    </a>
                                                    <button onclick="editLicenciado(<?php echo e($licenciado->id); ?>)" 
                                                            class="inline-flex items-center px-3 py-2 text-sm font-medium " style="color: var(--accent-color);" bg-green-50 hover:bg-green-100 rounded-lg transition-colors duration-200" 
                                                            title="Editar Licenciado">
                                                        <i class="fas fa-edit mr-1"></i>
                                                        <span class="hidden sm:inline">Editar</span>
                                                    </button>
                                                    <button onclick="openStatusModal(<?php echo e($licenciado->id); ?>, <?php echo e(json_encode($licenciado->razao_social)); ?>, '<?php echo e($licenciado->status); ?>')" 
                                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-purple-600 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors duration-200" 
                                                            title="Alterar Status">
                                                        <i class="fas fa-cogs mr-1"></i>
                                                        <span class="hidden sm:inline">Status</span>
                                                    </button>
                                                    <button onclick="deleteLicenciado(<?php echo e($licenciado->id); ?>, <?php echo e(json_encode($licenciado->razao_social)); ?>)" 
                                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors duration-200" 
                                                            title="Excluir Licenciado">
                                                        <i class="fas fa-trash mr-1"></i>
                                                        <span class="hidden sm:inline">Excluir</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-2"></i>
                                            <p>Nenhum licenciado cadastrado ainda</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal de Cadastro de Licenciado -->
    <div id="licenciadoModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" onclick="handleModalClick(event)">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
            <!-- Header do Modal -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">Cadastro de Licenciado</h2>
                        <p class="text-white/80 mt-1">Preencha as informações do novo licenciado</p>
                    </div>
                    <button type="button" onclick="closeLicenciadoModal()" class="text-white/80 hover:text-white hover:bg-white/20 text-2xl transition-all duration-200 rounded-full p-2" title="Fechar modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Progress Steps -->
                <div class="mt-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div id="step1-indicator" class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-sm font-semibold step-indicator active">
                                1
                            </div>
                            <span class="ml-2 text-sm">Dados Principais</span>
                        </div>
                        <div class="flex-1 h-0.5 bg-white/20 mx-4"></div>
                        <div class="flex items-center">
                            <div id="step2-indicator" class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-sm font-semibold step-indicator">
                                2
                            </div>
                            <span class="ml-2 text-sm">Documentos</span>
                        </div>
                        <div class="flex-1 h-0.5 bg-white/20 mx-4"></div>
                        <div class="flex items-center">
                            <div id="step3-indicator" class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-sm font-semibold step-indicator">
                                3
                            </div>
                            <span class="ml-2 text-sm">Operações</span>
                        </div>
                        <div class="flex-1 h-0.5 bg-white/20 mx-4"></div>
                        <div class="flex items-center">
                            <div id="step4-indicator" class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-sm font-semibold step-indicator">
                                4
                            </div>
                            <span class="ml-2 text-sm">Finalizar</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form id="licenciadoForm" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                
                <!-- Área de Erros de Validação -->
                <div id="validationErrors" class="hidden bg-red-50 border-l-4 border-red-400 p-4 mx-6 mt-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Erro de Validação
                            </h3>
                            <div class="mt-2 text-sm text-red-700" id="errorList">
                                <!-- Erros serão inseridos aqui -->
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 overflow-y-auto max-h-[60vh]">
                    <!-- Step 1: Dados Principais -->
                    <div id="step1" class="step-content">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Dados Principais</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">CNPJ/CPF *</label>
                                <div class="flex space-x-2">
                                    <input type="text" name="cnpj_cpf" id="cnpj_cpf" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="00.000.000/0000-00" required>
                                    <button type="button" id="consultarCnpjBtn" onclick="consultarCNPJ()" class="px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="fas fa-search" id="cnpj-search-icon"></i>
                                        <i class="fas fa-spinner fa-spin hidden" id="cnpj-loading-icon"></i>
                                    </button>
                                </div>
                                <div id="cnpj-status" class="mt-2 text-sm hidden"></div>
                                <p class="mt-1 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Digite um CNPJ válido e os dados serão preenchidos automaticamente
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Razão Social *</label>
                                <input type="text" name="razao_social" id="razao_social" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Nome da empresa" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nome Fantasia</label>
                                <input type="text" name="nome_fantasia" id="nome_fantasia" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Nome comercial">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">CEP *</label>
                                <input type="text" name="cep" id="cep" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="00000-000" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Endereço *</label>
                                <input type="text" name="endereco" id="endereco" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Rua, número, complemento" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bairro *</label>
                                <input type="text" name="bairro" id="bairro" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Nome do bairro" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cidade *</label>
                                <input type="text" name="cidade" id="cidade" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Nome da cidade" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                                <select name="estado" id="estado" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    <option value="">Selecione o estado</option>
                                    <option value="AC">Acre</option>
                                    <option value="AL">Alagoas</option>
                                    <option value="AP">Amapá</option>
                                    <option value="AM">Amazonas</option>
                                    <option value="BA">Bahia</option>
                                    <option value="CE">Ceará</option>
                                    <option value="DF">Distrito Federal</option>
                                    <option value="ES">Espírito Santo</option>
                                    <option value="GO">Goiás</option>
                                    <option value="MA">Maranhão</option>
                                    <option value="MT">Mato Grosso</option>
                                    <option value="MS">Mato Grosso do Sul</option>
                                    <option value="MG">Minas Gerais</option>
                                    <option value="PA">Pará</option>
                                    <option value="PB">Paraíba</option>
                                    <option value="PR">Paraná</option>
                                    <option value="PE">Pernambuco</option>
                                    <option value="PI">Piauí</option>
                                    <option value="RJ">Rio de Janeiro</option>
                                    <option value="RN">Rio Grande do Norte</option>
                                    <option value="RS">Rio Grande do Sul</option>
                                    <option value="RO">Rondônia</option>
                                    <option value="RR">Roraima</option>
                                    <option value="SC">Santa Catarina</option>
                                    <option value="SP">São Paulo</option>
                                    <option value="SE">Sergipe</option>
                                    <option value="TO">Tocantins</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
                                <input type="email" name="email" id="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="email@exemplo.com">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                                <input type="text" name="telefone" id="telefone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="(11) 99999-9999">
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Documentos -->
                    <div id="step2" class="step-content hidden">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Upload de Documentos</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="document-upload">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cartão CNPJ *</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors">
                                    <input type="file" name="cartao_cnpj" id="cartao_cnpj" class="hidden" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <label for="cartao_cnpj" class="cursor-pointer">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                        <p style="color: var(--secondary-color);">Clique para fazer upload</p>
                                        <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (máx. 2MB)</p>
                                    </label>
                                </div>
                                <div id="cartao_cnpj-preview" class="mt-2 hidden">
                                    <p class="text-sm " style="color: var(--accent-color);""><i class="fas fa-check mr-1"></i>Arquivo selecionado</p>
                                </div>
                            </div>

                            <div class="document-upload">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contrato Social *</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors">
                                    <input type="file" name="contrato_social" id="contrato_social" class="hidden" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <label for="contrato_social" class="cursor-pointer">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                        <p style="color: var(--secondary-color);">Clique para fazer upload</p>
                                        <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (máx. 2MB)</p>
                                    </label>
                                </div>
                                <div id="contrato_social-preview" class="mt-2 hidden">
                                    <p class="text-sm " style="color: var(--accent-color);""><i class="fas fa-check mr-1"></i>Arquivo selecionado</p>
                                </div>
                            </div>

                            <div class="document-upload">
                                <label class="block text-sm font-medium text-gray-700 mb-2">RG ou CNH *</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors">
                                    <input type="file" name="rg_cnh" id="rg_cnh" class="hidden" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <label for="rg_cnh" class="cursor-pointer">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                        <p style="color: var(--secondary-color);">Clique para fazer upload</p>
                                        <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (máx. 2MB)</p>
                                    </label>
                                </div>
                                <div id="rg_cnh-preview" class="mt-2 hidden">
                                    <p class="text-sm " style="color: var(--accent-color);""><i class="fas fa-check mr-1"></i>Arquivo selecionado</p>
                                </div>
                            </div>

                            <div class="document-upload">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Comprovante de Residência *</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors">
                                    <input type="file" name="comprovante_residencia" id="comprovante_residencia" class="hidden" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <label for="comprovante_residencia" class="cursor-pointer">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                        <p style="color: var(--secondary-color);">Clique para fazer upload</p>
                                        <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (máx. 2MB)</p>
                                    </label>
                                </div>
                                <div id="comprovante_residencia-preview" class="mt-2 hidden">
                                    <p class="text-sm " style="color: var(--accent-color);""><i class="fas fa-check mr-1"></i>Arquivo selecionado</p>
                                </div>
                            </div>

                            <div class="document-upload md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Comprovante de Atividade *</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors">
                                    <input type="file" name="comprovante_atividade" id="comprovante_atividade" class="hidden" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <label for="comprovante_atividade" class="cursor-pointer">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                        <p style="color: var(--secondary-color);">Clique para fazer upload</p>
                                        <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (máx. 2MB)</p>
                                    </label>
                                </div>
                                <div id="comprovante_atividade-preview" class="mt-2 hidden">
                                    <p class="text-sm " style="color: var(--accent-color);""><i class="fas fa-check mr-1"></i>Arquivo selecionado</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Operações -->
                    <div id="step3" class="step-content hidden">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Seleção de Operações</h3>
                        <p style="color: var(--secondary-color);">Selecione as operações que o licenciado poderá realizar:</p>
                        
                        <div id="operacoes-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Operações serão carregadas via JavaScript -->
                        </div>
                    </div>

                    <!-- Step 4: Finalização -->
                    <div id="step4" class="step-content hidden">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-check text-3xl " style="color: var(--accent-color);""></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Revisar Dados</h3>
                            <p style="color: var(--secondary-color);">Confira as informações antes de finalizar o cadastro</p>
                            
                            <div id="resumo-dados" class="bg-gray-50 rounded-lg p-6 text-left">
                                <!-- Resumo será preenchido via JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer do Modal -->
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-between">
                    <button type="button" id="prevBtn" onclick="changeStep(-1)" class="px-6 py-2 text-gray-600 hover:text-gray-800 transition-colors hidden">
                        <i class="fas fa-arrow-left mr-2"></i>Voltar
                    </button>
                    <div class="flex-1"></div>
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeLicenciadoModal()" class="px-6 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                            Cancelar
                        </button>
                        <button type="button" id="nextBtn" onclick="changeStep(1)" class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                            Próximo<i class="fas fa-arrow-right ml-2"></i>
                        </button>
                        <button type="submit" id="submitBtn" class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors hidden disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-save mr-2" id="submitIcon"></i>
                            <span id="submitText">Finalizar Cadastro</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Visualização de Licenciado -->
    <div id="viewLicenciadoModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Detalhes do Licenciado</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                <div id="licenciadoDetails">
                    <!-- Conteúdo será carregado via JavaScript -->
                    <div class="flex items-center justify-center py-12">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                        <span class="ml-3 text-gray-600">Carregando detalhes...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="deleteConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-gray-900">Confirmar Exclusão</h3>
                    </div>
                </div>
                
                <div class="mb-6">
                    <p style="color: var(--secondary-color);">
                        Tem certeza que deseja excluir o licenciado <strong id="deleteLicenciadoName"></strong>?
                    </p>
                    <p class="text-xs text-red-600 mt-2">
                        ⚠️ Esta ação não pode ser desfeita. Todos os dados relacionados serão perdidos.
                    </p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button onclick="closeDeleteModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        Cancelar
                    </button>
                    <button id="confirmDeleteBtn" onclick="confirmDelete()" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                        <i class="fas fa-trash mr-1"></i>
                        Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Aprovação -->
    <div id="approvalConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center p-4" 
         role="dialog" aria-labelledby="approval-title" aria-describedby="approval-description" aria-modal="true">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 ease-out scale-100 opacity-100">
            <!-- Gradiente suave no topo -->
            <div class="h-2 bg-gradient-to-r from-green-400 via-green-500 to-emerald-500 rounded-t-2xl"></div>
            
            <div class="p-8 text-center">
                <!-- Ícone de sucesso animado -->
                <div class="mb-6">
                    <div class="mx-auto w-20 h-20 bg-green-100 rounded-full flex items-center justify-center relative">
                        <div class="absolute inset-0 bg-green-100 rounded-full animate-ping opacity-20"></div>
                        <svg class="w-10 h-10 " style="color: var(--accent-color);" animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Título e descrição -->
                <h2 id="approval-title" class="text-2xl font-bold text-gray-900 mb-2">
                    Cadastro aprovado!
                </h2>
                <p style="color: var(--secondary-color);">
                    O licenciado <span id="approval-licenciado-name" class="font-semibold text-gray-900">[Nome do Licenciado]</span><br>
                    <span class="text-sm text-gray-500">ID: <span id="approval-licenciado-id" class="font-mono">#000</span></span>
                </p>

                <!-- Botões -->
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <button id="btn-ver-detalhes" 
                            class="px-6 py-3 btn-success font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-200"
                            aria-describedby="ver-detalhes-desc">
                        <i class="fas fa-eye mr-2"></i>
                        Ver detalhes
                    </button>
                    <button id="btn-concluir" 
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-gray-200"
                            aria-describedby="concluir-desc">
                        <i class="fas fa-check mr-2"></i>
                        Concluir
                    </button>
                </div>

                <!-- Descrições para acessibilidade -->
                <div class="sr-only">
                    <div id="ver-detalhes-desc">Abrir página de detalhes do licenciado</div>
                    <div id="concluir-desc">Fechar esta confirmação e voltar à lista</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast de Sucesso -->
    <div id="successToast" class="fixed bottom-4 left-1/2 transform -translate-x-1/2 translate-y-full opacity-0 transition-all duration-500 ease-out z-50">
        <div class="bg-green-500 text-white px-6 py-4 rounded-xl shadow-lg flex items-center space-x-3 min-w-80">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div class="flex-1">
                <p class="font-semibold">Sucesso!</p>
                <p class="text-sm opacity-90">Cadastro aprovado com sucesso</p>
            </div>
            <button onclick="hideToast()" class="flex-shrink-0 text-white hover:text-gray-200 transition-colors" aria-label="Fechar notificação">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Modal de Confirmação de Fechamento -->
    <div id="closeConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center p-4" 
         role="dialog" aria-labelledby="close-title" aria-describedby="close-description" aria-modal="true">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 ease-out">
            <!-- Gradiente suave no topo -->
            <div class="h-2 bg-gradient-to-r from-orange-400 via-orange-500 to-red-500 rounded-t-2xl"></div>
            
            <div class="p-8 text-center">
                <!-- Ícone de aviso -->
                <div class="mb-6">
                    <div class="mx-auto w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center relative">
                        <div class="absolute inset-0 bg-orange-100 rounded-full animate-pulse opacity-30"></div>
                        <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Título e descrição -->
                <h2 id="close-title" class="text-2xl font-bold text-gray-900 mb-2">
                    Deseja realmente sair?
                </h2>
                <p style="color: var(--secondary-color);">
                    Todos os dados preenchidos serão perdidos e não poderão ser recuperados.
                </p>

                <!-- Botões -->
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <button id="btn-cancel-close" 
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-gray-200"
                            aria-describedby="cancel-close-desc">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </button>
                    <button id="btn-confirm-close" 
                            class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-red-200"
                            aria-describedby="confirm-close-desc">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Sim, sair
                    </button>
                </div>

                <!-- Descrições para acessibilidade -->
                <div class="sr-only">
                    <div id="cancel-close-desc">Cancelar e continuar preenchendo o formulário</div>
                    <div id="confirm-close-desc">Confirmar saída e perder todos os dados</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Alteração de Status -->
    <div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none !important;">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="statusModalContent">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-cogs text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Alterar Status</h3>
                            <p style="color: var(--secondary-color);">Selecione o novo status do licenciado</p>
                        </div>
                    </div>
                    <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Licenciado Info -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-building " style="color: var(--primary-color);""></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900" id="statusModalLicenciadoName">Nome do Licenciado</h4>
                            <p style="color: var(--secondary-color);">Status atual: <span id="statusModalCurrentStatus" class="font-medium">Em Análise</span></p>
                        </div>
                    </div>
                </div>

                <!-- Status Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Novo Status</label>
                    <select id="statusSelect" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <option value="">Selecione um status</option>
                        <option value="aprovado">✅ Aprovado</option>
                        <option value="recusado">❌ Recusado</option>
                        <option value="em_analise">🔄 Em Análise</option>
                        <option value="risco">⚠️ Risco</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex space-x-3">
                    <button onclick="closeStatusModal()" 
                            class="flex-1 px-4 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                        Cancelar
                    </button>
                    <button onclick="confirmStatusChange()" 
                            id="confirmStatusBtn"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white rounded-lg font-medium transition-all transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i>
                        Alterar Status
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Aviso - Status Igual -->
    <div id="statusWarningModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none !important;">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="statusWarningModalContent">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-center justify-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                    </div>
                </div>

                <!-- Content -->
                <div class="text-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Status Igual ao Atual</h3>
                    <p style="color: var(--secondary-color);">
                        O status selecionado é o mesmo do atual. 
                        <br>
                        <span class="font-medium text-amber-600">Nenhuma alteração será feita.</span>
                    </p>
                    
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fas fa-info-circle text-amber-600"></i>
                            <span class="text-sm text-amber-700">
                                Selecione um status diferente para alterar
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-center">
                    <button onclick="closeStatusWarningModal()" 
                            class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-lg font-medium transition-all transform hover:scale-105">
                        <i class="fas fa-check mr-2"></i>
                        Entendi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Função para toggle dos filtros
        function toggleFilters() {
            const content = document.getElementById('filters-content');
            const chevron = document.getElementById('filter-chevron');
            
            if (content.style.display === 'none') {
                content.style.display = 'block';
                chevron.style.transform = 'rotate(180deg)';
            } else {
                content.style.display = 'none';
                chevron.style.transform = 'rotate(0deg)';
            }
        }

        // Auto-submit do formulário de filtros quando campos mudam
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filters-form');
            const selects = form.querySelectorAll('select');
            
            selects.forEach(select => {
                select.addEventListener('change', function() {
                    // Auto-submit apenas para selects (não para inputs de texto)
                    if (this.tagName === 'SELECT') {
                        form.submit();
                    }
                });
            });
        });

        let currentStep = 1;
        const totalSteps = 4;
        let operacoes = [];

        // Carregar operações do banco
        async function loadOperacoes() {
            try {
                const response = await fetch('/api/operacoes');
                operacoes = await response.json();
                renderOperacoes();
            } catch (error) {
                console.error('Erro ao carregar operações:', error);
            }
        }

        function renderOperacoes() {
            const container = document.getElementById('operacoes-container');
            container.innerHTML = '';
            
            operacoes.forEach(operacao => {
                const div = document.createElement('div');
                div.className = 'border border-gray-200 rounded-lg p-4 hover:border-blue-500 transition-colors cursor-pointer operacao-item';
                div.innerHTML = `
                    <div class="flex items-center">
                        <input type="checkbox" id="operacao_${operacao.id}" value="${operacao.id}" class="mr-3 operacao-checkbox">
                        <label for="operacao_${operacao.id}" class="flex-1 cursor-pointer">
                            <div class="font-medium text-gray-800">${operacao.nome}</div>
                            <div class="text-sm text-gray-500">${operacao.adquirente || 'N/A'}</div>
                        </label>
                    </div>
                `;
                container.appendChild(div);
            });
        }

        function openLicenciadoModal() {
            document.getElementById('licenciadoModal').classList.add('show');
            loadOperacoes();
            
            // Adicionar listener para ESC (opcional - pode remover se quiser que ESC feche)
            document.addEventListener('keydown', handleEscapeKey);
        }

        function closeLicenciadoModal() {
            // Verificar se há dados preenchidos
            const form = document.getElementById('licenciadoForm');
            const formData = new FormData(form);
            let hasData = false;
            
            // Verificar se algum campo obrigatório tem dados
            const requiredFields = ['cnpj_cpf', 'razao_social', 'endereco', 'bairro', 'cidade', 'estado', 'cep'];
            for (let field of requiredFields) {
                if (formData.get(field) && formData.get(field).trim() !== '') {
                    hasData = true;
                    break;
                }
            }
            
            // Se há dados, mostrar modal de confirmação elegante
            if (hasData) {
                showCloseConfirmModal();
                return;
            }
            
            document.getElementById('licenciadoModal').classList.remove('show');
            resetForm();
            
            // Remover listener do ESC
            document.removeEventListener('keydown', handleEscapeKey);
        }
        
        function handleEscapeKey(event) {
            // Modal não fecha com ESC - comentado para desabilitar
            // if (event.key === 'Escape') {
            //     closeLicenciadoModal();
            // }
        }
        
        function handleModalClick(event) {
            // Só fechar se clicar no backdrop (fora do modal)
            // Não fechar se clicar no conteúdo do modal
            if (event.target === event.currentTarget) {
                // Modal não fecha ao clicar fora - comentado para desabilitar
                // closeLicenciadoModal();
            }
        }

        function changeStep(direction) {
            if (direction === 1 && currentStep < totalSteps) {
                if (validateCurrentStep()) {
                    currentStep++;
                    updateStepDisplay();
                }
            } else if (direction === -1 && currentStep > 1) {
                currentStep--;
                updateStepDisplay();
            }
        }

        function validateCurrentStep() {
            const currentStepElement = document.getElementById(`step${currentStep}`);
            const requiredFields = currentStepElement.querySelectorAll('[required]');
            
            for (let field of requiredFields) {
                if (!field.value.trim()) {
                    field.focus();
                    alert('Por favor, preencha todos os campos obrigatórios.');
                    return false;
                }
            }
            
            return true;
        }

        function updateStepDisplay() {
            // Esconder todos os steps
            for (let i = 1; i <= totalSteps; i++) {
                document.getElementById(`step${i}`).classList.add('hidden');
                document.getElementById(`step${i}-indicator`).classList.remove('active', 'completed');
            }
            
            // Mostrar step atual
            document.getElementById(`step${currentStep}`).classList.remove('hidden');
            document.getElementById(`step${currentStep}-indicator`).classList.add('active');
            
            // Marcar steps anteriores como completos
            for (let i = 1; i < currentStep; i++) {
                document.getElementById(`step${i}-indicator`).classList.add('completed');
            }
            
            // Atualizar botões
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            
            prevBtn.classList.toggle('hidden', currentStep === 1);
            nextBtn.classList.toggle('hidden', currentStep === totalSteps);
            submitBtn.classList.toggle('hidden', currentStep !== totalSteps);
            
            // Atualizar resumo no step 4
            if (currentStep === 4) {
                updateResumo();
            }
        }

        function updateResumo() {
            const formData = new FormData(document.getElementById('licenciadoForm'));
            const resumo = document.getElementById('resumo-dados');
            
            const operacoesSelecionadas = Array.from(document.querySelectorAll('.operacao-checkbox:checked'))
                .map(cb => operacoes.find(op => op.id == cb.value)?.nome)
                .filter(Boolean);
            
            resumo.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Dados Principais</h4>
                        <p><strong>CNPJ/CPF:</strong> ${formData.get('cnpj_cpf')}</p>
                        <p><strong>Razão Social:</strong> ${formData.get('razao_social')}</p>
                        <p><strong>Nome Fantasia:</strong> ${formData.get('nome_fantasia') || 'Não informado'}</p>
                        <p><strong>Endereço:</strong> ${formData.get('endereco')}, ${formData.get('bairro')}</p>
                        <p><strong>Cidade/UF:</strong> ${formData.get('cidade')}/${formData.get('estado')}</p>
                        <p><strong>CEP:</strong> ${formData.get('cep')}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Contato</h4>
                        <p><strong>E-mail:</strong> ${formData.get('email') || 'Não informado'}</p>
                        <p><strong>Telefone:</strong> ${formData.get('telefone') || 'Não informado'}</p>
                        
                        <h4 class="font-semibold text-gray-800 mb-2 mt-4">Operações Selecionadas</h4>
                        <p>${operacoesSelecionadas.length > 0 ? operacoesSelecionadas.join(', ') : 'Nenhuma operação selecionada'}</p>
                    </div>
                </div>
            `;
        }

        function resetForm() {
            document.getElementById('licenciadoForm').reset();
            currentStep = 1;
            updateStepDisplay();
        }

        // Função para consultar CNPJ
        async function consultarCNPJ() {
            const cnpjInput = document.getElementById('cnpj_cpf');
            const cnpj = cnpjInput.value.replace(/\D/g, '');
            const statusDiv = document.getElementById('cnpj-status');
            const searchIcon = document.getElementById('cnpj-search-icon');
            const loadingIcon = document.getElementById('cnpj-loading-icon');
            const consultarBtn = document.getElementById('consultarCnpjBtn');
            
            // Validar se o CNPJ tem 11 ou 14 dígitos
            if (cnpj.length !== 11 && cnpj.length !== 14) {
                showCnpjStatus('CNPJ/CPF deve ter 11 ou 14 dígitos', 'error');
                return;
            }
            
            // Mostrar loading
            consultarBtn.disabled = true;
            searchIcon.classList.add('hidden');
            loadingIcon.classList.remove('hidden');
            statusDiv.classList.remove('hidden');
            statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Consultando dados...';
            statusDiv.className = 'mt-2 text-sm " style="color: var(--primary-color);"';
            
            try {
                const response = await fetch('/api/consultar-cnpj', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ cnpj: cnpj })
                });
                
                const data = await response.json();
                
                if (response.ok && !data.error) {
                    // Preencher campos automaticamente
                    preencherCamposComDadosCNPJ(data);
                    showCnpjStatus('Dados carregados com sucesso!', 'success');
                } else {
                    showCnpjStatus(data.error || 'Erro ao consultar CNPJ', 'error');
                }
            } catch (error) {
                console.error('Erro na consulta:', error);
                showCnpjStatus('Erro de conexão. Tente novamente.', 'error');
            } finally {
                // Restaurar botão
                consultarBtn.disabled = false;
                searchIcon.classList.remove('hidden');
                loadingIcon.classList.add('hidden');
            }
        }
        
        function preencherCamposComDadosCNPJ(data) {
            const camposPreenchidos = [];
            
            // Preencher campos com os dados retornados
            if (data.nome) {
                document.getElementById('razao_social').value = data.nome;
                camposPreenchidos.push('Razão Social');
            }
            if (data.fantasia) {
                document.getElementById('nome_fantasia').value = data.fantasia;
                camposPreenchidos.push('Nome Fantasia');
            }
            if (data.cep) {
                document.getElementById('cep').value = data.cep;
                camposPreenchidos.push('CEP');
            }
            if (data.logradouro) {
                let endereco = data.logradouro;
                if (data.numero) endereco += ', ' + data.numero;
                if (data.complemento) endereco += ', ' + data.complemento;
                document.getElementById('endereco').value = endereco;
                camposPreenchidos.push('Endereço');
            }
            if (data.bairro) {
                document.getElementById('bairro').value = data.bairro;
                camposPreenchidos.push('Bairro');
            }
            if (data.municipio) {
                document.getElementById('cidade').value = data.municipio;
                camposPreenchidos.push('Cidade');
            }
            if (data.uf) {
                document.getElementById('estado').value = data.uf;
                camposPreenchidos.push('Estado');
            }
            if (data.email) {
                document.getElementById('email').value = data.email;
                camposPreenchidos.push('E-mail');
            }
            if (data.telefone) {
                document.getElementById('telefone').value = data.telefone;
                camposPreenchidos.push('Telefone');
            }
            
            // Adicionar efeito visual nos campos preenchidos
            camposPreenchidos.forEach(campo => {
                const fieldId = campo.toLowerCase().replace(/\s+/g, '_').replace('ç', 'c').replace('ã', 'a');
                const field = document.getElementById(fieldId);
                if (field) {
                    field.classList.add('bg-green-50', 'border-green-300');
                    setTimeout(() => {
                        field.classList.remove('bg-green-50', 'border-green-300');
                    }, 3000);
                }
            });
            
            // Mostrar resumo dos campos preenchidos
            if (camposPreenchidos.length > 0) {
                showCnpjStatus(`${camposPreenchidos.length} campos preenchidos automaticamente: ${camposPreenchidos.join(', ')}`, 'success');
            }
        }
        
        function showCnpjStatus(message, type) {
            const statusDiv = document.getElementById('cnpj-status');
            statusDiv.classList.remove('hidden');
            
            let iconClass = '';
            let textClass = '';
            
            switch (type) {
                case 'success':
                    iconClass = 'fas fa-check-circle " style="color: var(--accent-color);"';
                    textClass = '" style="color: var(--accent-color);"';
                    break;
                case 'error':
                    iconClass = 'fas fa-exclamation-circle text-red-600';
                    textClass = 'text-red-600';
                    break;
                case 'warning':
                    iconClass = 'fas fa-exclamation-triangle text-yellow-600';
                    textClass = 'text-yellow-600';
                    break;
                default:
                    iconClass = 'fas fa-info-circle " style="color: var(--primary-color);"';
                    textClass = '" style="color: var(--primary-color);"';
            }
            
            statusDiv.innerHTML = `<i class="${iconClass} mr-2"></i>${message}`;
            statusDiv.className = `mt-2 text-sm ${textClass}`;
            
            // Esconder mensagem após 5 segundos se for sucesso
            if (type === 'success') {
                setTimeout(() => {
                    statusDiv.classList.add('hidden');
                }, 5000);
            }
        }

        // Máscaras de input
        document.addEventListener('DOMContentLoaded', function() {
            // Máscara CNPJ/CPF
            const cnpjCpfInput = document.getElementById('cnpj_cpf');
            cnpjCpfInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                } else {
                    value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
                }
                e.target.value = value;
                
                // Limpar status quando o usuário digitar
                const statusDiv = document.getElementById('cnpj-status');
                if (statusDiv && !statusDiv.classList.contains('hidden')) {
                    statusDiv.classList.add('hidden');
                }
            });
            
            // Consultar CNPJ automaticamente quando o usuário terminar de digitar (apenas para CNPJ)
            let timeoutId;
            cnpjCpfInput.addEventListener('input', function(e) {
                clearTimeout(timeoutId);
                const cnpj = e.target.value.replace(/\D/g, '');
                
                // Só consultar automaticamente se for CNPJ (14 dígitos) e estiver completo
                if (cnpj.length === 14) {
                    timeoutId = setTimeout(() => {
                        consultarCNPJ();
                    }, 1000); // Aguardar 1 segundo após parar de digitar
                }
            });

            // Máscara CEP
            const cepInput = document.getElementById('cep');
            cepInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
                e.target.value = value;
            });

            // Máscara Telefone
            const telefoneInput = document.getElementById('telefone');
            telefoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 10) {
                    value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                } else {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                }
                e.target.value = value;
            });

            // Preview de arquivos
            const fileInputs = document.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => {
                input.addEventListener('change', function(e) {
                    const preview = document.getElementById(`${this.name}-preview`);
                    if (e.target.files.length > 0) {
                        preview.classList.remove('hidden');
                    } else {
                        preview.classList.add('hidden');
                    }
                });
            });
        });

        // Submit do formulário
        document.getElementById('licenciadoForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Esconder erros anteriores
            hideValidationErrors();
            
            // Ativar estado de loading
            setSubmitButtonLoading(true);
            
            const formData = new FormData(this);
            const operacoesSelecionadas = Array.from(document.querySelectorAll('.operacao-checkbox:checked'))
                .map(cb => cb.value);
            
            formData.set('operacoes', JSON.stringify(operacoesSelecionadas));
            
            try {
                const response = await fetch('/licenciados', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Sucesso - mostrar feedback visual
                    showSuccessMessage();
                    setTimeout(() => {
                        closeLicenciadoModal();
                        location.reload();
                    }, 2000);
                } else {
                    // Mostrar erros de validação de forma bonita
                    showValidationErrors(result.errors || {});
                }
            } catch (error) {
                console.error('Erro:', error);
                showValidationErrors({ 
                    'erro_conexao': ['Erro de conexão. Tente novamente.'] 
                });
            } finally {
                // Desativar estado de loading
                setSubmitButtonLoading(false);
            }
        });

        // Funções para gerenciar estados do botão e erros
        function setSubmitButtonLoading(loading) {
            const submitBtn = document.getElementById('submitBtn');
            const submitIcon = document.getElementById('submitIcon');
            const submitText = document.getElementById('submitText');
            
            if (loading) {
                submitBtn.disabled = true;
                submitIcon.className = 'fas fa-spinner fa-spin mr-2';
                submitText.textContent = 'Salvando...';
            } else {
                submitBtn.disabled = false;
                submitIcon.className = 'fas fa-save mr-2';
                submitText.textContent = 'Finalizar Cadastro';
            }
        }

        function showValidationErrors(errors) {
            const errorContainer = document.getElementById('validationErrors');
            const errorList = document.getElementById('errorList');
            
            if (Object.keys(errors).length === 0) {
                hideValidationErrors();
                return;
            }
            
            // Mapear nomes de campos para nomes amigáveis
            const fieldNames = {
                'cnpj_cpf': 'CNPJ/CPF',
                'razao_social': 'Razão Social',
                'nome_fantasia': 'Nome Fantasia',
                'endereco': 'Endereço',
                'bairro': 'Bairro',
                'cidade': 'Cidade',
                'estado': 'Estado',
                'cep': 'CEP',
                'email': 'E-mail',
                'telefone': 'Telefone',
                'cartao_cnpj': 'Cartão CNPJ',
                'contrato_social': 'Contrato Social',
                'rg_cnh': 'RG/CNH',
                'comprovante_residencia': 'Comprovante de Residência',
                'comprovante_atividade': 'Comprovante de Atividade',
                'operacoes': 'Operações',
                'erro_conexao': 'Erro de Conexão'
            };
            
            let errorHtml = '<ul class="list-disc list-inside space-y-1">';
            
            Object.keys(errors).forEach(field => {
                const fieldName = fieldNames[field] || field;
                errors[field].forEach(error => {
                    errorHtml += `<li><strong>${fieldName}:</strong> ${error}</li>`;
                });
            });
            
            errorHtml += '</ul>';
            errorList.innerHTML = errorHtml;
            errorContainer.classList.remove('hidden');
            
            // Scroll para o topo do modal para mostrar os erros
            errorContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function hideValidationErrors() {
            const errorContainer = document.getElementById('validationErrors');
            errorContainer.classList.add('hidden');
        }

        function showSuccessMessage() {
            // Criar overlay de sucesso
            const successOverlay = document.createElement('div');
            successOverlay.className = 'fixed inset-0 bg-green-500 bg-opacity-90 z-50 flex items-center justify-center';
            successOverlay.innerHTML = `
                <div class="bg-white rounded-lg p-8 text-center shadow-2xl">
                    <div class="text-green-500 text-6xl mb-4">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Sucesso!</h3>
                    <p style="color: var(--secondary-color);">Licenciado cadastrado com sucesso!</p>
                    <div class="mt-4">
                        <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-green-500"></div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(successOverlay);
            
            // Remover após 2 segundos
            setTimeout(() => {
                document.body.removeChild(successOverlay);
            }, 2000);
        }

        // Validação em tempo real dos campos
        function validateField(field, fieldName) {
            const value = field.value.trim();
            const fieldContainer = field.closest('.grid').querySelector(`[for="${field.id}"]`) || field.parentElement;
            
            // Remover classes de erro anteriores
            field.classList.remove('border-red-500', 'border-green-500');
            fieldContainer.classList.remove('text-red-600', '" style="color: var(--accent-color);"');
            
            // Remover mensagens de erro anteriores
            const existingError = fieldContainer.querySelector('.field-error');
            if (existingError) {
                existingError.remove();
            }
            
            if (!value) {
                return; // Campo vazio, não mostrar erro ainda
            }
            
            let isValid = true;
            let errorMessage = '';
            
            switch (fieldName) {
                case 'cnpj_cpf':
                    const cleanCnpj = value.replace(/\D/g, '');
                    if (cleanCnpj.length !== 11 && cleanCnpj.length !== 14) {
                        isValid = false;
                        errorMessage = 'CNPJ deve ter 14 dígitos ou CPF deve ter 11 dígitos';
                    }
                    break;
                case 'email':
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        isValid = false;
                        errorMessage = 'E-mail inválido';
                    }
                    break;
                case 'cep':
                    const cleanCep = value.replace(/\D/g, '');
                    if (cleanCep.length !== 8) {
                        isValid = false;
                        errorMessage = 'CEP deve ter 8 dígitos';
                    }
                    break;
                case 'telefone':
                    const cleanPhone = value.replace(/\D/g, '');
                    if (cleanPhone.length < 10 || cleanPhone.length > 11) {
                        isValid = false;
                        errorMessage = 'Telefone deve ter 10 ou 11 dígitos';
                    }
                    break;
            }
            
            if (!isValid) {
                field.classList.add('border-red-500');
                fieldContainer.classList.add('text-red-600');
                
                const errorDiv = document.createElement('div');
                errorDiv.className = 'field-error text-xs text-red-600 mt-1';
                errorDiv.textContent = errorMessage;
                fieldContainer.appendChild(errorDiv);
            } else {
                field.classList.add('border-green-500');
                fieldContainer.classList.add('" style="color: var(--accent-color);"');
            }
        }

        // Adicionar validação em tempo real aos campos
        document.addEventListener('DOMContentLoaded', function() {
            const fieldsToValidate = ['cnpj_cpf', 'email', 'cep', 'telefone'];
            
            fieldsToValidate.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field) {
                    field.addEventListener('blur', function() {
                        validateField(this, fieldName);
                    });
                }
            });
        });

        // Variáveis globais para ações
        let licenciadoToDelete = null;
        let licenciadoToApprove = null;
        let focusableElements = [];
        let currentFocusIndex = 0;

        // Garantir que os modais estejam fechados ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteConfirmModal');
            const viewModal = document.getElementById('viewLicenciadoModal');
            const approvalModal = document.getElementById('approvalConfirmModal');
            const closeModal = document.getElementById('closeConfirmModal');
            
            if (deleteModal) {
                deleteModal.classList.remove('show');
            }
            if (viewModal) {
                viewModal.classList.remove('show');
            }
            if (approvalModal) {
                approvalModal.classList.remove('show');
            }
            if (closeModal) {
                closeModal.classList.remove('show');
            }
        });

        // Função para visualizar licenciado
        async function viewLicenciado(id) {
            const modal = document.getElementById('viewLicenciadoModal');
            const detailsContainer = document.getElementById('licenciadoDetails');
            
            // Mostrar modal com loading
            modal.classList.add('show');
            detailsContainer.innerHTML = `
                <div class="flex items-center justify-center py-12">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                    <span class="ml-3 text-gray-600">Carregando detalhes...</span>
                </div>
            `;
            
            try {
                const response = await fetch(`/dashboard/licenciados/${id}/detalhes`);
                const licenciado = await response.json();
                
                if (response.ok) {
                    detailsContainer.innerHTML = generateLicenciadoDetailsHTML(licenciado);
                } else {
                    detailsContainer.innerHTML = `
                        <div class="text-center py-12">
                            <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                            <p class="text-red-600">Erro ao carregar detalhes do licenciado</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Erro:', error);
                detailsContainer.innerHTML = `
                    <div class="text-center py-12">
                        <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                        <p class="text-red-600">Erro de conexão. Tente novamente.</p>
                    </div>
                `;
            }
        }

        // Função para gerar HTML dos detalhes do licenciado
        function generateLicenciadoDetailsHTML(licenciado) {
            const statusBadge = getStatusBadge(licenciado.status);
            const operacoesList = licenciado.operacoes && licenciado.operacoes.length > 0 
                ? licenciado.operacoes.map(op => `<span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-1 mb-1">${op}</span>`).join('')
                : '<span class="text-gray-500">Nenhuma operação selecionada</span>';

            return `
                <div class="space-y-6">
                    <!-- Header com Status -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="text-xl font-semibold text-gray-900">${licenciado.razao_social}</h4>
                            <p style="color: var(--secondary-color);">${licenciado.nome_fantasia || 'Sem nome fantasia'}</p>
                        </div>
                        <div class="text-right">
                            ${statusBadge}
                            <p class="text-sm text-gray-500 mt-1">Cadastrado em ${new Date(licenciado.created_at).toLocaleDateString('pt-BR')}</p>
                        </div>
                    </div>

                    <!-- Dados Principais -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-building text-blue-500 mr-2"></i>
                                Dados da Empresa
                            </h5>
                            <div class="space-y-2 text-sm">
                                <div><strong>CNPJ/CPF:</strong> ${licenciado.cnpj_cpf_formatado}</div>
                                <div><strong>Razão Social:</strong> ${licenciado.razao_social}</div>
                                <div><strong>Nome Fantasia:</strong> ${licenciado.nome_fantasia || 'Não informado'}</div>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>
                                Endereço
                            </h5>
                            <div class="space-y-2 text-sm">
                                <div><strong>CEP:</strong> ${licenciado.cep_formatado}</div>
                                <div><strong>Endereço:</strong> ${licenciado.endereco}</div>
                                <div><strong>Bairro:</strong> ${licenciado.bairro}</div>
                                <div><strong>Cidade:</strong> ${licenciado.cidade} - ${licenciado.estado}</div>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-phone text-purple-500 mr-2"></i>
                                Contato
                            </h5>
                            <div class="space-y-2 text-sm">
                                <div><strong>E-mail:</strong> ${licenciado.email || 'Não informado'}</div>
                                <div><strong>Telefone:</strong> ${licenciado.telefone_formatado || 'Não informado'}</div>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-cogs text-orange-500 mr-2"></i>
                                Operações
                            </h5>
                            <div class="text-sm">
                                ${operacoesList}
                            </div>
                        </div>
                    </div>

                    <!-- Documentos -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-file-alt text-indigo-500 mr-2"></i>
                            Documentos
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            ${generateDocumentLinks(licenciado)}
                        </div>
                    </div>

                    ${licenciado.observacoes ? `
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-sticky-note text-yellow-500 mr-2"></i>
                            Observações
                        </h5>
                        <p class="text-sm text-gray-700">${licenciado.observacoes}</p>
                    </div>
                    ` : ''}
                </div>
            `;
        }

        // Função para gerar links de documentos
        function generateDocumentLinks(licenciado) {
            const documents = [
                { name: 'Cartão CNPJ', path: licenciado.cartao_cnpj_path, key: 'cartao_cnpj' },
                { name: 'Contrato Social', path: licenciado.contrato_social_path, key: 'contrato_social' },
                { name: 'RG/CNH', path: licenciado.rg_cnh_path, key: 'rg_cnh' },
                { name: 'Comprovante Residência', path: licenciado.comprovante_residencia_path, key: 'comprovante_residencia' },
                { name: 'Comprovante Atividade', path: licenciado.comprovante_atividade_path, key: 'comprovante_atividade' }
            ];

            return documents.map(doc => {
                if (doc.path) {
                    return `
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                <span class="text-sm font-medium text-gray-700">${doc.name}</span>
                            </div>
                            <a href="/dashboard/licenciados/${licenciado.id}/download/${doc.key}" 
                               class="" style="color: var(--primary-color);" hover:text-blue-800 text-sm">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    `;
                } else {
                    return `
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-file-slash text-gray-400 mr-2"></i>
                            <span class="text-sm text-gray-500">${doc.name} - Não enviado</span>
                        </div>
                    `;
                }
            }).join('');
        }

        // Função para gerar badge de status
        function getStatusBadge(status) {
            const statusConfig = {
                'aprovado': { class: 'bg-green-100 text-green-800', text: 'Aprovado' },
                'em_analise': { class: 'bg-yellow-100 text-yellow-800', text: 'Em Análise' },
                'recusado': { class: 'bg-red-100 text-red-800', text: 'Recusado' }
            };
            
            const config = statusConfig[status] || statusConfig['em_analise'];
            return `<span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full ${config.class}">${config.text}</span>`;
        }

        // Função para fechar modal de visualização
        function closeViewModal() {
            document.getElementById('viewLicenciadoModal').classList.remove('show');
        }

        // Função para editar licenciado
        function editLicenciado(id) {
            // Por enquanto, redirecionar para uma página de edição
            // TODO: Implementar modal de edição
            window.location.href = `/dashboard/licenciados/${id}/edit`;
        }

        // Função para excluir licenciado
        function deleteLicenciado(id, name) {
            try {
                if (!id || !name) {
                    console.error('ID ou nome do licenciado não fornecido');
                    return;
                }
                
                licenciadoToDelete = id;
                const nameElement = document.getElementById('deleteLicenciadoName');
                const modal = document.getElementById('deleteConfirmModal');
                
                if (nameElement && modal) {
                    nameElement.textContent = name;
                    modal.classList.add('show');
                } else {
                    console.error('Elementos do modal não encontrados');
                }
            } catch (error) {
                console.error('Erro ao abrir modal de exclusão:', error);
                alert('Erro ao abrir modal de confirmação. Tente novamente.');
            }
        }

        // Função para fechar modal de confirmação
        function closeDeleteModal() {
            document.getElementById('deleteConfirmModal').classList.remove('show');
            licenciadoToDelete = null;
            
            // Resetar botão de confirmação
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            if (deleteBtn) {
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = '<i class="fas fa-trash mr-1"></i>Excluir';
            }
        }

        // Função para confirmar exclusão
        async function confirmDelete() {
            if (!licenciadoToDelete) {
                console.error('Nenhum licenciado selecionado para exclusão');
                return;
            }

            try {
                // Desabilitar botão para evitar cliques duplos
                const deleteBtn = document.getElementById('confirmDeleteBtn');
                deleteBtn.disabled = true;
                deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Excluindo...';

                const response = await fetch(`/dashboard/licenciados/${licenciadoToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    // Sucesso
                    closeDeleteModal();
                    
                    // Mostrar mensagem de sucesso
                    showSuccessMessage('Licenciado excluído com sucesso!');
                    
                    // Recarregar a página após um breve delay
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    // Erro
                    console.error('Erro na exclusão:', result);
                    alert('Erro ao excluir licenciado: ' + (result.message || 'Erro desconhecido'));
                    
                    // Reabilitar botão
                    deleteBtn.disabled = false;
                    deleteBtn.innerHTML = '<i class="fas fa-trash mr-1"></i>Excluir';
                }
            } catch (error) {
                console.error('Erro de conexão:', error);
                alert('Erro de conexão. Tente novamente.');
                
                // Reabilitar botão
                const deleteBtn = document.getElementById('confirmDeleteBtn');
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = '<i class="fas fa-trash mr-1"></i>Excluir';
            }
        }

        // Função para mostrar mensagem de sucesso
        function showSuccessMessage(message) {
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            successDiv.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(successDiv);
            
            setTimeout(() => {
                document.body.removeChild(successDiv);
            }, 3000);
        }

        // ===== FUNÇÕES DO MODAL DE APROVAÇÃO =====

        // Função para aprovar licenciado
        function approveLicenciado(id, name) {
            try {
                if (!id || !name) {
                    console.error('ID ou nome do licenciado não fornecido');
                    return;
                }
                
                licenciadoToApprove = id;
                const nameElement = document.getElementById('approval-licenciado-name');
                const idElement = document.getElementById('approval-licenciado-id');
                const modal = document.getElementById('approvalConfirmModal');
                
                if (nameElement && idElement && modal) {
                    nameElement.textContent = name;
                    idElement.textContent = `#${id.toString().padStart(3, '0')}`;
                    
                    // Mostrar modal
                    modal.classList.add('show');
                    
                    // Configurar focus trap
                    setupFocusTrap(modal);
                    
                    // Focar no primeiro botão
                    const firstButton = modal.querySelector('#btn-ver-detalhes');
                    if (firstButton) {
                        firstButton.focus();
                    }
                } else {
                    console.error('Elementos do modal de aprovação não encontrados');
                }
            } catch (error) {
                console.error('Erro ao abrir modal de aprovação:', error);
                alert('Erro ao abrir modal de confirmação. Tente novamente.');
            }
        }

        // Função para fechar modal de aprovação
        function closeApprovalModal() {
            const modal = document.getElementById('approvalConfirmModal');
            if (modal) {
                modal.classList.remove('show');
                licenciadoToApprove = null;
                removeFocusTrap();
            }
        }

        // Função para ver detalhes após aprovação
        function viewDetailsAfterApproval() {
            if (licenciadoToApprove) {
                closeApprovalModal();
                // Redirecionar para a página de gerenciamento completa
                window.location.href = `/dashboard/licenciados/${licenciadoToApprove}/gerenciar`;
            }
        }

        // Função para concluir aprovação
        async function concludeApproval() {
            if (!licenciadoToApprove) {
                console.error('ID do licenciado não encontrado');
                return;
            }

            try {
                // Mostrar loading no botão
                const btnConcluir = document.getElementById('btn-concluir');
                const originalText = btnConcluir.innerHTML;
                btnConcluir.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Aprovando...';
                btnConcluir.disabled = true;

                // Fazer chamada para aprovar o licenciado
                const response = await fetch(`/dashboard/licenciados/${licenciadoToApprove}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        status: 'aprovado'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    // Sucesso - fechar modal e mostrar toast
                    closeApprovalModal();
                    showSuccessToast();
                    
                    // Recarregar a página para atualizar o status
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    // Erro - mostrar mensagem
                    alert('Erro ao aprovar licenciado: ' + (result.message || 'Erro desconhecido'));
                }
            } catch (error) {
                console.error('Erro ao aprovar licenciado:', error);
                alert('Erro de conexão ao aprovar licenciado');
            } finally {
                // Restaurar botão
                const btnConcluir = document.getElementById('btn-concluir');
                if (btnConcluir) {
                    btnConcluir.innerHTML = '<i class="fas fa-check mr-2"></i>Concluir';
                    btnConcluir.disabled = false;
                }
            }
        }

        // Função para mostrar toast de sucesso
        function showSuccessToast() {
            const toast = document.getElementById('successToast');
            if (toast) {
                // Remover classes anteriores
                toast.classList.remove('toast-hide');
                toast.classList.add('toast-show');
                
                // Auto-hide após 4 segundos
                setTimeout(() => {
                    hideToast();
                }, 4000);
            }
        }

        // Função para esconder toast
        function hideToast() {
            const toast = document.getElementById('successToast');
            if (toast) {
                toast.classList.remove('toast-show');
                toast.classList.add('toast-hide');
                
                // Remover classe após animação
                setTimeout(() => {
                    toast.classList.remove('toast-hide');
                }, 300);
            }
        }

        // ===== FUNÇÕES DE ACESSIBILIDADE =====

        // Configurar focus trap
        function setupFocusTrap(modal) {
            focusableElements = modal.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            currentFocusIndex = 0;
            
            if (focusableElements.length > 0) {
                focusableElements[0].focus();
            }
        }

        // Remover focus trap
        function removeFocusTrap() {
            focusableElements = [];
            currentFocusIndex = 0;
        }

        // Navegar com Tab no modal
        function handleTabKey(event, modal) {
            if (focusableElements.length === 0) return;
            
            if (event.key === 'Tab') {
                event.preventDefault();
                
                if (event.shiftKey) {
                    // Shift + Tab (voltar)
                    currentFocusIndex = currentFocusIndex > 0 
                        ? currentFocusIndex - 1 
                        : focusableElements.length - 1;
                } else {
                    // Tab (avançar)
                    currentFocusIndex = currentFocusIndex < focusableElements.length - 1 
                        ? currentFocusIndex + 1 
                        : 0;
                }
                
                focusableElements[currentFocusIndex].focus();
            }
        }

        // ===== EVENT LISTENERS =====

        // Event listeners para os modais
        document.addEventListener('DOMContentLoaded', function() {
            const approvalModal = document.getElementById('approvalConfirmModal');
            const closeModal = document.getElementById('closeConfirmModal');
            const btnVerDetalhes = document.getElementById('btn-ver-detalhes');
            const btnConcluir = document.getElementById('btn-concluir');
            const btnCancelClose = document.getElementById('btn-cancel-close');
            const btnConfirmClose = document.getElementById('btn-confirm-close');
            
            // Modal de aprovação
            if (approvalModal) {
                // Clique fora do modal para fechar
                approvalModal.addEventListener('click', function(event) {
                    if (event.target === approvalModal) {
                        closeApprovalModal();
                    }
                });
                
                // Teclas de atalho
                approvalModal.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        closeApprovalModal();
                    } else if (event.key === 'Enter' && event.target.id === 'btn-concluir') {
                        concludeApproval();
                    } else if (event.key === 'Tab') {
                        handleTabKey(event, approvalModal);
                    }
                });
            }
            
            // Modal de confirmação de fechamento
            if (closeModal) {
                // Clique fora do modal para fechar
                closeModal.addEventListener('click', function(event) {
                    if (event.target === closeModal) {
                        closeCloseConfirmModal();
                    }
                });
                
                // Teclas de atalho
                closeModal.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        closeCloseConfirmModal();
                    } else if (event.key === 'Enter' && event.target.id === 'btn-confirm-close') {
                        confirmClose();
                    } else if (event.key === 'Tab') {
                        handleTabKey(event, closeModal);
                    }
                });
            }
            
            // Botões do modal de aprovação
            if (btnVerDetalhes) {
                btnVerDetalhes.addEventListener('click', viewDetailsAfterApproval);
            }
            
            if (btnConcluir) {
                btnConcluir.addEventListener('click', concludeApproval);
            }
            
            // Botões do modal de confirmação de fechamento
            if (btnCancelClose) {
                btnCancelClose.addEventListener('click', cancelClose);
            }
            
            if (btnConfirmClose) {
                btnConfirmClose.addEventListener('click', confirmClose);
            }
        });

        // Event listeners para o modal de status
        document.addEventListener('DOMContentLoaded', function() {
            const statusModal = document.getElementById('statusModal');
            const statusSelect = document.getElementById('statusSelect');
            const statusWarningModal = document.getElementById('statusWarningModal');
            
            // Modal de status
            if (statusModal) {
                // Clique fora do modal para fechar
                statusModal.addEventListener('click', function(event) {
                    if (event.target === statusModal) {
                        closeStatusModal();
                    }
                });
                
                // Teclas de atalho
                statusModal.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        closeStatusModal();
                    } else if (event.key === 'Enter' && event.target.id === 'confirmStatusBtn') {
                        confirmStatusChange();
                    } else if (event.key === 'Tab') {
                        handleTabKey(event, statusModal);
                    }
                });
            }
            
            // Select de status
            if (statusSelect) {
                statusSelect.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        confirmStatusChange();
                    }
                });
            }
            
            // Modal de aviso de status
            if (statusWarningModal) {
                // Clique fora do modal para fechar
                statusWarningModal.addEventListener('click', function(event) {
                    if (event.target === statusWarningModal) {
                        closeStatusWarningModal();
                    }
                });
                
                // Teclas de atalho
                statusWarningModal.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        closeStatusWarningModal();
                    } else if (event.key === 'Enter') {
                        closeStatusWarningModal();
                    } else if (event.key === 'Tab') {
                        handleTabKey(event, statusWarningModal);
                    }
                });
            }
        });

        // ===== FUNÇÕES DO MODAL DE CONFIRMAÇÃO DE FECHAMENTO =====

        // Função para mostrar modal de confirmação de fechamento
        function showCloseConfirmModal() {
            const modal = document.getElementById('closeConfirmModal');
            if (modal) {
                modal.classList.add('show');
                setupFocusTrap(modal);
                
                // Focar no botão de cancelar
                const cancelBtn = modal.querySelector('#btn-cancel-close');
                if (cancelBtn) {
                    cancelBtn.focus();
                }
            }
        }

        // Função para fechar modal de confirmação
        function closeCloseConfirmModal() {
            const modal = document.getElementById('closeConfirmModal');
            if (modal) {
                modal.classList.remove('show');
                removeFocusTrap();
            }
        }

        // Função para forçar fechamento do modal (sem confirmação)
        function forceCloseLicenciadoModal() {
            document.getElementById('licenciadoModal').classList.remove('show');
            resetForm();
            
            // Remover listener do ESC
            document.removeEventListener('keydown', handleEscapeKey);
        }

        // Função para cancelar fechamento
        function cancelClose() {
            closeCloseConfirmModal();
        }

        // Função para confirmar fechamento
        function confirmClose() {
            closeCloseConfirmModal();
            forceCloseLicenciadoModal();
        }

        // ===== MODAL DE ALTERAÇÃO DE STATUS =====
        let currentLicenciadoId = null;
        let currentLicenciadoName = null;
        let currentLicenciadoStatus = null;

        function openStatusModal(id, name, currentStatus) {
            currentLicenciadoId = id;
            currentLicenciadoName = name;
            currentLicenciadoStatus = currentStatus;
            
            // Preencher informações do modal
            document.getElementById('statusModalLicenciadoName').textContent = name;
            document.getElementById('statusModalCurrentStatus').textContent = getStatusLabel(currentStatus);
            
            // Limpar seleção anterior
            document.getElementById('statusSelect').value = '';
            
            // Mostrar modal com animação
            const modal = document.getElementById('statusModal');
            const modalContent = document.getElementById('statusModalContent');
            
            modal.style.display = 'flex';
            modal.classList.add('show');
            
            // Animar entrada
            setTimeout(() => {
                modalContent.style.transform = 'scale(1)';
                modalContent.style.opacity = '1';
            }, 10);
            
            // Focar no select
            setTimeout(() => {
                document.getElementById('statusSelect').focus();
            }, 300);
        }

        function closeStatusModal() {
            const modal = document.getElementById('statusModal');
            const modalContent = document.getElementById('statusModalContent');
            
            // Animar saída
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
            
            setTimeout(() => {
                modal.style.display = 'none';
                modal.classList.remove('show');
            }, 300);
            
            // Limpar variáveis
            currentLicenciadoId = null;
            currentLicenciadoName = null;
            currentLicenciadoStatus = null;
        }

        function confirmStatusChange() {
            const newStatus = document.getElementById('statusSelect').value;
            
            if (!newStatus) {
                alert('Por favor, selecione um status.');
                return;
            }
            
            if (newStatus === currentLicenciadoStatus) {
                showStatusWarningModal();
                return;
            }
            
            // Mostrar loading no botão
            const btn = document.getElementById('confirmStatusBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Alterando...';
            btn.disabled = true;
            
            // Fazer requisição para alterar status
            fetch(`/dashboard/licenciados/${currentLicenciadoId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar toast de sucesso
                    showStatusChangeToast(currentLicenciadoName, getStatusLabel(newStatus));
                    
                    // Fechar modal
                    closeStatusModal();
                    
                    // Recarregar página após um delay
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    alert('Erro ao alterar status: ' + (data.message || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao alterar status. Tente novamente.');
            })
            .finally(() => {
                // Restaurar botão
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }

        function getStatusLabel(status) {
            const labels = {
                'aprovado': 'Aprovado',
                'recusado': 'Recusado',
                'em_analise': 'Em Análise',
                'risco': 'Risco'
            };
            return labels[status] || status;
        }

        function showStatusChangeToast(licenciadoName, newStatus) {
            // Criar toast
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg transform translate-y-full transition-transform duration-300 z-50';
            toast.innerHTML = `
                <div class="flex items-center space-x-3">
                    <i class="fas fa-check-circle text-xl"></i>
                    <div>
                        <p class="font-semibold">Status alterado com sucesso!</p>
                        <p class="text-sm opacity-90">${licenciadoName} → ${newStatus}</p>
                    </div>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Animar entrada
            setTimeout(() => {
                toast.style.transform = 'translate-y(0)';
            }, 100);
            
            // Remover após 4 segundos
            setTimeout(() => {
                toast.style.transform = 'translate-y-full';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 4000);
        }

        // ===== MODAL DE AVISO - STATUS IGUAL =====
        function showStatusWarningModal() {
            const modal = document.getElementById('statusWarningModal');
            const modalContent = document.getElementById('statusWarningModalContent');
            
            // Mostrar modal com animação
            modal.style.display = 'flex';
            modal.classList.add('show');
            
            // Animar entrada
            setTimeout(() => {
                modalContent.style.transform = 'scale(1)';
                modalContent.style.opacity = '1';
            }, 10);
            
            // Focar no botão após animação
            setTimeout(() => {
                document.querySelector('#statusWarningModal button').focus();
            }, 300);
        }

        function closeStatusWarningModal() {
            const modal = document.getElementById('statusWarningModal');
            const modalContent = document.getElementById('statusWarningModalContent');
            
            // Animar saída
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
            
            setTimeout(() => {
                modal.style.display = 'none';
                modal.classList.remove('show');
            }, 300);
        }

        // ===== EXEMPLO DE USO =====
        // Para usar em qualquer lugar da página:
        // <button onclick="approveLicenciado(123, 'Nome da Empresa')">Aprovar cadastro</button>
    </script>

    <style>
        .step-indicator.active {
            background-color: rgba(255, 255, 255, 0.3);
        }

        /* Garantir que os modais estejam ocultos por padrão */
        #deleteConfirmModal {
            display: none !important;
        }
        
        #deleteConfirmModal.show {
            display: flex !important;
        }

        #viewLicenciadoModal {
            display: none !important;
        }
        
        #viewLicenciadoModal.show {
            display: flex !important;
        }

        /* Modal de Aprovação */
        #approvalConfirmModal {
            display: none !important;
        }
        
        #approvalConfirmModal.show {
            display: flex !important;
        }

        /* Modal de Confirmação de Fechamento */
        #closeConfirmModal {
            display: none !important;
        }
        
        #closeConfirmModal.show {
            display: flex !important;
        }

        /* Modal de Status */
        #statusModal {
            display: none !important;
        }
        
        #statusModal.show {
            display: flex !important;
        }

        /* Modal de Aviso de Status */
        #statusWarningModal {
            display: none !important;
        }
        
        #statusWarningModal.show {
            display: flex !important;
        }

        /* Animações personalizadas */
        @keyframes slideUp {
            from {
                transform: translate(-50%, 100%);
                opacity: 0;
            }
            to {
                transform: translate(-50%, 0);
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                transform: translate(-50%, 0);
                opacity: 1;
            }
            to {
                transform: translate(-50%, 100%);
                opacity: 0;
            }
        }

        .toast-show {
            animation: slideUp 0.5s ease-out forwards;
        }

        .toast-hide {
            animation: slideDown 0.3s ease-in forwards;
        }

        /* Focus trap para acessibilidade */
        .focus-trap {
            outline: none;
        }

        /* Melhorar contraste para acessibilidade */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        .step-indicator.completed {
            background-color: rgba(34, 197, 94, 0.8);
        }
        .operacao-item:hover {
            background-color: #f8fafc;
        }
        .operacao-checkbox:checked + label {
            color: #3b82f6;
        }
        
        /* Garantir que o modal fique oculto por padrão */
        #licenciadoModal {
            display: none !important;
        }
        
        #licenciadoModal.show {
            display: flex !important;
        }
            
        /* Estilos dinâmicos do dashboard */
        .dashboard-header {
            background: var(--background-color);
            color: var(--text-color);
        }
        .stat-card {
            background: var(--primary-gradient);
            color: var(--primary-text);
        }
        .progress-bar {
            background: var(--accent-gradient);
        }
    </style>
</body>
</html><?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/dashboard/licenciados.blade.php ENDPATH**/ ?>