<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Storefront Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| Cart Routes
|--------------------------------------------------------------------------
*/

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');

/*
|--------------------------------------------------------------------------
| Checkout Routes (Auth Required)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/orders/{order}/confirmation', [CheckoutController::class, 'confirmation'])->name('orders.confirmation');
});

/*
|--------------------------------------------------------------------------
| Account & Orders Routes (Auth Required)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'profile'])->name('account.profile');
    Route::put('/account', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.updatePassword');
    Route::get('/orders', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/orders/{order}', [AccountController::class, 'orderShow'])->name('account.orders.show');
});

/*
|--------------------------------------------------------------------------
| Wishlist Routes (Auth Required)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

/*
|--------------------------------------------------------------------------
| Search Suggestions (AJAX)
|--------------------------------------------------------------------------
*/

Route::get('/search/suggestions', [\App\Http\Controllers\SearchController::class, 'suggestions'])
    ->middleware('throttle:60,1')
    ->name('search.suggestions');

/*
|--------------------------------------------------------------------------
| Contact
|--------------------------------------------------------------------------
*/

Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'send'])->middleware('throttle:3,1')->name('contact.send');

/*
|--------------------------------------------------------------------------
| Newsletter
|--------------------------------------------------------------------------
*/

Route::post('/newsletter/subscribe', function (Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email|max:255']);

    // For MVP: log the subscription. Replace with Mailchimp/Sendinblue integration post-launch.
    \Illuminate\Support\Facades\Log::info('Newsletter subscription', ['email' => $request->email]);

    return response()->json(['success' => true, 'message' => 'Subscribed successfully!']);
})->middleware('throttle:3,1')->name('newsletter.subscribe');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['guest', 'throttle:5,1'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LogoutController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| SEO: Sitemap & Robots
|--------------------------------------------------------------------------
*/

Route::get('/robots.txt', function () {
    $content = "User-agent: *\nAllow: /\n\n";
    $content .= "Disallow: /admin\nDisallow: /admin/\n";
    $content .= "Disallow: /account\nDisallow: /orders\n";
    $content .= "Disallow: /cart\nDisallow: /checkout\n";
    $content .= "Disallow: /login\nDisallow: /register\n";
    $content .= "Disallow: /forgot-password\nDisallow: /reset-password\n";
    $content .= "Disallow: /wishlist\nDisallow: /api/\n\n";
    $content .= "Sitemap: " . url('/sitemap.xml') . "\n";
    return response($content, 200)->header('Content-Type', 'text/plain');
});

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap/products.xml', [SitemapController::class, 'products'])->name('sitemap.products');
Route::get('/sitemap/categories.xml', [SitemapController::class, 'categories'])->name('sitemap.categories');
Route::get('/sitemap/pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');

/*
|--------------------------------------------------------------------------
| CMS Pages (catch-all, must be last)
|--------------------------------------------------------------------------
*/

Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');

// Temporary route — clear caches & re-seed pages (DELETE after use)
Route::get('/clear-cache-seed-m8p2q', function () {
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--force' => true]);
    return '<pre>All caches cleared & DatabaseSeeder executed.</pre>';
});
