<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Configurações do Portal - dspay</title>
    <link rel="icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/dspay-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Branding Dinâmico -->
    <x-dynamic-branding />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }
        .main-content {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        .config-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.8);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .config-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        .config-card:hover::before {
            transform: scaleX(1);
        }
        .config-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
        }
        .sidebar-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }
        .sidebar-link:hover::before {
            left: 100%;
        }
        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(8px);
        }
        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.25);
            border-left: 4px solid #fbbf24;
            box-shadow: 0 4px 20px rgba(251, 191, 36, 0.3);
        }
        .form-input {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid #e5e7eb;
            background: #fafafa;
        }
        .form-input:focus {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
            border-color: #667eea;
            background: white;
        }
        .upload-area {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 3px dashed #d1d5db;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            position: relative;
            overflow: hidden;
        }
        .upload-area::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            transition: left 0.6s ease;
        }
        .upload-area:hover::before {
            left: 100%;
        }
        .upload-area:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
            background: linear-gradient(135deg, #f0f4ff 0%, #e6f0ff 100%);
        }
        .upload-area.dragover {
            border-color: #667eea;
            background: linear-gradient(135deg, #e6f0ff 0%, #dbeafe 100%);
            transform: scale(1.02);
        }
        .btn-primary {
            background: var(--primary-gradient); color: var(--primary-text);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        .btn-primary:hover::before {
            left: 100%;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: 2px solid #e2e8f0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: #cbd5e1;
        }
        .success-toast, .error-toast {
            z-index: 9999;
            backdrop-filter: blur(10px);
        }
        .field-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s ease;
        }
        .field-icon:hover {
            transform: scale(1.1) rotate(5deg);
        }
        .preview-container {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 16px;
            padding: 20px;
            border: 2px solid #e2e8f0;
        }
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        .shape {
            position: absolute;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        .shape:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 20%;
            right: 15%;
            animation-delay: 2s;
        }
        .shape:nth-child(3) {
            width: 40px;
            height: 40px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        .header-gradient {
            background: var(--primary-gradient); color: var(--primary-text);
            position: relative;
            overflow: hidden;
        }
        .header-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="header-grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23header-grain)"/></svg>');
            pointer-events: none;
        }
            
        /* Estilos dinâmicos do dashboard */
        .dashboard-header {
            background: var(--background-color);
            color: var(--text-color);
        }
        .stat-card {
            background: var(--primary-gradient);
            color: var(--primary-text);
        }
        .progress-bar {
            background: var(--accent-gradient);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar Dinâmico -->
        <x-dynamic-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden main-content">
            <!-- Header -->
            <header class="header-gradient text-white relative">
                <div class="flex items-center justify-between px-8 py-6 relative z-10">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Configurações do Portal</h1>
                        <p class="text-blue-100 text-lg">Personalize e configure seu sistema</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="p-3 bg-white/20 backdrop-blur-sm rounded-xl hover:bg-white/30 transition-all">
                            <i class="fas fa-bell text-lg"></i>
                        </button>
                        <form method="POST" action="{{ route('logout.custom') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-3 bg-white/20 backdrop-blur-sm rounded-xl hover:bg-white/30 transition-all">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-5xl mx-auto">
                    <!-- Breadcrumb -->
                    <nav class="flex mb-8" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-2">
                            <li class="inline-flex items-center">
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:" style="color: var(--primary-color);" transition-colors">
                                    <i class="fas fa-home mr-2"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span class="text-sm font-medium text-gray-800">Configurações</span>
                                </div>
                            </li>
                        </ol>
                    </nav>

                    <!-- Configuration Form -->
                    <div class="config-card p-8 relative">
                        <!-- Floating Shapes -->
                        <div class="floating-shapes">
                            <div class="shape"></div>
                            <div class="shape"></div>
                            <div class="shape"></div>
                        </div>

                        <div class="mb-10 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl mb-4 shadow-lg">
                                <i class="fas fa-cogs text-white text-2xl"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-800 mb-3">Configurações do Sistema</h2>
                            <p style="color: var(--secondary-color);">Configure as informações básicas e personalize a identidade visual do seu portal</p>
                        </div>

                        <form id="configForm" enctype="multipart/form-data" class="space-y-8">
                            <!-- Nome do Sistema -->
                            <div class="group">
                                <div class="flex items-center mb-4">
                                    <div class="field-icon bg-gradient-to-br from-blue-500 to-blue-600 text-white mr-4">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <label for="nome_sistema" class="text-lg font-semibold text-gray-800">
                                        Nome do Sistema
                                    </label>
                                </div>
                                <input type="text" 
                                       id="nome_sistema" 
                                       name="nome_sistema" 
                                       value="{{ $configuracoes['nome_sistema'] ?? '' }}"
                                       class="form-input w-full px-6 py-4 rounded-xl text-lg"
                                       placeholder="Digite o nome do sistema">
                                <div class="error-message text-red-500 text-sm mt-2 hidden"></div>
                            </div>

                            <!-- Logomarca do Sistema -->
                            <div class="group">
                                <div class="flex items-center mb-4">
                                    <div class="field-icon bg-gradient-to-br from-green-500 to-emerald-600 text-white mr-4">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    <label class="text-lg font-semibold text-gray-800">
                                        Logomarca do Sistema
                                    </label>
                                </div>
                                <div class="upload-area rounded-2xl p-8 text-center">
                                    <div class="mb-6">
                                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl mb-4">
                                            <i class="fas fa-cloud-upload-alt text-3xl " style="color: var(--accent-color);""></i>
                                        </div>
                                    </div>
                                    <div class="mb-6">
                                        <p class="text-lg text-gray-700 mb-2">
                                            <span class="font-semibold " style="color: var(--accent-color);"">Clique para fazer upload</span> ou arraste e solte
                                        </p>
                                        <p class="text-sm text-gray-500">PNG, JPG, GIF até 2MB</p>
                                    </div>
                                    <input type="file" 
                                           id="logomarca_sistema" 
                                           name="logomarca_sistema" 
                                           accept="image/*"
                                           class="hidden">
                                    <button type="button" 
                                            onclick="document.getElementById('logomarca_sistema').click()"
                                            class="btn-primary px-8 py-3 text-white rounded-xl font-medium">
                                        <i class="fas fa-upload mr-2"></i>
                                        Selecionar Arquivo
                                    </button>
                                </div>
                                <div id="preview-container" class="mt-6 hidden">
                                    <div class="flex items-center justify-between">
                                        <img id="image-preview" src="" alt="Preview" class="max-w-xs rounded-2xl shadow-lg">
                                        <button type="button" onclick="removeImage()" class="btn-secondary px-4 py-2 rounded-xl">
                                            <i class="fas fa-trash mr-2"></i>Remover
                                        </button>
                                    </div>
                                </div>
                                <div class="error-message text-red-500 text-sm mt-2 hidden"></div>
                            </div>

                            <!-- E-mail do Sistema -->
                            <div class="group">
                                <div class="flex items-center mb-4">
                                    <div class="field-icon bg-gradient-to-br from-purple-500 to-pink-600 text-white mr-4">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <label for="email_sistema" class="text-lg font-semibold text-gray-800">
                                        E-mail do Sistema
                                    </label>
                                </div>
                                <input type="email" 
                                       id="email_sistema" 
                                       name="email_sistema" 
                                       value="{{ $configuracoes['email_sistema'] ?? '' }}"
                                       class="form-input w-full px-6 py-4 rounded-xl text-lg"
                                       placeholder="contato@sistema.com">
                                <div class="error-message text-red-500 text-sm mt-2 hidden"></div>
                            </div>

                            <!-- Telefone do Sistema -->
                            <div class="group">
                                <div class="flex items-center mb-4">
                                    <div class="field-icon bg-gradient-to-br from-orange-500 to-red-600 text-white mr-4">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <label for="telefone_sistema" class="text-lg font-semibold text-gray-800">
                                        Telefone do Sistema
                                    </label>
                                </div>
                                <input type="text" 
                                       id="telefone_sistema" 
                                       name="telefone_sistema" 
                                       value="{{ $configuracoes['telefone_sistema'] ?? '' }}"
                                       class="form-input w-full px-6 py-4 rounded-xl text-lg"
                                       placeholder="(11) 99999-9999">
                                <div class="error-message text-red-500 text-sm mt-2 hidden"></div>
                            </div>

                            <!-- CNPJ do Sistema -->
                            <div class="group">
                                <div class="flex items-center mb-4">
                                    <div class="field-icon bg-gradient-to-br from-indigo-500 to-blue-600 text-white mr-4">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <label for="cnpj_sistema" class="text-lg font-semibold text-gray-800">
                                        CNPJ do Sistema
                                    </label>
                                </div>
                                <input type="text" 
                                       id="cnpj_sistema" 
                                       name="cnpj_sistema" 
                                       value="{{ $configuracoes['cnpj_sistema'] ?? '' }}"
                                       class="form-input w-full px-6 py-4 rounded-xl text-lg"
                                       placeholder="00.000.000/0000-00">
                                <div class="error-message text-red-500 text-sm mt-2 hidden"></div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex justify-end space-x-6 pt-8 border-t border-gray-200">
                                <button type="button" 
                                        onclick="resetForm()"
                                        class="btn-secondary px-8 py-4 rounded-xl font-medium flex items-center">
                                    <i class="fas fa-undo mr-3"></i>
                                    Restaurar Padrão
                                </button>
                                <button type="submit" 
                                        id="submitBtn"
                                        class="btn-primary px-8 py-4 rounded-xl font-medium flex items-center">
                                    <i class="fas fa-save mr-3"></i>
                                    <span id="submitText">Salvar Configurações</span>
                                    <div id="loadingSpinner" class="hidden ml-3">
                                        <i class="fas fa-spinner fa-spin text-lg"></i>
                                    </div>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast -->
    <div id="successToast" class="success-toast fixed top-6 right-6 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-6 rounded-2xl shadow-2xl transform translate-x-full transition-all duration-500">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-check text-lg"></i>
            </div>
            <div>
                <p class="font-semibold">Sucesso!</p>
                <span id="successMessage" class="text-sm opacity-90">Configurações salvas com sucesso!</span>
            </div>
        </div>
    </div>

    <!-- Error Toast -->
    <div id="errorToast" class="error-toast fixed top-6 right-6 bg-gradient-to-r from-red-500 to-pink-600 text-white px-8 py-6 rounded-2xl shadow-2xl transform translate-x-full transition-all duration-500">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-exclamation-triangle text-lg"></i>
            </div>
            <div>
                <p class="font-semibold">Erro!</p>
                <span id="errorMessage" class="text-sm opacity-90">Erro ao salvar configurações!</span>
            </div>
        </div>
    </div>

    <script>
        // File upload handling
        document.getElementById('logomarca_sistema').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    showError('A imagem não pode ter mais que 2MB.');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').src = e.target.result;
                    document.getElementById('preview-container').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Drag and drop functionality
        const uploadArea = document.querySelector('.upload-area');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            uploadArea.classList.add('dragover');
        }

        function unhighlight(e) {
            uploadArea.classList.remove('dragover');
        }

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                const file = files[0];
                if (file.type.startsWith('image/')) {
                    if (file.size > 2 * 1024 * 1024) {
                        showError('A imagem não pode ter mais que 2MB.');
                        return;
                    }
                    
                    document.getElementById('logomarca_sistema').files = files;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('image-preview').src = e.target.result;
                        document.getElementById('preview-container').classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    showError('Por favor, selecione apenas arquivos de imagem.');
                }
            }
        }

        function removeImage() {
            document.getElementById('logomarca_sistema').value = '';
            document.getElementById('preview-container').classList.add('hidden');
        }

        // Form submission
        document.getElementById('configForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveConfiguracoes();
        });

        function saveConfiguracoes() {
            const form = document.getElementById('configForm');
            const formData = new FormData(form);
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingSpinner = document.getElementById('loadingSpinner');

            // Show loading state
            submitBtn.disabled = true;
            submitText.textContent = 'Salvando...';
            loadingSpinner.classList.remove('hidden');

            // Clear previous errors
            clearErrors();

            fetch('{{ route("configuracoes.update") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess(data.message);
                    // Reset form to show updated values
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    if (data.errors) {
                        showFieldErrors(data.errors);
                    } else {
                        showError(data.message);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Erro ao salvar configurações. Tente novamente.');
            })
            .finally(() => {
                // Reset button state
                submitBtn.disabled = false;
                submitText.textContent = 'Salvar Configurações';
                loadingSpinner.classList.add('hidden');
            });
        }

        function showFieldErrors(errors) {
            Object.keys(errors).forEach(field => {
                const input = document.getElementById(field);
                const errorDiv = input.parentNode.querySelector('.error-message');
                if (input && errorDiv) {
                    errorDiv.textContent = errors[field][0];
                    errorDiv.classList.remove('hidden');
                    input.classList.add('border-red-500');
                }
            });
        }

        function clearErrors() {
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.add('hidden');
            });
            document.querySelectorAll('.form-input').forEach(el => {
                el.classList.remove('border-red-500');
            });
        }

        function showSuccess(message) {
            const toast = document.getElementById('successToast');
            const messageEl = document.getElementById('successMessage');
            messageEl.textContent = message;
            toast.classList.remove('translate-x-full');
            
            setTimeout(() => {
                toast.classList.add('translate-x-full');
            }, 4000);
        }

        function showError(message) {
            const toast = document.getElementById('errorToast');
            const messageEl = document.getElementById('errorMessage');
            messageEl.textContent = message;
            toast.classList.remove('translate-x-full');
            
            setTimeout(() => {
                toast.classList.add('translate-x-full');
            }, 6000);
        }

        function resetForm() {
            if (confirm('Tem certeza que deseja restaurar as configurações padrão?')) {
                location.reload();
            }
        }
    </script>
</body>
</html>
