<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'sku', 'description'
    ];
    /**
     * Get all of the variants for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function withVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    /**
     * Get all of the VariantPrices for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function withVariantPrices()
    {
        return $this->hasMany(ProductVariantPrice::class);
    }
    /**
     * Get all of the withVP for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function withVP()
    {
        return $this->hasManyThrough(ProductVariantPrice::class, ProductVariant::class);
    }

}
