@extends('layouts.app')

@section('title', 'Gerenciar Permissões')

@section('content')
<div class="min-h-screen bg-gray-50" x-data="permissionManager()">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">
                        Gerenciar Permissões: {{ $node->name ?? $node->display_name }}
                    </h1>
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {{ ucfirst(str_replace('_', ' ', $nodeType)) }}
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    <button @click="applyDefaults()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Aplicar Padrões
                    </button>
                    
                    <a href="{{ route('hierarchy.permissions.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações do nó -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Informações do Nó</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nome</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $node->name ?? $node->display_name }}</dd>
                    </div>
                    @if(isset($node->email))
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $node->email }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $nodeType)) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $node->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $node->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $node->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Permissões Ativas</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <span class="font-medium text-green-600">{{ $currentPermissions->where('granted', true)->count() }}</span>
                            de {{ $currentPermissions->count() }} configuradas
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulário de permissões -->
        <form @submit.prevent="savePermissions()">
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-gray-900">Configurar Permissões</h2>
                        <div class="flex items-center space-x-4">
                            <button type="button" @click="selectAll()" class="text-sm text-indigo-600 hover:text-indigo-800">
                                Selecionar Todas
                            </button>
                            <button type="button" @click="deselectAll()" class="text-sm text-gray-600 hover:text-gray-800">
                                Desmarcar Todas
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    @foreach($availablePermissions as $category => $permissions)
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <div class="w-6 h-6 bg-indigo-100 rounded-md flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($category === 'dashboard')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    @elseif($category === 'users')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 414 0zM7 10a2 2 0 11-4 0 2 2 0 414 0z"/>
                                    @elseif($category === 'reports')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    @endif
                                </svg>
                            </div>
                            {{ ucfirst($category) }}
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($permissions as $key => $label)
                            @php
                                $currentPermission = $currentPermissions->where('permission_key', $key)->first();
                                $isGranted = $currentPermission ? $currentPermission->granted : false;
                                $isDefault = in_array($key, $defaultPermissions);
                            @endphp
                            
                            <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer {{ $isDefault ? 'bg-blue-50 border-blue-200' : '' }}">
                                <input type="checkbox" 
                                       name="permissions[]" 
                                       value="{{ $key }}"
                                       x-model="selectedPermissions"
                                       {{ $isGranted ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mt-1">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium text-gray-900">{{ $label }}</span>
                                        @if($isDefault)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Padrão
                                        </span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $key }}</div>
                                    @if($currentPermission && $currentPermission->notes)
                                    <div class="text-xs text-gray-600 mt-1 italic">{{ $currentPermission->notes }}</div>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Botões de ação -->
            <div class="flex justify-end space-x-4 mb-8">
                <a href="{{ route('hierarchy.permissions.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancelar
                </a>
                <button type="submit" :disabled="saving" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg x-show="!saving" class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <svg x-show="saving" class="animate-spin -ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="saving ? 'Salvando...' : 'Salvar Permissões'">Salvar Permissões</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function permissionManager() {
    return {
        selectedPermissions: @json($currentPermissions->where('granted', true)->pluck('permission_key')->toArray()),
        saving: false,
        
        selectAll() {
            const allPermissions = [];
            document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
                allPermissions.push(checkbox.value);
                checkbox.checked = true;
            });
            this.selectedPermissions = allPermissions;
        },
        
        deselectAll() {
            document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
            this.selectedPermissions = [];
        },
        
        async savePermissions() {
            if (this.saving) return;
            
            this.saving = true;
            
            try {
                const response = await fetch(`{{ route('hierarchy.permissions.update', [$nodeType, $node->id]) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        permissions: this.selectedPermissions
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message, 'success');
                } else {
                    this.showNotification(data.message || 'Erro ao salvar permissões', 'error');
                }
            } catch (error) {
                console.error('Erro ao salvar permissões:', error);
                this.showNotification('Erro ao salvar permissões', 'error');
            } finally {
                this.saving = false;
            }
        },
        
        async applyDefaults() {
            if (!confirm('Tem certeza que deseja aplicar as permissões padrão? Isso irá sobrescrever as configurações atuais.')) {
                return;
            }
            
            try {
                const response = await fetch(`{{ route('hierarchy.permissions.apply-defaults', [$nodeType, $node->id]) }}`, {
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
                    this.showNotification(data.message || 'Erro ao aplicar permissões padrão', 'error');
                }
            } catch (error) {
                console.error('Erro ao aplicar permissões padrão:', error);
                this.showNotification('Erro ao aplicar permissões padrão', 'error');
            }
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
