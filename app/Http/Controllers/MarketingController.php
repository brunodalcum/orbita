<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Licenciado;
use App\Models\EmailModelo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\MarketingEmail;

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
        
        return view('dashboard.marketing.index', compact('totalLeads', 'totalLicenciados', 'totalModelos'));
    }

    /**
     * Display campaigns page
     */
    public function campanhas()
    {
        return view('dashboard.marketing.campanhas');
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
     * Send marketing email
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




