
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato - {{ $contract->licenciado->name }} - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
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
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <a href="{{ route('contracts.index') }}" class="mr-4 text-gray-600 hover:text-gray-800">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">
                                Contrato - {{ $contract->licenciado->name }}
                            </h1>
                            <p class="text-gray-600 mt-1">{{ $contract->licenciado->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $contract->status_color }}">
                            {{ $contract->status_label }}
                        </span>
                        @if($contract->canSendContract())
                            <button onclick="openGenerateModal()" style="background-color: var(--accent-color);" class="hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-paper-plane mr-2"></i>Enviar Contrato
                            </button>
                        @elseif($contract->contract_pdf_path)
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('contracts.view-pdf', $contract) }}" target="_blank" style="background-color: var(--accent-color);" class="hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-eye mr-2"></i>Exibir Contrato
                                </a>
                                @if($contract->signed_contract_path)
                                    <a href="{{ route('contracts.download', $contract) }}" style="background-color: #8B5CF6;" class="hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                                        <i class="fas fa-file-signature mr-2"></i>Download Assinado
                                    </a>
                                @else
                                    <a href="{{ route('contracts.download', $contract) }}" style="background-color: var(--primary-color);" class="hover:bg-primary-dark text-white px-4 py-2 rounded-lg transition-colors">
                                        <i class="fas fa-download mr-2"></i>Download PDF
                                    </a>
                                @endif
                                @if($contract->status === 'contrato_enviado')
                                    <span style="background-color: var(--primary-light); color: var(--primary-color);" class=" px-3 py-1 rounded-full text-sm">
                                        <i class="fas fa-envelope mr-1"></i>Contrato Enviado
                                    </span>
                                @elseif($contract->status === 'aguardando_assinatura')
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                                        <i class="fas fa-clock mr-1"></i>Aguardando Assinatura
                                    </span>
                                @elseif($contract->status === 'contrato_assinado')
                                    <span style="background-color: var(--accent-color); color: white;" class=" px-3 py-1 rounded-full text-sm">
                                        <i class="fas fa-signature mr-1"></i>Contrato Assinado
                                    </span>
                                @elseif($contract->status === 'licenciado_aprovado')
                                    <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm">
                                        <i class="fas fa-check-circle mr-1"></i>Licenciado Aprovado
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Progress Timeline -->
                    <div class="lg:col-span-1">
                        <div class="card rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6">Progresso do Contrato</h3>
                            
                            <!-- Progress Bar -->
                            <div class="mb-6">
                                <div class="flex justify-between text-sm text-gray-600 mb-2">
                                    <span>Progresso</span>
                                    <span>{{ $contract->progress_percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="progress-bar h-3 rounded-full" style="width: {{ $contract->progress_percentage }}%"></div>
                                </div>
                            </div>

                            <!-- Timeline Steps -->
                            <div class="space-y-4">
                                @php
                                    $steps = [
                                        ['key' => 'criado', 'label' => 'Contrato Criado', 'icon' => 'fas fa-plus'],
                                        ['key' => 'contrato_enviado', 'label' => 'Contrato Enviado', 'icon' => 'fas fa-envelope'],
                                        ['key' => 'aguardando_assinatura', 'label' => 'Aguardando Assinatura', 'icon' => 'fas fa-clock'],
                                        ['key' => 'contrato_assinado', 'label' => 'Contrato Assinado', 'icon' => 'fas fa-signature'],
                                        ['key' => 'licenciado_aprovado', 'label' => 'Licenciado Aprovado', 'icon' => 'fas fa-check-circle']
                                    ];
                                    
                                    $currentStepIndex = array_search($contract->status, array_column($steps, 'key'));
                                @endphp

                                @foreach($steps as $index => $step)
                                    <div class="timeline-item flex items-start {{ $index <= $currentStepIndex ? 'active' : '' }}">
                                        <div class="step-indicator {{ $index < $currentStepIndex ? 'step-completed' : ($index === $currentStepIndex ? 'step-current' : 'step-pending') }}">
                                            @if($index < $currentStepIndex)
                                                <i class="fas fa-check"></i>
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <h4 class="text-sm font-medium {{ $index <= $currentStepIndex ? 'text-gray-900' : 'text-gray-500' }}">
                                                {{ $step['label'] }}
                                            </h4>
                                            @if($step['key'] === $contract->status)
                                                <p class="text-xs text-primary mt-1">Etapa atual</p>
                                            @elseif($index < $currentStepIndex)
                                                <p class="text-xs text-green-600 mt-1">Concluído</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Key Dates -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-800 mb-3">Datas Importantes</h4>
                                <div class="space-y-2 text-xs text-gray-600">
                                    <div>Criado: {{ $contract->created_at->format('d/m/Y H:i') }}</div>
                                    @if($contract->documents_approved_at)
                                        <div>Documentos Aprovados: {{ $contract->documents_approved_at->format('d/m/Y H:i') }}</div>
                                    @endif
                                    @if($contract->contract_sent_at)
                                        <div>Contrato Enviado: {{ $contract->contract_sent_at->format('d/m/Y H:i') }}</div>
                                    @endif
                                    @if($contract->contract_signed_at)
                                        <div>Contrato Assinado: {{ $contract->contract_signed_at->format('d/m/Y H:i') }}</div>
                                    @endif
                                    @if($contract->licenciado_released_at)
                                        <div>Licenciado Liberado: {{ $contract->licenciado_released_at->format('d/m/Y H:i') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Area -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Documents Section -->
                        @if($contract->documents->count() > 0)
                            <div class="card rounded-xl p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-semibold text-gray-800">Documentos Enviados</h3>
                                    @if($contract->canApproveDocuments())
                                        <button onclick="openReviewModal()" style="background-color: var(--primary-color);" class="hover:bg-primary-dark text-white px-4 py-2 rounded-lg transition-colors">
                                            <i class="fas fa-gavel mr-2"></i>Analisar Documentos
                                        </button>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($contract->documents as $document)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex items-start">
                                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                                        @if($document->isPdf())
                                                            <i class="fas fa-file-pdf text-red-500"></i>
                                                        @elseif($document->isImage())
                                                            <i class="fas fa-image text-primary"></i>
                                                        @else
                                                            <i class="fas fa-file text-gray-500"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900">{{ $document->document_type_label }}</h4>
                                                        <p class="text-xs text-gray-500 mt-1">{{ $document->original_name }}</p>
                                                        <p class="text-xs text-gray-400">{{ $document->file_size_formatted }}</p>
                                                    </div>
                                                </div>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $document->status_color }}">
                                                    {{ $document->status_label }}
                                                </span>
                                            </div>

                                            @if($document->admin_notes)
                                                <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                                    <p class="text-xs text-gray-600">{{ $document->admin_notes }}</p>
                                                    @if($document->reviewedBy)
                                                        <p class="text-xs text-gray-500 mt-1">
                                                            Por: {{ $document->reviewedBy->name }} em {{ $document->reviewed_at->format('d/m/Y H:i') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            @endif

                                            <div class="flex justify-between items-center">
                                                <span class="text-xs text-gray-500">
                                                    Enviado em {{ $document->created_at->format('d/m/Y H:i') }}
                                                </span>
                                                <a href="{{ route('contracts.download-document', $document) }}" 
                                                   class="text-primary hover:text-blue-800 text-sm">
                                                    <i class="fas fa-download mr-1"></i>Download
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="card rounded-xl p-6 text-center">
                                <i class="fas fa-file-upload text-gray-300 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum documento enviado</h3>
                                <p class="text-gray-500">O licenciado ainda não enviou os documentos necessários.</p>
                            </div>
                        @endif

                        <!-- Admin Notes -->
                        <div class="card rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Observações do Admin</h3>
                            @if($contract->observacoes_admin)
                                <div style="background-color: var(--primary-light); border-color: var(--primary-color);" class="border rounded-lg p-4 mb-4">
                                    <p class="text-gray-700">{{ $contract->observacoes_admin }}</p>
                                </div>
                            @endif
                            
                            <form action="{{ route('contracts.update-notes', $contract) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="mb-4">
                                    <textarea name="observacoes_admin" rows="3" 
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                              placeholder="Adicionar observações sobre este contrato...">{{ old('observacoes_admin', $contract->observacoes_admin) }}</textarea>
                                </div>
                                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-save mr-2"></i>Salvar Observações
                                </button>
                            </form>
                        </div>

                        <!-- Audit Log -->
                        @if($contract->auditLogs->count() > 0)
                            <div class="card rounded-xl p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Histórico de Ações</h3>
                                <div class="space-y-4 max-h-96 overflow-y-auto">
                                    @foreach($contract->auditLogs->sortByDesc('created_at') as $log)
                                        <div class="flex items-start">
                                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3 mt-0.5">
                                                <i class="{{ $log->action_icon }} {{ $log->action_color }} text-xs"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between">
                                                    <h4 class="text-sm font-medium text-gray-900">{{ $log->action_label }}</h4>
                                                    <span class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-sm text-gray-600 mt-1">{{ $log->description }}</p>
                                                @if($log->user)
                                                    <p class="text-xs text-gray-500 mt-1">Por: {{ $log->user->name }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Review Documents Modal -->
    @if($contract->canApproveDocuments())
        <div id="reviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Analisar Documentos</h3>
                        <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form action="{{ route('contracts.review-documents', $contract) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            @foreach($contract->documents->where('status', 'pendente') as $document)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="font-medium">{{ $document->document_type_label }}</h4>
                                        <a href="{{ route('contracts.download-document', $document) }}" 
                                           class="text-primary hover:text-blue-800 text-sm">
                                            <i class="fas fa-download mr-1"></i>Ver Documento
                                        </a>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-3">
                                        <label class="flex items-center">
                                            <input type="radio" name="document_reviews[{{ $document->id }}][status]" value="aprovado" class="mr-2">
                                            <span class="text-green-600">Aprovar</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="document_reviews[{{ $document->id }}][status]" value="rejeitado" class="mr-2">
                                            <span class="text-red-600">Rejeitar</span>
                                        </label>
                                    </div>
                                    
                                    <textarea name="document_reviews[{{ $document->id }}][notes]" rows="2" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                              placeholder="Observações sobre este documento..."></textarea>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" onclick="closeReviewModal()" 
                                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" 
                                    style="background-color: var(--primary-color);" class="px-4 py-2 hover:bg-primary-dark text-white rounded-lg transition-colors">
                                <i class="fas fa-gavel mr-2"></i>Finalizar Análise
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Generate Contract Modal -->
    @if($contract->canSendContract())
        <div id="generateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Confirmar Envio de Contrato</h3>
                        <button onclick="closeGenerateModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Validação de Campos -->
                    <div id="validationSection" class="mb-6">
                        <div style="background-color: var(--primary-light); border-color: var(--primary-color);" class="border rounded-lg p-4 mb-4">
                            <h4 class="font-medium style="color: var(--primary-dark);" mb-2">Verificando dados obrigatórios...</h4>
                            <div id="validationResults">
                                <div class="flex items-center text-primary">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    Validando campos...
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview dos Dados -->
                    <div id="contractPreview" class="mb-6 hidden">
                        <h4 class="font-medium text-gray-900 mb-3">Dados do Contrato</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-medium text-gray-800 mb-2">Contratante</h5>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <div><strong>Nome:</strong> {{ $contract->licenciado->name ?? 'N/A' }}</div>
                                    <div><strong>Email:</strong> {{ $contract->licenciado->email ?? 'N/A' }}</div>
                                    <div><strong>CNPJ:</strong> <span id="previewCnpj">{{ $contract->licenciado->cnpj ?? 'N/A' }}</span></div>
                                    <div><strong>Cidade/UF:</strong> {{ $contract->licenciado->city ?? 'N/A' }}/{{ $contract->licenciado->state ?? 'N/A' }}</div>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-medium text-gray-800 mb-2">Representante</h5>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <div><strong>Nome:</strong> {{ $contract->licenciado->representative_name ?? $contract->licenciado->name ?? 'N/A' }}</div>
                                    <div><strong>CPF:</strong> <span id="previewCpf">{{ $contract->licenciado->representative_cpf ?? $contract->licenciado->cpf ?? 'N/A' }}</span></div>
                                    <div><strong>Email:</strong> {{ $contract->licenciado->representative_email ?? $contract->licenciado->email ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div id="modalActions" class="flex justify-between space-x-3">
                        <div>
                            <button type="button" onclick="previewPDF()" 
                                    style="background-color: var(--primary-color);" class="px-4 py-2 hover:bg-primary-dark text-white rounded-lg transition-colors" 
                                    id="previewBtn" disabled>
                                <i class="fas fa-eye mr-2"></i>Pré-visualizar PDF
                            </button>
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="closeGenerateModal()" 
                                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition-colors">
                                Cancelar
                            </button>
                            <button type="button" onclick="generateContract()" 
                                    style="background-color: var(--accent-color);" class="px-4 py-2 hover:bg-green-700 text-white rounded-lg transition-colors"
                                    id="generateBtn" disabled>
                                <i class="fas fa-paper-plane mr-2"></i>Gerar e Enviar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function openReviewModal() {
            document.getElementById('reviewModal').classList.remove('hidden');
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
        }

        function openGenerateModal() {
            document.getElementById('generateModal').classList.remove('hidden');
            validateContractFields();
        }

        function closeGenerateModal() {
            document.getElementById('generateModal').classList.add('hidden');
        }

        function validateContractFields() {
            // Simular validação - em produção, fazer chamada AJAX
            setTimeout(() => {
                const validationResults = document.getElementById('validationResults');
                const contractPreview = document.getElementById('contractPreview');
                const previewBtn = document.getElementById('previewBtn');
                const generateBtn = document.getElementById('generateBtn');
                
                // Simular resultado da validação
                const isValid = true; // Em produção, verificar campos reais
                
                if (isValid) {
                    validationResults.innerHTML = `
                        <div class="flex items-center text-green-700">
                            <i class="fas fa-check-circle mr-2"></i>
                            Todos os campos obrigatórios estão preenchidos
                        </div>
                    `;
                    contractPreview.classList.remove('hidden');
                    previewBtn.disabled = false;
                    generateBtn.disabled = false;
                    previewBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    generateBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    validationResults.innerHTML = `
                        <div class="flex items-center text-red-700">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Campos obrigatórios não preenchidos
                        </div>
                        <ul class="mt-2 text-sm text-red-600 list-disc list-inside">
                            <li>CNPJ do contratante</li>
                            <li>Endereço completo</li>
                        </ul>
                    `;
                }
            }, 1500);
        }

        function previewPDF() {
            // Abrir preview em nova aba
            window.open('{{ route("contracts.preview", $contract) }}', '_blank');
        }

        function generateContract() {
            if (confirm('Tem certeza que deseja gerar e enviar o contrato para este licenciado?')) {
                // Mostrar loading
                const generateBtn = document.getElementById('generateBtn');
                const originalText = generateBtn.innerHTML;
                generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Gerando...';
                generateBtn.disabled = true;
                
                // Fazer requisição
                fetch('{{ route("contracts.send", $contract) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // Sucesso - recarregar página
                        window.location.reload();
                    } else {
                        throw new Error('Erro na requisição');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao gerar contrato. Tente novamente.');
                    generateBtn.innerHTML = originalText;
                    generateBtn.disabled = false;
                });
            }
        }

        // Close modals when clicking outside
        document.getElementById('reviewModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeReviewModal();
            }
        });

        document.getElementById('generateModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeGenerateModal();
            }
        });

        // Show toast messages
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif

        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif

        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'text-white' style='background-color: var(--accent-color);' : 'bg-red-500 text-white'
            }`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                    ${message}
                </div>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }
    </script>
</body>
</html>
