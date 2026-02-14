@extends('layouts.app')

@section('title', 'Login - PickKart')

@section('content')
<div class="flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        {{-- Heading --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-heading font-extrabold text-gray-900 tracking-tight">Welcome back</h1>
            <p class="mt-2 text-sm text-gray-500">Sign in to your account</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <h3 class="text-sm font-semibold text-red-800">There were errors with your submission</h3>
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 focus:outline-none transition"
                        placeholder="you@example.com"
                    >
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 focus:outline-none transition"
                        placeholder="Enter your password"
                    >
                </div>

                {{-- Remember Me & Forgot Password --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            name="remember"
                            class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600"
                        >
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition">
                        Forgot password?
                    </a>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full btn-gradient-orange rounded-lg px-4 py-2.5 text-sm font-heading font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-accent-500/50 focus:ring-offset-2"
                >
                    Sign in
                </button>
            </form>
        </div>

        {{-- Register Link --}}
        <p class="mt-6 text-center text-sm text-gray-500">
            Don't have an account?
            <a href="{{ url('/register') }}" class="font-semibold text-primary-600 hover:text-primary-700 transition">
                Create an account
            </a>
        </p>
    </div>
</div>
@endsection
