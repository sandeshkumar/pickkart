<?php

use App\Services\CurrencyService;

if (! function_exists('format_currency')) {
    function format_currency(float|int|string|null $amount): string
    {
        return app(CurrencyService::class)->format($amount);
    }
}

if (! function_exists('currency_symbol')) {
    function currency_symbol(): string
    {
        return app(CurrencyService::class)->getSymbol();
    }
}

if (! function_exists('currency_code')) {
    function currency_code(): string
    {
        return app(CurrencyService::class)->getCode();
    }
}

if (! function_exists('image_url')) {
    function image_url(?string $path, string $placeholder = 'https://placehold.co/400x400/e0e7ff/4338ca?text=No+Image'): string
    {
        if (! $path) {
            return $placeholder;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        return asset('storage/' . $path);
    }
}
