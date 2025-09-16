<x-dynamic-branding />
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Contrato - Etapa 2 - DSPay</title>
    
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Branding Dinâmico -->
    
    @endif
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
<style>
/* CORREÇÃO AGRESSIVA DE CORES - GERADO AUTOMATICAMENTE */
:root {
    --primary-color: #3B82F6;
    --primary-color-rgb: 59, 130, 246;
    --primary-dark: #2563EB;
    --primary-light: rgba(59, 130, 246, 0.1);
    --primary-text: #FFFFFF;
    --secondary-color: #6B7280;
    --accent-color: #10B981;
}

/* Classes customizadas para substituir Tailwind */
.bg-primary { background-color: var(--primary-color) !important; }
.bg-primary-dark { background-color: var(--primary-dark) !important; }
.text-primary { color: var(--primary-color) !important; }
.border-primary { border-color: var(--primary-color) !important; }
.hover\:bg-primary-dark:hover { background-color: var(--primary-dark) !important; }

/* Sobrescrita total de cores azuis */
.bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400,
.bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-blue-800, .bg-blue-900,
.bg-indigo-50, .bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700, .bg-indigo-800, .bg-indigo-900 {
    background-color: var(--primary-color) !important;
}

.text-blue-50, .text-blue-100, .text-blue-200, .text-blue-300, .text-blue-400,
.text-blue-500, .text-blue-600, .text-blue-700, .text-blue-800, .text-blue-900,
.text-indigo-50, .text-indigo-100, .text-indigo-200, .text-indigo-300, .text-indigo-400,
.text-indigo-500, .text-indigo-600, .text-indigo-700, .text-indigo-800, .text-indigo-900 {
    color: var(--primary-color) !important;
}

.border-blue-50, .border-blue-100, .border-blue-200, .border-blue-300, .border-blue-400,
.border-blue-500, .border-blue-600, .border-blue-700, .border-blue-800, .border-blue-900,
.border-indigo-50, .border-indigo-100, .border-indigo-200, .border-indigo-300, .border-indigo-400,
.border-indigo-500, .border-indigo-600, .border-indigo-700, .border-indigo-800, .border-indigo-900 {
    border-color: var(--primary-color) !important;
}

/* Hovers */
.hover\:bg-blue-50:hover, .hover\:bg-blue-100:hover, .hover\:bg-blue-200:hover,
.hover\:bg-blue-300:hover, .hover\:bg-blue-400:hover, .hover\:bg-blue-500:hover,
.hover\:bg-blue-600:hover, .hover\:bg-blue-700:hover, .hover\:bg-blue-800:hover,
.hover\:bg-indigo-50:hover, .hover\:bg-indigo-100:hover, .hover\:bg-indigo-200:hover,
.hover\:bg-indigo-300:hover, .hover\:bg-indigo-400:hover, .hover\:bg-indigo-500:hover,
.hover\:bg-indigo-600:hover, .hover\:bg-indigo-700:hover, .hover\:bg-indigo-800:hover {
    background-color: var(--primary-dark) !important;
}

/* Sobrescrever estilos inline */
[style*="background-color: #3b82f6"], [style*="background-color: #2563eb"],
[style*="background-color: #1d4ed8"], [style*="background-color: #1e40af"],
[style*="background-color: rgb(59, 130, 246)"], [style*="background-color: rgb(37, 99, 235)"] {
    background-color: var(--primary-color) !important;
}

[style*="color: #3b82f6"], [style*="color: #2563eb"],
[style*="color: #1d4ed8"], [style*="color: #1e40af"],
[style*="color: rgb(59, 130, 246)"], [style*="color: rgb(37, 99, 235)"] {
    color: var(--primary-color) !important;
}

/* Botões e elementos interativos */
button:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
.btn:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
input[type="submit"], input[type="button"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

button:hover:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
.btn:hover:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]) {
    background-color: var(--primary-dark) !important;
}

/* Links */
a:not([class*="text-gray"]):not([class*="text-white"]):not([class*="text-black"]):not([class*="text-red"]):not([class*="text-green"]) {
    color: var(--primary-color) !important;
}

a:hover:not([class*="text-gray"]):not([class*="text-white"]):not([class*="text-black"]):not([class*="text-red"]):not([class*="text-green"]) {
    color: var(--primary-dark) !important;
}

/* Focus states */
input:focus, select:focus, textarea:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px var(--primary-light) !important;
    outline: none !important;
}

/* Spinners e loading */
.animate-spin {
    border-color: var(--primary-color) transparent var(--primary-color) transparent !important;
}

