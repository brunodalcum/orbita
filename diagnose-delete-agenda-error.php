<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "🔍 DIAGNÓSTICO: Erro ao Excluir Agenda em Produção\n";
echo "================================================\n\n";

try {
    // Verificar se a agenda existe
    $agendaId = 29;
    echo "📋 Verificando agenda ID: {$agendaId}\n";
    
    $agenda = \App\Models\Agenda::find($agendaId);
    if (!$agenda) {
        echo "❌ Agenda não encontrada\n";
        exit(1);
    }
    
    echo "✅ Agenda encontrada: {$agenda->titulo}\n";
    echo "   Data: {$agenda->data_inicio}\n";
    echo "   User ID: {$agenda->user_id}\n";
    echo "   Participantes: " . json_encode($agenda->participantes) . "\n\n";
    
    // Verificar se ReminderService existe
    echo "🔧 Verificando ReminderService...\n";
    if (class_exists('\App\Services\ReminderService')) {
        echo "✅ ReminderService encontrado\n";
        
        try {
            $reminderService = new \App\Services\ReminderService();
            echo "✅ ReminderService instanciado com sucesso\n";
            
            // Verificar lembretes existentes
            $reminders = \App\Models\Reminder::where('event_id', $agendaId)->get();
            echo "📋 Lembretes encontrados: {$reminders->count()}\n";
            
            foreach ($reminders as $reminder) {
                echo "   - ID: {$reminder->id}, Status: {$reminder->status}, Email: {$reminder->participant_email}\n";
            }
            
        } catch (\Exception $e) {
            echo "❌ Erro ao instanciar ReminderService: " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ ReminderService não encontrado\n";
    }
    
    // Verificar EmailService
    echo "\n📧 Verificando EmailService...\n";
    if (class_exists('\App\Services\EmailService')) {
        echo "✅ EmailService encontrado\n";
        
        try {
            $emailService = new \App\Services\EmailService();
            echo "✅ EmailService instanciado com sucesso\n";
        } catch (\Exception $e) {
            echo "❌ Erro ao instanciar EmailService: " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ EmailService não encontrado\n";
    }
    
    // Verificar GoogleCalendarService
    echo "\n📅 Verificando GoogleCalendarService...\n";
    if (class_exists('\App\Services\GoogleCalendarService')) {
        echo "✅ GoogleCalendarService encontrado\n";
        
        try {
            $googleService = new \App\Services\GoogleCalendarService();
            echo "✅ GoogleCalendarService instanciado com sucesso\n";
        } catch (\Exception $e) {
            echo "❌ Erro ao instanciar GoogleCalendarService: " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ GoogleCalendarService não encontrado\n";
    }
    
    // Simular exclusão (sem realmente excluir)
    echo "\n🧪 Simulando processo de exclusão...\n";
    
    // 1. Verificar Google Calendar
    if ($agenda->google_event_id) {
        echo "📅 Agenda tem Google Event ID: {$agenda->google_event_id}\n";
    } else {
        echo "📅 Agenda não tem Google Event ID\n";
    }
    
    // 2. Verificar participantes
    if (!empty($agenda->participantes)) {
        echo "📧 Agenda tem " . count($agenda->participantes) . " participantes\n";
    } else {
        echo "📧 Agenda não tem participantes\n";
    }
    
    // 3. Verificar lembretes
    $remindersCount = \App\Models\Reminder::where('event_id', $agendaId)->count();
    echo "🔔 Agenda tem {$remindersCount} lembretes\n";
    
    echo "\n✅ Diagnóstico concluído sem erros\n";
    echo "💡 O problema pode estar na autenticação ou permissões\n";
    
} catch (\Exception $e) {
    echo "❌ ERRO CRÍTICO: " . $e->getMessage() . "\n";
    echo "📍 Arquivo: " . $e->getFile() . "\n";
    echo "📍 Linha: " . $e->getLine() . "\n";
    echo "\n🔍 Stack Trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n================================================\n";
echo "🔍 Diagnóstico concluído\n";
?>
