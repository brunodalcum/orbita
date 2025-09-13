@extends('layouts.licenciado')

@section('title', 'Simulador de Taxas')
@section('subtitle', 'Calcule taxas efetivas (MDR + Antecipação) para 1 a 18 parcelas')

@push('styles')
<style>
    .input-percentage::after {
        content: '%';
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        pointer-events: none;
    }
    
    .badge-1x { @apply bg-blue-500 text-white; }
    .badge-2-6x { @apply bg-green-500 text-white; }
    .badge-7-12x { @apply bg-yellow-500 text-white; }
    .badge-13-18x { @apply bg-red-500 text-white; }
    .badge-debit { @apply bg-gray-500 text-white; }
    
    .loading-spinner {
        border: 3px solid #f3f4f6;
        border-top: 3px solid #3b82f6;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2 flex items-center">
                    <i class="fas fa-calculator text-purple-600 mr-3"></i>
                    Simulador de Taxas
                </h2>
                <p class="text-gray-600">Calcule taxas efetivas (MDR + Antecipação) para 1 a 18 parcelas</p>
            </div>
            
            <!-- Botão de Ajuda -->
            <button onclick="showHelpModal()" class="bg-purple-100 text-purple-700 px-4 py-2 rounded-lg hover:bg-purple-200 transition-colors">
                <i class="fas fa-question-circle mr-2"></i>
                Como funciona?
            </button>
        </div>
    </div>

    <!-- Layout Responsivo: Form + Resultados -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Formulário (Esquerda) -->
        <div class="lg:col-span-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-sliders-h text-purple-600 mr-2"></i>
                    Parâmetros
                </h3>
                
                <form id="taxSimulatorForm" class="space-y-4">
                    
                    <!-- MDR Débito -->
                    <div>
                        <label for="mdr_debit" class="block text-sm font-medium text-gray-700 mb-1">
                            MDR Débito (%)
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="mdr_debit" 
                                   name="mdr_debit" 
                                   step="0.01" 
                                   min="0" 
                                   max="100" 
                                   value="1.49"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent pr-8"
                                   placeholder="Ex: 1.49">
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">%</span>
                        </div>
                    </div>

                    <!-- MDR Crédito à vista -->
                    <div>
                        <label for="mdr_credit_1x" class="block text-sm font-medium text-gray-700 mb-1">
                            MDR Crédito à vista (1x) (%)
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="mdr_credit_1x" 
                                   name="mdr_credit_1x" 
                                   step="0.01" 
                                   min="0" 
                                   max="100" 
                                   value="3.09"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent pr-8"
                                   placeholder="Ex: 3.09">
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">%</span>
                        </div>
                    </div>

                    <!-- MDR Crédito 2-6x -->
                    <div>
                        <label for="mdr_credit_2_6x" class="block text-sm font-medium text-gray-700 mb-1">
                            MDR Crédito 2-6x (%)
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="mdr_credit_2_6x" 
                                   name="mdr_credit_2_6x" 
                                   step="0.01" 
                                   min="0" 
                                   max="100" 
                                   value="3.49"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent pr-8"
                                   placeholder="Ex: 3.49">
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">%</span>
                        </div>
                    </div>

                    <!-- MDR Crédito 7-12x -->
                    <div>
                        <label for="mdr_credit_7_12x" class="block text-sm font-medium text-gray-700 mb-1">
                            MDR Crédito 7-12x (%)
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="mdr_credit_7_12x" 
                                   name="mdr_credit_7_12x" 
                                   step="0.01" 
                                   min="0" 
                                   max="100" 
                                   value="3.99"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent pr-8"
                                   placeholder="Ex: 3.99">
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">%</span>
                        </div>
                    </div>

                    <!-- MDR Crédito 13-18x -->
                    <div>
                        <label for="mdr_credit_13_18x" class="block text-sm font-medium text-gray-700 mb-1">
                            MDR Crédito 13-18x (%)
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="mdr_credit_13_18x" 
                                   name="mdr_credit_13_18x" 
                                   step="0.01" 
                                   min="0" 
                                   max="100" 
                                   value="4.49"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent pr-8"
                                   placeholder="Ex: 4.49">
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">%</span>
                        </div>
                    </div>

                    <!-- Taxa de Antecipação -->
                    <div>
                        <label for="anticipation_rate" class="block text-sm font-medium text-gray-700 mb-1">
                            Taxa de Antecipação (% a.m.)
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="anticipation_rate" 
                                   name="anticipation_rate" 
                                   step="0.01" 
                                   min="0" 
                                   max="20" 
                                   value="2.99"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent pr-8"
                                   placeholder="Ex: 2.99">
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">%</span>
                        </div>
                    </div>

                    <!-- Opções Avançadas -->
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Opções Avançadas</h4>
                        
                        <!-- Método de Cálculo -->
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Método de Cálculo
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="calculation_method" value="linear" checked class="text-purple-600 focus:ring-purple-500">
                                    <span class="ml-2 text-sm text-gray-700">Linear (Pró-rata)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="calculation_method" value="compound" class="text-purple-600 focus:ring-purple-500">
                                    <span class="ml-2 text-sm text-gray-700">Composto</span>
                                </label>
                            </div>
                        </div>

                        <!-- Aplicar Antecipação no Débito -->
                        <div class="mb-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="apply_anticipation_debit" class="text-purple-600 focus:ring-purple-500">
                                <span class="ml-2 text-sm text-gray-700">Aplicar antecipação no débito</span>
                            </label>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="flex space-x-3 pt-4">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-2 px-4 rounded-lg font-medium hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 flex items-center justify-center">
                            <span id="calculateButtonText">
                                <i class="fas fa-calculator mr-2"></i>
                                Calcular
                            </span>
                            <div id="calculateButtonLoading" class="hidden">
                                <div class="loading-spinner mr-2"></div>
                                Calculando...
                            </div>
                        </button>
                        
                        <button type="button" 
                                onclick="clearForm()"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-eraser mr-1"></i>
                            Limpar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Resultados (Direita) -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-table text-purple-600 mr-2"></i>
                        Resultados da Simulação
                    </h3>
                    
                    <button id="exportCsvBtn" 
                            onclick="exportToCsv()" 
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm disabled:opacity-50 disabled:cursor-not-allowed" 
                            disabled>
                        <i class="fas fa-download mr-2"></i>
                        Exportar CSV
                    </button>
                </div>

                <!-- Aviso Inicial -->
                <div id="initialMessage" class="text-center py-12">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calculator text-purple-600 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Pronto para calcular</h4>
                    <p class="text-gray-600">Preencha os parâmetros ao lado e clique em "Calcular" para ver os resultados</p>
                </div>

                <!-- Tabela de Resultados -->
                <div id="resultsTable" class="hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">Parcelas</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">MDR (%)</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">Dias Médios</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">Juro Antecipação (%)</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">Taxa Total (%)</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">Líquido R$ 100</th>
                                </tr>
                            </thead>
                            <tbody id="resultsTableBody" class="divide-y divide-gray-200">
                                <!-- Resultados serão inseridos aqui via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Aviso -->
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-yellow-800 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Aviso:</strong> Os resultados são estimativas baseadas nos parâmetros informados. 
                            Consulte sempre as condições específicas do seu contrato.
                        </p>
                    </div>
                </div>

                <!-- Loading -->
                <div id="loadingResults" class="hidden text-center py-12">
                    <div class="loading-spinner mx-auto mb-4"></div>
                    <p class="text-gray-600">Calculando resultados...</p>
                </div>

                <!-- Erro -->
                <div id="errorResults" class="hidden text-center py-12">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Erro no cálculo</h4>
                    <p id="errorMessage" class="text-gray-600 mb-4"></p>
                    <button onclick="hideError()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        Tentar novamente
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Ajuda -->
<div id="helpModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <!-- Header do Modal -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">Como funciona o Simulador</h3>
                <button onclick="hideHelpModal()" class="text-white hover:text-purple-200 p-2 hover:bg-white hover:bg-opacity-10 rounded-full transition-colors duration-200">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
        
        <!-- Conteúdo do Modal -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]">
            <div class="space-y-4">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">📊 O que é calculado?</h4>
                    <p class="text-gray-600 text-sm">O simulador calcula a <strong>taxa total efetiva</strong> que inclui MDR + juros de antecipação para cada modalidade de pagamento.</p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">🔢 Fórmulas utilizadas:</h4>
                    <ul class="text-gray-600 text-sm space-y-1">
                        <li><strong>Dias médios:</strong> 30 × (n + 1) / 2</li>
                        <li><strong>Juro Linear:</strong> Taxa a.m. × (Dias médios / 30)</li>
                        <li><strong>Taxa Total:</strong> MDR + Juro de Antecipação</li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">💡 Exemplo prático:</h4>
                    <p class="text-gray-600 text-sm">Para 6x com MDR 3,49% e antecipação 2,99% a.m.:</p>
                    <ul class="text-gray-600 text-sm space-y-1 ml-4">
                        <li>• Dias médios: 30 × 7 / 2 = 105 dias</li>
                        <li>• Juro: 2,99% × (105/30) = 10,47%</li>
                        <li>• Total: 3,49% + 10,47% = 13,96%</li>
                    </ul>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <p class="text-yellow-800 text-sm">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Importante:</strong> Crédito à vista (1x) não sofre antecipação por padrão, mantendo apenas o MDR.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Variáveis globais
