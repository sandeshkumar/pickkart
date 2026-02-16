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
        <div class="flex gap-6" x-data="productFilters()" x-init="init()">

            {{-- Sidebar Filters --}}
            <aside class="hidden lg:block w-64 flex-shrink-0">
                <div class="sticky top-24 space-y-6">

                    {{-- Active Filters Summary --}}
                    <template x-if="hasActiveFilters()">
                        <div class="bg-primary-50 rounded-xl border border-primary-200 p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-semibold text-primary-800">Active Filters</h3>
                                <button @click="clearAll()" class="text-xs text-primary-600 hover:text-primary-800 underline">Clear All</button>
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                <template x-if="filters.category">
                                    <span class="inline-flex items-center gap-1 bg-white text-primary-700 text-xs px-2 py-1 rounded-full border border-primary-200">
                                        <span x-text="getCategoryName(filters.category)"></span>
                                        <button @click="setFilter('category', '')" class="hover:text-red-500">&times;</button>
                                    </span>
                                </template>
                                <template x-if="filters.brand">
                                    <span class="inline-flex items-center gap-1 bg-white text-primary-700 text-xs px-2 py-1 rounded-full border border-primary-200">
                                        <span x-text="getBrandName(filters.brand)"></span>
                                        <button @click="setFilter('brand', '')" class="hover:text-red-500">&times;</button>
                                    </span>
                                </template>
                                <template x-if="filters.min_price > 0 || filters.max_price < 100000">
                                    <span class="inline-flex items-center gap-1 bg-white text-primary-700 text-xs px-2 py-1 rounded-full border border-primary-200">
                                        <span x-text="'₹' + filters.min_price + ' - ₹' + filters.max_price"></span>
                                        <button @click="filters.min_price = 0; filters.max_price = 100000; applyFilters()" class="hover:text-red-500">&times;</button>
                                    </span>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Category Filter --}}
                    <div class="bg-white rounded-xl border border-gray-100 p-5" x-data="{ open: true }">
                        <button @click="open = !open" class="flex items-center justify-between w-full text-left">
                            <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">Categories</h3>
                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="mt-3 space-y-2">
                            @forelse($categories ?? [] as $cat)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="category" value="{{ $cat->slug }}"
                                           :checked="filters.category === '{{ $cat->slug }}'"
                                           @change="setFilter('category', '{{ $cat->slug }}')"
                                           class="w-4 h-4 border-gray-300 text-accent-600 focus:ring-accent-500">
                                    <span class="text-sm group-hover:text-primary-600" :class="filters.category === '{{ $cat->slug }}' ? 'text-primary-700 font-medium' : 'text-gray-600'">{{ $cat->name }}</span>
                                    @if($cat->products_count ?? false)
                                        <span class="text-xs text-gray-400 ml-auto">({{ $cat->products_count }})</span>
                                    @endif
                                </label>
                            @empty
                                <p class="text-sm text-gray-400 italic">No categories available</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Price Range Filter --}}
                    <div class="bg-white rounded-xl border border-gray-100 p-5" x-data="{ open: true }">
                        <button @click="open = !open" class="flex items-center justify-between w-full text-left">
                            <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">Price Range</h3>
                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="mt-3">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="relative flex-1">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">&#8377;</span>
                                    <input type="number" x-model.number="filters.min_price" @change="applyFilters()" placeholder="Min" class="w-full pl-7 pr-2 py-2 text-sm border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-200 outline-none">
                                </div>
                                <span class="text-gray-400">-</span>
                                <div class="relative flex-1">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">&#8377;</span>
                                    <input type="number" x-model.number="filters.max_price" @change="applyFilters()" placeholder="Max" class="w-full pl-7 pr-2 py-2 text-sm border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-200 outline-none">
                                </div>
                            </div>
                            <input type="range" min="0" max="100000" step="500" x-model.number="filters.max_price" @change="applyFilters()" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-accent-600">
                            <div class="flex justify-between text-xs text-gray-400 mt-1">
                                <span>&#8377;0</span>
                                <span x-text="'₹' + Number(filters.max_price).toLocaleString('en-IN')"></span>
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
                                    <input type="radio" name="brand" value="{{ $brand->slug }}"
                                           :checked="filters.brand === '{{ $brand->slug }}'"
                                           @change="setFilter('brand', '{{ $brand->slug }}')"
                                           class="w-4 h-4 border-gray-300 text-accent-600 focus:ring-accent-500">
                                    <span class="text-sm group-hover:text-primary-600" :class="filters.brand === '{{ $brand->slug }}' ? 'text-primary-700 font-medium' : 'text-gray-600'">{{ $brand->name }}</span>
                                    @if($brand->products_count ?? false)
                                        <span class="text-xs text-gray-400 ml-auto">({{ $brand->products_count }})</span>
                                    @endif
                                </label>
                            @empty
                                <p class="text-sm text-gray-400 italic">No brands available</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Clear Filters --}}
                    <button @click="clearAll()" class="block w-full text-center text-sm font-medium text-primary-600 hover:text-primary-800 py-2 transition-colors">
                        Clear All Filters
                    </button>
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
                                Showing <span class="font-semibold text-gray-800">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</span> of
                                <span class="font-semibold text-gray-800">{{ $products->total() ?? 0 }}</span> results
                                @if(request('search'))
                                    for "<span class="font-semibold text-primary-600">{{ request('search') }}</span>"
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            {{-- Sort Dropdown --}}
                            <select @change="setFilter('sort', $event.target.value)" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:border-primary-500 focus:ring-1 focus:ring-primary-200 outline-none bg-white">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>Relevance</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
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
                                    @forelse($categories ?? [] as $cat)
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="mobile_category" value="{{ $cat->slug }}"
                                                   :checked="filters.category === '{{ $cat->slug }}'"
                                                   @change="setFilter('category', '{{ $cat->slug }}')"
                                                   class="w-4 h-4 border-gray-300 text-accent-600 focus:ring-accent-500">
                                            <span class="text-sm text-gray-600">{{ $cat->name }}</span>
                                            @if($cat->products_count ?? false)
                                                <span class="text-xs text-gray-400 ml-auto">({{ $cat->products_count }})</span>
                                            @endif
                                        </label>
                                    @empty
                                        <p class="text-sm text-gray-400 italic">No categories available</p>
                                    @endforelse
                                </div>
                            </div>
                            {{-- Mobile Brand Filter --}}
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">Brands</h4>
                                <div class="space-y-2">
                                    @forelse($brands ?? [] as $brand)
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="mobile_brand" value="{{ $brand->slug }}"
                                                   :checked="filters.brand === '{{ $brand->slug }}'"
                                                   @change="setFilter('brand', '{{ $brand->slug }}')"
                                                   class="w-4 h-4 border-gray-300 text-accent-600 focus:ring-accent-500">
                                            <span class="text-sm text-gray-600">{{ $brand->name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-sm text-gray-400 italic">No brands available</p>
                                    @endforelse
                                </div>
                            </div>
                            {{-- Mobile Price Range --}}
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">Price Range</h4>
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="relative flex-1">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">&#8377;</span>
                                        <input type="number" x-model.number="filters.min_price" placeholder="Min" class="w-full pl-7 pr-2 py-2 text-sm border border-gray-200 rounded-lg">
                                    </div>
                                    <span class="text-gray-400">-</span>
                                    <div class="relative flex-1">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">&#8377;</span>
                                        <input type="number" x-model.number="filters.max_price" placeholder="Max" class="w-full pl-7 pr-2 py-2 text-sm border border-gray-200 rounded-lg">
                                    </div>
                                </div>
                                <input type="range" min="0" max="100000" step="500" x-model.number="filters.max_price" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-accent-600">
                            </div>
                            <button @click="applyFilters(); showFilters = false" class="w-full btn-gradient-orange font-heading font-semibold py-3 rounded-lg">
                                Apply Filters
                            </button>
                            <button @click="clearAll()" class="w-full text-sm text-primary-600 hover:text-primary-800 py-2">
                                Clear All Filters
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
                                    <div class="flex items-center gap-3 mt-3">
                                        <span class="text-xl font-bold text-gray-900">{{ format_currency($product->price) }}</span>
                                        @if($product->compare_at_price)
                                            <span class="text-sm text-gray-400 line-through">{{ format_currency($product->compare_at_price) }}</span>
                                            <span class="text-sm text-red-600 font-medium">{{ $product->discount_percentage }}% off</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3 mt-3">
                                        @php
                                            $hasVariants = $product->has_active_variants ?? $product->hasVariants();
                                            $isWishlisted = auth()->check() && auth()->user()->wishlists()->where('product_id', $product->id)->exists();
                                        @endphp
                                        @if($hasVariants)
                                            <a href="{{ route('products.show', $product->slug) }}" class="btn-gradient-blue text-sm font-heading font-semibold px-4 py-2 rounded-lg inline-flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                                                View Options
                                            </a>
                                        @else
                                            <form action="{{ route('cart.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn-gradient-orange text-sm font-heading font-semibold px-4 py-2 rounded-lg inline-flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                                                    Add to Cart
                                                </button>
                                            </form>
                                        @endif
                                        @auth
                                            <form action="{{ route('wishlist.toggle') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit" class="border {{ $isWishlisted ? 'border-red-300 text-red-500' : 'border-gray-200 text-gray-600 hover:text-red-500 hover:border-red-300' }} p-2 rounded-lg transition-colors" title="{{ $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                                                    <svg class="w-5 h-5" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('login') }}" class="border border-gray-200 text-gray-600 hover:text-red-500 hover:border-red-300 p-2 rounded-lg transition-colors" title="Login to add to Wishlist">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                            </a>
                                        @endauth
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
    function productFilters() {
        return {
            showFilters: false,
            viewMode: 'grid',
            filters: {
                category: '',
                brand: '',
                min_price: 0,
                max_price: 100000,
                sort: 'newest',
                search: '',
            },
            categoryMap: @json(($categories ?? collect())->pluck('name', 'slug')),
            brandMap: @json(($brands ?? collect())->pluck('name', 'slug')),

            init() {
                const params = new URLSearchParams(window.location.search);
                this.filters.category = params.get('category') || '';
                this.filters.brand = params.get('brand') || '';
                this.filters.min_price = parseInt(params.get('min_price')) || 0;
                this.filters.max_price = parseInt(params.get('max_price')) || 100000;
                this.filters.sort = params.get('sort') || 'newest';
                this.filters.search = params.get('search') || '';
            },

            setFilter(key, value) {
                this.filters[key] = value;
                this.applyFilters();
            },

            applyFilters() {
                const url = new URL(window.location.origin + window.location.pathname);

                if (this.filters.search) url.searchParams.set('search', this.filters.search);
                if (this.filters.category) url.searchParams.set('category', this.filters.category);
                if (this.filters.brand) url.searchParams.set('brand', this.filters.brand);
                if (this.filters.min_price > 0) url.searchParams.set('min_price', this.filters.min_price);
                if (this.filters.max_price < 100000) url.searchParams.set('max_price', this.filters.max_price);
                if (this.filters.sort && this.filters.sort !== 'newest') url.searchParams.set('sort', this.filters.sort);

                window.location.href = url.toString();
            },

            clearAll() {
                const url = new URL(window.location.origin + window.location.pathname);
                if (this.filters.search) url.searchParams.set('search', this.filters.search);
                window.location.href = url.toString();
            },

            hasActiveFilters() {
                return this.filters.category || this.filters.brand ||
                       this.filters.min_price > 0 || this.filters.max_price < 100000;
            },

            getCategoryName(slug) {
                return this.categoryMap[slug] || slug;
            },

            getBrandName(slug) {
                return this.brandMap[slug] || slug;
            },
        };
    }
</script>
@endpush