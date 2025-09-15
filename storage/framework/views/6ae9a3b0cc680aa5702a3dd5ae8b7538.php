<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview do Branding</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary-color: <?php echo e($previewBranding['primary_color'] ?? '#3B82F6'); ?>;
            --secondary-color: <?php echo e($previewBranding['secondary_color'] ?? '#6B7280'); ?>;
            --accent-color: <?php echo e($previewBranding['accent_color'] ?? '#10B981'); ?>;
            --text-color: <?php echo e($previewBranding['text_color'] ?? '#1F2937'); ?>;
            --background-color: <?php echo e($previewBranding['background_color'] ?? '#FFFFFF'); ?>;
            --font-family: '<?php echo e($previewBranding['font_family'] ?? 'Inter'); ?>', sans-serif;
        }
        
        @import url('https://fonts.googleapis.com/css2?family=<?php echo e(urlencode($previewBranding['font_family'] ?? 'Inter')); ?>:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: var(--font-family);
            color: var(--text-color);
            background-color: var(--background-color);
        }
        
        .primary-bg { background-color: var(--primary-color); }
        .secondary-bg { background-color: var(--secondary-color); }
        .accent-bg { background-color: var(--accent-color); }
        .primary-text { color: var(--primary-color); }
        .secondary-text { color: var(--secondary-color); }
        .accent-text { color: var(--accent-color); }
        .primary-border { border-color: var(--primary-color); }
        
        /* CSS Customizado */
        <?php echo $previewBranding['custom_css'] ?? ''; ?>

    </style>
</head>
<body class="p-4">
    <!-- Header simulado -->
    <div class="primary-bg text-white p-4 rounded-lg mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2L3 7v11h4v-6h6v6h4V7l-7-5z"/>
                    </svg>
                </div>
                <h1 class="text-lg font-semibold">Órbita Platform</h1>
            </div>
            <div class="flex items-center space-x-2">
                <button class="bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-1 rounded text-sm transition-colors duration-200">
                    Dashboard
                </button>
                <button class="bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-1 rounded text-sm transition-colors duration-200">
                    Configurações
                </button>
            </div>
        </div>
    </div>

    <!-- Cards de métricas -->
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
            <div class="flex items-center">
                <div class="primary-bg text-white p-2 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm secondary-text">Total Usuários</p>
                    <p class="text-xl font-semibold">1,234</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
            <div class="flex items-center">
                <div class="accent-bg text-white p-2 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm secondary-text">Crescimento</p>
                    <p class="text-xl font-semibold accent-text">+12%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Botões de exemplo -->
    <div class="flex space-x-3 mb-4">
        <button class="primary-bg text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity duration-200">
            Botão Primário
        </button>
        <button class="border primary-border primary-text px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
            Botão Secundário
        </button>
        <button class="accent-bg text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity duration-200">
            Botão de Destaque
        </button>
    </div>

    <!-- Lista de exemplo -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200">
            <h3 class="text-lg font-medium">Lista de Usuários</h3>
        </div>
        <div class="divide-y divide-gray-200">
            <div class="px-4 py-3 hover:bg-gray-50 transition-colors duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="primary-bg text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium">
                            JD
                        </div>
                        <div>
                            <p class="font-medium">João da Silva</p>
                            <p class="text-sm secondary-text">joao@exemplo.com</p>
                        </div>
                    </div>
                    <span class="accent-bg text-white px-2 py-1 rounded-full text-xs font-medium">
                        Ativo
                    </span>
                </div>
            </div>
            
            <div class="px-4 py-3 hover:bg-gray-50 transition-colors duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="secondary-bg text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium">
                            MS
                        </div>
                        <div>
                            <p class="font-medium">Maria Santos</p>
                            <p class="text-sm secondary-text">maria@exemplo.com</p>
                        </div>
                    </div>
                    <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded-full text-xs font-medium">
                        Pendente
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário de exemplo -->
    <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4">
        <h3 class="text-lg font-medium mb-4">Formulário de Exemplo</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Nome</label>
                <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-opacity-50" style="focus:ring-color: var(--primary-color);" placeholder="Digite seu nome">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-opacity-50" style="focus:ring-color: var(--primary-color);" placeholder="Digite seu email">
            </div>
            <div class="flex justify-end">
                <button class="primary-bg text-white px-6 py-2 rounded-lg hover:opacity-90 transition-opacity duration-200">
                    Salvar
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-6 text-center">
        <p class="text-sm secondary-text">
            Preview do Branding - <?php echo e(now()->format('d/m/Y H:i')); ?>

        </p>
    </div>
</body>
</html>
<?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/hierarchy/branding/preview.blade.php ENDPATH**/ ?>