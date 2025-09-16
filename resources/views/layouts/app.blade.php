<x-dynamic-branding />
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>
        
        @php
            $user = Auth::user();
            $faviconUrl = 'images/dspay-logo.png'; // padrão
            
            if ($user) {
                // Todos os usuários podem ter favicon personalizado
                $branding = $user->getBrandingWithInheritance();
                if (!empty($branding['favicon_url'])) {
                    $faviconUrl = 'storage/' . $branding['favicon_url'];
                } elseif ($user->isSuperAdminNode()) {
                    // Super Admin usa favicon da Órbita como fallback
                    $faviconUrl = 'storage/branding/orbita/orbita-favicon.svg';
                }
            }
        @endphp
        
        <link rel="icon" type="image/svg+xml" href="{{ asset($faviconUrl) }}?v={{ time() }}">
        <link rel="shortcut icon" type="image/svg+xml" href="{{ asset($faviconUrl) }}?v={{ time() }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUa6c4iuQ+jJkbHveb9QUL9+3j9lLLqXnfFBNvT4Hbb4nzjvQLL0Iz7WK1C4" crossorigin="anonymous">

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
        
        <!-- Branding Dinâmico -->

        <!-- Styles -->
        @livewireStyles
        @stack('styles')
        <!-- CSS Global de Branding Dinâmico -->
    <link href="{{ asset('css/global-branding.css') }}" rel="stylesheet">
</head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            <!-- Barra de impersonação -->
            @include('components.impersonation-bar')
            
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
        
        @stack('scripts')
    </body>
</html>
