<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Lead;
use App\Models\Plano;
use App\Models\Operacao;
use App\Models\Adquirente;
use App\Models\Estabelecimento;
use Carbon\Carbon;

class LicenciadoDashboardController extends Controller
{
    /**
     * Dashboard principal do licenciado
     */
    public function index()
    {
        $user = Auth::user();
        
        // Verificar se o usuário é licenciado
        if (!$user->role || $user->role->name !== 'licenciado') {
            abort(403, 'Acesso negado. Área restrita para licenciados.');
        }

        // Dados do licenciado
        $licenciado = $user;
        
        // Estatísticas básicas
        $stats = $this->getDashboardStats($licenciado);
        
        // Atividades recentes
        $recentActivities = $this->getRecentActivities($licenciado);
        
        // Planos disponíveis
        $availablePlans = Plano::where('ativo', true)
            ->with(['taxasAtivas' => function($query) {
                $query->orderBy('modalidade')->orderBy('bandeira');
            }])
            ->orderBy('nome')
            ->take(5)
            ->get();

        return view('licenciado.dashboard', compact(
            'licenciado',
            'stats',
            'recentActivities',
            'availablePlans'
        ));
    }

    /**
     * Página de estabelecimentos do licenciado
     */
    public function estabelecimentos()
    {
        $user = Auth::user();
        
        if (!$user->role || $user->role->name !== 'licenciado') {
            abort(403, 'Acesso negado.');
        }

        // Buscar estabelecimentos reais do licenciado
        $estabelecimentos = $user->estabelecimentos()
            ->orderBy('created_at', 'desc')
            ->get();

        // Se não há estabelecimentos, criar dados de exemplo para demonstração
        if ($estabelecimentos->isEmpty()) {
            $estabelecimentosExemplo = collect([
                (object) [
                    'id' => null,
                    'nome_fantasia' => 'Loja Centro (Exemplo)',
                    'razao_social' => 'Comercial Centro Ltda',
                    'cnpj' => '12.345.678/0001-90',
                    'status' => 'ativo',
                    'created_at' => Carbon::now()->subDays(30),
                    'cidade' => 'São Paulo',
                    'estado' => 'SP',
                    'tipo_negocio' => 'varejo',
                    'volume_mensal_estimado' => 15000.00,
                    'is_exemplo' => true
                ],
                (object) [
                    'id' => null,
                    'nome_fantasia' => 'Filial Norte (Exemplo)',
                    'razao_social' => 'Comercial Norte Ltda',
                    'cnpj' => '12.345.678/0002-71',
                    'status' => 'pendente',
                    'created_at' => Carbon::now()->subDays(15),
                    'cidade' => 'Rio de Janeiro',
                    'estado' => 'RJ',
                    'tipo_negocio' => 'servicos',
                    'volume_mensal_estimado' => 8500.00,
                    'is_exemplo' => true
                ]
            ]);
            
            return view('licenciado.estabelecimentos', compact('estabelecimentosExemplo'))
                ->with('mostrarExemplos', true);
        }

        return view('licenciado.estabelecimentos', compact('estabelecimentos'));
    }

    /**
     * Página de vendas do licenciado
     */
    public function vendas()
    {
        $user = Auth::user();
        
        if (!$user->role || $user->role->name !== 'licenciado') {
            abort(403, 'Acesso negado.');
        }

        // Dados de vendas do licenciado
        $vendas = $this->getVendasData($user);
        
        return view('licenciado.vendas', compact('vendas'));
    }

    /**
     * Página de comissões do licenciado
     */
    public function comissoes()
    {
        $user = Auth::user();
        
        if (!$user->role || $user->role->name !== 'licenciado') {
            abort(403, 'Acesso negado.');
        }

        // Dados de comissões do licenciado
        $comissoes = $this->getComissoesData($user);
        
        return view('licenciado.comissoes', compact('comissoes'));
    }

    /**
     * Página de relatórios do licenciado
     */
    public function relatorios()
    {
        $user = Auth::user();
        
        if (!$user->role || $user->role->name !== 'licenciado') {
            abort(403, 'Acesso negado.');
        }

        // Dados para relatórios
        $relatorios = $this->getRelatoriosData($user);
        
        return view('licenciado.relatorios', compact('relatorios'));
    }

    /**
     * Perfil do licenciado
     */
    public function perfil()
    {
        $user = Auth::user();
        
        if (!$user->role || $user->role->name !== 'licenciado') {
            abort(403, 'Acesso negado.');
        }

        return view('licenciado.perfil', compact('user'));
    }

    /**
     * Suporte para o licenciado
     */
    public function suporte()
    {
        $user = Auth::user();
        
        if (!$user->role || $user->role->name !== 'licenciado') {
            abort(403, 'Acesso negado.');
        }

        return view('licenciado.suporte');
    }

