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
        Schema::create('contract_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
            $table->string('document_type'); // rg, cpf, comprovante_residencia, cnpj, etc.
            $table->string('original_name'); // Nome original do arquivo
            $table->string('file_path'); // Caminho do arquivo no storage
            $table->string('mime_type');
            $table->bigInteger('file_size'); // Tamanho em bytes
            $table->enum('status', ['pendente', 'aprovado', 'rejeitado'])->default('pendente');
            $table->text('admin_notes')->nullable(); // Observações do admin sobre o documento
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null'); // Admin que analisou
            $table->timestamps();
            
            $table->index(['contract_id', 'document_type']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_documents');
    }
};
