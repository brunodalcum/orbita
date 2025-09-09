<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Participação - Orbita</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 119, 198, 0.2) 0%, transparent 50%);
            pointer-events: none;
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(1deg); }
            66% { transform: translateY(10px) rotate(-1deg); }
        }
        
        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            box-shadow: 
                0 25px 80px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            max-width: 550px;
            width: 100%;
            padding: 50px;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            border-radius: 25px;
            pointer-events: none;
        }
        
        .icon-container {
            position: relative;
            display: inline-block;
            margin-bottom: 30px;
        }
        
        .icon {
            font-size: 100px;
            margin-bottom: 20px;
            animation: bounceIn 1.5s ease-out, pulse 2s infinite;
            position: relative;
            z-index: 2;
        }
        
        .icon-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 150px;
            height: 150px;
            border-radius: 50%;
            opacity: 0.1;
            animation: rotate 10s linear infinite;
            z-index: 1;
        }
        
        .success { color: #48bb78; }
        .success .icon-bg { background: #48bb78; }
        
        .error { color: #e53e3e; }
        .error .icon-bg { background: #e53e3e; }
        
        .pending { color: #ed8936; }
        .pending .icon-bg { background: #ed8936; }
        
        .rejected { color: #e53e3e; }
        .rejected .icon-bg { background: #e53e3e; }
        
        h1 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 32px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .subtitle {
            color: #4a5568;
            font-size: 20px;
            margin-bottom: 35px;
            line-height: 1.5;
            font-weight: 400;
        }
        
        .meeting-title {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            border-left: 5px solid #667eea;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
        }
        
        .meeting-title::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }
        
        .meeting-title h2 {
            color: #2d3748;
            font-size: 22px;
            margin: 0;
            font-weight: 600;
        }
        
        .action-message {
            padding: 25px;
            border-radius: 15px;
            margin: 30px 0;
            font-weight: 500;
            font-size: 16px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .action-message::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        .action-confirmado {
            background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%);
            color: #22543d;
            border: 2px solid #9ae6b4;
        }
        
        .action-recusado {
            background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%);
            color: #742a2a;
            border: 2px solid #fc8181;
        }
        
        .action-pendente {
            background: linear-gradient(135deg, #fef5e7 0%, #fbd38d 100%);
            color: #744210;
            border: 2px solid #f6ad55;
        }
        
        .buttons-container {
            margin-top: 35px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .back-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 18px 35px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .back-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .back-button:hover::before {
            left: 100%;
        }
        
        .back-button:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }
        
        .share-button {
            display: inline-block;
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            padding: 18px 35px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .share-button:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 35px rgba(72, 187, 120, 0.4);
        }
        
        .footer {
            margin-top: 40px;
            color: #718096;
            font-size: 14px;
        }
        
        .footer .logo {
            font-size: 20px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 8px;
        }
        
        @keyframes bounceIn {
            0% { transform: scale(0.3) rotate(-360deg); opacity: 0; }
            50% { transform: scale(1.1) rotate(-180deg); opacity: 1; }
            70% { transform: scale(0.9) rotate(-90deg); }
            100% { transform: scale(1) rotate(0deg); }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        @keyframes rotate {
            from { transform: translate(-50%, -50%) rotate(0deg); }
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #667eea;
            animation: confetti-fall 3s linear infinite;
        }
        
        @keyframes confetti-fall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
        }
        
        @media (max-width: 600px) {
            .container {
                padding: 40px 25px;
                margin: 10px;
            }
            
            .icon {
                font-size: 80px;
            }
            
            h1 {
                font-size: 26px;
            }
            
            .subtitle {
                font-size: 18px;
            }
            
            .buttons-container {
                flex-direction: column;
                align-items: center;
            }
            
            .back-button, .share-button {
                width: 100%;
                max-width: 250px;
            }
        }
    </style>
    <script>
        // Criar confetti para confirmações
        function createConfetti() {
            const colors = ['#667eea', '#764ba2', '#48bb78', '#ed8936'];
            for(let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDelay = Math.random() * 3 + 's';
                confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';
                document.body.appendChild(confetti);
                
                setTimeout(() => {
                    confetti.remove();
                }, 5000);
            }
        }
        
        // Executar confetti se for sucesso
        window.addEventListener('load', function() {
            const status = '{{ $status }}';
            const action = '{{ $action }}';
            
            if(status === 'success' && action === 'confirmado') {
                setTimeout(createConfetti, 500);
            }
        });
    </script>
</head>
<body>
    <div class="container">
        @if($status === 'success')
            @if($action === 'confirmado')
                <div class="icon-container success">
                    <div class="icon-bg"></div>
                    <div class="icon">🎉</div>
                </div>
                <h1>Participação Confirmada!</h1>
                <div class="subtitle">Sua presença foi confirmada com sucesso. Obrigado!</div>
                
                @if($titulo)
                    <div class="meeting-title">
                        <h2>📅 {{ $titulo }}</h2>
                    </div>
                @endif
                
                <div class="action-message action-confirmado">
                    <strong>✅ Confirmado:</strong> Você confirmou sua participação na reunião. 
                    O organizador será notificado automaticamente e você receberá lembretes antes do evento.
                </div>
                
            @elseif($action === 'recusado')
                <div class="icon-container rejected">
                    <div class="icon-bg"></div>
                    <div class="icon">😔</div>
                </div>
                <h1>Participação Recusada</h1>
                <div class="subtitle">Entendemos que você não poderá participar desta vez.</div>
                
                @if($titulo)
                    <div class="meeting-title">
                        <h2>📅 {{ $titulo }}</h2>
                    </div>
                @endif
                
                <div class="action-message action-recusado">
                    <strong>❌ Recusado:</strong> Você informou que não poderá participar da reunião. 
                    O organizador foi notificado e esperamos contar com sua presença em futuros eventos.
                </div>
                
            @elseif($action === 'pendente')
                <div class="icon-container pending">
                    <div class="icon-bg"></div>
                    <div class="icon">⏰</div>
                </div>
                <h1>Confirmação Pendente</h1>
                <div class="subtitle">Você pode confirmar sua participação mais tarde.</div>
                
                @if($titulo)
                    <div class="meeting-title">
                        <h2>📅 {{ $titulo }}</h2>
                    </div>
                @endif
                
                <div class="action-message action-pendente">
                    <strong>⏰ Pendente:</strong> Sua resposta ficará como pendente. 
                    Você receberá lembretes e pode confirmar ou recusar a qualquer momento.
                </div>
            @endif
        @else
            <div class="icon-container error">
                <div class="icon-bg"></div>
                <div class="icon">⚠️</div>
            </div>
            <h1>Erro na Confirmação</h1>
            <div class="subtitle">{{ $message ?: 'Ocorreu um erro ao processar sua confirmação.' }}</div>
            
            <div class="action-message action-recusado">
                <strong>Erro:</strong> Não foi possível processar sua confirmação. 
                Entre em contato com o organizador da reunião ou tente novamente mais tarde.
            </div>
        @endif
        
        <div class="buttons-container">
            <a href="{{ url('/') }}" class="back-button">🏠 Voltar ao Início</a>
            @if($status === 'success')
                <a href="mailto:?subject=Confirmação de Participação&body=Confirmei minha participação na reunião: {{ $titulo ?? 'Reunião' }}" class="share-button">📧 Compartilhar</a>
            @endif
        </div>
        
        <div class="footer">
            <div class="logo">🚀 Orbita</div>
            <p>Sistema de Gestão de Reuniões e Agendas</p>
            <small>Sua resposta foi registrada em {{ date('d/m/Y H:i') }}</small>
        </div>
    </div>
</body>
</html>
