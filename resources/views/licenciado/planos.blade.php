@extends('layouts.licenciado')

@section('title', 'Planos')
@section('subtitle', 'Visualize os planos disponíveis')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-chart-line text-blue-600 mr-3"></i>
                    Planos Disponíveis
                </h2>
                <p class="text-gray-600">Visualize todos os planos comerciais e suas taxas</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <div class="bg-blue-50 px-4 py-2 rounded-lg">
                    <span class="text-sm font-medium text-blue-700">
                        <i class="fas fa-list mr-2"></i>
                        {{ $planos->count() }} planos disponíveis
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if($planos->count() > 0)
        <!-- Grid de Planos -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($planos as $plano)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <!-- Header do Card -->
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                        <h3 class="text-lg font-bold text-white mb-1">{{ $plano->nome }}</h3>
                        @if($plano->adquirente)
                            <p class="text-blue-100 text-sm">
                                <i class="fas fa-building mr-1"></i>
                                {{ $plano->adquirente->nome }}
                            </p>
                        @endif
                    </div>
                    
                    <!-- Conteúdo do Card -->
                    <div class="p-6">
                        <!-- Descrição -->
                        @if($plano->descricao)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $plano->descricao }}</p>
                        @endif
                        
                        <!-- Informações Principais -->
                        <div class="space-y-3 mb-4">
                            @if($plano->comissao_media)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 text-sm">Comissão Média:</span>
                                    <span class="font-semibold text-green-600">{{ number_format($plano->comissao_media, 2) }}%</span>
                                </div>
                            @endif
                            
                            @if($plano->parceiro)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 text-sm">Parceiro:</span>
                                    <span class="font-medium text-gray-900">{{ $plano->parceiro->nome }}</span>
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 text-sm">Status:</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Ativo
                                </span>
                            </div>
                        </div>
                        
                        <!-- Taxas Resumidas -->
                        @if($plano->taxasAtivas && $plano->taxasAtivas->count() > 0)
                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">
                                    <i class="fas fa-percentage mr-1"></i>
                                    Taxas Disponíveis
                                </h4>
                                
                                <div class="space-y-2 max-h-32 overflow-y-auto">
                                    @php
                                        $taxasGrouped = $plano->taxasAtivas->groupBy('modalidade');
                                    @endphp
                                    
                                    @foreach($taxasGrouped as $modalidade => $taxas)
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-xs font-medium text-gray-700 uppercase">
                                                    {{ ucfirst(str_replace('_', ' ', $modalidade)) }}
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    {{ $taxas->count() }} taxa{{ $taxas->count() > 1 ? 's' : '' }}
                                                </span>
                                            </div>
                                            
                                            @php
                                                $minTaxa = $taxas->min('taxa_percent');
                                                $maxTaxa = $taxas->max('taxa_percent');
                                            @endphp
                                            
                                            <div class="text-sm">
                                                @if($minTaxa == $maxTaxa)
                                                    <span class="font-semibold text-blue-600">{{ number_format($minTaxa, 2) }}%</span>
                                                @else
                                                    <span class="font-semibold text-blue-600">
                                                        {{ number_format($minTaxa, 2) }}% - {{ number_format($maxTaxa, 2) }}%
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Botão de Ação -->
                        <div class="mt-6">
                            <button onclick="showPlanoDetails({{ json_encode($plano) }})" 
                                    class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-2 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-eye mr-2"></i>
                                Ver Detalhes
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Estado Vazio -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-chart-line text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Nenhum plano disponível</h3>
            <p class="text-gray-600 mb-4">Não há planos ativos no momento.</p>
        </div>
    @endif
</div>

<!-- Modal de Detalhes do Plano -->
<div id="planoDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <!-- Header do Modal -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 id="modalPlanoNome" class="text-xl font-bold text-white"></h3>
                    <p id="modalPlanoAdquirente" class="text-blue-100 text-sm"></p>
                </div>
                <button onclick="closePlanoDetailsModal()" class="text-white hover:text-blue-200 p-2 hover:bg-white hover:bg-opacity-10 rounded-full transition-colors duration-200">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
        
        <!-- Conteúdo do Modal -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]">
            <div id="modalPlanoContent">
                <!-- Conteúdo será carregado via JavaScript -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Função para mostrar detalhes do plano
function showPlanoDetails(plano) {
    console.log('Mostrando detalhes do plano:', plano);
    
    // Atualizar header do modal
    document.getElementById('modalPlanoNome').textContent = plano.nome;
    document.getElementById('modalPlanoAdquirente').textContent = plano.adquirente ? plano.adquirente.nome : '';
    
    // Gerar conteúdo do modal
    let content = `
        <div class="space-y-6">
            <!-- Informações Básicas -->
            <div class="bg-gray-50 rounded-xl p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Informações Básicas
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Nome do Plano:</label>
                        <p class="text-gray-900 font-semibold">${plano.nome}</p>
                    </div>
                    
                    ${plano.adquirente ? `
                        <div>
                            <label class="text-sm font-medium text-gray-700">Adquirente:</label>
                            <p class="text-gray-900">${plano.adquirente.nome}</p>
                        </div>
                    ` : ''}
                    
                    ${plano.parceiro ? `
                        <div>
                            <label class="text-sm font-medium text-gray-700">Parceiro:</label>
                            <p class="text-gray-900">${plano.parceiro.nome}</p>
                        </div>
                    ` : ''}
                    
                    ${plano.comissao_media ? `
                        <div>
                            <label class="text-sm font-medium text-gray-700">Comissão Média:</label>
                            <p class="text-green-600 font-semibold">${parseFloat(plano.comissao_media).toFixed(2)}%</p>
                        </div>
                    ` : ''}
                </div>
                
                ${plano.descricao ? `
                    <div class="mt-4">
                        <label class="text-sm font-medium text-gray-700">Descrição:</label>
                        <p class="text-gray-600 mt-1">${plano.descricao}</p>
                    </div>
                ` : ''}
            </div>
            
            <!-- Taxas Detalhadas -->
            ${plano.taxas_ativas && plano.taxas_ativas.length > 0 ? `
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-percentage text-green-600 mr-2"></i>
                        Taxas Detalhadas
                    </h4>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">Modalidade</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">Bandeira</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">Parcelas</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">Taxa</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">Comissão</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                ${plano.taxas_ativas.map(taxa => `
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                ${taxa.modalidade.replace('_', ' ').toUpperCase()}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-900">${taxa.bandeira || '-'}</td>
                                        <td class="px-4 py-3 text-gray-900">${taxa.parcelas || '-'}</td>
                                        <td class="px-4 py-3">
                                            <span class="font-semibold text-blue-600">${parseFloat(taxa.taxa_percent || 0).toFixed(2)}%</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="font-semibold text-green-600">${parseFloat(taxa.comissao_percent || 0).toFixed(2)}%</span>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            ` : `
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl mb-2"></i>
                    <p class="text-yellow-800 font-medium">Nenhuma taxa configurada para este plano</p>
                </div>
            `}
        </div>
    `;
    
    document.getElementById('modalPlanoContent').innerHTML = content;
    document.getElementById('planoDetailsModal').classList.remove('hidden');
}

// Função para fechar modal
function closePlanoDetailsModal() {
    document.getElementById('planoDetailsModal').classList.add('hidden');
}

// Fechar modal ao clicar fora
document.getElementById('planoDetailsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePlanoDetailsModal();
    }
});

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePlanoDetailsModal();
    }
});
</script>
@endpush
