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
        Schema::table('users', function (Blueprint $table) {
            // Hierarquia
            $table->unsignedBigInteger('parent_id')->nullable()->after('role_id'); // Pai na hierarquia
            $table->enum('node_type', [
                'super_admin', 
                'operacao', 
                'white_label', 
                'licenciado_l1', 
                'licenciado_l2', 
                'licenciado_l3'
            ])->default('licenciado_l1')->after('parent_id');
            $table->integer('hierarchy_level')->default(1)->after('node_type'); // Nível na hierarquia (1-6)
            $table->string('hierarchy_path')->nullable()->after('hierarchy_level'); // Caminho completo (ex: 1/2/5)
            
            // Relacionamentos com entidades
            $table->foreignId('operacao_id')->nullable()->constrained('orbita_operacaos')->onDelete('set null')->after('hierarchy_path');
            $table->foreignId('white_label_id')->nullable()->constrained('white_labels')->onDelete('set null')->after('operacao_id');
            
            // Configurações específicas do nó
            $table->json('node_settings')->nullable()->after('white_label_id'); // Configurações específicas
            $table->json('modules')->nullable()->after('node_settings'); // Módulos ativos/disponíveis
            $table->string('domain')->nullable()->after('modules'); // Domínio personalizado
            $table->string('subdomain')->nullable()->after('domain'); // Subdomínio
            
            // Índices para performance
            $table->index(['parent_id']);
            $table->index(['node_type', 'hierarchy_level']);
            $table->index(['operacao_id', 'white_label_id']);
            $table->index(['hierarchy_path']);
            $table->unique(['domain']);
            $table->unique(['subdomain']);
            
            // Foreign key para parent_id
            $table->foreign('parent_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remover foreign keys primeiro
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['operacao_id']);
            $table->dropForeign(['white_label_id']);
            
            // Remover índices
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['node_type', 'hierarchy_level']);
            $table->dropIndex(['operacao_id', 'white_label_id']);
            $table->dropIndex(['hierarchy_path']);
            $table->dropUnique(['domain']);
            $table->dropUnique(['subdomain']);
            
            // Remover colunas
            $table->dropColumn([
                'parent_id',
                'node_type',
                'hierarchy_level',
                'hierarchy_path',
                'operacao_id',
                'white_label_id',
                'node_settings',
                'modules',
                'domain',
                'subdomain'
            ]);
        });
    }
};
