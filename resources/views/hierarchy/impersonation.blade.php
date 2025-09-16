@extends('layouts.app')

@section('title', 'Impersonação de Usuários')

@section('content')
<x-dynamic-branding />

<div class="min-h-screen bg-gray-50" x-data="impersonationManager()">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Impersonação de Usuários</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('hierarchy.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Voltar ao Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteúdo principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Coluna principal - Lista de usuários -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-medium text-gray-900">Usuários Disponíveis</h2>
                            <div class="flex items-center space-x-4">
                                <!-- Busca -->
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        x-model="searchTerm"
                                        @input.debounce.300ms="searchUsers()"
                                        placeholder="Buscar usuários..."
                                        class="block w-64 pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Loading -->
                        <div x-show="loading" class="text-center py-8">
                            <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Carregando usuários...</p>
                        </div>

                        <!-- Lista de usuários -->
                        <div x-show="!loading" class="space-y-4">
                            <template x-for="user in users" :key="user.id">
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="flex items-center space-x-4">
                                        <!-- Avatar -->
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center">
                                                <span class="text-white font-medium" x-text="user.name.charAt(0).toUpperCase()"></span>
                                            </div>
                                        </div>
                                        
                                        <!-- Informações do usuário -->
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <h3 class="text-sm font-medium text-gray-900" x-text="user.name"></h3>
                                                <span x-show="!user.is_active" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Inativo
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-500" x-text="user.email"></p>
                                            <div class="flex items-center space-x-4 mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800" x-text="user.node_type_label"></span>
                                                <span class="text-xs text-gray-500">Nível <span x-text="user.hierarchy_level"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Ações -->
                                    <div class="flex-shrink-0">
                                        <button 
                                            @click="startImpersonation(user)"
                                            :disabled="impersonating"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                            
                                            <svg x-show="!impersonating || selectedUserId !== user.id" class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                            </svg>
                                            
                                            <svg x-show="impersonating && selectedUserId === user.id" class="animate-spin w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            
                                            <span x-text="(impersonating && selectedUserId === user.id) ? 'Impersonando...' : 'Impersonar'">Impersonar</span>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <!-- Mensagem quando não há usuários -->
                            <div x-show="users.length === 0 && !loading" class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum usuário encontrado</h3>
                                <p class="mt-1 text-sm text-gray-500">Não há usuários disponíveis para impersonação ou sua busca não retornou resultados.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna lateral -->
            <div class="space-y-6">
                <!-- Informações sobre impersonação -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Sobre a Impersonação</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4 text-sm text-gray-600">
                            <div class="flex items-start space-x-3">
                                <svg class="flex-shrink-0 w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Segurança</p>
                                    <p>Todas as impersonações são registradas para auditoria.</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <svg class="flex-shrink-0 w-5 h-5 text-yellow-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Tempo Limite</p>
                                    <p>Sessões de impersonação expiram automaticamente em 4 horas.</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <svg class="flex-shrink-0 w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Permissões</p>
                                    <p>Você só pode impersonar usuários da sua hierarquia descendente.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Histórico recente -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Histórico Recente</h3>
                    </div>
                    <div class="p-6">
                        <div x-show="historyLoading" class="text-center py-4">
                            <svg class="animate-spin h-6 w-6 text-indigo-600 mx-auto" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>

                        <div x-show="!historyLoading" class="space-y-3">
                            <template x-for="item in history" :key="item.id">
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center">
                                            <span class="text-white text-xs" x-text="item.target_user_name.charAt(0).toUpperCase()"></span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate" x-text="item.target_user_name"></p>
                                        <p class="text-xs text-gray-500" x-text="formatDate(item.started_at)"></p>
                                        <p class="text-xs text-gray-500" x-text="`${item.duration_minutes} min`"></p>
                                    </div>
                                </div>
                            </template>

                            <div x-show="history.length === 0" class="text-center py-4 text-sm text-gray-500">
                                Nenhuma impersonação recente
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function impersonationManager() {
    return {
        users: [],
        history: [],
        searchTerm: '',
        loading: true,
        historyLoading: true,
        impersonating: false,
        selectedUserId: null,
        
        init() {
            this.loadUsers();
            this.loadHistory();
        },
        
        async loadUsers() {
            this.loading = true;
            try {
                const response = await fetch(`/impersonation/available-users?search=${encodeURIComponent(this.searchTerm)}`);
                const data = await response.json();
                this.users = data.users || [];
            } catch (error) {
                console.error('Erro ao carregar usuários:', error);
                this.users = [];
            } finally {
                this.loading = false;
            }
        },
        
        async loadHistory() {
            this.historyLoading = true;
            try {
                const response = await fetch('/impersonation/history');
                const data = await response.json();
                this.history = data.history || [];
            } catch (error) {
                console.error('Erro ao carregar histórico:', error);
                this.history = [];
            } finally {
                this.historyLoading = false;
            }
        },
        
        searchUsers() {
            this.loadUsers();
        },
        
        async startImpersonation(user) {
            if (this.impersonating) return;
            
            this.impersonating = true;
            this.selectedUserId = user.id;
            
            try {
                const response = await fetch(`/impersonation/start/${user.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.href = data.redirect_url || '/hierarchy/dashboard';
                } else {
                    alert(data.message || 'Erro ao iniciar impersonação');
                }
            } catch (error) {
                console.error('Erro ao iniciar impersonação:', error);
                alert('Erro ao iniciar impersonação. Tente novamente.');
            } finally {
                this.impersonating = false;
                this.selectedUserId = null;
            }
        },
        
        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }
}
</script>
@endsection
