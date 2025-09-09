<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>❌ Participação Recusada - Orbita</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
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
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            pointer-events: none;
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-15px) rotate(0.5deg); }
            66% { transform: translateY(8px) rotate(-0.5deg); }
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
            animation: fadeIn 1.5s ease-out, gentle-sway 3s ease-in-out infinite;
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
            background: rgba(229, 62, 62, 0.1);
            border-radius: 50%;
            animation: rotate 15s linear infinite;
            z-index: 1;
        }
        
        h1 {
            color: #742a2a;
            margin-bottom: 20px;
            font-size: 36px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .subtitle {
            color: #9b2c2c;
            font-size: 22px;
            margin-bottom: 40px;
            line-height: 1.5;
            font-weight: 500;
        }
        
        .meeting-title {
            background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%);
            border-radius: 20px;
            padding: 30px;
            margin: 40px 0;
            border: 2px solid #fc8181;
            box-shadow: 0 8px 25px rgba(229, 62, 62, 0.15);
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
            background: linear-gradient(90deg, #e53e3e, #c53030);
        }
        
        .meeting-title h2 {
            color: #742a2a;
            font-size: 24px;
            margin: 0;
            font-weight: 600;
        }
        
        .rejection-message {
            background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%);
            border: 2px solid #e53e3e;
            border-radius: 20px;
            padding: 30px;
            margin: 40px 0;
            font-weight: 500;
            font-size: 18px;
            color: #742a2a;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(229, 62, 62, 0.2);
        }
        
        .rejection-message::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shimmer 4s infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        .understanding-message {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border: 1px solid #cbd5e0;
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            color: #4a5568;
            font-size: 16px;
            line-height: 1.6;
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
        
        .contact-button {
            background: linear-gradient(135deg, #805ad5 0%, #6b46c1 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(128, 90, 213, 0.3);
        }
        
        .contact-button:hover {
            box-shadow: 0 15px 35px rgba(128, 90, 213, 0.4);
        }
        
        .footer {
            margin-top: 50px;
            color: #9b2c2c;
            font-size: 16px;
        }
        
        .footer .logo {
            font-size: 24px;
            font-weight: 700;
            color: #742a2a;
            margin-bottom: 10px;
        }
        
        @keyframes fadeIn {
            0% { opacity: 0; transform: scale(0.5); }
            100% { opacity: 1; transform: scale(1); }
        }
        
        @keyframes gentle-sway {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-2deg); }
            75% { transform: rotate(2deg); }
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
            
            .buttons-container {
                flex-direction: column;
                align-items: center;
            }
            
            .button {
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
            <div class="icon">😔</div>
        </div>
        
        <h1>Participação Recusada</h1>
        <div class="subtitle">Entendemos que você não poderá participar desta vez.</div>
        
        @if(isset($titulo) && $titulo)
            <div class="meeting-title">
                <h2>📅 {{ $titulo }}</h2>
            </div>
        @endif
        
        <div class="rejection-message">
            <strong>❌ Participação recusada</strong><br>
            Você informou que não poderá participar da reunião. O organizador foi notificado 
            automaticamente sobre sua decisão.
        </div>
        
        <div class="understanding-message">
            <strong>💙 Compreendemos sua situação</strong><br>
            Sabemos que às vezes é impossível participar de todos os eventos. Esperamos contar 
            com sua presença em futuras reuniões e oportunidades.
        </div>
        
        <div class="buttons-container">
            <a href="{{ url('/') }}" class="button back-button">🏠 Voltar ao Início</a>
            <a href="mailto:organizador@exemplo.com?subject=Sobre a reunião: {{ $titulo ?? 'Reunião' }}" 
               class="button contact-button">📧 Contatar Organizador</a>
        </div>
        
        <div class="footer">
            <div class="logo">🚀 Orbita</div>
            <p>Sistema de Gestão de Reuniões e Agendas</p>
            <small>Resposta registrada em {{ date('d/m/Y H:i') }}</small>
        </div>
    </div>
</body>
</html>
