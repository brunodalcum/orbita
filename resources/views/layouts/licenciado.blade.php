<x-dynamic-branding />
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
    
    <style>
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 16px;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }
        
        .stat-card.green {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .stat-card.blue {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stat-card.orange {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .stat-card.purple {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .activity-item {
            border-left: 3px solid #e5e7eb;
            padding-left: 16px;
            position: relative;
        }
        
        .activity-item::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 8px;
            width: 10px;
            height: 10px;
            background: #6b7280;
            border-radius: 50%;
        }
        
        .activity-item.green::before {
            background: #10b981;
        }
        
        .activity-item.blue::before {
            background: #3b82f6;
        }
        
        .activity-item.yellow::before {
            background: #f59e0b;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .table-responsive {
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .table {
            width: 100%;
            background: white;
        }
        
        .table th {
            background: #f8fafc;
            padding: 16px;
            text-align: left;
            font-weight: 600;
            color: var(--secondary-color);
            border-bottom: 1px solid #e5e7eb;
        }
        
        .table td {
            padding: 16px;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .table tbody tr:hover {
            background: #f9fafb;
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-badge.ativo {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-badge.inativo {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .status-badge.pendente {
            background: #fef3c7;
            color: #92400e;
        }
        
        .mobile-menu-btn {
            display: none;
        }
        
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
            
            .desktop-sidebar {
                display: none;
            }
        }
    </style>
    
    <!-- CSS Global de Branding Dinâmico -->
    <link href="{{ asset('css/global-branding.css') }}" rel="stylesheet">
    <!-- CSS Dinâmico de Branding -->
    <link href="{{ asset('css/dynamic-branding.css.php') }}" rel="stylesheet" type="text/css">
    <!-- CSS SELETIVO DE BRANDING - PRESERVA MENUS -->
    <link href="{{ asset('css/selective-branding.css') }}" rel="stylesheet">
    <!-- CSS ESPECÍFICO PARA SIDEBAR - FORÇA TEXTOS BRANCOS -->
    <link href="{{ asset('css/sidebar-fix.css') }}" rel="stylesheet">
    <!-- CSS ABRANGENTE - BRANDING EM TODAS AS PÁGINAS -->
    <link href="{{ asset('css/comprehensive-branding.css') }}" rel="stylesheet">
    <!-- CSS ESPECÍFICO PARA ELEMENTOS TEIMOSOS -->
    <link href="{{ asset('css/specific-elements-fix.css') }}" rel="stylesheet">
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
