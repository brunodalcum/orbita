<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GoogleStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Verificar status da conexão Google de forma simples
     */
    public function status()
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Status Google OK',
                'authenticated' => Auth::check(),
                'user_id' => Auth::id(),
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'basic_test' => 'working'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile()),
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 500);
        }
    }

    /**
     * Teste simples sem dependências complexas
     */
    public function simpleTest()
    {
        try {
            // Teste básico de configuração
            $config = [
                'client_id' => config('google.client_id'),
                'client_secret' => config('google.client_secret'),
                'redirect_uri' => config('google.redirect_uri'),
                'calendar_id' => config('google.calendar.primary_calendar_id'),
                'timezone' => config('google.calendar.timezone'),
            ];

            $configured = !empty($config['client_id']) && !empty($config['client_secret']);

            return response()->json([
                'success' => true,
                'message' => $configured ? 'Configurações Google encontradas' : 'Configurações Google não encontradas',
                'configured' => $configured,
                'config_summary' => [
                    'client_id' => !empty($config['client_id']) ? 'SET' : 'NOT_SET',
                    'client_secret' => !empty($config['client_secret']) ? 'SET' : 'NOT_SET',
                    'redirect_uri' => $config['redirect_uri'],
                    'calendar_id' => $config['calendar_id'],
                    'timezone' => $config['timezone'],
                ],
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erro no teste simples Google: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro no teste: ' . $e->getMessage(),
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 500);
        }
    }
}
