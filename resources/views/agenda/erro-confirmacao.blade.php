<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>❌ Erro na Confirmação - Orbita</title>
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
            animation: shake 1.5s ease-out;
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
            animation: pulse 2s infinite;
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
            color: #c53030;
            font-size: 22px;
            margin-bottom: 40px;
            line-height: 1.5;
            font-weight: 500;
        }
        
        .error-message {
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
        
        .error-details {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            text-align: left;
        }
        
        .error-details h3 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .error-details p {
            color: #4a5568;
            margin: 8px 0;
            font-size: 16px;
        }
        
        .error-details code {
            background: #edf2f7;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
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
        
        .retry-button {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(237, 137, 54, 0.3);
        }
        
        .retry-button:hover {
            box-shadow: 0 15px 35px rgba(237, 137, 54, 0.4);
        }
        
        .back-button {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(66, 153, 225, 0.3);
        }
        
        .back-button:hover {
            box-shadow: 0 15px 35px rgba(66, 153, 225, 0.4);
        }
        
        .footer {
            margin-top: 50px;
            color: #c53030;
            font-size: 16px;
        }
        
        .footer .logo {
            font-size: 24px;
            font-weight: 700;
            color: #742a2a;
            margin-bottom: 10px;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
            20%, 40%, 60%, 80% { transform: translateX(10px); }
        }
        
        @keyframes pulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.7; }
            50% { transform: translate(-50%, -50%) scale(1.1); opacity: 1; }
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
            <div class="icon">❌</div>
        </div>
        
        <h1>Erro na Confirmação</h1>
        <div class="subtitle">Ops! Algo deu errado ao processar sua confirmação.</div>
        
        <div class="error-message">
            <strong>⚠️ Erro encontrado!</strong><br>
            Não foi possível processar sua confirmação no momento. 
            Por favor, tente novamente ou entre em contato com o organizador.
        </div>
        
        <div class="error-details">
            <h3>📋 Detalhes do Erro</h3>
            <p><strong>Agenda ID:</strong> <code>{{ $agenda_id ?? 'N/A' }}</code></p>
            <p><strong>Email:</strong> <code>{{ $email ?? 'N/A' }}</code></p>
            <p><strong>Horário:</strong> <code>{{ date('d/m/Y H:i:s') }}</code></p>
            @if(isset($error_message))
                <p><strong>Mensagem:</strong> <code>{{ $error_message }}</code></p>
            @endif
        </div>
        
        <div class="buttons-container">
            @if(isset($agenda_id) && isset($email))
                <a href="{{ url('/agenda/confirmar/' . $agenda_id . '?status=confirmado&email=' . urlencode($email)) }}" 
                   class="button retry-button">🔄 Tentar Novamente</a>
            @endif
            <a href="{{ url('/') }}" class="button back-button">🏠 Voltar ao Início</a>
        </div>
        
        <div class="footer">
            <div class="logo">🚀 Orbita</div>
            <p>Sistema de Gestão de Reuniões e Agendas</p>
            <small>Se o problema persistir, entre em contato com o suporte</small>
        </div>
    </div>
</body>
</html>
