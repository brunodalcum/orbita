<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContractTemplate;

class ContractTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar se já existe um template padrão
        $existingTemplate = ContractTemplate::where('name', 'Contrato Padrão de Licenciamento')->first();
        
        if ($existingTemplate) {
            $this->command->info('Template padrão já existe. Pulando...');
            return;
        }

        // Ler o conteúdo do template
        $templatePath = resource_path('views/contracts/templates/default.blade.php');
        $content = file_get_contents($templatePath);

        // Criar o template
        ContractTemplate::create([
            'name' => 'Contrato Padrão de Licenciamento',
            'version' => '1.0',
            'engine' => 'blade',
            'content' => $content,
            'is_active' => true,
            'description' => 'Template padrão para contratos de licenciamento do sistema de pagamentos.',
            'placeholders_json' => [
                'contratante' => [
                    'nome' => 'licensee.company_name',
                    'cnpj' => 'licensee.cnpj_formatted',
                    'ie' => 'licensee.ie',
                    'endereco' => 'licensee.address',
                    'cidade' => 'licensee.city',
                    'uf' => 'licensee.state',
                    'cep' => 'licensee.zipcode_formatted'
                ],
                'representante' => [
                    'nome' => 'licensee.representative_name',
                    'cpf' => 'licensee.representative_cpf_formatted',
                    'email' => 'licensee.representative_email',
                    'telefone' => 'licensee.representative_phone_formatted'
                ],
                'contratada' => [
                    'nome' => 'operation.company_name',
                    'cnpj' => 'operation.cnpj_formatted',
                    'endereco' => 'operation.address',
                    'cidade' => 'operation.city',
                    'uf' => 'operation.state',
                    'cep' => 'operation.zipcode_formatted'
                ],
                'contrato' => [
                    'data' => 'contract.date_formatted',
                    'id' => 'contract.id_formatted',
                    'hash' => 'contract.hash'
                ]
            ]
        ]);

        $this->command->info('Template padrão criado com sucesso!');
    }
}