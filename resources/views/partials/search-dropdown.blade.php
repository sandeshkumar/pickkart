{{-- Search Autocomplete Dropdown --}}
{{-- Used by both desktop and mobile search bars --}}

{{-- Recent Searches (shown when input is empty and focused) --}}
<template x-if="showRecent">
    <div>
        <div class="flex items-center justify-between px-4 py-2 border-b border-gray-100 bg-gray-50">
            <span class="text-xs font-semibold text-gray-400 uppercase">Recent Searches</span>
            <button type="button" @click="clearRecent()" class="text-xs text-gray-400 hover:text-red-500 transition-colors">Clear</button>
        </div>
        <template x-for="(search, idx) in recentSearches" :key="search">
            <button type="button"
                    @click="query = search; onInput()"
                    class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition-colors text-left">
                <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span x-text="search"></span>
            </button>
        </template>
    </div>
</template>

{{-- Live Search Results --}}
<template x-if="showResults">
    <div>
        {{-- Products --}}
        <template x-if="results.products && results.products.length > 0">
            <div>
                <div class="px-4 py-2 border-b border-gray-100 bg-gray-50">
                    <span class="text-xs font-semibold text-gray-400 uppercase">Products</span>
                </div>
                <template x-for="(product, idx) in results.products" :key="product.id">
                    <a :href="product.url"
                       class="flex items-center gap-3 px-4 py-2.5 hover:bg-primary-50 transition-colors">
                        <img :src="product.image" :alt="product.name" class="w-10 h-10 object-cover rounded-lg border border-gray-100 flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate" x-html="highlightMatch(product.name, query)"></p>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-primary-600" x-text="product.price"></span>
                                <template x-if="product.compare_at_price">
                                    <span class="text-xs text-gray-400 line-through" x-text="product.compare_at_price"></span>
                                </template>
                            </div>
                        </div>
                        <span x-show="product.category" class="text-xs text-gray-400 flex-shrink-0 hidden sm:inline" x-text="product.category"></span>
                    </a>
                </template>
            </div>
        </template>

        {{-- Categories --}}
        <template x-if="results.categories && results.categories.length > 0">
            <div>
                <div class="px-4 py-2 border-b border-gray-100 bg-gray-50">
                    <span class="text-xs font-semibold text-gray-400 uppercase">Categories</span>
                </div>
                <template x-for="cat in results.categories" :key="cat.slug">
                    <a :href="cat.url" class="flex items-center gap-3 px-4 py-2.5 hover:bg-primary-50 transition-colors">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"></path></svg>
                        <span class="text-sm text-gray-700" x-html="highlightMatch(cat.name, query)"></span>
                        <span class="text-xs text-gray-400 ml-auto" x-text="cat.product_count + ' products'"></span>
                    </a>
                </template>
            </div>
        </template>

        {{-- Brands --}}
        <template x-if="results.brands && results.brands.length > 0">
            <div>
                <div class="px-4 py-2 border-b border-gray-100 bg-gray-50">
                    <span class="text-xs font-semibold text-gray-400 uppercase">Brands</span>
                </div>
                <template x-for="brand in results.brands" :key="brand.slug">
                    <a :href="brand.url" class="flex items-center gap-3 px-4 py-2.5 hover:bg-primary-50 transition-colors">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        <span class="text-sm text-gray-700" x-html="highlightMatch(brand.name, query)"></span>
                        <span class="text-xs text-gray-400 ml-auto" x-text="brand.product_count + ' products'"></span>
                    </a>
                </template>
            </div>
        </template>

        {{-- View All Results --}}
        <template x-if="results.total_count > 0">
            <a :href="results.view_all_url"
               class="flex items-center justify-center gap-2 px-4 py-3 bg-gray-50 border-t border-gray-100 text-sm font-medium text-primary-600 hover:bg-primary-50 hover:text-primary-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                View all <span x-text="results.total_count"></span> results for "<span x-text="query" class="font-semibold"></span>"
            </a>
        </template>

        {{-- No Results --}}
        <template x-if="hasSearched && results.total_count === 0">
            <div class="px-4 py-6 text-center">
                <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-sm text-gray-500">No results found for "<span x-text="query" class="font-medium"></span>"</p>
                <p class="text-xs text-gray-400 mt-1">Try a different keyword or check your spelling</p>
            </div>
        </template>
    </div>
</template>
