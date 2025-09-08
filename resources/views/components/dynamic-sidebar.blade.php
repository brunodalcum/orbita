<!-- Sidebar Dinâmico -->
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
                        <i class="{{ $item['icon'] }} mr-3"></i>
                        <span class="font-medium">{{ $item['name'] }}</span>
                        @if(count($item['submenu']) > 0)
                            <i class="fas fa-chevron-down ml-auto text-sm transition-transform duration-200 chevron-icon" id="chevron-{{ $loop->index }}" style="color: white; opacity: 0.8;" title="Submenu ({{ count($item['submenu']) }} items)"></i>
                        @endif
                    </a>

                    <!-- Submenu -->
                    @if(count($item['submenu']) > 0)
                        <div class="submenu ml-4 mt-2 space-y-1 {{ $item['is_active'] ? '' : 'hidden' }}" id="submenu-{{ $loop->index }}">
                            @foreach($item['submenu'] as $subItem)
                                <a href="{{ route($subItem['route']) }}{{ isset($subItem['action']) ? '#' . $subItem['action'] : '' }}" 
                                   class="flex items-center px-3 py-2 text-white/80 rounded-lg text-sm hover:bg-white/10 transition-all duration-200">
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
    </div>

    <!-- User Profile -->
    <div class="absolute bottom-0 w-64 p-6">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-white"></i>
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
    overflow: hidden;
    transition: max-height 0.3s ease-in-out;
}

.submenu.hidden {
    max-height: 0;
}

.submenu:not(.hidden) {
    max-height: 500px;
}

.submenu.show {
    max-height: 500px;
}

.menu-item:hover .submenu {
    max-height: 500px;
}

.chevron-icon {
    display: inline-block !important;
    visibility: visible !important;
    opacity: 0.8 !important;
    color: white !important;
    font-size: 0.875rem !important;
}

.menu-item:hover .chevron-icon {
    transform: rotate(180deg);
    opacity: 1 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize active submenus
    document.querySelectorAll('.menu-item').forEach(function(item, index) {
        const submenu = item.querySelector('.submenu');
        const chevron = item.querySelector('[id^="chevron-"]');
        
        // Set initial chevron state for active submenus
        if (submenu && chevron && !submenu.classList.contains('hidden')) {
            chevron.style.transform = 'rotate(180deg)';
        }
    });
    
    // Toggle submenu on click
    document.querySelectorAll('.menu-item').forEach(function(item, index) {
        const mainLink = item.querySelector('a');
        const submenu = item.querySelector('.submenu');
        const chevron = item.querySelector('[id^="chevron-"]');
        
        if (submenu && chevron) {
            mainLink.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Close other submenus
                document.querySelectorAll('.submenu').forEach(function(otherSubmenu) {
                    if (otherSubmenu !== submenu) {
                        otherSubmenu.classList.add('hidden');
                        // Reset other chevrons
                        const otherChevron = otherSubmenu.closest('.menu-item').querySelector('[id^="chevron-"]');
                        if (otherChevron) {
                            otherChevron.style.transform = 'rotate(0deg)';
                        }
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
            document.querySelectorAll('[id^="chevron-"]').forEach(function(chevron) {
                chevron.style.transform = 'rotate(0deg)';
            });
        }
    });
});
</script>