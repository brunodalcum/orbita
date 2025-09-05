<?php

// Script para restaurar sidebar original
// Execute: php restore-sidebar-original.php

echo "🔧 Restaurando sidebar original...\n";

// 1. Verificar se estamos no diretório correto
if (!file_exists('artisan')) {
    echo "❌ Erro: Execute este script no diretório raiz do Laravel\n";
    exit(1);
}

// 2. Limpar cache
echo "\n🗂️ Limpando cache...\n";

$commands = [
    'php artisan view:clear',
    'php artisan config:clear',
    'php artisan route:clear',
    'php artisan cache:clear',
    'php artisan optimize:clear'
];

foreach ($commands as $command) {
    echo "\n🔄 Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

// 3. Remover arquivos do sidebar dinâmico
echo "\n🗑️ Removendo arquivos do sidebar dinâmico...\n";

$files = [
    'app/View/Components/DynamicSidebar.php',
    'resources/views/components/dynamic-sidebar.blade.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "✅ Removido: $file\n";
    } else {
        echo "❌ Não encontrado: $file\n";
    }
}

// 4. Restaurar sidebar estático
echo "\n🔧 Restaurando sidebar estático...\n";

$staticSidebarContent = '<div class="bg-gray-900 text-white w-64 min-h-screen p-4">
    <!-- Logo -->
    <div class="mb-8">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                <span class="text-white font-bold text-sm">DS</span>
            </div>
            <span class="text-xl font-bold">DSPay</span>
        </div>
    </div>

    <!-- User Info -->
    <div class="mb-6 p-3 bg-gray-800 rounded-lg">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
            </div>
            <div>
                <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400">Super Admin</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="space-y-2">
        <a href="{{ route(\'dashboard\') }}" 
           class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs(\'dashboard\') ? \'bg-blue-600 text-white\' : \'text-gray-300 hover:bg-gray-800 hover:text-white\' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            Dashboard
        </a>
        
        <a href="{{ route(\'dashboard.operacoes\') }}" 
           class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs(\'dashboard.operacoes\') ? \'bg-blue-600 text-white\' : \'text-gray-300 hover:bg-gray-800 hover:text-white\' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Operações
        </a>
        
        <a href="{{ route(\'dashboard.licenciados\') }}" 
           class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs(\'dashboard.licenciados\') ? \'bg-blue-600 text-white\' : \'text-gray-300 hover:bg-gray-800 hover:text-white\' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Licenciados
        </a>
        
        <a href="{{ route(\'dashboard.planos\') }}" 
           class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs(\'dashboard.planos\') ? \'bg-blue-600 text-white\' : \'text-gray-300 hover:bg-gray-800 hover:text-white\' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Planos
        </a>
        
        <a href="{{ route(\'dashboard.adquirentes\') }}" 
           class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs(\'dashboard.adquirentes\') ? \'bg-blue-600 text-white\' : \'text-gray-300 hover:bg-gray-800 hover:text-white\' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            Adquirentes
        </a>
        
        <a href="{{ route(\'dashboard.agenda\') }}" 
           class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs(\'dashboard.agenda\') ? \'bg-blue-600 text-white\' : \'text-gray-300 hover:bg-gray-800 hover:text-white\' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Agenda
        </a>
        
        <a href="{{ route(\'dashboard.leads\') }}" 
           class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs(\'dashboard.leads\') ? \'bg-blue-600 text-white\' : \'text-gray-300 hover:bg-gray-800 hover:text-white\' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            Leads
        </a>
        
        <a href="{{ route(\'dashboard.marketing\') }}" 
           class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs(\'dashboard.marketing\') ? \'bg-blue-600 text-white\' : \'text-gray-300 hover:bg-gray-800 hover:text-white\' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
            </svg>
            Marketing
        </a>
        
        <a href="{{ route(\'dashboard.users.index\') }}" 
           class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs(\'dashboard.users\') ? \'bg-blue-600 text-white\' : \'text-gray-300 hover:bg-gray-800 hover:text-white\' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            Usuários
        </a>
        
        <a href="{{ route(\'dashboard.configuracoes\') }}" 
           class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs(\'dashboard.configuracoes\') ? \'bg-blue-600 text-white\' : \'text-gray-300 hover:bg-gray-800 hover:text-white\' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Configurações
        </a>
    </nav>

    <!-- Logout -->
    <div class="mt-8 pt-4 border-t border-gray-700">
        <form method="POST" action="{{ route(\'logout\') }}">
            @csrf
            <button type="submit" 
                    class="flex items-center w-full px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white transition-colors duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Sair
            </button>
        </form>
    </div>
</div>';

// Salvar sidebar estático
$staticSidebarFile = 'resources/views/components/static-sidebar.blade.php';
file_put_contents($staticSidebarFile, $staticSidebarContent);
echo "✅ Sidebar estático criado: $staticSidebarFile\n";

// 5. Atualizar views para usar sidebar estático
echo "\n🔧 Atualizando views para usar sidebar estático...\n";

$views = [
    'resources/views/dashboard.blade.php',
    'resources/views/dashboard/licenciados.blade.php',
    'resources/views/dashboard/users/index.blade.php'
];

foreach ($views as $view) {
    if (file_exists($view)) {
        $content = file_get_contents($view);
        
        // Substituir dynamic-sidebar por static-sidebar
        $newContent = str_replace('<x-dynamic-sidebar />', '<x-static-sidebar />', $content);
        
        if ($newContent !== $content) {
            file_put_contents($view, $newContent);
            echo "✅ Atualizado: $view\n";
        } else {
            echo "⚠️ Não alterado: $view\n";
        }
    } else {
        echo "❌ Não encontrado: $view\n";
    }
}

// 6. Recriar cache
echo "\n🗂️ Recriando cache...\n";

$cacheCommands = [
    'php artisan config:cache',
    'php artisan route:cache'
];

foreach ($cacheCommands as $command) {
    echo "\n🔄 Executando: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo "Resultado: $output\n";
}

echo "\n🎉 Sidebar restaurado com sucesso!\n";
echo "✅ Teste as rotas em: https://srv971263.hstgr.cloud/dashboard\n";
