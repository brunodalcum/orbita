<?php

/**
 * Teste rápido para verificar se a correção de sintaxe funcionou
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\User;

echo "🔧 TESTANDO CORREÇÃO DE SINTAXE\n";
echo "===============================\n\n";

try {
    // Autenticar como Super Admin
    $superAdmin = User::where('email', 'brunodalcum@dspay.com.br')->first();
    
    if (!$superAdmin) {
        echo "❌ Super Admin não encontrado!\n";
        exit(1);
    }
    
    Auth::login($superAdmin);
    echo "✅ Autenticado como: {$superAdmin->name}\n";
    
    // Preparar dados mínimos
    $user = Auth::user();
    $targetUser = $user;
    $selectedNodeId = null;
    
    $availableNodes = collect([$user]);
    $currentBranding = [
        'primary_color' => '#3B82F6',
        'secondary_color' => '#6B7280',
        'accent_color' => '#10B981',
        'text_color' => '#1F2937',
        'background_color' => '#FFFFFF',
        'font_family' => 'Inter',
        'custom_css' => '',
        'inherit_from_parent' => false,
        'logo_url' => null,
        'logo_small_url' => null,
        'favicon_url' => null
    ];
    
    $parentBranding = null;
    $availableFonts = ['Inter' => 'Inter'];
    $colorPresets = [
        'blue' => [
            'name' => 'Azul',
            'primary_color' => '#3B82F6',
            'secondary_color' => '#6B7280',
            'accent_color' => '#10B981'
        ]
    ];
    
    // Tentar renderizar a view
    echo "🎨 Testando renderização da view...\n";
    
    $viewData = compact(
        'user', 'targetUser', 'currentBranding', 'parentBranding',
        'availableFonts', 'colorPresets', 'availableNodes', 'selectedNodeId'
    );
    
    $html = View::make('hierarchy.branding.index', $viewData)->render();
    
    echo "✅ View renderizada com sucesso!\n";
    echo "📊 Tamanho do HTML: " . number_format(strlen($html)) . " caracteres\n";
    
    // Verificar se contém elementos essenciais
    $checks = [
        'node-selector' => strpos($html, 'id="node-selector"') !== false,
        'Super Admin' => strpos($html, 'Super Admin') !== false,
        'Personalizar branding' => strpos($html, 'Personalizar branding') !== false,
        'JavaScript' => strpos($html, 'brandingManager()') !== false
    ];
    
    echo "\n🔍 Verificações:\n";
    foreach ($checks as $item => $found) {
        echo "   " . ($found ? '✅' : '❌') . " {$item}\n";
    }
    
    if (all($checks)) {
        echo "\n🎉 SUCESSO! Todos os elementos essenciais estão presentes.\n";
        echo "✅ A correção de sintaxe funcionou perfeitamente!\n";
    } else {
        echo "\n⚠️  Alguns elementos podem estar faltando, mas a sintaxe está correta.\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERRO:\n";
    echo "   Tipo: " . get_class($e) . "\n";
    echo "   Mensagem: " . $e->getMessage() . "\n";
    echo "   Arquivo: " . $e->getFile() . "\n";
    echo "   Linha: " . $e->getLine() . "\n";
    
    if (strpos($e->getMessage(), 'syntax error') !== false) {
        echo "\n🔧 AINDA HÁ ERRO DE SINTAXE!\n";
        echo "   Verifique a estrutura @if/@endif na view.\n";
    }
}

function all($array) {
    foreach ($array as $value) {
        if (!$value) return false;
    }
    return true;
}

echo "\n✅ Teste concluído!\n";
