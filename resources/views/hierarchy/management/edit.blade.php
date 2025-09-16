
<x-dynamic-branding />
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editar {{ $nodeDetails['basic_info']['node_type'] === 'operacao' ? 'Operação' : ($nodeDetails['basic_info']['node_type'] === 'white_label' ? 'White Label' : 'Licenciado') }} - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
            <div class="min-h-screen bg-gray-50" x-data="nodeEdit()">
                <!-- Header com botões de ação fixos -->
                <div class="bg-white shadow-sm border-b sticky top-0 z-10">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center justify-between h-16">
                            <div class="flex items-center">
                                <h1 class="text-xl font-semibold text-gray-900">
                                    Editar {{ $nodeDetails['basic_info']['node_type'] === 'operacao' ? 'Operação' : ($nodeDetails['basic_info']['node_type'] === 'white_label' ? 'White Label' : 'Licenciado') }}
                                </h1>
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    ID: {{ $node->id }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('hierarchy.management.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                    Cancelar
                                </a>
                                <button @click="submitForm()" :disabled="loading" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="loading ? 'Salvando...' : 'Salvar Alterações'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 overflow-auto p-6">
                    <div class="max-w-4xl mx-auto">
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

                                                <!-- Nova senha (opcional) -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nova Senha (deixe em branco para manter atual)</label>
                                                    <input type="password" x-model="formData.password" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nova senha (opcional)">
                                                </div>

                                                <!-- Confirmar nova senha -->
                                                <div x-show="formData.password">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Nova Senha *</label>
                                                    <input type="password" x-model="formData.password_confirmation" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Confirme a nova senha">
                                                </div>

                                                @if(in_array($node->node_type, ['operacao', 'white_label']))
                                                <!-- Nome interno -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome Interno</label>
                                                    <input type="text" x-model="formData.name" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="nome-interno-sem-espacos">
                                                </div>

                                                <!-- Nome de exibição -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome de Exibição</label>
                                                    <input type="text" x-model="formData.display_name" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nome para exibição">
                                                </div>

                                                <!-- Descrição -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                                                    <textarea x-model="formData.description" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Descrição opcional"></textarea>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if(in_array($node->node_type, ['operacao', 'white_label']))
                                    <!-- Configurações de Domínio -->
                                    <div class="card rounded-lg">
                                        <div class="px-4 py-3 border-b border-gray-200">
                                            <h3 class="text-md font-medium text-gray-900">Configurações de Domínio</h3>
                                        </div>
                                        <div class="p-4">
                                            <div class="space-y-4">
                                                <!-- Domínio -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Domínio</label>
                                                    <input type="text" x-model="formData.domain" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="exemplo.com">
                                                </div>

                                                <!-- Subdomínio -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Subdomínio</label>
                                                    <input type="text" x-model="formData.subdomain" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="subdominio">
                                                </div>
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

                                    <!-- Informações do Sistema -->
                                    <div class="card rounded-lg">
                                        <div class="px-4 py-3 border-b border-gray-200">
                                            <h3 class="text-md font-medium text-gray-900">Informações do Sistema</h3>
                                        </div>
                                        <div class="p-4">
                                            <div class="space-y-2 text-sm">
                                                <div><span class="font-medium">ID:</span> {{ $node->id }}</div>
                                                <div><span class="font-medium">Tipo:</span> {{ ucfirst(str_replace('_', ' ', $node->node_type)) }}</div>
                                                <div><span class="font-medium">Nível Hierárquico:</span> {{ $node->hierarchy_level }}</div>
                                                <div><span class="font-medium">Criado em:</span> {{ $node->created_at->format('d/m/Y H:i') }}</div>
                                                <div><span class="font-medium">Atualizado em:</span> {{ $node->updated_at->format('d/m/Y H:i') }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Estatísticas -->
                                    <div class="card rounded-lg">
                                        <div class="px-4 py-3 border-b border-gray-200">
                                            <h3 class="text-md font-medium text-gray-900">Estatísticas</h3>
                                        </div>
                                        <div class="p-4">
                                            <div class="space-y-2 text-sm">
                                                <div><span class="font-medium">Filhos Diretos:</span> {{ $nodeDetails['statistics']['direct_children'] }}</div>
                                                <div><span class="font-medium">Total Descendentes:</span> {{ $nodeDetails['statistics']['total_descendants'] }}</div>
                                                <div><span class="font-medium">Descendentes Ativos:</span> {{ $nodeDetails['statistics']['active_descendants'] }}</div>
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
        function nodeEdit() {
            return {
                loading: false,
                formData: {
                    user_name: '{{ $node->name }}',
                    email: '{{ $node->email }}',
                    password: '',
                    password_confirmation: '',
                    name: '{{ $nodeDetails["operacao_info"]["name"] ?? $nodeDetails["white_label_info"]["name"] ?? "" }}',
                    display_name: '{{ $nodeDetails["operacao_info"]["display_name"] ?? $nodeDetails["white_label_info"]["display_name"] ?? "" }}',
                    description: '{{ $nodeDetails["operacao_info"]["description"] ?? $nodeDetails["white_label_info"]["description"] ?? "" }}',
                    domain: '{{ $nodeDetails["operacao_info"]["domain"] ?? $nodeDetails["white_label_info"]["domain"] ?? "" }}',
                    subdomain: '{{ $nodeDetails["operacao_info"]["subdomain"] ?? $nodeDetails["white_label_info"]["subdomain"] ?? "" }}',
                    is_active: {{ $node->is_active ? 'true' : 'false' }},
                },

                async submitForm() {
                    if (this.loading) return;

                    // Validações básicas
                    if (!this.formData.user_name || !this.formData.email) {
                        alert('Por favor, preencha todos os campos obrigatórios');
                        return;
                    }

                    if (this.formData.password && this.formData.password !== this.formData.password_confirmation) {
                        alert('As senhas não coincidem');
                        return;
                    }

                    try {
                        this.loading = true;

                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        if (!csrfToken) {
                            throw new Error('Token CSRF não encontrado');
                        }

                        // Preparar dados para envio
                        const dataToSend = {
                            ...this.formData,
                            is_active: this.formData.is_active ? 1 : 0,
                            _method: 'PUT'
                        };

                        console.log('Dados para envio:', dataToSend);

                        const response = await fetch('{{ route("hierarchy.management.update", $node->id) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(dataToSend)
                        });

                        console.log('Status da resposta:', response.status);

                        const result = await response.json();
                        console.log('Resultado:', result);

                        if (response.ok && result.success) {
                            // Sucesso - redirecionar para lista
                            window.location.href = '{{ route("hierarchy.management.index") }}';
                        } else {
                            // Erro de validação ou outro erro
                            if (result.errors) {
                                let errorMessage = 'Erros de validação:\n';
                                for (const [field, errors] of Object.entries(result.errors)) {
                                    errorMessage += `- ${field}: ${errors.join(', ')}\n`;
                                }
                                alert(errorMessage);
                            } else {
                                alert(result.error || 'Erro ao atualizar nó');
                            }
                        }

                    } catch (error) {
                        console.error('Erro ao atualizar:', error);
                        alert('Erro ao atualizar nó: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
