<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'icon',
        'color',
        'sort_order',
        'is_active',
        'is_featured',
        'meta_data'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'meta_data' => 'array',
        'sort_order' => 'integer'
    ];

    // Relacionamentos
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function activeProducts(): HasMany
    {
        return $this->hasMany(Product::class)->where('status', 'active')->where('in_stock', true);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Accessors
    public function getProductsCountAttribute()
    {
        return $this->activeProducts()->count();
    }

    public function getUrlAttribute()
    {
        return route('products.category', $this->slug);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/categories/default.jpg');
    }

    // Métodos auxiliares
    public function getFeaturedProducts($limit = 8)
    {
        return $this->activeProducts()
            ->where('is_featured', true)
            ->orderBy('sales_count', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getLatestProducts($limit = 12)
    {
        return $this->activeProducts()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getBestSellersProducts($limit = 8)
    {
        return $this->activeProducts()
            ->orderBy('sales_count', 'desc')
            ->limit($limit)
            ->get();
    }

    // Métodos estáticos
    public static function getMainCategories()
    {
        return self::active()
            ->ordered()
            ->get();
    }

    public static function getFeaturedCategories($limit = 6)
    {
        return self::active()
            ->featured()
            ->ordered()
            ->limit($limit)
            ->get();
    }
}
