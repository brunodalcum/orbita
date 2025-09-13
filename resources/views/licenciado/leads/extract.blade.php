@extends('layouts.licenciado')

@section('title', 'Extrair Leads')
@section('subtitle', 'Exporte seus leads com filtros personalizados')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2 flex items-center">
                    <i class="fas fa-download text-purple-600 mr-3"></i>
                    Extrair Leads
                </h2>
                <p class="text-gray-600">Exporte seus leads com filtros personalizados em CSV ou Excel</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <div class="bg-purple-50 px-4 py-2 rounded-lg">
                    <span class="text-sm font-medium text-purple-700">
                        <i class="fas fa-users mr-2"></i>
                        {{ $totalLeads }} leads disponíveis
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total de Leads -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total de Leads</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalLeads }}</p>
                </div>
            </div>
        </div>

        <!-- Leads Ativos -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Leads Ativos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $leadsAtivos }}</p>
                </div>
            </div>
        </div>

        <!-- Leads por Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-chart-pie text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Status Mais Comum</p>
                    @php
                        $statusMaisComum = collect($leadsPorStatus)->sortDesc()->keys()->first() ?? 'N/A';
                        $quantidadeStatus = $leadsPorStatus[$statusMaisComum] ?? 0;
                    @endphp
                    <p class="text-2xl font-bold text-gray-900">{{ ucfirst($statusMaisComum) }}</p>
                    <p class="text-xs text-gray-500">{{ $quantidadeStatus }} leads</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário de Filtros e Exportação -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-filter text-purple-600 mr-2"></i>
            Filtros de Exportação
        </h3>

        <form id="exportForm" action="{{ route('licenciado.leads.export') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <!-- Filtro por Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-1"></i>
                        Status dos Leads
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="status[]" value="novo" class="text-purple-600 focus:ring-purple-500 rounded">
                            <span class="ml-2 text-sm text-gray-700">Novo</span>
                            <span class="ml-auto text-xs text-gray-500">({{ $leadsPorStatus['novo'] ?? 0 }})</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="status[]" value="contatado" class="text-purple-600 focus:ring-purple-500 rounded">
                            <span class="ml-2 text-sm text-gray-700">Contatado</span>
                            <span class="ml-auto text-xs text-gray-500">({{ $leadsPorStatus['contatado'] ?? 0 }})</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="status[]" value="qualificado" class="text-purple-600 focus:ring-purple-500 rounded">
                            <span class="ml-2 text-sm text-gray-700">Qualificado</span>
                            <span class="ml-auto text-xs text-gray-500">({{ $leadsPorStatus['qualificado'] ?? 0 }})</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="status[]" value="proposta" class="text-purple-600 focus:ring-purple-500 rounded">
                            <span class="ml-2 text-sm text-gray-700">Proposta</span>
                            <span class="ml-auto text-xs text-gray-500">({{ $leadsPorStatus['proposta'] ?? 0 }})</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="status[]" value="fechado" class="text-purple-600 focus:ring-purple-500 rounded">
                            <span class="ml-2 text-sm text-gray-700">Fechado</span>
                            <span class="ml-auto text-xs text-gray-500">({{ $leadsPorStatus['fechado'] ?? 0 }})</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="status[]" value="perdido" class="text-purple-600 focus:ring-purple-500 rounded">
                            <span class="ml-2 text-sm text-gray-700">Perdido</span>
                            <span class="ml-auto text-xs text-gray-500">({{ $leadsPorStatus['perdido'] ?? 0 }})</span>
                        </label>
                    </div>
                </div>

                <!-- Filtro por Situação -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-toggle-on mr-1"></i>
                        Situação
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="ativo" value="" checked class="text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-sm text-gray-700">Todos</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="ativo" value="1" class="text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-sm text-gray-700">Apenas Ativos</span>
                            <span class="ml-auto text-xs text-gray-500">({{ $leadsAtivos }})</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="ativo" value="0" class="text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-sm text-gray-700">Apenas Inativos</span>
                            <span class="ml-auto text-xs text-gray-500">({{ $totalLeads - $leadsAtivos }})</span>
                        </label>
                    </div>
                </div>

                <!-- Filtro por Data -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-1"></i>
                        Período de Cadastro
                    </label>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Data Inicial</label>
                            <input type="date" 
                                   name="data_inicio" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Data Final</label>
                            <input type="date" 
                                   name="data_fim" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formato de Exportação -->
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-file-export text-purple-600 mr-2"></i>
                    Formato de Exportação
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <!-- CSV -->
                    <label class="relative">
                        <input type="radio" name="formato" value="csv" checked class="sr-only">
                        <div class="export-option border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-purple-300 transition-colors">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-file-csv text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-gray-900">CSV</h5>
                                    <p class="text-sm text-gray-600">Compatível com Excel e Google Sheets</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- Excel -->
                    <label class="relative">
                        <input type="radio" name="formato" value="excel" class="sr-only">
                        <div class="export-option border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-purple-300 transition-colors">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-file-excel text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-gray-900">Excel</h5>
                                    <p class="text-sm text-gray-600">Formato nativo do Microsoft Excel</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-3 px-6 rounded-lg font-medium hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 flex items-center justify-center">
                    <span id="exportButtonText">
                        <i class="fas fa-download mr-2"></i>
                        Exportar Leads
                    </span>
                    <span id="exportButtonLoading" class="hidden">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Exportando...
                    </span>
                </button>
                
                <button type="button" 
                        onclick="clearFilters()"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-eraser mr-2"></i>
                    Limpar Filtros
                </button>
                
                <a href="{{ route('licenciado.leads') }}" 
                   class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors text-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </form>
    </div>

    <!-- Informações Adicionais -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <h4 class="text-md font-semibold text-blue-900 mb-3 flex items-center">
            <i class="fas fa-info-circle mr-2"></i>
            Informações sobre a Exportação
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
            <div>
                <h5 class="font-medium mb-2">📊 Dados Incluídos:</h5>
                <ul class="space-y-1 text-blue-700">
                    <li>• ID do Lead</li>
                    <li>• Nome completo</li>
                    <li>• Email e telefone</li>
                    <li>• Empresa</li>
                    <li>• Status atual</li>
                    <li>• Origem do lead</li>
                    <li>• Situação (ativo/inativo)</li>
                    <li>• Data de cadastro</li>
                    <li>• Observações</li>
                </ul>
            </div>
            <div>
                <h5 class="font-medium mb-2">⚡ Dicas:</h5>
                <ul class="space-y-1 text-blue-700">
                    <li>• Use filtros para exportar apenas os leads necessários</li>
                    <li>• CSV é mais leve e compatível</li>
                    <li>• Excel oferece melhor formatação</li>
                    <li>• Dados são exportados em ordem cronológica</li>
                    <li>• Apenas seus leads atribuídos são incluídos</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.export-option input[type="radio"]:checked + div {
    border-color: #7c3aed;
    background-color: #f3f4f6;
}

