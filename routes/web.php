<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return view('welcome');
});

// Rota de teste para verificar se o problema é específico do login
Route::get('/test', function () {
    return 'Teste funcionando!';
});

// Rota de teste com view
Route::get('/test-view', function () {
    return view('test');
});


// Rotas de criação de usuários movidas para dentro do middleware



// Rota pública para cadastro de leads
Route::get('/cadastro-lead', function () {
    return view('leads.cadastro');
})->name('leads.cadastro');

Route::post('/cadastro-lead', [App\Http\Controllers\LeadController::class, 'storePublic'])->name('leads.cadastro.store');
Route::get('/onboarding/{id}', [App\Http\Controllers\LeadController::class, 'onboarding'])->name('leads.onboarding');
Route::get('/download-apresentacao/{id}', [App\Http\Controllers\LeadController::class, 'downloadPresentation'])->name('leads.download.presentation');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'redirect.role',
])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    // Dashboard específico para Licenciados
    Route::prefix('licenciado')->name('licenciado.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\LicenciadoDashboardController::class, 'index'])->name('dashboard');
        Route::get('/estabelecimentos', [App\Http\Controllers\LicenciadoDashboardController::class, 'estabelecimentos'])->name('estabelecimentos');
        Route::get('/estabelecimentos/create', [App\Http\Controllers\LicenciadoDashboardController::class, 'createEstabelecimento'])->name('estabelecimentos.create');
        Route::post('/estabelecimentos', [App\Http\Controllers\LicenciadoDashboardController::class, 'storeEstabelecimento'])->name('estabelecimentos.store');
        Route::get('/estabelecimentos/{estabelecimento}', [App\Http\Controllers\LicenciadoDashboardController::class, 'showEstabelecimento'])->name('estabelecimentos.show');
        Route::get('/estabelecimentos/{estabelecimento}/edit', [App\Http\Controllers\LicenciadoDashboardController::class, 'editEstabelecimento'])->name('estabelecimentos.edit');
        Route::put('/estabelecimentos/{estabelecimento}', [App\Http\Controllers\LicenciadoDashboardController::class, 'updateEstabelecimento'])->name('estabelecimentos.update');
        Route::get('/vendas', [App\Http\Controllers\LicenciadoDashboardController::class, 'vendas'])->name('vendas');
        Route::get('/comissoes', [App\Http\Controllers\LicenciadoDashboardController::class, 'comissoes'])->name('comissoes');
        Route::get('/relatorios', [App\Http\Controllers\LicenciadoDashboardController::class, 'relatorios'])->name('relatorios');
        Route::get('/perfil', [App\Http\Controllers\LicenciadoDashboardController::class, 'perfil'])->name('perfil');
        Route::get('/suporte', [App\Http\Controllers\LicenciadoDashboardController::class, 'suporte'])->name('suporte');
    });
    
    // Rotas para Leads
    Route::get('/leads', [App\Http\Controllers\LeadController::class, 'index'])->name('dashboard.leads');
    Route::post('/leads', [App\Http\Controllers\LeadController::class, 'store'])->name('leads.store');
    Route::get('/leads/{id}', [App\Http\Controllers\LeadController::class, 'show'])->name('leads.show');
    Route::get('/leads/{id}/edit', [App\Http\Controllers\LeadController::class, 'edit'])->name('leads.edit');
    Route::put('/leads/{id}', [App\Http\Controllers\LeadController::class, 'update'])->name('leads.update');
    Route::delete('/leads/{id}', [App\Http\Controllers\LeadController::class, 'destroy'])->name('leads.destroy');
    Route::patch('/leads/{id}/toggle-status', [App\Http\Controllers\LeadController::class, 'toggleStatus'])->name('leads.toggle-status');
    Route::get('/leads/{id}/followup', [App\Http\Controllers\LeadController::class, 'getFollowUp'])->name('leads.followup');
    Route::post('/leads/{id}/followup', [App\Http\Controllers\LeadController::class, 'storeFollowUp'])->name('leads.followup.store');
    Route::post('/leads/send-marketing-email', [App\Http\Controllers\LeadController::class, 'sendMarketingEmail'])->name('leads.send-marketing-email');
    
                    Route::get('/dashboard/licenciados', [App\Http\Controllers\LicenciadoController::class, 'index'])->name('dashboard.licenciados');
                Route::post('/licenciados', [App\Http\Controllers\LicenciadoController::class, 'store'])->name('licenciados.store');
                Route::get('/licenciados', [App\Http\Controllers\LicenciadoController::class, 'index'])->name('licenciados');
                Route::put('/dashboard/licenciados/{licenciado}', [App\Http\Controllers\LicenciadoController::class, 'update'])->name('licenciados.update');
                Route::delete('/dashboard/licenciados/{licenciado}', [App\Http\Controllers\LicenciadoController::class, 'destroy'])->name('licenciados.destroy');
                Route::patch('/dashboard/licenciados/{licenciado}/status', [App\Http\Controllers\LicenciadoController::class, 'alterarStatus'])->name('licenciados.status');
                Route::get('/dashboard/licenciados/{licenciado}/download/{tipo}', [App\Http\Controllers\LicenciadoController::class, 'downloadDocumento'])->name('licenciados.download');
                Route::get('/dashboard/licenciados/{licenciado}/detalhes', [App\Http\Controllers\LicenciadoController::class, 'getDetalhes'])->name('licenciados.detalhes');
                Route::get('/dashboard/licenciados/{licenciado}/gerenciar', [App\Http\Controllers\LicenciadoController::class, 'gerenciar'])->name('licenciados.gerenciar');
                Route::get('/dashboard/licenciados/{licenciado}/followup', [App\Http\Controllers\LicenciadoController::class, 'getFollowUp'])->name('licenciados.followup');
                Route::post('/dashboard/licenciados/{licenciado}/followup', [App\Http\Controllers\LicenciadoController::class, 'storeFollowUp'])->name('licenciados.followup.store');
    
    // Rotas para Marketing
    Route::get('/dashboard/marketing', [App\Http\Controllers\MarketingController::class, 'index'])->name('dashboard.marketing');
    Route::get('/dashboard/marketing/campanhas', [App\Http\Controllers\MarketingController::class, 'campanhas'])->name('dashboard.marketing.campanhas');
    Route::get('/dashboard/marketing/modelos', [App\Http\Controllers\MarketingController::class, 'modelos'])->name('dashboard.marketing.modelos');
    Route::post('/dashboard/marketing/modelos', [App\Http\Controllers\MarketingController::class, 'storeModelo'])->name('dashboard.marketing.modelos.store');
    Route::post('/dashboard/marketing/enviar-email', [App\Http\Controllers\MarketingController::class, 'enviarEmail'])->name('dashboard.marketing.enviar-email');
    
    // Rotas para Campanhas
    Route::get('/dashboard/marketing/campanhas/{id}', [App\Http\Controllers\MarketingController::class, 'getCampanha'])->name('dashboard.marketing.campanhas.show');
    Route::get('/dashboard/marketing/campanhas/{id}/detalhes', [App\Http\Controllers\MarketingController::class, 'showCampanha'])->name('dashboard.marketing.campanhas.detalhes');
    Route::post('/dashboard/marketing/campanhas', [App\Http\Controllers\MarketingController::class, 'storeCampanha'])->name('dashboard.marketing.campanhas.store');
    Route::put('/dashboard/marketing/campanhas/{id}', [App\Http\Controllers\MarketingController::class, 'updateCampanha'])->name('dashboard.marketing.campanhas.update');
    Route::patch('/dashboard/marketing/campanhas/{id}/status', [App\Http\Controllers\MarketingController::class, 'changeStatus'])->name('dashboard.marketing.campanhas.status');
    Route::post('/dashboard/marketing/campanhas/{id}/enviar', [App\Http\Controllers\MarketingController::class, 'enviarCampanha'])->name('dashboard.marketing.campanhas.enviar');
    
    // Rota para teste de e-mail
    Route::post('/dashboard/marketing/testar-email', [App\Http\Controllers\MarketingController::class, 'testarEmail'])->name('dashboard.marketing.testar-email');
    
    // Rotas para Configurações
    Route::get('/dashboard/configuracoes', [App\Http\Controllers\ConfiguracaoController::class, 'index'])->name('dashboard.configuracoes');
    Route::post('/dashboard/configuracoes', [App\Http\Controllers\ConfiguracaoController::class, 'update'])->name('configuracoes.update');
    
    // Rotas para Usuários (apenas Super Admin)
    Route::middleware(['permission:users.view'])->group(function () {
        Route::get('/dashboard/users', [App\Http\Controllers\UserController::class, 'index'])->name('dashboard.users');
        Route::get('/dashboard/users/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');
    });

    
    // Rotas para criação de usuários
    Route::middleware(['permission:users.create'])->group(function () {
        Route::get('/dashboard/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
        Route::post('/dashboard/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    });
    
    Route::middleware(['permission:users.update'])->group(function () {
        Route::get('/dashboard/users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
        Route::put('/dashboard/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
        Route::patch('/dashboard/users/{user}/toggle-status', [App\Http\Controllers\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });
    
    Route::middleware(['permission:users.delete'])->group(function () {
        Route::delete('/dashboard/users/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
    });
    
    // Rotas de Permissões (Sistema de Perfil de Usuário)
    Route::middleware(['permission:permissoes.view'])->group(function () {
        Route::get('/permissions', [App\Http\Controllers\PermissionController::class, 'index'])->name('permissions.index');
        Route::get('/permissions/{permission}', [App\Http\Controllers\PermissionController::class, 'show'])->name('permissions.show');
        Route::get('/permissions/role/{role}/manage', [App\Http\Controllers\PermissionController::class, 'manageRolePermissions'])->name('permissions.manage-role');
    });
    
    Route::middleware(['permission:permissoes.create'])->group(function () {
        Route::get('/permissions/create', [App\Http\Controllers\PermissionController::class, 'create'])->name('permissions.create');
        Route::post('/permissions', [App\Http\Controllers\PermissionController::class, 'store'])->name('permissions.store');
    });
    
    Route::middleware(['permission:permissoes.update'])->group(function () {
        Route::get('/permissions/{permission}/edit', [App\Http\Controllers\PermissionController::class, 'edit'])->name('permissions.edit');
        Route::put('/permissions/{permission}', [App\Http\Controllers\PermissionController::class, 'update'])->name('permissions.update');
        Route::put('/permissions/role/{role}/update', [App\Http\Controllers\PermissionController::class, 'updateRolePermissions'])->name('permissions.update-role');
    });
    
    Route::middleware(['permission:permissoes.delete'])->group(function () {
        Route::delete('/permissions/{permission}', [App\Http\Controllers\PermissionController::class, 'destroy'])->name('permissions.destroy');
    });
    
    Route::post('/api/consultar-cnpj', function (Illuminate\Http\Request $request) {
        $documento = preg_replace('/[^0-9]/', '', $request->input('cnpj'));
        
        if (strlen($documento) !== 11 && strlen($documento) !== 14) {
            return response()->json(['error' => 'CNPJ/CPF inválido'], 400);
        }
        
        // Verificar se é CPF ou CNPJ
        $isCPF = strlen($documento) === 11;
        
        try {
            if ($isCPF) {
                // Consulta de CPF
                $response = Http::timeout(10)
                    ->withOptions([
                        'curl' => [
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                        ]
                    ])
                    ->get("https://brasilapi.com.br/api/cpf/v1/{$documento}");
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Converter formato da BrasilAPI para CPF
                    $convertedData = [
                        'nome' => $data['nome'] ?? '',
                        'fantasia' => '', // CPF não tem nome fantasia
                        'logradouro' => $data['logradouro'] ?? '',
                        'numero' => $data['numero'] ?? '',
                        'complemento' => $data['complemento'] ?? '',
                        'bairro' => $data['bairro'] ?? '',
                        'municipio' => $data['municipio'] ?? '',
                        'uf' => $data['uf'] ?? '',
                        'cep' => $data['cep'] ?? '',
                        'situacao' => $data['situacao'] ?? 'ATIVO',
                        'tipo' => 'PESSOA FÍSICA',
                        'porte' => '',
                        'abertura' => $data['data_nascimento'] ?? '',
                        'natureza_juridica' => 'Pessoa Física',
                        'capital_social' => '',
                        'email' => '',
                        'telefone' => '',
                        'cnpj' => $documento
                    ];
                    
                    return response()->json($convertedData);
                }
                
                // Dados de exemplo para CPF de teste
                if ($documento === '12345678901') {
                    $dadosExemplo = [
                        'nome' => 'JOÃO SILVA SANTOS',
                        'fantasia' => '',
                        'logradouro' => 'Rua das Palmeiras',
                        'numero' => '456',
                        'complemento' => 'Apto 202',
                        'bairro' => 'Jardim América',
                        'municipio' => 'Rio de Janeiro',
                        'uf' => 'RJ',
                        'cep' => '20000-000',
                        'situacao' => 'ATIVO',
                        'tipo' => 'PESSOA FÍSICA',
                        'porte' => '',
                        'abertura' => '1985-05-15',
                        'natureza_juridica' => 'Pessoa Física',
                        'capital_social' => '',
                        'email' => 'joao.silva@email.com',
                        'telefone' => '21 98765-4321',
                        'cnpj' => $documento
                    ];
                    
                    return response()->json($dadosExemplo);
                }
                
                return response()->json(['error' => 'CPF não encontrado. Tente novamente ou verifique se o número está correto.'], 404);
                
            } else {
                // Consulta de CNPJ
                // Primeira tentativa: API pública da Receita Federal
                $response = Http::timeout(10)
                    ->withOptions([
                        'curl' => [
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                        ]
                    ])
                    ->get("https://receitaws.com.br/v1/cnpj/{$documento}");
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['status']) && $data['status'] === 'ERROR') {
                        // Se a primeira API falhar, tentar a segunda
                    } else {
                        return response()->json($data);
                    }
                }
                
                // Segunda tentativa: BrasilAPI
                $response2 = Http::timeout(10)
                    ->withOptions([
                        'curl' => [
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                        ]
                    ])
                    ->get("https://brasilapi.com.br/api/cnpj/v1/{$documento}");
                
                if ($response2->successful()) {
                    $data = $response2->json();
                    
                    // Converter formato da BrasilAPI para o formato esperado
                    $convertedData = [
                        'nome' => $data['razao_social'] ?? '',
                        'fantasia' => $data['nome_fantasia'] ?? '',
                        'logradouro' => $data['logradouro'] ?? '',
                        'numero' => $data['numero'] ?? '',
                        'complemento' => $data['complemento'] ?? '',
                        'bairro' => $data['bairro'] ?? '',
                        'municipio' => $data['municipio'] ?? '',
                        'uf' => $data['uf'] ?? '',
                        'cep' => $data['cep'] ?? '',
                        'situacao' => $data['situacao_cadastral'] ?? '',
                        'tipo' => $data['tipo'] ?? '',
                        'porte' => $data['porte'] ?? '',
                        'abertura' => $data['data_inicio_atividade'] ?? '',
                        'natureza_juridica' => $data['natureza_juridica'] ?? '',
                        'capital_social' => $data['capital_social'] ?? '',
                        'email' => $data['email'] ?? '',
                        'telefone' => $data['ddd_telefone_1'] ?? '',
                        'cnpj' => $data['cnpj'] ?? $documento
                    ];
                    
                    return response()->json($convertedData);
                }
                
                // Se ambas as APIs falharem, retornar dados de exemplo para teste
                if ($documento === '44051566000118') {
                    $dadosExemplo = [
                        'nome' => 'EMPRESA EXEMPLO LTDA',
                        'fantasia' => 'EMPRESA EXEMPLO',
                        'logradouro' => 'Rua das Flores',
                        'numero' => '123',
                        'complemento' => 'Sala 101',
                        'bairro' => 'Centro',
                        'municipio' => 'São Paulo',
                        'uf' => 'SP',
                        'cep' => '01234-567',
                        'situacao' => 'ATIVA',
                        'tipo' => 'MATRIZ',
                        'porte' => 'MEDIA EMPRESA',
                        'abertura' => '2020-01-01',
                        'natureza_juridica' => '206-2 - Sociedade Empresária Limitada',
                        'capital_social' => '100000.00',
                        'email' => 'contato@empresaexemplo.com.br',
                        'telefone' => '11 1234-5678',
                        'cnpj' => $documento
                    ];
                    
                    return response()->json($dadosExemplo);
                }
                
                return response()->json(['error' => 'CNPJ não encontrado. Tente novamente ou verifique se o número está correto.'], 404);
            }
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro na consulta: ' . $e->getMessage()], 500);
        }
    })->name('api.consultar-cnpj');
});




// Rota de teste para criação de usuário (TEMPORÁRIA)
Route::post('/test-user-create', function(\Illuminate\Http\Request $request) {
    \Log::info('🧪 TESTE - Criação de usuário', [
        'is_ajax' => $request->ajax(),
        'accept_header' => $request->header('Accept'),
        'x_requested_with' => $request->header('X-Requested-With'),
        'data' => $request->all()
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Teste funcionando!',
        'data' => $request->all()
    ]);
});

// Rota personalizada para logout
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout.custom');

// Rota para visualizar logs de email (apenas para desenvolvimento)
Route::get('/email-logs', [App\Http\Controllers\EmailLogController::class, 'index'])->name('email.logs');

// Rotas para Operações
Route::get('/operacoes', [App\Http\Controllers\OperacaoController::class, 'index'])->name('dashboard.operacoes');
Route::post('/operacoes', [App\Http\Controllers\OperacaoController::class, 'store'])->name('operacoes.store');
Route::put('/operacoes/{id}', [App\Http\Controllers\OperacaoController::class, 'update'])->name('operacoes.update');
Route::delete('/operacoes/{id}', [App\Http\Controllers\OperacaoController::class, 'destroy'])->name('operacoes.destroy');
Route::get('/operacoes/list', [App\Http\Controllers\OperacaoController::class, 'getOperacoes'])->name('operacoes.list');

// Rotas para Planos
Route::get('/planos', [App\Http\Controllers\PlanoController::class, 'index'])->name('dashboard.planos');
Route::post('/planos', [App\Http\Controllers\PlanoController::class, 'store'])->name('planos.store');
Route::get('/planos/{id}', [App\Http\Controllers\PlanoController::class, 'show'])->name('planos.show');
Route::get('/planos/{id}/edit', [App\Http\Controllers\PlanoController::class, 'edit'])->name('planos.edit');
Route::put('/planos/{id}', [App\Http\Controllers\PlanoController::class, 'update'])->name('planos.update');
Route::delete('/planos/{id}', [App\Http\Controllers\PlanoController::class, 'destroy'])->name('planos.destroy');
Route::patch('/planos/{id}/toggle-status', [App\Http\Controllers\PlanoController::class, 'toggleStatus'])->name('planos.toggle-status');
Route::get('/planos/operacoes/list', [App\Http\Controllers\PlanoController::class, 'getOperacoes'])->name('planos.operacoes.list');
Route::get('/planos/adquirentes/list', [App\Http\Controllers\PlanoController::class, 'getAdquirentes'])->name('planos.adquirentes.list');
Route::get('/planos/bandeiras/list', [App\Http\Controllers\PlanoController::class, 'getBandeiras'])->name('planos.bandeiras.list');
Route::post('/planos/filter', [App\Http\Controllers\PlanoController::class, 'filter'])->name('planos.filter');

// Rotas para Contratos
Route::prefix('contracts')->name('contracts.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\ContractController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\ContractController::class, 'create'])->name('create');
    
    // Novo fluxo de geração de contratos (DEVE VIR ANTES das rotas com parâmetros)
    Route::get('/generate', [App\Http\Controllers\ContractController::class, 'generateIndex'])->name('generate.index');
    
    // Rotas GET para exibir as páginas (apenas step1, step2 e step3 são processados via POST)
    Route::get('/generate/step1', function() { return redirect()->route('contracts.generate.index'); });
    Route::get('/generate/step2', function() { return redirect()->route('contracts.generate.index'); });
    Route::get('/generate/step3', function() { return redirect()->route('contracts.generate.index'); });
    
    // Rotas POST para processar os dados
    Route::post('/generate/step1', [App\Http\Controllers\ContractController::class, 'generateStep1'])->name('generate.step1');
    Route::post('/generate/step2', [App\Http\Controllers\ContractController::class, 'generateStep2'])->name('generate.step2');
    Route::post('/generate/step3', [App\Http\Controllers\ContractController::class, 'generateStep3'])->name('generate.step3');
    
    Route::post('/', [App\Http\Controllers\ContractController::class, 'store'])->name('store');
    Route::get('/{contract}', [App\Http\Controllers\ContractController::class, 'show'])->name('show');
    Route::get('/{contract}/view-pdf', [App\Http\Controllers\ContractController::class, 'viewPdf'])->name('view-pdf');
    Route::post('/{contract}/review-documents', [App\Http\Controllers\ContractController::class, 'reviewDocuments'])->name('review-documents');
    Route::patch('/{contract}/update-notes', [App\Http\Controllers\ContractController::class, 'updateNotes'])->name('update-notes');
    Route::post('/{contract}/send', [App\Http\Controllers\ContractController::class, 'sendContract'])->name('send');
    
    // Novos endpoints para steps encadeados
    Route::post('/{contract}/upload-template', [App\Http\Controllers\ContractController::class, 'uploadTemplate'])->name('upload-template');
    Route::post('/{contract}/fill', [App\Http\Controllers\ContractController::class, 'fillTemplate'])->name('fill');
    Route::post('/{contract}/generate-pdf', [App\Http\Controllers\ContractController::class, 'generatePdfFromTemplate'])->name('generate-pdf');
    Route::post('/{contract}/send-email', [App\Http\Controllers\ContractController::class, 'sendEmail'])->name('send-email');
    Route::get('/{contract}/signature-status', [App\Http\Controllers\ContractController::class, 'checkSignatureStatus'])->name('signature-status');
    Route::post('/{contract}/approve', [App\Http\Controllers\ContractController::class, 'approveContract'])->name('approve');
    Route::post('/{contract}/generate', [App\Http\Controllers\ContractController::class, 'generateContract'])->name('generate');
    Route::get('/{contract}/preview', [App\Http\Controllers\ContractController::class, 'previewContract'])->name('preview');
    Route::get('/{contract}/download', [App\Http\Controllers\ContractController::class, 'downloadContract'])->name('download');
    Route::get('/documents/{document}/download', [App\Http\Controllers\ContractController::class, 'downloadDocument'])->name('download-document');
    Route::delete('/{contract}', [App\Http\Controllers\ContractController::class, 'destroy'])->name('destroy');
});

// Rotas para templates de contrato
Route::prefix('contract-templates')->name('contract-templates.')->middleware(['auth', 'permission:contratos.manage'])->group(function () {
    Route::get('/', [App\Http\Controllers\ContractTemplateController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\ContractTemplateController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\ContractTemplateController::class, 'store'])->name('store');
    Route::get('/{contractTemplate}', [App\Http\Controllers\ContractTemplateController::class, 'show'])->name('show');
    Route::get('/{contractTemplate}/edit', [App\Http\Controllers\ContractTemplateController::class, 'edit'])->name('edit');
    Route::put('/{contractTemplate}', [App\Http\Controllers\ContractTemplateController::class, 'update'])->name('update');
    Route::delete('/{contractTemplate}', [App\Http\Controllers\ContractTemplateController::class, 'destroy'])->name('destroy');
    Route::get('/{contractTemplate}/preview', [App\Http\Controllers\ContractTemplateController::class, 'preview'])->name('preview');
    Route::post('/{contractTemplate}/duplicate', [App\Http\Controllers\ContractTemplateController::class, 'duplicate'])->name('duplicate');
    Route::patch('/{contractTemplate}/toggle-status', [App\Http\Controllers\ContractTemplateController::class, 'toggleStatus'])->name('toggle-status');
});

// Rotas para Adquirentes (dentro do middleware de autenticação)
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'redirect.role'])->group(function () {
    Route::get('/adquirentes', [App\Http\Controllers\AdquirenteController::class, 'index'])->name('dashboard.adquirentes');
    Route::post('/adquirentes', [App\Http\Controllers\AdquirenteController::class, 'store'])->name('adquirentes.store');
    Route::get('/adquirentes/{id}', [App\Http\Controllers\AdquirenteController::class, 'show'])->name('adquirentes.show');
    Route::get('/adquirentes/{id}/edit', [App\Http\Controllers\AdquirenteController::class, 'edit'])->name('adquirentes.edit');
    Route::put('/adquirentes/{id}', [App\Http\Controllers\AdquirenteController::class, 'update'])->name('adquirentes.update');
    Route::delete('/adquirentes/{id}', [App\Http\Controllers\AdquirenteController::class, 'destroy'])->name('adquirentes.destroy');
    Route::patch('/adquirentes/{id}/toggle-status', [App\Http\Controllers\AdquirenteController::class, 'toggleStatus'])->name('adquirentes.toggle-status');

    // Rotas para Agenda
    Route::get('/agenda', [App\Http\Controllers\AgendaController::class, 'index'])->name('dashboard.agenda');
    Route::get('/agenda/calendario', [App\Http\Controllers\AgendaController::class, 'calendar'])->name('dashboard.agenda.calendar');
    Route::get('/agenda/nova', [App\Http\Controllers\AgendaController::class, 'create'])->name('dashboard.agenda.create');
    Route::get('/agenda/{id}', [App\Http\Controllers\AgendaController::class, 'show'])->name('dashboard.agenda.show');
    Route::get('/agenda/{id}/edit', [App\Http\Controllers\AgendaController::class, 'edit'])->name('dashboard.agenda.edit');
    Route::put('/agenda/{id}', [App\Http\Controllers\AgendaController::class, 'update'])->name('dashboard.agenda.update');
    Route::delete('/agenda/{id}', [App\Http\Controllers\AgendaController::class, 'destroy'])->name('dashboard.agenda.destroy');
    
    // API para licenciados (usado na agenda)
    Route::get('/licenciados/api/list', [App\Http\Controllers\LicenciadoController::class, 'apiList'])->name('licenciados.api.list');
    Route::post('/agenda', [App\Http\Controllers\AgendaController::class, 'store'])->name('agenda.store');
    Route::patch('/agenda/{id}/toggle-status', [App\Http\Controllers\AgendaController::class, 'toggleStatus'])->name('agenda.toggle-status');
    Route::get('/agenda/data/{data}', [App\Http\Controllers\AgendaController::class, 'getAgendaPorData'])->name('agenda.por-data');
    Route::get('/agenda/data', [App\Http\Controllers\AgendaController::class, 'getAgendaPorData'])->name('agenda.por-data.query');
    Route::get('/agenda/licenciados/list', [App\Http\Controllers\AgendaController::class, 'getLicenciados'])->name('agenda.licenciados.list');
    Route::get('/agenda/licenciados/{id}', [App\Http\Controllers\AgendaController::class, 'getLicenciadoDetails'])->name('agenda.licenciados.details');

    // Rotas para integração com Google Calendar
    Route::prefix('google')->name('google.')->group(function () {
        Route::get('/auth', [App\Http\Controllers\GoogleAuthController::class, 'redirectToGoogle'])->name('auth');
        Route::get('/callback', [App\Http\Controllers\GoogleAuthController::class, 'handleGoogleCallback'])->name('callback');
        Route::post('/disconnect', [App\Http\Controllers\GoogleAuthController::class, 'disconnect'])->name('disconnect');
        
        // Rotas de status mais robustas
        Route::get('/status', [App\Http\Controllers\GoogleStatusController::class, 'status'])->name('status');
        Route::get('/simple-test', [App\Http\Controllers\GoogleStatusController::class, 'simpleTest'])->name('simple-test');
        Route::get('/test', [App\Http\Controllers\GoogleAuthController::class, 'test'])->name('test');
        
        Route::get('/debug', function () {
            return view('google-debug');
        })->name('debug');
    });
});

