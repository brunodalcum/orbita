<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato Assinado com Sucesso!</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .success-animation {
            animation: successPulse 2s infinite;
        }
        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #fbbf24;
            animation: confetti-fall 3s linear infinite;
        }
        @keyframes confetti-fall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(360deg); opacity: 0; }
        }
        .timeline-item {
            position: relative;
            padding-left: 2rem;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.5rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background: #10b981;
        }
        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 0.4375rem;
            top: 1.5rem;
            width: 2px;
            height: calc(100% - 1rem);
            background: #d1d5db;
        }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen relative overflow-hidden">
    <!-- Confetti Animation -->
    <div class="confetti no-print" style="left: 10%; animation-delay: 0s;"></div>
    <div class="confetti no-print" style="left: 20%; animation-delay: 0.5s; background: #ef4444;"></div>
    <div class="confetti no-print" style="left: 30%; animation-delay: 1s; background: #3b82f6;"></div>
    <div class="confetti no-print" style="left: 40%; animation-delay: 1.5s; background: #8b5cf6;"></div>
    <div class="confetti no-print" style="left: 50%; animation-delay: 2s; background: #10b981;"></div>
    <div class="confetti no-print" style="left: 60%; animation-delay: 0.3s; background: #f59e0b;"></div>
    <div class="confetti no-print" style="left: 70%; animation-delay: 0.8s; background: #ef4444;"></div>
    <div class="confetti no-print" style="left: 80%; animation-delay: 1.3s; background: #3b82f6;"></div>
    <div class="confetti no-print" style="left: 90%; animation-delay: 1.8s; background: #8b5cf6;"></div>

    <!-- Header -->
    <div class="gradient-bg text-white py-8 no-print">
        <div class="max-w-4xl mx-auto px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/dspay-logo.png') }}" alt="DSPAY" class="h-12 w-auto">
                    <div>
                        <h1 class="text-2xl font-bold">Assinatura Concluída</h1>
                        <p class="text-white/80">Contrato #{{ str_pad($contract->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-white/80">Status Atual</div>
                    <div class="text-lg font-semibold flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>✅ Assinado
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-6 py-8 relative z-10">
        <!-- Success Message -->
        <div class="bg-white rounded-xl shadow-2xl p-8 mb-8 text-center success-animation">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-green-600 text-4xl"></i>
            </div>
            
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                🎉 Parabéns! Contrato Assinado com Sucesso!
            </h2>
            
            <p class="text-xl text-gray-600 mb-6">
                Seu contrato foi assinado digitalmente e possui validade jurídica completa.
            </p>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-center space-x-4 text-green-800">
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ str_pad($contract->id, 6, '0', STR_PAD_LEFT) }}</div>
                        <div class="text-sm">Número do Contrato</div>
                    </div>
                    <div class="w-px h-12 bg-green-300"></div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $contract->contract_signed_at ? $contract->contract_signed_at->format('H:i') : now()->format('H:i') }}</div>
                        <div class="text-sm">Horário da Assinatura</div>
                    </div>
                    <div class="w-px h-12 bg-green-300"></div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $contract->contract_signed_at ? $contract->contract_signed_at->format('d/m/Y') : now()->format('d/m/Y') }}</div>
                        <div class="text-sm">Data da Assinatura</div>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4 text-sm">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="flex items-center space-x-2 text-blue-800">
                        <i class="fas fa-user"></i>
                        <span class="font-semibold">Assinante</span>
                    </div>
                    <div class="mt-1 text-blue-700">{{ $contract->licenciado->name }}</div>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="flex items-center space-x-2 text-purple-800">
                        <i class="fas fa-shield-alt"></i>
                        <span class="font-semibold">Segurança</span>
                    </div>
                    <div class="mt-1 text-purple-700">Criptografia SHA-256</div>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-list-check text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Próximos Passos</h3>
                    <p class="text-gray-600">O que acontece agora</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="timeline-item">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-check text-green-600"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">Contrato Assinado</h4>
                            <p class="text-gray-600 text-sm">Sua assinatura foi registrada com sucesso</p>
                            <span class="text-xs text-green-600">✅ Concluído - {{ $contract->contract_signed_at ? $contract->contract_signed_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-cog fa-spin text-blue-600"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">Processamento Automático</h4>
                            <p class="text-gray-600 text-sm">Sistema está processando sua aprovação automaticamente</p>
                            <span class="text-xs text-blue-600">⏳ Em andamento</span>
                        </div>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-gray-400"></i>
                        <div>
                            <h4 class="font-semibold text-gray-700">E-mail de Confirmação</h4>
                            <p class="text-gray-600 text-sm">Você receberá um e-mail com o contrato assinado em anexo</p>
                            <span class="text-xs text-gray-500">📧 Em breve</span>
                        </div>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-key text-gray-400"></i>
                        <div>
                            <h4 class="font-semibold text-gray-700">Acesso Liberado</h4>
                            <p class="text-gray-600 text-sm">Suas credenciais de acesso serão enviadas em até 24h</p>
                            <span class="text-xs text-gray-500">🔑 Próximo passo</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contract Details -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-file-contract text-gray-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Detalhes do Contrato</h3>
                    <p class="text-gray-600">Informações completas da assinatura</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Número do Contrato:</span>
                        <span class="font-medium text-gray-900">#{{ str_pad($contract->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Licenciado:</span>
                        <span class="font-medium text-gray-900">{{ $contract->licenciado->name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Documento:</span>
                        <span class="font-medium text-gray-900">{{ $contract->licenciado->cnpj_cpf }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">E-mail:</span>
                        <span class="font-medium text-gray-900">{{ $contract->licenciado->email }}</span>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Data de Criação:</span>
                        <span class="font-medium text-gray-900">{{ $contract->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Data de Assinatura:</span>
                        <span class="font-medium text-gray-900">{{ $contract->contract_signed_at ? $contract->contract_signed_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>Assinado
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Validade:</span>
                        <span class="font-medium text-gray-900">Permanente</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Information -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-shield-check text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Certificado de Segurança</h3>
                    <p class="text-gray-600">Sua assinatura está protegida</p>
                </div>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-green-800 mb-3">🔐 Recursos de Segurança</h4>
                        <ul class="space-y-2 text-sm text-green-700">
                            <li class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Criptografia SHA-256</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Timestamp criptografado</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>IP e localização registrados</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Hash de integridade único</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-green-800 mb-3">📋 Validade Jurídica</h4>
                        <ul class="space-y-2 text-sm text-green-700">
                            <li class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Lei 14.063/2020 (Marco Legal)</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>MP 2.200-2/2001 (ICP-Brasil)</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Código Civil Art. 219</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Certificado SSL/TLS</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6 text-center no-print">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Precisa de Ajuda?</h3>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="mailto:contrato@dspay.com.br" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center space-x-2">
                    <i class="fas fa-envelope"></i>
                    <span>Enviar E-mail</span>
                </a>
                <a href="tel:+5511999999999" 
                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center space-x-2">
                    <i class="fas fa-phone"></i>
                    <span>Ligar Agora</span>
                </a>
                <button onclick="window.print()" 
                        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center space-x-2">
                    <i class="fas fa-print"></i>
                    <span>Imprimir</span>
                </button>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    Este documento possui validade jurídica e pode ser usado como comprovante da assinatura do contrato.
                </p>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide confetti after animation
        setTimeout(() => {
            document.querySelectorAll('.confetti').forEach(el => {
                el.style.display = 'none';
            });
        }, 6000);

        // Prevent back navigation
        history.pushState(null, null, location.href);
        window.addEventListener('popstate', function() {
            history.pushState(null, null, location.href);
        });
    </script>
</body>
</html>
