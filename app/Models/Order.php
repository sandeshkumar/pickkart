<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id', 'status', 'subtotal', 'tax_amount',
        'shipping_amount', 'discount_amount', 'total', 'coupon_code',
        'payment_status', 'payment_method', 'payment_transaction_id',
        'shipping_method', 'tracking_number', 'shipping_address_id',
        'billing_address_id', 'notes', 'admin_notes',
        'shipped_at', 'delivered_at', 'cancelled_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public static function generateOrderNumber(): string
    {
        return 'ZK-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function isReturnable(): bool
    {
        return $this->status === 'delivered' && $this->delivered_at?->diffInDays(now()) <= 7;
    }
}
