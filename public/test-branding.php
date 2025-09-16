<?php
// Script de teste para verificar branding universal
// Acesse via: /test-branding.php

header("Content-Type: text/html; charset=utf-8");

echo "<!DOCTYPE html>
<html>
<head>
    <title>Teste de Branding Universal</title>
    <script src=\"https://cdn.tailwindcss.com\"></script>
</head>
<body class=\"p-8\">
    <h1 class=\"text-3xl font-bold mb-6\">🎨 Teste de Branding Universal</h1>
    
    <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6\">
        
        <!-- Botões Azuis -->
        <div class=\"bg-white p-6 rounded-lg shadow\">
            <h2 class=\"text-lg font-semibold mb-4\">Botões Azuis</h2>
            <button class=\"bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mb-2 block w-full\">
                bg-blue-500
            </button>
            <button class=\"bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-2 block w-full\">
                bg-blue-600
            </button>
            <button class=\"bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded block w-full\">
                bg-indigo-500
            </button>
        </div>
        
        <!-- Textos Azuis -->
        <div class=\"bg-white p-6 rounded-lg shadow\">
            <h2 class=\"text-lg font-semibold mb-4\">Textos Azuis</h2>
            <p class=\"text-blue-500 mb-2\">text-blue-500</p>
            <p class=\"text-blue-600 mb-2\">text-blue-600</p>
            <p class=\"text-indigo-500 mb-2\">text-indigo-500</p>
            <p class=\"text-indigo-600\">text-indigo-600</p>
        </div>
        
        <!-- Bordas Azuis -->
        <div class=\"bg-white p-6 rounded-lg shadow\">
            <h2 class=\"text-lg font-semibold mb-4\">Bordas Azuis</h2>
            <div class=\"border-2 border-blue-500 p-3 mb-2 rounded\">border-blue-500</div>
            <div class=\"border-2 border-blue-600 p-3 mb-2 rounded\">border-blue-600</div>
            <div class=\"border-2 border-indigo-500 p-3 rounded\">border-indigo-500</div>
        </div>
        
        <!-- Botões Verdes -->
        <div class=\"bg-white p-6 rounded-lg shadow\">
            <h2 class=\"text-lg font-semibold mb-4\">Botões Verdes (Accent)</h2>
            <button class=\"bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mb-2 block w-full\">
                bg-green-500
            </button>
            <button class=\"bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded mb-2 block w-full\">
                bg-green-600
            </button>
            <button class=\"bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded block w-full\">
                bg-emerald-500
            </button>
        </div>
        
        <!-- Formulários -->
        <div class=\"bg-white p-6 rounded-lg shadow\">
            <h2 class=\"text-lg font-semibold mb-4\">Formulários</h2>
            <input type=\"text\" placeholder=\"Input com foco\" class=\"w-full p-2 border rounded mb-2\">
            <select class=\"w-full p-2 border rounded mb-2\">
                <option>Select com foco</option>
            </select>
            <textarea placeholder=\"Textarea com foco\" class=\"w-full p-2 border rounded\" rows=\"3\"></textarea>
        </div>
        
        <!-- Loading -->
        <div class=\"bg-white p-6 rounded-lg shadow\">
            <h2 class=\"text-lg font-semibold mb-4\">Loading</h2>
            <div class=\"animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mb-4\"></div>
            <div class=\"animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500\"></div>
        </div>
        
    </div>
    
    <div class=\"mt-8 bg-gray-100 p-6 rounded-lg\">
        <h2 class=\"text-lg font-semibold mb-4\">🔍 Status do Branding</h2>
        <p><strong>CSS Injetado:</strong> <span id=\"css-status\">Verificando...</span></p>
        <p><strong>Cor Primária:</strong> <span id=\"primary-color\">Verificando...</span></p>
        <p><strong>Cor Accent:</strong> <span id=\"accent-color\">Verificando...</span></p>
    </div>
    
    <script>
        // Verificar se CSS foi injetado
        const brandingStyle = document.getElementById(\"branding-injected\");
        document.getElementById(\"css-status\").textContent = brandingStyle ? \"✅ Ativo\" : \"❌ Não encontrado\";
        
        // Mostrar cores atuais
        const rootStyles = getComputedStyle(document.documentElement);
        const primaryColor = rootStyles.getPropertyValue(\"--primary-color\").trim();
        const accentColor = rootStyles.getPropertyValue(\"--accent-color\").trim();
        
        document.getElementById(\"primary-color\").textContent = primaryColor || \"Não definida\";
        document.getElementById(\"accent-color\").textContent = accentColor || \"Não definida\";
        
        if (primaryColor) {
            document.getElementById(\"primary-color\").style.color = primaryColor;
        }
        if (accentColor) {
            document.getElementById(\"accent-color\").style.color = accentColor;
        }
    </script>
</body>
</html>";
?>