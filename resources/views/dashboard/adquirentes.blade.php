<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Adquirentes - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar {
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar-link {
            transition: all 0.3s ease;
        }
        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid white;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: white;
            margin: 2% auto;
            padding: 0;
            border-radius: 12px;
            width: 95%;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 16px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            transform: translateX(400px);
            transition: transform 0.3s ease;
        }
        .toast.show {
            transform: translateX(0);
        }
        .toast.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .toast.error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        .confirm-modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .confirm-content {
            background-color: white;
            margin: 15% auto;
            padding: 32px;
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar w-64 flex-shrink-0">
            <div class="p-6">
                <!-- Logo -->
                <div class="flex items-center mb-8">
                    <img src="{{ asset('images/dspay-logo.png') }}" alt="dspay" class="h-10 w-auto mx-auto">
                </div>

                <!-- Menu -->
                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('dashboard.licenciados') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-id-card mr-3"></i>
                        Licenciados
                    </a>
                    <a href="{{ route('dashboard.operacoes') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-cogs mr-3"></i>
                        Operações
                    </a>
                    <a href="{{ route('dashboard.planos') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-chart-line mr-3"></i>
                        Planos
                    </a>
                    <a href="{{ route('dashboard.adquirentes') }}" class="sidebar-link active flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-building mr-3"></i>
                        Adquirentes
                    </a>
                    <a href="{{ route('dashboard.agenda') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-calendar-alt mr-3"></i>
                        Agenda
                    </a>
                    <a href="{{ route('dashboard.leads') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-user-plus mr-3"></i>
                        Leads
                    </a>
                    <a href="{{ route('dashboard.marketing') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-bullhorn mr-3"></i>
                        Marketing
                    </a>
                    <a href="{{ route('dashboard.configuracoes') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-cog mr-3"></i>
                        Configurações
                    </a>
                </nav>
            </div>

            <!-- User Profile -->
            <div class="absolute bottom-0 w-64 p-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-white font-medium">{{ Auth::user()->name ?? 'Usuário' }}</p>
                        <p class="text-white/70 text-sm">Administrador</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Adquirentes</h1>
                        <p class="text-gray-600">Gerencie os adquirentes do sistema</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-bell"></i>
                        </button>
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-cog"></i>
                        </button>
                        <form method="POST" action="{{ route('logout.custom') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Breadcrumb -->
                <nav class="flex mb-6" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                <i class="fas fa-home mr-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-sm font-medium text-gray-500">Adquirentes</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Header da Lista -->
                <div class="bg-white rounded-xl shadow-sm border mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">Lista de Adquirentes</h2>
                                <p class="text-sm text-gray-600">Total de {{ $adquirentes->count() }} adquirentes cadastrados</p>
                            </div>
                            <button 
                                onclick="openAddAdquirenteModal()" 
                                class="btn-primary text-white px-6 py-3 rounded-lg font-medium flex items-center shadow-lg hover:shadow-xl"
                            >
                                <i class="fas fa-plus mr-2"></i>
                                Adicionar Adquirente
                            </button>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="p-6">
                        @if($adquirentes->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Criação</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($adquirentes as $adquirente)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                #{{ $adquirente->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                                                        <i class="fas fa-building text-white text-sm"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $adquirente->nome }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $adquirente->status === 'ativo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    <i class="fas fa-circle mr-1 text-xs"></i>
                                                    {{ ucfirst($adquirente->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $adquirente->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <button 
                                                        onclick="viewAdquirente({{ $adquirente->id }})"
                                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                                        title="Visualizar"
                                                    >
                                                        <i class="fas fa-eye mr-1"></i>
                                                        Visualizar
                                                    </button>
                                                    <button 
                                                        onclick="editAdquirente({{ $adquirente->id }})"
                                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200"
                                                        title="Editar"
                                                    >
                                                        <i class="fas fa-edit mr-1"></i>
                                                        Editar
                                                    </button>
                                                    <button 
                                                        onclick="toggleAdquirenteStatus({{ $adquirente->id }}, '{{ $adquirente->status }}')"
                                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md {{ $adquirente->status === 'ativo' ? 'text-red-700 bg-red-100 hover:bg-red-200' : 'text-green-700 bg-green-100 hover:bg-green-200' }} focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $adquirente->status === 'ativo' ? 'focus:ring-red-500' : 'focus:ring-green-500' }} transition-colors duration-200"
                                                        title="{{ $adquirente->status === 'ativo' ? 'Desativar' : 'Ativar' }}"
                                                    >
                                                        <i class="fas {{ $adquirente->status === 'ativo' ? 'fa-pause' : 'fa-play' }} mr-1"></i>
                                                        {{ $adquirente->status === 'ativo' ? 'Desativar' : 'Ativar' }}
                                                    </button>
                                                    <button 
                                                        onclick="deleteAdquirente({{ $adquirente->id }})"
                                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                                        title="Excluir"
                                                    >
                                                        <i class="fas fa-trash mr-1"></i>
                                                        Excluir
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-building text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum adquirente cadastrado</h3>
                                <p class="text-gray-500 mb-6">Comece adicionando o primeiro adquirente ao sistema.</p>
                                <button 
                                    onclick="openAddAdquirenteModal()" 
                                    class="btn-primary text-white px-6 py-3 rounded-lg font-medium flex items-center mx-auto"
                                >
                                    <i class="fas fa-plus mr-2"></i>
                                    Adicionar Primeiro Adquirente
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Adicionar/Editar Adquirente -->
    <div id="adquirenteModal" class="modal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-4 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <h3 id="modalTitle" class="text-lg font-medium">Adicionar Adquirente</h3>
                    <button onclick="closeAdquirenteModal()" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <form id="adquirenteForm">
                    <input type="hidden" id="adquirenteId" name="adquirenteId">
                    
                    <div class="mb-4">
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">Nome da Adquirente</label>
                        <input type="text" id="nome" name="nome" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Digite o nome da adquirente">
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeAdquirenteModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                            Cancelar
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
    </div>
</div>

    <!-- Modal Visualizar Adquirente -->
    <div id="viewAdquirenteModal" class="modal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-4 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium">Detalhes da Adquirente</h3>
                    <button onclick="closeViewAdquirenteModal()" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <div id="adquirenteDetails" class="space-y-4">
                    <!-- Conteúdo será preenchido via JavaScript -->
                </div>
                
                <div class="flex justify-end mt-6">
                    <button onclick="closeViewAdquirenteModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    <div id="confirmationModal" class="confirm-modal">
        <div class="confirm-content">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Confirmar Exclusão</h3>
            <p class="text-sm text-gray-500 mb-6">
                Tem certeza que deseja excluir esta adquirente? Esta ação não pode ser desfeita.
            </p>
            <div class="flex justify-center space-x-3">
                <button onclick="closeConfirmationModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                    Cancelar
                </button>
                <button onclick="confirmDelete()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                    Excluir
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

<script>
    let currentAdquirenteId = null;

    // Funções do Modal
    function openAddAdquirenteModal() {
        document.getElementById('modalTitle').textContent = 'Adicionar Adquirente';
        document.getElementById('adquirenteId').value = '';
        document.getElementById('nome').value = '';
        document.getElementById('adquirenteModal').style.display = 'block';
    }

    function closeAdquirenteModal() {
        document.getElementById('adquirenteModal').style.display = 'none';
    }

    function closeViewAdquirenteModal() {
        document.getElementById('viewAdquirenteModal').style.display = 'none';
    }

    function closeConfirmationModal() {
        document.getElementById('confirmationModal').style.display = 'none';
        currentAdquirenteId = null;
    }

    // Funções CRUD
    function viewAdquirente(adquirenteId) {
        fetch(`/adquirentes/${adquirenteId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const adquirente = data.adquirente;
                const detailsContainer = document.getElementById('adquirenteDetails');
                
                detailsContainer.innerHTML = `
                    <div class="space-y-4">
                        <div>
                            <span class="text-sm font-medium text-gray-600">ID:</span>
                            <p class="text-sm text-gray-900">#${adquirente.id}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">Nome:</span>
                            <p class="text-sm text-gray-900">${adquirente.nome}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${adquirente.status === 'ativo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                <i class="fas fa-circle mr-1 text-xs"></i>
                                ${adquirente.status.charAt(0).toUpperCase() + adquirente.status.slice(1)}
                            </span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">Data de Criação:</span>
                            <p class="text-sm text-gray-900">${new Date(adquirente.created_at).toLocaleDateString('pt-BR')}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">Última Atualização:</span>
                            <p class="text-sm text-gray-900">${new Date(adquirente.updated_at).toLocaleDateString('pt-BR')}</p>
                        </div>
                    </div>
                `;
                
                document.getElementById('viewAdquirenteModal').style.display = 'block';
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar adquirente:', error);
            showToast('Erro ao carregar dados da adquirente', 'error');
        });
    }

    function editAdquirente(adquirenteId) {
        fetch(`/adquirentes/${adquirenteId}/edit`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const adquirente = data.adquirente;
                
                document.getElementById('modalTitle').textContent = 'Editar Adquirente';
                document.getElementById('adquirenteId').value = adquirente.id;
                document.getElementById('nome').value = adquirente.nome;
                
                document.getElementById('adquirenteModal').style.display = 'block';
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar adquirente:', error);
            showToast('Erro ao carregar dados da adquirente', 'error');
        });
    }

    function toggleAdquirenteStatus(adquirenteId, currentStatus) {
        const newStatus = currentStatus === 'ativo' ? 'inativo' : 'ativo';
        const actionText = currentStatus === 'ativo' ? 'desativar' : 'ativar';
        
        if (confirm(`Tem certeza que deseja ${actionText} esta adquirente?`)) {
            fetch(`/adquirentes/${adquirenteId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erro ao alterar status:', error);
                showToast('Erro ao alterar status da adquirente', 'error');
            });
        }
    }

    function deleteAdquirente(adquirenteId) {
        currentAdquirenteId = adquirenteId;
        document.getElementById('confirmationModal').style.display = 'block';
    }

    function confirmDelete() {
        if (!currentAdquirenteId) return;
        
        fetch(`/adquirentes/${currentAdquirenteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            closeConfirmationModal();
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao excluir adquirente:', error);
            showToast('Erro ao excluir adquirente', 'error');
            closeConfirmationModal();
        });
    }

    // Form Submit
    document.getElementById('adquirenteForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const adquirenteId = document.getElementById('adquirenteId').value;
        const isEdit = adquirenteId && adquirenteId !== '';
        
        const url = isEdit ? `/adquirentes/${adquirenteId}` : '/adquirentes';
        const method = isEdit ? 'PUT' : 'POST';
        
        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                closeAdquirenteModal();
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao salvar adquirente:', error);
            showToast('Erro ao salvar adquirente', 'error');
        });
    });

    // Toast Notification
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.className = `toast ${type}`;
        toast.classList.add('show');
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    // Fechar modais ao clicar fora (exceto o modal de adquirente)
    window.onclick = function(event) {
        const adquirenteModal = document.getElementById('adquirenteModal');
        const viewAdquirenteModal = document.getElementById('viewAdquirenteModal');
        const confirmationModal = document.getElementById('confirmationModal');
        
        // Modal de adquirente não fecha ao clicar fora - apenas pelo botão X
        if (event.target === viewAdquirenteModal) {
            closeViewAdquirenteModal();
        }
        if (event.target === confirmationModal) {
            closeConfirmationModal();
        }
    }
</script>

<style>
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        padding: 16px 24px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        transform: translateX(400px);
        transition: transform 0.3s ease;
    }
    
    .toast.show {
        transform: translateX(0);
    }
    
    .toast.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .toast.error {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }
</style>
</body>
</html>
