
<x-dynamic-branding />
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
<style>
/* BRANDING FORÇADO PARA ESTA PÁGINA */
:root {
    --primary-color: #3B82F6;
    --secondary-color: #6B7280;
    --accent-color: #10B981;
    --text-color: #1F2937;
    --background-color: #FFFFFF;
    --primary-light: rgba(59, 130, 246, 0.1);
    --primary-dark: #2563EB;
    --primary-text: #FFFFFF;
}

/* Sobrescrita agressiva de todas as cores azuis */
.bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400,
.bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-blue-800, .bg-blue-900,
.bg-indigo-50, .bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700, .bg-indigo-800, .bg-indigo-900 {
    background-color: var(--primary-color) !important;
}

.hover\:bg-blue-600:hover, .hover\:bg-blue-700:hover, .hover\:bg-blue-800:hover,
.hover\:bg-indigo-600:hover, .hover\:bg-indigo-700:hover, .hover\:bg-indigo-800:hover {
    background-color: var(--primary-dark) !important;
}

.text-blue-500, .text-blue-600, .text-blue-700, .text-blue-800, .text-blue-900,
.text-indigo-500, .text-indigo-600, .text-indigo-700, .text-indigo-800, .text-indigo-900 {
    color: var(--primary-color) !important;
}

.border-blue-500, .border-blue-600, .border-blue-700, .border-blue-800, .border-blue-900,
.border-indigo-500, .border-indigo-600, .border-indigo-700, .border-indigo-800, .border-indigo-900 {
    border-color: var(--primary-color) !important;
}

