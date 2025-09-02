<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Operacao;

class OperacaoTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $operacoes = [
            ['nome' => 'Stone', 'adquirente' => 'Stone'],
            ['nome' => 'Cielo', 'adquirente' => 'Cielo'],
            ['nome' => 'Rede', 'adquirente' => 'Rede'],
            ['nome' => 'GetNet', 'adquirente' => 'GetNet'],
            ['nome' => 'PagSeguro', 'adquirente' => 'PagSeguro'],
            ['nome' => 'Mercado Pago', 'adquirente' => 'Mercado Pago'],
            ['nome' => 'Safra', 'adquirente' => 'Safra'],
            ['nome' => 'Bradesco', 'adquirente' => 'Bradesco'],
            ['nome' => 'Itaú', 'adquirente' => 'Itaú'],
            ['nome' => 'Santander', 'adquirente' => 'Santander']
        ];

        foreach ($operacoes as $operacao) {
            Operacao::firstOrCreate(
                ['nome' => $operacao['nome']],
                $operacao
            );
        }

        echo "Operações de teste criadas com sucesso!\n";
    }
}