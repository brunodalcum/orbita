<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Contrato - Etapa 3 - DSPay</title>
    
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endif
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <x-dynamic-sidebar />
        
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('contracts.generate.index') }}"
                           class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Voltar
                        </a>
                        <div class="h-6 border-l border-gray-300"></div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">
                                <i class="fas fa-file-contract text-blue-600 mr-3"></i>
                                Gerar Contrato
                            </h1>
                            <p class="text-gray-600 mt-1">Etapa 3 de 3 • Revisar e Gerar</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Progress Bar -->
            <div class="bg-white border-b px-6 py-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-blue-600">Etapa 3 de 3</span>
                    <span class="text-sm text-gray-500">100% concluído</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 100%"></div>
                </div>
            </div>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
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
                                <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span class="ml-2 text-sm font-medium text-green-600">Template Escolhido</span>
                            </div>
                            <div class="flex-1 h-px bg-green-300"></div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium">3</div>
                                <span class="ml-2 text-sm font-medium text-blue-600">Gerar Contrato</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                        <!-- Summary Sidebar -->
                        <div class="lg:col-span-1">
                            <div class="space-y-6">
                                <!-- Licensee Info -->
                                <div class="bg-white rounded-lg shadow-sm border p-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                        <i class="fas fa-user text-blue-600 mr-2"></i>
                                        Licenciado
                                    </h3>
                                    
                                    <div class="space-y-3">
                                        <div>
                                            <label class="text-sm font-medium text-gray-600">Nome</label>
                                            <p class="text-gray-900 text-sm">{{ $licenciado->razao_social ?: $licenciado->nome_fantasia }}</p>
                                        </div>
                                        
                                        <div>
                                            <label class="text-sm font-medium text-gray-600">Documento</label>
                                            <p class="text-gray-900 text-sm">{{ $licenciado->cnpj_cpf }}</p>
                                        </div>
                                        
                                        <div>
                                            <label class="text-sm font-medium text-gray-600">Email</label>
                                            <p class="text-gray-900 text-sm">{{ $licenciado->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Template Info -->
                                <div class="bg-white rounded-lg shadow-sm border p-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                        <i class="fas fa-file-alt text-green-600 mr-2"></i>
                                        Template
                                    </h3>
                                    
                                    <div class="space-y-3">
                                        <div>
                                            <label class="text-sm font-medium text-gray-600">Nome</label>
                                            <p class="text-gray-900 text-sm">{{ $template->name }}</p>
                                        </div>
                                        
                                        <div>
                                            <label class="text-sm font-medium text-gray-600">Versão</label>
                                            <p class="text-gray-900 text-sm">v{{ $template->version }}</p>
                                        </div>
                                        
                                        @if($template->description)
                                        <div>
                                            <label class="text-sm font-medium text-gray-600">Descrição</label>
                                            <p class="text-gray-900 text-sm">{{ $template->description }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Variables Used -->
                                <div class="bg-white rounded-lg shadow-sm border p-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                        <i class="fas fa-code text-purple-600 mr-2"></i>
                                        Variáveis Substituídas
                                    </h3>
                                    
                                    <div class="space-y-2">
                                        @foreach($contractData as $key => $value)
                                            @if($value)
                                            <div class="flex justify-between items-center text-xs">
                                                <span class="text-gray-600">@{{@{{ $key }}@}}</span>
                                                <span class="text-gray-900 font-mono bg-gray-100 px-1 rounded">{{ Str::limit($value, 15) }}</span>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contract Preview -->
                        <div class="lg:col-span-3">
                            <div class="bg-white rounded-lg shadow-sm border">
                                <div class="p-6 border-b">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-800">Preview do Contrato</h3>
                                            <p class="text-gray-600 mt-1">Revise o contrato antes de gerar</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                                <i class="fas fa-check mr-1"></i>Pronto para gerar
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <!-- Contract Content -->
                                    <div class="bg-gray-50 rounded-lg p-6 mb-6 max-h-96 overflow-y-auto">
                                        <div class="prose max-w-none text-sm">
                                            {!! $previewContent !!}
                                        </div>
                                    </div>

                                    <!-- Generation Form -->
                                    <form method="POST" action="{{ route('contracts.generate.step3') }}" x-data="contractGenerator()">
                                        @csrf
                                        <input type="hidden" name="licenciado_id" value="{{ $licenciado->id }}">
                                        <input type="hidden" name="template_id" value="{{ $template->id }}">

                                        <!-- Admin Notes -->
                                        <div class="mb-6">
                                            <label for="observacoes_admin" class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-sticky-note mr-2"></i>Observações Administrativas (Opcional)
                                            </label>
                                            <textarea name="observacoes_admin" 
                                                      id="observacoes_admin"
                                                      rows="3"
                                                      placeholder="Digite observações internas sobre este contrato..."
                                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                            <p class="text-xs text-gray-500 mt-1">Estas observações são apenas para controle interno e não aparecerão no contrato.</p>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex items-center justify-between">
                                            <a href="{{ route('contracts.generate.index') }}" 
                                               class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                                                <i class="fas fa-arrow-left mr-2"></i>Voltar ao Início
                                            </a>

                                            <div class="flex items-center space-x-3">
                                                <button type="button" 
                                                        @click="showFullPreview = true"
                                                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                                    <i class="fas fa-expand mr-2"></i>Ver Completo
                                                </button>

                                                <button type="submit" 
                                                        :disabled="generating"
                                                        class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-200 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                                    <span x-show="!generating">
                                                        <i class="fas fa-file-pdf mr-2"></i>Gerar Contrato
                                                    </span>
                                                    <span x-show="generating">
                                                        <i class="fas fa-spinner fa-spin mr-2"></i>Gerando...
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- Full Preview Modal -->
                                    <div x-show="showFullPreview" 
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                                         @click="showFullPreview = false">
                                        <div class="bg-white rounded-lg max-w-6xl max-h-[90vh] flex flex-col" @click.stop>
                                            <div class="flex items-center justify-between p-6 border-b">
                                                <h3 class="text-lg font-semibold">Contrato Completo</h3>
                                                <button @click="showFullPreview = false" class="text-gray-400 hover:text-gray-600">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            <div class="flex-1 overflow-y-auto p-6">
                                                <div class="prose max-w-none">
                                                    {!! $previewContent !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function contractGenerator() {
            return {
                generating: false,
                showFullPreview: false,
                
                init() {
                    // Handle form submission
                    this.$el.querySelector('form').addEventListener('submit', (e) => {
                        this.generating = true;
                    });
                }
            }
        }
    </script>
</body>
</html>
