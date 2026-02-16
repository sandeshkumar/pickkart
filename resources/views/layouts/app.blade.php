<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PickKart - Your One-Stop Online Shop')</title>
    <meta name="description" content="@yield('meta_description', 'PickKart - Shop millions of products at unbeatable prices. Free shipping, easy returns, and secure payments.')">

    {{-- Canonical URL --}}
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    {{-- Robots Meta --}}
    <meta name="robots" content="@yield('robots', 'index, follow')">

    {{-- Open Graph --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', 'PickKart - Your One-Stop Online Shop')">
    <meta property="og:description" content="@yield('og_description', 'Shop millions of products at unbeatable prices. Free shipping, easy returns.')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:site_name" content="PickKart">
    <meta property="og:locale" content="en_US">
    @hasSection('og_image')
        <meta property="og:image" content="@yield('og_image')">
        <meta property="og:image:alt" content="@yield('og_image_alt', 'PickKart')">
    @endif
    @yield('og_extra')

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="@yield('twitter_card', 'summary')">
    <meta name="twitter:title" content="@yield('og_title', 'PickKart - Your One-Stop Online Shop')">
    <meta name="twitter:description" content="@yield('og_description', 'Shop millions of products at unbeatable prices.')">
    @hasSection('og_image')
        <meta name="twitter:image" content="@yield('og_image')">
    @endif

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#e3f2fd',
                            100: '#bbdefb',
                            200: '#90caf9',
                            300: '#64b5f6',
                            400: '#42a5f5',
                            500: '#2196f3',
                            600: '#1976d2',
                            700: '#1565c0',
                            800: '#0d47a1',
                            900: '#0a3d91',
                            950: '#082f6f',
                        },
                        accent: {
                            50: '#fff3e0',
                            100: '#ffe0b2',
                            200: '#ffcc80',
                            300: '#ffb74d',
                            400: '#ffa726',
                            500: '#ff9800',
                            600: '#fb8c00',
                            700: '#f57c00',
                            800: '#ef6c00',
                            900: '#e65100',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        heading: ['Poppins', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    {{-- Alpine.js CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Custom Styles --}}
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', system-ui, sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', system-ui, sans-serif; }
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        /* Focus styles */
        *:focus-visible { outline: 2px solid #1976d2; outline-offset: 2px; border-radius: 4px; }
        input:focus, select:focus, textarea:focus { border-color: #1976d2 !important; box-shadow: 0 0 0 3px rgba(25,118,210,0.15) !important; }
        /* Loading spinner for form submissions */
        .btn-loading { position: relative; pointer-events: none; opacity: 0.75; }
        .btn-loading > * { visibility: hidden; }
        .btn-loading::after {
            content: ''; position: absolute; inset: 0; margin: auto;
            width: 20px; height: 20px; border: 2.5px solid transparent;
            border-top-color: currentColor; border-radius: 50%;
            animation: spin 0.6s linear infinite; visibility: visible;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        /* Button hover lift */
        .btn-hover-lift { transition: all 0.2s ease; }
        .btn-hover-lift:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(25,118,210,0.3); }
        /* Gradient buttons */
        .btn-gradient-orange {
            background: linear-gradient(135deg, #fb8c00 0%, #f57c00 100%);
            color: #fff; transition: all 0.3s ease;
        }
        .btn-gradient-orange:hover {
            background: linear-gradient(135deg, #f57c00 0%, #ef6c00 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(251,140,0,0.35);
        }
        .btn-gradient-blue {
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
            color: #fff; transition: all 0.3s ease;
        }
        .btn-gradient-blue:hover {
            background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(25,118,210,0.35);
        }
        /* Product image hover zoom */
        .product-image-zoom { overflow: hidden; }
        .product-image-zoom img { transition: transform 0.4s cubic-bezier(0.4,0,0.2,1); }
        .product-image-zoom:hover img { transform: scale(1.1); }
        /* Animated badge pulse */
        @keyframes badge-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .badge-pulse { animation: badge-pulse 2s cubic-bezier(0.4,0,0.6,1) infinite; }
        /* Styled pagination */
        nav[role="navigation"] span[aria-current="page"] > span { background-color: #1976d2 !important; border-color: #1976d2 !important; }
        nav[role="navigation"] a:hover { background-color: #e3f2fd !important; color: #1976d2 !important; border-color: #90caf9 !important; }
    </style>

    {{-- Global Structured Data: Organization --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "Organization",
        "name": "PickKart",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/logo.png') }}",
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+1-555-123-4567",
            "contactType": "customer service",
            "email": "support@pickkart.com",
            "availableLanguage": "English"
        }
    }
    </script>

    {{-- Global Structured Data: WebSite with SearchAction --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "WebSite",
        "name": "PickKart",
        "url": "{{ url('/') }}",
        "potentialAction": {
            "@type": "SearchAction",
            "target": {
                "@type": "EntryPoint",
                "urlTemplate": "{{ url('/products') }}?search={search_term_string}"
            },
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    {{-- Per-page Structured Data --}}
    @yield('structured_data')

    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    {{-- Top Promo Bar --}}
    <div x-data="{ dismissed: localStorage.getItem('pk_promo_dismissed') === '1' }" x-show="!dismissed" x-cloak
         class="bg-gradient-to-r from-accent-600 via-accent-500 to-accent-600 text-white text-center text-sm py-2.5 px-4 relative">
        <div class="max-w-7xl mx-auto flex items-center justify-center gap-2">
            <span class="animate-pulse">&#127881;</span>
            <span>Free shipping on orders over &#8377;499! Use code <strong class="bg-white/20 px-1.5 py-0.5 rounded text-xs">PICKKART10</strong> for 10% off your first order.</span>
        </div>
        <button @click="dismissed = true; localStorage.setItem('pk_promo_dismissed', '1')"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-white/50 hover:text-white transition-colors" aria-label="Dismiss promo">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    {{-- Main Header / Navbar --}}
    <header class="bg-white shadow-sm sticky top-0 z-50" x-data="{ mobileMenu: false, categoryDropdown: false, userDropdown: false, searchFocus: false }">
        {{-- Upper Navbar --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">

                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex-shrink-0 flex items-center gap-2">
                    <div class="bg-primary-600 rounded-lg p-1.5">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <span class="text-2xl font-bold text-primary-700 hidden sm:block">Pick<span class="text-accent-500">Kart</span></span>
                </a>

                {{-- Search Bar --}}
                <div class="flex-1 max-w-2xl hidden md:block" x-data="{
                    focused: false,
                    query: '{{ request('search') }}',
                    recentSearches: JSON.parse(localStorage.getItem('pk_recent_searches') || '[]'),
                    get showSuggestions() { return this.focused && this.recentSearches.length > 0 && !this.query; },
                    saveSearch() {
                        if (!this.query.trim()) return;
                        let searches = this.recentSearches.filter(s => s !== this.query.trim());
                        searches.unshift(this.query.trim());
                        searches = searches.slice(0, 5);
                        localStorage.setItem('pk_recent_searches', JSON.stringify(searches));
                    },
                    clearRecent() {
                        this.recentSearches = [];
                        localStorage.removeItem('pk_recent_searches');
                    }
                }">
                    <form action="{{ url('/products') }}" method="GET" class="relative" @submit="saveSearch()">
                        <input
                            type="text"
                            name="search"
                            x-model="query"
                            placeholder="Search for products, brands, and more..."
                            class="w-full pl-4 pr-12 py-2.5 border-2 border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all duration-200 text-sm"
                            @focus="focused = true"
                            @blur="setTimeout(() => focused = false, 200)"
                            autocomplete="off"
                        >
                        <button type="submit" class="absolute right-0 top-0 h-full px-4 bg-primary-600 text-white rounded-r-lg hover:bg-primary-700 transition-colors" aria-label="Search">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                        {{-- Recent Searches Dropdown --}}
                        <div x-show="showSuggestions" x-cloak
                             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute top-full left-0 right-0 mt-1 bg-white rounded-lg shadow-lg border border-gray-100 z-50 overflow-hidden">
                            <div class="flex items-center justify-between px-4 py-2 border-b border-gray-100">
                                <span class="text-xs font-semibold text-gray-400 uppercase">Recent Searches</span>
                                <button type="button" @click="clearRecent()" class="text-xs text-gray-400 hover:text-red-500 transition-colors">Clear</button>
                            </div>
                            <template x-for="search in recentSearches" :key="search">
                                <button type="button" @click="query = search; $nextTick(() => $el.closest('form').submit())"
                                        class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition-colors text-left">
                                    <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span x-text="search"></span>
                                </button>
                            </template>
                        </div>
                    </form>
                </div>

                {{-- Right Icons --}}
                <div class="flex items-center gap-1 sm:gap-3">

                    {{-- Wishlist --}}
                    <a href="{{ url('/wishlist') }}" class="relative p-2 text-gray-600 hover:text-primary-600 transition-colors" title="Wishlist">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        @if(isset($wishlistCount) && $wishlistCount > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-semibold">{{ $wishlistCount }}</span>
                        @endif
                    </a>

                    {{-- Cart --}}
                    <a href="{{ url('/cart') }}" class="relative p-2 text-gray-600 hover:text-primary-600 transition-colors" title="Shopping Cart">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                        <span class="absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-semibold">{{ $cartCount ?? 0 }}</span>
                    </a>

                    {{-- User Menu --}}
                    <div class="relative" @click.away="userDropdown = false">
                        <button @click="userDropdown = !userDropdown" class="flex items-center gap-1 p-2 text-gray-600 hover:text-primary-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <svg class="w-4 h-4 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="userDropdown" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-2 z-50">
                            @auth
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('account.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700">My Account</a>
                                <a href="{{ route('account.orders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700">My Orders</a>
                                <a href="{{ url('/wishlist') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700">My Wishlist</a>
                                <hr class="my-1 border-gray-100">
                                <form method="POST" action="{{ url('/logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                                </form>
                            @else
                                <a href="{{ url('/login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700">Login</a>
                                <a href="{{ url('/register') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700">Create Account</a>
                            @endauth
                        </div>
                    </div>

                    {{-- Mobile Menu Button --}}
                    <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 text-gray-600 hover:text-primary-600 transition-colors" aria-label="Toggle menu">
                        <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <svg x-show="mobileMenu" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Category Navigation Bar --}}
        <div class="hidden md:block bg-gradient-to-r from-primary-700 via-primary-600 to-primary-700 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center h-10">
                    {{-- All Categories Mega Menu --}}
                    <div class="relative" @mouseenter="categoryDropdown = true" @mouseleave="categoryDropdown = false" @click.away="categoryDropdown = false">
                        <button @click="categoryDropdown = !categoryDropdown" class="flex items-center gap-2 px-4 h-10 bg-primary-800 hover:bg-primary-900 transition-colors text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                            All Categories
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        {{-- Mega Menu Dropdown --}}
                        <div x-show="categoryDropdown" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute left-0 mt-0 w-[720px] bg-white rounded-b-lg shadow-xl border border-gray-100 z-50">
                            <div class="grid grid-cols-3 gap-0 p-6">
                                {{-- Column 1 --}}
                                <div class="space-y-4">
                                    <a href="{{ url('/products?category=electronics') }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-primary-50 group">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 group-hover:text-primary-600">Electronics</p>
                                            <p class="text-xs text-gray-500">Laptops, Phones, Gadgets</p>
                                        </div>
                                    </a>
                                    <a href="{{ url('/products?category=fashion') }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-primary-50 group">
                                        <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 group-hover:text-primary-600">Fashion</p>
                                            <p class="text-xs text-gray-500">Clothing, Shoes, Accessories</p>
                                        </div>
                                    </a>
                                    <a href="{{ url('/products?category=home-garden') }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-primary-50 group">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 group-hover:text-primary-600">Home & Garden</p>
                                            <p class="text-xs text-gray-500">Furniture, Decor, Kitchen</p>
                                        </div>
                                    </a>
                                    <a href="{{ url('/products?category=beauty') }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-primary-50 group">
                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 group-hover:text-primary-600">Beauty & Health</p>
                                            <p class="text-xs text-gray-500">Skincare, Makeup, Wellness</p>
                                        </div>
                                    </a>
                                </div>

                                {{-- Column 2 --}}
                                <div class="space-y-4">
                                    <a href="{{ url('/products?category=sports') }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-primary-50 group">
                                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 group-hover:text-primary-600">Sports & Outdoors</p>
                                            <p class="text-xs text-gray-500">Fitness, Camping, Gear</p>
                                        </div>
                                    </a>
                                    <a href="{{ url('/products?category=toys') }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-primary-50 group">
                                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 group-hover:text-primary-600">Toys & Games</p>
                                            <p class="text-xs text-gray-500">Kids, Board Games, Puzzles</p>
                                        </div>
                                    </a>
                                    <a href="{{ url('/products?category=automotive') }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-primary-50 group">
                                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8m-8 5h8m-4-10v2m0 12v2m8-10h2M4 12H2m15.364-6.364l1.414-1.414M6.636 17.364l-1.414 1.414m12.142 0l1.414-1.414M6.636 6.636L5.222 5.222"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 group-hover:text-primary-600">Automotive</p>
                                            <p class="text-xs text-gray-500">Parts, Accessories, Tools</p>
                                        </div>
                                    </a>
                                    <a href="{{ url('/products?category=books') }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-primary-50 group">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 group-hover:text-primary-600">Books & Media</p>
                                            <p class="text-xs text-gray-500">Books, Music, Movies</p>
                                        </div>
                                    </a>
                                </div>

                                {{-- Column 3 --}}
                                <div class="space-y-4">
                                    <a href="{{ url('/products?category=groceries') }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-primary-50 group">
                                        <div class="w-10 h-10 bg-lime-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-lime-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 group-hover:text-primary-600">Groceries</p>
                                            <p class="text-xs text-gray-500">Food, Beverages, Essentials</p>
                                        </div>
                                    </a>
                                    <a href="{{ url('/products?category=pet-supplies') }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-primary-50 group">
                                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 group-hover:text-primary-600">Pet Supplies</p>
                                            <p class="text-xs text-gray-500">Food, Toys, Accessories</p>
                                        </div>
                                    </a>
                                    <a href="{{ url('/products?category=baby-kids') }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-primary-50 group">
                                        <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 group-hover:text-primary-600">Baby & Kids</p>
                                            <p class="text-xs text-gray-500">Clothing, Toys, Care</p>
                                        </div>
                                    </a>
                                    <a href="{{ url('/products?category=jewelry') }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-primary-50 group">
                                        <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 group-hover:text-primary-600">Jewelry & Watches</p>
                                            <p class="text-xs text-gray-500">Rings, Necklaces, Watches</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="border-t border-gray-100 p-4 bg-gray-50 rounded-b-lg">
                                <a href="{{ url('/products') }}" class="text-sm text-primary-600 font-medium hover:text-primary-800">View All Categories &rarr;</a>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Nav Links --}}
                    <nav class="flex items-center gap-1 ml-4">
                        <a href="{{ url('/') }}" class="px-3 h-10 flex items-center text-sm font-medium text-primary-100 hover:text-white hover:bg-primary-800 rounded transition-colors">Home</a>
                        <a href="{{ url('/products') }}" class="px-3 h-10 flex items-center text-sm font-medium text-primary-100 hover:text-white hover:bg-primary-800 rounded transition-colors">Shop</a>
                        <a href="{{ url('/products?on_sale=1') }}" class="px-3 h-10 flex items-center text-sm font-medium text-yellow-300 hover:text-yellow-100 hover:bg-primary-800 rounded transition-colors">Deals</a>
                        <a href="{{ url('/products?new_arrivals=1') }}" class="px-3 h-10 flex items-center text-sm font-medium text-primary-100 hover:text-white hover:bg-primary-800 rounded transition-colors">New Arrivals</a>
                        <a href="{{ url('/products?is_featured=1') }}" class="px-3 h-10 flex items-center text-sm font-medium text-primary-100 hover:text-white hover:bg-primary-800 rounded transition-colors">Featured</a>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Mobile Search Bar --}}
        <div class="md:hidden px-4 py-2 bg-gray-50 border-t border-gray-100">
            <form action="{{ url('/products') }}" method="GET" class="relative">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search products..."
                    class="w-full pl-4 pr-10 py-2 border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none text-sm"
                >
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>

        {{-- Mobile Nav Drawer --}}
        <div x-show="mobileMenu" x-cloak class="md:hidden fixed inset-0 z-[60]">
            {{-- Backdrop --}}
            <div x-show="mobileMenu"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="mobileMenu = false" class="absolute inset-0 bg-black/50"></div>
            {{-- Drawer Panel --}}
            <div x-show="mobileMenu"
                 x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                 class="absolute top-0 left-0 h-full w-[85vw] max-w-sm bg-white shadow-2xl overflow-y-auto">
                {{-- Drawer Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        <div class="bg-primary-600 rounded-lg p-1"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg></div>
                        <span class="text-lg font-bold text-primary-700">Pick<span class="text-accent-500">Kart</span></span>
                    </a>
                    <button @click="mobileMenu = false" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg" aria-label="Close menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                {{-- Drawer Navigation --}}
                <nav class="px-4 py-3 space-y-1">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Home
                    </a>
                    <a href="{{ url('/products') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        All Products
                    </a>
                    <a href="{{ url('/products?on_sale=1') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-yellow-600 hover:bg-yellow-50 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                        Deals & Offers
                    </a>
                    <a href="{{ url('/products?new_arrivals=1') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        New Arrivals
                    </a>

                    <div class="border-t border-gray-100 pt-3 mt-3">
                        <p class="px-3 py-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Categories</p>
                        <a href="{{ url('/products?category=electronics') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 rounded-lg">Electronics</a>
                        <a href="{{ url('/products?category=fashion') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 rounded-lg">Fashion</a>
                        <a href="{{ url('/products?category=home-garden') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 rounded-lg">Home & Garden</a>
                        <a href="{{ url('/products?category=beauty') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 rounded-lg">Beauty & Health</a>
                        <a href="{{ url('/products?category=sports') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 rounded-lg">Sports & Outdoors</a>
                        <a href="{{ url('/products?category=toys') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 rounded-lg">Toys & Games</a>
                        <a href="{{ url('/products?category=automotive') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 rounded-lg">Automotive</a>
                        <a href="{{ url('/products?category=books') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 rounded-lg">Books & Media</a>
                        <a href="{{ url('/products?category=groceries') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 rounded-lg">Groceries</a>
                        <a href="{{ url('/products?category=pet-supplies') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 rounded-lg">Pet Supplies</a>
                        <a href="{{ url('/products?category=baby-kids') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 rounded-lg">Baby & Kids</a>
                        <a href="{{ url('/products?category=jewelry') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 rounded-lg">Jewelry & Watches</a>
                    </div>

                    {{-- Account Links --}}
                    <div class="border-t border-gray-100 pt-3 mt-3">
                        @auth
                            <a href="{{ route('account.profile') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                My Account
                            </a>
                            <a href="{{ route('account.orders') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                My Orders
                            </a>
                        @else
                            <a href="{{ url('/login') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-primary-600 hover:bg-primary-50 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                Sign In
                            </a>
                            <a href="{{ url('/register') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                Create Account
                            </a>
                        @endauth
                    </div>
                </nav>
            </div>
        </div>
    </header>

    {{-- Flash Data Carrier (read by toast component) --}}
    <div id="flash-data" class="hidden"
         data-success="{{ session('success') }}"
         data-error="{{ session('error') }}"
         data-warning="{{ session('warning') }}"
         data-info="{{ session('info') }}"></div>

    {{-- Main Content --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- Newsletter Banner --}}
    <section class="bg-gradient-to-br from-primary-800 via-primary-700 to-accent-700 py-10 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="text-xl font-bold text-white mb-2">Stay in the Loop</h3>
            <p class="text-primary-200 text-sm mb-6">Subscribe to our newsletter and get exclusive deals, new arrivals, and insider-only discounts delivered to your inbox.</p>
            <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto"
                  x-data="{ subscribed: false, email: '', error: '' }"
                  @submit.prevent="
                      error = '';
                      fetch('{{ route('newsletter.subscribe') }}', {
                          method: 'POST',
                          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                          body: JSON.stringify({ email: email })
                      })
                      .then(r => r.json())
                      .then(data => { if (data.success) { subscribed = true; } else { error = data.message || 'Something went wrong.'; } })
                      .catch(() => { error = 'Something went wrong. Please try again.'; });
                  ">
                <div class="flex-1">
                    <input type="email" required placeholder="Enter your email address" :disabled="subscribed" x-model="email"
                           class="w-full px-4 py-3 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-white/50">
                    <p x-show="error" x-text="error" class="text-xs text-red-200 mt-1 text-left"></p>
                </div>
                <button type="submit" x-show="!subscribed" class="btn-gradient-orange font-heading font-semibold px-6 py-3 rounded-lg text-sm">Subscribe</button>
                <span x-show="subscribed" x-cloak class="bg-green-500 text-white font-semibold px-6 py-3 rounded-lg text-sm inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Subscribed!
                </span>
            </form>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-300">
        {{-- Main Footer --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                {{-- About PickKart --}}
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="bg-primary-600 rounded-lg p-1.5">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <span class="text-xl font-bold text-white">Pick<span class="text-accent-400">Kart</span></span>
                    </div>
                    <p class="text-sm text-gray-400 mb-4">Your one-stop online shop for everything you need.</p>
                    <div class="flex gap-3">
                        <a href="#" class="w-9 h-9 bg-gray-800 hover:bg-primary-600 rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"></path></svg>
                        </a>
                        <a href="#" class="w-9 h-9 bg-gray-800 hover:bg-primary-600 rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"></path></svg>
                        </a>
                        <a href="#" class="w-9 h-9 bg-gray-800 hover:bg-primary-600 rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"></path></svg>
                        </a>
                    </div>
                </div>

                {{-- Categories --}}
                <div>
                    <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Categories</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/products?category=electronics') }}" class="text-sm text-gray-400 hover:text-primary-400 transition-colors">Electronics</a></li>
                        <li><a href="{{ url('/products?category=fashion') }}" class="text-sm text-gray-400 hover:text-primary-400 transition-colors">Fashion</a></li>
                        <li><a href="{{ url('/products?category=home-garden') }}" class="text-sm text-gray-400 hover:text-primary-400 transition-colors">Home & Garden</a></li>
                        <li><a href="{{ url('/products?category=beauty') }}" class="text-sm text-gray-400 hover:text-primary-400 transition-colors">Beauty & Health</a></li>
                        <li><a href="{{ url('/products?category=sports') }}" class="text-sm text-gray-400 hover:text-primary-400 transition-colors">Sports & Outdoors</a></li>
                        <li><a href="{{ url('/products?category=books') }}" class="text-sm text-gray-400 hover:text-primary-400 transition-colors">Books & Media</a></li>
                    </ul>
                </div>

                {{-- Customer Service --}}
                <div>
                    <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Customer Service</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('contact.show') }}" class="text-sm text-gray-400 hover:text-primary-400 transition-colors">Contact Us</a></li>
                        <li><a href="{{ url('/pages/shipping-policy') }}" class="text-sm text-gray-400 hover:text-primary-400 transition-colors">Shipping Policy</a></li>
                        <li><a href="{{ url('/pages/return-policy') }}" class="text-sm text-gray-400 hover:text-primary-400 transition-colors">Returns & Refunds</a></li>
                        <li><a href="{{ url('/pages/privacy-policy') }}" class="text-sm text-gray-400 hover:text-primary-400 transition-colors">Privacy Policy</a></li>
                        <li><a href="{{ url('/pages/terms') }}" class="text-sm text-gray-400 hover:text-primary-400 transition-colors">Terms & Conditions</a></li>
                        <li><a href="{{ url('/pages/faq') }}" class="text-sm text-gray-400 hover:text-primary-400 transition-colors">FAQ</a></li>
                    </ul>
                </div>

                {{-- Contact Info --}}
                <div>
                    <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Contact Info</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-sm text-gray-400">123 Commerce Street, Business District, New York, NY 10001</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span class="text-sm text-gray-400">+1 (555) 123-4567</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span class="text-sm text-gray-400">support@pickkart.com</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-sm text-gray-400">Mon - Sat: 9AM - 9PM</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Payment Methods & Bottom Bar --}}
        <div class="border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-gray-500">&copy; {{ date('Y') }} PickKart. All rights reserved.</p>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-500">We accept:</span>
                        <div class="flex items-center gap-2">
                            <div class="bg-gray-800 rounded px-2 py-1 text-xs font-semibold text-gray-300">VISA</div>
                            <div class="bg-gray-800 rounded px-2 py-1 text-xs font-semibold text-gray-300">MC</div>
                            <div class="bg-gray-800 rounded px-2 py-1 text-xs font-semibold text-gray-300">AMEX</div>
                            <div class="bg-gray-800 rounded px-2 py-1 text-xs font-semibold text-gray-300">PayPal</div>
                            <div class="bg-gray-800 rounded px-2 py-1 text-xs font-semibold text-gray-300">GPay</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    {{-- Toast Notification System --}}
    <div x-data="toastSystem()" x-init="init()" class="fixed bottom-6 right-6 z-[100] flex flex-col gap-3" style="pointer-events: none;">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4"
                 class="flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-lg border max-w-sm" :class="toast.classes" style="pointer-events: auto;">
                <div x-html="toast.icon" class="flex-shrink-0"></div>
                <p class="text-sm font-medium flex-1" x-text="toast.message"></p>
                <button @click="dismiss(toast)" class="flex-shrink-0 opacity-50 hover:opacity-100 transition-opacity">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </template>
    </div>

    {{-- Back to Top --}}
    <button x-data="{ visible: false }" @scroll.window="visible = window.scrollY > 400" x-show="visible" x-cloak
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-75" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-75"
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="fixed bottom-6 left-6 z-50 w-10 h-10 bg-primary-600 hover:bg-primary-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors"
            aria-label="Back to top">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
    </button>

    <script>
    function toastSystem() {
        return {
            toasts: [],
            counter: 0,
            init() {
                const fd = document.getElementById('flash-data');
                if (!fd) return;
                const types = {
                    success: { classes: 'bg-green-50 border-green-200 text-green-800', icon: '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' },
                    error:   { classes: 'bg-red-50 border-red-200 text-red-800',     icon: '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' },
                    warning: { classes: 'bg-yellow-50 border-yellow-200 text-yellow-800', icon: '<svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>' },
                    info:    { classes: 'bg-blue-50 border-blue-200 text-blue-800',   icon: '<svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' }
                };
                for (const [type, config] of Object.entries(types)) {
                    const msg = fd.dataset[type];
                    if (msg) this.show(msg, config);
                }
            },
            show(message, config) {
                const toast = { id: ++this.counter, message, ...config, visible: true };
                this.toasts.push(toast);
                setTimeout(() => this.dismiss(toast), 4000);
            },
            dismiss(toast) {
                toast.visible = false;
                setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== toast.id); }, 300);
            }
        };
    }
    // Loading state on form submissions
    document.addEventListener('submit', function(e) {
        const btn = e.target.querySelector('button[type="submit"]');
        if (btn && !btn.classList.contains('btn-loading')) {
            btn.classList.add('btn-loading');
        }
    });
    </script>

    @stack('scripts')
</body>
</html>