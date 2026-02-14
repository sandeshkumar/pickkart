@extends('layouts.app')

@section('title', 'Server Error - PickKart')

@section('content')
<div class="flex items-center justify-center px-4 py-20">
    <div class="text-center max-w-lg">
        <p class="text-7xl font-heading font-extrabold text-gray-400 mb-4">500</p>
        <h1 class="text-3xl font-heading font-bold text-gray-900 mb-3">Something went wrong</h1>
        <p class="text-gray-500 mb-8">We're sorry, an unexpected error occurred. Our team has been notified and we're working on it. Please try again later.</p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 btn-gradient-orange rounded-lg px-6 py-2.5 text-sm font-heading font-semibold shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Go Home
            </a>
            <button onclick="location.reload()" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Try Again
            </button>
        </div>
    </div>
</div>
@endsection
