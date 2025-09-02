<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plano;

class PlanoTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxasDetalhadas = [
            'debito' => [
                'visa_master' => 1.25,
                'elo' => 1.30,
                'demais' => 1.35
            ],
            'credito_vista' => [
                'visa_master' => 2.15,
                'elo' => 2.20,
                'demais' => 2.25
            ],
            'parcelado' => [
                'visa_master' => [
                    '2' => 2.50,
                    '3' => 2.75,
                    '4' => 3.00,
                    '5' => 3.25,
                    '6' => 3.50,
                    '7' => 3.75,
                    '8' => 4.00,
                    '9' => 4.25,
                    '10' => 4.50,
                    '11' => 4.75,
                    '12' => 5.00
                ],
                'elo' => [
                    '2' => 2.55,
                    '3' => 2.80,
                    '4' => 3.05,
                    '5' => 3.30,
                    '6' => 3.55,
                    '7' => 3.80,
                    '8' => 4.05,
                    '9' => 4.30,
                    '10' => 4.55,
                    '11' => 4.80,
                    '12' => 5.05
                ],
                'demais' => [
                    '2' => 2.60,
                    '3' => 2.85,
                    '4' => 3.10,
                    '5' => 3.35,
                    '6' => 3.60,
                    '7' => 3.85,
                    '8' => 4.10,
                    '9' => 4.35,
                    '10' => 4.60,
                    '11' => 4.85,
                    '12' => 5.10
                ]
            ]
        ];

        Plano::create([
            'nome' => 'Plano Premium Teste',
            'descricao' => 'Plano Premium Teste - Stone - Cielo - D+1 - 12x - Mundo físico',
            'taxa' => 3.25,
            'taxas_detalhadas' => $taxasDetalhadas,
            'operacoes' => [],
            'status' => 'ativo'
        ]);

        echo "Plano de teste criado com sucesso!\n";
    }
}
