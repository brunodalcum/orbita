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
        Schema::create('contract_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('version', 10)->default('1.0');
            $table->enum('engine', ['blade', 'docx'])->default('blade');
            $table->string('file_path')->nullable(); // Caminho do arquivo template
            $table->json('placeholders_json')->nullable(); // Mapa de placeholders
            $table->text('content')->nullable(); // Conteúdo do template (se blade)
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['is_active']);
            $table->index(['name', 'version']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_templates');
    }
};