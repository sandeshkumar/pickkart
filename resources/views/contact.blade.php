@extends('layouts.app')

@section('title', 'Contact Us - PickKart')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-800 font-medium">Contact Us</span>
    </nav>

    {{-- Heading --}}
    <div class="text-center mb-8">
        <h1 class="text-3xl font-heading font-extrabold text-gray-900 tracking-tight">Get in Touch</h1>
        <p class="mt-2 text-sm text-gray-500">Have a question or feedback? We'd love to hear from you.</p>
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

        <form method="POST" action="{{ route('contact.send') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                    <input
                        type="text" id="name" name="name"
                        value="{{ old('name', auth()->user()->name ?? '') }}"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 focus:outline-none transition"
                        placeholder="John Doe"
                    >
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input
                        type="email" id="email" name="email"
                        value="{{ old('email', auth()->user()->email ?? '') }}"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 focus:outline-none transition"
                        placeholder="you@example.com"
                    >
                </div>
            </div>

            {{-- Subject --}}
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <input
                    type="text" id="subject" name="subject"
                    value="{{ old('subject') }}"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 focus:outline-none transition"
                    placeholder="How can we help?"
                >
            </div>

            {{-- Message --}}
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                <textarea
                    id="message" name="message"
                    rows="5"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 focus:outline-none transition resize-none"
                    placeholder="Tell us more about your inquiry..."
                >{{ old('message') }}</textarea>
            </div>

            {{-- Submit --}}
            <button
                type="submit"
                class="w-full sm:w-auto btn-gradient-orange rounded-lg px-8 py-2.5 text-sm font-heading font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-accent-500/50 focus:ring-offset-2"
            >
                Send Message
            </button>
        </form>
    </div>

    {{-- Contact Info --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-10">
        <div class="text-center">
            <div class="w-12 h-12 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Email</h3>
            <p class="text-sm text-gray-500 mt-1">support@pickkart.co.in</p>
        </div>
        <div class="text-center">
            <div class="w-12 h-12 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Hours</h3>
            <p class="text-sm text-gray-500 mt-1">Mon-Sun, 9AM-6PM</p>
        </div>
    </div>
</div>
@endsection
