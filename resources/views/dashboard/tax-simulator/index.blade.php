@extends('layouts.dashboard')

@section('title', 'Simulador de Taxas')

@section('content')
<x-dynamic-branding />
<!-- Branding Dinâmico -->

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

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-calculator mr-3" style="color: var(--primary-color);"></i>
                        Simulador de Taxas
                    </h1>
                    <p class="text-gray-600 mt-2">Calcule taxas efetivas (MDR + Antecipação) para 1 a 18 parcelas</p>
                </div>
                    
                <!-- Botão de Ajuda -->
                <button onclick="showHelpModal()" class="px-4 py-2 rounded-lg transition-colors" style="background-color: var(--primary-light); color: var(--primary-color); border: 1px solid var(--primary-color);">
                    <i class="fas fa-question-circle mr-2"></i>
                    Como funciona?
                </button>
            </div>
        </div>

        <!-- Content -->
                    
                    <!-- Layout Responsivo: Form + Resultados -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                        
                        <!-- Formulário (Esquerda) -->
                        <div class="lg:col-span-4">
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-sliders-h text-blue-600 mr-2"></i>
                                    Parâmetros
                                </h2>
                                
                                <form id="taxSimulatorForm" class="space-y-4">
                                    @csrf
                                    
                                    <!-- MDR Débito -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            MDR Débito
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" 
                                                   name="mdr_debit" 
                                                   id="mdr_debit"
                                                   step="0.01" 
                                                   min="0" 
                                                   max="100"
                                                   placeholder="1,49"
                                                   class="w-full px-3 py-2 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">%</span>
                                        </div>
                                    </div>
                                    
                                    <!-- MDR Crédito à vista -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            MDR Crédito à vista (1x)
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" 
                                                   name="mdr_credit_1x" 
                                                   id="mdr_credit_1x"
                                                   step="0.01" 
                                                   min="0" 
                                                   max="100"
                                                   placeholder="3,09"
                                                   class="w-full px-3 py-2 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">%</span>
                                        </div>
                                    </div>
                                    
                                    <!-- MDR Crédito 2-6x -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            MDR Crédito 2-6x
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" 
                                                   name="mdr_credit_2_6x" 
                                                   id="mdr_credit_2_6x"
                                                   step="0.01" 
                                                   min="0" 
                                                   max="100"
                                                   placeholder="3,49"
                                                   class="w-full px-3 py-2 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">%</span>
                                        </div>
                                    </div>
                                    
                                    <!-- MDR Crédito 7-12x -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            MDR Crédito 7-12x
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" 
                                                   name="mdr_credit_7_12x" 
                                                   id="mdr_credit_7_12x"
                                                   step="0.01" 
                                                   min="0" 
                                                   max="100"
                                                   placeholder="3,99"
                                                   class="w-full px-3 py-2 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">%</span>
                                        </div>
                                    </div>
                                    
                                    <!-- MDR Crédito 13-18x -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            MDR Crédito 13-18x
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" 
                                                   name="mdr_credit_13_18x" 
                                                   id="mdr_credit_13_18x"
                                                   step="0.01" 
                                                   min="0" 
                                                   max="100"
                                                   placeholder="4,49"
                                                   class="w-full px-3 py-2 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">%</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Taxa de Antecipação -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Taxa de Antecipação (a.m.)
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" 
                                                   name="anticipation_rate" 
                                                   id="anticipation_rate"
                                                   step="0.01" 
                                                   min="0" 
                                                   max="20"
                                                   placeholder="2,99"
                                                   class="w-full px-3 py-2 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">% a.m.</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Opções Avançadas -->
                                    <div class="border-t pt-4 mt-6">
                                        <h3 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                                            <i class="fas fa-cog text-gray-500 mr-2"></i>
                                            Opções Avançadas
                                        </h3>
                                        
                                        <!-- Método de Cálculo -->
                                        <div class="mb-3">
                                            <label class="block text-sm text-gray-600 mb-2">Aplicação do juro:</label>
                                            <div class="space-y-2">
                                                <label class="flex items-center">
                                                    <input type="radio" name="calculation_method" value="linear" checked class="mr-2">
                                                    <span class="text-sm">Linear (pró-rata) - padrão</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="radio" name="calculation_method" value="compound" class="mr-2">
                                                    <span class="text-sm">Composto</span>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <!-- Antecipação no Débito -->
                                        <div class="mb-3">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="apply_anticipation_debit" class="mr-2">
                                                <span class="text-sm text-gray-600">Considerar antecipação no débito</span>
                                            </label>
                                        </div>
                                        
                                        <!-- Base do Mês -->
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Base de mês para pró-rata:</label>
                                            <select name="month_base_days" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                <option value="30" selected>30 dias (padrão)</option>
                                                <option value="28">28 dias</option>
                                                <option value="29">29 dias</option>
                                                <option value="31">31 dias</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- Botões -->
                                    <div class="flex space-x-3 pt-4">
                                        <button type="submit" 
                                                id="calculateBtn"
                                                class="flex-1 px-4 py-3 rounded-lg transition-colors font-medium flex items-center justify-center text-white" style="background-color: var(--primary-color);" onmouseover="this.style.backgroundColor='var(--primary-dark)'" onmouseout="this.style.backgroundColor='var(--primary-color)'">
                                            <span id="calculateBtnText">
                                                <i class="fas fa-calculator mr-2"></i>
                                                Calcular
                                            </span>
                                            <div id="calculateBtnLoading" class="hidden">
                                                <div class="loading-spinner mr-2"></div>
                                                Calculando...
                                            </div>
                                        </button>
                                        
                                        <button type="button" 
                                                onclick="clearForm()"
                                                class="bg-gray-100 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                            <i class="fas fa-eraser mr-2"></i>
                                            Limpar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Resultados (Direita) -->
                        <div class="lg:col-span-8">
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <i class="fas fa-table text-green-600 mr-2"></i>
                                        Resultados da Simulação
                                    </h2>
                                    
                                    <button id="exportCsvBtn" 
                                            onclick="exportToCsv()" 
                                            class="hidden px-4 py-2 rounded-lg transition-colors text-sm text-white" style="background-color: var(--accent-color);" onmouseover="this.style.backgroundColor='var(--primary-dark)'" onmouseout="this.style.backgroundColor='var(--accent-color)'">
                                        <i class="fas fa-download mr-2"></i>
                                        Exportar CSV
                                    </button>
                                </div>
                                
                                <!-- Estado Inicial -->
                                <div id="initialState" class="text-center py-12">
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-calculator text-gray-400 text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Pronto para calcular</h3>
                                    <p class="text-gray-600">Preencha os parâmetros ao lado e clique em "Calcular" para ver os resultados.</p>
                                </div>
                                
                                <!-- Loading -->
                                <div id="loadingState" class="hidden text-center py-12">
                                    <div class="loading-spinner mx-auto mb-4"></div>
                                    <p class="text-gray-600">Calculando taxas...</p>
                                </div>
                                
                                <!-- Tabela de Resultados -->
                                <div id="resultsTable" class="hidden">
                                    <div class="overflow-x-auto">
                                        <table class="w-full">
                                            <thead>
                                                <tr class="border-b border-gray-200">
                                                    <th class="text-left py-3 px-2 font-medium text-gray-700">Modalidade</th>
                                                    <th class="text-right py-3 px-2 font-medium text-gray-700">MDR (%)</th>
                                                    <th class="text-right py-3 px-2 font-medium text-gray-700">Dias Médios</th>
                                                    <th class="text-right py-3 px-2 font-medium text-gray-700">Antecipação (%)</th>
                                                    <th class="text-right py-3 px-2 font-medium text-gray-700">Taxa Total (%)</th>
                                                    <th class="text-right py-3 px-2 font-medium text-gray-700">Líquido R$ 100</th>
                                                </tr>
                                            </thead>
                                            <tbody id="resultsTableBody">
                                                <!-- Resultados serão inseridos aqui via JavaScript -->
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <!-- Resumo -->
                                    <div id="resultsSummary" class="mt-6 p-4 rounded-lg" style="background-color: var(--primary-light);">
                                        <!-- Resumo será inserido aqui via JavaScript -->
                                    </div>
                                    
                                    <!-- Aviso -->
                                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <div class="flex items-start">
                                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2 mt-0.5"></i>
                                            <p class="text-sm text-yellow-800">
                                                <strong>Aviso:</strong> Os resultados são estimativas baseadas nos parâmetros informados. 
                                                Consulte sempre as condições contratuais específicas.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Estado de Erro -->
                                <div id="errorState" class="hidden text-center py-12">
                                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Erro no cálculo</h3>
                                    <p id="errorMessage" class="text-gray-600"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal de Ajuda -->
    <div id="helpModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Como funciona o Simulador de Taxas?</h3>
                    <button onclick="hideHelpModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="space-y-4 text-sm text-gray-700">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">📊 O que é calculado?</h4>
                        <p>O simulador calcula a <strong>taxa total efetiva</strong> que inclui:</p>
                        <ul class="list-disc list-inside ml-4 mt-2 space-y-1">
                            <li><strong>MDR:</strong> Taxa do adquirente por modalidade</li>
                            <li><strong>Antecipação:</strong> Juros para receber à vista</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">⏰ Como são calculados os dias médios?</h4>
                        <p><strong>Fórmula:</strong> 30 × (n + 1) ÷ 2</p>
                        <ul class="list-disc list-inside ml-4 mt-2 space-y-1">
                            <li>1x → 30 dias</li>
                            <li>6x → 105 dias</li>
                            <li>12x → 195 dias</li>
                            <li>18x → 285 dias</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">🧮 Métodos de cálculo da antecipação:</h4>
                        <p><strong>Linear (padrão):</strong> Taxa a.m. × (Dias médios ÷ 30)</p>
                        <p><strong>Composto:</strong> (1 + Taxa a.m.)^(Dias médios ÷ 30) - 1</p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">🎯 Faixas de MDR:</h4>
                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <span class="badge-1x px-2 py-1 rounded text-xs">1x - À vista</span>
                            <span class="badge-2-6x px-2 py-1 rounded text-xs">2-6x - Parcelado</span>
                            <span class="badge-7-12x px-2 py-1 rounded text-xs">7-12x - Parcelado</span>
                            <span class="badge-13-18x px-2 py-1 rounded text-xs">13-18x - Parcelado</span>
                        </div>
                    </div>
                    
                                    <div class="p-3 rounded-lg" style="background-color: var(--primary-light);">
                        <p style="color: var(--primary-color);">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Exemplo:</strong> MDR 3,49% + Antecipação 10,47% (6x) = <strong>13,96% total</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variável global para armazenar os resultados
        let currentResults = null;

        // Configurar CSRF token para requisições AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Event listener para o formulário
        document.getElementById('taxSimulatorForm').addEventListener('submit', function(e) {
            e.preventDefault();
            calculateTaxes();
        });

        // Função para calcular taxas
        async function calculateTaxes() {
            const form = document.getElementById('taxSimulatorForm');
            const formData = new FormData(form);
            
            // Mostrar loading
            showLoading();
            
            try {
                const response = await fetch('/tax-simulator/calculate', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    currentResults = data.data.results;
                    displayResults(data.data.results, data.data.summary);
                } else {
                    showError(data.errors || data.message || 'Erro desconhecido');
                }
            } catch (error) {
                console.error('Erro:', error);
                showError('Erro de conexão. Tente novamente.');
            }
        }

        // Mostrar estado de loading
        function showLoading() {
            document.getElementById('initialState').classList.add('hidden');
            document.getElementById('resultsTable').classList.add('hidden');
            document.getElementById('errorState').classList.add('hidden');
            document.getElementById('loadingState').classList.remove('hidden');
            
            // Botão de calcular
            document.getElementById('calculateBtnText').classList.add('hidden');
            document.getElementById('calculateBtnLoading').classList.remove('hidden');
            document.getElementById('calculateBtn').disabled = true;
        }

        // Exibir resultados
        function displayResults(results, summary) {
            const tbody = document.getElementById('resultsTableBody');
            tbody.innerHTML = '';

            // Adicionar linha do débito primeiro
            if (results.debit) {
                tbody.appendChild(createResultRow(results.debit, true));
            }

            // Adicionar linhas de crédito (1 a 18x)
            for (let i = 1; i <= 18; i++) {
                const key = 'credit_' + i;
                if (results[key]) {
                    tbody.appendChild(createResultRow(results[key], false));
                }
            }

            // Exibir resumo
            displaySummary(summary);

            // Mostrar tabela e ocultar outros estados
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('initialState').classList.add('hidden');
            document.getElementById('errorState').classList.add('hidden');
            document.getElementById('resultsTable').classList.remove('hidden');
            document.getElementById('exportCsvBtn').classList.remove('hidden');

            // Restaurar botão de calcular
            document.getElementById('calculateBtnText').classList.remove('hidden');
            document.getElementById('calculateBtnLoading').classList.add('hidden');
            document.getElementById('calculateBtn').disabled = false;
        }

        // Criar linha da tabela de resultados
        function createResultRow(result, isDebit) {
            const row = document.createElement('tr');
            row.className = isDebit ? 'border-b border-gray-200 bg-gray-50' : 'border-b border-gray-100 hover:bg-gray-50';

            const badgeClass = getBadgeClass(result.installments_numeric);
            
            row.innerHTML = `
                <td class="py-3 px-2">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${badgeClass}">
                        ${result.installments}
                    </span>
                </td>
                <td class="py-3 px-2 text-right font-mono">${formatPercentage(result.mdr_applied)}</td>
                <td class="py-3 px-2 text-right font-mono">${result.average_days}</td>
                <td class="py-3 px-2 text-right font-mono">${formatPercentage(result.anticipation_rate)}</td>
                <td class="py-3 px-2 text-right font-mono font-semibold ${getTotalRateClass(result.total_rate)}">${formatPercentage(result.total_rate)}</td>
                <td class="py-3 px-2 text-right font-mono text-green-600">R$ ${formatCurrency(result.net_amount_100)}</td>
            `;

            return row;
        }

        // Obter classe CSS para badge
        function getBadgeClass(installments) {
            if (installments === 0) return 'bg-gray-500 text-white';
            if (installments === 1) return 'bg-blue-500 text-white';
            if (installments >= 2 && installments <= 6) return 'bg-green-500 text-white';
            if (installments >= 7 && installments <= 12) return 'bg-yellow-500 text-white';
            if (installments >= 13 && installments <= 18) return 'bg-red-500 text-white';
            return 'bg-gray-500 text-white';
        }

        // Obter classe CSS para taxa total
        function getTotalRateClass(rate) {
            if (rate < 5) return 'text-green-600';
            if (rate < 15) return 'text-yellow-600';
            return 'text-red-600';
        }

        // Exibir resumo
        function displaySummary(summary) {
            const summaryDiv = document.getElementById('resultsSummary');
            summaryDiv.innerHTML = `
                <h3 class="font-medium text-blue-900 mb-2">📊 Resumo da Simulação</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="text-blue-700">Taxa Mínima:</span>
                        <div class="font-semibold text-green-600">${formatPercentage(summary.min_total_rate)}</div>
                    </div>
                    <div>
                        <span class="text-blue-700">Taxa Máxima:</span>
                        <div class="font-semibold text-red-600">${formatPercentage(summary.max_total_rate)}</div>
                    </div>
                    <div>
                        <span class="text-blue-700">Taxa Média:</span>
                        <div class="font-semibold text-blue-600">${formatPercentage(summary.avg_total_rate)}</div>
                    </div>
                    <div>
                        <span class="text-blue-700">Simulações:</span>
                        <div class="font-semibold text-gray-700">${summary.total_simulations}</div>
                    </div>
                </div>
            `;
        }

        // Mostrar erro
        function showError(error) {
            let errorMessage = 'Erro desconhecido';
            
            if (typeof error === 'string') {
                errorMessage = error;
            } else if (typeof error === 'object') {
                const errors = [];
                for (const field in error) {
                    if (error[field] && Array.isArray(error[field])) {
                        errors.push(...error[field]);
                    }
                }
                errorMessage = errors.join(', ') || 'Erro de validação';
            }

            document.getElementById('errorMessage').textContent = errorMessage;
            
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('initialState').classList.add('hidden');
            document.getElementById('resultsTable').classList.add('hidden');
            document.getElementById('errorState').classList.remove('hidden');

            // Restaurar botão de calcular
            document.getElementById('calculateBtnText').classList.remove('hidden');
            document.getElementById('calculateBtnLoading').classList.add('hidden');
            document.getElementById('calculateBtn').disabled = false;
        }

        // Limpar formulário
        function clearForm() {
            document.getElementById('taxSimulatorForm').reset();
            currentResults = null;
            
            // Voltar ao estado inicial
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('resultsTable').classList.add('hidden');
            document.getElementById('errorState').classList.add('hidden');
            document.getElementById('exportCsvBtn').classList.add('hidden');
            document.getElementById('initialState').classList.remove('hidden');
        }

        // Exportar para CSV
        async function exportToCsv() {
            if (!currentResults) {
                alert('Nenhum resultado para exportar');
                return;
            }

            try {
                const response = await fetch('/tax-simulator/export-csv', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'text/csv',
                    },
                    body: JSON.stringify({ results: Object.values(currentResults) })
                });

                if (response.ok) {
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `simulador_taxas_${new Date().toISOString().slice(0, 19).replace(/:/g, '-')}.csv`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                } else {
                    alert('Erro ao exportar CSV');
                }
            } catch (error) {
                console.error('Erro ao exportar:', error);
                alert('Erro ao exportar CSV');
            }
        }

        // Mostrar modal de ajuda
        function showHelpModal() {
            document.getElementById('helpModal').classList.remove('hidden');
        }

        // Ocultar modal de ajuda
        function hideHelpModal() {
            document.getElementById('helpModal').classList.add('hidden');
        }

        // Fechar modal ao clicar fora
        document.getElementById('helpModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideHelpModal();
            }
        });

        // Funções de formatação
        function formatPercentage(value) {
            return parseFloat(value).toFixed(2) + '%';
        }

        function formatCurrency(value) {
            return parseFloat(value).toFixed(2);
        }

        // Preencher valores de exemplo ao carregar a página
        window.addEventListener('load', function() {
            document.getElementById('mdr_debit').value = '1.49';
            document.getElementById('mdr_credit_1x').value = '3.09';
            document.getElementById('mdr_credit_2_6x').value = '3.49';
            document.getElementById('mdr_credit_7_12x').value = '3.99';
            document.getElementById('mdr_credit_13_18x').value = '4.49';
            document.getElementById('anticipation_rate').value = '2.99';
        });
    </script>
@endsection
