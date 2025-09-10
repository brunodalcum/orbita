<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleCalendarIntegrationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GoogleAuthController extends Controller
{
    private $googleService;

    public function __construct()
    {
        $this->middleware('auth');
        // Inicializar o serviço no método para capturar erros
    }

    /**
     * Redirecionar para autenticação do Google
     */
    public function redirectToGoogle()
    {
        try {
            // Inicializar serviço com tratamento de erro
            $this->googleService = $this->initializeGoogleService();
            if (!$this->googleService) {
                return redirect()->back()->with('error', 'Google Calendar não está configurado. Verifique as credenciais no arquivo .env');
            }

            if (!$this->googleService->isConfigured()) {
                return redirect()->back()->with('error', 'Google Calendar não está configurado. Verifique as credenciais.');
            }

            $authUrl = $this->googleService->getAuthUrl();
            
            Log::info('🔐 Redirecionando usuário para autenticação Google', [
                'user_id' => Auth::id(),
                'auth_url' => $authUrl
            ]);

            return redirect($authUrl);
        } catch (\Exception $e) {
            Log::error('❌ Erro ao redirecionar para Google Auth: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao conectar com Google: ' . $e->getMessage());
        }
    }

    /**
     * Processar callback do Google
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $code = $request->get('code');
            
            if (!$code) {
                throw new \Exception('Código de autorização não recebido');
            }

            // Inicializar serviço
            $this->googleService = $this->initializeGoogleService();
            if (!$this->googleService) {
                return redirect()->route('dashboard.agenda')->with('error', 'Google Calendar não configurado');
            }

            // Obter token de acesso
            $token = $this->googleService->handleAuthCallback($code);
            
            // Salvar token na sessão/cache do usuário
            $this->storeUserToken($token);
            
            Log::info('✅ Autenticação Google concluída com sucesso', [
                'user_id' => Auth::id()
            ]);

            return redirect()->route('dashboard.agenda')->with('success', 'Google Calendar conectado com sucesso! 🎉');
            
        } catch (\Exception $e) {
            Log::error('❌ Erro no callback Google: ' . $e->getMessage());
            return redirect()->route('dashboard.agenda')->with('error', 'Erro ao conectar com Google: ' . $e->getMessage());
        }
    }

    /**
     * Desconectar Google Calendar
     */
    public function disconnect()
    {
        try {
            $this->clearUserToken();
            
            Log::info('🔌 Google Calendar desconectado', [
                'user_id' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'Google Calendar desconectado com sucesso.');
            
        } catch (\Exception $e) {
            Log::error('❌ Erro ao desconectar Google Calendar: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao desconectar: ' . $e->getMessage());
        }
    }

    /**
     * Verificar status da conexão
     */
    public function status()
    {
        try {
            // Inicializar serviço
            $this->googleService = $this->initializeGoogleService();
            if (!$this->googleService) {
                return response()->json([
                    'connected' => false,
                    'configured' => false,
                    'error' => 'Google Calendar não configurado no .env',
                    'user_id' => Auth::id()
                ]);
            }

            $token = $this->getUserToken();
            $isConnected = false;
            $tokenExpired = true;

            if ($token) {
                $this->googleService->setAccessToken($token);
                $isConnected = true;
                $tokenExpired = $this->googleService->isAuthenticated() === false;
            }

            return response()->json([
                'connected' => $isConnected,
                'token_expired' => $tokenExpired,
                'configured' => $this->googleService->isConfigured(),
                'user_id' => Auth::id()
            ]);
            
        } catch (\Exception $e) {
            Log::error('❌ Erro ao verificar status Google: ' . $e->getMessage());
            return response()->json([
                'connected' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Testar conexão com Google Calendar
     */
    public function test()
    {
        try {
            // Inicializar serviço
            $this->googleService = $this->initializeGoogleService();
            if (!$this->googleService) {
                return response()->json([
                    'success' => false,
                    'message' => 'Google Calendar não configurado no .env'
                ]);
            }

            $token = $this->getUserToken();
            
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token não encontrado. Faça a autenticação primeiro.'
                ]);
            }

            $this->googleService->setAccessToken($token);
            
            // Tentar listar eventos para testar a conexão
            $events = $this->googleService->listEvents(now(), now()->addDays(7), 5);
            
            Log::info('🧪 Teste de conexão Google Calendar realizado', [
                'user_id' => Auth::id(),
                'events_found' => count($events)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Conexão com Google Calendar funcionando!',
                'events_count' => count($events),
                'test_date' => now()->format('d/m/Y H:i:s')
            ]);
            
        } catch (\Exception $e) {
            Log::error('❌ Erro no teste Google Calendar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro na conexão: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Salvar token do usuário
     */
    private function storeUserToken($token)
    {
        $userId = Auth::id();
        
        // Salvar na sessão
        session(['google_token' => $token]);
        
        // Salvar no cache com TTL
        Cache::put("google_token_user_{$userId}", $token, now()->addDays(30));
        
        Log::info('💾 Token Google salvo para usuário', ['user_id' => $userId]);
    }

    /**
     * Obter token do usuário
     */
    private function getUserToken()
    {
        $userId = Auth::id();
        
        // Tentar da sessão primeiro
        $token = session('google_token');
        
        // Se não tiver na sessão, tentar do cache
        if (!$token) {
            $token = Cache::get("google_token_user_{$userId}");
        }
        
        return $token;
    }

    /**
     * Limpar token do usuário
     */
    private function clearUserToken()
    {
        $userId = Auth::id();
        
        // Limpar da sessão
        session()->forget('google_token');
        
        // Limpar do cache
        Cache::forget("google_token_user_{$userId}");
        
        Log::info('🗑️ Token Google removido para usuário', ['user_id' => $userId]);
    }

    /**
     * Inicializar Google Service com tratamento de erro
     */
    private function initializeGoogleService()
    {
        try {
            // Verificar se as credenciais estão configuradas
            if (empty(config('google.client_id')) || empty(config('google.client_secret'))) {
                Log::error('❌ Credenciais Google não configuradas no .env');
                return null;
            }

            $service = new GoogleCalendarIntegrationService();
            return $service;
        } catch (\Exception $e) {
            Log::error('❌ Erro ao inicializar Google Service: ' . $e->getMessage());
            return null;
        }
    }
}