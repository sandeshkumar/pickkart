@extends('layouts.app')

@section('title', isset($category) ? $category->name . ' - PickKart' : 'All Products - PickKart')
@section('meta_description', isset($category) ? $category->meta_description : 'Browse thousands of products at PickKart. Find the best deals on electronics, fashion, home goods, and more.')

@section('canonical_url', url('/products') . (isset($currentCategory) && $currentCategory ? '?category=' . $currentCategory->slug : ''))
@section('og_title', isset($currentCategory) && $currentCategory ? $currentCategory->name . ' - PickKart' : 'All Products - PickKart')
@section('og_description', isset($currentCategory) && $currentCategory ? 'Browse ' . $currentCategory->name . ' products at PickKart.' : 'Browse thousands of products at PickKart.')
@section('robots', request()->has('sort') || request()->has('min_price') || request()->has('max_price') || request()->has('search') || request()->has('page') ? 'noindex, follow' : 'index, follow')

@section('structured_data')
    {{-- CollectionPage JSON-LD --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "CollectionPage",
        "name": @json(isset($currentCategory) && $currentCategory ? $currentCategory->name : 'All Products'),
        "description": @json(isset($currentCategory) && $currentCategory ? 'Browse ' . $currentCategory->name . ' products at PickKart' : 'Browse all products at PickKart'),
        "url": "{{ url('/products') . (isset($currentCategory) && $currentCategory ? '?category=' . $currentCategory->slug : '') }}"
        @if(isset($products) && $products->count() > 0)
        ,"mainEntity": {
            "@type": "ItemList",
            "numberOfItems": {{ $products->total() }},
            "itemListElement": [
                @foreach($products as $index => $prod)
                {
                    "@type": "ListItem",
                    "position": {{ $products->firstItem() + $index }},
                    "url": "{{ url('/products/' . $prod->slug) }}",
                    "name": @json($prod->name)
                }@if(!$loop->last),@endif

                @endforeach
            ]
        }
        @endif
    }
    </script>

    {{-- BreadcrumbList JSON-LD --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "{{ url('/') }}"
            }
            @if(isset($currentCategory) && $currentCategory)
            ,{
                "@type": "ListItem",
                "position": 2,
                "name": "Products",
                "item": "{{ url('/products') }}"
            },
            {
                "@type": "ListItem",
                "position": 3,
                "name": @json($currentCategory->name),
                "item": "{{ url('/products?category=' . $currentCategory->slug) }}"
            }
            @else
            ,{
                "@type": "ListItem",
                "position": 2,
                "name": "All Products",
                "item": "{{ url('/products') }}"
            }
            @endif
        ]
    }
    </script>
@endsection

