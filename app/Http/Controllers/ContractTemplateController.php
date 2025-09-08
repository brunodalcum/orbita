<?php

namespace App\Http\Controllers;

use App\Models\ContractTemplate;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContractTemplateController extends Controller
{
    // Middleware aplicado nas rotas em routes/web.php

    /**
     * Lista todos os templates
     */
    public function index()
    {
        $templates = ContractTemplate::orderBy('is_active', 'desc')
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(10);

        return view('dashboard.contract-templates.index', compact('templates'));
    }

    /**
     * Mostra formulário para criar novo template
     */
    public function create()
    {
        $availableVariables = $this->getAvailableVariables();
        
        return view('dashboard.contract-templates.create', compact('availableVariables'));
    }

    /**
     * Salva novo template
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'content' => 'required|string|min:50',
            'is_active' => 'boolean'
        ]);

        try {
            // Processar conteúdo e extrair variáveis
            $content = $request->input('content');
            $variables = $this->extractVariables($content);
            
            $template = ContractTemplate::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'content' => $content,
                'variables' => json_encode($variables),
                'is_active' => $request->boolean('is_active', true),
                'created_by' => auth()->id(),
                'version' => 1
            ]);

            // Salvar arquivo do template
            $this->saveTemplateFile($template);

            return redirect()
                ->route('contract-templates.show', $template)
                ->with('success', 'Template de contrato criado com sucesso!');

        } catch (\Exception $e) {
            \Log::error('Erro ao criar template de contrato: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request_data' => $request->except(['content'])
            ]);

            return back()
                ->withInput()
                ->with('error', 'Erro ao criar template: ' . $e->getMessage());
        }
    }

    /**
     * Exibe template específico
     */
    public function show(ContractTemplate $contractTemplate)
    {
        $template = $contractTemplate->load('createdBy');
        $variables = json_decode($template->variables ?? '[]', true);
        $usageCount = Contract::where('template_id', $template->id)->count();
        
        return view('dashboard.contract-templates.show', compact('template', 'variables', 'usageCount'));
    }

    /**
     * Mostra formulário para editar template
     */
    public function edit(ContractTemplate $contractTemplate)
    {
        $template = $contractTemplate;
        $availableVariables = $this->getAvailableVariables();
        
        return view('dashboard.contract-templates.edit', compact('template', 'availableVariables'));
    }

    /**
     * Atualiza template
     */
    public function update(Request $request, ContractTemplate $contractTemplate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'content' => 'required|string|min:50',
            'is_active' => 'boolean'
        ]);

        try {
            $template = $contractTemplate;
            $oldContent = $template->content;
            
            // Processar conteúdo e extrair variáveis
            $content = $request->input('content');
            $variables = $this->extractVariables($content);
            
            // Se o conteúdo mudou, incrementar versão
            $version = $template->version;
            if ($oldContent !== $content) {
                $version++;
            }

            $template->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'content' => $content,
                'variables' => json_encode($variables),
                'is_active' => $request->boolean('is_active'),
                'version' => $version,
                'updated_by' => auth()->id()
            ]);

            // Atualizar arquivo do template
            $this->saveTemplateFile($template);

            return redirect()
                ->route('contract-templates.show', $template)
                ->with('success', 'Template atualizado com sucesso!');

        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar template: ' . $e->getMessage(), [
                'template_id' => $contractTemplate->id,
                'user_id' => auth()->id()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar template: ' . $e->getMessage());
        }
    }

    /**
     * Remove template
     */
    public function destroy(ContractTemplate $contractTemplate)
    {
        try {
            $template = $contractTemplate;
            
            // Verificar se há contratos usando este template
            $contractsCount = Contract::where('template_id', $template->id)->count();
            
            if ($contractsCount > 0) {
                return back()->with('error', "Não é possível excluir este template pois há {$contractsCount} contrato(s) utilizando-o.");
            }

            // Remover arquivo do template
            if ($template->file_path && Storage::exists($template->file_path)) {
                Storage::delete($template->file_path);
            }

            $template->delete();

            return redirect()
                ->route('contract-templates.index')
                ->with('success', 'Template excluído com sucesso!');

        } catch (\Exception $e) {
            \Log::error('Erro ao excluir template: ' . $e->getMessage(), [
                'template_id' => $contractTemplate->id,
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'Erro ao excluir template: ' . $e->getMessage());
        }
    }

    /**
     * Preview do template com dados de exemplo
     */
    public function preview(ContractTemplate $contractTemplate)
    {
        try {
            $template = $contractTemplate;
            $sampleData = $this->getSampleData();
            
            $previewContent = $this->replaceVariables($template->content, $sampleData);
            
            return response()->json([
                'success' => true,
                'preview' => $previewContent,
                'template_name' => $template->name,
                'variables_used' => json_decode($template->variables ?? '[]', true)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao gerar preview: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplicar template
     */
    public function duplicate(ContractTemplate $contractTemplate)
    {
        try {
            $original = $contractTemplate;
            
            $duplicate = ContractTemplate::create([
                'name' => $original->name . ' (Cópia)',
                'description' => $original->description,
                'content' => $original->content,
                'variables' => $original->variables,
                'is_active' => false, // Cópia inicia inativa
                'created_by' => auth()->id(),
                'version' => 1
            ]);

            // Salvar arquivo da cópia
            $this->saveTemplateFile($duplicate);

            return redirect()
                ->route('contract-templates.edit', $duplicate)
                ->with('success', 'Template duplicado com sucesso! Você pode editá-lo agora.');

        } catch (\Exception $e) {
            \Log::error('Erro ao duplicar template: ' . $e->getMessage(), [
                'original_id' => $contractTemplate->id,
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'Erro ao duplicar template: ' . $e->getMessage());
        }
    }

    /**
     * Ativar/desativar template
     */
    public function toggleStatus(ContractTemplate $contractTemplate)
    {
        try {
            $template = $contractTemplate;
            $template->update([
                'is_active' => !$template->is_active,
                'updated_by' => auth()->id()
            ]);

            $status = $template->is_active ? 'ativado' : 'desativado';
            
            return back()->with('success', "Template {$status} com sucesso!");

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao alterar status: ' . $e->getMessage());
        }
    }

    /**
     * Extrair variáveis do conteúdo
     */
    private function extractVariables(string $content): array
    {
        // Buscar por padrões como {{VARIAVEL}} ou {VARIAVEL}
        preg_match_all('/\{\{?([A-Z_][A-Z0-9_]*)\}?\}/', $content, $matches);
        
        $variables = [];
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $variable) {
                $variables[] = [
                    'name' => $variable,
                    'placeholder' => '{{' . $variable . '}}',
                    'description' => $this->getVariableDescription($variable),
                    'type' => $this->getVariableType($variable),
                    'required' => $this->isVariableRequired($variable)
                ];
            }
        }

        // Remover duplicatas
        $variables = array_unique($variables, SORT_REGULAR);
        
        return array_values($variables);
    }

    /**
     * Substituir variáveis no conteúdo
     */
    private function replaceVariables(string $content, array $data): string
    {
        foreach ($data as $variable => $value) {
            $content = str_replace([
                '{{' . $variable . '}}',
                '{' . $variable . '}'
            ], '<span class="bg-yellow-100 px-1 rounded font-semibold text-blue-800">' . $value . '</span>', $content);
        }
        
        // Converter quebras de linha em HTML
        $content = nl2br($content);
        
        // Adicionar espaçamento entre parágrafos
        $content = preg_replace('/\n\s*\n/', '</p><p class="mb-4">', $content);
        $content = '<p class="mb-4">' . $content . '</p>';
        
        return $content;
    }

    /**
     * Salvar arquivo do template
     */
    private function saveTemplateFile(ContractTemplate $template): void
    {
        $filename = 'template_' . $template->id . '_v' . $template->version . '.html';
        $path = 'contract-templates/' . $filename;
        
        Storage::put($path, $template->content);
        
        $template->update(['file_path' => $path]);
    }

    /**
     * Obter variáveis disponíveis
     */
    private function getAvailableVariables(): array
    {
        return [
            'LICENCIADO' => [
                'NOME' => 'Nome/Razão Social do licenciado',
                'NOME_FANTASIA' => 'Nome fantasia do licenciado',
                'DOCUMENTO' => 'CNPJ ou CPF formatado',
                'CNPJ' => 'CNPJ formatado',
                'CPF' => 'CPF formatado',
                'IE' => 'Inscrição Estadual',
                'ENDERECO' => 'Endereço completo',
                'LOGRADOURO' => 'Logradouro',
                'NUMERO' => 'Número',
                'COMPLEMENTO' => 'Complemento',
                'BAIRRO' => 'Bairro',
                'CIDADE' => 'Cidade',
                'UF' => 'Estado (sigla)',
                'ESTADO' => 'Estado (nome completo)',
                'CEP' => 'CEP formatado',
                'EMAIL' => 'Email do licenciado',
                'TELEFONE' => 'Telefone formatado',
                'CELULAR' => 'Celular formatado'
            ],
            'REPRESENTANTE' => [
                'REPRESENTANTE_NOME' => 'Nome do representante legal',
                'REPRESENTANTE_CPF' => 'CPF do representante',
                'REPRESENTANTE_RG' => 'RG do representante',
                'REPRESENTANTE_EMAIL' => 'Email do representante',
                'REPRESENTANTE_CARGO' => 'Cargo do representante'
            ],
            'CONTRATADA' => [
                'EMPRESA_NOME' => 'Nome da empresa contratada',
                'EMPRESA_CNPJ' => 'CNPJ da empresa',
                'EMPRESA_ENDERECO' => 'Endereço da empresa',
                'EMPRESA_CIDADE' => 'Cidade da empresa',
                'EMPRESA_UF' => 'Estado da empresa',
                'EMPRESA_CEP' => 'CEP da empresa'
            ],
            'CONTRATO' => [
                'DATA_ATUAL' => 'Data atual por extenso',
                'DATA_CONTRATO' => 'Data do contrato',
                'NUMERO_CONTRATO' => 'Número do contrato',
                'ANO_ATUAL' => 'Ano atual',
                'MES_ATUAL' => 'Mês atual',
                'VALOR' => 'Valor do contrato',
                'PRAZO' => 'Prazo do contrato'
            ]
        ];
    }

    /**
     * Obter descrição da variável
     */
    private function getVariableDescription(string $variable): string
    {
        $descriptions = [
            'NOME' => 'Nome/Razão Social do licenciado',
            'DOCUMENTO' => 'CNPJ ou CPF formatado',
            'ENDERECO' => 'Endereço completo',
            'CEP' => 'CEP formatado',
            'EMAIL' => 'Email do licenciado',
            'TELEFONE' => 'Telefone formatado',
            'DATA_ATUAL' => 'Data atual por extenso',
            'NUMERO_CONTRATO' => 'Número do contrato',
            'EMPRESA_NOME' => 'Nome da empresa'
        ];

        return $descriptions[$variable] ?? 'Variável personalizada';
    }

    /**
     * Obter tipo da variável
     */
    private function getVariableType(string $variable): string
    {
        if (in_array($variable, ['VALOR', 'PRAZO'])) {
            return 'number';
        } elseif (in_array($variable, ['EMAIL', 'REPRESENTANTE_EMAIL'])) {
            return 'email';
        } elseif (in_array($variable, ['DATA_ATUAL', 'DATA_CONTRATO'])) {
            return 'date';
        }
        
        return 'text';
    }

    /**
     * Verificar se variável é obrigatória
     */
    private function isVariableRequired(string $variable): bool
    {
        $required = ['NOME', 'DOCUMENTO', 'DATA_ATUAL', 'NUMERO_CONTRATO'];
        return in_array($variable, $required);
    }

    /**
     * Obter dados de exemplo para preview
     */
    private function getSampleData(): array
    {
        return [
            'NOME' => 'EMPRESA EXEMPLO LTDA',
            'DOCUMENTO' => '12.345.678/0001-90',
            'CNPJ' => '12.345.678/0001-90',
            'ENDERECO' => 'Rua Exemplo, 123, Centro, São Paulo, SP',
            'CEP' => '01234-567',
            'EMAIL' => 'contato@exemplo.com.br',
            'TELEFONE' => '(11) 9999-9999',
            'REPRESENTANTE_NOME' => 'João da Silva',
            'REPRESENTANTE_CPF' => '123.456.789-00',
            'EMPRESA_NOME' => 'SUA EMPRESA LTDA',
            'EMPRESA_CNPJ' => '98.765.432/0001-10',
            'EMPRESA_ENDERECO' => 'Av. Principal, 456, Centro, São Paulo, SP',
            'DATA_ATUAL' => 'quinze de janeiro de dois mil e vinte e cinco',
            'NUMERO_CONTRATO' => '001/2025',
            'ANO_ATUAL' => '2025',
            'VALOR' => 'R$ 5.000,00'
        ];
    }
}
