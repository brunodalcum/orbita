@if($isImpersonating ?? false)
<div class="bg-orange-500 text-white shadow-lg border-b-2 border-orange-600" x-data="impersonationBar()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-12">
            <!-- Informações da impersonação -->
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-orange-200 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <span class="text-sm font-medium">
                        Você está operando como: 
                        <strong>{{ $impersonationData['target_user_name'] ?? 'Usuário' }}</strong>
                    </span>
                </div>
                
                <div class="hidden sm:flex items-center text-xs text-orange-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-text="formatDuration()">
                        {{ \Carbon\Carbon::parse($impersonationData['started_at'])->diffForHumans() }}
                    </span>
                </div>
            </div>

            <!-- Ações -->
            <div class="flex items-center space-x-3">
                <!-- Usuário original -->
                <div class="hidden md:flex items-center text-sm text-orange-200">
                    <span>Usuário original: </span>
                    <strong class="ml-1">{{ $impersonationData['original_user_name'] ?? 'Admin' }}</strong>
                </div>

                <!-- Botão para voltar -->
                <button 
                    @click="stopImpersonation()"
                    :disabled="loading"
                    class="inline-flex items-center px-3 py-1 border border-orange-300 text-sm font-medium rounded-md text-orange-100 bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                    
                    <svg x-show="!loading" class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    
                    <svg x-show="loading" class="animate-spin w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    
                    <span x-text="loading ? 'Saindo...' : 'Voltar ao meu usuário'">Voltar ao meu usuário</span>
                </button>

                <!-- Botão de informações -->
                <button 
                    @click="showInfo = !showInfo"
                    class="p-1 text-orange-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 rounded">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Painel de informações expandido -->
        <div x-show="showInfo" x-transition class="pb-3 pt-1 border-t border-orange-400">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-orange-200">Iniciado em:</span>
                    <div class="font-medium">
                        {{ \Carbon\Carbon::parse($impersonationData['started_at'])->format('d/m/Y H:i:s') }}
                    </div>
                </div>
                <div>
                    <span class="text-orange-200">Duração:</span>
                    <div class="font-medium" x-text="formatDuration()">
                        {{ \Carbon\Carbon::parse($impersonationData['started_at'])->diffForHumans() }}
                    </div>
                </div>
                <div>
                    <span class="text-orange-200">Sessão expira em:</span>
                    <div class="font-medium" x-text="formatTimeToExpiry()">
                        {{ \Carbon\Carbon::parse($impersonationData['started_at'])->addHours(4)->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function impersonationBar() {
    return {
        loading: false,
        showInfo: false,
        startedAt: '{{ $impersonationData["started_at"] ?? "" }}',
        
        init() {
            // Atualizar duração a cada minuto
            setInterval(() => {
                this.$nextTick(() => {
                    // Força atualização dos elementos x-text
                });
            }, 60000);
        },
        
        formatDuration() {
            if (!this.startedAt) return '';
            
            const start = new Date(this.startedAt);
            const now = new Date();
            const diffMs = now - start;
            const diffMins = Math.floor(diffMs / 60000);
            
            if (diffMins < 60) {
                return `${diffMins} minuto${diffMins !== 1 ? 's' : ''}`;
            }
            
            const hours = Math.floor(diffMins / 60);
            const mins = diffMins % 60;
            
            return `${hours}h ${mins}min`;
        },
        
        formatTimeToExpiry() {
            if (!this.startedAt) return '';
            
            const start = new Date(this.startedAt);
            const expiry = new Date(start.getTime() + (4 * 60 * 60 * 1000)); // +4 horas
            const now = new Date();
            const diffMs = expiry - now;
            
            if (diffMs <= 0) return 'Expirado';
            
            const diffMins = Math.floor(diffMs / 60000);
            
            if (diffMins < 60) {
                return `${diffMins} minuto${diffMins !== 1 ? 's' : ''}`;
            }
            
            const hours = Math.floor(diffMins / 60);
            const mins = diffMins % 60;
            
            return `${hours}h ${mins}min`;
        },
        
        async stopImpersonation() {
            if (this.loading) return;
            
            this.loading = true;
            
            try {
                const response = await fetch('/impersonation/stop', {
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
                    alert(data.message || 'Erro ao terminar impersonação');
                }
            } catch (error) {
                console.error('Erro ao terminar impersonação:', error);
                alert('Erro ao terminar impersonação. Tente novamente.');
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endif
