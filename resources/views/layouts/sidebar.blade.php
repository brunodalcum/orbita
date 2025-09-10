<style>
    .sidebar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 40;
    }
    
    .sidebar-link {
        transition: all 0.3s ease;
        position: relative;
    }
    
    .sidebar-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateX(5px);
    }
    
    .sidebar-link.active {
        background-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
</style>

<!-- Sidebar -->
<div class="sidebar w-64 flex-shrink-0">
    <div class="p-6">
        <!-- Logo -->
        <div class="flex items-center mb-8">
            <img src="{{ asset('images/dspay-logo.png') }}" alt="DS Pay Logo" class="h-8 w-auto">
            <span class="ml-3 text-white font-bold text-lg">DS Pay</span>
        </div>
        
        <!-- Navigation -->
        <nav class="space-y-2">
            <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </a>
            
            <a href="{{ route('dashboard.licenciados') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                <i class="fas fa-id-card mr-3"></i>
                Licenciados
            </a>
            
            <a href="{{ route('dashboard.operacoes') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                <i class="fas fa-cogs mr-3"></i>
                Operações
            </a>
            
            <a href="{{ route('dashboard.planos') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                <i class="fas fa-chart-line mr-3"></i>
                Planos
            </a>
            
            <a href="{{ route('dashboard.adquirentes') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                <i class="fas fa-building mr-3"></i>
                Adquirentes
            </a>
            
            <!-- Menu Agenda com Submenu -->
            <div class="relative" x-data="{ open: {{ request()->routeIs('dashboard.agenda*') || request()->routeIs('agenda.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="sidebar-link flex items-center justify-between w-full px-4 py-3 text-white rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt mr-3"></i>
                        Agenda
                    </div>
                    <i class="fas fa-chevron-down transform transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-2 ml-4 space-y-1">
                    <a href="{{ route('dashboard.agenda') }}" class="sidebar-link flex items-center px-4 py-2 text-white rounded-lg text-sm {{ request()->routeIs('dashboard.agenda') && !request()->routeIs('dashboard.agenda.calendar') && !request()->routeIs('dashboard.agenda.create') && !request()->routeIs('agenda.pendentes-aprovacao') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-list mr-3"></i>
                        Lista de Compromissos
                    </a>
                    <a href="{{ route('dashboard.agenda.calendar') }}" class="sidebar-link flex items-center px-4 py-2 text-white rounded-lg text-sm {{ request()->routeIs('dashboard.agenda.calendar') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-calendar mr-3"></i>
                        Calendário
                    </a>
                    <a href="{{ route('dashboard.agenda.create') }}" class="sidebar-link flex items-center px-4 py-2 text-white rounded-lg text-sm {{ request()->routeIs('dashboard.agenda.create') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-plus mr-3"></i>
                        Novo Compromisso
                    </a>
                    <a href="{{ route('agenda.pendentes-aprovacao') }}" class="sidebar-link flex items-center px-4 py-2 text-white rounded-lg text-sm {{ request()->routeIs('agenda.pendentes-aprovacao') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-clock mr-2"></i>
                        Aprovação de Compromissos
                        @php
                            $pendentesCount = 0;
                            if (Auth::check()) {
                                $pendentesCount = \App\Models\Agenda::pendentesAprovacao(Auth::id())->count();
                            }
                        @endphp
                        @if($pendentesCount > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                {{ $pendentesCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>
            
            <a href="{{ route('dashboard.leads') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                <i class="fas fa-user-plus mr-3"></i>
                Leads
            </a>
            
            <a href="{{ route('dashboard.marketing') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                <i class="fas fa-bullhorn mr-3"></i>
                Marketing
            </a>
            
            <a href="{{ route('dashboard.configuracoes') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                <i class="fas fa-cog mr-3"></i>
                Configurações
            </a>
        </nav>
        
        <!-- Logout -->
        <div class="mt-8 pt-8 border-t border-white border-opacity-20">
            <form method="POST" action="{{ route('logout.custom') }}">
                @csrf
                <button type="submit" class="w-full sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Sair
                </button>
            </form>
        </div>
    </div>
</div>
