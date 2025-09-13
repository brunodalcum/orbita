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
        Schema::create('place_leads', function (Blueprint $table) {
            $table->id();
            
            // Relacionamentos
            $table->foreignId('place_id')->constrained()->onDelete('cascade');
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('extraction_id')->constrained('place_extractions')->onDelete('cascade');
            
            // Dados de conversão
            $table->enum('conversion_status', ['pending', 'converted', 'failed', 'skipped'])->default('pending');
            $table->text('conversion_notes')->nullable();
            $table->timestamp('converted_at')->nullable();
            
            // Dados de qualidade para o lead
            $table->integer('data_completeness_score')->default(0); // 0-100
            $table->json('missing_fields')->nullable(); // Campos que faltam
            $table->json('data_quality_flags')->nullable(); // Flags de qualidade
            
            // Deduplicação
            $table->boolean('is_duplicate')->default(false);
            $table->foreignId('duplicate_of_lead_id')->nullable()->constrained('leads')->onDelete('set null');
            $table->string('duplicate_reason')->nullable();
            
            // Compliance
            $table->boolean('consent_required')->default(true);
            $table->boolean('consent_obtained')->default(false);
            $table->timestamp('consent_date')->nullable();
            $table->string('consent_method')->nullable(); // 'automatic', 'manual', 'opt_in'
            
            $table->timestamps();
            
            // Índices
            $table->unique(['place_id', 'lead_id']); // Evitar duplicatas
            $table->index(['extraction_id', 'conversion_status']);
            $table->index(['is_duplicate', 'conversion_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('place_leads');
    }
};