// Rotas públicas para confirmação de agenda (sem autenticação)
Route::get('/agenda/confirmar/{id}', [App\Http\Controllers\AgendaController::class, 'confirmarParticipacao'])->name('agenda.confirmar');
Route::get('/agenda/confirmacao-sucesso', [App\Http\Controllers\AgendaController::class, 'confirmacaoSucesso'])->name('agenda.confirmacao.sucesso');

// Rota de debug Google SEM autenticação (temporária para debug)
Route::get('/google-debug-public', function () {
    try {
        return response()->json([
            'success' => true,
            'message' => 'Rota Google funcionando!',
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'config' => [
                'client_id_set' => !empty(config('google.client_id')),
                'client_secret_set' => !empty(config('google.client_secret')),
                'redirect_uri' => config('google.redirect_uri'),
            ],
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ], 500);
    }
});

// Rotas públicas para assinatura de contratos (sem autenticação)
Route::get('/contracts/sign/{token}', [App\Http\Controllers\ContractController::class, 'showSignaturePage'])->name('contracts.sign.show');
Route::post('/contracts/sign/{token}', [App\Http\Controllers\ContractController::class, 'processSignature'])->name('contracts.sign.process');
Route::get('/contracts/sign/{token}/success', [App\Http\Controllers\ContractController::class, 'showSignatureSuccess'])->name('contracts.sign.success');

