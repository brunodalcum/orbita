@extends('layouts.app')

@section('title', 'Relatório de Hierarquia')

@section('content')
<div class="min-h-screen bg-gray-50" x-data="hierarchyReport()">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Relatório de Hierarquia</h1>
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $dateRange['label'] }}
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    <button @click="printReport()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Imprimir
                    </button>
                    
                    <a href="{{ route('hierarchy.reports.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Voltar aos Relatórios
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteúdo do relatório -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" id="report-content">
        <!-- Resumo executivo -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Resumo Executivo</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @if($user->isSuperAdminNode())
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $hierarchyData['statistics']['by_type']['operacao'] ?? 0 }}</div>
                        <div class="text-sm text-gray-500">Operações Ativas</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $hierarchyData['statistics']['by_type']['white_label'] ?? 0 }}</div>
                        <div class="text-sm text-gray-500">White Labels</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">
                            {{ ($hierarchyData['statistics']['by_type']['licenciado_l1'] ?? 0) + 
                               ($hierarchyData['statistics']['by_type']['licenciado_l2'] ?? 0) + 
                               ($hierarchyData['statistics']['by_type']['licenciado_l3'] ?? 0) }}
                        </div>
                        <div class="text-sm text-gray-500">Total Licenciados</div>
                    </div>
                    @else
                    <div class="text-center">
                        <div class="text-3xl font-bold text-indigo-600">{{ $hierarchyData['structure']['total_levels'] ?? 0 }}</div>
                        <div class="text-sm text-gray-500">Níveis na Hierarquia</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $hierarchyData['structure']['max_depth'] ?? 0 }}</div>
                        <div class="text-sm text-gray-500">Profundidade Máxima</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">
                            {{ collect($hierarchyData['statistics']['by_type'] ?? [])->sum() }}
                        </div>
                        <div class="text-sm text-gray-500">Total de Nós</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Análise de crescimento -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Análise de Crescimento</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-medium text-gray-900">{{ $hierarchyData['growth_analysis']['current_period'] }}</div>
                                <div class="text-sm text-gray-500">Novos nós (período atual)</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gray-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-medium text-gray-900">{{ $hierarchyData['growth_analysis']['previous_period'] }}</div>
                                <div class="text-sm text-gray-500">Novos nós (período anterior)</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-{{ $hierarchyData['growth_analysis']['growth_rate'] >= 0 ? 'green' : 'red' }}-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-{{ $hierarchyData['growth_analysis']['growth_rate'] >= 0 ? 'green' : 'red' }}-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($hierarchyData['growth_analysis']['growth_rate'] >= 0)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                        @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                                        @endif
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-medium text-gray-900">
                                    {{ $hierarchyData['growth_analysis']['growth_rate'] >= 0 ? '+' : '' }}{{ $hierarchyData['growth_analysis']['growth_rate'] }}%
                                </div>
                                <div class="text-sm text-gray-500">Taxa de crescimento</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribuição por tipo -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Distribuição por Tipo de Nó</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($hierarchyData['statistics']['by_type'] as $type => $count)
                    @php
                        $total = collect($hierarchyData['statistics']['by_type'])->sum();
                        $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                        $colors = [
                            'operacao' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-700', 'bg-light' => 'bg-blue-100'],
                            'white_label' => ['bg' => 'bg-green-500', 'text' => 'text-green-700', 'bg-light' => 'bg-green-100'],
                            'licenciado_l1' => ['bg' => 'bg-yellow-500', 'text' => 'text-yellow-700', 'bg-light' => 'bg-yellow-100'],
                            'licenciado_l2' => ['bg' => 'bg-orange-500', 'text' => 'text-orange-700', 'bg-light' => 'bg-orange-100'],
                            'licenciado_l3' => ['bg' => 'bg-red-500', 'text' => 'text-red-700', 'bg-light' => 'bg-red-100']
                        ];
                        $color = $colors[$type] ?? ['bg' => 'bg-gray-500', 'text' => 'text-gray-700', 'bg-light' => 'bg-gray-100'];
                    @endphp
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 {{ $color['bg'] }} rounded-full"></div>
                            <span class="text-sm font-medium text-gray-900">
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                <div class="{{ $color['bg'] }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <div class="text-sm {{ $color['text'] }} font-medium w-16 text-right">
                                {{ $count }} ({{ $percentage }}%)
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        @if($user->isSuperAdminNode() && isset($hierarchyData['structure']['operacoes']))
        <!-- Estrutura detalhada (apenas para Super Admin) -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Estrutura Detalhada</h2>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    @foreach($hierarchyData['structure']['operacoes'] as $operacao)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ $operacao->display_name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $operacao->description }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $operacao->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $operacao->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">White Labels:</span>
                                <span class="text-gray-900">{{ $operacao->whiteLabels->count() }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Licenciados Diretos:</span>
                                <span class="text-gray-900">{{ $operacao->users->count() }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Criado em:</span>
                                <span class="text-gray-900">{{ $operacao->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        
                        @if($operacao->whiteLabels->count() > 0)
                        <div class="mt-4 pl-4 border-l-2 border-gray-200">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">White Labels:</h4>
                            <div class="space-y-2">
                                @foreach($operacao->whiteLabels as $whiteLabel)
                                <div class="flex items-center justify-between p-2 bg-green-50 rounded">
                                    <span class="text-sm text-gray-900">{{ $whiteLabel->display_name }}</span>
                                    <span class="text-xs text-gray-500">{{ $whiteLabel->users->count() }} licenciados</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Rodapé do relatório -->
        <div class="bg-white shadow rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <div>
                        <p>Relatório gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
                        <p>Usuário: {{ $user->name }} ({{ ucfirst(str_replace('_', ' ', $user->node_type)) }})</p>
                    </div>
                    <div class="text-right">
                        <p>Período: {{ $dateRange['start']->format('d/m/Y') }} - {{ $dateRange['end']->format('d/m/Y') }}</p>
                        <p>Órbita Platform - Sistema de Hierarquia White Label</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function hierarchyReport() {
    return {
        printReport() {
            window.print();
        }
    }
}

// Estilos para impressão
const printStyles = `
    @media print {
        body * {
            visibility: hidden;
        }
        #report-content, #report-content * {
            visibility: visible;
        }
        #report-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
    }
`;

const styleSheet = document.createElement("style");
styleSheet.type = "text/css";
styleSheet.innerText = printStyles;
document.head.appendChild(styleSheet);
</script>
@endsection
