<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->paginate(15);
        $roles = Role::active()->get();
        
        return view('dashboard.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verificar se o usuário atual é admin (opcional para teste)
        $currentUser = auth()->user();
        if ($currentUser && (!$currentUser->isAdmin() && !$currentUser->isSuperAdmin())) {
            return redirect()->route('dashboard')
                ->with('error', 'Apenas administradores podem acessar esta página!');
        }

        $roles = Role::active()->get();
        return view('dashboard.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'boolean',
            'admin_email' => 'required|email',
            'admin_password' => 'required|string'
        ]);

        // Verificar se o usuário atual é admin
        $currentUser = auth()->user();
        if (!$currentUser || (!$currentUser->isAdmin() && !$currentUser->isSuperAdmin())) {
            return redirect()->route('users.create')
                ->with('error', 'Apenas administradores podem cadastrar usuários!');
        }

        // Validar credenciais do admin
        $adminEmail = $request->admin_email;
        $adminPassword = $request->admin_password;

        // Buscar o admin pelo email
        $admin = User::where('email', $adminEmail)
            ->where('is_active', true)
            ->first();

        if (!$admin) {
            return redirect()->route('users.create')
                ->with('error', 'Email de administrador não encontrado ou usuário inativo!');
        }

        // Verificar se o admin tem permissão para cadastrar usuários
        if (!$admin->isAdmin() && !$admin->isSuperAdmin()) {
            return redirect()->route('users.create')
                ->with('error', 'O usuário informado não possui permissões de administrador!');
        }

        // Verificar a senha do admin
        if (!Hash::check($adminPassword, $admin->password)) {
            return redirect()->route('users.create')
                ->with('error', 'Senha do administrador incorreta!');
        }

        // Verificar se o admin pode cadastrar o tipo de usuário solicitado
        $role = Role::find($request->role_id);
        if (!$role) {
            return redirect()->route('users.create')
                ->with('error', 'Role selecionado não encontrado!');
        }

        // Apenas Super Admin pode cadastrar outros Super Admins
        if ($role->isSuperAdmin() && !$admin->isSuperAdmin()) {
            return redirect()->route('users.create')
                ->with('error', 'Apenas Super Administradores podem cadastrar outros Super Administradores!');
        }

        // Criar o usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('dashboard.users')
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('role');
        return view('dashboard.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::active()->get();
        return view('dashboard.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'boolean'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('dashboard.users')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Não permitir deletar o próprio usuário
        if ($user->id === auth()->id()) {
            return redirect()->route('dashboard.users')
                ->with('error', 'Você não pode deletar seu próprio usuário!');
        }

        $user->delete();

        return redirect()->route('dashboard.users')
            ->with('success', 'Usuário removido com sucesso!');
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus(User $user)
    {
        // Não permitir desativar o próprio usuário
        if ($user->id === auth()->id()) {
            return redirect()->route('dashboard.users')
                ->with('error', 'Você não pode desativar seu próprio usuário!');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'ativado' : 'desativado';
        
        return redirect()->route('dashboard.users')
            ->with('success', "Usuário {$status} com sucesso!");
    }
}
