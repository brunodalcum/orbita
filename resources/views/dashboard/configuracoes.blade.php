<x-simple-branding />
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
    
    
<style>
/* CORREÇÃO AGRESSIVA DE CORES - GERADO AUTOMATICAMENTE */
:root {
    --primary-color: #3B82F6;
    --primary-color-rgb: 59, 130, 246;
    --primary-dark: #2563EB;
    --primary-light: rgba(59, 130, 246, 0.1);
    --primary-text: #FFFFFF;
    --secondary-color: #6B7280;
    --accent-color: #10B981;
}

/* Classes customizadas para substituir Tailwind */
.bg-primary { background-color: var(--primary-color) !important; }
.bg-primary-dark { background-color: var(--primary-dark) !important; }
.text-primary { color: var(--primary-color) !important; }
.border-primary { border-color: var(--primary-color) !important; }
.hover\:bg-primary-dark:hover { background-color: var(--primary-dark) !important; }

/* Sobrescrita total de cores azuis */
.bg-blue-50, .bg-blue-100, .bg-blue-200, .bg-blue-300, .bg-blue-400,
.bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-blue-800, .bg-blue-900,
.bg-indigo-50, .bg-indigo-100, .bg-indigo-200, .bg-indigo-300, .bg-indigo-400,
.bg-indigo-500, .bg-indigo-600, .bg-indigo-700, .bg-indigo-800, .bg-indigo-900 {
    background-color: var(--primary-color) !important;
}

.text-blue-50, .text-blue-100, .text-blue-200, .text-blue-300, .text-blue-400,
.text-blue-500, .text-blue-600, .text-blue-700, .text-blue-800, .text-blue-900,
.text-indigo-50, .text-indigo-100, .text-indigo-200, .text-indigo-300, .text-indigo-400,
.text-indigo-500, .text-indigo-600, .text-indigo-700, .text-indigo-800, .text-indigo-900 {
    color: var(--primary-color) !important;
}

.border-blue-50, .border-blue-100, .border-blue-200, .border-blue-300, .border-blue-400,
.border-blue-500, .border-blue-600, .border-blue-700, .border-blue-800, .border-blue-900,
.border-indigo-50, .border-indigo-100, .border-indigo-200, .border-indigo-300, .border-indigo-400,
.border-indigo-500, .border-indigo-600, .border-indigo-700, .border-indigo-800, .border-indigo-900 {
    border-color: var(--primary-color) !important;
}

/* Hovers */
.hover\:bg-blue-50:hover, .hover\:bg-blue-100:hover, .hover\:bg-blue-200:hover,
.hover\:bg-blue-300:hover, .hover\:bg-blue-400:hover, .hover\:bg-blue-500:hover,
.hover\:bg-blue-600:hover, .hover\:bg-blue-700:hover, .hover\:bg-blue-800:hover,
.hover\:bg-indigo-50:hover, .hover\:bg-indigo-100:hover, .hover\:bg-indigo-200:hover,
.hover\:bg-indigo-300:hover, .hover\:bg-indigo-400:hover, .hover\:bg-indigo-500:hover,
.hover\:bg-indigo-600:hover, .hover\:bg-indigo-700:hover, .hover\:bg-indigo-800:hover {
    background-color: var(--primary-dark) !important;
}

/* Sobrescrever estilos inline */
[style*="background-color: #3b82f6"], [style*="background-color: #2563eb"],
[style*="background-color: #1d4ed8"], [style*="background-color: #1e40af"],
[style*="background-color: rgb(59, 130, 246)"], [style*="background-color: rgb(37, 99, 235)"] {
    background-color: var(--primary-color) !important;
}

[style*="color: #3b82f6"], [style*="color: #2563eb"],
[style*="color: #1d4ed8"], [style*="color: #1e40af"],
[style*="color: rgb(59, 130, 246)"], [style*="color: rgb(37, 99, 235)"] {
    color: var(--primary-color) !important;
}

/* Botões e elementos interativos */
button:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
.btn:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
input[type="submit"], input[type="button"] {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

button:hover:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
.btn:hover:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]) {
    background-color: var(--primary-dark) !important;
}

/* Links */
a:not([class*="text-gray"]):not([class*="text-white"]):not([class*="text-black"]):not([class*="text-red"]):not([class*="text-green"]) {
    color: var(--primary-color) !important;
}

a:hover:not([class*="text-gray"]):not([class*="text-white"]):not([class*="text-black"]):not([class*="text-red"]):not([class*="text-green"]) {
    color: var(--primary-dark) !important;
}

/* Focus states */
input:focus, select:focus, textarea:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px var(--primary-light) !important;
    outline: none !important;
}

/* Spinners e loading */
.animate-spin {
    border-color: var(--primary-color) transparent var(--primary-color) transparent !important;
}

/* Badges e tags */
.badge:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]),
.tag:not([class*="gray"]):not([class*="red"]):not([class*="green"]):not([class*="yellow"]) {
    background-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
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
