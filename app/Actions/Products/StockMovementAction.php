<?php

namespace App\Actions\Products;

use App\Models\Product;

class StockMovementAction
{
    public function __construct(Product $product, $newStock)
    {
        $product->stock = $newStock;
        $product->save();
    }

    static public function increase(Product $product, int $value)
    {
        $newStock = $product->stock - $value;
        $instance = new self($product, $newStock);
    }

    static public function decrease(Product $product, int $value)
    {
        $newStock = $product->stock + $value;
        $instance = new self($product, $newStock);
    }
}
