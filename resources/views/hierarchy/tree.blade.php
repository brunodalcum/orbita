@extends('layouts.app')

@section('title', 'Árvore da Hierarquia')

@section('content')
<div class="min-h-screen bg-gray-50" x-data="hierarchyTree()">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Árvore da Hierarquia</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button @click="expandAll()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Expandir Tudo
                    </button>
                    <button @click="collapseAll()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Recolher Tudo
                    </button>
                    <a href="{{ route('hierarchy.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Voltar ao Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteúdo principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900">Estrutura Completa</h2>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                <span>Operação</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <span>White Label</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                <span>L1</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-orange-500 rounded-full mr-2"></div>
                                <span>L2</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                <span>L3</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Árvore hierárquica -->
                <div class="space-y-4">
                    @if($user->isSuperAdminNode())
                        <!-- Super Admin vê tudo -->
                        <div class="tree-node">
                            <div class="flex items-center p-3 bg-purple-50 rounded-lg border-2 border-purple-200">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">Super Admin - Controle Total</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Super Admin
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Operações -->
                            <div class="ml-8 mt-4 space-y-4">
                                @php
                                    $operacoes = \App\Models\OrbitaOperacao::with(['whiteLabels.users', 'users'])->get();
                                @endphp
                                
                                @foreach($operacoes as $operacao)
                                <div x-data="{ expanded: true }">
                                    <div class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-200 cursor-pointer" @click="expanded = !expanded">
                                        <div class="flex-shrink-0">
                                            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{ 'rotate-90': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                        <div class="flex-shrink-0 ml-2">
                                            <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $operacao->display_name }}</p>
                                            <p class="text-sm text-gray-500">{{ $operacao->subdomain }}</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Operação
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div x-show="expanded" x-transition class="ml-8 mt-2 space-y-2">
                                        <!-- White Labels da operação -->
                                        @foreach($operacao->whiteLabels as $whiteLabel)
                                        <div x-data="{ wlExpanded: true }">
                                            <div class="flex items-center p-2 bg-green-50 rounded border border-green-200 cursor-pointer" @click="wlExpanded = !wlExpanded">
                                                <div class="flex-shrink-0">
                                                    <svg class="w-3 h-3 text-gray-400 transition-transform" :class="{ 'rotate-90': wlExpanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-shrink-0 ml-2">
                                                    <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-2 flex-1">
                                                    <p class="text-sm font-medium text-gray-900">{{ $whiteLabel->display_name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $whiteLabel->subdomain }}</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        White Label
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div x-show="wlExpanded" x-transition class="ml-6 mt-1 space-y-1">
                                                @php
                                                    $l1Users = \App\Models\User::where('white_label_id', $whiteLabel->id)
                                                                              ->where('node_type', 'licenciado_l1')
                                                                              ->with(['children.children'])
                                                                              ->get();
                                                @endphp
                                                
                                                @foreach($l1Users as $l1)
                                                    @include('hierarchy.partials.user-node', ['user' => $l1, 'level' => 'l1'])
                                                @endforeach
                                            </div>
                                        </div>
                                        @endforeach
                                        
                                        <!-- Licenciados diretos da operação (sem White Label) -->
                                        @php
                                            $directL1 = \App\Models\User::where('operacao_id', $operacao->id)
                                                                      ->whereNull('white_label_id')
                                                                      ->where('node_type', 'licenciado_l1')
                                                                      ->with(['children.children'])
                                                                      ->get();
                                        @endphp
                                        
                                        @foreach($directL1 as $l1)
                                            @include('hierarchy.partials.user-node', ['user' => $l1, 'level' => 'l1'])
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- Outros usuários veem apenas sua descendência -->
                        <div class="tree-node">
                            @include('hierarchy.partials.user-node', ['user' => $user, 'level' => $user->node_type, 'isRoot' => true])
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function hierarchyTree() {
    return {
        expandAll() {
            // Expandir todos os nós
            document.querySelectorAll('[x-data]').forEach(el => {
                if (el._x_dataStack && el._x_dataStack[0].expanded !== undefined) {
                    el._x_dataStack[0].expanded = true;
                }
                if (el._x_dataStack && el._x_dataStack[0].wlExpanded !== undefined) {
                    el._x_dataStack[0].wlExpanded = true;
                }
                if (el._x_dataStack && el._x_dataStack[0].userExpanded !== undefined) {
                    el._x_dataStack[0].userExpanded = true;
                }
            });
        },
        
        collapseAll() {
            // Recolher todos os nós
            document.querySelectorAll('[x-data]').forEach(el => {
                if (el._x_dataStack && el._x_dataStack[0].expanded !== undefined) {
                    el._x_dataStack[0].expanded = false;
                }
                if (el._x_dataStack && el._x_dataStack[0].wlExpanded !== undefined) {
                    el._x_dataStack[0].wlExpanded = false;
                }
                if (el._x_dataStack && el._x_dataStack[0].userExpanded !== undefined) {
                    el._x_dataStack[0].userExpanded = false;
                }
            });
        }
    }
}
</script>
@endsection