// Webhook para receber notificações de assinatura
Route::post('/contracts/signature-webhook', [App\Http\Controllers\ContractController::class, 'signatureWebhook'])->name('contracts.signature-webhook');

// Rotas públicas para produtos e categorias (sem autenticação)
Route::prefix('produtos')->name('products.')->group(function () {
    Route::get('/', [App\Http\Controllers\ProductController::class, 'index'])->name('index');
    Route::get('/buscar', [App\Http\Controllers\ProductController::class, 'search'])->name('search');
    Route::get('/categoria/{categorySlug}', [App\Http\Controllers\ProductController::class, 'category'])->name('category');
    Route::get('/{productSlug}', [App\Http\Controllers\ProductController::class, 'show'])->name('show');
});

// API Routes para produtos (sem autenticação)
Route::prefix('api/produtos')->name('api.products.')->group(function () {
    Route::get('/busca-rapida', [App\Http\Controllers\ProductController::class, 'quickSearch'])->name('quick-search');
    Route::get('/categoria/{categorySlug}', [App\Http\Controllers\ProductController::class, 'categoryProducts'])->name('category');
});


// Rota para callback OAuth2 do Google Calendar
Route::get('/auth/google/callback', [App\Http\Controllers\GoogleAuthController::class, 'callback'])->name('google.auth.callback');

// Rotas públicas para confirmação de compromissos (sem autenticação)
Route::get('/agenda/confirmacao/{token}', [App\Http\Controllers\AgendaController::class, 'showConfirmation'])->name('agenda.confirmation.show');
Route::post('/agenda/confirmacao/{token}/aceitar', [App\Http\Controllers\AgendaController::class, 'acceptInvitation'])->name('agenda.confirmation.accept');
Route::post('/agenda/confirmacao/{token}/rejeitar', [App\Http\Controllers\AgendaController::class, 'rejectInvitation'])->name('agenda.confirmation.reject');
