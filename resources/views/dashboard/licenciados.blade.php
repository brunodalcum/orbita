<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Licenciados - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar {
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        }
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .stat-card-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .stat-card-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .stat-card-warning {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        .sidebar-link {
            transition: all 0.3s ease;
        }
        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
                 .sidebar-link.active {
             background: rgba(255, 255, 255, 0.2);
             border-left: 4px solid white;
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
             width: 90%;
             max-width: 800px;
             max-height: 90vh;
             overflow-y: auto;
         }
         .step {
             display: none;
         }
         .step.active {
             display: block;
         }
         .step-indicator {
             display: flex;
             justify-content: center;
             margin-bottom: 2rem;
             padding: 1rem;
             background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
             border-radius: 12px 12px 0 0;
         }
         .step-dot {
             width: 40px;
             height: 40px;
             border-radius: 50%;
             background: rgba(255, 255, 255, 0.3);
             color: white;
             display: flex;
             align-items: center;
             justify-content: center;
             margin: 0 1rem;
             font-weight: bold;
             transition: all 0.3s ease;
         }
         .step-dot.active {
             background: white;
             color: #667eea;
         }
         .step-dot.completed {
             background: #10b981;
             color: white;
         }
         .step-line {
             width: 60px;
             height: 2px;
             background: rgba(255, 255, 255, 0.3);
             margin: auto 0;
         }
         .step-line.completed {
             background: #10b981;
         }
         
         .operation-card.selected {
             border-width: 2px;
             box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
         }
         
         .operation-card.selected[data-operation="pagseguro"] {
             border-color: #f59e0b;
             background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
         }
         
         .operation-card.selected[data-operation="adiq"] {
             border-color: #3b82f6;
             background: linear-gradient(135deg, #dbeafe 0%, #c7d2fe 100%);
         }
         
         .operation-card.selected[data-operation="confrapag"] {
             border-color: #8b5cf6;
             background: linear-gradient(135deg, #f3e8ff 0%, #fce7f3 100%);
         }
         
                   .operation-card.selected[data-operation="mercadopago"] {
              border-color: #10b981;
              background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
          }
          
          /* Animações para o modal de sucesso */
          @keyframes bounce {
              0%, 20%, 53%, 80%, 100% {
                  transform: translate3d(0,0,0);
              }
              40%, 43% {
                  transform: translate3d(0, -30px, 0);
              }
              70% {
                  transform: translate3d(0, -15px, 0);
              }
              90% {
                  transform: translate3d(0, -4px, 0);
              }
          }
          
          .animate-bounce {
              animation: bounce 1s infinite;
          }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar w-64 flex-shrink-0">
            <div class="p-6">
                <!-- Logo -->
                <div class="flex items-center mb-8">
                    <img src="{{ asset('images/dspay-logo.png') }}" alt="dspay" class="h-10 w-auto mx-auto">
                </div>

                <!-- Menu -->
                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('dashboard.licenciados') }}" class="sidebar-link active flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-id-card mr-3"></i>
                        Licenciados
                    </a>
                    <a href="{{ route('dashboard.operacoes') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-cogs mr-3"></i>
                        Operações
                    </a>
                    <a href="{{ route('dashboard.planos') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-chart-line mr-3"></i>
                        Planos
                    </a>
                    <a href="{{ route('dashboard.adquirentes') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-building mr-3"></i>
                        Adquirentes
                    </a>
                    <a href="{{ route('dashboard.agenda') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-calendar-alt mr-3"></i>
                        Agenda
                    </a>
                    <a href="{{ route('dashboard.leads') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-user-plus mr-3"></i>
                        Leads
                    </a>
                    <a href="{{ route('dashboard.configuracoes') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg">
                        <i class="fas fa-cog mr-3"></i>
                        Configurações
                    </a>
                </nav>
            </div>

            <!-- User Profile -->
            <div class="absolute bottom-0 w-64 p-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-white font-medium">{{ Auth::user()->name ?? 'Usuário' }}</p>
                        <p class="text-white/70 text-sm">Administrador</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Licenciados</h1>
                        <p class="text-gray-600">Gerenciamento de licenciados do sistema</p>
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
                                 <!-- Stats Cards -->
                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                     <div class="stat-card card rounded-xl p-4 text-white">
                         <div class="flex items-center justify-between">
                             <div>
                                 <p class="text-white/80 text-sm">Total de Licenciados</p>
                                 <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                                 <p class="text-white/80 text-xs mt-1">
                                     <i class="fas fa-arrow-up mr-1"></i>
                                     +8% este mês
                                 </p>
                             </div>
                             <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                 <i class="fas fa-id-card text-xl"></i>
                             </div>
                         </div>
                     </div>
 
                     <div class="stat-card-secondary card rounded-xl p-4 text-white">
                         <div class="flex items-center justify-between">
                             <div>
                                 <p class="text-white/80 text-sm">Aprovados</p>
                                 <p class="text-2xl font-bold">{{ $stats['aprovados'] }}</p>
                                 <p class="text-white/80 text-xs mt-1">
                                     <i class="fas fa-arrow-up mr-1"></i>
                                     +5% este mês
                                 </p>
                             </div>
                             <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                 <i class="fas fa-check-circle text-xl"></i>
                             </div>
                         </div>
                     </div>
  
                     <div class="stat-card-success card rounded-xl p-4 text-white">
                         <div class="flex items-center justify-between">
                             <div>
                                 <p class="text-white/80 text-sm">Em Análise</p>
                                 <p class="text-2xl font-bold">{{ $stats['em_analise'] }}</p>
                                 <p class="text-white/80 text-xs mt-1">
                                     <i class="fas fa-clock mr-1"></i>
                                     Aguardando
                                 </p>
                             </div>
                             <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                 <i class="fas fa-clock text-xl"></i>
                             </div>
                         </div>
                     </div>
  
                     <div class="stat-card-warning card rounded-xl p-4 text-white">
                         <div class="flex items-center justify-between">
                             <div>
                                 <p class="text-white/80 text-sm">Recusados</p>
                                 <p class="text-2xl font-bold">{{ $stats['recusados'] }}</p>
                                 <p class="text-white/80 text-xs mt-1">
                                     <i class="fas fa-times-circle mr-1"></i>
                                     Não aprovados
                                 </p>
                             </div>
                             <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                 <i class="fas fa-times text-xl"></i>
                             </div>
                         </div>
                     </div>
                 </div>

                 <!-- Filtros -->
                 <div class="card rounded-xl p-6 mb-6">
                     <div class="flex items-center justify-between mb-4">
                         <div>
                             <div class="flex items-center">
                                 <h3 class="text-lg font-bold text-gray-800">Filtros</h3>
                                 @if(request()->hasAny(['data_inicial', 'data_final', 'status', 'operacao', 'estado']))
                                     <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                         <i class="fas fa-filter mr-1"></i>
                                         Ativos
                                     </span>
                                 @endif
                             </div>
                             <p class="text-gray-600 text-sm mt-1">Filtre os licenciados por diferentes critérios</p>
                         </div>
                         <button onclick="limparFiltros()" class="text-gray-500 hover:text-gray-700 text-sm flex items-center">
                             <i class="fas fa-times mr-1"></i>
                             Limpar Filtros
                         </button>
                     </div>
                     
                     <form id="filtroForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                         <!-- Data de Cadastro -->
                         <div class="space-y-2">
                             <label class="block text-sm font-semibold text-gray-700">
                                 <i class="fas fa-calendar-alt mr-1 text-blue-500"></i>
                                 Data de Cadastro
                             </label>
                             <div class="grid grid-cols-2 gap-2">
                                 <input type="date" id="data_inicial" name="data_inicial" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                 <input type="date" id="data_final" name="data_final" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                             </div>
                         </div>
                         
                         <!-- Status -->
                         <div class="space-y-2">
                             <label class="block text-sm font-semibold text-gray-700">
                                 <i class="fas fa-info-circle mr-1 text-green-500"></i>
                                 Status
                             </label>
                             <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                                 <option value="">Todos os Status</option>
                                 <option value="aprovado">Aprovado</option>
                                 <option value="recusado">Recusado</option>
                                 <option value="em_analise">Em Análise</option>
                                 <option value="risco">Em Risco</option>
                             </select>
                         </div>
                         
                         <!-- Operação -->
                         <div class="space-y-2">
                             <label class="block text-sm font-semibold text-gray-700">
                                 <i class="fas fa-credit-card mr-1 text-purple-500"></i>
                                 Por Operação
                             </label>
                             <select id="operacao" name="operacao" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                                 <option value="">Todas as Operações</option>
                                 @foreach($operacoes as $operacao)
                                     <option value="{{ $operacao->id }}">{{ $operacao->nome }} ({{ $operacao->adquirente }})</option>
                                 @endforeach
                             </select>
                         </div>
                         
                         <!-- Estado -->
                         <div class="space-y-2">
                             <label class="block text-sm font-semibold text-gray-700">
                                 <i class="fas fa-map-marker-alt mr-1 text-red-500"></i>
                                 Por Estado
                             </label>
                             <select id="estado" name="estado" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm">
                                 <option value="">Todos os Estados</option>
                                 <option value="AC">Acre</option>
                                 <option value="AL">Alagoas</option>
                                 <option value="AP">Amapá</option>
                                 <option value="AM">Amazonas</option>
                                 <option value="BA">Bahia</option>
                                 <option value="CE">Ceará</option>
                                 <option value="DF">Distrito Federal</option>
                                 <option value="ES">Espírito Santo</option>
                                 <option value="GO">Goiás</option>
                                 <option value="MA">Maranhão</option>
                                 <option value="MT">Mato Grosso</option>
                                 <option value="MS">Mato Grosso do Sul</option>
                                 <option value="MG">Minas Gerais</option>
                                 <option value="PA">Pará</option>
                                 <option value="PB">Paraíba</option>
                                 <option value="PR">Paraná</option>
                                 <option value="PE">Pernambuco</option>
                                 <option value="PI">Piauí</option>
                                 <option value="RJ">Rio de Janeiro</option>
                                 <option value="RN">Rio Grande do Norte</option>
                                 <option value="RS">Rio Grande do Sul</option>
                                 <option value="RO">Rondônia</option>
                                 <option value="RR">Roraima</option>
                                 <option value="SC">Santa Catarina</option>
                                 <option value="SP">São Paulo</option>
                                 <option value="SE">Sergipe</option>
                                 <option value="TO">Tocantins</option>
                             </select>
                         </div>
                     </form>
                     
                     <div class="flex justify-end mt-4">
                         <button onclick="aplicarFiltros()" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                             <i class="fas fa-filter mr-2"></i>
                             Aplicar Filtros
                         </button>
                     </div>
                 </div>

                                 <!-- Licenciados Table -->
                 <div class="card rounded-xl p-6">
                     <div class="flex items-center justify-between mb-6">
                         <div>
                             <h3 class="text-xl font-bold text-gray-800">Lista de Licenciados</h3>
                             <p class="text-gray-600 text-sm mt-1">
                                 @if(request()->hasAny(['data_inicial', 'data_final', 'status', 'operacao', 'estado']))
                                     Mostrando {{ $licenciados->count() }} resultado{{ $licenciados->count() !== 1 ? 's' : '' }} com filtros aplicados
                                 @else
                                     Gerencie todos os licenciados do sistema
                                 @endif
                             </p>
                         </div>
                         <button onclick="openModal()" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                             <i class="fas fa-plus mr-2"></i>
                             Novo Licenciado
                         </button>
                     </div>
 
                     <div class="overflow-x-auto">
                         @if($licenciados->count() > 0)
                             <table class="w-full">
                                 <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                     <tr>
                                         <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                             <div class="flex items-center">
                                                 <i class="fas fa-building mr-2 text-gray-400"></i>
                                                 Licenciado
                                             </div>
                                         </th>
                                         <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                             <div class="flex items-center">
                                                 <i class="fas fa-id-card mr-2 text-gray-400"></i>
                                                 CNPJ/CPF
                                             </div>
                                         </th>
                                         <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                             <div class="flex items-center">
                                                 <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                                 Localização
                                             </div>
                                         </th>
                                         <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                             <div class="flex items-center">
                                                 <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                                 Contato
                                             </div>
                                         </th>
                                         <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                             <div class="flex items-center">
                                                 <i class="fas fa-credit-card mr-2 text-gray-400"></i>
                                                 Operações
                                             </div>
                                         </th>
                                         <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                             <div class="flex items-center">
                                                 <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                                 Data de Cadastro
                                             </div>
                                         </th>
                                         <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                             <div class="flex items-center">
                                                 <i class="fas fa-info-circle mr-2 text-gray-400"></i>
                                                 Status
                                             </div>
                                         </th>
                                         <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                             <div class="flex items-center">
                                                 <i class="fas fa-cogs mr-2 text-gray-400"></i>
                                                 Ações
                                             </div>
                                         </th>
                                     </tr>
                                 </thead>
                                 <tbody class="bg-white divide-y divide-gray-100">
                                     @foreach($licenciados as $licenciado)
                                         <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200" data-id="{{ $licenciado->id }}" data-status="{{ $licenciado->status }}">
                                             <td class="px-6 py-4 whitespace-nowrap">
                                                 <div class="flex items-center">
                                                     <div class="w-10 h-10 {{ $licenciado->tipo_pessoa === 'Pessoa Física' ? 'bg-gradient-to-br from-blue-500 to-blue-600' : 'bg-gradient-to-br from-purple-500 to-purple-600' }} rounded-full flex items-center justify-center mr-3 shadow-lg">
                                                         <i class="fas {{ $licenciado->tipo_pessoa === 'Pessoa Física' ? 'fa-user text-white' : 'fa-building text-white' }} text-sm"></i>
                                                     </div>
                                                     <div>
                                                         <div class="text-sm font-semibold text-gray-900">{{ $licenciado->razao_social }}</div>
                                                         @if($licenciado->nome_fantasia)
                                                             <div class="text-sm text-gray-500">{{ $licenciado->nome_fantasia }}</div>
                                                         @endif
                                                         <div class="text-xs text-gray-400">{{ $licenciado->tipo_pessoa }}</div>
                                                     </div>
                                                 </div>
                                             </td>
                                             <td class="px-6 py-4 whitespace-nowrap">
                                                 <div class="text-sm text-gray-900 font-medium">{{ $licenciado->cnpj_cpf_formatado }}</div>
                                             </td>
                                             <td class="px-6 py-4 whitespace-nowrap">
                                                 <div class="text-sm text-gray-900">{{ $licenciado->cidade }}/{{ $licenciado->estado }}</div>
                                                 <div class="text-xs text-gray-500">{{ $licenciado->bairro }}</div>
                                             </td>
                                             <td class="px-6 py-4 whitespace-nowrap">
                                                 <div class="text-sm text-gray-900">{{ $licenciado->email ?? 'N/A' }}</div>
                                                 <div class="text-xs text-gray-500">{{ $licenciado->telefone_formatado ?? 'N/A' }}</div>
                                             </td>
                                             <td class="px-6 py-4 whitespace-nowrap">
                                                 <div class="flex flex-wrap gap-1">
                                                     @forelse($licenciado->operacoes_selecionadas as $operacao)
                                                         <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                             <i class="fas fa-check mr-1"></i>{{ $operacao }}
                                                         </span>
                                                     @empty
                                                         <span class="text-gray-400 text-xs">Nenhuma operação</span>
                                                     @endforelse
                                                 </div>
                                             </td>
                                             <td class="px-6 py-4 whitespace-nowrap">
                                                 <div class="text-sm text-gray-900">{{ $licenciado->data_cadastro_formatada }}</div>
                                             </td>
                                             <td class="px-6 py-4 whitespace-nowrap">
                                                 {!! $licenciado->status_badge !!}
                                             </td>
                                             <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                 <div class="flex items-center space-x-2">
                                                                                                           <!-- Alterar Status -->
                                                      <div class="relative">
                                                          <button onclick="toggleStatusMenu({{ $licenciado->id }})" class="bg-blue-50 hover:bg-blue-100 text-blue-700 p-2 rounded-md transition-colors" title="Alterar Status">
                                                              <i class="fas fa-toggle-on"></i>
                                                          </button>
                                                         
                                                         <div id="statusMenu{{ $licenciado->id }}" class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-200 z-10 hidden">
                                                             <div class="py-1">
                                                                 <button onclick="alterarStatus({{ $licenciado->id }}, 'aprovado')" class="w-full text-left px-3 py-2 text-xs hover:bg-green-50 text-green-700">
                                                                     <i class="fas fa-check-circle mr-2"></i>Aprovado
                                                                 </button>
                                                                 <button onclick="alterarStatus({{ $licenciado->id }}, 'recusado')" class="w-full text-left px-3 py-2 text-xs hover:bg-red-50 text-red-700">
                                                                     <i class="fas fa-times-circle mr-2"></i>Recusado
                                                                 </button>
                                                                 <button onclick="alterarStatus({{ $licenciado->id }}, 'em_analise')" class="w-full text-left px-3 py-2 text-xs hover:bg-yellow-50 text-yellow-700">
                                                                     <i class="fas fa-clock mr-2"></i>Em Análise
                                                                 </button>
                                                                 <button onclick="alterarStatus({{ $licenciado->id }}, 'risco')" class="w-full text-left px-3 py-2 text-xs hover:bg-orange-50 text-orange-700">
                                                                     <i class="fas fa-exclamation-triangle mr-2"></i>Risco
                                                                 </button>
                                                             </div>
                                                         </div>
                                                     </div>
                                                     
                                                     <!-- Follow-up -->
                                                     <button onclick="abrirFollowUp({{ $licenciado->id }})" class="bg-purple-50 hover:bg-purple-100 text-purple-700 p-2 rounded-md transition-colors" title="Follow-up">
                                                         <i class="fas fa-comments"></i>
                                                     </button>
                                                     
                                                                                             <!-- Visualizar -->
                                        <button onclick="visualizarLicenciado({{ $licenciado->id }})" class="bg-green-50 hover:bg-green-100 text-green-700 p-2 rounded-md transition-colors" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                                                                 <!-- Editar -->
                                         <button onclick="editarLicenciado({{ $licenciado->id }})" class="bg-gray-50 hover:bg-gray-100 text-gray-700 p-2 rounded-md transition-colors" title="Editar">
                                             <i class="fas fa-edit"></i>
                                         </button>
                                                     
                                                     <!-- Excluir -->
                                                     <button onclick="excluirLicenciado({{ $licenciado->id }})" class="bg-red-50 hover:bg-red-100 text-red-700 p-2 rounded-md transition-colors" title="Excluir">
                                                         <i class="fas fa-trash"></i>
                                                     </button>
                                                 </div>
                                             </td>
                                         </tr>
                                     @endforeach
                                 </tbody>
                             </table>
                         @else
                             <div class="text-center py-12">
                                 <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                     <i class="fas fa-inbox text-3xl text-gray-400"></i>
                                 </div>
                                 <h3 class="text-lg font-semibold text-gray-600 mb-2">Nenhum licenciado encontrado</h3>
                                 <p class="text-gray-500 mb-6">Comece adicionando seu primeiro licenciado ao sistema</p>
                                 <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                                     <i class="fas fa-plus mr-2"></i>
                                     Adicionar Primeiro Licenciado
                                 </button>
                             </div>
                         @endif
                     </div>
                 </div>
            </main>
                 </div>
     </div>

     <!-- Modal Novo Licenciado -->
     <div id="licenciadoModal" class="modal">
         <div class="modal-content">
             <!-- Step Indicator -->
             <div class="step-indicator">
                 <div class="step-dot active" id="step1-dot">1</div>
                 <div class="step-line" id="step1-line"></div>
                 <div class="step-dot" id="step2-dot">2</div>
                 <div class="step-line" id="step2-line"></div>
                 <div class="step-dot" id="step3-dot">3</div>
             </div>
             
             <!-- Área de Erro Global do Modal -->
             <div id="modalErrorArea" class="hidden mx-6 mt-4"></div>

                                                       <!-- Step 1: Dados Básicos -->
               <div id="step1" class="step active p-6">
                   <div class="text-center mb-8">
                       <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                           <i class="fas fa-user-plus text-2xl text-blue-600"></i>
                       </div>
                       <h2 class="text-2xl font-bold text-gray-800 mb-2">Dados Básicos</h2>
                       <p class="text-gray-600 text-sm">Preencha as informações principais do licenciado</p>
                   </div>
                   
                   <!-- Área de Erro Step 1 -->
                   <div id="step1-errors" class="hidden mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                       <div class="text-red-700 text-sm flex items-center">
                           <i class="fas fa-exclamation-triangle mr-2"></i>
                           <span id="step1-error-message"></span>
                       </div>
                   </div>
                   
                   <!-- Seção: Informações Principais -->
                   <div class="mb-8">
                       <div class="flex items-center mb-4">
                           <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                               <i class="fas fa-building text-white text-sm"></i>
                           </div>
                           <h3 class="text-lg font-semibold text-gray-800">Informações Principais</h3>
                       </div>
                       <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                           <div class="space-y-4">
                               <div>
                                   <label class="block text-sm font-semibold text-gray-700 mb-2">
                                       <i class="fas fa-building mr-1 text-blue-500"></i>
                                       Razão Social *
                                   </label>
                                   <input type="text" id="razao-social" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Digite a razão social">
                               </div>
                               <div>
                                   <label class="block text-sm font-semibold text-gray-700 mb-2">
                                       <i class="fas fa-tag mr-1 text-purple-500"></i>
                                       Nome Fantasia
                                   </label>
                                   <input type="text" id="nome-fantasia" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all" placeholder="Digite o nome fantasia">
                               </div>
                           </div>
                           <div class="space-y-4">
                               <div>
                                   <label class="block text-sm font-semibold text-gray-700 mb-2">
                                       <i class="fas fa-id-card mr-1 text-green-500"></i>
                                       CNPJ/CPF *
                                   </label>
                                   <div class="relative">
                                       <input type="text" id="cnpj-cpf" class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all" placeholder="Digite o CNPJ ou CPF">
                                       <button type="button" onclick="buscarCNPJ()" class="absolute right-3 top-3 bg-green-600 text-white px-3 py-1 rounded-md text-sm hover:bg-green-700 transition-colors">
                                           <i class="fas fa-search"></i>
                                       </button>
                                   </div>
                                   <div id="cnpj-loading" class="hidden mt-2 text-sm text-green-600">
                                       <i class="fas fa-spinner fa-spin mr-2"></i>
                                       Buscando dados...
                                   </div>
                                   <div id="cnpj-error" class="hidden mt-2 text-sm text-red-600">
                                       <i class="fas fa-exclamation-triangle mr-2"></i>
                                       <span id="cnpj-error-message"></span>
                                   </div>
                               </div>
                               <div>
                                   <label class="block text-sm font-semibold text-gray-700 mb-2">
                                       <i class="fas fa-envelope mr-1 text-orange-500"></i>
                                       E-mail
                                   </label>
                                   <input type="email" id="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all" placeholder="Digite o e-mail">
                               </div>
                           </div>
                       </div>
                   </div>

                   <!-- Seção: Endereço -->
                   <div class="mb-8">
                       <div class="flex items-center mb-4">
                           <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center mr-3">
                               <i class="fas fa-map-marker-alt text-white text-sm"></i>
                           </div>
                           <h3 class="text-lg font-semibold text-gray-800">Endereço</h3>
                       </div>
                       <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                           <div class="space-y-4">
                               <div>
                                   <label class="block text-sm font-semibold text-gray-700 mb-2">
                                       <i class="fas fa-road mr-1 text-indigo-500"></i>
                                       Endereço *
                                   </label>
                                   <input type="text" id="endereco" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Digite o endereço">
                               </div>
                               <div>
                                   <label class="block text-sm font-semibold text-gray-700 mb-2">
                                       <i class="fas fa-map mr-1 text-teal-500"></i>
                                       Bairro *
                                   </label>
                                   <input type="text" id="bairro" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all" placeholder="Digite o bairro">
                               </div>
                               <div>
                                   <label class="block text-sm font-semibold text-gray-700 mb-2">
                                       <i class="fas fa-city mr-1 text-blue-500"></i>
                                       Cidade *
                                   </label>
                                   <input type="text" id="cidade" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Digite a cidade">
                               </div>
                           </div>
                           <div class="space-y-4">
                               <div>
                                   <label class="block text-sm font-semibold text-gray-700 mb-2">
                                       <i class="fas fa-flag mr-1 text-red-500"></i>
                                       Estado *
                                   </label>
                                   <select id="estado" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                                       <option value="">Selecione o estado...</option>
                                       <option value="AC">Acre</option>
                                       <option value="AL">Alagoas</option>
                                       <option value="AP">Amapá</option>
                                       <option value="AM">Amazonas</option>
                                       <option value="BA">Bahia</option>
                                       <option value="CE">Ceará</option>
                                       <option value="DF">Distrito Federal</option>
                                       <option value="ES">Espírito Santo</option>
                                       <option value="GO">Goiás</option>
                                       <option value="MA">Maranhão</option>
                                       <option value="MT">Mato Grosso</option>
                                       <option value="MS">Mato Grosso do Sul</option>
                                       <option value="MG">Minas Gerais</option>
                                       <option value="PA">Pará</option>
                                       <option value="PB">Paraíba</option>
                                       <option value="PR">Paraná</option>
                                       <option value="PE">Pernambuco</option>
                                       <option value="PI">Piauí</option>
                                       <option value="RJ">Rio de Janeiro</option>
                                       <option value="RN">Rio Grande do Norte</option>
                                       <option value="RS">Rio Grande do Sul</option>
                                       <option value="RO">Rondônia</option>
                                       <option value="RR">Roraima</option>
                                       <option value="SC">Santa Catarina</option>
                                       <option value="SP">São Paulo</option>
                                       <option value="SE">Sergipe</option>
                                       <option value="TO">Tocantins</option>
                                   </select>
                               </div>
                               <div>
                                   <label class="block text-sm font-semibold text-gray-700 mb-2">
                                       <i class="fas fa-mail-bulk mr-1 text-yellow-500"></i>
                                       CEP *
                                   </label>
                                   <input type="text" id="cep" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all" placeholder="Digite o CEP">
                               </div>
                               <div>
                                   <label class="block text-sm font-semibold text-gray-700 mb-2">
                                       <i class="fas fa-phone mr-1 text-green-500"></i>
                                       Telefone
                                   </label>
                                   <input type="text" id="telefone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all" placeholder="Digite o telefone">
                               </div>
                           </div>
                       </div>
                   </div>
                 <div class="flex justify-end mt-6">
                     <button onclick="nextStep()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                         Próximo <i class="fas fa-arrow-right ml-2"></i>
                     </button>
                 </div>
             </div>

                                                                                   <!-- Step 2: Documentos -->
                <div id="step2" class="step p-6">
                    <div class="text-center mb-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-file-upload text-xl text-blue-600"></i>
                        </div>
                                                 <h2 class="text-xl font-bold text-gray-800 mb-1">Documentos Necessários</h2>
                         <p class="text-gray-600 text-sm" id="step2-description">Faça upload dos documentos obrigatórios</p>
                    </div>
                    
                    <!-- Área de Erro Step 2 -->
                    <div id="step2-errors" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="text-red-700 text-sm flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span id="step2-error-message"></span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                       <!-- Cartão CNPJ -->
                       <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-xl border border-blue-100 hover:shadow-md transition-all duration-300">
                           <div class="flex items-start space-x-3">
                               <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                   <i class="fas fa-id-card text-white"></i>
                               </div>
                               <div class="flex-1">
                                   <label class="block text-sm font-semibold text-gray-800 mb-1">Cartão CNPJ</label>
                                   <p class="text-gray-600 text-xs mb-2">Documento oficial da Receita Federal</p>
                                   <div class="relative">
                                       <input type="file" id="cartao-cnpj" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                       <label for="cartao-cnpj" class="cursor-pointer">
                                           <div class="border-2 border-dashed border-blue-300 rounded-lg p-3 text-center hover:border-blue-400 hover:bg-blue-50 transition-colors">
                                               <i class="fas fa-cloud-upload-alt text-xl text-blue-400 mb-1"></i>
                                               <p class="text-blue-600 text-sm font-medium">Selecionar arquivo</p>
                                               <p class="text-gray-500 text-xs">PDF, JPG, JPEG ou PNG</p>
                                           </div>
                                       </label>
                                       <div id="cartao-cnpj-preview" class="mt-2 hidden">
                                           <div class="flex items-center space-x-2 bg-green-50 p-2 rounded-lg">
                                               <i class="fas fa-check-circle text-green-500 text-sm"></i>
                                               <span class="text-green-700 text-xs font-medium">Arquivo selecionado</span>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>

                       <!-- Contrato Social -->
                       <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-xl border border-purple-100 hover:shadow-md transition-all duration-300">
                           <div class="flex items-start space-x-3">
                               <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                   <i class="fas fa-file-contract text-white"></i>
                               </div>
                               <div class="flex-1">
                                   <label class="block text-sm font-semibold text-gray-800 mb-1">Contrato Social</label>
                                   <p class="text-gray-600 text-xs mb-2">Documento de constituição da empresa</p>
                                   <div class="relative">
                                       <input type="file" id="contrato-social" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                       <label for="contrato-social" class="cursor-pointer">
                                           <div class="border-2 border-dashed border-purple-300 rounded-lg p-3 text-center hover:border-purple-400 hover:bg-purple-50 transition-colors">
                                               <i class="fas fa-cloud-upload-alt text-xl text-purple-400 mb-1"></i>
                                               <p class="text-purple-600 text-sm font-medium">Selecionar arquivo</p>
                                               <p class="text-gray-500 text-xs">PDF, JPG, JPEG ou PNG</p>
                                           </div>
                                       </label>
                                       <div id="contrato-social-preview" class="mt-2 hidden">
                                           <div class="flex items-center space-x-2 bg-green-50 p-2 rounded-lg">
                                               <i class="fas fa-check-circle text-green-500 text-sm"></i>
                                               <span class="text-green-700 text-xs font-medium">Arquivo selecionado</span>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>

                       <!-- RG ou CNH -->
                       <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-xl border border-green-100 hover:shadow-md transition-all duration-300">
                           <div class="flex items-start space-x-3">
                               <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                   <i class="fas fa-id-badge text-white"></i>
                               </div>
                               <div class="flex-1">
                                   <label class="block text-sm font-semibold text-gray-800 mb-1">RG ou CNH</label>
                                   <p class="text-gray-600 text-xs mb-2">Documento de identificação pessoal</p>
                                   <div class="relative">
                                       <input type="file" id="rg-cnh" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                       <label for="rg-cnh" class="cursor-pointer">
                                           <div class="border-2 border-dashed border-green-300 rounded-lg p-3 text-center hover:border-green-400 hover:bg-green-50 transition-colors">
                                               <i class="fas fa-cloud-upload-alt text-xl text-green-400 mb-1"></i>
                                               <p class="text-green-600 text-sm font-medium">Selecionar arquivo</p>
                                               <p class="text-gray-500 text-xs">PDF, JPG, JPEG ou PNG</p>
                                           </div>
                                       </label>
                                       <div id="rg-cnh-preview" class="mt-2 hidden">
                                           <div class="flex items-center space-x-2 bg-green-50 p-2 rounded-lg">
                                               <i class="fas fa-check-circle text-green-500 text-sm"></i>
                                               <span class="text-green-700 text-xs font-medium">Arquivo selecionado</span>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>

                       <!-- Comprovante de Residência -->
                       <div class="bg-gradient-to-r from-orange-50 to-red-50 p-4 rounded-xl border border-orange-100 hover:shadow-md transition-all duration-300">
                           <div class="flex items-start space-x-3">
                               <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                   <i class="fas fa-home text-white"></i>
                               </div>
                               <div class="flex-1">
                                   <label class="block text-sm font-semibold text-gray-800 mb-1">Comprovante de Residência</label>
                                   <p class="text-gray-600 text-xs mb-2">Conta de luz, água ou telefone</p>
                                   <div class="relative">
                                       <input type="file" id="comprovante-residencia" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                       <label for="comprovante-residencia" class="cursor-pointer">
                                           <div class="border-2 border-dashed border-orange-300 rounded-lg p-3 text-center hover:border-orange-400 hover:bg-orange-50 transition-colors">
                                               <i class="fas fa-cloud-upload-alt text-xl text-orange-400 mb-1"></i>
                                               <p class="text-orange-600 text-sm font-medium">Selecionar arquivo</p>
                                               <p class="text-gray-500 text-xs">PDF, JPG, JPEG ou PNG</p>
                                           </div>
                                       </label>
                                       <div id="comprovante-residencia-preview" class="mt-2 hidden">
                                           <div class="flex items-center space-x-2 bg-green-50 p-2 rounded-lg">
                                               <i class="fas fa-check-circle text-green-500 text-sm"></i>
                                               <span class="text-green-700 text-xs font-medium">Arquivo selecionado</span>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>

                       <!-- Comprovante de Atividade -->
                       <div class="bg-gradient-to-r from-teal-50 to-cyan-50 p-4 rounded-xl border border-teal-100 hover:shadow-md transition-all duration-300 md:col-span-2">
                           <div class="flex items-start space-x-3">
                               <div class="w-10 h-10 bg-teal-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                   <i class="fas fa-briefcase text-white"></i>
                               </div>
                               <div class="flex-1">
                                   <label class="block text-sm font-semibold text-gray-800 mb-1">Comprovante de Atividade</label>
                                   <p class="text-gray-600 text-xs mb-2">Documento que comprove a atividade comercial</p>
                                   <div class="relative">
                                       <input type="file" id="comprovante-atividade" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                       <label for="comprovante-atividade" class="cursor-pointer">
                                           <div class="border-2 border-dashed border-teal-300 rounded-lg p-3 text-center hover:border-teal-400 hover:bg-teal-50 transition-colors">
                                               <i class="fas fa-cloud-upload-alt text-xl text-teal-400 mb-1"></i>
                                               <p class="text-teal-600 text-sm font-medium">Selecionar arquivo</p>
                                               <p class="text-gray-500 text-xs">PDF, JPG, JPEG ou PNG</p>
                                           </div>
                                       </label>
                                       <div id="comprovante-atividade-preview" class="mt-2 hidden">
                                           <div class="flex items-center space-x-2 bg-green-50 p-2 rounded-lg">
                                               <i class="fas fa-check-circle text-green-500 text-sm"></i>
                                               <span class="text-green-700 text-xs font-medium">Arquivo selecionado</span>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
                 <div class="flex justify-between mt-6">
                     <button onclick="prevStep()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                         <i class="fas fa-arrow-left mr-2"></i> Anterior
                     </button>
                     <button onclick="nextStep()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                         Próximo <i class="fas fa-arrow-right ml-2"></i>
                     </button>
                 </div>
             </div>

                                                       <!-- Step 3: Operações -->
               <div id="step3" class="step p-6">
                   <div class="text-center mb-6">
                       <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                           <i class="fas fa-credit-card text-xl text-green-600"></i>
                       </div>
                       <h2 class="text-xl font-bold text-gray-800 mb-1">Escolher Operações</h2>
                       <p class="text-gray-600 text-sm">Selecione as operações de pagamento desejadas</p>
                   </div>
                   
                   <!-- Área de Erro Step 3 -->
                   <div id="step3-errors" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                       <div class="text-red-700 text-sm flex items-center">
                           <i class="fas fa-exclamation-triangle mr-2"></i>
                           <span id="step3-error-message"></span>
                       </div>
                   </div>
                   
                   <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="operacoesContainer">
                      @forelse($operacoes as $operacao)
                          <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-xl border border-blue-100 hover:shadow-md transition-all duration-300 cursor-pointer group operation-card" onclick="toggleOperation('{{ $operacao->id }}', event)" data-operation="{{ $operacao->id }}">
                              <div class="flex items-start space-x-3">
                                  <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                      <i class="fas fa-cogs text-white"></i>
                                  </div>
                                  <div class="flex-1">
                                      <div class="flex items-center justify-between mb-2">
                                          <label class="text-sm font-semibold text-gray-800 cursor-pointer">{{ $operacao->nome }}</label>
                                          <input type="checkbox" id="operacao_{{ $operacao->id }}" name="operacoes[]" value="{{ $operacao->id }}" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" onclick="event.stopPropagation()">
                                      </div>
                                      <p class="text-gray-600 text-xs mb-2">{{ $operacao->adquirente }}</p>
                                      <div class="flex items-center text-xs text-gray-500">
                                          <i class="fas fa-check text-blue-400 mr-1"></i>
                                          <span>Disponível</span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      @empty
                          <div class="col-span-2 text-center py-8">
                              <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                  <i class="fas fa-exclamation-triangle text-2xl text-gray-400"></i>
                              </div>
                              <h3 class="text-lg font-semibold text-gray-600 mb-2">Nenhuma operação cadastrada</h3>
                              <p class="text-gray-500 mb-4">É necessário cadastrar operações antes de criar licenciados</p>
                              <a href="{{ route('dashboard.operacoes') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                  <i class="fas fa-plus mr-2"></i>
                                  Cadastrar Operação
                              </a>
                          </div>
                      @endforelse
                  </div>

                  <!-- Informações Adicionais -->
                  <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                      <div class="flex items-start space-x-3">
                          <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                              <i class="fas fa-info-circle text-blue-600 text-sm"></i>
                          </div>
                          <div>
                              <h4 class="text-sm font-semibold text-gray-800 mb-1">Informações Importantes</h4>
                              <ul class="text-xs text-gray-600 space-y-1">
                                  <li class="flex items-center">
                                      <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                      Você pode selecionar múltiplas operações
                                  </li>
                                  <li class="flex items-center">
                                      <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                      Cada operação será configurada individualmente
                                  </li>
                                  <li class="flex items-center">
                                      <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                      As taxas variam conforme a operação escolhida
                                  </li>
                              </ul>
                          </div>
                      </div>
                  </div>
                 <div class="flex justify-between mt-6">
                     <button onclick="prevStep()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                         <i class="fas fa-arrow-left mr-2"></i> Anterior
                     </button>
                                           <button onclick="saveLicenciado()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                          <i class="fas fa-save mr-2"></i> <span id="save-button-text">Salvar</span>
                      </button>
                 </div>
             </div>

             <!-- Botão Fechar -->
             <div class="absolute top-4 right-4">
                 <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                     <i class="fas fa-times"></i>
                 </button>
             </div>
         </div>
     </div>

           <!-- Modal de Sucesso -->
      <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
          <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 transform scale-95 opacity-0 transition-all duration-300">
              <div class="text-center">
                  <!-- Ícone de Sucesso Animado -->
                  <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                      <i class="fas fa-check-circle text-4xl text-green-600"></i>
                  </div>
                  
                  <!-- Título -->
                  <h3 class="text-2xl font-bold text-gray-800 mb-4">Sucesso!</h3>
                  
                  <!-- Mensagem -->
                  <p id="successMessage" class="text-gray-600 mb-8 text-lg">Licenciado cadastrado com sucesso!</p>
                  
                  <!-- Botão -->
                  <button onclick="closeSuccessModal()" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors transform hover:scale-105">
                      <i class="fas fa-check mr-2"></i>
                      Continuar
                  </button>
              </div>
          </div>
      </div>

                       <!-- Toast de Erro -->
       <div id="errorToast" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-[9999] max-w-md">
           <div class="flex items-start">
               <i class="fas fa-exclamation-circle mr-2 mt-1"></i>
               <div class="flex-1">
                   <span id="errorMessage" class="text-sm">Erro ao cadastrar licenciado</span>
               </div>
               <button onclick="closeErrorToast()" class="ml-2 text-white hover:text-gray-200">
                   <i class="fas fa-times"></i>
               </button>
           </div>
       </div>

       <!-- Modal de Visualização -->
       <div id="visualizarModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
           <div class="bg-white rounded-3xl w-full max-w-6xl mx-4 max-h-[95vh] overflow-y-auto shadow-2xl">
               <!-- Header -->
               <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white p-8 rounded-t-3xl relative overflow-hidden">
                   <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-indigo-600/20"></div>
                   <div class="relative flex items-center justify-between">
                       <div class="flex items-center space-x-4">
                           <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                               <i class="fas fa-eye text-2xl"></i>
                           </div>
                           <div>
                               <h2 class="text-3xl font-bold" id="modal-titulo">Detalhes do Licenciado</h2>
                               <p class="text-blue-100 text-lg" id="modal-subtitulo">Visualize todas as informações e documentos</p>
                           </div>
                       </div>
                       <button onclick="closeVisualizarModal()" class="text-white hover:text-blue-200 text-3xl transition-colors duration-200 hover:scale-110">
                           <i class="fas fa-times"></i>
                       </button>
                   </div>
               </div>

               <!-- Content -->
               <div class="p-8">
                   <!-- Loading -->
                   <div id="loading-detalhes" class="text-center py-16">
                       <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                           <i class="fas fa-spinner fa-spin text-3xl text-blue-600"></i>
                       </div>
                       <p class="text-gray-600 text-lg font-medium">Carregando detalhes...</p>
                   </div>

                   <!-- Conteúdo -->
                   <div id="conteudo-detalhes" class="hidden space-y-8">
                       <!-- Informações Básicas -->
                       <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-8 border border-blue-100 shadow-lg">
                           <div class="flex items-center mb-6">
                               <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                   <i class="fas fa-user-circle text-white text-xl"></i>
                               </div>
                               <h3 class="text-2xl font-bold text-gray-800">Informações Básicas</h3>
                           </div>
                           <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                               <div class="space-y-6">
                                   <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                                       <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">Razão Social</label>
                                       <p class="text-gray-900 text-lg font-medium" id="detalhe-razao-social"></p>
                                   </div>
                                   <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                                       <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">Nome Fantasia</label>
                                       <p class="text-gray-900 text-lg font-medium" id="detalhe-nome-fantasia"></p>
                                   </div>
                                   <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                                       <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">CNPJ/CPF</label>
                                       <p class="text-gray-900 text-lg font-medium" id="detalhe-cnpj-cpf"></p>
                                   </div>
                                   <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                                       <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">Tipo de Pessoa</label>
                                       <p class="text-gray-900 text-lg font-medium" id="detalhe-tipo-pessoa"></p>
                                   </div>
                               </div>
                               <div class="space-y-6">
                                   <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                                       <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">E-mail</label>
                                       <p class="text-gray-900 text-lg font-medium" id="detalhe-email"></p>
                                   </div>
                                   <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                                       <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">Telefone</label>
                                       <p class="text-gray-900 text-lg font-medium" id="detalhe-telefone"></p>
                                   </div>
                                   <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                                       <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">Status</label>
                                       <div id="detalhe-status" class="mt-2"></div>
                                   </div>
                                   <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                                       <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">Data de Cadastro</label>
                                       <p class="text-gray-900 text-lg font-medium" id="detalhe-data-cadastro"></p>
                                   </div>
                               </div>
                           </div>
                       </div>

                       <!-- Endereço -->
                       <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-8 border border-green-100 shadow-lg">
                           <div class="flex items-center mb-6">
                               <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                   <i class="fas fa-map-marker-alt text-white text-xl"></i>
                               </div>
                               <h3 class="text-2xl font-bold text-gray-800">Endereço</h3>
                           </div>
                           <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                               <div class="space-y-6">
                                   <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                                       <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">Endereço</label>
                                       <p class="text-gray-900 text-lg font-medium" id="detalhe-endereco"></p>
                                   </div>
                                   <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                                       <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">Bairro</label>
                                       <p class="text-gray-900 text-lg font-medium" id="detalhe-bairro"></p>
                                   </div>
                                   <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                                       <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">Cidade</label>
                                       <p class="text-gray-900 text-lg font-medium" id="detalhe-cidade"></p>
                                   </div>
                               </div>
                               <div class="space-y-6">
                                   <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                                       <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">Estado</label>
                                       <p class="text-gray-900 text-lg font-medium" id="detalhe-estado"></p>
                                   </div>
                                   <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                                       <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">CEP</label>
                                       <p class="text-gray-900 text-lg font-medium" id="detalhe-cep"></p>
                                   </div>
                               </div>
                           </div>
                       </div>

                       <!-- Operações -->
                       <div class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-2xl p-8 border border-purple-100 shadow-lg">
                           <div class="flex items-center mb-6">
                               <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                   <i class="fas fa-credit-card text-white text-xl"></i>
                               </div>
                               <h3 class="text-2xl font-bold text-gray-800">Operações de Pagamento</h3>
                           </div>
                           <div class="grid grid-cols-2 lg:grid-cols-4 gap-6" id="detalhe-operacoes">
                               <!-- Operações serão inseridas aqui -->
                           </div>
                       </div>

                       <!-- Documentos -->
                       <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-2xl p-8 border border-orange-100 shadow-lg">
                           <div class="flex items-center mb-6">
                               <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                   <i class="fas fa-file-alt text-white text-xl"></i>
                               </div>
                               <h3 class="text-2xl font-bold text-gray-800">Documentos</h3>
                           </div>
                           <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="detalhe-documentos">
                               <!-- Documentos serão inseridos aqui -->
                           </div>
                       </div>

                       <!-- Botão Aprovar -->
                       <div class="flex justify-center pt-8 border-t-2 border-gray-200">
                           <button onclick="aprovarLicenciado()" id="btn-aprovar" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-12 py-4 rounded-2xl font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                               <i class="fas fa-check-circle mr-3"></i>
                               Aprovar Cadastro
                           </button>
                       </div>
                   </div>
               </div>
                  </div>
   </div>

   <!-- Modal de Confirmação de Status -->
   <div id="statusConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 z-[9999] hidden">
       <div class="flex items-center justify-center min-h-screen p-4">
           <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="statusConfirmContent">
               <!-- Header -->
               <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6 rounded-t-2xl">
                   <div class="flex items-center space-x-3">
                       <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                           <i class="fas fa-exclamation-triangle text-xl"></i>
                       </div>
                       <div>
                           <h3 class="text-xl font-bold">Confirmar Alteração</h3>
                           <p class="text-blue-100 text-sm">Confirme a alteração de status</p>
                       </div>
                   </div>
               </div>
               
               <!-- Content -->
               <div class="p-6">
                   <div class="text-center mb-6">
                       <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                           <i class="fas fa-question text-white text-2xl"></i>
                       </div>
                       <h4 class="text-lg font-semibold text-gray-800 mb-2">Alterar Status do Licenciado</h4>
                       <p class="text-gray-600" id="statusConfirmMessage">
                           Tem certeza que deseja alterar o status para <span class="font-semibold text-blue-600" id="statusConfirmTarget"></span>?
                       </p>
                   </div>
                   
                   <!-- Status Preview -->
                   <div class="bg-gray-50 rounded-lg p-4 mb-6">
                       <div class="flex items-center justify-center space-x-3">
                           <span class="text-sm text-gray-600">Status atual:</span>
                           <span class="px-3 py-1 rounded-full text-xs font-medium" id="currentStatusBadge"></span>
                           <i class="fas fa-arrow-right text-gray-400"></i>
                           <span class="px-3 py-1 rounded-full text-xs font-medium" id="newStatusBadge"></span>
                       </div>
                   </div>
               </div>
               
               <!-- Actions -->
               <div class="flex space-x-3 p-6 pt-0">
                   <button onclick="closeStatusConfirmModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105">
                       <i class="fas fa-times mr-2"></i>
                       Cancelar
                   </button>
                   <button onclick="confirmStatusChange()" class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                       <i class="fas fa-check mr-2"></i>
                       Confirmar
                   </button>
               </div>
           </div>
       </div>
   </div>

       <!-- Modal de Confirmação de Exclusão -->
    <div id="deleteConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 z-[9999] hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="deleteConfirmContent">
                <!-- Header -->
                <div class="bg-gradient-to-r from-red-500 to-pink-600 text-white p-6 rounded-t-2xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-trash-alt text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Confirmar Exclusão</h3>
                            <p class="text-red-100 text-sm">Excluir licenciado permanentemente</p>
                        </div>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-red-400 to-pink-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Excluir Licenciado</h4>
                        <p class="text-gray-600">
                            Tem certeza que deseja excluir este licenciado?
                        </p>
                    </div>
                    
                    <!-- Warning -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-3 text-lg"></i>
                            <div>
                                <p class="text-red-700 font-medium text-sm">Ação Irreversível</p>
                                <p class="text-red-600 text-xs">Esta ação não pode ser desfeita e todos os dados serão perdidos permanentemente.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex space-x-3 p-6 pt-0">
                    <button onclick="closeDeleteConfirmModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </button>
                    <button onclick="confirmDelete()" class="flex-1 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Aprovação -->
    <div id="approveConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 z-[9999] hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="approveConfirmContent">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white p-6 rounded-t-2xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Confirmar Aprovação</h3>
                            <p class="text-green-100 text-sm">Aprovar cadastro do licenciado</p>
                        </div>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-thumbs-up text-white text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Aprovar Licenciado</h4>
                        <p class="text-gray-600">
                            Tem certeza que deseja aprovar este licenciado?
                        </p>
                    </div>
                    
                    <!-- Info -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-green-500 mr-3 text-lg"></i>
                            <div>
                                <p class="text-green-700 font-medium text-sm">Status será alterado</p>
                                <p class="text-green-600 text-xs">O status do licenciado será alterado para "Aprovado" e ele poderá operar normalmente.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex space-x-3 p-6 pt-0">
                    <button onclick="closeApproveConfirmModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </button>
                    <button onclick="confirmApprove()" class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-check-circle mr-2"></i>
                        Aprovar
                    </button>
                </div>
            </div>
        </div>
         </div>

     <!-- Modal de Follow-up -->
     <div id="followUpModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
         <div class="bg-white rounded-3xl w-full max-w-4xl mx-4 max-h-[95vh] overflow-y-auto shadow-2xl">
             <!-- Header -->
             <div class="bg-gradient-to-br from-purple-600 via-purple-700 to-indigo-800 text-white p-8 rounded-t-3xl relative overflow-hidden">
                 <div class="absolute inset-0 bg-gradient-to-r from-purple-600/20 to-indigo-600/20"></div>
                 <div class="relative flex items-center justify-between">
                     <div class="flex items-center space-x-4">
                         <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                             <i class="fas fa-comments text-2xl"></i>
                         </div>
                         <div>
                             <h2 class="text-3xl font-bold" id="followup-titulo">Follow-up do Licenciado</h2>
                             <p class="text-purple-100 text-lg" id="followup-subtitulo">Acompanhe todas as movimentações e adicione observações</p>
                         </div>
                     </div>
                     <button onclick="closeFollowUpModal()" class="text-white hover:text-purple-200 text-3xl transition-colors duration-200 hover:scale-110">
                         <i class="fas fa-times"></i>
                     </button>
                 </div>
             </div>
             
             <!-- Content -->
             <div class="p-8">
                 <!-- Loading -->
                 <div id="loading-followup" class="text-center py-16">
                     <div class="w-20 h-20 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                         <i class="fas fa-spinner fa-spin text-3xl text-purple-600"></i>
                     </div>
                     <p class="text-gray-600 text-lg font-medium">Carregando follow-up...</p>
                 </div>

                 <!-- Conteúdo -->
                 <div id="conteudo-followup" class="hidden space-y-8">
                     <!-- Informações do Licenciado -->
                     <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-2xl p-6 border border-purple-100 shadow-lg">
                         <div class="flex items-center mb-4">
                             <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                 <i class="fas fa-user text-white text-lg"></i>
                             </div>
                             <h3 class="text-xl font-bold text-gray-800">Informações do Licenciado</h3>
                         </div>
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                             <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                                 <label class="block text-sm font-semibold text-gray-600 mb-1">Razão Social</label>
                                 <p class="text-gray-900 font-medium" id="followup-razao-social"></p>
                             </div>
                             <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                                 <label class="block text-sm font-semibold text-gray-600 mb-1">Status Atual</label>
                                 <div id="followup-status" class="mt-1"></div>
                             </div>
                         </div>
                     </div>

                     <!-- Timeline de Movimentações -->
                     <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-lg">
                         <div class="flex items-center justify-between mb-6">
                             <div class="flex items-center">
                                 <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                     <i class="fas fa-history text-white text-lg"></i>
                                 </div>
                                 <h3 class="text-xl font-bold text-gray-800">Timeline de Movimentações</h3>
                             </div>
                             <div class="text-sm text-gray-500">
                                 <i class="fas fa-clock mr-1"></i>
                                 <span id="followup-total-movimentacoes">0 movimentações</span>
                             </div>
                         </div>
                         
                         <div id="timeline-followup" class="space-y-4">
                             <!-- Timeline será inserida aqui -->
                         </div>
                     </div>

                     <!-- Adicionar Novo Follow-up -->
                     <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-lg">
                         <div class="flex items-center mb-6">
                             <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                 <i class="fas fa-plus text-white text-lg"></i>
                             </div>
                             <h3 class="text-xl font-bold text-gray-800">Adicionar Novo Follow-up</h3>
                         </div>
                         
                         <form id="form-followup" class="space-y-4">
                             <div>
                                 <label for="followup-tipo" class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Movimentação</label>
                                 <select id="followup-tipo" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                                     <option value="contato">Contato Realizado</option>
                                     <option value="documentacao">Documentação</option>
                                     <option value="analise">Análise</option>
                                     <option value="aprovacao">Aprovação</option>
                                     <option value="rejeicao">Rejeição</option>
                                     <option value="observacao">Observação Geral</option>
                                 </select>
                             </div>
                             
                             <div>
                                 <label for="followup-observacao" class="block text-sm font-semibold text-gray-700 mb-2">Observação</label>
                                 <textarea id="followup-observacao" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 resize-none" placeholder="Descreva a movimentação ou observação..."></textarea>
                             </div>
                             
                             <div class="flex justify-end">
                                 <button type="submit" class="bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                     <i class="fas fa-plus mr-2"></i>
                                     Adicionar Follow-up
                                 </button>
                             </div>
                         </form>
                     </div>
                 </div>
             </div>
         </div>
     </div>

     <script>
                   let currentStep = 1;
          const totalSteps = 3;
          let isEditing = false;
          let editingLicenciadoId = null;

                   function openModal() {
              document.getElementById('licenciadoModal').style.display = 'block';
              isEditing = false;
              editingLicenciadoId = null;
              resetForm();
              updateModalTitle();
          }

                   function closeModal() {
              document.getElementById('licenciadoModal').style.display = 'none';
              isEditing = false;
              editingLicenciadoId = null;
              
              // Limpar área de erro do modal
              const modalErrorArea = document.getElementById('modalErrorArea');
              if (modalErrorArea) {
                  modalErrorArea.style.display = 'none';
                  modalErrorArea.innerHTML = '';
              }
              
              resetForm();
          }

                              function resetForm() {
               currentStep = 1;
               updateStepIndicator();
               
               // Limpar todos os campos
               const inputs = document.querySelectorAll('#licenciadoModal input[type="text"], #licenciadoModal input[type="file"], #licenciadoModal select');
               inputs.forEach(input => input.value = '');
               
               const checkboxes = document.querySelectorAll('#licenciadoModal input[type="checkbox"]');
               checkboxes.forEach(checkbox => {
                   checkbox.checked = false;
                   // Resetar visual feedback das operações
                   if (checkbox.id && ['pagseguro', 'adiq', 'confrapag', 'mercadopago'].includes(checkbox.id)) {
                       updateOperationCardVisual(checkbox.id, false);
                   }
               });
               
               // Limpar previews de arquivos
               const previews = document.querySelectorAll('[id$="-preview"]');
               previews.forEach(preview => preview.classList.add('hidden'));
               
               // Limpar áreas de erro
               hideAllErrors();
           }

         function nextStep() {
             if (validateCurrentStep()) {
                 if (currentStep < totalSteps) {
                     currentStep++;
                     updateStepIndicator();
                 }
             }
         }

         function prevStep() {
             if (currentStep > 1) {
                 currentStep--;
                 updateStepIndicator();
             }
         }

                   function updateStepIndicator() {
              console.log('updateStepIndicator: Updating step indicators for step', currentStep, 'isEditing:', isEditing);
              
              // Atualizar steps
              for (let i = 1; i <= totalSteps; i++) {
                  const step = document.getElementById(`step${i}`);
                  
                  // Verificar se o step existe antes de acessar classList
                  if (step) {
                      if (i === currentStep) {
                          step.classList.add('active');
                      } else {
                          step.classList.remove('active');
                      }
                  } else {
                      console.log(`updateStepIndicator: Step element not found for step ${i}`);
                  }
                  
                  // Atualizar indicadores visuais (dots e lines)
                  const dot = document.getElementById(`step${i}-dot`);
                  const line = document.getElementById(`step${i}-line`);
                  
                  if (dot) {
                      if (i < currentStep) {
                          dot.classList.remove('active');
                          dot.classList.add('completed');
                      } else if (i === currentStep) {
                          dot.classList.add('active');
                          dot.classList.remove('completed');
                      } else {
                          dot.classList.remove('active', 'completed');
                      }
                  }
                  
                  if (line) {
                      if (i < currentStep) {
                          line.classList.add('completed');
                      } else {
                          line.classList.remove('completed');
                      }
                  }
              }
          }

                   function validateCurrentStep() {
              const errors = [];
              
              // Limpar erros anteriores
              hideAllErrors();
              
                             if (currentStep === 1) {
                   const requiredFields = ['razao-social', 'cnpj-cpf', 'endereco', 'bairro', 'cidade', 'estado', 'cep'];
                   const fieldNames = {
                       'razao-social': 'Razão Social',
                       'cnpj-cpf': 'CNPJ/CPF',
                       'endereco': 'Endereço',
                       'bairro': 'Bairro',
                       'cidade': 'Cidade',
                       'estado': 'Estado',
                       'cep': 'CEP'
                   };
                  
                  requiredFields.forEach(field => {
                      const value = document.getElementById(field).value.trim();
                      if (!value) {
                          errors.push(`${fieldNames[field]} é obrigatório`);
                      }
                  });
                  
                  if (errors.length > 0) {
                      showStepError(1, errors.join(', '));
                      return false;
                  }
              }
              
                             if (currentStep === 2) {
                   // Durante a edição, os documentos não são obrigatórios se já existem
                   if (!isEditing) {
                       const requiredFiles = ['cartao-cnpj', 'contrato-social', 'rg-cnh', 'comprovante-residencia', 'comprovante-atividade'];
                       const fileNames = {
                           'cartao-cnpj': 'Cartão CNPJ',
                           'contrato-social': 'Contrato Social',
                           'rg-cnh': 'RG ou CNH',
                           'comprovante-residencia': 'Comprovante de Residência',
                           'comprovante-atividade': 'Comprovante de Atividade'
                       };
                       
                       requiredFiles.forEach(file => {
                           const fileInput = document.getElementById(file);
                           if (!fileInput.files || fileInput.files.length === 0) {
                               errors.push(`${fileNames[file]} é obrigatório`);
                           }
                       });
                       
                       if (errors.length > 0) {
                           showStepError(2, errors.join(', '));
                           return false;
                       }
                   }
               }
              
              if (currentStep === 3) {
                  // Verificar se pelo menos uma operação foi selecionada
                  const checkboxes = document.querySelectorAll('input[name="operacoes[]"]');
                  const hasOperation = Array.from(checkboxes).some(checkbox => checkbox.checked);
                  
                  if (!hasOperation) {
                      errors.push('Selecione pelo menos uma operação');
                  }
                  
                  if (errors.length > 0) {
                      showStepError(3, errors.join(', '));
                      return false;
                  }
              }
              
              return true;
          }

         function toggleOperation(operation, event) {
             // Se o clique foi no checkbox, não fazer nada (já foi tratado pelo checkbox)
             if (event && event.target.type === 'checkbox') {
                 return;
             }
             
             const checkbox = document.getElementById(`operacao_${operation}`);
             if (checkbox) {
                 checkbox.checked = !checkbox.checked;
                 
                 // Atualizar visual feedback
                 updateOperationCardVisual(operation, checkbox.checked);
                 
                 // Disparar evento change para atualizar a validação
                 checkbox.dispatchEvent(new Event('change'));
             }
         }
         
         function updateOperationCardVisual(operation, isChecked) {
             const card = document.querySelector(`[data-operation="${operation}"]`);
             if (card) {
                 if (isChecked) {
                     card.classList.add('selected');
                 } else {
                     card.classList.remove('selected');
                 }
             }
         }

                   function saveLicenciado() {
              if (!validateCurrentStep()) {
                  return;
              }
              
              // Criar FormData para enviar arquivos
              const formData = new FormData();
              
                             // Dados básicos
               const razaoSocial = document.getElementById('razao-social').value;
               const nomeFantasia = document.getElementById('nome-fantasia').value;
               const cnpjCpf = document.getElementById('cnpj-cpf').value;
               const endereco = document.getElementById('endereco').value;
               const bairro = document.getElementById('bairro').value;
               const cidade = document.getElementById('cidade').value;
               const estado = document.getElementById('estado').value;
               const cep = document.getElementById('cep').value;
               const email = document.getElementById('email').value;
               const telefone = document.getElementById('telefone').value;
               
               // Debug: Log dos valores dos campos
               console.log('Valores dos campos:', {
                   razao_social: razaoSocial,
                   nome_fantasia: nomeFantasia,
                   cnpj_cpf: cnpjCpf,
                   endereco: endereco,
                   bairro: bairro,
                   cidade: cidade,
                   estado: estado,
                   cep: cep,
                   email: email,
                   telefone: telefone
               });
               
               formData.append('razao_social', razaoSocial);
               formData.append('nome_fantasia', nomeFantasia);
               formData.append('cnpj_cpf', cnpjCpf);
               formData.append('endereco', endereco);
               formData.append('bairro', bairro);
               formData.append('cidade', cidade);
               formData.append('estado', estado);
               formData.append('cep', cep);
               formData.append('email', email);
               formData.append('telefone', telefone);
              
              // Arquivos
              const cartaoCnpj = document.getElementById('cartao-cnpj').files[0];
              const contratoSocial = document.getElementById('contrato-social').files[0];
              const rgCnh = document.getElementById('rg-cnh').files[0];
              const comprovanteResidencia = document.getElementById('comprovante-residencia').files[0];
              const comprovanteAtividade = document.getElementById('comprovante-atividade').files[0];
              
              if (cartaoCnpj) formData.append('cartao_cnpj', cartaoCnpj);
              if (contratoSocial) formData.append('contrato_social', contratoSocial);
              if (rgCnh) formData.append('rg_cnh', rgCnh);
              if (comprovanteResidencia) formData.append('comprovante_residencia', comprovanteResidencia);
              if (comprovanteAtividade) formData.append('comprovante_atividade', comprovanteAtividade);
              
                             // Operações selecionadas
               const operacoesSelecionadas = [];
               const checkboxes = document.querySelectorAll('input[name="operacoes[]"]:checked');
               checkboxes.forEach(checkbox => {
                   operacoesSelecionadas.push(checkbox.value);
               });
               formData.append('operacoes', JSON.stringify(operacoesSelecionadas));
              
                             // Mostrar loading
               const saveButton = document.querySelector('button[onclick="saveLicenciado()"]');
               const originalText = saveButton.innerHTML;
               const loadingText = isEditing ? 'Atualizando...' : 'Salvando...';
               saveButton.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i> ${loadingText}`;
               saveButton.disabled = true;
              
                             // Determinar URL e método baseado se está editando ou criando
               const url = isEditing ? `/dashboard/licenciados/${editingLicenciadoId}` : '{{ route("licenciados.store") }}';
               const method = 'POST'; // Sempre usar POST quando usando FormData com _method
               
               // Debug: Log do FormData
               console.log('URL:', url);
               console.log('Method:', method);
               console.log('isEditing:', isEditing);
               console.log('editingLicenciadoId:', editingLicenciadoId);
               
               // Adicionar _method para PUT requests (necessário para Laravel)
               if (isEditing) {
                   formData.append('_method', 'PUT');
               }
               
               // Log do conteúdo do FormData
               for (let [key, value] of formData.entries()) {
                   console.log(`FormData ${key}:`, value);
               }
               
               // Enviar para o servidor
               fetch(url, {
                   method: method,
                  headers: {
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                  },
                  body: formData
              })
              .then(response => {
                  console.log('Response status:', response.status);
                  return response.json().then(data => {
                      return { status: response.status, data: data };
                  });
              })
              .then(({ status, data }) => {
                  console.log('Response data:', data);
                  
                  if (status === 200 && data.success) {
                      closeModal();
                      const message = isEditing ? 'Licenciado atualizado com sucesso!' : data.message;
                      showSuccessModal(message);
                  } else {
                      // Mostrar erros específicos de validação
                      if (data.errors) {
                          let errorMessages = [];
                          Object.keys(data.errors).forEach(field => {
                              data.errors[field].forEach(error => {
                                  errorMessages.push(error);
                              });
                          });
                          showError('Erro de validação: ' + errorMessages.join(', '));
                      } else {
                          showError(data.message || 'Erro ao salvar licenciado');
                      }
                  }
              })
              .catch(error => {
                  console.error('Erro na requisição:', error);
                  showError('Erro de conexão. Verifique sua internet e tente novamente.');
              })
              .finally(() => {
                  // Restaurar botão
                  saveButton.innerHTML = originalText;
                  saveButton.disabled = false;
              });
          }

                   function showSuccessModal(message = 'Licenciado cadastrado com sucesso!') {
              const modal = document.getElementById('successModal');
              const messageElement = document.getElementById('successMessage');
              const modalContent = modal.querySelector('.bg-white');
              
              // Atualizar mensagem
              messageElement.textContent = message;
              
              // Mostrar modal
              modal.classList.remove('hidden');
              
              // Animar entrada
              setTimeout(() => {
                  modalContent.classList.remove('scale-95', 'opacity-0');
                  modalContent.classList.add('scale-100', 'opacity-100');
              }, 10);
          }
          
          function closeSuccessModal() {
              const modal = document.getElementById('successModal');
              const modalContent = modal.querySelector('.bg-white');
              
              // Animar saída
              modalContent.classList.remove('scale-100', 'opacity-100');
              modalContent.classList.add('scale-95', 'opacity-0');
              
              // Esconder modal após animação e recarregar página
              setTimeout(() => {
                  modal.classList.add('hidden');
                  location.reload();
              }, 300);
          }

                   function showStepError(step, message) {
              const errorContainer = document.getElementById(`step${step}-errors`);
              const errorMessage = document.getElementById(`step${step}-error-message`);
              
              errorMessage.textContent = message;
              errorContainer.classList.remove('hidden');
              
              // Scroll para o topo do step para mostrar o erro
              errorContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
          
          function hideAllErrors() {
              // Limpar erros dos steps
              for (let i = 1; i <= 3; i++) {
                  const errorContainer = document.getElementById(`step${i}-errors`);
                  if (errorContainer) {
                      errorContainer.classList.add('hidden');
                  }
              }
              
              // Limpar área de erro do modal
              const modalErrorArea = document.getElementById('modalErrorArea');
              if (modalErrorArea) {
                  modalErrorArea.style.display = 'none';
                  modalErrorArea.innerHTML = '';
              }
          }
          
                     function showError(message) {
               console.log('showError called with message:', message);
               
               // Mostrar erro no toast
               const toast = document.getElementById('errorToast');
               const messageElement = document.getElementById('errorMessage');
               if (toast && messageElement) {
                   messageElement.textContent = message;
                   toast.classList.remove('translate-x-full');
               }
               
               // Mostrar erro dentro do modal também
               const modalErrorArea = document.getElementById('modalErrorArea');
               if (modalErrorArea) {
                   modalErrorArea.innerHTML = `
                       <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                           <div class="flex items-center">
                               <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                               <span class="text-red-700 text-sm">${message}</span>
                           </div>
                       </div>
                   `;
                   modalErrorArea.style.display = 'block';
                   
                   // Scroll para o erro
                   modalErrorArea.scrollIntoView({ behavior: 'smooth', block: 'center' });
               }
               
               // Não fechar automaticamente para erros de validação
               // setTimeout(() => {
               //     toast.classList.add('translate-x-full');
               // }, 5000);
           }
           
           function closeErrorToast() {
               const toast = document.getElementById('errorToast');
               toast.classList.add('translate-x-full');
           }

                                       // Fechar modais ao clicar fora
           window.onclick = function(event) {
               const licenciadoModal = document.getElementById('licenciadoModal');
               const visualizarModal = document.getElementById('visualizarModal');
               const statusConfirmModal = document.getElementById('statusConfirmModal');
               const deleteConfirmModal = document.getElementById('deleteConfirmModal');
               const approveConfirmModal = document.getElementById('approveConfirmModal');
               const followUpModal = document.getElementById('followUpModal');
               
               if (event.target === licenciadoModal) {
                   closeModal();
               }
               
               if (event.target === visualizarModal) {
                   closeVisualizarModal();
               }
               
               if (event.target === statusConfirmModal) {
                   console.log('Clicking outside status confirm modal, closing...');
                   closeStatusConfirmModal();
               }
               
               if (event.target === deleteConfirmModal) {
                   console.log('Clicking outside delete confirm modal, closing...');
                   closeDeleteConfirmModal();
               }
               
               if (event.target === approveConfirmModal) {
                   console.log('Clicking outside approve confirm modal, closing...');
                   closeApproveConfirmModal();
               }
               
               if (event.target === followUpModal) {
                   closeFollowUpModal();
               }
           }
          
          // Função para buscar CNPJ/CPF na Receita Federal
          async function buscarCNPJ() {
              const cnpjInput = document.getElementById('cnpj-cpf');
              const cnpj = cnpjInput.value.replace(/\D/g, ''); // Remove caracteres não numéricos
              
              if (!cnpj) {
                  showCNPJError('Digite um CNPJ ou CPF válido');
                  return;
              }
              
              if (cnpj.length !== 11 && cnpj.length !== 14) {
                  showCNPJError('CNPJ deve ter 14 dígitos ou CPF deve ter 11 dígitos');
                  return;
              }
              
              // Mostrar loading
              showCNPJLoading();
              
              try {
                  // Usar nossa rota Laravel que consulta a API da Receita Federal
                  const response = await fetch('{{ route("api.consultar-cnpj") }}', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                      },
                      body: JSON.stringify({ cnpj: cnpj })
                  });
                  
                  if (!response.ok) {
                      const errorData = await response.json();
                      throw new Error(errorData.error || 'Erro na consulta');
                  }
                  
                  const data = await response.json();
                  
                  // Preencher os campos com os dados retornados
                  preencherDadosCNPJ(data);
                  hideCNPJLoading();
                  hideCNPJError();
                  
              } catch (error) {
                  console.error('Erro ao buscar CNPJ:', error);
                  hideCNPJLoading();
                  showCNPJError(error.message || 'Erro ao consultar CNPJ. Tente novamente.');
              }
          }
          
          // Função para preencher os campos com os dados do CNPJ
          function preencherDadosCNPJ(data) {
              // Preencher Razão Social
              if (data.nome) {
                  document.getElementById('razao-social').value = data.nome;
              }
              
              // Preencher Nome Fantasia
              if (data.fantasia) {
                  document.getElementById('nome-fantasia').value = data.fantasia;
              }
              
              // Preencher Endereço
              let endereco = '';
              if (data.logradouro) endereco += data.logradouro;
              if (data.numero) endereco += ', ' + data.numero;
              if (data.complemento) endereco += ' - ' + data.complemento;
              
              if (endereco) {
                  document.getElementById('endereco').value = endereco;
              }
              
              // Preencher Bairro
              if (data.bairro) {
                  document.getElementById('bairro').value = data.bairro;
              }
              
              // Preencher Cidade
              if (data.municipio) {
                  document.getElementById('cidade').value = data.municipio;
              }
              
              // Preencher Estado
              if (data.uf) {
                  const estadoSelect = document.getElementById('estado');
                  for (let option of estadoSelect.options) {
                      if (option.value === data.uf) {
                          estadoSelect.value = data.uf;
                          break;
                      }
                  }
              }
              
                             // Preencher CEP
               if (data.cep) {
                   document.getElementById('cep').value = data.cep.replace(/\D/g, '');
               }
               
               // Preencher E-mail
               if (data.email) {
                   document.getElementById('email').value = data.email;
               }
               
               // Preencher Telefone
               if (data.telefone) {
                   document.getElementById('telefone').value = data.telefone;
               }
              
                             // Mostrar mensagem de sucesso
               showSuccessModal('Dados carregados com sucesso!');
          }
          
          // Funções auxiliares para mostrar/ocultar loading e erros
          function showCNPJLoading() {
              document.getElementById('cnpj-loading').classList.remove('hidden');
              document.getElementById('cnpj-error').classList.add('hidden');
          }
          
          function hideCNPJLoading() {
              document.getElementById('cnpj-loading').classList.add('hidden');
          }
          
          function showCNPJError(message) {
              document.getElementById('cnpj-error-message').textContent = message;
              document.getElementById('cnpj-error').classList.remove('hidden');
              document.getElementById('cnpj-loading').classList.add('hidden');
          }
          
          function hideCNPJError() {
              document.getElementById('cnpj-error').classList.add('hidden');
          }
          
          
          
                        // Buscar CNPJ ao pressionar Enter no campo
              document.addEventListener('DOMContentLoaded', function() {
                  const cnpjInput = document.getElementById('cnpj-cpf');
                  
                                     // Formatar CNPJ/CPF enquanto digita
                   cnpjInput.addEventListener('input', function(e) {
                       let value = e.target.value.replace(/\D/g, '');
                       
                       if (value.length <= 11) {
                           // Formatar CPF: 000.000.000-00
                           value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                       } else {
                           // Formatar CNPJ: 00.000.000/0000-00
                           value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
                       }
                       
                       e.target.value = value;
                       
                       // Buscar automaticamente quando o CNPJ/CPF estiver completo
                       const cleanValue = value.replace(/\D/g, '');
                       if (cleanValue.length === 11 || cleanValue.length === 14) {
                           // Aguardar um pouco para o usuário terminar de digitar
                           setTimeout(() => {
                               if (cnpjInput.value.replace(/\D/g, '').length === cleanValue.length) {
                                   buscarCNPJ();
                               }
                           }, 1000);
                       }
                   });
                   
                   // Formatar telefone enquanto digita
                   const telefoneInput = document.getElementById('telefone');
                   telefoneInput.addEventListener('input', function(e) {
                       let value = e.target.value.replace(/\D/g, '');
                       
                       if (value.length <= 10) {
                           // Formatar telefone fixo: (11) 3333-4444
                           value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                       } else if (value.length <= 11) {
                           // Formatar celular: (11) 99999-9999
                           value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                       }
                       
                       e.target.value = value;
                   });
                  
                  cnpjInput.addEventListener('keypress', function(e) {
                      if (e.key === 'Enter') {
                          e.preventDefault();
                          buscarCNPJ();
                      }
                  });
              
              // Para campos de texto e select
              const textInputs = document.querySelectorAll('#licenciadoModal input[type="text"], #licenciadoModal select');
              textInputs.forEach(input => {
                  input.addEventListener('input', function() {
                      if (currentStep === 1) {
                          document.getElementById('step1-errors').classList.add('hidden');
                      }
                  });
              });
              
                             // Para campos de arquivo
               const fileInputs = document.querySelectorAll('#licenciadoModal input[type="file"]');
               fileInputs.forEach(input => {
                   input.addEventListener('change', function() {
                       if (currentStep === 2) {
                           document.getElementById('step2-errors').classList.add('hidden');
                           
                           // Mostrar preview do arquivo selecionado
                           const file = this.files[0];
                           if (file) {
                               const previewId = this.id + '-preview';
                               const previewElement = document.getElementById(previewId);
                               if (previewElement) {
                                   previewElement.classList.remove('hidden');
                                   
                                   // Atualizar texto com nome do arquivo
                                   const fileNameElement = previewElement.querySelector('span');
                                   if (fileNameElement) {
                                       fileNameElement.textContent = `Arquivo selecionado: ${file.name}`;
                                   }
                               }
                           }
                       }
                   });
               });
              
              // Para checkboxes de operações
              const checkboxes = document.querySelectorAll('#licenciadoModal input[type="checkbox"]');
              checkboxes.forEach(checkbox => {
                  checkbox.addEventListener('change', function() {
                      if (currentStep === 3) {
                          document.getElementById('step3-errors').classList.add('hidden');
                          
                          // Atualizar visual feedback
                          const operation = this.id;
                          updateOperationCardVisual(operation, this.checked);
                      }
                  });
              });
                     });
           
                       // Variáveis globais para visualização
            let licenciadoAtual = null;

                         // Funções para CRUD
             function editarLicenciado(id) {
                 console.log('Editando licenciado ID:', id);
                 isEditing = true;
                 editingLicenciadoId = id;
                 
                 // Mostrar modal e loading
                 document.getElementById('licenciadoModal').style.display = 'block';
                 updateModalTitle();
                 
                 // Buscar dados do licenciado
                 const url = `/dashboard/licenciados/${id}/detalhes`;
                 console.log('Fazendo fetch para:', url);
                 
                 fetch(url, {
                     method: 'GET',
                     headers: {
                         'Accept': 'application/json',
                         'Content-Type': 'application/json'
                     }
                 })
                 .then(response => {
                     console.log('Response status:', response.status);
                     console.log('Response ok:', response.ok);
                     if (!response.ok) {
                         throw new Error(`HTTP error! status: ${response.status}`);
                     }
                     return response.json();
                 })
                 .then(data => {
                     console.log('Response data:', data);
                     if (data.success) {
                         preencherFormularioEdicao(data.licenciado);
                         currentStep = 1;
                         updateStepIndicator();
                     } else {
                         console.error('API returned success: false:', data.message);
                         showError(data.message || 'Erro ao carregar dados do licenciado');
                         closeModal();
                     }
                 })
                 .catch(error => {
                     console.error('Fetch error:', error);
                     showError('Erro ao carregar dados do licenciado: ' + error.message);
                     closeModal();
                 });
             }
             
             function preencherFormularioEdicao(licenciado) {
                 // Preencher dados básicos
                 document.getElementById('razao-social').value = licenciado.razao_social || '';
                 document.getElementById('nome-fantasia').value = licenciado.nome_fantasia || '';
                 document.getElementById('cnpj-cpf').value = licenciado.cnpj_cpf || '';
                 document.getElementById('email').value = licenciado.email || '';
                 document.getElementById('telefone').value = licenciado.telefone || '';
                 
                 // Preencher endereço
                 document.getElementById('endereco').value = licenciado.endereco || '';
                 document.getElementById('bairro').value = licenciado.bairro || '';
                 document.getElementById('cidade').value = licenciado.cidade || '';
                 document.getElementById('estado').value = licenciado.estado || '';
                 document.getElementById('cep').value = licenciado.cep || '';
                 
                 // Preencher operações dinâmicas
                 if (licenciado.operacoes && Array.isArray(licenciado.operacoes)) {
                     // Desmarcar todas as operações primeiro
                     const checkboxes = document.querySelectorAll('input[name="operacoes[]"]');
                     checkboxes.forEach(checkbox => {
                         checkbox.checked = false;
                         updateOperationCardVisual(checkbox.value, false);
                     });
                     
                     // Marcar as operações selecionadas
                     licenciado.operacoes.forEach(operacaoId => {
                         const checkbox = document.getElementById(`operacao_${operacaoId}`);
                         if (checkbox) {
                             checkbox.checked = true;
                             updateOperationCardVisual(operacaoId, true);
                         }
                     });
                 }
                 
                 // Mostrar documentos existentes (se houver)
                 mostrarDocumentosExistentes(licenciado);
             }
             
             function mostrarDocumentosExistentes(licenciado) {
                 const documentos = [
                     { id: 'cartao-cnpj', path: licenciado.cartao_cnpj_path, nome: 'Cartão CNPJ' },
                     { id: 'contrato-social', path: licenciado.contrato_social_path, nome: 'Contrato Social' },
                     { id: 'rg-cnh', path: licenciado.rg_cnh_path, nome: 'RG ou CNH' },
                     { id: 'comprovante-residencia', path: licenciado.comprovante_residencia_path, nome: 'Comprovante de Residência' },
                     { id: 'comprovante-atividade', path: licenciado.comprovante_atividade_path, nome: 'Comprovante de Atividade' }
                 ];
                 
                 documentos.forEach(doc => {
                     if (doc.path) {
                         const previewElement = document.getElementById(`${doc.id}-preview`);
                         if (previewElement) {
                             previewElement.classList.remove('hidden');
                             const spanElement = previewElement.querySelector('span');
                             if (spanElement) {
                                 spanElement.textContent = `${doc.nome} já enviado`;
                             }
                         }
                     }
                 });
             }
             
                           function updateModalTitle() {
                  const stepIndicator = document.querySelector('.step-indicator');
                  const saveButtonText = document.getElementById('save-button-text');
                  const step2Description = document.getElementById('step2-description');
                  
                  if (isEditing) {
                      // Manter a estrutura dos steps mas adicionar um indicador de edição
                      stepIndicator.innerHTML = `
                          <div class="flex items-center justify-center mb-2">
                              <i class="fas fa-edit text-white mr-2"></i>
                              <span class="text-white font-semibold">Editando Licenciado</span>
                          </div>
                          <div class="flex justify-center">
                              <div class="step-dot active" id="step1-dot">1</div>
                              <div class="step-line" id="step1-line"></div>
                              <div class="step-dot" id="step2-dot">2</div>
                              <div class="step-line" id="step2-line"></div>
                              <div class="step-dot" id="step3-dot">3</div>
                          </div>
                      `;
                      if (saveButtonText) {
                          saveButtonText.textContent = 'Atualizar';
                      }
                      if (step2Description) {
                          step2Description.textContent = 'Atualize os documentos se necessário (opcional)';
                      }
                  } else {
                      stepIndicator.innerHTML = `
                          <div class="step-dot active" id="step1-dot">1</div>
                          <div class="step-line" id="step1-line"></div>
                          <div class="step-dot" id="step2-dot">2</div>
                          <div class="step-line" id="step2-line"></div>
                          <div class="step-dot" id="step3-dot">3</div>
                      `;
                      if (saveButtonText) {
                          saveButtonText.textContent = 'Salvar';
                      }
                      if (step2Description) {
                          step2Description.textContent = 'Faça upload dos documentos obrigatórios';
                      }
                  }
              }
            
            function visualizarLicenciado(id) {
                // Mostrar modal e loading
                document.getElementById('visualizarModal').classList.remove('hidden');
                document.getElementById('loading-detalhes').classList.remove('hidden');
                document.getElementById('conteudo-detalhes').classList.add('hidden');
                
                // Buscar detalhes do licenciado
                fetch(`/dashboard/licenciados/${id}/detalhes`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        licenciadoAtual = data.licenciado;
                        preencherDetalhes(data);
                        
                        // Esconder loading e mostrar conteúdo
                        document.getElementById('loading-detalhes').classList.add('hidden');
                        document.getElementById('conteudo-detalhes').classList.remove('hidden');
                        
                        // Atualizar título do modal
                        document.getElementById('modal-titulo').textContent = `Detalhes - ${data.licenciado.razao_social}`;
                        
                        // Mostrar/esconder botão aprovar baseado no status
                        const btnAprovar = document.getElementById('btn-aprovar');
                        if (data.licenciado.status === 'aprovado') {
                            btnAprovar.classList.add('hidden');
                        } else {
                            btnAprovar.classList.remove('hidden');
                        }
                    } else {
                        showError(data.message || 'Erro ao carregar detalhes');
                        closeVisualizarModal();
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showError('Erro ao carregar detalhes do licenciado');
                    closeVisualizarModal();
                });
            }
            
            function preencherDetalhes(data) {
                const licenciado = data.licenciado;
                
                // Informações básicas
                document.getElementById('detalhe-razao-social').textContent = licenciado.razao_social || 'N/A';
                document.getElementById('detalhe-nome-fantasia').textContent = licenciado.nome_fantasia || 'N/A';
                document.getElementById('detalhe-cnpj-cpf').textContent = licenciado.cnpj_cpf_formatado || 'N/A';
                document.getElementById('detalhe-tipo-pessoa').textContent = licenciado.tipo_pessoa || 'N/A';
                document.getElementById('detalhe-email').textContent = licenciado.email || 'N/A';
                document.getElementById('detalhe-telefone').textContent = licenciado.telefone_formatado || 'N/A';
                document.getElementById('detalhe-data-cadastro').textContent = licenciado.data_cadastro_formatada || 'N/A';
                
                // Status
                document.getElementById('detalhe-status').innerHTML = licenciado.status_badge || 'N/A';
                
                // Endereço
                document.getElementById('detalhe-endereco').textContent = licenciado.endereco || 'N/A';
                document.getElementById('detalhe-bairro').textContent = licenciado.bairro || 'N/A';
                document.getElementById('detalhe-cidade').textContent = licenciado.cidade || 'N/A';
                document.getElementById('detalhe-estado').textContent = licenciado.estado || 'N/A';
                document.getElementById('detalhe-cep').textContent = licenciado.cep_formatado || 'N/A';
                
                // Operações
                const operacoesContainer = document.getElementById('detalhe-operacoes');
                operacoesContainer.innerHTML = '';
                
                const operacoes = [
                    { key: 'pagseguro', nome: 'PagSeguro', cor: 'green', icon: 'fa-credit-card' },
                    { key: 'adiq', nome: 'Adiq', cor: 'blue', icon: 'fa-credit-card' },
                    { key: 'confrapag', nome: 'Confrapag', cor: 'purple', icon: 'fa-credit-card' },
                    { key: 'mercadopago', nome: 'Mercado Pago', cor: 'yellow', icon: 'fa-credit-card' }
                ];
                
                operacoes.forEach(op => {
                    const operacao = document.createElement('div');
                    const isActive = licenciado[op.key];
                    operacao.className = `bg-white rounded-xl p-6 shadow-sm border transition-all duration-300 transform hover:scale-105 ${isActive ? `border-${op.cor}-200 shadow-${op.cor}-100` : 'border-gray-200'}`;
                    operacao.innerHTML = `
                        <div class="flex flex-col items-center text-center space-y-3">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center ${isActive ? `bg-gradient-to-br from-${op.cor}-500 to-${op.cor}-600` : 'bg-gray-100'} shadow-lg">
                                <i class="fas ${op.icon} text-lg ${isActive ? 'text-white' : 'text-gray-400'}"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg ${isActive ? `text-${op.cor}-700` : 'text-gray-500'}">${op.nome}</h4>
                                <p class="text-sm ${isActive ? `text-${op.cor}-600` : 'text-gray-400'}">${isActive ? 'Ativo' : 'Inativo'}</p>
                            </div>
                            <div class="w-6 h-6 rounded-full flex items-center justify-center ${isActive ? 'bg-green-100' : 'bg-gray-100'}">
                                <i class="fas ${isActive ? 'fa-check text-green-600' : 'fa-times text-gray-400'} text-xs"></i>
                            </div>
                        </div>
                    `;
                    operacoesContainer.appendChild(operacao);
                });
                
                // Documentos
                const documentosContainer = document.getElementById('detalhe-documentos');
                documentosContainer.innerHTML = '';
                
                Object.entries(data.documentos).forEach(([key, doc]) => {
                    const documento = document.createElement('div');
                    const hasDocument = doc.path;
                    documento.className = `bg-white rounded-xl p-6 shadow-sm border transition-all duration-300 transform hover:scale-105 ${hasDocument ? 'border-green-200 shadow-green-100' : 'border-gray-200'}`;
                    documento.innerHTML = `
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4 flex-1">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center ${hasDocument ? 'bg-gradient-to-br from-green-500 to-emerald-600' : 'bg-gray-100'} shadow-lg">
                                    <i class="fas fa-file-alt text-lg ${hasDocument ? 'text-white' : 'text-gray-400'}"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-lg text-gray-800 mb-1">${doc.nome}</h4>
                                    <p class="text-sm ${hasDocument ? 'text-green-600' : 'text-gray-500'} font-medium">
                                        ${hasDocument ? 'Documento enviado' : 'Documento não enviado'}
                                    </p>
                                </div>
                            </div>
                            ${hasDocument ? `
                                <div class="flex space-x-2">
                                    <a href="${doc.url}" target="_blank" class="w-10 h-10 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-sm" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/dashboard/licenciados/${licenciado.id}/download/${key}" class="w-10 h-10 bg-green-100 hover:bg-green-200 text-green-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-sm" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            ` : `
                                <div class="w-10 h-10 bg-red-100 text-red-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-times"></i>
                                </div>
                            `}
                        </div>
                    `;
                    documentosContainer.appendChild(documento);
                });
            }
            
            function closeVisualizarModal() {
                document.getElementById('visualizarModal').classList.add('hidden');
                licenciadoAtual = null;
            }

            // Modal de Confirmação de Status
            let statusConfirmData = null;

            function showStatusConfirmModal(id, status, currentStatus) {
                console.log('showStatusConfirmModal called with:', id, status, currentStatus);
                
                const statusLabels = {
                    'aprovado': 'Aprovado',
                    'recusado': 'Recusado',
                    'em_analise': 'Em Análise',
                    'risco': 'Risco'
                };

                const statusColors = {
                    'aprovado': 'bg-green-100 text-green-800',
                    'recusado': 'bg-red-100 text-red-800',
                    'em_analise': 'bg-yellow-100 text-yellow-800',
                    'risco': 'bg-orange-100 text-orange-800'
                };

                statusConfirmData = { id, status };
                
                // Verificar se os elementos existem
                const statusConfirmMessage = document.getElementById('statusConfirmMessage');
                const statusConfirmTarget = document.getElementById('statusConfirmTarget');
                const currentStatusBadge = document.getElementById('currentStatusBadge');
                const newStatusBadge = document.getElementById('newStatusBadge');
                const modal = document.getElementById('statusConfirmModal');
                const content = document.getElementById('statusConfirmContent');
                
                console.log('Modal elements found:', {
                    statusConfirmMessage: !!statusConfirmMessage,
                    statusConfirmTarget: !!statusConfirmTarget,
                    currentStatusBadge: !!currentStatusBadge,
                    newStatusBadge: !!newStatusBadge,
                    modal: !!modal,
                    content: !!content
                });
                
                if (!modal || !content) {
                    console.error('Modal elements not found!');
                    return;
                }
                
                // Atualizar conteúdo do modal
                if (statusConfirmMessage) {
                    statusConfirmMessage.innerHTML = 
                        `Tem certeza que deseja alterar o status para <span class="font-semibold text-blue-600">${statusLabels[status]}</span>?`;
                }
                if (statusConfirmTarget) {
                    statusConfirmTarget.textContent = statusLabels[status];
                }
                
                // Atualizar badges de status
                if (currentStatusBadge) {
                    currentStatusBadge.textContent = statusLabels[currentStatus] || 'N/A';
                    currentStatusBadge.className = `px-3 py-1 rounded-full text-xs font-medium ${statusColors[currentStatus] || 'bg-gray-100 text-gray-800'}`;
                }
                
                if (newStatusBadge) {
                    newStatusBadge.textContent = statusLabels[status];
                    newStatusBadge.className = `px-3 py-1 rounded-full text-xs font-medium ${statusColors[status]}`;
                }
                
                // Mostrar modal com animação
                console.log('Showing modal, removing hidden class');
                console.log('Modal before removing hidden:', modal.classList.toString());
                modal.classList.remove('hidden');
                modal.style.display = 'block'; // Forçar display block
                console.log('Modal after removing hidden:', modal.classList.toString());
                console.log('Modal style.display:', modal.style.display);
                
                setTimeout(() => {
                    console.log('Adding animation classes');
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function closeStatusConfirmModal() {
                const modal = document.getElementById('statusConfirmModal');
                const content = document.getElementById('statusConfirmContent');
                
                content.classList.add('scale-95', 'opacity-0');
                content.classList.remove('scale-100', 'opacity-100');
                
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.style.display = 'none'; // Forçar display none
                    statusConfirmData = null;
                }, 300);
            }

            function confirmStatusChange() {
                if (!statusConfirmData) return;
                
                const { id, status } = statusConfirmData;
                const statusLabels = {
                    'aprovado': 'Aprovado',
                    'recusado': 'Recusado',
                    'em_analise': 'Em Análise',
                    'risco': 'Risco'
                };

                // Fechar modal de confirmação
                closeStatusConfirmModal();
                
                // Fechar menu de status
                document.getElementById(`statusMenu${id}`).classList.add('hidden');

                fetch(`/dashboard/licenciados/${id}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessModal(`Status alterado para "${statusLabels[status]}" com sucesso!`);
                        // Recarregar a página para atualizar a lista
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showError(data.message || 'Erro ao alterar status');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showError('Erro ao alterar status. Tente novamente.');
                });
            }


            
            // Variável global para armazenar dados da confirmação de aprovação
            let approveConfirmData = null;

            function aprovarLicenciado() {
                if (!licenciadoAtual) return;
                
                // Mostrar modal de confirmação
                showApproveConfirmModal();
            }

            function showApproveConfirmModal() {
                console.log('showApproveConfirmModal called');
                
                // Armazenar dados para uso posterior
                approveConfirmData = { id: licenciadoAtual.id };
                
                // Verificar se os elementos existem
                const modal = document.getElementById('approveConfirmModal');
                const content = document.getElementById('approveConfirmContent');
                
                console.log('Approve modal elements found:', {
                    modal: !!modal,
                    content: !!content
                });
                
                if (!modal || !content) {
                    console.error('Approve modal elements not found!');
                    return;
                }
                
                // Mostrar modal com animação
                console.log('Showing approve modal, removing hidden class');
                modal.classList.remove('hidden');
                modal.style.display = 'block'; // Forçar display block
                
                setTimeout(() => {
                    console.log('Adding animation classes');
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function closeApproveConfirmModal() {
                const modal = document.getElementById('approveConfirmModal');
                const content = document.getElementById('approveConfirmContent');
                
                content.classList.add('scale-95', 'opacity-0');
                content.classList.remove('scale-100', 'opacity-100');
                
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.style.display = 'none'; // Forçar display none
                    approveConfirmData = null;
                }, 300);
            }

            function confirmApprove() {
                if (!approveConfirmData) {
                    console.error('No approve data available');
                    return;
                }
                
                const { id } = approveConfirmData;
                console.log('Confirming approve for id:', id);
                
                // Fechar modal
                closeApproveConfirmModal();
                
                // Executar aprovação
                fetch(`/dashboard/licenciados/${id}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: 'aprovado' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessModal('Licenciado aprovado com sucesso!');
                        closeVisualizarModal();
                    } else {
                        showError(data.message || 'Erro ao aprovar licenciado');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showError('Erro ao aprovar licenciado. Tente novamente.');
                });
            }
           
                       // Variável global para armazenar dados da confirmação de exclusão
            let deleteConfirmData = null;

            function excluirLicenciado(id) {
                // Mostrar modal de confirmação
                showDeleteConfirmModal(id);
            }

            function showDeleteConfirmModal(id) {
                console.log('showDeleteConfirmModal called with id:', id);
                
                // Armazenar dados para uso posterior
                deleteConfirmData = { id: id };
                
                // Verificar se os elementos existem
                const modal = document.getElementById('deleteConfirmModal');
                const content = document.getElementById('deleteConfirmContent');
                
                console.log('Modal elements found:', {
                    modal: !!modal,
                    content: !!content
                });
                
                if (!modal || !content) {
                    console.error('Delete modal elements not found!');
                    return;
                }
                
                // Mostrar modal com animação
                console.log('Showing delete modal, removing hidden class');
                modal.classList.remove('hidden');
                modal.style.display = 'block'; // Forçar display block
                
                setTimeout(() => {
                    console.log('Adding animation classes');
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function closeDeleteConfirmModal() {
                const modal = document.getElementById('deleteConfirmModal');
                const content = document.getElementById('deleteConfirmContent');
                
                content.classList.add('scale-95', 'opacity-0');
                content.classList.remove('scale-100', 'opacity-100');
                
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.style.display = 'none'; // Forçar display none
                    deleteConfirmData = null;
                }, 300);
            }

            function confirmDelete() {
                if (!deleteConfirmData) {
                    console.error('No delete data available');
                    return;
                }
                
                const { id } = deleteConfirmData;
                console.log('Confirming delete for id:', id);
                
                // Fechar modal
                closeDeleteConfirmModal();
                
                // Executar exclusão
                fetch(`/dashboard/licenciados/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessModal(data.message);
                        // A página será recarregada quando o usuário fechar o modal
                    } else {
                        showError(data.message || 'Erro ao excluir licenciado');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showError('Erro ao excluir licenciado. Tente novamente.');
                });
            }

            // Funções para gerenciar status
            function toggleStatusMenu(id) {
                const menu = document.getElementById(`statusMenu${id}`);
                const allMenus = document.querySelectorAll('[id^="statusMenu"]');
                
                // Fechar todos os outros menus
                allMenus.forEach(m => {
                    if (m !== menu) {
                        m.classList.add('hidden');
                    }
                });
                
                // Alternar o menu atual
                menu.classList.toggle('hidden');
            }

            function alterarStatus(id, status) {
                console.log('alterarStatus called with id:', id, 'status:', status);
                
                // Buscar o status atual do licenciado
                const row = document.querySelector(`tr[data-id="${id}"]`);
                console.log('Found row:', row);
                
                // O data-status está no próprio elemento tr, não em um elemento filho
                const currentStatus = row ? row.getAttribute('data-status') : 'em_analise';
                console.log('Current status:', currentStatus);
                
                // Mostrar modal de confirmação
                console.log('Calling showStatusConfirmModal with:', id, status, currentStatus);
                showStatusConfirmModal(id, status, currentStatus);
            }

            // Fechar menus de status ao clicar fora
            document.addEventListener('click', function(event) {
                const statusMenus = document.querySelectorAll('[id^="statusMenu"]');
                const statusButtons = document.querySelectorAll('[onclick^="toggleStatusMenu"]');
                
                let clickedInside = false;
                
                statusButtons.forEach(button => {
                    if (button.contains(event.target)) {
                        clickedInside = true;
                    }
                });
                
                statusMenus.forEach(menu => {
                    if (menu.contains(event.target)) {
                        clickedInside = true;
                    }
                });
                
                // Verificar se o clique foi em um botão de alterar status
                const statusChangeButtons = document.querySelectorAll('[onclick^="alterarStatus"]');
                statusChangeButtons.forEach(button => {
                    if (button.contains(event.target)) {
                        clickedInside = true;
                    }
                });
                
                if (!clickedInside) {
                    statusMenus.forEach(menu => {
                        menu.classList.add('hidden');
                    });
                }
            });

            // Variáveis globais para follow-up
            let licenciadoFollowUpAtual = null;

            // Função para abrir o modal de follow-up
            function abrirFollowUp(id) {
                console.log('abrirFollowUp called with id:', id);
                
                // Mostrar loading
                document.getElementById('loading-followup').classList.remove('hidden');
                document.getElementById('conteudo-followup').classList.add('hidden');
                
                // Mostrar modal
                document.getElementById('followUpModal').classList.remove('hidden');
                
                // Carregar dados do follow-up
                carregarFollowUp(id);
            }

            // Função para fechar o modal de follow-up
            function closeFollowUpModal() {
                document.getElementById('followUpModal').classList.add('hidden');
                licenciadoFollowUpAtual = null;
                
                // Limpar formulário
                document.getElementById('form-followup').reset();
            }

            // Função para carregar dados do follow-up
            async function carregarFollowUp(id) {
                try {
                    const response = await fetch(`/dashboard/licenciados/${id}/followup`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Erro ao carregar follow-up');
                    }

                    const data = await response.json();
                    
                    if (data.success) {
                        licenciadoFollowUpAtual = data.licenciado;
                        preencherFollowUp(data);
                    } else {
                        showError(data.message || 'Erro ao carregar follow-up');
                        closeFollowUpModal();
                    }
                } catch (error) {
                    console.error('Erro ao carregar follow-up:', error);
                    showError('Erro ao carregar follow-up. Tente novamente.');
                    closeFollowUpModal();
                }
            }

            // Função para preencher o modal de follow-up
            function preencherFollowUp(data) {
                const licenciado = data.licenciado;
                const followups = data.followups || [];
                
                // Preencher informações do licenciado
                document.getElementById('followup-razao-social').textContent = licenciado.razao_social || 'N/A';
                
                // Preencher status
                const statusElement = document.getElementById('followup-status');
                const statusLabels = {
                    'aprovado': 'Aprovado',
                    'recusado': 'Recusado',
                    'em_analise': 'Em Análise',
                    'risco': 'Risco'
                };
                const statusColors = {
                    'aprovado': 'bg-green-100 text-green-800',
                    'recusado': 'bg-red-100 text-red-800',
                    'em_analise': 'bg-yellow-100 text-yellow-800',
                    'risco': 'bg-orange-100 text-orange-800'
                };
                
                statusElement.innerHTML = `
                    <span class="px-3 py-1 rounded-full text-xs font-medium ${statusColors[licenciado.status] || 'bg-gray-100 text-gray-800'}">
                        ${statusLabels[licenciado.status] || licenciado.status}
                    </span>
                `;
                
                // Preencher timeline
                preencherTimelineFollowUp(followups);
                
                // Atualizar contador
                document.getElementById('followup-total-movimentacoes').textContent = `${followups.length} movimentação${followups.length !== 1 ? 'ões' : ''}`;
                
                // Esconder loading e mostrar conteúdo
                document.getElementById('loading-followup').classList.add('hidden');
                document.getElementById('conteudo-followup').classList.remove('hidden');
            }

            // Função para preencher a timeline de follow-up
            function preencherTimelineFollowUp(followups) {
                const timelineContainer = document.getElementById('timeline-followup');
                
                if (followups.length === 0) {
                    timelineContainer.innerHTML = `
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-comments text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 text-lg font-medium">Nenhuma movimentação registrada</p>
                            <p class="text-gray-400 text-sm">Adicione a primeira observação usando o formulário abaixo</p>
                        </div>
                    `;
                    return;
                }
                
                const timelineHTML = followups.map((followup, index) => {
                    const tipoIcons = {
                        'contato': 'fa-phone',
                        'documentacao': 'fa-file-alt',
                        'analise': 'fa-search',
                        'aprovacao': 'fa-check-circle',
                        'rejeicao': 'fa-times-circle',
                        'observacao': 'fa-comment'
                    };
                    
                    const tipoColors = {
                        'contato': 'bg-blue-500',
                        'documentacao': 'bg-purple-500',
                        'analise': 'bg-yellow-500',
                        'aprovacao': 'bg-green-500',
                        'rejeicao': 'bg-red-500',
                        'observacao': 'bg-gray-500'
                    };
                    
                    const tipoLabels = {
                        'contato': 'Contato',
                        'documentacao': 'Documentação',
                        'analise': 'Análise',
                        'aprovacao': 'Aprovação',
                        'rejeicao': 'Rejeição',
                        'observacao': 'Observação'
                    };
                    
                    const dataFormatada = new Date(followup.created_at).toLocaleString('pt-BR');
                    
                    return `
                        <div class="flex items-start space-x-4 ${index !== followups.length - 1 ? 'pb-6 border-b border-gray-200' : ''}">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 ${tipoColors[followup.tipo] || 'bg-gray-500'} rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas ${tipoIcons[followup.tipo] || 'fa-comment'} text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-sm font-semibold text-gray-900">${tipoLabels[followup.tipo] || 'Movimentação'}</h4>
                                    <span class="text-xs text-gray-500">${dataFormatada}</span>
                                </div>
                                <p class="text-sm text-gray-700 leading-relaxed">${followup.observacao}</p>
                            </div>
                        </div>
                    `;
                }).join('');
                
                timelineContainer.innerHTML = timelineHTML;
            }

            // Event listener para o formulário de follow-up
            document.addEventListener('DOMContentLoaded', function() {
                const formFollowup = document.getElementById('form-followup');
                if (formFollowup) {
                    formFollowup.addEventListener('submit', function(e) {
                        e.preventDefault();
                        adicionarFollowUp();
                    });
                }
            });

            // Função para adicionar novo follow-up
            async function adicionarFollowUp() {
                if (!licenciadoFollowUpAtual) {
                    showError('Erro: Licenciado não identificado');
                    return;
                }
                
                const tipo = document.getElementById('followup-tipo').value;
                const observacao = document.getElementById('followup-observacao').value.trim();
                
                if (!observacao) {
                    showError('Por favor, preencha a observação');
                    return;
                }
                
                try {
                    const response = await fetch(`/dashboard/licenciados/${licenciadoFollowUpAtual.id}/followup`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            tipo: tipo,
                            observacao: observacao
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Erro ao adicionar follow-up');
                    }

                    const data = await response.json();
                    
                    if (data.success) {
                        showSuccessModal('Follow-up adicionado com sucesso!');
                        
                        // Recarregar follow-up
                        await carregarFollowUp(licenciadoFollowUpAtual.id);
                        
                        // Limpar formulário
                        document.getElementById('form-followup').reset();
                    } else {
                        showError(data.message || 'Erro ao adicionar follow-up');
                    }
                } catch (error) {
                    console.error('Erro ao adicionar follow-up:', error);
                    showError('Erro ao adicionar follow-up. Tente novamente.');
                }
            }

            // ========================================
            // FUNÇÕES DE FILTRO
            // ========================================

            // Função para aplicar filtros
            function aplicarFiltros() {
                const dataInicial = document.getElementById('data_inicial').value;
                const dataFinal = document.getElementById('data_final').value;
                const status = document.getElementById('status').value;
                const operacao = document.getElementById('operacao').value;
                const estado = document.getElementById('estado').value;

                // Validar datas
                if (dataInicial && dataFinal && dataInicial > dataFinal) {
                    showError('A data inicial não pode ser maior que a data final');
                    return;
                }

                // Construir URL com parâmetros
                const params = new URLSearchParams();
                if (dataInicial) params.append('data_inicial', dataInicial);
                if (dataFinal) params.append('data_final', dataFinal);
                if (status) params.append('status', status);
                if (operacao) params.append('operacao', operacao);
                if (estado) params.append('estado', estado);

                // Redirecionar com filtros
                const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
                window.location.href = url;
            }

            // Função para limpar filtros
            function limparFiltros() {
                document.getElementById('data_inicial').value = '';
                document.getElementById('data_final').value = '';
                document.getElementById('status').value = '';
                document.getElementById('operacao').value = '';
                document.getElementById('estado').value = '';
                
                // Redirecionar sem filtros
                window.location.href = window.location.pathname;
            }

            // Função para preencher filtros com valores da URL
            function preencherFiltrosDaURL() {
                const urlParams = new URLSearchParams(window.location.search);
                
                if (urlParams.has('data_inicial')) {
                    document.getElementById('data_inicial').value = urlParams.get('data_inicial');
                }
                if (urlParams.has('data_final')) {
                    document.getElementById('data_final').value = urlParams.get('data_final');
                }
                if (urlParams.has('status')) {
                    document.getElementById('status').value = urlParams.get('status');
                }
                if (urlParams.has('operacao')) {
                    document.getElementById('operacao').value = urlParams.get('operacao');
                }
                if (urlParams.has('estado')) {
                    document.getElementById('estado').value = urlParams.get('estado');
                }
            }

            // Event listener para aplicar filtros com Enter
            document.addEventListener('DOMContentLoaded', function() {
                const filtroForm = document.getElementById('filtroForm');
                if (filtroForm) {
                    filtroForm.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            aplicarFiltros();
                        }
                    });
                }

                // Preencher filtros com valores da URL ao carregar a página
                preencherFiltrosDaURL();
            });
      </script>
  </body>
  </html>
