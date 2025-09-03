<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onboarding - DSPay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-purple-50 to-indigo-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full mb-6">
                <i class="fas fa-rocket text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">🚀 Bem-vindo à DSPay!</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Olá <strong>{{ $lead->nome }}</strong>, sua jornada para o sucesso em meios de pagamento começa agora!
            </p>
        </div>

        <!-- Progress Steps -->
        <div class="max-w-4xl mx-auto mb-12">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">1</div>
                    <div class="text-lg font-semibold text-gray-800">Apresentação do Negócio</div>
                </div>
                <div class="flex-1 h-1 bg-gray-200 mx-4"></div>
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold">2</div>
                    <div class="text-lg font-semibold text-gray-600">Treinamentos</div>
                </div>
                <div class="flex-1 h-1 bg-gray-200 mx-4"></div>
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold">3</div>
                    <div class="text-lg font-semibold text-gray-600">Primeiros Passos</div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto">
            <!-- Step 1: Apresentação do Negócio -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mr-4">1</div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Apresentação Completa do Negócio</h2>
                        <p class="text-gray-600">Entenda como funciona o mercado e onde você pode ganhar</p>
                    </div>
                </div>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl border border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-900 mb-3 flex items-center">
                            <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                            Mercado em Crescimento
                        </h3>
                        <p class="text-blue-800">
                            O mercado de meios de pagamento movimenta bilhões anualmente no Brasil, com crescimento constante e oportunidades para todos os tipos de empreendedores.
                        </p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl border border-purple-200">
                        <h3 class="text-lg font-semibold text-purple-900 mb-3 flex items-center">
                            <i class="fas fa-handshake text-purple-600 mr-2"></i>
                            Modelo de Parceria
                        </h3>
                        <p class="text-purple-800">
                            Você será nosso parceiro, com total autonomia para construir seu negócio, mas com todo o suporte e tecnologia da DSPay.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Step 2: Treinamentos -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 opacity-75">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 text-2xl font-bold mr-4">2</div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-600">Treinamentos Práticos</h2>
                        <p class="text-gray-500">Passo a passo para dominar produtos e estratégias</p>
                    </div>
                </div>
                
                <div class="text-center py-8">
                    <i class="fas fa-lock text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-500">Disponível em breve após a apresentação</p>
                </div>
            </div>

            <!-- Step 3: Primeiros Passos -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 opacity-75">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 text-2xl font-bold mr-4">3</div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-600">Primeiros Passos</h2>
                        <p class="text-gray-500">Comece sua jornada conosco</p>
                    </div>
                </div>
                
                <div class="text-center py-8">
                    <i class="fas fa-lock text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-500">Disponível em breve após os treinamentos</p>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-xl p-8 text-center text-white">
                <h2 class="text-3xl font-bold mb-4">🎯 Próximos Passos</h2>
                <p class="text-xl mb-6 opacity-90">
                    Nossa equipe entrará em contato em breve para agendar uma apresentação personalizada e responder todas as suas dúvidas.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-phone text-2xl"></i>
                        <span class="text-lg">Contato direto</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-calendar-alt text-2xl"></i>
                        <span class="text-lg">Agendamento flexível</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-users text-2xl"></i>
                        <span class="text-lg">Suporte dedicado</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-16 text-gray-500">
            <p class="mb-2">© 2025 DSPay Pagamentos. Todos os direitos reservados.</p>
            <p class="text-sm">
                <i class="fas fa-envelope mr-2"></i>
                contato@dspay.com.br
                <span class="mx-4">|</span>
                <i class="fas fa-phone mr-2"></i>
                (11) 99999-9999
            </p>
        </div>
    </div>
</body>
</html>



