<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class ImpersonationMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar se há impersonação ativa
        if (Session::has('impersonating')) {
            $impersonationData = Session::get('impersonating');
            
            // Verificar se a impersonação não expirou (máximo 4 horas)
            $startedAt = Carbon::parse($impersonationData['started_at']);
            $maxDuration = 4 * 60; // 4 horas em minutos
            
            if ($startedAt->diffInMinutes(Carbon::now()) > $maxDuration) {
                // Impersonação expirou, limpar sessão
                Session::forget('impersonating');
                
                return redirect()->route('hierarchy.dashboard')
                    ->with('warning', 'Sua sessão de impersonação expirou por segurança.');
            }
            
            // Compartilhar dados da impersonação com todas as views
            View::share('impersonationData', $impersonationData);
            View::share('isImpersonating', true);
        } else {
            View::share('impersonationData', null);
            View::share('isImpersonating', false);
        }

        return $next($request);
    }
}