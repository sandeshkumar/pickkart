@extends('layouts.app')

@section('title', 'Order Confirmed #' . $order->order_number . ' - PickKart')
@section('meta_description', 'Your order has been placed successfully. View your order details and tracking information.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Checkout Progress Steps --}}
    <div class="flex items-center justify-center mb-10">
        <div class="flex items-center">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-primary-600 text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="ml-2 text-sm font-medium text-primary-700">Cart</span>
            </div>
            <div class="w-12 sm:w-20 h-0.5 mx-2 sm:mx-4 bg-primary-600"></div>
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-primary-600 text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="ml-2 text-sm font-medium text-primary-700">Checkout</span>
            </div>
            <div class="w-12 sm:w-20 h-0.5 mx-2 sm:mx-4 bg-primary-600"></div>
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-green-500 text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="ml-2 text-sm font-medium text-green-600">Confirmed</span>
            </div>
        </div>
    </div>

    {{-- Success Header --}}
    <div class="text-center mb-10">
        {{-- Animated Checkmark --}}
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="text-2xl md:text-3xl font-heading font-bold text-gray-900 mb-2">Order Confirmed!</h1>
        <p class="text-gray-500">Thank you for your purchase. Your order has been placed successfully.</p>
        <p class="text-sm text-gray-500 mt-2">
            Order Number: <span class="font-semibold text-primary-600">{{ $order->order_number }}</span>
        </p>
    </div>

    {{-- Order Details Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">

        {{-- Order Items Table --}}
        <div class="p-6">
            <h2 class="text-lg font-heading font-bold text-gray-900 mb-4">Order Items</h2>

            {{-- Desktop Table --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left font-semibold text-gray-500 uppercase tracking-wider text-xs py-3 pr-4">Product</th>
                            <th class="text-center font-semibold text-gray-500 uppercase tracking-wider text-xs py-3 px-4">Quantity</th>
                            <th class="text-right font-semibold text-gray-500 uppercase tracking-wider text-xs py-3 px-4">Unit Price</th>
                            <th class="text-right font-semibold text-gray-500 uppercase tracking-wider text-xs py-3 pl-4">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="py-4 pr-4">
                                    <span class="font-medium text-gray-800">{{ $item->product_name }}</span>
                                    @if($item->variant_name)
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $item->variant_name }}</p>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center text-gray-600">{{ $item->quantity }}</td>
                                <td class="py-4 px-4 text-right text-gray-600">{{ format_currency($item->unit_price) }}</td>
                                <td class="py-4 pl-4 text-right font-semibold text-gray-800">{{ format_currency($item->total) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View --}}
            <div class="sm:hidden space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $item->product_name }}</p>
                            @if($item->variant_name)
                                <p class="text-xs text-gray-500">{{ $item->variant_name }}</p>
                            @endif
                            <p class="text-xs text-gray-500">Qty: {{ $item->quantity }} x {{ format_currency($item->unit_price) }}</p>
                        </div>
                        <span class="text-sm font-semibold text-gray-800">{{ format_currency($item->total) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Totals --}}
        <div class="bg-gray-50 px-6 py-4">
            <div class="max-w-xs ml-auto space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium text-gray-800">{{ format_currency($order->subtotal) }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Shipping</span>
                    @if($order->shipping_amount > 0)
                        <span class="font-medium text-gray-800">{{ format_currency($order->shipping_amount) }}</span>
                    @else
                        <span class="font-medium text-green-600">Free</span>
                    @endif
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Tax</span>
                    <span class="font-medium text-gray-800">{{ format_currency($order->tax_amount) }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="flex items-center justify-between text-sm text-green-600">
                        <span>Discount</span>
                        <span class="font-medium">-{{ format_currency($order->discount_amount) }}</span>
                    </div>
                @endif
                <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                    <span class="text-base font-bold text-gray-900">Total</span>
                    <span class="text-lg font-bold text-gray-900">{{ format_currency($order->total) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Shipping & Payment Info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        {{-- Shipping Address --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Shipping Address</h3>
            @if($order->shippingAddress)
                <div class="text-sm text-gray-700 space-y-1">
                    <p class="font-semibold text-gray-800">{{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</p>
                    <p>{{ $order->shippingAddress->address_line_1 }}</p>
                    @if($order->shippingAddress->address_line_2)
                        <p>{{ $order->shippingAddress->address_line_2 }}</p>
                    @endif
                    <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}</p>
                    <p>{{ $order->shippingAddress->country }}</p>
                    @if($order->shippingAddress->phone)
                        <p class="text-gray-500 mt-2">{{ $order->shippingAddress->phone }}</p>
                    @endif
                    @if($order->shippingAddress->email)
                        <p class="text-gray-500">{{ $order->shippingAddress->email }}</p>
                    @endif
                </div>
            @else
                <p class="text-sm text-gray-500">Address information unavailable.</p>
            @endif
        </div>

        {{-- Payment Method --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Payment Method</h3>
            <div class="flex items-center gap-3">
                @if($order->payment_method === 'cod')
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Cash on Delivery</p>
                        <p class="text-xs text-gray-500">Pay when your order arrives</p>
                    </div>
                @elseif($order->payment_method === 'stripe')
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Credit Card (Stripe)</p>
                        <p class="text-xs text-gray-500">Payment processed securely</p>
                    </div>
                @elseif($order->payment_method === 'razorpay')
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Razorpay</p>
                        <p class="text-xs text-gray-500">Payment processed securely</p>
                    </div>
                @else
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ ucfirst($order->payment_method) }}</p>
                    </div>
                @endif
            </div>

            {{-- Payment Status --}}
            <div class="mt-4 pt-4 border-t border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-gray-500 uppercase">Status:</span>
                    @if($order->payment_status === 'paid')
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-100 px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                            Paid
                        </span>
                    @elseif($order->payment_status === 'pending')
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-700 bg-yellow-100 px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span>
                            Pending
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-gray-500 rounded-full"></span>
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Confirmation Note --}}
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 mb-8">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            <div>
                <p class="text-sm font-semibold text-blue-800">A confirmation email has been sent</p>
                <p class="text-sm text-blue-600 mt-0.5">We've sent the order details and tracking information to your registered email address.</p>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 btn-gradient-orange font-heading font-semibold px-6 py-3 rounded-xl shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            Continue Shopping
        </a>
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-primary-600 border border-gray-200 hover:border-primary-300 px-6 py-3 rounded-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Back to Home
        </a>
    </div>
</div>
@endsection
