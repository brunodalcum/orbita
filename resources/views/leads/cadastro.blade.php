<!DOCTYPE html>
<html lang="pt-BR">
<head>
<x-dynamic-branding />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Lead - Orbita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .form-field {
            transition: all 0.3s ease;
        }
        .form-field:focus-within {
            transform: translateY(-2px);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        .floating-particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        .particle:nth-child(1) { left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 2s; }
        .particle:nth-child(3) { left: 30%; animation-delay: 4s; }
        .particle:nth-child(4) { left: 40%; animation-delay: 1s; }
        .particle:nth-child(5) { left: 50%; animation-delay: 3s; }
        .particle:nth-child(6) { left: 60%; animation-delay: 5s; }
        .particle:nth-child(7) { left: 70%; animation-delay: 2s; }
        .particle:nth-child(8) { left: 80%; animation-delay: 4s; }
        .particle:nth-child(9) { left: 90%; animation-delay: 1s; }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 1; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 0.7; }
        }
        .success-animation {
            animation: successPulse 0.6s ease-in-out;
        }
        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body class="min-h-screen gradient-bg relative">
    <!-- Floating Particles -->
    <div class="floating-particles">
        <div class="particle w-4 h-4"></div>
        <div class="particle w-6 h-6"></div>
        <div class="particle w-3 h-3"></div>
        <div class="particle w-5 h-5"></div>
        <div class="particle w-4 h-4"></div>
        <div class="particle w-6 h-6"></div>
        <div class="particle w-3 h-3"></div>
        <div class="particle w-5 h-5"></div>
        <div class="particle w-4 h-4"></div>
    </div>

    <div class="container mx-auto px-4 py-8 relative z-10">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-block p-4 bg-white/10 backdrop-blur-sm rounded-full mb-6">
                <i class="fas fa-rocket text-4xl text-white"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Cadastro de Lead
            </h1>
            <p class="text-xl text-white/80 max-w-2xl mx-auto">
                Preencha o formulário abaixo e nossa equipe entrará em contato em breve para discutir como podemos ajudar seu negócio.
            </p>
        </div>

        <!-- Form Card -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 shadow-2xl border border-white/20">
                <form id="leadForm" class="space-y-6">
                    <!-- Nome -->
                    <div class="form-field">
                        <label for="nome" class="block text-white font-semibold mb-2">
                            <i class="fas fa-user mr-2"></i>Nome Completo *
                        </label>
                        <input type="text" id="nome" name="nome" required
                               class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-300"
                               placeholder="Digite seu nome completo">
                    </div>

                    <!-- Email -->
                    <div class="form-field">
                        <label for="email" class="block text-white font-semibold mb-2">
                            <i class="fas fa-envelope mr-2"></i>E-mail
                        </label>
                        <input type="email" id="email" name="email"
                               class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-300"
                               placeholder="seu@email.com">
                    </div>

                    <!-- Telefone -->
                    <div class="form-field">
                        <label for="telefone" class="block text-white font-semibold mb-2">
                            <i class="fas fa-phone mr-2"></i>Telefone
                        </label>
                        <input type="tel" id="telefone" name="telefone"
                               class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-300"
                               placeholder="(11) 99999-9999">
                    </div>

                    <!-- Empresa -->
                    <div class="form-field">
                        <label for="empresa" class="block text-white font-semibold mb-2">
                            <i class="fas fa-building mr-2"></i>Empresa
                        </label>
                        <input type="text" id="empresa" name="empresa"
                               class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-300"
                               placeholder="Nome da sua empresa">
                    </div>

                    <!-- Origem -->
                    <div class="form-field">
                        <label for="origem" class="block text-white font-semibold mb-2">
                            <i class="fas fa-tag mr-2"></i>Como nos conheceu?
                        </label>
                        <select id="origem" name="origem"
                                class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-300">
                            <option value="">Selecione uma opção</option>
                            <option value="Google">Google</option>
                            <option value="Indicação">Indicação</option>
                            <option value="Redes Sociais">Redes Sociais</option>
                            <option value="Feira/Evento">Feira/Evento</option>
                            <option value="LinkedIn">LinkedIn</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>

                    <!-- Observações -->
                    <div class="form-field">
                        <label for="observacoes" class="block text-white font-semibold mb-2">
                            <i class="fas fa-comment mr-2"></i>Mensagem
                        </label>
                        <textarea id="observacoes" name="observacoes" rows="4"
                                  class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-300 resize-none"
                                  placeholder="Conte-nos um pouco sobre o que você precisa..."></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" id="submitBtn"
                                class="w-full btn-primary text-white font-bold py-4 px-6 rounded-lg text-lg flex items-center justify-center space-x-2">
                            <i class="fas fa-paper-plane"></i>
                            <span>Enviar Cadastro</span>
                        </button>
                    </div>
                </form>

                <!-- Success Message -->
                <div id="successMessage" class="hidden mt-6 p-6 bg-green-500/20 border border-green-400/30 rounded-lg text-center">
                    <div class="text-green-400 text-6xl mb-4">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="text-green-400 text-xl font-bold mb-2">Cadastro Realizado com Sucesso!</h3>
                    <p class="text-green-300">Nossa equipe entrará em contato em breve. Obrigado pelo interesse!</p>
                </div>

                <!-- Error Message -->
                <div id="errorMessage" class="hidden mt-6 p-6 bg-red-500/20 border border-red-400/30 rounded-lg text-center">
                    <div class="text-red-400 text-6xl mb-4">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="text-red-400 text-xl font-bold mb-2">Erro no Cadastro</h3>
                    <p id="errorText" class="text-red-300">Ocorreu um erro. Tente novamente.</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-12 text-white/60">
            <p>&copy; 2025 Orbita. Todos os direitos reservados.</p>
        </div>
    </div>

    <script>
        document.getElementById('leadForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
            submitBtn.disabled = true;
            
            // Hide previous messages
            document.getElementById('successMessage').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
            
            try {
                const formData = new FormData(this);
                const response = await fetch('/cadastro-lead', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Show success message
                    document.getElementById('successMessage').classList.remove('hidden');
                    document.getElementById('successMessage').classList.add('success-animation');
                    
                    // Reset form
                    this.reset();
                    
                    // Scroll to success message
                    document.getElementById('successMessage').scrollIntoView({ behavior: 'smooth' });
                } else {
                    // Show error message
                    document.getElementById('errorText').textContent = result.message;
                    document.getElementById('errorMessage').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('errorText').textContent = 'Erro de conexão. Tente novamente.';
                document.getElementById('errorMessage').classList.remove('hidden');
            } finally {
                // Reset button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });

        // Add CSRF token meta tag
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = '{{ csrf_token() }}';
        document.head.appendChild(meta);
    </script>
</body>
</html>




