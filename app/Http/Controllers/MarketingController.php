<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Licenciado;
use App\Models\EmailModelo;
use App\Models\Campanha;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\MarketingEmail;
use Illuminate\Support\Facades\Log;

class MarketingController extends Controller
{
    /**
     * Display marketing dashboard
     */
    public function index()
    {
        $totalLeads = Lead::count();
        $totalLicenciados = Licenciado::count();
        $totalModelos = EmailModelo::count();
        $totalCampanhas = Campanha::count();
        $campanhasAtivas = Campanha::ativas()->count();
        
        return view('dashboard.marketing.index', compact('totalLeads', 'totalLicenciados', 'totalModelos', 'totalCampanhas', 'campanhasAtivas'));
    }

    /**
     * Display campaigns page
     */
    public function campanhas()
    {
        $campanhas = Campanha::with('modelo')->orderBy('created_at', 'desc')->get();
        $modelos = EmailModelo::orderBy('nome')->get();
        $totalLeads = Lead::count();
        $totalLicenciados = Licenciado::count();
        
        return view('dashboard.marketing.campanhas', compact('campanhas', 'modelos', 'totalLeads', 'totalLicenciados'));
    }

    /**
     * Get campaign data for editing
     */
    public function getCampanha($id): JsonResponse
    {
        try {
            $campanha = Campanha::with('modelo')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'campanha' => $campanha
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Campanha não encontrada'
            ], 404);
        }
    }

    /**
     * Show campaign details page
     */
    public function showCampanha($id)
    {
        try {
            $campanha = Campanha::with('modelo')->findOrFail($id);
            return view('dashboard.marketing.campanha-detalhes', compact('campanha'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard.marketing.campanhas')
                ->with('error', 'Campanha não encontrada');
        }
    }

    /**
     * Display email templates page
     */
    public function modelos()
    {
        $modelos = EmailModelo::orderBy('created_at', 'desc')->get();
        return view('dashboard.marketing.modelos', compact('modelos'));
    }

    /**
     * Store new email template
     */
    public function storeModelo(Request $request): JsonResponse
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'assunto' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'tipo' => 'required|in:lead,licenciado,geral'
        ]);

        try {
            $modelo = EmailModelo::create([
                'nome' => $request->nome,
                'assunto' => $request->assunto,
                'conteudo' => $request->conteudo,
                'tipo' => $request->tipo,
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Modelo de e-mail criado com sucesso!',
                'modelo' => $modelo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar modelo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store new campaign
     */
    public function storeCampanha(Request $request): JsonResponse
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:lead,licenciado,geral',
            'modelo_id' => 'required|exists:email_modelos,id',
            'descricao' => 'nullable|string',
            'data_inicio' => 'nullable|date',
            'segmentacao' => 'nullable|string'
        ]);

        try {
            // Processar segmentação se for JSON string
            $segmentacao = null;
            if ($request->segmentacao) {
                $segmentacao = json_decode($request->segmentacao, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $segmentacao = [];
                }
            }

            $campanha = Campanha::create([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'tipo' => $request->tipo,
                'modelo_id' => $request->modelo_id,
                'status' => 'rascunho',
                'data_inicio' => $request->data_inicio,
                'segmentacao' => $segmentacao,
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Campanha criada com sucesso!',
                'campanha' => $campanha->load('modelo')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar campanha: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update campaign
     */
    public function updateCampanha(Request $request, $id): JsonResponse
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:lead,licenciado,geral',
            'modelo_id' => 'required|exists:email_modelos,id',
            'descricao' => 'nullable|string',
            'data_inicio' => 'nullable|date',
            'segmentacao' => 'nullable|string'
        ]);

        try {
            // Processar segmentação se for JSON string
            $segmentacao = null;
            if ($request->segmentacao) {
                $segmentacao = json_decode($request->segmentacao, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $segmentacao = [];
                }
            }

            $campanha = Campanha::findOrFail($id);
            $campanha->update([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'tipo' => $request->tipo,
                'modelo_id' => $request->modelo_id,
                'data_inicio' => $request->data_inicio,
                'segmentacao' => $segmentacao
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Campanha atualizada com sucesso!',
                'campanha' => $campanha->load('modelo')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar campanha: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Change campaign status
     */
    public function changeStatus(Request $request, $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:rascunho,ativa,pausada,concluida'
        ]);

        try {
            $campanha = Campanha::findOrFail($id);
            $campanha->update(['status' => $request->status]);
            
            return response()->json([
                'success' => true,
                'message' => "Status da campanha alterado para {$request->status}!",
                'campanha' => $campanha
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send campaign emails
     */
    public function enviarCampanha(Request $request, $id): JsonResponse
    {
        try {
            $campanha = Campanha::with('modelo')->findOrFail($id);
            
            if ($campanha->status !== 'ativa') {
                return response()->json([
                    'success' => false,
                    'message' => 'Apenas campanhas ativas podem ser enviadas!'
                ], 400);
            }

            $destinatarios = $this->getDestinatarios($campanha);
            $enviados = 0;
            $erros = [];

            foreach ($destinatarios as $email) {
                try {
                    Mail::to($email)->send(new MarketingEmail($campanha->modelo));
                    $enviados++;
                } catch (\Exception $e) {
                    $erros[] = "Erro ao enviar para {$email}: " . $e->getMessage();
                    Log::error("Erro ao enviar e-mail da campanha {$campanha->id} para {$email}: " . $e->getMessage());
                }
            }

            // Atualizar estatísticas da campanha
            $campanha->update([
                'emails_enviados' => $enviados,
                'total_destinatarios' => count($destinatarios)
            ]);

            return response()->json([
                'success' => true,
                'message' => "Campanha enviada com sucesso! {$enviados} e-mails enviados.",
                'enviados' => $enviados,
                'erros' => $erros
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar campanha: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recipients based on campaign type and segmentation
     */
    private function getDestinatarios(Campanha $campanha): array
    {
        $query = null;
        
        if ($campanha->tipo === 'lead') {
            $query = Lead::whereNotNull('email');
        } elseif ($campanha->tipo === 'licenciado') {
            $query = Licenciado::whereNotNull('email');
        } else {
            // Campanha geral - incluir leads e licenciados
            $leads = Lead::whereNotNull('email')->pluck('email')->toArray();
            $licenciados = Licenciado::whereNotNull('email')->pluck('email')->toArray();
            return array_unique(array_merge($leads, $licenciados));
        }

        // Aplicar segmentação se existir
        if ($campanha->segmentacao) {
            foreach ($campanha->segmentacao as $segmento) {
                switch ($segmento) {
                    case 'novos':
                        $query->where('created_at', '>=', now()->subDays(30));
                        break;
                    case 'ativos':
                        $query->where('status', 'ativo');
                        break;
                    case 'inativos':
                        $query->where('status', 'inativo');
                        break;
                }
            }
        }

        return $query->pluck('email')->toArray();
    }

    /**
     * Test email sending functionality
     */
    public function testarEmail(Request $request): JsonResponse
    {
        $request->validate([
            'email_teste' => 'required|email',
            'modelo_teste' => 'required|exists:email_modelos,id'
        ]);

        try {
            $modelo = EmailModelo::findOrFail($request->modelo_teste);
            $emailDestino = $request->email_teste;

            // Enviar e-mail de teste
            Mail::to($emailDestino)->send(new MarketingEmail($modelo));

            Log::info("E-mail de teste enviado com sucesso para: {$emailDestino}");

            return response()->json([
                'success' => true,
                'message' => "E-mail de teste enviado com sucesso para {$emailDestino}! Verifique sua caixa de entrada.",
                'destinatario' => $emailDestino,
                'modelo' => $modelo->nome
            ]);

        } catch (\Exception $e) {
            Log::error("Erro ao enviar e-mail de teste: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar e-mail de teste: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send marketing email (método original mantido para compatibilidade)
     */
    public function enviarEmail(Request $request): JsonResponse
    {
        $request->validate([
            'modelo_id' => 'required|exists:email_modelos,id',
            'tipo_destinatario' => 'required|in:lead,licenciado,especifico',
            'destinatarios' => 'required_if:tipo_destinatario,especifico|array',
            'destinatarios.*' => 'email'
        ]);

        try {
            $modelo = EmailModelo::findOrFail($request->modelo_id);
            $enviados = 0;
            $erros = [];

            if ($request->tipo_destinatario === 'lead') {
                $destinatarios = Lead::whereNotNull('email')->pluck('email')->toArray();
            } elseif ($request->tipo_destinatario === 'licenciado') {
                $destinatarios = Licenciado::whereNotNull('email')->pluck('email')->toArray();
            } else {
                $destinatarios = $request->destinatarios;
            }

            foreach ($destinatarios as $email) {
                try {
                    Mail::to($email)->send(new MarketingEmail($modelo));
                    $enviados++;
                } catch (\Exception $e) {
                    $erros[] = "Erro ao enviar para {$email}: " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'message' => "E-mails enviados com sucesso! {$enviados} enviados.",
                'enviados' => $enviados,
                'erros' => $erros
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar e-mails: ' . $e->getMessage()
            ], 500);
        }
    }
}




