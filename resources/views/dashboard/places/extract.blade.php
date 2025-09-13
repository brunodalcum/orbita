<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Extração via Google Places API</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
        }
        .btn-success {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
        }
        .btn-success:hover {
            background: linear-gradient(45deg, #218838, #1ba085);
        }
        .border-start {
            border-left: 4px solid !important;
        }
    </style>
</head>
<body>

<div class="container-fluid py-4">
    <!-- Navegação -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard.leads') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i>
                            Leads
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Google Places API</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-start border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="text-success mb-2">
                                <i class="fab fa-google me-2"></i>
                                Extração via Google Places API
                            </h2>
                            <p class="text-muted mb-0">Descubra novos leads automaticamente através da Google Places API</p>
                        </div>
                        
                        <div class="badge bg-success fs-6">
                            <i class="fas fa-shield-alt me-1"></i>
                            100% Compliant LGPD
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center border-primary">
                <div class="card-body">
                    <i class="fas fa-map-marker-alt text-primary fs-1 mb-2"></i>
                    <h5 class="text-muted">Places Coletados</h5>
                    <h3 class="text-primary">{{ $totalPlaces ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-center border-success">
                <div class="card-body">
                    <i class="fas fa-phone text-success fs-1 mb-2"></i>
                    <h5 class="text-muted">Com Telefone</h5>
                    <h3 class="text-success">{{ $placesWithPhone ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-center border-info">
                <div class="card-body">
                    <i class="fas fa-globe text-info fs-1 mb-2"></i>
                    <h5 class="text-muted">Com Website</h5>
                    <h3 class="text-info">{{ $placesWithWebsite ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <i class="fas fa-search text-warning fs-1 mb-2"></i>
                    <h5 class="text-muted">Extrações (7 dias)</h5>
                    <h3 class="text-warning">{{ $recentExtractions ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário de Extração -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-search text-primary me-2"></i>
                        Nova Extração de Leads
                    </h5>
                </div>
                <div class="card-body">
                    @if(!$apiInfo['configured'])
                        <div class="alert alert-warning border-0 mb-4" style="background: rgba(255, 193, 7, 0.1);">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle text-warning fs-4 me-3"></i>
                                <div>
                                    <h6 class="alert-heading mb-2">⚠️ API Key do Google Places não configurada</h6>
                                    <p class="mb-2">Para usar a funcionalidade completa, configure a chave da API do Google Places no arquivo <code>.env</code>:</p>
                                    <code class="d-block bg-light p-2 rounded">GOOGLE_PLACES_API_KEY=sua_chave_aqui</code>
                                    <small class="text-muted mt-2 d-block">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Por enquanto, você pode testar com dados de exemplo que serão gerados automaticamente.
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <form id="extractionForm">
                        @csrf
                        
                        <div class="row mb-4">
                            <!-- Termo de Busca -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-search me-1"></i>
                                    Termo de Busca *
                                </label>
                                <input type="text" 
                                       name="query" 
                                       class="form-control form-control-lg" 
                                       placeholder="Ex: farmácia, restaurante, hospital..."
                                       required>
                                <div class="form-text">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Dica: Seja específico. Ex: "farmácia em São Paulo" ou "restaurante italiano"
                                </div>
                            </div>

                            <!-- Localização -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    Localização
                                </label>
                                <input type="text" 
                                       name="location" 
                                       class="form-control form-control-lg" 
                                       placeholder="Ex: São Paulo, SP ou Maceió, AL">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Opcional: Cidade, estado ou endereço específico
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <!-- Raio de Busca -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-circle-notch me-1"></i>
                                    Raio (metros)
                                </label>
                                <select name="radius" class="form-select">
                                    <option value="5000">5 km</option>
                                    <option value="10000" selected>10 km</option>
                                    <option value="25000">25 km</option>
                                    <option value="50000">50 km</option>
                                </select>
                            </div>

                            <!-- Tipos de Estabelecimento -->
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-tags me-1"></i>
                                    Tipos de Estabelecimento (Opcional)
                                </label>
                                <div class="row">
                                    @if(isset($commonTypes))
                                        @foreach(array_chunk($commonTypes, 5, true) as $chunk)
                                            <div class="col-md-6">
                                                @foreach($chunk as $type => $label)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="types[]" value="{{ $type }}" id="type_{{ $type }}">
                                                        <label class="form-check-label small" for="type_{{ $type }}">
                                                            {{ $label }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Base Legal LGPD -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Base Legal (LGPD) *
                                </label>
                                <textarea name="legal_basis" 
                                          class="form-control" 
                                          rows="3" 
                                          placeholder="Descreva a finalidade e base legal para coleta destes dados..."
                                          required>Coleta de dados públicos de estabelecimentos comerciais para fins de prospecção comercial, baseada no legítimo interesse da empresa, conforme Art. 7º, IX da LGPD.</textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Obrigatório: Justifique a finalidade da coleta conforme LGPD
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="d-flex flex-column flex-sm-row gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-success btn-lg flex-fill">
                                <span id="extractButtonText">
                                    <i class="fas fa-search me-2"></i>
                                    Iniciar Extração
                                </span>
                                <span id="extractButtonLoading" class="d-none">
                                    <i class="fas fa-spinner fa-spin me-2"></i>
                                    Extraindo...
                                </span>
                            </button>
                            
                            <button type="button" class="btn btn-outline-secondary" onclick="clearForm()">
                                <i class="fas fa-eraser me-2"></i>
                                Limpar
                            </button>
                            
                            <a href="{{ route('dashboard.leads') }}" class="btn btn-outline-dark">
                                <i class="fas fa-arrow-left me-2"></i>
                                Voltar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Histórico de Extrações -->
    @if(isset($userExtractions) && $userExtractions->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-history text-primary me-2"></i>
                        Histórico de Extrações
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Consulta</th>
                                    <th>Localização</th>
                                    <th>Status</th>
                                    <th>Encontrados</th>
                                    <th>Processados</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userExtractions as $extraction)
                                <tr>
                                    <td>{{ $extraction->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <strong>{{ $extraction->query }}</strong>
                                    </td>
                                    <td>{{ $extraction->location ?? 'N/A' }}</td>
                                    <td>
                                        @if($extraction->status === 'completed')
                                            <span class="badge bg-success">Concluída</span>
                                        @elseif($extraction->status === 'running')
                                            <span class="badge bg-primary">Em execução</span>
                                        @elseif($extraction->status === 'failed')
                                            <span class="badge bg-danger">Falhou</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($extraction->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $extraction->total_found }}</td>
                                    <td>{{ $extraction->total_processed }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" 
                                                onclick="viewExtractionDetails({{ $extraction->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Informações e Compliance -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-info shadow-sm">
                <div class="card-header bg-info bg-opacity-10 border-info">
                    <h6 class="mb-0 text-info d-flex align-items-center">
                        <i class="fas fa-shield-alt me-2"></i>
                        Compliance e Informações Legais
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">🔒 Proteção de Dados (LGPD):</h6>
                            <ul class="list-unstyled text-muted small">
                                <li>• Utilizamos apenas APIs oficiais do Google</li>
                                <li>• Coletamos apenas dados públicos disponíveis</li>
                                <li>• Base legal: Legítimo interesse comercial</li>
                                <li>• Respeitamos solicitações de opt-out</li>
                                <li>• Dados minimizados e com finalidade específica</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">⚡ Termos de Uso:</h6>
                            <ul class="list-unstyled text-muted small">
                                <li>• Não fazemos scraping de HTML</li>
                                <li>• Respeitamos rate limits da API</li>
                                <li>• Cache otimizado para reduzir custos</li>
                                <li>• Deduplicação automática de dados</li>
                                <li>• Auditoria completa de todas as operações</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Progresso -->
<div class="modal fade" id="progressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="fas fa-search text-success me-2"></i>
                    Extração em Progresso
                </h5>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <div class="spinner-border text-success" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                </div>
                <h6 id="progressStatus">Iniciando extração...</h6>
                <p class="text-muted" id="progressDetails">Aguarde enquanto buscamos os dados na Google Places API</p>
                
                <div class="progress mt-3">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                         role="progressbar" 
                         style="width: 0%" 
                         id="progressBar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentExtractionId = null;
let progressInterval = null;

// Submissão do formulário
document.getElementById('extractionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Mostrar loading
    showLoading();
    
    // Fazer requisição
    fetch('{{ route("dashboard.places.extract.run") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        hideLoading();
        
        if (data.success) {
            currentExtractionId = data.extraction_id;
            showProgressModal();
            startProgressTracking();
        } else {
            alert('Erro: ' + (data.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Erro:', error);
        alert('Erro ao iniciar extração');
    });
});

// Mostrar loading
function showLoading() {
    document.getElementById('extractButtonText').classList.add('d-none');
    document.getElementById('extractButtonLoading').classList.remove('d-none');
}

// Esconder loading
function hideLoading() {
    document.getElementById('extractButtonText').classList.remove('d-none');
    document.getElementById('extractButtonLoading').classList.add('d-none');
}

// Mostrar modal de progresso
function showProgressModal() {
    const modal = new bootstrap.Modal(document.getElementById('progressModal'));
    modal.show();
}

// Iniciar tracking de progresso
function startProgressTracking() {
    if (!currentExtractionId) return;
    
    progressInterval = setInterval(() => {
        fetch(`{{ url('/places/extraction') }}/${currentExtractionId}/status`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateProgress(data.extraction);
                    
                    if (data.extraction.status === 'completed' || data.extraction.status === 'failed') {
                        clearInterval(progressInterval);
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }
                }
            })
            .catch(error => {
                console.error('Erro ao verificar status:', error);
            });
    }, 2000); // Verificar a cada 2 segundos
}

// Atualizar progresso
function updateProgress(extraction) {
    const statusElement = document.getElementById('progressStatus');
    const detailsElement = document.getElementById('progressDetails');
    const progressBar = document.getElementById('progressBar');
    
    if (extraction.status === 'running') {
        statusElement.textContent = 'Processando dados...';
        detailsElement.textContent = `${extraction.total_processed} de ${extraction.total_found} processados`;
        
        const progress = extraction.total_found > 0 ? 
            (extraction.total_processed / extraction.total_found) * 100 : 0;
        progressBar.style.width = progress + '%';
        
    } else if (extraction.status === 'completed') {
        statusElement.textContent = 'Extração concluída!';
        detailsElement.textContent = `${extraction.total_new} novos leads encontrados`;
        progressBar.style.width = '100%';
        progressBar.classList.remove('progress-bar-animated');
        
    } else if (extraction.status === 'failed') {
        statusElement.textContent = 'Extração falhou';
        detailsElement.textContent = 'Ocorreu um erro durante a extração';
        progressBar.classList.add('bg-danger');
    }
}

// Limpar formulário
function clearForm() {
    document.getElementById('extractionForm').reset();
}

// Função viewExtractionDetails implementada mais abaixo no código
</script>

<!-- Bootstrap JS -->
<!-- Modal para Detalhes dos Leads -->
<div class="modal fade" id="leadsDetailsModal" tabindex="-1" aria-labelledby="leadsDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="leadsDetailsModalLabel">
                    <i class="fas fa-list-ul me-2"></i>
                    Detalhes dos Leads Encontrados
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Informações da Extração -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Data/Hora:</strong>
                                        <div id="extraction-date" class="text-muted"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Consulta:</strong>
                                        <div id="extraction-query" class="text-muted"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Localização:</strong>
                                        <div id="extraction-location" class="text-muted"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Status:</strong>
                                        <div id="extraction-status" class="text-muted"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loading -->
                <div id="leads-loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <div class="mt-2">Carregando detalhes dos leads...</div>
                </div>

                <!-- Lista de Leads -->
                <div id="leads-container" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">
                            <i class="fas fa-users me-1"></i>
                            Leads Encontrados (<span id="leads-count">0</span>)
                        </h6>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-primary" onclick="insertLeads()" id="insert-leads-btn">
                                <i class="fas fa-plus me-1"></i>
                                Inserir Leads
                            </button>
                            <button class="btn btn-sm btn-success" onclick="exportLeads()">
                                <i class="fas fa-download me-1"></i>
                                Exportar CSV
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nome do Estabelecimento</th>
                                    <th>Endereço</th>
                                    <th>Telefone</th>
                                    <th>Website</th>
                                    <th>Avaliação</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="leads-table-body">
                                <!-- Leads serão inseridos aqui via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mensagem de Erro -->
                <div id="leads-error" class="alert alert-danger" style="display: none;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="error-message">Erro ao carregar detalhes dos leads.</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Variável global para armazenar os leads atuais
let currentLeads = [];

/**
 * Visualizar detalhes de uma extração
 */
function viewExtractionDetails(extractionId) {
    // Definir ID da extração atual para uso nas outras funções
    currentExtractionId = extractionId;
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('leadsDetailsModal'));
    modal.show();
    
    // Mostrar loading
    document.getElementById('leads-loading').style.display = 'block';
    document.getElementById('leads-container').style.display = 'none';
    document.getElementById('leads-error').style.display = 'none';
    
    // Buscar detalhes via AJAX
    fetch(`/places/extraction/${extractionId}/details`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        document.getElementById('leads-loading').style.display = 'none';
        
        if (data.success) {
            displayExtractionDetails(data.extraction, data.leads);
        } else {
            showError(data.message || 'Erro ao carregar detalhes');
        }
    })
    .catch(error => {
        document.getElementById('leads-loading').style.display = 'none';
        console.error('Erro ao buscar detalhes:', error);
        showError('Erro ao carregar detalhes: ' + error.message);
    });
}

/**
 * Exibir detalhes da extração e leads
 */
function displayExtractionDetails(extraction, leads) {
    // Preencher informações da extração
    document.getElementById('extraction-date').textContent = extraction.created_at;
    document.getElementById('extraction-query').textContent = extraction.query;
    document.getElementById('extraction-location').textContent = extraction.location;
    document.getElementById('extraction-status').innerHTML = getStatusBadge(extraction.status);
    
    // Armazenar leads globalmente para exportação
    currentLeads = leads;
    
    // Preencher tabela de leads
    const tableBody = document.getElementById('leads-table-body');
    const leadsCount = document.getElementById('leads-count');
    
    leadsCount.textContent = leads.length;
    tableBody.innerHTML = '';
    
    if (leads.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                    Nenhum lead encontrado nesta extração
                </td>
            </tr>
        `;
    } else {
        leads.forEach(lead => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <div class="fw-semibold">${escapeHtml(lead.name)}</div>
                    <small class="text-muted">${lead.types ? lead.types.join(', ') : 'N/A'}</small>
                </td>
                <td>
                    <small>${escapeHtml(lead.formatted_address || 'N/A')}</small>
                </td>
                <td>
                    ${lead.formatted_phone_number ? 
                        `<a href="tel:${lead.formatted_phone_number}" class="text-decoration-none">
                            <i class="fas fa-phone me-1"></i>${lead.formatted_phone_number}
                        </a>` : 
                        '<span class="text-muted">N/A</span>'
                    }
                </td>
                <td>
                    ${lead.website ? 
                        `<a href="${lead.website}" target="_blank" class="text-decoration-none">
                            <i class="fas fa-external-link-alt me-1"></i>Site
                        </a>` : 
                        '<span class="text-muted">N/A</span>'
                    }
                </td>
                <td>
                    ${lead.rating ? 
                        `<div class="d-flex align-items-center">
                            <span class="me-1">${lead.rating}</span>
                            <i class="fas fa-star text-warning"></i>
                            <small class="text-muted ms-1">(${lead.user_ratings_total || 0})</small>
                        </div>` : 
                        '<span class="text-muted">N/A</span>'
                    }
                </td>
                <td>
                    <span class="badge ${lead.business_status === 'OPERATIONAL' ? 'bg-success' : 'bg-warning'}">
                        ${lead.business_status === 'OPERATIONAL' ? 'Ativo' : 'Inativo'}
                    </span>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }
    
    // Mostrar container de leads
    document.getElementById('leads-container').style.display = 'block';
}

/**
 * Mostrar erro
 */
function showError(message) {
    document.getElementById('error-message').textContent = message;
    document.getElementById('leads-error').style.display = 'block';
}

/**
 * Gerar badge de status
 */
function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="badge bg-warning">Pendente</span>',
        'processing': '<span class="badge bg-info">Processando</span>',
        'completed': '<span class="badge bg-success">Concluído</span>',
        'failed': '<span class="badge bg-danger">Falhou</span>'
    };
    return badges[status] || `<span class="badge bg-secondary">${status}</span>`;
}

/**
 * Escapar HTML para prevenir XSS
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * Exportar leads para CSV
 */
function exportLeads() {
    if (currentLeads.length === 0) {
        alert('Nenhum lead para exportar');
        return;
    }
    
    // Cabeçalhos do CSV
    const headers = ['Nome', 'Endereço', 'Telefone', 'Website', 'Avaliação', 'Total Avaliações', 'Status', 'Tipos'];
    
    // Converter leads para CSV
    const csvContent = [
        headers.join(','),
        ...currentLeads.map(lead => [
            `"${(lead.name || '').replace(/"/g, '""')}"`,
            `"${(lead.formatted_address || '').replace(/"/g, '""')}"`,
            `"${lead.formatted_phone_number || ''}"`,
            `"${lead.website || ''}"`,
            lead.rating || '',
            lead.user_ratings_total || '',
            lead.business_status || '',
            `"${lead.types ? lead.types.join('; ') : ''}"`
        ].join(','))
    ].join('\n');
    
    // Criar e baixar arquivo
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', `leads_extracao_${new Date().toISOString().slice(0, 10)}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

/**
 * Inserir leads na tabela de leads do sistema
 */
function insertLeads() {
    if (currentLeads.length === 0) {
        alert('Nenhum lead para inserir');
        return;
    }
    
    if (!currentExtractionId) {
        console.error('currentExtractionId não definido:', currentExtractionId);
        alert('❌ ID da extração não encontrado. Tente fechar e abrir a modal novamente.');
        return;
    }
    
    console.log('Inserindo leads da extração ID:', currentExtractionId);
    
    // Confirmar ação
    const confirmMessage = `Deseja inserir ${currentLeads.length} leads na lista de leads do sistema?\n\nEsta ação irá:\n- Adicionar os leads à aba "Leads"\n- Verificar duplicatas automaticamente\n- Criar registros com origem "Google Places API"`;
    
    if (!confirm(confirmMessage)) {
        return;
    }
    
    // Desabilitar botão e mostrar loading
    const insertBtn = document.getElementById('insert-leads-btn');
    const originalText = insertBtn.innerHTML;
    insertBtn.disabled = true;
    insertBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Inserindo...';
    
    // Fazer requisição AJAX
    fetch(`{{ url('/places/extraction') }}/${currentExtractionId}/insert-leads`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({})
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        insertBtn.disabled = false;
        insertBtn.innerHTML = originalText;
        
        if (data.success) {
            // Mostrar resultado detalhado
            const stats = data.stats;
            let message = `✅ Leads inseridos com sucesso!\n\n`;
            message += `📊 Estatísticas:\n`;
            message += `• Inseridos: ${stats.inserted}\n`;
            message += `• Duplicados (ignorados): ${stats.duplicates}\n`;
            message += `• Erros: ${stats.errors}\n`;
            message += `• Total processados: ${stats.total_processed}\n\n`;
            message += `Os leads estão agora disponíveis na aba "Leads" do sistema.`;
            
            alert(message);
            
            // Atualizar botão para indicar que já foi inserido
            insertBtn.innerHTML = '<i class="fas fa-check me-1"></i>Leads Inseridos';
            insertBtn.classList.remove('btn-primary');
            insertBtn.classList.add('btn-success');
            insertBtn.disabled = true;
            
            // Opcionalmente, redirecionar para a página de leads
            if (confirm('Deseja ir para a página de Leads para ver os registros inseridos?')) {
                window.open('/leads', '_blank');
            }
            
        } else {
            alert('❌ Erro ao inserir leads: ' + (data.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        insertBtn.disabled = false;
        insertBtn.innerHTML = originalText;
        console.error('Erro ao inserir leads:', error);
        alert('❌ Erro ao inserir leads: ' + error.message);
    });
}
</script>

</body>
</html>
