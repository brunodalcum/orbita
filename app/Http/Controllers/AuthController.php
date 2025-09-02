<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Logout do usuário e redirecionamento para login
     */
    public function logout(Request $request)
    {
        // Fazer logout do usuário
        Auth::logout();
        
        // Invalidar a sessão
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Redirecionar para a página de login
        return redirect()->route('login');
    }
}
