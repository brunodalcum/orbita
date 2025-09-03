<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Log;

class GoogleAuthController extends Controller
{
    protected $googleService;

    public function __construct(GoogleCalendarService $googleService)
    {
        $this->googleService = $googleService;
    }

    /**
     * Processar callback OAuth2 do Google Calendar
     */
    public function callback(Request $request)
    {
        try {
            $code = $request->get('code');
            
            if (!$code) {
                Log::error('Código de autorização não fornecido no callback OAuth2');
                return $this->showError('Código de autorização não fornecido');
            }

            Log::info('Processando callback OAuth2 com código: ' . substr($code, 0, 20) . '...');

            // Processar o código de autorização
            $result = $this->googleService->handleAuthCallback($code);

            if ($result['success']) {
                Log::info('Autorização OAuth2 realizada com sucesso');
                return $this->showSuccess('Autorização realizada com sucesso! Agora você pode agendar reuniões que serão salvas no Google Calendar.');
            } else {
                Log::error('Falha na autorização OAuth2: ' . $result['error']);
                return $this->showError('Falha na autorização: ' . $result['error']);
            }

        } catch (\Exception $e) {
            Log::error('Erro durante callback OAuth2: ' . $e->getMessage());
            return $this->showError('Erro durante a autorização: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar página de sucesso
     */
    private function showSuccess($message)
    {
        return view('google-auth.success', compact('message'));
    }

    /**
     * Mostrar página de erro
     */
    private function showError($error)
    {
        return view('google-auth.error', compact('error'));
    }
}
