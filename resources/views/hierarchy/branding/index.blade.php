<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Configuração de Branding - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Branding Dinâmico -->
    <x-dynamic-branding />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid white;
        }
        .card {
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        /* Garantir scroll completo da página */
        html, body {
            height: 100%;
            overflow-x: hidden;
        }
        
        .main-content {
            height: 100vh;
            overflow-y: auto;
        }
        
        /* Espaçamento adequado para o conteúdo */
        .content-wrapper {
            min-height: calc(100vh - 4rem);
            padding-bottom: 2rem;
        }
        
        /* Garantir que cards não sejam cortados */
        .branding-section {
            margin-bottom: 2rem;
        }
        
        .branding-section:last-child {
            margin-bottom: 4rem;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar Dinâmico -->
        <x-dynamic-sidebar />
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col ml-64 main-content">
            <div class="bg-gray-50 content-wrapper" x-data="brandingManager()">
                <!-- Header -->
                <div class="bg-white shadow-sm border-b">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center justify-between h-16">
                            <div class="flex items-center">
                                <h1 class="text-xl font-semibold text-gray-900">Configuração de Branding</h1>
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst(str_replace('_', ' ', $user->node_type)) }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <button @click="resetBranding()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Resetar
                                </button>
                                
                                <button @click="exportCss()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Exportar CSS
                                </button>
                                
                                <a href="{{ route('hierarchy.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                    Voltar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if($user->isSuperAdminNode() && count($availableNodes) > 0)
                <!-- Seletor de Nó para Super Admin -->
                <div class="bg-white border-b">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"/>
                                    </svg>
                                    <label for="node-selector" class="text-sm font-medium text-gray-700">Personalizar branding de:</label>
                                </div>
                                <select id="node-selector" onchange="selectNode(this.value)" class="block w-80 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Selecione um nó...</option>
                                    
                                    @php
                                        $superAdminNodes = $availableNodes->where('node_type', 'super_admin');
                                        $otherNodes = $availableNodes->where('node_type', '!=', 'super_admin')->groupBy('node_type');
                                    @endphp
                                    
                                    <!-- Super Admin primeiro -->
                                    @if($superAdminNodes->count() > 0)
                                        <optgroup label="Super Admin">
                                            @foreach($superAdminNodes as $node)
                                                <option value="{{ $node->id }}" {{ $selectedNodeId == $node->id ? 'selected' : '' }}>
                                                    {{ $node->name }} ({{ $node->email }}) - Super Admin
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                    
                                    <!-- Outros nós -->
                                    @foreach($otherNodes as $nodeType => $nodes)
                                        <optgroup label="{{ ucfirst(str_replace('_', ' ', $nodeType)) }}">
                                            @foreach($nodes as $node)
                                                <option value="{{ $node->id }}" {{ $selectedNodeId == $node->id ? 'selected' : '' }}>
                                                    {{ $node->name }} ({{ $node->email }})
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            @if($selectedNodeId)
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Editando: {{ $targetUser->name }}
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $targetUser->isSuperAdminNode() ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $targetUser->isSuperAdminNode() ? 'Super Admin' : ucfirst(str_replace('_', ' ', $targetUser->node_type)) }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                @if($selectedNodeId && $targetUser->isSuperAdminNode())
                <!-- Aviso para edição do Super Admin -->
                <div class="bg-purple-50 border-l-4 border-purple-400">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-purple-700">
                                    <strong>Editando Super Admin:</strong> As alterações de branding serão aplicadas especificamente ao Super Admin. 
                                    Isso permitirá personalizar a aparência do portal administrativo principal.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif($selectedNodeId)
                <!-- Aviso para edição de outros nós -->
                <div class="bg-blue-50 border-l-4 border-blue-400">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <strong>Editando {{ $targetUser->name }}:</strong> As alterações de branding serão aplicadas especificamente a este nó 
                                    ({{ $targetUser->isSuperAdminNode() ? 'Super Admin' : ucfirst(str_replace('_', ' ', $targetUser->node_type)) }}).
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

    <!-- Conteúdo principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Coluna principal - Configurações -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Herança -->
                @if($parentBranding)
                <div class="bg-white shadow rounded-lg branding-section">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-medium text-gray-900">Herança de Branding</h2>
                            <label class="inline-flex items-center">
                                <input type="checkbox" x-model="inheritFromParent" @change="toggleInheritance()" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Herdar do pai</span>
                            </label>
                        </div>
                    </div>
                    <div class="p-6" x-show="inheritFromParent">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="flex-shrink-0 w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Herdando configurações</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>Suas configurações de branding estão sendo herdadas do nó pai. Desmarque a opção acima para personalizar.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Presets de cores -->
                <div class="bg-white shadow rounded-lg branding-section" x-show="!inheritFromParent">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Presets de Cores</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($colorPresets as $key => $preset)
                            <div @click="applyColorPreset('{{ $key }}')" class="cursor-pointer group">
                                <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition-colors duration-200 group-hover:shadow-md">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <div class="w-6 h-6 rounded-full" style="background-color: {{ $preset['primary_color'] }}"></div>
                                        <div class="w-6 h-6 rounded-full" style="background-color: {{ $preset['secondary_color'] }}"></div>
                                        <div class="w-6 h-6 rounded-full" style="background-color: {{ $preset['accent_color'] }}"></div>
                                    </div>
                                    <h3 class="text-sm font-medium text-gray-900">{{ $preset['name'] }}</h3>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Configurações de cores -->
                <div class="bg-white shadow rounded-lg branding-section" x-show="!inheritFromParent">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Cores Personalizadas</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cor Primária</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" x-model="colors.primary_color" @change="updatePreview()" class="h-10 w-20 border border-gray-300 rounded-md">
                                    <input type="text" x-model="colors.primary_color" @input="updatePreview()" class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cor Secundária</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" x-model="colors.secondary_color" @change="updatePreview()" class="h-10 w-20 border border-gray-300 rounded-md">
                                    <input type="text" x-model="colors.secondary_color" @input="updatePreview()" class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cor de Destaque</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" x-model="colors.accent_color" @change="updatePreview()" class="h-10 w-20 border border-gray-300 rounded-md">
                                    <input type="text" x-model="colors.accent_color" @input="updatePreview()" class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cor do Texto</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" x-model="colors.text_color" @change="updatePreview()" class="h-10 w-20 border border-gray-300 rounded-md">
                                    <input type="text" x-model="colors.text_color" @input="updatePreview()" class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm">
                                </div>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cor de Fundo</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" x-model="colors.background_color" @change="updatePreview()" class="h-10 w-20 border border-gray-300 rounded-md">
                                    <input type="text" x-model="colors.background_color" @input="updatePreview()" class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Configurações de tipografia -->
                <div class="bg-white shadow rounded-lg branding-section" x-show="!inheritFromParent">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Tipografia</h2>
                    </div>
                    <div class="p-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Família da Fonte</label>
                            <select x-model="fontFamily" @change="updatePreview()" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                @foreach($availableFonts as $key => $font)
                                <option value="{{ $key }}">{{ $font }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Upload de logos -->
                <div class="bg-white shadow rounded-lg branding-section" x-show="!inheritFromParent">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Logos e Ícones</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Logo principal -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Logo Principal</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors duration-200">
                                    <input type="file" @change="handleFileUpload($event, 'logo')" accept="image/*" class="hidden" id="logo-upload">
                                    <label for="logo-upload" class="cursor-pointer">
                                        <div x-show="!logoPreview">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600">Clique para enviar</p>
                                            <p class="text-xs text-gray-500">PNG, JPG até 2MB</p>
                                        </div>
                                        <div x-show="logoPreview">
                                            <img :src="logoPreview" class="mx-auto h-20 object-contain">
                                            <p class="mt-2 text-sm text-gray-600">Clique para alterar</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Logo pequeno -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Logo Pequeno</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors duration-200">
                                    <input type="file" @change="handleFileUpload($event, 'logo_small')" accept="image/*" class="hidden" id="logo-small-upload">
                                    <label for="logo-small-upload" class="cursor-pointer">
                                        <div x-show="!logoSmallPreview">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600">Clique para enviar</p>
                                            <p class="text-xs text-gray-500">PNG, JPG até 1MB</p>
                                        </div>
                                        <div x-show="logoSmallPreview">
                                            <img :src="logoSmallPreview" class="mx-auto h-16 object-contain">
                                            <p class="mt-2 text-sm text-gray-600">Clique para alterar</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Favicon -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors duration-200">
                                    <input type="file" @change="handleFileUpload($event, 'favicon')" accept="image/png,image/x-icon" class="hidden" id="favicon-upload">
                                    <label for="favicon-upload" class="cursor-pointer">
                                        <div x-show="!faviconPreview">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600">Clique para enviar</p>
                                            <p class="text-xs text-gray-500">PNG, ICO até 512KB</p>
                                        </div>
                                        <div x-show="faviconPreview">
                                            <img :src="faviconPreview" class="mx-auto h-12 object-contain">
                                            <p class="mt-2 text-sm text-gray-600">Clique para alterar</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CSS Customizado -->
                <div class="bg-white shadow rounded-lg branding-section" x-show="!inheritFromParent">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">CSS Customizado</h2>
                    </div>
                    <div class="p-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CSS Adicional</label>
                            <textarea x-model="customCss" @input="updatePreview()" rows="10" class="w-full border border-gray-300 rounded-md px-3 py-2 font-mono text-sm" placeholder="/* Adicione seu CSS customizado aqui */"></textarea>
                            <p class="mt-2 text-sm text-gray-500">Use variáveis CSS como var(--primary-color) para referenciar as cores definidas acima.</p>
                        </div>
                    </div>
                </div>

                <!-- Botões de ação -->
                <div class="flex justify-end space-x-4">
                    <button @click="saveBranding()" :disabled="saving" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg x-show="!saving" class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <svg x-show="saving" class="animate-spin -ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="saving ? 'Salvando...' : 'Salvar Configurações'">Salvar Configurações</span>
                    </button>
                </div>
            </div>

            <!-- Coluna lateral - Preview -->
            <div class="space-y-6">
                <!-- Preview em tempo real -->
                <div class="bg-white shadow rounded-lg branding-section sticky top-4">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Preview</h3>
                    </div>
                    <div class="p-6">
                        <div id="preview-container" class="border border-gray-200 rounded-lg overflow-hidden">
                            <iframe id="preview-frame" src="{{ route('hierarchy.branding.preview') }}" class="w-full h-96 border-0"></iframe>
                        </div>
                    </div>
                </div>

                <!-- Informações sobre herança -->
                @if($parentBranding)
                <div class="bg-white shadow rounded-lg branding-section">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Branding Herdado</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @if(isset($parentBranding['primary_color']))
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Cor Primária:</span>
                                <div class="flex items-center space-x-2">
                                    <div class="w-4 h-4 rounded-full border" style="background-color: {{ $parentBranding['primary_color'] }}"></div>
                                    <span class="text-sm font-mono">{{ $parentBranding['primary_color'] }}</span>
                                </div>
                            </div>
                            @endif
                            
                            @if(isset($parentBranding['font_family']))
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Fonte:</span>
                                <span class="text-sm">{{ $parentBranding['font_family'] }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function brandingManager() {
    return {
        inheritFromParent: @json($currentBranding['inherit_from_parent'] ?? false),
        colors: {
            primary_color: @json($currentBranding['primary_color'] ?? '#3B82F6'),
            secondary_color: @json($currentBranding['secondary_color'] ?? '#6B7280'),
            accent_color: @json($currentBranding['accent_color'] ?? '#10B981'),
            text_color: @json($currentBranding['text_color'] ?? '#1F2937'),
            background_color: @json($currentBranding['background_color'] ?? '#FFFFFF')
        },
        fontFamily: @json($currentBranding['font_family'] ?? 'Inter'),
        customCss: @json($currentBranding['custom_css'] ?? ''),
        logoPreview: @json(($currentBranding['logo_url'] ?? null) ? asset('storage/' . $currentBranding['logo_url']) : null),
        logoSmallPreview: @json(($currentBranding['logo_small_url'] ?? null) ? asset('storage/' . $currentBranding['logo_small_url']) : null),
        faviconPreview: @json(($currentBranding['favicon_url'] ?? null) ? asset('storage/' . $currentBranding['favicon_url']) : null),
        saving: false,
        uploadedFiles: {},
        
        init() {
            this.updatePreview();
        },
        
        toggleInheritance() {
            if (this.inheritFromParent) {
                this.updatePreview();
            }
        },
        
        applyColorPreset(preset) {
            const presets = @json($colorPresets);
            if (presets[preset]) {
                this.colors = { ...presets[preset] };
                this.updatePreview();
            }
        },
        
        updatePreview() {
            // Atualizar preview em tempo real
            const iframe = document.getElementById('preview-frame');
            if (iframe) {
                const params = new URLSearchParams({
                    primary_color: this.colors.primary_color,
                    secondary_color: this.colors.secondary_color,
                    accent_color: this.colors.accent_color,
                    text_color: this.colors.text_color,
                    background_color: this.colors.background_color,
                    font_family: this.fontFamily,
                    custom_css: this.customCss
                });
                
                iframe.src = '{{ route("hierarchy.branding.preview") }}?' + params.toString();
            }
        },
        
        handleFileUpload(event, type) {
            const file = event.target.files[0];
            if (file) {
                this.uploadedFiles[type] = file;
                
                // Criar preview
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (type === 'logo') {
                        this.logoPreview = e.target.result;
                    } else if (type === 'logo_small') {
                        this.logoSmallPreview = e.target.result;
                    } else if (type === 'favicon') {
                        this.faviconPreview = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        },
        
        async saveBranding() {
            if (this.saving) return;
            
            this.saving = true;
            
            try {
                const formData = new FormData();
                
                // Adicionar node_id se selecionado (Super Admin)
                const urlParams = new URLSearchParams(window.location.search);
                const nodeId = urlParams.get('node_id');
                if (nodeId) {
                    formData.append('node_id', nodeId);
                }
                
                // Adicionar configurações
                formData.append('inherit_from_parent', this.inheritFromParent);
                
                if (!this.inheritFromParent) {
                    formData.append('primary_color', this.colors.primary_color);
                    formData.append('secondary_color', this.colors.secondary_color);
                    formData.append('accent_color', this.colors.accent_color);
                    formData.append('text_color', this.colors.text_color);
                    formData.append('background_color', this.colors.background_color);
                    formData.append('font_family', this.fontFamily);
                    formData.append('custom_css', this.customCss);
                    
                    // Adicionar arquivos
                    Object.keys(this.uploadedFiles).forEach(key => {
                        formData.append(key, this.uploadedFiles[key]);
                    });
                }
                
                const response = await fetch('{{ route("hierarchy.branding.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Mostrar sucesso
                    this.showNotification('Branding salvo com sucesso!', 'success');
                    
                    // Limpar arquivos enviados
                    this.uploadedFiles = {};
                } else {
                    this.showNotification(data.message || 'Erro ao salvar branding', 'error');
                }
            } catch (error) {
                console.error('Erro ao salvar branding:', error);
                this.showNotification('Erro ao salvar branding', 'error');
            } finally {
                this.saving = false;
            }
        },
        
        async resetBranding() {
            if (!confirm('Tem certeza que deseja resetar todas as configurações de branding?')) {
                return;
            }
            
            try {
                // Preparar dados com node_id se selecionado
                const urlParams = new URLSearchParams(window.location.search);
                const nodeId = urlParams.get('node_id');
                const requestData = nodeId ? { node_id: nodeId } : {};
                
                const response = await fetch('{{ route("hierarchy.branding.reset") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(requestData)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification('Branding resetado com sucesso!', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    this.showNotification(data.message || 'Erro ao resetar branding', 'error');
                }
            } catch (error) {
                console.error('Erro ao resetar branding:', error);
                this.showNotification('Erro ao resetar branding', 'error');
            }
        },
        
        exportCss() {
            window.open('{{ route("hierarchy.branding.export-css") }}', '_blank');
        },
        
        showNotification(message, type) {
            // Implementar notificação toast
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    }
}

// Função para seleção de nó (fora do Alpine.js)
function selectNode(nodeId) {
    if (nodeId) {
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('node_id', nodeId);
        window.location.href = currentUrl.toString();
    } else {
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.delete('node_id');
        window.location.href = currentUrl.toString();
    }
}
</script>
            </div>
        </div>
    </div>
</body>
</html>
