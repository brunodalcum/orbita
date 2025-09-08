<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro na Assinatura</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/dspay-logo.png')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="gradient-bg text-white py-8">
        <div class="max-w-4xl mx-auto px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="<?php echo e(asset('images/dspay-logo.png')); ?>" alt="DSPAY" class="h-12 w-auto">
                    <div>
                        <h1 class="text-2xl font-bold">Erro na Assinatura</h1>
                        <p class="text-white/80">Contrato #<?php echo e(str_pad($contract->id, 6, '0', STR_PAD_LEFT)); ?></p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-white/80">Status Atual</div>
                    <div class="text-lg font-semibold flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>❌ <?php echo e(ucfirst(str_replace('_', ' ', $contract->status))); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-6 py-8">
        <!-- Error Message -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8 text-center">
            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-exclamation-triangle text-red-600 text-4xl"></i>
            </div>
            
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Não é Possível Assinar Este Contrato
            </h2>
            
            <p class="text-xl text-gray-600 mb-6">
                <?php echo e($message ?? 'Este contrato não está disponível para assinatura no momento.'); ?>

            </p>

            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                <div class="flex items-center justify-center space-x-4 text-red-800">
                    <div class="text-center">
                        <div class="text-2xl font-bold"><?php echo e(str_pad($contract->id, 6, '0', STR_PAD_LEFT)); ?></div>
                        <div class="text-sm">Número do Contrato</div>
                    </div>
                    <div class="w-px h-12 bg-red-300"></div>
                    <div class="text-center">
                        <div class="text-2xl font-bold"><?php echo e(ucfirst(str_replace('_', ' ', $contract->status))); ?></div>
                        <div class="text-sm">Status Atual</div>
                    </div>
                    <div class="w-px h-12 bg-red-300"></div>
                    <div class="text-center">
                        <div class="text-2xl font-bold"><?php echo e($contract->created_at->format('d/m/Y')); ?></div>
                        <div class="text-sm">Data de Criação</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Information -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Possíveis Motivos</h3>
                    <p class="text-gray-600">Por que este contrato não pode ser assinado</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-red-500 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">Contrato Já Assinado</h4>
                            <p class="text-gray-600 text-sm">Este contrato já foi assinado anteriormente</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-clock text-red-500 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">Link Expirado</h4>
                            <p class="text-gray-600 text-sm">O link de assinatura pode ter expirado</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-ban text-red-500 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">Contrato Cancelado</h4>
                            <p class="text-gray-600 text-sm">O contrato foi cancelado pelo administrador</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-cog text-red-500 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">Em Processamento</h4>
                            <p class="text-gray-600 text-sm">O contrato ainda está sendo processado</p>
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
                    <p class="text-gray-600">Informações do contrato</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Número do Contrato:</span>
                        <span class="font-medium text-gray-900">#<?php echo e(str_pad($contract->id, 6, '0', STR_PAD_LEFT)); ?></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Licenciado:</span>
                        <span class="font-medium text-gray-900"><?php echo e($contract->licenciado->name); ?></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">E-mail:</span>
                        <span class="font-medium text-gray-900"><?php echo e($contract->licenciado->email); ?></span>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Data de Criação:</span>
                        <span class="font-medium text-gray-900"><?php echo e($contract->created_at->format('d/m/Y H:i')); ?></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Status Atual:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <?php echo e(ucfirst(str_replace('_', ' ', $contract->status))); ?>

                        </span>
                    </div>
                    <?php if($contract->contract_signed_at): ?>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Data de Assinatura:</span>
                        <span class="font-medium text-gray-900"><?php echo e($contract->contract_signed_at->format('d/m/Y H:i')); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-lightbulb text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">O Que Fazer Agora?</h3>
                    <p class="text-gray-600">Próximos passos recomendados</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-phone text-blue-600 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-blue-800">Entre em Contato</h4>
                            <p class="text-blue-700 text-sm mt-1">
                                Ligue para nossa equipe para esclarecer a situação do seu contrato.
                            </p>
                            <div class="mt-2">
                                <a href="tel:+5511999999999" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                    📞 (11) 9999-9999
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-envelope text-green-600 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-green-800">Envie um E-mail</h4>
                            <p class="text-green-700 text-sm mt-1">
                                Envie uma mensagem detalhando sua situação e o número do contrato.
                            </p>
                            <div class="mt-2">
                                <a href="mailto:contrato@dspay.com.br?subject=Problema com Contrato #<?php echo e(str_pad($contract->id, 6, '0', STR_PAD_LEFT)); ?>" 
                                   class="text-green-600 hover:text-green-700 font-medium text-sm">
                                    📧 contrato@dspay.com.br
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if($contract->status === 'contrato_assinado'): ?>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-purple-600 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-purple-800">Contrato Já Assinado</h4>
                            <p class="text-purple-700 text-sm mt-1">
                                Seu contrato já foi assinado com sucesso. Você deve ter recebido um e-mail de confirmação.
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6 text-center">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Precisa de Ajuda Imediata?</h3>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="mailto:contrato@dspay.com.br?subject=Problema com Contrato #<?php echo e(str_pad($contract->id, 6, '0', STR_PAD_LEFT)); ?>" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center space-x-2">
                    <i class="fas fa-envelope"></i>
                    <span>Enviar E-mail</span>
                </a>
                <a href="tel:+5511999999999" 
                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center space-x-2">
                    <i class="fas fa-phone"></i>
                    <span>Ligar Agora</span>
                </a>
                <a href="https://wa.me/5511999999999?text=Olá, preciso de ajuda com o contrato #<?php echo e(str_pad($contract->id, 6, '0', STR_PAD_LEFT)); ?>" 
                   target="_blank"
                   class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center space-x-2">
                    <i class="fab fa-whatsapp"></i>
                    <span>WhatsApp</span>
                </a>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-600">
                    <i class="fas fa-clock mr-1"></i>
                    Horário de atendimento: Segunda a Sexta, das 9h às 18h
                </p>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/contracts/sign-error.blade.php ENDPATH**/ ?>