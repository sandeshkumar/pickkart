<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Traits\SearchesProducts;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    use SearchesProducts;

    public function suggestions(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:1|max:100',
        ]);

        $query = trim($request->input('q'));
        $isShortQuery = mb_strlen($query) < 3;

        $products = $this->searchProducts($query, $isShortQuery, 5);

        $categories = Category::active()
            ->where('name', 'like', "%{$query}%")
            ->withCount(['products' => fn($q) => $q->where('status', 'active')])
            ->orderByDesc('products_count')
            ->limit(3)
            ->get()
            ->map(fn($cat) => [
                'name' => $cat->name,
                'slug' => $cat->slug,
                'url' => url('/products?category=' . $cat->slug),
                'product_count' => $cat->products_count,
            ]);

        $brands = Brand::active()
            ->where('name', 'like', "%{$query}%")
            ->withCount(['products' => fn($q) => $q->where('status', 'active')])
            ->orderByDesc('products_count')
            ->limit(3)
            ->get()
            ->map(fn($brand) => [
                'name' => $brand->name,
                'slug' => $brand->slug,
                'url' => url('/products?brand=' . $brand->slug),
                'product_count' => $brand->products_count,
            ]);

        $totalCount = $this->countTotalProducts($query, $isShortQuery);

        return response()->json([
            'query' => $query,
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'total_count' => $totalCount,
            'view_all_url' => url('/products?search=' . urlencode($query)),
        ]);
    }

    private function searchProducts(string $query, bool $isShortQuery, int $limit): array
    {
        $productsQuery = Product::active()
            ->inStock()
            ->with(['primaryImage', 'category', 'brand']);

        if (!$isShortQuery) {
            $booleanQuery = $this->buildBooleanQuery($query);
            $productsQuery->where(function ($q) use ($query, $booleanQuery) {
                $q->whereRaw(
                    'MATCH(name, short_description, description) AGAINST(? IN BOOLEAN MODE)',
                    [$booleanQuery]
                )
                ->orWhere('sku', 'like', "{$query}%")
                ->orWhereHas('brand', fn($bq) => $bq->where('name', 'like', "%{$query}%"))
                ->orWhereHas('category', fn($cq) => $cq->where('name', 'like', "%{$query}%"))
                ->orWhereHas('tags', fn($tq) => $tq->where('name', 'like', "%{$query}%"));
            });

            $productsQuery->orderByRaw(
                'MATCH(name, short_description, description) AGAINST(? IN BOOLEAN MODE) DESC',
                [$booleanQuery]
            );
        } else {
            $productsQuery->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "{$query}%")
                  ->orWhereHas('brand', fn($bq) => $bq->where('name', 'like', "%{$query}%"))
                  ->orWhereHas('category', fn($cq) => $cq->where('name', 'like', "%{$query}%"));
            });
        }

        $productsQuery->orderBy('is_featured', 'desc')->latest();

        return $productsQuery->limit($limit)->get()->map(fn($product) => [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'url' => url('/products/' . $product->slug),
            'price' => format_currency($product->price),
            'compare_at_price' => $product->compare_at_price && $product->compare_at_price > $product->price
                ? format_currency($product->compare_at_price) : null,
            'image' => image_url(optional($product->primaryImage)->path),
            'category' => optional($product->category)->name,
            'brand' => optional($product->brand)->name,
        ])->toArray();
    }

    private function countTotalProducts(string $query, bool $isShortQuery): int
    {
        $countQuery = Product::active()->inStock();

        if (!$isShortQuery) {
            $booleanQuery = $this->buildBooleanQuery($query);
            $countQuery->where(function ($q) use ($query, $booleanQuery) {
                $q->whereRaw(
                    'MATCH(name, short_description, description) AGAINST(? IN BOOLEAN MODE)',
                    [$booleanQuery]
                )
                ->orWhere('sku', 'like', "{$query}%")
                ->orWhereHas('brand', fn($bq) => $bq->where('name', 'like', "%{$query}%"))
                ->orWhereHas('category', fn($cq) => $cq->where('name', 'like', "%{$query}%"))
                ->orWhereHas('tags', fn($tq) => $tq->where('name', 'like', "%{$query}%"));
            });
        } else {
            $countQuery->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "{$query}%")
                  ->orWhereHas('brand', fn($bq) => $bq->where('name', 'like', "%{$query}%"))
                  ->orWhereHas('category', fn($cq) => $cq->where('name', 'like', "%{$query}%"));
            });
        }

        return $countQuery->count();
    }
}
