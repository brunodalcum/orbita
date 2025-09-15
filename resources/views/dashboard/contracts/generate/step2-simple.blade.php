<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Contrato - Etapa 3 - DSPay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Branding Dinâmico -->
    <x-dynamic-branding />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-50">
    <div class="min-h-screen p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <h1 class="text-2xl font-bold " style="color: var(--text-color);" mb-2">
                    <i class="fas fa-file-contract " style="color: var(--primary-color);" mr-3"></i>
                    Gerar Contrato - Etapa 3
                </h1>
                <p class="" style="color: var(--secondary-color);"">Revisar e gerar o contrato final</p>
            </div>

            <!-- Licensee Info -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-user-check " style="color: var(--primary-color);" mr-2"></i>
                    Licenciado Selecionado
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Nome/Razão Social</label>
                        <p class="text-gray-900">{{ $licenciado->razao_social ?: $licenciado->nome_fantasia }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-600">CNPJ/CPF</label>
                        <p class="text-gray-900">{{ $licenciado->cnpj_cpf }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-600">Email</label>
                        <p class="text-gray-900">{{ $licenciado->email }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-600">Template</label>
                        <p class="text-gray-900">{{ $template->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Contract Preview -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-eye " style="color: var(--primary-color);" mr-2"></i>
                    Preview do Contrato
                </h3>
                
                <div class="border rounded-lg p-4 bg-gray-50 max-h-96 overflow-y-auto">
                    <div class="prose max-w-none text-sm">
                        {!! Str::limit(strip_tags($previewContent), 500) !!}
                        @if(strlen(strip_tags($previewContent)) > 500)
                            <p class="text-gray-500 mt-2">... (conteúdo truncado para preview)</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Generation Form -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-cog " style="color: var(--primary-color);" mr-2"></i>
                    Gerar Contrato
                </h3>

                <form method="POST" action="{{ route('contracts.generate.step3') }}">
                    @csrf
                    <input type="hidden" name="licenciado_id" value="{{ $licenciado->id }}">
                    <input type="hidden" name="template_id" value="{{ $template->id }}">

                    <!-- Admin Notes -->
                    <div class="mb-6">
                        <label for="observacoes_admin" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sticky-note mr-2"></i>Observações Administrativas (Opcional)
                        </label>
                        <textarea name="observacoes_admin"
                                  id="observacoes_admin"
                                  rows="3"
                                  placeholder="Adicione observações internas sobre este contrato..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between">
                        <a href="{{ route('contracts.generate.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Voltar ao Início
                        </a>

                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition-colors">
                            <i class="fas fa-file-pdf mr-2"></i>Gerar Contrato
                        </button>
                    </div>
                </form>
            </div>

            <!-- Debug Info -->
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h4 class="font-medium text-yellow-800 mb-2">🔍 Debug Info:</h4>
                <div class="text-sm text-yellow-700">
                    <p><strong>Licenciado ID:</strong> {{ $licenciado->id }}</p>
                    <p><strong>Template ID:</strong> {{ $template->id }}</p>
                    <p><strong>CSRF Token:</strong> {{ csrf_token() }}</p>
                    <p><strong>Route:</strong> {{ route('contracts.generate.step3') }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
