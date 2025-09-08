<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'manage_stock',
        'in_stock',
        'status',
        'images',
        'brand',
        'weight',
        'dimensions',
        'attributes',
        'tags',
        'views_count',
        'sales_count',
        'rating',
        'reviews_count',
        'is_featured',
        'is_digital',
        'published_at'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'rating' => 'decimal:2',
        'images' => 'array',
        'dimensions' => 'array',
        'attributes' => 'array',
        'tags' => 'array',
        'manage_stock' => 'boolean',
        'in_stock' => 'boolean',
        'is_featured' => 'boolean',
        'is_digital' => 'boolean',
        'published_at' => 'datetime',
        'stock_quantity' => 'integer',
        'views_count' => 'integer',
        'sales_count' => 'integer',
        'reviews_count' => 'integer'
    ];

    // Relacionamentos
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInStock($query)
    {
        return $query->where('in_stock', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeByCategory($query, $categorySlug)
    {
        return $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug)->where('is_active', true);
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('short_description', 'like', "%{$search}%")
              ->orWhere('brand', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%");
        });
    }

    public function scopePriceRange($query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
        return $query;
    }

    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Accessors
    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->sale_price && $this->price > $this->sale_price) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getMainImageAttribute()
    {
        if ($this->images && count($this->images) > 0) {
            return asset('storage/' . $this->images[0]);
        }
        return asset('images/products/default.jpg');
    }

    public function getUrlAttribute()
    {
        return route('products.show', $this->slug);
    }

    public function getFormattedPriceAttribute()
    {
        return 'R$ ' . number_format($this->current_price, 2, ',', '.');
    }

    public function getFormattedOriginalPriceAttribute()
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }

    public function getIsOnSaleAttribute()
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    public function getStockStatusAttribute()
    {
        if (!$this->manage_stock) {
            return 'Em estoque';
        }

        if ($this->stock_quantity <= 0) {
            return 'Fora de estoque';
        } elseif ($this->stock_quantity <= 5) {
            return 'Últimas unidades';
        }

        return 'Em estoque';
    }

    public function getRatingStarsAttribute()
    {
        $rating = $this->rating;
        $stars = [];
        
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars[] = 'full';
            } elseif ($i - 0.5 <= $rating) {
                $stars[] = 'half';
            } else {
                $stars[] = 'empty';
            }
        }
        
        return $stars;
    }

    // Métodos auxiliares
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function incrementSales($quantity = 1)
    {
        $this->increment('sales_count', $quantity);
    }

    public function updateStock($quantity, $operation = 'decrease')
    {
        if (!$this->manage_stock) {
            return true;
        }

        if ($operation === 'decrease') {
            if ($this->stock_quantity >= $quantity) {
                $this->decrement('stock_quantity', $quantity);
                
                // Atualizar status de estoque
                if ($this->stock_quantity <= 0) {
                    $this->update(['in_stock' => false]);
                }
                
                return true;
            }
            return false;
        } else {
            $this->increment('stock_quantity', $quantity);
            
            // Reativar se estava fora de estoque
            if (!$this->in_stock && $this->stock_quantity > 0) {
                $this->update(['in_stock' => true]);
            }
            
            return true;
        }
    }

    // Métodos estáticos
    public static function getFeaturedProducts($limit = 8)
    {
        return self::active()
            ->inStock()
            ->published()
            ->featured()
            ->orderBy('sales_count', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getLatestProducts($limit = 12)
    {
        return self::active()
            ->inStock()
            ->published()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getBestSellers($limit = 8)
    {
        return self::active()
            ->inStock()
            ->published()
            ->orderBy('sales_count', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getOnSale($limit = 12)
    {
        return self::active()
            ->inStock()
            ->published()
            ->whereNotNull('sale_price')
            ->where('sale_price', '<', \DB::raw('price'))
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
