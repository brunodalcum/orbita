<!-- Sidebar Dinâmico -->
@php
    $user = Auth::user();
    $branding = $user ? $user->getBrandingWithInheritance() : [];
    $logoUrl = $branding['logo_small_url'] ?? $branding['logo_url'] ?? null;
    
    // Para Super Admin, usar logo da Órbita como fallback se não houver personalizada
    if($user && $user->isSuperAdminNode() && !$logoUrl) {
        $logoUrl = 'branding/orbita/orbita-logo-small.svg';
    }
    
    // Determinar tipo de nó para classes CSS específicas
    $nodeTypeClass = '';
    $badgeClass = '';
    if ($user) {
        if ($user->isSuperAdminNode()) {
            $nodeTypeClass = 'super-admin-sidebar';
            $badgeClass = 'super-admin-badge';
        } elseif ($user->node_type === 'operacao') {
            $nodeTypeClass = 'operacao-sidebar';
            $badgeClass = 'operacao-badge';
        } elseif ($user->node_type === 'white_label') {
            $nodeTypeClass = 'white-label-sidebar';
            $badgeClass = 'white-label-badge';
        }
    }
    
    // Usar cores CSS dinâmicas
    $textColor = 'white'; // Padrão para sidebar com gradiente
@endphp

<div class="w-64 flex-shrink-0 relative brand-sidebar {{ $nodeTypeClass }}">
    <div class="p-6">
        <!-- Logo -->
        <div class="flex items-center justify-center mb-8 w-full">
            <div class="logo-container sidebar-logo-container">
                @if($logoUrl)
                    <img src="{{ asset('storage/' . $logoUrl) }}?v={{ time() }}" 
                         alt="Logo" 
                         class="sidebar-logo"
                         onerror="this.onerror=null;this.src='{{ asset('storage/branding/orbita/orbita-logo-small.svg') }}';">
                @else
                    <img src="{{ asset('images/dspay-logo.png') }}" 
                         alt="dspay" 
                         class="sidebar-logo"
                         onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiByeD0iOCIgZmlsbD0iIzMzNjZFRiIvPgo8dGV4dCB4PSIyMCIgeT0iMjYiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZm9udC13ZWlnaHQ9ImJvbGQiIGZpbGw9IndoaXRlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5EU1BBWTwvdGV4dD4KPC9zdmc+';">
                @endif
            </div>
        </div>

        <!-- Badge do Tipo de Nó -->
        @if($user)
            <div class="flex justify-center mb-6">
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                    @if($user->isSuperAdminNode())
                        Super Admin
                    @elseif($user->node_type === 'operacao')
                        Operação
                    @elseif($user->node_type === 'white_label')
                        White Label
                    @else
                        {{ ucfirst($user->node_type) }}
                    @endif
                </span>
            </div>
        @endif

        <!-- Menu Dinâmico -->
        <nav class="space-y-2">
            @foreach($menuItems as $item)
                <div class="menu-item">
                    <!-- Item Principal -->
                    <a href="{{ route($item['route']) }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 sidebar-menu-item {{ $item['is_active'] ? 'sidebar-menu-active' : 'sidebar-menu-hover' }}"
                       style="color: {{ $textColor }};">
                        <i class="{{ $item['icon'] }} mr-3"></i>
                        <span class="font-medium">{{ $item['name'] }}</span>
                        @if(count($item['submenu']) > 0)
                            <i class="fas fa-chevron-down ml-auto text-sm transition-transform duration-200 chevron-icon" 
                               id="chevron-{{ $loop->index }}" 
                               style="color: {{ $textColor }}; opacity: 0.8;" 
                               title="Submenu ({{ count($item['submenu']) }} items)"></i>
                        @endif
                    </a>

                    <!-- Submenu -->
                    @if(count($item['submenu']) > 0)
                        <div class="submenu ml-4 mt-2 space-y-1 {{ $item['is_active'] ? '' : 'hidden' }}" id="submenu-{{ $loop->index }}">
                            @foreach($item['submenu'] as $subItem)
                                <a href="{{ $subItem['route'] === 'dashboard.agenda' && isset($subItem['action']) && $subItem['action'] === 'create' ? route('dashboard.agenda.create') : route($subItem['route']) . (isset($subItem['action']) ? '#' . $subItem['action'] : '') }}" 
                                   class="flex items-center px-3 py-2 rounded-lg text-sm sidebar-submenu-item transition-all duration-200"
                                   style="color: {{ $textColor }}; opacity: 0.8;">
                                    <i class="fas fa-circle mr-2 text-xs"></i>
                                    {{ $subItem['name'] }}
                                    @if(isset($subItem['action']))
                                        <span class="ml-auto text-xs bg-white/20 px-2 py-1 rounded">{{ ucfirst($subItem['action']) }}</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </nav>

        <!-- Informações do Usuário -->
        @if($user)
            <div class="mt-8 pt-6 border-t border-white/20">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">
                            {{ $user->name }}
                        </p>
                        <p class="text-xs text-white/70 truncate">
                            {{ $user->email }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>



<script>
// JavaScript para controle do menu
document.addEventListener('DOMContentLoaded', function() {
    // Toggle submenu
    document.querySelectorAll('.menu-item > a').forEach(function(menuItem) {
        menuItem.addEventListener('click', function(e) {
            const submenu = this.parentElement.querySelector('.submenu');
            const chevron = this.querySelector('.chevron-icon');
            
            if (submenu) {
                e.preventDefault();
                submenu.classList.toggle('hidden');
                if (chevron) {
                    chevron.style.transform = submenu.classList.contains('hidden') ? 
                        'rotate(0deg)' : 'rotate(180deg)';
                }
            }
        });
    });
});
</script>