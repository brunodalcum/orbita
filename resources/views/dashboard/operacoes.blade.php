<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Operações - dspay</title>
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
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
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
        .operation-card {
            transition: all 0.3s ease;
        }
        .operation-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
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
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Operações</h1>
                        <p class="text-gray-600">Gerencie as operações do sistema</p>
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
                                <span class="text-sm font-medium text-gray-500">Operações</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Header da Lista -->
                <div class="bg-white rounded-xl shadow-sm border mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">Lista de Operações</h2>
                                <p class="text-sm text-gray-600">Total de {{ $operacoes->count() }} operações cadastradas</p>
                            </div>
                            <button 
                                onclick="openModal()" 
                                class="btn-primary text-white px-6 py-3 rounded-lg font-medium flex items-center shadow-lg hover:shadow-xl"
                            >
                                <i class="fas fa-plus mr-2"></i>
                                Nova Operação
                            </button>
                        </div>
                    </div>

                    <!-- Lista de Operações em Cards -->
                    <div class="p-6">
                        @if($operacoes->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($operacoes as $operacao)
                                    <div class="operation-card bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-lg transition-all duration-300 group">
                                        <div class="p-6">
                                            <!-- Header do Card -->
                                            <div class="flex items-start justify-between mb-4">
                                                <div class="flex items-center">
                                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                                        <i class="fas fa-cogs text-white text-lg"></i>
                                                    </div>
                                                    <div class="ml-3">
                                                        <h3 class="text-lg font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">
                                                            {{ $operacao->nome }}
                                                        </h3>
                                                        <p class="text-sm text-gray-500">ID: #{{ $operacao->id }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-1">
                                                    <button 
                                                        onclick="editOperacao({{ $operacao->id }}, '{{ $operacao->nome }}', '{{ $operacao->adquirente }}')"
                                                        class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200"
                                                        title="Editar"
                                                    >
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button 
                                                        onclick="openPlanosModal({{ $operacao->id }}, '{{ $operacao->nome }}')"
                                                        class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200"
                                                        title="Planos"
                                                    >
                                                        <i class="fas fa-list-alt"></i>
                                                    </button>
                                                    <button 
                                                        onclick="deleteOperacao({{ $operacao->id }}, '{{ $operacao->nome }}')"
                                                        class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                                                        title="Excluir"
                                                    >
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Informações da Operação -->
                                            <div class="space-y-3">
                                                <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                        <i class="fas fa-building text-blue-600 text-sm"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-medium text-blue-800 uppercase tracking-wide">Adquirente</p>
                                                        <p class="text-sm font-semibold text-gray-800">{{ $operacao->adquirente }}</p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center p-3 bg-green-50 rounded-lg">
                                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                                        <i class="fas fa-calendar-alt text-green-600 text-sm"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-medium text-green-800 uppercase tracking-wide">Data de Cadastro</p>
                                                        <p class="text-sm font-semibold text-gray-800">{{ $operacao->created_at->format('d/m/Y') }}</p>
                                                        <p class="text-xs text-gray-500">{{ $operacao->created_at->format('H:i') }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Status e Badges -->
                                            <div class="mt-4 pt-4 border-t border-gray-100">
                                                <div class="flex items-center justify-between">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Ativa
                                                    </span>
                                                    <span class="text-xs text-gray-500">
                                                        {{ $operacao->updated_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Estado Vazio -->
                            <div class="text-center py-16">
                                <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-cogs text-3xl text-gray-400"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-600 mb-2">Nenhuma operação encontrada</h3>
                                <p class="text-gray-500 mb-8 max-w-md mx-auto">
                                    Comece criando sua primeira operação para gerenciar os adquirentes do sistema.
                                </p>
                                <button 
                                    onclick="openModal()" 
                                    class="btn-primary text-white px-8 py-4 rounded-lg font-medium flex items-center mx-auto shadow-lg hover:shadow-xl"
                                >
                                    <i class="fas fa-plus mr-2"></i>
                                    Criar Primeira Operação
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal de Operação -->
    <div id="operacaoModal" class="modal">
        <div class="modal-content">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <h3 id="modalTitle" class="text-lg font-semibold text-white">Nova Operação</h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <form id="operacaoForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="operacaoId" name="id">
                    
                    <!-- Nome -->
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome da Operação *
                        </label>
                        <input 
                            type="text" 
                            id="nome" 
                            name="nome" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Digite o nome da operação"
                        >
                    </div>

                    <!-- Adquirente -->
                    <div>
                        <label for="adquirente" class="block text-sm font-medium text-gray-700 mb-2">
                            Adquirente *
                        </label>
                        <input 
                            type="text" 
                            id="adquirente" 
                            name="adquirente" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Digite o nome do adquirente"
                        >
                    </div>

                    <!-- Área de Erro -->
                    <div id="modalErrorArea" class="hidden p-3 bg-red-50 border border-red-200 rounded-md">
                        <div id="modalErrorMessage" class="text-red-700 text-sm"></div>
                    </div>

                    <!-- Botões -->
                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <button 
                            type="button" 
                            onclick="closeModal()"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors"
                        >
                            Cancelar
                        </button>
                        <button 
                            type="submit"
                            id="submitBtn"
                            class="btn-primary text-white px-4 py-2 rounded-md font-medium"
                        >
                            Salvar Operação
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="deleteConfirmModal" class="confirm-modal">
        <div class="confirm-content">
            <div class="mb-4">
                <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Confirmar Exclusão</h3>
                <p class="text-gray-600" id="deleteConfirmMessage">
                    Tem certeza que deseja excluir esta operação?
                </p>
            </div>
            <div class="flex items-center justify-center space-x-3">
                <button 
                    onclick="closeDeleteConfirmModal()"
                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors"
                >
                    Cancelar
                </button>
                <button 
                    onclick="confirmDelete()"
                    class="px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors"
                >
                    Excluir
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Planos -->
    <div id="planosModal" class="modal">
        <div class="modal-content" style="max-width: 700px;">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <h3 id="planosModalTitle" class="text-lg font-semibold text-white">Planos da Operação</h3>
                    <button onclick="closePlanosModal()" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Header dos Planos -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800" id="operacaoNomePlanos"></h4>
                        <p class="text-sm text-gray-600">Gerencie os planos desta operação</p>
                    </div>
                    <button 
                        onclick="openNovoPlanoModal()"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg font-medium flex items-center hover:bg-green-700 transition-colors"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        Novo Plano
                    </button>
                </div>

                <!-- Lista de Planos -->
                <div id="planosList" class="space-y-4">
                    <!-- Os planos serão carregados dinamicamente aqui -->
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-list-alt text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-600 mb-2">Nenhum plano encontrado</h3>
                        <p class="text-gray-500">Clique em "Novo Plano" para começar</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Novo Plano -->
    <div id="novoPlanoModal" class="modal">
        <div class="modal-content">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Novo Plano</h3>
                    <button onclick="closeNovoPlanoModal()" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <form id="planoForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="operacaoIdPlano" name="operacao_id">
                    
                    <!-- Nome do Plano -->
                    <div>
                        <label for="nomePlano" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome do Plano *
                        </label>
                        <input 
                            type="text" 
                            id="nomePlano" 
                            name="nome" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="Digite o nome do plano"
                        >
                    </div>

                    <!-- Descrição -->
                    <div>
                        <label for="descricaoPlano" class="block text-sm font-medium text-gray-700 mb-2">
                            Descrição
                        </label>
                        <textarea 
                            id="descricaoPlano" 
                            name="descricao" 
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="Digite a descrição do plano"
                        ></textarea>
                    </div>

                    <!-- Valor -->
                    <div>
                        <label for="valorPlano" class="block text-sm font-medium text-gray-700 mb-2">
                            Valor *
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">R$</span>
                            <input 
                                type="number" 
                                id="valorPlano" 
                                name="valor" 
                                step="0.01"
                                min="0"
                                required
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                placeholder="0,00"
                            >
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="statusPlano" class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <select 
                            id="statusPlano" 
                            name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                            <option value="ativo">Ativo</option>
                            <option value="inativo">Inativo</option>
                        </select>
                    </div>

                    <!-- Área de Erro -->
                    <div id="planoErrorArea" class="hidden p-3 bg-red-50 border border-red-200 rounded-md">
                        <div id="planoErrorMessage" class="text-red-700 text-sm"></div>
                    </div>

                    <!-- Botões -->
                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <button 
                            type="button" 
                            onclick="closeNovoPlanoModal()"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors"
                        >
                            Cancelar
                        </button>
                        <button 
                            type="submit"
                            class="bg-green-600 text-white px-4 py-2 rounded-md font-medium hover:bg-green-700 transition-colors"
                        >
                            Salvar Plano
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div id="toast" class="toast"></div>

    <script>
        let isEditing = false;
        let deleteOperacaoId = null;

        // Funções do Modal
        function openModal() {
            console.log('Abrindo modal para nova operação');
            
            // Verificar se os elementos existem
            const modalTitle = document.getElementById('modalTitle');
            const submitBtn = document.getElementById('submitBtn');
            const operacaoForm = document.getElementById('operacaoForm');
            const operacaoId = document.getElementById('operacaoId');
            const modal = document.getElementById('operacaoModal');
            
            if (!modalTitle || !submitBtn || !operacaoForm || !operacaoId || !modal) {
                console.error('Elementos do modal não encontrados');
                return;
            }
            
            isEditing = false;
            modalTitle.textContent = 'Nova Operação';
            submitBtn.textContent = 'Salvar Operação';
            operacaoForm.reset();
            operacaoId.value = '';
            hideModalError();
            modal.style.display = 'block';
        }

        function closeModal() {
            document.getElementById('operacaoModal').style.display = 'none';
            hideModalError();
        }

        function editOperacao(id, nome, adquirente) {
            console.log('Editando operação:', { id, nome, adquirente });
            
            // Verificar se os elementos existem
            const modalTitle = document.getElementById('modalTitle');
            const submitBtn = document.getElementById('submitBtn');
            const operacaoId = document.getElementById('operacaoId');
            const nomeInput = document.getElementById('nome');
            const adquirenteInput = document.getElementById('adquirente');
            const modal = document.getElementById('operacaoModal');
            
            if (!modalTitle || !submitBtn || !operacaoId || !nomeInput || !adquirenteInput || !modal) {
                console.error('Elementos do modal não encontrados');
                return;
            }
            
            isEditing = true;
            modalTitle.textContent = 'Editar Operação';
            submitBtn.textContent = 'Atualizar Operação';
            operacaoId.value = id;
            nomeInput.value = nome;
            adquirenteInput.value = adquirente;
            hideModalError();
            modal.style.display = 'block';
        }

        // Funções de Confirmação de Exclusão
        function deleteOperacao(id, nome) {
            deleteOperacaoId = id;
            document.getElementById('deleteConfirmMessage').textContent = 
                `Tem certeza que deseja excluir a operação "${nome}"?`;
            document.getElementById('deleteConfirmModal').style.display = 'block';
        }

        function closeDeleteConfirmModal() {
            document.getElementById('deleteConfirmModal').style.display = 'none';
            deleteOperacaoId = null;
        }

        function confirmDelete() {
            if (deleteOperacaoId) {
                fetch(`/operacoes/${deleteOperacaoId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    showToast('Erro ao excluir operação', 'error');
                });
            }
            closeDeleteConfirmModal();
        }

        // Funções de Erro
        function showModalError(message) {
            document.getElementById('modalErrorMessage').textContent = message;
            document.getElementById('modalErrorArea').classList.remove('hidden');
        }

        function hideModalError() {
            document.getElementById('modalErrorArea').classList.add('hidden');
        }

        // Toast
        function showToast(message, type) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = `toast ${type}`;
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // Form Submit
        document.getElementById('operacaoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const url = isEditing ? `/operacoes/${formData.get('id')}` : '/operacoes';
            const method = isEditing ? 'PUT' : 'POST';
            
            if (isEditing) {
                formData.append('_method', 'PUT');
            }

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    closeModal();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showModalError(data.message);
                }
            })
            .catch(error => {
                showModalError('Erro ao salvar operação');
            });
        });

        // Fechar modais ao clicar fora
        window.onclick = function(event) {
            const modal = document.getElementById('operacaoModal');
            const confirmModal = document.getElementById('deleteConfirmModal');
            const planosModal = document.getElementById('planosModal');
            const novoPlanoModal = document.getElementById('novoPlanoModal');
            
            if (event.target === modal) {
                closeModal();
            }
            if (event.target === confirmModal) {
                closeDeleteConfirmModal();
            }
            if (event.target === planosModal) {
                closePlanosModal();
            }
            if (event.target === novoPlanoModal) {
                closeNovoPlanoModal();
            }
        }

        // Variáveis para planos
        let currentOperacaoId = null;
        let currentOperacaoNome = null;

        // Funções do Modal de Planos
        function openPlanosModal(operacaoId, operacaoNome) {
            console.log('Abrindo modal de planos para:', { operacaoId, operacaoNome });
            currentOperacaoId = operacaoId;
            currentOperacaoNome = operacaoNome;
            
            document.getElementById('operacaoNomePlanos').textContent = operacaoNome;
            document.getElementById('operacaoIdPlano').value = operacaoId;
            document.getElementById('planosModal').style.display = 'block';
            
            // Aqui você pode carregar os planos da operação via AJAX
            loadPlanos(operacaoId);
        }

        function closePlanosModal() {
            document.getElementById('planosModal').style.display = 'none';
            currentOperacaoId = null;
            currentOperacaoNome = null;
        }

        function openNovoPlanoModal() {
            document.getElementById('novoPlanoModal').style.display = 'block';
            hidePlanoError();
        }

        function closeNovoPlanoModal() {
            document.getElementById('novoPlanoModal').style.display = 'none';
            document.getElementById('planoForm').reset();
            hidePlanoError();
        }

        function loadPlanos(operacaoId) {
            // Simular carregamento de planos (substitua por chamada AJAX real)
            console.log('Carregando planos para operação:', operacaoId);
            
            // Por enquanto, mostra mensagem de "nenhum plano"
            // Você pode implementar a chamada AJAX aqui para buscar os planos do banco
        }

        function showPlanoError(message) {
            document.getElementById('planoErrorMessage').textContent = message;
            document.getElementById('planoErrorArea').classList.remove('hidden');
        }

        function hidePlanoError() {
            document.getElementById('planoErrorArea').classList.add('hidden');
        }

        // Form Submit para Planos
        document.getElementById('planoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Aqui você implementaria a chamada AJAX para salvar o plano
            console.log('Salvando plano:', Object.fromEntries(formData));
            
            // Simular sucesso
            showToast('Plano salvo com sucesso!', 'success');
            closeNovoPlanoModal();
            
            // Recarregar planos
            if (currentOperacaoId) {
                loadPlanos(currentOperacaoId);
            }
        });

        // Adicionar listeners para os botões de editar após o carregamento da página
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Página carregada, verificando botões de editar...');
            
            // Adicionar listeners para todos os botões de editar
            const editButtons = document.querySelectorAll('button[onclick*="editOperacao"]');
            editButtons.forEach(button => {
                console.log('Botão de editar encontrado:', button);
                button.addEventListener('click', function(e) {
                    console.log('Botão de editar clicado!');
                });
            });
        });
    </script>
</body>
</html>
