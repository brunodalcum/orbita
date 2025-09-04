<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Leads - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                        <h1 class="text-2xl font-bold text-gray-800">Leads</h1>
                        <p class="text-gray-600">Gerencie todos os leads da sua empresa</p>
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
                <div class="container mx-auto px-4 py-8">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Leads</h1>
                            <p class="text-gray-600">Gerencie todos os leads da sua empresa</p>
                        </div>
                        <button onclick="openAddLeadModal()" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <i class="fas fa-plus mr-2"></i>
                            Adicionar Lead
                        </button>
                    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total de Leads</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $leads->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Novos</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $leads->where('status', 'novo')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Qualificados</p>
                    <p class="text-3xl font-bold text-green-600">{{ $leads->where('status', 'qualificado')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Fechados</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $leads->where('status', 'fechado')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-trophy text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Leads Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Lista de Leads</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contato</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origem</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($leads as $lead)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    {{ strtoupper(substr($lead->nome, 0, 2)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $lead->nome }}</div>
                                    @if($lead->email)
                                        <div class="text-sm text-gray-500">{{ $lead->email }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $lead->empresa ?: 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $lead->telefone ?: 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $lead->status_color }}">
                                {{ ucfirst($lead->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $lead->origem ?: 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $lead->created_at->format('d/m/Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $lead->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button onclick="viewLead({{ $lead->id }})" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100 transition-colors duration-200" title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editLead({{ $lead->id }})" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-100 transition-colors duration-200" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteLead({{ $lead->id }})" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100 transition-colors duration-200" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button onclick="openFollowUpModal({{ $lead->id }})" class="text-yellow-600 hover:text-yellow-900 p-1 rounded hover:bg-yellow-100 transition-colors duration-200" title="Follow-up">
                                    <i class="fas fa-phone"></i>
                                </button>
                                <button onclick="sendEmailMarketing({{ $lead->id }})" class="text-purple-600 hover:text-purple-900 p-1 rounded hover:bg-purple-100 transition-colors duration-200" title="Email Marketing">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                <button onclick="toggleLeadStatus({{ $lead->id }})" class="text-gray-600 hover:text-gray-900 p-1 rounded hover:bg-gray-100 transition-colors duration-200" title="{{ $lead->ativo ? 'Desativar' : 'Ativar' }}">
                                    <i class="fas fa-{{ $lead->ativo ? 'check-circle' : 'times-circle' }}"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-users text-4xl mb-4"></i>
                                <p class="text-lg font-medium">Nenhum lead encontrado</p>
                                <p class="text-sm">Comece adicionando seu primeiro lead!</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Lead Modal -->
<div id="leadModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-0 border-0 w-11/12 md:w-3/4 lg:w-1/2 shadow-2xl rounded-2xl bg-white overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-plus text-white text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white" id="modalTitle">Adicionar Lead</h3>
                </div>
                <button onclick="closeLeadModal()" class="text-white/80 hover:text-white transition-colors duration-200 p-2 hover:bg-white/20 rounded-full">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <form id="leadForm" class="space-y-6">
                @csrf
                <input type="hidden" id="lead_id" name="lead_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="nome" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-user text-blue-500 mr-2"></i>Nome Completo *
                        </label>
                        <input type="text" id="nome" name="nome" required 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                    </div>
                    
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-envelope text-green-500 mr-2"></i>Email
                        </label>
                        <input type="email" id="email" name="email" 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                    </div>
                    
                    <div class="space-y-2">
                        <label for="telefone" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-phone text-purple-500 mr-2"></i>Telefone
                        </label>
                        <input type="text" id="telefone" name="telefone" 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                    </div>
                    
                    <div class="space-y-2">
                        <label for="empresa" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-building text-orange-500 mr-2"></i>Empresa
                        </label>
                        <input type="text" id="empresa" name="empresa" 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                    </div>
                    
                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-flag text-red-500 mr-2"></i>Status *
                        </label>
                        <select id="status" name="status" required 
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                            <option value="novo">🆕 Novo</option>
                            <option value="contatado">📞 Contatado</option>
                            <option value="qualificado">✅ Qualificado</option>
                            <option value="proposta">📋 Proposta</option>
                            <option value="fechado">🎯 Fechado</option>
                            <option value="perdido">❌ Perdido</option>
                        </select>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="origem" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-tag text-indigo-500 mr-2"></i>Origem
                        </label>
                        <input type="text" id="origem" name="origem" 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label for="observacoes" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-comment text-teal-500 mr-2"></i>Observações
                    </label>
                    <textarea id="observacoes" name="observacoes" rows="4" 
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white resize-none"
                              placeholder="Adicione observações importantes sobre este lead..."></textarea>
                </div>
            </form>
        </div>
        
        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex justify-end space-x-3">
                <button onclick="closeLeadModal()" 
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-200 font-medium flex items-center space-x-2">
                    <i class="fas fa-times"></i>
                    <span>Cancelar</span>
                </button>
                <button onclick="saveLead()" 
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 font-medium flex items-center space-x-2 shadow-lg">
                    <i class="fas fa-save"></i>
                    <span>Salvar Lead</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Lead Modal -->
<div id="viewLeadModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-0 border-0 w-11/12 md:w-3/4 lg:w-1/2 shadow-2xl rounded-2xl bg-white overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-eye text-white text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Detalhes do Lead</h3>
                </div>
                <button onclick="closeViewLeadModal()" class="text-white/80 hover:text-white transition-colors duration-200 p-2 hover:bg-white/20 rounded-full">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div id="leadDetails" class="space-y-6">
                <!-- Lead details will be populated here -->
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex justify-end">
                <button onclick="closeViewLeadModal()" 
                        class="px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-xl hover:from-green-700 hover:to-teal-700 transition-all duration-200 font-medium flex items-center space-x-2 shadow-lg">
                    <i class="fas fa-check"></i>
                    <span>Fechar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Follow-up Modal -->
<div id="followUpModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-0 border-0 w-11/12 md:w-3/4 lg:w-2/3 shadow-2xl rounded-2xl bg-white overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-phone text-white text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Follow-up do Lead</h3>
                </div>
                <button onclick="closeFollowUpModal()" class="text-white/80 hover:text-white transition-colors duration-200 p-2 hover:bg-white/20 rounded-full">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <!-- Informações do Lead -->
            <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800" id="followup-lead-nome">Nome do Lead</h4>
                        <p class="text-sm text-gray-600" id="followup-lead-empresa">Empresa</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800" id="followup-lead-status">Status</span>
                    </div>
                </div>
            </div>

            <!-- Lista de Follow-ups -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-history text-yellow-500 mr-2"></i>
                        Histórico de Follow-ups
                    </h4>
                    <span class="text-sm text-gray-500" id="followup-count">0 follow-ups</span>
                </div>
                
                <div id="followup-list" class="space-y-4 max-h-64 overflow-y-auto">
                    <!-- Follow-ups serão carregados aqui -->
                </div>
            </div>

            <!-- Formulário para Novo Follow-up -->
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-plus text-green-500 mr-2"></i>
                    Adicionar Novo Follow-up
                </h4>
                
                <form id="followUpForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="followup_lead_id" name="lead_id">
                    
                    <div class="space-y-2">
                        <label for="followup_observacoes" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-comment text-yellow-500 mr-2"></i>Observações *
                        </label>
                        <textarea id="followup_observacoes" name="observacoes" rows="4" required 
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white resize-none" 
                                  placeholder="Descreva o resultado do contato, próximos passos, observações importantes..."></textarea>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="proximo_contato" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-calendar-alt text-orange-500 mr-2"></i>Próximo Contato
                        </label>
                        <input type="datetime-local" id="proximo_contato" name="proximo_contato" 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex justify-end space-x-3">
                <button onclick="closeFollowUpModal()" 
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-200 font-medium flex items-center space-x-2">
                    <i class="fas fa-times"></i>
                    <span>Cancelar</span>
                </button>
                <button onclick="saveFollowUp()" 
                        class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 font-medium flex items-center space-x-2 shadow-lg">
                    <i class="fas fa-save"></i>
                    <span>Salvar Follow-up</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Email Marketing Modal -->
<div id="emailMarketingModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-0 border-0 w-11/12 md:w-3/4 lg:w-1/2 shadow-2xl rounded-2xl bg-white overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-envelope text-white text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Enviar Email Marketing</h3>
                </div>
                <button onclick="closeEmailMarketingModal()" class="text-white/80 hover:text-white transition-colors duration-200 p-2 hover:bg-white/20 rounded-full">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <form id="emailMarketingForm" class="space-y-6">
                @csrf
                <input type="hidden" id="email_lead_id" name="lead_id">
                
                <div class="space-y-2">
                    <label for="email_subject" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-tag text-purple-500 mr-2"></i>Assunto *
                    </label>
                    <input type="text" id="email_subject" name="subject" required 
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                           placeholder="Digite um assunto atrativo para o email...">
                </div>
                
                <div class="space-y-2">
                    <label for="email_content" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-edit text-pink-500 mr-2"></i>Conteúdo *
                    </label>
                    <textarea id="email_content" name="content" rows="10" required 
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white resize-none"
                              placeholder="Digite o conteúdo do email marketing...&#10;&#10;Dicas:&#10;- Seja pessoal e direto&#10;- Inclua uma chamada para ação clara&#10;- Mantenha o foco no valor para o cliente"></textarea>
                </div>
            </form>
        </div>
        
        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex justify-end space-x-3">
                <button onclick="closeEmailMarketingModal()" 
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-200 font-medium flex items-center space-x-2">
                    <i class="fas fa-times"></i>
                    <span>Cancelar</span>
                </button>
                <button type="submit" onclick="sendEmail(event)" 
                        class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 font-medium flex items-center space-x-2 shadow-lg">
                    <i class="fas fa-paper-plane"></i>
                    <span>Enviar Email</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentLeadId = null;

// Modal functions
function openAddLeadModal() {
    document.getElementById('modalTitle').textContent = 'Adicionar Lead';
    document.getElementById('leadForm').reset();
    document.getElementById('lead_id').value = '';
    document.getElementById('leadModal').classList.remove('hidden');
}

function openEditLeadModal(leadId) {
    currentLeadId = leadId;
    document.getElementById('modalTitle').textContent = 'Editar Lead';
    
    // Fetch lead data
    fetch(`/leads/${leadId}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const lead = data.lead;
                document.getElementById('lead_id').value = lead.id;
                document.getElementById('nome').value = lead.nome;
                document.getElementById('email').value = lead.email || '';
                document.getElementById('telefone').value = lead.telefone || '';
                document.getElementById('empresa').value = lead.empresa || '';
                document.getElementById('status').value = lead.status;
                document.getElementById('origem').value = lead.origem || '';
                document.getElementById('observacoes').value = lead.observacoes || '';
                
                document.getElementById('leadModal').classList.remove('hidden');
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Erro ao carregar dados do lead', 'error');
        });
}

function closeLeadModal() {
    document.getElementById('leadModal').classList.add('hidden');
    currentLeadId = null;
}

function saveLead() {
    const form = document.getElementById('leadForm');
    const formData = new FormData(form);
    
    const url = currentLeadId ? `/leads/${currentLeadId}` : '/leads';
    const method = currentLeadId ? 'PUT' : 'POST';
    
    if (currentLeadId) {
        formData.append('_method', 'PUT');
    }
    
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
            closeLeadModal();
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Erro ao salvar lead', 'error');
    });
}

function viewLead(leadId) {
    fetch(`/leads/${leadId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const lead = data.lead;
                const detailsHtml = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200">
                            <label class="block text-sm font-semibold text-blue-700 mb-2 flex items-center">
                                <i class="fas fa-user text-blue-500 mr-2"></i>Nome
                            </label>
                            <p class="text-lg font-medium text-blue-900">${lead.nome}</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl border border-green-200">
                            <label class="block text-sm font-semibold text-green-700 mb-2 flex items-center">
                                <i class="fas fa-envelope text-green-500 mr-2"></i>Email
                            </label>
                            <p class="text-lg font-medium text-green-900">${lead.email || 'N/A'}</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl border border-purple-200">
                            <label class="block text-sm font-semibold text-purple-700 mb-2 flex items-center">
                                <i class="fas fa-phone text-purple-500 mr-2"></i>Telefone
                            </label>
                            <p class="text-lg font-medium text-purple-900">${lead.telefone || 'N/A'}</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-4 rounded-xl border border-orange-200">
                            <label class="block text-sm font-semibold text-orange-700 mb-2 flex items-center">
                                <i class="fas fa-building text-orange-500 mr-2"></i>Empresa
                            </label>
                            <p class="text-lg font-medium text-orange-900">${lead.empresa || 'N/A'}</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-red-50 to-red-100 p-4 rounded-xl border border-red-200">
                            <label class="block text-sm font-semibold text-red-700 mb-2 flex items-center">
                                <i class="fas fa-flag text-red-500 mr-2"></i>Status
                            </label>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full ${lead.status_color}">
                                ${lead.status.charAt(0).toUpperCase() + lead.status.slice(1)}
                            </span>
                        </div>
                        
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-4 rounded-xl border border-indigo-200">
                            <label class="block text-sm font-semibold text-indigo-700 mb-2 flex items-center">
                                <i class="fas fa-tag text-indigo-500 mr-2"></i>Origem
                            </label>
                            <p class="text-lg font-medium text-indigo-900">${lead.origem || 'N/A'}</p>
                        </div>
                        
                        <div class="md:col-span-2 bg-gradient-to-br from-teal-50 to-teal-100 p-4 rounded-xl border border-teal-200">
                            <label class="block text-sm font-semibold text-teal-700 mb-2 flex items-center">
                                <i class="fas fa-comment text-teal-500 mr-2"></i>Observações
                            </label>
                            <p class="text-base text-teal-900 leading-relaxed">${lead.observacoes || 'Nenhuma observação registrada'}</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-xl border border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-calendar-plus text-gray-500 mr-2"></i>Data de Criação
                            </label>
                            <p class="text-base font-medium text-gray-900">${new Date(lead.created_at).toLocaleDateString('pt-BR')}</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-xl border border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-calendar-check text-gray-500 mr-2"></i>Última Atualização
                            </label>
                            <p class="text-base font-medium text-gray-900">${new Date(lead.updated_at).toLocaleDateString('pt-BR')}</p>
                        </div>
                    </div>
                `;
                
                document.getElementById('leadDetails').innerHTML = detailsHtml;
                document.getElementById('viewLeadModal').classList.remove('hidden');
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Erro ao carregar dados do lead', 'error');
        });
}

function closeViewLeadModal() {
    document.getElementById('viewLeadModal').classList.add('hidden');
}

function editLead(leadId) {
    openEditLeadModal(leadId);
}

function deleteLead(leadId) {
    // Create confirmation modal
    const confirmModal = document.createElement('div');
    confirmModal.className = 'fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full z-50';
    confirmModal.innerHTML = `
        <div class="relative top-20 mx-auto p-0 border-0 w-11/12 md:w-1/2 shadow-2xl rounded-2xl bg-white overflow-hidden">
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                <div class="flex items-center justify-center space-x-3">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Confirmar Exclusão</h3>
                </div>
            </div>
            
            <div class="p-6 text-center">
                <p class="text-lg text-gray-700 mb-6">Tem certeza que deseja excluir este lead?</p>
                <p class="text-sm text-gray-500 mb-8">Esta ação não pode ser desfeita.</p>
                
                <div class="flex justify-center space-x-4">
                    <button onclick="closeDeleteModal()" 
                            class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-200 font-medium flex items-center space-x-2">
                        <i class="fas fa-times"></i>
                        <span>Cancelar</span>
                    </button>
                    <button onclick="confirmDelete(${leadId})" 
                            class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-200 font-medium flex items-center space-x-2 shadow-lg">
                        <i class="fas fa-trash"></i>
                        <span>Excluir Lead</span>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(confirmModal);
    
    // Store modal reference for closing
    window.currentDeleteModal = confirmModal;
}

function closeDeleteModal() {
    if (window.currentDeleteModal) {
        window.currentDeleteModal.remove();
        window.currentDeleteModal = null;
    }
}

function confirmDelete(leadId) {
    closeDeleteModal();
    
    fetch(`/leads/${leadId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
        console.error('Error:', error);
        showToast('Erro ao excluir lead', 'error');
    });
}

function toggleLeadStatus(leadId) {
    fetch(`/leads/${leadId}/toggle-status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
        console.error('Error:', error);
        showToast('Erro ao alterar status do lead', 'error');
    });
}

function openFollowUpModal(leadId) {
    document.getElementById('followup_lead_id').value = leadId;
    document.getElementById('followUpForm').reset();
    document.getElementById('followUpModal').classList.remove('hidden');
    
    // Carregar dados do lead e follow-ups
    loadLeadFollowUps(leadId);
}

function closeFollowUpModal() {
    document.getElementById('followUpModal').classList.add('hidden');
}

function loadLeadFollowUps(leadId) {
    fetch(`/leads/${leadId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const lead = data.lead;
                
                // Preencher informações do lead
                document.getElementById('followup-lead-nome').textContent = lead.nome;
                document.getElementById('followup-lead-empresa').textContent = lead.empresa || 'N/A';
                document.getElementById('followup-lead-status').textContent = lead.status.charAt(0).toUpperCase() + lead.status.slice(1);
                document.getElementById('followup-lead-status').className = `inline-flex px-3 py-1 text-sm font-semibold rounded-full ${lead.status_color}`;
                
                // Preencher lista de follow-ups
                displayFollowUps(lead.follow_ups || []);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Erro ao carregar dados do lead', 'error');
        });
}

function displayFollowUps(followUps) {
    const container = document.getElementById('followup-list');
    const countElement = document.getElementById('followup-count');
    
    countElement.textContent = `${followUps.length} follow-up${followUps.length !== 1 ? 's' : ''}`;
    
    if (followUps.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-3"></i>
                <p class="text-lg font-medium">Nenhum follow-up registrado</p>
                <p class="text-sm">Seja o primeiro a registrar um follow-up para este lead!</p>
            </div>
        `;
        return;
    }
    
    const followUpsHtml = followUps.map((followUp, index) => {
        const dataFormatada = new Date(followUp.created_at).toLocaleString('pt-BR');
        const proximoContato = followUp.proximo_contato ? 
            new Date(followUp.proximo_contato).toLocaleString('pt-BR') : 'Não agendado';
        
        return `
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-4 rounded-xl border border-yellow-200 ${index !== followUps.length - 1 ? 'border-b-2' : ''}">
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center shadow-lg flex-shrink-0">
                        <i class="fas fa-phone text-white text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-2">
                            <h5 class="text-sm font-semibold text-gray-900">Follow-up #${followUps.length - index}</h5>
                            <span class="text-xs text-gray-500">${dataFormatada}</span>
                        </div>
                        <p class="text-sm text-gray-700 leading-relaxed mb-2">${followUp.observacao}</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span><i class="fas fa-calendar-alt mr-1"></i>Próximo: ${proximoContato}</span>
                            <span><i class="fas fa-user mr-1"></i>${followUp.user?.name || 'Usuário'}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
    
    container.innerHTML = followUpsHtml;
}

function saveFollowUp() {
    const form = document.getElementById('followUpForm');
    const formData = new FormData(form);
    const leadId = document.getElementById('followup_lead_id').value;
    
    fetch(`/leads/${leadId}/followup`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            
            // Recarregar follow-ups sem fechar o modal
            loadLeadFollowUps(leadId);
            
            // Limpar formulário
            form.reset();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Erro ao salvar follow-up', 'error');
    });
}

function sendEmailMarketing(leadId) {
    document.getElementById('email_lead_id').value = leadId;
    document.getElementById('emailMarketingForm').reset();
    document.getElementById('emailMarketingModal').classList.remove('hidden');
}

function closeEmailMarketingModal() {
    document.getElementById('emailMarketingModal').classList.add('hidden');
}

function sendEmail(event) {
    // Prevenir o comportamento padrão do formulário
    if (event) {
        event.preventDefault();
    }
    
    const form = document.getElementById('emailMarketingForm');
    const formData = new FormData(form);
    const leadId = document.getElementById('email_lead_id').value;
    
    const assunto = formData.get('subject');
    const mensagem = formData.get('content');
    
    console.log('Assunto:', assunto);
    console.log('Mensagem:', mensagem);
    console.log('Lead ID:', leadId);
    
    if (!assunto || !mensagem) {
        showToast('Por favor, preencha todos os campos!', 'error');
        return;
    }
    
    // Mostrar loading
    const submitBtn = document.querySelector('#emailMarketingModal button[type="submit"]');
    console.log('Botão encontrado:', submitBtn);
    if (!submitBtn) {
        console.error('Botão de submit não encontrado!');
        showToast('Erro: Botão não encontrado', 'error');
        return;
    }
    
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
    submitBtn.disabled = true;
    
    // Fazer requisição para o backend
    fetch('{{ route("leads.send-marketing-email") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            lead_id: leadId,
            assunto: assunto,
            mensagem: mensagem
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            closeEmailMarketingModal();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showToast('Erro ao enviar email. Tente novamente.', 'error');
    })
    .finally(() => {
        // Restaurar botão
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Toast notification function
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };
    
    const colors = {
        success: 'bg-gradient-to-r from-green-500 to-green-600',
        error: 'bg-gradient-to-r from-red-500 to-red-600',
        info: 'bg-gradient-to-r from-blue-500 to-blue-600'
    };
    
    toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl text-white font-medium shadow-2xl transform transition-all duration-300 translate-x-full ${
        colors[type] || colors.info
    }`;
    
    toast.innerHTML = `
        <div class="flex items-center space-x-3">
            <i class="${icons[type] || icons.info} text-xl"></i>
            <span class="font-semibold">${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 4000);
}

// Close modals when clicking outside
window.onclick = function(event) {
    const leadModal = document.getElementById('leadModal');
    const viewLeadModal = document.getElementById('viewLeadModal');
    const followUpModal = document.getElementById('followUpModal');
    const emailMarketingModal = document.getElementById('emailMarketingModal');
    
    if (event.target === leadModal) {
        closeLeadModal();
    }
    if (event.target === viewLeadModal) {
        closeViewLeadModal();
    }
    if (event.target === followUpModal) {
        closeFollowUpModal();
    }
    if (event.target === emailMarketingModal) {
        closeEmailMarketingModal();
    }
    
    // Close delete confirmation modal
    if (window.currentDeleteModal && event.target === window.currentDeleteModal) {
        closeDeleteModal();
    }
}
</script>
            </main>
        </div>
    </div>


</body>
</html>
