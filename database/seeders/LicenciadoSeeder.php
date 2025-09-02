<?php

namespace Database\Seeders;

use App\Models\Licenciado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LicenciadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $licenciados = [
            [
                'razao_social' => 'João Silva Santos',
                'nome_fantasia' => 'João Silva',
                'cnpj_cpf' => '12345678901',
                'endereco' => 'Rua das Palmeiras, 456',
                'bairro' => 'Jardim América',
                'cidade' => 'Rio de Janeiro',
                'estado' => 'RJ',
                'cep' => '20000000',
                'email' => 'joao.silva@email.com',
                'telefone' => '21987654321',
                'pagseguro' => true,
                'adiq' => false,
                'confrapag' => true,
                'mercadopago' => false,
                'status' => 'aprovado',
            ],
            [
                'razao_social' => 'Empresa ABC Ltda',
                'nome_fantasia' => 'ABC Ltda',
                'cnpj_cpf' => '12345678000190',
                'endereco' => 'Av. Paulista, 1000',
                'bairro' => 'Bela Vista',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'cep' => '01310000',
                'email' => 'contato@abc.com',
                'telefone' => '1133334444',
                'pagseguro' => true,
                'adiq' => true,
                'confrapag' => false,
                'mercadopago' => true,
                'status' => 'em_analise',
            ],
            [
                'razao_social' => 'Maria Santos Oliveira',
                'nome_fantasia' => 'Maria Santos',
                'cnpj_cpf' => '98765432100',
                'endereco' => 'Rua da Liberdade, 789',
                'bairro' => 'Centro',
                'cidade' => 'Belo Horizonte',
                'estado' => 'MG',
                'cep' => '30112000',
                'email' => 'maria@email.com',
                'telefone' => '31987654321',
                'pagseguro' => false,
                'adiq' => true,
                'confrapag' => true,
                'mercadopago' => false,
                'status' => 'risco',
            ],
        ];

        foreach ($licenciados as $licenciado) {
            Licenciado::create($licenciado);
        }
    }
}
