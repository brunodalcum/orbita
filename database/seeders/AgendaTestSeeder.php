<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Agenda;
use App\Models\User;
use Carbon\Carbon;

class AgendaTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar o primeiro usuário ou criar um de teste
        $user = User::first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'Usuário Teste',
                'email' => 'teste@exemplo.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
        }

        // Criar reuniões de exemplo para hoje
        $hoje = Carbon::today();
        
        // Reunião da manhã
        Agenda::create([
            'titulo' => 'Reunião com Cliente - Projeto Alpha',
            'descricao' => 'Discussão sobre os requisitos do novo projeto e cronograma de entrega.',
            'data_inicio' => $hoje->copy()->setTime(9, 0),
            'data_fim' => $hoje->copy()->setTime(10, 30),
            'tipo_reuniao' => 'online',
            'google_meet_link' => 'https://meet.google.com/abc-defg-hij',
            'participantes' => ['cliente@empresa.com', 'gerente@empresa.com'],
            'status' => 'agendada',
            'user_id' => $user->id,
        ]);

        // Reunião do meio-dia
        Agenda::create([
            'titulo' => 'Daily Standup - Equipe Dev',
            'descricao' => 'Reunião diária para alinhamento da equipe de desenvolvimento.',
            'data_inicio' => $hoje->copy()->setTime(12, 0),
            'data_fim' => $hoje->copy()->setTime(12, 30),
            'tipo_reuniao' => 'online',
            'google_meet_link' => 'https://meet.google.com/xyz-1234-567',
            'participantes' => ['dev1@empresa.com', 'dev2@empresa.com', 'dev3@empresa.com'],
            'status' => 'agendada',
            'user_id' => $user->id,
        ]);

        // Reunião da tarde
        Agenda::create([
            'titulo' => 'Apresentação de Resultados',
            'descricao' => 'Apresentação dos resultados do trimestre para a diretoria.',
            'data_inicio' => $hoje->copy()->setTime(14, 0),
            'data_fim' => $hoje->copy()->setTime(15, 30),
            'tipo_reuniao' => 'presencial',
            'participantes' => ['diretor@empresa.com', 'gerente@empresa.com'],
            'status' => 'agendada',
            'user_id' => $user->id,
        ]);

        // Reunião híbrida
        Agenda::create([
            'titulo' => 'Workshop de Treinamento',
            'descricao' => 'Workshop sobre novas tecnologias para a equipe.',
            'data_inicio' => $hoje->copy()->setTime(16, 0),
            'data_fim' => $hoje->copy()->setTime(18, 0),
            'tipo_reuniao' => 'hibrida',
            'google_meet_link' => 'https://meet.google.com/workshop-2024',
            'participantes' => ['equipe@empresa.com', 'instrutor@externa.com'],
            'status' => 'agendada',
            'user_id' => $user->id,
        ]);

        // Criar reuniões para amanhã
        $amanha = $hoje->copy()->addDay();

        Agenda::create([
            'titulo' => 'Reunião de Planejamento',
            'descricao' => 'Planejamento das atividades da próxima semana.',
            'data_inicio' => $amanha->copy()->setTime(10, 0),
            'data_fim' => $amanha->copy()->setTime(11, 0),
            'tipo_reuniao' => 'online',
            'google_meet_link' => 'https://meet.google.com/planning-next-week',
            'participantes' => ['gerente@empresa.com'],
            'status' => 'agendada',
            'user_id' => $user->id,
        ]);

        // Criar uma reunião já concluída (ontem)
        $ontem = $hoje->copy()->subDay();

        Agenda::create([
            'titulo' => 'Reunião Concluída - Retrospectiva',
            'descricao' => 'Retrospectiva do sprint anterior.',
            'data_inicio' => $ontem->copy()->setTime(15, 0),
            'data_fim' => $ontem->copy()->setTime(16, 0),
            'tipo_reuniao' => 'online',
            'google_meet_link' => 'https://meet.google.com/retrospective-done',
            'participantes' => ['equipe@empresa.com'],
            'status' => 'concluida',
            'user_id' => $user->id,
        ]);

        $this->command->info('Agenda de teste criada com sucesso!');
    }
}
