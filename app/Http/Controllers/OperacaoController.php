<?php

namespace App\Http\Controllers;

use App\Models\Operacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OperacaoController extends Controller
{
    public function index()
    {
        $operacoes = Operacao::orderBy('nome')->get();
        return view('dashboard.operacoes', compact('operacoes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'adquirente' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . implode(', ', $validator->errors()->all())
            ], 422);
        }

        try {
            $operacao = Operacao::create([
                'nome' => $request->nome,
                'adquirente' => $request->adquirente,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Operação cadastrada com sucesso!',
                'operacao' => $operacao
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cadastrar operação: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'adquirente' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . implode(', ', $validator->errors()->all())
            ], 422);
        }

        try {
            $operacao = Operacao::findOrFail($id);
            $operacao->update([
                'nome' => $request->nome,
                'adquirente' => $request->adquirente,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Operação atualizada com sucesso!',
                'operacao' => $operacao
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar operação: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $operacao = Operacao::findOrFail($id);
            $operacao->delete();

            return response()->json([
                'success' => true,
                'message' => 'Operação excluída com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir operação: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getOperacoes()
    {
        $operacoes = Operacao::orderBy('nome')->get();
        return response()->json($operacoes);
    }
}
