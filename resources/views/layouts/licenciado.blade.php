<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DSPay') }} - Dashboard Licenciado</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- CSS Global de Branding Dinâmico -->
    <!-- CSS Dinâmico de Branding -->
    <!-- CSS SELETIVO DE BRANDING - PRESERVA MENUS -->
    <!-- CSS ESPECÍFICO PARA SIDEBAR - FORÇA TEXTOS BRANCOS -->
    <link href="{{ asset('css/sidebar-fix.css') }}" rel="stylesheet">
    <!-- CSS ABRANGENTE - BRANDING EM TODAS AS PÁGINAS -->
    <!-- CSS ESPECÍFICO PARA ELEMENTOS TEIMOSOS -->
    <link href="{{ asset('css/specific-elements-fix.css') }}" rel="stylesheet">
    <!-- CSS Mínimo para Funcionalidade Básica -->
    <link href="{{ asset('css/minimal-functionality.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="desktop-sidebar">
            <x-licenciado-sidebar />
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <button class="mobile-menu-btn p-2 text-gray-400 hover:text-gray-600 mr-4">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">@yield('title', 'Dashboard')</h1>
                            <p class="text-gray-600">@yield('subtitle', 'Área do Licenciado')</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="p-2 text-gray-400 hover:text-gray-600 relative">
                                <i class="fas fa-bell"></i>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                            </button>
                        </div>
                        
                        <!-- Quick Actions -->
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-cog"></i>
                        </button>
                        
                        <!-- User Menu -->
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <span class="text-gray-700 font-medium hidden md:block">{{ Auth::user()->name }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="mobile-sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden md:hidden">
        <div class="mobile-sidebar">
            <x-licenciado-sidebar />
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const mobileOverlay = document.getElementById('mobile-sidebar-overlay');
            const mobileSidebar = document.querySelector('.mobile-sidebar aside');

            if (mobileMenuBtn && mobileOverlay) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileOverlay.classList.remove('hidden');
                    setTimeout(() => {
                        mobileSidebar?.classList.add('mobile-open');
                    }, 10);
                });

                mobileOverlay.addEventListener('click', function(e) {
                    if (e.target === mobileOverlay) {
                        mobileSidebar?.classList.remove('mobile-open');
                        setTimeout(() => {
                            mobileOverlay.classList.add('hidden');
                        }, 300);
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
