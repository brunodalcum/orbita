<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Planos - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: white;
            margin: 2% auto;
            padding: 0;
            border-radius: 12px;
            width: 95%;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 16px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            transform: translateX(400px);
            transition: transform 0.3s ease;
        }
        .toast.show {
            transform: translateX(0);
        }
        .toast.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .toast.error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        .confirm-modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .confirm-content {
            background-color: white;
            margin: 15% auto;
            padding: 32px;
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .plan-card {
            transition: all 0.3s ease;
        }
        .plan-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .step-indicator {
            text-align: center;
        }
        .step-indicator.active {
            color: white;
        }
        .step-content {
            min-height: 300px;
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
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Planos</h1>
                        <p class="text-gray-600">Gerencie os planos comerciais e taxas das operações</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-bell"></i>
                        </button>
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-cog"></i>
                        </button>
                        <form method="POST" action="{{ route('logout.custom') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Breadcrumb -->
                <nav class="flex mb-6" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                <i class="fas fa-home mr-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-sm font-medium text-gray-500">Planos</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Header da Lista -->
                <div class="bg-white rounded-xl shadow-sm border mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">Lista de Planos</h2>
                                <p class="text-sm text-gray-600">Total de {{ $planos->count() }} planos cadastrados</p>
                            </div>
                            <button 
                                onclick="openAddPlanModal()" 
                                class="btn-primary text-white px-6 py-3 rounded-lg font-medium flex items-center shadow-lg hover:shadow-xl"
                            >
                                <i class="fas fa-plus mr-2"></i>
                                Criar Plano
                            </button>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="bg-white rounded-xl shadow-sm border mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">Filtros</h3>
                            <button 
                                onclick="clearFilters()" 
                                class="text-sm text-gray-500 hover:text-gray-700 flex items-center"
                            >
                                <i class="fas fa-times mr-1"></i>
                                Limpar Filtros
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                            <!-- Filtro por Adquirente -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Adquirente</label>
                                <select 
                                    id="filterAdquirente" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onchange="applyFilters()"
                                >
                                    <option value="">Todos os adquirentes</option>
                                </select>
                            </div>

                            <!-- Filtro por Tipo de Taxa -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Taxa</label>
                                <select 
                                    id="filterTipoTaxa" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onchange="applyFilters()"
                                >
                                    <option value="">Todos os tipos</option>
                                    <option value="debito">Débito</option>
                                    <option value="credito_vista">Crédito à Vista</option>
                                    <option value="parcelado">Parcelado</option>
                                </select>
                            </div>

                            <!-- Filtro por Percentual de Taxa -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Taxa (%)</label>
                                <div class="flex space-x-2">
                                    <input 
                                        type="number" 
                                        id="filterTaxaMin" 
                                        placeholder="Mín" 
                                        step="0.01" 
                                        min="0" 
                                        max="100"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        onchange="applyFilters()"
                                    >
                                    <input 
                                        type="number" 
                                        id="filterTaxaMax" 
                                        placeholder="Máx" 
                                        step="0.01" 
                                        min="0" 
                                        max="100"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        onchange="applyFilters()"
                                    >
                                </div>
                            </div>

                            <!-- Filtro por Percentual de Comissão -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Comissão (%)</label>
                                <div class="flex space-x-2">
                                    <input 
                                        type="number" 
                                        id="filterComissaoMin" 
                                        placeholder="Mín" 
                                        step="0.01" 
                                        min="0" 
                                        max="100"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        onchange="applyFilters()"
                                    >
                                    <input 
                                        type="number" 
                                        id="filterComissaoMax" 
                                        placeholder="Máx" 
                                        step="0.01" 
                                        min="0" 
                                        max="100"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        onchange="applyFilters()"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Filtros Adicionais -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                            <!-- Filtro por Bandeira -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bandeira</label>
                                <select 
                                    id="filterBandeira" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onchange="applyFilters()"
                                >
                                    <option value="">Todas as bandeiras</option>
                                    <option value="visa_master">Visa/Master</option>
                                    <option value="elo">Elo</option>
                                    <option value="demais">Demais</option>
                                </select>
                            </div>

                            <!-- Filtro por Nome do Plano -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nome do Plano</label>
                                <input 
                                    type="text" 
                                    id="filterNome" 
                                    placeholder="Digite o nome do plano..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onkeyup="applyFilters()"
                                >
                            </div>

                            <!-- Filtro por Operação -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Operação</label>
                                <select 
                                    id="filterParceiro" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onchange="applyFilters()"
                                >
                                    <option value="">Todas as operações</option>
                                </select>
                            </div>
                        </div>

                        <!-- Resultados dos Filtros -->
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">
                                    <span id="filterResults">Mostrando todos os {{ $planos->count() }} planos</span>
                                </span>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-500">Ordenar por:</span>
                                    <select 
                                        id="sortBy" 
                                        class="text-sm border border-gray-300 rounded px-2 py-1"
                                        onchange="applyFilters()"
                                    >
                                        <option value="nome">Nome</option>
                                        <option value="operacao">Operação</option>
                                        <option value="adquirente">Adquirente</option>
                                        <option value="parceiro">Parceiro</option>
                                        <option value="comissao_media">Comissão Média</option>
                                    </select>
                                    <select 
                                        id="sortOrder" 
                                        class="text-sm border border-gray-300 rounded px-2 py-1"
                                        onchange="applyFilters()"
                                    >
                                        <option value="asc">Crescente</option>
                                        <option value="desc">Decrescente</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                                         <!-- Lista de Planos em Tabela -->
                     <div class="p-6">
                         @if($planos->count() > 0)
                             <div class="overflow-x-auto">
                                 <table id="planosTable" class="w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                                     <thead class="bg-gray-50 border-b border-gray-200">
                                         <tr>
                                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome do Plano</th>
                                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taxa Média</th>
                                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comissão Média</th>
                                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Criação</th>
                                             <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                         </tr>
                                     </thead>
                                     <tbody class="bg-white divide-y divide-gray-200">
                                         @foreach($planos as $plano)
                                             <tr class="hover:bg-gray-50 transition-colors duration-200 plan-row" 
                                                 data-id="{{ $plano->id }}"
                                                 data-nome="{{ strtolower($plano->nome) }}"
                                                 data-operacao="{{ $plano->operacao }}"
                                                 data-adquirente="{{ strtolower($plano->adquirente) }}"
                                                 data-parceiro="{{ strtolower($plano->parceiro) }}"
                                                 data-comissao-media="{{ $plano->comissao_media ?? 0 }}"
                                                 data-taxa-debito-visa="{{ $plano->taxas_detalhadas['debito']['visa_master'] ?? 0 }}"
                                                 data-taxa-debito-elo="{{ $plano->taxas_detalhadas['debito']['elo'] ?? 0 }}"
                                                 data-taxa-debito-demais="{{ $plano->taxas_detalhadas['debito']['demais'] ?? 0 }}"
                                                 data-taxa-credito-visa="{{ $plano->taxas_detalhadas['credito_vista']['visa_master'] ?? 0 }}"
                                                 data-taxa-credito-elo="{{ $plano->taxas_detalhadas['credito_vista']['elo'] ?? 0 }}"
                                                 data-taxa-credito-demais="{{ $plano->taxas_detalhadas['credito_vista']['demais'] ?? 0 }}"
                                                 data-comissao-debito-visa="{{ $plano->comissoes_detalhadas['debito']['visa_master'] ?? 0 }}"
                                                 data-comissao-debito-elo="{{ $plano->comissoes_detalhadas['debito']['elo'] ?? 0 }}"
                                                 data-comissao-debito-demais="{{ $plano->comissoes_detalhadas['debito']['demais'] ?? 0 }}"
                                                 data-comissao-credito-visa="{{ $plano->comissoes_detalhadas['credito_vista']['visa_master'] ?? 0 }}"
                                                 data-comissao-credito-elo="{{ $plano->comissoes_detalhadas['credito_vista']['elo'] ?? 0 }}"
                                                 data-comissao-credito-demais="{{ $plano->comissoes_detalhadas['credito_vista']['demais'] ?? 0 }}">
                                                 <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                     #{{ $plano->id }}
                                                 </td>
                                                 <td class="px-6 py-4 whitespace-nowrap">
                                                     <div class="flex items-center">
                                                         <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                                             <i class="fas fa-chart-line text-white text-sm"></i>
                                                         </div>
                                                         <div>
                                                             <div class="text-sm font-medium text-gray-900">{{ $plano->nome }}</div>
                                                         </div>
                                                     </div>
                                                 </td>
                                                 <td class="px-6 py-4">
                                                     <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $plano->descricao }}">
                                                         {{ Str::limit($plano->descricao, 50) ?: 'Sem descrição' }}
                                                     </div>
                                                 </td>
                                                 <td class="px-6 py-4 whitespace-nowrap">
                                                     <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                         {{ number_format($plano->taxa, 2) }}%
                                                     </span>
                                                 </td>
                                                 <td class="px-6 py-4 whitespace-nowrap">
                                                     <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                         {{ number_format($plano->comissao_media ?? 0, 2) }}%
                                                     </span>
                                                 </td>
                                                 <td class="px-6 py-4 whitespace-nowrap">
                                                     <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $plano->status === 'ativo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                         <i class="fas fa-circle mr-1 text-xs"></i>
                                                         {{ ucfirst($plano->status) }}
                                                     </span>
                                                 </td>
                                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                     {{ $plano->created_at->format('d/m/Y H:i') }}
                                                 </td>
                                                 <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                     <div class="flex items-center justify-center space-x-2">
                                                         <button 
                                                             onclick="viewPlan({{ $plano->id }})"
                                                             class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                                             title="Visualizar"
                                                         >
                                                             <i class="fas fa-eye mr-1"></i>
                                                             Visualizar
                                                         </button>
                                                         <button 
                                                             onclick="editPlan({{ $plano->id }})"
                                                             class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200"
                                                             title="Editar"
                                                         >
                                                             <i class="fas fa-edit mr-1"></i>
                                                             Editar
                                                         </button>
                                                         <button 
                                                             onclick="togglePlanStatus({{ $plano->id }}, '{{ $plano->status }}')"
                                                             class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md {{ $plano->status === 'ativo' ? 'text-red-700 bg-red-100 hover:bg-red-200' : 'text-green-700 bg-green-100 hover:bg-green-200' }} focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $plano->status === 'ativo' ? 'focus:ring-red-500' : 'focus:ring-green-500' }} transition-colors duration-200"
                                                             title="{{ $plano->status === 'ativo' ? 'Desativar' : 'Ativar' }}"
                                                         >
                                                             <i class="fas {{ $plano->status === 'ativo' ? 'fa-pause' : 'fa-play' }} mr-1"></i>
                                                             {{ $plano->status === 'ativo' ? 'Desativar' : 'Ativar' }}
                                                         </button>
                                                         <button 
                                                             onclick="deletePlan({{ $plano->id }})"
                                                             class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                                             title="Excluir"
                                                         >
                                                             <i class="fas fa-trash mr-1"></i>
                                                             Excluir
                                                         </button>
                                                     </div>
                                                 </td>
                                             </tr>
                                         @endforeach
                                     </tbody>
                                 </table>
                             </div>
                         @else
                             <div class="text-center py-12">
                                 <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                     <i class="fas fa-chart-line text-gray-400 text-2xl"></i>
                                 </div>
                                 <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum plano cadastrado</h3>
                                 <p class="text-gray-500 mb-6">Comece criando seu primeiro plano comercial.</p>
                                 <button 
                                     onclick="openAddPlanModal()" 
                                     class="btn-primary text-white px-6 py-3 rounded-lg font-medium flex items-center mx-auto"
                                 >
                                     <i class="fas fa-plus mr-2"></i>
                                     Criar Primeiro Plano
                                 </button>
                             </div>
                         @endif
                     </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add/Edit Plan Modal -->
    <div id="planModal" class="modal">
        <div class="modal-content">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-4 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium" id="modalTitle">Criar Plano</h3>
                    <button onclick="closePlanModal()" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Step Indicator -->
                <div class="mt-4">
                    <div class="flex items-center justify-center space-x-4">
                        <div class="step-indicator active" data-step="1">
                            <div class="w-8 h-8 bg-white text-blue-600 rounded-full flex items-center justify-center font-bold text-sm">1</div>
                            <span class="text-xs mt-1">Informações</span>
                        </div>
                        <div class="w-8 h-1 bg-white/30 rounded"></div>
                        <div class="step-indicator" data-step="2">
                            <div class="w-8 h-8 bg-white/30 text-white rounded-full flex items-center justify-center font-bold text-sm">2</div>
                            <span class="text-xs mt-1">Modalidade</span>
                        </div>
                        <div class="w-8 h-1 bg-white/30 rounded"></div>
                        <div class="step-indicator" data-step="3">
                            <div class="w-8 h-8 bg-white/30 text-white rounded-full flex items-center justify-center font-bold text-sm">3</div>
                            <span class="text-xs mt-1">Tipo</span>
                        </div>
                        <div class="w-8 h-1 bg-white/30 rounded"></div>
                        <div class="step-indicator" data-step="4">
                            <div class="w-8 h-8 bg-white/30 text-white rounded-full flex items-center justify-center font-bold text-sm">4</div>
                            <span class="text-xs mt-1">Taxas</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <form id="planForm" class="space-y-6">
                    @csrf
                    <input type="hidden" id="planId" name="plan_id">
                    
                    <!-- Step 1: Informações Básicas -->
                    <div id="step1" class="step-content">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Informações do Plano</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome do Plano *</label>
                                <input type="text" id="nome" name="nome" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="parceiro" class="block text-sm font-medium text-gray-700 mb-1">Parceiro *</label>
                                <select id="parceiro" name="parceiro" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Selecione um parceiro...</option>
                                    @foreach($operacoes as $operacao)
                                        <option value="{{ $operacao->nome }}">{{ $operacao->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label for="adquirente" class="block text-sm font-medium text-gray-700 mb-1">Adquirente *</label>
                                <select id="adquirente" name="adquirente" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Selecione um adquirente...</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Modalidade -->
                    <div id="step2" class="step-content hidden">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Modalidade de Pagamento</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Liquidação *</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="modalidade" value="D+1" class="mr-2 text-blue-600 focus:ring-blue-500" required>
                                        <span class="text-sm text-gray-700">D+1</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="modalidade" value="D30" class="mr-2 text-blue-600 focus:ring-blue-500" required>
                                        <span class="text-sm text-gray-700">D30</span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Parcelamento *</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="parcelamento" value="12" class="mr-2 text-blue-600 focus:ring-blue-500" required>
                                        <span class="text-sm text-gray-700">12x</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="parcelamento" value="18" class="mr-2 text-blue-600 focus:ring-blue-500" required>
                                        <span class="text-sm text-gray-700">18x</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="parcelamento" value="21" class="mr-2 text-blue-600 focus:ring-blue-500" required>
                                        <span class="text-sm text-gray-700">21x</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Tipo -->
                    <div id="step3" class="step-content hidden">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Tipo de Transação</h4>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="radio" name="tipo" value="Mundo físico" class="mr-3 text-blue-600 focus:ring-blue-500" required>
                                <span class="text-sm text-gray-700">Mundo físico</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="tipo" value="Online" class="mr-3 text-blue-600 focus:ring-blue-500" required>
                                <span class="text-sm text-gray-700">Online</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="tipo" value="Tap To Pay" class="mr-3 text-blue-600 focus:ring-blue-500" required>
                                <span class="text-sm text-gray-700">Tap To Pay</span>
                            </label>
                        </div>
                    </div>

                    <!-- Step 4: Taxas -->
                    <div id="step4" class="step-content hidden">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Tabela de Taxas</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full border border-gray-300 rounded-lg">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase border-b">Tipo</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase border-b">Visa e Master</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase border-b">Elo</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase border-b">Demais Bandeiras</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Débito à Vista -->
                                    <tr>
                                        <td class="px-3 py-2 text-sm font-medium text-gray-700 border-b">Débito à Vista</td>
                                        <td class="px-3 py-2 border-b">
                                            <input type="number" name="taxas[debito][visa_master]" step="0.01" min="0" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="0.00">
                                        </td>
                                        <td class="px-3 py-2 border-b">
                                            <input type="number" name="taxas[debito][elo]" step="0.01" min="0" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="0.00">
                                        </td>
                                        <td class="px-3 py-2 border-b">
                                            <input type="number" name="taxas[debito][demais]" step="0.01" min="0" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="0.00">
                                        </td>
                                    </tr>
                                    <!-- Crédito à Vista -->
                                    <tr>
                                        <td class="px-3 py-2 text-sm font-medium text-gray-700 border-b">Crédito à Vista</td>
                                        <td class="px-3 py-2 border-b">
                                            <input type="number" name="taxas[credito_vista][visa_master]" step="0.01" min="0" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="0.00">
                                        </td>
                                        <td class="px-3 py-2 border-b">
                                            <input type="number" name="taxas[credito_vista][elo]" step="0.01" min="0" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="0.00">
                                        </td>
                                        <td class="px-3 py-2 border-b">
                                            <input type="number" name="taxas[credito_vista][demais]" step="0.01" min="0" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="0.00">
                                        </td>
                                    </tr>
                                    <!-- Parcelado -->
                                    <tr id="parcelado-row">
                                        <td class="px-3 py-2 text-sm font-medium text-gray-700 border-b">Parcelado</td>
                                                                                 <td class="px-3 py-2 border-b">
                                             <div id="parcelado-visa_master" class="space-y-1">
                                                 <!-- Parcelas serão geradas dinamicamente -->
                                             </div>
                                         </td>
                                         <td class="px-3 py-2 border-b">
                                             <div id="parcelado-elo" class="space-y-1">
                                                 <!-- Parcelas serão geradas dinamicamente -->
                                             </div>
                                         </td>
                                         <td class="px-3 py-2 border-b">
                                             <div id="parcelado-demais" class="space-y-1">
                                                 <!-- Parcelas serão geradas dinamicamente -->
                                             </div>
                                         </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Seção de Comissões -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-percentage mr-2 text-green-600"></i>
                            Comissões do Licenciado
                        </h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Defina o percentual de comissão que o licenciado receberá para cada modalidade de transação.
                        </p>
                        
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase border-b">Modalidade</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase border-b">Visa/Master</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase border-b">Elo</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase border-b">Demais Bandeiras</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Comissões de Débito à Vista -->
                                    <tr>
                                        <td class="px-3 py-2 text-sm font-medium text-gray-700 border-b">Débito à Vista</td>
                                        <td class="px-3 py-2 border-b">
                                            <input type="number" name="comissoes[debito][visa_master]" step="0.01" min="0" max="100" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="0.00">
                                        </td>
                                        <td class="px-3 py-2 border-b">
                                            <input type="number" name="comissoes[debito][elo]" step="0.01" min="0" max="100" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="0.00">
                                        </td>
                                        <td class="px-3 py-2 border-b">
                                            <input type="number" name="comissoes[debito][demais]" step="0.01" min="0" max="100" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="0.00">
                                        </td>
                                    </tr>
                                    <!-- Comissões de Crédito à Vista -->
                                    <tr>
                                        <td class="px-3 py-2 text-sm font-medium text-gray-700 border-b">Crédito à Vista</td>
                                        <td class="px-3 py-2 border-b">
                                            <input type="number" name="comissoes[credito_vista][visa_master]" step="0.01" min="0" max="100" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="0.00">
                                        </td>
                                        <td class="px-3 py-2 border-b">
                                            <input type="number" name="comissoes[credito_vista][elo]" step="0.01" min="0" max="100" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="0.00">
                                        </td>
                                        <td class="px-3 py-2 border-b">
                                            <input type="number" name="comissoes[credito_vista][demais]" step="0.01" min="0" max="100" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="0.00">
                                        </td>
                                    </tr>
                                    <!-- Comissões Parceladas -->
                                    <tr id="comissoes-parcelado-row">
                                        <td class="px-3 py-2 text-sm font-medium text-gray-700 border-b">Parcelado</td>
                                        <td class="px-3 py-2 border-b">
                                            <div id="comissoes-parcelado-visa_master" class="space-y-1">
                                                <!-- Comissões parceladas serão geradas dinamicamente -->
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 border-b">
                                            <div id="comissoes-parcelado-elo" class="space-y-1">
                                                <!-- Comissões parceladas serão geradas dinamicamente -->
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 border-b">
                                            <div id="comissoes-parcelado-demais" class="space-y-1">
                                                <!-- Comissões parceladas serão geradas dinamicamente -->
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Navigation Buttons -->
                    <div class="flex justify-between pt-4 border-t">
                        <button type="button" id="prevBtn" onclick="previousStep()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors hidden">
                            <i class="fas fa-arrow-left mr-2"></i>Anterior
                        </button>
                        <div class="flex space-x-3">
                            <button type="button" onclick="resetPlanForm()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                                Limpar
                            </button>
                            <button type="button" id="nextBtn" onclick="nextStep()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                Próximo<i class="fas fa-arrow-right ml-2"></i>
                            </button>
                            <button type="submit" id="submitBtn" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors hidden">
                                <i class="fas fa-check mr-2"></i>Salvar Plano
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

         <!-- View Plan Modal -->
     <div id="viewPlanModal" class="modal">
         <div class="modal-content" style="max-width: 800px;">
             <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-4 rounded-t-lg">
                 <div class="flex items-center justify-between">
                     <h3 class="text-lg font-medium">Detalhes do Plano</h3>
                     <button onclick="closeViewPlanModal()" class="text-white hover:text-gray-200">
                         <i class="fas fa-times text-xl"></i>
                     </button>
                 </div>
             </div>
             <div class="p-6">
                 <div id="planDetails" class="space-y-6">
                     <!-- Detalhes serão carregados dinamicamente -->
                 </div>
             </div>
         </div>
     </div>

     <!-- Confirmation Modal -->
     <div id="confirmationModal" class="confirm-modal">
         <div class="confirm-content">
             <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                 <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
             </div>
             <h3 class="text-lg font-medium text-gray-900 mb-2" id="confirmationTitle">Confirmar Exclusão</h3>
             <p class="text-sm text-gray-500 mb-6" id="confirmationMessage">
                 Tem certeza que deseja excluir este plano? Esta ação não pode ser desfeita.
             </p>
             <div class="flex justify-center space-x-3">
                 <button onclick="closeConfirmationModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                     Cancelar
                 </button>
                 <button onclick="confirmDelete()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                     Excluir
                 </button>
             </div>
         </div>
     </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

    <script>
    let currentPlanId = null;
    let currentStep = 1;
    const totalSteps = 4;

    // ==================== SISTEMA DE FILTROS ====================
    
    // Carregar dados dos filtros ao inicializar a página
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM carregado, iniciando loadFilterData...');
        loadFilterData();
    });

    // Carregar dados para os filtros (adquirentes, parceiros, operações, etc.)
    async function loadFilterData() {
        try {
            console.log('Carregando dados dos filtros...');
            
            // Carregar adquirentes
            const adquirentesResponse = await fetch('/planos/adquirentes/list');
            const adquirentesData = await adquirentesResponse.json();
            console.log('Dados de adquirentes:', adquirentesData);
            
            const adquirenteSelect = document.getElementById('filterAdquirente');
            if (adquirentesData.success && adquirentesData.adquirentes) {
                adquirentesData.adquirentes.forEach(adquirente => {
                    const option = document.createElement('option');
                    option.value = adquirente.nome.toLowerCase();
                    option.textContent = adquirente.nome;
                    adquirenteSelect.appendChild(option);
                });
                console.log('Adquirentes carregados:', adquirentesData.adquirentes.length);
            }

            // Carregar operações
            const operacoesResponse = await fetch('/planos/operacoes/list');
            const operacoesData = await operacoesResponse.json();
            console.log('Dados de operações:', operacoesData);
            
            const operacaoSelect = document.getElementById('filterParceiro');
            if (operacoesData.success && operacoesData.operacoes) {
                operacoesData.operacoes.forEach(operacao => {
                    const option = document.createElement('option');
                    option.value = operacao.nome.toLowerCase();
                    option.textContent = operacao.nome;
                    operacaoSelect.appendChild(option);
                });
                console.log('Operações carregadas:', operacoesData.operacoes.length);
            }

        } catch (error) {
            console.error('Erro ao carregar dados dos filtros:', error);
            showToast('Erro ao carregar dados dos filtros: ' + error.message, 'error');
        }
    }

    // Aplicar filtros via AJAX
    async function applyFilters() {
        const adquirente = document.getElementById('filterAdquirente').value;
        const tipoTaxa = document.getElementById('filterTipoTaxa').value;
        const taxaMin = document.getElementById('filterTaxaMin').value;
        const taxaMax = document.getElementById('filterTaxaMax').value;
        const comissaoMin = document.getElementById('filterComissaoMin').value;
        const comissaoMax = document.getElementById('filterComissaoMax').value;
        const bandeira = document.getElementById('filterBandeira').value;
        const nome = document.getElementById('filterNome').value;
        const operacao = document.getElementById('filterParceiro').value;
        const sortBy = document.getElementById('sortBy').value;
        const sortOrder = document.getElementById('sortOrder').value;

        // Mostrar loading
        showLoading();

        try {
            const formData = new FormData();
            if (adquirente) formData.append('adquirente', adquirente);
            if (tipoTaxa) formData.append('tipo_taxa', tipoTaxa);
            if (taxaMin) formData.append('taxa_min', taxaMin);
            if (taxaMax) formData.append('taxa_max', taxaMax);
            if (comissaoMin) formData.append('comissao_min', comissaoMin);
            if (comissaoMax) formData.append('comissao_max', comissaoMax);
            if (bandeira) formData.append('bandeira', bandeira);
            if (nome) formData.append('nome', nome);
            if (operacao) formData.append('parceiro', operacao);
            if (sortBy) formData.append('sort_by', sortBy);
            if (sortOrder) formData.append('sort_order', sortOrder);

            const response = await fetch('/planos/filter', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                updateTableWithFilteredData(data.planos);
                updateFilterResults(data.total);
            } else {
                showToast('Erro ao filtrar planos: ' + data.message, 'error');
            }
        } catch (error) {
            console.error('Erro ao filtrar planos:', error);
            showToast('Erro ao filtrar planos: ' + error.message, 'error');
        } finally {
            hideLoading();
        }
    }

    // Atualizar tabela com dados filtrados
    function updateTableWithFilteredData(planos) {
        const tbody = document.querySelector('#planosTable tbody');
        tbody.innerHTML = '';

        planos.forEach(plano => {
            const row = createPlanRow(plano);
            tbody.appendChild(row);
        });
    }

    // Criar linha da tabela para um plano
    function createPlanRow(plano) {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50 transition-colors duration-200 plan-row';
        row.setAttribute('data-id', plano.id);
        row.setAttribute('data-nome', plano.nome.toLowerCase());
        row.setAttribute('data-operacao', plano.operacao || '');
        row.setAttribute('data-adquirente', (plano.adquirente || '').toLowerCase());
        row.setAttribute('data-parceiro', (plano.parceiro || '').toLowerCase());
        row.setAttribute('data-comissao-media', plano.comissao_media || 0);

        // Extrair taxas e comissões dos dados JSON
        const taxas = plano.taxas_detalhadas || {};
        const comissoes = plano.comissoes_detalhadas || {};

        // Adicionar atributos de taxa
        if (taxas.debito) {
            row.setAttribute('data-taxa-debito-visa', taxas.debito.visa_master || 0);
            row.setAttribute('data-taxa-debito-elo', taxas.debito.elo || 0);
            row.setAttribute('data-taxa-debito-demais', taxas.debito.demais || 0);
        }
        if (taxas.credito_vista) {
            row.setAttribute('data-taxa-credito-visa', taxas.credito_vista.visa_master || 0);
            row.setAttribute('data-taxa-credito-elo', taxas.credito_vista.elo || 0);
            row.setAttribute('data-taxa-credito-demais', taxas.credito_vista.demais || 0);
        }

        // Adicionar atributos de comissão
        if (comissoes.debito) {
            row.setAttribute('data-comissao-debito-visa', comissoes.debito.visa_master || 0);
            row.setAttribute('data-comissao-debito-elo', comissoes.debito.elo || 0);
            row.setAttribute('data-comissao-debito-demais', comissoes.debito.demais || 0);
        }
        if (comissoes.credito_vista) {
            row.setAttribute('data-comissao-credito-visa', comissoes.credito_vista.visa_master || 0);
            row.setAttribute('data-comissao-credito-elo', comissoes.credito_vista.elo || 0);
            row.setAttribute('data-comissao-credito-demais', comissoes.credito_vista.demais || 0);
        }

        // Criar conteúdo da linha
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                #${plano.id}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-line text-white text-sm"></i>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">${plano.nome}</div>
                        <div class="text-sm text-gray-500">${plano.status}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${plano.descricao || ''}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${plano.adquirente || ''}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${plano.parceiro || ''}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${plano.comissao_media ? plano.comissao_media + '%' : '0%'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${new Date(plano.created_at).toLocaleDateString('pt-BR')}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                <div class="flex items-center justify-center space-x-2">
                    <button onclick="viewPlan(${plano.id})" class="text-blue-600 hover:text-blue-900" title="Visualizar">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="editPlan(${plano.id})" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deletePlan(${plano.id})" class="text-red-600 hover:text-red-900" title="Excluir">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;

        return row;
    }

    // Mostrar loading
    function showLoading() {
        const tbody = document.querySelector('#planosTable tbody');
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-8 text-center">
                    <div class="flex items-center justify-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <span class="ml-2 text-gray-600">Carregando...</span>
                    </div>
                </td>
            </tr>
        `;
    }

    // Esconder loading
    function hideLoading() {
        // Loading será substituído pelos dados filtrados
    }

    // Atualizar contador de resultados
    function updateFilterResults(visibleCount) {
        const totalCount = document.querySelectorAll('.plan-row').length;
        const resultsElement = document.getElementById('filterResults');
        
        if (visibleCount === totalCount) {
            resultsElement.textContent = `Mostrando todos os ${totalCount} planos`;
        } else {
            resultsElement.textContent = `Mostrando ${visibleCount} de ${totalCount} planos`;
        }
    }

    // Limpar todos os filtros
    function clearFilters() {
        document.getElementById('filterAdquirente').value = '';
        document.getElementById('filterTipoTaxa').value = '';
        document.getElementById('filterTaxaMin').value = '';
        document.getElementById('filterTaxaMax').value = '';
        document.getElementById('filterComissaoMin').value = '';
        document.getElementById('filterComissaoMax').value = '';
        document.getElementById('filterBandeira').value = '';
        document.getElementById('filterNome').value = '';
        document.getElementById('filterParceiro').value = '';
        document.getElementById('sortBy').value = 'nome';
        document.getElementById('sortOrder').value = 'asc';
        
        // Recarregar todos os planos
        loadAllPlans();
    }

    // Carregar todos os planos
    async function loadAllPlans() {
        showLoading();
        
        try {
            const response = await fetch('/planos');
            const html = await response.text();
            
            // Extrair apenas o tbody da resposta
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTbody = doc.querySelector('#planosTable tbody');
            
            if (newTbody) {
                const currentTbody = document.querySelector('#planosTable tbody');
                currentTbody.innerHTML = newTbody.innerHTML;
                
                // Atualizar contador
                const totalCount = document.querySelectorAll('.plan-row').length;
                updateFilterResults(totalCount);
            }
        } catch (error) {
            console.error('Erro ao carregar planos:', error);
            showToast('Erro ao carregar planos: ' + error.message, 'error');
        }
    }

    // ==================== FIM SISTEMA DE FILTROS ====================

    function openAddPlanModal() {
        document.getElementById('modalTitle').textContent = 'Criar Plano';
        document.getElementById('planForm').reset();
        document.getElementById('planId').value = '';
        currentStep = 1;
        
        // Garantir que os indicadores de steps sejam mostrados no modo de criação
        document.querySelectorAll('.step-indicator').forEach(indicator => {
            indicator.style.display = 'flex';
        });
        
        // Restaurar validação required para modo de criação
        restoreRequiredValidation();
        
        showStep(1);
        document.getElementById('planModal').style.display = 'block';
        
        // Limpar campos de parcelamento
        const bandeiras = ['visa_master', 'elo', 'demais'];
        bandeiras.forEach(bandeira => {
            const container = document.getElementById(`parcelado-${bandeira}`);
            if (container) {
                container.innerHTML = '<p class="text-xs text-gray-500">Selecione o parcelamento no Step 2</p>';
            }
        });
        
        // Carregar operações e adquirentes dinamicamente
        loadOperacoes();
        loadAdquirentes();
        
        // Adicionar listeners para o Step 2
        addStep2Listeners();
        
        // Gerar campos de parcelamento se já estiver no Step 4
        setTimeout(() => {
            if (currentStep === 4) {
                generateParceladoFields();
                generateComissoesParceladoFields();
            }
        }, 100);
    }

    function closePlanModal() {
        document.getElementById('planModal').style.display = 'none';
    }

    function resetPlanForm() {
        document.getElementById('planForm').reset();
        document.getElementById('planId').value = '';
        currentStep = 1;
        showStep(1);
        
        // Limpar campos de parcelamento
        const bandeiras = ['visa_master', 'elo', 'demais'];
        bandeiras.forEach(bandeira => {
            const container = document.getElementById(`parcelado-${bandeira}`);
            if (container) {
                container.innerHTML = '<p class="text-xs text-gray-500">Selecione o parcelamento no Step 2</p>';
            }
        });
        
        // Recarregar operações e adquirentes
        loadOperacoes();
        loadAdquirentes();
    }

    function showStep(step) {
        // Hide all steps
        for (let i = 1; i <= totalSteps; i++) {
            document.getElementById(`step${i}`).classList.add('hidden');
        }
        
        // Show current step
        document.getElementById(`step${step}`).classList.remove('hidden');
        
        // Update step indicators
        document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
            const stepNumber = index + 1;
            const circle = indicator.querySelector('div');
            if (stepNumber <= step) {
                indicator.classList.add('active');
                circle.classList.remove('bg-white/30', 'text-white');
                circle.classList.add('bg-white', 'text-blue-600');
            } else {
                indicator.classList.remove('active');
                circle.classList.remove('bg-white', 'text-blue-600');
                circle.classList.add('bg-white/30', 'text-white');
            }
        });
        
        // Update navigation buttons
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');
        
        if (step === 1) {
            prevBtn.classList.add('hidden');
        } else {
            prevBtn.classList.remove('hidden');
        }
        
        if (step === totalSteps) {
            nextBtn.classList.add('hidden');
            submitBtn.classList.remove('hidden');
        } else {
            nextBtn.classList.remove('hidden');
            submitBtn.classList.add('hidden');
        }
    }

    function showEditMode() {
        // Hide all steps
        for (let i = 1; i <= totalSteps; i++) {
            document.getElementById(`step${i}`).classList.add('hidden');
        }
        
        // Show step 4 (taxas e comissões)
        document.getElementById('step4').classList.remove('hidden');
        
        // Ocultar indicadores de steps
        document.querySelectorAll('.step-indicator').forEach(indicator => {
            indicator.style.display = 'none';
        });
        
        // Ocultar botões de navegação
        document.getElementById('prevBtn').classList.add('hidden');
        document.getElementById('nextBtn').classList.add('hidden');
        
        // Mostrar apenas o botão de submit com texto de atualização
        document.getElementById('submitBtn').classList.remove('hidden');
        document.getElementById('submitBtn').textContent = 'Atualizar Plano';
        
        // Remover validação required dos campos não visíveis no modo de edição
        removeRequiredFromHiddenFields();
        
        // Garantir que os campos dinâmicos sejam visíveis
        setTimeout(() => {
            // Mostrar todos os containers de parcelamento
            const parceladoContainers = document.querySelectorAll('[id^="parcelado-"]');
            parceladoContainers.forEach(container => {
                container.style.display = 'block';
                container.style.visibility = 'visible';
                console.log('Container parcelado visível:', container.id);
            });
            
            // Mostrar todos os containers de comissões
            const comissoesContainers = document.querySelectorAll('[id^="comissoes-parcelado-"]');
            comissoesContainers.forEach(container => {
                container.style.display = 'block';
                container.style.visibility = 'visible';
                console.log('Container comissão visível:', container.id);
            });
            
            // Forçar visibilidade de todos os campos de input
            const allInputs = document.querySelectorAll('#step4 input[type="number"]');
            allInputs.forEach(input => {
                input.style.display = 'block';
                input.style.visibility = 'visible';
            });
            
            console.log('Total de inputs visíveis:', allInputs.length);
        }, 100);
    }

    function removeRequiredFromHiddenFields() {
        // Lista de campos que estão nos steps ocultos (1, 2, 3) e não devem ser obrigatórios no modo de edição
        const hiddenFields = [
            'nome',           // Step 1
            'parceiro',       // Step 1
            'adquirente',     // Step 1
            'modalidade',     // Step 2
            'parcelamento',   // Step 2
            'tipo'            // Step 2
        ];
        
        hiddenFields.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.removeAttribute('required');
                console.log(`Removido required do campo: ${fieldName}`);
            }
        });
        
        // Também remover required dos radio buttons
        const radioGroups = ['modalidade', 'parcelamento', 'tipo'];
        radioGroups.forEach(groupName => {
            const radios = document.querySelectorAll(`input[name="${groupName}"]`);
            radios.forEach(radio => {
                radio.removeAttribute('required');
            });
        });
    }

    function restoreRequiredValidation() {
        // Lista de campos que devem ser obrigatórios no modo de criação
        const requiredFields = [
            'nome',           // Step 1
            'parceiro',       // Step 1
            'adquirente',     // Step 1
            'modalidade',     // Step 2
            'parcelamento',   // Step 2
            'tipo'            // Step 2
        ];
        
        requiredFields.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.setAttribute('required', 'required');
                console.log(`Restaurado required no campo: ${fieldName}`);
            }
        });
        
        // Também restaurar required dos radio buttons
        const radioGroups = ['modalidade', 'parcelamento', 'tipo'];
        radioGroups.forEach(groupName => {
            const radios = document.querySelectorAll(`input[name="${groupName}"]`);
            radios.forEach(radio => {
                radio.setAttribute('required', 'required');
            });
        });
    }

    function nextStep() {
        if (validateCurrentStep()) {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
                
                // If moving to step 4, generate parcelado fields based on step 2 selection
                if (currentStep === 4) {
                    setTimeout(() => {
                        generateParceladoFields();
                generateComissoesParceladoFields();
                    }, 100);
                }
            }
        }
    }

    function previousStep() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
            
            // Se voltou para o Step 4, regenerar os campos de parcelamento
            if (currentStep === 4) {
                setTimeout(() => {
                    generateParceladoFields();
                generateComissoesParceladoFields();
                }, 100);
            }
        }
    }

    function validateCurrentStep() {
        const currentStepElement = document.getElementById(`step${currentStep}`);
        
        switch (currentStep) {
            case 1:
                const nome = document.getElementById('nome').value.trim();
                const parceiro = document.getElementById('parceiro').value.trim();
                const adquirente = document.getElementById('adquirente').value.trim();
                
                if (!nome || !parceiro || !adquirente) {
                    showToast('Por favor, preencha todos os campos obrigatórios do Step 1', 'error');
                    return false;
                }
                break;
                
            case 2:
                const modalidade = document.querySelector('input[name="modalidade"]:checked');
                const parcelamento = document.querySelector('input[name="parcelamento"]:checked');
                
                if (!modalidade || !parcelamento) {
                    showToast('Por favor, selecione a modalidade e o parcelamento', 'error');
                    return false;
                }
                break;
                
            case 3:
                const tipo = document.querySelector('input[name="tipo"]:checked');
                
                if (!tipo) {
                    showToast('Por favor, selecione o tipo de transação', 'error');
                    return false;
                }
                break;
        }
        
        return true;
    }

    function generateParceladoFields(parcelamentoValue = null) {
        let maxParcelas;
        
        if (parcelamentoValue) {
            // Se um valor foi passado como parâmetro, usar ele
            maxParcelas = parseInt(parcelamentoValue);
            console.log('generateParceladoFields chamada com valor:', parcelamentoValue);
        } else {
            // Caso contrário, procurar pelo radio button selecionado
        const parcelamento = document.querySelector('input[name="parcelamento"]:checked');
            console.log('generateParceladoFields chamada, parcelamento:', parcelamento);
            
        if (!parcelamento) {
                console.log('Nenhum parcelamento selecionado');
            // Se não há seleção, limpar os campos
            const bandeiras = ['visa_master', 'elo', 'demais'];
            bandeiras.forEach(bandeira => {
                const container = document.getElementById(`parcelado-${bandeira}`);
                if (container) {
                    container.innerHTML = '<p class="text-xs text-gray-500">Selecione o parcelamento no Step 2</p>';
                }
            });
            return;
        }
        
            maxParcelas = parseInt(parcelamento.value);
        }
        console.log('Gerando campos para até', maxParcelas, 'parcelas');
        const bandeiras = ['visa_master', 'elo', 'demais'];
        
        bandeiras.forEach(bandeira => {
            const container = document.getElementById(`parcelado-${bandeira}`);
            if (!container) {
                console.log(`Container não encontrado para ${bandeira}`);
                return;
            }
            
            container.innerHTML = '';
            console.log(`Gerando campos para ${bandeira}`);
            
            for (let i = 2; i <= maxParcelas; i++) {
                const div = document.createElement('div');
                div.className = 'flex items-center space-x-2 mb-1';
                div.innerHTML = `
                    <span class="text-xs text-gray-600 w-8 font-medium">${i}x</span>
                    <input type="number" name="taxas[parcelado][${bandeira}][${i}]" step="0.01" min="0" max="100"
                           class="flex-1 px-2 py-1 border border-gray-300 rounded text-xs" placeholder="0.00">
                    <span class="text-xs text-gray-500">%</span>
                `;
                container.appendChild(div);
                console.log(`Campo gerado: taxas[parcelado][${bandeira}][${i}]`);
            }
            console.log(`Campos gerados para ${bandeira}: 2x até ${maxParcelas}x`);
        });
    }

    // Função para gerar campos de comissão parcelada
    function generateComissoesParceladoFields(parcelamentoValue = null) {
        let maxParcelas;
        
        if (parcelamentoValue) {
            maxParcelas = parseInt(parcelamentoValue);
            console.log('generateComissoesParceladoFields chamada com valor:', parcelamentoValue);
        } else {
            const parcelamento = document.querySelector('input[name="parcelamento"]:checked');
            console.log('generateComissoesParceladoFields chamada, parcelamento:', parcelamento);
            
            if (!parcelamento) {
                console.log('Nenhum parcelamento selecionado para comissões');
                const bandeiras = ['visa_master', 'elo', 'demais'];
                bandeiras.forEach(bandeira => {
                    const container = document.getElementById(`comissoes-parcelado-${bandeira}`);
                    if (container) {
                        container.innerHTML = '<p class="text-xs text-gray-500">Selecione o parcelamento no Step 2</p>';
                    }
                });
                return;
            }
            
            maxParcelas = parseInt(parcelamento.value);
        }
        
        console.log('Gerando campos de comissão para até', maxParcelas, 'parcelas');
        const bandeiras = ['visa_master', 'elo', 'demais'];
        
        bandeiras.forEach(bandeira => {
            const container = document.getElementById(`comissoes-parcelado-${bandeira}`);
            if (!container) {
                console.log(`Container de comissão não encontrado para ${bandeira}`);
                return;
            }
            
            container.innerHTML = '';
            console.log(`Gerando campos de comissão para ${bandeira}`);
            
            for (let i = 2; i <= maxParcelas; i++) {
                const div = document.createElement('div');
                div.className = 'flex items-center space-x-2 mb-1';
                div.innerHTML = `
                    <span class="text-xs text-gray-600 w-8 font-medium">${i}x</span>
                    <input type="number" name="comissoes[parcelado][${bandeira}][${i}]" step="0.01" min="0" max="100"
                           class="flex-1 px-2 py-1 border border-gray-300 rounded text-xs" placeholder="0.00">
                    <span class="text-xs text-gray-500">%</span>
                `;
                container.appendChild(div);
                console.log(`Campo de comissão gerado: comissoes[parcelado][${bandeira}][${i}]`);
            }
            console.log(`Campos de comissão gerados para ${bandeira}: 2x até ${maxParcelas}x`);
        });
    }

    // Adicionar listeners para mudanças no Step 2
    function addStep2Listeners() {
        const parcelamentoInputs = document.querySelectorAll('input[name="parcelamento"]');
        parcelamentoInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (currentStep === 4) {
                    generateParceladoFields();
                generateComissoesParceladoFields();
                    generateComissoesParceladoFields();
                }
            });
        });
    }

    function viewPlan(planId) {
        fetch(`/planos/${planId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const plan = data.plano;
                const detailsContainer = document.getElementById('planDetails');
                
                // Decodificar dados do plano se existirem
                let planData = {};
                if (plan.descricao && plan.descricao.includes(' - ')) {
                    const parts = plan.descricao.split(' - ');
                    if (parts.length >= 6) {
                        planData = {
                            parceiro: parts[1] || 'N/A',
                            adquirente: parts[2] || 'N/A',
                            modalidade: parts[3] || 'N/A',
                            parcelamento: parts[4] || 'N/A',
                            tipo: parts[5] || 'N/A'
                        };
                    }
                }
                
                detailsContainer.innerHTML = `
                    <div class="space-y-6">
                        <!-- Informações Básicas -->
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                Informações Básicas
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Nome do Plano:</span>
                                    <p class="text-sm text-gray-900 font-semibold">${plan.nome}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Taxa Média:</span>
                                    <p class="text-sm font-bold text-green-600">${parseFloat(plan.taxa).toFixed(2)}%</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Status:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${plan.status === 'ativo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                        <i class="fas fa-circle mr-1 text-xs"></i>
                                        ${plan.status.charAt(0).toUpperCase() + plan.status.slice(1)}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">ID do Plano:</span>
                                    <p class="text-sm text-gray-900">#${plan.id}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Detalhes do Plano -->
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-cogs text-purple-500 mr-2"></i>
                                Configurações do Plano
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Parceiro:</span>
                                    <p class="text-sm text-gray-900">${planData.parceiro || 'Não informado'}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Adquirente:</span>
                                    <p class="text-sm text-gray-900">${planData.adquirente || 'Não informado'}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Modalidade:</span>
                                    <p class="text-sm text-gray-900">${planData.modalidade || 'Não informado'}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Parcelamento:</span>
                                    <p class="text-sm text-gray-900">${planData.parcelamento || 'Não informado'}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Tipo de Transação:</span>
                                    <p class="text-sm text-gray-900">${planData.tipo || 'Não informado'}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Operações Vinculadas -->
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-link text-indigo-500 mr-2"></i>
                                Operações Vinculadas
                            </h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                ${plan.operacoes_nomes && plan.operacoes_nomes.length > 0 ? 
                                    plan.operacoes_nomes.map(op => `<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mr-2 mb-2">${op}</span>`).join('') :
                                    '<p class="text-sm text-gray-500 italic">Nenhuma operação vinculada a este plano</p>'
                                }
                            </div>
                        </div>

                        <!-- Datas -->
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-calendar-alt text-orange-500 mr-2"></i>
                                Informações de Data
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Criado em:</span>
                                    <p class="text-sm text-gray-900">${new Date(plan.created_at).toLocaleDateString('pt-BR')} às ${new Date(plan.created_at).toLocaleTimeString('pt-BR')}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Última atualização:</span>
                                    <p class="text-sm text-gray-900">${new Date(plan.updated_at).toLocaleDateString('pt-BR')} às ${new Date(plan.updated_at).toLocaleTimeString('pt-BR')}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Taxas Detalhadas -->
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-percentage text-green-500 mr-2"></i>
                                Taxas Detalhadas
                            </h4>
                            ${plan.taxas_detalhadas ? generateTaxasTable(plan.taxas_detalhadas) : '<p class="text-sm text-gray-500 italic">Nenhuma taxa detalhada disponível</p>'}
                        </div>

                        <!-- Comissões do Licenciado -->
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-hand-holding-usd text-blue-500 mr-2"></i>
                                Comissões do Licenciado
                            </h4>
                            <div class="mb-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-600">Comissão Média:</span>
                                    <span class="text-lg font-bold text-blue-600">${parseFloat(plan.comissao_media || 0).toFixed(2)}%</span>
                                </div>
                            </div>
                            ${plan.comissoes_detalhadas ? generateComissoesTable(plan.comissoes_detalhadas) : '<p class="text-sm text-gray-500 italic">Nenhuma comissão definida para este plano</p>'}
                        </div>

                        <!-- Ações -->
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-tools text-gray-500 mr-2"></i>
                                Ações Disponíveis
                            </h4>
                            <div class="flex flex-wrap gap-3">
                                <button onclick="editPlan(${plan.id})" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                                    <i class="fas fa-edit mr-2"></i>
                                    Editar Plano
                                </button>
                                <button onclick="togglePlanStatus(${plan.id}, '${plan.status}')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md ${plan.status === 'ativo' ? 'text-red-700 bg-red-100 hover:bg-red-200' : 'text-green-700 bg-green-100 hover:bg-green-200'} focus:outline-none focus:ring-2 focus:ring-offset-2 ${plan.status === 'ativo' ? 'focus:ring-red-500' : 'focus:ring-green-500'} transition-colors duration-200">
                                    <i class="fas ${plan.status === 'ativo' ? 'fa-pause' : 'fa-play'} mr-2"></i>
                                    ${plan.status === 'ativo' ? 'Desativar' : 'Ativar'} Plano
                                </button>
                                <button onclick="deletePlan(${plan.id})" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                    <i class="fas fa-trash mr-2"></i>
                                    Excluir Plano
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                document.getElementById('viewPlanModal').style.display = 'block';
            } else {
                showToast('Erro ao carregar detalhes do plano', 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar plano:', error);
            showToast('Erro ao carregar detalhes do plano', 'error');
        });
    }

    function closeViewPlanModal() {
        document.getElementById('viewPlanModal').style.display = 'none';
    }

    function editPlan(planId) {
        console.log('Editando plano ID:', planId);
        
        // Fechar modal de visualização se estiver aberto
        closeViewPlanModal();
        
        // Carregar dados do plano usando endpoint de edição
        fetch(`/planos/${planId}/edit`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const plan = data.plano;
                const planData = data.planData || {};
                console.log('Dados do plano carregados:', plan);
                console.log('Dados decodificados do backend:', planData);
                
                // Preencher formulário com dados do plano
                document.getElementById('modalTitle').textContent = 'Editar Plano';
                document.getElementById('planId').value = plan.id;
                document.getElementById('nome').value = plan.nome;
                document.getElementById('adquirente').value = planData.adquirente || '';
                
                console.log('Preenchendo campos básicos:', {
                    id: plan.id,
                    nome: plan.nome,
                    adquirente: planData.adquirente,
                    parceiro: planData.parceiro
                });
                
                // Selecionar parceiro
                const parceiroSelect = document.getElementById('parceiro');
                parceiroSelect.value = planData.parceiro || '';
                
                // Função para selecionar campos com delay
                setTimeout(() => {
                    console.log('Selecionando campos de radio button:', planData);
                    
                    // Selecionar modalidade
                    if (planData.modalidade) {
                        const modalidadeRadio = document.querySelector(`input[name="modalidade"][value="${planData.modalidade}"]`);
                        console.log('Modalidade radio:', modalidadeRadio);
                        if (modalidadeRadio) {
                            modalidadeRadio.checked = true;
                            console.log('Modalidade selecionada:', planData.modalidade);
                        }
                    }
                    
                    // Selecionar parcelamento
                    if (planData.parcelamento) {
                        const parcelamentoRadio = document.querySelector(`input[name="parcelamento"][value="${planData.parcelamento}"]`);
                        console.log('Parcelamento radio:', parcelamentoRadio);
                        if (parcelamentoRadio) {
                            parcelamentoRadio.checked = true;
                            console.log('Parcelamento selecionado:', planData.parcelamento);
                        }
                    }
                    
                    // Selecionar tipo
                    if (planData.tipo) {
                        const tipoRadio = document.querySelector(`input[name="tipo"][value="${planData.tipo}"]`);
                        console.log('Tipo radio:', tipoRadio);
                        if (tipoRadio) {
                            tipoRadio.checked = true;
                            console.log('Tipo selecionado:', planData.tipo);
                        }
                    }
                }, 100);
                
                // Preencher taxas se existirem
                if (plan.taxas_detalhadas) {
                    console.log('Preenchendo taxas detalhadas:', plan.taxas_detalhadas);
                    fillTaxasFields(plan.taxas_detalhadas, plan);
                } else {
                    console.log('Nenhuma taxa detalhada encontrada');
                }
                
                // Carregar operações e adquirentes primeiro
                loadOperacoes();
                loadAdquirentes();
                
                // Adicionar listeners
                addStep2Listeners();
                
                // Abrir modal primeiro
                currentStep = 4;
                showEditMode();
                document.getElementById('planModal').style.display = 'block';
                
                // Gerar campos de parcelamento APÓS abrir o modal
                setTimeout(() => {
                    // Detectar parcelamento baseado nos dados existentes
                    let maxParcelas = 12; // Default
                    
                    if (plan.taxas_detalhadas && plan.taxas_detalhadas.parcelado) {
                        // Encontrar o maior número de parcelas nos dados existentes
                        const bandeiras = ['visa_master', 'elo', 'demais'];
                        bandeiras.forEach(bandeira => {
                            if (plan.taxas_detalhadas.parcelado[bandeira]) {
                                const parcelas = Object.keys(plan.taxas_detalhadas.parcelado[bandeira]).map(Number);
                                const maxBandeira = Math.max(...parcelas);
                                if (maxBandeira > maxParcelas) {
                                    maxParcelas = maxBandeira;
                                }
                            }
                        });
                        console.log('Parcelamento detectado automaticamente:', maxParcelas);
                    } else if (planData.parcelamento) {
                        maxParcelas = parseInt(planData.parcelamento);
                        console.log('Parcelamento do campo:', maxParcelas);
                    } else {
                        console.log('Usando parcelamento padrão:', maxParcelas);
                    }
                    
                    // Forçar geração dos campos com o parcelamento detectado
                    generateParceladoFields(maxParcelas);
                    generateComissoesParceladoFields(maxParcelas);
                    
                    // Garantir que os containers sejam visíveis
                    const bandeiras = ['visa_master', 'elo', 'demais'];
                    bandeiras.forEach(bandeira => {
                        const container = document.getElementById(`parcelado-${bandeira}`);
                        if (container) {
                            container.style.display = 'block';
                            container.style.visibility = 'visible';
                        }
                        
                        const comissaoContainer = document.getElementById(`comissoes-parcelado-${bandeira}`);
                        if (comissaoContainer) {
                            comissaoContainer.style.display = 'block';
                            comissaoContainer.style.visibility = 'visible';
                        }
                    });
                    
                    // Preencher taxas parceladas após gerar os campos
                    setTimeout(() => {
                        console.log('Verificando taxas detalhadas:', plan.taxas_detalhadas);
                        if (plan.taxas_detalhadas && plan.taxas_detalhadas.parcelado) {
                            console.log('Preenchendo taxas parceladas:', plan.taxas_detalhadas.parcelado);
                            fillParceladoTaxas(plan.taxas_detalhadas.parcelado);
                        } else {
                            console.log('Nenhuma taxa parcelada encontrada');
                        }
                    }, 200);

                    // Preencher comissões parceladas após gerar os campos
                    setTimeout(() => {
                        console.log('Verificando comissões detalhadas:', plan.comissoes_detalhadas);
                        if (plan.comissoes_detalhadas && plan.comissoes_detalhadas.parcelado) {
                            console.log('Preenchendo comissões parceladas:', plan.comissoes_detalhadas.parcelado);
                            fillParceladoComissoes(plan.comissoes_detalhadas.parcelado);
                        } else {
                            console.log('Nenhuma comissão parcelada encontrada');
                        }
                    }, 300);
                }, 100);
                
            } else {
                showToast('Erro ao carregar dados do plano', 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar plano:', error);
            showToast('Erro ao carregar dados do plano', 'error');
        });
    }

    function togglePlanStatus(planId, currentStatus) {
        const newStatus = currentStatus === 'ativo' ? 'inativo' : 'ativo';
        const actionText = currentStatus === 'ativo' ? 'desativar' : 'ativar';
        
        if (confirm(`Tem certeza que deseja ${actionText} este plano?`)) {
            fetch(`/planos/${planId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erro ao alterar status:', error);
                showToast('Erro ao alterar status do plano', 'error');
            });
        }
    }

    function deletePlan(planId) {
        currentPlanId = planId;
        document.getElementById('confirmationModal').style.display = 'block';
    }

    function closeConfirmationModal() {
        document.getElementById('confirmationModal').style.display = 'none';
        currentPlanId = null;
    }

    function confirmDelete() {
        if (!currentPlanId) return;
        
        fetch(`/planos/${currentPlanId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            closeConfirmationModal();
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao excluir plano:', error);
            showToast('Erro ao excluir plano', 'error');
            closeConfirmationModal();
        });
    }

    document.getElementById('planForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Coletar todos os dados do formulário
        const formData = new FormData(this);
        
        // Adicionar dados dos steps
        const modalidade = document.querySelector('input[name="modalidade"]:checked');
        const parcelamento = document.querySelector('input[name="parcelamento"]:checked');
        const tipo = document.querySelector('input[name="tipo"]:checked');
        
        if (modalidade) formData.append('modalidade', modalidade.value);
        if (parcelamento) formData.append('parcelamento', parcelamento.value);
        if (tipo) formData.append('tipo', tipo.value);
        
        // Determinar se é criação ou edição
        const planId = document.getElementById('planId').value;
        const isEdit = planId && planId !== '';
        
        // Configurar URL e método
        const url = isEdit ? `/planos/${planId}` : '/planos';
        const method = isEdit ? 'POST' : 'POST'; // Laravel simula PUT via POST
        
        // Adicionar _method para simular PUT
        if (isEdit) {
            formData.append('_method', 'PUT');
        }
        
        // Debug: mostrar dados sendo enviados
        console.log('=== DEBUG FORM SUBMISSION ===');
        console.log('URL:', url);
        console.log('Method:', method);
        console.log('Is Edit:', isEdit);
        console.log('Plan ID:', planId);
        
        // Mostrar todos os dados do FormData
        console.log('FormData contents:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}:`, value);
        }
        
        // Enviar para o servidor
        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
        })
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                closePlanModal();
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao salvar plano:', error);
            showToast(`Erro ao salvar plano: ${error.message}`, 'error');
        });
    });

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.className = `toast ${type}`;
        toast.classList.add('show');
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

         // Fechar modais ao clicar fora (exceto o modal de planos)
     window.onclick = function(event) {
         const planModal = document.getElementById('planModal');
         const viewPlanModal = document.getElementById('viewPlanModal');
         const confirmationModal = document.getElementById('confirmationModal');
         
         // Modal de planos não fecha ao clicar fora - apenas pelo botão X
         if (event.target === viewPlanModal) {
             closeViewPlanModal();
         }
         if (event.target === confirmationModal) {
             closeConfirmationModal();
         }
     }

    function generateTaxasTable(taxas) {
        if (!taxas || typeof taxas !== 'object') {
            return '<p class="text-sm text-gray-500 italic">Nenhuma taxa detalhada disponível</p>';
        }
        
        let html = '<div class="space-y-4">';
        
        // Taxas de Débito
        if (taxas.debito && Object.keys(taxas.debito).length > 0) {
            html += `
                <div class="bg-gray-50 rounded-lg p-4">
                    <h5 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-credit-card text-blue-500 mr-2"></i>
                        Taxas de Débito
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            `;
            Object.entries(taxas.debito).forEach(([bandeira, taxa]) => {
                const bandeiraNome = getBandeiraNome(bandeira);
                html += `
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">${bandeiraNome}</span>
                            <span class="text-sm font-bold text-green-600">${parseFloat(taxa).toFixed(2)}%</span>
                        </div>
                    </div>
                `;
            });
            html += '</div></div>';
        }
        
        // Taxas de Crédito à Vista
        if (taxas.credito_vista && Object.keys(taxas.credito_vista).length > 0) {
            html += `
                <div class="bg-gray-50 rounded-lg p-4">
                    <h5 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-credit-card text-green-500 mr-2"></i>
                        Taxas de Crédito à Vista
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            `;
            Object.entries(taxas.credito_vista).forEach(([bandeira, taxa]) => {
                const bandeiraNome = getBandeiraNome(bandeira);
                html += `
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">${bandeiraNome}</span>
                            <span class="text-sm font-bold text-green-600">${parseFloat(taxa).toFixed(2)}%</span>
                        </div>
                    </div>
                `;
            });
            html += '</div></div>';
        }
        
        // Taxas de Crédito Parcelado
        if (taxas.parcelado && Object.keys(taxas.parcelado).length > 0) {
            html += `
                <div class="bg-gray-50 rounded-lg p-4">
                    <h5 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-credit-card text-purple-500 mr-2"></i>
                        Taxas de Crédito Parcelado
                    </h5>
            `;
            Object.entries(taxas.parcelado).forEach(([bandeira, parcelas]) => {
                const bandeiraNome = getBandeiraNome(bandeira);
                html += `
                    <div class="mb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-2">${bandeiraNome}</h6>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                `;
                Object.entries(parcelas).forEach(([parcela, taxa]) => {
                    html += `
                        <div class="bg-white rounded-lg p-2 border border-gray-200">
                            <div class="text-center">
                                <div class="text-xs text-gray-500">${parcela}x</div>
                                <div class="text-sm font-bold text-green-600">${parseFloat(taxa).toFixed(2)}%</div>
                            </div>
                        </div>
                    `;
                });
                html += '</div></div>';
            });
            html += '</div>';
        }
        
        html += '</div>';
        return html;
    }

    function generateComissoesTable(comissoes) {
        if (!comissoes || typeof comissoes !== 'object') {
            return '<p class="text-sm text-gray-500 italic">Nenhuma comissão detalhada disponível</p>';
        }
        
        let html = '<div class="space-y-4">';
        
        // Comissões de Débito
        if (comissoes.debito && Object.keys(comissoes.debito).length > 0) {
            html += `
                <div class="bg-blue-50 rounded-lg p-4">
                    <h5 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-credit-card text-blue-500 mr-2"></i>
                        Comissões de Débito
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            `;
            Object.entries(comissoes.debito).forEach(([bandeira, comissao]) => {
                const bandeiraNome = getBandeiraNome(bandeira);
                html += `
                    <div class="bg-white rounded-lg p-3 border border-blue-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">${bandeiraNome}</span>
                            <span class="text-sm font-bold text-blue-600">${parseFloat(comissao).toFixed(2)}%</span>
                        </div>
                    </div>
                `;
            });
            html += '</div></div>';
        }
        
        // Comissões de Crédito à Vista
        if (comissoes.credito_vista && Object.keys(comissoes.credito_vista).length > 0) {
            html += `
                <div class="bg-blue-50 rounded-lg p-4">
                    <h5 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-credit-card text-green-500 mr-2"></i>
                        Comissões de Crédito à Vista
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            `;
            Object.entries(comissoes.credito_vista).forEach(([bandeira, comissao]) => {
                const bandeiraNome = getBandeiraNome(bandeira);
                html += `
                    <div class="bg-white rounded-lg p-3 border border-blue-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">${bandeiraNome}</span>
                            <span class="text-sm font-bold text-blue-600">${parseFloat(comissao).toFixed(2)}%</span>
                        </div>
                    </div>
                `;
            });
            html += '</div></div>';
        }
        
        // Comissões de Crédito Parcelado
        if (comissoes.parcelado && Object.keys(comissoes.parcelado).length > 0) {
            html += `
                <div class="bg-blue-50 rounded-lg p-4">
                    <h5 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-credit-card text-purple-500 mr-2"></i>
                        Comissões de Crédito Parcelado
                    </h5>
            `;
            Object.entries(comissoes.parcelado).forEach(([bandeira, parcelas]) => {
                const bandeiraNome = getBandeiraNome(bandeira);
                html += `
                    <div class="mb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-2">${bandeiraNome}</h6>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                `;
                Object.entries(parcelas).forEach(([parcela, comissao]) => {
                    html += `
                        <div class="bg-white rounded-lg p-2 border border-blue-200">
                            <div class="text-center">
                                <div class="text-xs text-gray-500">${parcela}x</div>
                                <div class="text-sm font-bold text-blue-600">${parseFloat(comissao).toFixed(2)}%</div>
                            </div>
                        </div>
                    `;
                });
                html += '</div></div>';
            });
            html += '</div>';
        }
        
        html += '</div>';
        return html;
    }

    function getBandeiraNome(bandeira) {
        const nomes = {
            'visa_master': 'Visa e Master',
            'elo': 'Elo',
            'demais': 'Demais Bandeiras'
        };
        return nomes[bandeira] || bandeira;
    }

    function loadOperacoes() {
        fetch('/planos/operacoes/list', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const parceiroSelect = document.getElementById('parceiro');
                if (parceiroSelect) {
                    // Salvar valor atual
                    const currentValue = parceiroSelect.value;
                    
                    // Limpar opções existentes (exceto a primeira)
                    parceiroSelect.innerHTML = '<option value="">Selecione um parceiro...</option>';
                    
                    // Adicionar operações
                    data.operacoes.forEach(operacao => {
                        const option = document.createElement('option');
                        option.value = operacao.nome;
                        option.textContent = operacao.nome;
                        parceiroSelect.appendChild(option);
                    });
                    
                    // Restaurar valor se existir
                    if (currentValue) {
                        parceiroSelect.value = currentValue;
                    }
                }
            }
        })
        .catch(error => {
            console.error('Erro ao carregar operações:', error);
        });
    }

    function loadAdquirentes() {
        fetch('/planos/adquirentes/list', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const adquirenteSelect = document.getElementById('adquirente');
                if (adquirenteSelect) {
                    // Salvar valor atual
                    const currentValue = adquirenteSelect.value;
                    
                    // Limpar opções existentes (exceto a primeira)
                    adquirenteSelect.innerHTML = '<option value="">Selecione um adquirente...</option>';
                    
                    // Adicionar adquirentes
                    data.adquirentes.forEach(adquirente => {
                        const option = document.createElement('option');
                        option.value = adquirente.nome;
                        option.textContent = adquirente.nome;
                        adquirenteSelect.appendChild(option);
                    });
                    
                    // Restaurar valor se existir
                    if (currentValue) {
                        adquirenteSelect.value = currentValue;
                    }
                }
            }
        })
        .catch(error => {
            console.error('Erro ao carregar adquirentes:', error);
        });
    }

    function fillTaxasFields(taxas, plan) {
        // Preencher taxas de débito
        if (taxas.debito) {
            Object.entries(taxas.debito).forEach(([bandeira, valor]) => {
                const input = document.querySelector(`input[name="taxas[debito][${bandeira}]"]`);
                if (input) input.value = valor;
            });
        }
        
        // Preencher taxas de crédito à vista
        if (taxas.credito_vista) {
            Object.entries(taxas.credito_vista).forEach(([bandeira, valor]) => {
                const input = document.querySelector(`input[name="taxas[credito_vista][${bandeira}]"]`);
                if (input) input.value = valor;
            });
        }

        // Preencher comissões de débito
        if (plan && plan.comissoes_detalhadas && plan.comissoes_detalhadas.debito) {
            Object.entries(plan.comissoes_detalhadas.debito).forEach(([bandeira, valor]) => {
                const input = document.querySelector(`input[name="comissoes[debito][${bandeira}]"]`);
                if (input) input.value = valor;
            });
        }
        
        // Preencher comissões de crédito à vista
        if (plan && plan.comissoes_detalhadas && plan.comissoes_detalhadas.credito_vista) {
            Object.entries(plan.comissoes_detalhadas.credito_vista).forEach(([bandeira, valor]) => {
                const input = document.querySelector(`input[name="comissoes[credito_vista][${bandeira}]"]`);
                if (input) input.value = valor;
            });
        }
    }

    function fillParceladoTaxas(parceladoTaxas) {
        console.log('Iniciando preenchimento de taxas parceladas');
        console.log('Dados recebidos:', parceladoTaxas);
        
        // Função para tentar preencher os campos
        const tryFillFields = (attempt = 1) => {
            let filledCount = 0;
            let totalFields = 0;
            
            // Verificar se os containers existem
            const containers = ['visa_master', 'elo', 'demais'];
            containers.forEach(bandeira => {
                const container = document.getElementById(`parcelado-${bandeira}`);
                console.log(`Container ${bandeira}:`, container);
            });
            
            Object.entries(parceladoTaxas).forEach(([bandeira, parcelas]) => {
                console.log(`Processando bandeira: ${bandeira}`);
                Object.entries(parcelas).forEach(([parcela, valor]) => {
                    totalFields++;
                    const selector = `input[name="taxas[parcelado][${bandeira}][${parcela}]"]`;
                    const input = document.querySelector(selector);
                    console.log(`Tentativa ${attempt} - Procurando campo: ${selector}`);
                    if (input) {
                        input.value = valor;
                        filledCount++;
                        console.log(`Preenchido: ${bandeira} ${parcela}x = ${valor}%`);
                    } else {
                        console.log(`Campo não encontrado: ${selector}`);
                        // Listar todos os campos disponíveis para debug
                        const allInputs = document.querySelectorAll('input[name*="taxas[parcelado]"]');
                        console.log(`Campos disponíveis (${allInputs.length}):`, Array.from(allInputs).map(input => input.name));
                    }
                });
            });
            
            console.log(`Tentativa ${attempt}: ${filledCount}/${totalFields} campos preenchidos`);
            
            // Se não preencheu todos os campos e ainda não tentou 5 vezes, tentar novamente
            if (filledCount < totalFields && attempt < 5) {
                setTimeout(() => tryFillFields(attempt + 1), 200);
            } else if (filledCount < totalFields) {
                console.log('Não foi possível preencher todos os campos após 5 tentativas');
            } else {
                console.log('Todos os campos foram preenchidos com sucesso!');
            }
        };
        
        // Iniciar tentativas
        tryFillFields();
     }

    // Função para preencher comissões parceladas
    function fillParceladoComissoes(parceladoComissoes) {
        console.log('Iniciando preenchimento de comissões parceladas');
        console.log('Dados recebidos:', parceladoComissoes);
        
        // Função para tentar preencher os campos
        const tryFillFields = (attempt = 1) => {
            let filledCount = 0;
            let totalFields = 0;
            
            // Verificar se os containers existem
            const containers = ['visa_master', 'elo', 'demais'];
            containers.forEach(bandeira => {
                const container = document.getElementById(`comissoes-parcelado-${bandeira}`);
                console.log(`Container de comissão ${bandeira}:`, container);
            });
            
            Object.entries(parceladoComissoes).forEach(([bandeira, parcelas]) => {
                console.log(`Processando comissões da bandeira: ${bandeira}`);
                Object.entries(parcelas).forEach(([parcela, valor]) => {
                    totalFields++;
                    const selector = `input[name="comissoes[parcelado][${bandeira}][${parcela}]"]`;
                    const input = document.querySelector(selector);
                    console.log(`Tentativa ${attempt} - Procurando campo de comissão: ${selector}`);
                    if (input) {
                        input.value = valor;
                        filledCount++;
                        console.log(`Comissão preenchida: ${bandeira} ${parcela}x = ${valor}%`);
                    } else {
                        console.log(`Campo de comissão não encontrado: ${selector}`);
                        // Listar todos os campos de comissão disponíveis para debug
                        const allInputs = document.querySelectorAll('input[name*="comissoes[parcelado]"]');
                        console.log(`Campos de comissão disponíveis (${allInputs.length}):`, Array.from(allInputs).map(input => input.name));
                    }
                });
            });
            
            console.log(`Tentativa ${attempt} de comissões: ${filledCount}/${totalFields} campos preenchidos`);
            
            // Se não preencheu todos os campos e ainda não tentou 5 vezes, tentar novamente
            if (filledCount < totalFields && attempt < 5) {
                setTimeout(() => tryFillFields(attempt + 1), 200);
            } else if (filledCount < totalFields) {
                console.log('Não foi possível preencher todos os campos de comissão após 5 tentativas');
            } else {
                console.log('Todos os campos de comissão foram preenchidos com sucesso!');
            }
        };
        
        // Iniciar tentativas
        tryFillFields();
     }
    </script>
</body>
</html>
