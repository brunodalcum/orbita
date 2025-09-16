<x-dynamic-branding />
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contratos - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Branding Dinâmico -->
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .card { 
            background: white; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); 
            transition: all 0.3s ease; 
        }
        .card:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); 
        }
        .stat-card { 
            background: var(--primary-gradient);
            color: var(--primary-text);
        }
        .progress-bar { 
            background: var(--accent-gradient);
            transition: width 0.5s ease-in-out;
        }
        .dashboard-header {
            background: var(--background-color);
            color: var(--text-color);
        }
    
        /* Sobrescrever cores Tailwind específicas */
        .bg-primary, .bg-primary, .bg-primary-dark {
            background-color: var(--primary-color) !important;
        }
        .hover\:bg-primary:hover, .hover\:bg-primary-dark:hover {
            background-color: var(--primary-dark) !important;
        }
        .text-primary, .text-primary, .text-primary, .text-blue-800 {
            color: var(--primary-color) !important;
        }
        .border-primary, .hover\:border-primary:hover {
            border-color: var(--primary-color) !important;
        }
        .bg-blue-50, .bg-blue-100 {
            background-color: var(--primary-light) !important;
        }
        .hover\:bg-blue-100:hover, .hover\:bg-blue-200:hover {
            background-color: var(--primary-light) !important;
        }
        .border-b-2.border-primary {
            border-color: var(--primary-color) !important;
        }
    </style>

    <style>
        /* Força aplicação do branding nesta página específica */
        .bg-primary, .bg-primary, .bg-primary-dark { background-color: var(--primary-color) !important; }
        .text-primary, .text-primary, .text-primary { color: var(--primary-color) !important; }
        .border-primary, .border-primary, .border-primary { border-color: var(--primary-color) !important; }
        .hover\:bg-primary:hover, .hover\:bg-primary-dark:hover { background-color: var(--primary-dark) !important; }
    </style>
<style>
/* BRANDING FORÇADO PARA ESTA PÁGINA */
:root {
    --primary-color: var(--primary-color);
    --secondary-color: var(--secondary-color);
    --accent-color: var(--accent-color);
    --text-color: #1F2937;
    --background-color: #FFFFFF;
    --primary-light: rgba(var(--primary-color-rgb), 0.1);
    --primary-dark: var(--primary-dark);
    --primary-text: #FFFFFF;
}

/* Sobrescrita agressiva de todas as cores azuis */
.bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400,
.bg-primary, .bg-primary, .bg-primary-dark, .bg-blue-800, .bg-blue-900,
.bg-indigo-50, .bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400,
.bg-primary, .bg-primary, .bg-primary-dark, .bg-indigo-800, .bg-indigo-900 {
    background-color: var(--primary-color) !important;
}

.hover\:bg-primary:hover, .hover\:bg-primary-dark:hover, .hover\:bg-blue-800:hover,
.hover\:bg-primary:hover, .hover\:bg-primary-dark:hover, .hover\:bg-indigo-800:hover {
    background-color: var(--primary-dark) !important;
}

.text-primary, .text-primary, .text-primary, .text-blue-800, .text-blue-900,
.text-primary, .text-primary, .text-primary, .text-indigo-800, .text-indigo-900 {
    color: var(--primary-color) !important;
}

.border-primary, .border-primary, .border-primary, .border-blue-800, .border-blue-900,
.border-primary, .border-primary, .border-primary, .border-indigo-800, .border-indigo-900 {
    border-color: var(--primary-color) !important;
}

