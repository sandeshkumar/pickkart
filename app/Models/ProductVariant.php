<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'name', 'sku', 'option1', 'option2', 'option3',
        'price', 'compare_at_price', 'stock_quantity', 'image',
        'weight', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function getDisplayNameAttribute(): string
    {
        return collect([$this->option1, $this->option2, $this->option3])
            ->filter()
            ->implode(' / ');
    }

    public function getOptionValuesAttribute(): array
    {
        return collect([$this->option1, $this->option2, $this->option3])
            ->filter()
            ->values()
            ->toArray();
    }
}
