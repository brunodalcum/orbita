<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LicenciadoLeadController extends Controller
{
    /**
     * Display a listing of leads atribuídos ao licenciado logado.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Buscar apenas leads atribuídos ao licenciado logado
        $leads = Lead::doLicenciado($user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        return view('licenciado.leads.index', compact('leads'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            // Verificar se o lead pertence ao licenciado logado
            $lead = Lead::doLicenciado($user->id)
                       ->with('followUps.user')
                       ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'lead' => $lead
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lead não encontrado ou não autorizado.'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:novo,contatado,qualificado,proposta,fechado,perdido',
            'observacoes' => 'nullable|string'
        ]);

        try {
            $user = Auth::user();
            
            // Verificar se o lead pertence ao licenciado logado
            $lead = Lead::doLicenciado($user->id)->findOrFail($id);
            
            $lead->update([
                'status' => $request->status,
                'observacoes' => $request->observacoes
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Lead atualizado com sucesso!',
                'lead' => $lead->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar lead: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle status ativo/inativo do lead
     */
    public function toggleStatus(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            // Verificar se o lead pertence ao licenciado logado
            $lead = Lead::doLicenciado($user->id)->findOrFail($id);
            
            $lead->update(['ativo' => !$lead->ativo]);
            
            return response()->json([
                'success' => true,
                'message' => 'Status do lead alterado com sucesso!',
                'lead' => $lead->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exibir página de extração de leads
     */
    public function extract()
    {
        $user = Auth::user();
        
        // Estatísticas dos leads do licenciado
        $totalLeads = Lead::doLicenciado($user->id)->count();
        $leadsAtivos = Lead::doLicenciado($user->id)->where('ativo', true)->count();
        $leadsPorStatus = Lead::doLicenciado($user->id)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        return view('licenciado.leads.extract', compact('totalLeads', 'leadsAtivos', 'leadsPorStatus'));
    }

    /**
     * Exportar leads baseado nos filtros
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        
        // Construir query base
        $query = Lead::doLicenciado($user->id);
        
        // Aplicar filtros
        if ($request->filled('status')) {
            $query->whereIn('status', $request->status);
        }
        
        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo === '1');
        }
        
        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }
        
        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }
        
        // Buscar leads
        $leads = $query->orderBy('created_at', 'desc')->get();
        
        // Determinar formato de exportação
        $formato = $request->get('formato', 'csv');
        
        if ($formato === 'csv') {
            return $this->exportCsv($leads);
        } elseif ($formato === 'excel') {
            return $this->exportExcel($leads);
        }
        
        return back()->with('error', 'Formato de exportação inválido.');
    }

    /**
     * Exportar leads para CSV
     */
    private function exportCsv($leads)
    {
        $filename = 'leads_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($leads) {
            $file = fopen('php://output', 'w');
            
            // Cabeçalho CSV
            fputcsv($file, [
                'ID',
                'Nome',
                'Email',
                'Telefone',
                'Empresa',
                'Status',
                'Origem',
                'Ativo',
                'Data de Cadastro',
                'Observações'
            ]);
            
            // Dados dos leads
            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->id,
                    $lead->nome,
                    $lead->email,
                    $lead->telefone,
                    $lead->empresa,
                    ucfirst($lead->status),
                    $lead->origem,
                    $lead->ativo ? 'Sim' : 'Não',
                    $lead->created_at->format('d/m/Y H:i'),
                    $lead->observacoes
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar leads para Excel (JSON para processamento no frontend)
     */
    private function exportExcel($leads)
    {
        $data = $leads->map(function($lead) {
            return [
                'ID' => $lead->id,
                'Nome' => $lead->nome,
                'Email' => $lead->email,
                'Telefone' => $lead->telefone,
                'Empresa' => $lead->empresa,
                'Status' => ucfirst($lead->status),
                'Origem' => $lead->origem,
                'Ativo' => $lead->ativo ? 'Sim' : 'Não',
                'Data de Cadastro' => $lead->created_at->format('d/m/Y H:i'),
                'Observações' => $lead->observacoes
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'filename' => 'leads_' . date('Y-m-d_H-i-s') . '.xlsx'
        ]);
    }
}