<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuditController extends Controller
{
    /**
     * Interface principal de auditoria
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$this->canViewAudit($user)) {
            abort(403, 'Você não tem permissão para visualizar logs de auditoria');
        }
        
        $action = $request->get('action', 'all');
        $severity = $request->get('severity', 'all');
        $dateRange = $request->get('date_range', '7days');
        $search = $request->get('search', '');
        
        $logs = $this->getViewableLogs($user, $action, $severity, $dateRange, $search);
        $stats = $this->getAuditStatistics($user, $dateRange);
        
        return view('hierarchy.audit.index', compact(
            'user', 'logs', 'stats', 'action', 'severity', 'dateRange', 'search'
        ));
    }

    /**
     * Visualizar detalhes de um log específico
     */
    public function show($id)
    {
        $user = Auth::user();
        
        if (!$this->canViewAudit($user)) {
            abort(403, 'Você não tem permissão para visualizar logs de auditoria');
        }
        
        $log = AuditLog::with(['user', 'impersonatedBy'])->find($id);
        
        if (!$log || !$this->canViewLog($user, $log)) {
            abort(404, 'Log não encontrado ou sem permissão');
        }
        
        return view('hierarchy.audit.show', compact('user', 'log'));
    }

    /**
     * Exportar logs de auditoria
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        
        if (!$this->canViewAudit($user)) {
            return response()->json(['success' => false, 'message' => 'Sem permissão'], 403);
        }
        
        $format = $request->get('format', 'csv');
        $dateRange = $request->get('date_range', '30days');
        
        try {
            $logs = $this->getExportLogs($user, $dateRange);
            $filename = "audit_logs_" . date('Y-m-d_H-i-s') . ".{$format}";
            
            return response()->json([
                'success' => true,
                'message' => "Exportação {$format} será implementada em breve",
                'filename' => $filename,
                'count' => $logs->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao exportar logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar se o usuário pode visualizar auditoria
     */
    private function canViewAudit(User $user): bool
    {
        return $user->isSuperAdminNode() || $user->isOperacaoNode() || $user->isWhiteLabelNode();
    }

    /**
     * Verificar se pode visualizar um log específico
     */
    private function canViewLog(User $user, AuditLog $log): bool
    {
        if ($user->isSuperAdminNode()) {
            return true;
        }
        
        if ($log->user_id && $user->getAllDescendants()->contains('id', $log->user_id)) {
            return true;
        }
        
        if ($log->user_id === $user->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Obter logs que o usuário pode visualizar
     */
    private function getViewableLogs(User $user, string $action, string $severity, string $dateRange, string $search)
    {
        $query = AuditLog::with(['user', 'impersonatedBy']);
        
        if (!$user->isSuperAdminNode()) {
            $descendants = $user->getAllDescendants();
            $descendantIds = $descendants->pluck('id')->push($user->id);
            $query->whereIn('user_id', $descendantIds);
        }
        
        if ($action !== 'all') {
            $query->where('action', $action);
        }
        
        if ($severity !== 'all') {
            $query->where('severity', $severity);
        }
        
        $dates = $this->getDateRange($dateRange);
        $query->whereBetween('occurred_at', [$dates['start'], $dates['end']]);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('resource_name', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%");
            });
        }
        
        return $query->orderBy('occurred_at', 'desc')->paginate(50);
    }

    /**
     * Obter estatísticas de auditoria
     */
    private function getAuditStatistics(User $user, string $dateRange): array
    {
        $dates = $this->getDateRange($dateRange);
        $query = AuditLog::whereBetween('occurred_at', [$dates['start'], $dates['end']]);
        
        if (!$user->isSuperAdminNode()) {
            $descendants = $user->getAllDescendants();
            $descendantIds = $descendants->pluck('id')->push($user->id);
            $query->whereIn('user_id', $descendantIds);
        }
        
        return [
            'total_logs' => $query->count(),
            'unique_users' => $query->distinct('user_id')->count('user_id'),
            'by_severity' => [
                'info' => $query->clone()->where('severity', 'info')->count(),
                'warning' => $query->clone()->where('severity', 'warning')->count(),
                'error' => $query->clone()->where('severity', 'error')->count()
            ],
            'sensitive_logs' => $query->clone()->where('sensitive', true)->count()
        ];
    }

    /**
     * Obter range de datas
     */
    private function getDateRange(string $period): array
    {
        switch ($period) {
            case '1day':
                return ['start' => Carbon::now()->subDay(), 'end' => Carbon::now()];
            case '7days':
                return ['start' => Carbon::now()->subDays(7), 'end' => Carbon::now()];
            case '30days':
                return ['start' => Carbon::now()->subDays(30), 'end' => Carbon::now()];
            default:
                return ['start' => Carbon::now()->subDays(7), 'end' => Carbon::now()];
        }
    }

    /**
     * Obter logs para exportação
     */
    private function getExportLogs(User $user, string $dateRange): \Illuminate\Database\Eloquent\Collection
    {
        $dates = $this->getDateRange($dateRange);
        $query = AuditLog::with(['user', 'impersonatedBy'])
                         ->whereBetween('occurred_at', [$dates['start'], $dates['end']]);
        
        if (!$user->isSuperAdminNode()) {
            $descendants = $user->getAllDescendants();
            $descendantIds = $descendants->pluck('id')->push($user->id);
            $query->whereIn('user_id', $descendantIds);
        }
        
        return $query->orderBy('occurred_at', 'desc')->get();
    }
}