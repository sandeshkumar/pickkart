@extends('layouts.app')

@section('title', 'My Account - PickKart')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-800 font-medium">My Account</span>
    </nav>

    <h1 class="text-2xl md:text-3xl font-heading font-bold text-gray-900 mb-8">My Account</h1>

    {{-- Account Nav Tabs --}}
    <div class="flex gap-4 border-b border-gray-200 mb-8">
        <a href="{{ route('account.profile') }}" class="pb-3 text-sm font-semibold text-primary-600 border-b-2 border-primary-600">Profile</a>
        <a href="{{ route('account.orders') }}" class="pb-3 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">Orders</a>
    </div>

    <div class="space-y-8">

        {{-- Profile Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-heading font-bold text-gray-900 mb-6">Profile Information</h2>

            <form method="POST" action="{{ route('account.updateProfile') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input
                            type="text" id="name" name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 focus:outline-none transition"
                        >
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input
                            type="email" id="email" name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 focus:outline-none transition"
                        >
                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-gray-400 text-xs">(Optional)</span></label>
                        <input
                            type="tel" id="phone" name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 focus:outline-none transition"
                            placeholder="+1 (555) 123-4567"
                        >
                        @error('phone')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn-gradient-orange rounded-lg px-6 py-2.5 text-sm font-heading font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-accent-500/50 focus:ring-offset-2">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Change Password --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-heading font-bold text-gray-900 mb-6">Change Password</h2>

            <form method="POST" action="{{ route('account.updatePassword') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input
                            type="password" id="current_password" name="current_password"
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 focus:outline-none transition"
                        >
                        @error('current_password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input
                            type="password" id="password" name="password"
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 focus:outline-none transition"
                            placeholder="Minimum 8 characters"
                        >
                        @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input
                            type="password" id="password_confirmation" name="password_confirmation"
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 focus:outline-none transition"
                        >
                    </div>
                </div>

                <div>
                    <button type="submit" class="rounded-lg bg-gray-800 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-800/50 focus:ring-offset-2 transition">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        {{-- Saved Addresses --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-heading font-bold text-gray-900 mb-6">Saved Addresses</h2>

            @if($user->addresses->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($user->addresses as $address)
                        <div class="border border-gray-200 rounded-xl p-4 {{ $address->is_default ? 'ring-2 ring-accent-200 border-accent-300' : '' }}">
                            @if($address->is_default)
                                <span class="inline-flex items-center rounded-full bg-accent-50 px-2.5 py-0.5 text-xs font-medium text-accent-700 mb-2">Default</span>
                            @endif
                            @if($address->label)
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ $address->label }}</p>
                            @endif
                            <p class="text-sm font-semibold text-gray-800">{{ $address->full_name }}</p>
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
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <p class="text-sm text-gray-500">No saved addresses yet.</p>
                    <p class="text-xs text-gray-400 mt-1">Addresses are saved automatically during checkout.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
