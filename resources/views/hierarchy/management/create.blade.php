<x-dynamic-branding />
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Criar {{ $nodeType === 'operacao' ? 'Nova Operação' : ($nodeType === 'white_label' ? 'Novo White Label' : 'Novo Licenciado') }} - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Branding Dinâmico -->

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid white;
        }
        .card {
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar Dinâmico -->
        <x-dynamic-sidebar />
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden ml-64">
            <div class="min-h-screen bg-gray-50" x-data="nodeCreation()">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">
                                    Criar {{ $nodeType === 'operacao' ? 'Nova Operação' : ($nodeType === 'white_label' ? 'Novo White Label' : 'Novo Licenciado') }}
                    </h1>
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst(str_replace('_', ' ', $nodeType)) }}
                                </span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('hierarchy.management.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>

                <!-- Content -->
                <div class="flex-1 overflow-auto p-4">
                    <div class="max-w-6xl mx-auto">
                        <!-- Botões de ação fixos no topo -->
                        <div class="bg-white rounded-lg shadow-sm border mb-4 p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <h2 class="text-lg font-semibold text-gray-900">
                                        Criar {{ $nodeType === 'operacao' ? 'Nova Operação' : ($nodeType === 'white_label' ? 'Novo White Label' : 'Novo Licenciado') }}
                                    </h2>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst(str_replace('_', ' ', $nodeType)) }}
                                    </span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('hierarchy.management.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Cancelar
                                    </a>
                                    <button @click="submitForm()" :disabled="loading" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span x-text="loading ? 'Criando...' : 'Criar {{ $nodeType === 'operacao' ? 'Operação' : ($nodeType === 'white_label' ? 'White Label' : 'Licenciado') }}'"></span>
                                    </button>
                                </div>
                </div>
                        </div>

                        <form @submit.prevent="submitForm()">
                            <!-- Layout em duas colunas -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Coluna Esquerda -->
                                <div class="space-y-6">
                                    <!-- Informações básicas -->
                                    <div class="card rounded-lg">
                                        <div class="px-4 py-3 border-b border-gray-200">
                                            <h3 class="text-md font-medium text-gray-900">Informações Básicas</h3>
                                        </div>
                                        <div class="p-4">
                                            <div class="space-y-4">
                                                @if($nodeType === 'operacao' || $nodeType === 'white_label')
                                                <!-- Nome interno -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome Interno *</label>
                                                    <input type="text" x-model="formData.name" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="nome-interno-sem-espacos">
                                                </div>

                                                <!-- Nome de exibição -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome de Exibição *</label>
                                                    <input type="text" x-model="formData.display_name" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nome para exibição">
                                                </div>
                                                @endif

                                                <!-- Nome do usuário -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Usuário *</label>
                                                    <input type="text" x-model="formData.user_name" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nome completo do usuário">
                                                </div>

                                                <!-- Email -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                                    <input type="email" x-model="formData.email" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="email@exemplo.com">
                                                </div>

                                                <!-- Senha -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Senha *</label>
                                                    <input type="password" x-model="formData.password" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Senha segura">
                        </div>

                                                <!-- Confirmar senha -->
                        <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Senha *</label>
                                                    <input type="password" x-model="formData.password_confirmation" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Confirme a senha">
                                                </div>
                                            </div>
                                        </div>
                        </div>

                                    <!-- Relacionamentos Hierárquicos -->
                                    @if($nodeType !== 'operacao')
                                    <div class="card rounded-lg">
                                        <div class="px-4 py-3 border-b border-gray-200">
                                            <h3 class="text-md font-medium text-gray-900">Relacionamentos Hierárquicos</h3>
                                            <p class="text-sm text-gray-500 mt-1">Defina a posição deste nó na hierarquia</p>
                                        </div>
                                        <div class="p-4">
                                            <div class="space-y-4">
                                                <!-- Operação (obrigatório para todos exceto operação) -->
                        <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        Operação *
                                                        <span class="text-xs text-gray-500">(Obrigatório)</span>
                                                    </label>
                                                    <select x-model="formData.operacao_id" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                        <option value="">Selecione uma operação...</option>
                                                        @foreach($availableOperacoes as $operacao)
                                                            <option value="{{ $operacao->id }}">{{ $operacao->name }} ({{ $operacao->email }})</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-xs text-gray-500 mt-1">Toda hierarquia deve estar vinculada a uma operação</p>
                        </div>

                                                <!-- White Label (opcional para licenciados) -->
                                                @if(in_array($nodeType, ['licenciado_l1', 'licenciado_l2', 'licenciado_l3']))
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        White Label
                                                        <span class="text-xs text-gray-500">(Opcional)</span>
                                                    </label>
                                                    <select x-model="formData.white_label_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                        <option value="">Nenhum (vinculado diretamente à operação)</option>
                                                        @foreach($availableWhiteLabels as $whiteLabel)
                                                            <option value="{{ $whiteLabel->id }}">{{ $whiteLabel->name }} ({{ $whiteLabel->email }})</option>
                                    @endforeach
                                                    </select>
                                                    <p class="text-xs text-gray-500 mt-1">Se não selecionado, ficará vinculado diretamente à operação</p>
                                                </div>
                                @endif

                                                <!-- Licenciado Pai (para L2 e L3) -->
                                                @if($nodeType === 'licenciado_l2')
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        Licenciado L1 Pai *
                                                        <span class="text-xs text-gray-500">(Obrigatório)</span>
                                                    </label>
                                                    <select x-model="formData.licenciado_pai_id" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                        <option value="">Selecione um Licenciado L1...</option>
                                                        <!-- Será preenchido dinamicamente via JavaScript baseado na operação/WL selecionada -->
                            </select>
                                                    <p class="text-xs text-gray-500 mt-1">Licenciado L1 ao qual este L2 estará subordinado</p>
                        </div>
                        @endif

                                                @if($nodeType === 'licenciado_l3')
                        <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        Licenciado L2 Pai *
                                                        <span class="text-xs text-gray-500">(Obrigatório)</span>
                                                    </label>
                                                    <select x-model="formData.licenciado_pai_id" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                        <option value="">Selecione um Licenciado L2...</option>
                                                        <!-- Será preenchido dinamicamente via JavaScript baseado na operação/WL selecionada -->
                                                    </select>
                                                    <p class="text-xs text-gray-500 mt-1">Licenciado L2 ao qual este L3 estará subordinado</p>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                        </div>

                                <!-- Coluna Direita -->
                                <div class="space-y-6">
                                    <!-- Status -->
                                    <div class="card rounded-lg">
                                        <div class="px-4 py-3 border-b border-gray-200">
                                            <h3 class="text-md font-medium text-gray-900">Status</h3>
                                        </div>
                                        <div class="p-4">
                                            <div class="flex items-center">
                                                <input type="checkbox" x-model="formData.is_active" id="is_active" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                                    Nó ativo
                                                </label>
                        </div>
                                            <p class="text-xs text-gray-500 mt-1">Nós inativos não podem ser utilizados</p>
                        </div>
                        </div>

                                    <!-- Módulos Disponíveis -->
                                    <div class="card rounded-lg">
                                        <div class="px-4 py-3 border-b border-gray-200">
                                            <h3 class="text-md font-medium text-gray-900">Módulos Disponíveis</h3>
                                            <p class="text-sm text-gray-500 mt-1">Selecione os módulos que este nó terá acesso</p>
                        </div>
                                        <div class="p-4" style="max-height: 300px; overflow-y: auto;">
                                            <div class="space-y-3">
                                                @foreach($availableModules as $module)
                                                <div class="flex items-center">
                                                    <input type="checkbox" x-model="formData.modules" value="{{ $module }}" id="module_{{ $module }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                    <label for="module_{{ $module }}" class="ml-2 block text-sm text-gray-900 capitalize">
                                                        {{ str_replace('_', ' ', $module) }}
                                                    </label>
                        </div>
                                                @endforeach
                    </div>
                </div>
            </div>

                                    @if($nodeType === 'operacao' || $nodeType === 'white_label')
                                    <!-- Configurações de Domínio -->
                                    <div class="card rounded-lg">
                                        <div class="px-4 py-3 border-b border-gray-200">
                                            <h3 class="text-md font-medium text-gray-900">Configurações de Domínio</h3>
                </div>
                                        <div class="p-4">
                                            <div class="space-y-4">
                                                <!-- Domínio personalizado -->
                        <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Domínio Personalizado</label>
                                                    <input type="text" x-model="formData.domain" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="exemplo.com">
                        </div>

                                                <!-- Subdomínio -->
                        <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Subdomínio</label>
                                                    <div class="flex">
                                                        <input type="text" x-model="formData.subdomain" class="flex-1 border border-gray-300 rounded-l-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="meusubdominio">
                                                        <span class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm rounded-r-md">.orbita.dspay.com.br</span>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
                                </div>

                                <!-- Coluna Direita -->
                                <div class="space-y-6">
                                    <!-- Módulos Disponíveis -->
                                    <div class="card rounded-lg">
                                        <div class="px-4 py-3 border-b border-gray-200">
                                            <h3 class="text-md font-medium text-gray-900">Módulos Disponíveis</h3>
                                            <p class="mt-1 text-sm text-gray-500">Selecione os módulos para este nó</p>
                </div>
                                        <div class="p-4">
                                            <div class="grid grid-cols-1 gap-2 max-h-96 overflow-y-auto">
                        @foreach($availableModules as $key => $name)
                                                <label class="flex items-center p-2 border border-gray-200 rounded hover:bg-gray-50 cursor-pointer">
                                                    <input type="checkbox" x-model="formData.modules.{{ $key }}.enabled" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    <div class="ml-2">
                                                        <div class="text-sm font-medium text-gray-900">{{ $name }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
                                </div>
            </div>
        </form>
                    </div>
                </div>
            </div>
    </div>
</div>

<script>
        function nodeCreation() {
    return {
                loading: false,
        formData: {
            node_type: '{{ $nodeType }}',
            parent_id: {{ $parent ? $parent->id : 'null' }},
            name: '',
            display_name: '',
            user_name: '',
            email: '',
            password: '',
            password_confirmation: '',
            phone: '',
            description: '',
            domain: '',
            subdomain: '',
            is_active: true,
            modules: [],
            operacao_id: null,
            white_label_id: null,
            licenciado_pai_id: null,
        },
        
        async submitForm() {
                    if (this.loading) return;
            
            // Validações básicas
                    if (this.formData.password !== this.formData.password_confirmation) {
                        alert('As senhas não coincidem');
                        return;
                    }
                    
                    this.loading = true;
                    
                    try {
                        // Verificar se o token CSRF existe
                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (!csrfToken) {
                            throw new Error('Token CSRF não encontrado');
                        }
                        
                        // Preparar dados para envio
                        const dataToSend = {
                            ...this.formData,
                            is_active: this.formData.is_active ? 1 : 0, // Converter boolean para integer
                            parent_id: this.formData.parent_id || null,  // Garantir que seja null se vazio
                            operacao_id: this.formData.operacao_id || null,
                            white_label_id: this.formData.white_label_id || null,
                            licenciado_pai_id: this.formData.licenciado_pai_id || null
                        };
                        
                        console.log('Dados originais:', this.formData);
                        console.log('Dados para envio:', dataToSend);
                        console.log('operacao_id específico:', this.formData.operacao_id);
                        console.log('operacao_id no dataToSend:', dataToSend.operacao_id);
                        
                        const response = await fetch('{{ route('hierarchy.management.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                            },
                            body: JSON.stringify(dataToSend)
                        });
                        
                        console.log('Status da resposta:', response.status);
                        console.log('Headers da resposta:', response.headers);
                        
                        // Verificar se a resposta é JSON
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            const textResponse = await response.text();
                            console.error('Resposta não é JSON:', textResponse);
                            throw new Error('Servidor retornou HTML em vez de JSON. Verifique os logs do servidor.');
                        }
                        
                        const result = await response.json();
                        console.log('Resultado:', result);
                        
                        if (response.ok) {
                            alert('Nó criado com sucesso!');
                            window.location.href = '{{ route('hierarchy.management.index') }}';
                } else {
                            // Mostrar erros detalhados para 422 (validação)
                            if (response.status === 422 && result.errors) {
                                console.error('Erros de validação:', result.errors);
                                let errorMessages = [];
                                for (const [field, messages] of Object.entries(result.errors)) {
                                    errorMessages.push(`${field}: ${messages.join(', ')}`);
                                }
                                alert('Erros de validação:\n' + errorMessages.join('\n'));
                    } else {
                                alert('Erro ao criar nó: ' + (result.message || result.error || 'Erro desconhecido'));
                    }
                }
            } catch (error) {
                        console.error('Erro completo:', error);
                        alert('Erro ao criar nó: ' + error.message);
            } finally {
                        this.loading = false;
                    }
        }
    }
}
</script>
</body>
</html>