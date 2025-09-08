<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products by category
     */
    public function index(Request $request)
    {
        $categories = Category::getMainCategories();
        $featuredProducts = Product::getFeaturedProducts(8);
        $latestProducts = Product::getLatestProducts(12);
        $onSaleProducts = Product::getOnSale(8);

        return view('products.index', compact(
            'categories',
            'featuredProducts', 
            'latestProducts',
            'onSaleProducts'
        ));
    }

    /**
     * Display products by category
     */
    public function category(Request $request, $categorySlug)
    {
        $category = Category::where('slug', $categorySlug)
            ->where('is_active', true)
            ->firstOrFail();

        $query = Product::active()
            ->inStock()
            ->published()
            ->byCategory($categorySlug)
            ->with('category');

        // Filtros
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->priceRange($request->min_price, $request->max_price);
        }

        // Ordenação
        $sortBy = $request->get('sort', 'name');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('sales_count', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $products = $query->paginate(12)->withQueryString();

        // Dados para filtros
        $brands = Product::active()
            ->byCategory($categorySlug)
            ->whereNotNull('brand')
            ->distinct()
            ->pluck('brand')
            ->sort();

        $priceRange = Product::active()
            ->byCategory($categorySlug)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        // Produtos em destaque da categoria
        $featuredProducts = $category->getFeaturedProducts(4);

        return view('products.category', compact(
            'category',
            'products',
            'brands',
            'priceRange',
            'featuredProducts'
        ));
    }

    /**
     * Display the specified product
     */
    public function show(Request $request, $productSlug)
    {
        $product = Product::where('slug', $productSlug)
            ->active()
            ->published()
            ->with('category')
            ->firstOrFail();

        // Incrementar visualizações
        $product->incrementViews();

        // Produtos relacionados (da mesma categoria)
        $relatedProducts = Product::active()
            ->inStock()
            ->published()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->orderBy('sales_count', 'desc')
            ->limit(8)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');
        $categorySlug = $request->get('category');

        $query = Product::active()
            ->inStock()
            ->published()
            ->with('category');

        if ($search) {
            $query->search($search);
        }

        if ($categorySlug) {
            $query->byCategory($categorySlug);
        }

        // Filtros adicionais
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->priceRange($request->min_price, $request->max_price);
        }

        // Ordenação
        $sortBy = $request->get('sort', 'relevance');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('sales_count', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                // Relevância por nome primeiro, depois por descrição
                if ($search) {
                    $query->orderByRaw("CASE WHEN name LIKE '%{$search}%' THEN 1 ELSE 2 END");
                }
                $query->orderBy('name', 'asc');
        }

        $products = $query->paginate(12)->withQueryString();

        // Dados para filtros
        $categories = Category::getMainCategories();
        $brands = Product::active()
            ->when($search, function ($q) use ($search) {
                return $q->search($search);
            })
            ->when($categorySlug, function ($q) use ($categorySlug) {
                return $q->byCategory($categorySlug);
            })
            ->whereNotNull('brand')
            ->distinct()
            ->pluck('brand')
            ->sort();

        return view('products.search', compact(
            'products',
            'search',
            'categories',
            'brands',
            'categorySlug'
        ));
    }

    /**
     * API endpoint para busca rápida (autocomplete)
     */
    public function quickSearch(Request $request)
    {
        $search = $request->get('q', '');
        
        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $products = Product::active()
            ->inStock()
            ->published()
            ->search($search)
            ->with('category')
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->formatted_price,
                    'image' => $product->main_image,
                    'category' => $product->category->name,
                    'url' => $product->url
                ];
            });

        return response()->json($products);
    }

    /**
     * Get products for a specific category (API)
     */
    public function categoryProducts(Request $request, $categorySlug)
    {
        $category = Category::where('slug', $categorySlug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = Product::active()
            ->inStock()
            ->published()
            ->byCategory($categorySlug)
            ->with('category')
            ->limit($request->get('limit', 12))
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->formatted_price,
                    'original_price' => $product->is_on_sale ? $product->formatted_original_price : null,
                    'discount_percentage' => $product->discount_percentage,
                    'image' => $product->main_image,
                    'rating' => $product->rating,
                    'is_featured' => $product->is_featured,
                    'stock_status' => $product->stock_status,
                    'url' => $product->url
                ];
            });

        return response()->json([
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'products_count' => $category->products_count
            ],
            'products' => $products
        ]);
    }
}
