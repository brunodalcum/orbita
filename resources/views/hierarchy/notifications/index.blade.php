@extends('layouts.app')

@section('title', 'Notificações')

@section('content')
<x-dynamic-branding />
<div class="min-h-screen bg-gray-50" x-data="notificationsManager()">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Notificações</h1>
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ ucfirst(str_replace('_', ' ', $user->node_type)) }}
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    @if($user->isSuperAdminNode() || $user->isOperacaoNode() || $user->isWhiteLabelNode())
                    <a href="{{ route('hierarchy.notifications.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nova Notificação
                    </a>
                    @endif
                    
                    <a href="{{ route('hierarchy.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-500">todas as notificações</span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Não Lidas</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['unread']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-500">requerem atenção</span>
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
                                <dt class="text-sm font-medium text-gray-500 truncate">Info</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['by_type']['info']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-500">informativas</span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Requer Ação</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['requires_action']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-500">precisam de resposta</span>
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
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-700">Status:</label>
                        <select x-model="selectedFilter" @change="applyFilters()" class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="all">Todas</option>
                            <option value="unread">Não Lidas</option>
                            <option value="read">Lidas</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-700">Categoria:</label>
                        <select x-model="selectedCategory" @change="applyFilters()" class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="all">Todas</option>
                            <option value="system">Sistema</option>
                            <option value="user_action">Ação de Usuário</option>
                            <option value="hierarchy_change">Mudança na Hierarquia</option>
                            <option value="permission_change">Mudança de Permissão</option>
                        </select>
                    </div>
                    
                    <button @click="markAllAsRead()" class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Marcar Todas como Lidas
                    </button>
                </div>
            </div>
        </div>

        <!-- Lista de notificações -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900">Suas Notificações</h2>
                    <div class="text-sm text-gray-500">
                        {{ $notifications->total() }} notificações
                    </div>
                </div>
            </div>
            
            <div class="divide-y divide-gray-200">
                @if($notifications->count() > 0)
                @foreach($notifications as $notification)
                <div class="p-6 hover:bg-gray-50 transition-colors duration-200 {{ $notification->isRead() ? 'opacity-75' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-{{ $notification->type_color }}-100">
                                    <svg class="w-5 h-5 text-{{ $notification->type_color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($notification->type === 'info')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        @elseif($notification->type === 'success')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        @elseif($notification->type === 'warning')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        @elseif($notification->type === 'error')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"/>
                                        @endif
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <h3 class="text-sm font-medium text-gray-900">{{ $notification->title }}</h3>
                                    @if(!$notification->isRead())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Nova
                                    </span>
                                    @endif
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $notification->type_color }}-100 text-{{ $notification->type_color }}-800">
                                        {{ ucfirst($notification->type) }}
                                    </span>
                                    @if($notification->requires_action)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Requer Ação
                                    </span>
                                    @endif
                                </div>
                                <p class="mt-1 text-sm text-gray-600">{{ $notification->message }}</p>
                                <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                    <span>{{ $notification->created_at->diffForHumans() }}</span>
                                    @if($notification->sender_name)
                                    <span>Por: {{ $notification->sender_name }}</span>
                                    @endif
                                    <span>{{ ucfirst(str_replace('_', ' ', $notification->category)) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            @if($notification->action_url && $notification->action_text)
                            <a href="{{ $notification->action_url }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ $notification->action_text }}
                            </a>
                            @endif
                            
                            @if(!$notification->isRead())
                            <button @click="markAsRead({{ $notification->id }})" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                            @endif
                            
                            <button @click="deleteNotification({{ $notification->id }})" class="text-gray-400 hover:text-red-600 focus:outline-none">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma notificação</h3>
                    <p class="mt-1 text-sm text-gray-500">Você não tem notificações no momento.</p>
                </div>
                @endif
            </div>
            
            <!-- Paginação -->
            @if($notifications->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $notifications->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function notificationsManager() {
    return {
        selectedFilter: '{{ $filter }}',
        selectedCategory: '{{ $category }}',
        
        applyFilters() {
            const params = new URLSearchParams();
            if (this.selectedFilter !== 'all') params.append('filter', this.selectedFilter);
            if (this.selectedCategory !== 'all') params.append('category', this.selectedCategory);
            
            window.location.href = `{{ route('hierarchy.notifications.index') }}?${params.toString()}`;
        },
        
        async markAsRead(notificationId) {
            try {
                const response = await fetch(`/hierarchy/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message || 'Erro ao marcar como lida', 'error');
                }
            } catch (error) {
                console.error('Erro ao marcar notificação como lida:', error);
                showNotification('Erro ao marcar como lida', 'error');
            }
        },
        
        async markAllAsRead() {
            try {
                const response = await fetch('/hierarchy/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message || 'Erro ao marcar todas como lidas', 'error');
                }
            } catch (error) {
                console.error('Erro ao marcar todas como lidas:', error);
                showNotification('Erro ao marcar todas como lidas', 'error');
            }
        },
        
        async deleteNotification(notificationId) {
            if (!confirm('Tem certeza que deseja excluir esta notificação?')) {
                return;
            }
            
            try {
                const response = await fetch(`/hierarchy/notifications/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message || 'Erro ao excluir notificação', 'error');
                }
            } catch (error) {
                console.error('Erro ao excluir notificação:', error);
                showNotification('Erro ao excluir notificação', 'error');
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