.export-option input[type="radio"]:checked + div h5 {
    color: #7c3aed;
}
</style>
@endpush

@push('scripts')
<script>
// Event listener para o formulário
document.getElementById('exportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const formato = formData.get('formato');
    
    // Mostrar loading
    showLoading();
    
    if (formato === 'excel') {
        // Para Excel, fazer requisição AJAX
        exportExcel(formData);
    } else {
        // Para CSV, fazer submit normal
        this.submit();
        hideLoading();
    }
});

// Função para exportar Excel
async function exportExcel(formData) {
    try {
        const response = await fetch('{{ route("licenciado.leads.export") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Criar e baixar arquivo Excel usando SheetJS
            downloadExcel(data.data, data.filename);
        } else {
            alert('Erro ao exportar: ' + (data.message || 'Erro desconhecido'));
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao exportar dados');
    } finally {
        hideLoading();
    }
}

// Função para baixar Excel (usando SheetJS via CDN)
function downloadExcel(data, filename) {
    // Carregar SheetJS se não estiver carregado
    if (typeof XLSX === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js';
        script.onload = function() {
            createExcelFile(data, filename);
        };
        document.head.appendChild(script);
    } else {
        createExcelFile(data, filename);
    }
}

// Criar arquivo Excel
function createExcelFile(data, filename) {
    const ws = XLSX.utils.json_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Leads');
    XLSX.writeFile(wb, filename);
}

// Mostrar loading
function showLoading() {
    document.getElementById('exportButtonText').classList.add('hidden');
    document.getElementById('exportButtonLoading').classList.remove('hidden');
}

// Esconder loading
function hideLoading() {
    document.getElementById('exportButtonText').classList.remove('hidden');
    document.getElementById('exportButtonLoading').classList.add('hidden');
}

// Limpar filtros
function clearFilters() {
    // Desmarcar todos os checkboxes de status
    document.querySelectorAll('input[name="status[]"]').forEach(cb => cb.checked = false);
    
    // Resetar radio buttons de ativo
    document.querySelector('input[name="ativo"][value=""]').checked = true;
    
    // Limpar campos de data
    document.querySelector('input[name="data_inicio"]').value = '';
    document.querySelector('input[name="data_fim"]').value = '';
    
    // Resetar formato para CSV
    document.querySelector('input[name="formato"][value="csv"]').checked = true;
    
    // Atualizar visual dos radio buttons
    updateRadioVisuals();
}

// Atualizar visual dos radio buttons
function updateRadioVisuals() {
    document.querySelectorAll('input[name="formato"]').forEach(radio => {
        const option = radio.nextElementSibling;
        if (radio.checked) {
            option.classList.add('border-purple-500', 'bg-gray-50');
            option.querySelector('h5').classList.add('text-purple-600');
        } else {
            option.classList.remove('border-purple-500', 'bg-gray-50');
            option.querySelector('h5').classList.remove('text-purple-600');
        }
    });
}

// Event listeners para radio buttons
document.querySelectorAll('input[name="formato"]').forEach(radio => {
    radio.addEventListener('change', updateRadioVisuals);
});

// Inicializar visual
document.addEventListener('DOMContentLoaded', function() {
    updateRadioVisuals();
});
</script>
@endpush
