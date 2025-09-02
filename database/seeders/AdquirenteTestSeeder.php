<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Adquirente;

class AdquirenteTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adquirentes = [
            'Stone',
            'Cielo',
            'Rede',
            'Safra',
            'Bradesco',
            'Itaú',
            'Santander',
            'Banco do Brasil',
            'Caixa Econômica Federal',
            'PagSeguro'
        ];

        foreach ($adquirentes as $nome) {
            Adquirente::create([
                'nome' => $nome,
                'status' => 'ativo'
            ]);
        }

        $this->command->info('Adquirentes de teste criados com sucesso!');
    }
}
