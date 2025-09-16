<!DOCTYPE html>
<html lang="pt-BR">
<head>
<x-dynamic-branding />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato {{ str_pad($contract->id, 6, '0', STR_PAD_LEFT) }} - {{ $contract->licenciado->razao_social }}</title>
    
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endif
    
    <style>
        [x-cloak] { display: none !important; }
        .step-completed { @apply bg-emerald-500 text-white border-emerald-500; }
        .step-current { @apply bg-blue-600 text-white border-blue-600 ring-4 ring-blue-200; }
        .step-pending { @apply bg-gray-200 text-gray-500 border-gray-300; }
        .step-error { @apply bg-red-500 text-white border-red-500; }
        .step-disabled { @apply bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed; }
        
        .spinner { animation: spin 1s linear infinite; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        
        .toast-enter { transform: translateX(100%); opacity: 0; }
        .toast-enter-active { transform: translateX(0); opacity: 1; transition: all 0.3s ease-out; }
        .toast-exit { transform: translateX(100%); opacity: 0; transition: all 0.3s ease-in; }
    </style>
</head>

<body class="bg-gray-50 min-h-screen" x-data="contractSteps({{ $contract->id }}, '{{ $contract->status }}')" x-init="init()">
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2" aria-live="polite"></div>
    
    <!-- Sidebar -->
    <x-dynamic-sidebar />
    
    <div class="flex-1 ml-64">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('contracts.index') }}" 
                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Voltar
                    </a>
                    
                    <div class="h-6 border-l border-gray-300"></div>
                    
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            Contrato #{{ str_pad($contract->id, 6, '0', STR_PAD_LEFT) }}
                        </h1>
                        <div class="flex items-center space-x-4 mt-1">
                            <p class="text-sm text-gray-600">{{ $contract->licenciado->razao_social }}</p>
                            <span class="text-gray-400">•</span>
                            <p class="text-sm text-gray-600">{{ $contract->licenciado->cnpj_cpf ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" 
                          :class="getStatusColor(currentStatus)" x-text="getStatusLabel(currentStatus)">
                    </span>
                    
                    <button @click="refreshContract()" 
                            :disabled="loading"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors disabled:opacity-50">
                        <i class="fas fa-sync-alt mr-2" :class="{ 'spinner': loading }"></i>
                        Atualizar
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="p-6">
            <!-- Progress Overview -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Progresso do Contrato</h2>
                    <div class="text-sm text-gray-500">
                        Última atualização: <span x-text="lastUpdated"></span>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-3 mb-6">
                    <div class="bg-blue-500 h-3 rounded-full transition-all duration-500" 
                         :style="`width: ${progressPercentage}%`"></div>
                </div>
                
                <!-- Steps Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <template x-for="(step, index) in steps" :key="step.id">
                        <div class="bg-gray-50 rounded-lg p-4 border-2 transition-all duration-200"
                             :class="getStepCardClass(step)"
                             :data-step-id="step.id">
                            
                            <!-- Step Header -->
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-sm font-bold transition-all"
                                     :class="getStepBadgeClass(step)">
                                    <template x-if="step.status === 'completed'">
                                        <i class="fas fa-check"></i>
                                    </template>
                                    <template x-if="step.status === 'error'">
                                        <i class="fas fa-times"></i>
                                    </template>
                                    <template x-if="step.status === 'current' && step.loading">
                                        <i class="fas fa-spinner spinner"></i>
                                    </template>
                                    <template x-if="!['completed', 'error'].includes(step.status) && !step.loading">
                                        <span x-text="step.id"></span>
                                    </template>
                                </div>
                                
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                      :class="getStepStatusBadgeClass(step)" x-text="getStepStatusLabel(step)">
                                </span>
                            </div>
                            
                            <!-- Step Title -->
                            <h3 class="font-semibold text-gray-900 mb-2" x-text="step.title"></h3>
                            <p class="text-sm text-gray-600 mb-4" x-text="step.description"></p>
                            
                            <!-- Step Content -->
                            <div class="space-y-3">
                                <!-- Step 1: Licenciado Info -->
                                <template x-if="step.id === 1">
                                    <div class="bg-white rounded-lg p-3 border">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-blue-600"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900">{{ $contract->licenciado->razao_social }}</p>
                                                <p class="text-sm text-gray-600">{{ $contract->licenciado->cnpj_cpf ?? 'N/A' }}</p>
                                                <p class="text-xs text-gray-500">{{ $contract->licenciado->email ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                
                                <!-- Step 2: Upload Template -->
                                <template x-if="step.id === 2">
                                    <div class="space-y-3">
                                        <template x-if="step.status === 'completed' && step.result">
                                            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                                                <p class="text-sm text-emerald-700 mb-1">✅ Template carregado</p>
                                                <p class="text-xs text-gray-600" x-text="step.result.template_name"></p>
                                            </div>
                                        </template>
                                        
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                                            <input type="file" 
                                                   id="template-upload" 
                                                   accept=".blade.php,.html,.docx"
                                                   @change="handleFileUpload($event)"
                                                   :disabled="!canExecuteStep(step) || step.loading"
                                                   class="hidden">
                                            <label for="template-upload" 
                                                   class="cursor-pointer block text-center"
                                                   :class="{ 'cursor-not-allowed opacity-50': !canExecuteStep(step) || step.loading }">
                                                <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                                <p class="text-sm text-gray-600">Clique para enviar template</p>
                                                <p class="text-xs text-gray-500">.blade.php, .html ou .docx (max 10MB)</p>
                                            </label>
                                        </div>
                                    </div>
                                </template>
                                
                                <!-- Step 3: Fill Template -->
                                <template x-if="step.id === 3">
                                    <div class="space-y-3">
                                        <template x-if="step.status === 'completed' && step.result">
                                            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                                                <p class="text-sm text-emerald-700 mb-2">✅ Template preenchido</p>
                                                <button @click="showPreview = !showPreview" 
                                                        class="text-sm text-blue-600 hover:text-blue-700">
                                                    <span x-text="showPreview ? 'Ocultar preview' : 'Ver preview'"></span>
                                                </button>
                                                <div x-show="showPreview" x-collapse class="mt-2 p-2 bg-white rounded border text-xs max-h-32 overflow-y-auto">
                                                    <div x-html="step.result.preview_html"></div>
                                                </div>
                                            </div>
                                        </template>
                                        
                                        <button @click="fillTemplate()" 
                                                :disabled="!canExecuteStep(step) || step.loading"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                            <i class="fas fa-edit mr-2" :class="{ 'fa-spinner spinner': step.loading }"></i>
                                            <span x-text="step.loading ? 'Preenchendo...' : 'Preencher Template'"></span>
                                        </button>
                                    </div>
                                </template>
                                
                                <!-- Step 4: Generate PDF -->
                                <template x-if="step.id === 4">
                                    <div class="space-y-3">
                                        <template x-if="step.status === 'completed' && step.result">
                                            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                                                <p class="text-sm text-emerald-700 mb-2">✅ PDF gerado</p>
                                                <a :href="step.result.pdf_url" target="_blank"
                                                   class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700">
                                                    <i class="fas fa-file-pdf mr-1"></i>Visualizar PDF
                                                </a>
                                            </div>
                                        </template>
                                        
                                        <button @click="generatePdf()" 
                                                :disabled="!canExecuteStep(step) || step.loading"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                            <i class="fas fa-file-pdf mr-2" :class="{ 'fa-spinner spinner': step.loading }"></i>
                                            <span x-text="step.loading ? 'Gerando PDF...' : 'Gerar PDF'"></span>
                                        </button>
                                    </div>
                                </template>
                                
                                <!-- Step 5: Send Email -->
                                <template x-if="step.id === 5">
                                    <div class="space-y-3">
                                        <template x-if="step.status === 'completed' && step.result">
                                            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                                                <p class="text-sm text-emerald-700 mb-1">✅ Email enviado</p>
                                                <p class="text-xs text-gray-600">Para: <span x-text="step.result.sent_to"></span></p>
                                                <p class="text-xs text-gray-600">Em: <span x-text="step.result.sent_at"></span></p>
                                            </div>
                                        </template>
                                        
                                        <button @click="sendEmail()" 
                                                :disabled="!canExecuteStep(step) || step.loading"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                            <i class="fas fa-envelope mr-2" :class="{ 'fa-spinner spinner': step.loading }"></i>
                                            <span x-text="step.loading ? 'Enviando...' : 'Enviar por Email'"></span>
                                        </button>
                                    </div>
                                </template>
                                
                                <!-- Step 6: Await Signature -->
                                <template x-if="step.id === 6">
                                    <div class="space-y-3">
                                        <template x-if="step.status === 'completed' && step.result && step.result.signature_data">
                                            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                                                <p class="text-sm text-emerald-700 mb-2">✅ Contrato assinado!</p>
                                                <div class="text-xs text-gray-600 space-y-1">
                                                    <p><strong>Por:</strong> <span x-text="step.result.signature_data.signer_name"></span></p>
                                                    <p><strong>Em:</strong> <span x-text="step.result.signed_at"></span></p>
                                                </div>
                                            </div>
                                        </template>
                                        
                                        <template x-if="step.status === 'current'">
                                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <i class="fas fa-clock text-yellow-600 spinner"></i>
                                                    <p class="text-sm text-yellow-700">Aguardando assinatura...</p>
                                                </div>
                                                <p class="text-xs text-yellow-600">Verificando a cada 15 segundos</p>
                                            </div>
                                        </template>
                                        
                                        <button @click="checkSignature()" 
                                                :disabled="step.status !== 'current' || step.loading"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                            <i class="fas fa-search mr-2" :class="{ 'fa-spinner spinner': step.loading }"></i>
                                            <span x-text="step.loading ? 'Verificando...' : 'Verificar Status'"></span>
                                        </button>
                                    </div>
                                </template>
                                
                                <!-- Step 7: Approve -->
                                <template x-if="step.id === 7">
                                    <div class="space-y-3">
                                        <template x-if="step.status === 'completed' && step.result">
                                            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                                                <p class="text-sm text-emerald-700 mb-2">🎉 Contrato aprovado!</p>
                                                <p class="text-xs text-gray-600 mb-3">Em: <span x-text="step.result.approved_at"></span></p>
                                                <a :href="step.result.licensee_url"
                                                   class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 font-medium">
                                                    <i class="fas fa-user mr-1"></i>Ver Licenciado
                                                </a>
                                            </div>
                                        </template>
                                        
                                        <button @click="approveContract()" 
                                                :disabled="!canExecuteStep(step) || step.loading"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                            <i class="fas fa-check-circle mr-2" :class="{ 'fa-spinner spinner': step.loading }"></i>
                                            <span x-text="step.loading ? 'Aprovando...' : 'Aprovar Contrato'"></span>
                                        </button>
                                    </div>
                                </template>
                                
                                <!-- Error Display -->
                                <template x-if="step.status === 'error' && step.error">
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                        <p class="text-sm text-red-700 mb-2" x-text="step.error"></p>
                                        <button @click="retryStep(step)" 
                                                class="text-sm text-red-600 hover:text-red-700 font-medium">
                                            <i class="fas fa-redo mr-1"></i>Tentar novamente
                                        </button>
                                    </div>
                                </template>
                                
                                <!-- Timestamp -->
                                <template x-if="step.timestamp">
                                    <div class="text-xs text-gray-500">
                                        <i class="fas fa-clock mr-1"></i>
                                        <span x-text="step.timestamp"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </main>
    </div>

    <script>
        function contractSteps(contractId, initialStatus) {
            return {
                contractId: contractId,
                currentStatus: initialStatus,
                loading: false,
                lastUpdated: new Date().toLocaleString('pt-BR'),
                progressPercentage: 14,
                showPreview: false,
                pollingInterval: null,
                
                steps: [
                    { id: 1, title: 'Licenciado Selecionado', description: 'Dados do licenciado vinculado', status: 'completed', loading: false, result: null, error: null, timestamp: null },
                    { id: 2, title: 'Upload de Template', description: 'Carregar modelo de contrato', status: 'pending', loading: false, result: null, error: null, timestamp: null },
                    { id: 3, title: 'Preencher Template', description: 'Substituir placeholders com dados', status: 'pending', loading: false, result: null, error: null, timestamp: null },
                    { id: 4, title: 'Gerar PDF', description: 'Converter template em PDF', status: 'pending', loading: false, result: null, error: null, timestamp: null },
                    { id: 5, title: 'Enviar por Email', description: 'Enviar para assinatura', status: 'pending', loading: false, result: null, error: null, timestamp: null },
                    { id: 6, title: 'Aguardar Assinatura', description: 'Monitorar status de assinatura', status: 'pending', loading: false, result: null, error: null, timestamp: null },
                    { id: 7, title: 'Aprovar Contrato', description: 'Finalizar e liberar licenciado', status: 'pending', loading: false, result: null, error: null, timestamp: null }
                ],
                
                init() {
                    this.updateStepsFromStatus(this.currentStatus);
                    this.updateProgressFromStatus(this.currentStatus);
                    
                    // Iniciar polling se necessário
                    if (this.currentStatus === 'sent') {
                        this.startPolling();
                    }
                    
                    // Foco no próximo step
                    this.$nextTick(() => {
                        this.focusCurrentStep();
                    });
                },
                
                updateStepsFromStatus(status) {
                    // Reset steps
                    this.steps.forEach(step => {
                        step.status = 'pending';
                        step.loading = false;
                        step.error = null;
                    });
                    
                    // Update based on status
                    switch(status) {
                        case 'draft':
                        case 'criado':
                            this.steps[0].status = 'completed';
                            this.steps[1].status = 'current';
                            break;
                        case 'template_uploaded':
                            this.steps[0].status = 'completed';
                            this.steps[1].status = 'completed';
                            this.steps[2].status = 'current';
                            break;
                        case 'filled':
                            this.steps[0].status = 'completed';
                            this.steps[1].status = 'completed';
                            this.steps[2].status = 'completed';
                            this.steps[3].status = 'current';
                            break;
                        case 'pdf_ready':
                            this.steps[0].status = 'completed';
                            this.steps[1].status = 'completed';
                            this.steps[2].status = 'completed';
                            this.steps[3].status = 'completed';
                            this.steps[4].status = 'current';
                            break;
                        case 'sent':
                            this.steps[0].status = 'completed';
                            this.steps[1].status = 'completed';
                            this.steps[2].status = 'completed';
                            this.steps[3].status = 'completed';
                            this.steps[4].status = 'completed';
                            this.steps[5].status = 'current';
                            break;
                        case 'signed':
                            this.steps[0].status = 'completed';
                            this.steps[1].status = 'completed';
                            this.steps[2].status = 'completed';
                            this.steps[3].status = 'completed';
                            this.steps[4].status = 'completed';
                            this.steps[5].status = 'completed';
                            this.steps[6].status = 'current';
                            break;
                        case 'approved':
                            this.steps.forEach(step => step.status = 'completed');
                            break;
                        case 'error':
                            const currentStep = this.steps.find(s => s.status === 'current');
                            if (currentStep) currentStep.status = 'error';
                            break;
                    }
                },
                
                updateProgressFromStatus(status) {
                    const progressMap = {
                        'draft': 0, 'criado': 14, 'template_uploaded': 28, 'filled': 42,
                        'pdf_ready': 57, 'sent': 71, 'signed': 85, 'approved': 100, 'error': 0
                    };
                    this.progressPercentage = progressMap[status] || 0;
                },
                
                canExecuteStep(step) {
                    if (step.id === 1) return false; // Sempre completed
                    const prevStep = this.steps[step.id - 2];
                    return prevStep && prevStep.status === 'completed';
                },
                
                getStepCardClass(step) {
                    switch(step.status) {
                        case 'completed': return 'border-emerald-200 bg-emerald-50';
                        case 'current': return 'border-blue-300 bg-blue-50 ring-2 ring-blue-200';
                        case 'error': return 'border-red-200 bg-red-50';
                        default: return 'border-gray-200';
                    }
                },
                
                getStepBadgeClass(step) {
                    switch(step.status) {
                        case 'completed': return 'step-completed';
                        case 'current': return 'step-current';
                        case 'error': return 'step-error';
                        default: return this.canExecuteStep(step) ? 'step-pending' : 'step-disabled';
                    }
                },
                
                getStepStatusBadgeClass(step) {
                    switch(step.status) {
                        case 'completed': return 'bg-emerald-100 text-emerald-800';
                        case 'current': return 'bg-blue-100 text-blue-800';
                        case 'error': return 'bg-red-100 text-red-800';
                        default: return 'bg-gray-100 text-gray-600';
                    }
                },
                
                getStepStatusLabel(step) {
                    switch(step.status) {
                        case 'completed': return 'Concluído';
                        case 'current': return step.loading ? 'Processando...' : 'Disponível';
                        case 'error': return 'Erro';
                        default: return this.canExecuteStep(step) ? 'Pendente' : 'Bloqueado';
                    }
                },
                
                getStatusColor(status) {
                    const colorMap = {
                        'draft': 'bg-gray-100 text-gray-800', 'criado': 'bg-blue-100 text-blue-800',
                        'template_uploaded': 'bg-indigo-100 text-indigo-800', 'filled': 'bg-purple-100 text-purple-800',
                        'pdf_ready': 'bg-pink-100 text-pink-800', 'sent': 'bg-orange-100 text-orange-800',
                        'signed': 'bg-green-100 text-green-800', 'approved': 'bg-emerald-100 text-emerald-800',
                        'error': 'bg-red-100 text-red-800'
                    };
                    return colorMap[status] || 'bg-gray-100 text-gray-800';
                },
                
                getStatusLabel(status) {
                    const labelMap = {
                        'draft': 'Rascunho', 'criado': 'Licenciado Selecionado', 'template_uploaded': 'Template Carregado',
                        'filled': 'Template Preenchido', 'pdf_ready': 'PDF Gerado', 'sent': 'Enviado por Email',
                        'signed': 'Assinado', 'approved': 'Aprovado', 'error': 'Erro'
                    };
                    return labelMap[status] || 'Status Desconhecido';
                },
                
                async handleFileUpload(event) {
                    const step = this.steps[1];
                    const file = event.target.files[0];
                    if (!file) return;
                    
                    step.loading = true;
                    step.error = null;
                    
                    const formData = new FormData();
                    formData.append('template', file);
                    
                    try {
                        const response = await axios.post(`/contracts/${this.contractId}/upload-template`, formData, {
                            headers: { 'Content-Type': 'multipart/form-data' }
                        });
                        
                        if (response.data.success) {
                            step.status = 'completed';
                            step.result = response.data;
                            step.timestamp = new Date().toLocaleString('pt-BR');
                            
                            this.currentStatus = 'template_uploaded';
                            this.updateStepsFromStatus(this.currentStatus);
                            this.updateProgressFromStatus(this.currentStatus);
                            
                            this.showToast(response.data.message, 'success');
                            this.focusNextStep();
                        }
                    } catch (error) {
                        step.status = 'error';
                        step.error = error.response?.data?.error || 'Erro ao fazer upload';
                        this.showToast(step.error, 'error');
                    } finally {
                        step.loading = false;
                        this.lastUpdated = new Date().toLocaleString('pt-BR');
                        event.target.value = '';
                    }
                },
                
                async fillTemplate() {
                    const step = this.steps[2];
                    step.loading = true;
                    step.error = null;
                    
                    try {
                        const response = await axios.post(`/contracts/${this.contractId}/fill`);
                        
                        if (response.data.success) {
                            step.status = 'completed';
                            step.result = response.data;
                            step.timestamp = new Date().toLocaleString('pt-BR');
                            
                            this.currentStatus = 'filled';
                            this.updateStepsFromStatus(this.currentStatus);
                            this.updateProgressFromStatus(this.currentStatus);
                            
                            this.showToast(response.data.message, 'success');
                            this.focusNextStep();
                        }
                    } catch (error) {
                        step.status = 'error';
                        step.error = error.response?.data?.error || 'Erro ao preencher template';
                        this.showToast(step.error, 'error');
                    } finally {
                        step.loading = false;
                        this.lastUpdated = new Date().toLocaleString('pt-BR');
                    }
                },
                
                async generatePdf() {
                    const step = this.steps[3];
                    step.loading = true;
                    step.error = null;
                    
                    try {
                        const response = await axios.post(`/contracts/${this.contractId}/generate-pdf`);
                        
                        if (response.data.success) {
                            step.status = 'completed';
                            step.result = response.data;
                            step.timestamp = new Date().toLocaleString('pt-BR');
                            
                            this.currentStatus = 'pdf_ready';
                            this.updateStepsFromStatus(this.currentStatus);
                            this.updateProgressFromStatus(this.currentStatus);
                            
                            this.showToast(response.data.message, 'success');
                            this.focusNextStep();
                        }
                    } catch (error) {
                        step.status = 'error';
                        step.error = error.response?.data?.error || 'Erro ao gerar PDF';
                        this.showToast(step.error, 'error');
                    } finally {
                        step.loading = false;
                        this.lastUpdated = new Date().toLocaleString('pt-BR');
                    }
                },
                
                async sendEmail() {
                    const step = this.steps[4];
                    step.loading = true;
                    step.error = null;
                    
                    try {
                        const response = await axios.post(`/contracts/${this.contractId}/send-email`);
                        
                        if (response.data.success) {
                            step.status = 'completed';
                            step.result = response.data;
                            step.timestamp = new Date().toLocaleString('pt-BR');
                            
                            this.currentStatus = 'sent';
                            this.updateStepsFromStatus(this.currentStatus);
                            this.updateProgressFromStatus(this.currentStatus);
                            
                            this.showToast(response.data.message, 'success');
                            this.focusNextStep();
                            this.startPolling();
                        }
                    } catch (error) {
                        step.status = 'error';
                        step.error = error.response?.data?.error || 'Erro ao enviar email';
                        this.showToast(step.error, 'error');
                    } finally {
                        step.loading = false;
                        this.lastUpdated = new Date().toLocaleString('pt-BR');
                    }
                },
                
                async checkSignature() {
                    const step = this.steps[5];
                    step.loading = true;
                    
                    try {
                        const response = await axios.get(`/contracts/${this.contractId}/signature-status`);
                        
                        if (response.data.status === 'signed') {
                            step.status = 'completed';
                            step.result = response.data;
                            step.timestamp = new Date().toLocaleString('pt-BR');
                            
                            this.currentStatus = 'signed';
                            this.updateStepsFromStatus(this.currentStatus);
                            this.updateProgressFromStatus(this.currentStatus);
                            
                            this.showToast('Contrato foi assinado!', 'success');
                            this.stopPolling();
                            this.focusNextStep();
                        } else if (response.data.status === 'error') {
                            step.status = 'error';
                            step.error = 'Erro na assinatura';
                            this.showToast(step.error, 'error');
                            this.stopPolling();
                        }
                    } catch (error) {
                        console.error('Erro ao verificar assinatura:', error);
                    } finally {
                        step.loading = false;
                        this.lastUpdated = new Date().toLocaleString('pt-BR');
                    }
                },
                
                async approveContract() {
                    const step = this.steps[6];
                    step.loading = true;
                    step.error = null;
                    
                    try {
                        const response = await axios.post(`/contracts/${this.contractId}/approve`);
                        
                        if (response.data.success) {
                            step.status = 'completed';
                            step.result = response.data;
                            step.timestamp = new Date().toLocaleString('pt-BR');
                            
                            this.currentStatus = 'approved';
                            this.updateStepsFromStatus(this.currentStatus);
                            this.updateProgressFromStatus(this.currentStatus);
                            
                            this.showToast(response.data.message, 'success');
                        }
                    } catch (error) {
                        step.status = 'error';
                        step.error = error.response?.data?.error || 'Erro ao aprovar contrato';
                        this.showToast(step.error, 'error');
                    } finally {
                        step.loading = false;
                        this.lastUpdated = new Date().toLocaleString('pt-BR');
                    }
                },
                
                async refreshContract() {
                    this.loading = true;
                    try {
                        // Atualizar dados se necessário
                        this.lastUpdated = new Date().toLocaleString('pt-BR');
                    } catch (error) {
                        console.error('Erro ao atualizar:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                
                retryStep(step) {
                    step.status = 'current';
                    step.error = null;
                    step.loading = false;
                },
                
                startPolling() {
                    this.stopPolling();
                    this.pollingInterval = setInterval(() => {
                        if (this.currentStatus === 'sent') {
                            this.checkSignature();
                        } else {
                            this.stopPolling();
                        }
                    }, 15000);
                },
                
                stopPolling() {
                    if (this.pollingInterval) {
                        clearInterval(this.pollingInterval);
                        this.pollingInterval = null;
                    }
                },
                
                focusCurrentStep() {
                    const currentStep = this.steps.find(s => s.status === 'current');
                    if (currentStep) {
                        const stepElement = document.querySelector(`[data-step-id="${currentStep.id}"]`);
                        if (stepElement) {
                            stepElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            const focusableElement = stepElement.querySelector('button, input, [tabindex]');
                            if (focusableElement && !focusableElement.disabled) {
                                setTimeout(() => focusableElement.focus(), 500);
                            }
                        }
                    }
                },
                
                focusNextStep() {
                    this.$nextTick(() => {
                        this.focusCurrentStep();
                    });
                },
                
                showToast(message, type = 'info') {
                    const toast = document.createElement('div');
                    toast.className = `toast-enter max-w-md bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden`;
                    
                    const bgColor = type === 'success' ? 'bg-emerald-50' : type === 'error' ? 'bg-red-50' : 'bg-blue-50';
                    const textColor = type === 'success' ? 'text-emerald-800' : type === 'error' ? 'text-red-800' : 'text-blue-800';
                    const iconColor = type === 'success' ? 'text-emerald-400' : type === 'error' ? 'text-red-400' : 'text-blue-400';
                    const icon = type === 'success' ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 
                                 type === 'error' ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z' : 
                                 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                    
                    toast.innerHTML = `
                        <div class="p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 ${iconColor}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${icon}" />
                                    </svg>
                                </div>
                                <div class="ml-3 w-0 flex-1">
                                    <p class="text-sm font-medium ${textColor}">${message}</p>
                                </div>
                                <div class="ml-4 flex-shrink-0 flex">
                                    <button onclick="this.parentElement.parentElement.parentElement.parentElement.remove()" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    const container = document.getElementById('toast-container');
                    container.appendChild(toast);
                    
                    requestAnimationFrame(() => {
                        toast.classList.remove('toast-enter');
                        toast.classList.add('toast-enter-active');
                    });
                    
                    setTimeout(() => {
                        toast.classList.remove('toast-enter-active');
                        toast.classList.add('toast-exit');
                        setTimeout(() => toast.remove(), 300);
                    }, 5000);
                },
                
                destroy() {
                    this.stopPolling();
                }
            }
        }
    </script>
</body>
</html>