<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Wishlist;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CurrencyService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $cartCount = 0;
            $wishlistCount = 0;

            if (auth()->check()) {
                $cart = Cart::where('user_id', auth()->id())->first();
                $cartCount = $cart ? (int) $cart->items()->sum('quantity') : 0;
                $wishlistCount = Wishlist::where('user_id', auth()->id())->count();
            } else {
                $cart = Cart::where('session_id', session()->getId())->first();
                $cartCount = $cart ? (int) $cart->items()->sum('quantity') : 0;
            }

            $view->with(compact('cartCount', 'wishlistCount'));
        });
    }
}
