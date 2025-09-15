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
        Schema::create('node_domains', function (Blueprint $table) {
            $table->id();
            $table->string('node_type'); // 'user', 'operacao', 'white_label'
            $table->unsignedBigInteger('node_id');
            $table->string('domain_type')->default('subdomain'); // 'custom', 'subdomain'
            $table->string('domain')->nullable(); // Domínio personalizado completo
            $table->string('subdomain')->nullable(); // Subdomínio (ex: cliente.orbita.com)
            $table->string('base_domain')->default('orbita.dspay.com.br'); // Domínio base
            $table->boolean('is_primary')->default(true); // Domínio principal do nó
            $table->boolean('is_active')->default(true);
            $table->boolean('ssl_enabled')->default(false);
            $table->string('ssl_status')->default('pending'); // 'pending', 'active', 'expired', 'error'
            $table->timestamp('ssl_expires_at')->nullable();
            $table->json('dns_records')->nullable(); // Registros DNS necessários
            $table->string('verification_token')->nullable(); // Token para verificação
            $table->boolean('verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->json('redirect_rules')->nullable(); // Regras de redirecionamento
            $table->json('settings')->nullable(); // Configurações adicionais
            $table->unsignedBigInteger('created_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Índices
            $table->index(['node_type', 'node_id']);
            $table->index('domain');
            $table->index('subdomain');
            $table->index(['is_active', 'verified']);
            $table->unique(['domain'], 'unique_custom_domain');
            $table->unique(['subdomain', 'base_domain'], 'unique_subdomain');
            
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('node_domains');
    }
};