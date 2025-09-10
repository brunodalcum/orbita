<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BusinessHours;

class BusinessHoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar horários comerciais globais (padrão para todos os usuários)
        BusinessHours::createDefaultBusinessHours();
        
        $this->command->info('Horários comerciais padrão criados (09:00 às 18:00, segunda a sexta)');
    }
}