<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agenda_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agenda_id')->comment('ID da agenda');
            $table->unsignedBigInteger('user_id')->comment('Usuário que recebe a notificação');
            $table->enum('tipo', ['solicitacao', 'aprovacao', 'recusa', 'cancelamento', 'lembrete'])->comment('Tipo da notificação');
            $table->string('titulo')->comment('Título da notificação');
            $table->text('mensagem')->comment('Mensagem da notificação');
            $table->boolean('lida')->default(false)->comment('Se a notificação foi lida');
            $table->timestamp('lida_em')->nullable()->comment('Data/hora da leitura');
            $table->json('dados_extras')->nullable()->comment('Dados extras da notificação');
            $table->timestamps();
            
            $table->foreign('agenda_id')->references('id')->on('agendas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->index(['user_id', 'lida', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_notifications');
    }
};