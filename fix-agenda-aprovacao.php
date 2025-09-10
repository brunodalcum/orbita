<?php

require_once 'vendor/autoload.php';

// Carregar configuração do Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Agenda;
use App\Models\Licenciado;
use App\Models\AgendaNotification;
use App\Models\User;

echo "🔍 Verificando agendas sem destinatário definido...\n\n";

// Buscar agendas que têm licenciado_id mas não têm destinatario_id
$agendas = Agenda::whereNotNull('licenciado_id')
                ->whereNull('destinatario_id')
                ->get();

echo "📋 Encontradas " . $agendas->count() . " agenda(s) para corrigir:\n\n";

foreach ($agendas as $agenda) {
    echo "🔧 Agenda ID: {$agenda->id}\n";
    echo "   Título: {$agenda->titulo}\n";
    echo "   Licenciado ID: {$agenda->licenciado_id}\n";
    echo "   Data: " . $agenda->data_inicio->format('d/m/Y H:i') . "\n";
    
    // Buscar o licenciado
    $licenciado = Licenciado::find($agenda->licenciado_id);
    
    if ($licenciado && $licenciado->user_id) {
        echo "   👤 Licenciado: {$licenciado->razao_social}\n";
        echo "   📧 Email: {$licenciado->email}\n";
        echo "   🆔 User ID: {$licenciado->user_id}\n";
        
        // Atualizar a agenda
        $agenda->destinatario_id = $licenciado->user_id;
        $agenda->requer_aprovacao = true;
        $agenda->status_aprovacao = 'pendente';
        $agenda->status = 'pendente';
        $agenda->save();
        
        // Criar notificação se não existir
        $notificacaoExiste = AgendaNotification::where('agenda_id', $agenda->id)
                                             ->where('user_id', $licenciado->user_id)
                                             ->where('tipo', 'solicitacao')
                                             ->exists();
        
        if (!$notificacaoExiste) {
            AgendaNotification::createSolicitacaoNotification($agenda, $licenciado->user_id);
            echo "   ✅ Notificação criada\n";
        } else {
            echo "   ℹ️  Notificação já existe\n";
        }
        
        echo "   ✅ Agenda corrigida - agora requer aprovação\n";
    } else {
        echo "   ❌ Licenciado não encontrado ou sem user_id\n";
    }
    
    echo "\n";
}

echo "🎉 Correção concluída!\n";
echo "📱 Acesse: http://127.0.0.1:8000/licenciado/agenda/pendentes\n";
