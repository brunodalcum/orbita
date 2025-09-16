@extends('layouts.licenciado')

@section('title', 'Comissões')
@section('subtitle', 'Acompanhe suas comissões e pagamentos')

@section('content')
<x-dynamic-branding />
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Comissões</h2>
            <p class="text-gray-600">Gerencie suas comissões e histórico de pagamentos</p>
        </div>
        <div class="flex space-x-2">
            <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-download mr-2"></i>
                Extrato
            </button>
            <button class="btn-primary">
                <i class="fas fa-calculator mr-2"></i>
                Simular
            </button>
        </div>
    </div>

    <!-- Commission Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card green">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Este Mês</p>
                    <p class="text-3xl font-bold">R$ {{ number_format($comissoes->comissao_mes, 2, ',', '.') }}</p>
                    <p class="text-green-200 text-xs mt-1">Comissão acumulada</p>
                </div>
                <div class="text-4xl text-green-200">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
        </div>

        <div class="stat-card orange">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-pink-100 text-sm font-medium">Pendente</p>
                    <p class="text-3xl font-bold">R$ {{ number_format($comissoes->comissao_pendente, 2, ',', '.') }}</p>
                    <p class="text-pink-200 text-xs mt-1">Aguardando pagamento</p>
                </div>
                <div class="text-4xl text-pink-200">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div class="stat-card blue">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Pago</p>
                    <p class="text-3xl font-bold">R$ {{ number_format($comissoes->comissao_paga, 2, ',', '.') }}</p>
                    <p class="text-blue-200 text-xs mt-1">Já recebido</p>
                </div>
                <div class="text-4xl text-blue-200">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="stat-card purple">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-cyan-100 text-sm font-medium">Próximo Pagamento</p>
                    <p class="text-2xl font-bold">{{ $comissoes->proximo_pagamento->format('d/m') }}</p>
                    <p class="text-cyan-200 text-xs mt-1">{{ $comissoes->proximo_pagamento->diffForHumans() }}</p>
                </div>
                <div class="text-4xl text-cyan-200">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Commission Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Commission by Type -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Comissão por Modalidade</h3>
            <canvas id="commissionChart" width="400" height="300"></canvas>
        </div>

        <!-- Commission Timeline -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Evolução das Comissões</h3>
            <canvas id="timelineChart" width="400" height="300"></canvas>
        </div>
    </div>

    <!-- Detailed Breakdown -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Detalhamento das Comissões</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Débito -->
            <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-green-800">Débito à Vista</h4>
                    <i class="fas fa-credit-card text-green-600 text-xl"></i>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Volume de vendas:</span>
                        <span class="font-medium">R$ 8.500,00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Taxa média:</span>
                        <span class="font-medium">1.2%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Comissão média:</span>
                        <span class="font-medium text-green-600">0.85%</span>
                    </div>
                    <hr class="border-green-200">
                    <div class="flex justify-between">
                        <span class="font-semibold text-green-800">Total comissão:</span>
                        <span class="font-bold text-green-600">R$ 72,25</span>
                    </div>
                </div>
            </div>

            <!-- Crédito -->
            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-blue-800">Crédito à Vista</h4>
                    <i class="fas fa-credit-card text-blue-600 text-xl"></i>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Volume de vendas:</span>
                        <span class="font-medium">R$ 12.000,00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Taxa média:</span>
                        <span class="font-medium">2.8%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Comissão média:</span>
                        <span class="font-medium text-blue-600">1.2%</span>
                    </div>
                    <hr class="border-blue-200">
                    <div class="flex justify-between">
                        <span class="font-semibold text-blue-800">Total comissão:</span>
                        <span class="font-bold text-blue-600">R$ 144,00</span>
                    </div>
                </div>
            </div>

            <!-- Parcelado -->
            <div class="bg-purple-50 rounded-lg p-6 border border-purple-200">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-purple-800">Parcelado</h4>
                    <i class="fas fa-credit-card text-purple-600 text-xl"></i>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Volume de vendas:</span>
                        <span class="font-medium">R$ 15.500,00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Taxa média:</span>
                        <span class="font-medium">4.2%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Comissão média:</span>
                        <span class="font-medium text-purple-600">1.8%</span>
                    </div>
                    <hr class="border-purple-200">
                    <div class="flex justify-between">
                        <span class="font-semibold text-purple-800">Total comissão:</span>
                        <span class="font-bold text-purple-600">R$ 279,00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment History -->
    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Histórico de Pagamentos</h3>
                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Ver todos <i class="fas fa-arrow-right ml-1"></i>
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Período</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Data de Pagamento</th>
                        <th>Comprovante</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comissoes->historico as $item)
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar text-blue-600"></i>
                                </div>
                                <span class="font-medium">{{ $item['mes'] }}/2024</span>
                            </div>
                        </td>
                        <td>
                            <span class="font-semibold text-green-600 text-lg">R$ {{ number_format($item['valor'], 2, ',', '.') }}</span>
                        </td>
                        <td>
                            <span class="status-badge {{ $item['status'] == 'pago' ? 'ativo' : 'pendente' }}">
                                {{ ucfirst($item['status']) }}
                            </span>
                        </td>
                        <td>
                            @if($item['status'] == 'pago')
                                <div>
                                    <p class="font-medium">05/{{ $item['mes'] == 'Janeiro' ? '02' : ($item['mes'] == 'Fevereiro' ? '03' : '04') }}/2024</p>
                                    <p class="text-sm text-gray-500">Transferência bancária</p>
                                </div>
                            @else
                                <span class="text-gray-500">{{ $comissoes->proximo_pagamento->format('d/m/Y') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($item['status'] == 'pago')
                                <button class="text-blue-600 hover:text-blue-800 font-medium">
                                    <i class="fas fa-download mr-1"></i>
                                    Baixar
                                </button>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Commission Calculator -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Simulador de Comissões</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Valor da Venda</label>
                        <input type="number" id="saleValue" placeholder="0,00" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Modalidade</label>
                        <select id="paymentType" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="debito">Débito à Vista</option>
                            <option value="credito">Crédito à Vista</option>
                            <option value="parcelado">Parcelado</option>
                        </select>
                    </div>
                    
                    <div id="installmentsDiv" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Número de Parcelas</label>
                        <select id="installments" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="2">2x</option>
                            <option value="3">3x</option>
                            <option value="6">6x</option>
                            <option value="12">12x</option>
                        </select>
                    </div>
                    
                    <button onclick="calculateCommission()" class="w-full btn-primary">
                        <i class="fas fa-calculator mr-2"></i>
                        Calcular Comissão
                    </button>
                </div>
            </div>
            
            <div>
                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="font-semibold text-gray-800 mb-4">Resultado da Simulação</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Valor da venda:</span>
                            <span id="resultSaleValue" class="font-medium">R$ 0,00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Taxa do adquirente:</span>
                            <span id="resultRate" class="font-medium">0%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sua comissão:</span>
                            <span id="resultCommissionRate" class="font-medium text-blue-600">0%</span>
                        </div>
                        <hr>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-800">Você receberá:</span>
                            <span id="resultCommissionValue" class="font-bold text-green-600 text-lg">R$ 0,00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Commission by Type Chart
    const commissionCtx = document.getElementById('commissionChart').getContext('2d');
    const commissionChart = new Chart(commissionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Débito', 'Crédito', 'Parcelado'],
            datasets: [{
                data: [72.25, 144.00, 279.00],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(var(--primary-color-rgb), 0.8)',
                    'rgba(147, 51, 234, 0.8)'
                ],
                borderColor: [
                    'rgba(34, 197, 94, 1)',
                    'rgba(var(--primary-color-rgb), 1)',
                    'rgba(147, 51, 234, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': R$ ' + context.parsed.toFixed(2);
                        }
                    }
                }
            }
        }
    });

    // Timeline Chart
    const timelineCtx = document.getElementById('timelineChart').getContext('2d');
    const timelineChart = new Chart(timelineCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fev', 'Mar'],
            datasets: [{
                label: 'Comissões (R$)',
                data: [980, 1250, 1175],
                borderColor: 'rgba(var(--primary-color-rgb), 1)',
                backgroundColor: 'rgba(var(--primary-color-rgb), 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgba(var(--primary-color-rgb), 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Payment type change handler
    document.getElementById('paymentType').addEventListener('change', function() {
        const installmentsDiv = document.getElementById('installmentsDiv');
        if (this.value === 'parcelado') {
            installmentsDiv.classList.remove('hidden');
        } else {
            installmentsDiv.classList.add('hidden');
        }
    });
});

function calculateCommission() {
    const saleValue = parseFloat(document.getElementById('saleValue').value) || 0;
    const paymentType = document.getElementById('paymentType').value;
    const installments = document.getElementById('installments').value;
    
    let rate = 0;
    let commissionRate = 0;
    
    switch(paymentType) {
        case 'debito':
            rate = 1.2;
            commissionRate = 0.85;
            break;
        case 'credito':
            rate = 2.8;
            commissionRate = 1.2;
            break;
        case 'parcelado':
            rate = 4.2;
            commissionRate = 1.8;
            break;
    }
    
    const commissionValue = (saleValue * commissionRate) / 100;
    
    document.getElementById('resultSaleValue').textContent = 'R$ ' + saleValue.toLocaleString('pt-BR', {minimumFractionDigits: 2});
    document.getElementById('resultRate').textContent = rate + '%';
    document.getElementById('resultCommissionRate').textContent = commissionRate + '%';
    document.getElementById('resultCommissionValue').textContent = 'R$ ' + commissionValue.toLocaleString('pt-BR', {minimumFractionDigits: 2});
}
</script>
@endpush
