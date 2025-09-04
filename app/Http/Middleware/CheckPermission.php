<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission = null, string $module = null, string $action = null): Response
    {
        $user = $request->user();

        // Se não estiver autenticado, redirecionar para login
        if (!$user) {
            return redirect()->route('login');
        }

        // Se o usuário não estiver ativo, negar acesso
        if (!$user->isActive()) {
            abort(403, 'Usuário inativo');
        }

        // Super Admin tem acesso total
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Verificar permissão específica
        if ($permission && !$user->hasPermission($permission)) {
            abort(403, 'Acesso negado. Permissão necessária: ' . $permission);
        }

        // Verificar permissão por módulo e ação
        if ($module && !$user->hasModulePermission($module, $action)) {
            $message = $action 
                ? "Acesso negado. Permissão necessária: {$module}.{$action}"
                : "Acesso negado. Permissão necessária para o módulo: {$module}";
            abort(403, $message);
        }

        return $next($request);
    }
}