@section('content')

    {{-- Breadcrumbs --}}
    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <ol class="flex items-center text-sm text-gray-500 gap-2">
                <li><a href="{{ url('/') }}" class="hover:text-primary-600 transition-colors">Home</a></li>
                <li><svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                @if(isset($category) && $category->parent)
                    <li><a href="{{ url('/products?category=' . $category->parent->slug) }}" class="hover:text-primary-600 transition-colors">{{ $category->parent->name }}</a></li>
                    <li><svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                @endif
                <li class="text-gray-800 font-medium">{{ isset($category) ? $category->name : 'All Products' }}</li>
            </ol>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex gap-6" x-data="{ showFilters: false, viewMode: 'grid' }">

            {{-- Sidebar Filters --}}
            <aside class="hidden lg:block w-64 flex-shrink-0">
                <div class="sticky top-24 space-y-6">

                    {{-- Category Filter --}}
                    <div class="bg-white rounded-xl border border-gray-100 p-5" x-data="{ open: true }">
                        <button @click="open = !open" class="flex items-center justify-between w-full text-left">
                            <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">Categories</h3>
                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="mt-3 space-y-2">
                            @forelse($categories ?? [] as $cat)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="categories[]" value="{{ $cat->slug }}"
                                           {{ request('category') == $cat->slug ? 'checked' : '' }}
                                           class="w-4 h-4 rounded border-gray-300 text-accent-600 focus:ring-accent-500">
                                    <span class="text-sm text-gray-600 group-hover:text-primary-600">{{ $cat->name }}</span>
                                    @if($cat->products_count ?? false)
                                        <span class="text-xs text-gray-400 ml-auto">({{ $cat->products_count }})</span>
                                    @endif
                                </label>
                            @empty
                                @php
                                    $staticCategories = ['Electronics', 'Fashion', 'Home & Garden', 'Beauty & Health', 'Sports & Outdoors', 'Toys & Games', 'Automotive', 'Books & Media', 'Groceries', 'Pet Supplies', 'Baby & Kids', 'Jewelry & Watches'];
                                @endphp
                                @foreach($staticCategories as $cat)
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-accent-600 focus:ring-accent-500">
                                        <span class="text-sm text-gray-600 group-hover:text-primary-600">{{ $cat }}</span>
                                    </label>
                                @endforeach
                            @endforelse
                        </div>
                    </div>

                    {{-- Price Range Filter --}}
                    <div class="bg-white rounded-xl border border-gray-100 p-5" x-data="{ open: true, minPrice: {{ request('min_price', 0) }}, maxPrice: {{ request('max_price', 1000) }} }">
                        <button @click="open = !open" class="flex items-center justify-between w-full text-left">
                            <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">Price Range</h3>
                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="mt-3">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="relative flex-1">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">$</span>
                                    <input type="number" x-model="minPrice" name="min_price" placeholder="Min" class="w-full pl-7 pr-2 py-2 text-sm border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-200 outline-none">
                                </div>
                                <span class="text-gray-400">-</span>
                                <div class="relative flex-1">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">$</span>
                                    <input type="number" x-model="maxPrice" name="max_price" placeholder="Max" class="w-full pl-7 pr-2 py-2 text-sm border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-200 outline-none">
                                </div>
                            </div>
                            <input type="range" min="0" max="1000" x-model="maxPrice" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-accent-600">
                            <div class="flex justify-between text-xs text-gray-400 mt-1">
                                <span>$0</span>
                                <span x-text="'$' + maxPrice"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Brand Filter --}}
                    <div class="bg-white rounded-xl border border-gray-100 p-5" x-data="{ open: true }">
                        <button @click="open = !open" class="flex items-center justify-between w-full text-left">
                            <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">Brands</h3>
                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="mt-3 space-y-2">
                            @forelse($brands ?? [] as $brand)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="brands[]" value="{{ $brand->slug }}" class="w-4 h-4 rounded border-gray-300 text-accent-600 focus:ring-accent-500">
                                    <span class="text-sm text-gray-600 group-hover:text-primary-600">{{ $brand->name }}</span>
                                </label>
                            @empty
                                @foreach(['Apple', 'Samsung', 'Nike', 'Adidas', 'Sony', 'LG', 'Dell', 'HP'] as $brand)
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-accent-600 focus:ring-accent-500">
                                        <span class="text-sm text-gray-600 group-hover:text-primary-600">{{ $brand }}</span>
                                    </label>
                                @endforeach
                            @endforelse
                        </div>
                    </div>

                    {{-- Rating Filter --}}
                    <div class="bg-white rounded-xl border border-gray-100 p-5" x-data="{ open: true }">
                        <button @click="open = !open" class="flex items-center justify-between w-full text-left">
                            <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">Rating</h3>
                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="mt-3 space-y-2">
                            @for($rating = 5; $rating >= 1; $rating--)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="rating" value="{{ $rating }}" class="w-4 h-4 border-gray-300 text-accent-600 focus:ring-accent-500">
                                    <div class="flex items-center gap-0.5">
                                        @for($s = 0; $s < 5; $s++)
                                            <svg class="w-4 h-4 {{ $s < $rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-400">& Up</span>
                                </label>
                            @endfor
                        </div>
                    </div>

                    {{-- Clear Filters --}}
                    <a href="{{ url('/products') }}" class="block text-center text-sm font-medium text-primary-600 hover:text-primary-800 py-2">
                        Clear All Filters
                    </a>
                </div>
            </aside>

            {{-- Main Content Area --}}
            <div class="flex-1 min-w-0">

                {{-- Header Bar: Sort, View, Results Count --}}
                <div class="bg-white rounded-xl border border-gray-100 p-4 mb-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            {{-- Mobile Filter Toggle --}}
                            <button @click="showFilters = !showFilters" class="lg:hidden flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-primary-600 border border-gray-200 rounded-lg px-3 py-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                Filters
                            </button>
                            <p class="text-sm text-gray-500">
                                Showing <span class="font-semibold text-gray-800">{{ $products->firstItem() ?? 1 }}-{{ $products->lastItem() ?? 12 }}</span> of
                                <span class="font-semibold text-gray-800">{{ $products->total() ?? 120 }}</span> results
                                @if(request('search'))
                                    for "<span class="font-semibold text-primary-600">{{ request('search') }}</span>"
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            {{-- Sort Dropdown --}}
                            <select name="sort" onchange="window.location.href = updateQueryParam('sort', this.value)" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:border-primary-500 focus:ring-1 focus:ring-primary-200 outline-none bg-white">
                                <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>Relevance</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                            </select>

                            {{-- View Toggle --}}
                            <div class="hidden sm:flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-primary-600 text-white' : 'bg-white text-gray-500 hover:text-primary-600'" class="p-2 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                </button>
                                <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-primary-600 text-white' : 'bg-white text-gray-500 hover:text-primary-600'" class="p-2 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mobile Filters Drawer --}}
                <div x-show="showFilters" x-cloak class="lg:hidden fixed inset-0 z-50 flex" @keydown.escape.window="showFilters = false">
                    <div @click="showFilters = false" class="fixed inset-0 bg-black/50"></div>
                    <div class="relative w-80 max-w-full bg-white h-full overflow-y-auto shadow-xl"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="-translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="-translate-x-full">
                        <div class="flex items-center justify-between p-4 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-800">Filters</h3>
                            <button @click="showFilters = false" class="p-2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="p-4 space-y-6">
                            {{-- Mobile Category Filter --}}
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">Categories</h4>
                                <div class="space-y-2">
                                    @foreach(['Electronics', 'Fashion', 'Home & Garden', 'Beauty & Health', 'Sports', 'Toys & Games', 'Automotive', 'Books', 'Groceries', 'Pet Supplies', 'Baby & Kids', 'Jewelry'] as $cat)
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-accent-600 focus:ring-accent-500">
                                            <span class="text-sm text-gray-600">{{ $cat }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            {{-- Mobile Price Range --}}
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">Price Range</h4>
                                <div class="flex items-center gap-2">
                                    <input type="number" placeholder="Min" class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-lg">
                                    <span class="text-gray-400">-</span>
                                    <input type="number" placeholder="Max" class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-lg">
                                </div>
                            </div>
                            {{-- Mobile Rating --}}
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">Rating</h4>
                                <div class="space-y-2">
                                    @for($r = 5; $r >= 1; $r--)
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="mobile_rating" class="w-4 h-4 border-gray-300 text-accent-600 focus:ring-accent-500">
                                            <div class="flex gap-0.5">
                                                @for($s = 0; $s < 5; $s++)
                                                    <svg class="w-4 h-4 {{ $s < $r ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                @endfor
                                            </div>
                                            <span class="text-xs text-gray-400">& Up</span>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            <button @click="showFilters = false" class="w-full btn-gradient-orange font-heading font-semibold py-3 rounded-lg">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Grid View --}}
                <div x-show="viewMode === 'grid'">
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                        @forelse($products ?? [] as $product)
                            @include('components.product-card', ['product' => $product])
                        @empty
                            <div class="col-span-full text-center py-16">
                                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                <h3 class="text-xl font-semibold text-gray-700 mb-2">No products found</h3>
                                <p class="text-sm text-gray-500 mb-6 max-w-md mx-auto">
                                    @if(request('search'))
                                        We couldn't find any products matching "{{ request('search') }}". Try a different search term or browse our categories.
                                    @else
                                        No products match your current filters. Try adjusting your filters or browse all products.
                                    @endif
                                </p>
                                <a href="{{ url('/products') }}" class="inline-flex items-center gap-2 btn-gradient-blue font-heading font-semibold px-6 py-2.5 rounded-lg text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                    View All Products
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- List View --}}
                <div x-show="viewMode === 'list'" x-cloak>
                    <div class="space-y-4">
                        @forelse($products ?? [] as $product)
                            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-md transition-shadow flex">
                                <a href="{{ url('/products/' . $product->slug) }}" class="flex-shrink-0 w-48 h-48">
                                    <img src="{{ image_url(optional($product->primaryImage)->path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover" loading="lazy">
                                </a>
                                <div class="p-4 flex-1">
                                    <a href="{{ url('/products/' . $product->slug) }}" class="text-lg font-medium text-gray-800 hover:text-primary-600">{{ $product->name }}</a>
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $product->short_description }}</p>
                                    <div class="flex items-center gap-1 mt-2">
                                        @for($s = 0; $s < 5; $s++)
                                            <svg class="w-4 h-4 {{ $s < round($product->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        @endfor
                                        <span class="text-sm text-gray-500 ml-1">({{ $product->review_count }} reviews)</span>
                                    </div>
                                    <div class="flex items-center gap-3 mt-3">
                                        <span class="text-xl font-bold text-gray-900">{{ format_currency($product->price) }}</span>
                                        @if($product->compare_at_price)
                                            <span class="text-sm text-gray-400 line-through">{{ format_currency($product->compare_at_price) }}</span>
                                            <span class="text-sm text-red-600 font-medium">{{ $product->discount_percentage }}% off</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3 mt-3">
                                        <button class="btn-gradient-orange text-sm font-heading font-semibold px-4 py-2 rounded-lg">Add to Cart</button>
                                        <button class="border border-gray-200 text-gray-600 hover:text-red-500 hover:border-red-300 p-2 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16">
                                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                <h3 class="text-xl font-semibold text-gray-700 mb-2">No products found</h3>
                                <p class="text-sm text-gray-500 mb-6">Try adjusting your filters or search terms.</p>
                                <a href="{{ url('/products') }}" class="inline-flex items-center gap-2 btn-gradient-blue font-heading font-semibold px-6 py-2.5 rounded-lg text-sm">
                                    View All Products
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    @if(isset($products) && $products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $products->appends(request()->query())->links() }}
                    @else
                        {{-- Static pagination placeholder --}}
                        <nav class="flex items-center justify-center gap-1">
                            <span class="px-3 py-2 text-sm text-gray-400 border border-gray-200 rounded-lg cursor-not-allowed">&laquo; Previous</span>
                            <span class="px-3 py-2 text-sm text-white bg-primary-600 border border-primary-600 rounded-lg font-medium">1</span>
                            <a href="#" class="px-3 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-primary-50 hover:text-primary-600 hover:border-primary-300 transition-colors">2</a>
                            <a href="#" class="px-3 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-primary-50 hover:text-primary-600 hover:border-primary-300 transition-colors">3</a>
                            <span class="px-2 py-2 text-sm text-gray-400">...</span>
                            <a href="#" class="px-3 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-primary-50 hover:text-primary-600 hover:border-primary-300 transition-colors">10</a>
                            <a href="#" class="px-3 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-primary-50 hover:text-primary-600 hover:border-primary-300 transition-colors">Next &raquo;</a>
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function updateQueryParam(key, value) {
        const url = new URL(window.location.href);
        url.searchParams.set(key, value);
        return url.toString();
    }
</script>
@endpush