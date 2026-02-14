@extends('layouts.app')

@section('title', 'Shopping Cart - PickKart')
@section('meta_description', 'Review items in your shopping cart. Update quantities, apply coupons, and proceed to checkout.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-800 font-medium">Shopping Cart</span>
    </nav>

    {{-- Checkout Progress Steps --}}
    <div class="flex items-center justify-center mb-8">
        <div class="flex items-center">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-primary-600 text-white">1</div>
                <span class="ml-2 text-sm font-medium text-primary-700">Cart</span>
            </div>
            <div class="w-12 sm:w-20 h-0.5 mx-2 sm:mx-4 bg-gray-200"></div>
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-200 text-gray-500">2</div>
                <span class="ml-2 text-sm font-medium text-gray-500">Checkout</span>
            </div>
            <div class="w-12 sm:w-20 h-0.5 mx-2 sm:mx-4 bg-gray-200"></div>
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-200 text-gray-500">3</div>
                <span class="ml-2 text-sm font-medium text-gray-500">Confirmation</span>
            </div>
        </div>
    </div>

    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

    @if(isset($cart) && $cart->items->count() > 0)
        <div class="lg:grid lg:grid-cols-3 lg:gap-8">

            {{-- Cart Items (Left Column) --}}
            <div class="lg:col-span-2">

                {{-- Desktop Table View --}}
                <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-4">Product</th>
                                <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-4">Price</th>
                                <th class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-4">Quantity</th>
                                <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-4">Total</th>
                                <th class="px-4 py-4"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($cart->items as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    {{-- Product Info --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <img
                                                src="{{ image_url(optional($item->product->primaryImage)->path) }}"
                                                alt="{{ $item->product->name }}"
                                                class="w-20 h-20 object-cover rounded-lg border border-gray-200 flex-shrink-0"
                                            >
                                            <div>
                                                <a href="{{ route('products.show', $item->product->slug) }}" class="text-sm font-medium text-gray-800 hover:text-primary-600 transition-colors line-clamp-2">
                                                    {{ $item->product->name }}
                                                </a>
                                                @if($item->variant)
                                                    <p class="text-xs text-gray-500 mt-1">Variant: {{ $item->variant->name }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Unit Price --}}
                                    <td class="px-4 py-4 text-center">
                                        <span class="text-sm font-medium text-gray-700">{{ format_currency($item->unit_price) }}</span>
                                    </td>

                                    {{-- Quantity --}}
                                    <td class="px-4 py-4">
                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center justify-center">
                                            @csrf
                                            @method('PATCH')
                                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                                <button
                                                    type="button"
                                                    onclick="this.nextElementSibling.stepDown(); this.closest('form').submit();"
                                                    class="px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-600 transition-colors"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                                </button>
                                                <input
                                                    type="number"
                                                    name="quantity"
                                                    value="{{ $item->quantity }}"
                                                    min="1"
                                                    max="99"
                                                    class="w-14 text-center text-sm font-medium border-0 focus:ring-0 py-2"
                                                    onchange="this.closest('form').submit();"
                                                >
                                                <button
                                                    type="button"
                                                    onclick="this.previousElementSibling.stepUp(); this.closest('form').submit();"
                                                    class="px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-600 transition-colors"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                </button>
                                            </div>
                                        </form>
                                    </td>

                                    {{-- Line Total --}}
                                    <td class="px-4 py-4 text-right">
                                        <span class="text-sm font-bold text-gray-900">{{ format_currency($item->unit_price * $item->quantity) }}</span>
                                    </td>

                                    {{-- Remove --}}
                                    <td class="px-4 py-4 text-right">
                                        <form action="{{ route('cart.remove', $item) }}" method="POST" onsubmit="return confirm('Remove this item from your cart?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="Remove item">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View --}}
                <div class="md:hidden space-y-4">
                    @foreach($cart->items as $item)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                            <div class="flex gap-4">
                                <img
                                    src="{{ image_url(optional($item->product->primaryImage)->path) }}"
                                    alt="{{ $item->product->name }}"
                                    class="w-24 h-24 object-cover rounded-lg border border-gray-200 flex-shrink-0"
                                >
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('products.show', $item->product->slug) }}" class="text-sm font-medium text-gray-800 hover:text-primary-600 transition-colors line-clamp-2">
                                        {{ $item->product->name }}
                                    </a>
                                    @if($item->variant)
                                        <p class="text-xs text-gray-500 mt-1">Variant: {{ $item->variant->name }}</p>
                                    @endif
                                    <p class="text-sm font-medium text-gray-700 mt-1">{{ format_currency($item->unit_price) }}</p>
                                </div>
                                <form action="{{ route('cart.remove', $item) }}" method="POST" onsubmit="return confirm('Remove this item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-gray-400 hover:text-red-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </form>
                            </div>
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                                <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                        <button
                                            type="button"
                                            onclick="this.nextElementSibling.stepDown(); this.closest('form').submit();"
                                            class="px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-600 transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                        </button>
                                        <input
                                            type="number"
                                            name="quantity"
                                            value="{{ $item->quantity }}"
                                            min="1"
                                            max="99"
                                            class="w-12 text-center text-sm font-medium border-0 focus:ring-0 py-1.5"
                                            onchange="this.closest('form').submit();"
                                        >
                                        <button
                                            type="button"
                                            onclick="this.previousElementSibling.stepUp(); this.closest('form').submit();"
                                            class="px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-600 transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        </button>
                                    </div>
                                </form>
                                <span class="text-base font-bold text-gray-900">{{ format_currency($item->unit_price * $item->quantity) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Continue Shopping Link --}}
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Continue Shopping
                    </a>
                </div>
            </div>

            {{-- Cart Summary Sidebar (Right Column) --}}
            <div class="mt-8 lg:mt-0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <h2 class="text-lg font-bold text-gray-900 mb-6">Order Summary</h2>

                    <div class="space-y-4">
                        {{-- Subtotal --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Subtotal</span>
                            <span class="text-sm font-medium text-gray-800">{{ format_currency($cart->subtotal) }}</span>
                        </div>

                        {{-- Discount --}}
                        @if($cart->discount_amount > 0)
                            <div class="flex items-center justify-between text-green-600">
                                <span class="text-sm">Discount</span>
                                <span class="text-sm font-medium">-{{ format_currency($cart->discount_amount) }}</span>
                            </div>
                        @endif

                        {{-- Shipping Estimate --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Shipping</span>
                            <span class="text-sm text-gray-500">Calculated at checkout</span>
                        </div>

                        {{-- Coupon Code Form --}}
                        <div class="pt-4 border-t border-gray-100">
                            <form action="{{ route('cart.coupon') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input
                                    type="text"
                                    name="coupon_code"
                                    placeholder="Coupon code"
                                    value="{{ old('coupon_code') }}"
                                    class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all"
                                >
                                <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors whitespace-nowrap">
                                    Apply
                                </button>
                            </form>
                            @error('coupon_code')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Total --}}
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-base font-bold text-gray-900">Total</span>
                                <span class="text-xl font-bold text-gray-900">
                                    {{ format_currency($cart->subtotal - ($cart->discount_amount ?? 0)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Checkout Button --}}
                    <a href="{{ route('checkout.index') }}" class="mt-6 w-full btn-gradient-orange text-sm font-heading font-bold py-3 rounded-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Proceed to Checkout
                    </a>

                    {{-- Security Note --}}
                    <div class="mt-4 flex items-center gap-2 text-xs text-gray-400">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <span>Secure checkout with SSL encryption</span>
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- Empty Cart State --}}
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Your cart is empty</h2>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">Looks like you haven't added any products to your cart yet. Browse our store and find something you love!</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 btn-gradient-orange font-heading font-semibold px-6 py-3 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Shop Now
            </a>
        </div>
    @endif

</div>
@endsection
