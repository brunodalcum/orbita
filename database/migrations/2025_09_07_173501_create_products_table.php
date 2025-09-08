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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('sku')->unique()->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->boolean('manage_stock')->default(true);
            $table->boolean('in_stock')->default(true);
            $table->string('status')->default('active'); // active, inactive, draft
            $table->json('images')->nullable(); // Array de URLs das imagens
            $table->string('brand')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->json('dimensions')->nullable(); // {length, width, height}
            $table->json('attributes')->nullable(); // Atributos específicos do produto
            $table->json('tags')->nullable(); // Tags para busca
            $table->integer('views_count')->default(0);
            $table->integer('sales_count')->default(0);
            $table->decimal('rating', 3, 2)->default(0); // Rating de 0 a 5
            $table->integer('reviews_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_digital')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            $table->index(['category_id', 'status']);
            $table->index(['slug']);
            $table->index(['is_featured', 'status']);
            $table->index(['brand']);
            $table->index(['price']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
