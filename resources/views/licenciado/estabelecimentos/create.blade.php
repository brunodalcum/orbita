@extends('layouts.licenciado')

@section('title', 'Novo Estabelecimento')
@section('subtitle', 'Cadastrar um novo estabelecimento')

@section('content')
<x-dynamic-branding />

<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Novo Estabelecimento</h1>
            <p class="text-gray-600 mt-1">Preencha os dados do estabelecimento que deseja cadastrar</p>
        </div>
        <a href="{{ route('licenciado.estabelecimentos') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Voltar
        </a>
    </div>

    <!-- Form Card -->
    <div class="card">
        <form action="{{ route('licenciado.estabelecimentos.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Informações Básicas -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações Básicas</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nome_fantasia" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome Fantasia *
                        </label>
                        <input type="text" 
                               id="nome_fantasia" 
                               name="nome_fantasia" 
                               value="{{ old('nome_fantasia') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nome_fantasia') border-red-500 @enderror"
                               required>
                        @error('nome_fantasia')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="razao_social" class="block text-sm font-medium text-gray-700 mb-2">
                            Razão Social *
                        </label>
                        <input type="text" 
                               id="razao_social" 
                               name="razao_social" 
                               value="{{ old('razao_social') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('razao_social') border-red-500 @enderror"
                               required>
                        @error('razao_social')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cnpj" class="block text-sm font-medium text-gray-700 mb-2">
                            CNPJ *
                        </label>
                        <input type="text" 
                               id="cnpj" 
                               name="cnpj" 
                               value="{{ old('cnpj') }}"
                               placeholder="00.000.000/0000-00"
                               maxlength="18"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cnpj') border-red-500 @enderror"
                               required>
                        @error('cnpj')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tipo_negocio" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Negócio *
                        </label>
                        <select id="tipo_negocio" 
                                name="tipo_negocio" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tipo_negocio') border-red-500 @enderror"
                                required>
                            <option value="">Selecione...</option>
                            <option value="varejo" {{ old('tipo_negocio') == 'varejo' ? 'selected' : '' }}>Varejo</option>
                            <option value="atacado" {{ old('tipo_negocio') == 'atacado' ? 'selected' : '' }}>Atacado</option>
                            <option value="servicos" {{ old('tipo_negocio') == 'servicos' ? 'selected' : '' }}>Serviços</option>
                            <option value="alimentacao" {{ old('tipo_negocio') == 'alimentacao' ? 'selected' : '' }}>Alimentação</option>
                            <option value="outros" {{ old('tipo_negocio') == 'outros' ? 'selected' : '' }}>Outros</option>
                        </select>
                        @error('tipo_negocio')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contato -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Contato</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            E-mail
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">
                            Telefone
                        </label>
                        <input type="text" 
                               id="telefone" 
                               name="telefone" 
                               value="{{ old('telefone') }}"
                               placeholder="(00) 0000-0000"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('telefone') border-red-500 @enderror">
                        @error('telefone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="celular" class="block text-sm font-medium text-gray-700 mb-2">
                            Celular
                        </label>
                        <input type="text" 
                               id="celular" 
                               name="celular" 
                               value="{{ old('celular') }}"
                               placeholder="(00) 00000-0000"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('celular') border-red-500 @enderror">
                        @error('celular')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Endereço -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Endereço</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="md:col-span-2">
                        <label for="endereco" class="block text-sm font-medium text-gray-700 mb-2">
                            Logradouro *
                        </label>
                        <input type="text" 
                               id="endereco" 
                               name="endereco" 
                               value="{{ old('endereco') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('endereco') border-red-500 @enderror"
                               required>
                        @error('endereco')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="numero" class="block text-sm font-medium text-gray-700 mb-2">
                            Número *
                        </label>
                        <input type="text" 
                               id="numero" 
                               name="numero" 
                               value="{{ old('numero') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('numero') border-red-500 @enderror"
                               required>
                        @error('numero')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="complemento" class="block text-sm font-medium text-gray-700 mb-2">
                            Complemento
                        </label>
                        <input type="text" 
                               id="complemento" 
                               name="complemento" 
                               value="{{ old('complemento') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('complemento') border-red-500 @enderror">
                        @error('complemento')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bairro" class="block text-sm font-medium text-gray-700 mb-2">
                            Bairro *
                        </label>
                        <input type="text" 
                               id="bairro" 
                               name="bairro" 
                               value="{{ old('bairro') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('bairro') border-red-500 @enderror"
                               required>
                        @error('bairro')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cidade" class="block text-sm font-medium text-gray-700 mb-2">
                            Cidade *
                        </label>
                        <input type="text" 
                               id="cidade" 
                               name="cidade" 
                               value="{{ old('cidade') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cidade') border-red-500 @enderror"
                               required>
                        @error('cidade')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                            Estado *
                        </label>
                        <select id="estado" 
                                name="estado" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('estado') border-red-500 @enderror"
                                required>
                            <option value="">UF</option>
                            <option value="AC" {{ old('estado') == 'AC' ? 'selected' : '' }}>AC</option>
                            <option value="AL" {{ old('estado') == 'AL' ? 'selected' : '' }}>AL</option>
                            <option value="AP" {{ old('estado') == 'AP' ? 'selected' : '' }}>AP</option>
                            <option value="AM" {{ old('estado') == 'AM' ? 'selected' : '' }}>AM</option>
                            <option value="BA" {{ old('estado') == 'BA' ? 'selected' : '' }}>BA</option>
                            <option value="CE" {{ old('estado') == 'CE' ? 'selected' : '' }}>CE</option>
                            <option value="DF" {{ old('estado') == 'DF' ? 'selected' : '' }}>DF</option>
                            <option value="ES" {{ old('estado') == 'ES' ? 'selected' : '' }}>ES</option>
                            <option value="GO" {{ old('estado') == 'GO' ? 'selected' : '' }}>GO</option>
                            <option value="MA" {{ old('estado') == 'MA' ? 'selected' : '' }}>MA</option>
                            <option value="MT" {{ old('estado') == 'MT' ? 'selected' : '' }}>MT</option>
                            <option value="MS" {{ old('estado') == 'MS' ? 'selected' : '' }}>MS</option>
                            <option value="MG" {{ old('estado') == 'MG' ? 'selected' : '' }}>MG</option>
                            <option value="PA" {{ old('estado') == 'PA' ? 'selected' : '' }}>PA</option>
                            <option value="PB" {{ old('estado') == 'PB' ? 'selected' : '' }}>PB</option>
                            <option value="PR" {{ old('estado') == 'PR' ? 'selected' : '' }}>PR</option>
                            <option value="PE" {{ old('estado') == 'PE' ? 'selected' : '' }}>PE</option>
                            <option value="PI" {{ old('estado') == 'PI' ? 'selected' : '' }}>PI</option>
                            <option value="RJ" {{ old('estado') == 'RJ' ? 'selected' : '' }}>RJ</option>
                            <option value="RN" {{ old('estado') == 'RN' ? 'selected' : '' }}>RN</option>
                            <option value="RS" {{ old('estado') == 'RS' ? 'selected' : '' }}>RS</option>
                            <option value="RO" {{ old('estado') == 'RO' ? 'selected' : '' }}>RO</option>
                            <option value="RR" {{ old('estado') == 'RR' ? 'selected' : '' }}>RR</option>
                            <option value="SC" {{ old('estado') == 'SC' ? 'selected' : '' }}>SC</option>
                            <option value="SP" {{ old('estado') == 'SP' ? 'selected' : '' }}>SP</option>
                            <option value="SE" {{ old('estado') == 'SE' ? 'selected' : '' }}>SE</option>
                            <option value="TO" {{ old('estado') == 'TO' ? 'selected' : '' }}>TO</option>
                        </select>
                        @error('estado')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cep" class="block text-sm font-medium text-gray-700 mb-2">
                            CEP *
                        </label>
                        <input type="text" 
                               id="cep" 
                               name="cep" 
                               value="{{ old('cep') }}"
                               placeholder="00000-000"
                               maxlength="9"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cep') border-red-500 @enderror"
                               required>
                        @error('cep')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informações Adicionais -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações Adicionais</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="volume_mensal_estimado" class="block text-sm font-medium text-gray-700 mb-2">
                            Volume Mensal Estimado (R$)
                        </label>
                        <input type="number" 
                               id="volume_mensal_estimado" 
                               name="volume_mensal_estimado" 
                               value="{{ old('volume_mensal_estimado') }}"
                               step="0.01"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('volume_mensal_estimado') border-red-500 @enderror">
                        @error('volume_mensal_estimado')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-2">
                            Observações
                        </label>
                        <textarea id="observacoes" 
                                  name="observacoes" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('observacoes') border-red-500 @enderror">{{ old('observacoes') }}</textarea>
                        @error('observacoes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('licenciado.estabelecimentos') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Cadastrar Estabelecimento
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para CNPJ
    const cnpjInput = document.getElementById('cnpj');
    cnpjInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/^(\d{2})(\d)/, '$1.$2');
        value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
        value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
        value = value.replace(/(\d{4})(\d)/, '$1-$2');
        e.target.value = value;
    });

    // Máscara para CEP
    const cepInput = document.getElementById('cep');
    cepInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/^(\d{5})(\d)/, '$1-$2');
        e.target.value = value;
    });

    // Máscara para telefone
    const telefoneInput = document.getElementById('telefone');
    telefoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/^(\d{2})(\d)/, '($1) $2');
        value = value.replace(/(\d{4})(\d)/, '$1-$2');
        e.target.value = value;
    });

    // Máscara para celular
    const celularInput = document.getElementById('celular');
    celularInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/^(\d{2})(\d)/, '($1) $2');
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
        e.target.value = value;
    });
});
</script>
@endsection
