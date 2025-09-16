<!DOCTYPE html>
<html lang="pt-BR">
<head>
<x-dynamic-branding />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalhes da Campanha - DSPay Orbita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar Dinâmico -->
        <x-dynamic-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard.marketing.campanhas') }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Detalhes da Campanha</h1>
                            <p class="text-gray-600">Visualize e gerencie sua campanha de marketing</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if($campanha->status === 'ativa')
                            <button onclick="enviarCampanha({{ $campanha->id }})" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Enviar Campanha
                            </button>
                        @endif
                        <button onclick="editCampanha({{ $campanha->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                            <i class="fas fa-edit mr-2"></i>
                            Editar
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
                <div class="container mx-auto">
                    <!-- Informações Principais -->
                    <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 mb-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-bullhorn text-white text-2xl"></i>
                                </div>
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900">{{ $campanha->nome }}</h1>
                                    <div class="flex items-center space-x-3 mt-2">
                                        @if($campanha->status === 'ativa')
                                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-play mr-1"></i>Ativa
                                            </span>
                                        @elseif($campanha->status === 'pausada')
                                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-pause mr-1"></i>Pausada
                                            </span>
                                        @elseif($campanha->status === 'concluida')
                                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <i class="fas fa-check mr-1"></i>Concluída
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                                <i class="fas fa-edit mr-1"></i>Rascunho
                                            </span>
                                        @endif
                                        
                                        @if($campanha->tipo === 'lead')
                                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <i class="fas fa-users mr-1"></i>Campanha para Leads
                                            </span>
                                        @elseif($campanha->tipo === 'licenciado')
                                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-user-tie mr-1"></i>Campanha para Licenciados
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                                                <i class="fas fa-globe mr-1"></i>Campanha Geral
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600">{{ $campanha->total_destinatarios }}</div>
                                <div class="text-sm text-gray-600">Total de Destinatários</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-600">{{ $campanha->emails_enviados }}</div>
                                <div class="text-sm text-gray-600">E-mails Enviados</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600">{{ $campanha->taxa_abertura }}%</div>
                                <div class="text-sm text-gray-600">Taxa de Abertura</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-purple-600">{{ $campanha->taxa_clique }}%</div>
                                <div class="text-sm text-gray-600">Taxa de Clique</div>
                            </div>
                        </div>
                    </div>

                    <!-- Grid de Informações -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Detalhes da Campanha -->
                        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                Detalhes da Campanha
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Descrição</label>
                                    <p class="text-gray-900 mt-1">{{ $campanha->descricao ?: 'Sem descrição' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Modelo de E-mail</label>
                                    <p class="text-gray-900 mt-1">{{ $campanha->modelo->nome }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Data de Criação</label>
                                    <p class="text-gray-900 mt-1">{{ $campanha->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                @if($campanha->data_inicio)
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Data de Início</label>
                                    <p class="text-gray-900 mt-1">{{ \Carbon\Carbon::parse($campanha->data_inicio)->format('d/m/Y H:i') }}</p>
                                </div>
                                @endif
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Segmentação</label>
                                    @if($campanha->segmentacao && count($campanha->segmentacao) > 0)
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            @foreach($campanha->segmentacao as $segmento)
                                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                    {{ ucfirst($segmento) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-500 mt-1">Nenhuma segmentação aplicada</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Estatísticas -->
                        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                                Estatísticas
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-medium text-gray-600">Taxa de Entrega</span>
                                        <span class="text-sm font-medium text-gray-900">
                                            @if($campanha->total_destinatarios > 0)
                                                {{ round(($campanha->emails_enviados / $campanha->total_destinatarios) * 100, 1) }}%
                                            @else
                                                0%
                                            @endif
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $campanha->total_destinatarios > 0 ? ($campanha->emails_enviados / $campanha->total_destinatarios) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-medium text-gray-600">Taxa de Abertura</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $campanha->taxa_abertura }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $campanha->taxa_abertura }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-medium text-gray-600">Taxa de Clique</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $campanha->taxa_clique }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $campanha->taxa_clique }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ações da Campanha -->
                    <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 mt-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-cogs text-orange-600 mr-2"></i>
                            Ações da Campanha
                        </h3>
                        <div class="flex flex-wrap gap-4">
                            @if($campanha->status === 'rascunho')
                                <button onclick="activateCampanha({{ $campanha->id }})" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                                    <i class="fas fa-check mr-2"></i>
                                    Ativar Campanha
                                </button>
                            @endif
                            
                            @if($campanha->status === 'ativa')
                                <button onclick="pauseCampanha({{ $campanha->id }})" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                                    <i class="fas fa-pause mr-2"></i>
                                    Pausar Campanha
                                </button>
                                <button onclick="enviarCampanha({{ $campanha->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Enviar Campanha
                                </button>
                            @endif
                            
                            @if($campanha->status === 'pausada')
                                <button onclick="resumeCampanha({{ $campanha->id }})" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                                    <i class="fas fa-play mr-2"></i>
                                    Retomar Campanha
                                </button>
                            @endif
                            
                            <button onclick="duplicateCampanha({{ $campanha->id }})" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                                <i class="fas fa-copy mr-2"></i>
                                Duplicar Campanha
                            </button>
                            
                            <button onclick="viewModelo({{ $campanha->modelo_id }})" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                                <i class="fas fa-eye mr-2"></i>
                                Ver Modelo
                            </button>
                        </div>
                    </div>

                    <!-- Histórico de Envios -->
                    @if($campanha->emails_enviados > 0)
                    <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 mt-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-history text-indigo-600 mr-2"></i>
                            Histórico de Envios
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-mails Enviados</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $campanha->updated_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $campanha->emails_enviados }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Enviado
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="reenviarCampanha({{ $campanha->id }})" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-redo mr-1"></i>Reenviar
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <!-- Sistema de Notificações -->
    <div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <style>
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }
        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateX(4px);
        }
        
        /* Estilos para notificações */
        .notification {
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            font-weight: 500;
            max-width: 400px;
            animation: slideInRight 0.3s ease-out;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .notification.success {
            background: linear-gradient(135deg, #10b981 0%, var(--accent-color) 100%);
            color: white;
            border-left: 4px solid var(--accent-color);
        }
        
        .notification.error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border-left: 4px solid #b91c1c;
        }
        
        .notification.info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border-left: 4px solid #1d4ed8;
        }
        
        .notification.warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border-left: 4px solid #b45309;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .notification-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 0.25rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .notification-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>

    <script>
        // Sistema de notificações
        function showNotification(message, type = 'info', duration = 5000) {
            const container = document.getElementById('notificationContainer');
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            
            const icon = document.createElement('i');
            switch (type) {
                case 'success':
                    icon.className = 'fas fa-check-circle text-lg';
                    break;
                case 'error':
                    icon.className = 'fas fa-exclamation-circle text-lg';
                    break;
                case 'warning':
                    icon.className = 'fas fa-exclamation-triangle text-lg';
                    break;
                default:
                    icon.className = 'fas fa-info-circle text-lg';
            }
            
            const text = document.createElement('span');
            text.textContent = message;
            
            const closeBtn = document.createElement('button');
            closeBtn.className = 'notification-close ml-auto';
            closeBtn.innerHTML = '<i class="fas fa-times"></i>';
            closeBtn.onclick = () => notification.remove();
            
            notification.appendChild(icon);
            notification.appendChild(text);
            notification.appendChild(closeBtn);
            
            container.appendChild(notification);
            
            // Auto-remove após duração
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, duration);
        }

        function editCampanha(id) {
            window.location.href = `/dashboard/marketing/campanhas/${id}/edit`;
        }

        function pauseCampanha(id) {
            if (confirm('Tem certeza que deseja pausar esta campanha?')) {
                changeStatus(id, 'pausada');
            }
        }

        function resumeCampanha(id) {
            if (confirm('Tem certeza que deseja retomar esta campanha?')) {
                changeStatus(id, 'ativa');
            }
        }

        function activateCampanha(id) {
            if (confirm('Tem certeza que deseja ativar esta campanha?')) {
                changeStatus(id, 'ativa');
            }
        }

        function changeStatus(id, status) {
            fetch(`/dashboard/marketing/campanhas/${id}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na requisição');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message || 'Erro ao alterar status', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showNotification('Erro ao alterar status da campanha', 'error');
            });
        }

        function enviarCampanha(id) {
            if (confirm('Tem certeza que deseja enviar esta campanha? Esta ação não pode ser desfeita.')) {
                fetch(`/dashboard/marketing/campanhas/${id}/enviar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na requisição');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        showNotification(data.message || 'Erro ao enviar campanha', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showNotification('Erro ao enviar campanha', 'error');
                });
            }
        }

        function reenviarCampanha(id) {
            if (confirm('Tem certeza que deseja reenviar esta campanha? Esta ação não pode ser desfeita.')) {
                enviarCampanha(id);
            }
        }

        function duplicateCampanha(id) {
            if (confirm('Tem certeza que deseja duplicar esta campanha?')) {
                // Implementar duplicação da campanha
                showNotification(`Campanha ${id} duplicada com sucesso!`, 'success');
            }
        }

        function viewModelo(id) {
            // Abrir modal ou redirecionar para visualizar o modelo
            showNotification(`Visualizando modelo ${id}`, 'info');
        }
    </script>
</body>
</html>
