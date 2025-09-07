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
        Schema::table('contracts', function (Blueprint $table) {
            // Template e provider
            $table->foreignId('template_id')->nullable()->after('licenciado_id')->constrained('contract_templates')->onDelete('set null');
            $table->string('provider')->default('internal')->after('signature_data'); // clicksign, docusign, zapsign, internal
            $table->string('provider_envelope_id')->nullable()->after('provider');
            
            // Campos de hash e IP
            $table->string('contract_hash', 64)->nullable()->after('provider_envelope_id');
            $table->string('signer_ip')->nullable()->after('contract_hash');
            
            // Timestamps mais específicos
            $table->timestamp('generated_at')->nullable()->after('signer_ip');
            $table->timestamp('sent_at')->nullable()->after('generated_at');
            $table->timestamp('resent_at')->nullable()->after('sent_at');
            
            // Metadados do contrato
            $table->json('contract_data')->nullable()->after('resent_at'); // Dados usados na geração
            $table->json('provider_response')->nullable()->after('contract_data'); // Resposta do provider
            
            // Índices
            $table->index(['contract_hash']);
            $table->index(['provider', 'provider_envelope_id']);
            $table->index(['generated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropColumn([
                'template_id',
                'provider',
                'provider_envelope_id',
                'contract_hash',
                'signer_ip',
                'generated_at',
                'sent_at',
                'resent_at',
                'contract_data',
                'provider_response'
            ]);
        });
    }
};