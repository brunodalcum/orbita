<x-dynamic-branding />
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gerenciar Licenciado - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Branding Dinâmico -->
    
<style>
/* CORREÇÃO AGRESSIVA DE CORES - GERADO AUTOMATICAMENTE */
:root {
    --primary-color: #3B82F6;
    --primary-color-rgb: 59, 130, 246;
    --primary-dark: #2563EB;
    --primary-light: rgba(59, 130, 246, 0.1);
    --primary-text: #FFFFFF;
    --secondary-color: #6B7280;
    --accent-color: #10B981;
}

/* Classes customizadas para substituir Tailwind */
.bg-primary { background-color: var(--primary-color) !important; }
.bg-primary-dark { background-color: var(--primary-dark) !important; }
.text-primary { color: var(--primary-color) !important; }
.border-primary { border-color: var(--primary-color) !important; }
.hover\:bg-primary-dark:hover { background-color: var(--primary-dark) !important; }

/* Sobrescrita total de cores azuis */
.bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400,
.bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-blue-800, .bg-blue-900,
.bg-indigo-50, .bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700, .bg-indigo-800, .bg-indigo-900 {
    background-color: var(--primary-color) !important;
}

.text-blue-50, .text-blue-100, .text-blue-200, .text-blue-300, .text-blue-400,
.text-blue-500, .text-blue-600, .text-blue-700, .text-blue-800, .text-blue-900,
.text-indigo-50, .text-indigo-100, .text-indigo-200, .text-indigo-300, .text-indigo-400,
.text-indigo-500, .text-indigo-600, .text-indigo-700, .text-indigo-800, .text-indigo-900 {
    color: var(--primary-color) !important;
}

.border-blue-50, .border-blue-100, .border-blue-200, .border-blue-300, .border-blue-400,
.border-blue-500, .border-blue-600, .border-blue-700, .border-blue-800, .border-blue-900,
.border-indigo-50, .border-indigo-100, .border-indigo-200, .border-indigo-300, .border-indigo-400,
.border-indigo-500, .border-indigo-600, .border-indigo-700, .border-indigo-800, .border-indigo-900 {
    border-color: var(--primary-color) !important;
}

/* Hovers */
.hover\:bg-blue-50:hover, .hover\:bg-blue-100:hover, .hover\:bg-blue-200:hover,
.hover\:bg-blue-300:hover, .hover\:bg-blue-400:hover, .hover\:bg-blue-500:hover,
.hover\:bg-blue-600:hover, .hover\:bg-blue-700:hover, .hover\:bg-blue-800:hover,
.hover\:bg-indigo-50:hover, .hover\:bg-indigo-100:hover, .hover\:bg-indigo-200:hover,
.hover\:bg-indigo-300:hover, .hover\:bg-indigo-400:hover, .hover\:bg-indigo-500:hover,
.hover\:bg-indigo-600:hover, .hover\:bg-indigo-700:hover, .hover\:bg-indigo-800:hover {
    background-color: var(--primary-dark) !important;
}

/* Sobrescrever estilos inline */
[style*="background-color: #3b82f6"], [style*="background-color: #2563eb"],
[style*="background-color: #1d4ed8"], [style*="background-color: #1e40af"],
[style*="background-color: rgb(59, 130, 246)"], [style*="background-color: rgb(37, 99, 235)"] {
    background-color: var(--primary-color) !important;
}

[style*="color: #3b82f6"], [style*="color: #2563eb"],
[style*="color: #1d4ed8"], [style*="color: #1e40af"],
[style*="color: rgb(59, 130, 246)"], [style*="color: rgb(37, 99, 235)"] {
    color: var(--primary-color) !important;
}

/* Botões e elementos interativos */
button:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
.btn:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
input[type="submit"], input[type="button"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

button:hover:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
.btn:hover:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]) {
    background-color: var(--primary-dark) !important;
}

/* Links */
a:not([class*="text-gray"]):not([class*="text-white"]):not([class*="text-black"]):not([class*="text-red"]):not([class*="text-green"]) {
    color: var(--primary-color) !important;
}

