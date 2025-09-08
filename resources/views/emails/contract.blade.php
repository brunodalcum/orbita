<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato de Licenciamento - {{ $company_name }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2d3748;
        }
        .message {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 30px;
            color: #4a5568;
        }
        .contract-info {
            background: #f7fafc;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }
        .contract-info h3 {
            margin: 0 0 15px 0;
            color: #2d3748;
            font-size: 18px;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #4a5568;
        }
        .info-value {
            color: #2d3748;
            font-weight: 500;
        }
        .attachment-notice {
            background: #e6fffa;
            border: 1px solid #81e6d9;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        .attachment-notice i {
            font-size: 24px;
            color: #38b2ac;
            margin-bottom: 10px;
            display: block;
        }
        .next-steps {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .next-steps h3 {
            color: #c53030;
            margin: 0 0 15px 0;
            font-size: 18px;
        }
        .next-steps ol {
            margin: 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin-bottom: 8px;
            color: #4a5568;
        }
        .footer {
            background: #2d3748;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .footer p {
            margin: 5px 0;
            opacity: 0.8;
        }
        .company-info {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #4a5568;
        }
        .signature {
            margin-top: 30px;
            font-style: italic;
            color: #718096;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .content {
                padding: 20px 15px;
            }
            .header {
                padding: 20px 15px;
            }
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>📄 Contrato de Licenciamento</h1>
            <p>{{ $company_name }} - Soluções em Pagamentos</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Olá, <strong>{{ $licensee_name }}</strong>!
            </div>

            <div class="message">
                Esperamos que esteja bem! É com grande satisfação que enviamos o seu <strong>contrato de licenciamento</strong> 
                para análise e assinatura.
            </div>

            <!-- Contract Information -->
            <div class="contract-info">
                <h3>📋 Informações do Contrato</h3>
                <div class="info-item">
                    <span class="info-label">Número do Contrato:</span>
                    <span class="info-value">#{{ str_pad($contract_id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Data de Geração:</span>
                    <span class="info-value">{{ $contract_created_at }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Licenciado:</span>
                    <span class="info-value">{{ $licensee_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status:</span>
                    <span class="info-value">📤 Enviado para Análise</span>
                </div>
            </div>

            <!-- Attachment Notice -->
            <div class="attachment-notice">
                📎<br>
                <strong>Documento em Anexo</strong><br>
                O contrato completo está anexado a este e-mail em formato PDF.
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h3>🚀 Próximos Passos</h3>
                <ol>
                    <li><strong>Análise:</strong> Leia atentamente todo o conteúdo do contrato anexado</li>
                    <li><strong>Dúvidas:</strong> Entre em contato conosco caso tenha alguma dúvida</li>
                    <li><strong>Assinatura:</strong> Aguarde as instruções para assinatura digital</li>
                    <li><strong>Ativação:</strong> Após a assinatura, seu licenciamento será ativado</li>
                </ol>
            </div>

            <div class="message">
                Nosso time está à disposição para esclarecer qualquer dúvida sobre o contrato ou o processo de licenciamento.
                <br><br>
                <strong>Estamos ansiosos para tê-lo(a) como nosso parceiro!</strong>
            </div>

            <div class="signature">
                Atenciosamente,<br>
                <strong>Equipe {{ $company_name }}</strong>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="company-info">
                <p><strong>{{ $company_name }}</strong></p>
                <p>Soluções completas em meios de pagamento</p>
                <p>📧 contato@dspay.com.br | 📞 (11) 9999-9999</p>
            </div>
            
            <p style="margin-top: 20px; font-size: 12px; opacity: 0.7;">
                Este é um e-mail automático, mas você pode responder caso tenha dúvidas.
            </p>
        </div>
    </div>
</body>
</html>
