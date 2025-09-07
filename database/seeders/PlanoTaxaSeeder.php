<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plano;
use App\Models\PlanoTaxa;

class PlanoTaxaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar planos existentes
        $planos = Plano::all();

        if ($planos->isEmpty()) {
            $this->command->info('Nenhum plano encontrado. Criando planos de exemplo...');
            
            // Criar alguns planos de exemplo
            $planos = collect([
                Plano::create([
                    'nome' => 'Plano Premium Stone',
                    'descricao' => 'Plano completo para estabelecimentos de grande porte',
                    'status' => 'ativo',
                    'ativo' => true
                ]),
                Plano::create([
                    'nome' => 'Plano Básico Cielo',
                    'descricao' => 'Plano para pequenos negócios',
                    'status' => 'ativo',
                    'ativo' => true
                ]),
                Plano::create([
                    'nome' => 'Plano Intermediário Rede',
                    'descricao' => 'Plano para médios estabelecimentos',
                    'status' => 'ativo',
                    'ativo' => true
                ])
            ]);
        }

        $bandeiras = ['visa', 'mastercard', 'elo', 'amex', 'hipercard'];
        $modalidades = ['debito', 'credito_avista', 'parcelado'];

        foreach ($planos as $plano) {
            foreach ($modalidades as $modalidade) {
                foreach ($bandeiras as $bandeira) {
                    if ($modalidade === 'parcelado') {
                        // Para parcelado, criar taxas para diferentes números de parcelas
                        $parcelas = [2, 3, 6, 12, 18, 21];
                        foreach ($parcelas as $parcela) {
                            PlanoTaxa::create([
                                'plano_id' => $plano->id,
                                'modalidade' => $modalidade,
                                'bandeira' => $bandeira,
                                'parcelas' => $parcela,
                                'taxa_percent' => $this->getTaxaByModalidadeAndParcelas($modalidade, $parcela, $bandeira),
                                'comissao_percent' => $this->getComissaoByModalidade($modalidade, $bandeira),
                                'ativo' => true
                            ]);
                        }
                    } else {
                        // Para débito e crédito à vista, parcelas é NULL
                        PlanoTaxa::create([
                            'plano_id' => $plano->id,
                            'modalidade' => $modalidade,
                            'bandeira' => $bandeira,
                            'parcelas' => null,
                            'taxa_percent' => $this->getTaxaByModalidadeAndParcelas($modalidade, null, $bandeira),
                            'comissao_percent' => $this->getComissaoByModalidade($modalidade, $bandeira),
                            'ativo' => true
                        ]);
                    }
                }
            }
        }

        $this->command->info('PlanoTaxa seeder executado com sucesso!');
    }

    private function getTaxaByModalidadeAndParcelas($modalidade, $parcelas, $bandeira)
    {
        $baseTaxa = match($modalidade) {
            'debito' => 1.5,
            'credito_avista' => 2.5,
            'parcelado' => 3.5,
            default => 2.0
        };

        // Ajustar taxa baseado na bandeira
        $bandeiraMultiplier = match($bandeira) {
            'visa' => 1.0,
            'mastercard' => 1.0,
            'elo' => 1.1,
            'amex' => 1.3,
            'hipercard' => 1.2,
            default => 1.0
        };

        // Ajustar taxa baseado no número de parcelas
        if ($parcelas) {
            $parcelaMultiplier = 1 + ($parcelas * 0.1); // 10% a mais por parcela
            $baseTaxa *= $parcelaMultiplier;
        }

        return round($baseTaxa * $bandeiraMultiplier + (rand(-50, 50) / 100), 4);
    }

    private function getComissaoByModalidade($modalidade, $bandeira)
    {
        $baseComissao = match($modalidade) {
            'debito' => 0.8,
            'credito_avista' => 1.2,
            'parcelado' => 1.8,
            default => 1.0
        };

        // Ajustar comissão baseado na bandeira
        $bandeiraMultiplier = match($bandeira) {
            'visa' => 1.0,
            'mastercard' => 1.0,
            'elo' => 0.9,
            'amex' => 0.7,
            'hipercard' => 0.8,
            default => 1.0
        };

        return round($baseComissao * $bandeiraMultiplier + (rand(-20, 20) / 100), 4);
    }
}
