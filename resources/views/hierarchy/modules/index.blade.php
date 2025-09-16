@extends('layouts.app')

@section('title', 'Gerenciamento de Módulos')

@section('content')
<x-dynamic-branding />

<div class="min-h-screen bg-gray-50" x-data="modulesManager()">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Gerenciamento de Módulos</h1>
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst(str_replace('_', ' ', $user->node_type)) }}
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-500">
                        <span class="font-medium text-green-600" x-text="enabledCount">{{ collect($modulesWithStatus)->where('enabled', true)->count() }}</span>
                        de {{ count($modulesWithStatus) }} módulos ativos
                    </div>
                    
                    <a href="{{ route('hierarchy.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
        <!-- Filtros por categoria -->
        <div class="mb-6">
            <div class="flex flex-wrap items-center space-x-4">
                <span class="text-sm font-medium text-gray-700">Filtrar por categoria:</span>
                <button @click="selectedCategory = 'all'" :class="selectedCategory === 'all' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700'" class="px-3 py-1 rounded-full text-sm font-medium hover:bg-indigo-50 transition-colors duration-200">
                    Todas
                </button>
                <template x-for="category in categories" :key="category">
                    <button @click="selectedCategory = category" :class="selectedCategory === category ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700'" class="px-3 py-1 rounded-full text-sm font-medium hover:bg-indigo-50 transition-colors duration-200" x-text="category"></button>
                </template>
            </div>
        </div>

        <!-- Grid de módulos -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($modulesWithStatus as $key => $module)
            <div x-show="selectedCategory === 'all' || selectedCategory === '{{ $module['category'] }}'" 
                 class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                
                <!-- Header do módulo -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 {{ $module['enabled'] ? 'bg-green-500' : 'bg-gray-400' }} rounded-lg flex items-center justify-center transition-colors duration-200">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($module['icon'] === 'credit-card')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        @elseif($module['icon'] === 'document-text')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        @elseif($module['icon'] === 'globe-alt')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                        @elseif($module['icon'] === 'chart-bar')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        @elseif($module['icon'] === 'desktop-computer')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        @elseif($module['icon'] === 'calendar')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        @elseif($module['icon'] === 'user-group')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        @endif
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900">{{ $module['name'] }}</h3>
                                <p class="text-sm text-gray-500">{{ $module['description'] }}</p>
                            </div>
                        </div>
                        
                        <!-- Toggle switch -->
                        @if($module['can_configure'])
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   {{ $module['locally_enabled'] ? 'checked' : '' }}
                                   @change="toggleModule('{{ $key }}', $event.target.checked)"
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                        @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $module['enabled'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $module['enabled'] ? 'Ativo' : 'Inativo' }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Corpo do módulo -->
                <div class="p-6">
                    <!-- Status e herança -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Status:</span>
                            <div class="flex items-center space-x-2">
                                @if($module['enabled'])
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Ativo
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Inativo
                                </span>
                                @endif
                            </div>
                        </div>

                        @if($module['inherited'])
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Fonte:</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                Herdado ({{ $module['inherited_from'] }})
                            </span>
                        </div>
                        @endif

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Categoria:</span>
                            <span class="text-gray-900 font-medium">{{ $module['category'] }}</span>
                        </div>

                        @if(!empty($module['dependencies']))
                        <div class="text-sm">
                            <span class="text-gray-600">Dependências:</span>
                            <div class="mt-1 flex flex-wrap gap-1">
                                @foreach($module['dependencies'] as $dep)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $modulesWithStatus[$dep]['enabled'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $modulesWithStatus[$dep]['name'] }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Ações -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            @if($module['can_configure'])
                            <button @click="configureModule('{{ $key }}')" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Configurar
                            </button>
                            @else
                            <span class="text-sm text-gray-500">Configuração herdada</span>
                            @endif

                            @if($module['locally_enabled'] && $module['can_configure'])
                            <button @click="resetModule('{{ $key }}')" class="text-sm text-gray-600 hover:text-gray-800">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Resetar
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Mensagem quando não há módulos -->
        <div x-show="filteredModulesCount === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum módulo encontrado</h3>
            <p class="mt-1 text-sm text-gray-500">Não há módulos disponíveis para esta categoria.</p>
        </div>
    </div>
</div>

<script>
function modulesManager() {
    return {
        selectedCategory: 'all',
        enabledCount: {{ collect($modulesWithStatus)->where('enabled', true)->count() }},
        categories: @json(array_unique(array_column($modulesWithStatus, 'category'))),
        modules: @json($modulesWithStatus),
        
        get filteredModulesCount() {
            if (this.selectedCategory === 'all') {
                return Object.keys(this.modules).length;
            }
            return Object.values(this.modules).filter(module => module.category === this.selectedCategory).length;
        },
        
        async toggleModule(moduleKey, enabled) {
            try {
                const response = await fetch(`/hierarchy/modules/${moduleKey}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ enabled })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    
                    // Atualizar estado local
                    this.modules[moduleKey].locally_enabled = enabled;
                    this.modules[moduleKey].enabled = enabled;
                    
                    // Atualizar contador
                    this.updateEnabledCount();
                } else {
                    this.showNotification(data.message || 'Erro ao atualizar módulo', 'error');
                    
                    // Reverter toggle
                    event.target.checked = !enabled;
                }
            } catch (error) {
                console.error('Erro ao atualizar módulo:', error);
                this.showNotification('Erro ao atualizar módulo', 'error');
                
                // Reverter toggle
                event.target.checked = !enabled;
            }
        },
        
        configureModule(moduleKey) {
            // Por enquanto, apenas mostrar alerta
            // Em produção, abriria modal de configuração
            alert(`Configurar módulo: ${this.modules[moduleKey].name}\n\nEsta funcionalidade será implementada em breve.`);
        },
        
        async resetModule(moduleKey) {
            if (!confirm(`Tem certeza que deseja resetar o módulo ${this.modules[moduleKey].name}?`)) {
                return;
            }
            
            try {
                const response = await fetch(`/hierarchy/modules/${moduleKey}/reset`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    this.showNotification(data.message || 'Erro ao resetar módulo', 'error');
                }
            } catch (error) {
                console.error('Erro ao resetar módulo:', error);
                this.showNotification('Erro ao resetar módulo', 'error');
            }
        },
        
        updateEnabledCount() {
            this.enabledCount = Object.values(this.modules).filter(module => module.enabled).length;
        },
        
        showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    }
}
</script>
@endsection
