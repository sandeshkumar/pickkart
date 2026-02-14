<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Mail\OrderConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        $cart->load('items.product.primaryImage', 'items.variant');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $addresses = auth()->check() ? auth()->user()->addresses : collect();

        return view('checkout.index', compact('cart', 'addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:2',
            'payment_method' => 'required|in:cod',
        ]);

        $cart = $this->getCart();
        $cart->load('items.product', 'items.variant');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Stock re-validation
        foreach ($cart->items as $item) {
            $product = $item->product->fresh();
            if ($product->stock_quantity < $item->quantity) {
                return back()->with('error', "{$product->name} only has {$product->stock_quantity} in stock.");
            }
            if ($item->variant) {
                $variant = $item->variant->fresh();
                if ($variant->stock_quantity < $item->quantity) {
                    return back()->with('error', "{$product->name} ({$variant->name}) only has {$variant->stock_quantity} in stock.");
                }
            }
        }

        // Coupon re-validation
        $discountAmount = $cart->discount_amount ?? 0;
        if ($cart->coupon_code) {
            $coupon = Coupon::where('code', $cart->coupon_code)->first();
            if (!$coupon || !$coupon->isValid()) {
                $cart->update(['coupon_code' => null, 'discount_amount' => 0]);
                $discountAmount = 0;
                return back()->with('error', 'Your coupon is no longer valid. It has been removed. Please review your order.');
            }
        }

        return DB::transaction(function () use ($request, $cart, $discountAmount) {
            // Create shipping address
            $address = Address::create([
                'user_id' => auth()->id(),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
            ]);

            $subtotal = $cart->subtotal;
            $shippingAmount = $subtotal >= 500 ? 0 : 50;
            $taxAmount = round($subtotal * 0.18, 2);
            $total = $subtotal + $shippingAmount + $taxAmount - $discountAmount;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'coupon_code' => $cart->coupon_code,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'shipping_address_id' => $address->id,
                'billing_address_id' => $address->id,
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $item->product->name,
                    'variant_name' => $item->variant?->name,
                    'sku' => $item->variant?->sku ?? $item->product->sku,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total' => $item->unit_price * $item->quantity,
                    'seller_id' => $item->product->seller_id,
                ]);

                // Deduct stock
                $item->product->decrement('stock_quantity', $item->quantity);
                if ($item->variant) {
                    $item->variant->decrement('stock_quantity', $item->quantity);
                }
            }

            // Increment coupon usage
            if ($cart->coupon_code) {
                Coupon::where('code', $cart->coupon_code)->increment('usage_count');
            }

            // Clear cart
            $cart->items()->delete();
            $cart->update(['coupon_code' => null, 'discount_amount' => 0]);

            // Send order confirmation email
            try {
                $order->load('items', 'shippingAddress');
                Mail::to($request->email)->send(new OrderConfirmationMail($order));
            } catch (\Throwable $e) {
                // Email failure should not block the order
                report($e);
            }

            return redirect()->route('orders.confirmation', $order)->with('success', 'Order placed successfully!');
        });
    }

    public function confirmation(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load('items.product', 'shippingAddress');
        return view('checkout.confirmation', compact('order'));
    }

    private function getCart(): Cart
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(['user_id' => auth()->id()]);
        }
        return Cart::firstOrCreate(['session_id' => session()->getId()]);
    }
}
