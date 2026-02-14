@extends('layouts.app')

@section('title', 'Checkout - PickKart')
@section('meta_description', 'Complete your order securely. Enter shipping details and choose your preferred payment method.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <a href="{{ route('cart.index') }}" class="hover:text-primary-600 transition-colors">Cart</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-800 font-medium">Checkout</span>
    </nav>

    {{-- Checkout Progress Steps --}}
    <div class="flex items-center justify-center mb-8">
        <div class="flex items-center">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-primary-600 text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="ml-2 text-sm font-medium text-primary-700">Cart</span>
            </div>
            <div class="w-12 sm:w-20 h-0.5 mx-2 sm:mx-4 bg-primary-600"></div>
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-primary-600 text-white">2</div>
                <span class="ml-2 text-sm font-medium text-primary-700">Checkout</span>
            </div>
            <div class="w-12 sm:w-20 h-0.5 mx-2 sm:mx-4 bg-gray-200"></div>
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-gray-200 text-gray-500">3</div>
                <span class="ml-2 text-sm font-medium text-gray-500">Confirmation</span>
            </div>
        </div>
    </div>

    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

    <form action="{{ route('checkout.store') }}" method="POST" x-data="{
        paymentMethod: '{{ old('payment_method', 'cod') }}',
        selectedAddress: '{{ old('address_id', '') }}',
        useNewAddress: {{ old('address_id') ? 'false' : 'true' }}
    }">
        @csrf

        <div class="lg:grid lg:grid-cols-3 lg:gap-8">

            {{-- Left Column: Shipping & Payment --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- Saved Addresses --}}
                @if(isset($addresses) && $addresses->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Saved Addresses</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            @foreach($addresses as $address)
                                <div
                                    @click="selectedAddress = '{{ $address->id }}'; useNewAddress = false"
                                    :class="selectedAddress == '{{ $address->id }}' && !useNewAddress ? 'border-accent-500 ring-2 ring-accent-200 bg-accent-50' : 'border-gray-200 hover:border-gray-300'"
                                    class="border-2 rounded-xl p-4 cursor-pointer transition-all"
                                >
                                    <input type="radio" name="address_id" value="{{ $address->id }}"
                                           x-model="selectedAddress"
                                           @click="useNewAddress = false"
                                           class="hidden">
                                    <p class="text-sm font-semibold text-gray-800">{{ $address->first_name }} {{ $address->last_name }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $address->address_line_1 }}</p>
                                    @if($address->address_line_2)
                                        <p class="text-sm text-gray-600">{{ $address->address_line_2 }}</p>
                                    @endif
                                    <p class="text-sm text-gray-600">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                    <p class="text-sm text-gray-600">{{ $address->country }}</p>
                                    @if($address->phone)
                                        <p class="text-xs text-gray-400 mt-1">{{ $address->phone }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <button
                            type="button"
                            @click="useNewAddress = true; selectedAddress = ''"
                            :class="useNewAddress ? 'text-primary-700 bg-primary-50 border-primary-300' : 'text-gray-600 border-gray-200 hover:border-gray-300'"
                            class="inline-flex items-center gap-2 text-sm font-medium border rounded-lg px-4 py-2 transition-all"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Use a new address
                        </button>
                    </div>
                @endif

                {{-- Shipping Address Form --}}
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-6"
                    x-show="useNewAddress"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                >
                    <h2 class="text-lg font-bold text-gray-900 mb-6">Shipping Address</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- First Name --}}
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                id="first_name"
                                name="first_name"
                                value="{{ old('first_name', auth()->user()->first_name ?? '') }}"
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all text-sm"
                                placeholder="John"
                            >
                            @error('first_name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Last Name --}}
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                id="last_name"
                                name="last_name"
                                value="{{ old('last_name', auth()->user()->last_name ?? '') }}"
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all text-sm"
                                placeholder="Doe"
                            >
                            @error('last_name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', auth()->user()->email ?? '') }}"
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all text-sm"
                                placeholder="john@example.com"
                            >
                            @error('email')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all text-sm"
                                placeholder="+1 (555) 123-4567"
                            >
                            @error('phone')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Address Line 1 --}}
                        <div class="sm:col-span-2">
                            <label for="address_line_1" class="block text-sm font-medium text-gray-700 mb-1">Address Line 1 <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                id="address_line_1"
                                name="address_line_1"
                                value="{{ old('address_line_1') }}"
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all text-sm"
                                placeholder="123 Main Street"
                            >
                            @error('address_line_1')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Address Line 2 --}}
                        <div class="sm:col-span-2">
                            <label for="address_line_2" class="block text-sm font-medium text-gray-700 mb-1">Address Line 2 <span class="text-gray-400">(Optional)</span></label>
                            <input
                                type="text"
                                id="address_line_2"
                                name="address_line_2"
                                value="{{ old('address_line_2') }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all text-sm"
                                placeholder="Apt, suite, unit, etc."
                            >
                            @error('address_line_2')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- City --}}
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                id="city"
                                name="city"
                                value="{{ old('city') }}"
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all text-sm"
                                placeholder="New York"
                            >
                            @error('city')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- State --}}
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State / Province <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                id="state"
                                name="state"
                                value="{{ old('state') }}"
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all text-sm"
                                placeholder="NY"
                            >
                            @error('state')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Postal Code --}}
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                id="postal_code"
                                name="postal_code"
                                value="{{ old('postal_code') }}"
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all text-sm"
                                placeholder="10001"
                            >
                            @error('postal_code')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Country --}}
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country <span class="text-red-500">*</span></label>
                            <select
                                id="country"
                                name="country"
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all text-sm bg-white"
                            >
                                <option value="">Select Country</option>
                                <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>United States</option>
                                <option value="CA" {{ old('country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                <option value="GB" {{ old('country') == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                <option value="AU" {{ old('country') == 'AU' ? 'selected' : '' }}>Australia</option>
                                <option value="DE" {{ old('country') == 'DE' ? 'selected' : '' }}>Germany</option>
                                <option value="FR" {{ old('country') == 'FR' ? 'selected' : '' }}>France</option>
                                <option value="IN" {{ old('country') == 'IN' ? 'selected' : '' }}>India</option>
                                <option value="JP" {{ old('country') == 'JP' ? 'selected' : '' }}>Japan</option>
                                <option value="BR" {{ old('country') == 'BR' ? 'selected' : '' }}>Brazil</option>
                                <option value="MX" {{ old('country') == 'MX' ? 'selected' : '' }}>Mexico</option>
                                <option value="SG" {{ old('country') == 'SG' ? 'selected' : '' }}>Singapore</option>
                                <option value="AE" {{ old('country') == 'AE' ? 'selected' : '' }}>United Arab Emirates</option>
                            </select>
                            @error('country')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6">Payment Method</h2>

                    <div class="space-y-3">
                        {{-- Cash on Delivery --}}
                        <label
                            @click="paymentMethod = 'cod'"
                            :class="paymentMethod === 'cod' ? 'border-accent-500 ring-2 ring-accent-200 bg-accent-50' : 'border-gray-200 hover:border-gray-300'"
                            class="flex items-center gap-4 border-2 rounded-xl p-4 cursor-pointer transition-all"
                        >
                            <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" class="w-4 h-4 text-primary-600 focus:ring-primary-500">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <span class="text-sm font-semibold text-gray-800">Cash on Delivery</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 ml-7">Pay when your order is delivered to your doorstep.</p>
                            </div>
                        </label>

                        {{-- Stripe (Credit Card) — Coming Soon --}}
                        <div class="flex items-center gap-4 border-2 rounded-xl p-4 border-gray-200 opacity-60 cursor-not-allowed">
                            <input type="radio" disabled class="w-4 h-4 text-gray-400">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    <span class="text-sm font-semibold text-gray-500">Credit Card</span>
                                    <span class="ml-auto inline-flex items-center rounded-full bg-amber-50 border border-amber-200 px-2 py-0.5 text-[10px] font-semibold text-amber-700">Coming Soon</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1 ml-7">Pay securely with your credit or debit card via Stripe.</p>
                            </div>
                        </div>

                        {{-- Razorpay — Coming Soon --}}
                        <div class="flex items-center gap-4 border-2 rounded-xl p-4 border-gray-200 opacity-60 cursor-not-allowed">
                            <input type="radio" disabled class="w-4 h-4 text-gray-400">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="text-sm font-semibold text-gray-500">Razorpay</span>
                                    <span class="ml-auto inline-flex items-center rounded-full bg-amber-50 border border-amber-200 px-2 py-0.5 text-[10px] font-semibold text-amber-700">Coming Soon</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1 ml-7">Pay with UPI, Net Banking, Wallets, and more via Razorpay.</p>
                            </div>
                        </div>
                    </div>

                    @error('payment_method')
                        <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Order Notes --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Order Notes <span class="text-sm font-normal text-gray-400">(Optional)</span></h2>
                    <textarea
                        name="notes"
                        rows="3"
                        placeholder="Special delivery instructions, gift messages, or any other notes for your order..."
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all text-sm resize-none"
                    >{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Right Column: Order Summary --}}
            <div class="mt-8 lg:mt-0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <h2 class="text-lg font-bold text-gray-900 mb-6">Order Summary</h2>

                    {{-- Cart Items --}}
                    <div class="space-y-4 mb-6 max-h-80 overflow-y-auto scrollbar-hide">
                        @foreach($cart->items as $item)
                            <div class="flex items-center gap-3">
                                <img
                                    src="{{ image_url(optional($item->product->primaryImage)->path) }}"
                                    alt="{{ $item->product->name }}"
                                    class="w-14 h-14 object-cover rounded-lg border border-gray-200 flex-shrink-0"
                                >
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate">{{ $item->product->name }}</p>
                                    @if($item->variant)
                                        <p class="text-xs text-gray-500">{{ $item->variant->display_name }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                </div>
                                <span class="text-sm font-semibold text-gray-800">{{ format_currency($item->unit_price * $item->quantity) }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Totals Breakdown --}}
                    @php
                        $subtotal = $cart->subtotal;
                        $shipping = $subtotal >= 500 ? 0 : 50;
                        $tax = round($subtotal * 0.18, 2);
                        $discount = $cart->discount_amount ?? 0;
                        $total = $subtotal + $shipping + $tax - $discount;
                    @endphp

                    <div class="border-t border-gray-100 pt-4 space-y-3">
                        {{-- Subtotal --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Subtotal</span>
                            <span class="text-sm font-medium text-gray-800">{{ format_currency($subtotal) }}</span>
                        </div>

                        {{-- Shipping --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Shipping</span>
                            @if($shipping === 0)
                                <span class="text-sm font-medium text-green-600">Free</span>
                            @else
                                <span class="text-sm font-medium text-gray-800">{{ format_currency($shipping) }}</span>
                            @endif
                        </div>

                        {{-- Tax --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Tax (18%)</span>
                            <span class="text-sm font-medium text-gray-800">{{ format_currency($tax) }}</span>
                        </div>

                        {{-- Discount --}}
                        @if($discount > 0)
                            <div class="flex items-center justify-between text-green-600">
                                <span class="text-sm">Discount</span>
                                <span class="text-sm font-medium">-{{ format_currency($discount) }}</span>
                            </div>
                        @endif

                        {{-- Total --}}
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex items-center justify-between">
                                <span class="text-base font-bold text-gray-900">Total</span>
                                <span class="text-xl font-bold text-gray-900">{{ format_currency($total) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Place Order Button --}}
                    <button type="submit" class="mt-6 w-full btn-gradient-orange text-sm font-heading font-bold py-3.5 rounded-xl flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Place Order
                    </button>

                    {{-- Security Note --}}
                    <div class="mt-4 flex items-center gap-2 text-xs text-gray-400">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <span>Your payment information is encrypted and secure.</span>
                    </div>

                    {{-- Free Shipping Notice --}}
                    @if($shipping > 0)
                        <div class="mt-4 bg-yellow-50 border border-yellow-100 rounded-lg p-3">
                            <p class="text-xs text-yellow-700">
                                <strong>Free shipping</strong> on orders over $500.00. Add <strong>{{ format_currency(500 - $subtotal) }}</strong> more to qualify!
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
