@extends('layouts.app')

@section('title', 'My Wishlist - PickKart')
@section('meta_description', 'View and manage your saved products. Add items to your cart or remove them from your wishlist.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-800 font-medium">My Wishlist</span>
    </nav>

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl md:text-3xl font-heading font-bold text-gray-900">My Wishlist</h1>
        @if($wishlists->total() > 0)
            <span class="text-sm text-gray-500">{{ $wishlists->total() }} {{ Str::plural('item', $wishlists->total()) }}</span>
        @endif
    </div>

    @if($wishlists->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach($wishlists as $wishlist)
                @php $product = $wishlist->product; @endphp

                @if($product)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="relative">
                            {{-- Product Image --}}
                            <a href="{{ route('products.show', $product->slug) }}">
                                <img
                                    src="{{ image_url(optional($product->primaryImage)->path) }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                                    loading="lazy"
                                >
                            </a>

                            {{-- Discount Badge --}}
                            @if($product->compare_at_price && $product->compare_at_price > $product->price)
                                @php $discountPercent = round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100); @endphp
                                <span class="absolute top-2 left-2 bg-gradient-to-br from-red-500 to-red-600 text-white text-xs font-heading font-bold px-2 py-1 rounded-lg badge-pulse shadow-md">
                                    -{{ $discountPercent }}%
                                </span>
                            @endif

                            {{-- Remove from Wishlist --}}
                            <form action="{{ route('wishlist.toggle') }}" method="POST" class="absolute top-2 right-2">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="w-8 h-8 bg-white/90 rounded-full flex items-center justify-center text-red-500 hover:text-red-600 hover:bg-white transition-colors shadow-sm" title="Remove from Wishlist">
                                    <svg class="w-4 h-4" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <div class="p-3">
                            {{-- Category --}}
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
                                <span class="text-base font-bold text-gray-900">{{ format_currency($product->price) }}</span>
                                @if($product->compare_at_price && $product->compare_at_price > $product->price)
                                    <span class="text-xs text-gray-400 line-through">{{ format_currency($product->compare_at_price) }}</span>
                                @endif
                            </div>

                            {{-- Add to Cart --}}
                            <form action="{{ route('cart.add') }}" method="POST" class="mb-2">
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

                            {{-- Remove Button (Text link) --}}
                            <form action="{{ route('wishlist.toggle') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="w-full text-xs text-gray-400 hover:text-red-500 transition-colors py-1">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $wishlists->links() }}
        </div>

    @else
        {{-- Empty Wishlist State --}}
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-heading font-bold text-gray-800 mb-2">Your wishlist is empty</h2>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">
                You haven't added any products to your wishlist yet. Browse our store and save items you love!
            </p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 btn-gradient-orange font-heading font-semibold px-6 py-3 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Explore Products
            </a>
        </div>
    @endif

</div>
@endsection
