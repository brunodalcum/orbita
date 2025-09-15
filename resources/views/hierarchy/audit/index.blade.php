@extends('layouts.app')

@section('title', 'Auditoria e Logs')

@section('content')
<div class="min-h-screen bg-gray-50" x-data="auditManager()">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Auditoria e Logs</h1>
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ ucfirst(str_replace('_', ' ', $user->node_type)) }}
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Dropdown para exportar -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Exportar
                            <svg class="ml-2 -mr-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                            <div class="py-1">
                                <button @click="exportLogs('csv')" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="mr-3 h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    CSV (.csv)
                                </button>
                                <button @click="exportLogs('excel')" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="mr-3 h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Excel (.xlsx)
                                </button>
                            </div>
                        </div>
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

    <!-- Estatísticas -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total de Logs</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_logs']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="font-medium text-blue-600">{{ $stats['unique_users'] }}</span>
                        <span class="text-gray-500">usuários únicos</span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Logs Info</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['by_severity']['info']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-500">ações normais</span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Logs Warning</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['by_severity']['warning']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-500">ações importantes</span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Logs Sensíveis</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['sensitive_logs']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-500">dados críticos</span>
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
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                        <input type="text" x-model="searchTerm" @input.debounce.300ms="applyFilters()" placeholder="Descrição, usuário, recurso..." class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ação</label>
                        <select x-model="selectedAction" @change="applyFilters()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="all">Todas</option>
                            <option value="create">Criar</option>
                            <option value="update">Atualizar</option>
                            <option value="delete">Excluir</option>
                            <option value="login">Login</option>
                            <option value="logout">Logout</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Severidade</label>
                        <select x-model="selectedSeverity" @change="applyFilters()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="all">Todas</option>
                            <option value="info">Info</option>
                            <option value="warning">Warning</option>
                            <option value="error">Error</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Período</label>
                        <select x-model="selectedDateRange" @change="applyFilters()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="1day">Último dia</option>
                            <option value="7days" selected>Últimos 7 dias</option>
                            <option value="30days">Últimos 30 dias</option>
                            <option value="90days">Últimos 90 dias</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de logs -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900">Logs de Auditoria</h2>
                    <div class="text-sm text-gray-500">
                        {{ $logs->total() }} logs encontrados
                    </div>
                </div>
            </div>
            
            <div class="overflow-hidden">
                @if($logs->count() > 0)
                @foreach($logs as $log)
                <div class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                    <div class="px-6 py-4">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center bg-{{ $log->severity_color }}-100">
                                        <svg class="w-5 h-5 text-{{ $log->severity_color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($log->action === 'create')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            @elseif($log->action === 'update')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            @elseif($log->action === 'delete')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            @elseif($log->action === 'login')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                            @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            @endif
                                        </svg>
                                    </div>
                                </div>
                                
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <p class="text-sm font-medium text-gray-900">{{ $log->description }}</p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $log->severity_color }}-100 text-{{ $log->severity_color }}-800">
                                            {{ ucfirst($log->severity) }}
                                        </span>
                                        @if($log->sensitive)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Sensível
                                        </span>
                                        @endif
                                    </div>
                                    <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                        <span>{{ $log->user_name ?? 'Sistema' }}</span>
                                        <span>{{ ucfirst($log->action) }}</span>
                                        <span>{{ ucfirst($log->resource_type) }}</span>
                                        @if($log->resource_name)
                                        <span>{{ $log->resource_name }}</span>
                                        @endif
                                        <span>{{ $log->occurred_at->format('d/m/Y H:i:s') }}</span>
                                        @if($log->ip_address)
                                        <span>IP: {{ $log->ip_address }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('hierarchy.audit.show', $log->id) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    Ver Detalhes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum log encontrado</h3>
                    <p class="mt-1 text-sm text-gray-500">Tente ajustar os filtros de busca.</p>
                </div>
                @endif
            </div>
            
            <!-- Paginação -->
            @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $logs->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function auditManager() {
    return {
        searchTerm: '{{ $search }}',
        selectedAction: '{{ $action }}',
        selectedSeverity: '{{ $severity }}',
        selectedDateRange: '{{ $dateRange }}',
        
        applyFilters() {
            const params = new URLSearchParams();
            if (this.searchTerm) params.append('search', this.searchTerm);
            if (this.selectedAction !== 'all') params.append('action', this.selectedAction);
            if (this.selectedSeverity !== 'all') params.append('severity', this.selectedSeverity);
            if (this.selectedDateRange !== '7days') params.append('date_range', this.selectedDateRange);
            
            window.location.href = `{{ route('hierarchy.audit.index') }}?${params.toString()}`;
        },
        
        async exportLogs(format) {
            try {
                const response = await fetch(`{{ route('hierarchy.audit.export') }}?format=${format}&date_range=${this.selectedDateRange}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification(`${data.message} (${data.count} logs)`, 'success');
                } else {
                    showNotification(data.message || 'Erro ao exportar logs', 'error');
                }
            } catch (error) {
                console.error('Erro ao exportar logs:', error);
                showNotification('Erro ao exportar logs', 'error');
            }
        }
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
