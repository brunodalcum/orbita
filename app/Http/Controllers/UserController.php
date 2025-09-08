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
        \Log::info('🔍 DEBUG UserController@store iniciado', [
            'is_ajax' => $request->ajax(),
            'accept_header' => $request->header('Accept'),
            'x_requested_with' => $request->header('X-Requested-With'),
            'content_type' => $request->header('Content-Type'),
            'user_id' => auth()->id()
        ]);
        
        try {
            // Verificar se o usuário atual é admin
            $currentUser = auth()->user();
            if (!$currentUser || (!$currentUser->isAdmin() && !$currentUser->isSuperAdmin())) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Apenas administradores podem cadastrar usuários!'
                    ], 403);
                }
                return redirect()->route('users.create')
                    ->with('error', 'Apenas administradores podem cadastrar usuários!');
            }

            $validationRules = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role_id' => 'required|exists:roles,id',
                'is_active' => 'boolean',
            ];

            $validator = \Validator::make($request->all(), $validationRules);
            
            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ], 422);
                }
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Verificar se o admin pode cadastrar o tipo de usuário solicitado
            $role = Role::find($request->role_id);
            if (!$role) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Role selecionado não encontrado!'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Role selecionado não encontrado!');
            }

            // Apenas Super Admin pode cadastrar outros Super Admins
            if ($role->isSuperAdmin() && !$currentUser->isSuperAdmin()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Apenas Super Administradores podem cadastrar outros Super Administradores!'
                    ], 403);
                }
                return redirect()->back()->with('error', 'Apenas Super Administradores podem cadastrar outros Super Administradores!');
            }

            // Criar o usuário
            \Log::info('📝 PRODUÇÃO - Iniciando criação de usuário', [
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
                'is_ajax' => $request->ajax(),
                'environment' => app()->environment(),
                'current_user_id' => $currentUser ? $currentUser->id : null,
                'current_user_role' => $currentUser && $currentUser->role ? $currentUser->role->name : null
            ]);
            
            // Verificar se a role existe
            $role = Role::find($request->role_id);
            \Log::info('📋 PRODUÇÃO - Role verificada', [
                'role_id' => $request->role_id,
                'role_found' => $role ? true : false,
                'role_name' => $role ? $role->name : null,
                'role_active' => $role ? $role->is_active : null
            ]);
            
            // Preparar dados para criação
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
            ];
            
            \Log::info('📊 PRODUÇÃO - Dados preparados', [
                'user_data_keys' => array_keys($userData),
                'password_hashed' => !empty($userData['password']),
                'email_unique_check' => User::where('email', $userData['email'])->exists() ? 'EMAIL_EXISTS' : 'EMAIL_AVAILABLE'
            ]);
            
            // Tentar criar usuário
            try {
                $user = User::create($userData);
                
                \Log::info('✅ PRODUÇÃO - Usuário criado com sucesso', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'user_role_id' => $user->role_id,
                    'user_active' => $user->is_active,
                    'created_at' => $user->created_at,
                    'is_ajax' => $request->ajax()
                ]);
                
            } catch (\Exception $createError) {
                \Log::error('❌ PRODUÇÃO - Erro na criação do usuário', [
                    'error_message' => $createError->getMessage(),
                    'error_code' => $createError->getCode(),
                    'error_file' => $createError->getFile(),
                    'error_line' => $createError->getLine(),
                    'user_data' => $userData,
                    'stack_trace' => $createError->getTraceAsString()
                ]);
                
                throw $createError; // Re-throw para ser capturado pelo catch externo
            }

            if ($request->ajax()) {
                \Log::info('📤 Retornando JSON response');
                return response()->json([
                    'success' => true,
                    'message' => 'Usuário criado com sucesso!',
                    'user' => $user->load('role')
                ]);
            }

            return redirect()->route('dashboard.users')
                ->with('success', 'Usuário criado com sucesso!');

        } catch (\Exception $e) {
            \Log::error('Erro ao criar usuário: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro interno do servidor'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Erro ao criar usuário: ' . $e->getMessage());
        }
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
