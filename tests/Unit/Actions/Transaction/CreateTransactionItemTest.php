<?php

use App\Actions\Transactions\CreateTransactionItem;
use App\Models\Store;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;

uses()->group('actions', 'actions.transaction');

it("Can create new transaction Item", function () {
    $item = TransactionItem::factory()
        ->for(Transaction::factory()->create())
        ->for(
            Product::factory()
                ->for(Store::factory()->create())
                ->create()
        )->make();

    $instance = new CreateTransactionItem();
    $instance->execute($item->toArray(), $item->transaction);

    $transaction = Transaction::find($item->transaction->id);
    $transactionItem = $transaction->products->first();
    expect($transactionItem)->quantity->toEqual($item->quantity);
    expect($transactionItem)->price->toEqual($item->price);
});

test("Product stock not changed when store not use stock opname feature", function () {
    $item = TransactionItem::factory()
        ->for(Transaction::factory()->create())
        ->for(
            Product::factory()
                ->state(["use_stock_opname" => false])
                ->for(Store::factory()->create())
                ->create()
        )
        ->make();
    $productExpectedOld = Product::first();

    $instance = new CreateTransactionItem();
    $instance->execute($item->toArray(), $item->transaction);

    $productExpectedNew = Product::first();
    $transaction = Transaction::find($item->transaction->id);
    expect($productExpectedOld->stock)->toEqual($productExpectedNew->stock);
});

test("Product stock changed when store use stock opname feature", function () {
    $item = TransactionItem::factory()
        ->for(Transaction::factory()->create())
        ->for(
            Product::factory()
                ->state(["use_stock_opname" => true])
                ->for(Store::factory()->create())
                ->create()
        )
        ->make();
    $productExpectedOld = Product::first();

    $instance = new CreateTransactionItem();
    $instance->execute($item->toArray(), $item->transaction);

    $productExpectedNew = Product::first();
    $transaction = Transaction::find($item->transaction->id);
    expect($productExpectedOld->stock)->not->toEqual($productExpectedNew->stock);
});
