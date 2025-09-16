@extends('layouts.app')

@section('content')
<x-dynamic-branding />

<div class="min-h-screen bg-gray-50">
    <div class="flex">
        <!-- Sidebar -->
        <x-dynamic-sidebar />

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <main class="p-6">
                <div class="max-w-4xl mx-auto">
                    <!-- Header -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Gerar Contrato</h1>
                                <p class="" style="color: var(--secondary-color);" mt-1">Processo de geração de contratos em 3 etapas</p>
                            </div>
                            <a href="{{ route('contracts.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Voltar
                            </a>
                        </div>
                    </div>

                    <!-- Progress Steps -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full">
                                    <span class="text-sm font-medium">1</span>
                                </div>
                                <span class="ml-2 text-sm font-medium text-blue-600">Selecionar Licenciado</span>
                            </div>
                            <div class="flex-1 mx-4 h-px bg-gray-300"></div>
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 bg-gray-300 text-gray-500 rounded-full">
                                    <span class="text-sm font-medium">2</span>
                                </div>
                                <span class="ml-2 text-sm font-medium text-gray-500">Selecionar Template</span>
                            </div>
                            <div class="flex-1 mx-4 h-px bg-gray-300"></div>
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 bg-gray-300 text-gray-500 rounded-full">
                                    <span class="text-sm font-medium">3</span>
                                </div>
                                <span class="ml-2 text-sm font-medium text-gray-500">Gerar Contrato</span>
                            </div>
                        </div>
                    </div>

                    <!-- Licensee Selection -->
                    <div class="bg-white rounded-lg shadow-sm border">
                        <div class="p-6 border-b">
                            <h3 class="text-lg font-semibold text-gray-800">Selecionar Licenciado</h3>
                            <p class="" style="color: var(--secondary-color);" mt-1">Escolha o licenciado para o qual deseja gerar o contrato</p>
                            <div class="mt-3 flex items-center space-x-4 text-xs text-gray-500">
                                <span class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-1"></div>
                                    Ativo
                                </span>
                                <span class="flex items-center">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-1"></div>
                                    Aprovado
                                </span>
                                <span class="flex items-center">
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full mr-1"></div>
                                    Pendente
                                </span>
                                <span class="flex items-center">
                                    <div class="w-2 h-2 bg-red-500 rounded-full mr-1"></div>
                                    Recusado
                                </span>
                                <span class="text-gray-400">• Mostrando todos os licenciados cadastrados ({{ $licenciados->count() }})</span>
                            </div>
                        </div>

                        <div class="p-6">
                            <form method="POST" action="{{ route('contracts.generate.step1') }}">
                                @csrf
                                
                                <!-- Select Tradicional -->
                                <div class="mb-6">
                                    <label for="licenciado_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-user mr-2"></i>Selecione o Licenciado
                                    </label>
                                    <select name="licenciado_id" id="licenciado_id" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="">-- Selecione um licenciado --</option>
                                        @foreach($licenciados as $licenciado)
                                            <option value="{{ $licenciado->id }}">
                                                {{ $licenciado->razao_social ?: $licenciado->nome_fantasia }} 
                                                ({{ $licenciado->cnpj_cpf }}) 
                                                - {{ ucfirst($licenciado->status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Informações do Licenciado Selecionado -->
                                <div id="licensee-info" class="hidden mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <h4 class="font-medium text-blue-900 mb-2">
                                        <i class="fas fa-info-circle mr-2"></i>Informações do Licenciado
                                    </h4>
                                    <div id="licensee-details" class="text-sm text-blue-800">
                                        <!-- Será preenchido via JavaScript -->
                                    </div>
                                </div>

                                <!-- Continue Button -->
                                <div class="flex justify-end">
                                    <button type="submit" 
                                            id="continue-btn"
                                            disabled
                                            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                        Continuar para Etapa 2
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Dados dos licenciados
        const licenciados = @json($licenciados->keyBy('id'));
        console.log('📋 Licenciados carregados:', licenciados);

        // Elementos DOM
        const selectElement = document.getElementById('licenciado_id');
        const continueBtn = document.getElementById('continue-btn');
        const licenseeInfo = document.getElementById('licensee-info');
        const licenseeDetails = document.getElementById('licensee-details');

        // Função para mostrar informações do licenciado
        function showLicenseeInfo(licenciado) {
            const statusColors = {
                'ativo': 'text-green-600',
                'aprovado': 'text-blue-600',
                'pendente': 'text-yellow-600',
                'recusado': 'text-red-600'
            };

            const statusColor = statusColors[licenciado.status] || 'text-gray-600';

            licenseeDetails.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p><strong>Nome:</strong> ${licenciado.razao_social || licenciado.nome_fantasia}</p>
                        <p><strong>CNPJ/CPF:</strong> ${licenciado.cnpj_cpf}</p>
                        <p><strong>Status:</strong> <span class="${statusColor} font-medium">${licenciado.status.toUpperCase()}</span></p>
                    </div>
                    <div>
                        <p><strong>Email:</strong> ${licenciado.email}</p>
                        <p><strong>Telefone:</strong> ${licenciado.telefone || 'N/A'}</p>
                        <p><strong>Cidade:</strong> ${licenciado.cidade}, ${licenciado.estado}</p>
                    </div>
                </div>
            `;
            
            licenseeInfo.classList.remove('hidden');
        }

        // Event listener para o select
        selectElement.addEventListener('change', function() {
            const selectedId = this.value;
            
            if (selectedId) {
                const licenciado = licenciados[selectedId];
                if (licenciado) {
                    console.log('✅ Licenciado selecionado:', licenciado);
                    showLicenseeInfo(licenciado);
                    continueBtn.disabled = false;
                } else {
                    console.error('❌ Licenciado não encontrado:', selectedId);
                }
            } else {
                licenseeInfo.classList.add('hidden');
                continueBtn.disabled = true;
            }
        });

        console.log('🚀 Sistema de seleção inicializado com sucesso!');
    </script>
</div>
@endsection
