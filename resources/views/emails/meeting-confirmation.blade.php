<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Reunião - Orbita</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            color: #667eea;
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
        
        .meet-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
            color: white;
        }
        
        .meet-section h3 {
            font-size: 20px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .meet-section p {
            margin-bottom: 20px;
            opacity: 0.9;
        }
        
        .meet-button {
            display: inline-block;
            background-color: #ffffff;
            color: #667eea;
            padding: 16px 32px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .meet-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        
        .meet-link-text {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: #4a5568;
            word-break: break-all;
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
            color: #667eea;
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
            <h1>📅 Confirmação de Reunião</h1>
            <div class="subtitle">Sistema Orbita - Gestão de Reuniões</div>
        </div>
        
        <div class="content">
            <div class="greeting">
                <p>Olá! 👋</p>
                <p>Você foi convidado para uma reunião importante. Confira todos os detalhes abaixo:</p>
            </div>
            
            <div class="meeting-details">
                <div class="meeting-title">{{ $titulo }}</div>
                
                @if($descricao)
                    <div class="detail-row">
                        <span class="detail-icon">📝</span>
                        <span class="detail-label">Descrição:</span>
                        <span class="detail-value">{{ $descricao }}</span>
                    </div>
                @endif
                
                <div class="detail-row">
                    <span class="detail-icon">🕐</span>
                    <span class="detail-label">Data de Início:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($data_inicio)->format('d/m/Y \à\s H:i') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-icon">🕐</span>
                    <span class="detail-label">Data de Fim:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($data_fim)->format('d/m/Y \à\s H:i') }}</span>
                </div>
                
                @if($organizador)
                    <div class="detail-row">
                        <span class="detail-icon">👤</span>
                        <span class="detail-label">Organizador:</span>
                        <span class="detail-value">{{ $organizador }}</span>
                    </div>
                @endif
            </div>
            

            
            @if($meet_link)
                <div class="meet-section">
                    <h3>🔗 Link da Reunião</h3>
                    <p>Clique no botão abaixo para acessar a reunião no Google Meet:</p>
                    <a href="{{ $meet_link }}" class="meet-button">🚀 Entrar na Reunião</a>
                    
                    <div style="margin-top: 20px;">
                        <p style="font-size: 14px; opacity: 0.8;">Ou copie e cole este link no seu navegador:</p>
                        <div class="meet-link-text">{{ $meet_link }}</div>
                    </div>
                </div>
            @else
                <div class="meet-section" style="background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);">
                    <h3>⚠️ Reunião Presencial</h3>
                    <p>Esta é uma reunião presencial. Verifique com o organizador o local da reunião.</p>
                </div>
            @endif
            
            <!-- Botões de Confirmação -->
            <div class="confirmation-section">
                <h3 style="text-align: center; color: #4a5568; margin-bottom: 20px;">📋 Confirme sua Participação</h3>
                
                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ url('/agenda/confirmar/' . ($agenda_id ?? 'teste') . '?status=confirmado&email=' . urlencode($participant_email ?? 'participante@exemplo.com')) }}" 
                       style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; text-decoration: none; border-radius: 50px; font-weight: 600; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);">
                        ✅ Confirmar Presença
                    </a>
                    
                    <a href="{{ url('/agenda/confirmar/' . ($agenda_id ?? 'teste') . '?status=pendente&email=' . urlencode($participant_email ?? 'participante@exemplo.com')) }}" 
                       style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 15px 25px; text-decoration: none; border-radius: 50px; font-weight: 600; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);">
                        ⏰ Confirmar Mais Tarde
                    </a>
                    
                    <a href="{{ url('/agenda/confirmar/' . ($agenda_id ?? 'teste') . '?status=recusado&email=' . urlencode($participant_email ?? 'participante@exemplo.com')) }}" 
                       style="background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%); color: white; padding: 15px 25px; text-decoration: none; border-radius: 50px; font-weight: 600; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);">
                        ❌ Não Posso Participar
                    </a>
                </div>
                
                <p style="text-align: center; margin-top: 20px; color: #718096; font-size: 14px;">
                    Clique em um dos botões acima para confirmar sua participação na reunião.
                </p>
            </div>
            
            <div style="text-align: center; margin: 30px 0; padding: 20px; background-color: #f0fff4; border-radius: 8px; border: 1px solid #c6f6d5;">
                <p style="color: #22543d; font-weight: 600; margin: 0;">✅ Confirmação automática enviada pelo sistema Orbita</p>
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
