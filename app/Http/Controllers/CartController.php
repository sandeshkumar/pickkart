<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        $cart->load('items.product.primaryImage', 'items.variant');
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'integer|min:1|max:10',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Require variant selection for products with variants
        if ($product->hasVariants() && !$request->variant_id) {
            return redirect()->back()->with('error', 'Please select product options before adding to cart.');
        }

        $cart = $this->getCart();
        $quantity = $request->get('quantity', 1);
        $price = $product->price;

        if ($request->variant_id) {
            $variant = $product->variants()->findOrFail($request->variant_id);
            $price = $variant->price ?? $product->price;
        }

        $existingItem = $cart->items()->where('product_id', $product->id)
            ->where('product_variant_id', $request->variant_id)
            ->first();

        if ($existingItem) {
            $existingItem->update(['quantity' => $existingItem->quantity + $quantity]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'product_variant_id' => $request->variant_id,
                'quantity' => $quantity,
                'unit_price' => $price,
            ]);
        }

        if ($request->redirect_to === 'checkout') {
            return redirect()->route('checkout.index')->with('success', 'Product added to cart!');
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:10']);
        $cartItem->update(['quantity' => $request->quantity]);
        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();
        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);
        $coupon = Coupon::where('code', $request->coupon_code)->first();

        if (!$coupon || !$coupon->isValid()) {
            return redirect()->back()->with('error', 'Invalid or expired coupon code.');
        }

        $cart = $this->getCart();
        $discount = $coupon->calculateDiscount($cart->subtotal);

        $cart->update([
            'coupon_code' => $coupon->code,
            'discount_amount' => $discount,
        ]);

        return redirect()->back()->with('success', "Coupon applied! You saved \${$discount}");
    }

    private function getCart(): Cart
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(['user_id' => auth()->id()]);
        }
        $sessionId = session()->getId();
        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }
}
