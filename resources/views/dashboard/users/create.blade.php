
<x-dynamic-branding />
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Usuário - DSPay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar Dinâmico -->
        <x-dynamic-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Novo Usuário</h1>
                            <p class="text-gray-600 mt-1">Criar um novo usuário no sistema</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('dashboard.users') }}" 
                               class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors flex items-center space-x-2">
                                <i class="fas fa-arrow-left"></i>
                                <span>Voltar</span>
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <div class="max-w-2xl mx-auto">
                    <!-- Mensagens de Erro e Sucesso -->
                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                                <div>
                                    <h3 class="text-sm font-medium text-red-800">Erro</h3>
                                    <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                                <div>
                                    <h3 class="text-sm font-medium text-green-800">Sucesso</h3>
                                    <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nome -->
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nome Completo *
                                    </label>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                           placeholder="Digite o nome completo"
                                           required>
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="md:col-span-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        E-mail *
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                           placeholder="Digite o e-mail"
                                           required>
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Senha -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Senha *
                                    </label>
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                                           placeholder="Digite a senha"
                                           required>
                                    @error('password')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirmar Senha -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                        Confirmar Senha *
                                    </label>
                                    <input type="password" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Confirme a senha"
                                           required>
                                </div>

                                <!-- Role -->
                                <div class="md:col-span-2">
                                    <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Role *
                                    </label>
                                    <select id="role_id" 
                                            name="role_id" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role_id') border-red-500 @enderror"
                                            required>
                                        <option value="">Selecione um role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ $role->display_name }} - {{ $role->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="md:col-span-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1"
                                               {{ old('is_active', true) ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                            Usuário ativo
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Usuários inativos não conseguem fazer login no sistema
                                    </p>
                                </div>
                            </div>

                            <!-- Seção de Validação de Admin -->
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                    <div class="flex items-center">
                                        <i class="fas fa-shield-alt text-yellow-600 mr-3"></i>
                                        <div>
                                            <h3 class="text-sm font-medium text-yellow-800">Validação de Administrador</h3>
                                            <p class="text-sm text-yellow-700 mt-1">
                                                Para cadastrar usuários, é necessário validar as credenciais de um administrador ativo.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Email do Admin -->
                                    <div>
                                        <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-envelope mr-1"></i>
                                            Email do Administrador *
                                        </label>
                                        <input type="email" 
                                               id="admin_email" 
                                               name="admin_email" 
                                               value="{{ old('admin_email') }}"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('admin_email') border-red-500 @enderror"
                                               placeholder="Digite o email do administrador"
                                               required>
                                        @error('admin_email')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-xs text-gray-500 mt-1">
                                            Email de um administrador ativo no sistema
                                        </p>
                                    </div>

                                    <!-- Senha do Admin -->
                                    <div>
                                        <label for="admin_password" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-lock mr-1"></i>
                                            Senha do Administrador *
                                        </label>
                                        <input type="password" 
                                               id="admin_password" 
                                               name="admin_password" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('admin_password') border-red-500 @enderror"
                                               placeholder="Digite a senha do administrador"
                                               required>
                                        @error('admin_password')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-xs text-gray-500 mt-1">
                                            Senha do administrador para validação
                                        </p>
                                    </div>
                                </div>

                                <!-- Informações sobre permissões -->
                                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <h4 class="text-sm font-medium text-blue-800 mb-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Informações sobre Permissões
                                    </h4>
                                    <ul class="text-sm text-blue-700 space-y-1">
                                        <li>• <strong>Administradores:</strong> Podem cadastrar funcionários e licenciados</li>
                                        <li>• <strong>Super Administradores:</strong> Podem cadastrar todos os tipos de usuários</li>
                                        <li>• <strong>Funcionários e Licenciados:</strong> Não podem cadastrar outros usuários</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                                <a href="{{ route('dashboard.users') }}" 
                                   class="px-6 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                                    Cancelar
                                </a>
                                <button type="submit" 
                                        class="px-6 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200 flex items-center space-x-2">
                                    <i class="fas fa-save"></i>
                                    <span>Criar Usuário</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role_id');
            const adminValidationSection = document.querySelector('.mt-8.pt-6.border-t.border-gray-200');
            const adminEmailInput = document.getElementById('admin_email');
            const adminPasswordInput = document.getElementById('admin_password');

            // Função para mostrar/ocultar validação de admin baseado no role selecionado
            function toggleAdminValidation() {
                const selectedRole = roleSelect.value;
                const roleText = roleSelect.options[roleSelect.selectedIndex].text;
                
                if (selectedRole) {
                    // Mostrar seção de validação
                    adminValidationSection.style.display = 'block';
                    
                    // Atualizar informações baseado no role
                    const infoBox = adminValidationSection.querySelector('.bg-blue-50');
                    if (infoBox) {
                        let permissionText = '';
                        
                        if (roleText.includes('Super Admin')) {
                            permissionText = 'Apenas Super Administradores podem cadastrar outros Super Administradores.';
                        } else if (roleText.includes('Admin')) {
                            permissionText = 'Administradores podem cadastrar funcionários e licenciados.';
                        } else if (roleText.includes('Funcionário')) {
                            permissionText = 'Funcionários têm acesso limitado ao sistema.';
                        } else if (roleText.includes('Licenciado')) {
                            permissionText = 'Licenciados têm acesso apenas aos seus próprios dados.';
                        }
                        
                        const permissionElement = infoBox.querySelector('ul');
                        if (permissionElement) {
                            permissionElement.innerHTML = `
                                <li>• <strong>Role Selecionado:</strong> ${roleText}</li>
                                <li>• <strong>Permissão:</strong> ${permissionText}</li>
                                <li>• <strong>Validação:</strong> É necessário confirmar as credenciais de um administrador ativo</li>
                            `;
                        }
                    }
                } else {
                    // Ocultar seção de validação
                    adminValidationSection.style.display = 'none';
                }
            }

            // Event listener para mudança no select de role
            roleSelect.addEventListener('change', toggleAdminValidation);

            // Verificar se há um valor selecionado ao carregar a página
            toggleAdminValidation();

            // Validação em tempo real do email do admin
            adminEmailInput.addEventListener('blur', function() {
                const email = this.value;
                if (email) {
                    // Aqui poderia fazer uma validação AJAX para verificar se o email existe
                    // Por enquanto, apenas validação visual
                    this.classList.remove('border-red-500', 'border-green-500');
                    this.classList.add('border-blue-500');
                }
            });

            // Validação do formulário antes do envio
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const selectedRole = roleSelect.value;
                const adminEmail = adminEmailInput.value;
                const adminPassword = adminPasswordInput.value;

                if (!selectedRole) {
                    e.preventDefault();
                    alert('Por favor, selecione um tipo de usuário.');
                    roleSelect.focus();
                    return false;
                }

                if (!adminEmail || !adminPassword) {
                    e.preventDefault();
                    alert('Por favor, preencha as credenciais do administrador.');
                    if (!adminEmail) {
                        adminEmailInput.focus();
                    } else {
                        adminPasswordInput.focus();
                    }
                    return false;
                }

                // Confirmar antes de enviar
                const roleText = roleSelect.options[roleSelect.selectedIndex].text;
                const confirmMessage = `Confirma o cadastro do usuário com role "${roleText}"?`;
                
                if (!confirm(confirmMessage)) {
                    e.preventDefault();
                    return false;
                }
            });

            // Auto-preenchimento do email do admin se o usuário atual for admin
            @if(auth()->user() && (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()))
                adminEmailInput.value = '{{ auth()->user()->email }}';
            @endif
        });
    </script>
</body>
</html>