/* Badges e tags */
.badge:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
.tag:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]) {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}
</style>
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <x-dynamic-sidebar />
        
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="dashboard-header bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('contracts.generate.index') }}"
                           class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Voltar
                        </a>
                        <div class="h-6 border-l border-gray-300"></div>
                        <div>
                            <h1 class="text-2xl font-bold " style="color: var(--text-color);"">
                                <i class="fas fa-file-contract " style="color: var(--primary-color);" mr-3"></i>
                                Gerar Contrato
                            </h1>
                            <p class="" style="color: var(--secondary-color);" mt-1">Etapa 2 de 3 • Escolher Template</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Progress Bar -->
            <div class="bg-white border-b px-6 py-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-primary">Etapa 2 de 3</span>
                    <span class="text-sm text-gray-500">66% concluído</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-primary h-2 rounded-full" style="width: 66%"></div>
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
                                <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium">2</div>
                                <span class="ml-2 text-sm font-medium text-primary">Escolher Template</span>
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
                                    <i class="fas fa-user-check " style="color: var(--primary-color);" mr-2"></i>
                                    Licenciado Selecionado
                                </h3>
                                
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Nome/Razão Social</label>
                                        <p class="text-gray-900">{{ $licenciado->razao_social ?: $licenciado->nome_fantasia }}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">CNPJ/CPF</label>
                                        <p class="text-gray-900">{{ $licenciado->cnpj_cpf }}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Email</label>
                                        <p class="text-gray-900">{{ $licenciado->email }}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Status</label>
                                        <span class="inline-flex px-2 py-1 text-xs rounded-full 
                                            {{ $licenciado->status === 'ativo' ? 'bg-green-100 text-green-800' : 
                                               ($licenciado->status === 'aprovado' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ $licenciado->status }}
                                        </span>
                                    </div>
                                    
                                    @if($licenciado->representante_nome)
                                    <div class="pt-3 border-t">
                                        <label class="text-sm font-medium text-gray-600">Representante</label>
                                        <p class="text-gray-900">{{ $licenciado->representante_nome }}</p>
                                        <p class="text-sm " style="color: var(--secondary-color);"">{{ $licenciado->representante_cargo ?: 'Representante Legal' }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Template Selection -->
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-lg shadow-sm border">
                                <div class="p-6 border-b">
                                    <h3 class="text-lg font-semibold text-gray-800">Escolher Template de Contrato</h3>
                                    <p class="" style="color: var(--secondary-color);" mt-1">Selecione o modelo de contrato que será usado</p>
                                </div>

                                <div class="p-6">
                                    @if($templates->count() > 0)
                                        <div class="space-y-4">
                                            @foreach($templates as $template)
                                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors cursor-pointer template-card"
                                                 onclick="selectTemplate({{ json_encode($template) }})">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-3">
                                                            <h4 class="font-medium text-gray-900">{{ $template->name }}</h4>
                                                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                                                v{{ $template->version }}
                                                            </span>
                                                        </div>
                                                        
                                                        @if($template->description)
                                                        <p class="text-sm " style="color: var(--secondary-color);" mt-1">{{ $template->description }}</p>
                                                        @endif
                                                        
                                                        <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                                            <span><i class="fas fa-calendar mr-1"></i>{{ $template->created_at->format('d/m/Y') }}</span>
                                                            <span><i class="fas fa-user mr-1"></i>{{ $template->createdBy->name ?? 'Sistema' }}</span>
                                                            @if($template->variables)
                                                                <span><i class="fas fa-code mr-1"></i>{{ count(json_decode($template->variables, true)) }} variáveis</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="ml-4 flex items-center space-x-2">
                                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center border-gray-300 template-circle">
                                                            <i class="fas fa-check text-white text-xs template-check" style="display: none;"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>

                                        <!-- Continue Button -->
                                        <div class="mt-6 flex justify-end">
                                            <form method="POST" action="{{ route('contracts.generate.step3') }}" id="templateForm">
                                                @csrf
                                                <input type="hidden" name="licenciado_id" value="{{ $licenciado->id }}">
                                                <input type="hidden" name="template_id" id="selected_template_id">
                                                <button type="submit" 
                                                        id="continue-btn"
                                                        disabled
                                                        class="inline-flex items-center px-6 py-3 bg-primary text-white font-medium rounded-lg hover:bg-primary-dark focus:ring-4 focus:ring-blue-200 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                                    Continuar para Etapa 3
                                                    <i class="fas fa-arrow-right ml-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="text-center py-8">
                                            <i class="fas fa-file-alt text-gray-400 text-3xl mb-3"></i>
                                            <p class="text-gray-500 mb-4">Nenhum template de contrato disponível</p>
                                            <a href="{{ route('contract-templates.create') }}" 
                                               class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                                <i class="fas fa-plus mr-2"></i>Criar Template
                                            </a>
                                        </div>
                                    @endif

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
            
            circle.className = 'w-5 h-5 rounded-full border-2 flex items-center justify-center border-primary bg-primary template-circle';
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
            console.log('📊 Templates disponíveis:', {{ $templates->count() }});
            
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
