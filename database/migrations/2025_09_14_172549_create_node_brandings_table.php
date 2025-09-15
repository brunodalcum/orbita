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
        Schema::create('node_brandings', function (Blueprint $table) {
            $table->id();
            $table->string('node_type'); // 'operacao', 'white_label', 'user'
            $table->unsignedBigInteger('node_id'); // ID do nó (operacao_id, white_label_id, user_id)
            $table->string('logo_url')->nullable(); // URL do logo
            $table->string('logo_small_url')->nullable(); // URL do logo pequeno
            $table->string('favicon_url')->nullable(); // URL do favicon
            $table->string('primary_color', 7)->nullable(); // Cor primária (#RRGGBB)
            $table->string('secondary_color', 7)->nullable(); // Cor secundária
            $table->string('accent_color', 7)->nullable(); // Cor de destaque
            $table->string('text_color', 7)->nullable(); // Cor do texto
            $table->string('background_color', 7)->nullable(); // Cor de fundo
            $table->string('font_family')->nullable(); // Família da fonte
            $table->text('custom_css')->nullable(); // CSS customizado
            $table->json('theme_settings')->nullable(); // Configurações avançadas do tema
            $table->boolean('inherit_from_parent')->default(true); // Herdar do pai
            $table->timestamps();
            
            // Índices
            $table->unique(['node_type', 'node_id']);
            $table->index(['node_type', 'node_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('node_brandings');
    }
};
