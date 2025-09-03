<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $modelo->assunto }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #7c3aed 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            color: #1e3a8a;
            margin-top: 0;
            font-size: 20px;
        }
        .content p {
            margin-bottom: 20px;
            font-size: 16px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
            color: #6c757d;
            font-size: 14px;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #6c757d;
            text-decoration: none;
            font-size: 20px;
        }
        .social-links a:hover {
            color: #1e3a8a;
        }
        .unsubscribe {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        .unsubscribe a {
            color: #6c757d;
            text-decoration: none;
            font-size: 12px;
        }
        .unsubscribe a:hover {
            text-decoration: underline;
        }
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
            }
            .header, .content, .footer {
                padding: 20px !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>DSPay Orbita</h1>
            <p>{{ $modelo->assunto }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            {!! $modelo->conteudo !!}
            
            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="#" class="cta-button">
                    🚀 Acessar Agora
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="social-links">
                <a href="#" title="LinkedIn">
                    <i class="fab fa-linkedin"></i>
                </a>
                <a href="#" title="Facebook">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="#" title="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" title="WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
            
            <p><strong>DSPay Pagamentos</strong></p>
            <p>Transformando Oportunidades em Sucesso</p>
            <p>contato@dspay.com.br | (XX) XXXX-XXXX</p>
            
            <div class="unsubscribe">
                <a href="#">Cancelar inscrição</a> | 
                <a href="#">Gerenciar preferências</a>
            </div>
        </div>
    </div>
</body>
</html>



