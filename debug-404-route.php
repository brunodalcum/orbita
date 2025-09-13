<?php

// Script para diagnosticar erro 404 na rota de inserção de leads

echo "🔍 DIAGNÓSTICO DE ROTA 404 - INSERT LEADS\n";
echo "=========================================\n\n";

// Verificar se estamos no contexto correto do Laravel
if (!function_exists('route')) {
    echo "❌ ERRO: Execute este script via 'php artisan tinker'\n";
    echo "Comando: php artisan tinker --execute=\"require 'debug-404-route.php';\"\n";
    exit(1);
}

try {
    echo "📋 VERIFICAÇÕES DE ROTA:\n";
    echo "========================\n";
    
    // 1. Verificar se a rota existe
    echo "1️⃣ Testando rota 'dashboard.places.extraction.insert-leads':\n";
    try {
        $routeUrl = route('dashboard.places.extraction.insert-leads', ['id' => 1]);
        echo "✅ Rota encontrada: {$routeUrl}\n";
    } catch (Exception $e) {
        echo "❌ Rota NÃO encontrada: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
    
    // 2. Verificar outras rotas relacionadas
    echo "2️⃣ Testando outras rotas do PlaceExtractionController:\n";
    
    $routesToTest = [
        'dashboard.places.extract' => 'Página principal',
        'dashboard.places.extract.run' => 'Executar extração',
        'dashboard.places.extraction.status' => 'Status da extração',
        'dashboard.places.extraction.details' => 'Detalhes da extração'
    ];
    
    foreach ($routesToTest as $routeName => $description) {
        try {
            if (strpos($routeName, '{id}') !== false || strpos($routeName, 'status') !== false || strpos($routeName, 'details') !== false) {
                $url = route($routeName, ['id' => 1]);
            } else {
                $url = route($routeName);
            }
            echo "✅ {$description}: {$url}\n";
        } catch (Exception $e) {
            echo "❌ {$description}: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n";
    
    // 3. Listar todas as rotas que contêm 'places'
    echo "3️⃣ Rotas disponíveis com 'places':\n";
    $routes = Route::getRoutes();
    $placesRoutes = [];
    
    foreach ($routes as $route) {
        $uri = $route->uri();
        if (strpos($uri, 'places') !== false) {
            $name = $route->getName() ?: 'sem nome';
            $methods = implode('|', $route->methods());
            $placesRoutes[] = "{$methods} /{$uri} -> {$name}";
        }
    }
    
    if (empty($placesRoutes)) {
        echo "❌ Nenhuma rota com 'places' encontrada\n";
    } else {
        foreach ($placesRoutes as $routeInfo) {
            echo "📍 {$routeInfo}\n";
        }
    }
    
    echo "\n";
    
    // 4. Verificar se o controller existe
    echo "4️⃣ Verificando controller:\n";
    if (class_exists('App\\Http\\Controllers\\PlaceExtractionController')) {
        echo "✅ PlaceExtractionController existe\n";
        
        // Verificar se o método insertLeads existe
        if (method_exists('App\\Http\\Controllers\\PlaceExtractionController', 'insertLeads')) {
            echo "✅ Método insertLeads existe\n";
        } else {
            echo "❌ Método insertLeads NÃO existe\n";
        }
    } else {
        echo "❌ PlaceExtractionController NÃO existe\n";
    }
    
    echo "\n";
    
    // 5. Gerar URL de teste
    echo "5️⃣ URLs de teste:\n";
    $baseUrl = config('app.url') ?: 'https://srv971263.hstgr.cloud';
    echo "Base URL: {$baseUrl}\n";
    echo "URL de teste: {$baseUrl}/places/extraction/1/insert-leads\n";
    
    echo "\n";
    echo "🎯 RESUMO:\n";
    echo "==========\n";
    
    // Verificar cache
    if (app()->routesAreCached()) {
        echo "⚠️ ATENÇÃO: Rotas estão em cache\n";
        echo "Execute: php artisan route:clear\n";
    } else {
        echo "✅ Rotas não estão em cache\n";
    }
    
    echo "\n";
    echo "📞 PRÓXIMOS PASSOS:\n";
    echo "===================\n";
    echo "1. Se a rota não foi encontrada, verifique routes/web.php\n";
    echo "2. Se o controller não existe, verifique app/Http/Controllers/\n";
    echo "3. Limpe o cache: php artisan route:clear\n";
    echo "4. Recompile: php artisan route:cache\n";
    
} catch (Exception $e) {
    echo "❌ ERRO GERAL: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n🎉 DIAGNÓSTICO CONCLUÍDO!\n";
