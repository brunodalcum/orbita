@extends('layouts.app')

@section('title', 'Dashboard da Hierarquia')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header do Dashboard -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            Dashboard da Hierarquia
                        </h1>
                        <p class="mt-1 text-sm text-gray-600">
                            Contexto: {{ $context['name'] }} ({{ ucfirst($context['type']) }})
                        </p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Indicador de Status -->
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-400 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">Sistema Ativo</span>
                        </div>
                        <!-- Botão de Atualizar -->
                        <button onclick="refreshMetrics()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Atualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <div>
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-home"></i>
                            <span class="sr-only">Home</span>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mr-4"></i>
                        <a href="#" class="text-sm font-medium text-gray-500 hover:text-gray-700">Hierarquia</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mr-4"></i>
                        <span class="text-sm font-medium text-gray-900">Dashboard</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <!-- Cards de Métricas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @include('hierarchy.components.metric-card', [
                'title' => 'Total de Nós',
                'value' => $metrics['total_nodes'] ?? 0,
                'icon' => 'users',
                'color' => 'blue',
                'trend' => '+12%'
            ])
            
            @include('hierarchy.components.metric-card', [
                'title' => 'Nós Ativos',
                'value' => $metrics['active_nodes'] ?? 0,
                'icon' => 'user-check',
                'color' => 'green',
                'trend' => '+5%'
            ])
            
            @if($context['type'] === 'super_admin')
                @include('hierarchy.components.metric-card', [
                    'title' => 'Operações',
                    'value' => $metrics['operacoes'] ?? 0,
                    'icon' => 'building',
                    'color' => 'purple',
                    'trend' => '+2%'
                ])
                
                @include('hierarchy.components.metric-card', [
                    'title' => 'White Labels',
                    'value' => $metrics['white_labels'] ?? 0,
                    'icon' => 'tag',
                    'color' => 'orange',
                    'trend' => '+8%'
                ])
            @elseif($context['type'] === 'licenciado')
                @include('hierarchy.components.metric-card', [
                    'title' => 'Descendentes',
                    'value' => $metrics['total_descendants'] ?? 0,
                    'icon' => 'sitemap',
                    'color' => 'purple',
                    'trend' => '+3%'
                ])
                
                @include('hierarchy.components.metric-card', [
                    'title' => 'Filhos Diretos',
                    'value' => $metrics['direct_children'] ?? 0,
                    'icon' => 'user-friends',
                    'color' => 'orange',
                    'trend' => '+1%'
                ])
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Coluna Principal -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Visão Geral da Hierarquia -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Visão Geral da Hierarquia</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600">{{ $hierarchyOverview['current_level'] ?? 1 }}</div>
                                <div class="text-sm text-gray-600">Nível Atual</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-600">{{ $hierarchyOverview['ancestors'] ?? 0 }}</div>
                                <div class="text-sm text-gray-600">Ancestrais</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-purple-600">{{ $hierarchyOverview['descendants'] ?? 0 }}</div>
                                <div class="text-sm text-gray-600">Descendentes</div>
                            </div>
                        </div>
                        
                        <!-- Visualização da Hierarquia -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-center">
                                <div class="hierarchy-visualization">
                                    @include('hierarchy.components.hierarchy-tree-mini')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Módulos Ativos -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Módulos Disponíveis</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($activeModules as $key => $module)
                                <div class="text-center p-4 rounded-lg {{ $module['active'] ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }} border">
                                    <div class="w-12 h-12 mx-auto mb-3 rounded-full {{ $module['active'] ? 'bg-green-100' : 'bg-gray-100' }} flex items-center justify-center">
                                        <i class="fas fa-{{ $module['icon'] }} {{ $module['active'] ? 'text-green-600' : 'text-gray-400' }}"></i>
                                    </div>
                                    <div class="text-sm font-medium {{ $module['active'] ? 'text-green-900' : 'text-gray-500' }}">
                                        {{ $module['name'] }}
                                    </div>
                                    <div class="text-xs {{ $module['active'] ? 'text-green-600' : 'text-gray-400' }} mt-1">
                                        {{ $module['active'] ? 'Ativo' : 'Inativo' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Atividades Recentes -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Atividades Recentes</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($recentActivities as $activity)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-{{ $activity['color'] }}-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-{{ $activity['icon'] }} text-{{ $activity['color'] }}-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                                        <p class="text-sm text-gray-600">{{ $activity['description'] }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $activity['timestamp']->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Ações Rápidas -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Ações Rápidas</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @if($context['type'] === 'super_admin')
                                <a href="#" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    Nova Operação
                                </a>
                                <a href="#" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                    <i class="fas fa-eye mr-2"></i>
                                    Ver Hierarquia Completa
                                </a>
                            @elseif($context['type'] === 'licenciado' && ($metrics['can_have_children'] ?? false))
                                <a href="#" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                    <i class="fas fa-user-plus mr-2"></i>
                                    Adicionar Licenciado
                                </a>
                            @endif
                            
                            <a href="#" class="block w-full bg-gray-600 hover:bg-gray-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                <i class="fas fa-cog mr-2"></i>
                                Configurações
                            </a>
                            
                            <a href="#" class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Relatórios
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function refreshMetrics() {
    // Mostrar loading
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Atualizando...';
    button.disabled = true;
    
    // Simular requisição AJAX
    fetch('{{ route("hierarchy.dashboard.metrics") }}')
        .then(response => response.json())
        .then(data => {
            // Atualizar métricas na página
            console.log('Métricas atualizadas:', data);
            
            // Restaurar botão
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 1000);
        })
        .catch(error => {
            console.error('Erro ao atualizar métricas:', error);
            button.innerHTML = originalText;
            button.disabled = false;
        });
}

// Auto-refresh a cada 30 segundos
setInterval(() => {
    fetch('{{ route("hierarchy.dashboard.metrics") }}')
        .then(response => response.json())
        .then(data => {
            console.log('Auto-refresh das métricas:', data);
        });
}, 30000);
</script>
@endpush
@endsection
