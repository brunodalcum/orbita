<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assinatura Digital - Contrato <?php echo e($contract->id); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/dspay-logo.png')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/4.1.7/signature_pad.umd.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .signature-canvas {
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            background: #fafafa;
            cursor: crosshair;
        }
        .signature-canvas.signed {
            border-color: #10b981;
            background: #f0fdf4;
        }
        .step {
            transition: all 0.3s ease;
        }
        .step.active {
            transform: scale(1.02);
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .contract-preview {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: white;
        }
        .loading-overlay {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
        }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="gradient-bg text-white py-8 no-print">
        <div class="max-w-4xl mx-auto px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="<?php echo e(asset('images/dspay-logo.png')); ?>" alt="DSPAY" class="h-12 w-auto">
                    <div>
                        <h1 class="text-2xl font-bold">Assinatura Digital</h1>
                        <p class="text-white/80">Contrato de Licenciamento #<?php echo e(str_pad($contract->id, 6, '0', STR_PAD_LEFT)); ?></p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-white/80">Status do Contrato</div>
                    <div class="text-lg font-semibold">📝 Aguardando Assinatura</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-6 py-8">
        <!-- Progress Steps -->
        <div class="mb-8 no-print">
            <div class="flex items-center justify-between">
                <div class="step active flex items-center space-x-2 bg-white p-4 rounded-lg shadow">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                    <span class="font-medium text-gray-900">Revisão do Contrato</span>
                </div>
                <div class="flex-1 h-0.5 bg-gray-300 mx-4"></div>
                <div class="step flex items-center space-x-2 bg-white p-4 rounded-lg shadow opacity-50">
                    <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold">2</div>
                    <span class="font-medium text-gray-600">Dados Pessoais</span>
                </div>
                <div class="flex-1 h-0.5 bg-gray-300 mx-4"></div>
                <div class="step flex items-center space-x-2 bg-white p-4 rounded-lg shadow opacity-50">
                    <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold">3</div>
                    <span class="font-medium text-gray-600">Assinatura Digital</span>
                </div>
            </div>
        </div>

        <!-- Step 1: Contract Review -->
        <div id="step1" class="step-content">
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-file-contract text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Revisão do Contrato</h2>
                        <p class="text-gray-600">Leia atentamente todos os termos antes de prosseguir</p>
                    </div>
                </div>

                <!-- Contract Info -->
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-900 mb-3">📋 Informações do Contrato</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Número:</span>
                                <span class="font-medium">#<?php echo e(str_pad($contract->id, 6, '0', STR_PAD_LEFT)); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Data de Criação:</span>
                                <span class="font-medium"><?php echo e($contract->created_at->format('d/m/Y')); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Licenciado:</span>
                                <span class="font-medium"><?php echo e($licenciado->name); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Documento:</span>
                                <span class="font-medium"><?php echo e($licenciado->cnpj_cpf); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-900 mb-3">🔒 Segurança da Assinatura</h3>
                        <div class="space-y-2 text-sm text-gray-700">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-shield-alt text-green-600"></i>
                                <span>Certificado SSL ativo</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-fingerprint text-green-600"></i>
                                <span>IP e dispositivo registrados</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-clock text-green-600"></i>
                                <span>Timestamp criptografado</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-file-signature text-green-600"></i>
                                <span>Hash de integridade</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contract Preview -->
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3">📄 Prévia do Contrato</h3>
                    <div class="contract-preview p-6">
                        <div class="text-center mb-6">
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">CONTRATO DE LICENCIAMENTO</h1>
                            <p class="text-gray-600">DSPAY - Soluções em Pagamentos</p>
                        </div>
                        
                        <div class="space-y-4 text-sm text-gray-700 leading-relaxed">
                            <p><strong>CONTRATANTE:</strong> DSPAY LTDA, pessoa jurídica de direito privado...</p>
                            <p><strong>CONTRATADO:</strong> <?php echo e($licenciado->name); ?>, <?php echo e($licenciado->cnpj_cpf); ?></p>
                            
                            <h4 class="font-semibold text-gray-900 mt-6">CLÁUSULA 1ª - DO OBJETO</h4>
                            <p>O presente contrato tem por objeto o licenciamento de uso da plataforma DSPAY para processamento de pagamentos...</p>
                            
                            <h4 class="font-semibold text-gray-900 mt-4">CLÁUSULA 2ª - DAS OBRIGAÇÕES</h4>
                            <p>São obrigações do CONTRATADO: cumprir as normas estabelecidas, manter sigilo das informações...</p>
                            
                            <div class="text-center mt-6 p-4 bg-gray-100 rounded-lg">
                                <p class="text-gray-600"><i class="fas fa-info-circle mr-2"></i>
                                Este é apenas um resumo. O contrato completo será exibido após a confirmação dos dados.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms Acceptance -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-yellow-800">Importante</h4>
                            <p class="text-yellow-700 text-sm mt-1">
                                Ao prosseguir, você declara ter lido e compreendido todos os termos do contrato. 
                                A assinatura digital terá o mesmo valor jurídico de uma assinatura manuscrita.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-3 mb-6">
                    <input type="checkbox" id="terms-agreement" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                    <label for="terms-agreement" class="text-sm text-gray-700">
                        Eu li, compreendi e concordo com todos os termos e condições deste contrato
                    </label>
                </div>

                <div class="flex justify-end">
                    <button id="btn-next-step1" onclick="nextStep(2)" disabled 
                            class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white px-8 py-3 rounded-lg font-medium transition-colors">
                        Prosseguir para Dados Pessoais
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 2: Personal Data -->
        <div id="step2" class="step-content hidden">
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user-check text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Confirmação de Dados Pessoais</h2>
                        <p class="text-gray-600">Confirme seus dados para validar a assinatura</p>
                    </div>
                </div>

                <form id="signature-form" class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2"></i>Nome Completo *
                            </label>
                            <input type="text" id="full_name" name="full_name" required
                                   value="<?php echo e($licenciado->name); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-id-card mr-2"></i>CPF/CNPJ *
                            </label>
                            <input type="text" id="document" name="document" required
                                   value="<?php echo e($licenciado->cnpj_cpf); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2"></i>E-mail *
                            </label>
                            <input type="email" id="email" name="email" required
                                   value="<?php echo e($licenciado->email); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar mr-2"></i>Data da Assinatura *
                            </label>
                            <input type="date" id="signature_date" name="signature_date" required
                                   value="<?php echo e(date('Y-m-d')); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Security Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-3">🔐 Informações de Segurança</h4>
                        <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <strong>IP de Acesso:</strong> <span id="user-ip">Detectando...</span>
                            </div>
                            <div>
                                <strong>Navegador:</strong> <span id="user-agent"><?php echo e(request()->userAgent()); ?></span>
                            </div>
                            <div>
                                <strong>Data/Hora:</strong> <span id="current-datetime"><?php echo e(now()->format('d/m/Y H:i:s')); ?></span>
                            </div>
                            <div>
                                <strong>Localização:</strong> <span id="user-location">Detectando...</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <button type="button" onclick="nextStep(1)" 
                                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Voltar
                        </button>
                        <button type="button" id="btn-next-step2" onclick="validateDataAndNext()" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-colors">
                            Prosseguir para Assinatura
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Step 3: Digital Signature -->
        <div id="step3" class="step-content hidden">
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-signature text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Assinatura Digital</h2>
                        <p class="text-gray-600">Desenhe sua assinatura no campo abaixo</p>
                    </div>
                </div>

                <!-- Signature Pad -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-pen-fancy mr-2"></i>Sua Assinatura Digital *
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-50">
                        <canvas id="signature-pad" class="signature-canvas w-full h-48"></canvas>
                        <div class="flex justify-between items-center mt-3">
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Desenhe sua assinatura usando o mouse ou toque na tela
                            </p>
                            <button type="button" id="clear-signature" 
                                    class="text-red-600 hover:text-red-700 text-sm font-medium">
                                <i class="fas fa-eraser mr-1"></i>Limpar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Final Confirmation -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-exclamation-circle text-red-600 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-red-800">Confirmação Final</h4>
                            <p class="text-red-700 text-sm mt-1">
                                Ao clicar em "Assinar Contrato", você estará concordando eletronicamente com todos os termos 
                                e condições. Esta ação é irreversível e possui validade jurídica.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-3 mb-6">
                    <input type="checkbox" id="final-confirmation" class="w-4 h-4 text-red-600 rounded focus:ring-red-500">
                    <label for="final-confirmation" class="text-sm text-gray-700">
                        Confirmo que todos os dados estão corretos e desejo assinar este contrato digitalmente
                    </label>
                </div>

                <div class="flex justify-between">
                    <button type="button" onclick="nextStep(2)" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Voltar
                    </button>
                    <button type="button" id="btn-sign-contract" onclick="signContract()" disabled
                            class="bg-red-600 hover:bg-red-700 disabled:bg-gray-400 text-white px-8 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-signature mr-2"></i>Assinar Contrato
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="fixed inset-0 loading-overlay hidden z-50">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white rounded-xl shadow-2xl p-8 text-center max-w-md">
                    <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-600 mx-auto mb-4"></div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Processando Assinatura...</h3>
                    <p class="text-gray-600 text-sm">Aguarde enquanto validamos e registramos sua assinatura digital.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variables
        let signaturePad;
        let currentStep = 1;
        const contractToken = '<?php echo e($contract->signature_token); ?>';

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            initializeSignaturePad();
            setupEventListeners();
            detectUserInfo();
        });

        function initializeSignaturePad() {
            const canvas = document.getElementById('signature-pad');
            
            if (!canvas) {
                console.error('Canvas de assinatura não encontrado!');
                return;
            }

            // Aguardar um momento para garantir que o canvas está visível
            setTimeout(() => {
                try {
                    signaturePad = new SignaturePad(canvas, {
                        backgroundColor: '#fafafa',
                        penColor: '#1f2937',
                        minWidth: 2,
                        maxWidth: 4,
                        throttle: 16,
                        minDistance: 5,
                    });

                    // Resize canvas
                    function resizeCanvas() {
                        try {
                            const ratio = Math.max(window.devicePixelRatio || 1, 1);
                            const rect = canvas.getBoundingClientRect();
                            
                            if (rect.width === 0 || rect.height === 0) {
                                console.warn('Canvas tem dimensões zero, tentando novamente...');
                                setTimeout(resizeCanvas, 100);
                                return;
                            }
                            
                            canvas.width = rect.width * ratio;
                            canvas.height = rect.height * ratio;
                            canvas.getContext("2d").scale(ratio, ratio);
                            signaturePad.clear();
                            
                            console.log('Canvas redimensionado:', rect.width, 'x', rect.height);
                        } catch (error) {
                            console.error('Erro ao redimensionar canvas:', error);
                        }
                    }

                    window.addEventListener("resize", resizeCanvas);
                    resizeCanvas();

                    signaturePad.addEventListener("endStroke", function() {
                        canvas.classList.add('signed');
                        updateSignButton();
                        console.log('Assinatura detectada');
                    });
                    
                    console.log('SignaturePad inicializado com sucesso');
                } catch (error) {
                    console.error('Erro ao inicializar SignaturePad:', error);
                }
            }, 100);
        }

        function setupEventListeners() {
            // Terms agreement
            document.getElementById('terms-agreement').addEventListener('change', function() {
                document.getElementById('btn-next-step1').disabled = !this.checked;
            });

            // Final confirmation
            document.getElementById('final-confirmation').addEventListener('change', function() {
                updateSignButton();
            });

            // Clear signature
            document.getElementById('clear-signature').addEventListener('click', function() {
                if (signaturePad) {
                    signaturePad.clear();
                    document.getElementById('signature-pad').classList.remove('signed');
                    updateSignButton();
                    console.log('Assinatura limpa');
                } else {
                    console.warn('SignaturePad não está inicializado');
                }
            });

            // Form validation
            const formInputs = document.querySelectorAll('#signature-form input[required]');
            formInputs.forEach(input => {
                input.addEventListener('input', validateForm);
            });
        }

        function detectUserInfo() {
            // Detect IP
            fetch('https://api.ipify.org?format=json')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('user-ip').textContent = data.ip;
                })
                .catch(() => {
                    document.getElementById('user-ip').textContent = 'Não detectado';
                });

            // Update datetime every second
            setInterval(() => {
                const now = new Date();
                document.getElementById('current-datetime').textContent = 
                    now.toLocaleString('pt-BR');
            }, 1000);

            // Detect location (optional)
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        document.getElementById('user-location').textContent = 
                            `${position.coords.latitude.toFixed(4)}, ${position.coords.longitude.toFixed(4)}`;
                    },
                    () => {
                        document.getElementById('user-location').textContent = 'Não autorizado';
                    }
                );
            }
        }

        function nextStep(step) {
            // Hide all steps
            document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
            
            // Show target step
            document.getElementById(`step${step}`).classList.remove('hidden');
            
            // Update progress
            updateProgress(step);
            currentStep = step;

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function updateProgress(activeStep) {
            document.querySelectorAll('.step').forEach((step, index) => {
                if (index + 1 <= activeStep) {
                    step.classList.add('active');
                    step.classList.remove('opacity-50');
                    step.querySelector('div').classList.remove('bg-gray-300', 'text-gray-600');
                    step.querySelector('div').classList.add('bg-blue-600', 'text-white');
                    step.querySelector('span').classList.remove('text-gray-600');
                    step.querySelector('span').classList.add('text-gray-900');
                } else {
                    step.classList.remove('active');
                    step.classList.add('opacity-50');
                }
            });
        }

        function validateForm() {
            const formInputs = document.querySelectorAll('#signature-form input[required]');
            const allValid = Array.from(formInputs).every(input => input.value.trim() !== '');
            document.getElementById('btn-next-step2').disabled = !allValid;
            return allValid;
        }

        function validateDataAndNext() {
            if (validateForm()) {
                nextStep(3);
            }
        }

        function updateSignButton() {
            const hasSignature = !signaturePad.isEmpty();
            const hasConfirmation = document.getElementById('final-confirmation').checked;
            document.getElementById('btn-sign-contract').disabled = !(hasSignature && hasConfirmation);
        }

        function signContract() {
            if (signaturePad.isEmpty()) {
                alert('Por favor, desenhe sua assinatura antes de continuar.');
                return;
            }

            if (!document.getElementById('final-confirmation').checked) {
                alert('Por favor, confirme que deseja assinar o contrato.');
                return;
            }

            // Show loading
            document.getElementById('loading-overlay').classList.remove('hidden');

            // Get signature data
            const signatureDataURL = signaturePad.toDataURL();
            
            // Prepare form data
            const formData = {
                full_name: document.getElementById('full_name').value,
                document: document.getElementById('document').value,
                email: document.getElementById('email').value,
                signature_date: document.getElementById('signature_date').value,
                signature_data: signatureDataURL,
                ip_address: document.getElementById('user-ip').textContent,
                user_agent: navigator.userAgent,
                location: document.getElementById('user-location').textContent,
                timestamp: new Date().toISOString()
            };

            // Submit signature
            fetch(`/contracts/sign/${contractToken}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading-overlay').classList.add('hidden');
                
                if (data.success) {
                    // Redirect to success page
                    window.location.href = data.redirect;
                } else {
                    alert('Erro ao processar assinatura: ' + (data.message || data.error));
                }
            })
            .catch(error => {
                document.getElementById('loading-overlay').classList.add('hidden');
                console.error('Erro:', error);
                alert('Erro de conexão. Tente novamente.');
            });
        }

        // Prevent accidental page leave
        window.addEventListener('beforeunload', function(e) {
            if (currentStep > 1) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    </script>
</body>
</html>
<?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/contracts/sign.blade.php ENDPATH**/ ?>