let currentResults = null;

// Event listener para o formulário
document.getElementById('taxSimulatorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    calculateTaxes();
});

// Função para calcular as taxas
async function calculateTaxes() {
    const form = document.getElementById('taxSimulatorForm');
    const formData = new FormData(form);
    
    // Mostrar loading
    showLoading();
    
    try {
        const response = await fetch('/tax-simulator/calculate', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            currentResults = data.results;
            showResults(data.results);
            document.getElementById('exportCsvBtn').disabled = false;
        } else {
            showError(data.message || 'Erro no cálculo');
        }
    } catch (error) {
        console.error('Erro:', error);
        showError('Erro de conexão. Tente novamente.');
    }
}

// Função para mostrar loading
function showLoading() {
    document.getElementById('calculateButtonText').classList.add('hidden');
    document.getElementById('calculateButtonLoading').classList.remove('hidden');
    
    document.getElementById('initialMessage').classList.add('hidden');
    document.getElementById('resultsTable').classList.add('hidden');
    document.getElementById('errorResults').classList.add('hidden');
    document.getElementById('loadingResults').classList.remove('hidden');
}

// Função para mostrar resultados
function showResults(results) {
    document.getElementById('calculateButtonText').classList.remove('hidden');
    document.getElementById('calculateButtonLoading').classList.add('hidden');
    
    document.getElementById('loadingResults').classList.add('hidden');
    document.getElementById('errorResults').classList.add('hidden');
    document.getElementById('initialMessage').classList.add('hidden');
    
    // Preencher tabela
    const tbody = document.getElementById('resultsTableBody');
    tbody.innerHTML = '';
    
    results.forEach(result => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        
        // Determinar badge class
        let badgeClass = 'badge-1x';
        if (result.parcelas === 'Débito') {
            badgeClass = 'badge-debit';
        } else if (result.parcelas >= 2 && result.parcelas <= 6) {
            badgeClass = 'badge-2-6x';
        } else if (result.parcelas >= 7 && result.parcelas <= 12) {
            badgeClass = 'badge-7-12x';
        } else if (result.parcelas >= 13 && result.parcelas <= 18) {
            badgeClass = 'badge-13-18x';
        }
        
        row.innerHTML = `
            <td class="px-4 py-3">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${badgeClass}">
                    ${result.parcelas}${result.parcelas !== 'Débito' ? 'x' : ''}
                </span>
            </td>
            <td class="px-4 py-3 font-semibold text-blue-600">${result.mdr}%</td>
            <td class="px-4 py-3 text-gray-600">${result.dias_medios}</td>
            <td class="px-4 py-3 font-semibold text-orange-600">${result.juro_antecipacao}%</td>
            <td class="px-4 py-3 font-bold text-purple-600">${result.taxa_total}%</td>
            <td class="px-4 py-3 font-semibold text-green-600">R$ ${result.liquido_100}</td>
        `;
        
        tbody.appendChild(row);
    });
    
    document.getElementById('resultsTable').classList.remove('hidden');
}

