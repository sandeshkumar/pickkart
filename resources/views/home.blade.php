@extends('layouts.app')

@section('title', 'PickKart - Your One-Stop Online Shop')
@section('meta_description', 'Shop millions of products at unbeatable prices. Free shipping, easy returns, and secure payments at PickKart.')

@section('content')

    {{-- ===== Hero Banner Carousel ===== --}}
    <section
        x-data="{
            current: 0,
            totalSlides: {{ isset($banners) && $banners->count() > 0 ? $banners->count() : 0 }},
            autoplayInterval: null,
            startAutoplay() {
                if (this.totalSlides > 1) {
                    this.autoplayInterval = setInterval(() => { this.next() }, 5000);
                }
            },
            stopAutoplay() {
                clearInterval(this.autoplayInterval);
            },
            next() {
                this.current = (this.current + 1) % this.totalSlides;
            },
            prev() {
                this.current = (this.current - 1 + this.totalSlides) % this.totalSlides;
            }
        }"
        x-init="startAutoplay()"
        @mouseenter="stopAutoplay()"
        @mouseleave="startAutoplay()"
        class="relative w-full overflow-hidden bg-gray-200"
    >
        @if(isset($banners) && $banners->count() > 0)
            <div class="relative h-[300px] sm:h-[400px] md:h-[500px]">
                @foreach($banners as $index => $banner)
                    <div
                        x-show="current === {{ $index }}"
                        x-transition:enter="transition ease-out duration-700"
                        x-transition:enter-start="opacity-0 transform scale-105"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-500"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="absolute inset-0"
                    >
                        <img
                            src="{{ image_url($banner->image, 'https://placehold.co/1920x500/4338ca/ffffff?text=PickKart+Banner') }}"
                            alt="{{ $banner->title ?? 'Banner' }}"
                            class="w-full h-full object-cover"
                        >
                        <div class="absolute inset-0 bg-gradient-to-r from-primary-900/80 via-primary-800/40 to-transparent"></div>
                        <div class="absolute inset-0 flex items-center">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                                <div class="max-w-xl">
                                    @if(!empty($banner->subtitle))
                                        <p class="text-primary-300 text-sm sm:text-base font-semibold mb-2 uppercase tracking-wider">{{ $banner->subtitle }}</p>
                                    @endif
                                    <h2 class="text-white text-3xl sm:text-4xl md:text-5xl font-bold mb-4 leading-tight">{{ $banner->title }}</h2>
                                    @if(!empty($banner->link))
                                        <a href="{{ $banner->link }}" class="inline-flex items-center gap-2 btn-gradient-orange text-white px-6 py-3 rounded-lg font-heading font-semibold text-sm sm:text-base">
                                            {{ $banner->button_text ?? 'Shop Now' }}
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Navigation Arrows --}}
            @if($banners->count() > 1)
                <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 backdrop-blur-sm text-white rounded-full p-2 transition-all z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 backdrop-blur-sm text-white rounded-full p-2 transition-all z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>

                {{-- Dots Indicator --}}
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 z-10">
                    @foreach($banners as $index => $banner)
                        <button
                            @click="current = {{ $index }}"
                            :class="current === {{ $index }} ? 'bg-white w-8' : 'bg-white/50 w-3'"
                            class="h-3 rounded-full transition-all duration-300"
                        ></button>
                    @endforeach
                </div>
            @endif
        @else
            {{-- Fallback Hero when no banners --}}
            <div class="h-[300px] sm:h-[400px] md:h-[500px] bg-gradient-to-r from-primary-800 via-primary-600 to-accent-600 flex items-center">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="max-w-xl">
                        <p class="text-primary-200 text-sm font-semibold mb-2 uppercase tracking-wider">Welcome to PickKart</p>
                        <h2 class="text-white text-3xl sm:text-4xl md:text-5xl font-bold mb-4 leading-tight">Shop Smart, Live Better</h2>
                        <p class="text-primary-100 mb-6">Discover millions of products at unbeatable prices with free shipping on orders over &#8377;499.</p>
                        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 btn-gradient-orange px-6 py-3 rounded-lg font-heading font-semibold">
                            Start Shopping
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </section>

    {{-- ===== Category Grid ===== --}}
    @if(isset($categories) && $categories->count() > 0)
        <section class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Shop by Category</h2>
                    <p class="text-gray-500 mt-2">Browse our wide range of categories</p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($categories as $category)
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="group">
                            <div class="bg-gray-50 rounded-xl p-4 text-center hover:bg-primary-50 hover:shadow-md transition-all duration-300">
                                @if($category->image)
                                    <div class="w-16 h-16 mx-auto mb-3 rounded-full overflow-hidden bg-gray-100">
                                        <img src="{{ image_url($category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy">
                                    </div>
                                @elseif($category->icon)
                                    <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-primary-100 flex items-center justify-center text-3xl">
                                        {!! $category->icon !!}
                                    </div>
                                @else
                                    <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-primary-100 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    </div>
                                @endif
                                <h3 class="text-sm font-semibold text-gray-800 group-hover:text-primary-700 transition-colors line-clamp-2">{{ $category->name }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===== Shop by Brand ===== --}}
    @if(isset($brands) && $brands->count() > 0)
        <section class="py-10 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Shop by Brand</h2>
                    <p class="text-gray-500 mt-2">Explore products from your favorite brands</p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($brands as $brand)
                        <a href="{{ route('products.index', ['brand' => $brand->slug]) }}" class="group bg-white rounded-xl border border-gray-100 p-6 flex flex-col items-center justify-center gap-3 hover:shadow-md hover:border-primary-200 transition-all duration-300">
                            @if($brand->logo)
                                <img src="{{ image_url($brand->logo) }}" alt="{{ $brand->name }}" class="h-10 object-contain grayscale group-hover:grayscale-0 transition-all duration-300" loading="lazy">
                            @else
                                <span class="text-lg font-bold text-gray-400 group-hover:text-primary-600 transition-colors">{{ $brand->name }}</span>
                            @endif
                            <span class="text-xs text-gray-400 group-hover:text-primary-600 transition-colors">{{ $brand->products_count }} {{ Str::plural('product', $brand->products_count) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===== Featured Products ===== --}}
    @if(isset($featuredProducts) && $featuredProducts->count() > 0)
        <section class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Featured Products</h2>
                        <p class="text-gray-500 mt-1">Handpicked products just for you</p>
                    </div>
                    <a href="{{ route('products.index', ['is_featured' => 1]) }}" class="hidden sm:inline-flex items-center gap-1 text-primary-600 hover:text-primary-700 font-semibold text-sm">
                        View All
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6">
                    @foreach($featuredProducts as $product)
                        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden group flex flex-col">
                            <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="block">
                                <div class="relative aspect-square overflow-hidden bg-gray-100">
                                    <img
                                        src="{{ image_url(optional($product->primaryImage)->path) }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy"
                                    >
                                    @if($product->compare_at_price && $product->compare_at_price > $product->price)
                                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-lg">
                                            -{{ round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100) }}%
                                        </span>
                                    @endif
                                </div>
                            </a>
                            <div class="p-3 sm:p-4 flex flex-col flex-1">
                                @if($product->category)
                                    <p class="text-xs text-primary-600 font-medium mb-1">{{ $product->category->name }}</p>
                                @endif
                                <a href="{{ route('products.show', $product->slug ?? $product->id) }}">
                                    <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 hover:text-primary-600 transition-colors mb-2">{{ $product->name }}</h3>
                                </a>
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-lg font-bold text-gray-900">{{ format_currency($product->price) }}</span>
                                    @if($product->compare_at_price && $product->compare_at_price > $product->price)
                                        <span class="text-sm text-gray-400 line-through">{{ format_currency($product->compare_at_price) }}</span>
                                    @endif
                                </div>
                                <div class="mt-auto">
                                @if($product->has_active_variants ?? $product->hasVariants())
                                    <a href="{{ route('products.show', $product->slug) }}" class="w-full btn-gradient-blue text-sm font-heading font-semibold py-2 px-4 rounded-lg flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                                        View Options
                                    </a>
                                @else
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full btn-gradient-orange text-sm font-heading font-semibold py-2 px-4 rounded-lg flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                                            Add to Cart
                                        </button>
                                    </form>
                                @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-6 sm:hidden">
                    <a href="{{ route('products.index', ['is_featured' => 1]) }}" class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-700 font-semibold text-sm">
                        View All Featured Products
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ===== New Arrivals ===== --}}
    @if(isset($newArrivals) && $newArrivals->count() > 0)
        <section class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">New Arrivals</h2>
                        <p class="text-gray-500 mt-1">The latest additions to our collection</p>
                    </div>
                    <a href="{{ route('products.index', ['new_arrivals' => 1]) }}" class="hidden sm:inline-flex items-center gap-1 text-primary-600 hover:text-primary-700 font-semibold text-sm">
                        View All
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6">
                    @foreach($newArrivals as $product)
                        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden group border border-gray-100 flex flex-col">
                            <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="block">
                                <div class="relative aspect-square overflow-hidden bg-gray-100">
                                    <img
                                        src="{{ image_url(optional($product->primaryImage)->path) }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy"
                                    >
                                    <span class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-lg">NEW</span>
                                    @if($product->compare_at_price && $product->compare_at_price > $product->price)
                                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-lg">
                                            -{{ round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100) }}%
                                        </span>
                                    @endif
                                </div>
                            </a>
                            <div class="p-3 sm:p-4 flex flex-col flex-1">
                                @if($product->category)
                                    <p class="text-xs text-primary-600 font-medium mb-1">{{ $product->category->name }}</p>
                                @endif
                                <a href="{{ route('products.show', $product->slug ?? $product->id) }}">
                                    <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 hover:text-primary-600 transition-colors mb-2">{{ $product->name }}</h3>
                                </a>
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-lg font-bold text-gray-900">{{ format_currency($product->price) }}</span>
                                    @if($product->compare_at_price && $product->compare_at_price > $product->price)
                                        <span class="text-sm text-gray-400 line-through">{{ format_currency($product->compare_at_price) }}</span>
                                    @endif
                                </div>
                                <div class="mt-auto">
                                @if($product->has_active_variants ?? $product->hasVariants())
                                    <a href="{{ route('products.show', $product->slug) }}" class="w-full btn-gradient-blue text-sm font-heading font-semibold py-2 px-4 rounded-lg flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                                        View Options
                                    </a>
                                @else
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full btn-gradient-orange text-sm font-heading font-semibold py-2 px-4 rounded-lg flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                                            Add to Cart
                                        </button>
                                    </form>
                                @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-6 sm:hidden">
                    <a href="{{ route('products.index', ['new_arrivals' => 1]) }}" class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-700 font-semibold text-sm">
                        View All New Arrivals
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ===== Deals Section ===== --}}
    @if(isset($dealProducts) && $dealProducts->count() > 0)
        <section class="py-12 bg-gradient-to-br from-accent-900 via-accent-700 to-red-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-white">Hot Deals</h2>
                        <p class="text-accent-200 mt-1">Limited time offers you don't want to miss</p>
                    </div>
                    <a href="{{ route('products.index', ['on_sale' => 1]) }}" class="hidden sm:inline-flex items-center gap-1 text-accent-200 hover:text-white font-semibold text-sm">
                        View All Deals
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6">
                    @foreach($dealProducts as $product)
                        @php
                            $discountPercent = ($product->compare_at_price && $product->compare_at_price > $product->price)
                                ? round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100)
                                : 0;
                        @endphp
                        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden group flex flex-col">
                            <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="block">
                                <div class="relative aspect-square overflow-hidden bg-gray-100">
                                    <img
                                        src="{{ image_url(optional($product->primaryImage)->path) }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy"
                                    >
                                    @if($discountPercent > 0)
                                        <span class="absolute top-2 left-2 bg-gradient-to-br from-red-500 to-red-600 text-white text-xs font-heading font-bold px-2.5 py-1.5 rounded-lg shadow-md badge-pulse">
                                            -{{ $discountPercent }}% OFF
                                        </span>
                                    @endif
                                </div>
                            </a>
                            <div class="p-3 sm:p-4 flex flex-col flex-1">
                                @if($product->category)
                                    <p class="text-xs text-primary-600 font-medium mb-1">{{ $product->category->name }}</p>
                                @endif
                                <a href="{{ route('products.show', $product->slug ?? $product->id) }}">
                                    <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 hover:text-primary-600 transition-colors mb-2">{{ $product->name }}</h3>
                                </a>
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-lg font-bold text-red-600">{{ format_currency($product->price) }}</span>
                                    @if($product->compare_at_price && $product->compare_at_price > $product->price)
                                        <span class="text-sm text-gray-400 line-through">{{ format_currency($product->compare_at_price) }}</span>
                                    @endif
                                </div>
                                @if($discountPercent > 0)
                                    <div class="mb-3">
                                        <div class="w-full bg-red-100 rounded-full h-1.5">
                                            <div class="bg-red-500 h-1.5 rounded-full" style="width: {{ min($discountPercent + 40, 95) }}%"></div>
                                        </div>
                                        <p class="text-xs text-red-600 font-medium mt-1">You save {{ format_currency($product->compare_at_price - $product->price) }}</p>
                                    </div>
                                @endif
                                <div class="mt-auto">
                                @if($product->has_active_variants ?? $product->hasVariants())
                                    <a href="{{ route('products.show', $product->slug) }}" class="w-full bg-white text-accent-700 hover:bg-accent-50 text-sm font-heading font-semibold py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                                        View Options
                                    </a>
                                @else
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full bg-white text-accent-700 hover:bg-accent-50 text-sm font-heading font-semibold py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                                        Grab Deal
                                    </button>
                                </form>
                                @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-6 sm:hidden">
                    <a href="{{ route('products.index', ['on_sale' => 1]) }}" class="inline-flex items-center gap-1 text-accent-200 hover:text-white font-semibold text-sm">
                        View All Deals
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ===== Customer Testimonials ===== --}}
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">What Our Customers Say</h2>
                <p class="text-gray-500 mt-2">Trusted by thousands of happy shoppers</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $testimonials = [
                        ['name' => 'Sarah M.', 'initials' => 'SM', 'color' => 'bg-primary-100 text-primary-700', 'stars' => 5, 'text' => 'Amazing selection and super fast delivery! I ordered on Monday and it arrived Wednesday. The quality exceeded my expectations. Will definitely shop here again.'],
                        ['name' => 'James R.', 'initials' => 'JR', 'color' => 'bg-green-100 text-green-700', 'stars' => 5, 'text' => 'Best prices I\'ve found online. The customer service team was incredibly helpful when I had questions about sizing. Highly recommend PickKart to everyone!'],
                        ['name' => 'Priya K.', 'initials' => 'PK', 'color' => 'bg-orange-100 text-orange-700', 'stars' => 4, 'text' => 'Love the easy returns policy. I had to exchange a product and the process was seamless. Great variety of brands and products to choose from.'],
                    ];
                @endphp
                @foreach($testimonials as $t)
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                        <div class="flex items-center gap-1 mb-3">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 {{ $i < $t['stars'] ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endfor
                        </div>
                        <p class="text-sm text-gray-600 mb-4 leading-relaxed">"{{ $t['text'] }}"</p>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full {{ $t['color'] }} flex items-center justify-center text-xs font-bold">{{ $t['initials'] }}</div>
                            <span class="text-sm font-semibold text-gray-800">{{ $t['name'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== Trust Badges ===== --}}
    <section class="py-8 bg-gradient-to-r from-primary-50 via-blue-50 to-primary-50 border-t border-primary-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="flex items-center gap-3 justify-center md:justify-start">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">Free Shipping</h3>
                        <p class="text-xs text-gray-500">On orders over &#8377;499</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 justify-center md:justify-start">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">Secure Payment</h3>
                        <p class="text-xs text-gray-500">100% secure checkout</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 justify-center md:justify-start">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">Easy Returns</h3>
                        <p class="text-xs text-gray-500">30-day return policy</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 justify-center md:justify-start">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">24/7 Support</h3>
                        <p class="text-xs text-gray-500">Dedicated customer service</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
