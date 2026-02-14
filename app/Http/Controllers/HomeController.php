<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::active()->position('hero')->orderBy('sort_order')->get();
        $categories = Category::active()->topLevel()->with('children')->orderBy('sort_order')->get();
        $brands = Brand::active()->withCount('products')->orderByDesc('products_count')->take(8)->get();
        $variantExists = fn($q) => $q->where('is_active', true);
        $featuredProducts = Product::active()->featured()->inStock()->with(['primaryImage', 'category'])->withExists(['variants as has_active_variants' => $variantExists])->latest()->take(12)->get();
        $newArrivals = Product::active()->inStock()->with(['primaryImage', 'category'])->withExists(['variants as has_active_variants' => $variantExists])->latest()->take(12)->get();
        $dealProducts = Product::active()->inStock()->whereNotNull('compare_at_price')->whereColumn('compare_at_price', '>', 'price')->with(['primaryImage', 'category'])->withExists(['variants as has_active_variants' => $variantExists])->take(8)->get();

        return view('home', compact('banners', 'categories', 'brands', 'featuredProducts', 'newArrivals', 'dealProducts'));
    }
}
