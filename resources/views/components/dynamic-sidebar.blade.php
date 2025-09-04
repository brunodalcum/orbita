<!-- Sidebar Dinâmico -->
<div class="w-64 bg-gradient-to-b from-blue-500 to-purple-600 flex-shrink-0 relative">
    <div class="p-6">
        <!-- Logo -->
        <div class="flex items-center mb-8">
            <img src="{{ asset('images/dspay-logo.png') }}" alt="dspay" class="h-10 w-auto mx-auto">
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
                            <i class="fas fa-chevron-down ml-auto text-xs transition-transform duration-200 chevron-icon" id="chevron-{{ $loop->index }}"></i>
                        @endif
                    </a>

                    <!-- Submenu -->
                    @if(count($item['submenu']) > 0)
                        <div class="submenu ml-4 mt-2 space-y-1 hidden" id="submenu-{{ $loop->index }}">
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