// Função para mostrar erro
function showError(message) {
    document.getElementById('calculateButtonText').classList.remove('hidden');
    document.getElementById('calculateButtonLoading').classList.add('hidden');
    
    document.getElementById('loadingResults').classList.add('hidden');
    document.getElementById('initialMessage').classList.add('hidden');
    document.getElementById('resultsTable').classList.add('hidden');
    
    document.getElementById('errorMessage').textContent = message;
    document.getElementById('errorResults').classList.remove('hidden');
}

// Função para esconder erro
function hideError() {
    document.getElementById('errorResults').classList.add('hidden');
    document.getElementById('initialMessage').classList.remove('hidden');
}

// Função para limpar formulário
function clearForm() {
    document.getElementById('taxSimulatorForm').reset();
    
    // Restaurar valores padrão
    document.getElementById('mdr_debit').value = '1.49';
    document.getElementById('mdr_credit_1x').value = '3.09';
    document.getElementById('mdr_credit_2_6x').value = '3.49';
    document.getElementById('mdr_credit_7_12x').value = '3.99';
    document.getElementById('mdr_credit_13_18x').value = '4.49';
    document.getElementById('anticipation_rate').value = '2.99';
    
    // Limpar resultados
    document.getElementById('resultsTable').classList.add('hidden');
    document.getElementById('errorResults').classList.add('hidden');
    document.getElementById('initialMessage').classList.remove('hidden');
    document.getElementById('exportCsvBtn').disabled = true;
    
    currentResults = null;
}

// Função para exportar CSV
async function exportToCsv() {
    if (!currentResults) {
        alert('Nenhum resultado para exportar');
        return;
    }
    
    try {
        const form = document.getElementById('taxSimulatorForm');
        const formData = new FormData(form);
        
        const response = await fetch('/tax-simulator/export-csv', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'simulacao-taxas.csv';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        } else {
            alert('Erro ao exportar CSV');
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao exportar CSV');
    }
}

// Funções do modal de ajuda
function showHelpModal() {
    document.getElementById('helpModal').classList.remove('hidden');
}

function hideHelpModal() {
    document.getElementById('helpModal').classList.add('hidden');
}

// Fechar modal ao clicar fora
document.getElementById('helpModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideHelpModal();
    }
});

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideHelpModal();
    }
});
</script>
@endpush
