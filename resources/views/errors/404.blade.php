@extends('layouts.app')

@section('title', 'Page Not Found - PickKart')

@section('content')
<div class="flex items-center justify-center px-4 py-20">
    <div class="text-center max-w-lg">
        <p class="text-7xl font-heading font-extrabold text-accent-500 mb-4">404</p>
        <h1 class="text-3xl font-heading font-bold text-gray-900 mb-3">Page not found</h1>
        <p class="text-gray-500 mb-8">Sorry, we couldn't find the page you're looking for. It might have been moved or no longer exists.</p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 btn-gradient-orange rounded-lg px-6 py-2.5 text-sm font-heading font-semibold shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Go Home
            </a>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition">
                Browse Products
            </a>
        </div>
    </div>
</div>
@endsection
