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
        Schema::create('node_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('node_type'); // 'user', 'operacao', 'white_label'
            $table->unsignedBigInteger('node_id');
            $table->string('permission_key'); // 'dashboard.view', 'users.create', etc.
            $table->boolean('granted')->default(true);
            $table->json('restrictions')->nullable(); // Restrições específicas
            $table->boolean('inherit_from_parent')->default(true);
            $table->unsignedBigInteger('granted_by')->nullable(); // Quem concedeu
            $table->timestamp('granted_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Índices
            $table->index(['node_type', 'node_id']);
            $table->index('permission_key');
            $table->unique(['node_type', 'node_id', 'permission_key']);
            
            // Foreign keys
            $table->foreign('granted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('node_permissions');
    }
};