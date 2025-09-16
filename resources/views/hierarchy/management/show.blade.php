@extends('layouts.app')

@section('title', 'Detalhes do Nó')

@section('content')
<x-dynamic-branding />

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">{{ $node->name }}</h1>
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst(str_replace('_', ' ', $node->node_type)) }}
                    </span>
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $node->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $node->is_active ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('hierarchy.management.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteúdo principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Coluna principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informações básicas -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Informações Básicas</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nome</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $node->name }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $node->email }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tipo de Nó</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $node->node_type)) }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nível Hierárquico</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $node->hierarchy_level }}</dd>
                            </div>
                            
                            @if($node->domain)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Domínio</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $node->domain }}</dd>
                            </div>
                            @endif
                            
                            @if($node->subdomain)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Subdomínio</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $node->subdomain }}</dd>
                            </div>
                            @endif
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $node->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Última atualização</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $node->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Hierarquia -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Estrutura Hierárquica</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Caminho hierárquico -->
                            @if($node->hierarchy_path)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Caminho na Hierarquia</h3>
                                <nav class="flex" aria-label="Breadcrumb">
                                    <ol class="flex items-center space-x-4">
                                        @php
                                            $pathIds = explode('/', $node->hierarchy_path);
                                            $pathUsers = \App\Models\User::whereIn('id', $pathIds)->get()->keyBy('id');
                                        @endphp
                                        
                                        @foreach($pathIds as $index => $pathId)
                                            @if(isset($pathUsers[$pathId]))
                                            <li class="flex items-center">
                                                @if($index > 0)
                                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                @endif
                                                <span class="text-sm font-medium text-gray-500">{{ $pathUsers[$pathId]->name }}</span>
                                            </li>
                                            @endif
                                        @endforeach
                                        
                                        <li class="flex items-center">
                                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-sm font-medium text-indigo-600">{{ $node->name }}</span>
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                            @endif

                            <!-- Pai -->
                            @if($node->parent)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Nó Pai</h3>
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center">
                                            <span class="text-white text-sm font-medium">{{ substr($node->parent->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $node->parent->name }}</p>
                                        <p class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $node->parent->node_type)) }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Filhos -->
                            @if($node->children()->count() > 0)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Filhos Diretos ({{ $node->children()->count() }})</h3>
                                <div class="space-y-2">
                                    @foreach($node->children()->limit(5)->get() as $child)
                                    <div class="flex items-center p-2 bg-gray-50 rounded">
                                        <div class="flex-shrink-0">
                                            <div class="w-6 h-6 bg-indigo-500 rounded-full flex items-center justify-center">
                                                <span class="text-white text-xs">{{ substr($child->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-2 flex-1">
                                            <p class="text-sm text-gray-900">{{ $child->name }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $child->node_type)) }}</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <a href="{{ route('hierarchy.management.show', $child->id) }}" class="text-indigo-600 hover:text-indigo-800 text-xs">Ver</a>
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                    @if($node->children()->count() > 5)
                                    <p class="text-sm text-gray-500 text-center py-2">
                                        E mais {{ $node->children()->count() - 5 }} filhos...
                                    </p>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Módulos -->
                @if($node->modules && count($node->modules) > 0)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Módulos Configurados</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($node->modules as $moduleKey => $moduleConfig)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-2 h-2 {{ ($moduleConfig['enabled'] ?? false) ? 'bg-green-400' : 'bg-gray-300' }} rounded-full"></div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ ucfirst($moduleKey) }}</p>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ ($moduleConfig['enabled'] ?? false) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ($moduleConfig['enabled'] ?? false) ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Coluna lateral -->
            <div class="space-y-6">
                <!-- Métricas -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Métricas</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Total de Descendentes:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $nodeMetrics['total_descendants'] }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Descendentes Ativos:</span>
                                <span class="text-sm font-medium text-green-600">{{ $nodeMetrics['active_descendants'] }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Filhos Diretos:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $nodeMetrics['direct_children'] }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Módulos Configurados:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $nodeMetrics['modules_count'] }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Criado há:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $nodeMetrics['created_days_ago'] }} dias</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Relacionamentos -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Relacionamentos</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @if($node->orbitaOperacao)
                            <div>
                                <span class="text-sm text-gray-600">Operação:</span>
                                <p class="text-sm font-medium text-gray-900">{{ $node->orbitaOperacao->display_name }}</p>
                            </div>
                            @endif
                            
                            @if($node->whiteLabel)
                            <div>
                                <span class="text-sm text-gray-600">White Label:</span>
                                <p class="text-sm font-medium text-gray-900">{{ $node->whiteLabel->display_name }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Ações</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @if($user->canImpersonate($node))
                            <a href="{{ route('impersonation.start', $node->id) }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                                Impersonar
                            </a>
                            @endif
                            
                            @if($node->canHaveChildren())
                            <a href="{{ route('hierarchy.management.create', ['type' => $node->getChildNodeType()]) }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Criar Filho
                            </a>
                            @endif
                            
                            <button onclick="toggleStatus({{ $node->id }})" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md {{ $node->is_active ? 'text-red-700 hover:bg-red-50' : 'text-green-700 hover:bg-green-50' }} bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($node->is_active)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    @endif
                                </svg>
                                {{ $node->is_active ? 'Desativar' : 'Ativar' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function toggleStatus(nodeId) {
    if (!confirm('Tem certeza que deseja alterar o status deste nó?')) {
        return;
    }
    
    try {
        const response = await fetch(`/hierarchy/management/${nodeId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert(data.message || 'Erro ao alterar status');
        }
    } catch (error) {
        console.error('Erro ao alterar status:', error);
        alert('Erro ao alterar status');
    }
}
</script>
@endsection
