<?php

namespace App\Http\Traits;

use App\Models\Product;

trait ProductRateCompute
{

    public function getRate(Product $product)
    {
        $rate = 0;
        foreach ($product->product_rates as $product_rate) {
            $rate += $product_rate->rate;
        }
        if (count($product->product_rates) != 0) {
            $rate = $rate / count($product->product_rates);
        }
        return $rate;
    }
}
