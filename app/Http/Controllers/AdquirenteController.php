<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Adquirente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdquirenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adquirentes = Adquirente::orderBy('nome')->get();
        return view('dashboard.adquirentes', compact('adquirentes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255|unique:adquirentes,nome'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . implode(', ', $validator->errors()->all())
            ], 422);
        }

        try {
            $adquirente = Adquirente::create([
                'nome' => $request->nome,
                'status' => 'ativo'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Adquirente criado com sucesso!',
                'adquirente' => $adquirente
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar adquirente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $adquirente = Adquirente::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'adquirente' => $adquirente
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar adquirente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $adquirente = Adquirente::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'adquirente' => $adquirente
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar dados do adquirente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255|unique:adquirentes,nome,' . $id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . implode(', ', $validator->errors()->all())
            ], 422);
        }

        try {
            $adquirente = Adquirente::findOrFail($id);
            $adquirente->update([
                'nome' => $request->nome
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Adquirente atualizado com sucesso!',
                'adquirente' => $adquirente
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar adquirente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $adquirente = Adquirente::findOrFail($id);
            $adquirente->delete();

            return response()->json([
                'success' => true,
                'message' => 'Adquirente excluído com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir adquirente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(string $id)
    {
        try {
            $adquirente = Adquirente::findOrFail($id);
            $newStatus = $adquirente->status === 'ativo' ? 'inativo' : 'ativo';
            $adquirente->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'Status alterado com sucesso!',
                'newStatus' => $newStatus
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status: ' . $e->getMessage()
            ], 500);
        }
    }
}