button[class*="blue"], .btn[class*="blue"], button[class*="indigo"], .btn[class*="indigo"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

button[class*="blue"]:hover, .btn[class*="blue"]:hover, button[class*="indigo"]:hover, .btn[class*="indigo"]:hover {
    background-color: var(--primary-dark) !important;
}

.bg-green-500, .bg-green-600, .bg-green-700 {
    background-color: var(--accent-color) !important;
}

.text-green-500, .text-green-600, .text-green-700, .text-green-800 {
    color: var(--accent-color) !important;
}

/* Sobrescrever estilos inline hardcoded */
[style*="background-color: var(--primary-color)"], [style*="background-color: var(--primary-color)"],
[style*="background-color: var(--primary-color)"], [style*="background-color: var(--primary-color)"] {
    background-color: var(--primary-color) !important;
}

[style*="color: var(--primary-color)"], [style*="color: var(--primary-color)"],
[style*="color: var(--primary-color)"], [style*="color: var(--primary-color)"] {
    color: var(--primary-color) !important;
}

.animate-spin[class*="border-blue"], .animate-spin[class*="border-indigo"] {
    border-color: var(--primary-color) transparent var(--primary-color) transparent !important;
}
</style>
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
                        <h1 class="text-2xl font-bold" style="color: var(--text-color);">
                            <i class="fas fa-file-contract mr-3" style="color: var(--primary-color);"></i>
                            Contratos de Licenciados
                        </h1>
                        <p class="mt-1" style="color: var(--secondary-color);">Gerencie todo o fluxo de contratos e documentação</p>
                    </div>
                    <a href="{{ route('contracts.create') }}" class="btn-primary px-4 py-2 rounded-lg transition-colors flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Novo Contrato
                    </a>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                    <div class="stat-card rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Criados</p>
                                <p class="text-2xl font-bold">{{ $statusStats['criado'] }}</p>
                            </div>
                            <i class="fas fa-plus text-white/60 text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Enviados</p>
                                <p class="text-2xl font-bold">{{ $statusStats['contrato_enviado'] }}</p>
                            </div>
                            <i class="fas fa-envelope text-white/60 text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Aguardando</p>
                                <p class="text-2xl font-bold">{{ $statusStats['aguardando_assinatura'] }}</p>
                            </div>
                            <i class="fas fa-clock text-white/60 text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Assinados</p>
                                <p class="text-2xl font-bold">{{ $statusStats['contrato_assinado'] }}</p>
                            </div>
                            <i class="fas fa-signature text-white/60 text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-600 to-green-700 rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Aprovados</p>
                                <p class="text-2xl font-bold">{{ $statusStats['licenciado_aprovado'] }}</p>
                            </div>
                            <i class="fas fa-check-circle text-white/60 text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Cancelados</p>
                                <p class="text-2xl font-bold">{{ $statusStats['cancelado'] }}</p>
                            </div>
                            <i class="fas fa-times text-white/60 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card rounded-xl p-6 mb-6">
                    <form method="GET" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-64">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pesquisar</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Nome ou email do licenciado..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="min-w-48">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Todos os status</option>
                                <option value="criado" {{ request('status') === 'criado' ? 'selected' : '' }}>Contrato Criado</option>
                                <option value="contrato_enviado" {{ request('status') === 'contrato_enviado' ? 'selected' : '' }}>Contrato Enviado</option>
                                <option value="aguardando_assinatura" {{ request('status') === 'aguardando_assinatura' ? 'selected' : '' }}>Aguardando Assinatura</option>
                                <option value="contrato_assinado" {{ request('status') === 'contrato_assinado' ? 'selected' : '' }}>Contrato Assinado</option>
                                <option value="licenciado_aprovado" {{ request('status') === 'licenciado_aprovado' ? 'selected' : '' }}>Licenciado Aprovado</option>
                                <option value="cancelado" {{ request('status') === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" style="background-color: var(--primary-color);" class="hover:bg-primary-dark text-white px-6 py-2 rounded-lg transition-colors">
                                <i class="fas fa-search mr-2"></i>Filtrar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Contracts List -->
                <div class="card rounded-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Licenciado</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progresso</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criado em</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($contracts as $contract)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fas fa-user text-white text-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $contract->licenciado->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $contract->licenciado->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $contract->status_color }}">
                                                {{ $contract->status_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                                                    <div class="progress-bar h-2 rounded-full" style="width: {{ $contract->progress_percentage }}%"></div>
                                                </div>
                                                <span class="text-sm text-gray-600">{{ $contract->progress_percentage }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $contract->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('contracts.show', $contract) }}" 
                                                   class="bg-blue-100 hover:bg-blue-200 text-primary px-3 py-1 rounded-lg text-sm transition-colors">
                                                    <i class="fas fa-eye mr-1"></i>Ver
                                                </a>
                                                
                                                <!-- Botão de Enviar por E-mail -->
                                                @if($contract->contract_pdf_path && file_exists(storage_path('app/' . $contract->contract_pdf_path)))
                                                    <button onclick="openEmailModal({{ $contract->id }}, '{{ $contract->licenciado->name }}', '{{ $contract->licenciado->email }}')" 
                                                            class="bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1 rounded-lg text-sm transition-colors">
                                                        <i class="fas fa-envelope mr-1"></i>E-mail
                                                    </button>
                                                @endif
                                                
                                                @if($contract->canApproveDocuments())
                                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-lg text-sm">
                                                        <i class="fas fa-clock mr-1"></i>Aguardando
                                                    </span>
                                                @elseif($contract->canSendContract())
                                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-sm">
                                                        <i class="fas fa-check mr-1"></i>Pronto
                                                    </span>
                                                @endif
                                                
                                                <!-- Botão de Excluir -->
                                                <button onclick="confirmDelete({{ $contract->id }}, '{{ $contract->licenciado->name }}')" 
                                                        class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1 rounded-lg text-sm transition-colors">
                                                    <i class="fas fa-trash mr-1"></i>Excluir
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-file-contract text-gray-300 text-6xl mb-4"></i>
                                                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum contrato encontrado</h3>
                                                <p class="text-gray-500 mb-4">Comece criando um novo contrato para um licenciado.</p>
                                                <a href="{{ route('contracts.create') }}" style="background-color: var(--primary-color);" class="hover:bg-primary-dark text-white px-4 py-2 rounded-lg transition-colors">
                                                    <i class="fas fa-plus mr-2"></i>Criar Primeiro Contrato
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($contracts->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $contracts->links() }}
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <!-- Modal de Envio por E-mail -->
    <div id="emailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl max-w-md w-full p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-envelope text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Enviar Contrato por E-mail</h3>
                </div>
                
                <div class="mb-4">
                    <p class="text-gray-600 mb-3">
                        Deseja enviar o contrato do licenciado <strong id="emailLicenseeName"></strong> para o e-mail:
                    </p>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-gray-400 mr-2"></i>
                            <span id="emailLicenseeEmail" class="font-medium text-gray-900"></span>
                        </div>
                    </div>
                </div>
                
                <div id="emailStatus" class="hidden mb-4">
                    <!-- Status será inserido dinamicamente -->
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button onclick="closeEmailModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                        Cancelar
                    </button>
                    <button id="sendEmailBtn" onclick="sendContractEmail()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Enviar E-mail
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl max-w-md w-full p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Confirmar Exclusão</h3>
                </div>
                
                <p class="text-gray-600 mb-6">
                    Tem certeza que deseja excluir o contrato do licenciado <strong id="contractLicensee"></strong>?
                    <br><br>
                    <span class="text-red-600 font-medium">Esta ação não pode ser desfeita e removerá todos os arquivos relacionados.</span>
                </p>
                
                <div class="flex justify-end space-x-3">
                    <button onclick="closeDeleteModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                        Cancelar
                    </button>
                    <button onclick="executeDelete()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-trash mr-2"></i>Excluir Contrato
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário oculto para DELETE -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        let contractToDelete = null;
        let contractToEmail = null;

        // Funções do Modal de E-mail
        function openEmailModal(contractId, licenseeName, licenseeEmail) {
            contractToEmail = contractId;
            document.getElementById('emailLicenseeName').textContent = licenseeName;
            document.getElementById('emailLicenseeEmail').textContent = licenseeEmail;
            document.getElementById('emailStatus').classList.add('hidden');
            document.getElementById('emailModal').classList.remove('hidden');
        }

        function closeEmailModal() {
            document.getElementById('emailModal').classList.add('hidden');
            contractToEmail = null;
            // Reset button state
            const btn = document.getElementById('sendEmailBtn');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Enviar E-mail';
        }

        function sendContractEmail() {
            if (!contractToEmail) return;

            const btn = document.getElementById('sendEmailBtn');
            const statusDiv = document.getElementById('emailStatus');
            
            // Desabilitar botão e mostrar loading
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
            
            // Fazer requisição AJAX
            fetch(`/contracts/${contractToEmail}/send-email`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                statusDiv.classList.remove('hidden');
                
                if (data.success) {
                    statusDiv.innerHTML = `
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>${data.message}</span>
                            </div>
                        </div>
                    `;
                    
                    // Fechar modal após 2 segundos
                    setTimeout(() => {
                        closeEmailModal();
                        location.reload(); // Recarregar para atualizar status
                    }, 2000);
                } else {
                    statusDiv.innerHTML = `
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span>${data.message || 'Erro ao enviar e-mail'}</span>
                            </div>
                        </div>
                    `;
                    
                    // Reabilitar botão
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Enviar E-mail';
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                statusDiv.classList.remove('hidden');
                statusDiv.innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span>Erro de conexão. Tente novamente.</span>
                        </div>
                    </div>
                `;
                
                // Reabilitar botão
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Enviar E-mail';
            });
        }

        // Funções do Modal de Exclusão
        function confirmDelete(contractId, licenseeName) {
            contractToDelete = contractId;
            document.getElementById('contractLicensee').textContent = licenseeName;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            contractToDelete = null;
        }

        function executeDelete() {
            if (contractToDelete) {
                const form = document.getElementById('deleteForm');
                form.action = `/contracts/${contractToDelete}`;
                form.submit();
            }
        }

        // Event Listeners
        document.getElementById('emailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEmailModal();
            }
        });

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEmailModal();
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>
