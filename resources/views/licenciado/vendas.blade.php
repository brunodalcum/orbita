@extends('layouts.licenciado')

@section('title', 'Vendas')
@section('subtitle', 'Acompanhe suas vendas e performance')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Vendas</h2>
            <p class="text-gray-600">Acompanhe o desempenho das suas vendas</p>
        </div>
        <div class="flex space-x-2">
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option>Últimos 7 dias</option>
                <option>Últimos 30 dias</option>
                <option>Últimos 90 dias</option>
            </select>
            <button class="btn-primary">
                <i class="fas fa-download mr-2"></i>
                Exportar
            </button>
        </div>
    </div>

    <!-- Sales Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card green">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Hoje</p>
                    <p class="text-3xl font-bold">R$ {{ number_format($vendas->vendas_hoje, 2, ',', '.') }}</p>
                    <p class="text-green-200 text-xs mt-1">Vendas realizadas</p>
                </div>
                <div class="text-4xl text-green-200">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>

        <div class="stat-card blue">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Esta Semana</p>
                    <p class="text-3xl font-bold">R$ {{ number_format($vendas->vendas_semana, 2, ',', '.') }}</p>
                    <p class="text-blue-200 text-xs mt-1">Acumulado</p>
                </div>
                <div class="text-4xl text-blue-200">
                    <i class="fas fa-calendar-week"></i>
                </div>
            </div>
        </div>

        <div class="stat-card orange">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-pink-100 text-sm font-medium">Este Mês</p>
                    <p class="text-3xl font-bold">R$ {{ number_format($vendas->vendas_mes, 2, ',', '.') }}</p>
                    <p class="text-pink-200 text-xs mt-1">{{ $vendas->percentual_meta }}% da meta</p>
                </div>
                <div class="text-4xl text-pink-200">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>

        <div class="stat-card purple">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-cyan-100 text-sm font-medium">Meta Mensal</p>
                    <p class="text-3xl font-bold">R$ {{ number_format($vendas->meta_mes, 2, ',', '.') }}</p>
                    <p class="text-cyan-200 text-xs mt-1">Objetivo</p>
                </div>
                <div class="text-4xl text-cyan-200">
                    <i class="fas fa-target"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="card p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Progresso da Meta Mensal</h3>
            <span class="text-2xl font-bold text-blue-600">{{ $vendas->percentual_meta }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
            <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-4 rounded-full transition-all duration-500" 
                 style="width: {{ $vendas->percentual_meta }}%"></div>
        </div>
        <div class="flex justify-between text-sm text-gray-600">
            <span>R$ {{ number_format($vendas->vendas_mes, 2, ',', '.') }} vendido</span>
            <span>R$ {{ number_format($vendas->meta_mes - $vendas->vendas_mes, 2, ',', '.') }} restante</span>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales Chart -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Vendas dos Últimos 7 Dias</h3>
            <canvas id="salesChart" width="400" height="300"></canvas>
        </div>

        <!-- Performance Metrics -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Métricas de Performance</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-arrow-up text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Crescimento</p>
                            <p class="text-sm text-gray-600">Comparado ao mês anterior</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-green-600">+15.5%</span>
                </div>

                <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-credit-card text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Ticket Médio</p>
                            <p class="text-sm text-gray-600">Valor médio por transação</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-blue-600">R$ 125,50</span>
                </div>

                <div class="flex justify-between items-center p-4 bg-purple-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-chart-line text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Conversão</p>
                            <p class="text-sm text-gray-600">Taxa de aprovação</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-purple-600">94.2%</span>
                </div>

                <div class="flex justify-between items-center p-4 bg-orange-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-orange-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Tempo Médio</p>
                            <p class="text-sm text-gray-600">Por transação</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-orange-600">2.3s</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Transações Recentes</h3>
                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Ver todas <i class="fas fa-arrow-right ml-1"></i>
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Data/Hora</th>
                        <th>Estabelecimento</th>
                        <th>Valor</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th>Comissão</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div>
                                <p class="font-medium">{{ now()->format('d/m/Y') }}</p>
                                <p class="text-sm text-gray-500">{{ now()->format('H:i:s') }}</p>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-store text-blue-600 text-xs"></i>
                                </div>
                                <span>Loja Centro</span>
                            </div>
                        </td>
                        <td>
                            <span class="font-semibold text-green-600">R$ 350,00</span>
                        </td>
                        <td>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Débito</span>
                        </td>
                        <td>
                            <span class="status-badge ativo">Aprovada</span>
                        </td>
                        <td>
                            <span class="font-semibold text-purple-600">R$ 2,80</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                <p class="font-medium">{{ now()->subHour()->format('d/m/Y') }}</p>
                                <p class="text-sm text-gray-500">{{ now()->subHour()->format('H:i:s') }}</p>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-store text-green-600 text-xs"></i>
                                </div>
                                <span>Filial Norte</span>
                            </div>
                        </td>
                        <td>
                            <span class="font-semibold text-green-600">R$ 125,50</span>
                        </td>
                        <td>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Crédito</span>
                        </td>
                        <td>
                            <span class="status-badge ativo">Aprovada</span>
                        </td>
                        <td>
                            <span class="font-semibold text-purple-600">R$ 1,65</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                <p class="font-medium">{{ now()->subHours(2)->format('d/m/Y') }}</p>
                                <p class="text-sm text-gray-500">{{ now()->subHours(2)->format('H:i:s') }}</p>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-store text-purple-600 text-xs"></i>
                                </div>
                                <span>Loja Centro</span>
                            </div>
                        </td>
                        <td>
                            <span class="font-semibold text-green-600">R$ 89,90</span>
                        </td>
                        <td>
                            <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs">Parcelado</span>
                        </td>
                        <td>
                            <span class="status-badge ativo">Aprovada</span>
                        </td>
                        <td>
                            <span class="font-semibold text-purple-600">R$ 1,35</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json(collect($vendas->vendas_por_dia)->pluck('data')),
            datasets: [{
                label: 'Vendas (R$)',
                data: @json(collect($vendas->vendas_por_dia)->pluck('valor')),
                borderColor: 'rgba(59, 130, 246, 1)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
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
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return 'Vendas: R$ ' + context.parsed.y.toLocaleString('pt-BR');
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
});
</script>
@endpush
