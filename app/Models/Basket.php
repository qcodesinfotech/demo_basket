<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    protected $fillable = [
        'user_id',
        'sku',
        'product_name',
        'product_price',
        'quantity',
    ];

    protected $casts = [
        'product_price' => 'float',
        'quantity' => 'integer',
    ];

    // ✅ Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'sku', 'sku'); // assuming SKU is the link
    }

    // ✅ Accessors (already covered, just confirming)
    public function getSubtotalAttribute()
    {
        return $this->product_price * $this->quantity;
    }

    public function getDiscountAttribute()
    {
        $halfPriceCount = floor($this->quantity / 2);
        return ($this->product_price / 2) * $halfPriceCount;
    }

    public function getTotalAttribute()
    {
        return $this->subtotal - $this->discount;
    }
}
