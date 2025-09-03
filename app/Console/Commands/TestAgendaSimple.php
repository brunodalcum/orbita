<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Agenda;
use Illuminate\Support\Facades\Auth;

class TestAgendaSimple extends Command
{
    protected $signature = 'test:agenda-simple';
    protected $description = 'Testar criação de agenda simples';

    public function handle()
    {
        $this->info('🧪 Testando criação de agenda simples...');
        
        try {
            // Simular dados de uma agenda
            $agenda = new Agenda();
            $agenda->titulo = 'Teste de Agenda - ' . date('Y-m-d H:i:s');
            $agenda->descricao = 'Teste da funcionalidade da agenda sem Google Calendar';
            $agenda->data_inicio = now()->addHour();
            $agenda->data_fim = now()->addHours(2);
            $agenda->tipo_reuniao = 'online';
            $agenda->participantes = ['teste@exemplo.com'];
            $agenda->user_id = 1; // Usuário de teste
            $agenda->status = 'agendada';
            
            // Gerar link do Meet manualmente
            $agenda->google_meet_link = 'https://meet.google.com/' . strtolower(substr(md5(uniqid()), 0, 8)) . '-' . strtolower(substr(md5(uniqid()), 0, 4)) . '-' . strtolower(substr(md5(uniqid()), 0, 4));
            
            $agenda->save();
            
            $this->info('✅ Agenda criada com sucesso!');
            $this->info("   ID: {$agenda->id}");
            $this->info("   Título: {$agenda->titulo}");
            $this->info("   Meet Link: {$agenda->google_meet_link}");
            $this->info("   Status: {$agenda->status}");
            
            // Limpar agenda de teste
            if ($this->confirm('Deseja deletar a agenda de teste?')) {
                $agenda->delete();
                $this->info('✅ Agenda de teste deletada!');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erro ao criar agenda: ' . $e->getMessage());
            $this->line('Stack trace: ' . $e->getTraceAsString());
        }
        
        return 0;
    }
}

