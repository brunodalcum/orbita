<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Campanhas - DSPay Orbita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar w-64 flex-shrink-0">
            <div class="bg-gradient-to-b from-blue-800 to-purple-900 h-full flex flex-col">
                <!-- Logo -->
                <div class="p-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                            <i class="fas fa-rocket text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-white font-bold text-lg">DSPay</h1>
                            <p class="text-white/70 text-xs">Orbita</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 space-y-2">
                    <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('dashboard.licenciados') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-users mr-3"></i>
                        Licenciados
                    </a>
                    <a href="{{ route('dashboard.operacoes') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-chart-line mr-3"></i>
                        Operações
                    </a>
                    <a href="{{ route('dashboard.planos') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-cube mr-3"></i>
                        Planos
                    </a>
                    <a href="{{ route('dashboard.adquirentes') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
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
                        <h1 class="text-2xl font-bold text-gray-800">Campanhas</h1>
                        <p class="text-gray-600">Gerencie suas campanhas de marketing</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button onclick="openAddCampanhaModal()" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <i class="fas fa-plus mr-2"></i>
                            Nova Campanha
                        </button>
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
                <div class="container mx-auto px-4 py-8">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Campanhas de Marketing</h1>
                            <p class="text-gray-600">Crie e gerencie campanhas para seus leads e licenciados</p>
                        </div>
                        <div class="flex space-x-4">
                            <a href="{{ route('dashboard.marketing') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Voltar
                            </a>
                        </div>
                    </div>

                    <!-- Campanhas Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Campanha de Exemplo -->
                        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-play mr-1"></i>Ativa
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-users mr-1"></i>Leads
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="editCampanha(1)" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100 transition-colors duration-200" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="pauseCampanha(1)" class="text-yellow-600 hover:text-yellow-900 p-1 rounded hover:bg-yellow-100 transition-colors duration-200" title="Pausar">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">🎯 Boas-vindas DSPay</h3>
                            <p class="text-sm text-gray-600 mb-3">Campanha de boas-vindas para novos leads</p>
                            
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Destinatários:</span>
                                    <span class="font-medium">0 leads</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Enviados:</span>
                                    <span class="font-medium text-green-600">0</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Abertos:</span>
                                    <span class="font-medium text-blue-600">0%</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Cliques:</span>
                                    <span class="font-medium text-purple-600">0%</span>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button onclick="viewCampanha(1)" class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i>Ver Detalhes
                                </button>
                                <button onclick="duplicateCampanha(1)" class="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-copy mr-1"></i>Duplicar
                                </button>
                            </div>
                        </div>

                        <!-- Campanha Pausada -->
                        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-pause mr-1"></i>Pausada
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-user-tie mr-1"></i>Licenciados
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="editCampanha(2)" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100 transition-colors duration-200" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="resumeCampanha(2)" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-100 transition-colors duration-200" title="Retomar">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">🚀 Oportunidades de Negócio</h3>
                            <p class="text-sm text-gray-600 mb-3">Campanha para licenciados ativos</p>
                            
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Destinatários:</span>
                                    <span class="font-medium">0 licenciados</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Enviados:</span>
                                    <span class="font-medium text-green-600">0</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Abertos:</span>
                                    <span class="font-medium text-blue-600">0%</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Cliques:</span>
                                    <span class="font-medium text-purple-600">0%</span>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button onclick="viewCampanha(2)" class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i>Ver Detalhes
                                </button>
                                <button onclick="duplicateCampanha(2)" class="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-copy mr-1"></i>Duplicar
                                </button>
                            </div>
                        </div>

                        <!-- Campanha Concluída -->
                        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        <i class="fas fa-check mr-1"></i>Concluída
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        <i class="fas fa-globe mr-1"></i>Geral
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="viewCampanha(3)" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100 transition-colors duration-200" title="Ver Detalhes">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="duplicateCampanha(3)" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-100 transition-colors duration-200" title="Duplicar">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">💡 Dicas e Insights</h3>
                            <p class="text-sm text-gray-600 mb-3">Campanha educativa para todos os contatos</p>
                            
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Destinatários:</span>
                                    <span class="font-medium">0 contatos</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Enviados:</span>
                                    <span class="font-medium text-green-600">0</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Abertos:</span>
                                    <span class="font-medium text-blue-600">0%</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Cliques:</span>
                                    <span class="font-medium text-purple-600">0%</span>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button onclick="viewCampanha(3)" class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i>Ver Detalhes
                                </button>
                                <button onclick="duplicateCampanha(3)" class="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-copy mr-1"></i>Duplicar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Criar Primeira Campanha -->
                    <div class="mt-12 text-center">
                        <div class="bg-white rounded-xl p-8 shadow-lg border border-gray-100 max-w-md mx-auto">
                            <i class="fas fa-rocket text-6xl text-blue-500 mb-4"></i>
                            <h3 class="text-xl font-medium mb-2">Pronto para criar sua primeira campanha?</h3>
                            <p class="text-sm text-gray-500 mb-6">Comece criando campanhas personalizadas para seus leads e licenciados</p>
                            <button onclick="openAddCampanhaModal()" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                                <i class="fas fa-plus mr-2"></i>
                                Criar Primeira Campanha
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add/Edit Campanha Modal -->
    <div id="campanhaModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-0 border-0 w-11/12 md:w-3/4 lg:w-2/3 shadow-2xl rounded-2xl bg-white overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-bullhorn text-white text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white" id="modalTitle">Nova Campanha</h3>
                    </div>
                    <button onclick="closeCampanhaModal()" class="text-white/80 hover:text-white transition-colors duration-200 p-2 hover:bg-white/20 rounded-full">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <form id="campanhaForm" class="space-y-6">
                    @csrf
                    <input type="hidden" id="campanha_id" name="id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="nome" class="block text-sm font-semibold text-gray-700 mb-2">Nome da Campanha *</label>
                            <input type="text" id="nome" name="nome" required 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                                   placeholder="Ex: Boas-vindas DSPay">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="tipo" class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Campanha *</label>
                            <select id="tipo" name="tipo" required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                                <option value="">Selecione o tipo...</option>
                                <option value="lead">Campanha para Leads</option>
                                <option value="licenciado">Campanha para Licenciados</option>
                                <option value="geral">Campanha Geral</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="descricao" class="block text-sm font-semibold text-gray-700 mb-2">Descrição da Campanha</label>
                        <textarea id="descricao" name="descricao" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white resize-none" 
                                  placeholder="Descreva o objetivo e público-alvo da campanha..."></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="modelo_id" class="block text-sm font-semibold text-gray-700 mb-2">Modelo de E-mail *</label>
                            <select id="modelo_id" name="modelo_id" required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                                <option value="">Selecione um modelo...</option>
                                <option value="1">🎯 Boas-vindas DSPay</option>
                                <option value="2">🚀 Oportunidades de Negócio</option>
                                <option value="3">💡 Dicas e Insights</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="agendamento" class="block text-sm font-semibold text-gray-700 mb-2">Data de Início</label>
                            <input type="datetime-local" id="agendamento" name="agendamento" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="segmentacao" class="block text-sm font-semibold text-gray-700 mb-2">Segmentação</label>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="segmentacao[]" value="novos" class="mr-2">
                                <span class="text-sm">Novos leads (últimos 30 dias)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="segmentacao[]" value="ativos" class="mr-2">
                                <span class="text-sm">Leads ativos</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="segmentacao[]" value="inativos" class="mr-2">
                                <span class="text-sm">Leads inativos</span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex justify-between">
                    <button onclick="closeCampanhaModal()" 
                            class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all duration-200 font-medium flex items-center space-x-2 shadow-lg">
                        <i class="fas fa-times"></i>
                        <span>Cancelar</span>
                    </button>
                    <button onclick="saveCampanha()" 
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 font-medium flex items-center space-x-2 shadow-lg">
                        <i class="fas fa-save"></i>
                        <span>Salvar Campanha</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .sidebar {
            background: linear-gradient(135deg, #1e3a8a 0%, #7c3aed 100%);
        }
        .sidebar-link {
            transition: all 0.3s ease;
            border-radius: 0.5rem;
        }
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }
        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateX(4px);
        }
    </style>

    <script>
        function openAddCampanhaModal() {
            document.getElementById('modalTitle').textContent = 'Nova Campanha';
            document.getElementById('campanhaForm').reset();
            document.getElementById('campanha_id').value = '';
            document.getElementById('campanhaModal').classList.remove('hidden');
        }

        function closeCampanhaModal() {
            document.getElementById('campanhaModal').classList.add('hidden');
        }

        function editCampanha(id) {
            // Implementar edição da campanha
            alert(`Editar campanha ${id} será implementado aqui!`);
        }

        function pauseCampanha(id) {
            if (confirm('Tem certeza que deseja pausar esta campanha?')) {
                // Implementar pausa da campanha
                alert(`Campanha ${id} pausada com sucesso!`);
            }
        }

        function resumeCampanha(id) {
            if (confirm('Tem certeza que deseja retomar esta campanha?')) {
                // Implementar retomada da campanha
                alert(`Campanha ${id} retomada com sucesso!`);
            }
        }

        function viewCampanha(id) {
            // Implementar visualização da campanha
            alert(`Ver detalhes da campanha ${id} será implementado aqui!`);
        }

        function duplicateCampanha(id) {
            if (confirm('Tem certeza que deseja duplicar esta campanha?')) {
                // Implementar duplicação da campanha
                alert(`Campanha ${id} duplicada com sucesso!`);
            }
        }

        function saveCampanha() {
            const form = document.getElementById('campanhaForm');
            const formData = new FormData(form);
            
            // Implementar salvamento da campanha
            alert('Campanha salva com sucesso!');
            closeCampanhaModal();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('campanhaModal');
            if (event.target === modal) {
                closeCampanhaModal();
            }
        }
    </script>
</body>
</html>