    /**
     * Mostrar formulário de criação de estabelecimento
     */
    public function createEstabelecimento()
    {
        $user = Auth::user();
        
        if (!$user->role || $user->role->name !== 'licenciado') {
            abort(403, 'Acesso negado.');
        }

        return view('licenciado.estabelecimentos.create');
    }

    /**
     * Salvar novo estabelecimento
     */
    public function storeEstabelecimento(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->role || $user->role->name !== 'licenciado') {
            abort(403, 'Acesso negado.');
        }

        $validated = $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'razao_social' => 'required|string|max:255',
            'cnpj' => 'required|string|size:18|unique:estabelecimentos,cnpj',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'celular' => 'nullable|string|max:20',
            'endereco' => 'required|string|max:255',
            'numero' => 'required|string|max:10',
            'complemento' => 'nullable|string|max:100',
            'bairro' => 'required|string|max:100',
            'cidade' => 'required|string|max:100',
            'estado' => 'required|string|size:2',
            'cep' => 'required|string|size:9',
            'tipo_negocio' => 'required|in:varejo,atacado,servicos,alimentacao,outros',
            'volume_mensal_estimado' => 'nullable|numeric|min:0|max:9999999999.99',
            'observacoes' => 'nullable|string|max:1000'
        ]);

        $validated['licenciado_id'] = $user->id;
        $validated['status'] = 'pendente'; // Novo estabelecimento sempre pendente

        $estabelecimento = Estabelecimento::create($validated);

        return redirect()->route('licenciado.estabelecimentos')
            ->with('success', 'Estabelecimento cadastrado com sucesso! Aguarde aprovação.');
    }

    /**
     * Mostrar detalhes do estabelecimento
     */
    public function showEstabelecimento(Estabelecimento $estabelecimento)
    {
        $user = Auth::user();
        
        if (!$user->role || $user->role->name !== 'licenciado') {
            abort(403, 'Acesso negado.');
        }

        // Verificar se o estabelecimento pertence ao licenciado
        if ($estabelecimento->licenciado_id !== $user->id) {
            abort(403, 'Você não tem permissão para ver este estabelecimento.');
        }

        return view('licenciado.estabelecimentos.show', compact('estabelecimento'));
    }

    /**
     * Mostrar formulário de edição do estabelecimento
     */
    public function editEstabelecimento(Estabelecimento $estabelecimento)
    {
        $user = Auth::user();
        
        if (!$user->role || $user->role->name !== 'licenciado') {
            abort(403, 'Acesso negado.');
        }

        // Verificar se o estabelecimento pertence ao licenciado
        if ($estabelecimento->licenciado_id !== $user->id) {
            abort(403, 'Você não tem permissão para editar este estabelecimento.');
        }

        return view('licenciado.estabelecimentos.edit', compact('estabelecimento'));
    }

    /**
     * Atualizar estabelecimento
     */
    public function updateEstabelecimento(Request $request, Estabelecimento $estabelecimento)
    {
        $user = Auth::user();
        
        if (!$user->role || $user->role->name !== 'licenciado') {
            abort(403, 'Acesso negado.');
        }

        // Verificar se o estabelecimento pertence ao licenciado
        if ($estabelecimento->licenciado_id !== $user->id) {
            abort(403, 'Você não tem permissão para editar este estabelecimento.');
        }

        $validated = $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'razao_social' => 'required|string|max:255',
            'cnpj' => 'required|string|size:18|unique:estabelecimentos,cnpj,' . $estabelecimento->id,
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'celular' => 'nullable|string|max:20',
            'endereco' => 'required|string|max:255',
            'numero' => 'required|string|max:10',
            'complemento' => 'nullable|string|max:100',
            'bairro' => 'required|string|max:100',
            'cidade' => 'required|string|max:100',
            'estado' => 'required|string|size:2',
            'cep' => 'required|string|size:9',
            'tipo_negocio' => 'required|in:varejo,atacado,servicos,alimentacao,outros',
            'volume_mensal_estimado' => 'nullable|numeric|min:0|max:9999999999.99',
            'observacoes' => 'nullable|string|max:1000'
        ]);

        $estabelecimento->update($validated);

        return redirect()->route('licenciado.estabelecimentos')
            ->with('success', 'Estabelecimento atualizado com sucesso!');
    }

    /**
     * Obter estatísticas do dashboard
     */
    private function getDashboardStats($licenciado)
    {
        // Contar estabelecimentos reais
        $estabelecimentosAtivos = $licenciado->estabelecimentos()->where('status', 'ativo')->count();
        $estabelecimentosPendentes = $licenciado->estabelecimentos()->where('status', 'pendente')->count();
        $totalEstabelecimentos = $licenciado->estabelecimentos()->count();

        // Se não há dados reais, usar exemplos para demonstração
        if ($totalEstabelecimentos === 0) {
            return (object) [
                'estabelecimentos_ativos' => 2,
                'estabelecimentos_pendentes' => 1,
                'total_estabelecimentos' => 3,
                'vendas_mes' => 23500.00,
                'transacoes_mes' => 235,
                'comissao_mes' => 1175.00,
                'crescimento_vendas' => 15.5,
                'crescimento_transacoes' => 8.3,
                'volume_estimado' => 32000.00,
                'is_exemplo' => true
            ];
        }

        // Calcular volume mensal estimado dos estabelecimentos
        $volumeEstimado = $licenciado->estabelecimentos()
            ->where('status', 'ativo')
            ->sum('volume_mensal_estimado') ?? 0;

        return (object) [
            'estabelecimentos_ativos' => $estabelecimentosAtivos,
            'estabelecimentos_pendentes' => $estabelecimentosPendentes,
            'total_estabelecimentos' => $totalEstabelecimentos,
            'vendas_mes' => $volumeEstimado * 0.7, // Estimativa de 70% do volume
            'transacoes_mes' => intval($volumeEstimado / 100), // Estimativa baseada no volume
            'comissao_mes' => $volumeEstimado * 0.05, // Estimativa de 5% de comissão
            'crescimento_vendas' => rand(5, 25) + (rand(0, 99) / 100), // Simulação
            'crescimento_transacoes' => rand(3, 20) + (rand(0, 99) / 100), // Simulação
            'volume_estimado' => $volumeEstimado,
            'is_exemplo' => false
        ];
    }

    /**
     * Obter atividades recentes
     */
    private function getRecentActivities($licenciado)
    {
        return collect([
            (object) [
                'tipo' => 'venda',
                'descricao' => 'Nova venda aprovada - R$ 350,00',
                'data' => Carbon::now()->subHours(2),
                'icon' => 'fas fa-shopping-cart',
                'color' => 'green'
            ],
            (object) [
                'tipo' => 'estabelecimento',
                'descricao' => 'Estabelecimento "Loja Centro" atualizado',
                'data' => Carbon::now()->subHours(5),
                'icon' => 'fas fa-store',
                'color' => 'blue'
            ],
            (object) [
                'tipo' => 'comissao',
                'descricao' => 'Comissão de R$ 125,00 creditada',
                'data' => Carbon::now()->subDay(),
                'icon' => 'fas fa-coins',
                'color' => 'yellow'
            ]
        ]);
    }

    /**
     * Obter dados de vendas
     */
    private function getVendasData($user)
    {
        return (object) [
            'vendas_hoje' => 2350.00,
            'vendas_semana' => 15800.00,
            'vendas_mes' => 23500.00,
            'meta_mes' => 30000.00,
            'percentual_meta' => 78.3,
            'vendas_por_dia' => [
                ['data' => Carbon::now()->subDays(6)->format('d/m'), 'valor' => 1200],
                ['data' => Carbon::now()->subDays(5)->format('d/m'), 'valor' => 1800],
                ['data' => Carbon::now()->subDays(4)->format('d/m'), 'valor' => 2200],
                ['data' => Carbon::now()->subDays(3)->format('d/m'), 'valor' => 1900],
                ['data' => Carbon::now()->subDays(2)->format('d/m'), 'valor' => 2800],
                ['data' => Carbon::now()->subDays(1)->format('d/m'), 'valor' => 2100],
                ['data' => Carbon::now()->format('d/m'), 'valor' => 2350]
            ]
        ];
    }

    /**
     * Obter dados de comissões
     */
    private function getComissoesData($user)
    {
        return (object) [
            'comissao_mes' => 1175.00,
            'comissao_pendente' => 325.00,
            'comissao_paga' => 850.00,
            'proximo_pagamento' => Carbon::now()->addDays(5),
            'historico' => [
                ['mes' => 'Janeiro', 'valor' => 980.00, 'status' => 'pago'],
                ['mes' => 'Fevereiro', 'valor' => 1250.00, 'status' => 'pago'],
                ['mes' => 'Março', 'valor' => 1175.00, 'status' => 'pendente']
            ]
        ];
    }

    /**
     * Obter dados de relatórios
     */
    private function getRelatoriosData($user)
    {
        return (object) [
            'relatorios_disponiveis' => [
                'vendas_detalhado',
                'comissoes_mensais',
                'estabelecimentos_performance',
                'transacoes_por_bandeira'
            ],
            'periodo_disponivel' => [
                'ultima_semana',
                'ultimo_mes',
                'ultimos_3_meses',
                'ultimo_ano'
            ]
        ];
    }
}
