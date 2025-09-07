<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectByRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Se não estiver autenticado, continuar
        if (!$user) {
            return $next($request);
        }

        // Se não tiver role, continuar
        if (!$user->role) {
            return $next($request);
        }

        // Verificar se está tentando acessar o dashboard geral
        if ($request->routeIs('dashboard')) {
            // Redirecionar licenciados para seu dashboard específico
            if ($user->role->name === 'licenciado') {
                return redirect()->route('licenciado.dashboard');
            }
        }

        // Verificar se licenciado está tentando acessar áreas administrativas
        if ($user->role->name === 'licenciado') {
            $adminRoutes = [
                'dashboard.licenciados',
                'dashboard.users',
                'dashboard.operacoes',
                'dashboard.planos',
                'dashboard.adquirentes',
                'dashboard.marketing',
                'dashboard.configuracoes'
            ];

            foreach ($adminRoutes as $route) {
                if ($request->routeIs($route)) {
                    return redirect()->route('licenciado.dashboard')
                        ->with('error', 'Acesso negado. Área restrita para administradores.');
                }
            }
        }

        return $next($request);
    }
}
