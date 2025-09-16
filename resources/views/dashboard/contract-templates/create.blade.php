
<x-dynamic-branding />
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Template de Contrato - DSPay</title>
    
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endif
    
    <style>
        [x-cloak] { display: none !important; }
        .variable-highlight { background-color: #fef3c7; border: 1px solid #f59e0b; padding: 2px 4px; border-radius: 3px; }
        .editor-toolbar { position: sticky; top: 0; background: white; z-index: 10; border-bottom: 1px solid #e5e7eb; }
    </style>
</head>

<body class="bg-gray-50" x-data="templateEditor()">
    <div class="flex h-screen">
        <!-- Sidebar Dinâmico -->
        <x-dynamic-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('contract-templates.index') }}" 
                           class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Voltar
                        </a>
                        
                        <div class="h-6 border-l border-gray-300"></div>
                        
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">
                                <i class="fas fa-plus text-blue-600 mr-3"></i>
                                Criar Template de Contrato
                            </h1>
                            <p class="text-gray-600 mt-1">Cole seu texto e marque as variáveis que serão substituídas</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <button @click="previewTemplate()" 
                                type="button"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            Preview
                        </button>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
            <form action="{{ route('contract-templates.store') }}" method="POST" class="max-w-6xl mx-auto">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Editor Principal -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Informações Básicas -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informações do Template</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nome do Template *
                                    </label>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}"
                                           required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <div class="flex items-center">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1"
                                               {{ old('is_active', true) ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="is_active" class="ml-2 text-sm text-gray-700">
                                            Template ativo
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Descrição
                                </label>
                                <textarea id="description" 
                                          name="description" 
                                          rows="3"
                                          placeholder="Descreva o propósito deste template..."
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <!-- Editor de Conteúdo -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <!-- Toolbar -->
                            <div class="editor-toolbar px-6 py-4 flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <h2 class="text-lg font-semibold text-gray-900">Conteúdo do Contrato</h2>
                                    <span class="text-sm text-gray-500">
                                        Use <code class="bg-gray-100 px-1 rounded">@{{VARIAVEL}}</code> para marcar substituições
                                    </span>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <button type="button" 
                                            @click="detectVariables()"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-md transition-colors">
                                        <i class="fas fa-search mr-1"></i>
                                        Detectar Variáveis
                                    </button>
                                    
                                    <button type="button" 
                                            @click="formatContent()"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
                                        <i class="fas fa-align-left mr-1"></i>
                                        Formatar
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Editor -->
                            <div class="p-6 pt-0">
                                <textarea id="content" 
                                          name="content" 
                                          x-model="content"
                                          @input="detectVariables()"
                                          rows="20"
                                          required
                                          placeholder="Cole aqui o texto do seu contrato. Use @{{NOME}}, @{{DOCUMENTO}}, @{{ENDERECO}} etc. para marcar as variáveis que serão substituídas automaticamente..."
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 font-mono text-sm">{{ old('content') }}</textarea>
                                
                                <!-- Contador de caracteres -->
                                <div class="mt-2 flex items-center justify-between text-sm text-gray-500">
                                    <span x-text="`${content.length} caracteres`"></span>
                                    <span x-text="`${detectedVariables.length} variáveis detectadas`"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Ações -->
                        <div class="flex items-center justify-between">
                            <a href="{{ route('contract-templates.index') }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                Cancelar
                            </a>
                            
                            <div class="flex items-center space-x-3">
                                <button type="button" 
                                        @click="previewTemplate()"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                    <i class="fas fa-eye mr-2"></i>
                                    Preview
                                </button>
                                
                                <button type="submit" 
                                        :disabled="content.length < 50"
                                        class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-save mr-2"></i>
                                    Salvar Template
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar com Variáveis -->
                    <div class="space-y-6">
                        <!-- Variáveis Detectadas -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                Variáveis Detectadas
                                <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800" 
                                      x-text="detectedVariables.length"></span>
                            </h3>
                            
                            <div class="space-y-2 max-h-60 overflow-y-auto">
                                <template x-for="variable in detectedVariables" :key="variable">
                                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                        <span class="font-mono text-sm" x-text="variable"></span>
                                        <button type="button" 
                                                @click="insertVariable(variable)"
                                                class="text-xs text-blue-600 hover:text-blue-700">
                                            Inserir
                                        </button>
                                    </div>
                                </template>
                                
                                <div x-show="detectedVariables.length === 0" class="text-center py-4 text-gray-500">
                                    <i class="fas fa-search text-2xl mb-2 block"></i>
                                    <p class="text-sm">Digite no editor para detectar variáveis</p>
                                </div>
                            </div>
                        </div>

                        <!-- Variáveis Disponíveis -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Variáveis Disponíveis</h3>
                            
                            <div class="space-y-4">
                                @foreach($availableVariables as $category => $variables)
                                    <div x-data="{ open: false }">
                                        <button type="button" 
                                                @click="open = !open"
                                                class="flex items-center justify-between w-full text-left p-2 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                                            <span>{{ strtoupper($category) }}</span>
                                            <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': open }"></i>
                                        </button>
                                        
                                        <div x-show="open" x-collapse class="mt-2 space-y-1">
                                            @foreach($variables as $key => $description)
                                                <div class="flex items-center justify-between p-2 text-xs border-l-2 border-gray-200 ml-2">
                                                    <div class="flex-1">
                                                        <div class="font-mono text-blue-600">{{ $key }}</div>
                                                        <div class="text-gray-500 mt-1">{{ $description }}</div>
                                                    </div>
                                                    <button type="button" 
                                                            @click="insertVariable('{{ $key }}')"
                                                            class="ml-2 text-blue-600 hover:text-blue-700">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Dicas -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-blue-900 mb-2">
                                <i class="fas fa-lightbulb mr-1"></i>
                                Dicas
                            </h4>
                            <ul class="text-xs text-blue-800 space-y-1">
                                <li>• Use @{{VARIAVEL}} para marcar substituições</li>
                                <li>• Variáveis são detectadas automaticamente</li>
                                <li>• Clique em "Preview" para ver o resultado</li>
                                <li>• Templates inativos não aparecem na seleção</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
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
                    <h3 class="text-lg font-semibold text-gray-900">Preview do Template</h3>
                    <button @click="showPreview = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto border border-gray-200 rounded-lg p-6 bg-white">
                    <div class="prose prose-sm max-w-none" x-html="previewContent"></div>
                </div>
                
                <div class="mt-4 flex justify-end">
                    <button @click="showPreview = false" 
                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function templateEditor() {
            return {
                content: {!! json_encode(old('content', '')) !!},
                detectedVariables: [],
                showPreview: false,
                previewContent: '',
                
                init() {
                    this.detectVariables();
                },
                
                detectVariables() {
                    const regex = /\{\{([A-Z_][A-Z0-9_]*)\}\}/g;
                    const matches = [];
                    let match;
                    while ((match = regex.exec(this.content)) !== null) {
                        matches.push('{{' + match[1] + '}}');
                    }
                    this.detectedVariables = [...new Set(matches)];
                },
                
                insertVariable(variable) {
                    const textarea = document.getElementById('content');
                    const start = textarea.selectionStart;
                    const end = textarea.selectionEnd;
                    
                    const before = this.content.substring(0, start);
                    const after = this.content.substring(end);
                    
                    this.content = before + variable + after;
                    
                    // Reposicionar cursor
                    this.$nextTick(() => {
                        textarea.focus();
                        textarea.setSelectionRange(start + variable.length, start + variable.length);
                        this.detectVariables();
                    });
                },
                
                formatContent() {
                    // Normalizar quebras de linha e espaçamentos
                    this.content = this.content
                        .replace(/\r\n/g, '\n')
                        .replace(/\n{3,}/g, '\n\n')
                        .trim();
                    
                    this.detectVariables();
                },
                
                async previewTemplate() {
                    if (this.content.length < 10) {
                        alert('Digite algum conteúdo antes de visualizar o preview.');
                        return;
                    }
                    
                    // Simular dados para preview
                    const sampleData = {
                        'NOME': 'EMPRESA EXEMPLO LTDA',
                        'DOCUMENTO': '12.345.678/0001-90',
                        'CNPJ': '12.345.678/0001-90',
                        'ENDERECO': 'Rua Exemplo, 123, Centro, São Paulo, SP',
                        'CEP': '01234-567',
                        'EMAIL': 'contato@exemplo.com.br',
                        'TELEFONE': '(11) 9999-9999',
                        'REPRESENTANTE_NOME': 'João da Silva',
                        'REPRESENTANTE_CPF': '123.456.789-00',
                        'EMPRESA_NOME': 'SUA EMPRESA LTDA',
                        'EMPRESA_CNPJ': '98.765.432/0001-10',
                        'DATA_ATUAL': 'quinze de janeiro de dois mil e vinte e cinco',
                        'NUMERO_CONTRATO': '001/2025',
                        'VALOR': 'R$ 5.000,00'
                    };
                    
                    let preview = this.content;
                    
                    // Substituir variáveis
                    Object.entries(sampleData).forEach(([key, value]) => {
                        const regex = new RegExp(`\\{\\{${key}\\}\\}`, 'g');
                        preview = preview.replace(regex, `<span class="bg-yellow-100 px-1 rounded font-semibold text-blue-800">${value}</span>`);
                    });
                    
                    // Converter quebras de linha em HTML preservando formatação
                    preview = preview.replace(/\n/g, '<br>');
                    
                    // Adicionar espaçamento entre parágrafos (dupla quebra de linha)
                    preview = preview.replace(/(<br>\s*){2,}/g, '</p><p class="mb-4">');
                    preview = '<p class="mb-4">' + preview + '</p>';
                    
                    this.previewContent = preview;
                    this.showPreview = true;
                }
            }
        }
    </script>
</body>
</html>
