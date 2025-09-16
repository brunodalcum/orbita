@extends('layouts.licenciado')

@section('title', 'Relatórios')
@section('subtitle', 'Gere e baixe relatórios detalhados')

@section('content')
<x-dynamic-branding />

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Relatórios</h2>
            <p class="text-gray-600">Gere relatórios personalizados das suas atividades</p>
        </div>
    </div>

    <!-- Quick Reports -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card p-6 text-center hover:shadow-lg transition-shadow cursor-pointer">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Vendas Detalhado</h3>
            <p class="text-sm text-gray-600 mb-4">Relatório completo de vendas por período</p>
            <button class="btn-primary w-full">Gerar</button>
        </div>

        <div class="card p-6 text-center hover:shadow-lg transition-shadow cursor-pointer">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-coins text-green-600 text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Comissões Mensais</h3>
            <p class="text-sm text-gray-600 mb-4">Histórico detalhado de comissões</p>
            <button class="btn-primary w-full">Gerar</button>
        </div>

        <div class="card p-6 text-center hover:shadow-lg transition-shadow cursor-pointer">
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-store text-purple-600 text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Performance</h3>
            <p class="text-sm text-gray-600 mb-4">Desempenho dos estabelecimentos</p>
            <button class="btn-primary w-full">Gerar</button>
        </div>

        <div class="card p-6 text-center hover:shadow-lg transition-shadow cursor-pointer">
            <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-credit-card text-orange-600 text-2xl"></i>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Por Bandeira</h3>
            <p class="text-sm text-gray-600 mb-4">Transações por bandeira de cartão</p>
            <button class="btn-primary w-full">Gerar</button>
        </div>
    </div>

    <!-- Custom Report Generator -->
    <div class="card p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Relatório Personalizado</h3>
        
        <form class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Relatório</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option>Vendas Detalhado</option>
                        <option>Comissões</option>
                        <option>Transações</option>
                        <option>Performance</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Período</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option>Última semana</option>
                        <option>Último mês</option>
                        <option>Últimos 3 meses</option>
                        <option>Último ano</option>
                        <option>Personalizado</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data Inicial</label>
                    <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data Final</label>
                    <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Formato</label>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="format" value="pdf" class="mr-2" checked>
                        <span class="text-sm">PDF</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="format" value="excel" class="mr-2">
                        <span class="text-sm">Excel</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="format" value="csv" class="mr-2">
                        <span class="text-sm">CSV</span>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-download mr-2"></i>
                    Gerar Relatório
                </button>
            </div>
        </form>
    </div>

    <!-- Recent Reports -->
    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Relatórios Recentes</h3>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Relatório</th>
                        <th>Período</th>
                        <th>Formato</th>
                        <th>Data de Geração</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                                <span class="font-medium">Vendas Março 2024</span>
                            </div>
                        </td>
                        <td>01/03/2024 - 31/03/2024</td>
                        <td><span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">PDF</span></td>
                        <td>{{ now()->format('d/m/Y H:i') }}</td>
                        <td><span class="status-badge ativo">Pronto</span></td>
                        <td>
                            <button class="text-blue-600 hover:text-blue-800 mr-2">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="text-gray-600 hover:text-gray-800">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <i class="fas fa-file-excel text-green-500 mr-3"></i>
                                <span class="font-medium">Comissões Fevereiro 2024</span>
                            </div>
                        </td>
                        <td>01/02/2024 - 29/02/2024</td>
                        <td><span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Excel</span></td>
                        <td>{{ now()->subDay()->format('d/m/Y H:i') }}</td>
                        <td><span class="status-badge ativo">Pronto</span></td>
                        <td>
                            <button class="text-blue-600 hover:text-blue-800 mr-2">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="text-gray-600 hover:text-gray-800">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <i class="fas fa-file-csv text-blue-500 mr-3"></i>
                                <span class="font-medium">Transações Janeiro 2024</span>
                            </div>
                        </td>
                        <td>01/01/2024 - 31/01/2024</td>
                        <td><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">CSV</span></td>
                        <td>{{ now()->subDays(5)->format('d/m/Y H:i') }}</td>
                        <td><span class="status-badge pendente">Processando</span></td>
                        <td>
                            <span class="text-gray-400">
                                <i class="fas fa-clock"></i>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
