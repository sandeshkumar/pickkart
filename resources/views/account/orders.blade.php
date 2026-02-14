@extends('layouts.app')

@section('title', 'My Orders - PickKart')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-800 font-medium">My Orders</span>
    </nav>

    <h1 class="text-2xl md:text-3xl font-heading font-bold text-gray-900 mb-8">My Orders</h1>

    {{-- Account Nav Tabs --}}
    <div class="flex gap-4 border-b border-gray-200 mb-8">
        <a href="{{ route('account.profile') }}" class="pb-3 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">Profile</a>
        <a href="{{ route('account.orders') }}" class="pb-3 text-sm font-semibold text-primary-600 border-b-2 border-primary-600">Orders</a>
    </div>

    @if($orders->isNotEmpty())
        <div class="space-y-4">
            @foreach($orders as $order)
                <a href="{{ route('account.orders.show', $order) }}" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:border-primary-200 hover:shadow-md transition-all group">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        {{-- Order Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-sm font-bold text-gray-900">{{ $order->order_number }}</span>
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
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium {{ $color }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                <span>{{ $order->created_at->format('M d, Y') }}</span>
                                <span>{{ $order->items_count }} {{ Str::plural('item', $order->items_count) }}</span>
                                <span class="font-medium text-gray-700">{{ format_currency($order->total) }}</span>
                            </div>
                        </div>

                        {{-- Arrow --}}
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No orders yet</h3>
            <p class="text-sm text-gray-500 mb-6">When you place an order, it will appear here.</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 btn-gradient-orange rounded-lg px-6 py-2.5 text-sm font-heading font-semibold shadow-sm">
                Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection
