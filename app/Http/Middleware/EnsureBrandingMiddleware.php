<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class EnsureBrandingMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Compartilhar variáveis de branding com todas as views
        View::share("globalBrandingCSS", asset("css/global-branding.css"));
        
        return $next($request);
    }
}
