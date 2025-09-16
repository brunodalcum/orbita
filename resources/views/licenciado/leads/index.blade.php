@extends('layouts.licenciado')

@section('title', 'Meus Leads')

@section('content')
<x-dynamic-branding />

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Meus Leads</h1>
                    <p class="mt-2 text-gray-600">Gerencie os leads atribuídos a você</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm border">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                            <span class="text-sm font-medium text-gray-700">{{ $leads->count() }} Leads Atribuídos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @php
                $statusCounts = $leads->groupBy('status')->map->count();
                $totalLeads = $leads->count();
            @endphp
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                                <i class="fas fa-user-plus text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Novos</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $statusCounts->get('novo', 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                                <i class="fas fa-phone text-yellow-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Contatados</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $statusCounts->get('contatado', 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Qualificados</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $statusCounts->get('qualificado', 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                                <i class="fas fa-handshake text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Fechados</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $statusCounts->get('fechado', 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border mb-6">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div class="flex items-center space-x-4">
                        <select id="statusFilter" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Todos os Status</option>
                            <option value="novo">Novo</option>
                            <option value="contatado">Contatado</option>
                            <option value="qualificado">Qualificado</option>
                            <option value="proposta">Proposta</option>
                            <option value="fechado">Fechado</option>
                            <option value="perdido">Perdido</option>
                        </select>
                        
                        <select id="ativoFilter" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Todos</option>
                            <option value="1">Ativos</option>
                            <option value="0">Inativos</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <input type="text" id="searchInput" placeholder="Buscar por nome, email ou empresa..." 
                               class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 w-64">
                        <button id="clearFilters" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Limpar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leads Table -->
        <div class="bg-white shadow-sm rounded-lg border overflow-hidden">
            @if($leads->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Lead
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Empresa
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Telefone
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Origem
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Data
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="leadsTableBody">
                            @foreach($leads as $lead)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 lead-row" 
                                data-status="{{ $lead->status }}" 
                                data-ativo="{{ $lead->ativo ? '1' : '0' }}"
                                data-search="{{ strtolower($lead->nome . ' ' . $lead->email . ' ' . $lead->empresa) }}">
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
                                    <div class="text-xs text-gray-500">{{ $lead->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="viewLead({{ $lead->id }})" 
                                                class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                                title="Ver detalhes">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick="editLead({{ $lead->id }})" 
                                                class="text-green-600 hover:text-green-900 transition-colors duration-200"
                                                title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="toggleLeadStatus({{ $lead->id }})" 
                                                class="text-{{ $lead->ativo ? 'red' : 'green' }}-600 hover:text-{{ $lead->ativo ? 'red' : 'green' }}-900 transition-colors duration-200"
                                                title="{{ $lead->ativo ? 'Desativar' : 'Ativar' }}">
                                            <i class="fas fa-{{ $lead->ativo ? 'times-circle' : 'check-circle' }}"></i>
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
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum lead atribuído</h3>
                    <p class="text-gray-500 mb-4">Você ainda não possui leads atribuídos a você.</p>
                    <p class="text-sm text-gray-400">Entre em contato com o administrador para solicitar leads.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para visualizar lead -->
<div id="viewLeadModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
    <div class="relative top-10 mx-auto p-0 w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 max-w-4xl">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <!-- Header com gradiente -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Detalhes do Lead</h3>
                            <p class="text-blue-100 text-sm">Informações completas do cliente</p>
                        </div>
                    </div>
                    <button onclick="closeViewLeadModal()" class="text-white hover:text-blue-200 transition-colors duration-200 p-2 hover:bg-white hover:bg-opacity-10 rounded-full">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>
            
            <!-- Conteúdo -->
            <div class="p-6">
                <div id="leadDetails">
                    <!-- Conteúdo será carregado via JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar lead -->
<div id="editLeadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Editar Lead</h3>
                <button onclick="closeModal('editLeadModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editLeadForm">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="editStatus" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="novo">Novo</option>
                            <option value="contatado">Contatado</option>
                            <option value="qualificado">Qualificado</option>
                            <option value="proposta">Proposta</option>
                            <option value="fechado">Fechado</option>
                            <option value="perdido">Perdido</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Observações</label>
                        <textarea id="editObservacoes" name="observacoes" rows="4" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Adicione suas observações..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('editLeadModal')" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentLeadId = null;

// Filtros
document.getElementById('statusFilter').addEventListener('change', filterLeads);
document.getElementById('ativoFilter').addEventListener('change', filterLeads);
document.getElementById('searchInput').addEventListener('input', filterLeads);
document.getElementById('clearFilters').addEventListener('click', clearFilters);

function filterLeads() {
    const statusFilter = document.getElementById('statusFilter').value;
    const ativoFilter = document.getElementById('ativoFilter').value;
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    
    const rows = document.querySelectorAll('.lead-row');
    
    rows.forEach(row => {
        const status = row.dataset.status;
        const ativo = row.dataset.ativo;
        const searchData = row.dataset.search;
        
        let showRow = true;
        
        if (statusFilter && status !== statusFilter) showRow = false;
        if (ativoFilter && ativo !== ativoFilter) showRow = false;
        if (searchTerm && !searchData.includes(searchTerm)) showRow = false;
        
        row.style.display = showRow ? '' : 'none';
    });
}

function clearFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('ativoFilter').value = '';
    document.getElementById('searchInput').value = '';
    filterLeads();
}

// Visualizar lead
function viewLead(leadId) {
    console.log('Visualizando lead ID:', leadId);
    fetch(`/licenciado/leads/${leadId}`)
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Dados recebidos:', data);
            if (data.success) {
                const lead = data.lead;
                console.log('Carregando detalhes do lead:', lead.nome);
                
                // Função para obter cor do status
                const getStatusColor = (status) => {
                    const colors = {
                        'novo': 'bg-blue-100 text-blue-800 border-blue-200',
                        'contatado': 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'qualificado': 'bg-purple-100 text-purple-800 border-purple-200',
                        'proposta': 'bg-orange-100 text-orange-800 border-orange-200',
                        'fechado': 'bg-green-100 text-green-800 border-green-200',
                        'perdido': 'bg-red-100 text-red-800 border-red-200'
                    };
                    return colors[status] || 'bg-gray-100 text-gray-800 border-gray-200';
                };
                
                document.getElementById('leadDetails').innerHTML = `
                    <!-- Card principal com informações -->
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-6 border border-gray-200 mb-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                    ${lead.nome.charAt(0).toUpperCase()}
                                </div>
                                <div>
                                    <h4 class="text-2xl font-bold text-gray-900">${lead.nome}</h4>
                                    <p class="text-gray-600">${lead.empresa || 'Empresa não informada'}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border ${getStatusColor(lead.status)}">
                                    <i class="fas fa-circle mr-2 text-xs"></i>
                                    ${lead.status.charAt(0).toUpperCase() + lead.status.slice(1)}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Grid de informações -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Contato -->
                        <div class="bg-white rounded-xl p-5 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-envelope text-blue-600"></i>
                                </div>
                                <h5 class="font-semibold text-gray-900">Email</h5>
                            </div>
                            <p class="text-gray-700 font-medium">${lead.email || 'Não informado'}</p>
                            ${lead.email ? `<a href="mailto:${lead.email}" class="text-blue-600 hover:text-blue-800 text-sm">Enviar email</a>` : ''}
                        </div>

                        <!-- Telefone -->
                        <div class="bg-white rounded-xl p-5 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-phone text-green-600"></i>
                                </div>
                                <h5 class="font-semibold text-gray-900">Telefone</h5>
                            </div>
                            <p class="text-gray-700 font-medium">${lead.telefone || 'Não informado'}</p>
                            ${lead.telefone ? `<a href="tel:${lead.telefone}" class="text-green-600 hover:text-green-800 text-sm">Ligar agora</a>` : ''}
                        </div>

                        <!-- Origem -->
                        <div class="bg-white rounded-xl p-5 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-map-marker-alt text-purple-600"></i>
                                </div>
                                <h5 class="font-semibold text-gray-900">Origem</h5>
                            </div>
                            <p class="text-gray-700 font-medium">${lead.origem || 'Não informada'}</p>
                        </div>

                        <!-- Data de criação -->
                        <div class="bg-white rounded-xl p-5 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar text-orange-600"></i>
                                </div>
                                <h5 class="font-semibold text-gray-900">Criado em</h5>
                            </div>
                            <p class="text-gray-700 font-medium">${new Date(lead.created_at).toLocaleDateString('pt-BR')}</p>
                            <p class="text-gray-500 text-sm">${new Date(lead.created_at).toLocaleTimeString('pt-BR')}</p>
                        </div>
                    </div>

                    ${lead.observacoes ? `
                        <!-- Observações -->
                        <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-5 border border-amber-200">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-sticky-note text-amber-600"></i>
                                </div>
                                <h5 class="font-semibold text-gray-900">Observações</h5>
                            </div>
                            <div class="bg-white rounded-lg p-4 border border-amber-200">
                                <p class="text-gray-700 leading-relaxed">${lead.observacoes}</p>
                            </div>
                        </div>
                    ` : ''}

                    <!-- Ações rápidas -->
                    <div class="mt-6 flex flex-wrap gap-3 justify-center">
                        ${lead.email ? `
                            <button onclick="window.open('mailto:${lead.email}', '_blank')" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                                <i class="fas fa-envelope mr-2"></i>
                                Enviar Email
                            </button>
                        ` : ''}
                        ${lead.telefone ? `
                            <button onclick="window.open('tel:${lead.telefone}', '_blank')" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200">
                                <i class="fas fa-phone mr-2"></i>
                                Ligar
                            </button>
                        ` : ''}
                        <button onclick="editLead(${lead.id}); closeViewLeadModal();" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Editar Lead
                        </button>
                    </div>
                `;
                console.log('Abrindo modal de visualização');
                const modal = document.getElementById('viewLeadModal');
                const modalContent = document.getElementById('modalContent');
                
                modal.classList.remove('hidden');
                
                // Animação de entrada
                setTimeout(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            } else {
                console.error('Erro na resposta:', data.message || 'Resposta sem sucesso');
                alert('Erro ao carregar detalhes do lead: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
            alert('Erro ao carregar detalhes do lead');
        });
}

// Editar lead
function editLead(leadId) {
    currentLeadId = leadId;
    
    fetch(`/licenciado/leads/${leadId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const lead = data.lead;
                document.getElementById('editStatus').value = lead.status;
                document.getElementById('editObservacoes').value = lead.observacoes || '';
                document.getElementById('editLeadModal').classList.remove('hidden');
            } else {
                alert('Erro ao carregar dados do lead');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao carregar dados do lead');
        });
}

// Salvar edição
document.getElementById('editLeadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`/licenciado/leads/${currentLeadId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: formData.get('status'),
            observacoes: formData.get('observacoes')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Lead atualizado com sucesso!');
            location.reload();
        } else {
            alert('Erro ao atualizar lead: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao atualizar lead');
    });
});

// Toggle status ativo/inativo
function toggleLeadStatus(leadId) {
    if (confirm('Tem certeza que deseja alterar o status deste lead?')) {
        fetch(`/licenciado/leads/${leadId}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao alterar status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao alterar status');
        });
    }
}

// Fechar modals
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function closeViewLeadModal() {
    const modal = document.getElementById('viewLeadModal');
    const modalContent = document.getElementById('modalContent');
    
    // Animação de saída
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    // Esconder modal após animação
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function closeEditLeadModal() {
    document.getElementById('editLeadModal').classList.add('hidden');
}

// Fechar modal ao clicar fora
window.onclick = function(event) {
    const modals = ['viewLeadModal', 'editLeadModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });
}
</script>
@endpush
