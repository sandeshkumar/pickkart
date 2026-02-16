@extends('layouts.app')

@section('title', ($product->meta_title ?? $product->name ?? 'Product Details') . ' - PickKart')
@section('meta_description', $product->meta_description ?? $product->short_description ?? 'View product details, reviews, and more at PickKart.')

@section('canonical_url', url('/products/' . $product->slug))
@section('og_type', 'product')
@section('og_title', ($product->meta_title ?? $product->name) . ' - PickKart')
@section('og_description', Str::limit(strip_tags($product->meta_description ?? $product->short_description ?? $product->description ?? ''), 160))
@section('og_url', url('/products/' . $product->slug))
@section('twitter_card', 'summary_large_image')

@if($product->primaryImage)
    @section('og_image', image_url($product->primaryImage->path))
    @section('og_image_alt', $product->name)
@endif

@section('og_extra')
    <meta property="product:price:amount" content="{{ $product->price }}">
    <meta property="product:price:currency" content="{{ currency_code() }}">
    @if($product->brand)
        <meta property="product:brand" content="{{ $product->brand->name }}">
    @endif
    <meta property="product:availability" content="{{ $product->stock_quantity > 0 ? 'in stock' : 'out of stock' }}">
@endsection

@section('structured_data')
    {{-- Product JSON-LD --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "Product",
        "name": @json($product->name),
        "description": @json(Str::limit(strip_tags($product->description ?? ''), 500)),
        "sku": @json($product->sku ?? ''),
        "url": "{{ url('/products/' . $product->slug) }}",
        @if($product->images && $product->images->count() > 0)
        "image": [
            @foreach($product->images as $img)
                "{{ image_url($img->path) }}"@if(!$loop->last),@endif

            @endforeach
        ],
        @elseif($product->primaryImage)
        "image": "{{ image_url($product->primaryImage->path) }}",
        @endif
        @if($product->brand)
        "brand": {
            "@type": "Brand",
            "name": @json($product->brand->name)
        },
        @endif
        @if($product->category)
        "category": @json($product->category->name),
        @endif
        "offers": {
            "@type": "Offer",
            "url": "{{ url('/products/' . $product->slug) }}",
            "priceCurrency": "{{ currency_code() }}",
            "price": "{{ $product->price }}",
            "availability": "{{ $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
            "seller": {
                "@type": "Organization",
                "name": "PickKart"
            }
            @if($product->compare_at_price && $product->compare_at_price > $product->price)
            ,"priceValidUntil": "{{ now()->addMonths(3)->format('Y-m-d') }}"
            @endif
        }
        @if($product->review_count > 0)
        ,"aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "{{ number_format($product->average_rating, 1) }}",
            "reviewCount": "{{ $product->review_count }}",
            "bestRating": "5",
            "worstRating": "1"
        }
        @endif
        @if($product->approvedReviews && $product->approvedReviews->count() > 0)
        ,"review": [
            @foreach($product->approvedReviews->take(5) as $review)
            {
                "@type": "Review",
                "author": {
                    "@type": "Person",
                    "name": @json($review->user->name ?? 'Anonymous')
                },
                "datePublished": "{{ $review->created_at->toIso8601String() }}",
                "reviewRating": {
                    "@type": "Rating",
                    "ratingValue": "{{ $review->rating }}",
                    "bestRating": "5"
                }
                @if($review->body)
                ,"reviewBody": @json(Str::limit($review->body, 500))
                @endif
            }@if(!$loop->last),@endif

            @endforeach
        ]
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
            },
            {
                "@type": "ListItem",
                "position": 2,
                "name": "Products",
                "item": "{{ url('/products') }}"
            }
            @if($product->category)
            ,{
                "@type": "ListItem",
                "position": 3,
                "name": @json($product->category->name),
                "item": "{{ url('/products?category=' . $product->category->slug) }}"
            },
            {
                "@type": "ListItem",
                "position": 4,
                "name": @json($product->name),
                "item": "{{ url('/products/' . $product->slug) }}"
            }
            @else
            ,{
                "@type": "ListItem",
                "position": 3,
                "name": @json($product->name),
                "item": "{{ url('/products/' . $product->slug) }}"
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
            <ol class="flex items-center text-sm text-gray-500 gap-2 flex-wrap">
                <li><a href="{{ url('/') }}" class="hover:text-primary-600 transition-colors">Home</a></li>
                <li><svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                <li><a href="{{ url('/products') }}" class="hover:text-primary-600 transition-colors">Products</a></li>
                @if(isset($product) && $product->category)
                    <li><svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                    <li><a href="{{ url('/products?category=' . $product->category->slug) }}" class="hover:text-primary-600 transition-colors">{{ $product->category->name }}</a></li>
                @endif
                <li><svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                <li class="text-gray-800 font-medium truncate max-w-[200px]">{{ $product->name ?? 'Product Name' }}</li>
            </ol>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Product Main Section --}}
        @php
            $activeVariants = ($product->variants ?? collect())->where('is_active', true)->values();
            $variantJson = $activeVariants->map(fn($v) => [
                'id' => $v->id,
                'option1' => $v->option1,
                'option2' => $v->option2,
                'option3' => $v->option3,
                'price' => (float) $v->price,
                'compare_at_price' => $v->compare_at_price ? (float) $v->compare_at_price : null,
                'stock' => (int) $v->stock_quantity,
                'name' => $v->display_name,
                'image' => $v->image,
            ])->values();
            $imagePaths = ($product->images ?? collect())->pluck('path')->values();
            $productOptions = $product->options ?? [];
            $initSelections = collect($productOptions)->mapWithKeys(fn($opt, $i) => ['option'.($i+1) => ''])->all();
        @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" x-data="{
            selectedImage: 0,
            lightboxOpen: false,
            quantity: 1,
            activeTab: 'description',
            options: @js($productOptions),
            variants: @js($variantJson),
            selections: @js($initSelections),
            imagePaths: @js($imagePaths),
            currencySymbol: @js(currency_symbol()),
            get selectedVariant() {
                if (!this.options || this.options.length === 0) return null;
                for (let i = 0; i < this.options.length; i++) {
                    if (!this.selections['option' + (i + 1)]) return null;
                }
                return this.variants.find(v => {
                    for (let i = 0; i < this.options.length; i++) {
                        let key = 'option' + (i + 1);
                        if (v[key] !== this.selections[key]) return false;
                    }
                    return true;
                }) || null;
            },
            get displayPrice() {
                return this.selectedVariant && this.selectedVariant.price ? this.selectedVariant.price : {{ (float) $product->price }};
            },
            get displayComparePrice() {
                if (this.selectedVariant) return this.selectedVariant.compare_at_price;
                return {{ $product->compare_at_price ? (float) $product->compare_at_price : 'null' }};
            },
            get displayStock() {
                if (this.selectedVariant) return this.selectedVariant.stock;
                return {{ (int) $product->stock_quantity }};
            },
            get hasOptions() {
                return this.options && this.options.length > 0 && this.variants.length > 0;
            },
            get variantId() {
                return this.selectedVariant ? this.selectedVariant.id : '';
            },
            get canAddToCart() {
                if (!this.hasOptions) return {{ $product->stock_quantity > 0 ? 'true' : 'false' }};
                return this.selectedVariant !== null && this.selectedVariant.stock > 0;
            },
            get needsSelection() {
                if (!this.hasOptions) return false;
                return this.selectedVariant === null;
            },
            formatPrice(amount) {
                if (amount === null || amount === undefined) return '';
                return this.currencySymbol + parseFloat(amount).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            },
            get savings() {
                if (this.displayComparePrice && this.displayComparePrice > this.displayPrice) {
                    return this.displayComparePrice - this.displayPrice;
                }
                return null;
            }
        }" x-effect="if (selectedVariant && selectedVariant.image) {
            let idx = imagePaths.indexOf(selectedVariant.image);
            if (idx !== -1) selectedImage = idx;
        }">
            <div class="grid md:grid-cols-2 gap-0">

                {{-- Image Gallery --}}
                <div class="p-6 md:p-8 border-b md:border-b-0 md:border-r border-gray-100">
                    {{-- Main Image --}}
                    <div class="relative rounded-xl overflow-hidden bg-gray-50 mb-4 aspect-square cursor-pointer" @click="lightboxOpen = true">
                        @if(isset($product) && $product->images && $product->images->count() > 0)
                            @foreach($product->images as $index => $image)
                                <img x-show="selectedImage === {{ $index }}"
                                     src="{{ image_url($image->path) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-contain"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100">
                            @endforeach
                        @else
                            <img x-show="selectedImage === 0" src="https://placehold.co/600x600/e0e7ff/4338ca?text=Product+Image+1" alt="Product Image" class="w-full h-full object-contain">
                            <img x-show="selectedImage === 1" x-cloak src="https://placehold.co/600x600/dbeafe/3b82f6?text=Product+Image+2" alt="Product Image" class="w-full h-full object-contain">
                            <img x-show="selectedImage === 2" x-cloak src="https://placehold.co/600x600/e0e7ff/6366f1?text=Product+Image+3" alt="Product Image" class="w-full h-full object-contain">
                            <img x-show="selectedImage === 3" x-cloak src="https://placehold.co/600x600/c7d2fe/4f46e5?text=Product+Image+4" alt="Product Image" class="w-full h-full object-contain">
                        @endif

                        {{-- Discount Badge --}}
                        @if(isset($product) && $product->compare_at_price && $product->compare_at_price > $product->price)
                            <span class="absolute top-4 left-4 bg-gradient-to-br from-red-500 to-red-600 text-white text-sm font-heading font-bold px-3 py-1.5 rounded-lg shadow-md badge-pulse">-{{ round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100) }}%</span>
                        @endif

                        {{-- Wishlist Button --}}
                        <button @click.stop class="absolute top-4 right-4 w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 shadow-md transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </button>

                        {{-- Zoom Hint --}}
                        <div class="absolute bottom-3 right-3 bg-black/40 text-white text-xs px-2 py-1 rounded flex items-center gap-1 pointer-events-none">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                            Click to zoom
                        </div>
                    </div>

                    {{-- Lightbox Overlay --}}
                    <div x-show="lightboxOpen" x-cloak @keydown.escape.window="lightboxOpen = false"
                         class="fixed inset-0 z-[90] flex items-center justify-center bg-black/90 p-4"
                         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                        <button @click="lightboxOpen = false" class="absolute top-4 right-4 text-white/70 hover:text-white z-10" aria-label="Close lightbox">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        @php $totalImages = (isset($product) && $product->images && $product->images->count() > 0) ? $product->images->count() : 4; @endphp
                        <button @click="selectedImage = (selectedImage - 1 + {{ $totalImages }}) % {{ $totalImages }}" class="absolute left-4 text-white/70 hover:text-white z-10">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button @click="selectedImage = (selectedImage + 1) % {{ $totalImages }}" class="absolute right-4 text-white/70 hover:text-white z-10">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                        <div class="max-w-4xl max-h-[85vh] w-full">
                            @if(isset($product) && $product->images && $product->images->count() > 0)
                                @foreach($product->images as $index => $image)
                                    <img x-show="selectedImage === {{ $index }}" src="{{ image_url($image->path) }}" alt="{{ $product->name }}" class="w-full h-full object-contain max-h-[85vh]"
                                         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                                @endforeach
                            @else
                                <img x-show="selectedImage === 0" src="https://placehold.co/800x800/e0e7ff/4338ca?text=Product+Image+1" class="w-full h-full object-contain max-h-[85vh]">
                                <img x-show="selectedImage === 1" src="https://placehold.co/800x800/dbeafe/3b82f6?text=Product+Image+2" class="w-full h-full object-contain max-h-[85vh]">
                                <img x-show="selectedImage === 2" src="https://placehold.co/800x800/e0e7ff/6366f1?text=Product+Image+3" class="w-full h-full object-contain max-h-[85vh]">
                                <img x-show="selectedImage === 3" src="https://placehold.co/800x800/c7d2fe/4f46e5?text=Product+Image+4" class="w-full h-full object-contain max-h-[85vh]">
                            @endif
                        </div>
                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/60 text-sm">
                            <span x-text="selectedImage + 1"></span> / {{ $totalImages }}
                        </div>
                    </div>

                    {{-- Thumbnail Gallery --}}
                    <div class="flex gap-3 overflow-x-auto scrollbar-hide">
                        @if(isset($product) && $product->images && $product->images->count() > 0)
                            @foreach($product->images as $index => $image)
                                <button @click="selectedImage = {{ $index }}"
                                        :class="selectedImage === {{ $index }} ? 'ring-2 ring-primary-500 border-primary-500' : 'border-gray-200 hover:border-gray-300'"
                                        class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-all">
                                    <img src="{{ image_url($image->path) }}" alt="Thumbnail" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        @else
                            @for($t = 0; $t < 4; $t++)
                                <button @click="selectedImage = {{ $t }}"
                                        :class="selectedImage === {{ $t }} ? 'ring-2 ring-primary-500 border-primary-500' : 'border-gray-200 hover:border-gray-300'"
                                        class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-all">
                                    <img src="https://placehold.co/80x80/e0e7ff/4338ca?text={{ $t + 1 }}" alt="Thumbnail {{ $t + 1 }}" class="w-full h-full object-cover">
                                </button>
                            @endfor
                        @endif
                    </div>
                </div>

                {{-- Product Info --}}
                <div class="p-6 md:p-8">
                    {{-- Brand --}}
                    @if(isset($product) && $product->brand)
                        <a href="{{ url('/products?brand=' . $product->brand->slug) }}" class="text-sm text-primary-600 hover:text-primary-800 font-medium">{{ $product->brand->name }}</a>
                    @else
                        <span class="text-sm text-primary-600 font-medium">Premium Brand</span>
                    @endif

                    {{-- Title --}}
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mt-1 mb-3">{{ $product->name ?? 'Premium Wireless Bluetooth Headphones with Active Noise Cancellation' }}</h1>

                    {{-- Rating --}}
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex items-center gap-0.5">
                            @php $avgRating = isset($product) ? round($product->average_rating) : 4; @endphp
                            @for($s = 0; $s < 5; $s++)
                                <svg class="w-5 h-5 {{ $s < $avgRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endfor
                        </div>
                        <span class="text-sm text-primary-600 font-medium">{{ isset($product) ? number_format($product->average_rating, 1) : '4.2' }}</span>
                        <span class="text-sm text-gray-400">|</span>
                        <a href="#reviews" class="text-sm text-gray-500 hover:text-primary-600">{{ $product->review_count ?? 128 }} Reviews</a>
                        <span class="text-sm text-gray-400">|</span>
                        @if(($soldCount ?? 0) > 0)
                            <span class="text-sm text-gray-500">{{ $soldCount }}+ Sold</span>
                        @endif
                    </div>

                    {{-- Price (Dynamic) --}}
                    <div class="bg-gray-50 rounded-xl p-4 mb-6">
                        <div class="flex items-baseline gap-3 flex-wrap">
                            <span class="text-3xl font-bold text-primary-700" x-text="formatPrice(displayPrice)">{{ format_currency($product->price) }}</span>
                            <span x-show="displayComparePrice && displayComparePrice > displayPrice" x-cloak class="text-lg text-gray-400 line-through" x-text="formatPrice(displayComparePrice)"></span>
                            <span x-show="savings" x-cloak class="bg-red-100 text-red-700 text-sm font-semibold px-2 py-0.5 rounded">Save <span x-text="formatPrice(savings)"></span></span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Inclusive of all taxes. Free shipping on this item.</p>
                    </div>

                    {{-- Stock Status (Dynamic) --}}
                    <div class="mb-6">
                        <div x-show="!hasOptions || selectedVariant">
                            <div x-show="displayStock > 5" class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="text-sm font-medium text-green-600">In Stock</span>
                            </div>
                            <div x-show="displayStock > 0 && displayStock <= 5" x-cloak class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></span>
                                <span class="text-sm font-medium text-orange-600">Only <span x-text="displayStock"></span> left in stock - order soon!</span>
                            </div>
                            <div x-show="displayStock <= 0" x-cloak class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                <span class="text-sm font-medium text-red-600">Out of Stock</span>
                            </div>
                        </div>
                        <div x-show="hasOptions && !selectedVariant" x-cloak class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-blue-400 rounded-full"></span>
                            <span class="text-sm font-medium text-blue-600">Select options to check availability</span>
                        </div>
                    </div>

                    {{-- Dynamic Option Selectors --}}
                    <template x-if="hasOptions">
                        <div class="space-y-5 mb-6">
                            <template x-for="(option, optIndex) in options" :key="optIndex">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <span x-text="option.name"></span>:
                                        <span class="font-normal text-gray-500" x-text="selections['option' + (optIndex + 1)] || 'Select'"></span>
                                    </label>
                                    <div class="flex gap-2 flex-wrap">
                                        <template x-for="value in option.values" :key="value">
                                            <button type="button"
                                                    @click="selections['option' + (optIndex + 1)] = value"
                                                    :class="selections['option' + (optIndex + 1)] === value ? 'ring-2 ring-accent-500 border-accent-500 bg-accent-50' : 'border-gray-300 hover:border-primary-400'"
                                                    class="px-4 py-2 text-sm border rounded-lg transition-all"
                                                    x-text="value">
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Quantity Selector --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                        <div class="flex items-center border border-gray-300 rounded-lg w-fit overflow-hidden">
                            <button @click="quantity = Math.max(1, quantity - 1)" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                            </button>
                            <input type="number" x-model="quantity" min="1" class="w-14 h-10 text-center border-x border-gray-300 text-sm font-medium focus:outline-none">
                            <button @click="quantity++" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3 mb-6">
                        <form action="{{ url('/cart/add') }}" method="POST" class="flex-1" id="main-add-to-cart">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id ?? 1 }}">
                            <input type="hidden" name="quantity" :value="quantity">
                            <input type="hidden" name="variant_id" :value="variantId">
                            <button type="submit"
                                    :disabled="!canAddToCart"
                                    :class="canAddToCart ? 'btn-gradient-orange' : 'bg-gray-300 cursor-not-allowed text-gray-500'"
                                    class="w-full text-white font-heading font-semibold py-3.5 px-6 rounded-xl flex items-center justify-center gap-2 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                                <span x-text="needsSelection ? 'Select Options' : (canAddToCart ? 'Add to Cart' : 'Out of Stock')">Add to Cart</span>
                            </button>
                        </form>
                        <form action="{{ url('/cart/add') }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id ?? 1 }}">
                            <input type="hidden" name="quantity" :value="quantity">
                            <input type="hidden" name="variant_id" :value="variantId">
                            <input type="hidden" name="redirect_to" value="checkout">
                            <button type="submit"
                                    :disabled="!canAddToCart"
                                    :class="canAddToCart ? 'bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 hover:shadow-xl' : 'bg-gray-200 cursor-not-allowed text-gray-400'"
                                    class="w-full text-yellow-900 font-heading font-semibold py-3.5 px-6 rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Buy Now
                            </button>
                        </form>
                    </div>

                    {{-- Wishlist & Share --}}
                    <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                        <form action="{{ url('/wishlist/toggle') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id ?? 1 }}">
                            <button type="submit" class="flex items-center gap-2 text-sm text-gray-600 hover:text-red-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                Add to Wishlist
                            </button>
                        </form>
                        <button @click="
                            if (navigator.share) {
                                navigator.share({ title: '{{ addslashes($product->name) }}', url: window.location.href });
                            } else {
                                navigator.clipboard.writeText(window.location.href).then(() => {
                                    const toast = document.createElement('div');
                                    toast.className = 'fixed bottom-6 right-6 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg z-[100] text-sm';
                                    toast.textContent = 'Link copied to clipboard!';
                                    document.body.appendChild(toast);
                                    setTimeout(() => toast.remove(), 2000);
                                });
                            }
                        " class="flex items-center gap-2 text-sm text-gray-600 hover:text-primary-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                            Share
                        </button>
                    </div>

                    {{-- Delivery Info --}}
                    <div class="mt-6 space-y-3 bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                            <span class="text-sm text-gray-600"><strong>Free Delivery</strong> on orders over &#8377;499</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            <span class="text-sm text-gray-600"><strong>30-Day Returns</strong> - Hassle-free refunds</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <span class="text-sm text-gray-600"><strong>Secure Payment</strong> - 100% protected</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Product Detail Tabs --}}
            <div class="border-t border-gray-100">
                {{-- Tab Headers --}}
                <div class="flex border-b border-gray-100 overflow-x-auto scrollbar-hide">
                    <button @click="activeTab = 'description'"
                            :class="activeTab === 'description' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="px-6 py-4 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">
                        Description
                    </button>
                    <button @click="activeTab = 'specifications'"
                            :class="activeTab === 'specifications' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="px-6 py-4 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">
                        Specifications
                    </button>
                    <button @click="activeTab = 'reviews'" id="reviews"
                            :class="activeTab === 'reviews' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="px-6 py-4 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">
                        Reviews ({{ $product->review_count ?? 128 }})
                    </button>
                </div>

                {{-- Tab Content --}}
                <div class="p-6 md:p-8">
                    {{-- Description Tab --}}
                    <div x-show="activeTab === 'description'">
                        <div class="prose prose-sm max-w-none text-gray-600">
                            @if(isset($product) && $product->description)
                                {!! $product->description !!}
                            @else
                                <h3>Product Description</h3>
                                <p>Experience premium quality with this carefully crafted product. Designed with attention to detail, it combines functionality with modern aesthetics to deliver an exceptional user experience.</p>
                                <h4>Key Features</h4>
                                <ul>
                                    <li>Premium materials ensure long-lasting durability</li>
                                    <li>Ergonomic design for maximum comfort</li>
                                    <li>Advanced technology for superior performance</li>
                                    <li>Versatile - suitable for multiple use cases</li>
                                    <li>Eco-friendly and sustainably sourced materials</li>
                                </ul>
                                <h4>What's in the Box</h4>
                                <ul>
                                    <li>1x Main Product</li>
                                    <li>1x User Manual</li>
                                    <li>1x Carrying Case</li>
                                    <li>1x Warranty Card</li>
                                </ul>
                            @endif
                        </div>
                    </div>

                    {{-- Specifications Tab --}}
                    <div x-show="activeTab === 'specifications'" x-cloak>
                        <div class="max-w-2xl">
                            <table class="w-full text-sm">
                                <tbody class="divide-y divide-gray-100">
                                    @if(isset($product) && $product->custom_attributes)
                                        @foreach($product->custom_attributes as $key => $value)
                                            <tr>
                                                <td class="py-3 pr-4 font-medium text-gray-500 w-1/3">{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                <td class="py-3 text-gray-800">{{ $value }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                        <td class="py-3 pr-4 font-medium text-gray-500 w-1/3">SKU</td>
                                        <td class="py-3 text-gray-800">{{ $product->sku ?? 'ZK-PRD-001' }}</td>
                                    </tr>
                                    @if(isset($product) && $product->weight)
                                        <tr>
                                            <td class="py-3 pr-4 font-medium text-gray-500">Weight</td>
                                            <td class="py-3 text-gray-800">{{ $product->weight }} {{ $product->weight_unit }}</td>
                                        </tr>
                                    @else
                                        <tr><td class="py-3 pr-4 font-medium text-gray-500">Weight</td><td class="py-3 text-gray-800">0.5 kg</td></tr>
                                    @endif
                                    @if(isset($product) && ($product->length || $product->width || $product->height))
                                        <tr>
                                            <td class="py-3 pr-4 font-medium text-gray-500">Dimensions</td>
                                            <td class="py-3 text-gray-800">{{ $product->length }} x {{ $product->width }} x {{ $product->height }} cm</td>
                                        </tr>
                                    @else
                                        <tr><td class="py-3 pr-4 font-medium text-gray-500">Dimensions</td><td class="py-3 text-gray-800">25 x 15 x 8 cm</td></tr>
                                    @endif
                                    <tr><td class="py-3 pr-4 font-medium text-gray-500">Category</td><td class="py-3 text-gray-800">{{ $product->category->name ?? 'Electronics' }}</td></tr>
                                    <tr><td class="py-3 pr-4 font-medium text-gray-500">Brand</td><td class="py-3 text-gray-800">{{ $product->brand->name ?? 'Premium Brand' }}</td></tr>
                                    <tr><td class="py-3 pr-4 font-medium text-gray-500">Material</td><td class="py-3 text-gray-800">Premium Quality Materials</td></tr>
                                    <tr><td class="py-3 pr-4 font-medium text-gray-500">Warranty</td><td class="py-3 text-gray-800">1 Year Manufacturer Warranty</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Reviews Tab --}}
                    <div x-show="activeTab === 'reviews'" x-cloak x-data="{ showReviewForm: false }">
                        {{-- Write a Review Button --}}
                        <div class="flex justify-end mb-6">
                            @auth
                                <button @click="showReviewForm = !showReviewForm" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Write a Review
                                </button>
                            @else
                                <a href="{{ url('/login') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 text-sm font-medium">
                                    Sign in to write a review
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            @endauth
                        </div>

                        {{-- Review Form Modal --}}
                        @auth
                        <div x-show="showReviewForm" x-cloak x-transition class="mb-8 bg-gray-50 rounded-xl p-6 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Write Your Review</h3>
                            <form action="{{ route('reviews.store', $product->slug) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating *</label>
                                    <div class="flex gap-1" x-data="{ hoverRating: 0, selectedRating: 0 }">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button"
                                                    @mouseenter="hoverRating = {{ $i }}"
                                                    @mouseleave="hoverRating = 0"
                                                    @click="selectedRating = {{ $i }}; document.getElementById('rating-input').value = {{ $i }}"
                                                    :class="(hoverRating >= {{ $i }} || selectedRating >= {{ $i }}) ? 'text-yellow-400' : 'text-gray-300'"
                                                    class="transition-colors">
                                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            </button>
                                        @endfor
                                        <input type="hidden" name="rating" id="rating-input" value="{{ old('rating') }}">
                                    </div>
                                    @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="review-title" class="block text-sm font-medium text-gray-700 mb-1">Title (optional)</label>
                                    <input type="text" name="title" id="review-title" value="{{ old('title') }}" placeholder="Sum up your review" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="review-body" class="block text-sm font-medium text-gray-700 mb-1">Your Review *</label>
                                    <textarea name="body" id="review-body" rows="4" placeholder="What did you like or dislike about this product?" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">{{ old('body') }}</textarea>
                                    @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="flex gap-3">
                                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">Submit Review</button>
                                    <button type="button" @click="showReviewForm = false" class="text-gray-600 hover:text-gray-800 text-sm font-medium px-4 py-2.5">Cancel</button>
                                </div>
                            </form>
                        </div>
                        @endauth

                        {{-- Review Summary --}}
                        <div class="flex flex-col md:flex-row gap-8 mb-8 pb-8 border-b border-gray-100">
                            <div class="text-center md:w-48 flex-shrink-0">
                                <div class="text-5xl font-bold text-gray-900">{{ isset($product) ? number_format($product->average_rating, 1) : '4.2' }}</div>
                                <div class="flex items-center justify-center gap-0.5 mt-2">
                                    @for($s = 0; $s < 5; $s++)
                                        <svg class="w-5 h-5 {{ $s < 4 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    @endfor
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Based on {{ $product->review_count ?? 128 }} reviews</p>
                            </div>
                            <div class="flex-1 space-y-2">
                                @php
                                    $totalReviews = $product->approvedReviews->count();
                                    $ratingDist = [];
                                    for ($i = 5; $i >= 1; $i--) {
                                        $count = $product->approvedReviews->where('rating', $i)->count();
                                        $ratingDist[] = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
                                    }
                                @endphp
                                @for($r = 5; $r >= 1; $r--)
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm text-gray-600 w-6">{{ $r }}</span>
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <div class="flex-1 h-2.5 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-yellow-400 rounded-full" style="width: {{ $ratingDist[5 - $r] }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-500 w-10">{{ $ratingDist[5 - $r] }}%</span>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        {{-- Individual Reviews --}}
                        <div class="space-y-6">
                            @forelse($product->approvedReviews ?? [] as $review)
                                <div class="border-b border-gray-100 pb-6">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 font-semibold text-sm">{{ substr($review->user->name, 0, 1) }}</div>
                                                <span class="text-sm font-medium text-gray-800">{{ $review->user->name }}</span>
                                                <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="flex items-center gap-0.5 mb-2">
                                                @for($s = 0; $s < 5; $s++)
                                                    <svg class="w-4 h-4 {{ $s < $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    @if($review->title)
                                        <h4 class="text-sm font-semibold text-gray-800 mb-1">{{ $review->title }}</h4>
                                    @endif
                                    <p class="text-sm text-gray-600">{{ $review->body }}</p>
                                </div>
                            @empty
                                <div class="text-center py-10">
                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    <h3 class="text-lg font-semibold text-gray-700 mb-1">No reviews yet</h3>
                                    <p class="text-sm text-gray-500">Be the first to share your thoughts on this product.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sticky Add-to-Cart Bar --}}
        <div x-data="{ showBar: false }" x-init="
            const target = document.getElementById('main-add-to-cart');
            if (target) {
                const observer = new IntersectionObserver(([entry]) => { showBar = !entry.isIntersecting; }, { threshold: 0 });
                observer.observe(target);
            }
        " x-show="showBar" x-cloak
             x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
             class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 shadow-[0_-4px_12px_rgba(0,0,0,0.1)] py-3">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    @if(isset($product) && $product->images && $product->images->count() > 0)
                        <img src="{{ image_url($product->images->first()->path) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                    @endif
                    <div class="min-w-0">
                        <h4 class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</h4>
                        <span class="text-lg font-bold text-primary-700">{{ format_currency($product->price) }}</span>
                    </div>
                </div>
                <form action="{{ url('/cart/add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-2.5 rounded-lg transition-colors flex items-center gap-2 text-sm whitespace-nowrap btn-hover-lift">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                        Add to Cart
                    </button>
                </form>
            </div>
        </div>

        {{-- Related Products --}}
        <section class="mt-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Related Products</h2>
                <a href="{{ url('/products' . (isset($product) && $product->category ? '?category=' . $product->category->slug : '')) }}" class="text-sm font-medium text-primary-600 hover:text-primary-800">
                    View More &rarr;
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @forelse($relatedProducts ?? [] as $related)
                    @include('components.product-card', ['product' => $related])
                @empty
                    <div class="col-span-full text-center py-8 text-gray-500">
                        <p class="text-sm">No related products found.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

@endsection