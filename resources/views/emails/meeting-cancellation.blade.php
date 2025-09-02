<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reunião Cancelada - Orbita</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2d3748;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            border-radius: 0;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .header .subtitle {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 300;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            color: #4a5568;
            margin-bottom: 25px;
        }
        
        .cancellation-notice {
            background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%);
            border: 1px solid #fc8181;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        
        .cancellation-notice h3 {
            color: #c53030;
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        .meeting-details {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            position: relative;
        }
        
        .meeting-details::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            border-radius: 2px;
        }
        
        .meeting-title {
            font-size: 22px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .detail-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px 0;
        }
        
        .detail-icon {
            width: 24px;
            height: 24px;
            margin-right: 15px;
            color: #e53e3e;
            font-size: 18px;
        }
        
        .detail-label {
            font-weight: 600;
            color: #4a5568;
            min-width: 120px;
        }
        
        .detail-value {
            color: #2d3748;
            flex: 1;
        }
        
        .contact-section {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
            color: white;
        }
        
        .contact-section h3 {
            font-size: 20px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .contact-section p {
            margin-bottom: 20px;
            opacity: 0.9;
        }
        
        .contact-button {
            display: inline-block;
            background-color: #ffffff;
            color: #e53e3e;
            padding: 16px 32px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .contact-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        
        .footer {
            background-color: #f7fafc;
            border-top: 1px solid #e2e8f0;
            padding: 25px 30px;
            text-align: center;
            color: #718096;
            font-size: 14px;
        }
        
        .footer .logo {
            font-size: 18px;
            font-weight: 600;
            color: #e53e3e;
            margin-bottom: 10px;
        }
        
        .footer .disclaimer {
            font-size: 12px;
            opacity: 0.7;
            margin-top: 10px;
        }
        
        @media (max-width: 600px) {
            .content, .header {
                padding: 20px 15px;
            }
            
            .meeting-title {
                font-size: 20px;
            }
            
            .detail-row {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .detail-label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>❌ Reunião Cancelada</h1>
            <div class="subtitle">Sistema Orbita - Gestão de Reuniões</div>
        </div>
        
        <div class="content">
            <div class="greeting">
                <p>Olá! 👋</p>
                <p>Infelizmente uma reunião foi cancelada. Confira os detalhes abaixo:</p>
            </div>
            
            <div class="cancellation-notice">
                <h3>⚠️ Reunião Cancelada</h3>
                <p>Esta reunião foi cancelada pelo organizador. Entre em contato para mais informações.</p>
            </div>
            
            <div class="meeting-details">
                <div class="meeting-title">{{ $titulo }}</div>
                
                @if($organizador)
                    <div class="detail-row">
                        <span class="detail-icon">👤</span>
                        <span class="detail-label">Organizador:</span>
                        <span class="detail-value">{{ $organizador }}</span>
                    </div>
                @endif
                
                <div class="detail-row">
                    <span class="detail-icon">📅</span>
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">Cancelada</span>
                </div>
            </div>
            
            <div class="contact-section">
                <h3>📞 Entre em Contato</h3>
                <p>Para mais informações sobre o cancelamento ou reagendamento, entre em contato com o organizador.</p>
                <a href="mailto:{{ $organizador ?? 'contato@orbita.com' }}" class="contact-button">✉️ Enviar E-mail</a>
            </div>
            
            <div style="text-align: center; margin: 30px 0; padding: 20px; background-color: #fef5e7; border-radius: 8px; border: 1px solid #fed7aa;">
                <p style="color: #c05621; font-weight: 600; margin: 0;">ℹ️ Cancelamento automático enviado pelo sistema Orbita</p>
            </div>
        </div>
        
        <div class="footer">
            <div class="logo">🚀 Orbita</div>
            <p>Sistema de Gestão de Reuniões e Agendas</p>
            <div class="disclaimer">
                Este e-mail foi enviado automaticamente. Não é necessário responder.
            </div>
        </div>
    </div>
</body>
</html>
