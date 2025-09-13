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
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            
            // Dados básicos do Google Places
            $table->string('place_id')->unique()->index(); // ID único do Google
            $table->string('name');
            $table->text('formatted_address')->nullable();
            $table->string('vicinity')->nullable();
            
            // Localização
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('plus_code')->nullable();
            
            // Contato
            $table->string('formatted_phone_number')->nullable();
            $table->string('international_phone_number')->nullable();
            $table->string('website')->nullable();
            
            // Classificação e avaliação
            $table->json('types')->nullable(); // Array de tipos do Google
            $table->decimal('rating', 2, 1)->nullable();
            $table->integer('user_ratings_total')->nullable();
            $table->string('price_level')->nullable();
            
            // Horário de funcionamento
            $table->json('opening_hours')->nullable();
            $table->boolean('open_now')->nullable();
            
            // Dados adicionais
            $table->json('photos')->nullable(); // URLs das fotos
            $table->text('editorial_summary')->nullable();
            $table->string('business_status')->nullable();
            
            // Metadados de coleta
            $table->string('source')->default('google_places');
            $table->timestamp('collected_at');
            $table->string('search_query')->nullable(); // Query que encontrou este place
            $table->string('search_location')->nullable(); // Localização da busca
            $table->decimal('search_radius', 8, 2)->nullable(); // Raio da busca em metros
            
            // Dados para deduplicação
            $table->string('phone_hash')->nullable()->index(); // Hash do telefone para dedupe
            $table->string('website_domain')->nullable()->index(); // Domínio do site
            $table->string('address_hash')->nullable()->index(); // Hash do endereço
            
            // Status e qualidade
            $table->integer('quality_score')->default(0); // Score baseado em completude dos dados
            $table->boolean('has_phone')->default(false)->index();
            $table->boolean('has_website')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            
            // LGPD e Compliance
            $table->boolean('opt_out')->default(false)->index(); // Se solicitou remoção
            $table->timestamp('opt_out_date')->nullable();
            $table->text('opt_out_reason')->nullable();
            
            // Auditoria
            $table->timestamps();
            $table->softDeletes(); // Para compliance com LGPD
            
            // Índices compostos para performance
            $table->index(['latitude', 'longitude']);
            $table->index(['collected_at', 'is_active']);
            $table->index(['quality_score', 'is_active']);
            $table->index(['search_query', 'collected_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};