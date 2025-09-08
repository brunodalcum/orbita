<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎉 Licenciamento Aprovado!</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin: -40px -40px 40px -40px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .title {
            font-size: 24px;
            margin: 0;
        }
        .celebration {
            font-size: 48px;
            margin: 10px 0;
        }
        .content {
            margin-bottom: 30px;
        }
        .greeting {
            font-size: 20px;
            color: #28a745;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .success-box {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 2px solid #28a745;
            border-radius: 10px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
        }
        .success-box h3 {
            color: #155724;
            margin-top: 0;
            font-size: 20px;
        }
        .contract-details {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .contract-details h4 {
            margin-top: 0;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 12px 0;
            padding: 8px 0;
            border-bottom: 1px dotted #dee2e6;
        }
        .detail-label {
            font-weight: bold;
            color: #495057;
        }
        .detail-value {
            color: #28a745;
            font-weight: 500;
        }
        .next-steps {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            border-radius: 5px;
            padding: 20px;
            margin: 25px 0;
        }
        .next-steps h3 {
            color: #1565c0;
            margin-top: 0;
        }
        .next-steps ul {
            margin: 15px 0;
            padding-left: 25px;
        }
        .next-steps li {
            margin: 8px 0;
            color: #1976d2;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white !important;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
            transition: all 0.3s ease;
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }
        .footer {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            color: #6c757d;
        }
        .footer h4 {
            color: #495057;
            margin-bottom: 15px;
        }
        .contact-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .status-badge {
            display: inline-block;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
        }
        .highlight {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo"><?php echo e($company_name); ?></div>
            <div class="celebration">🎉 🎊 ✨</div>
            <h1 class="title">Licenciamento Aprovado!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Parabéns, <?php echo e($licenciado->razao_social); ?>!
            </div>

            <p>É com grande satisfação que informamos que seu <strong>licenciamento foi aprovado com sucesso</strong>!</p>

            <!-- Success Box -->
            <div class="success-box">
                <h3>✅ Status Atual</h3>
                <div class="status-badge">LICENCIADO ATIVO</div>
                <p>Você já pode começar a utilizar nossos serviços!</p>
            </div>

            <!-- Contract Details -->
            <div class="contract-details">
                <h4>📋 Detalhes do Licenciamento</h4>
                <div class="detail-row">
                    <span class="detail-label">Número do Contrato:</span>
                    <span class="detail-value">#<?php echo e(str_pad($contract->id, 6, '0', STR_PAD_LEFT)); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Licenciado:</span>
                    <span class="detail-value"><?php echo e($licenciado->razao_social); ?></span>
                </div>
                <?php if($licenciado->nome_fantasia && $licenciado->nome_fantasia !== $licenciado->razao_social): ?>
                <div class="detail-row">
                    <span class="detail-label">Nome Fantasia:</span>
                    <span class="detail-value"><?php echo e($licenciado->nome_fantasia); ?></span>
                </div>
                <?php endif; ?>
                <div class="detail-row">
                    <span class="detail-label">CNPJ/CPF:</span>
                    <span class="detail-value"><?php echo e($licenciado->cnpj_cpf); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Data de Aprovação:</span>
                    <span class="detail-value"><?php echo e($approval_date->format('d/m/Y H:i')); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">✅ ATIVO</span>
                </div>
            </div>

            <!-- Highlight Box -->
            <div class="highlight">
                <strong>🚀 Importante:</strong> Seu acesso ao sistema já está liberado! Você pode começar a processar transações imediatamente.
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h3>📝 Próximos Passos</h3>
                <ul>
                    <li><strong>Acesse sua conta:</strong> Use suas credenciais para entrar no sistema</li>
                    <li><strong>Configure seus dados:</strong> Complete seu perfil e configurações</li>
                    <li><strong>Integre seu sistema:</strong> Use nossa API para integrar com sua plataforma</li>
                    <li><strong>Comece a processar:</strong> Suas primeiras transações já podem ser realizadas</li>
                    <li><strong>Suporte técnico:</strong> Nossa equipe está disponível para ajudar</li>
                </ul>
            </div>

            <!-- Action Button -->
            <div style="text-align: center;">
                <a href="<?php echo e(url('/login')); ?>" class="button">
                    🔐 Acessar Minha Conta
                </a>
            </div>

            <p>Estamos muito felizes em tê-lo como nosso parceiro e esperamos uma parceria de muito sucesso!</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <h4>📞 Precisa de Ajuda?</h4>
            <div class="contact-info">
                <p><strong>Suporte Técnico:</strong> Disponível 24/7</p>
                <p><strong>Comercial:</strong> Horário comercial</p>
                <p><strong>Email:</strong> suporte@dspay.com.br</p>
                <p><strong>Telefone:</strong> (11) 1234-5678</p>
            </div>
            
            <p><strong><?php echo e($company_name); ?></strong></p>
            <p>Este é um email automático de confirmação de aprovação.</p>
            <p>Guarde este email para seus registros.</p>
            
            <p style="margin-top: 20px; font-size: 12px; color: #999;">
                Email enviado em <?php echo e(now()->format('d/m/Y H:i')); ?> | Contrato #<?php echo e($contract->id); ?>

            </p>
        </div>
    </div>
</body>
</html>
<?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/emails/licensee-approved.blade.php ENDPATH**/ ?>