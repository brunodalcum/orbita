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
                <i class="fas fa-users mr-3"></i>
                Licenciados
            </a>
            
            <a href="{{ route('dashboard.operacoes') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                <i class="fas fa-handshake mr-3"></i>
                Operações
            </a>
            
            <a href="{{ route('dashboard.planos') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                <i class="fas fa-credit-card mr-3"></i>
                Planos
            </a>
            
            <a href="{{ route('dashboard.adquirentes') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                <i class="fas fa-building mr-3"></i>
                Adquirentes
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
