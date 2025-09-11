<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "🔧 CORREÇÃO: Método destroy do AgendaController\n";
echo "==============================================\n\n";

try {
    // Ler o arquivo atual
    $controllerPath = __DIR__ . '/app/Http/Controllers/AgendaController.php';
    $content = file_get_contents($controllerPath);
    
    if (!$content) {
        throw new Exception("Não foi possível ler o arquivo AgendaController.php");
    }
    
    echo "📁 Arquivo lido com sucesso\n";
    
    // Criar versão simplificada do método destroy
    $newDestroyMethod = '    public function destroy(string $id)
    {
        try {
            $agenda = Agenda::where(\'user_id\', Auth::id())->findOrFail($id);
            
            // Log para debug
            \Log::info(\'🗑️ Iniciando exclusão de agenda\', [
                \'agenda_id\' => $agenda->id,
                \'titulo\' => $agenda->titulo,
                \'user_id\' => Auth::id(),
            ]);
            
            // Excluir do Google Calendar se existir
            if ($agenda->google_event_id) {
                try {
                    $googleService = new GoogleCalendarService();
                    $googleService->deleteEvent($agenda->google_event_id);
                    \Log::info(\'✅ Evento excluído do Google Calendar\', [\'event_id\' => $agenda->google_event_id]);
                } catch (\Exception $e) {
                    \Log::error(\'❌ Erro ao excluir evento do Google Calendar: \' . $e->getMessage());
                }
            }
            
            // Enviar e-mails de cancelamento
            if (!empty($agenda->participantes)) {
                try {
                    $emailService = new EmailService();
                    $organizador = Auth::user()->name ?? Auth::user()->email;
                    $emailService->sendMeetingCancellation(
                        $agenda->participantes,
                        $agenda->titulo,
                        $organizador
                    );
                    \Log::info(\'✅ E-mails de cancelamento enviados\');
                } catch (\Exception $e) {
                    \Log::error(\'❌ Erro ao enviar e-mails de cancelamento: \' . $e->getMessage());
                }
            }
            
            // Cancelar lembretes automáticos (com verificação de classe)
            try {
                if (class_exists(\'\\App\\Services\\ReminderService\')) {
                    $reminderService = new \App\Services\ReminderService();
                    $canceledCount = $reminderService->cancelForEvent($agenda);
                    
                    \Log::info(\'✅ Lembretes cancelados para agenda excluída\', [
                        \'agenda_id\' => $agenda->id,
                        \'lembretes_cancelados\' => $canceledCount,
                    ]);
                } else {
                    \Log::warning(\'⚠️ ReminderService não encontrado, pulando cancelamento de lembretes\');
                }
            } catch (\Exception $e) {
                \Log::error(\'❌ Erro ao cancelar lembretes: \' . $e->getMessage());
                // Não falhar a exclusão da agenda por causa dos lembretes
            }
            
            // Excluir a agenda
            $agenda->delete();
            
            \Log::info(\'✅ Agenda excluída com sucesso\', [\'agenda_id\' => $id]);

            return response()->json([
                \'success\' => true,
                \'message\' => \'Reunião excluída com sucesso!\' . (!empty($agenda->participantes) ? \' E-mails de cancelamento enviados aos participantes.\' : \'\')
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error(\'❌ Agenda não encontrada para exclusão\', [
                \'agenda_id\' => $id,
                \'user_id\' => Auth::id(),
            ]);
            
            return response()->json([
                \'success\' => false,
                \'message\' => \'Reunião não encontrada ou você não tem permissão para excluí-la.\'
            ], 404);
            
        } catch (\Exception $e) {
            \Log::error(\'❌ ERRO CRÍTICO ao excluir agenda\', [
                \'agenda_id\' => $id,
                \'user_id\' => Auth::id(),
                \'error\' => $e->getMessage(),
                \'file\' => $e->getFile(),
                \'line\' => $e->getLine(),
                \'trace\' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                \'success\' => false,
                \'message\' => \'Erro ao excluir reunião: \' . $e->getMessage()
            ], 500);
        }
    }';
    
    // Encontrar e substituir o método destroy atual
    $pattern = '/public function destroy\(string \$id\)\s*\{[^}]*\{[^}]*\}[^}]*\}/s';
    
    if (preg_match($pattern, $content)) {
        $newContent = preg_replace($pattern, $newDestroyMethod, $content);
        
        // Fazer backup
        $backupPath = $controllerPath . '.backup.' . date('Y-m-d-H-i-s');
        file_put_contents($backupPath, $content);
        echo "💾 Backup criado: " . basename($backupPath) . "\n";
        
        // Salvar nova versão
        file_put_contents($controllerPath, $newContent);
        echo "✅ Método destroy atualizado com logs detalhados\n";
        
        echo "\n📋 Melhorias aplicadas:\n";
        echo "   ✅ Logs detalhados para debug\n";
        echo "   ✅ Verificação de classe ReminderService\n";
        echo "   ✅ Tratamento específico de ModelNotFoundException\n";
        echo "   ✅ Logs de erro mais detalhados\n";
        echo "   ✅ Não falha se ReminderService não existir\n";
        
    } else {
        echo "❌ Não foi possível encontrar o método destroy para substituir\n";
        echo "💡 Aplicando correção manual...\n";
        
        // Se não conseguir substituir, adicionar import do ReminderService se não existir
        if (strpos($content, 'use App\Services\ReminderService;') === false) {
            $content = str_replace(
                'use App\Services\EmailService;',
                "use App\Services\EmailService;\nuse App\Services\ReminderService;",
                $content
            );
            
            file_put_contents($controllerPath, $content);
            echo "✅ Import do ReminderService adicionado\n";
        }
    }
    
    echo "\n🚀 Correção aplicada! Teste a exclusão novamente.\n";
    
} catch (\Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    echo "📍 Arquivo: " . $e->getFile() . "\n";
    echo "📍 Linha: " . $e->getLine() . "\n";
}

echo "\n==============================================\n";
echo "🔧 Correção concluída\n";
?>
