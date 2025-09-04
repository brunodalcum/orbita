<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeLeadEmail;
use App\Mail\MarketingEmail;
use App\Models\EmailModelo;
use Dompdf\Dompdf;
use Dompdf\Options;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leads = Lead::orderBy('created_at', 'desc')->get();
        
        return view('dashboard.leads', compact('leads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'empresa' => 'nullable|string|max:255',
            'status' => 'required|in:novo,contatado,qualificado,proposta,fechado,perdido',
            'origem' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string'
        ]);

        try {
            $lead = Lead::create($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Lead criado com sucesso!',
                'lead' => $lead
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar lead: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $lead = Lead::with('followUps.user')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'lead' => $lead
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lead não encontrado'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): JsonResponse
    {
        try {
            $lead = Lead::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'lead' => $lead
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lead não encontrado'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'empresa' => 'nullable|string|max:255',
            'status' => 'required|in:novo,contatado,qualificado,proposta,fechado,perdido',
            'origem' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string'
        ]);

        try {
            $lead = Lead::findOrFail($id);
            $lead->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Lead atualizado com sucesso!',
                'lead' => $lead
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar lead: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $lead = Lead::findOrFail($id);
            $lead->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Lead excluído com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir lead: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle lead status (active/inactive)
     */
    public function toggleStatus(string $id): JsonResponse
    {
        try {
            $lead = Lead::findOrFail($id);
            $lead->ativo = !$lead->ativo;
            $lead->save();
            
            $status = $lead->ativo ? 'ativado' : 'desativado';
            
            return response()->json([
                'success' => true,
                'message' => "Lead {$status} com sucesso!",
                'ativo' => $lead->ativo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status do lead: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get leads for follow-up
     */
    public function getFollowUp(string $id): JsonResponse
    {
        try {
            $lead = Lead::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'lead' => $lead
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lead não encontrado'
            ], 404);
        }
    }

    /**
     * Store follow-up information
     */
    public function storeFollowUp(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'observacoes' => 'required|string',
            'proximo_contato' => 'nullable|date'
        ]);

        try {
            $lead = Lead::findOrFail($id);
            
            // Criar o follow-up na nova tabela
            $followUp = $lead->followUps()->create([
                'observacao' => $request->observacoes,
                'proximo_contato' => $request->proximo_contato,
                'user_id' => auth()->id()
            ]);
            
            // Atualizar as observações do lead também
            $lead->update([
                'observacoes' => $lead->observacoes . "\n\n" . now()->format('d/m/Y H:i') . " - " . $request->observacoes
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Follow-up registrado com sucesso!',
                'followUp' => $followUp->load('user')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao registrar follow-up: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store lead from public form
     */
    public function storePublic(Request $request): JsonResponse
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'empresa' => 'nullable|string|max:255',
            'origem' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string'
        ]);

        try {
            $lead = Lead::create([
                'nome' => $request->nome,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'empresa' => $request->empresa,
                'status' => 'novo',
                'origem' => $request->origem ?: 'Formulário Externo',
                'observacoes' => $request->observacoes,
                'ativo' => true
            ]);
            
            // Enviar e-mail de boas-vindas se o lead tiver e-mail
            if ($lead->email) {
                try {
                    Mail::to($lead->email)->send(new WelcomeLeadEmail($lead));
                } catch (\Exception $emailException) {
                    // Log do erro de e-mail, mas não falha o cadastro do lead
                    \Log::error('Erro ao enviar e-mail de boas-vindas: ' . $emailException->getMessage(), [
                        'lead_id' => $lead->id,
                        'email' => $lead->email
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Lead cadastrado com sucesso! Em breve entraremos em contato.',
                'lead' => $lead
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cadastrar lead: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show onboarding page for lead
     */
    public function onboarding(string $id)
    {
        try {
            $lead = Lead::findOrFail($id);
            
            return view('leads.onboarding', compact('lead'));
        } catch (\Exception $e) {
            abort(404, 'Lead não encontrado');
        }
    }

    /**
     * Download presentation PDF for lead
     */
    public function downloadPresentation(string $id)
    {
        try {
            $lead = Lead::findOrFail($id);
            
            $filePath = storage_path('app/presentations/dspay-apresentacao.pdf');
            
            if (!file_exists($filePath)) {
                // Se o arquivo não existir, criar um arquivo de exemplo
                $this->createSamplePresentation($filePath);
            }
            
            return response()->download($filePath, 'DSPay_Apresentacao.pdf', [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="DSPay_Apresentacao.pdf"'
            ]);
        } catch (\Exception $e) {
            abort(404, 'Lead não encontrado');
        }
    }

    /**
     * Create a sample presentation file if it doesn't exist
     */
    private function createSamplePresentation(string $filePath): void
    {
        // Configurar Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        
        // HTML da apresentação
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>DSPay - Apresentação</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
                .header { text-align: center; border-bottom: 3px solid #667eea; padding-bottom: 20px; margin-bottom: 30px; }
                .title { color: #667eea; font-size: 28px; font-weight: bold; }
                .subtitle { color: #4a5568; font-size: 18px; }
                .section { margin-bottom: 25px; }
                .section-title { color: #2d3748; font-size: 20px; font-weight: bold; margin-bottom: 15px; }
                .content { color: #4a5568; font-size: 14px; }
                .highlight { background-color: #f7fafc; padding: 15px; border-left: 4px solid #667eea; margin: 15px 0; }
                .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 2px solid #e2e8f0; color: #718096; }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="title">🚀 DSPay Pagamentos</div>
                <div class="subtitle">Sua Oportunidade de Sucesso em Meios de Pagamento</div>
            </div>
            
            <div class="section">
                <div class="section-title">📋 Sobre a DSPay</div>
                <div class="content">
                    A DSPay é uma empresa especializada em meios de pagamento, oferecendo soluções completas 
                    para parceiros que desejam empreender neste mercado bilionário.
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">💡 Nossa Proposta de Valor</div>
                <div class="content">
                    <ul>
                        <li><strong>Tecnologia Avançada:</strong> Plataforma robusta e confiável</li>
                        <li><strong>Suporte Dedicado:</strong> Equipe especializada para seu sucesso</li>
                        <li><strong>Modelo de Recorrência:</strong> Renda mensal previsível</li>
                        <li><strong>Mercado em Crescimento:</strong> Setor que movimenta bilhões anualmente</li>
                    </ul>
                </div>
            </div>
            
            <div class="highlight">
                <strong>🎯 Oportunidade Única:</strong> Você pode construir seu próprio negócio em meios de pagamento 
                com todo o suporte e tecnologia da DSPay, gerando renda mensal previsível e escalável.
            </div>
            
            <div class="section">
                <div class="section-title">📊 Mercado e Oportunidades</div>
                <div class="content">
                    O mercado de meios de pagamento no Brasil está em constante crescimento, com:
                    <ul>
                        <li>Volume de transações aumentando anualmente</li>
                        <li>Digitalização acelerada do comércio</li>
                        <li>Demanda crescente por soluções de pagamento</li>
                        <li>Oportunidades para todos os tipos de empreendedores</li>
                    </ul>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">🔄 Modelo de Negócio</div>
                <div class="content">
                    <strong>Como Funciona:</strong>
                    <ol>
                        <li>Você se torna nosso parceiro</li>
                        <li>Recebe treinamento e suporte completo</li>
                        <li>Utiliza nossa tecnologia e infraestrutura</li>
                        <li>Gera receita através de comissões</li>
                        <li>Constrói um negócio escalável e recorrente</li>
                    </ol>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">📞 Próximos Passos</div>
                <div class="content">
                    Nossa equipe entrará em contato em breve para:
                    <ul>
                        <li>Apresentação detalhada do negócio</li>
                        <li>Demonstração da plataforma</li>
                        <li>Planejamento da sua estratégia</li>
                        <li>Início da parceria</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer">
                <strong>DSPay Pagamentos</strong><br>
                Transformando sonhos em realidade através da tecnologia<br>
                📧 contato@dspay.com.br | 📱 (11) 99999-9999
            </div>
        </body>
        </html>';
        
        // Carregar HTML no Dompdf
        $dompdf->loadHtml($html);
        
        // Configurar orientação e tamanho
        $dompdf->setPaper('A4', 'portrait');
        
        // Renderizar PDF
        $dompdf->render();
        
        // Salvar arquivo
        file_put_contents($filePath, $dompdf->output());
    }

    /**
     * Send marketing email to a specific lead
     */
    public function sendMarketingEmail(Request $request): JsonResponse
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'assunto' => 'required|string|max:255',
            'mensagem' => 'required|string'
        ]);

        try {
            $lead = Lead::findOrFail($request->lead_id);
            
            if (!$lead->email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lead não possui email cadastrado.'
                ], 400);
            }

            // Criar um modelo temporário para o email
            $modelo = new EmailModelo([
                'nome' => 'Email Marketing Individual',
                'assunto' => $request->assunto,
                'conteudo' => $request->mensagem,
                'tipo' => 'lead',
                'user_id' => auth()->id()
            ]);

            // Enviar o email
            Mail::to($lead->email)->send(new MarketingEmail($modelo));

            return response()->json([
                'success' => true,
                'message' => 'Email enviado com sucesso para ' . $lead->email . '!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar email: ' . $e->getMessage()
            ], 500);
        }
    }
}
