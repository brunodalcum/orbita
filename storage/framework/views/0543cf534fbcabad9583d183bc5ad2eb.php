<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>✅ Participação Confirmada - Orbita</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animação de confetti */
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #fff;
            animation: confetti-fall 3s linear infinite;
        }
        
        @keyframes confetti-fall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
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
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(1deg); }
            66% { transform: translateY(10px) rotate(-1deg); }
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
            animation: bounceIn 1.5s ease-out, pulse 2s infinite;
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
            background: rgba(72, 187, 120, 0.1);
            border-radius: 50%;
            animation: rotate 10s linear infinite;
            z-index: 1;
        }
        
        h1 {
            color: #22543d;
            margin-bottom: 20px;
            font-size: 36px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .subtitle {
            color: #2f855a;
            font-size: 22px;
            margin-bottom: 40px;
            line-height: 1.5;
            font-weight: 500;
        }
        
        .meeting-title {
            background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%);
            border-radius: 20px;
            padding: 30px;
            margin: 40px 0;
            border: 2px solid #9ae6b4;
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.15);
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
            background: linear-gradient(90deg, #48bb78, #38a169);
        }
        
        .meeting-title h2 {
            color: #22543d;
            font-size: 24px;
            margin: 0;
            font-weight: 600;
        }
        
        .success-message {
            background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%);
            border: 2px solid #48bb78;
            border-radius: 20px;
            padding: 30px;
            margin: 40px 0;
            font-weight: 500;
            font-size: 18px;
            color: #22543d;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.2);
        }
        
        .success-message::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
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
        
        .share-button {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(56, 161, 105, 0.3);
        }
        
        .share-button:hover {
            box-shadow: 0 15px 35px rgba(56, 161, 105, 0.4);
        }
        
        .footer {
            margin-top: 50px;
            color: #2f855a;
            font-size: 16px;
        }
        
        .footer .logo {
            font-size: 24px;
            font-weight: 700;
            color: #22543d;
            margin-bottom: 10px;
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
    <script>
        // Criar confetti
        function createConfetti() {
            const colors = ['#48bb78', '#38a169', '#68d391', '#9ae6b4', '#c6f6d5'];
            for(let i = 0; i < 100; i++) {
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
        
        // Executar confetti ao carregar
        window.addEventListener('load', function() {
            setTimeout(createConfetti, 500);
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="icon-container">
            <div class="icon-bg"></div>
            <div class="icon">🎉</div>
        </div>
        
        <h1>Participação Confirmada!</h1>
        <div class="subtitle">Sua presença foi confirmada com sucesso. Obrigado!</div>
        
        <?php if(isset($titulo) && $titulo): ?>
            <div class="meeting-title">
                <h2>📅 <?php echo e($titulo); ?></h2>
            </div>
        <?php endif; ?>
        
        <div class="success-message">
            <strong>✅ Confirmado com sucesso!</strong><br>
            Você confirmou sua participação na reunião. O organizador será notificado automaticamente 
            e você receberá lembretes antes do evento.
        </div>
        
        <div class="buttons-container">
            <a href="<?php echo e(url('/')); ?>" class="button back-button">🏠 Voltar ao Início</a>
            <a href="mailto:?subject=Confirmação de Participação&body=Confirmei minha participação na reunião: <?php echo e($titulo ?? 'Reunião'); ?>" 
               class="button share-button">📧 Compartilhar</a>
        </div>
        
        <div class="footer">
            <div class="logo">🚀 Orbita</div>
            <p>Sistema de Gestão de Reuniões e Agendas</p>
            <small>Confirmação registrada em <?php echo e(date('d/m/Y H:i')); ?></small>
        </div>
    </div>
</body>
</html><?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/agenda/confirmacao.blade.php ENDPATH**/ ?>