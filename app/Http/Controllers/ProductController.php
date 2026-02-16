<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Traits\SearchesProducts;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use SearchesProducts;
    public function index(Request $request)
    {
        $query = Product::active()->inStock()->with(['primaryImage', 'category', 'brand'])->withExists(['variants as has_active_variants' => fn($q) => $q->where('is_active', true)]);

        // Category filter
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $categoryIds = $category->children()->pluck('id')->push($category->id);
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Brand filter (by slug)
        if ($request->filled('brand')) {
            $brand = Brand::where('slug', $request->brand)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Search (FULLTEXT + relationships)
        $isSearching = false;
        $isShortQuery = false;
        if ($request->filled('search')) {
            $isSearching = true;
            $search = trim($request->search);
            $isShortQuery = mb_strlen($search) < 3;

            $query->where(function ($q) use ($search, $isShortQuery) {
                if (!$isShortQuery) {
                    $booleanQuery = $this->buildBooleanQuery($search);
                    $q->whereRaw(
                        'MATCH(name, short_description, description) AGAINST(? IN BOOLEAN MODE)',
                        [$booleanQuery]
                    );
                } else {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('short_description', 'like', "%{$search}%");
                }

                $q->orWhere('sku', 'like', "{$search}%")
                  ->orWhereHas('brand', fn($bq) => $bq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('category', fn($cq) => $cq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('tags', fn($tq) => $tq->where('name', 'like', "%{$search}%"));
            });
        }

        // Sorting (relevance-first when searching)
        $sort = $request->get('sort', 'newest');
        if ($isSearching && $sort === 'newest') {
            $sort = 'relevance';
        }

        if ($isSearching && !$isShortQuery && $sort === 'relevance') {
            $booleanQuery = $this->buildBooleanQuery(trim($request->search));
            $query->orderByRaw(
                'MATCH(name, short_description, description) AGAINST(? IN BOOLEAN MODE) DESC',
                [$booleanQuery]
            );
        } else {
            match ($sort) {
                'price_low' => $query->orderBy('price', 'asc'),
                'price_high' => $query->orderBy('price', 'desc'),
                'relevance' => $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc'),
                default => $query->latest(),
            };
        }

        $products = $query->paginate(16)->withQueryString();
        $categories = Category::active()->topLevel()->withCount('products')->get();
        $brands = Brand::active()->withCount('products')->get();
        $currentCategory = $request->filled('category') ? Category::where('slug', $request->category)->first() : null;

        $category = $currentCategory;

        return view('products.index', compact('products', 'categories', 'brands', 'currentCategory', 'category'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['images', 'variants', 'category', 'brand', 'tags', 'attributeValues.attribute'])
            ->firstOrFail();

        $relatedProducts = Product::active()->inStock()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['primaryImage', 'category'])
            ->withExists(['variants as has_active_variants' => fn($q) => $q->where('is_active', true)])
            ->take(6)
            ->get();

        $soldCount = OrderItem::where('product_id', $product->id)->sum('quantity');

        return view('products.show', compact('product', 'relatedProducts', 'soldCount'));
    }
}
