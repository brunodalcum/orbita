<?php

namespace App\Http\Controllers;

use App\Models\Licenciado;
use App\Models\Lead;
use App\Models\Operacao;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Estatísticas dos licenciados
        $stats = [
            'total' => Licenciado::count(),
            'aprovados' => Licenciado::where('status', 'aprovado')->count(),
            'em_analise' => Licenciado::where('status', 'em_analise')->count(),
            'recusados' => Licenciado::where('status', 'recusado')->count(),
        ];

        // Licenciados recentes
        $licenciadosRecentes = Licenciado::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Operações
        $operacoes = Operacao::orderBy('nome')->get();

        // Últimos leads cadastrados
        $leadsRecentes = Lead::orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('dashboard', compact('stats', 'licenciadosRecentes', 'operacoes', 'leadsRecentes'));
    }
}
