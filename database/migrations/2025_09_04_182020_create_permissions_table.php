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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // dashboard.view, licenciados.create, etc.
            $table->string('display_name'); // Visualizar Dashboard, Criar Licenciados, etc.
            $table->text('description')->nullable();
            $table->string('module')->nullable(); // dashboard, licenciados, operacoes, etc.
            $table->string('action')->nullable(); // view, create, update, delete, manage
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
