<?php

use App\Actions\Products\StockMovementAction;
use App\Models\Product;
use App\Models\Store;

uses()->group('actions', 'actions.product');

it("Stock product can increase", function ($value) {
    $product = Product::factory()->for(Store::factory()->create())->create();
    $oldStock = $product->stock;
    StockMovementAction::increase($product, $value);

    expect($product)->stock->toEqual($oldStock - $value);
})->with([5, 10, 20]);


it("Stock product can decrease", function ($value) {
    $product = Product::factory()->for(Store::factory()->create())->create();
    $oldStock = $product->stock;
    StockMovementAction::decrease($product, $value);

    expect($product)->stock->toEqual($oldStock + $value);
})->with([5, 10, 20]);
