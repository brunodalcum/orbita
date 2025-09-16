<!DOCTYPE html>
<html lang="pt-BR">
<head>
<x-dynamic-branding />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Extrair Leads - Dashboard Moderno</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
            --shadow-light: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            --shadow-heavy: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.3) 0%, transparent 50%);
            z-index: -1;
            animation: backgroundFloat 20s ease-in-out infinite;
        }

        @keyframes backgroundFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
        }

        /* Glass Morphism Container */
        .glass-container {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 20px;
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
        }

        .glass-container:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-heavy);
        }

        /* Modern Cards */
        .modern-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            position: relative;
        }

        .modern-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .modern-card:hover::before {
            transform: scaleX(1);
        }

        .modern-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        }

        /* Gradient Text */
        .gradient-text {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
        }

        /* Stats Cards */
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: none;
            padding: 2rem;
            text-align: center;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: rotate 4s linear infinite;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        @keyframes rotate {
            100% { transform: rotate(360deg); }
        }

        .stat-card:hover {
            transform: translateY(-15px) scale(1.05);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
            position: relative;
            z-index: 1;
        }

        .stat-icon.primary { background: var(--primary-gradient); }
        .stat-icon.success { background: var(--success-gradient); }
        .stat-icon.warning { background: var(--warning-gradient); }
        .stat-icon.info { background: var(--info-gradient); }

        /* Modern Form */
        .modern-form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 3rem;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            border: 2px solid rgba(102, 126, 234, 0.1);
            border-radius: 16px;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
            transform: translateY(-2px);
        }

        .form-select {
            border: 2px solid rgba(102, 126, 234, 0.1);
            border-radius: 16px;
            padding: 1rem 1.5rem;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }

        /* Modern Buttons */
        .btn-modern {
            border: none;
            border-radius: 16px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-modern:hover::before {
            left: 100%;
        }

        .btn-primary-modern {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-primary-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-success-modern {
            background: var(--success-gradient);
            color: white;
        }

        .btn-success-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(17, 153, 142, 0.4);
            color: white;
        }

        .btn-outline-modern {
            background: transparent;
            border: 2px solid rgba(102, 126, 234, 0.3);
            color: #667eea;
        }

        .btn-outline-modern:hover {
            background: var(--primary-gradient);
            border-color: transparent;
            color: white;
            transform: translateY(-3px);
        }

        /* Header Animation */
        .header-content {
            animation: slideInDown 0.8s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Stats Animation */
        .stat-card {
            animation: slideInUp 0.8s ease-out;
            animation-fill-mode: both;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Google Places Section */
        .google-places-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .google-places-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #4285f4, #ea4335, #fbbc05, #34a853);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .modern-form {
                padding: 2rem;
                border-radius: 20px;
            }
            
            .stat-card {
                padding: 1.5rem;
                margin-bottom: 1rem;
            }
            
            .stat-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }

        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(102, 126, 234, 0.9);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Floating Elements */
        .floating-element {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            top: 20%;
            right: 10%;
            animation-delay: 2s;
        }

        .floating-element:nth-child(3) {
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
    </style>
</head>
<body>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="text-center text-white">
        <div class="loading-spinner mb-3"></div>
        <h5>Processando...</h5>
    </div>
</div>

<!-- Floating Elements -->
<div class="floating-element">
    <i class="fas fa-users" style="font-size: 3rem; color: rgba(255, 255, 255, 0.1);"></i>
</div>
<div class="floating-element">
    <i class="fas fa-chart-line" style="font-size: 2.5rem; color: rgba(255, 255, 255, 0.1);"></i>
</div>
<div class="floating-element">
    <i class="fas fa-download" style="font-size: 2rem; color: rgba(255, 255, 255, 0.1);"></i>
</div>

<div class="container-fluid py-5">
    <!-- Modern Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="glass-container p-4 header-content">
                <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
                    <div class="mb-3 mb-lg-0">
                        <h1 class="gradient-text mb-3" style="font-size: 3rem; font-weight: 800;">
                            <i class="fas fa-rocket me-3"></i>
                            Extrair Leads
                        </h1>
                        <p class="text-white fs-5 mb-0" style="opacity: 0.9;">
                            Dashboard avançado para extração e análise de leads com tecnologia de ponta
                        </p>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="glass-container px-4 py-3">
                            <div class="text-white text-center">
                                <i class="fas fa-database fs-3 mb-2 d-block"></i>
                                <h4 class="mb-1">{{ $totalLeads }}</h4>
                                <small style="opacity: 0.8;">Total de Leads</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Statistics -->
    <div class="row mb-5 g-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="fw-bold mb-2">{{ $totalLeads }}</h3>
                <p class="text-muted mb-0">Total de Leads</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="fw-bold mb-2">{{ $leadsAtivos }}</h3>
                <p class="text-muted mb-0">Leads Ativos</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3 class="fw-bold mb-2">{{ $leadsComLicenciado }}</h3>
                <p class="text-muted mb-0">Com Licenciado</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon info">
                    <i class="fas fa-user-clock"></i>
                </div>
                <h3 class="fw-bold mb-2">{{ $leadsSemLicenciado }}</h3>
                <p class="text-muted mb-0">Sem Licenciado</p>
            </div>
        </div>
    </div>

    <!-- Google Places Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="google-places-section">
                <div class="card-body p-5">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center mb-4">
                                <div class="me-4">
                                    <i class="fab fa-google" style="font-size: 4rem; color: #4285f4;"></i>
                                </div>
                                <div>
                                    <h2 class="fw-bold mb-2" style="color: #1a73e8;">
                                        🚀 NOVO: Extração via Google Places API
                                    </h2>
                                    <p class="text-muted fs-5 mb-0">
                                        Descubra novos leads automaticamente usando a tecnologia Google
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="text-primary fw-bold mb-3">✅ Dados Capturados:</h6>
                                    <ul class="list-unstyled text-muted">
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Nome do estabelecimento</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Endereço completo</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Telefone (quando disponível)</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Website (quando disponível)</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Avaliações e rating</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary fw-bold mb-3">🎯 Exemplos de Busca:</h6>
                                    <ul class="list-unstyled text-muted">
                                        <li class="mb-2"><i class="fas fa-search text-info me-2"></i>"farmácia em São Paulo"</li>
                                        <li class="mb-2"><i class="fas fa-search text-info me-2"></i>"restaurante em Maceió"</li>
                                        <li class="mb-2"><i class="fas fa-search text-info me-2"></i>"hospital em Recife"</li>
                                        <li class="mb-2"><i class="fas fa-search text-info me-2"></i>"banco em Fortaleza"</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="alert alert-info border-0" style="background: rgba(26, 115, 232, 0.1);">
                                <i class="fas fa-shield-alt text-primary me-2"></i>
                                <strong>100% Compliant:</strong> Utilizamos apenas APIs oficiais do Google, 
                                respeitando termos de uso e LGPD.
                            </div>
                        </div>

                        <div class="col-lg-4 text-center">
                            <div class="p-4">
                                <div class="mb-4">
                                    <i class="fab fa-google" style="font-size: 5rem; color: #4285f4;"></i>
                                </div>
                                
                                <a href="{{ route('dashboard.places.extract') }}" 
                                   class="btn btn-modern btn-primary-modern btn-lg w-100 mb-3">
                                    <i class="fas fa-rocket me-2"></i>
                                    Extrair Novos Leads
                                </a>

                                <small class="text-muted d-block">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Ferramenta avançada de descoberta
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Export Form -->
    <div class="row">
        <div class="col-12">
            <div class="modern-form">
                <div class="text-center mb-5">
                    <h3 class="gradient-text fw-bold mb-3">
                        <i class="fas fa-filter me-2"></i>
                        Filtros Avançados de Exportação
                    </h3>
                    <p class="text-muted fs-5">Configure os filtros para exportar exatamente os leads que você precisa</p>
                </div>

                <form id="exportForm" method="POST" action="{{ route('dashboard.leads.export') }}">
                    @csrf
                    
                    <div class="row g-4 mb-4">
                        <!-- Status -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-flag me-2 text-primary"></i>
                                Status do Lead
                            </label>
                            <select name="status" class="form-select">
                                <option value="">Todos os status</option>
                                @foreach($leadsPorStatus as $status => $count)
                                    <option value="{{ $status }}">{{ ucfirst($status) }} ({{ $count }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Origem -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-source me-2 text-success"></i>
                                Origem do Lead
                            </label>
                            <select name="origem" class="form-select">
                                <option value="">Todas as origens</option>
                                @foreach($leadsPorOrigem as $origem => $count)
                                    <option value="{{ $origem }}">{{ $origem }} ({{ $count }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Licenciado -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-tie me-2 text-warning"></i>
                                Licenciado
                            </label>
                            <select name="licenciado" class="form-select">
                                <option value="">Todos (com e sem licenciado)</option>
                                <option value="com_licenciado">Apenas com licenciado ({{ $leadsComLicenciado }})</option>
                                <option value="sem_licenciado">Apenas sem licenciado ({{ $leadsSemLicenciado }})</option>
                            </select>
                        </div>

                        <!-- Ativo -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-toggle-on me-2 text-info"></i>
                                Status Ativo
                            </label>
                            <select name="ativo" class="form-select">
                                <option value="">Todos (ativos e inativos)</option>
                                <option value="1">Apenas ativos ({{ $leadsAtivos }})</option>
                                <option value="0">Apenas inativos ({{ $totalLeads - $leadsAtivos }})</option>
                            </select>
                        </div>

                        <!-- Data Inicial -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                Data Inicial
                            </label>
                            <input type="date" name="data_inicial" class="form-control">
                        </div>

                        <!-- Data Final -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar-check me-2 text-success"></i>
                                Data Final
                            </label>
                            <input type="date" name="data_final" class="form-control">
                        </div>

                        <!-- Formato -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-file-export me-2 text-warning"></i>
                                Formato de Exportação
                            </label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="formato" id="csv" value="csv" checked>
                                        <label class="form-check-label fw-semibold" for="csv">
                                            <i class="fas fa-file-csv text-success me-2"></i>
                                            CSV (Comma Separated Values)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="formato" id="excel" value="excel">
                                        <label class="form-check-label fw-semibold" for="excel">
                                            <i class="fas fa-file-excel text-success me-2"></i>
                                            Excel (XLSX)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row g-3">
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-modern btn-success-modern w-100">
                                <i class="fas fa-download me-2"></i>
                                Exportar Leads
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-modern btn-outline-modern w-100" onclick="clearFilters()">
                                <i class="fas fa-eraser me-2"></i>
                                Limpar Filtros
                            </button>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('dashboard.leads') }}" class="btn btn-modern btn-outline-modern w-100">
                                <i class="fas fa-arrow-left me-2"></i>
                                Voltar para Lista
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Show loading overlay on form submit
document.getElementById('exportForm').addEventListener('submit', function() {
    document.getElementById('loadingOverlay').classList.add('active');
    
    // Hide loading after 3 seconds (adjust based on your export time)
    setTimeout(function() {
        document.getElementById('loadingOverlay').classList.remove('active');
    }, 3000);
});

// Clear filters function
function clearFilters() {
    document.getElementById('exportForm').reset();
}

// Add smooth scroll behavior
document.documentElement.style.scrollBehavior = 'smooth';

// Add entrance animations on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe all cards for animation
document.querySelectorAll('.modern-card, .stat-card, .modern-form, .google-places-section').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';
    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(card);
});

// Add particle effect on button hover
document.querySelectorAll('.btn-modern').forEach(button => {
    button.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-3px) scale(1.05)';
    });
    
    button.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
});

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Add stagger animation to stat cards
    document.querySelectorAll('.stat-card').forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
    
    // Add hover effects to form controls
    document.querySelectorAll('.form-control, .form-select').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });
});
</script>

</body>
</html>
