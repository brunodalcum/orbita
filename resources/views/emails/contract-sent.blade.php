<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato para Assinatura Digital</title>
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
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .title {
            font-size: 20px;
            color: #333;
            margin: 0;
        }
        .content {
            margin-bottom: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .info-box {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white !important;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .instructions {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .instructions h3 {
            color: #856404;
            margin-top: 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 14px;
            color: #6c757d;
            text-align: center;
        }
        .contract-details {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .contract-details h4 {
            margin-top: 0;
            color: #495057;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            padding: 5px 0;
            border-bottom: 1px dotted #dee2e6;
        }
        .detail-label {
            font-weight: bold;
            color: #495057;
        }
        .detail-value {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">{{ $company_name }}</div>
            <h1 class="title">Contrato para Assinatura Digital</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Olá, <strong>{{ $licenciado->razao_social }}</strong>!
            </div>

            <p>Esperamos que esteja bem! Temos o prazer de informar que seu contrato está pronto para assinatura digital.</p>

            <!-- Contract Details -->
            <div class="contract-details">
                <h4>📋 Detalhes do Contrato</h4>
                <div class="detail-row">
                    <span class="detail-label">Número do Contrato:</span>
                    <span class="detail-value">#{{ str_pad($contract->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Razão Social:</span>
                    <span class="detail-value">{{ $licenciado->razao_social }}</span>
                </div>
                @if($licenciado->nome_fantasia)
                <div class="detail-row">
                    <span class="detail-label">Nome Fantasia:</span>
                    <span class="detail-value">{{ $licenciado->nome_fantasia }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">CNPJ/CPF:</span>
                    <span class="detail-value">{{ $licenciado->cnpj_cpf }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Data de Envio:</span>
                    <span class="detail-value">{{ $contract->sent_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <strong>📎 Anexo:</strong> O contrato em PDF está anexado a este email para sua análise.
            </div>

            <!-- Instructions -->
            <div class="instructions">
                <h3>📝 Como assinar digitalmente:</h3>
                <ol>
                    <li>Clique no botão abaixo para acessar a página de assinatura</li>
                    <li>Revise o contrato cuidadosamente</li>
                    <li>Se concordar com os termos, clique em "Assinar Digitalmente"</li>
                    <li>Sua assinatura será registrada com certificado digital</li>
                    <li>Você receberá uma confirmação por email após a assinatura</li>
                </ol>
            </div>

            <!-- Action Button -->
            <div style="text-align: center;">
                <a href="{{ $signature_url }}" class="button">
                    ✍️ Assinar Contrato Digitalmente
                </a>
            </div>

            <!-- Additional Info -->
            <div class="info-box">
                <strong>⚡ Importante:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Este link é único e seguro, válido apenas para este contrato</li>
                    <li>Após a assinatura, seu status será automaticamente atualizado</li>
                    <li>Em caso de dúvidas, entre em contato conosco</li>
                </ul>
            </div>

            <p>Agradecemos pela confiança e esperamos iniciar nossa parceria em breve!</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ $company_name }}</strong></p>
            <p>Este é um email automático. Por favor, não responda diretamente.</p>
            <p>Em caso de dúvidas, entre em contato através dos nossos canais oficiais.</p>
            <p style="margin-top: 15px; font-size: 12px; color: #999;">
                Email enviado em {{ now()->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>
</body>
</html>
