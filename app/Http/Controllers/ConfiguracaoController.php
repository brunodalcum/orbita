<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ConfiguracaoController extends Controller
{
    public function index()
    {
        // Carregar configurações atuais (você pode criar uma tabela de configurações no futuro)
        $configuracoes = [
            'nome_sistema' => config('app.name', 'dspay'),
            'email_sistema' => config('mail.from.address', 'contato@dspay.com'),
            'telefone_sistema' => config('app.telefone', '(11) 99999-9999'),
            'cnpj_sistema' => config('app.cnpj', '00.000.000/0000-00'),
            'logomarca_sistema' => config('app.logomarca', 'images/dspay-logo.png'),
        ];

        return view('dashboard.configuracoes', compact('configuracoes'));
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'nome_sistema' => 'required|string|max:255',
                'email_sistema' => 'required|email|max:255',
                'telefone_sistema' => 'required|string|max:20',
                'cnpj_sistema' => 'required|string|max:20',
                'logomarca_sistema' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'nome_sistema.required' => 'O nome do sistema é obrigatório.',
                'email_sistema.required' => 'O e-mail do sistema é obrigatório.',
                'email_sistema.email' => 'Digite um e-mail válido.',
                'telefone_sistema.required' => 'O telefone do sistema é obrigatório.',
                'cnpj_sistema.required' => 'O CNPJ do sistema é obrigatório.',
                'logomarca_sistema.image' => 'O arquivo deve ser uma imagem.',
                'logomarca_sistema.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg, gif.',
                'logomarca_sistema.max' => 'A imagem não pode ter mais que 2MB.',
            ]);

            $dados = $request->only([
                'nome_sistema',
                'email_sistema', 
                'telefone_sistema',
                'cnpj_sistema'
            ]);

            // Processar upload da logomarca se fornecida
            if ($request->hasFile('logomarca_sistema')) {
                $arquivo = $request->file('logomarca_sistema');
                $nomeArquivo = 'logomarca_' . time() . '.' . $arquivo->getClientOriginalExtension();
                
                // Salvar no diretório public/images
                $caminho = $arquivo->storeAs('images', $nomeArquivo, 'public');
                $dados['logomarca_sistema'] = 'storage/' . $caminho;
            }

            // Aqui você pode salvar as configurações em um arquivo .env ou em uma tabela
            // Por enquanto, vamos apenas retornar sucesso
            Log::info('Configurações atualizadas', $dados);

            return response()->json([
                'success' => true,
                'message' => 'Configurações atualizadas com sucesso!',
                'data' => $dados
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar configurações: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor. Tente novamente.'
            ], 500);
        }
    }
}
