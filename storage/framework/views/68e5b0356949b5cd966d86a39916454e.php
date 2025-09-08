<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo à DSPay</title>
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
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        .subject {
            font-size: 24px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 10px;
        }
        .greeting {
            font-size: 20px;
            color: #4a5568;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .section-content {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 15px;
            line-height: 1.7;
        }
        .highlight-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 25px 0;
            text-align: center;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            color: #718096;
        }
        .signature {
            font-weight: bold;
            color: #2d3748;
        }
        .emoji {
            font-size: 1.2em;
            margin-right: 8px;
        }
        .checklist {
            list-style: none;
            padding: 0;
        }
        .checklist li {
            padding: 8px 0;
            padding-left: 30px;
            position: relative;
        }
        .checklist li:before {
            content: "✅";
            position: absolute;
            left: 0;
            color: #48bb78;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">📩 E-mail de Boas-vindas DSPay</div>
            <div class="subject">🚀 Bem-vindo à DSPay — sua nova oportunidade começa agora!</div>
        </div>

        <!-- Abertura calorosa -->
        <div class="section">
            <div class="section-title">
                <span class="emoji">👋</span>
                Abertura calorosa e personalizada
            </div>
            <div class="section-content">
                Olá <strong><?php echo e($lead->nome); ?></strong>, seja muito bem-vindo(a) à família DSPay! 🎉
            </div>
            <div class="section-content">
                Você acaba de dar um passo que pode transformar não só sua carreira, mas também sua vida financeira. E não é exagero: estamos falando de um mercado que movimenta bilhões todos os anos no Brasil.
            </div>
        </div>

        <!-- Contexto + visão inspiradora -->
        <div class="section">
            <div class="section-title">
                <span class="emoji">🌎</span>
                Contexto + visão inspiradora
            </div>
            <div class="section-content">
                Na DSPay, acreditamos que cada parceiro pode construir seu próprio negócio em meios de pagamento com suporte, tecnologia e um modelo que gera recorrência verdadeira.
            </div>
            <div class="section-content">
                Nosso propósito é simples: dar a você as ferramentas e o conhecimento para empreender sem limites.
            </div>
        </div>

        <!-- O que você vai encontrar -->
        <div class="section">
            <div class="section-title">
                <span class="emoji">🔑</span>
                O que você vai encontrar a partir de agora
            </div>
            <div class="section-content">
                Aqui está um resumo do que preparamos para o seu início:
            </div>
            <ul class="checklist">
                <li>Apresentação completa do negócio — entenda como funciona o mercado e onde você pode ganhar.</li>
                <li>Treinamentos práticos — passo a passo para você dominar produtos e estratégias.</li>
                <li>Suporte próximo — você nunca vai caminhar sozinho.</li>
                <li>Modelo de recorrência — um sistema desenhado para que você construa renda mensal previsível.</li>
            </ul>
        </div>

        <!-- Seu primeiro passo -->
        <div class="section">
            <div class="section-title">
                <span class="emoji">🚀</span>
                Seu primeiro passo agora
            </div>
            <div class="section-content">
                Esse é o início da sua jornada dentro de um ecossistema que já transformou a vida de milhares de empreendedores como você.
            </div>
            <div class="section-content">
                👉 Clique no botão abaixo e baixe nossa apresentação completa para entender melhor como funciona o negócio.
            </div>
        </div>

        <!-- CTA Button -->
        <div style="text-align: center;">
            <a href="<?php echo e($downloadUrl); ?>" class="cta-button">
                📥 Baixe agora nossa apresentação
            </a>
        </div>
        
        <!-- Anexo Info -->
        <div style="text-align: center; margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 8px; border: 2px dashed #dee2e6;">
            <p style="margin: 0; color: #6c757d; font-size: 14px;">
                📎 <strong>Atenção:</strong> Esta mensagem contém um anexo com nossa apresentação completa.
                <br>Se o anexo não aparecer, use o botão acima para baixar diretamente.
            </p>
        </div>

        <!-- Fechamento -->
        <div class="section">
            <div class="section-title">
                <span class="emoji">🤝</span>
                Fechamento humano e encorajador
            </div>
            <div class="section-content">
                Prepare-se para crescer com a gente. O futuro está nas suas mãos e a DSPay vai caminhar ao seu lado em cada etapa.
            </div>
            <div class="section-content">
                Nos vemos no onboarding! 🚀
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="signature">Abraço,</div>
            <div class="signature">Bruno Dalcum</div>
            <div>CEO – DSPay Pagamentos</div>
        </div>
    </div>
</body>
</html>
<?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/emails/welcome-lead.blade.php ENDPATH**/ ?>