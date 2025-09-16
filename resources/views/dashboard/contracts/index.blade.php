<x-dynamic-branding />
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contratos - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Branding Dinâmico -->
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .card { 
            background: white; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); 
            transition: all 0.3s ease; 
        }
        .card:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); 
        }
        .stat-card { 
            background: var(--primary-gradient);
            color: var(--primary-text);
        }
        .progress-bar { 
            background: var(--accent-gradient);
            transition: width 0.5s ease-in-out;
        }
        .dashboard-header {
            background: var(--background-color);
            color: var(--text-color);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar Dinâmico -->
        <x-dynamic-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="dashboard-header bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold" style="color: var(--text-color);">
                            <i class="fas fa-file-contract mr-3" style="color: var(--primary-color);"></i>
                            Contratos de Licenciados
                        </h1>
                        <p class="mt-1" style="color: var(--secondary-color);">Gerencie todo o fluxo de contratos e documentação</p>
                    </div>
                    <a href="{{ route('contracts.create') }}" class="btn-primary px-4 py-2 rounded-lg transition-colors flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Novo Contrato
                    </a>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                    <div class="stat-card rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Criados</p>
                                <p class="text-2xl font-bold">{{ $statusStats['criado'] }}</p>
                            </div>
                            <i class="fas fa-plus text-white/60 text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Enviados</p>
                                <p class="text-2xl font-bold">{{ $statusStats['contrato_enviado'] }}</p>
                            </div>
                            <i class="fas fa-envelope text-white/60 text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Aguardando</p>
                                <p class="text-2xl font-bold">{{ $statusStats['aguardando_assinatura'] }}</p>
                            </div>
                            <i class="fas fa-clock text-white/60 text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Assinados</p>
                                <p class="text-2xl font-bold">{{ $statusStats['contrato_assinado'] }}</p>
                            </div>
                            <i class="fas fa-signature text-white/60 text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-600 to-green-700 rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Aprovados</p>
                                <p class="text-2xl font-bold">{{ $statusStats['licenciado_aprovado'] }}</p>
                            </div>
                            <i class="fas fa-check-circle text-white/60 text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Cancelados</p>
                                <p class="text-2xl font-bold">{{ $statusStats['cancelado'] }}</p>
                            </div>
                            <i class="fas fa-times text-white/60 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card rounded-xl p-6 mb-6">
                    <form method="GET" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-64">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pesquisar</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Nome ou email do licenciado..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="min-w-48">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Todos os status</option>
                                <option value="criado" {{ request('status') === 'criado' ? 'selected' : '' }}>Contrato Criado</option>
                                <option value="contrato_enviado" {{ request('status') === 'contrato_enviado' ? 'selected' : '' }}>Contrato Enviado</option>
                                <option value="aguardando_assinatura" {{ request('status') === 'aguardando_assinatura' ? 'selected' : '' }}>Aguardando Assinatura</option>
                                <option value="contrato_assinado" {{ request('status') === 'contrato_assinado' ? 'selected' : '' }}>Contrato Assinado</option>
                                <option value="licenciado_aprovado" {{ request('status') === 'licenciado_aprovado' ? 'selected' : '' }}>Licenciado Aprovado</option>
                                <option value="cancelado" {{ request('status') === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                                <i class="fas fa-search mr-2"></i>Filtrar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Contracts List -->
                <div class="card rounded-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Licenciado</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progresso</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criado em</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($contracts as $contract)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fas fa-user text-white text-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $contract->licenciado->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $contract->licenciado->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $contract->status_color }}">
                                                {{ $contract->status_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                                                    <div class="progress-bar h-2 rounded-full" style="width: {{ $contract->progress_percentage }}%"></div>
                                                </div>
                                                <span class="text-sm text-gray-600">{{ $contract->progress_percentage }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $contract->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('contracts.show', $contract) }}" 
                                                   class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-lg text-sm transition-colors">
                                                    <i class="fas fa-eye mr-1"></i>Ver
                                                </a>
                                                
                                                <!-- Botão de Enviar por E-mail -->
                                                @if($contract->contract_pdf_path && file_exists(storage_path('app/' . $contract->contract_pdf_path)))
                                                    <button onclick="openEmailModal({{ $contract->id }}, '{{ $contract->licenciado->name }}', '{{ $contract->licenciado->email }}')" 
                                                            class="bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1 rounded-lg text-sm transition-colors">
                                                        <i class="fas fa-envelope mr-1"></i>E-mail
                                                    </button>
                                                @endif
                                                
                                                @if($contract->canApproveDocuments())
                                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-lg text-sm">
                                                        <i class="fas fa-clock mr-1"></i>Aguardando
                                                    </span>
                                                @elseif($contract->canSendContract())
                                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-sm">
                                                        <i class="fas fa-check mr-1"></i>Pronto
                                                    </span>
                                                @endif
                                                
                                                <!-- Botão de Excluir -->
                                                <button onclick="confirmDelete({{ $contract->id }}, '{{ $contract->licenciado->name }}')" 
                                                        class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1 rounded-lg text-sm transition-colors">
                                                    <i class="fas fa-trash mr-1"></i>Excluir
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-file-contract text-gray-300 text-6xl mb-4"></i>
                                                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum contrato encontrado</h3>
                                                <p class="text-gray-500 mb-4">Comece criando um novo contrato para um licenciado.</p>
                                                <a href="{{ route('contracts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                                    <i class="fas fa-plus mr-2"></i>Criar Primeiro Contrato
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($contracts->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $contracts->links() }}
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <!-- Modal de Envio por E-mail -->
    <div id="emailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl max-w-md w-full p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-envelope text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Enviar Contrato por E-mail</h3>
                </div>
                
                <div class="mb-4">
                    <p class="text-gray-600 mb-3">
                        Deseja enviar o contrato do licenciado <strong id="emailLicenseeName"></strong> para o e-mail:
                    </p>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-gray-400 mr-2"></i>
                            <span id="emailLicenseeEmail" class="font-medium text-gray-900"></span>
                        </div>
                    </div>
                </div>
                
                <div id="emailStatus" class="hidden mb-4">
                    <!-- Status será inserido dinamicamente -->
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button onclick="closeEmailModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                        Cancelar
                    </button>
                    <button id="sendEmailBtn" onclick="sendContractEmail()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Enviar E-mail
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl max-w-md w-full p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Confirmar Exclusão</h3>
                </div>
                
                <p class="text-gray-600 mb-6">
                    Tem certeza que deseja excluir o contrato do licenciado <strong id="contractLicensee"></strong>?
                    <br><br>
                    <span class="text-red-600 font-medium">Esta ação não pode ser desfeita e removerá todos os arquivos relacionados.</span>
                </p>
                
                <div class="flex justify-end space-x-3">
                    <button onclick="closeDeleteModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                        Cancelar
                    </button>
                    <button onclick="executeDelete()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-trash mr-2"></i>Excluir Contrato
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário oculto para DELETE -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        let contractToDelete = null;
        let contractToEmail = null;

        // Funções do Modal de E-mail
        function openEmailModal(contractId, licenseeName, licenseeEmail) {
            contractToEmail = contractId;
            document.getElementById('emailLicenseeName').textContent = licenseeName;
            document.getElementById('emailLicenseeEmail').textContent = licenseeEmail;
            document.getElementById('emailStatus').classList.add('hidden');
            document.getElementById('emailModal').classList.remove('hidden');
        }

        function closeEmailModal() {
            document.getElementById('emailModal').classList.add('hidden');
            contractToEmail = null;
            // Reset button state
            const btn = document.getElementById('sendEmailBtn');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Enviar E-mail';
        }

        function sendContractEmail() {
            if (!contractToEmail) return;

            const btn = document.getElementById('sendEmailBtn');
            const statusDiv = document.getElementById('emailStatus');
            
            // Desabilitar botão e mostrar loading
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
            
            // Fazer requisição AJAX
            fetch(`/contracts/${contractToEmail}/send-email`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                statusDiv.classList.remove('hidden');
                
                if (data.success) {
                    statusDiv.innerHTML = `
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>${data.message}</span>
                            </div>
                        </div>
                    `;
                    
                    // Fechar modal após 2 segundos
                    setTimeout(() => {
                        closeEmailModal();
                        location.reload(); // Recarregar para atualizar status
                    }, 2000);
                } else {
                    statusDiv.innerHTML = `
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span>${data.message || 'Erro ao enviar e-mail'}</span>
                            </div>
                        </div>
                    `;
                    
                    // Reabilitar botão
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Enviar E-mail';
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                statusDiv.classList.remove('hidden');
                statusDiv.innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span>Erro de conexão. Tente novamente.</span>
                        </div>
                    </div>
                `;
                
                // Reabilitar botão
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Enviar E-mail';
            });
        }

        // Funções do Modal de Exclusão
        function confirmDelete(contractId, licenseeName) {
            contractToDelete = contractId;
            document.getElementById('contractLicensee').textContent = licenseeName;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            contractToDelete = null;
        }

        function executeDelete() {
            if (contractToDelete) {
                const form = document.getElementById('deleteForm');
                form.action = `/contracts/${contractToDelete}`;
                form.submit();
            }
        }

        // Event Listeners
        document.getElementById('emailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEmailModal();
            }
        });

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEmailModal();
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>
