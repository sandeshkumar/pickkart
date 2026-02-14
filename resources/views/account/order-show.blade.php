@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' - PickKart')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <a href="{{ route('account.orders') }}" class="hover:text-primary-600 transition-colors">Orders</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-800 font-medium">{{ $order->order_number }}</span>
    </nav>

    {{-- Order Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-heading font-bold text-gray-900">Order {{ $order->order_number }}</h1>
            <p class="text-sm text-gray-500 mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t g:i A') }}</p>
        </div>
        @php
            $statusColors = [
                'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                'processing' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                'shipped' => 'bg-purple-50 text-purple-700 border-purple-200',
                'delivered' => 'bg-green-50 text-green-700 border-green-200',
                'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                'refunded' => 'bg-gray-50 text-gray-700 border-gray-200',
            ];
            $color = $statusColors[$order->status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
        @endphp
        <span class="inline-flex items-center self-start rounded-full border px-3 py-1 text-sm font-semibold {{ $color }}">
            {{ ucfirst($order->status) }}
        </span>
    </div>

    {{-- Order Status Timeline --}}
    @php
        $steps = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
        $currentIndex = array_search($order->status, $steps);
        if ($currentIndex === false) $currentIndex = -1;
    @endphp
    @if(!in_array($order->status, ['cancelled', 'refunded']))
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex items-center justify-between">
                @foreach($steps as $i => $step)
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $i <= $currentIndex ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-500' }}">
                            @if($i < $currentIndex)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <span class="text-xs mt-1.5 font-medium {{ $i <= $currentIndex ? 'text-primary-700' : 'text-gray-400' }}">{{ ucfirst($step) }}</span>
                    </div>
                    @if(!$loop->last)
                        <div class="flex-1 h-0.5 mx-1 {{ $i < $currentIndex ? 'bg-primary-600' : 'bg-gray-200' }}"></div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Left: Order Items --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-heading font-bold text-gray-900 mb-4">Order Items</h2>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4 py-4 first:pt-0 last:pb-0">
                            @if($item->product && $item->product->primaryImage)
                                <img src="{{ image_url($item->product->primaryImage->path) }}" alt="{{ $item->product_name }}" class="w-16 h-16 object-cover rounded-lg border border-gray-200 flex-shrink-0">
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $item->product_name }}</p>
                                @if($item->variant_name)
                                    <p class="text-xs text-gray-500">{{ $item->variant_name }}</p>
                                @endif
                                <p class="text-xs text-gray-500">Qty: {{ $item->quantity }} &times; {{ format_currency($item->unit_price) }}</p>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ format_currency($item->total) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tracking Info --}}
            @if($order->tracking_number)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-heading font-bold text-gray-900 mb-2">Tracking Information</h2>
                    <p class="text-sm text-gray-600">Tracking Number: <span class="font-mono font-semibold text-gray-900">{{ $order->tracking_number }}</span></p>
                    @if($order->shipped_at)
                        <p class="text-xs text-gray-500 mt-1">Shipped on {{ $order->shipped_at->format('M d, Y') }}</p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Right: Summary + Shipping --}}
        <div class="space-y-8">

            {{-- Order Summary --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-heading font-bold text-gray-900 mb-4">Order Summary</h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium text-gray-800">{{ format_currency($order->subtotal) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Shipping</span>
                        @if($order->shipping_amount == 0)
                            <span class="font-medium text-green-600">Free</span>
                        @else
                            <span class="font-medium text-gray-800">{{ format_currency($order->shipping_amount) }}</span>
                        @endif
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tax</span>
                        <span class="font-medium text-gray-800">{{ format_currency($order->tax_amount) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Discount</span>
                            <span class="font-medium">-{{ format_currency($order->discount_amount) }}</span>
                        </div>
                    @endif
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex justify-between">
                            <span class="text-base font-bold text-gray-900">Total</span>
                            <span class="text-lg font-bold text-gray-900">{{ format_currency($order->total) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-heading font-bold text-gray-900 mb-3">Payment</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Method</span>
                        <span class="font-medium text-gray-800">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : ucfirst($order->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status</span>
                        <span class="font-medium {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">{{ ucfirst($order->payment_status) }}</span>
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            @if($order->shippingAddress)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-heading font-bold text-gray-900 mb-3">Shipping Address</h2>
                    <div class="text-sm text-gray-600 space-y-0.5">
                        <p class="font-semibold text-gray-800">{{ $order->shippingAddress->full_name }}</p>
                        <p>{{ $order->shippingAddress->address_line_1 }}</p>
                        @if($order->shippingAddress->address_line_2)
                            <p>{{ $order->shippingAddress->address_line_2 }}</p>
                        @endif
                        <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}</p>
                        <p>{{ $order->shippingAddress->country }}</p>
                        @if($order->shippingAddress->phone)
                            <p class="text-gray-400">{{ $order->shippingAddress->phone }}</p>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Back Link --}}
    <div class="mt-8">
        <a href="{{ route('account.orders') }}" class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Orders
        </a>
    </div>
</div>
@endsection
