@extends('layouts.app')

@section('title', 'Gerenciamento de Domínios')

@section('content')
<div class="min-h-screen bg-gray-50" x-data="domainsManager()">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Gerenciamento de Domínios</h1>
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        {{ ucfirst(str_replace('_', ' ', $user->node_type)) }}
                    </span>
                </div>
                <div class="flex items-center space-x-4">
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

    <!-- Estatísticas -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total de Domínios</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_domains'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="font-medium text-green-600">{{ $stats['active_domains'] }}</span>
                        <span class="text-gray-500">ativos</span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Domínios Verificados</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['verified_domains'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-500">de {{ $stats['total_domains'] }} total</span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Domínios Personalizados</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['custom_domains'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="font-medium text-blue-600">{{ $stats['subdomains'] }}</span>
                        <span class="text-gray-500">subdomínios</span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">SSL Habilitado</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['ssl_enabled'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        @if($stats['ssl_expiring_soon'] > 0)
                        <span class="font-medium text-red-600">{{ $stats['ssl_expiring_soon'] }}</span>
                        <span class="text-gray-500">expirando</span>
                        @else
                        <span class="text-gray-500">todos válidos</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Filtros</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar Domínio</label>
                        <input type="text" x-model="searchTerm" @input.debounce.300ms="applyFilters()" placeholder="dominio.com ou subdominio..." class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select x-model="selectedStatus" @change="applyFilters()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="all">Todos</option>
                            <option value="active">Ativos</option>
                            <option value="inactive">Inativos</option>
                            <option value="verified">Verificados</option>
                            <option value="unverified">Não Verificados</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                        <select x-model="selectedType" @change="applyFilters()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="all">Todos</option>
                            <option value="custom">Domínios Personalizados</option>
                            <option value="subdomain">Subdomínios</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de domínios -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900">Domínios Configurados</h2>
                    <div class="text-sm text-gray-500">
                        {{ $domains->total() }} domínios encontrados
                    </div>
                </div>
            </div>
            
            <div class="overflow-hidden">
                @if($domains->count() > 0)
                @foreach($domains as $domain)
                <div class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $domain->domain_type === 'custom' ? 'bg-purple-500' : 'bg-blue-500' }}">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($domain->domain_type === 'custom')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                            @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                                            @endif
                                        </svg>
                                    </div>
                                </div>
                                
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <h3 class="text-sm font-medium text-gray-900">{{ $domain->display_name }}</h3>
                                        @if($domain->is_primary)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Primário
                                        </span>
                                        @endif
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $domain->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $domain->is_active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $domain->verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $domain->verified ? 'Verificado' : 'Pendente' }}
                                        </span>
                                        @if($domain->ssl_enabled)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            SSL
                                        </span>
                                        @endif
                                    </div>
                                    <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                        <span>{{ ucfirst($domain->domain_type) }}</span>
                                        <span>Criado em {{ $domain->created_at->format('d/m/Y') }}</span>
                                        @if($domain->createdBy)
                                        <span>por {{ $domain->createdBy->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                @if(!$domain->verified)
                                <button onclick="verifyDomain({{ $domain->id }})" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    Verificar
                                </button>
                                @endif
                                
                                <button onclick="toggleDomainStatus({{ $domain->id }})" class="text-sm font-medium {{ $domain->is_active ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800' }}">
                                    {{ $domain->is_active ? 'Desativar' : 'Ativar' }}
                                </button>
                                
                                <a href="{{ route('hierarchy.domains.manage', [$domain->node_type, $domain->node_id]) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    Gerenciar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum domínio encontrado</h3>
                    <p class="mt-1 text-sm text-gray-500">Tente ajustar os filtros de busca.</p>
                </div>
                @endif
            </div>
            
            <!-- Paginação -->
            @if($domains->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $domains->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function domainsManager() {
    return {
        searchTerm: '{{ $search }}',
        selectedStatus: '{{ $status }}',
        selectedType: '{{ $type }}',
        
        applyFilters() {
            const params = new URLSearchParams();
            if (this.searchTerm) params.append('search', this.searchTerm);
            if (this.selectedStatus !== 'all') params.append('status', this.selectedStatus);
            if (this.selectedType !== 'all') params.append('type', this.selectedType);
            
            window.location.href = `{{ route('hierarchy.domains.index') }}?${params.toString()}`;
        }
    }
}

async function verifyDomain(domainId) {
    try {
        const response = await fetch(`/hierarchy/domains/${domainId}/verify`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification(data.message || 'Erro ao verificar domínio', 'error');
        }
    } catch (error) {
        console.error('Erro ao verificar domínio:', error);
        showNotification('Erro ao verificar domínio', 'error');
    }
}

async function toggleDomainStatus(domainId) {
    try {
        const response = await fetch(`/hierarchy/domains/${domainId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification(data.message || 'Erro ao alterar status', 'error');
        }
    } catch (error) {
        console.error('Erro ao alterar status:', error);
        showNotification('Erro ao alterar status', 'error');
    }
}

function showNotification(message, type) {
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
</script>
@endsection
