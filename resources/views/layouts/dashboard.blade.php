<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <!-- Scripts com fallback -->
        @if (file_exists(public_path('build/manifest.json')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <!-- Fallback CDN para produção -->
            <script src="https://cdn.tailwindcss.com"></script>
            <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
            <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
            <style>
                body { font-family: 'Figtree', sans-serif; }
            </style>
        @endif

        <!-- Styles -->
        @livewireStyles
        <x-dynamic-branding />
</head>
    <body class="bg-gray-50">
        <x-banner />
        
        <div class="flex h-screen">
            <!-- Sidebar Dinâmico -->
            <x-dynamic-sidebar />
            
            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden ml-64">
                <!-- Header -->
                <header class="bg-white shadow-sm border-b">
                    <div class="flex items-center justify-between px-6 py-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">@yield('title', 'Dashboard')</h1>
                            <p class="text-gray-600">@yield('description', 'Painel de controle')</p>
                        </div>
                        
                        <!-- User Menu -->
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'Usuário' }}</span>
                            </div>
                            
                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout.custom') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-1"></i>
                                    Sair
                                </button>
                            </form>
                        </div>
                    </div>
                </header>
                
                <!-- Page Content -->
                <main class="flex-1 overflow-auto p-6">
                    @yield('content')
                </main>
            </div>
        </div>

        @stack('modals')
        @livewireScripts
    </body>
</html>