button[class*="blue"], .btn[class*="blue"], button[class*="indigo"], .btn[class*="indigo"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

button[class*="blue"]:hover, .btn[class*="blue"]:hover, button[class*="indigo"]:hover, .btn[class*="indigo"]:hover {
    background-color: var(--primary-dark) !important;
}

.bg-green-500, .bg-green-600, .bg-green-700 {
    background-color: var(--accent-color) !important;
}

.text-green-500, .text-green-600, .text-green-700, .text-green-800 {
    color: var(--accent-color) !important;
}

/* Sobrescrever estilos inline hardcoded */
[style*="background-color: #3b82f6"], [style*="background-color: #2563eb"],
[style*="background-color: #1d4ed8"], [style*="background-color: #1e40af"] {
    background-color: var(--primary-color) !important;
}

[style*="color: #3b82f6"], [style*="color: #2563eb"],
[style*="color: #1d4ed8"], [style*="color: #1e40af"] {
    color: var(--primary-color) !important;
}

.animate-spin[class*="border-blue"], .animate-spin[class*="border-indigo"] {
    border-color: var(--primary-color) transparent var(--primary-color) transparent !important;
}
</style>
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
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Campanhas</h1>
                        <p class="text-gray-600">Gerencie suas campanhas de marketing</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button onclick="openAddCampanhaModal()" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <i class="fas fa-plus mr-2"></i>
                            Nova Campanha
                        </button>
                        <button onclick="testarEnvioEmail()" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Testar E-mail
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
                        @forelse($campanhas as $campanha)
                        <!-- Campanha Dinâmica -->
                        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    @if($campanha->status === 'ativa')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-play mr-1"></i>Ativa
                                        </span>
                                    @elseif($campanha->status === 'pausada')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-pause mr-1"></i>Pausada
                                        </span>
                                    @elseif($campanha->status === 'concluida')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <i class="fas fa-check mr-1"></i>Concluída
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            <i class="fas fa-edit mr-1"></i>Rascunho
                                        </span>
                                    @endif
                                    
                                    @if($campanha->tipo === 'lead')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <i class="fas fa-users mr-1"></i>Leads
                                        </span>
                                    @elseif($campanha->tipo === 'licenciado')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-user-tie mr-1"></i>Licenciados
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            <i class="fas fa-globe mr-1"></i>Geral
                                        </span>
                                    @endif
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="editCampanha({{ $campanha->id }})" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100 transition-colors duration-200" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if($campanha->status === 'ativa')
                                        <button onclick="pauseCampanha({{ $campanha->id }})" class="text-yellow-600 hover:text-yellow-900 p-1 rounded hover:bg-yellow-100 transition-colors duration-200" title="Pausar">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    @elseif($campanha->status === 'pausada')
                                        <button onclick="resumeCampanha({{ $campanha->id }})" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-100 transition-colors duration-200" title="Retomar">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    @endif
                                    @if($campanha->status === 'rascunho')
                                        <button onclick="activateCampanha({{ $campanha->id }})" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-100 transition-colors duration-200" title="Ativar">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $campanha->nome }}</h3>
                            <p class="text-sm text-gray-600 mb-3">{{ $campanha->descricao ?: 'Sem descrição' }}</p>
                            
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Destinatários:</span>
                                    <span class="font-medium">{{ $campanha->total_destinatarios }} {{ $campanha->tipo === 'lead' ? 'leads' : ($campanha->tipo === 'licenciado' ? 'licenciados' : 'contatos') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Enviados:</span>
                                    <span class="font-medium text-green-600">{{ $campanha->emails_enviados }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Abertos:</span>
                                    <span class="font-medium text-blue-600">{{ $campanha->taxa_abertura }}%</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Cliques:</span>
                                    <span class="font-medium text-purple-600">{{ $campanha->taxa_clique }}%</span>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button onclick="viewCampanha({{ $campanha->id }})" class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i>Ver Detalhes
                                </button>
                                @if($campanha->status === 'ativa')
                                    <button onclick="enviarCampanha({{ $campanha->id }})" class="flex-1 bg-green-50 hover:bg-green-100 text-green-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                        <i class="fas fa-paper-plane mr-1"></i>Enviar
                                    </button>
                                @else
                                    <button onclick="duplicateCampanha({{ $campanha->id }})" class="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                        <i class="fas fa-copy mr-1"></i>Duplicar
                                    </button>
                                @endif
                            </div>
                        </div>
                        @empty
                        <!-- Estado vazio -->
                        <div class="col-span-full">
                            <div class="text-center py-12">
                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-bullhorn text-4xl text-gray-400"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-600 mb-2">Nenhuma campanha criada</h3>
                                <p class="text-gray-500 mb-6">Crie sua primeira campanha de marketing para começar a engajar seus leads e licenciados</p>
                                <button onclick="openAddCampanhaModal()" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                    <i class="fas fa-plus mr-2"></i>
                                    Criar Primeira Campanha
                                </button>
                            </div>
                        </div>
                        @endforelse
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

    <!-- Modal de Teste de E-mail -->
    <div id="testeEmailModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-0 border-0 w-11/12 md:w-1/2 lg:w-1/3 shadow-2xl rounded-2xl bg-white overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-paper-plane text-white text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">Testar Envio de E-mail</h3>
                    </div>
                    <button onclick="closeTesteEmailModal()" class="text-white/80 hover:text-white transition-colors duration-200 p-2 hover:bg-white/20 rounded-full">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <form id="testeEmailForm" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label for="email_teste" class="block text-sm font-semibold text-gray-700 mb-2">E-mail de Teste *</label>
                        <input type="email" id="email_teste" name="email_teste" required 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                               placeholder="seu@email.com">
                    </div>
                    
                    <div class="space-y-2">
                        <label for="modelo_teste" class="block text-sm font-semibold text-gray-700 mb-2">Modelo de E-mail *</label>
                        <select id="modelo_teste" name="modelo_teste" required 
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                            <option value="">Selecione um modelo...</option>
                            @foreach($modelos as $modelo)
                                <option value="{{ $modelo->id }}">{{ $modelo->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-semibold mb-1">Como funciona o teste:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>O e-mail será enviado usando o servidor SMTP configurado</li>
                                    <li>Você receberá uma cópia do e-mail no endereço informado</li>
                                    <li>O teste usa o mesmo sistema das campanhas reais</li>
                                    <li>Verifique sua caixa de entrada e spam</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex justify-between">
                    <button onclick="closeTesteEmailModal()" 
                            class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all duration-200 font-medium flex items-center space-x-2 shadow-lg">
                        <i class="fas fa-times"></i>
                        <span>Cancelar</span>
                    </button>
                    <button onclick="enviarEmailTeste()" 
                            class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-medium flex items-center space-x-2 shadow-lg">
                        <i class="fas fa-paper-plane"></i>
                        <span>Enviar Teste</span>
                    </button>
                </div>
            </div>
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
                                @foreach($modelos as $modelo)
                                    <option value="{{ $modelo->id }}">{{ $modelo->nome }}</option>
                                @endforeach
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
        let campanhaEditando = null;

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

        function openAddCampanhaModal() {
            document.getElementById('modalTitle').textContent = 'Nova Campanha';
            document.getElementById('campanhaForm').reset();
            document.getElementById('campanha_id').value = '';
            campanhaEditando = null;
            document.getElementById('campanhaModal').classList.remove('hidden');
        }

        function testarEnvioEmail() {
            document.getElementById('testeEmailForm').reset();
            document.getElementById('testeEmailModal').classList.remove('hidden');
        }

        function closeTesteEmailModal() {
            document.getElementById('testeEmailModal').classList.add('hidden');
        }

        function closeCampanhaModal() {
            document.getElementById('campanhaModal').classList.add('hidden');
            campanhaEditando = null;
        }

        function editCampanha(id) {
            // Buscar dados da campanha via AJAX
            fetch(`/dashboard/marketing/campanhas/${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na requisição');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const campanha = data.campanha;
                        document.getElementById('modalTitle').textContent = 'Editar Campanha';
                        document.getElementById('campanha_id').value = campanha.id;
                        document.getElementById('nome').value = campanha.nome;
                        document.getElementById('descricao').value = campanha.descricao || '';
                        document.getElementById('tipo').value = campanha.tipo;
                        document.getElementById('modelo_id').value = campanha.modelo_id;
                        document.getElementById('agendamento').value = campanha.data_inicio ? campanha.data_inicio.slice(0, 16) : '';
                        
                        // Limpar checkboxes anteriores
                        document.querySelectorAll('input[name="segmentacao[]"]').forEach(cb => cb.checked = false);
                        
                        // Marcar segmentação atual
                        if (campanha.segmentacao) {
                            campanha.segmentacao.forEach(segmento => {
                                const checkbox = document.querySelector(`input[name="segmentacao[]"][value="${segmento}"]`);
                                if (checkbox) checkbox.checked = true;
                            });
                        }
                        
                        campanhaEditando = campanha.id;
                        document.getElementById('campanhaModal').classList.remove('hidden');
                    } else {
                        showNotification(data.message || 'Erro ao carregar dados da campanha', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showNotification('Erro ao carregar dados da campanha', 'error');
                });
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

        function viewCampanha(id) {
            // Redirecionar para a página de detalhes da campanha
            window.location.href = `/dashboard/marketing/campanhas/${id}/detalhes`;
        }

        function duplicateCampanha(id) {
            if (confirm('Tem certeza que deseja duplicar esta campanha?')) {
                // Implementar duplicação da campanha
                showNotification(`Campanha ${id} duplicada com sucesso!`, 'success');
            }
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

        function saveCampanha() {
            const form = document.getElementById('campanhaForm');
            const formData = new FormData(form);
            
            // Adicionar segmentação
            const segmentacao = [];
            document.querySelectorAll('input[name="segmentacao[]"]:checked').forEach(cb => {
                segmentacao.push(cb.value);
            });
            formData.append('segmentacao', JSON.stringify(segmentacao));
            
            const url = campanhaEditando 
                ? `/dashboard/marketing/campanhas/${campanhaEditando}`
                : '/dashboard/marketing/campanhas';
            
            const method = campanhaEditando ? 'PUT' : 'POST';
            
            // Adicionar _method para PUT
            if (campanhaEditando) {
                formData.append('_method', 'PUT');
            }
            
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
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
                    closeCampanhaModal();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message || 'Erro ao salvar campanha', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showNotification('Erro ao salvar campanha. Verifique os dados e tente novamente.', 'error');
            });
        }

        function enviarEmailTeste() {
            const form = document.getElementById('testeEmailForm');
            const formData = new FormData(form);
            
            // Validar campos
            const email = formData.get('email_teste');
            const modelo = formData.get('modelo_teste');
            
            if (!email || !modelo) {
                showNotification('Por favor, preencha todos os campos obrigatórios.', 'warning');
                return;
            }
            
            // Mostrar loading
            const btn = document.querySelector('button[onclick="enviarEmailTeste()"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
            btn.disabled = true;
            
            fetch('/dashboard/marketing/testar-email', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
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
                    closeTesteEmailModal();
                } else {
                    showNotification(data.message || 'Erro ao enviar e-mail de teste', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showNotification('Erro ao enviar e-mail de teste. Verifique a conexão e tente novamente.', 'error');
            })
            .finally(() => {
                // Restaurar botão
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('campanhaModal');
            const testeModal = document.getElementById('testeEmailModal');
            if (event.target === modal) {
                closeCampanhaModal();
            }
            if (event.target === testeModal) {
                closeTesteEmailModal();
            }
        }
    </script>
</body>
</html>

