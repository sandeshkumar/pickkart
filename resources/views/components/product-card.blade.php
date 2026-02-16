{{-- Product Card Component --}}
{{-- Receives: $product --}}

@php
    $image = image_url(optional($product->primaryImage)->path);
    $hasDiscount = $product->compare_at_price && $product->compare_at_price > $product->price;
    $discountPercent = $hasDiscount ? round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100) : 0;
    $hasVariants = $product->has_active_variants ?? $product->hasVariants();
    $isWishlisted = auth()->check() && auth()->user()->wishlists()->where('product_id', $product->id)->exists();
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-xl transition-all duration-300 hover:-translate-y-2 hover:border-primary-200 flex flex-col h-full">
    <div class="relative">
        {{-- Product Image --}}
        <a href="{{ route('products.show', $product->slug) }}" class="block product-image-zoom">
            <img
                src="{{ $image }}"
                alt="{{ $product->name }}"
                class="w-full h-48 object-cover"
                loading="lazy"
            >
        </a>

        {{-- Discount Badge --}}
        @if($hasDiscount)
            <span class="absolute top-2 left-2 bg-gradient-to-br from-red-500 to-red-600 text-white text-xs font-heading font-bold px-2 py-1 rounded-lg shadow-md badge-pulse">
                -{{ $discountPercent }}%
            </span>
        @endif

        {{-- Wishlist Button --}}
        @auth
            <form action="{{ route('wishlist.toggle') }}" method="POST" class="absolute top-2 right-2">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="w-8 h-8 bg-white/90 rounded-full flex items-center justify-center {{ $isWishlisted ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }} transition-all duration-200 shadow-sm hover:scale-110 active:scale-90" title="{{ $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                    <svg class="w-4 h-4" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition-all duration-200 shadow-sm hover:scale-110" title="Login to add to Wishlist">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </a>
        @endauth
    </div>

    <div class="p-3 flex flex-col flex-1">
        {{-- Category Name --}}
        @if($product->category)
            <p class="text-xs text-primary-600 font-medium mb-1">{{ $product->category->name }}</p>
        @endif

        {{-- Product Name --}}
        <a href="{{ route('products.show', $product->slug) }}">
            <h3 class="text-sm font-heading font-medium text-gray-800 line-clamp-2 mb-1 hover:text-primary-600 transition-colors">
                {{ $product->name }}
            </h3>
        </a>

        {{-- Price --}}
        <div class="flex items-center gap-2 mb-3">
            <span class="text-base font-heading font-bold text-gray-900">{{ format_currency($product->price) }}</span>
            @if($hasDiscount)
                <span class="text-xs text-gray-400 line-through">{{ format_currency($product->compare_at_price) }}</span>
            @endif
        </div>

        {{-- Add to Cart / View Options --}}
        <div class="mt-auto">
        @if($hasVariants)
            <a href="{{ route('products.show', $product->slug) }}" class="w-full btn-gradient-blue text-xs font-heading font-semibold py-2 rounded-lg flex items-center justify-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
                View Options
            </a>
        @else
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="w-full btn-gradient-orange text-xs font-heading font-semibold py-2 rounded-lg flex items-center justify-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                    </svg>
                    Add to Cart
                </button>
            </form>
        @endif
        </div>
    </div>
</div>
