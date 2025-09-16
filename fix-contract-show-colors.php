<?php

// Script para corrigir cores na página de detalhes do contrato

$filePath = 'resources/views/dashboard/contracts/show.blade.php';
$fullPath = __DIR__ . '/' . $filePath;

if (!file_exists($fullPath)) {
    echo "❌ Arquivo não encontrado: $filePath\n";
    exit(1);
}

$content = file_get_contents($fullPath);
$originalContent = $content;

// Substituições específicas para usar variáveis CSS
$replacements = [
    // Botões primários (azuis) -> usar cor primária
    'class="bg-blue-600 hover:bg-blue-700' => 'style="background-color: var(--primary-color);" class="hover:bg-blue-700',
    'class="px-4 py-2 bg-blue-600 hover:bg-blue-700' => 'style="background-color: var(--primary-color);" class="px-4 py-2 hover:bg-blue-700',
    
    // Botões verdes -> usar cor de accent
    'class="bg-green-600 hover:bg-green-700' => 'style="background-color: var(--accent-color);" class="hover:bg-green-700',
    'class="px-4 py-2 bg-green-600 hover:bg-green-700' => 'style="background-color: var(--accent-color);" class="px-4 py-2 hover:bg-green-700',
    
    // Botões roxos -> manter como accent secundário
    'class="bg-purple-600 hover:bg-purple-700' => 'style="background-color: #8B5CF6;" class="hover:bg-purple-700',
    
    // Backgrounds e textos
    'class="bg-blue-50 border border-blue-200' => 'style="background-color: var(--primary-light); border-color: var(--primary-color);" class="border',
    'class="bg-blue-100 text-blue-800' => 'style="background-color: var(--primary-light); color: var(--primary-color);" class="',
    'class="bg-green-100 text-green-800' => 'style="background-color: var(--accent-color); color: white;" class="',
    'text-blue-900' => 'style="color: var(--primary-dark);"',
    
    // Toast notifications
    "'bg-green-500 text-white'" => "'text-white' style='background-color: var(--accent-color);'",
    "'bg-red-500 text-white'" => "'bg-red-500 text-white'", // Manter vermelho para erros
];

$changes = [];

foreach ($replacements as $old => $new) {
    if (strpos($content, $old) !== false) {
        $content = str_replace($old, $new, $content);
        $changes[] = "$old → $new";
    }
}

// Adicionar CSS específico para sobrescrever cores restantes
$additionalCSS = '
        /* Sobrescrever cores específicas da página de contratos */
        .bg-blue-600, .bg-blue-700 {
            background-color: var(--primary-color) !important;
        }
        .hover\\:bg-blue-700:hover {
            background-color: var(--primary-dark) !important;
        }
        .bg-green-600, .bg-green-700 {
            background-color: var(--accent-color) !important;
        }
        .hover\\:bg-green-700:hover {
            opacity: 0.9;
        }
        .bg-blue-50 {
            background-color: var(--primary-light) !important;
        }
        .border-blue-200 {
            border-color: var(--primary-color) !important;
            opacity: 0.3;
        }
        .bg-blue-100 {
            background-color: var(--primary-light) !important;
        }
        .text-blue-800, .text-blue-900 {
            color: var(--primary-color) !important;
        }
        .bg-green-100 {
            background-color: var(--accent-color) !important;
            opacity: 0.2;
        }
        .text-green-800 {
            color: var(--accent-color) !important;
        }
';

// Inserir CSS adicional antes do </style> final
if (strpos($content, '</style>') !== false) {
    $content = str_replace('</style>', $additionalCSS . '</style>', $content);
    $changes[] = "CSS adicional para sobrescrever cores de contratos";
}

// Se houve mudanças, salvar o arquivo
if ($content !== $originalContent) {
    if (file_put_contents($fullPath, $content)) {
        echo "✅ $filePath atualizado com sucesso!\n\n";
        echo "Mudanças aplicadas:\n";
        foreach ($changes as $change) {
            echo "  • $change\n";
        }
    } else {
        echo "❌ Erro ao escrever arquivo: $filePath\n";
    }
} else {
    echo "ℹ️  $filePath: Nenhuma mudança necessária\n";
}

echo "\n=== CORREÇÃO CONCLUÍDA ===\n";
echo "A página de detalhes do contrato agora usa as cores do branding dinâmico.\n";

?>
