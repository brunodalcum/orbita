<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lead;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leads = [
            [
                'nome' => 'João Silva',
                'email' => 'joao.silva@empresa.com.br',
                'telefone' => '(11) 99999-9999',
                'empresa' => 'Empresa ABC Ltda',
                'status' => 'novo',
                'origem' => 'Website',
                'observacoes' => 'Cliente interessado em planos de pagamento',
                'ativo' => true
            ],
            [
                'nome' => 'Maria Santos',
                'email' => 'maria.santos@loja.com.br',
                'telefone' => '(21) 88888-8888',
                'empresa' => 'Loja XYZ',
                'status' => 'contatado',
                'origem' => 'Indicação',
                'observacoes' => 'Aguardando retorno do cliente',
                'ativo' => true
            ],
            [
                'nome' => 'Pedro Oliveira',
                'email' => 'pedro.oliveira@restaurante.com.br',
                'telefone' => '(31) 77777-7777',
                'empresa' => 'Restaurante Sabor',
                'status' => 'qualificado',
                'origem' => 'Feira de Negócios',
                'observacoes' => 'Cliente qualificado, enviar proposta',
                'ativo' => true
            ],
            [
                'nome' => 'Ana Costa',
                'email' => 'ana.costa@consultoria.com.br',
                'telefone' => '(41) 66666-6666',
                'empresa' => 'Consultoria Pro',
                'status' => 'proposta',
                'origem' => 'LinkedIn',
                'observacoes' => 'Proposta enviada, aguardando resposta',
                'ativo' => true
            ],
            [
                'nome' => 'Carlos Ferreira',
                'email' => 'carlos.ferreira@tech.com.br',
                'telefone' => '(51) 55555-5555',
                'empresa' => 'Tech Solutions',
                'status' => 'fechado',
                'origem' => 'Cold Call',
                'observacoes' => 'Venda fechada com sucesso!',
                'ativo' => true
            ]
        ];

        foreach ($leads as $lead) {
            Lead::create($lead);
        }
    }
}
