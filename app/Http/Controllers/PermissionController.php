<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Verificar se o usuário tem permissão
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())) {
            abort(403, 'Acesso negado. Apenas administradores podem gerenciar permissões.');
        }

        // Buscar todas as permissões agrupadas por módulo
        $permissions = Permission::active()
            ->orderBy('module')
            ->orderBy('action')
            ->get()
            ->groupBy('module');

        // Buscar todos os roles ativos
        $roles = Role::active()->orderBy('level')->get();

        // Ícones dos módulos
        $moduleIcons = $this->getModuleIcons();

        return view('dashboard.permissions.index', compact('permissions', 'roles', 'moduleIcons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verificar se o usuário tem permissão
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())) {
            abort(403, 'Acesso negado.');
        }

        $modules = $this->getAvailableModules();
        $actions = $this->getAvailableActions();

        return view('dashboard.permissions.create', compact('modules', 'actions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar se o usuário tem permissão
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())) {
            abort(403, 'Acesso negado.');
        }

        $request->validate([
            'name' => 'required|string|unique:permissions,name|max:255',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'module' => 'required|string|max:100',
            'action' => 'required|string|max:50',
            'is_active' => 'boolean'
        ]);

        try {
            $permission = Permission::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'module' => $request->module,
                'action' => $request->action,
                'is_active' => $request->boolean('is_active', true)
            ]);

            Log::info('Nova permissão criada', [
                'permission_id' => $permission->id,
                'name' => $permission->name,
                'created_by' => auth()->user()->id
            ]);

            return redirect()->route('permissions.index')
                ->with('success', 'Permissão criada com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao criar permissão: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erro ao criar permissão: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        // Verificar se o usuário tem permissão
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())) {
            abort(403, 'Acesso negado.');
        }

        $roles = $permission->roles()->with('users')->get();

        return view('dashboard.permissions.show', compact('permission', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        // Verificar se o usuário tem permissão
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())) {
            abort(403, 'Acesso negado.');
        }

        $modules = $this->getAvailableModules();
        $actions = $this->getAvailableActions();

        return view('dashboard.permissions.edit', compact('permission', 'modules', 'actions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        // Verificar se o usuário tem permissão
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())) {
            abort(403, 'Acesso negado.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'module' => 'required|string|max:100',
            'action' => 'required|string|max:50',
            'is_active' => 'boolean'
        ]);

        try {
            $permission->update([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'module' => $request->module,
                'action' => $request->action,
                'is_active' => $request->boolean('is_active', true)
            ]);

            Log::info('Permissão atualizada', [
                'permission_id' => $permission->id,
                'name' => $permission->name,
                'updated_by' => auth()->user()->id
            ]);

            return redirect()->route('permissions.index')
                ->with('success', 'Permissão atualizada com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar permissão: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erro ao atualizar permissão: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        // Verificar se o usuário tem permissão
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())) {
            abort(403, 'Acesso negado.');
        }

        try {
            // Verificar se a permissão está sendo usada
            if ($permission->roles()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Não é possível excluir esta permissão pois ela está sendo usada por um ou mais perfis.');
            }

            $permissionName = $permission->name;
            $permission->delete();

            Log::info('Permissão excluída', [
                'permission_name' => $permissionName,
                'deleted_by' => auth()->user()->id
            ]);

            return redirect()->route('permissions.index')
                ->with('success', 'Permissão excluída com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao excluir permissão: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erro ao excluir permissão: ' . $e->getMessage());
        }
    }

    /**
     * Página para gerenciar permissões de um role específico
     */
    public function manageRolePermissions(Role $role)
    {
        // Verificar se o usuário tem permissão
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())) {
            abort(403, 'Acesso negado.');
        }

        // Buscar todas as permissões agrupadas por módulo
        $permissions = Permission::active()
            ->orderBy('module')
            ->orderBy('action')
            ->get()
            ->groupBy('module');

        // Buscar permissões já atribuídas ao role
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        // Ícones dos módulos
        $moduleIcons = $this->getModuleIcons();

        return view('dashboard.permissions.manage-role', compact('role', 'permissions', 'rolePermissions', 'moduleIcons'));
    }

    /**
     * Atualizar permissões de um role específico
     */
    public function updateRolePermissions(Request $request, Role $role)
    {
        // Verificar se o usuário tem permissão
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())) {
            abort(403, 'Acesso negado.');
        }

        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        try {
            DB::beginTransaction();

            // Sincronizar permissões (remove as antigas e adiciona as novas)
            $role->permissions()->sync($request->permissions ?? []);

            DB::commit();

            Log::info('Permissões do role atualizadas', [
                'role_id' => $role->id,
                'role_name' => $role->name,
                'permissions_count' => count($request->permissions ?? []),
                'updated_by' => auth()->user()->id
            ]);

            return redirect()->route('permissions.manage-role', $role)
                ->with('success', 'Permissões do perfil atualizadas com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar permissões do role: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erro ao atualizar permissões: ' . $e->getMessage());
        }
    }

    /**
     * Obter módulos disponíveis no sistema
     */
    private function getAvailableModules(): array
    {
        return [
            'dashboard' => 'Dashboard',
            'licenciados' => 'Licenciados',
            'contratos' => 'Contratos',
            'leads' => 'Leads',
            'estabelecimentos' => 'Estabelecimentos',
            'operacoes' => 'Operações',
            'planos' => 'Planos',
            'adquirentes' => 'Adquirentes',
            'agenda' => 'Agenda',
            'marketing' => 'Marketing',
            'usuarios' => 'Usuários',
            'relatorios' => 'Relatórios',
            'configuracoes' => 'Configurações'
        ];
    }

    /**
     * Obter ações disponíveis no sistema
     */
    private function getAvailableActions(): array
    {
        return [
            'view' => 'Visualizar',
            'create' => 'Criar',
            'update' => 'Editar',
            'delete' => 'Excluir',
            'manage' => 'Gerenciar',
            'approve' => 'Aprovar',
            'export' => 'Exportar',
            'import' => 'Importar'
        ];
    }

    /**
     * Obter ícones dos módulos
     */
    private function getModuleIcons(): array
    {
        return [
            'dashboard' => 'tachometer-alt',
            'licenciados' => 'id-card',
            'contratos' => 'file-contract',
            'leads' => 'user-plus',
            'estabelecimentos' => 'store',
            'operacoes' => 'cogs',
            'planos' => 'chart-line',
            'adquirentes' => 'building',
            'agenda' => 'calendar-alt',
            'marketing' => 'bullhorn',
            'usuarios' => 'users',
            'permissoes' => 'key',
            'relatorios' => 'chart-bar',
            'configuracoes' => 'cog'
        ];
    }
}