<!-- Sidebar Dinâmico - Versão Produção (sem dependências externas) -->
<div class="w-64 bg-gradient-to-b from-blue-500 to-purple-600 flex-shrink-0 relative">
    <div class="p-6">
        <!-- Logo -->
        <div class="flex items-center mb-8">
            <img src="{{ asset('images/dspay-logo.png') }}" 
                 alt="dspay" 
                 class="h-10 w-auto mx-auto"
                 onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiByeD0iOCIgZmlsbD0iIzMzNjZFRiIvPgo8dGV4dCB4PSIyMCIgeT0iMjYiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZm9udC13ZWlnaHQ9ImJvbGQiIGZpbGw9IndoaXRlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5EU1BBWTwvdGV4dD4KPC9zdmc+';">
        </div>

        <!-- Menu Dinâmico -->
        <nav class="space-y-2">
            @foreach($menuItems as $item)
                <div class="menu-item">
                    <!-- Item Principal -->
                    <a href="{{ route($item['route']) }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 {{ $item['is_active'] ? 'bg-white/20 border-l-4 border-white' : 'hover:bg-white/10' }}">
                        <!-- Ícones SVG inline -->
                        @if($item['icon'] === 'fas fa-tachometer-alt')
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        @elseif($item['icon'] === 'fas fa-users')
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                        @elseif($item['icon'] === 'fas fa-cogs')
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                            </svg>
                        @elseif($item['icon'] === 'fas fa-chart-line')
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        @elseif($item['icon'] === 'fas fa-calendar-alt')
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                        @elseif($item['icon'] === 'fas fa-bullhorn')
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.369 4.369 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z" clip-rule="evenodd"/>
                            </svg>
                        @elseif($item['icon'] === 'fas fa-users-cog')
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                            </svg>
                        @else
                            <!-- Ícone padrão -->
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                        <span class="font-medium">{{ $item['name'] }}</span>
                        @if(count($item['submenu']) > 0)
                            <svg class="w-4 h-4 ml-auto transition-transform duration-200 chevron-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                    </a>

                    <!-- Submenu -->
                    @if(count($item['submenu']) > 0)
                        <div class="submenu ml-4 mt-2 space-y-1">
                            @foreach($item['submenu'] as $subItem)
                                <a href="{{ route($subItem['route']) }}{{ isset($subItem['action']) ? '#' . $subItem['action'] : '' }}" 
                                   class="flex items-center px-3 py-2 text-white/80 rounded-lg text-sm hover:bg-white/10 transition-all duration-200">
                                    <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <circle cx="10" cy="10" r="2"/>
                                    </svg>
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
    </div>

    <!-- User Profile -->
    <div class="absolute bottom-0 w-64 p-6">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-white font-medium">{{ $user->name ?? 'Usuário' }}</p>
                <p class="text-white/70 text-sm">{{ $user->role->display_name ?? 'Usuário' }}</p>
            </div>
        </div>
    </div>
</div>

<style>
.menu-item {
    position: relative;
}

.submenu {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-in-out;
}

.submenu.show {
    max-height: 500px;
}

.menu-item:hover .submenu {
    max-height: 500px;
}

.menu-item:hover .chevron-icon {
    transform: rotate(180deg);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    document.querySelectorAll('.menu-item').forEach(function(item, index) {
        const mainLink = item.querySelector('a');
        const submenu = item.querySelector('.submenu');
        const chevron = item.querySelector('.chevron-icon');
        
        if (submenu && chevron) {
            mainLink.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Close other submenus
                document.querySelectorAll('.submenu').forEach(function(otherSubmenu) {
                    if (otherSubmenu !== submenu) {
                        otherSubmenu.classList.add('hidden');
                    }
                });
                
                // Toggle current submenu
                submenu.classList.toggle('hidden');
                
                // Rotate chevron
                chevron.style.transform = submenu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
            });
        }
    });
    
    // Close submenu when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.menu-item')) {
            document.querySelectorAll('.submenu').forEach(function(submenu) {
                submenu.classList.add('hidden');
            });
            document.querySelectorAll('.chevron-icon').forEach(function(chevron) {
                chevron.style.transform = 'rotate(0deg)';
            });
        }
    });
});
</script>