a:hover:not([class*="text-gray"]):not([class*="text-white"]):not([class*="text-black"]):not([class*="text-red"]):not([class*="text-green"]) {
    color: var(--primary-dark) !important;
}

/* Focus states */
input:focus, select:focus, textarea:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px var(--primary-light) !important;
    outline: none !important;
}

/* Spinners e loading */
.animate-spin {
    border-color: var(--primary-color) transparent var(--primary-color) transparent !important;
}

/* Badges e tags */
.badge:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
.tag:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]) {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}
</style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar Dinâmico -->
        <x-dynamic-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="dashboard-header bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold" style="color: var(--text-color);">Gerenciar Licenciado</h1>
                        <p style="color: var(--secondary-color);">Centro de gestão e controle do licenciado</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard.licenciados') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar à Lista
                        </a>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Header do Licenciado - Redesenhado -->
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 rounded-2xl shadow-2xl mb-8">
                    <!-- Decoração de fundo -->
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
                    
                    <div class="relative p-8">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-6">
                                <!-- Avatar da empresa com animação -->
                                <div class="relative">
                                    <div class="w-20 h-20 bg-gradient-to-br from-white/20 to-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/20 shadow-xl">
                                        <i class="fas fa-building text-white text-3xl"></i>
                                    </div>
                                    <!-- Indicador de status animado -->
                                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-400 rounded-full border-2 border-white animate-pulse"></div>
                                </div>
                                
                                <div class="text-white">
                                    <h1 class="text-3xl font-bold mb-2">{{ $licenciado->razao_social }}</h1>
                                    <p class="text-blue-200 text-lg mb-1">{{ $licenciado->nome_fantasia }}</p>
                                    <div class="flex items-center space-x-4 text-sm text-blue-300">
                                        <span class="flex items-center">
                                            <i class="fas fa-id-card mr-2"></i>
                                            {{ $licenciado->cnpj_cpf }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-2"></i>
                                            {{ $licenciado->data_cadastro_formatada }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <div class="mb-4">
                                    {!! $licenciado->status_badge !!}
                                </div>
                                <div class="text-white/80 text-sm">
                                    <p>Última atualização</p>
                                    <p class="font-semibold">{{ $licenciado->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ações de Status - Redesenhado -->
                @if($licenciado->status !== 'aprovado')
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 mb-8 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-cogs " style="color: var(--primary-color);" mr-3"></i>
                            Ações de Controle
                        </h3>
                        <p style="color: var(--secondary-color);">Gerencie o status do licenciado com ações diretas</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Aprovar -->
                            <button onclick="alterarStatus({{ $licenciado->id }}, 'aprovado')" 
                                    class="group relative overflow-hidden bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white p-6 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                <div class="absolute inset-0 bg-white/10 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                <div class="relative">
                                    <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-lg mb-3 mx-auto">
                                        <i class="fas fa-check text-2xl"></i>
                                    </div>
                                    <h4 class="font-bold text-lg mb-1">Aprovar Cadastro</h4>
                                    <p class="text-green-100 text-sm">Liberar para operação</p>
                                </div>
                            </button>
                            
                            <!-- Reprovar -->
                            <button onclick="alterarStatus({{ $licenciado->id }}, 'recusado')" 
                                    class="group relative overflow-hidden bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white p-6 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                <div class="absolute inset-0 bg-white/10 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                <div class="relative">
                                    <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-lg mb-3 mx-auto">
                                        <i class="fas fa-times text-2xl"></i>
                                    </div>
                                    <h4 class="font-bold text-lg mb-1">Reprovar Cadastro</h4>
                                    <p class="text-red-100 text-sm">Bloquear acesso</p>
                                </div>
                            </button>
                            
                            <!-- Revisar -->
                            <button onclick="alterarStatus({{ $licenciado->id }}, 'em_analise')" 
                                    class="group relative overflow-hidden bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white p-6 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                <div class="absolute inset-0 bg-white/10 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                <div class="relative">
                                    <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-lg mb-3 mx-auto">
                                        <i class="fas fa-redo text-2xl"></i>
                                    </div>
                                    <h4 class="font-bold text-lg mb-1">Revisar Cadastro</h4>
                                    <p class="text-amber-100 text-sm">Retornar para análise</p>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Coluna Principal -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Dados Cadastrais - Redesenhado -->
                        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                    <i class="fas fa-building " style="color: var(--primary-color);" mr-3"></i>
                                    Dados Cadastrais
                                </h3>
                                <p style="color: var(--secondary-color);">Informações principais da empresa</p>
                            </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Razão Social -->
                                <div class="group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-building text-primary mr-2"></i>
                                        Razão Social
                                    </label>
                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 group-hover:border-blue-300 transition-colors">
                                        <p class="text-gray-900 font-medium">{{ $licenciado->razao_social }}</p>
                                    </div>
                                </div>
                                
                                <!-- Nome Fantasia -->
                                <div class="group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-tag text-purple-500 mr-2"></i>
                                        Nome Fantasia
                                    </label>
                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 group-hover:border-purple-300 transition-colors">
                                        <p class="text-gray-900 font-medium">{{ $licenciado->nome_fantasia ?: 'Não informado' }}</p>
                                    </div>
                                </div>
                                
                                <!-- CNPJ/CPF -->
                                <div class="group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-id-card text-green-500 mr-2"></i>
                                        CNPJ/CPF
                                    </label>
                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 group-hover:border-green-300 transition-colors">
                                        <p class="text-gray-900 font-medium font-mono">{{ $licenciado->cnpj_cpf_formatado }}</p>
                                    </div>
                                </div>
                                
                                <!-- Email -->
                                <div class="group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-envelope text-red-500 mr-2"></i>
                                        Email
                                    </label>
                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 group-hover:border-red-300 transition-colors">
                                        <p class="text-gray-900 font-medium">{{ $licenciado->email ?: 'Não informado' }}</p>
                                    </div>
                                </div>
                                
                                <!-- Telefone -->
                                <div class="group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-phone text-orange-500 mr-2"></i>
                                        Telefone
                                    </label>
                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 group-hover:border-orange-300 transition-colors">
                                        <p class="text-gray-900 font-medium">{{ $licenciado->telefone_formatado ?: 'Não informado' }}</p>
                                    </div>
                                </div>
                                
                                <!-- CEP -->
                                <div class="group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-map-marker-alt text-pink-500 mr-2"></i>
                                        CEP
                                    </label>
                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 group-hover:border-pink-300 transition-colors">
                                        <p class="text-gray-900 font-medium font-mono">{{ $licenciado->cep_formatado }}</p>
                                    </div>
                                </div>
                                
                                <!-- Endereço Completo -->
                                <div class="md:col-span-2 group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-map text-primary mr-2"></i>
                                        Endereço Completo
                                    </label>
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 group-hover:border-indigo-300 transition-colors">
                                        <p class="text-gray-900 font-medium">
                                            {{ $licenciado->endereco }}, {{ $licenciado->bairro }}<br>
                                            <span class="text-gray-600">{{ $licenciado->cidade }}/{{ $licenciado->estado }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Operações Vinculadas - Redesenhado -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-cogs " style="color: var(--accent-color);" mr-3"></i>
                                Operações Vinculadas
                            </h3>
                            <p style="color: var(--secondary-color);">Serviços e produtos disponíveis para este licenciado</p>
                        </div>
                        
                        <div class="p-6">
                            @if($licenciado->operacoes && count($licenciado->operacoes) > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($licenciado->operacoes as $operacaoId)
                                        @php
                                            $operacao = $operacoes->find($operacaoId);
                                        @endphp
                                        @if($operacao)
                                            <div class="group relative overflow-hidden bg-gradient-to-br from-green-50 to-emerald-50 hover:from-green-100 hover:to-emerald-100 border border-green-200 hover:border-green-300 rounded-xl p-4 transition-all duration-300 transform hover:scale-105">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-lg">
                                                        <i class="fas fa-cog text-white text-lg"></i>
                                                    </div>
                                                    <div class="ml-4 flex-1">
                                                        <h4 class="font-bold text-gray-900 text-lg">{{ $operacao->nome }}</h4>
                                                        @if($operacao->adquirente)
                                                            <p style="color: var(--secondary-color);">
                                                                <i class="fas fa-building mr-1"></i>
                                                                {{ $operacao->adquirente }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-cogs text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500 text-lg font-medium">Nenhuma operação vinculada</p>
                                    <p class="text-gray-400 text-sm mt-1">Este licenciado ainda não possui operações configuradas</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Documentos - Redesenhado -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-file-alt text-purple-600 mr-3"></i>
                                        Documentos
                                    </h3>
                                    <p style="color: var(--secondary-color);">Gestão completa de documentos e arquivos</p>
                                </div>
                                <button onclick="openDocumentModal()" 
                                        class="group relative overflow-hidden bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                    <div class="absolute inset-0 bg-white/10 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                    <div class="relative flex items-center">
                                        <i class="fas fa-upload mr-2"></i>
                                        Upload
                                    </div>
                                </button>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @php
                                    $documentos = [
                                        'cartao_cnpj' => ['nome' => 'Cartão CNPJ', 'desc' => 'Documento de identificação', 'icon' => 'fas fa-id-card', 'color' => 'blue'],
                                        'contrato_social' => ['nome' => 'Contrato Social', 'desc' => 'Documento societário', 'icon' => 'fas fa-file-contract', 'color' => 'green'],
                                        'rg_cnh' => ['nome' => 'RG/CNH', 'desc' => 'Documento pessoal', 'icon' => 'fas fa-user', 'color' => 'purple'],
                                        'comprovante_residencia' => ['nome' => 'Comprovante de Residência', 'desc' => 'Documento de endereço', 'icon' => 'fas fa-home', 'color' => 'orange'],
                                        'comprovante_atividade' => ['nome' => 'Comprovante de Atividade', 'desc' => 'Documento de atividade', 'icon' => 'fas fa-briefcase', 'color' => 'red']
                                    ];
                                @endphp
                                
                                @foreach($documentos as $tipo => $info)
                                    @php
                                        $path = $licenciado->{$tipo . '_path'};
                                        $hasFile = !empty($path);
                                    @endphp
                                    
                                    <div class="group relative overflow-hidden bg-gradient-to-br from-{{ $info['color'] }}-50 to-{{ $info['color'] }}-100 hover:from-{{ $info['color'] }}-100 hover:to-{{ $info['color'] }}-200 border border-{{ $info['color'] }}-200 hover:border-{{ $info['color'] }}-300 rounded-xl p-4 transition-all duration-300 transform hover:scale-105">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-{{ $info['color'] }}-500 to-{{ $info['color'] }}-600 rounded-lg flex items-center justify-center shadow-lg">
                                                    <i class="{{ $info['icon'] }} text-white text-lg"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <h4 class="font-bold text-gray-900">{{ $info['nome'] }}</h4>
                                                    <p style="color: var(--secondary-color);">{{ $info['desc'] }}</p>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center space-x-2">
                                                @if($hasFile)
                                                    <a href="{{ route('licenciados.download', ['licenciado' => $licenciado->id, 'tipo' => $tipo]) }}" 
                                                       class="group/btn relative overflow-hidden bg-gradient-to-r from-{{ $info['color'] }}-500 to-{{ $info['color'] }}-600 hover:from-{{ $info['color'] }}-600 hover:to-{{ $info['color'] }}-700 text-white p-2 rounded-lg transition-all duration-300 transform hover:scale-110"
                                                       title="Download">
                                                        <div class="absolute inset-0 bg-white/10 transform -skew-x-12 -translate-x-full group-hover/btn:translate-x-full transition-transform duration-500"></div>
                                                        <i class="fas fa-download relative"></i>
                                                    </a>
                                                    <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse" title="Documento disponível"></div>
                                                @else
                                                    <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center" title="Documento não enviado">
                                                        <i class="fas fa-file text-gray-400"></i>
                                                    </div>
                                                    <div class="w-3 h-3 bg-gray-300 rounded-full" title="Documento pendente"></div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Redesenhado -->
                <div class="space-y-6">
                    <!-- Status e Estatísticas -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-chart-line " style="color: var(--primary-color);" mr-3"></i>
                                Status & Estatísticas
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-6">
                                <!-- Status Atual -->
                                <div class="text-center">
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Status Atual</label>
                                    <div class="transform scale-110">
                                        {!! $licenciado->status_badge !!}
                                    </div>
                                </div>
                                
                                <!-- Estatísticas -->
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                                                <i class="fas fa-calendar-plus text-white"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-700">Data de Cadastro</p>
                                                <p class="text-lg font-bold " style="color: var(--primary-color);"">{{ $licenciado->data_cadastro_formatada }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-clock text-white"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-700">Última Atualização</p>
                                                <p class="text-lg font-bold " style="color: var(--accent-color);"">{{ $licenciado->updated_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Follow-ups -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-50 to-green-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-comments text-emerald-600 mr-3"></i>
                                        Follow-ups
                                    </h3>
                                    <p style="color: var(--secondary-color);">Histórico de interações</p>
                                </div>
                                <button onclick="openFollowUpModal()" 
                                        class="group relative overflow-hidden bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white p-2 rounded-lg transition-all duration-300 transform hover:scale-110 hover:shadow-lg">
                                    <div class="absolute inset-0 bg-white/10 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-500"></div>
                                    <i class="fas fa-plus relative"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-4 max-h-96 overflow-y-auto">
                                @forelse($followups as $followup)
                                    <div class="group relative overflow-hidden bg-gradient-to-r from-gray-50 to-blue-50 hover:from-blue-50 hover:to-indigo-50 border border-gray-200 hover:border-blue-300 rounded-xl p-4 transition-all duration-300">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-comment text-white text-sm"></i>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm font-bold text-gray-900 capitalize">{{ $followup->tipo }}</span>
                                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $followup->created_at->format('d/m H:i') }}</span>
                                                </div>
                                                <p class="text-sm text-gray-700 mt-2 leading-relaxed">{{ $followup->observacao }}</p>
                                                @if($followup->user)
                                                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                                                        <i class="fas fa-user mr-1"></i>
                                                        Por: {{ $followup->user->name }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-comments text-gray-400 text-2xl"></i>
                                        </div>
                                        <p class="text-gray-500 text-lg font-medium">Nenhum follow-up</p>
                                        <p class="text-gray-400 text-sm mt-1">Ainda não há interações registradas</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Upload de Documento -->
    <div id="documentModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center p-4" 
         role="dialog" aria-labelledby="document-title" aria-describedby="document-description" aria-modal="true">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 ease-out">
            <div class="h-2 bg-gradient-to-r from-blue-400 via-blue-500 to-indigo-500 rounded-t-2xl"></div>
            
            <div class="p-6">
                <h2 id="document-title" class="text-xl font-bold text-gray-900 mb-4">
                    Upload de Documento
                </h2>
                
                <form id="documentForm" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="document_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Documento
                            </label>
                            <select id="document_type" name="document_type" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-primary">
                                <option value="">Selecione o tipo</option>
                                <option value="cartao_cnpj">Cartão CNPJ</option>
                                <option value="contrato_social">Contrato Social</option>
                                <option value="rg_cnh">RG/CNH</option>
                                <option value="comprovante_residencia">Comprovante de Residência</option>
                                <option value="comprovante_atividade">Comprovante de Atividade</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="document_file" class="block text-sm font-medium text-gray-700 mb-2">
                                Arquivo
                            </label>
                            <input type="file" id="document_file" name="document_file" 
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-primary">
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeDocumentModal()" 
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 btn-primary rounded-lg transition-colors">
                            <i class="fas fa-upload mr-2"></i>
                            Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Follow-up -->
    <div id="followUpModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center p-4" 
         role="dialog" aria-labelledby="followup-title" aria-describedby="followup-description" aria-modal="true">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 ease-out">
            <div class="h-2 bg-gradient-to-r from-green-400 via-green-500 to-emerald-500 rounded-t-2xl"></div>
            
            <div class="p-6">
                <h2 id="followup-title" class="text-xl font-bold text-gray-900 mb-4">
                    Novo Follow-up
                </h2>
                
                <form id="followUpForm">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="followup_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo
                            </label>
                            <select id="followup_type" name="tipo" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="contato">Contato</option>
                                <option value="documentacao">Documentação</option>
                                <option value="analise">Análise</option>
                                <option value="aprovacao">Aprovação</option>
                                <option value="rejeicao">Rejeição</option>
                                <option value="observacao">Observação</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="followup_observacao" class="block text-sm font-medium text-gray-700 mb-2">
                                Observação
                            </label>
                            <textarea id="followup_observacao" name="observacao" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                      placeholder="Descreva o follow-up..."></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeFollowUpModal()" 
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 btn-success rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Adicionar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Modais */
        #documentModal, #followUpModal {
            display: none !important;
        }
        
        #documentModal.show, #followUpModal.show {
            display: flex !important;
        }
            
        /* Estilos dinâmicos do dashboard */
        .dashboard-header {
            background: var(--background-color);
            color: var(--text-color);
        }
        .stat-card {
            background: var(--primary-gradient);
            color: var(--primary-text);
        }
        .progress-bar {
            background: var(--accent-gradient);
        }
    </style>

    <script>
        const licenciadoId = {{ $licenciado->id }};

        // Função para alterar status
        async function alterarStatus(id, status) {
            const statusLabels = {
                'aprovado': 'Aprovar',
                'recusado': 'Reprovar',
                'em_analise': 'Revisar'
            };

            if (!confirm(`Tem certeza que deseja ${statusLabels[status]} este licenciado?`)) {
                return;
            }

            try {
                const response = await fetch(`/dashboard/licenciados/${id}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status })
                });

                const result = await response.json();

                if (result.success) {
                    alert('Status alterado com sucesso!');
                    location.reload();
                } else {
                    alert('Erro ao alterar status: ' + (result.message || 'Erro desconhecido'));
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro de conexão');
            }
        }

        // Funções do modal de documento
        function openDocumentModal() {
            document.getElementById('documentModal').classList.add('show');
        }

        function closeDocumentModal() {
            document.getElementById('documentModal').classList.remove('show');
            document.getElementById('documentForm').reset();
        }

        // Funções do modal de follow-up
        function openFollowUpModal() {
            document.getElementById('followUpModal').classList.add('show');
        }

        function closeFollowUpModal() {
            document.getElementById('followUpModal').classList.remove('show');
            document.getElementById('followUpForm').reset();
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Form de documento
            document.getElementById('documentForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                formData.append('licenciado_id', licenciadoId);
                
                try {
                    const response = await fetch(`/dashboard/licenciados/${licenciadoId}/document`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert('Documento enviado com sucesso!');
                        closeDocumentModal();
                        location.reload();
                    } else {
                        alert('Erro ao enviar documento: ' + (result.message || 'Erro desconhecido'));
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    alert('Erro de conexão');
                }
            });

            // Form de follow-up
            document.getElementById('followUpForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                try {
                    const response = await fetch(`/dashboard/licenciados/${licenciadoId}/followup`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert('Follow-up adicionado com sucesso!');
                        closeFollowUpModal();
                        location.reload();
                    } else {
                        alert('Erro ao adicionar follow-up: ' + (result.message || 'Erro desconhecido'));
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    alert('Erro de conexão');
                }
            });

            // Fechar modais ao clicar fora
            document.getElementById('documentModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDocumentModal();
                }
            });

            document.getElementById('followUpModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeFollowUpModal();
                }
            });
        });
    </script>
            </main>
        </div>
    </div>
</body>
</html>
