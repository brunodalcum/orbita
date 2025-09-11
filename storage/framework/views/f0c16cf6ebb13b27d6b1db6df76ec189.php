<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - dspay</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/dspay-logo.png')); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('images/dspay-logo.png')); ?>">
    <link href="<?php echo e(asset('app.css')); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="login-container">
    <div class="login-card">
        <!-- Logo -->
        <div class="text-center mb-8">
            <!-- Logo dspay PNG -->
            <div class="mb-6">
                <img src="<?php echo e(asset('images/dspay-logo.png')); ?>" alt="dspay">
            </div>
            
            <h1>Acesso Dspay</h1>
        </div>

        <!-- Mensagens de erro -->
        <?php if($errors->any()): ?>
            <div class="mb-6" style="padding: 0.75rem; background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 0.375rem;">
                <div class="text-red-600">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Mensagem de status -->
        <?php if(session('status')): ?>
            <div class="mb-6" style="padding: 0.75rem; background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 0.375rem;">
                <p class="text-green-600"><?php echo e(session('status')); ?></p>
            </div>
        <?php endif; ?>

        <!-- Formulário -->
        <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>

            <!-- Email -->
            <div>
                <label for="email">
                    Usuário ou Email
                </label>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="<?php echo e(old('email')); ?>"
                    required 
                    autofocus 
                    autocomplete="username"
                    class="input-field"
                    placeholder="Digite seu usuário ou email"
                >
            </div>

            <!-- Senha -->
            <div>
                <label for="password">
                    Senha
                </label>
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    class="input-field"
                    placeholder="Digite sua senha"
                >
            </div>

            <!-- Esqueceu a senha -->
            <div style="text-align: right;">
                <?php if(Route::has('password.request')): ?>
                    <a href="<?php echo e(route('password.request')); ?>">
                        Esqueceu a senha?
                    </a>
                <?php endif; ?>
            </div>

            <!-- Botão de Login -->
            <button type="submit" class="btn-login">
                Entrar
            </button>
        </form>
    </div>
</body>
</html>
<?php /**PATH /Applications/MAMP/htdocs/orbita/resources/views/auth/login.blade.php ENDPATH**/ ?>