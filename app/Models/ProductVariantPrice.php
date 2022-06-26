<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantPrice extends Model
{
    protected $fillable = [
        'product_variant_one', 'product_variant_two', 'product_variant_three', 'price', 'stock', 'product_id'
    ];

    /**
     * Get the variantPrices that owns the ProductVariantPrice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variantPrices()
    {
        return $this->belongsTo(Product::class);
    }
}
