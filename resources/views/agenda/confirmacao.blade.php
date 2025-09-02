<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Participação - Orbita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .success-bg {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        }
        .warning-bg {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
        }
        .danger-bg {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
        }
        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="gradient-bg text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <div class="text-4xl mb-2">🚀</div>
            <h1 class="text-3xl font-bold mb-2">Orbita</h1>
            <p class="text-lg opacity-90">Sistema de Gestão de Reuniões</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Status Card -->
            <div class="bg-white rounded-2xl card-shadow p-8 mb-6 animate-fade-in">
                <div class="text-center mb-6">
                    @if($status === 'confirmado')
                        <div class="text-6xl mb-4">✅</div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Participação Confirmada!</h2>
                        <p class="text-gray-600">Você confirmou sua presença na reunião</p>
                        <div class="status-badge success-bg text-white inline-block mt-4">
                            <i class="fas fa-check mr-2"></i>Confirmado
                        </div>
                    @elseif($status === 'pendente')
                        <div class="text-6xl mb-4">⏰</div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Confirmação Pendente</h2>
                        <p class="text-gray-600">Você marcou para confirmar mais tarde</p>
                        <div class="status-badge warning-bg text-white inline-block mt-4">
                            <i class="fas fa-clock mr-2"></i>Pendente
                        </div>
                    @elseif($status === 'recusado')
                        <div class="text-6xl mb-4">❌</div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Participação Recusada</h2>
                        <p class="text-gray-600">Você informou que não pode participar</p>
                        <div class="status-badge danger-bg text-white inline-block mt-4">
                            <i class="fas fa-times mr-2"></i>Recusado
                        </div>
                    @endif
                </div>

                <!-- Meeting Details -->
                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-calendar-alt text-blue-500 mr-3"></i>
                        Detalhes da Reunião
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-heading text-gray-400 w-5 mr-3"></i>
                            <span class="text-gray-600 font-medium">{{ $agenda->titulo }}</span>
                        </div>
                        
                        @if($agenda->descricao)
                        <div class="flex items-start">
                            <i class="fas fa-align-left text-gray-400 w-5 mr-3 mt-1"></i>
                            <span class="text-gray-600">{{ $agenda->descricao }}</span>
                        </div>
                        @endif
                        
                        <div class="flex items-center">
                            <i class="fas fa-clock text-gray-400 w-5 mr-3"></i>
                            <span class="text-gray-600">
                                <strong>Início:</strong> {{ \Carbon\Carbon::parse($agenda->data_inicio)->format('d/m/Y \à\s H:i') }}
                            </span>
                        </div>
                        
                        <div class="flex items-center">
                            <i class="fas fa-clock text-gray-400 w-5 mr-3"></i>
                            <span class="text-gray-600">
                                <strong>Fim:</strong> {{ \Carbon\Carbon::parse($agenda->data_fim)->format('d/m/Y \à\s H:i') }}
                            </span>
                        </div>
                        
                        <div class="flex items-center">
                            <i class="fas fa-user text-gray-400 w-5 mr-3"></i>
                            <span class="text-gray-600">
                                <strong>Organizador:</strong> {{ $agenda->user->name ?? 'Sistema' }}
                            </span>
                        </div>
                        
                        @if($agenda->google_meet_link)
                        <div class="flex items-center">
                            <i class="fas fa-video text-gray-400 w-5 mr-3"></i>
                            <span class="text-gray-600">
                                <strong>Tipo:</strong> Reunião Online
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="text-center space-y-4">
                    @if($status === 'confirmado')
                        <div class="text-green-600 font-medium">
                            <i class="fas fa-check-circle mr-2"></i>
                            Sua participação foi confirmada com sucesso!
                        </div>
                    @elseif($status === 'pendente')
                        <div class="text-orange-600 font-medium">
                            <i class="fas fa-clock mr-2"></i>
                            Você pode confirmar sua participação mais tarde
                        </div>
                    @elseif($status === 'recusado')
                        <div class="text-red-600 font-medium">
                            <i class="fas fa-info-circle mr-2"></i>
                            O organizador foi notificado sobre sua indisponibilidade
                        </div>
                    @endif

                    <div class="pt-4 border-t border-gray-200">
                        <a href="{{ route('dashboard.agenda') }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar para a Agenda
                        </a>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 animate-fade-in" style="animation-delay: 0.2s;">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-blue-800 mb-2">O que acontece agora?</h4>
                        <ul class="text-blue-700 space-y-1 text-sm">
                            @if($status === 'confirmado')
                                <li>• O organizador foi notificado sobre sua confirmação</li>
                                <li>• Você receberá lembretes da reunião</li>
                                <li>• O link do Google Meet estará disponível na hora da reunião</li>
                            @elseif($status === 'pendente')
                                <li>• O organizador foi notificado sobre o status pendente</li>
                                <li>• Você pode confirmar sua participação a qualquer momento</li>
                                <li>• Receberá lembretes para confirmar a participação</li>
                            @elseif($status === 'recusado')
                                <li>• O organizador foi notificado sobre sua indisponibilidade</li>
                                <li>• A reunião pode ser reagendada se necessário</li>
                                <li>• Você pode entrar em contato com o organizador</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="bg-gray-800 text-white py-6 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-400">
                © {{ date('Y') }} Orbita. Sistema de Gestão de Reuniões e Agendas.
            </p>
        </div>
    </div>

    <script>
        // Adicionar efeitos de hover e interação
        document.addEventListener('DOMContentLoaded', function() {
            // Animar elementos na entrada
            const cards = document.querySelectorAll('.animate-fade-in');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });

            // Adicionar confetti para confirmação
            @if($status === 'confirmado')
            setTimeout(() => {
                createConfetti();
            }, 1000);
            @endif
        });

        function createConfetti() {
            const colors = ['#48bb78', '#38a169', '#667eea', '#764ba2'];
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.style.position = 'fixed';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.top = '-10px';
                confetti.style.width = '10px';
                confetti.style.height = '10px';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.borderRadius = '50%';
                confetti.style.pointerEvents = 'none';
                confetti.style.zIndex = '9999';
                confetti.style.animation = `fall ${Math.random() * 3 + 2}s linear forwards`;
                
                document.body.appendChild(confetti);
                
                setTimeout(() => {
                    confetti.remove();
                }, 5000);
            }
        }

        // Adicionar CSS para animação de confetti
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fall {
                to {
                    transform: translateY(100vh) rotate(360deg);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>

