<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>⏰ Confirmação Pendente - Orbita</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Partículas de fundo */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.12) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.08) 0%, transparent 50%);
            pointer-events: none;
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-18px) rotate(0.8deg); }
            66% { transform: translateY(12px) rotate(-0.8deg); }
        }
        
        .container {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            box-shadow: 
                0 25px 80px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.2);
            max-width: 600px;
            width: 100%;
            padding: 60px;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        
        .icon-container {
            position: relative;
            display: inline-block;
            margin-bottom: 40px;
        }
        
        .icon {
            font-size: 120px;
            animation: fadeIn 1.5s ease-out, tick-tock 2s ease-in-out infinite;
            position: relative;
            z-index: 2;
        }
        
        .icon-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 180px;
            height: 180px;
            background: rgba(237, 137, 54, 0.1);
            border-radius: 50%;
            animation: rotate 12s linear infinite;
            z-index: 1;
        }
        
        h1 {
            color: #744210;
            margin-bottom: 20px;
            font-size: 36px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .subtitle {
            color: #92400e;
            font-size: 22px;
            margin-bottom: 40px;
            line-height: 1.5;
            font-weight: 500;
        }
        
        .meeting-title {
            background: linear-gradient(135deg, #fef5e7 0%, #fbd38d 100%);
            border-radius: 20px;
            padding: 30px;
            margin: 40px 0;
            border: 2px solid #f6ad55;
            box-shadow: 0 8px 25px rgba(237, 137, 54, 0.15);
            position: relative;
            overflow: hidden;
        }
        
        .meeting-title::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #ed8936, #dd6b20);
        }
        
        .meeting-title h2 {
            color: #744210;
            font-size: 24px;
            margin: 0;
            font-weight: 600;
        }
        
        .pending-message {
            background: linear-gradient(135deg, #fef5e7 0%, #fbd38d 100%);
            border: 2px solid #ed8936;
            border-radius: 20px;
            padding: 30px;
            margin: 40px 0;
            font-weight: 500;
            font-size: 18px;
            color: #744210;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(237, 137, 54, 0.2);
        }
        
        .pending-message::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
            animation: shimmer 3.5s infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        .reminder-message {
            background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%);
            border: 1px solid #9ae6b4;
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            color: #22543d;
            font-size: 16px;
            line-height: 1.6;
        }
        
        .action-buttons {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border-radius: 20px;
            padding: 30px;
            margin: 30px 0;
            border: 1px solid #cbd5e0;
        }
        
        .action-buttons h3 {
            color: #4a5568;
            margin-bottom: 20px;
            font-size: 20px;
        }
        
        .quick-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        
        .quick-action {
            display: inline-block;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .confirm-action {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
        }
        
        .reject-action {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(229, 62, 62, 0.3);
        }
        
        .quick-action:hover {
            transform: translateY(-2px) scale(1.05);
        }
        
        .buttons-container {
            margin-top: 50px;
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .button {
            display: inline-block;
            padding: 20px 40px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 18px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }
        
        .button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .button:hover::before {
            left: 100%;
        }
        
        .button:hover {
            transform: translateY(-3px) scale(1.05);
        }
        
        .back-button {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(66, 153, 225, 0.3);
        }
        
        .back-button:hover {
            box-shadow: 0 15px 35px rgba(66, 153, 225, 0.4);
        }
        
        .calendar-button {
            background: linear-gradient(135deg, #805ad5 0%, #6b46c1 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(128, 90, 213, 0.3);
        }
        
        .calendar-button:hover {
            box-shadow: 0 15px 35px rgba(128, 90, 213, 0.4);
        }
        
        .footer {
            margin-top: 50px;
            color: #92400e;
            font-size: 16px;
        }
        
        .footer .logo {
            font-size: 24px;
            font-weight: 700;
            color: #744210;
            margin-bottom: 10px;
        }
        
        @keyframes fadeIn {
            0% { opacity: 0; transform: scale(0.5); }
            100% { opacity: 1; transform: scale(1); }
        }
        
        @keyframes tick-tock {
            0%, 100% { transform: rotate(-5deg); }
            50% { transform: rotate(5deg); }
        }
        
        @keyframes rotate {
            from { transform: translate(-50%, -50%) rotate(0deg); }
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }
        
        @media (max-width: 600px) {
            .container {
                padding: 40px 30px;
                margin: 10px;
            }
            
            .icon {
                font-size: 100px;
            }
            
            h1 {
                font-size: 28px;
            }
            
            .subtitle {
                font-size: 18px;
            }
            
            .buttons-container, .quick-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .button, .quick-action {
                width: 100%;
                max-width: 280px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-container">
            <div class="icon-bg"></div>
            <div class="icon">⏰</div>
        </div>
        
        <h1>Confirmação Pendente</h1>
        <div class="subtitle">Você pode confirmar sua participação mais tarde.</div>
        
        @if(isset($titulo) && $titulo)
            <div class="meeting-title">
                <h2>📅 {{ $titulo }}</h2>
            </div>
        @endif
        
        <div class="pending-message">
            <strong>⏰ Status pendente</strong><br>
            Sua resposta ficará como pendente. Você receberá lembretes e pode confirmar 
            ou recusar a qualquer momento antes da reunião.
        </div>
        
        <div class="reminder-message">
            <strong>📢 Lembretes automáticos</strong><br>
            Enviaremos lembretes por email para ajudá-lo a tomar uma decisão antes da data da reunião.
        </div>
        
        <div class="action-buttons">
            <h3>Quer decidir agora?</h3>
            <div class="quick-actions">
                <a href="{{ url('/agenda/confirmar/' . ($agenda_id ?? '1') . '?status=confirmado&email=' . urlencode($email ?? 'teste@exemplo.com')) }}" 
                   class="quick-action confirm-action">✅ Confirmar Agora</a>
                <a href="{{ url('/agenda/confirmar/' . ($agenda_id ?? '1') . '?status=recusado&email=' . urlencode($email ?? 'teste@exemplo.com')) }}" 
                   class="quick-action reject-action">❌ Recusar Agora</a>
            </div>
            <p style="font-size: 14px; color: #718096; margin: 0;">
                Ou você pode aguardar e decidir mais tarde através dos lembretes.
            </p>
        </div>
        
        <div class="buttons-container">
            <a href="{{ url('/') }}" class="button back-button">🏠 Voltar ao Início</a>
            <a href="mailto:?subject=Reunião pendente&body=Marquei como pendente minha participação na reunião: {{ $titulo ?? 'Reunião' }}" 
               class="button calendar-button">📅 Adicionar ao Calendário</a>
        </div>
        
        <div class="footer">
            <div class="logo">🚀 Orbita</div>
            <p>Sistema de Gestão de Reuniões e Agendas</p>
            <small>Status pendente registrado em {{ date('d/m/Y H:i') }}</small>
        </div>
    </div>
</body>
</html>
