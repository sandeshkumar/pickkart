<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Seller extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'user_id', 'store_name', 'store_slug', 'store_description',
        'store_logo', 'store_banner', 'business_email', 'business_phone',
        'business_address', 'gst_number', 'pan_number', 'bank_name',
        'bank_account_number', 'bank_ifsc_code', 'commission_rate',
        'status', 'kyc_status', 'total_sales', 'total_earnings',
        'pending_payout', 'rating', 'total_reviews',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'pending_payout' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('store_name')
            ->saveSlugsTo('store_slug');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id', 'user_id');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}
